<?php

namespace App\Services;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Transport\Dsn;

class EmailSender
{
    public function sendEmail(string $to , string $subject ,  $resetToken)
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
            ->text("dfghjkmù")
            ;

        // Send the email
        $mailer->send($email);
    }
    public function sendEmail2(string $to , string $subject ,  $resetToken)
    {
        // Create a Transport object
        $transport = Transport::fromDsn('smtp://nadabha135@gmail.com:wkqe%20peai%20lmbx%20qdvx@smtp.gmail.com:587');

        // Create a Mailer object
        $mailer = new Mailer($transport);

        // Create an Email object
        $email = (new TemplatedEmail())
            ->from(new Address('nadabha135@gmail.com', 'Nada'))
            ->to($to)
            ->subject('Your password reset request')
            ->htmlTemplate('reset_password/email.html.twig')
            ->context([
                'resetToken' => $resetToken,
            ])
        ;

        // Send the email
        $mailer->send($email);
    }
}