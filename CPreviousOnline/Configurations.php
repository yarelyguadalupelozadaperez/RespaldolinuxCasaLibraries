<?php

include 'CasaLibraries/CasaDb/PgsqlQueries3.php';
include 'CasaLibraries/CasaDb/PgsqlQueries.php';
require_once 'CasaLibraries/ExportationReports/ExportToExcel.php';


class Configurations {
    
    public function selectAllImporters($idClient, $id_aduana) {
        $db = new PgsqlQueries;
        
        $db->setTable("'general'.casac_importadores I");
        $db->setJoins("INNER JOIN 'general'.casag_licencias L ON I.id_cliente = L.id_cliente");
        $db->setJoin("INNER JOIN 'general'.casac_aduanas A ON L.id_aduana = A.id_aduana");
        $db->setFields(array(
            "DISTINCT(I.id_importador)",
            "I.nombre_importador",
            "I.id_cliente",
            "A.id_aduana"
        ));
        
        if($idTypeuser == 1){
            $db->setParameters("I.id_cliente = $idClient AND A.id_aduana = $id_aduana");
        } else {
            $db->setParameters("I.id_cliente = $idClient AND A.id_aduana = $id_aduana");
        }
        
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $importers = $db->query();
        
        $db->setTable("'general'.casac_importadores_aduana IA");
        $db->setJoins("INNER JOIN 'general'.casac_importadores I ON IA.id_importador = I.id_importador");
        $db->setJoin("INNER JOIN 'general'.casac_aduanas A ON IA.id_aduana = A.id_aduana");
        $db->setFields(array(
            "IA.id_importador",
            "IA.id_aduana"
            
        ));
        $db->setParameters("I.id_cliente = $idClient AND A.id_aduana = $id_aduana");
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $importerscustoms= $db->query();
        
        $tempArray = Array();
        
        foreach ($importers as $importer) {
            $tempArray2 = Array();
            $tempArray2 = $importer;

            if(count($importerscustoms) > 0){
                foreach ($importerscustoms as $importercustom) {
                    if ($importercustom["id_importador"] == $importer["id_importador"]) {
                        $tempArray2               = $importer;
                        $tempArray2["selected"]   = 1;
                        $tempArray2["id_aduana"]  = $id_aduana;
                    }
                }
            }
            
            if (!isset($tempArray2["selected"])) {
                $db->setTable("general.casac_importadores_aduana");
                $db->setValues(array(
                    "id_importador" => $importer["id_importador"],
                    "id_aduana"     => $id_aduana
                ));
                
                $importersAll = $db->insert();
            }
        }
    }
    
    public function importersUpdate($data, $id_aduana) {
        $db = new PgsqlQueries;
        
        try {
            if ($data->selected == true) {
                $db->setTable("general.casac_importadores_aduana");
                $db->setValues(array(
                    "id_importador" => $data->id_importador,
                    "id_aduana"     => $id_aduana
                ));
                
                $importers = $db->insert();
       
            } else if($data->selected == false) {
                $db->setTable("general.casac_importadores_aduana");
                $db->setParameters("id_importador = $data->id_importador");
                $importers = $db->delete();
            }

            return true;
            
        } catch (\Exception $e) {
            return false;
        }
    }
    
    public function getImporters($idTypeuser, $idClient, $id_aduana, $query) {
        $db = new PgsqlQueries;
   
        if($id_aduana == null){
            $id_aduana = 0;
        }else {
            $db->setTable("'general'.casac_importadores I");
            $db->setJoins("INNER JOIN 'general'.casag_licencias L ON I.id_cliente = L.id_cliente");
            $db->setJoin("INNER JOIN 'general'.casac_aduanas A ON L.id_aduana = A.id_aduana");
            $db->setFields(array(
                "DISTINCT(I.id_importador)",
                "I.nombre_importador",
                "I.id_cliente",
                "A.id_aduana"
            ));
            
            if($query != null){
                $db->setParameters("I.id_cliente = $idClient AND A.id_aduana = $id_aduana  AND (SP_ASCII(I.nombre_importador) iLIKE '%$query%') ORDER BY I.id_importador ASC");
            } else{
                $db->setParameters("I.id_cliente = $idClient AND A.id_aduana = $id_aduana ORDER BY I.id_importador ASC");
            }
        
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $importers = $db->query();

            $db->setTable("'general'.casac_importadores_aduana IA");
            $db->setJoins("INNER JOIN 'general'.casac_importadores I ON IA.id_importador = I.id_importador");
            $db->setJoin("INNER JOIN 'general'.casac_aduanas A ON IA.id_aduana = A.id_aduana");
            $db->setFields(array(
                "IA.id_importador",
                "IA.id_aduana"

            ));
            $db->setParameters("I.id_cliente = $idClient AND A.id_aduana = $id_aduana");
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $importerscustoms= $db->query();

            $tempArray = Array();
            $count     = 0;

            foreach ($importers as $importer) {
                $tempArray2 = Array();
                $tempArray2 = $importer;

                if(count($importerscustoms) > 0){
                    foreach ($importerscustoms as $importercustom) {
                        if ($importercustom["id_importador"] == $importer["id_importador"]) {
                            $tempArray2                          = $importer;
                            $tempArray2["selected"]              = 1;
                            $tempArray2["id_aduana"]             = $id_aduana;
                            $tempArray2["id_importador_aduana"]  = $count;
                        }
                    }
                }

                if (!isset($tempArray2["selected"])) {
                    $tempArray2["selected"]             = 0;
                    $tempArray2["id_aduana"]            = $id_aduana;
                    $tempArray2["id_importador_aduana"] = $count;
                }

                $count++;
                $tempArray[] = $tempArray2; 
            }

            if(count($tempArray) > 0){
                return $tempArray;
            } else {
                return NULL;
            }
        }
    }
    
    public function getCustoms($idUser, $idTypeuser, $idClient) {
        $db = new PgsqlQueries;
        
        $db->setTable("'general'.casac_aduanas A");
        $db->setJoins("INNER JOIN 'general'.casag_licencias L ON A.id_aduana = L.id_aduana");
        $db->setJoin("INNER JOIN 'general'.casac_clientes C ON L.id_cliente = C.id_cliente");
        $db->setFields(array(
            "DISTINCT(A.id_aduana)",
            "A.clave_aduana",
            "A.nombre_aduana",
            "C.id_cliente"
        ));
        
        if($idTypeuser == 1){
            $db->setParameters("C.id_cliente = $idClient");
        } else {
            $db->setParameters("C.id_cliente = $idClient");
        }
        
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $customs = $db->query();
        
        if(count($customs) > 0) {
            return $customs;
        } else {
            return NULL;
        }
    }
    
}



?>

