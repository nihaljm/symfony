<?php

namespace App\Service\Cart;

use App\Entity\Product;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class CartHandler
{
    private CartInterface $cartStrategy;

    public function __construct(
        #[Autowire(service: SessionCart::class)] CartInterface $cartStrategy
    ) {
        $this->cartStrategy = $cartStrategy;
    }

    public function getCart(): Cart
    {
        return $this->cartStrategy->getCart('user_cart');
    }

    public function addToCart(Product $product, int $quantity = 1): Cart
    {
        $cart = $this->getCart();
        $cartItem = new CartItem($product, $quantity);
        return $this->cartStrategy->add($cartItem, $cart);
    }

    public function removeFromCart(Product $product): Cart
    {
        $cart = $this->getCart();
        $cartItem = new CartItem($product);
        return $this->cartStrategy->remove($cartItem, $cart);
    }

    public function decreaseQuantity(Product $product): Cart
    {
        $cart = $this->getCart();
        $existingItem = null;
        foreach ($cart->getCartItems() as $item) {
            if ($item->getProduct()->getId() === $product->getId()) {
                $existingItem = $item;
                break;
            }
        }

        if ($existingItem) {
            if ($existingItem->getQuantity() > 1) {
                $cart = $this->cartStrategy->remove($existingItem, $cart);
                $newItem = new CartItem($product, $existingItem->getQuantity() - 1);
                return $this->cartStrategy->add($newItem, $cart);
            } else {
                return $this->cartStrategy->remove($existingItem, $cart);
            }
        }

        return $cart;
    }

    public function getCartItems(): array
    {
        return $this->getCart()->getCartItems();
    }

    public function getCartTotal(): float
    {
        return $this->getCart()->total();
    }

    public function clearCart(): void
    {
        $this->cartStrategy->clearCart('user_cart');
    }
}
