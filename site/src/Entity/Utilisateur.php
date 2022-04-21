<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name : 'im22_users')]
#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 60)]
    private $login;

    #[ORM\Column(type: 'string', length: 60)]
    private $password;

    #[ORM\Column(type: 'string', length: 30)]
    private $surname;

    #[ORM\Column(type: 'string', length: 30)]
    private $firstname;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $dateOfBirth;

    #[ORM\Column(type: 'boolean', options: ['default'=>false])]
    private $isAdmin;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Panier::class, cascade: ['persist', 'remove'])]
    private $shoppingBasket;

    #[ORM\Column(type: 'boolean', options: ['default'=>false])]
    private $isSuperAdmin;

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

    public function getPassword(): ?string
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

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(?\DateTimeInterface $dateOfBirth): self
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

    public function getShoppingBasket(): ?Panier
    {
        return $this->shoppingBasket;
    }

    public function setShoppingBasket(Panier $shoppingBasket): self
    {
        // set the owning side of the relation if necessary
        if ($shoppingBasket->getUser() !== $this) {
            $shoppingBasket->setUser($this);
        }

        $this->shoppingBasket = $shoppingBasket;

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

}
