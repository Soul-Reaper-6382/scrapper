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
        if (Session::has('auth_token')) {
        return redirect()->route('home');
        }

        return view('login'); // Ensure you have a login view
    }

    public function checking()
    {
        
        return view('checking'); // Ensure you have a login view
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
            // Store the token in the session
            Session::put('auth_token', $data['results']['id']);
            
            return redirect()->route('home'); // Change to your intended route
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return redirect()->back()->with('error', 'Invalid credentials.');
        }
    }

    public function logout()
    {
        Session::forget('auth_token');
        return redirect()->route('login.form');
    }
}
