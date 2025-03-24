<?php


namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\AuditeurFactory;
use App\Factory\AuditFactory;
use App\Factory\OperationFactory;
use App\Factory\SiteFactory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture

{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    ){

    }

    public function load(ObjectManager $manager): void

    {
        $user = new User();
        $user->setEmail('admin@gmail.com')
            ->setPassword($this->hasher->hashPassword($user, 'admin'));

        $manager->persist($user);
        $manager->flush();

        AuditeurFactory::createMany(10);
        SiteFactory::createMany(10);
        OperationFactory::createMany(30);
        AuditFactory::createMany(50);

        $manager->flush();

    }

}