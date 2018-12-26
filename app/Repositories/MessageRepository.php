<?php

namespace App\Repositories;

use App\Models\Message;

class MessageRepository{

    public function create(array $attributes) {
        return Message::create($attributes);
    }

    public function getAllMessages(){
        return Message::where('sender_id',user()->id)
            ->orWhere('receiver_id',user()->id)
            ->with(['sender'=>function($query) {
                return $query->select(['id','name']);
            },'receiver'=>function($query){
                return $query->select(['id','name']);
            }])->latest()->get();
    }

   
}