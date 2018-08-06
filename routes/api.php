<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('country', function(Request $request) {
    $geocode_service_url = 'https://ip2c.org/';

    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'])
    {
        $clientIpAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'])
    {
        $clientIpAddress = $_SERVER['REMOTE_ADDR'];
    }
    else
    {
        $clientIpAddress = '';
    }

    $url = $geocode_service_url.$clientIpAddress;
    $result = file_get_contents($url);
    switch ($result[0])
    {
        case '1':
            $data = explode(';', $result);
            return $data[1];
            break;
        default:
            return false;
    }
});
