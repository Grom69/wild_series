<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public const TITLES = [
        'Friends',
        'Game of Throne',
        'Dexter',
        'Haunting of Hill House',
        'L\'Attaque des Titans'
    ];

    public const SYNOPSIS = [
        'L\'histoire raconte les péripéties de trois jeunes femmes et trois jeunes hommes new-yorkais liés par une profonde amitié. Entre amour, travail, famille, ils partagent leurs bonheurs et leurs soucis au Central Perk, leur café favori.',
        'Histoire de jeux de pouvoir',
        'Histoire de seriel killer',
        'Histoire de fantôme',
        'Histoire de titans'
    ];

    public const REF_CATEGORIES = [
        'category_Action',
        'category_Fantastique',
        'category_Action',
        'category_Horreur',
        'category_Animation'
    ];

    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 5; $i++) {
            $program = new Program();
            $program->setTitle(self::TITLES[$i]);
            $program->setSynopsis(self::SYNOPSIS[$i]);
            $program->setCategory($this->getReference(self::REF_CATEGORIES[$i]));
            $program->setSlug($this->slugger->slug($program->getTitle()));
            $manager->persist($program);
            $this->addReference('program_' . $i, $program);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
            CategoryFixtures::class,
        ];
    }
}
