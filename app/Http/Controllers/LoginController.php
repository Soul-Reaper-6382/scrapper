<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Session;


class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Session::has('refresh_token')) {
        return redirect()->route('home');
        }

        return view('login'); // Ensure you have a login view
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);

        $client = new Client();
        
        try {
            $apiUrlLogin = env('API_Smugglers_URL') . 'api/authentication/public/auth/token/';
            $apiToken = env('API_Smugglers_Authorization');
            $response = $client->post($apiUrlLogin, [
                 'headers' => [
                        'Authorization' => 'Bearer ' . $apiToken, // Ensure Bearer token format if required
                        'Accept' => 'application/json'
                    ],
                    'json' => [
                        'username' => $request->username,
                        'password' => $request->password,
                    ]   
            ]);

            $data = json_decode($response->getBody(), true);
            // dd($data);
            // Store the token in the session
            // Store access and refresh tokens
            Session::put('access_token', $data['results']['access']);
            Session::put('refresh_token', $data['results']['refresh']);

            // Store user and store IDs
            Session::put('user_id', $data['results']['id']);
            Session::put('store_id', $data['results']['store']['id']);

            // Optional: Store user role and name
            Session::put('role', $data['results']['role']['name']);
            Session::put('user_name', $data['results']['name']);
            
            return redirect()->route('home'); // Change to your intended route
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return redirect()->back()->with('error', 'Invalid credentials.');
        }
    }

    public function logout()
    {
        Session::forget([
        'access_token',
        'refresh_token',
        'user_id',
        'store_id',
        'role',
        'user_name']);
        return redirect()->route('login.form');
    }
}
