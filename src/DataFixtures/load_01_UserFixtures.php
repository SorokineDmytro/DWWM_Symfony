<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class load_01_UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;
    public function __construct(UserPasswordHasherInterface $myHasher){
        $this->hasher=$myHasher;
    }

    public function load(ObjectManager $manager): void
    {
        //--------------------------------------------ROLE-------------------------------------------
        $roles = [
            ['rang' => '01', 'code' => 'ROLE_ADMIN', 'libelle' => 'Administrateur'],
            ['rang' => '02', 'code' => 'ROLE_ASSISTANT', 'libelle' => 'Asistant de direction'],
            ['rang' => '03', 'code' => 'ROLE_INFORMATIQUE', 'libelle' => "Responsable d'informatique"],
            ['rang' => '04', 'code' => 'ROLE_DEPOT', 'libelle' => 'Approvisionneur'],
            ['rang' => '05', 'code' => 'ROLE_CAISSE', 'libelle' => 'Vendeur'],
            ['rang' => '06', 'code' => 'ROLE_USER', 'libelle' => 'Visiteur'],
        ];
        foreach($roles as $valeurs) {
            $role = new Role;
            foreach($valeurs as $key => $valeur) {
                $set = "set".ucfirst($key); // si $key = 'code' alors $set = setCode
                $role->$set($valeur);
            }
            $manager->persist($role);
        }
        $manager->flush();

        //--------------------------------------------USER-------------------------------------------
        $users = [
            ['username' => 'admin', 'password' => '123456', 'roles' => ["ROLE_ADMIN", "ROLE_ASSISTANT", "ROLE_INFORMATIQUE", "ROLE_DEPOT", "ROLE_CAISSE", "ROLE_USER"]],
            ['username' => 'paul', 'password' => '123456', 'roles' => ["ROLE_DEPOT", "ROLE_USER"]],
            ['username' => 'marie', 'password' => '123456', 'roles' => ["ROLE_CAISSE", "ROLE_USER"]],
            
        ];
        foreach($users as $valeurs) {
            $user = new User;
            foreach($valeurs as $key => $valeur) {
                $set = "set".ucfirst($key);
                if($key == 'password') {
                    $valeur = $this->hasher->hashPassword($user, $valeur);
                }
                $user->$set($valeur);
            }
            $manager->persist($user);
        }
        $manager->flush();

    }
}
