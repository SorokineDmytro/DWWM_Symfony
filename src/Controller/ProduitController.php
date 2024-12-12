<?php

namespace App\Controller;


use App\Service\MyFct;
use App\Entity\Produit;
use App\Entity\Categorie;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/depot/produit')]
final class ProduitController extends AbstractController
{
    #[Route('/api', name:'app_produit_api', methods:["GET"])]
    public function apiProduit(ProduitRepository $pr){
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
		$produits=$pr->findBy([],['id'=>'ASC']);
		$response=[];
		foreach($produits as $produit){
			$response[]=[
				'id'=>$produit->getId(),
				'code'=>$produit->getNumProduit(),
				'designation'=>$produit->getDesignation(),
				'pu'=>$produit->getPrixUnitaire(),
				'pr'=>$produit->getPrixUnitaire()/2,
            ];
		}
		echo json_encode($response);
		exit();
	} 

    #[Route('/api/edit', name:'app_produit_api_edit', methods:["GET", "POST"])]
    public function apiEditProduit(EntityManagerInterface $em, Request $request) {
        header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        $edit_id = (int) $request->get('edit_id');
        $numProduit = $request->get('numProduit');
        $designation = $request->get('designation');
        $prixUnitaire = $request->get('prixUnitaire');
        $prixRevient = $request->get('prixRevient');
        $error = false;
        if($edit_id == 0) {
            $produit = new Produit;
        } else {
            $produit = $em->getRepository(Produit::class)->find($edit_id);
            if(!$produit) {
                $error = true;
            }
        }
        if($error) {
            $response = "Erreur d'enregistrement! Le produit dont l'id = $edit_id n'existe pas!";
        } else {
            $produit->setNumProduit($numProduit);
            $produit->setDesignation($designation);
            $produit->setPrixUnitaire($prixUnitaire);
            $produit->setPrixRevient($prixRevient);
            $em->persist($produit);
            $em->flush();
            $response = "Enregistrement bien fait";
        }
        echo json_encode($response);
        exit();
    }

    #[Route("/api/delete", name:"app_produit_api_delete", methods:["GET", "POST"])]
	public function apiDeleteProduit(EntityManagerInterface $em, Request $request){
        header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
		$id = (int) $request->get('id');
		$produit = $em->getRepository(Produit::class)->find($id);
		if($produit) {
			$em->remove($produit);
			$em->flush();
			$response = "Suppression bien faite dans la base de données!";
		}else{
			$response = "Il existe une erreur sur la suppression de id= $id! Suppression annulée!";
		}
		echo json_encode($response);
		exit();
	} 

    #[Route(name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $myFct = new MyFct();
            $categorie = $form->get('categorie')->getData();
            $numProduit = $myFct->createNumEntity($entityManager, $categorie); 
            $produit->setNumProduit($numProduit);
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/import/excel', name: 'app_produit_import_excel', methods: ['GET'])]
    public function importExcel(EntityManagerInterface $em) {
        $filePath='/Users/sorokine/Codding/myProjects/2024/Symphony/produit.xlsx';
        $r0=2;
        $myFct=new MyFct();
        $datas=$myFct->readExcel($filePath,$r0);
        foreach($datas as $data){
            $numProduit=$data[0];
            $designation=$data[1];
            $prixUnitaire=$data[2];
            $prixRevient=$data[3];
            $categorie_id=$data[4];
            $categorie=$em->getRepository(Categorie::class)->find($categorie_id);
            $produit=$em->getRepository(Produit::class)->findOneBy(['numProduit'=>$numProduit]);
            if(!$produit){
                $produit=new Produit;
            }
            $produit->setNumProduit($numProduit);
            $produit->setDesignation($designation);
            $produit->setPrixUnitaire($prixUnitaire);
            $produit->setPrixRevient($prixRevient);
            $produit->setCategorie($categorie);
            $em->persist($produit);
            $em->flush();
        }
        return $this->redirectToRoute("app_produit_index");
        }

    #[Route('/export/excel', name: 'app_produit_export_excel', methods: ['GET'])]
    public function exportExcel(ProduitRepository $pr) {
        $produits = $pr->findBy([], ['numProduit'=>'ASC']);
        $datas[] = [
            'NUM PRODUIT',
            'DESIGNATION',
            'PRIX UNITAIRE',
            'PRIX REVIENT',
        ];
        foreach($produits as $produit) {
            $datas[] = [
                $produit->getNumProduit(),
                $produit->getDesignation(),
                $produit->getPrixUnitaire(),
                $produit->getPrixRevient(),
            ];
        }
        $myFct=new MyFct();
        $filePath = $filePath='/Users/sorokine/Codding/myProjects/2024/Symphony/all_produit.xlsx';
        $r0 = 1;
        $file = $myFct->writeExcel($datas,$filePath,$r0=1);
        return $this->file($filePath,"Export produit.xlsx", ResponseHeaderBag::DISPOSITION_INLINE);
    }

    #[Route("/import/modele_excel",name:"app_produit_import_modele_excel",methods:["GET","POST"])]
    public function importModelExcel(EntityManagerInterface $em,Request $request){
        if(isset($_FILES['excel'])){
            $filePath=$_FILES['excel']['tmp_name']; 
            $myFct=new MyFct();
            $r0=3; 
            $datas=$myFct->readExcel($filePath,$r0);
            foreach($datas as $data){
                $numProduit= (string) $data[0];
                $designation= (string) $data[1];
                $prixUnitaire= (float) $data[2];
                $prixRevient= (float) $data[3];
                $categorie_id= (int) $data[4];
                $categorie=$em->getRepository(Categorie::class)->find($categorie_id);
                $produit=$em->getRepository(Produit::class)->findOneBy(['numProduit'=>$numProduit]);
                if(!$produit){  // Le produit n'existe pas
                    $produit=new Produit;
                }
                $produit->setNumProduit($numProduit);
                $produit->setDesignation($designation);
                $produit->setPrixUnitaire($prixUnitaire);
                $produit->setPrixRevient($prixRevient);
                $produit->setCategorie($categorie);
                $em->persist($produit);
            }
            $em->flush();
            $response="Importation bien faite!";
       }else{
            $response="Vous n'avez pas choisi un fichier Excel!";
        }

        return new JsonResponse($response);

    } 


    #[Route('/export/modele_excel', name: 'app_produit_export_modele_excel', methods: ['GET'])]
    public function exportModelExcel(ProduitRepository $pr) {
        $produits = $pr->findBy([], ['numProduit'=>'ASC']);
        foreach($produits as $produit) {
            $datas[] = [
                $produit->getNumProduit(),
                $produit->getDesignation(),
                $produit->getPrixUnitaire(),
                $produit->getPrixRevient(),
            ];
        }
        $myFct=new MyFct();
        $fileModele = __DIR__."/../../public/modeles/Modele_Liste_Produits.xlsx";
        $filePath = __DIR__."/../../public/modeles/Liste_Produits.xlsx";
        $r0 = 1;
        $file = $myFct->writeModeleExcel($datas,$fileModele,$filePath,$r0=3);
        return $this->file($filePath,"Export produit.xlsx", ResponseHeaderBag::DISPOSITION_INLINE);
    }

    
}
