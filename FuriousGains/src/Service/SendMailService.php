<?php
namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

class SendMailService{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(
        string $from,
        string $to,
        string $subject,
         $resetToken

    ): void
    {
        $transport = Transport::fromDsn('smtp://nadabha135@gmail.com:wkqe%20peai%20lmbx%20qdvx@smtp.gmail.com:587');

        // Create a Mailer object
        $mailer = new Mailer($transport);
        //On crée le mail
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate("reset_password/email.html.twig")
            ->context(['resetToken' => $resetToken,
           ]
        );

        // On envoie le mail
        $mailer->send($email);
    }
    public function sendEmail(string $to, string $subject, string $text,$resetToken)
    {
        // Create a Transport object
        $transport = Transport::fromDsn('smtp://nadabha135@gmail.com:wkqe%20peai%20lmbx%20qdvx@smtp.gmail.com:587');

        // Create a Mailer object
        $mailer = new Mailer($transport);

        // Create an Email object
        $email = (new TemplatedEmail())
            ->from('nadabha135@gmail.com')
            ->to($to)
            ->htmlTemplate("reset_password/email.html.twig")
            ->context(['resetToken' => $resetToken,
            ]);

        // Send the email
        $mailer->send($email);
    }
}

