<?php

namespace App\Controller\Api;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
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

    #[Route('/api/products/{id}', methods: ['PATCH'], name: 'app_api_product_update')]
    public function update(
        Product $product,
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager,
    ): JsonResponse
    {
        $serializer->deserialize(
            $request->getContent(),
            Product::class,
            'json',
            ['object_to_populate' => $product]
        );

        $errors = $validator->validate($product);

        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $entityManager->flush();

        return $this->json(
            $product,
            Response::HTTP_OK
        );
    }

    #[Route('/api/products/{id}', methods: ['DELETE'], name: 'app_api_product_delete')]
    public function delete(
        Product $product,
        EntityManagerInterface $entityManager,
    ): JsonResponse
    {
        $entityManager->remove($product);
        $entityManager->flush();

        return $this->json(
            null,
            Response::HTTP_NO_CONTENT
        );
    }
}
