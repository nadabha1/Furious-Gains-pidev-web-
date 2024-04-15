<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_produit;

    #[ORM\Column(length: 255)]
    private ?string $marque_produit;

    #[ORM\Column]
    private ?int $quantite;

    #[ORM\Column(type: 'float')]
    private ?float $prix_produit;

    #[ORM\Column(length: 255)]
    private ?string $description;

    #[ORM\Column(length: 255)]
    private ?string $image_name;

    #[ORM\ManyToOne(targetEntity: Categorie::class)]
    #[ORM\JoinColumn(name: "id_categorie", referencedColumnName: "id_categorie")]
    private ?Categorie $id_categorie;
    

    public function getIdProduit(): ?int
    {
        return $this->id_produit;
    }

    public function setIdProduit(?int $id_produit): void
    {
        $this->id_produit = $id_produit;
    }

    public function getMarqueProduit(): ?string
    {
        return $this->marque_produit;
    }

    public function setMarqueProduit(?string $marque_produit): void
    {
        $this->marque_produit = $marque_produit;
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
        return $this->prix_produit;
    }

    public function setPrixProduit(?float $prix_produit): void
    {
        $this->prix_produit = $prix_produit;
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
        return $this->image_name;
    }

    public function setImageName(?string $image_name): void
    {
        $this->image_name = $image_name;
    }

    public function getIdCategorie(): ?Categorie
    {
        return $this->id_categorie;
    }

    public function setIdCategorie(?Categorie $id_categorie): void
    {
        $this->id_categorie = $id_categorie;
    }
}
