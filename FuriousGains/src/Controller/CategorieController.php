<?php

namespace App\Controller;

use App\Entity\Classroom;

use App\Entity\Categorie ;
use App\Form\ProduitType;
use App\Form\CategorieType;

use App\Repository\CategorieRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;

class CategorieController extends AbstractController
{




    #[Route('/addcategorie', name: 'addc')]
    public function add(NotifierInterface $notifier, ManagerRegistry $doctrine, Request $request): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($categorie);
            $em->flush();

            $this->addFlash('success', 'Catégorie ajoutée avec succès.');

            // Envoi de la notification du bureau
            $notification = new Notification('Catégorie ajoutée', ['browser']);
            $notifier->send($notification);

            return $this->redirectToRoute('addC');
        }

        return $this->renderForm("categorie/add.html.twig", ["f" => $form]);
    }

    #[Route('/categorie/{id}/supprimer', name: 'supprimer_categorie')]
    public function supprimerCategorie(Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        // Vérifie si le produit existe
        if (!$categorie) {
            throw $this->createNotFoundException('La categorie n\'existe pas');
        }
    
        // Supprime le produit
        $entityManager->remove($categorie);
        $entityManager->flush();
    
        // Redirige vers une page de confirmation ou une autre action
        return $this->redirectToRoute('readCategorie');
    }
    

    #[Route('about', name : 'app_about' )]
    public function about() : Response
    {
        return new Response('Welcome to About Page');
    }
    #[Route('/search/category', name: 'search_category')]
    public function searchCategories(Request $request, CategorieRepository $CategorieRepository): Response
    {
        $keyword = $request->query->get('keyword');
    
        // Recherche des produits correspondant au mot-clé
        $categories = $CategorieRepository->findByKeyword($keyword);
    
        return $this->render('categorie/readCategorie.html.twig', [
            'categories' => $categories,
        ]);
    }
    #[Route('/addcategorie', name: 'addC')]
    public function addCategorie(Request $request, EntityManagerInterface $entityManager): Response
    {
        $formData = ['nom_categorie' => '', 'descriptionC' => ''];
        $form = $this->createForm(CategorieType::class, $formData);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $categorie = new Categorie();
            $categorie->setNomCategorie($form->get('nom_categorie')->getData());
            $categorie->setDescriptionC($form->get('descriptionC')->getData());

            $entityManager->persist($categorie);
            $entityManager->flush();

           // return $this->redirectToRoute('read_produit');
        }
        
        return $this->renderForm("categorie/add.html.twig", [
            "f" => $form
        ]);
    }
    #[Route('/readC', name: 'readCategorie')]
    public function readB(CategorieRepository $rep): Response
    {
        $categories = $rep->findAll();
    
        return $this->render("Categorie/readCategorie.html.twig", [
            "categories" => $categories,
        ]);
    }
    #[Route('/categorie/{id}/modifier', name: 'modifier_categorie')]
    public function modifierCategorie(Categorie $categorie, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
            $this->addFlash('success', 'Catégorie modifiée avec succès.');
    
           return $this->redirectToRoute('readCategorie');
        }
    
        return $this->renderForm('categorie/modifier.html.twig', [
            'f' => $form,
        ]);
    }



}