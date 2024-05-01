<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]

class Categorie
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_categorie;

    #[ORM\Column(length:255)]

    private ?string $nom_categorie;

    #[ORM\Column(length:255)]

    private ?string $descriptionC;

    public function getIdCategorie(): ?int
    {
        return $this->id_categorie;
    }

    public function setIdCategorie(?int $id_categorie): void
    {
        $this->id_categorie = $id_categorie;
    }

    public function getNomCategorie(): ?string
    {
        return $this->nom_categorie;
    }

    public function setNomCategorie(?string $nom_categorie): void
    {
        $this->nom_categorie = $nom_categorie;
    }

    public function getDescriptionC(): ?string
    {
        return $this->descriptionC;
    }

    public function setDescriptionC(?string $descriptionC): void
    {
        $this->descriptionC = $descriptionC;
    }


}