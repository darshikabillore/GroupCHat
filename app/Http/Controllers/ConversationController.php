<?php

namespace App\Http\Controllers;

use App\Conversation;
use App\Events\NewMessage;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function store()
    {
        try{
            $conversation = Conversation::create([
                'message' => request('message'),
                'group_id' => request('group_id'),
                'user_id' => auth()->user()->id,
            ]);

            broadcast(new NewMessage($conversation))->toOthers();

            return $conversation->load('user');
        } catch (\Exception $e) {
            \Log::error($e);  
            return $e;
            throw new \Exception("Unable to get users.");         
        }
    }
}
