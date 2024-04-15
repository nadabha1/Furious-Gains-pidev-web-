<?php

namespace App\Controller;

use App\Entity\Classroom;

use App\Entity\Categorie ;
use App\Form\ProduitType;
use App\Repository\CategorieRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    #[Route('categorie', name : 'app_categorie' )]
    public function index() : Response
    {
        return $this->render("categorie/index.html.twig");
    }
    #[Route('/readCategorie', name : 'read_Categorie' )]
    public function read(CategorieRepository $rep) : Response
    { $categories = $rep->findAll();
        return $this->render("categorie/read.html.twig",
            ["categories"=>$categories]);
    }



    #[Route('/addcategorie', name: 'addc')]
    public function  add(ManagerRegistry $doctrine, Request  $request) : Response
    { $categorie = new Categorie() ;
        $form = $this->createForm(categorieType::class, $categorie);

        $form->handleRequest($request);
        if ($form->isSubmitted()  && $form->isValid())
        { $em = $doctrine->getManager();
            $em->persist($categorie);
            $em->flush();


            return $this->redirectToRoute('read_categorie');
        }
        return $this->renderForm("categorie/add.html.twig",
            ["f"=>$form]) ;
            

    }



    #[Route('about', name : 'app_about' )]
    public function about() : Response
    {
        return new Response('Welcome to About Page');
    }




}