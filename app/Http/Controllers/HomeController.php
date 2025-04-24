<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    

    public function index()
    {
         if (!Session::has('refresh_token')) {
            return redirect()->route('login')->with('error', 'You need to log in first.');
        }

        return view('home');
    }

     public function download()
    {
        return view('download');
    }

   

}
