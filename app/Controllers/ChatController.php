<?php

namespace App\Controllers;

use App\Models\Message;
use App\Models\UserModel;

class ChatController extends BaseController
{
    public function index() {
        $model = new UserModel();

        $users = $model->getAll();

        foreach ($users as $key => $user) {
            if ($user->id == $this->auth->id) {
                unset($users[$key]);
            }
        }

        $socket = $this->getSocketUri();
        $fullscreen = true;

        return view('chat/index', compact('users', 'socket', 'fullscreen'));
    }

    public function conversation() {
        $user_id = $this->request->getGet('user');
        $model = new Message();
        $user_model = new UserModel();

        $user = $user_model->getById($user_id);
        $conversation = $model->getConversation($this->auth->id, $user_id);

        return $this->response->setJSON(compact('user', 'conversation'));
    }

    private function getSocketUri(): string {
        $secure = env('app.websocketSecure') ?? false;

        return ($secure ? 'wss' : 'ws') . '://' . env('app.websocketUri') . '/?user=' . $this->auth->id;
    }
}