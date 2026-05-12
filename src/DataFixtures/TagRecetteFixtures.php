<?php

namespace App\DataFixtures;

use App\Entity\TagRecette;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TagRecetteFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $tags = [
            ['nom' => 'Végétarien',  'couleur' => '#27ae60'],
            ['nom' => 'Végan',       'couleur' => '#2ecc71'],
            ['nom' => 'Sans Gluten', 'couleur' => '#f39c12'],
            ['nom' => 'Bio',         'couleur' => '#16a085'],
            ['nom' => 'Rapide',      'couleur' => '#e74c3c'],
            ['nom' => 'Familial',    'couleur' => '#3498db'],
            ['nom' => 'Festif',      'couleur' => '#9b59b6'],
            ['nom' => 'Économique',  'couleur' => '#e67e22'],
        ];

        foreach ($tags as $i => $data) {
            $tag = new TagRecette();
            $tag->setNom($data['nom']);
            $tag->setCouleur($data['couleur']);
            $manager->persist($tag);
            $this->addReference('tag_' . $i, $tag);
        }

        $manager->flush();
    }
}
