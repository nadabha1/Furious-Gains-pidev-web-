<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idProduit;

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
    private ?Categorie $id_categorie;

    public function getIdProduit(): ?int
    {
        return $this->idProduit;
    }

    public function setIdProduit(?int $idProduit): void
    {
        $this->idProduit = $idProduit;
    }

    public function getMarqueProduit(): ?string
    {
        return $this->marqueProduit;
    }

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

    public function getId_categorie(): ?Categorie
    {
        return $this->id_categorie;
    }

    public function setIdCategorie(?Categorie $idCategorie): void
    {
        $this->id_categorie = $idCategorie;
    }


}
