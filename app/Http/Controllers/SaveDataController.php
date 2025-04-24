<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataScrapper; // Adjust this to your actual model
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;


class SaveDataController extends Controller
{
    /**
     * Store the data in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(Request $request)
    {
        // Normalize headers to consistent JSON string
        $normalizedHeaders = collect(json_decode($request->headers_get, true))->map(function($header) {
            return strtolower(trim($header));
        })->sort()->values()->toJson();

        // Use updateOrCreate for clean and safe saving
        DataScrapper::updateOrCreate(
            [
                'userid' => $request->key,
                'url' => $request->url,
                'type' => $request->type,
                'table_id' => $request->tableId,
                'table_class' => $request->tableClass,
                'headers' => $normalizedHeaders,
            ],
            [
                'data' => $request->value,
            ]
        );

        return response()->json(['message' => 'Data saved successfully'], 201);
    }


    // public function store(Request $request)
    // {

    //     // Normalize headers to consistent JSON string
    //     $normalizedHeaders = collect(json_decode($request->headers, true))->map(function($header) {
    //         return strtolower(trim($header));
    //     })->sort()->values()->toJson();

    //     // Try to find matching record
    //     $data = DataScrapper::where('userid', $request->key)
    //         ->where('url', $request->url)
    //         ->where('type', $request->type)
    //         ->where('table_id', $request->tableId)
    //         ->where('table_class', $request->tableClass)
    //         ->where('headers', $normalizedHeaders)
    //         ->first();

    //     DataScrapper::updateOrCreate(
    //     [
    //         'userid' => $request->key,
    //         'url' => $request->url,
    //         'type' => $request->type,
    //         'table_id' => $request->tableId,
    //         'table_class' => $request->tableClass,
    //         'headers' => $normalizedHeaders,
    //     ],
    //     [
    //         'data' => $request->value,
    //     ]
    //     );

    //     $valueJson = $request->value;
    //     if ($data) {
    //         // If record exists, update it
    //         $data->data = $valueJson;
    //         $data->save();
    //     } else {
    //         // If no record exists, create a new one
    //         $data = new DataScrapper();
    //         $data->userid = $request->key;
    //         $data->data = $valueJson;
    //         $data->url = $request->url;
    //         $data->type = $request->type;
    //         $data->table_id = $request->tableId;
    //         $data->table_class = $request->tableClass;
    //         $data->headers = $request->headers;
    //         $data->save();
    //     }

    //     // Return a JSON response
    //     return response()->json(['message' => 'Data saved successfully'], 201);
    // }
    
    public function retrieve(Request $request)
    {
        // Try to find an existing record with the given userid and url
        $data = DataScrapper::where('userid', $request->userid)
                            ->where('url', $request->url)
                            ->where('type', $request->type)
                            ->first();

        if ($data) {
            // Return the data if found
            return response()->json(json_decode($data->data), 200);
        } else {
            // Return an error response if no data is found
            return response()->json(['message' => 'No data found'], 200);
        }
    }

    public function retrieve_alldata(Request $request)
    {
       $records = DataScrapper::where('userid', $request->userid)
                            ->where('url', $request->url)
                            ->get();

    if ($records->isEmpty()) {
        return response()->json(['message' => 'No data found'], 200);
    }

    $transformed = $records->map(function ($record) {
        return [
            'headers' => $record->headers,
            'data' => $record->data,
            'type' => $record->type,
            'table_id' => $record->table_id,
            'table_class' => $record->table_class
        ];
    });

    return response()->json($transformed, 200);
    }

    
   public function send_data(Request $request)
    {
        try {
            // Process Inventory Data
            $inventoryData = DataScrapper::where('userid', $request->userid)
                                         ->where('url', $request->url)
                                         ->where('type', 'inventory')
                                         ->first();

            if (!$inventoryData) {
                return response()->json(['message' => 'No inventory data found'], 200);
            }

            $decodedInventoryData = json_decode($inventoryData->data, true);
            $inventorySuccess = [];
            $inventoryFailed = [];

            foreach ($decodedInventoryData as $item) {
                try {
                    // Log the access token and request payload for debugging
                    Log::info('Access Token: ' . Session::get('access_token'));
                    Log::info('Inventory Request Payload: ' . json_encode($item));

                    // Send inventory data to the API
                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . Session::get('access_token'),
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ])->post(env('API_Smugglers_URL') . 'api/pos/smugglers/inventory/store-inventory/create/', $item);

                    if ($response->successful()) {
                        $inventorySuccess[] = $item;
                    } else {
                        $inventoryFailed[] = [
                            'item' => $item,
                            'error' => $response->body(),
                        ];
                    }
                } catch (\Exception $e) {
                    $inventoryFailed[] = [
                        'item' => $item,
                        'error' => $e->getMessage(),
                    ];
                    Log::error('Inventory Item POST failed: ' . $e->getMessage());
                }
            }

            // Process Order Data
            $orderData = DataScrapper::where('userid', $request->userid)
                                     ->where('url', $request->url)
                                     ->where('type', 'order')
                                     ->first();

            if (!$orderData) {
                return response()->json(['message' => 'No order data found'], 200);
            }

            $decodedOrderData = json_decode($orderData->data, true);
            $orderSuccess = [];
            $orderFailed = [];

            foreach ($decodedOrderData as $item) {
                try {
                    // Log the access token and request payload for debugging
                    Log::info('Access Token: ' . Session::get('access_token'));
                    Log::info('Order Request Payload: ' . json_encode($item));

                    // Send order data to the API
                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . Session::get('access_token'),
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ])->post(env('API_Smugglers_URL') . 'api/pos/smugglers/customer-orders/', $item);

                    if ($response->successful()) {
                        $orderSuccess[] = $item;
                    } else {
                        $orderFailed[] = [
                            'item' => $item,
                            'error' => $response->body(),
                        ];
                    }
                } catch (\Exception $e) {
                    $orderFailed[] = [
                        'item' => $item,
                        'error' => $e->getMessage(),
                    ];
                    Log::error('Order Item POST failed: ' . $e->getMessage());
                }
            }

            // Return combined result for both inventory and order data
            return response()->json([
                'message' => 'Data processed',
                'inventory' => [
                    'success_count' => count($inventorySuccess),
                    'failed_count' => count($inventoryFailed),
                    'failed_items' => $inventoryFailed,
                ],
                'orders' => [
                    'success_count' => count($orderSuccess),
                    'failed_count' => count($orderFailed),
                    'failed_items' => $orderFailed,
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Send Data Error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error', 'details' => $e->getMessage()], 500);
        }
    }



}
