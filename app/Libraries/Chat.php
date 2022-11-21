<?php

namespace App\Libraries;

use App\Models\Message;
use App\Models\UserModel;
use Psr\Http\Message\RequestInterface;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\MessageComponentInterface;

class Chat implements MessageComponentInterface {
    /** @var ConnectionInterface[] $clients */
    protected $clients;

    /** @var Message */
    private $writer;

    public function __construct() {
        $this->clients = [];
        $this->writer = new Message();
    }

    public function onOpen(ConnectionInterface $conn) {
        /**
         * @var RequestInterface $httpRequest
         * @noinspection PhpUndefinedFieldInspection
         */
        $httpRequest = $conn->httpRequest;
        $query = $httpRequest->getUri()->getQuery();
        parse_str($query, $result);
        $user = (int)$result['user'];

        // Store the new connection to send messages to later
        $this->clients[$user] = $conn;
    }

    /**
     * @param ConnectionInterface $conn
     * @param string $msg JSON {"recipient": 11, "message": "Hello"}
     * @return void
     */
    public function onMessage(ConnectionInterface $conn, $msg) {
        $packet = json_decode($msg, true);
        $sender_id = $packet['sender'];
        $recipient = (int)$packet['recipient'];
        $message = $packet['message'];

        $user_model = new UserModel();
        $sender = $user_model->getById($sender_id);

        $this->writer->write($sender_id, $recipient, $message);

        if (array_key_exists($recipient, $this->clients)) {
            $this->clients[$recipient]->send(json_encode([
                'type' => 'message',
                'from' => [
                    'id' => $sender->id,
                    'name' => $sender->name,
                    'profile' => $sender->profile_image,
                    'department' => $sender->department
                ],
                'message' => $message
            ]));
        }
    }

    public function onClose(ConnectionInterface $conn) {
        /**
         * @var RequestInterface $httpRequest
         * @noinspection PhpUndefinedFieldInspection
         */
        $httpRequest = $conn->httpRequest;
        $query = $httpRequest->getUri()->getQuery();
        parse_str($query, $result);

        $user = $result['user'];
        // The connection is closed, remove it, as we can no longer send it messages
        unset($this->clients[$user]);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $this->log("An error has occurred: {$e->getMessage()}");

        $conn->close();
    }

    private function log(string $message) {
        fwrite(STDOUT, '[' . date('Y-m-d H:i:s') . '] [info] ' . $message . PHP_EOL);
    }
}
