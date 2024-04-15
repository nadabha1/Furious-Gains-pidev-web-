<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]

class Avis
{
    #[ORM\Column(name: "id_avis", type: "integer", nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $idAvis;

    #[ORM\Column(name: "note", type: "integer", nullable: false)]
    private ?int $note;

#[ORM\ManyToOne(targetEntity: "Produit")]

    private ?Produit $idProduit;

#[ORM\ManyToOne(targetEntity: "User")]

    private ?User $idUser;

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

    public function getIdProduit(): ?Produit
    {
        return $this->idProduit;
    }

    public function setIdProduit(?Produit $idProduit): void
    {
        $this->idProduit = $idProduit;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): void
    {
        $this->idUser = $idUser;
    }


}
