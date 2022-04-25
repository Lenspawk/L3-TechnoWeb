<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Table(name : 'im22_products')]
#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[UniqueEntity(
    fields: ['label', 'price'],
    message: 'Ce produit existe déjà avec ce prix',
    errorPath: 'label',
)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 30)]
    private string $label;

    #[ORM\Column(type: 'float')]
    private float $price;

    #[ORM\Column(type: 'integer')]
    private int $stock;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Panier::class, orphanRemoval: true)]
    private Collection $product;

    public function __construct()
    {
        $this->product = new ArrayCollection();
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
   public function getProduct(): Collection
   {
       return $this->product;
   }

   public function addProduct(Panier $product): self
   {
       if (!$this->product->contains($product)) {
           $this->product[] = $product;
           $product->setProduct($this);
       }

       return $this;
   }

   public function removeProduct(Panier $product): self
   {
       if ($this->product->removeElement($product)) {
           // set the owning side to null (unless already changed)
           if ($product->getProduct() === $this) {
               $product->setProduct(null);
           }
       }

       return $this;
   }
}
