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
        'client_id' => '606658787946765',
        'redirect_uri' => 'https://auth.kreatinc.dev/callback',
        'response_type' => 'code',
        'scope' => 'public_profile,email',
    ]);
    session(['subdomain' => $url]);
    return redirect('https://www.facebook.com/v16.0/dialog/oauth?'.$query);
});


Route::get('/callback', function(Request $request){
    $code = $request->code;

    // requesting access_token
    $query = http_build_query([
        'client_id' => '606658787946765',
        'redirect_uri' => 'https://auth.kreatinc.dev/callback',
        'client_secret' => 'f36bc6d4ba09ba7ed6fe37ae44baa88b',
        'code' => $code,
    ]);
    
    $url = "https://graph.facebook.com/v16.0/oauth/access_token?" . $query;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $head = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $data = ['body'=>$head, 'httpCode'=>$httpCode];
    $data = json_decode($data['body'], true);
    $access_token = $data['access_token'];
    // return $data;
    // get name and email 

    $url = "https://graph.facebook.com/me?fields=name,email&access_token=" . $access_token;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $head = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $data = ['body'=>$head, 'httpCode'=>$httpCode];
    $data = json_decode($data['body'], true);

    $name = $data['name'] ?? '';
    $email = $data['email'] ?? '';
    
    return redirect("https://".session('subdomain')."/callback?name=$name&email=$email" );


});

Route::get('/', function () {
    return view('welcome');
});
