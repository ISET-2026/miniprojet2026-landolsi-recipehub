<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RecetteControllerTest extends WebTestCase
{
    public function testRecettesPageLoads(): void
    {
        $client = static::createClient();
        $client->request('GET', '/recettes');
        $this->assertResponseIsSuccessful();
    }

    public function testNouvelleRecetteRequiresAuth(): void
    {
        $client = static::createClient();
        $client->request('GET', '/recettes/nouvelle');
        $this->assertResponseRedirects('/login');
    }
}
