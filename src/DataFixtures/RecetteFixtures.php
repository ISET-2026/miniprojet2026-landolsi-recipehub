<?php

namespace App\DataFixtures;

use App\Entity\Recette;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class RecetteFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $titres = [
            'Tarte aux pommes', 'Soupe à l\'oignon', 'Quiche lorraine',
            'Boeuf bourguignon', 'Crème brûlée', 'Ratatouille',
            'Coq au vin', 'Mousse au chocolat', 'Salade niçoise',
            'Gratin dauphinois', 'Blanquette de veau', 'Croissants maison',
            'Pizza margherita', 'Risotto aux champignons', 'Tiramisu',
            'Poulet rôti', 'Gaspacho andalou', 'Fondant au chocolat',
            'Taboulé maison', 'Lasagnes bolognaise'
        ];

        for ($i = 0; $i < 20; $i++) {
            $recette = new Recette();
            $recette->setTitre($titres[$i]);
            $recette->setDescription($faker->paragraph(3));
            $recette->setInstructions($faker->paragraph(5));
            $recette->setTempsPreparation($faker->numberBetween(10, 60));
            $recette->setTempsCuisson($faker->numberBetween(10, 120));
            $recette->setDifficulte($faker->randomElement(['facile', 'moyen', 'difficile']));
            $recette->setNbPersonnes($faker->numberBetween(1, 8));
            $recette->setDateCreation($faker->dateTimeBetween('-1 year', 'now'));
            $recette->setPubliee($faker->boolean(80));
            $recette->setCategorie($this->getReference('categorie_' . $faker->numberBetween(0, 5), \App\Entity\CategorieRecette::class));
            $recette->setAuteur($this->getReference('user_' . $faker->numberBetween(0, 4), \App\Entity\User::class));
            // 1 to 4 random tags
            $nbTags = $faker->numberBetween(1, 4);
            $tagIndexes = $faker->randomElements(range(0, 7), $nbTags);
            foreach ($tagIndexes as $tagIndex) {
                $recette->addTag($this->getReference('tag_' . $tagIndex, \App\Entity\TagRecette::class));
            }

            $manager->persist($recette);
            $this->addReference('recette_' . $i, $recette);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategorieRecetteFixtures::class,
            TagRecetteFixtures::class,
            UserFixtures::class,
        ];
    }
}
