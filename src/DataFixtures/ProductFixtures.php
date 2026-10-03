<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\DataFixtures\CategoryFixtures;
use App\Entity\Category;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * Fixtures are run by abc order. This could cause trouble. The best way is to use this function.
     * getDependencies() tells Doctrine "run CategoryFixtures before ProductFixtures". That way, we
     * will already have the needed category object. 
     *
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        /**
         * We created category objects in CategoryFixtures, with references. Now, we are getting
         * these category object into this ProductFixtures class, by using those references.
         * We need these concrete category objects to create relationships in the seeding.
         */
        $category1 = $this->getReference('category1', Category::class);
        $category2 = $this->getReference('category2', Category::class);
        $category3 = $this->getReference('category3', Category::class);

        /**
         * Creating the product objects, related to given category objects...
         */
        $product1 = new Product();
        $product1->setName('Product 1');
        $product1->setDescription('Description for Product 1');
        $product1->setSize(10);
        $product1->setIsAvailable(true);
        $product1->addCategory($category1)
            ->addCategory($category2);
        $manager->persist($product1);

        $product2 = new Product();
        $product2->setName('Product 2');
        $product2->setDescription('Description for Product 2');
        $product2->setSize(20);
        $product2->setIsAvailable(false);
        $product2->addCategory($category1);
        $manager->persist($product2);

        $product3 = new Product();
        $product3->setName('Product 3');
        $product3->setDescription('Description for Product 3');
        $product3->setSize(30);
        $product3->setIsAvailable(true);
        $product3->addCategory($category3);
        $manager->persist($product3);

        $manager->flush();
    }
}
