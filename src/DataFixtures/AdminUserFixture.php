<?php

namespace App\DataFixtures;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminUserFixture extends Fixture implements FixtureGroupInterface
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setName('Admin');
        $admin->setEmail('admin@example.com');
        $admin->setIsVerified(true);
        $admin->setRoles(['ROLE_ADMIN']);

        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'randomString');
        $admin->setPassword($hashedPassword);

        $manager->persist($admin);
        $manager->flush();
    }

    /**
     * Allows to run only this fixture, and not all fixtures together.
     * bin/console doctrine:fixtures:load ---- this will run all fixtures
     * bin/console doctrine:fixtures:load --group=admin ---- this will run only the admin fixture
     */
    public static function getGroups(): array
    {
        return ['admin'];
    }
}
