<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Tiers;
use App\Form\MvtType;
use App\Service\MyFct;
use App\Entity\Produit;
use App\Entity\Mouvement;
use App\Entity\TypeMouvement;
use App\Entity\LigneMouvement;
use Doctrine\ORM\EntityManager;
use App\Repository\ProduitRepository;
use App\Repository\MouvementRepository;
use App\Repository\TiersRepository;
use App\Repository\TypeMouvementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
#[Route("mvt")]

class MvtController extends AbstractController
{
    #[Route('', name: 'app_mvt')]
    public function index(MouvementRepository $mr): Response
    {
        if(!$this->isGranted('ROLE_CAISSE')){
            return $this->redirectToRoute('app_accueil_erreur');
        }
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

    #[Route('/update/ligne/{id}', name:"app_mvt_update_ligne", methods:["GET", "POST"])]
    public function updateLigne(LigneMouvement $ligneMouvement, EntityManagerInterface $em, Request $request)
    {
        if ($request->isMethod('POST')) {
            $quantite = $request->get('quantite');
            $prixUnitaire = $request->get('prixUnitaire');

        if ($quantite !== null && $prixUnitaire !== null) {
            $ligneMouvement->setQuantite($quantite);
            $ligneMouvement->setPrixUnitaire($prixUnitaire);
            $em->persist($ligneMouvement);
            $em->flush();
            $response = [
                "message" => "Modification bien effectuée !",
                "ok" => 1,
            ];
        } else {
            $response = [
                "message" => "Modification non effectuée !",
                "ok" => 0,
            ];
        }
    } else {
        $response = [
            "numProduit" => $ligneMouvement->getProduit()->getNumProduit(),
            "designation" => $ligneMouvement->getProduit()->getDesignation(),
            "prixUnitaire" => $ligneMouvement->getPrixUnitaire(),
            "quantite" => $ligneMouvement->getQuantite(),
            "ok" => 1,
        ];
    }
    return new JsonResponse($response);
    }

    #[Route('/delete/ligne/{id}', name:"app_mvt_delete_ligne", methods:["GET"])]
    public function deleteLigne($id, EntityManagerInterface $em)
    {
        $ligneMouvement = $em->getRepository(LigneMouvement::class)->find($id);
        if ($ligneMouvement) {
            $em->remove($ligneMouvement);
            $em->flush();
            $message = "Suppression bien effectuée !";
            $ok = 1;
        } else {
            $message = "Suppression non effectuée !";
            $ok = 0;
        }
        $response = [
            "message" => $message,
            "ok" => $ok,
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

    #[Route('/import/excel', name: 'app_mouvement_import_excel', methods: ['GET'])]
    public function importExcel(EntityManagerInterface $em) {
        $filePath='/Users/sorokine/Codding/myProjects/2024/Symphony/mouvement.xlsx';
        $r0=2;
        $myFct=new MyFct();
        $datas=$myFct->readExcel($filePath,$r0);
        foreach($datas as $data){
            $numMouvement=$data[0];
            $dateMouvement=$data[1];
            $type_mouvement_id=$data[2];
            $tiers_id=$data[3];
            $typeMouvement=$em->getRepository(TypeMouvement::class)->find($type_mouvement_id);
            $tiers=$em->getRepository(Tiers::class)->find($tiers_id);
            $mouvement=$em->getRepository(Mouvement::class)->findOneBy(['numMouvement'=>$numMouvement]);
            if(!$mouvement){
                $mouvement=new Mouvement;
            }
            $mouvement->setNumMouvement($numMouvement);
            $mouvement->setDateMouvement($dateMouvement);
            $mouvement->setTypeMouvement($typeMouvement);
            $mouvement->setTiers($tiers);
            $em->persist($mouvement);
            $em->flush();
        }
        return $this->redirectToRoute("app_mouvement_index");
        }

    #[Route('/export/excel', name: 'app_mouvement_export_excel', methods: ['GET'])]
    public function exportExcel(MouvementRepository $mr, TypeMouvementRepository $tmr, TiersRepository $tr) {
        $mouvements = $mr->findBy([], ['dateMouvement'=>'ASC']);
        $typeMouvement = $tmr->findBy([], ['id'=>'ASC']);
        $tiers = $tr->findBy([], ['id'=>'ASC']);
        $datas[] = [
            'NUM MOUVEMENT',
            'DATE MOUVEMENT',
            'TYPE MOUVEMENT',
            'TIERS',
        ];
        
        foreach($mouvements as $mouvement) {
            $typeMouvement = $mouvement->getTypeMouvement();
            $libelle = $typeMouvement->getLibelle();

            $tiers = $mouvement->getTiers();
            $nomTiers = $tiers->getNomTiers();

            $datas[] = [
                $mouvement->getNumMouvement(),
                $mouvement->getDateMouvement(),
                $libelle,
                $nomTiers,
            ];
        }
       
        $myFct=new MyFct();
        $filePath = $filePath='/Users/sorokine/Codding/myProjects/2024/Symphony/all_mouvements.xlsx';
        $r0 = 1;
        $file = $myFct->writeExcel($datas,$filePath,$r0=1);
        return $this->file($filePath,"Export mouvements.xlsx", ResponseHeaderBag::DISPOSITION_INLINE);
    }

    #[Route('/type/{prefixe}', name: 'app_mvt_type', methods: ['GET'])]
    public function indexType(MouvementRepository $mr,TypeMouvementRepository $tr, $prefixe){
        $typeMouvement=$tr->findOneBy(['prefixe'=>$prefixe]);
        if($typeMouvement) {
            $libelle = $typeMouvement->getLibelle();
        } else {
            $libelle = 'inconnue';
        }
        $mouvements=$mr->findBy(['typeMouvement'=>$typeMouvement],["numMouvement"=>"DESC"]);
        return $this->render("mvt/index.html.twig",[
            'title'=>"Liste ".$libelle,
            'mouvements'=>$mouvements,
        ]);
    } 
}
