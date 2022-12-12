<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

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
                $episode->setDuration($faker->numberBetween(40, 60));
                $episode->setSlug($this->slugger->slug($episode->getTitle()));
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
