<?php

namespace App\Controller;

use App\Entity\TypeMouvement;
use App\Form\TypeMouvementType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TypeMouvementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
#[Route('/typemouvement')]

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
}