<?php

namespace App\Models;

use CodeIgniter\Model;

class Message extends Model
{
    public function write($from, $to, $msg) {
        $sql = 'INSERT INTO messages (sender, recipient, message) VALUES (?, ?, ?)';

        $this->db->query($sql, [$from, $to, $msg]);
    }

    public function getConversation($from, $to, $limit = 100, $offset = 0) {
        $sql = "
            SELECT * 
            FROM messages 
            WHERE (sender=? AND recipient=?) OR (recipient=? AND sender=?)
            ORDER BY created DESC
            LIMIT $offset, $limit
        ";

        $query = $this->db->query($sql, [$from, $to, $from, $to]);

        return $query->getResult();
    }
}