<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';
    protected $fillable = ['sender_id', 'receiver_id', 'content', 'reply_id', 'reply_src_id'];

    /**
    * 获得对应用户
    *
    * @return User
    */
    public function sender() {
        return $this->belongsTo('App\Models\User', 'sender_id');
    }

    public function receiver() {
        return $this->belongsTo('App\Models\User', 'receiver_id');
    }

    public function addReply(Message $reply) {
        $replies = json_decode($this->reply_id);
        array_push($replies, $reply->id);
        $this->reply_id = json_encode($replies);
    }

    public function setRead() {
        $this->read = true;
    }
}