<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\Commande;
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
        $annonce = $entityManager->getRepository(Annonces::class)->findOneBy([]); // Modifier les critères de recherche selon vos besoins

        // Vérifier si une commande a été trouvée
        if ($annonce) {
            $titreAnnonces = $annonce->getTitreAnnonces();
            $descriptionAnnonces  = $annonce->getDescriptionAnnonces();
        } else {
            // Gérer le cas où aucune commande n'est trouvée
            $titreAnnonces = 'titre inconnu';
            $descriptionAnnonces  = 'description inconnu';
        }

        // Le reste du code reste inchangé

        $qrCodes = [];
        $qrCodes['titre'] =$titreAnnonces; // Ajouter le montant à l'array $qrCodes
        $qrCodes['description'] = $descriptionAnnonces; // Ajouter le statut à l'array $qrCodes

        return $this->render('qr_code_generator/index.html.twig', [$qrCodes,'commande' => $annonce]);

    }

}
