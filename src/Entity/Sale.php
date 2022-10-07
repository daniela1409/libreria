<?php

namespace App\Entity;

use App\Repository\SaleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: SaleRepository::class)]
class Sale
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'sales')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $user = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createAt = null;

    #[ORM\OneToMany(mappedBy: 'sale', targetEntity: ItemSale::class)]
    private Collection $itemSales;

    public function __construct()
    {
        $this->itemSales = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * @return Collection<int, ItemSale>
     */
    public function getItemSales(): Collection
    {
        return $this->itemSales;
    }

    public function addItemSale(ItemSale $itemSale): self
    {
        if (!$this->itemSales->contains($itemSale)) {
            $this->itemSales->add($itemSale);
            $itemSale->setSale($this);
        }

        return $this;
    }

    public function removeItemSale(ItemSale $itemSale): self
    {
        if ($this->itemSales->removeElement($itemSale)) {
            // set the owning side to null (unless already changed)
            if ($itemSale->getSale() === $this) {
                $itemSale->setSale(null);
            }
        }

        return $this;
    }
}
