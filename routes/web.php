<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterStoreController;
use App\Http\Controllers\ScraperController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware'=>['auth','roles:admin']],function(){ 

});
Route::get('/', function () {
    return redirect('/home');
});

Route::get('/ifram', function () {
    return view('iframe');
});

Route::get('/home', [HomeController::class,'index']);
Route::get('/scrape', [ScraperController::class,'index']);
Route::post('/registerstore', [RegisterStoreController::class, 'registerstore']);
Auth::routes([]);

Route::group(['middleware'=>['auth','roles:user']],function(){ 
});


// Route::controller(SearchController::class)->group(function(){

//     Route::get('demo-search', 'index');

//     Route::get('autocomplete', 'autocomplete')->name('autocomplete');

// });
