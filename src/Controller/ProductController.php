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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * There is one strange and funny thing with the Symfony controllers. Some controller method have
 * two methods (GET and POST), and can run twice. Example the edit(). Or delete...
 */
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

        // If the form is submitted and valid, we will handle the creation of the new product. Otherwise, we show the form for creating a new product.
        if ($form->isSubmitted() && $form->isValid()) {

            // Get the data from the form (the product instance)
            $product = $form->getData();

            // Save the product data into the database
            $entityManager->persist($product);
            $entityManager->flush();

            // Do a flash message to inform the user that the product was successfully created
            $this->addFlash('success', 'Product created successfully.');

            return $this->redirectToRoute('product_index');
        }

        // If the form is not submitted, it means this is a GET request, so we show the form for creating a new product.
        return $this->render('product/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edits a product. It runs twice. First, for the GET request, to show the form for the editing.
     * Secondly, to handle the actual POST request, for the actual editing in the DB.
     */
    #[Route('/products/{id}/edit', name: 'product_edit', methods: ['GET', 'POST'], requirements: ['id' => Requirement::DIGITS])]
    public function edit(Product $product, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        // If the form is submitted and valid, we update the product in the database. Otherwise, we show the form for editing.
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Product updated successfully.');

            return $this->redirectToRoute('product_index');
        }

        // If the form is not submitted, it means this is a GET request, so we show the form for editing.
        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a product. It runs twice. First, for the GET request to show the confirmation form.
     * and then for the DELETE request to actually delete the product.
     */
    #[Route('/products/{id}/delete', name: 'product_delete', methods: ['GET', 'DELETE'], requirements: ['id' => Requirement::DIGITS])]
    public function delete(Product $product, Request $request, EntityManagerInterface $entityManager): Response
    {
        /**
         * We create here a 'Are you sure you want to delete this product?' logic, with a delete
         * button. Instead of a popup, this is a whole page, with a dedicated form for deletion.
         */
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('product_delete', ['id' => $product->getId()]))

            // Ordinary HTML can't do DELETE requests. So we use $form to simulate it in the form
            ->setMethod('DELETE')

            // Add a submit button for deletion, that displays 'Yes' to confirm the action
            ->add('delete', SubmitType::class, [
                'label' => 'Yes',
            ])
            ->getForm()
            ->handleRequest($request);

        // This is the deleting logic, but it is skipped with the GET request, with the if()
        if ($form->isSubmitted()) {
            $entityManager->remove($product);
            $entityManager->flush();

            $this->addFlash('success', 'Product deleted successfully.');

            return $this->redirectToRoute('product_index');
        }

        // If the form is not submitted, it means this is a GET request, so we show the confirmation form.
        return $this->render('product/delete.html.twig', [
            'form' => $form,
        ]);
    }
}