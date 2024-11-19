<?php

namespace App\Controller;

use App\Service\MyFct;
use App\Form\MvtType;
use App\Entity\Mouvement;
use Doctrine\ORM\EntityManager;
use App\Repository\MouvementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MvtController extends AbstractController
{
    #[Route('/mvt', name: 'app_mvt')]
    public function index(MouvementRepository $mr): Response
    {
        $mouvements=$mr->findBy([],['id'=>'desc']);
        return $this->render('mvt/index.html.twig', [
            'title'=>'Liste Mouvements',
            'mouvements'=>$mouvements,
        ]);
    } 

    #[Route('/mvt/new', name: 'app_mvt_new', methods:["GET", "POST"])]
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

    #[Route('mvt/show/{id}', name:"app_mvt_show", methods:["GET"])]
    public function show(Mouvement $mouvement) {
        $form = $this->createForm(MvtType::class, $mouvement);
        return $this->render("mvt/form.html.twig", [
            'title' => 'Affichage Mouvement',
            'form' => $form,
            'disabled' => true,
        ]);
    }

    #[Route('mvt/update/{id}', name:"app_mvt_update", methods:["GET", "POST"])]
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

    #[Route('mvt/delete/{id}', name:"app_mvt_delete", methods:["GET"])]
    public function delete(Mouvement $mouvement, EntityManagerInterface $em, $id) {
        // $mouvement = $em->getRepository(Mouvement::class)->find($id); //sans injectin de dépendance
        $em->remove($mouvement);
        $em->flush();
        return $this->redirectToRoute("app_mvt");
    }

    #[Route("/mvt/search",name:"app_mvt_search_fetch",methods:['GET','POST'])]
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
}
