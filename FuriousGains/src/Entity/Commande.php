<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_command;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "id_client", referencedColumnName: "id_user")]
    private  $id_client;

    #[ORM\Column(length:255)]

    private ?string $statutCommande;

    #[ORM\Column]
    private ?float $montantTotal;

    #[ORM\ManyToOne(targetEntity: Produit::class)]
    #[ORM\JoinColumn(name: "id_produit", referencedColumnName: "id_produit")]
    private ?Produit $id_produit;

    public function getIdCommand(): ?int
    {
        return $this->id_command;
    }

    public function setIdCommand(?int $id_command): void
    {
        $this->id_command = $id_command;
    }

    public function getId_client(): ?User
    {
        return $this->id_client;
    }

    public function setId_client(?User $id_client): void
    {
        $this->id_client = $id_client;
    }

    public function getStatutCommande(): ?string
    {
        return $this->statutCommande;
    }

    public function setStatutCommande(?string $statutCommande): void
    {
        $this->statutCommande = $statutCommande;
    }

    public function getMontantTotal(): ?float
    {
        return $this->montantTotal;
    }

    public function setMontantTotal(?float $montantTotal): void
    {
        $this->montantTotal = $montantTotal;
    }

    public function getIdProduit(): ?Produit
    {
        return $this->id_produit;
    }

    public function setIdProduit(?Produit $id_produit): void
    {
        $this->id_produit = $id_produit;
    }


}
