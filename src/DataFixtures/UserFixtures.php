<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    public const EMAIL = [
        'henry.quatre@hotmail.com',
        'fake.mail@orange.fr',
        'Jacquie.lilo@gmail.com',
        'jerome.dannfald@orange.fr',
        'julie.jolie.@gmail.com'
    ];

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $contributor = new User();
        $contributor->setEmail('henry.quatre@hotmail.com');
        $contributor->setRoles(['ROLE_CONTRIBUTOR']);

        $plaintextPassword = 'Azerty+1234';
        $hashedPassword = $this->passwordHasher->hashPassword(
            $contributor,
            $plaintextPassword
        );

        $contributor->setPassword($hashedPassword);
        $manager->persist($contributor);

        $admin = new User();
        $admin->setEmail('jerome.dannfald@orange.fr');
        $admin->setRoles(['ROLE_ADMIN']);

        $plaintextPassword = 'pc+jd';
        $hashedPassword = $this->passwordHasher->hashPassword(
            $admin,
            $plaintextPassword
        );

        $admin->setPassword($hashedPassword);
        $manager->persist($admin);

        $manager->flush();
    }
}
