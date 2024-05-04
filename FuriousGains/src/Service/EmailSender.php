<?php

namespace App\Service;

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Transport\Dsn;

class EmailSender
{
    public function sendEmail(string $to , string $subject , string $text)
    {
        // Create a Transport object
        $transport = Transport::fromDsn('smtp://nadabha135@gmail.com:wkqe%20peai%20lmbx%20qdvx@smtp.gmail.com:587');

        // Create a Mailer object
        $mailer = new Mailer($transport);

        // Create an Email object
        $email = (new Email())
            ->from('nadabha135@gmail.com')
            ->to($to)
            ->subject($subject)
            ->html($text);

        // Send the email
        $mailer->send($email);
    }
}