<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]

class Commande
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idCommand;

    #[ORM\ManyToOne(targetEntity: "User")]
    private ?User $idClient;

    #[ORM\Column(length:255)]

    private ?string $statutCommande;

    #[ORM\Column]
    private ?float $montantTotal;

    #[ORM\ManyToOne(targetEntity: "Produit")]
    private ?Produit $idProduit;


}
