<?php

namespace App\Service\Cart;

use Symfony\Component\HttpFoundation\RequestStack;

class SessionCart implements CartInterface
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    private function getSession()
    {
        return $this->requestStack->getSession();
    }

    public function add(CartItem $item, Cart $cart): Cart
    {
        $items = $cart->getCartItems();

        foreach ($items as $existing) {
            if ($existing->getId() === $item->getId()) {
                $existing->setQuantity($existing->getQuantity() + $item->getQuantity());
                $cart->setCartItems($items);
                $this->getSession()->set($cart->getId(), $cart);
                return $cart;
            }
        }

        $items[] = $item;
        $cart->setCartItems($items);
        $this->getSession()->set($cart->getId(), $cart);

        return $cart;
    }

    public function remove(CartItem $item, Cart $cart): Cart
    {
        $items = array_filter(
            $cart->getCartItems(),
            fn(CartItem $existing) => $existing->getId() !== $item->getId()
        );

        $cart->setCartItems(array_values($items));
        $this->getSession()->set($cart->getId(), $cart);

        return $cart;
    }

    public function getCart(string $identifier): Cart
    {
        $cart = $this->getSession()->get($identifier);

        if (!$cart instanceof Cart) {
            $cart = new Cart($identifier);
            $this->getSession()->set($identifier, $cart);
        }

        return $cart;
    }

    public function clearCart(string $identifier): void
    {
        $this->getSession()->remove($identifier);
    }
}
