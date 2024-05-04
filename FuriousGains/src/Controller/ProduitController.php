<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use App\Service\SmsSender;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Knp\Component\Pager\Pagination\SlidingPaginationInterface;
use App\Event\ProductLowQuantityEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use Symfony\Component\Form\Extension\Core\Type\EntityType;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Label\Font\NotoSans;
use App\Service\QrCodeGenerator;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Notifier\NotifierInterface;
use Exception;
use App\Service\EmailService;



class ProduitController extends AbstractController
{
    private $eventDispatcher;
    private $smsSender;



    public function __construct(EventDispatcherInterface $eventDispatcher,SmsSender $smsSender )
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->smsSender = $smsSender;


    }



    #[Route('/produit', name: 'app_produit')]
    public function index(): Response
    {
        return $this->render("produit/index.html.twig");
    }

    #[Route('/readproduit', name: 'read_produit')]
    public function read(ProduitRepository $rep, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $rep->createQueryBuilder('p')
            ->getQuery();

        $produits = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            9
        );

        $categories = $rep->findAllCategories();

        return $this->render("produit/read.html.twig", [
            "produits" => $produits,
            "categories" => $categories,
        ]);
    }
    #[Route('/readp', name: 'readBack'),IsGranted('ROLE_ADMIN')]
public function readB(ProduitRepository $rep): Response
{
    $produits = $rep->findAll();

    return $this->render("produit/readBack.html.twig", [
        "produits" => $produits,
    ]);
}

#[Route('/produit/{id}/supprimer', name: 'supprimer_produit'),IsGranted('ROLE_ADMIN')]
public function supprimerProduit(Produit $produit, EntityManagerInterface $entityManager): Response
{
    // Vérifie si le produit existe
    if (!$produit) {
        throw $this->createNotFoundException('Le produit n\'existe pas');
    }

    // Supprime le produit
    $entityManager->remove($produit);
    $entityManager->flush();

    // Redirige vers une page de confirmation ou une autre action
    return $this->redirectToRoute('readBack');
}

