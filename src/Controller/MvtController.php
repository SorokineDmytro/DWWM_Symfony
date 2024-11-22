<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Form\MvtType;
use App\Service\MyFct;
use App\Entity\Produit;
use App\Entity\Mouvement;
use App\Entity\LigneMouvement;
use Doctrine\ORM\EntityManager;
use App\Repository\ProduitRepository;
use App\Repository\MouvementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
#[Route("/mvt")]

class MvtController extends AbstractController
{
    #[Route('', name: 'app_mvt')]
    public function index(MouvementRepository $mr): Response
    {
        $mouvements=$mr->findBy([],['id'=>'desc']);
        return $this->render('mvt/index.html.twig', [
            'title'=>'Liste Mouvements',
            'mouvements'=>$mouvements,
        ]);
    } 

    #[Route('/new', name: 'app_mvt_new', methods:["GET", "POST"])]
    public function new(EntityManagerInterface $em, Request $request) {
        $mouvement = new Mouvement;
        $dateMouvement = new \DateTime();
        $mouvement->setDateMouvement($dateMouvement);
        $form = $this->createForm(MvtType::class, $mouvement);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $myFct = new MyFct();
            $entity = $form->get('typeMouvement')->getData();
            $numMouvement = $myFct->createNumEntity($em, $entity);  
            $mouvement->setNumMouvement($numMouvement);
            $em->persist($mouvement);
            $em->flush();
            return $this->redirectToRoute("app_mvt");
        }
        return $this->render("mvt/form.html.twig", [
            'title' => 'Creation Mouvement',
            'form' => $form,
            'disabled' => false,
        ]);
    }

    #[Route('/show/{id}', name:"app_mvt_show", methods:["GET"])]
    public function show(Mouvement $mouvement) {
        $form = $this->createForm(MvtType::class, $mouvement);
        return $this->render("mvt/form.html.twig", [
            'title' => 'Affichage Mouvement',
            'form' => $form,
            'disabled' => true,
        ]);
    }

    #[Route('/update/{id}', name:"app_mvt_update", methods:["GET", "POST"])]
    public function update(Mouvement $mouvement, EntityManagerInterface $em, Request $request) {
        $form = $this->createForm(MvtType::class, $mouvement);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($mouvement);
            $em->flush();
            return $this->redirectToRoute("app_mvt");
        }
        return $this->render("mvt/form.html.twig", [
            'title' => 'Modification Mouvement',
            'form' => $form,
            'disabled' => false,
        ]);
    }

    #[Route('/delete/{id}', name:"app_mvt_delete", methods:["GET"])]
    public function delete(Mouvement $mouvement, EntityManagerInterface $em, $id) {
        // $mouvement = $em->getRepository(Mouvement::class)->find($id); //sans injectin de dépendance
        $em->remove($mouvement);
        $em->flush();
        return $this->redirectToRoute("app_mvt");
    }

    #[Route("/search",name:"app_mvt_search_fetch",methods:['GET','POST'])]
    public function searchFetch(MouvementRepository $mr,Request $request){
        $mot=$request->get('mot');  // $_POST['mot]
        $mouvements=$mr->searchFetch($mot);
        // dd($mouvements);
        $rows=[];
        foreach($mouvements as $mouvement){
            extract($mouvement);
            $rows[]=[
                'id'=>$id,
                'numMouvement'=>$numMouvement,
                'dateMouvement'=>$dateMouvement->format('d/m/Y H:i:s'),
                'typeMouvement'=>$libelle,
                'tiers'=>$nomTiers,
            ];

        }
        // $myFct=new MyFct;
        // $myFct->printr($rows);die;
        return new JsonResponse($rows);
    } 

    #[Route("/ligne/{id}", name:"app_mvt_ligne", methods:['GET'])]
    public function ligne(Mouvement $mouvement) {
        $ligneMouvements = $mouvement->getLigneMouvements();
        return $this->render("/mvt/ligneMouvement.html.twig", [
            'title' => 'Saisie Ligne Mouvement',
            'mouvement' => $mouvement,
            'ligneMouvements' => $ligneMouvements,
        ]);
    }

    #[Route("/search_produit",name:"app_mvt_search_produit",methods:["GET","POST"])]
    public function searchProduit(ProduitRepository $pr, Request $request){
        $mot=$request->get('mot');
        $produits=$pr->search($mot);

        return new JsonResponse($produits);
    } 

    #[Route("/tbody/ligne/{id}", name: "app_mvt_ligne_tbody", methods: ["GET"])]
    public function ligneTbody(Mouvement $mouvement)
    {
        $ligneMouvements = $mouvement->getLigneMouvements();
        $rows = [];
        $total = 0;
        foreach ($ligneMouvements as $ligne) {
            $produit = $ligne->getProduit();
            $prixUnitaire = $ligne->getPrixUnitaire();
            $designation = $produit->getDesignation();
            $quantite = $ligne->getQuantite();
            $montant = $quantite * $prixUnitaire;
            $total += $montant;
            $rows[] = [
                'id' => $ligne->getId(),
                'numProduit' => $produit->getNumProduit(),
                'designation' => $designation,
                'prixUnitaire' => number_format($prixUnitaire, 2, '.', ' '),
                'quantite' => number_format($quantite, 2, '.', ' '),
                'montant' => number_format($montant, 2, '.', ' '),
            ];
        }

        if (!$rows) {
            $rows[] = [
                'id' => 0,
                'numProduit' => '',
                'designation' => '',
                'prixUnitaire' => '',
                'quantite' => '',
                'montant' => '',
            ];
        }

        $response = [
            'rows' => $rows,
            'total' => number_format($total, 2, '.', ' '),
        ];
        return new JsonResponse($response);
    }

    #[Route("/check/num_produit", name: "app_mvt_check_num_produit", methods: ["GET", "POST"])]
    public function checkNumProduit(ProduitRepository $pr, Request $request) 
    {
        $numProduit = $request->get("numProduit");
        $produit = $pr->findOneBy(["numProduit" => $numProduit]);
        if($produit) {
            $row = [
                'id' => $produit->getId(),
                'numProduit' => $produit->getNumProduit(),
                'designation' => $produit->getDesignation(),
                'prixUnitaire' => $produit->getPrixUnitaire(),
            ];
            $ok = 1;
        } else {
            $row = [
                'id' => 0,
                'numProduit' => $numProduit,
                'designation' => '',
                'prixUnitaire' => '',
            ];
            $ok = 0;
        }
        $response = [
            'ok' => $ok,
            'row' => $row,
        ];
        return new JsonResponse($response);
    }

    #[Route("/save/ligne/{id}", name:"app_mvt_save_ligne", methods: ["GET", "POST"])]
    public function saveLigne(Mouvement $mouvement, EntityManagerInterface $em, Request $request) {
        $numProduit = $request->get('numProduit');
        $quantite = $request->get('quantite');
        $produit = $em->getRepository(Produit::class)->findOneBy(['numProduit' => $numProduit]);
        if($produit) {
            $prixUnitaire = $produit->getPrixUnitaire();
            $ligneMouvement = new LigneMouvement;
            $ligneMouvement->setMouvement($mouvement); 
            $ligneMouvement->setProduit($produit); 
            $ligneMouvement->setQuantite($quantite); 
            $ligneMouvement->setPrixUnitaire($prixUnitaire); 
            $em->persist($ligneMouvement);
            $em->flush();
            $message = "Enregistrement bien fait à la base de donées !";
            $ok = 1;
        } else {
            $message = "Le code '$numProduit' n'existe pas !";
            $ok = 0;
        }
        $response = [
            'message' => $message,
            'ok' => $ok,
        ];
        return new JsonResponse($response);
    }

    #[Route("/export/pdf/{id}", name:"app_mvt_export_pdf", methods: ["GET"])]
    public function exportPdf(Mouvement $mouvement) {
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);
        $html = $this->renderView("mvt/export_pdf.html.twig", [
            'title' => 'Export Mouvement en PDF',
            'mouvement' => $mouvement,
            'ligneMouvements' => $mouvement->getLigneMouvements(),
        ]);
        // Charger le HTML dans Dompdf
        $dompdf->loadHtml($html);
        // (Facultatif) Définir les options de la page
        $dompdf->setPaper('A4', 'portrait');
        // Générer le PDF
        $dompdf->render();
        // Renvoyer le PDF comme réponse
        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Export_mouvement.pdf"',
        ]);
    }
}
