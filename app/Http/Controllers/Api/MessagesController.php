<?php

namespace App\Http\Controllers\Api;

use Dingo\Api\Routing\Helpers;
use App\Models\Message;
use App\Http\Requests\Api\MessageRequest;
use App\Transformers\MessageTransformer;
use App\Repositories\MessageRepository;

class MessagesController extends Controller {
    protected $message;

    public function __construct(MessageRepository $message)
    {
        $this->message = $message;
    }

    public function show(Message $message) {
        return $this->response->item($message, new MessageTransformer());
    }

    public function store(MessageRequest $request)
    {
        if ($request->has('reply_src_id')) {
            $message = Message::create([
                'sender_id'    => $this->auth->user()->sid,
                'receiver_id'  => $request->receiver_id,//这里是SID
                'content'      => $request->content,
                'reply_src_id' => $request->reply_src_id,
            ]);
            $reply_src = Message::find($request->reply_src_id);
            $reply_src->addReply($message);
        } else {
            $message = Message::create([
                'sender_id'    => $this->auth->user()->sid,
                'receiver_id'  => $request->receiver_id,
                'content'      => $request->content,
            ]);
        }
        return $this->response->item($message, new MessageTransformer())->setStatusCode(201);
    }

    public function sentIndex() {
        $user = $this->auth->user();

        $messages = $user->messages_to_other()->where('reply_src_id', null)->orderBy('created_at','desc')->paginate(20);

        return $this->response->paginator($messages, new MessageTransformer());
    }

    public function receiveIndex() {
        $user = $this->auth->user();

        $messages = $user->messages_to_me()->where('reply_src_id', null)->orderBy('created_at','desc')->paginate(20);

        return $this->response->paginator($messages, new MessageTransformer());
    }

    public function setRead(Message $message) {
        $message->setRead();
        return $this->response->noContent()->setStatusCode(200);
    }
}
