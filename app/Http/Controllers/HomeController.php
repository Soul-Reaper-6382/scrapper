<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Carbon\Carbon;


class HomeController extends Controller
{
    

    public function index()
    {
        return view('home');
    }

   

}
