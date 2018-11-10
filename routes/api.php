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
    'namespace'  => 'App\Http\Controllers\Api',
    'middleware' => ['serializer:array', 'bindings']
], function($api) {
    /* 主要API */
    $api->get('', function () {
        return response()->json([
            'message'     => 'Welcome to SUSTC Faculty Search & Appointment System API.',
            'status_code' => 200
        ])->setStatusCode(200);
    })->name('api.home.show');
    //用户注册
    $api->post('users', 'UserController@store')
        ->name('api.users.store');
    //用户登录
    $api->post('authorizations', 'AuthorizationsController@store')
        ->name('api.authorizations.store');
    //刷新token
    $api->put('authorizations/current', 'AuthorizationsController@update')
        ->name('api.authorizations.update');
    //删除token
    $api->delete('authorizations/current', 'AuthorizationsController@destroy')
        ->name('api.authorizations.destroy');
    //需要登录验证
    $api->group(['middleware' => 'api.auth'], function($api) {
        //获取当前登录用户信息
        $api->get('user', 'UserController@me')
            ->name('api.user.show');
        //编辑当前登录用户信息
        $api->patch('user', 'UserController@update')
            ->name('api.user.update');
        //上传图片
        $api->post('images', 'ImagesController@store')
            ->name('api.images.store');
    });
});
