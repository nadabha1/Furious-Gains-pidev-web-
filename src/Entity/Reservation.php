<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_Res;

    #[ORM\Column(nullable: true)]
    private ?int $nb_place;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status_Res;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "id_client", referencedColumnName: "id_user")]
    private ?User $id_client;

    #[ORM\ManyToOne(targetEntity: Evenement::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(name: 'id_event', referencedColumnName: 'id_event')]
    private ?Evenement $evenement;

    public function getId_Res(): ?int
    {
        return $this->id_Res;
    }

    public function getNbPlace(): ?int
    {
        return $this->nb_place;
    }

    public function setNbPlace(?int $nb_place): self
    {
        $this->nb_place = $nb_place;

        return $this;
    }

    public function getStatusRes(): ?string
    {
        return $this->status_Res;
    }

    public function setStatusRes(?string $status_Res): self
    {
        $this->status_Res = $status_Res;

        return $this;
    }

    public function getIdClient(): ?User
    {
        return $this->id_client;
    }

    public function setIdClient(?User $id_client): self
    {
        $this->id_client = $id_client;

        return $this;
    }

    public function getEvenement(): ?Evenement
{
    return $this->evenement;
}

public function setEvenement(?Evenement $evenement): self
{
    $this->evenement = $evenement;

    return $this;
}

}
