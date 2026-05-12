<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class IngredientFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $ingredients = ['Farine', 'Sucre', 'Beurre', 'Oeufs', 'Lait', 'Sel',
                        'Poivre', 'Huile', 'Ail', 'Oignon', 'Tomate', 'Fromage',
                        'Poulet', 'Boeuf', 'Carotte', 'Pomme de terre'];

        for ($i = 0; $i < 20; $i++) {
            $nbIngredients = $faker->numberBetween(3, 8);
            for ($j = 0; $j < $nbIngredients; $j++) {
                $ingredient = new Ingredient();
                $ingredient->setNom($faker->randomElement($ingredients));
                $ingredient->setQuantite($faker->numberBetween(1, 500) . $faker->randomElement(['g', 'ml', 'cl', ' pièce(s)']));
                $ingredient->setRecette($this->getReference('recette_' . $i, \App\Entity\Recette::class));
                $manager->persist($ingredient);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [RecetteFixtures::class];
    }
}
