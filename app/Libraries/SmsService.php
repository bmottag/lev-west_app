<?php

namespace App\Libraries;

use Twilio\Rest\Client;

class SmsService
{
    protected $client;
    protected $from;

    public function __construct()
    {
        $sid   = env('twilio.sid');
        $token = env('twilio.token');

        $this->from = env('twilio.from_number');

        $this->client = new Client($sid, $token);
    }

    public function send($to, $message)
    {
        return $this->client->messages->create(
            $to,
            [
                'from' => $this->from,
                'body' => $message
            ]
        );
    }

    public function sendBulk(array $numbers, string $message)
    {
        $results = [];

        foreach ($numbers as $to) {
            $results[] = $this->send($to, $message);
        }

        return $results;
    }
}