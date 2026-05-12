<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Entity\Recette;
use App\Form\IngredientType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class IngredientController extends AbstractController
{
    #[Route('/recettes/{id}/ingredients/nouveau', name: 'app_ingredient_nouveau', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_USER')]
    public function nouveau(Recette $recette, Request $request, EntityManagerInterface $em): Response
    {
        $ingredient = new Ingredient();
        $ingredient->setRecette($recette);

        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($ingredient);
            $em->flush();

            $this->addFlash('success', 'Ingrédient ajouté !');
            return $this->redirectToRoute('app_recette_detail', ['id' => $recette->getId()]);
        }

        return $this->render('ingredient/nouveau.html.twig', [
            'formulaire' => $form,
            'recette' => $recette,
        ]);
    }

    #[Route('/ingredients/{id}/supprimer', name: 'app_ingredient_supprimer', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function supprimer(Ingredient $ingredient, Request $request, EntityManagerInterface $em): Response
    {
        $recetteId = $ingredient->getRecette()->getId();

        if ($this->isCsrfTokenValid('supprimer_' . $ingredient->getId(), $request->request->get('_token'))) {
            $em->remove($ingredient);
            $em->flush();
            $this->addFlash('success', 'Ingrédient supprimé.');
        }

        return $this->redirectToRoute('app_recette_detail', ['id' => $recetteId]);
    }
}