<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProduitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produit::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('numProduit'),
            TextField::new('designation'),
            MoneyField::new('prixUnitaire')
            ->setCurrency('EUR')
            ->setCustomOption('storedAsCents', false)
            ->formatValue(function ($value, $entity) {
                return sprintf('%s €', number_format($value, 2));
            }),
            MoneyField::new('prixRevient')
            ->setCurrency('EUR')
            ->setCustomOption('storedAsCents', false)
            ->formatValue(function ($value, $entity) {
                return sprintf('%s €', number_format($value, 2));
            }),
            AssociationField::new('categorie'),
            TextEditorField::new('description')->hideOnIndex()
            ->setFormTypeOption('mapped',false),
        ];
    }
}
