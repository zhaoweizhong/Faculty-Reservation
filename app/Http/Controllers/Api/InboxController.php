<?php

namespace App\Http\Controllers\Api;

use App\Models\Message;
use Auth;
use Illuminate\Http\Request;

class InboxController extends Controller{
    protected $message;

    public function __construct(MessageRepository $message)
    {
        $this->middleware('auth');
        $this->message = $message;
    }
    //私信列表
    public function index()
    {
        $user = $this->user();
        $messages = Message::where('receiver_id', $user->id)
            ->orWhere('sender_id', $user->id)
            ->with(['sender','receiver'])->latest()->get();
        return $messages;
        return view('inbox.index',['messages'=>$messages->groupBy('receiver_id')]);
    }
    //回复私信
    public function reply($reply_src_id)
    {
        $user = $this->user();
        $message = Message::where('id', $reply_src_id);
        $recerver_id = $message->sender_id === $user->id ? $message->receiver_id : $message->sender_id;
        $newMessage = $this->message->create([
            'sender'       => Auth::id(),
            'receiver_id'  => $recerver_id,
            'content'      => request('content'),
            'reply_src_id' => $message->id
        ]);
    }
}