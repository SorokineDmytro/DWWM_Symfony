<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $passwordHasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
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
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('username','USERNAME'),
            TextField::new('password', 'Mot de passe')
            ->setFormTypeOption('required', $pageName === Crud::PAGE_NEW) // Obligatoire pour la création
            ->setFormType(PasswordType::class) // Obligatoire pour la création
            ->setHelp('Laissez vide pour ne pas changer le mot de passe (en édition).'),
        ]; 
    }
}
