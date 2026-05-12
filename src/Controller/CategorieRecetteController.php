<?php

namespace App\Controller;

use App\Entity\CategorieRecette;
use App\Form\CategorieRecetteType;
use App\Repository\CategorieRecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CategorieRecetteController extends AbstractController
{
    #[Route('/categories', name: 'app_categories')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(CategorieRecetteRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();

        return $this->render('categorie_recette/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/categories/nouvelle', name: 'app_categorie_nouvelle')]
    #[IsGranted('ROLE_ADMIN')]
    public function nouvelle(Request $request, EntityManagerInterface $em): Response
    {
        $categorie = new CategorieRecette();

        $form = $this->createForm(CategorieRecetteType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($categorie);
            $em->flush();

            $this->addFlash('success', 'Catégorie créée avec succès !');
            return $this->redirectToRoute('app_categories');
        }

        return $this->render('categorie_recette/nouvelle.html.twig', [
            'formulaire' => $form,
        ]);
    }

    #[Route('/categories/{id}/supprimer', name: 'app_categorie_supprimer', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function supprimer(CategorieRecette $categorie, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('supprimer_' . $categorie->getId(), $request->request->get('_token'))) {
            $em->remove($categorie);
            $em->flush();
            $this->addFlash('success', 'Catégorie supprimée.');
        }

        return $this->redirectToRoute('app_categories');
    }
}