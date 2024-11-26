<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Form\UserType;
use App\Service\MyFct;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route("/admin/user")]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user')]
    public function index(UserRepository $ur): Response
    {
        $users = $ur->findBy([], ["username"=>"ASC"]);
        return $this->render('user/index.html.twig', [
            'title' => 'Liste des utilisateurs',
            'users' => $users,
        ]);
    }

    #[Route("/edit/{id}",name:"app_user_edit",methods:["GET","POST"])]
    public function edit($id, EntityManagerInterface $em, UserPasswordHasherInterface $userPasswordHasher, Request $request){
        $id = (int) $id;
        if($id) {
            $user = $em->getRepository(User::class)->find($id);
            $title = 'Modification User';
        } else {
            $user=new User;
            $title = 'Creation User';
        }
        $roles=$em->getRepository(Role::class)->findBy([],["rang"=>'ASC']);
        $data_roles=[];
        foreach($roles as $role){
            $key=$role->getCode();
            $data_roles[$key]=$key;
        }
        $form=$this->createForm(UserType::class,$user);
        $form
        ->add('username',TextType::class,[
            'label'=>'Identifiant ',
            'label_attr'=>['class'=>'lab30 obligatoire'],
            'attr'=>['class'=>'form-control w70 mt-2'],
        ])
        ->add('plainPassword',PasswordType::class,[
            'label'=>'Password ',
            'label_attr'=>['class'=>'lab30'],
            'attr'=>['class'=>'form-control w70 mt-2', 'placeholder' => "Ne rien saisir en cas de modification pour garder l'ancienne valeur"],
            'mapped'=>false , // dire à symfony de ne pas persistrer sur cette propriété
            'required'=>false, // dire à symfony que cette propriété n'est pas obligatoire
        ])
        ->add("roles",ChoiceType::class,[
            'choices'=>$data_roles,
            'label'=>'Roles',
            'label_attr'=>['class'=>'lab30'],
            'attr'=>['class'=>'form-select w70 mt-2'],   
            'multiple'=>true,         
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            // recuperation de plain password
            $plainPassword = $form->get('plainPassword')->getData();
            if($plainPassword) {
                // encode the plain password
                $password = $userPasswordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($password);
            }
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_user');
        }
        return $this->render("user/form.html.twig",[
            'title'=>$title,
            'form'=>$form,
        ]);
    } 

    #[Route("/show/{id}",name:"app_user_show",methods:["GET"])]
    public function show(User $user, EntityManagerInterface $em, Request $request){
        return $this->render("user/show.html.twig", [
            'title' => 'Affichage User',
            'user' => $user,
        ]);
    }

    #[Route("/delete/{id}", name: "app_user_delete", methods: ['GET'])]
    public function delete(User $user, EntityManagerInterface $em,) {
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('app_user');
    }

}
