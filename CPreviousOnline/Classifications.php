
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
require_once 'CasaLibraries/CasaDb/ConnectionFactory.class.php';
include 'CasaLibraries/CasaDb/PgsqlQueries3.php';


class Classifications{
    private $_connection;
	public function savePatentAduana($id_cliente, $aduanast, $patente){
		
	}

	public function getClassifications($start, $limit, $idUser, $idClient, $idTypeuser, $query) {
       
        $db = new PgsqlQueries;

       if (empty($query)) {
           
            $db->setTable('previo.ctrac_fracpar pcf');
            $db->setJoins('INNER JOIN general.casac_importadores gci ON pcf.id_importador = gci.id_importador INNER JOIN general.casac_proveedores gcp ON pcf.id_proveedor = gcp.id_proveedor');
            $db->setFields(array(
                'pcf.id_fracpar',
                'pcf.cve_nico',
                'pcf.desc_merc',
                'pcf.num_fracc',
                'pcf.num_part',
                'pcf.status_fracpar',
                'pcf.id_cliente',
                'pcf.origen_fracc',
                'pcf.num_partcove',
                'pcf.val_part',
                'pcf.tip_ope',
                'pcf.uni_fact',
                'gci.clave_importador',
                'gcp.cve_prov'
            ));
            $db->setParameters("pcf.id_cliente = $idClient AND status_fracpar < 2 ORDER BY id_fracpar LIMIT $limit OFFSET $start");
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $response = $db->query();

          //esta surve para sacar el conteo total porque el paginador tiene un total

            $db->setTable('previo.ctrac_fracpar pcf');
            $db->setJoins('INNER JOIN general.casac_importadores gci ON pcf.id_importador = gci.id_importador INNER JOIN general.casac_proveedores gcp ON pcf.id_proveedor = gcp.id_proveedor');
            $db->setFields(array(
                'pcf.id_fracpar',
                'pcf.cve_nico',
                'pcf.desc_merc',
                'pcf.num_fracc',
                'pcf.num_part',
                'pcf.status_fracpar',
                'pcf.id_cliente',
                'pcf.origen_fracc',
                'pcf.num_partcove',
                'pcf.val_part',
                'pcf.tip_ope',
                'pcf.uni_fact',
                'gci.clave_importador',
                'gcp.cve_prov'
            ));
            $db->setParameters("pcf.id_cliente = $idClient AND status_fracpar < 2 ORDER BY id_fracpar");
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $responseTotal = $db->query();
            
             $response['total'] = count($responseTotal);
       
            if(count($response) > 1) {
                return $response;
            } else {
                return NULL;
            }
       } else {

//Editar
            $db->setTable('previo.ctrac_fracpar pcf');
            $db->setJoins('INNER JOIN general.casac_importadores gci ON pcf.id_importador = gci.id_importador INNER JOIN general.casac_proveedores gcp ON pcf.id_proveedor = gcp.id_proveedor');
            $db->setFields(array(
                'pcf.id_fracpar',
                'pcf.cve_nico',
                'pcf.desc_merc',
                'pcf.num_fracc',
                'pcf.num_part',
                'pcf.status_fracpar',
                'pcf.id_cliente',
                'pcf.origen_fracc',
                'pcf.num_partcove',
                'pcf.val_part',
                'pcf.tip_ope',
                'pcf.uni_fact',
                'gci.clave_importador',
                'gcp.cve_prov'
            ));
            $db->setParameters("pcf.id_cliente = $idClient AND status_fracpar < 2 AND (num_fracc iLIKE SP_ASCII(CONCAT('%$query%')) OR cve_nico iLIKE SP_ASCII(CONCAT('%$query%')) OR num_part iLIKE SP_ASCII(CONCAT('%$query%')) OR desc_merc iLIKE SP_ASCII(CONCAT('%$query%')))  ORDER BY id_fracpar LIMIT $limit OFFSET $start");
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $response = $db->query();

          //esta surve para sacar el conteo total porque el paginador tiene un total

            $db->setTable('previo.ctrac_fracpar pcf');
            $db->setJoins('INNER JOIN general.casac_importadores gci ON pcf.id_importador = gci.id_importador INNER JOIN general.casac_proveedores gcp ON pcf.id_proveedor = gcp.id_proveedor');
            $db->setFields(array(
                'pcf.id_fracpar',
                'pcf.cve_nico', 
                'pcf.desc_merc',
                'pcf.num_fracc',
                'pcf.num_part',
                'pcf.status_fracpar',
                'pcf.id_cliente',
                'pcf.origen_fracc',
                'pcf.num_partcove',
                'pcf.val_part',
                'pcf.tip_ope',
                'pcf.uni_fact',
                'gci.clave_importador',
                'gcp.cve_prov'
            ));
            $db->setParameters("pcf.id_cliente = $idClient AND status_fracpar < 2 AND (num_fracc iLIKE SP_ASCII(CONCAT('%$query%')) OR cve_nico iLIKE SP_ASCII(CONCAT('%$query%')) OR num_part iLIKE SP_ASCII(CONCAT('%$query%')) OR desc_merc iLIKE SP_ASCII(CONCAT('%$query%'))) ORDER BY id_fracpar");
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

    public function updateClassifications($data){
    	$data = json_decode(json_encode($data), true);
        $idFracpar = $data["id_fracpar"];

        $db = new PgsqlQueries;
        $db->setTable('previo.ctrac_fracpar');
        $db->setFields(array(
            'cve_nico',
            'desc_merc',
            'num_fracc',
            'num_part',
            'status_fracpar',
            'id_cliente',
            'origen_fracc',
            'num_partcove'
        ));
    
        $db->setParameters("id_fracpar = $idFracpar AND status_fracpar < 2");
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $classification = $db->query();

        if (isset($data["cve_nico"])) {
            $cve_nico = $data["cve_nico"];
        } else {
            $cve_nico = $classification[0]["cve_nico"];
        }
        
        if (isset($data["desc_merc"])) {
            $desc_merc = $data["desc_merc"];
        } else {
            $desc_merc = $classification[0]["desc_merc"];
        }

        if (isset($data["num_fracc"])) {
            $num_fracc = $data["num_fracc"];
        } else {
            $num_fracc = $classification[0]["num_fracc"];
        }

        if (isset($data["num_part"])) {
            $num_part = $data["num_part"];
        } else {
            $num_part = $classification[0]["num_part"];
        }

        if (isset($data["status_fracpar"])) {
            if ($data["status_fracpar"] == false){
              $status_fracpar = 0;  
            } else {
              $status_fracpar = 1;  
            }
            
        } else {
            $status_fracpar = $classification[0]["status_fracpar"];
        }

        if (isset($data["id_cliente"])) {
            $id_cliente = $data["id_cliente"];
        } else {
            $id_cliente = $classification[0]["id_cliente"];
        }

        if (isset($data["origen_fracc"])) {
            $origen_fracc = $data["origen_fracc"];
        } else {
            $origen_fracc = $classification[0]["origen_fracc"];
        }

        if (isset($data["num_partcove"])) {
            $num_partcove = $data["num_partcove"];
        } else {
            $num_partcove = $classification[0]["num_partcove"];
        }
        
        try { 
        	$db->setTable('previo.ctrac_fracpar');
            $db->setValues(array(
            		'cve_nico' => $cve_nico,
		            'desc_merc' => $desc_merc,
		            'num_fracc' => $num_fracc,
		            'num_part' => $num_part,
		            'status_fracpar' => $status_fracpar,
		            'id_cliente' => $id_cliente,
		            'origen_fracc' => $origen_fracc,
		            'num_partcove' => $num_partcove
                    ));
            $db->setParameters("id_fracpar = $idFracpar");
            return $classificationDatas = $db->update();
       
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }   

    }

    public function newClassifications($idClient, $data){
    	$data = json_decode(json_encode($data), true);

    	if (isset($data["cve_nico"])) {
            $cve_nico = $data["cve_nico"];
        } else {
            $cve_nico = '00';
        }
        
        if (isset($data["desc_merc"])) {
            $desc_merc = $data["desc_merc"];
        } else {
            $desc_merc = 'S/A';
        }

        if (isset($data["num_fracc"])) {
            $num_fracc = $data["num_fracc"];
        } else {
            $num_fracc = '00000000';
        }

        if (isset($data["num_part"])) {
            $num_part = $data["num_part"];
        } else {
            $num_part = 'S/A';
        }


    	try {
    		$db = new PgsqlQueries;
		    $db->setTable('previo.ctrac_fracpar');
            $db->setValues(array(
                'cve_nico' => $cve_nico,
	            'desc_merc' => $desc_merc,
	            'num_fracc' => $num_fracc,
	            'num_part' => $num_part,
	            'status_fracpar' => 1,
	            'id_cliente' => $idClient,
	            'origen_fracc' => 1,
	            'num_partcove' => 0
            ));
            return $classificationNew = $db->insert();

        } catch (Exception $exc) {
            echo $exc->getMessage();

        }
    }

    public function deleteClassification($idClassification){

        try { 
        	$db = new PgsqlQueries;
        	$db->setTable('previo.ctrac_fracpar');
		    $db->setValues(array(
		        "status_fracpar" => 2
		    ));
		    $db->setParameters("id_fracpar =  $idClassification");

            return $classification = $db->update();
       
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }    
    }
    
    public function updateclassification($idCli, $id_user, $classActive){
        $dbAdoP = ConnectionFactory::Connectpostgres();
        if ($classActive == 1) {
            $enviroment  = 'Pruebas o local';
        } else if ($classActive == 2) {
            $enviroment  = 'Producción';
        } else {
            $enviroment  = 'No se reconoce.';
        }

        try {

            $dbAdoP->beginTrans();
            
            $date= date('d-m-Y H:i:s');
            $params = array("-", ":", " ");
            $replace   = array("", "-", "_");
            $replaceSequence   = array("", "_", "_");
            
            $dateFormat = str_replace($params, $replace, $date);

            $dateFormatSequence = str_replace($params, $replaceSequence, $date);

            $name = "ctrac_fracparbackup_" . $dateFormat;
            $nameSequence = "ctrac_fracparbackup_" . $dateFormatSequence . "_id_fracpar_seq";

            try {
                
                $sqlNextValRename = "ALTER SEQUENCE previo.ctrac_fracpar_id_fracpar_seq RENAME TO  \"" . $nameSequence ."\"";
                $nextValRename = $dbAdoP->Execute ( $sqlNextValRename );

                $sqlRenameReference = "ALTER TABLE previo.ctrac_fracpar RENAME TO \"" . $name ."\"";
                
                $saveRenameReference = $dbAdoP->Execute ( $sqlRenameReference );
                
                $sqlCreateTableClasification = "CREATE TABLE previo.ctrac_fracpar
            (
                id_fracpar bigserial NOT NULL,
                altfec_fracc timestamp without time zone,
                bajfec_fracc timestamp without time zone,
                cve_nico character varying(255),
                desc_merc character varying(255),
                flag_fracc smallint,
                num_fracc character varying(255),
                num_part character varying(255),
                num_partcove smallint,
                origen_fracc smallint,
                status_fracpar smallint,
                tip_ope smallint,
                uni_fact integer,
                val_part smallint,
                id_cliente bigint,
                id_importador serial NOT NULL,
                id_proveedor serial NOT NULL,
                id_part integer,
                PRIMARY KEY (id_fracpar),
                FOREIGN KEY (id_cliente)
                REFERENCES general.casac_clientes (id_cliente) MATCH SIMPLE
                ON UPDATE NO ACTION ON DELETE NO ACTION,
                FOREIGN KEY (id_proveedor)
                REFERENCES general.casac_proveedores (id_proveedor) MATCH SIMPLE
                ON UPDATE NO ACTION ON DELETE NO ACTION,
                FOREIGN KEY (id_importador)
                REFERENCES general.casac_importadores (id_importador) MATCH SIMPLE
                ON UPDATE NO ACTION ON DELETE NO ACTION)";
                $saveRenameReference = $dbAdoP->Execute ( $sqlCreateTableClasification );
                $dbAdoP->commitTrans();

                $nextValFrac = "SELECT setval('previo.ctrac_fracpar_id_fracpar_seq', COALESCE((SELECT MAX(id_fracpar) FROM previo.ctrac_fracpar), 0) + 1) AS nextId";
                $nextIdFrac= $dbAdoP->Execute ( $nextValFrac );
                $id_FracNext = $nextIdFrac->fields["nextid"];


                $insertDefault =    "INSERT INTO previo.ctrac_fracpar(id_fracpar, cve_nico, desc_merc, num_fracc, num_part, status_fracpar, tip_ope,   id_cliente, id_importador, id_proveedor)
                                VALUES ($id_FracNext, '00', 'Sin asignar', '00000000', 'S/A', 0, 1, 189,1,1)";
                $resultInsert= $dbAdoP->Execute ( $insertDefault );


                if (isset($resultInsert)) {
                    $sqlNextVal = "SELECT pg_catalog.setval('previo.ctrac_fracpar_id_fracpar_seq', (SELECT max(id_fracpar) FROM previo.ctrac_fracpar), true)";
                    $nextVal = $dbAdoP->Execute ( $sqlNextVal );
                    $comments = "";
                    $nameusu = "";
                    
                    $tip_accion = 6; //Limpiar clasificaciones
                    
                    $response = $this->insertLogs($idCli, 15, ' Limpiar clasificaciones en '. $enviroment, ' Limpiar clasificaciones en '. $enviroment, $id_user, $tip_accion, $comments, $nameusu);

                    if($response == true){
                        $tempArray["message"] = "Operación exitosa. Se eliminaron las clasificaciones correctamente.";
                        $tempArray["success"] = "true";
                        return $tempArray;
                        
                    } else {

                        if (getenv('HTTP_CLIENT_IP')) {
                            $ip = getenv('HTTP_CLIENT_IP');
                        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
                            $ip = getenv('HTTP_X_FORWARDED_FOR');
                        } elseif (getenv('HTTP_X_FORWARDED')) {
                            $ip = getenv('HTTP_X_FORWARDED');
                        } elseif (getenv('HTTP_FORWARDED_FOR')) {
                            $ip = getenv('HTTP_FORWARDED_FOR');
                        } elseif (getenv('HTTP_FORWARDED')) {
                            $ip = getenv('HTTP_FORWARDED');
                        } else {
                            $ip = $_SERVER['REMOTE_ADDR'];
                        }
                        $filename = '/library/logPrev/logClasif.log';
                        $hour = date("G");
                        $hour -= 1;
                        $now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));
                        $time = $now->format("Y-m-d ".$hour.":i:s.u");
                        file_put_contents($filename, $time ." La persona con número de ID ". $id_user . " realizó el evento: " .  $tip_accion. " por el motivo de limpieza de catálogo de clasificaciones desde la IP: ". $ip . " en la fecha: ". date("Y-m-d H:i:s") . PHP_EOL.'', FILE_APPEND);

                        $tempArray["message"] = "Se eliminaron las clasificaciones.";
                        $tempArray["success"] = "true";
                        return $tempArray;
                    }
                
                } 

            }catch(Exception $e){
                $dbAdoP->rollbackTrans();
                $tempArray["message"] = "Ocurrió un error. Favor de notificar a soporte.";
                $tempArray["success"] = "false";
                return $tempArray;
            }
     
 
        } catch (Exception $ex) {
           $dbAdoP->rollbackTrans();
           return false;
        }
        
    }
    
