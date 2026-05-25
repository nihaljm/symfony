<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Repository\ProductRepository;
use App\Service\Cart\CartHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    private CartHandler $cartHandler;

    public function __construct(CartHandler $cartHandler)
    {
        $this->cartHandler = $cartHandler;
    }

    #[Route('/cart', name: 'app_cart')]
    public function index(): Response
    {
        return $this->render('cart/index.html.twig', [
            'items' => $this->cartHandler->getCartItems(),
            'total' => $this->cartHandler->getCartTotal(),
        ]);
    }

    #[Route('/cart/add/{id}', name: 'app_cart_add', methods: ['POST', 'GET'])]
    public function add(int $id, Request $request, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        $quantity = (int) $request->request->get('quantity', 1);
        if ($quantity <= 0) {
            $quantity = 1;
        }

        $this->cartHandler->addToCart($product, $quantity);

        $this->addFlash('success', sprintf('Product "%s" added to cart!', $product->getName()));

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/increase/{id}', name: 'app_cart_increase')]
    public function increase(int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        $this->cartHandler->addToCart($product, 1);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/decrease/{id}', name: 'app_cart_decrease')]
    public function decrease(int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        $this->cartHandler->decreaseQuantity($product);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove/{id}', name: 'app_cart_remove')]
    public function remove(int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        $this->cartHandler->removeFromCart($product);
        $this->addFlash('info', sprintf('Product "%s" removed from cart.', $product->getName()));

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/checkout', name: 'app_cart_checkout')]
    public function checkout(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'You must log in to checkout.');
            return $this->redirectToRoute('app_login');
        }

        $items = $this->cartHandler->getCartItems();
        if (empty($items)) {
            $this->addFlash('warning', 'Your cart is empty.');
            return $this->redirectToRoute('app_products');
        }

        $order = new Order();
        $order->setUser($user);
        $order->setStatus('Completed');

        foreach ($items as $item) {
            $orderItem = new OrderItem();
            $orderItem->setOrder($order);
            $orderItem->setProduct($item->getProduct());
            $orderItem->setQuantity($item->getQuantity());
            $orderItem->setPrice($item->getProduct()->getPrice());
            $entityManager->persist($orderItem);
            
            $order->addOrderItem($orderItem);
        }

        $entityManager->persist($order);
        $entityManager->flush();

        $this->cartHandler->clearCart();

        $this->addFlash('success', 'Panier enregistré');

        return $this->render('cart/checkout_success.html.twig', [
            'order' => $order,
        ]);
    }
}
