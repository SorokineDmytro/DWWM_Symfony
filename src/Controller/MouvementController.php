<?php

namespace App\Controller;

use App\Service\MyFct;
use App\Entity\Mouvement;
use App\Form\MouvementType;
use App\Repository\MouvementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MouvementController extends AbstractController
{
    #[Route('/mouvement', name: 'app_mouvement')]
    public function index(MouvementRepository $mouvementRepository): Response
    {
        $mouvements = $mouvementRepository->findAll();
        return $this->render('mouvement/index.html.twig', [
            'mouvements' => $mouvements,
            'title' => 'Liste mouvements',
        ]);
    }

    #[Route('/mouvement/new', name: 'app_mouvement_new', methods: ['GET', 'POST'])]

    public function new(Request $request, EntityManagerInterface $em) {
        $mouvement = new Mouvement();
        $form = $this->createForm(MouvementType::class, $mouvement);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $myFct = new MyFct();
            $typeMouvement = $form->get('typeMouvement')->getData();
            $numMouvement = $myFct->createNumEntity($em, $typeMouvement); 
            $mouvement->setNumMouvement($numMouvement);
            $em->persist($mouvement);
            $em->flush();
            return $this->redirectToRoute('app_mouvement');
        }
        
        return $this->render('mouvement/form.html.twig', [
            'title' => 'Creation mouvement',
            'form' => $form->createView(),
        ]);
    } 

    #[Route("/mouvement/show/{id}",name:"app_mouvement_show",methods:["GET"])]
    public function show(MouvementRepository $mr, $id){
        $mouvement=$mr->find($id);
        $file="mouvement/show.html.twig";
        $variables=[
            'title'=>'Affichage mouvement',
            'mouvement'=>$mouvement,
        ];
        return $this->render($file,$variables);

    } 

    #[Route('/mouvement/edit/{id}', name: 'app_mouvement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mouvement $mouvement, EntityManagerInterface $em) {
        $form = $this->createForm(MouvementType::class, $mouvement);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($mouvement);
            $em->flush();
            return $this->redirectToRoute('app_mouvement');
        }
        
        return $this->render('mouvement/form.html.twig', [
            'title' => 'Edition mouvement',
            'form' => $form->createView(),
        ]);
    }

    #[Route("/mouvement/delete/{id}",name:"app_mouvement_delete",methods:["GET"])]
    public function delete(EntityManagerInterface $em,$id){
        $mouvement = $em->getRepository(Mouvement::class)->find($id);
        $em->remove($mouvement);
        $em->flush();
        return $this->redirectToRoute("app_mouvement");
    } 
}
