<?php
require_once 'CasaLibraries/CasaDb/PgsqlConnection.php';

class licenceSistema
{
    public function licenceSistema()
    {
            $sistema = 2;
            $idCliente = 952;
            $db = new PgsqlConnection();
            
           $table = "'general'.casag_licencias";
            $joins = "";
            $fieldsArray = array(
                "id_licencia"
            );
            $parameters = "id_cliente = $idCliente";
            $array = $db->query($table, $fieldsArray, $joins, $parameters);

            
           foreach ($array as $value){
                $idLicence = $value["id_licencia"];
                
                $table = "'general'.casag_licenciasistema";
                $joins = "";
                $fieldsArray = array(
                    "id_licenciasistema"
                );
                $parameters = "id_licencia = $idLicence AND id_sistema = 1";
                $arraylicenseSistema = $db->query($table, $fieldsArray, $joins, $parameters);
                
                if(!$arraylicenseSistema){
                    $table = "general.casag_licenciasistema";
                    $valuesArray = Array(
                        "id_licencia" => $idLicence,
                        "id_sistema" => 2
                        );
                    $insertimporter = $db->insert($table, $valuesArray);
                } 
            }

            return true;
            exit;
    }

}
