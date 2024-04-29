<?php

namespace App\EventListener;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;

use App\Event\ProductLowQuantityEvent;
use App\Service\EmailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductLowQuantityListener implements EventSubscriberInterface
{
    private const LOW_QUANTITY_THRESHOLD = 5;

    private $emailService;
    private $entityManager;


    public function __construct(EmailService $emailService, EntityManagerInterface $entityManager)
    {
        $this->emailService = $emailService;
        $this->entityManager = $entityManager;

    }




    public static function getSubscribedEvents() : array
    {
        return [
            ProductLowQuantityEvent::class => 'onProductLowQuantity',
        ];
    }

    public function onProductLowQuantity(ProductLowQuantityEvent $event)
    {
        $productId = $event->getProductId();
        $productQuantity = $event->getProductQuantity();

        if ($productQuantity < self::LOW_QUANTITY_THRESHOLD) {
            $this->sendLowQuantityAlert($productId, $productQuantity);
        }
    }
    private function sendLowQuantityAlert(int $productId, int $productQuantity): void
    {
        // Récupérer l'entité Produit correspondant à l'ID du produit
        $product = $this->entityManager->getRepository(Produit::class)->find($productId);
    
        // Vérifier si le produit existe
        if ($product) {
            $productBrand = $product->getMarqueProduit();
            

            // Construire le contenu de l'e-mail avec les détails du produit
            $message = "
            <html>
            <head>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        line-height: 1.6;
                        color: #333333;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 20px;
                        background-color: #ffe6e6; /* Couleur de fond rouge légère */
                    }
                    th, td {
                        border: 1px solid #dddddd;
                        text-align: left;
                        padding: 8px;
                    }
                    th {
                        background-color: #f2f2f2;
                    }
                    .email-header {
                        background-color: #f5f5f5;
                        padding: 20px;
                        border-radius: 5px;
                    }
                    .email-footer {
                        margin-top: 20px;
                        font-size: 0.8em;
                        color: #888888;
                    }
                </style>
            </head>
            <body>
                <div class='email-header'>
                    <p>Bonjour,</p>
                    <p>Une quantité faible de l'article suivant a été détectée :</p>
                </div>
                <table>
                    <tr>
                        <th>ID du produit</th>
                        <td>{$productId}</td>
                    </tr>
                    <tr>
                        <th>Marque</th>
                        <td>{$productBrand}</td>
                    </tr>
                    <tr>
                        <th>Quantité</th>
                        <td>{$productQuantity}</td>
                    </tr>
                </table>
                <p>Nous vous recommandons de prendre des mesures pour reconstituer le stock de ce produit.</p>
                <div class='email-footer'>
                    <p>Cordialement,<br>L'équipe de votre boutique en ligne</p>
                </div>
            </body>
            </html>
        ";
        
        
        

            $subject = 'Alerte : Quantité faible de produit';
            $recipient = 'mahmoudkhm71@gmail.com'; // Adresse e-mail de l'administrateur
    
            try {
                // Envoyer l'e-mail avec le contenu personnalisé
                $this->emailService->sendEmail($recipient, $subject, $message);
                // Enregistrer l'envoi réussi dans les journaux
                // $logger->info('Low quantity alert email sent successfully');
            } catch (\Swift_TransportException $e) {
                // Enregistrer l'exception de transport SMTP
                var_dump('Failed to send low quantity alert email. SMTP transport exception: ' . $e->getMessage());
            } catch (\Exception $e) {
                // Enregistrer les autres exceptions
                var_dump('Failed to send low quantity alert email: ' . $e->getMessage());
            }
        } else {
            // Gérer le cas où le produit n'est pas trouvé
            // Par exemple, lever une exception ou enregistrer un message dans les journaux
        }
    }
    
    
}
