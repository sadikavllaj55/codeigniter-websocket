<?php

namespace App\Controllers;

use App\Libraries\Chat;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

class ChatServerController extends BaseController
{
    public function index() {
        if ($this->request->isCLI()) {
            try {
                $server = IoServer::factory(
                    new HttpServer(
                        new WsServer(
                            new Chat()
                        )
                    ),
                    env('app.websocketPort')
                );

                $server->run();
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        } else {
            die('not allowed');
        }
    }
}