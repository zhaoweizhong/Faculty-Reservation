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

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api',
    //'middleware' => ['serializer:array', 'bindings']
], function($api) {
    /* 主要API */
    $api->get('', function () {
        return response()->json([
            'message' => 'Welcome to SUSTC Faculty Search & Appointment System API.',
            'status_code' => 200
        ])->setStatusCode(200);
    })->name('api.home.show');
    //用户注册
    $api->post('users', 'UsersController@store')
    ->name('api.users.store');
});
