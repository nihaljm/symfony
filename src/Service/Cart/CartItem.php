<?php

namespace App\Service\Cart;

use App\Entity\Product;

class CartItem
{
    private int $id;
    private Product $product;
    private float $price;
    private int $quantity;

    public function __construct(Product $product, int $quantity = 1)
    {
        $this->id = $product->getId();
        $this->product = $product;
        $this->price = $product->getPrice();
        $this->quantity = $quantity;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getSubtotal(): float
    {
        return $this->price * $this->quantity;
    }
}
