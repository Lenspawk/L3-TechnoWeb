<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name : 'im22_products')]
#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 30)]
    private $label;

    #[ORM\Column(type: 'float')]
    private $price;

    #[ORM\Column(type: 'integer')]
    private $stock;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Panier::class)]
    private $shoppingBasket;

    public function __construct()
    {
        $this->shoppingBasket = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

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
            $shoppingBasket->setProduct($this);
        }

        return $this;
    }

    public function removeShoppingBasket(Panier $shoppingBasket): self
    {
        if ($this->shoppingBasket->removeElement($shoppingBasket)) {
            // set the owning side to null (unless already changed)
            if ($shoppingBasket->getProduct() === $this) {
                $shoppingBasket->setProduct(null);
            }
        }

        return $this;
    }
}
