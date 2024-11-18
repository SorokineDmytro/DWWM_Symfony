<?php

namespace App\Controller;

use App\Entity\TypeTiers;
use App\Form\TypeTiersType;
use App\Repository\TypeTiersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/typetiers')]
final class TypeTiersController extends AbstractController
{
    #[Route(name: 'app_type_tiers_index', methods: ['GET'])]
    public function index(TypeTiersRepository $typeTiersRepository): Response
    {
        return $this->render('type_tiers/index.html.twig', [
            'type_tiers' => $typeTiersRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_type_tiers_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $typeTier = new TypeTiers();
        $form = $this->createForm(TypeTiersType::class, $typeTier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typeTier);
            $entityManager->flush();

            return $this->redirectToRoute('app_type_tiers_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('type_tiers/new.html.twig', [
            'type_tier' => $typeTier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_tiers_show', methods: ['GET'])]
    public function show(TypeTiers $typeTier): Response
    {
        return $this->render('type_tiers/show.html.twig', [
            'type_tier' => $typeTier,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_type_tiers_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypeTiers $typeTier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypeTiersType::class, $typeTier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_type_tiers_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('type_tiers/edit.html.twig', [
            'type_tier' => $typeTier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_tiers_delete', methods: ['POST'])]
    public function delete(Request $request, TypeTiers $typeTier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeTier->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($typeTier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_type_tiers_index', [], Response::HTTP_SEE_OTHER);
    }
}
