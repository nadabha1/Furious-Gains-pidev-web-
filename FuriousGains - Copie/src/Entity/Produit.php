<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_Produit;

    #[ORM\Column(length:255)]

    private ?string$marqueProduit;
    #[ORM\Column]

    private ?int $quantite;

    #[ORM\Column]

    private ?float $prixProduit;

    #[ORM\Column(length:255)]

    private ?string $description;

    #[ORM\Column(length:255)]

    private ?string$imageName;

    #[ORM\ManyToOne(targetEntity: Categorie::class)]
    #[ORM\JoinColumn(name: "id_categorie", referencedColumnName: "id_categorie")]

    private ?Categorie $idCategorie;

    public function getIdProduit(): ?int
    {
        return $this->id_Produit;
    }

    public function setIdProduit(?int $id_Produit): void
    {
        $this->id_Produit = $id_Produit;
    }



    public function getMarqueProduit(): ?string
    {
        return $this->marqueProduit;
    }

    private $idProduit;

    public function setMarqueProduit(?string $marqueProduit): void
    {
        $this->marqueProduit = $marqueProduit;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): void
    {
        $this->quantite = $quantite;
    }

    public function getPrixProduit(): ?float
    {
        return $this->prixProduit;
    }

    public function setPrixProduit(?float $prixProduit): void
    {
        $this->prixProduit = $prixProduit;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getIdCategorie(): ?Categorie
    {
        return $this->idCategorie;
    }

    public function setIdCategorie(?Categorie $idCategorie): void
    {
        $this->idCategorie = $idCategorie;
    }


}