<?php
require_once 'CasaLibraries/CasaDb/PgsqlConnection.php';

class PreviosDescargar
{
    public function downloadprevios()
    {

            $sistema = 2;
            $idCliente = 952;
            $db = new PgsqlConnection();
            $sql = "SELECT CURRENT_TIMESTAMP::time without time zone";
            $hour = $db->execute($sql);
            
            $table = "'Previo'.cprevo_refe R";
            $joins = "INNER JOIN \"Previo\".cprevo_previos P ON R.id_prev = P.id_prev ";
            $joins .= "INNER JOIN \"General\".casac_importadores I ON P.id_importador = I.id_importador ";
            $joins .= "LEFT JOIN \"Previo\".cprevo_descar D ON P.id_prev = D.id_prev AND D.id_gdb = 1 ";
            $fieldsArray = array(
                "P.fec_soli", 
                "R.id_prev", 
                "R.num_refe",
                "P.num_guia",
                "I.rfc_importador",
                "D.id_gdb",
                "P.id_prev"
            );
            $parameters = "R.id_licencia = 43 AND P.fol_soli = -1 AND D.id_gdb IS NULL ORDER BY R.id_prev";
            $array = $db->query($table, $fieldsArray, $joins, $parameters);
            
            $count = 0;
            foreach ($array as $value){
               $table = "'Previo'.cprevo_descar";
                $joins = "";
                $fieldsArray = array(
                    "id_prev"
                );
                $parameters = "id_prev = " . $value["id_prev"];
                $arrayprevios = $db->query($table, $fieldsArray, $joins, $parameters); 
                
                if(count($arrayprevios) == 0){
                    $table =  "Previo.cprevo_descar";
                    $valuesArray = Array(
                        "id_prev" => $value["id_prev"],
                        "id_gdb" => 1,
                        "fec_desca" => 'TODAY()',
                        "nom_movil" => 'DESCARGA_WEB_11_JULIO_2019'
                        );
                    $insertimporter = $db->insert($table, $valuesArray);
                   
                }
                $count++;
              
            
            }
             var_dump($count);
            exit;
          
    }

}
