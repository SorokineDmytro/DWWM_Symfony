<?php

namespace App\Controller\Admin;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{

    private UserPasswordHasherInterface $passwordHasher;
    private EntityManagerInterface $em; 

    public function __construct(UserPasswordHasherInterface $passwordHasher,EntityManagerInterface $em)
    {
        $this->passwordHasher = $passwordHasher;
        $this->em=$em;
    }
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
    if ($entityInstance instanceof User && $entityInstance->getPlainPassword()) {
        // Hasher le mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword(
            $entityInstance,
            $entityInstance->getPlainPassword()
        );
        $entityInstance->setPassword($hashedPassword); // Stocker le hash
        $entityInstance->setPlainPassword(null); // Nettoyer le champ temporaire
    }
    parent::persistEntity($entityManager, $entityInstance);
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        $roles=$this->em->getRepository(Role::class)->findAll();
        $dataRoles=[];
        foreach($roles as $role){
            $key=$role->getCode();
            $dataRoles[$key]=$key;
        }
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('username','USERNAME'),
            TextField::new('plainPassword', 'Mot de passe')
            ->hideOnIndex()
            ->setFormTypeOption('required',false)
            ->setFormType(PasswordType::class) // Obligatoire pour la création
            ->setHelp('Laissez vide pour ne pas changer le mot de passe (en édition).'),
            ChoiceField::new('roles','ROLES')
            ->setChoices($dataRoles)
            // ->setChoices([
            //     'ROLE_ADMIN'=>'ROLE_ADMIN',
            //     'ROLE_INFORMATIQUE'=>'ROLE_INFORMATIQUE',
            //     'ROLE_ASSINTANT'=>'ROLE_ASSINTANT',
            //     'ROLE_DEPOT'=>'ROLE_DEPOT',
            //     'ROLE_CAISSE'=>'ROLE_CAISSE',
            //     'ROLE_USER'=>'ROLE_USER',
            //     'ROLE_VENTE'=>'ROLE_VENTE',
            // ])
            ->allowMultipleChoices()
            ->renderExpanded()
        ];
    }
    
}