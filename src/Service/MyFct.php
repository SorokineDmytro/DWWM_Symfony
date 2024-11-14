<?php
    namespace App\Service;
    class MyFct {
        // Fonctionne qui va faire des sequences dans la BDD
        public function numeroter($prefixe, $format, $numInitial) {
            return sprintf($prefixe.$format, $numInitial);
        }

        public function createNumEntity($em, $entity) {
            $prefixe = $entity -> getPrefixe();
            $numeroInitial = $entity -> getNumeroInitial();
            $format = $entity -> getFormat();
            //-- creation du numEntity Par exemple : numProduit ="BB0001"
            $numEntity = $this -> numeroter($prefixe, $format, $numeroInitial);
            //-- incrementer numeroInitial
            $numeroInitial++;
            //-- mettre à jour numeroInitial de l'entity sur $entity
            $entity -> setNumeroInitial($numeroInitial);
            //-- creation de reauette de sauvegarde
            $em->persist($entity);
            //-- sauvegarder les données dans la table correspondante
            $em->flush();
            return $numEntity;
        }
    }