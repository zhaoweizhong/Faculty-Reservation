<?php

namespace App\Http\Controllers;

use App\Models\Message
use Illuminate\Http\Request;
use Auth;

class MessageController extends Controllers{
    protected $message;

    public function __construct(MessageRepository $message)
    {
        $this->message = $message;
    }

    public function store()
    {
        $sender_id = request('user');
        $receiver_id = user('api')->id;
        $message = $this->message->create([
            'receiver_id'=>request('user'),
            'sender_id'=> user('api')->id,
            'content'=>request('content'),
            //'reply_src_id'=>
        ]);

        //成功创建数据
        if($message){
            return response()->json(['status'=>true]);
        }
        return response()->json(['status'=>false]);
    }

}
