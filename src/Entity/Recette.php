<?php

// src/Entity/Recette.php
namespace App\Entity;

use App\Repository\RecetteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecetteRepository::class)]
class Recette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $nomRecette = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $ingredients = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $tempsPreparation = null;

    #[ORM\ManyToOne(targetEntity: Regime::class)]
    #[ORM\JoinColumn(name: 'id_regime', referencedColumnName: 'id')]
    private ?Regime $regime = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomRecette(): ?string
    {
        return $this->nomRecette;
    }

    public function setNomRecette(string $nomRecette): static
    {
        $this->nomRecette = $nomRecette;

        return $this;
    }

    public function getIngredients(): ?string
    {
        return $this->ingredients;
    }

    public function setIngredients(string $ingredients): static
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    public function getTempsPreparation(): ?string
    {
        return $this->tempsPreparation;
    }

    public function setTempsPreparation(string $tempsPreparation): static
    {
        $this->tempsPreparation = $tempsPreparation;

        return $this;
    }

    public function getRegime(): ?Regime
    {
        return $this->regime;
    }

    public function setRegime(?Regime $regime): static
    {
        $this->regime = $regime;

        return $this;
    }
}
