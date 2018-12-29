<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Message extends Model
{
    use Searchable;
    
    protected $table = 'messages';
    protected $fillable = ['sender_id', 'receiver_id', 'content', 'reply_id', 'reply_src_id'];

    /**
    * 获得对应用户
    *
    * @return User
    */
    public function sender() {
        return $this->belongsTo('App\Models\User', 'sender_id', 'sid');
    }

    public function receiver() {
        return $this->belongsTo('App\Models\User', 'receiver_id', 'sid');
    }

    public function addReply(Message $reply) {
        $replies = json_decode($this->reply_id);
        if (is_null($replies)) {
            $replies = array($reply->id);
        } else {
            array_push($replies, $reply->id);
        }
        $this->reply_id = json_encode($replies);
        $this->save();
    }

    public function setRead() {
        $this->read = true;
        $this->save();
    }
}