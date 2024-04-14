<?php

namespace App\Entity;

use Cassandra\Date;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]

class Livraison
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idLivraison;

    #[ORM\Column]

    private ?\DateTime $dateLivraison;

    #[ORM\Column(length:255)]

    private ?string $statutLivraison;

    #[ORM\Column(length:255)]

    private ?string$adresseLivraison;

    #[ORM\Column]

    private ?float $montantPaiement;

    #[ORM\Column(length:255)]

    private ?string $modeLivraison;
    #[ORM\ManyToOne(targetEntity: Commande::class,inversedBy: "livraisons")]


    private $idCommande;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $idClient;

    public function getIdLivraison(): ?int
    {
        return $this->idLivraison;
    }

    public function setIdLivraison(?int $idLivraison): void
    {
        $this->idLivraison = $idLivraison;
    }

    public function getDateLivraison(): ?\DateTime
    {
        return $this->dateLivraison;
    }

    public function setDateLivraison(?\DateTime $dateLivraison): void
    {
        $this->dateLivraison = $dateLivraison;
    }

    public function getStatutLivraison(): ?string
    {
        return $this->statutLivraison;
    }

    public function setStatutLivraison(?string $statutLivraison): void
    {
        $this->statutLivraison = $statutLivraison;
    }

    public function getAdresseLivraison(): ?string
    {
        return $this->adresseLivraison;
    }

    public function setAdresseLivraison(?string $adresseLivraison): void
    {
        $this->adresseLivraison = $adresseLivraison;
    }

    public function getMontantPaiement(): ?float
    {
        return $this->montantPaiement;
    }

    public function setMontantPaiement(?float $montantPaiement): void
    {
        $this->montantPaiement = $montantPaiement;
    }

    public function getModeLivraison(): ?string
    {
        return $this->modeLivraison;
    }

    public function setModeLivraison(?string $modeLivraison): void
    {
        $this->modeLivraison = $modeLivraison;
    }

    /**
     * @return mixed
     */
    public function getIdCommande()
    {
        return $this->idCommande;
    }

    /**
     * @param mixed $idCommande
     */
    public function setIdCommande($idCommande): void
    {
        $this->idCommande = $idCommande;
    }

    public function getIdClient(): ?User
    {
        return $this->idClient;
    }

    public function setIdClient(?User $idClient): void
    {
        $this->idClient = $idClient;
    }


}
