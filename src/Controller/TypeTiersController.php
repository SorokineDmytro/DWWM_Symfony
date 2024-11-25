<?php

namespace App\Controller;

use App\Service\MyFct;
use App\Entity\TypeTiers;
use App\Form\TypeTiersType;
use App\Repository\TypeTiersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    #[Route('/import/excel', name: 'app_typeTiers_import_excel', methods: ['GET'])]
    public function importExcel(EntityManagerInterface $em) {
        $filePath='/Users/sorokine/Codding/myProjects/2024/Symphony/typeTiers.xlsx';
        $r0=2;
        $myFct=new MyFct();
        $datas=$myFct->readExcel($filePath,$r0);
        foreach($datas as $data){
            $prefixe=$data[0];
            $numeroInitial=$data[1];
            $libelle=$data[2];
            $format=$data[3];
            $typeTiers=$em->getRepository(TypeTiers::class)->findOneBy(['prefixe'=>$prefixe]);
            if(!$typeTiers){
                $typeTiers=new TypeTiers;
            }
            $typeTiers->setPrefixe($prefixe);
            $typeTiers->setNumeroInitial($numeroInitial);
            $typeTiers->setLibelle($libelle);
            $typeTiers->setFormat($format);
            $em->persist($typeTiers);
            $em->flush();
        }
        return $this->redirectToRoute("app_typeTiers_index");
        }

    #[Route('/export/excel', name: 'app_typeTiers_export_excel', methods: ['GET'])]
    public function exportExcel(TypeTiersRepository $ttr) {
        $typeTiers = $ttr->findBy([], ['prefixe'=>'ASC']);
        $datas[] = [
            'PREFIXE',
            'NUM INITIAL',
            'LIBELLE',
            'FORMAT',
        ];
        foreach($typeTiers as $typeTier) {
            $datas[] = [
                $typeTier->getPrefixe(),
                $typeTier->getNumeroInitial(),
                $typeTier->getLibelle(),
                $typeTier->getFormat(),
            ];
        }
        $myFct=new MyFct();
        $filePath = $filePath='/Users/sorokine/Codding/myProjects/2024/Symphony/all_typeTiers.xlsx';
        $r0 = 1;
        $file = $myFct->writeExcel($datas,$filePath,$r0=1);
        return $this->file($filePath,"Export typeTiers.xlsx", ResponseHeaderBag::DISPOSITION_INLINE);
    }
}
