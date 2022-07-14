<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * @ORM\Entity(repositoryClass=ProductsRepository::class)
 * 
 */
class Products
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     * @Assert\Length(min=3, minMessage="El nombre debe tener al menos 3 caracteres")
     * 
     */
    private $name;

    /**
     * @ORM\Column(type="datetime_immutable")
     * 
     */
    private $created_at;

    /**
     * 
     * @ORM\Column(type="integer")
     * @Assert\Type(type="integer", message="Solo valores numéricos")
     * @Assert\NotBlank(message="Este campo no puede estar vacio")
     */
    private $stock;

    /**
     * @ORM\OneToMany(targetEntity=StockHistoric::class, mappedBy="product")
     */
    private $stockHistorics;
	
    public function __construct()
    {
        $this->stockHistorics = new ArrayCollection();
        $this->setCreatedAt(new \DateTimeImmutable());

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?Categories
    {
        return $this->category;
    }

    public function setCategory(?Categories $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * @return Collection<int, StockHistoric>
     */
    public function getStockHistorics(): Collection
    {
        return $this->stockHistorics;
    }
    


}
