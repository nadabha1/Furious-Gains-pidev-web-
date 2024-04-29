<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity]

class Categorie
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_categorie;

    #[ORM\Column(length:255)]
    #[Assert\NotBlank(message: "Le nom de la catégorie ne peut pas être vide")]
    #[Assert\Regex(
        pattern: '/^(?=.*[a-zA-Z]).+$/',
        message: "Le nom de la catégorie doit contenir au moins un caractère"
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le nom de la catégorie ne peut pas dépasser {{ limit }} caractères"
    )]
    private ?string $nom_categorie;

    #[ORM\Column(name:'descriptionC',length:255)]
    #[Assert\NotBlank(message: "La description ne peut pas être vide")]
    #[Assert\Regex(
        pattern: '/^(?=.*[a-zA-Z]).+$/',
        message: "La descriprition doit contenir au moins un caractère"
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: "La description ne peut pas dépasser {{ limit }} caractères"
    )]
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
    public function __toString(): string
    {
        return $this->getIdCategorie();
    }


}