    public function insertLogs($idClient, $id_prev, $num_refe, $num_refe_orig, $id_user, $tip_accion, $comments, $nameusu_rename)
    {
        
        $dbAdoP = ConnectionFactory::Connectpostgres();
        
        try {
            
            if (getenv('HTTP_CLIENT_IP')) {
                $ip = getenv('HTTP_CLIENT_IP');
            } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
                $ip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_X_FORWARDED')) {
                $ip = getenv('HTTP_X_FORWARDED');
            } elseif (getenv('HTTP_FORWARDED_FOR')) {
                $ip = getenv('HTTP_FORWARDED_FOR');
            } elseif (getenv('HTTP_FORWARDED')) {
                $ip = getenv('HTTP_FORWARDED');
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }

            $db =  $this->getConnection();
         
            
           /* $nextVal = "SELECT setval('previo.cprevo_logs_id_log_seq', (SELECT MAX(id_log) FROM previo.cprevo_logs)+1)";

            $nextIdLog= $dbAdoP->Execute ( $nextVal );
            $idLogPrev= json_decode(json_encode($nextIdLog->fields), true);
            $id_logNext = $idLogPrev[0];     

            $db->setTable("previo.cprevo_logs");
            $db->setValues(array(
                "id_log" =>$id_logNext,
                "id_prev" => $id_prev,
                "id_usuario"=> $id_user,
                "fec_act" => date('m-d-Y H:i:s'),
                //"fec_act" => date('Y-m-d H:i:s'),
                "ip_origen" => $ip,
                "nom_orig_refe" => $num_refe_orig,
                "nom_new_refe" => $num_refe,
                "tip_accion" =>  $tip_accion,
                "motivo_accion" =>$comments,
                "nom_usu_accion" => $nameusu_rename
                
            ));
            $logs = $db->insert();*/

            $nextValLog = "SELECT setval('previo.cprevo_logs_id_log_seq', COALESCE((SELECT MAX(id_log) FROM previo.cprevo_logs), 0) + 1) AS nextId";
            $nextIdLog= $dbAdoP->Execute ( $nextValLog );
            $id_LogNext = $nextIdLog->fields["nextid"];

            $dateNow = date('m-d-Y H:i:s');
            $insertDefault =    "INSERT INTO previo.cprevo_logs(id_log, id_prev, id_usuario, fec_act, ip_origen, nom_orig_refe, nom_new_refe, tip_accion, motivo_accion, nom_usu_accion)
                            VALUES ($id_LogNext, $id_prev, $id_user,  '$dateNow', '$ip',  '$num_refe_orig', '$num_refe', $tip_accion,'$comments','$nameusu_rename')";
            
            $resultInsertLog= $dbAdoP->Execute ( $insertDefault );

            if (isset($resultInsertLog)) {
                return true;
            } 

            return false;
        } catch (Exception $exc) {
            return false;
            
        }
    }
    
    /**
     *
     * @return the $_connection
     */
    public function getConnection(){
        return new PgsqlQueries3;
    }
 
    public function setConnection($_connection){
        $this->_connection =  $this->getConnection();
    }
}

   
    
    
	
?>
