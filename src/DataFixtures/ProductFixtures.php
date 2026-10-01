<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product1 = new Product();
        $product1->setName('Product 1');
        $product1->setDescription('Description for Product 1');
        $product1->setSize(10);
        $product1->setIsAvailable(true);
        $manager->persist($product1);

        $product2 = new Product();
        $product2->setName('Product 2');
        $product2->setDescription('Description for Product 2');
        $product2->setSize(20);
        $product2->setIsAvailable(false);
        $manager->persist($product2);

        $product3 = new Product();
        $product3->setName('Product 3');
        $product3->setDescription('Description for Product 3');
        $product3->setSize(30);
        $product3->setIsAvailable(true);
        $manager->persist($product3);

        $manager->flush();
    }
}