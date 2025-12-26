<?php
namespace App\Controller;

use App\Form\CartItemType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product_show')]
    public function show(Request $request): Response
    {
        $form = $this->createForm(CartItemType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
        }

        return $this->render('product/show.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
