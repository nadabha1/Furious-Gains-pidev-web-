<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_event = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_event = null;

    #[ORM\Column(length: 255)]
    private ?string $lieu_event = null;

    #[ORM\Column]
    private ?float $prix_event = null;

    #[ORM\Column]
    private ?int $nb_participation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_event = null;

    #[ORM\Column(length: 255)]
    private ?string $heure_event = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'evenement')]
    private Collection $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId_event(): ?int
    {
        return $this->id_event;
    }

    public function getNomEvent(): ?string
    {
        return $this->nom_event;
    }

    public function setNomEvent(string $nom_event): self
    {
        $this->nom_event = $nom_event;

        return $this;
    }

    public function getLieuEvent(): ?string
    {
        return $this->lieu_event;
    }

    public function setLieuEvent(string $lieu_event): self
    {
        $this->lieu_event = $lieu_event;

        return $this;
    }

    public function getPrixEvent(): ?float
    {
        return $this->prix_event;
    }

    public function setPrixEvent(float $prix_event): self
    {
        $this->prix_event = $prix_event;

        return $this;
    }

    public function getNbParticipation(): ?int
    {
        return $this->nb_participation;
    }

    public function setNbParticipation(int $nb_participation): self
    {
        $this->nb_participation = $nb_participation;

        return $this;
    }

    public function getDateEvent(): ?\DateTimeInterface
    {
        return $this->date_event;
    }

    public function setDateEvent(\DateTimeInterface $date_event): self
    {
        $this->date_event = $date_event;

        return $this;
    }

    public function getHeureEvent(): ?string
    {
        return $this->heure_event;
    }

    public function setHeureEvent(?string $heure_event): self
    {
        $this->heure_event = $heure_event;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }
}
