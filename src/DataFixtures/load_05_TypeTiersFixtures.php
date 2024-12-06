<?php

namespace App\DataFixtures;

use App\Entity\TypeTiers;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class load_05_TypeTiersFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //--------------------------------------------TYPE TIERS-------------------------------------------
        $types = [
            ['prefixe' => 'CLT', 'libelle' => 'Client', 'numeroInitial' => '1', 'format' => '%05d'], 
            ['prefixe' => 'FRS', 'libelle' => 'Fournisseur', 'numeroInitial' => '1', 'format' => '%05d'], 
        ];
        foreach($types as $valeurs) {
            $type = new TypeTiers;
            foreach($valeurs as $key => $valeur) {
                $set = "set".ucfirst($key);
                $type->$set($valeur);
            }
            $manager->persist($type);
        }
        $manager->flush();

    }
}
