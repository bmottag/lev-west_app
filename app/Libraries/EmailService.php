<?php

namespace App\Libraries;

use Config\Services;

class EmailService
{
    protected $email;

    public function __construct()
    {
        $this->email = Services::email();
    }

    public function sendTemplate($to, $subject, $view, $data = [])
    {
        $data['appName'] = env('app.name');
        $data['companyName'] = env('app.companyName');

        $message = view($view, $data);

        $this->email->setFrom(env('email.fromEmail'), env('email.fromName'));
        $this->email->setTo($to);
        $this->email->setSubject($subject);
        $this->email->setMessage($message);
        $this->email->setMailType('html');

        if (! $this->email->send()) {
            return $this->email->printDebugger(['headers', 'subject', 'body']);
        }

        return true;
    }

    public function sendRaw($to, $subject, $message)
    {
        $this->email->setFrom(env('email.fromEmail'), env('email.fromName'));
        $this->email->setTo($to);
        $this->email->setSubject($subject);
        $this->email->setMessage($message);
        $this->email->setMailType('html');

        return $this->email->send();
    }
}