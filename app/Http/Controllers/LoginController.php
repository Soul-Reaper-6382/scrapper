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
        return view('login'); // Ensure you have a login view
    }

    public function login(Request $request)
    {
        // $request->validate([
        //     'email' => ['required', 'string'],
        //     'password' => ['required', 'string']
        // ]);

        // $client = new Client();
        
        // try {
        //     $apiUrlLogin = env('API_Smugglers_URL') . 'api/authentication/public/auth/token/';
        //     $apiToken = env('API_Smugglers_Authorization');
        //     $response = $client->post($apiUrlLogin, [
        //          'headers' => [
        //                 'Authorization' => 'Bearer ' . $apiToken, // Ensure Bearer token format if required
        //                 'Accept' => 'application/json'
        //             ],
        //             'json' => [
        //                 'username' => $request->email,
        //                 'password' => $request->password,
        //             ]   
        //     ]);

        //     $data = json_decode($response->getBody(), true);
        //     dd($data);
        //     // Store the token in the session
        //     Session::put('auth_token', $data['token']);
            
        //     return redirect()->route('home'); // Change to your intended route
        // } catch (\GuzzleHttp\Exception\RequestException $e) {
        //     dd($e);
        //     return redirect()->back()->with('error', 'Invalid credentials.');
        // }
        return redirect()->route('home'); // Change to your intended route

    }

    public function logout()
    {
        // Session::forget('auth_token');
        return redirect()->route('login.form');
    }
}
