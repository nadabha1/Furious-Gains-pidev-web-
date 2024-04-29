<?php
// src/Service/RatingService.php

namespace App\Service;

use App\Entity\Rating;
use App\Entity\Recette;
use Doctrine\ORM\EntityManagerInterface;

class RatingService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addRating(Recette $recette, int $ratingValue)
    {
        $rating = new Rating();
        $rating->setRecetteId($recette);
        $rating->setRating($ratingValue);

        $this->entityManager->persist($rating);
        $this->entityManager->flush();
    }

    public function updateRating(Recette $recette, int $ratingValue)
    {
        // Implement logic to update the rating for the given recipe
        // You can fetch the existing rating entity and update its value
        // Alternatively, you can add logic to prevent multiple ratings from the same user
    }

    public function calculateAverageRating(Recette $recette): float
    {
        $ratings = $recette->getRatings();
        $totalRating = 0;
        $count = count($ratings);

        foreach ($ratings as $rating) {
            $totalRating += $rating->getRating();
        }

        return $count > 0 ? $totalRating / $count : 0;
    }
}
