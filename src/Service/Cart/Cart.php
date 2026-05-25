<?php

namespace App\Service\Cart;

class Cart
{
    private string $id;
    private \DateTimeImmutable $createdAt;
    private array $cartItems = [];

    public function __construct(string $id = '')
    {
        $this->id = $id ?: uniqid('cart_', true);
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getCartItems(): array
    {
        return $this->cartItems;
    }

    public function setCartItems(array $cartItems): void
    {
        $this->cartItems = $cartItems;
    }

    public function total(): float
    {
        $total = 0.0;
        foreach ($this->cartItems as $item) {
            $total += $item->getPrice() * $item->getQuantity();
        }
        return $total;
    }
}
