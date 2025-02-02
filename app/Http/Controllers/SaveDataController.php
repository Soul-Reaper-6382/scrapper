<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataScrapper; // Adjust this to your actual model

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
        // Create a new instance of your model and save the data
         $data = DataScrapper::where('userid', $request->key)
                            ->where('url', $request->url)
                            ->first();
        $valueJson = $request->value;
        if ($data) {
            // If record exists, update it
            $data->data = $valueJson;
            $data->save();
        } else {
            // If no record exists, create a new one
            $data = new DataScrapper();
            $data->userid = $request->key;
            $data->data = $valueJson;
            $data->url = $request->url;
            $data->save();
        }

        // Return a JSON response
        return response()->json(['message' => 'Data saved successfully'], 201);
    }
    
    public function retrieve(Request $request)
    {
        // Try to find an existing record with the given userid and url
        $data = DataScrapper::where('userid', $request->userid)
                            ->where('url', $request->url)
                            ->first();

        if ($data) {
            // Return the data if found
            return response()->json(json_decode($data->data), 200);
        } else {
            // Return an error response if no data is found
            return response()->json(['message' => 'No data found'], 200);
        }
    }
}
