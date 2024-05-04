<?php
// src/Controller/RatingController.php

namespace App\Controller;

use App\Entity\Recette;
use App\Service\RatingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RatingController extends AbstractController
{
    private $ratingService;

    public function __construct(RatingService $ratingService)
    {
        $this->ratingService = $ratingService;
    }

    #[Route('/rating/add', name: 'rating_add', methods: ['POST'])]
    public function addRating(Request $request): Response
    {
        $recetteId = $request->request->get('recetteId');
        $ratingValue = $request->request->get('rating');

        // Fetch the Recette entity
        $entityManager = $this->getDoctrine()->getManager();
        $recette = $entityManager->getRepository(Recette::class)->find($recetteId);

        if (!$recette) {
            return new JsonResponse(['error' => 'Recipe not found'], Response::HTTP_NOT_FOUND);
        }

        // Add rating to the recipe
        $this->ratingService->addRating($recette, $ratingValue);

        // Calculate and return the new average rating
        $averageRating = $this->ratingService->calculateAverageRating($recette);

        return new JsonResponse(['averageRating' => $averageRating], Response::HTTP_OK);
    }
}
