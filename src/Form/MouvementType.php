<?php

namespace App\Form;

use App\Entity\Mouvement;
use App\Entity\Tiers;
use App\Entity\TypeMouvement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Date;

class MouvementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numMouvement', TextType::class, [
                'label' => 'N° Mouvement',
                'label_attr' => ['class' => 'lab30'],
                'attr' => ['class' => 'form-control w70 mt-2', 'placeholder' => 'Gérée automatiquement à la création'],
                'required' => false,
            ])
            ->add('dateMouvement', DateType::class, [
                'label' => 'Date Mouvement',
                'label_attr' => ['class' => 'lab30'],
                'attr' => ['class' => 'form-control w70 mt-2'],
            ])
            ->add('typeMouvement', EntityType::class, [
                'class' => TypeMouvement::class,
                'label' => 'Type Mouvement',
                'label_attr' => ['class' => 'lab30'],
                'choice_label' => 'libelle',
                'attr' => ['class' => 'form-select w70 mt-2'],
            ])
            ->add('tiers', EntityType::class, [
                'class' => Tiers::class,
                'label_attr' => ['class' => 'lab30'],
                'choice_label' => 'nomTiers',
                'attr' => ['class' => 'form-select w70 mt-2'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mouvement::class,
        ]);
    }
}
