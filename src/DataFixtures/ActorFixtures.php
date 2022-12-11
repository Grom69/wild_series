<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();


        for ($i = 0; $i < 10; $i++) {
            $actor = new Actor();
            $actor->setName($faker->firstName() . ' ' . $faker->lastName());
            // $this->addReference('actor_' . $i, $actor);
            for ($y = 0; $y < 3; $y++) {
                $actor->addProgram($this->getReference('program_' . $y));
            }

            $manager->persist($actor);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProgramFixtures::class,
        ];
    }
}
