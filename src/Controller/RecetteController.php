<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Form\RecetteType;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RecetteController extends AbstractController
{
    #[Route('/recettes', name: 'app_recettes')]
    public function index(RecetteRepository $recetteRepository): Response
    {
        $recettes = $recetteRepository->findAll();

        return $this->render('recette/index.html.twig', [
            'recettes' => $recettes,
        ]);
    }

    #[Route('/recettes/nouvelle', name: 'app_recette_nouvelle')]
    #[IsGranted('ROLE_CUISINIER')]
    public function nouvelle(Request $request, EntityManagerInterface $em): Response
    {
        $recette = new Recette();
        $recette->setDateCreation(new \DateTime());
        $recette->setAuteur($this->getUser());

        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($recette);
            $em->flush();

            $this->addFlash('success', 'Recette créée avec succès !');
            return $this->redirectToRoute('app_recettes');
        }

        return $this->render('recette/nouvelle.html.twig', [
            'formulaire' => $form,
        ]);
    }

    #[Route('/recettes/{id}', name: 'app_recette_detail', requirements: ['id' => '\d+'])]
    public function detail(Recette $recette): Response
    {
        return $this->render('recette/detail.html.twig', [
            'recette' => $recette,
        ]);
    }

    #[Route('/recettes/{id}/modifier', name: 'app_recette_modifier', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_USER')]
    public function modifier(Recette $recette, Request $request, EntityManagerInterface $em): Response
    {
        if ($recette->getAuteur() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas l\'auteur de cette recette !');
        }

        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Recette modifiée avec succès !');
            return $this->redirectToRoute('app_recette_detail', ['id' => $recette->getId()]);
        }

        return $this->render('recette/modifier.html.twig', [
            'formulaire' => $form,
            'recette' => $recette,
        ]);
    }

    #[Route('/recettes/{id}/supprimer', name: 'app_recette_supprimer', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function supprimer(Recette $recette, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('supprimer_' . $recette->getId(), $request->request->get('_token'))) {
            $em->remove($recette);
            $em->flush();
            $this->addFlash('success', 'Recette supprimée avec succès.');
        } else {
            $this->addFlash('danger', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('app_recettes');
    }
}