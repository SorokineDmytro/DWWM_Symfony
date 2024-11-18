<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numProduit', TextType::class, [
                'label' => 'N° Produit',
                'label_attr' => ['class' => 'lab30'],
                'attr' => ['class' => 'form-control w70 mt-2', 'placeholder' => 'Gérée automatiquement à la création'],
                'required' => false,
            ])
            ->add('designation', TextType::class, [
                'label' => 'Désignation',
                'label_attr' => ['class' => 'lab30'],
                'attr' => ['class' => 'form-control w70 mt-2']
            ])
            ->add('prixUnitaire', TextType::class, [
                'label' => 'PU',
                'label_attr' => ['class' => 'lab30'],
                'attr' => ['class' => 'form-control w70 text-end mt-2']
            ])
            ->add('prixRevient', TextType::class, [
                'label' => 'PR',
                'label_attr' => ['class' => 'lab30'],
                'attr' => ['class' => 'form-control w70 text-end mt-2']
            ])
            ->add('categorie', EntityType::class, [
                'label' => 'Categorie',
                'class' => Categorie::class,
                // 'choice_label' => 'libelle',
                'label_attr' => ['class' => 'lab30'],
                'attr' => ['class' => 'form-select w70 text-end mt-2']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
