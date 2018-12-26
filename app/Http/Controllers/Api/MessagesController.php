<?php

namespace App\Http\Controllers\Api;

use App\Models\Message;
use Illuminate\Http\Request;
use Auth;

class MessageController extends Controllers{
    protected $message;

    public function __construct(MessageRepository $message)
    {
        $this->message = $message;
    }

    public function store(MessageRequest $request)
    {
        $message=Message::create([
            'sender_id'=>$this->user()->id,
            'receiver_id'=>$request->receiver_id,
            'content'=>$request->content,

        ]);

        return $this->response->item($message,new MessageTransformer())
        ->setStatusCode(201);
    }

}
