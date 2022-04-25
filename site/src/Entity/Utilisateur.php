<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Table(name : 'im22_users')]
#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[UniqueEntity(
    fields: ['login'],
    message: 'Ce login existe déjà',
    errorPath: 'login',
)]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 60,
        minMessage: 'Vous devez saisir au moins {{ limit }} caractères',
        maxMessage: 'Vous ne pouvez pas saisir plus de {{ limit }} caractères',
    )]
    #[ORM\Column(type: 'string', length: 60, unique: true)]
    private string $login;

    #[ORM\Column(type: 'string', length: 60)]
    private string $password;

    #[ORM\Column(type: 'string', length: 30)]
    #[Assert\Length(
        min: 3,
        max: 30,
        minMessage: 'Vous devez saisir au moins {{ limit }} caractères',
        maxMessage: 'Vous ne pouvez pas saisir plus de {{ limit }} caractères',
    )]
    private string $surname;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string', length: 30)]
    #[Assert\Length(
        min: 2,
        max: 30,
        minMessage: 'Vous devez saisir au moins {{ limit }} caractères',
        maxMessage: 'Vous ne pouvez pas saisir plus de {{ limit }} caractères',
    )]
    private string $firstname;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?DateTimeInterface $dateOfBirth;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $isAdmin = false;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $isSuperAdmin = false;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Panier::class, orphanRemoval: true)]
    private Collection $shoppingBasket;

    public function __construct()
    {
        $this->shoppingBasket = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getDateOfBirth(): ?DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(?DateTimeInterface $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getIsAdmin(): ?bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin): self
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    public function getIsSuperAdmin(): ?bool
    {
        return $this->isSuperAdmin;
    }

    public function setIsSuperAdmin(bool $isSuperAdmin): self
    {
        $this->isSuperAdmin = $isSuperAdmin;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->login;
    }

    public function getUsername(): string
    {
        return (string) $this->login;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials()
    {}

    /**
     * @return Collection<int, Panier>
     */
    public function getShoppingBasket(): Collection
    {
        return $this->shoppingBasket;
    }

    public function addShoppingBasket(Panier $shoppingBasket): self
    {
        if (!$this->shoppingBasket->contains($shoppingBasket)) {
            $this->shoppingBasket[] = $shoppingBasket;
            $shoppingBasket->setUser($this);
        }

        return $this;
    }

    public function removeShoppingBasket(Panier $shoppingBasket): self
    {
        if ($this->shoppingBasket->removeElement($shoppingBasket)) {
            // set the owning side to null (unless already changed)
            if ($shoppingBasket->getUser() === $this) {
                $shoppingBasket->setUser(null);
            }
        }

        return $this;
    }
}
