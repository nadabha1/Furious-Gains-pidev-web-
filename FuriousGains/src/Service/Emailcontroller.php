<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Mime\Address;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// Import Classess
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;

class Emailcontroller
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    #[Route('/envoyeremail', name: 'envoyer_email')]
    public function envoyer_email(Request $request)
    {
        $from = 'nadabha135@gmail.com';
        $to = $request->request->get('to'); // Récupérer l'e-mail du champ "to"
        $subject = $request->request->get('subject'); // Récupérer le sujet du champ "subject"
        $message = $request->request->get('message');
        $smtpHost = 'smtp.gmail.com';
        $smtpPort = 587;
        $smtpUsername = 'nadabha135@gmail.com'; // Remplacez par votre adresse Gmail
        $smtpPassword = 'wkqe peai lmbx qdvx'; // Remplacez par votre mot de passe Gmail

        if ($to === null) {
            $to = 'nadabha2@gmail.com';
        }
        if ($subject === null) {
            $subject = 'Sujet par défaut';
        }
        $email = (new Email())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->text($message);
        $transport = (new \Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport($smtpHost, $smtpPort))
            ->setUsername($smtpUsername)
            ->setPassword($smtpPassword);
        // Envoyer l'e-mail
        $this->mailer->send($email);

        return $this->render('email.html.twig');
    }
}


