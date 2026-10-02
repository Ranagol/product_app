<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

final class ProductController extends AbstractController
{
    #[Route('/products', name: 'product_index', methods: ['GET'])]
    public function index(ProductRepository $repository): Response
    {
        $products = $repository->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/products/{id}', name: 'product_show', methods: ['GET'], requirements: ['id' => Requirement::DIGITS])]
    public function show(Product $product): Response
    {

        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/products/new', name: 'product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Create a new product instance
        $product = new Product();

        // Create the form for the new product, connect it to the product instance
        $form = $this->createForm(ProductType::class, $product);

        // We just give the $request to the form to handle it
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Get the data from the form (the product instance)
            $product = $form->getData();

            // Save the product data into the database
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
