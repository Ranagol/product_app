<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        /**
         * These category objects will be used to create references for/in the ProductFixtures.
         */
        $category1 = new Category();
        $category1->setName('Category 1');
        $manager->persist($category1);

        $category2 = new Category();
        $category2->setName('Category 2');
        $manager->persist($category2);

        $category3 = new Category();
        $category3->setName('Category 3');
        $manager->persist($category3);

        /**
         * We create references for the given category objects. These references will be reused in
         * ProductFixtures, where we need to referr to these category objects, because we want to
         * create relationships between products and category objects. The relationship connecting is
         * happening in ProductFixtures, with the use of these references.
         */
        $this->addReference('category1', $category1);
        $this->addReference('category2', $category2);
        $this->addReference('category3', $category3);

        $manager->flush();
    }
}