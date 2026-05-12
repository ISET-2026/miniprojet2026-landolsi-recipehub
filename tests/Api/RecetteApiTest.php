<?php

namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RecetteApiTest extends WebTestCase
{
    public function testGetRecettesReturns200(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/recettes', [], [], [
            'HTTP_ACCEPT' => 'application/ld+json',
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testPostRecetteInvalidReturns422(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/recettes', [], [], [
            'CONTENT_TYPE' => 'application/ld+json',
            'HTTP_ACCEPT'  => 'application/ld+json',
        ], json_encode(['titre' => '']));
        $this->assertResponseStatusCodeSame(422);
    }
}

