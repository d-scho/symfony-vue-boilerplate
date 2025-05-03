<?php

namespace SymfonyVueBoilerplateBackend\Authentication\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyVueBoilerplateBackend\Authentication\Entity\User;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User(
            Uuid::uuid4()->toString(),
            'admin',
            '',
            'Administrator',
            [
                'ROLE_ADMIN',
                'ROLE_USER',
            ],
        );

        $user->setPassword($this->passwordHasher->hashPassword($user, 'admin-pw'));

        $manager->persist($user);
        $manager->flush();
    }
}
