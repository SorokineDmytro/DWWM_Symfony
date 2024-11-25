<?php

namespace App\Controller;

use App\Service\MyFct;
use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/categorie')]
final class CategorieController extends AbstractController
{
    #[Route(name: 'app_categorie_index', methods: ['GET'])]
    public function index(CategorieRepository $categorieRepository): Response
    {
        return $this->render('categorie/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_categorie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_show', methods: ['GET'])]
    public function show(Categorie $categorie): Response
    {
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categorie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_delete', methods: ['POST'])]
    public function delete(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($categorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/import/excel', name: 'app_categorie_import_excel', methods: ['GET'])]
    public function importExcel(EntityManagerInterface $em) {
        $filePath='/Users/sorokine/Codding/myProjects/2024/Symphony/categorie.xlsx';
        $r0=2;
        $myFct=new MyFct();
        $datas=$myFct->readExcel($filePath,$r0);
        foreach($datas as $data){
            $prefixe=$data[0];
            $numeroInitial=$data[1];
            $libelle=$data[2];
            $format=$data[3];
            $categorie=$em->getRepository(Categorie::class)->findOneBy(['prefixe'=>$prefixe]);
            if(!$categorie){
                $categorie=new Categorie;
            }
            $categorie->setPrefixe($prefixe);
            $categorie->setNumeroInitial($numeroInitial);
            $categorie->setLibelle($libelle);
            $categorie->setFormat($format);
            $em->persist($categorie);
            $em->flush();
        }
        return $this->redirectToRoute("app_categorie_index");
        }

    #[Route('/export/excel', name: 'app_categorie_export_excel', methods: ['GET'])]
    public function exportExcel(CategorieRepository $cr) {
        $categories = $cr->findBy([], ['prefixe'=>'ASC']);
        $datas[] = [
            'PREFIXE',
            'NUM INITIAL',
            'LIBELLE',
            'FORMAT',
        ];
        foreach($categories as $categorie) {
            $datas[] = [
                $categorie->getPrefixe(),
                $categorie->getNumeroInitial(),
                $categorie->getLibelle(),
                $categorie->getFormat(),
            ];
        }
        $myFct=new MyFct();
        $filePath = $filePath='/Users/sorokine/Codding/myProjects/2024/Symphony/all_categories.xlsx';
        $r0 = 1;
        $file = $myFct->writeExcel($datas,$filePath,$r0=1);
        return $this->file($filePath,"Export categories.xlsx", ResponseHeaderBag::DISPOSITION_INLINE);
    }
}
