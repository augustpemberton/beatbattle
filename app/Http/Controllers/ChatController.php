<?php

namespace App\Http\Controllers;

use App\Message;
use App\Http\Resources\MessageResource;
use Illuminate\Http\Request;
use App\Events\NewMessage;
use App\Http\Requests\SendMessageRequest;

class ChatController extends BaseController
{
    public function __construct() {
      $this->middleware('auth')->except('index');
    }

    public function index()
    {
      return MessageResource::collection(Message::with('user')->orderBy('created_at', 'desc')->take(20)->get()->reverse());
    }

    public function store(SendMessageRequest $request)
    {
      $message = $request->user()->messages()->create([
        'message' => $request->input('message')
      ]);

      broadcast(new NewMessage($message));

      return $this->sendEmptyResponse();
    }
}
