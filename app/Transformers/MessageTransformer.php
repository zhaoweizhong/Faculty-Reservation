<?php

namespace App\Transformers;

use App\Models\Message;
use League\Fractal\TransformerAbstract;

class MessageTransformer extends TransformerAbstract
{
    public function transform(Message $message)
    {
        return [
            'id' =>$message->id,
            'sender_id'    => $message->sender_id,
            'receiver_id'  => $message->receiver_id,
            'content'      => $message->content,
            'reply_src_id' => $message->reply_src_id,
            'reply_id'     => $message->reply_id,
            'created_at'   => $message->created_at->toDateTimeString(),
            'updated_at'   => $message->updated_at->toDateTimeString(),
        ];
    }
}