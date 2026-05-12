<?php

namespace App\Controller;

use App\Entity\TagRecette;
use App\Form\TagRecetteType;
use App\Repository\TagRecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TagRecetteController extends AbstractController
{
    #[Route('/tags', name: 'app_tags')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(TagRecetteRepository $tagRepository): Response
    {
        $tags = $tagRepository->findAll();

        return $this->render('tag_recette/index.html.twig', [
            'tags' => $tags,
        ]);
    }

    #[Route('/tags/nouveau', name: 'app_tag_nouveau')]
    #[IsGranted('ROLE_ADMIN')]
    public function nouveau(Request $request, EntityManagerInterface $em): Response
    {
        $tag = new TagRecette();

        $form = $this->createForm(TagRecetteType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($tag);
            $em->flush();

            $this->addFlash('success', 'Tag créé avec succès !');
            return $this->redirectToRoute('app_tags');
        }

        return $this->render('tag_recette/nouveau.html.twig', [
            'formulaire' => $form,
        ]);
    }

    #[Route('/tags/{id}/supprimer', name: 'app_tag_supprimer', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function supprimer(TagRecette $tag, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('supprimer_' . $tag->getId(), $request->request->get('_token'))) {
            $em->remove($tag);
            $em->flush();
            $this->addFlash('success', 'Tag supprimé.');
        }

        return $this->redirectToRoute('app_tags');
    }
}