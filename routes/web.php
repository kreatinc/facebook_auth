<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/redirect', function (Request $request) {  
    
    // get all headers from the request
    $url = $request->url;
    

    $query = http_build_query([
        'client_id' => '753862712981672',
        'redirect_uri' => 'http://auth.kreatinc.dev/callback',
        'response_type' => 'code',
        'scope' => '',
    ]);
    session(['subdomain' => $url]);
    return redirect('https://www.facebook.com/v16.0/dialog/oauth?'.$query);
});


Route::get('/callback', function(Request $request){
    return redirect('https://' . session('subdomain') . '/callback?' . $request->getQueryString());
});

Route::get('/', function () {
    return view('welcome');
});
