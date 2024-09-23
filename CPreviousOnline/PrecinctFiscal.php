
<?php

/**
 * CasaLibraries Classifications class
 * File Classifications.php
 * Connection to posgresql database
 *
 * @category     CasaLibraries
 * @package     CasaLibraries_CasaSkeketon
 * @copyright     Copyright (c) 2005-2013 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author         AJPE
 * @version     Classifications 1.0.0
 *
 */
include 'CasaLibraries/CasaDb/PgsqlQueries.php';

class PrecinctFiscal{

 	public function getPrecinctFiscal($start, $limit, $idUser, $idClient, $idTypeuser, $query) {
       
      //esta surve para ir paginando
        $db = new PgsqlQueries;

        $db->setTable('general.saaic_refis');
        $db->setJoins("");
        $db->setFields(array(
            'id_refi',
            'cve_adua',
            'cve_refi',
            'nom_refi'
        ));

        if (empty($query)) {
            $db->setParameters("id_refi > 1 AND status_refi = 1 ORDER BY id_refi LIMIT $limit OFFSET $start");

        } else {
            $db->setParameters("id_refi > 1 AND status_refi = 1 AND (cve_adua iLIKE SP_ASCII(CONCAT('%$query%')) OR cve_refi iLIKE SP_ASCII(CONCAT('%$query%')) OR nom_refi iLIKE SP_ASCII(CONCAT('%$query%'))) ORDER BY id_refi LIMIT $limit OFFSET $start");
        }
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $response = $db->query();

      //esta surve para sacar el conteo total porque el paginador tiene un total

        $db->setTable('general.saaic_refis');
        $db->setJoins("");
        $db->setFields(array(
            'id_refi',
            'cve_adua',
            'cve_refi',
            'nom_refi'
        ));

        if (empty($query)) {
            $db->setParameters("id_refi > 1 AND status_refi = 1 ORDER BY id_refi");
        } else {
            $db->setParameters("id_refi > 1 AND status_refi = 1 AND (cve_adua iLIKE SP_ASCII(CONCAT('%$query%')) OR cve_refi iLIKE SP_ASCII(CONCAT('%$query%')) OR nom_refi iLIKE SP_ASCII(CONCAT('%$query%'))) ORDER BY id_refi");
        }
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $responseTotal = $db->query();


        
         $response['total'] = count($responseTotal);
   
        if(count($response) > 1) {
            return $response;
        } else {
            return NULL;
        }
        
    }

    public function savePrecinctFiscal($cve_aduana, $cve_refi, $nom_refi){
        $db = new PgsqlQueries;

        $db->setTable('general.saaic_refis');
        $db->setFields(array(
            "id_refi"
        ));
        $where = "cve_adua = ? AND cve_refi = ? AND nom_refi = ?";
        $paramsArray = array($cve_aduana, $cve_refi, $nom_refi);
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ONE);
        $precinctFiscal = $db->queryParametrize($where, $paramsArray);
        
        if (!$precinctFiscal) {
            try {
                $db->setTable('general.saaic_refis');
                $db->setValues(array(
                    "cve_adua" => $cve_aduana,
                    "cve_refi" => $cve_refi,
                    "nom_refi" => $nom_refi,
                    "status_refi" =>  1
                ));
                $precinctFiscals = $db->insert();

                return true;
                exit();
            } catch (Exception $exc) {
                var_dump($exc->getMessage());
                exit();
            }
        }else{
            return false;
        }
    }

    public function deletePrecinctFiscal($idPrecinctFiscal){

        try { 
        	$db = new PgsqlQueries;
        	$db->setTable('general.saaic_refis');
		    $db->setValues(array(
		        "status_refi" => 2
		    ));
		    $db->setParameters("id_refi =  $idPrecinctFiscal");

            return $classification = $db->update();
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }    
    }
	
}
?>