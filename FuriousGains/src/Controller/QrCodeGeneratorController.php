<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Label\Font\NotoSans;

class QrCodeGeneratorController extends AbstractController
{
    #[Route('/qr-codes', name: 'app_qr_codes')]
    public function index(): Response
    {
        // Récupérer le montant et le statut de la table "commande"
        $entityManager = $this->getDoctrine()->getManager();
        $commande = $entityManager->getRepository(Commande::class)->findOneBy([]); // Modifier les critères de recherche selon vos besoins

        // Vérifier si une commande a été trouvée
        if ($commande) {
            $montant_total = $commande->getMontantPaiement();
            $statut_commande  = $commande->getStatutLivraison();
        } else {
            // Gérer le cas où aucune commande n'est trouvée
            $montant_total = 'Montant inconnu';
            $statut_commande  = 'Statut inconnu';
        }

        // Le reste du code reste inchangé

        $qrCodes = [];
        $qrCodes['montant'] = $montant_total; // Ajouter le montant à l'array $qrCodes
        $qrCodes['statut'] = $statut_commande; // Ajouter le statut à l'array $qrCodes

        return $this->render('qr_code_generator/index.html.twig', $qrCodes);
    }

}