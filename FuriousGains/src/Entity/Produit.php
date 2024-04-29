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
    #[Assert\NotBlank(message: "La marque du produit ne doit pas être vide")]
    #[Assert\Regex(
        pattern: '/^(?=.*[a-zA-Z]).+$/',
        message: "La marque du produit doit contenir au moins un caractère alphabétique"
    )]
    #[Assert\Length(max: 255, maxMessage: "La marque du produit ne peut pas dépasser {{ limit }} caractères")]
    private ?string $marque_produit;
    #[ORM\Column]
    #[Assert\NotBlank(message: "La quantité ne doit pas être vide")]
    #[Assert\GreaterThan(value: 0, message: "La quantité doit être supérieure à zéro")]
    #[Assert\Regex(
        pattern: '/^[^a-zA-Z]*$/',
        message: "La quantité ne doit pas contenir de lettres de l'alphabet"
    )]
    #[Assert\Type(type: "numeric", message: "La quantité doit être un nombre entier positif")]
    private ?int $quantite;
    
    #[ORM\Column(type: 'float')]
    #[Assert\NotBlank(message: "Le prix du produit ne doit pas être vide")]
    #[Assert\GreaterThan(value: 0, message: "Le prix du produit doit être supérieur à zéro")]
    #[Assert\Regex(
        pattern: '/^[^a-zA-Z]*$/',
        message: "Le prix ne doit pas contenir de lettres de l'alphabet"
    )]
    #[Assert\Type(type: "numeric", message: "Le prix du produit doit être un nombre positif")]
    private ?float $prix_produit;
    

    #[ORM\Column(name:'description', length: 255)]
    #[Assert\NotBlank(message: "La description ne doit pas être vide !")]
    #[Assert\Regex(
        pattern: '/^(?=.*[a-zA-Z]).+$/',
        message: "La description doit contenir au moins un caractère alphabétique"
    )]
    #[Assert\Length(max: 255, maxMessage: "La description ne peut pas dépasser {{ limit }} caractères")]
    private ?string $description;

    //#[ORM\Column(name: "idC")] // Assurez-vous que la colonne correspond à votre schéma de base de données
    //private ?int $idC;

    #[ORM\ManyToOne(targetEntity: Categorie::class, cascade:['persist'])]
    #[ORM\JoinColumn(name: "id_categorie", referencedColumnName: "id_categorie",nullable: false)]
    private ?Categorie $id_categorie = null;
    //private ?Categorie $categorie;

    #[ORM\Column(length: 255)]
    private ?string $image_name;

    
    

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

    public function setIdCategorie(Categorie $id_categorie): static
    {
        $this->id_categorie = $id_categorie;
        return $this;
    }
    public function __toString(): string{
        return $this->getIdProduit();
    }
}
