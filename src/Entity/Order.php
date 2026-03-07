<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $OrderItem = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderItem(): ?string
    {
        return $this->OrderItem;
    }

    public function setOrderItem(string $OrderItem): static
    {
        $this->OrderItem = $OrderItem;

        return $this;
    }
}
