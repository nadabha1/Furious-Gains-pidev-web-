<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Form\RecetteType;
use App\Repository\RecetteRepository;
use App\Service\RatingService;
use App\Service\SmsSender;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/recette')]
class RecetteController extends AbstractController
{
    private RatingService $ratingService;
    private $smsSender;

    public function __construct(RatingService $ratingService,SmsSender $smsSender)
    {
        $this->ratingService = $ratingService;
        $this->smsSender = $smsSender;

    }

    #[Route('/read', name: 'app_recette_index', methods: ['GET'])]
    public function index(RecetteRepository $recetteRepository): Response
    {
        $recettes = $recetteRepository->findAll();

        return $this->render('recette/index.html.twig', [
            'recettes' => $recettes,
            'ratingService' => $this->ratingService, // Pass the RatingService instance to the template
        ]);
    }
    #[Route('/download-pdf', name: 'app_recette_download_pdf', methods: ['GET'])]
public function downloadPDF(RecetteRepository $recetteRepository): Response
{
    // Retrieve all recipes from the database
    $recettes = $recetteRepository->findAll();

    // Render recipes as HTML
    $html = $this->renderView('recette/pdf_template.html.twig', [
        'recettes' => $recettes,
    ]);

    // Create PDF
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);

    // (Optional) Set options
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $dompdf->setOptions($options);

    $dompdf->render();

    // Generate PDF file and send as response
    $pdfOutput = $dompdf->output();
    $response = new Response($pdfOutput);
    $response->headers->set('Content-Type', 'application/pdf');
    $response->headers->set('Content-Disposition', 'attachment; filename="recettes.pdf"');

    return $response;
}


    #[Route('/new', name: 'app_recette_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $recette = new Recette();
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($recette);
            $entityManager->flush();
            $this->smsSender->sendSms('+21650545219', 'y nada ija y benti');

            return $this->redirectToRoute('app_recette_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recette/new.html.twig', [
            'recette' => $recette,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_recette_show', methods: ['GET'])]
    public function show(Recette $recette): Response
    {
        $averageRating = $this->ratingService->calculateAverageRating($recette);

        return $this->render('recette/show.html.twig', [
            'recette' => $recette,
            'ratingService' => $this->ratingService,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recette_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recette $recette, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_recette_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recette/edit.html.twig', [
            'recette' => $recette,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_recette_delete', methods: ['POST'])]
    public function delete(Request $request, Recette $recette, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recette->getId(), $request->request->get('_token'))) {
            $entityManager->remove($recette);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recette_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/rate', name: 'app_recette_rate', methods: ['POST'])]
    public function rate(Request $request, Recette $recette, RatingService $ratingService): Response
    {
        $ratingValue = (int)$request->request->get('rating');

        $ratingService->addRating($recette, $ratingValue);

        // Redirect back to the recipe page after rating
        return $this->redirectToRoute('app_recette_show', ['id' => $recette->getId()]);
    }
}
