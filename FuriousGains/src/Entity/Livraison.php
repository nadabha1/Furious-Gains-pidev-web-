<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use FontLib\Table\Type\name;

#[ORM\Table(name:"Livraison")]
#[ORM\Entity]

class Livraison
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private ?int $idLivraison;

    #[ORM\Column( type: Types::DATE_MUTABLE)]


    private ?\DateTimeInterface $dateLivraison;

    #[ORM\Column(length:255)]

    private ?string $statutLivraison;

    #[ORM\Column(length:255)]
    #[Assert\NotBlank(message:"Il faut compléter le champ adresse livraison")]
    private ?string$adresseLivraison;

    #[ORM\Column]

    #[Assert\Positive(message:"Le montant doit être positif")]
    #[Assert\NotBlank(message:"Il faut compléter le champ montant paiement")]
    private ?float $montantPaiement;

    #[ORM\Column(length:255)]
    #[Assert\NotBlank(message:"Il faut completer le champ mode livraison")]
    private ?string $modeLivraison;
    #[ORM\ManyToOne(targetEntity: Commande::class)]
    #[ORM\JoinColumn(name: "id_commande", referencedColumnName: "id_command")]
    private ?Commande $commande;


    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "id_client", referencedColumnName: "id_user")]
    private ?User $id_client;

    public function setCommande(?Commande $commande): void
    {
        $this->commande = $commande;
    }
    public function getIdLivraison(): ?int
    {
        return $this->idLivraison;
    }

    public function setIdLivraison(?int $idLivraison): void
    {
        $this->idLivraison = $idLivraison;
    }

    public function getDateLivraison(): ?\DateTimeInterface
    {
        return $this->dateLivraison;
    }

    public function setDateLivraison(?\DateTimeInterface $dateLivraison): void
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
    public function getid_commande()
    {
        return $this->id_commande;
    }

    /**
     * @param mixed $id_commande
     */
    public function setid_commande($id_commande): void
    {
        $this->id_commande = $id_commande;
    }

    public function getIdClient(): ?User
    {
        return $this->id_client;
    }

    public function setIdClient(?User $id_client): void
    {
        $this->id_client = $id_client;
    }



    public function getCommande(): Commande
    {
        return $this->commande;
    }

}
