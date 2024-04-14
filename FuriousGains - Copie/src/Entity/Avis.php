<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]

class Avis
{
    #[ORM\Column(name: "id_avis", type: "integer", nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $idAvis;

    #[ORM\Column(name: "note", type: "integer", nullable: false)]
    #[Assert\Positive(message: "la note doit etre positive")]
    private ?int $note;

    #[ORM\ManyToOne(targetEntity: Produit::class)]
    #[ORM\JoinColumn(name: "id_produit", referencedColumnName: "id_produit")]
    #[Assert\NotBlank(message:" veuillez séléctionner un élément dans la liste")]

    private ?Produit $produit;
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "id_user", referencedColumnName: "id_user")]
    private ?User $user;

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): void
    {
        $this->produit = $produit;
    }

    public function getIdAvis(): ?int
    {
        return $this->idAvis;
    }

    public function setIdAvis(?int $idAvis): void
    {
        $this->idAvis = $idAvis;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): void
    {
        $this->note = $note;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }



}
