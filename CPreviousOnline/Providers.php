
<?php

/**
 * CasaLibraries Providers class
 * File Providers.php
 * Connection to posgresql database
 *
 * @category     CasaLibraries
 * @package     CasaLibraries_CasaSkeketon
 * @copyright     Copyright (c) 2005-2013 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author         AJPE
 * @version     Providers 1.0.0
 *
 */
include 'CasaLibraries/CasaDb/PgsqlQueries.php';
 
class Providers{

	public function savePatentAduana($id_cliente, $aduanast, $patente){
		
	}

	public function getProviders($start, $limit, $idUser, $idClient, $idTypeuser, $query) {
       
        $db = new PgsqlQueries;

       if (empty($query)) {
           
            $db->setTable('general.casac_proveedores');
            $db->setJoins('');
            $db->setFields(array(
                '*'
            ));
            $db->setParameters("id_cliente = $idClient AND imp_exp = 1 AND status_prov != 2 AND (SP_ASCII(cve_prov) iLIKE SP_ASCII(CONCAT('%$query%')) OR SP_ASCII(nom_prov) iLIKE SP_ASCII(CONCAT('%$query%')) OR SP_ASCII(tax_pro) iLIKE SP_ASCII(CONCAT('%$query%'))) ORDER BY id_proveedor LIMIT $limit OFFSET $start");
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $response = $db->query();

          //esta surve para sacar el conteo total porque el paginador tiene un total

            $db->setTable('general.casac_proveedores');
            $db->setJoins('');
            $db->setFields(array(
                '*'
            ));
            $db->setParameters("id_cliente = $idClient AND imp_exp = 1 AND status_prov != 2 AND (SP_ASCII(cve_prov) iLIKE SP_ASCII(CONCAT('%$query%')) OR SP_ASCII(nom_prov) iLIKE SP_ASCII(CONCAT('%$query%')) OR SP_ASCII(tax_pro) iLIKE SP_ASCII(CONCAT('%$query%'))) ORDER BY id_proveedor");
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $responseTotal = $db->query();
            
             $response['total'] = count($responseTotal);
       
            if(count($response) > 1) {
                return $response;
            } else {
                return NULL;
            }
       } else {

            $db->setTable('general.casac_proveedores');
            $db->setJoins('');
            $db->setFields(array(
                '*'
            ));
            $db->setParameters("id_cliente = $idClient AND imp_exp = 1 AND status_prov != 2 AND (SP_ASCII(cve_prov) iLIKE SP_ASCII(CONCAT('%$query%')) OR SP_ASCII(nom_prov) iLIKE SP_ASCII(CONCAT('%$query%')) OR SP_ASCII(tax_pro) iLIKE SP_ASCII(CONCAT('%$query%'))) ORDER BY id_proveedor LIMIT $limit OFFSET $start");
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $response = $db->query();

          //esta surve para sacar el conteo total porque el paginador tiene un total

            $db->setTable('general.casac_proveedores');
            $db->setJoins('');
            $db->setFields(array(
                '*'
            ));
            $db->setParameters("id_cliente = $idClient AND imp_exp = 1 AND status_prov != 2 AND (SP_ASCII(cve_prov) iLIKE SP_ASCII(CONCAT('%$query%')) OR SP_ASCII(nom_prov) iLIKE SP_ASCII(CONCAT('%$query%')) OR SP_ASCII(tax_pro) iLIKE SP_ASCII(CONCAT('%$query%'))) ORDER BY id_proveedor");
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $responseTotal = $db->query();


            
             $response['total'] = count($responseTotal);
       
            if(count($response) > 1) {
                return $response;
            } else {
                return NULL;
            }
       }     
    }
}
?>