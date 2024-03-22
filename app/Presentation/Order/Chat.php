<?php

namespace App\Presentation\Order;

use App\Models\User;

class Chat
{
    public function toArray($chats)
    {
        $messages = [];
        foreach ($chats as $chat) {
            foreach ($chat->messages as $message) {
                $messages[] = [
                    'username' => optional(User::query()->find($message['user_id']))->username,
                    'from' => $message['from'],
                    'message' => $message['message'],
                    'created_at' => $message['created_at'],
                ];
            }
        }
        return $messages;
    }
}
