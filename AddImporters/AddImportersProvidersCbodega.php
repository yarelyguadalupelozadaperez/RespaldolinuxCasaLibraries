<?php
require_once 'CasaLibraries/CasaDb/PgsqlConnection.php';

class AddImportersProvidersCbodega
{
    public function AddImportersProvidersCbodega()
    {
            $sistema = 2;
            $idCliente = 952;
            $db = new PgsqlConnection();
            
            $table = "cbodega.users";
            $joins = "";
            $fieldsArray = array(
                "id"
            );
            $parameters = "idclient = $idCliente AND idusertype = 2";
            $users = $db->query($table, $fieldsArray, $joins, $parameters);
           
            
            foreach($users as $user){
                
                $userId = $user["id"];
                $table = "cbodega.userimporters";
                $joins = "";
                $fieldsArray = array(
                    "id",
                    "importer_id",
                    "user_id"
                    
                );
                $parameters = "user_id = $userId";
                $arrayUserImporter = $db->query($table, $fieldsArray, $joins, $parameters);
                
                if( count($arrayUserImporter) > 0){
                    
                    foreach($arrayUserImporter as $userimporter){
                         
                        $idImporter = $userimporter["importer_id"];
                        if($idImporter > 0){
                            $table = "cbodega.ctrac_correl";
                            $joins = "";
                            $fieldsArray = array(
                                "id",
                                "cve_imp",
                                "cve_pro"

                            );
                            $parameters = "cve_imp = $idImporter AND idclient = $idCliente";
                            $arrayCorrelAll = $db->query($table, $fieldsArray, $joins, $parameters);

                            if($arrayCorrelAll){
                                foreach($arrayCorrelAll as $arrayCorrelOne){
                                    $idImpo = $arrayCorrelOne["cve_imp"];
                                    $idPro = $arrayCorrelOne["cve_pro"];


                                    $table = "cbodega.userprovidersimporters";
                                    $joins = "";
                                    $fieldsArray = array(
                                        "id"
                                    );
                                    $parameters = "\"idImporter\" = $idImpo AND \"idProveedor\" = $idPro AND \"idUser\" = $userId";
                                    $arrayimpprovexist= $db->query($table, $fieldsArray, $joins, $parameters);

                                    if(!$arrayimpprovexist){

                                        $table = "cbodega.userprovidersimporters";
                                        $valuesArray = Array(
                                            "idUser" => $userId,
                                            "idImporter" => $idImpo,
                                            "idProveedor" => $idPro
                                            );
                                        $insertimporterprov = $db->insert($table, $valuesArray);

                                    }

                                }
                            }
                        }
                        
                    }
                }
            }
  
    }
}
