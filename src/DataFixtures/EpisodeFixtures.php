<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 25; $i++) {
            for ($y = 0; $y < 10; $y++) {
                $episode = new Episode();
                $episode->setTitle($faker->sentence(3));
                $episode->setNumber($y + 1);
                $episode->setSynopsis($faker->paragraphs(3, true));
                $episode->setSeason($this->getReference('season_' . $i + 1));
                $manager->persist($episode);
            }
            $manager->flush();
        }
    }

    public function getDependencies(): array
    {
        return [
            SeasonFixtures::class,
        ];
    }
}
