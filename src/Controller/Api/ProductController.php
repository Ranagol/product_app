<?php

namespace App\Controller\Api;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class ProductController extends AbstractController
{
    #[Route('/api/products', methods: ['GET'], name: 'app_api_products')]
    public function index(ProductRepository $productRepository): JsonResponse
    {
        return $this->json(
            $productRepository->findAll(),
            context: ['groups' => ['api-product-index']]
        );
    }

    #[Route('/api/products/{id}', methods: ['GET'], name: 'app_api_product_show')]
    public function show(
        ProductRepository $productRepository,
        Product $product,
        SerializerInterface $serializer
    ): JsonResponse
    {
        $jsonData = $serializer->serialize(
            $product,
            'json',
            context: ['groups' => ['api-product-detail']]
        );

        return new JsonResponse($jsonData, 200, [], true);
    }

    #[Route('/api/products', methods: ['POST'], name: 'app_api_product_create')]
    public function create(
        #[MapRequestPayload] Product $product,//this directly creates a Product from the request
        EntityManagerInterface $entityManager,//this will be used to save the new Product
    ): JsonResponse
    {
        $entityManager->persist($product);
        $entityManager->flush();

        return $this->json(
            $product,
            Response::HTTP_CREATED
        );
    }
}