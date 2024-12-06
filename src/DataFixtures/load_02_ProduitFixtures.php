<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use App\Entity\Categorie;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class load_02_ProduitFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //--------------------------------------------CATEGORIE-------------------------------------------
        $categories = [
            ['prefixe' => 'BB', 'libelle' => 'Biere', 'numeroInitial' => '1', 'format' => '%05d'], // à referencer par "biere" 
            ['prefixe' => 'BJ', 'libelle' => 'Jus', 'numeroInitial' => '1', 'format' => '%05d'], // à referencer par "jus" 
            ['prefixe' => 'BA', 'libelle' => 'Alcool', 'numeroInitial' => '1', 'format' => '%05d'], // à referencer par "alcool" 
            ['prefixe' => 'BC', 'libelle' => 'Champagne', 'numeroInitial' => '1', 'format' => '%05d'], // à referencer par "champagne"
            ['prefixe' => 'XA', 'libelle' => 'Alimentaire', 'numeroInitial' => '1', 'format' => '%05d'], // à referencer par "alimentaire"
        ];
        foreach($categories as $valeurs) {
            $categorie = new Categorie;
            foreach($valeurs as $key => $valeur) {
                $set = "set".ucfirst($key);
                if($key == 'libelle') {
                    $this->addReference(strtolower($valeur), $categorie); // cela fait referencement par categorie 
                }
                $categorie->$set($valeur);
            }
            $manager->persist($categorie);
        }
        $manager->flush();

        //--------------------------------------------PRODUIT-------------------------------------------
        $produits = [
            ['numProduit' => 'BB001', 'designation' => 'Biere Castel', 'prixUnitaire' => '4.50', 'prixRevient' => '2.25', 'categorie' => 'biere'],
            ['numProduit' => 'BB002', 'designation' => 'Biere Phoenix', 'prixUnitaire' => '3.50', 'prixRevient' => '1.25', 'categorie' => 'biere'],
            ['numProduit' => 'BJ001', 'designation' => 'Jus Ananas', 'prixUnitaire' => '2.50', 'prixRevient' => '1.25', 'categorie' => 'jus'],
            ['numProduit' => 'BA001', 'designation' => 'Whisky Long John', 'prixUnitaire' => '14.50', 'prixRevient' => '10.25', 'categorie' => 'alcool'],
            ['numProduit' => 'XA001', 'designation' => 'Ris Basmati 20kg', 'prixUnitaire' => '34.50', 'prixRevient' => '15.25', 'categorie' => 'alimentaire'],
        ];
        foreach($produits as $valeurs) {
            $produit = new Produit;
            foreach($valeurs as $key => $valeur) {
                $set = "set".ucfirst($key);
                if($key == 'categorie') {
                    $valeur = $this->getReference($valeur);
                }
                $produit->$set($valeur);
            }
            $manager->persist($produit);
        }
        $manager->flush();

    }
}
