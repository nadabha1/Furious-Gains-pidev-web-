<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity]
class Annonces
{
    #[ORM\Column(name: "id_annonces", type: "integer", nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $idAnnonces;

    #[ORM\Column(name: "titre_annonces", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message:" must be not emty")]
    private ?string $titreAnnonces;

    #[ORM\Column(name: "description_annonces", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message:" must be not emty")]
    private ?string $descriptionAnnonces;

    #[ORM\Column(name: "imag", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message:" must be not emty")]
    private ?string $imag;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "id_user", referencedColumnName: "id_user")]
    private ?User $user;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }


    public function getIdAnnonces(): ?int
    {
        return $this->idAnnonces;
    }

    public function setIdAnnonces(?int $idAnnonces): void
    {
        $this->idAnnonces = $idAnnonces;
    }

    public function getTitreAnnonces(): ?string
    {
        return $this->titreAnnonces;
    }

    public function setTitreAnnonces(?string $titreAnnonces): void
    {
        $this->titreAnnonces = $titreAnnonces;
    }

    public function getDescriptionAnnonces(): ?string
    {
        return $this->descriptionAnnonces;
    }

    public function setDescriptionAnnonces(?string $descriptionAnnonces): void
    {
        $this->descriptionAnnonces = $descriptionAnnonces;
    }

    public function getImag(): ?string
    {
        return $this->imag;
    }

    public function setImag(?string $imag): void
    {
        $this->imag = $imag;
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
