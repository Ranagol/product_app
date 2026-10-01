<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    #[Route('/products', name: 'product_index', methods: ['GET'])]
    public function index(): Response
    {
        $products = [
            'Product 1',
            'Product 2',
            'Product 3',
        ];


        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }
}
