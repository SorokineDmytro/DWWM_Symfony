<?php

namespace App\DataFixtures;

use App\Entity\Menu;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class load_03_MenuFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //--------------------------------------------MENU-------------------------------------------
        $menus = [
            ['rang' => '01-00', 'libelle' => 'Accueil', 'url' => '/', 'role' => 'ROLE_USER'],
            ['rang' => '02-00', 'libelle' => 'Produit', 'url' => '/depot/produit', 'role' => 'ROLE_USER'],
            ['rang' => '03-00', 'libelle' => 'Tiers', 'url' => '/vente/tiers', 'role' => 'ROLE_USER'],
            ['rang' => '04-00', 'libelle' => 'Mouvement', 'url' => '/mvt', 'role' => 'ROLE_USER'],
            ['rang' => '05-00', 'libelle' => 'Parametre', 'url' => '/parametre', 'role' => 'ROLE_USER'],
        ];
        foreach($menus as $valeurs) {
            $menu = new Menu;
            foreach($valeurs as $key => $valeur) {
                $set = "set".ucfirst($key);
                $menu->$set($valeur);
                if($key == 'libelle') {
                    $this->addReference(strtolower($valeur), $menu); // cela fait referencement par libelle 
                }
            }
            $manager->persist($menu);
        }
        $manager->flush();

                //--------------------------------------------SOUS-MENU-------------------------------------------
        $sousMenus = [
            ['rang' => '05-01', 'libelle' => 'Role', 'url' => '/admin/role', 'role' => 'ROLE_USER', 'parent' => 'parametre'],
            ['rang' => '05-02', 'libelle' => 'User', 'url' => '/admin/user', 'role' => 'ROLE_USER', 'parent' => 'parametre'],
            ['rang' => '05-03', 'libelle' => 'Type Tiers', 'url' => '/admin/typetiers', 'role' => 'ROLE_USER', 'parent' => 'parametre'],
            ['rang' => '05-04', 'libelle' => 'Type Mouvement', 'url' => '/admin/typemouvement', 'role' => 'ROLE_USER', 'parent' => 'parametre'],
            ['rang' => '05-05', 'libelle' => 'Categorie', 'url' => '/admin/categorie', 'role' => 'ROLE_USER', 'parent' => 'parametre'],
            ['rang' => '05-06', 'libelle' => 'Administration', 'url' => '/admin', 'role' => 'ROLE_USER', 'parent' => 'parametre'],
            ['rang' => '05-07', 'libelle' => 'Menu', 'url' => '/menu', 'role' => 'ROLE_USER', 'parent' => 'parametre'],
        ];
        foreach($sousMenus as $valeurs) {
            $sousMenu = new Menu;
            foreach($valeurs as $key => $valeur) {
                $set = "set".ucfirst($key);
                if($key == 'parent') {
                    $valeur = $this->getReference(strtolower($valeur));
                }
                $sousMenu->$set($valeur);
            }
            $manager->persist($sousMenu);
        }
        $manager->flush();
    
    }
}
