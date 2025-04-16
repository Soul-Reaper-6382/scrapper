<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataScrapper; // Adjust this to your actual model
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

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
        // Try to find an existing record with the given userid and url
        $data = DataScrapper::where('userid', $request->userid)
                            ->where('url', $request->url)
                            ->first();

        if ($data) {
            // Return the data if found
            return response()->json($data, 200);
        } else {
            // Return an error response if no data is found
            return response()->json(['message' => 'No data found'], 200);
        }
    }

    
    public function send_data(Request $request)
    {
        $data = DataScrapper::where('userid', $request->userid)
                            ->where('url', $request->url)
                            ->where('type', 'inventory')
                            ->first();

        if (!$data) {
            return response()->json(['message' => 'No data found'], 200);
        }

        $decodedData = json_decode($data->data, true); // array of inventory items
        $success = [];
        $failed = [];

        foreach ($decodedData as $item) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => env('API_Smugglers_Authorization'),
                    'Accept' => 'application/json',
                ])->post(env('API_Smugglers_URL') . 'api/pos/smugglers/inventory/store-inventory/create/', $item);

                if ($response->successful()) {
                    $success[] = $item;
                } else {
                    $failed[] = [
                        'item' => $item,
                        'error' => $response->body(),
                    ];
                }
            } catch (\Exception $e) {
                $failed[] = [
                    'item' => $item,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return response()->json([
            'message' => 'Inventory data processed',
            'success_count' => count($success),
            'failed_count' => count($failed),
            'failed_items' => $failed
        ], 200);
    }

}
