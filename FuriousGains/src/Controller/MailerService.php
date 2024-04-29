<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MailerService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    #[Route('/email', name: 'email')]
    public function sendEmail(): Response
    {
        $email = (new Email())
            ->from('ribd1920@gmail.com')
            ->to('nefzisaidi.siwar@esprit.tn')
            ->subject('siadam')
            ->text('siademmich_normale');

        $this->mailer->send($email);
        return new Response('Email sent successfully!');
    }
}
