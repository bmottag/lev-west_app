<?php

namespace App\Libraries;

class Logger
{
    protected $userId;
    protected $type;
    protected $id;
    protected $token;
    protected $comment;

    public function user($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function type($type)
    {
        $this->type = $type;
        return $this;
    }

    public function id($id)
    {
        $this->id = $id;
        return $this;
    }

    public function token($token)
    {
        $this->token = $token;
        return $this;
    }

    public function comment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    public function log()
    {
        try {
            $db = \Config\Database::connect();

            $result = $db->table('logger')->insert([
                'created_by' => $this->userId,
                'type' => $this->type,
                'type_id' => $this->id,
                'token' => $this->token,
                'comment' => $this->comment,
                'created_on' => date('Y-m-d H:i:s')
            ]);

            if (!$result) {
                dd($db->error());
            }

            return $result;

        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }
}