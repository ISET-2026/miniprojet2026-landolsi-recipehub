<?php

namespace App\DataFixtures;

use App\Entity\CategorieRecette;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategorieRecetteFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = [
            ['nom' => 'Entrée',   'icone' => '🥗'],
            ['nom' => 'Plat',     'icone' => '🍝'],
            ['nom' => 'Dessert',  'icone' => '🍰'],
            ['nom' => 'Boisson',  'icone' => '🥤'],
            ['nom' => 'Snack',    'icone' => '🍕'],
            ['nom' => 'Soupe',    'icone' => '🥣'],
        ];

        foreach ($categories as $i => $data) {
            $cat = new CategorieRecette();
            $cat->setNom($data['nom']);
            $cat->setIcone($data['icone']);
            $manager->persist($cat);
            $this->addReference('categorie_' . $i, $cat);
        }

        $manager->flush();
    }
}
