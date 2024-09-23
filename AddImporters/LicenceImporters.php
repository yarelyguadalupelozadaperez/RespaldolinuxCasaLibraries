<?php
require_once 'CasaLibraries/CasaDb/PgsqlConnection.php';

class LicenceImporters
{
    public function licenceImporters()
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
                $parameters = "id_licencia = $idLicence AND id_sistema = $sistema";
                $arraylicenseSistema = $db->query($table, $fieldsArray, $joins, $parameters);

                
                if($arraylicenseSistema){
                    foreach ($arraylicenseSistema as $arraylicenseSist){
             
                        $idlicenciasistema = $arraylicenseSist["id_licenciasistema"];
                        $table = "'general'.casac_importadores";
                        $joins = "";
                        $fieldsArray = array(
                            "id_importador"
                        );
                        $parameters = "id_cliente = $idCliente";
                        $arrayimporters = $db->query($table, $fieldsArray, $joins, $parameters);

                        $count = 0;
                        foreach ($arrayimporters as $importer){
                            $idImporter = $importer["id_importador"];

                            $table = "'general'.casag_licenciasimportador";
                            $joins = "";
                            $fieldsArray = array(
                                "id_licenciasimportador"
                            );
                            $parameters = "id_licenciasistema = $idlicenciasistema AND id_importador = $idImporter";
                            $arraylicenseSistema = $db->query($table, $fieldsArray, $joins, $parameters);

                            if(!$arraylicenseSistema){
                                $table = "general.casag_licenciasimportador";
                                $valuesArray = Array(
                                    "id_licenciasistema" => $idlicenciasistema,
                                    "id_importador" => $idImporter
                                    );
                                $insertimporter = $db->insert($table, $valuesArray);
                                $count++;
                            }
                        }
                    }
                  
                
               

         
                }
            }

            return true;
            exit;
    }

}
