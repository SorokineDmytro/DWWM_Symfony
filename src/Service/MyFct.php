<?php
    namespace App\Service;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    class MyFct {

        public function writeExcel($datas,$filePath,$r0=1){
            $spreadsheet=new Spreadsheet();
            $sheet=$spreadsheet->getActiveSheet();
            $r=$r0;
            $colonnes=$this->excelColonnes();
            foreach($datas as $data){
                $c=0;
                foreach($data as $key=>$d){
                    $colonne=$colonnes[$c];
                    $cell="$colonne$r";
                    $sheet->setCellValue($cell,$d);
                    $c++;
                }
                $r++;
            }
            $writer=new Xlsx($spreadsheet);
            $writer->save($filePath);
            return $filePath;
        } 

        public function excelColonnes(){
            $bases=array();
            $colonnes=array();
            for ($i=65;$i<=90;$i++){
                $bases[]=chr($i);
                $colonnes[]=chr($i);
            }
            $k=1;
            foreach($bases as $base){
                for ($i=65;$i<=90;$i++){
                    $colonnes[]=$base.chr($i);
                    $k++;
                }
                if ($k>=400){
                    break ;
                }
            }
            return $colonnes;
            }
            

        public function readExcel($filePath,$r0=1){
            $spreadsheet=IOFactory::load($filePath);
            $sheet=$spreadsheet->getActiveSheet();
            $datas=[];
            foreach($sheet->getRowIterator($r0) as $row){
                $rowData=[];
                foreach($row->getCellIterator() as $cell){
                    $rowData[]=$cell->getValue();
                }
                $datas[]=$rowData;
            }
            return $datas;
        } 

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

        function printr($array){
            echo "<pre>";
            print_r($array);
            echo "</pre>";
            die;
        } 
    }