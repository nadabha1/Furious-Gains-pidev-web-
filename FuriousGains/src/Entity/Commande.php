<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_command;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "id_client", referencedColumnName: "id_user")]
    private ?User $id_client;

    #[ORM\Column(length:255)]
    #[Assert\NotBlank(message:"Il faut completer le champ statut commande")]
    private ?string $statutCommande;

    #[ORM\Column]
    #[Assert\Positive(message:"Le montant doit être positif")]
    private ?float $montantTotal;

    #[ORM\ManyToOne(targetEntity: Produit::class)]
    #[ORM\JoinColumn(name: "id_produit", referencedColumnName: "id_produit")]
    private ?Produit $idProduit;

    public function getIdCommand(): ?int
    {
        return $this->id_command;
    }

    public function setIdCommand(?int $id_command): void
    {
        $this->id_command = $id_command;
    }

    public function getIdClient(): ?User
    {
        return $this->id_client;
    }

    public function setIdClient(?User $id_client): void
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
        return $this->idProduit;
    }

    public function setIdProduit(?Produit $id_produit): void
    {
        $this->idProduit = $id_produit;
    }


}
