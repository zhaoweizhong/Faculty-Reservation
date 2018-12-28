<?php

namespace App\Transformers;

use App\Models\Message;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class MessageTransformer extends TransformerAbstract
{
    public function transform(Message $message)
    {
        return [
            'id'           =>$message->id,
            'sender_id'    => $message->sender_id,
            'receiver_id'  => $message->receiver_id,
            'content'      => $message->content,
            'read'         => $message->read,
            'reply_src_id' => $message->reply_src_id,
            'reply_id'     => $message->reply_id,
            'created_at'   => $message->created_at->toDateTimeString(),
            'updated_at'   => $message->updated_at->toDateTimeString(),
            'sender'       => User::where('sid', $message->sender_id)->first(),
            'receiver'     => User::where('sid', $message->receiver_id)->first(),
            'replies'      => Message::where('reply_src_id', $message->id)->get()->toArray(),
        ];
    }
}