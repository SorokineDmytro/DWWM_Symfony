<?php

namespace App\Controller;

use App\Service\MyFct;
use App\Entity\TypeMouvement;
use App\Form\TypeMouvementType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TypeMouvementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
#[Route('/admin/typemouvement')]

class TypeMouvementController extends AbstractController
{
    #[Route('/', name: 'app_type_mouvement')]
    public function index(TypeMouvementRepository $tr): Response
    {
        $typeMouvements=$tr->findAll();
        return $this->render('type_mouvement/index.html.twig', [
            'title' => 'Liste Types Mouvement',
            'typeMouvement' => $typeMouvements,
            'controller_name' => 'TypeMouvementController',
        ]);
    }

    #[Route("/new", name:'app_type_mouvement_new', methods:['GET', 'POST'])]
    public function new(EntityManagerInterface $em, Request $request) {
        $typeMouvement = new TypeMouvement;
        $form = $this->createForm(TypeMouvementType::class, $typeMouvement);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($typeMouvement);
            $em->flush();
            return $this->redirectToRoute("app_type_mouvement");
        } 
        $file="type_mouvement/form.html.twig";
        $variables=[
            'title'=>"Nouveau type mouvement",
            'form'=>$form->createView(),
        ];
        return $this->render($file,$variables);

    } 

    #[Route("/show/{id}",name:"app_type_mouvement_show",methods:["GET"])]
    public function show(TypeMouvementRepository $tmr,$id){
        $typeMouvement=$tmr->find($id);
        $file="type_mouvement/show.html.twig";
        $variables=[
            'title'=>'Affichage type mouvement',
            'typeMouvement'=>$typeMouvement,
        ];
        return $this->render($file,$variables);

    } 

    #[Route("/update/{id}", name:'app_type_mouvement_update', methods:['GET', 'POST'])]
    public function update(EntityManagerInterface $em, $id, Request $request) {
        $typeMouvement = $em->getRepository(TypeMouvement::class)->find($id);
        // -- ou $typeMouvement = $tupeMouvementRepository->find($id);
        $form = $this->createForm(TypeMouvementType::class, $typeMouvement);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($typeMouvement);
            $em->flush();
            return $this->redirectToRoute("app_type_mouvement");
        } 
        $file="type_mouvement/form.html.twig";
        $variables=[
            'title'=>"Modification type mouvement",
            'form'=>$form->createView(),
        ];
        return $this->render($file,$variables);
    }

    #[Route("/delete/{id}",name:"app_type_mouvement_delete",methods:["GET"])]
    public function delete(EntityManagerInterface $em,$id){
        $typeMouvement = $em->getRepository(TypeMouvement::class)->find($id);
        $em->remove($typeMouvement);
        $em->flush();
        return $this->redirectToRoute("app_type_mouvement");
    } 

    #[Route("/search/{mot}",name:"app_type_mouvement_search",methods:["GET"])]
    public function search(EntityManagerInterface $em,$mot){
        $typeMouvement=$em->getRepository(TypeMouvement::class)->search($mot);
        return $this->render("type_mouvement/index.html.twig",[
            'title'=>'Resultat de recheche',
            'typeMouvement'=>$typeMouvement 
        ]);
    } 

    #[Route('/import/excel', name: 'app_typeMouvement_import_excel', methods: ['GET'])]
    public function importExcel(EntityManagerInterface $em) {
        $filePath='/Users/sorokine/Codding/myProjects/2024/Symphony/typeMouvement.xlsx';
        $r0=2;
        $myFct=new MyFct();
        $datas=$myFct->readExcel($filePath,$r0);
        foreach($datas as $data){
            $prefixe=$data[0];
            $numeroInitial=$data[1];
            $libelle=$data[2];
            $format=$data[3];
            $typeMouvement=$em->getRepository(TypeMouvement::class)->findOneBy(['prefixe'=>$prefixe]);
            if(!$typeMouvement){
                $categorie=new TypeMouvement;
            }
            $categorie->setPrefixe($prefixe);
            $categorie->setNumeroInitial($numeroInitial);
            $categorie->setLibelle($libelle);
            $categorie->setFormat($format);
            $em->persist($typeMouvement);
            $em->flush();
        }
        return $this->redirectToRoute("app_typeMouvement_index");
        }

    #[Route('/export/excel', name: 'app_typeMouvement_export_excel', methods: ['GET'])]
    public function exportExcel(TypeMouvementRepository $ttr) {
        $typeMouvements = $ttr->findBy([], ['prefixe'=>'ASC']);
        $datas[] = [
            'PREFIXE',
            'NUM INITIAL',
            'LIBELLE',
            'FORMAT',
        ];
        foreach($typeMouvements as $typeMouvement) {
            $datas[] = [
                $typeMouvement->getPrefixe(),
                $typeMouvement->getNumeroInitial(),
                $typeMouvement->getLibelle(),
                $typeMouvement->getFormat(),
            ];
        }
        $myFct=new MyFct();
        $filePath = $filePath='/Users/sorokine/Codding/myProjects/2024/Symphony/all_typeMouvement.xlsx';
        $r0 = 1;
        $file = $myFct->writeExcel($datas,$filePath,$r0=1);
        return $this->file($filePath,"Export typeMouvement.xlsx", ResponseHeaderBag::DISPOSITION_INLINE);
    }
}