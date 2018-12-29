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
    //内部注册
    $api->post('users/spider', 'UserController@storeSpider')
        ->name('api.users.storeSpider');
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
        /* 用户 */
        //获取当前登录用户信息
        $api->get('user', 'UserController@me')
            ->name('api.user.me');
        //编辑当前登录用户信息
        $api->patch('user', 'UserController@update')
            ->name('api.user.update');
        //获取某用户信息
        $api->get('user/{user}', 'UserController@show')
            ->name('api.user.show');
        //上传图片
        $api->post('images', 'ImagesController@store')
            ->name('api.images.store');
        //搜索用户
        $api->post('users/search', 'UserController@search')
            ->name('api.users.search');
        //搜索教师
        $api->post('users/faculty/search', 'UserController@searchFaculty')
            ->name('api.users.searchFaculty');
        /* 预约 */
        //创建预约
        $api->post('appointments', 'AppointmentsController@store')
            ->name('api.appointments.store');
        //查看预约
        $api->get('appointment/{appointment}', 'AppointmentsController@show')
            ->name('api.appointment.show');
        //查看用户的预约
        $api->get('user/{user}/appointments', 'AppointmentsController@userIndex')
            ->name('api.appointments.userIndex');
        //修改预约
        $api->patch('appointment/{appointment}', 'AppointmentsController@update')
            ->name('api.appointments.update');
        //修改预约状态
        $api->post('appointment/{appointment}/status', 'AppointmentsController@setStatus')
            ->name('api.appointments.status');
        /* 消息 */
        //创建消息
        $api->post('messages', 'MessagesController@store')
            ->name('api.messages.store');
        //查看消息
        $api->get('message/{message}', 'MessagesController@show')
            ->name('api.message.show');
        //标记消息为已读
        $api->get('message/{message}/read', 'MessagesController@setRead')
            ->name('api.messages.setRead');
        //发件箱
        $api->get('messages/sent', 'MessagesController@sentIndex')
            ->name('api.messages.sentIndex');
        //收件箱
        $api->get('messages/inbox', 'MessagesController@receiveIndex')
            ->name('api.messages.receiveIndex');

    });
});
