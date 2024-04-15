<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Form\Extension\Core\Type\EntityType;
class ProduitController extends AbstractController
{
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
            6
        );

        $categories = $rep->findAllCategories();

        return $this->render("produit/read.html.twig", [
            "produits" => $produits,
            "categories" => $categories,
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
    
    #[Route('/add-to-cart/{id}', name: 'add_to_cart')]
    public function addToCart(ProduitRepository $produitRepository, SessionInterface $session, $id): Response
    {
        // Récupérer le produit à partir de son ID
        $produit = $produitRepository->find($id);
        
        // Vérifier si le produit existe
        if (!$produit) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        // Récupérer le panier depuis la session ou initialiser un nouveau panier
        $cart = $session->get('cart', []);

        // Ajouter le produit au panier
        $cart[] = [
            'id' => $produit->getIdProduit(),
            'nom' => $produit->getMarqueProduit(), // Par exemple, récupérez d'autres informations sur le produit nécessaires
            'prix' => $produit->getPrixProduit(),
            // Ajoutez d'autres informations sur le produit au besoin
        ];

        // Mettre à jour le panier dans la session
        $session->set('cart', $cart);

        // Rediriger vers une page appropriée, par exemple la page de détails du produit
        return $this->redirectToRoute('details_produit', ['id' => $id]);
    }

    #[Route('/addproduit', name: 'addp')]
    public function add(Request $request): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();

            //return $this->redirectToRoute('read_produit');
        }
        return $this->renderForm("produit/add.html.twig", [
            "f" => $form
        ]);
    }

    #[Route('about', name: 'app_about')]
    public function about(): Response
    {
        return new Response('Welcome to About Page');
    }

    #[Route('/filterproduit/{id?}', name: 'filter_produit')]
    public function filterByCategory(ProduitRepository $produitRepository, $id): Response
    {
        $produits = $produitRepository->findBy(['id_categorie' => $id]);

        return $this->render('produit/read.html.twig', [
            'produits' => $produits,
        ]);
    }
}