#[Route('/produit/{id}/modifier', name: 'modifier_produit')]
public function modifierProduit(Produit $produit, Request $request, EntityManagerInterface $entityManager): Response
{
    // Récupérer les données actuelles du produit
    $formData = [
        'marque_produit' => $produit->getMarqueProduit(),
        'quantite' => $produit->getQuantite(),
        'prix_produit' => $produit->getPrixProduit(),
        'description' => $produit->getDescription(),
        'id_categorie' => $produit->getIdCategorie(), // Assurez-vous de récupérer l'ID de la catégorie
       // 'image_name' => $produit->getImageName(),
    ];

    // Créez le formulaire en utilisant le ProduitType et les données du formulaire
    $form = $this->createForm(ProduitType::class, $formData);

    // Traitez le formulaire s'il est soumis
    $form->handleRequest($request);

    // Vérifie si le formulaire est soumis et valide
    if ($form->isSubmitted() && $form->isValid()) {
        // Mettre à jour les données du produit avec les données du formulaire
        $produit->setMarqueProduit($form->get('marque_produit')->getData());
        $produit->setQuantite($form->get('quantite')->getData());
        $produit->setPrixProduit($form->get('prix_produit')->getData());
        $produit->setDescription($form->get('description')->getData());
        $produit->setImageName($form->get('image_name')->getData());
        $category_id = $form->get('id_categorie')->getData();
        // Fetch the Categorie entity from the database based on the provided id
        $category = $entityManager->getRepository(Categorie::class)->find($category_id);
        // Set the fetched Categorie entity on the Produit entity
        $produit->setIdCategorie($category);
        

            // Mettez à jour le nom de l'image dans l'entité Produit
            $imageFile = $form->get('image_name')->getData();
            if ($imageFile) {
                // Donnez un nom unique au fichier
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
    
                // Déplacez le fichier dans le répertoire où vous souhaitez le stocker (img/)
                try {
                    $imageFile->move(
                        $this->getParameter('kernel.project_dir').'/public/img',
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer l'exception s'il y a un problème lors du déplacement du fichier
                }
    
                // Mettez à jour le nom de l'image dans l'entité Produit
                $produit->setImageName($newFilename);
            }
            if ($produit->getQuantite() < 5) {
                // Dispatch the low quantity event
                $event = new ProductLowQuantityEvent($produit->getIdProduit(), $produit->getQuantite());
                $this->eventDispatcher->dispatch($event);
            }
        
        // Persister les changements dans la base de données
        $entityManager->flush();

        // Rediriger vers une page de confirmation ou une autre action
        //return $this->redirectToRoute('modifier_produit', ['id' => $produit->getIdProduit()]);
    }

    // Afficher le formulaire de modification
    return $this->renderForm('produit/modifier.html.twig', [
        'form' => $form
    ]);
}




#[Route('/search/products', name: 'search_products'),IsGranted('ROLE_ADMIN')]
public function searchProducts(Request $request, ProduitRepository $produitRepository): Response
{
    $keyword = $request->query->get('keyword');
    $minPrice = $request->query->get('minPrice');
    $maxPrice = $request->query->get('maxPrice');

    // Recherche des produits correspondant au mot-clé
    $produitsQuery = $produitRepository->createQueryBuilder('p');

    if ($keyword) {
        $produitsQuery
            ->andWhere('p.marque_produit LIKE :keyword')
            ->orWhere('p.description LIKE :keyword')

            ->setParameter('keyword', '%' . $keyword . '%');
    }

    // Appliquer les filtres de prix
    if ($minPrice) {
        $produitsQuery
            ->andWhere('p.prix_produit >= :minPrice')
            ->setParameter('minPrice', $minPrice);
    }

    if ($maxPrice) {
        $produitsQuery
            ->andWhere('p.prix_produit <= :maxPrice')
            ->setParameter('maxPrice', $maxPrice);
    }

    // Exécutez la requête
    $produits = $produitsQuery->getQuery()->getResult();

    return $this->render('produit/readBack.html.twig', [
        'produits' => $produits,
    ]);
}
#[Route('/search/product', name: 'search_product')]
public function searchProduct(Request $request, ProduitRepository $produitRepository, PaginatorInterface $paginator): Response
{
    $keyword = $request->query->get('keyword');
    $minPrice = $request->query->get('minPrice');
    $maxPrice = $request->query->get('maxPrice');

    // Recherche des produits correspondant au mot-clé
    $produitsQuery = $produitRepository->createQueryBuilder('p');

    if ($keyword) {
        $produitsQuery
            ->andWhere('p.marque_produit LIKE :keyword')
            ->orWhere('p.description LIKE :keyword')

            ->setParameter('keyword', '%' . $keyword . '%');
    }

    // Appliquer les filtres de prix
    if ($minPrice) {
        $produitsQuery
            ->andWhere('p.prix_produit >= :minPrice')
            ->setParameter('minPrice', $minPrice);
    }

    if ($maxPrice) {
        $produitsQuery
            ->andWhere('p.prix_produit <= :maxPrice')
            ->setParameter('maxPrice', $maxPrice);
    }

   
   
   
    // Paginer les résultats de la recherche
    $produits = $paginator->paginate(
        $produitsQuery, // La requête
        $request->query->getInt('page', 1), // Le numéro de page
        9 // Limite par page
    );

    return $this->render('produit/read.html.twig', [
        'produits' => $produits,
    ]);
}
    #[Route('/produit/{id}', name: 'details_produit')]
    public function show(ProduitRepository $produitRepository, $id): Response
    {
        $produit = $produitRepository->find($id);
    
        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé');
        }
    
        return $this->render('produit/details.html.twig', [
            'produit' => $produit,
        ]);
    }
 
    #[Route('/prod/{id}', name: 'detailBack'),IsGranted('ROLE_ADMIN'),IsGranted('ROLE_ADMIN')]
    public function showBack(ProduitRepository $produitRepository,Request $request, $id): Response
    {
        $produit = $produitRepository->find($id);
        //$qrCodeImageUrl = $this->generateQrCode($produit);

    
        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé');
        }
        $qrContent = "🏷️ Marque: " . $produit->getMarqueProduit() . "\n"
           . "📝 Description: " . $produit->getDescription() . "\n"
           . "🔖 Référence produit: " . $produit->getIdProduit() . "\n"
           . "💰 Prix: " . $produit->getPrixProduit() . " DT" ."\n"
           ;


    


           //. 'Image: ' . $imageUrl;

        // Créer un objet QrCode avec le contenu spécifique du produit
        $qrCode = new QrCode($qrContent);

        // Utiliser un écrivain pour générer l'image du code QR au format PNG
        $writer = new PngWriter();
        $qrImage = $writer->write($qrCode)->getDataUri();
     

        return $this->render('produit/detailBack.html.twig', [
            'produit' => $produit,
            'qrImage' => $qrImage,
        ]);
    }

    #[Route('/add-to-cart/{id}', name: 'add_to_cart')]

    // Récupérer le produit à partir de son ID
    public function addToCart(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        // Récupérer le produit depuis la base de données
        $produit = $entityManager->getRepository(Produit::class)->find($id);

        // Vérifier si le produit existe
        if (!$produit) {
            throw $this->createNotFoundException('Le produit n\'existe pas');
        }

        // Récupérer la quantité saisie par le client depuis le formulaire
        $quantite = $request->request->get('quantity');

        // Vérifier si la quantité en stock est suffisante
        if ($quantite > $produit->getQuantite()) {
            throw $this->createNotFoundException('La quantité demandée est supérieure à la quantité en stock');
        }

        // Mettre à jour la quantité dans la base de données
        $nouvelleQuantite = $produit->getQuantite() - $quantite;
        $produit->setQuantite($nouvelleQuantite);

        // Enregistrer les modifications dans la base de données
        $entityManager->flush();
        if ($nouvelleQuantite < 5) {
            // Dispatch the low quantity event
            $event = new ProductLowQuantityEvent($produit->getIdProduit(), $nouvelleQuantite);
            $this->eventDispatcher->dispatch($event);
        }
        // Rediriger ou renvoyer une réponse appropriée


        // Rediriger vers une page appropriée, par exemple la page de détails du produit
        return $this->redirectToRoute('details_produit', ['id' => $id]);
    }
    #[Route('/addproduit', name: 'addp')]
    public function add(Request $request, EntityManagerInterface $entityManager, SessionInterface $session, ValidatorInterface $validator): Response
    {
        $formData=['marque_produit'=>'','quantite'=>'','prix_produit'=>'','description'=>'','id_categorie'=>'','image_name'=>''];
        $form = $this->createForm(ProduitType::class, $formData);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $produit = new Produit();
            $produit->setMarqueProduit($form->get('marque_produit')->getData());
            $produit->setQuantite($form->get('quantite')->getData());
            $produit->setPrixProduit($form->get('prix_produit')->getData());
            $produit->setDescription($form->get('description')->getData());
            $produit->setImageName($form->get('image_name')->getData());
            $category_id = $form->get('id_categorie')->getData();
            // Fetch the Categorie entity from the database based on the provided id
            $category = $entityManager->getRepository(Categorie::class)->find($category_id);
            // Set the fetched Categorie entity on the Produit entity
            $produit->setIdCategorie($category);
    
            // Récupérez le fichier téléchargé depuis le formulaire
            $imageFile = $form->get('image_name')->getData();
            if ($imageFile) {
                // Donnez un nom unique au fichier
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
    
                // Déplacez le fichier dans le répertoire où vous souhaitez le stocker (img/)
                try {
                    $imageFile->move(
                        $this->getParameter('kernel.project_dir').'/public/img',
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer l'exception s'il y a un problème lors du déplacement du fichier
                }
    
                // Mettez à jour le nom de l'image dans l'entité Produit
                $produit->setImageName($newFilename);
            }
          
            // Validate the produit entity
            $errors = $validator->validate($produit);
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    // Dump the error messages for debugging
                    dump($error->getMessage());
                }
                // You might want to handle the errors appropriately, such as displaying them in the form
            }
    
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $produit_added = true;
            $this->addFlash('success', 'Produit ajouté avec succès.');

            $em->flush();
            // Envoyer une notification de succès
            $this->smsSender->sendSms('+21650545219', 'Bonjour, nous avons le plaisir de vous informer qu\'un nouveau produit a été ajouté à notre catalogue. Venez découvrir notre dernière collection sur notre site web. Merci pour votre confiance.');

    
           // return $this->redirectToRoute('addp');
           return $this->redirectToRoute('addp', [
            'produit_added' => $produit_added,
        ]);

        }
    
      
        return $this->renderForm("produit/add.html.twig", [
            "f" => $form,
            'produit_added' => $produit_added ?? false, // Définir produit_added à false par défaut
        ]);
    }

  
    #[Route('/filterproduit/{id}', name: 'filter_produit')]

    public function filterByCategory(ProduitRepository $produitRepository, $id): Response
{
    $produits = $produitRepository->findBy(['id_categorie' => $id]);
    return $this->render("produit/readBack.html.twig", [
        "produits" => $produits,
        "id_categorie" => $id, // Ajout de la variable id_categorie
    ]);
}


    
    #[Route('about', name: 'app_about')]
    public function about(): Response
    {
        return new Response('Welcome to About Page');
    }

   

    
}
