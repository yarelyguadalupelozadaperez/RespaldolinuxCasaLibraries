<?php
require_once 'CasaLibraries/CasaDb/PgsqlConnection.php';

class AddImporters
{
    public function fileUpload($rfc_impo,$clave_importador,$nom_impo)
    {

        //$nom_impo = str_replace("'", "\"", $nom_impo);
        try {
            $idCliente = 952;
            $db = new PgsqlConnection();

            
            $table = "'General'.casac_importadores I";
            $joins = "";
            $fieldsArray = array(
                "nombre_importador",
                "rfc_importador"
                
            );
            $parameters = "I.rfc_importador = '$rfc_impo' AND I.id_cliente = $idCliente";
            $importador = $db->query($table, $fieldsArray, $joins, $parameters);
            

            if($importador){
                var_dump('<pre>');
                var_dump("El importador con el rfc: $rfc_impo ya existe");
                return;
            } else{
                $table = "\"General\".casac_importadores";
                $insertEstimatedPrices = "INSERT INTO $table " . "(\"id_cliente\", \"rfc_importador\", \"clave_importador\", \"nombre_importador\")" . "VALUES " . "('$idCliente', '$rfc_impo', '$clave_importador' ,'$nom_impo')";
                $quieries[] = $insertEstimatedPrices;
                $status = $db->transaction($quieries);
                
                if($status ==  NULL){
                    var_dump('<pre>');
                    var_dump("El importador con el rfc: $rfc_impo se agregó correctamente <br>");
                    return;
                }else{
                    var_dump('<pre>');
                    echo '<b> La operación tiene algunos detalles' . $rfc_impo . '</b>';
                    return;
                }
            }

        }catch (Exception $e) {
            var_dump($e->getMessage());
            exit();
        }
        
    }
}
