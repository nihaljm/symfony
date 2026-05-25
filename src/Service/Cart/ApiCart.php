<?php

namespace App\Service\Cart;

class ApiCart implements CartInterface
{
    public function add(CartItem $item, Cart $cart): Cart
    {
        dd('ApiCart strategy: Adding product ' . $item->getProduct()->getName() . ' to remote API cart.');
    }

    public function remove(CartItem $item, Cart $cart): Cart
    {
        dd('ApiCart strategy: Removing product ' . $item->getProduct()->getName() . ' from remote API cart.');
    }

    public function getCart(string $identifier): Cart
    {
        dd('ApiCart strategy: Fetching cart ' . $identifier . ' from remote API cart.');
    }

    public function clearCart(string $identifier): void
    {
        dd('ApiCart strategy: Clearing remote API cart ' . $identifier);
    }
}
