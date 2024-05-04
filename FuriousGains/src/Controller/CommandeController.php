<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\QrCode;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/commande')]
class CommandeController extends AbstractController
{
    #[Route('/', name: 'app_commande_index', methods: ['GET'])]

    public function index(EntityManagerInterface $entityManager,UserRepository $repo, AuthenticationUtils $authenticationUtils): Response
    {
        $lastUsername = $authenticationUtils->getLastUsername();
        $user=$repo->findOneByEmail($lastUsername);

        $commandes= $entityManager
            ->getRepository(Commande::class)
            ->findBy(['id_client'=>$user->getId_user()]);

        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    #[Route('/adminlist/', name: 'app_commande_list', methods: ['GET']),IsGranted('ROLE_ADMIN')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $commandes = $entityManager
            ->getRepository(Commande::class)
            ->findAll();

        return $this->render('commande/liste.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{idCommand}', name: 'app_commande_show', methods: ['GET'])]
    public function show(CommandeRepository $repo,$idCommand): Response
    { $commande =$repo->find($idCommand);
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{idCommand}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request,CommandeRepository $repo,$idCommand,  EntityManagerInterface $entityManager): Response
    {  $commande =$repo->find($idCommand);
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{idCommand}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request,CommandeRepository $repo,$idCommand, EntityManagerInterface $entityManager): Response
    { $commande =$repo->find($idCommand);
        if ($this->isCsrfTokenValid('delete'.$commande->getIdCommand(), $request->request->get('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/adminnew', name: 'app_commande_newAdmin', methods: ['GET', 'POST']),IsGranted('ROLE_ADMIN')]
    public function newAdmin(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/newAdmin.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/adminshow/{idCommand}', name: 'app_commande_showAdmin', methods: ['GET']),IsGranted('ROLE_ADMIN')]
    public function showAdmin(CommandeRepository $repo,$idCommand): Response
    { $commande =$repo->find($idCommand);
        return $this->render('commande/showAdmin.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{idCommand}/editadmin', name: 'app_commande_editAdmin', methods: ['GET', 'POST']),IsGranted('ROLE_ADMIN')]
    public function editAdmin(Request $request,CommandeRepository $repo,$idCommand,  EntityManagerInterface $entityManager): Response
    {  $commande =$repo->find($idCommand);
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commande_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/editAdmin.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/admindelite/{idCommand}', name: 'app_commande_deleteAdmin', methods: ['POST']),IsGranted('ROLE_ADMIN')]
    public function deleteAdmin(Request $request,CommandeRepository $repo,$idCommand, EntityManagerInterface $entityManager): Response
    { $commande =$repo->find($idCommand);
        if ($this->isCsrfTokenValid('delete'.$commande->getIdCommand(), $request->request->get('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commande_list', [], Response::HTTP_SEE_OTHER);
    }

}
