<?php

namespace App\Controller\Admin;

use App\Entity\Tiers;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TiersCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tiers::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('numTiers', 'NUM TIERS'),
            TextField::new('nomTiers', 'NOM TIERS'),
            TextField::new('adresseTiers', 'ADRESSE TIERS'),
            AssociationField::new('typeTiers', 'TYPE TIERS')
            ->setFormTypeOption('mapped',false),
        ];
    }

}
