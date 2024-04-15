<?php

// src/Entity/Regime.php
namespace App\Entity;

use App\Repository\RegimeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegimeRepository::class)]
class Regime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $typeRegime = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $nomRegime = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $instruction = null;

    #[ORM\OneToMany(mappedBy: 'regime', targetEntity: Recette::class)]
    private Collection $recettes;

    public function __construct()
    {
        $this->recettes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeRegime(): ?string
    {
        return $this->typeRegime;
    }

    public function setTypeRegime(string $typeRegime): static
    {
        $this->typeRegime = $typeRegime;

        return $this;
    }

    public function getNomRegime(): ?string
    {
        return $this->nomRegime;
    }

    public function setNomRegime(string $nomRegime): static
    {
        $this->nomRegime = $nomRegime;

        return $this;
    }

    public function getInstruction(): ?string
    {
        return $this->instruction;
    }

    public function setInstruction(string $instruction): static
    {
        $this->instruction = $instruction;

        return $this;
    }

    public function getRecettes(): Collection
    {
        return $this->recettes;
    }

    public function addRecette(Recette $recette): static
    {
        if (!$this->recettes->contains($recette)) {
            $this->recettes[] = $recette;
            $recette->setRegime($this);
        }

        return $this;
    }

    public function removeRecette(Recette $recette): static
    {
        if ($this->recettes->removeElement($recette)) {
            // set the owning side to null (unless already changed)
            if ($recette->getRegime() === $this) {
                $recette->setRegime(null);
            }
        }

        return $this;
    }
}
