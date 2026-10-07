<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['api-product-index'])]
    private ?int $id = null;

    #[ORM\Column(length: 128)]
    #[Assert\NotBlank(message: "Name should not be blank")]
    #[Groups(['api-product-index', 'api-product-detail'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['api-product-index', 'api-product-detail'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::SMALLINT, options: ["unsigned" => true])]
    #[Assert\NotBlank(message: "Size should not be blank")]
    #[Assert\Positive(message: "Size must be a positive number")]
    #[Assert\Type(type: 'integer', message: 'Size must be an integer')]
    #[Groups(['api-product-index', 'api-product-detail'])]
    private ?int $size = null;

    #[ORM\Column(options: ["default" => true])]
    #[Groups(['api-product-index', 'api-product-detail'])]
    private ?bool $is_available = true;

    /**
     * @var Collection<int, Category>
     * Categores have many-to-many relationship with products. In this property, we will have belonging
     * categories.
     */
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'products')]
    #[Groups(['api-product-index', 'api-product-detail'])]
    private Collection $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function isAvailable(): ?bool
    {
        return $this->is_available;
    }

    public function setIsAvailable(bool $is_available): static
    {
        $this->is_available = $is_available;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }
}