<?php

namespace App\Form;

use App\Entity\Mouvement;
use App\Entity\Tiers;
use App\Entity\TypeMouvement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MvtType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numMouvement')
            ->add('dateMouvement', null, [
                'widget' => 'single_text',
            ])
            ->add('typeMouvement', EntityType::class, [
                'class' => TypeMouvement::class,
                'choice_label' => 'libelle',
            ])
            ->add('tiers', EntityType::class, [
                'class' => Tiers::class,
                'choice_label' => 'nomTiers',
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
