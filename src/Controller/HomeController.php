<?php

namespace App\Controller;

use App\Service\RecetteAnalyser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public function __construct(private RecetteAnalyser $analyser) {}

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'totalPubliees'      => $this->analyser->getTotalRecettesPubliees(),
            'parCategorie'       => $this->analyser->getRecettesParCategorie(),
            'moyenneIngredients' => $this->analyser->getMoyenneIngredients(),
        ]);
    }
}