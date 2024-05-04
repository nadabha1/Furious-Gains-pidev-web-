<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisType;
use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;


#[Route('/avis')]
class AvisController extends AbstractController
{
    #[Route('/', name: 'app_avis_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $avis = $entityManager
            ->getRepository(Avis::class)
            ->findAll();

        return $this->render('avis/index.html.twig', [
            'avis' => $avis,
        ]);
    }

    #[Route('/new', name: 'app_avis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $avi = new Avis();
        
        $form = $this->createForm(AvisType::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($avi);
            $this->addFlash('success', 'Avis ajouté avec succès.');
            $entityManager->flush();

            return $this->redirectToRoute('app_avis_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('avis/new.html.twig', [
            'avi' => $avi,
            'form' => $form,
        ]);
    }

    #[Route('/{idAvis}', name: 'app_avis_show', methods: ['GET'])]
    public function show(Avis $avi): Response
    {
        return $this->render('avis/show.html.twig', [
            'avi' => $avi,
        ]);
    }

    #[Route('/{idAvis}/edit', name: 'app_avis_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Avis $avi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AvisType::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_avis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('avis/edit.html.twig', [
            'avi' => $avi,
            'form' => $form,
        ]);
    }

    #[Route('/{idAvis}', name: 'app_avis_delete', methods: ['POST'])]
    public function delete(Request $request, Avis $avi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$avi->getIdAvis(), $request->request->get('_token'))) {
            $entityManager->remove($avi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_avis_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/telecharger_pdf/{idAvis}', name: 'telecharger_pdfavis')]
    public function telechargerPdf(Request $request,$idAvis): Response
    {
        // Récupérer le don depuis la base de données (ou tout autre moyen)
        $avi = $this->getDoctrine()->getRepository(avis::class)->find($idAvis);

        if (!$avi) {
            throw $this->createNotFoundException('avis non trouvé');
        }

        // Créer un nouveau document PDF avec Dompdf
        $dompdf = new Dompdf();
        $html = $this->renderView('avis/pdf.html.twig', [
            'avis' => $avi,
            'date_telechargement' => new \DateTime(),
        ]);
        $dompdf->loadHtml($html);

        // (Optionnel) Définir les options du PDF
        $dompdf->setPaper('A4', 'portrait');

        // Rendre le PDF
        $dompdf->render();

        // Générer le nom du fichier PDF
        $nomFichier = sprintf('Avis_%s.pdf', $avi->getIdAvis());

        // Créer une réponse avec le contenu du PDF
        $response = new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s" ', $nomFichier)
            ]
        );

        // Envoyer la réponse pour télécharger le fichier PDF
        return $response;
    }
#[Route('/search/avis', name: 'search_avis')]
    public function searchAvis(Request $request, AvisRepository $AvisRepository): Response
    {
        $keyword = $request->query->get('keyword');
        $avis = $AvisRepository->findByKeywordQuery($keyword);

        return $this->render('avis/index.html.twig', [
            'avis' => $avis,
        ]);
    }


    ///////////////////////admin
    ///


    #[Route('/adminavis/', name: 'app_avis_indexAdmin', methods: ['GET']),IsGranted('ROLE_ADMIN')]
    public function AdminList(EntityManagerInterface $entityManager,AvisRepository $avisRepository): Response
    {
        $avis = $avisRepository->findAll();

        return $this->render('avis/indexAdmin.html.twig', [
            'avis' => $avis,
        ]);
    }

    #[Route('/newAdmin', name: 'app_avis_newAdmin', methods: ['GET', 'POST']),IsGranted('ROLE_ADMIN')]
    public function newAdmin(Request $request, EntityManagerInterface $entityManager): Response
    {
        $avi = new Avis();

        $form = $this->createForm(AvisType::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($avi);
            $this->addFlash('success', 'Avis ajouté avec succès.');
            $entityManager->flush();

            return $this->redirectToRoute('app_avis_newAdmin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('avis/newAdmin.html.twig', [
            'avi' => $avi,
            'form' => $form,
        ]);
    }

    #[Route('/Admin/{idAvis}', name: 'app_avis_showAdmin', methods: ['GET']),IsGranted('ROLE_ADMIN')]
    public function showAdmin(Avis $avi): Response
    {
        return $this->render('avis/showAdmin.html.twig', [
            'avi' => $avi,
        ]);
    }

    #[Route('/Admin/{idAvis}/editAdmin', name: 'app_avis_editAdmin', methods: ['GET', 'POST']),IsGranted('ROLE_ADMIN')]
    public function editAdmin(Request $request, Avis $avi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AvisType::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_avis_indexAdmin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('avis/editAdmin.html.twig', [
            'avi' => $avi,
            'form' => $form,
        ]);
    }

    #[Route('/Admind/{idAvis}', name: 'app_avis_deleteAdmin', methods: ['POST']),IsGranted('ROLE_ADMIN')]
    public function deleteAdmin(Request $request, Avis $avi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$avi->getIdAvis(), $request->request->get('_token'))) {
            $entityManager->remove($avi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_avis_indexAdmin', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/search/avis', name: 'search_avisAdmin'),IsGranted('ROLE_ADMIN')]
    public function searchAvisAdmin(Request $request, AvisRepository $AvisRepository): Response
    {
        $keyword = $request->query->get('keyword');
        $avis = $AvisRepository->findByKeywordQuery($keyword);

        return $this->render('avis/indexAdmin.html.twig', [
            'avis' => $avis,
        ]);
    }
}
