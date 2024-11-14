<?php

namespace App\Form;

use App\Entity\Categorie;
use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Text;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prefixe', TextType::class, [
                'label' => 'Préfixe',
                'label_attr' => ['class' => 'lab30 obligatoire'],
                'attr' => ['
                class' => 'form-control w70',
                'autocomplete' => 'off'
                ]
            ])
            ->add('libelle', TextType::class, [
                'label' => 'Libellé',
                'label_attr' => ['class' => 'lab30 obligatoire'],
                'attr' => [
                    'class' => 'form-control w70', 
                    'autocomplete' => 'off'
                    ]
                    ])
            ->add('numeroInitial' , TextType::class, [
                'label' => 'N° Initial',
                'label_attr' => ['class' => 'lab30'],
                'attr' => [
                    'class' => 'form-control w70',
                    'autocomplete' => 'off'
                ],
                'required' => false
                    ])
            ->add('format', TextType::class, [
                'label' => 'Format',
                'label_attr' => ['class' => 'lab30 obligatoire'],
                'attr' => [
                    'class' => 'form-control w70'
                    ]
                    ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
