<?php

namespace App\Entity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;


#[ORM\Table(name:"User")]
#[ORM\Table(name:"User")]
#[ORM\Entity]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]


class User implements UserInterface,  PasswordAuthenticatedUserInterface
class User implements UserInterface,  PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private ?int $id_user;
    #[ORM\Column(type:"integer")]
    private ?int $id_user;

    #[ORM\Column]
    #[Assert\Length(min: 8,max: 8,maxMessage:" 8 caractères.",minMessage: "8carac")]
    #[Assert\Length(min: 8,max: 8,maxMessage:" 8 caractères.",minMessage: "8carac")]
    private ?int $cin;
    #[ORM\Column(length:255)]
    #[Assert\NotBlank(message: "Ce champ ne peut pas être vide.")]
    #[Assert\Length(min: 4,minMessage: 'Veuillez avoir au moins 4 caractères')]
    #[Assert\NotBlank(message: "Ce champ ne peut pas être vide.")]
    #[Assert\Length(min: 4,minMessage: 'Veuillez avoir au moins 4 caractères')]
    private ?string $nom;

    #[ORM\Column(length:255)]
    #[Assert\NotBlank(message: "Ce champ ne peut pas être vide.")]
    #[Assert\Length(min: 4,minMessage: 'Veuillez avoir au moins 4 caractères')]
    #[Assert\NotBlank(message: "Ce champ ne peut pas être vide.")]
    #[Assert\Length(min: 4,minMessage: 'Veuillez avoir au moins 4 caractères')]
    private ?string $prenom;

    #[ORM\Column(name: "dateuser", type: Types::DATE_MUTABLE, nullable: true, options: ["default" => "NULL"])]
    private  ?\DateTimeInterface $dateuser ;

    #[ORM\Column(name: "num_tel", type: "integer", nullable: false)]
    #[Assert\NotBlank(message: "Ce champ est obligatoire.")]
    #[Assert\NotBlank(message: "Ce champ est obligatoire.")]
    private ?int $numTel;

    #[ORM\Column(name: "adresse", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "Ce champ est obligatoire.")]
    #[Assert\NotBlank(message: "Ce champ est obligatoire.")]
    private ?string $adresse;
    #[Assert\NotBlank(message: "Ce champ est obligatoire.")]
    #[Assert\Email(message: "Veuillez entrer une adresse email valide.")]
    #[Assert\NotBlank(message: "Ce champ est obligatoire.")]
    #[Assert\Email(message: "Veuillez entrer une adresse email valide.")]
    #[ORM\Column(name: "email", type: "string", length: 255, nullable: false)]

    private ?string $email;

    #[ORM\Column(name: "password", type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "Ce champ ne peut pas être vide.")]
    #[Assert\NotNull(message:'Veuillez renseigner ce champ')]
    #[Assert\Length(min: 8,minMessage: 'Your password must be at least {{ limit }} characters long')]
    #[Assert\Regex(
        pattern: '/(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}/',
    #[Assert\NotBlank(message: "Ce champ ne peut pas être vide.")]
    #[Assert\NotNull(message:'Veuillez renseigner ce champ')]
    #[Assert\Length(min: 8,minMessage: 'Your password must be at least {{ limit }} characters long')]
    #[Assert\Regex(
        pattern: '/(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}/',

        message: 'Your password must contain at least one uppercase letter, one lowercase letter, and one number',
    )]
        message: 'Your password must contain at least one uppercase letter, one lowercase letter, and one number',
    )]
    private ?string $password;

    #[ORM\Column]
    private array $roles = [];
    #[ORM\Column]
    private array $roles = [];
    #[ORM\Column(name: "image", type: "string", length: 255, nullable: false)]

    private ?string $image;

    #[ORM\Column(name: "ban", type: "boolean", nullable: false)]
    private ?bool $ban ;

    #[ORM\Column]

    private ?int $id_Code_Promo;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $token = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): void
    {
        $this->token = $token;
    }
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $token = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    public function getId_user(): ?int
    public function getId_user(): ?int
    {
        return $this->id_user;
        return $this->id_user;
    }

    public function setId_user(?int $id_user): void
    public function setId_user(?int $id_user): void
    {
        $this->id_user = $id_user;
        $this->id_user = $id_user;
    }
    /*  #[ORM\ManyToOne(targetEntity: Codepromo::class,inversedBy: 'Users')]
      private ?Codepromo $codepromo = null;*/

 

    public function getCin(): ?int
    {
        return $this->cin;
    }

    public function setCin(?int $cin): void
    {
        $this->cin = $cin;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): void
    {
        $this->prenom = $prenom;
    }


    public function getNumTel(): ?int
    {
        return $this->numTel;
    }

    public function setNumTel(?int $numTel): void
    {
        $this->numTel = $numTel;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): void
    {
        $this->adresse = $adresse;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }


    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function getBan(): ?bool
    {
        return $this->ban;
    }

    public function setBan(?bool $ban): void
    {
        $this->ban = $ban;
    }



    public function getDateuser(): ?\DateTimeInterface
    {
        return $this->dateuser;
    }

    public function setDateuser(?\DateTimeInterface $dateuser): void
    {
        $this->dateuser = $dateuser;
    }

    public function getIdCodePromo(): ?int
    {
        return $this->id_Code_Promo;
    }

    public function setIdCodePromo(?int $id_Code_Promo): void
    {
        $this->id_Code_Promo = $id_Code_Promo;
    }



    public function isBan(): ?bool
    {
        return $this->ban;
    }
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }


    public function addRoles(string $roles): self
    {
        if (is_array($this->roles)) {
            if (!in_array($roles, $this->roles, true)) {
                $this->roles[] = $roles;
            }
        }

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }


    public function addRoles(string $roles): self
    {
        if (is_array($this->roles)) {
            if (!in_array($roles, $this->roles, true)) {
                $this->roles[] = $roles;
            }
        }

        return $this;
    }


    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUsername()
    {
        return $this->nom;    }

    public function __call(string $name, array $arguments)
    {
        // TODO: Implement @method string getUserIdentifier()
    }
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }
}
