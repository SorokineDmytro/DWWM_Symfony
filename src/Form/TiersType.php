<?php

namespace App\Form;

use App\Entity\Tiers;
use App\Entity\TypeTiers;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TiersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numTiers', TextType::class, [
                'label' => 'N° Tiers',
                'label_attr' => ['class' => 'lab30'],
                'attr' => ['class' => 'form-control w70 mt-2', 'placeholder' => 'Gérée automatiquement à la création'],
                'required' => false,
            ])
            ->add('nomTiers', TextType::class, [
                'label' => 'Nom Tiers',
                'label_attr' => ['class' => 'lab30'],
                'attr' => ['class' => 'form-control w70 mt-2']
            ])
            ->add('adresseTiers', TextType::class, [
                'label' => 'Adresse Tiers',
                'label_attr' => ['class' => 'lab30'],
                'attr' => ['class' => 'form-control w70 mt-2']
            ])
            ->add('typeTiers', EntityType::class, [
                'label' => 'TypeTiers',
                'class' => TypeTiers::class,
                // 'choice_label' => 'libelle',
                'label_attr' => ['class' => 'lab30'],
                'attr' => ['class' => 'form-select w70 text-end mt-2']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tiers::class,
        ]);
    }
}
