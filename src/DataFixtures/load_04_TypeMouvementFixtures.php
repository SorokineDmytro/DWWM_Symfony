<?php

namespace App\DataFixtures;

use App\Entity\TypeMouvement;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class load_04_TypeMouvementFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //--------------------------------------------TYPE MOUVEMENT-------------------------------------------
        $types = [
            ['prefixe' => 'FACT', 'libelle' => 'Facture', 'numeroInitial' => '1', 'format' => '%05d'], // à referencer par "facture" 
            ['prefixe' => 'DEV', 'libelle' => 'Devis', 'numeroInitial' => '1', 'format' => '%05d'], // à referencer par "devis" 
            ['prefixe' => 'INT', 'libelle' => 'Interne', 'numeroInitial' => '1', 'format' => '%05d'], // à referencer par "interne" 
        ];
        foreach($types as $valeurs) {
            $type = new TypeMouvement;
            foreach($valeurs as $key => $valeur) {
                $set = "set".ucfirst($key);
                $type->$set($valeur);
            }
            $manager->persist($type);
        }
        $manager->flush();

    }
}
