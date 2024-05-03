<?php

namespace App\Controller;

use Symfony\Component\Mime\Address;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// Import des classes nécessaires
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class EmailController extends AbstractController
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    #[Route('/envoyeremail', name: 'envoyer_email')]
    public function envoyer_email(Request $request)
    {
        $from = 'adams.chemengui@gmail.com';
        $to = $request->request->get('to');
        $subject = $request->request->get('subject');
        $message = $request->request->get('message');
        $smtpHost = 'smtp.gmail.com';
        $smtpPort = 587;
        $smtpUsername = 'adams.chemengui@gmail.com'; // Remplacez par votre adresse Gmail
        $smtpPassword = 'otpd ybir gpwb avzp'; // Remplacez par votre mot de passe Gmail

        // Créer l'e-mail
        $email = (new Email())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->text($message);

        try {
            // Configurer le transport SMTP
            $transport = (new \Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport($smtpHost, $smtpPort))
                ->setUsername($smtpUsername)
                ->setPassword($smtpPassword);

            // Envoyer l'e-mail
            $this->mailer->send($email);

            // Afficher un message de succès ou rediriger vers une autre page
            // ...
        } catch (TransportExceptionInterface $e) {
            // Gérer les erreurs liées au transport de messagerie
            // Afficher un message d'erreur ou enregistrer des informations de journal
            // ...
            $this->addFlash('error', 'Une erreur s\'est produite lors de l\'envoi de l\'e-mail. Veuillez réessayer plus tard.');
        } catch (\Exception $e) {
            // Gérer les autres exceptions inattendues
            // Afficher un message d'erreur générique ou enregistrer des informations de journal
            // ...
            $this->addFlash('error', 'Une erreur inattendue s\'est produite. Veuillez réessayer plus tard.');
        }

        return $this->render('Email.html.twig');
    }
}
