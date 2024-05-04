<?php
namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


class EmailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

 
    public function sendEmail(string $recipient, string $subject, string $message)
    {
        $email = (new Email())
            ->from('nadabha135@gmail.com')
            ->to($recipient)
            ->subject($subject)
            ->html($message); // Utiliser html() pour définir le contenu au format HTML

        $this->mailer->send($email);
    }
}
