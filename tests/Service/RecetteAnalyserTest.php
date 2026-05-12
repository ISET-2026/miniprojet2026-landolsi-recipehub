<?php

namespace App\Tests\Service;

use App\Entity\Recette;
use App\Repository\RecetteRepository;
use App\Service\RecetteAnalyser;
use PHPUnit\Framework\TestCase;

class RecetteAnalyserTest extends TestCase
{
    public function testGetTempsTotalWithCuisson(): void
    {
        $recette = new Recette();
        $recette->setTempsPreparation(30);
        $recette->setTempsCuisson(20);

        $repo = $this->createMock(RecetteRepository::class);
        $analyser = new RecetteAnalyser($repo);

        $this->assertEquals(50, $analyser->getTempsTotal($recette));
    }

    public function testGetTempsTotalWithoutCuisson(): void
    {
        $recette = new Recette();
        $recette->setTempsPreparation(30);
        $recette->setTempsCuisson(null);

        $repo = $this->createMock(RecetteRepository::class);
        $analyser = new RecetteAnalyser($repo);

        $this->assertEquals(30, $analyser->getTempsTotal($recette));
    }

    public function testGetTotalRecettesPubliees(): void
    {
        $repo = $this->createMock(RecetteRepository::class);
        $repo->method('findBy')->willReturn([new Recette(), new Recette()]);

        $analyser = new RecetteAnalyser($repo);
        $this->assertEquals(2, $analyser->getTotalRecettesPubliees());
    }

    public function testGetMoyenneIngredientsNoRecettes(): void
    {
        $repo = $this->createMock(RecetteRepository::class);
        $repo->method('findAll')->willReturn([]);

        $analyser = new RecetteAnalyser($repo);
        $this->assertEquals(0, $analyser->getMoyenneIngredients());
    }
}

