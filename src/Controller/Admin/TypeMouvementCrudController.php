<?php

namespace App\Controller\Admin;

use App\Entity\TypeMouvement;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class TypeMouvementCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TypeMouvement::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('prefixe', 'PREFIXE'),
            TextField::new('libelle', 'LIBELLE'),
            NumberField::new('numeroInitial', 'NUM INITIAL'),
            TextField::new('format', 'FORMAT'),
            AssociationField::new('typeTiers', 'TYPE TIERS'),
        ];
    }

}
