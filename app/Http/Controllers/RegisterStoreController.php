<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mail;

class RegisterStoreController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('guest');
    }


//     public function check()
//     {
//         $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
//        $plan = Plan::find(1);
//    $subscription =  $stripe->subscriptions->create([
//   'customer' => 'cus_OXfsMPrDEWfZBk',
//   'items' => [
//     ['price' => 'price_1NjA68F5ufWtnIxqUvzyKaMH'],
//   ],
// ]);
//         dd($subscription->items->data[0]->id);
//     }
   public function registerstore(Request $request){
$this->validate(request(), [
            'name' => ['required'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:8'],
            'roles' => ['required'],
            ]);
        // user db
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'roles' => $request['roles']
        ]);
        $user->roles()->attach($request['roles']);

        auth()->login($user);
        session()->flash('message', 'Registration Successfully!');
        return redirect('/home');


   }

   
}
