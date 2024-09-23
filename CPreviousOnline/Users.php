<?php

/**
 * CasaLibraries User class
 * File User.php
 * Connection to posgresql database
 *
 * @category 	CasaLibraries
 * @package 	CasaLibraries_CasaSkeketon
 * @copyright 	Copyright (c) 2005-2013 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author 		Jaime Santana Zaldivar
 * @version 	User 1.0.0
 *
 */
include 'CasaLibraries/CasaDb/PgsqlQueries.php';

class Users
{
    public function getUsers($start, $limit, $idUser, $idClient, $idTypeuser) {
        $db = new PgsqlQueries;
        
        $db->setTable("'previo'.usuarios U");
        $db->setJoins("INNER JOIN 'general'.casac_clientes C ON U.id_cliente = C.id_cliente");
        $db->setJoin("INNER JOIN 'general'.casag_licencias L ON U.id_cliente = L.id_cliente");
        $db->setJoin("INNER JOIN 'general'.casag_licenciasistema LS ON L.id_licencia = LS.id_licencia AND LS.id_sistema = 2");
        $db->setJoin("INNER JOIN 'general'.casag_configcliente CS ON CS.id_cliente = C.id_cliente");
        $db->setJoin("INNER JOIN 'general'.casag_temas T ON T.id_tema = CS.id_tema");
        $db->setFields(array(
            "DISTINCT ON (U.id_usuario) U.id_usuario",
            "U.id_cliente",
            "U.id_tipousuario",
            "U.alias_usuario",
            "U.nombre_usuario",
            "U.correo_usuario",
            "to_char(U.fechalta_usuario, #DD/MM/YYYY#::text) AS fechalta_usuario",
            "U.status_usuario",
        ));
        
        if($idTypeuser == 1){
            $db->setParameters("U.id_usuario > 1 AND U.id_usuario <> $idUser AND U.status_usuario < 2 AND U.id_cliente = $idClient LIMIT $limit OFFSET $start");
        } else {
            $db->setParameters("U.id_usuario > 1 AND U.id_usuario <> $idUser AND U.status_usuario < 2 AND U.id_cliente = $idClient LIMIT $limit OFFSET $start");
        }
        
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $users = $db->query();
        
        $db->setTable("'previo'.usuarios U");
        $db->setJoins("INNER JOIN 'general'.casac_clientes C ON U.id_cliente = C.id_cliente");
        $db->setJoin("INNER JOIN 'general'.casag_licencias L ON U.id_cliente = L.id_cliente");
        $db->setJoin("INNER JOIN 'general'.casag_licenciasistema LS ON L.id_licencia = LS.id_licencia AND LS.id_sistema = 2");
        $db->setJoin("INNER JOIN 'general'.casag_configcliente CS ON CS.id_cliente = C.id_cliente");
        $db->setJoin("INNER JOIN 'general'.casag_temas T ON T.id_tema = CS.id_tema");
        $db->setFields(array(
            "distinct(U.id_usuario)",
            "U.id_cliente",
            "U.id_tipousuario",
            "U.alias_usuario",
            "U.nombre_usuario",
            "U.correo_usuario",
            "to_char(U.fechalta_usuario, #DD/MM/YYYY#::text) AS fechalta_usuario",
            "U.status_usuario",
        ));
        if($idTypeuser == 1){
            $db->setParameters("U.id_usuario > 1 AND U.id_usuario <> $idUser AND U.status_usuario < 2 AND U.id_cliente = $idClient");
        } else {
            $db->setParameters("U.id_usuario > 1 AND U.id_usuario <> $idUser AND U.status_usuario < 2 AND U.id_cliente = $idClient");
        }
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $users1 = $db->query();
        
        $users['total'] = count($users1);
    
        if(count($users) > 1) {
            return $users;
        } else {
            return NULL;
        }
    }
    
    public function updateUsers($data, $idClient, $idTypeuser) {
        $data = json_decode(json_encode($data), true);
        $idUser = $data["id_usuario"];
        $db = new PgsqlQueries;
        $db->setTable("'previo'.usuarios U");
        $db->setFields(array(
            "U.id_usuario",
            "U.nombre_usuario",
            "U.id_tipousuario",
            "U.contrasena_usuario",
            "U.status_usuario",
            "U.id_cliente"
        ));
    
        $db->setParameters("U.id_usuario = '" . $idUser . "'");
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $users = $db->query();

        $someData = (isset($data["id_tipousuario"]))?$data["id_tipousuario"]:5;

        if($users[0]["id_cliente"] == $idClient){

            if($users[0]["id_tipousuario"] >= $idTypeuser && $someData >= $users[0]["id_tipousuario"]){

                if (isset($data["nombre_usuario"])) {
                    $username = $data["nombre_usuario"];
                } else {
                    $username = $users[0]["nombre_usuario"];
                }
                
                if (isset($data["id_tipousuario"])) {
                    $typeuser = $data["id_tipousuario"];
                } else {
                    $typeuser = $users[0]["id_tipousuario"];
                }
        
                if (isset($data["status_usuario"])) {
                    if ($data["status_usuario"] == false){
                        $status = 0;  
                    } else {
                        $status = 1;  
                    }
                    
                } else {
                    $status = $users[0]["status_usuario"];
                }
        
                
                try { 
                    $db->setTable("previo.usuarios");
                    $db->setValues(array(
                            "nombre_usuario" => $username,
                            "id_tipousuario" => $typeuser,
                            "status_usuario" => $status
                            ));
                    $db->setParameters("id_usuario = $idUser");
                    return $usersDatas = $db->update();
        
                } catch (Exception $exc) {
                    return '0R';
                    exit();
                }  
            }else{
                return '0Q';
                exit();
            }
        }else{
            return '0P';
            exit();
        }
    }
    
    /*public function saveUserinformation($userName, $alias, $userPassword, $userEmail, $typeUser, $idClient, $importers){
        $importers = json_decode($importers);

        $date = date('Y-m-d');
        $db = new PgsqlQueries;

        $db->setTable("'previo'.usuarios");
        $db->setFields(array(
            "id_usuario",
            "status_usuario"
        ));
        $db->setParameters("correo_usuario = '$userEmail'");
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ONE);
        $user = $db->query();
        
        $typeuser = 0;
        
        if($typeUser == "true"){
            $typeuser = 2;
        } else {
            $typeuser = 3;
        }

        if(!$user){
            try {
                $db->setTable("previo.usuarios");
                $db->setValues(array(
                    "nombre_usuario" => $userName,
                    "alias_usuario" => $alias,
                    "id_cliente" => $idClient,
                    "correo_usuario" =>  $userEmail,
                    "contrasena_usuario" =>  $userPassword,
                    "id_tipousuario" => $typeuser,
                    "fechalta_usuario" =>  $date,
                    "status_usuario" => 1,
                ));
                $users = $db->insert();
                
                $db->setTable("'previo'.usuarios");
                $db->setFields(array(
                    "max(id_usuario) AS countusers"
                ));

                $db->setParameters("TRUE");
                $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ONE);
                $lastUser = $db->query();

                $idUser = $lastUser["countusers"];
               

                if ($typeUser == "true") {
                    $db->setTable("'general'.casag_licencias L");
                    $db->setJoins("INNER JOIN 'general'.casag_licenciasistema LS ON L.id_licencia = LS.id_licencia");
                    $db->setJoin("INNER JOIN 'general'.casag_licenciasimportador LI ON LS.id_licenciasistema = LI.id_licenciasistema");
                    $db->setJoin("INNER JOIN 'general'.casac_importadores I ON LI.id_importador = I.id_importador");
                    $db->setJoin("INNER JOIN 'general'.casac_aduanas A ON L.id_aduana = A.id_aduana");
                    $db->setFields(array(
                        "LI.id_licenciasimportador",
                        "I.nombre_importador",
                        "A.nombre_aduana"
                    ));

                    $db->setParameters("L.id_cliente = $idClient AND I.status_importador = 1 ORDER BY I.id_importador ASC");
                    $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                    $importersSQL = $db->query();
                    
                    foreach ($importersSQL as $importer) {
                        $db->setTable("previo.cprevc_usulicimp");
                        $db->setValues(array(
                            "id_usuario" => $idUser,
                            "id_licenciasimportador" => $importer["id_licenciasimportador"] ,
                        ));
                        $users = $db->insert();
                    }
                    
                } else {

                    foreach ($importers as $importer) {
                           
                        $importerSelected = $importer->selected;
                        if ($importerSelected == true) {
                            
                            $db->setTable("previo.cprevc_usulicimp");
                                $db->setValues(array(
                                    "id_usuario" => $idUser,
                                    "id_licenciasimportador" => $importer->id_licenciasimportador,
                                ));
                            $users = $db->insert();
                        }
                    }
                    
                }

                return true;
                exit();
            } catch (Exception $exc) {
                var_dump($exc->getMessage());
                exit();
            }
        } else {
            if ($user["status_usuario"] == 2) 
            {
                try {
                    $db->setTable("previo.usuarios");
                    $db->setValues(array(
                        "nombre_usuario" => $userName,
                        "alias_usuario" => $alias,
                        "contrasena_usuario" =>  $userPassword,
                        "status_usuario" => 1,
                    ));
                    $db->setParameters("correo_usuario =  '$userEmail'");
                    $users = $db->update();
                    return true;
                    exit();
                } catch (Exception $exc) {
                    var_dump($exc->getMessage());
                    exit();
                }
            } else {
                return false;
            }
        }
        
    }*/
    
    public function saveUserinformation($userName, $alias, $userPassword, $userEmail, $typeUser, $idClient, $licences, $importers, $cve){
        $licences  = json_decode($licences);
        $importers = json_decode($importers);
        $date      = date('Y-m-d');
        
        $db = new PgsqlQueries;

        $db->setTable("'previo'.usuarios");
        $db->setFields(array(
            "id_usuario",
            "status_usuario"
        ));
        $db->setParameters("correo_usuario = '$userEmail'");
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ONE);
        $user = $db->query();
        
        $typeuser = 0;
        if($typeUser == "true"){
            $typeuser = 2; 
        } else {
            $typeuser = 3;
        }

    
        if(!$user){
            try {
                $db->setTable("previo.usuarios");
                $db->setValues(array(
                    "nombre_usuario" => $userName,
                    "alias_usuario" => $alias,
                    "id_cliente" => ($cve != 0)?$cve:$idClient,
                    "correo_usuario" =>  $userEmail,
                    "contrasena_usuario" =>  $userPassword,
                    "id_tipousuario" => $typeuser,
                    "fechalta_usuario" =>  $date,
                    "status_usuario" => 1,
                ));
                $users = $db->insert();
                
        
                
                $db->setTable("'previo'.usuarios");
                $db->setFields(array(
                    "max(id_usuario) AS countusers"
                ));

                $db->setParameters("TRUE");
                $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ONE);
                $lastUser = $db->query();

                $idUser = $lastUser["countusers"];
                if ($typeUser == "true") {
                    $db->setTable("'general'.casag_licencias L");
                    $db->setJoin("INNER JOIN 'general'.casac_aduanas A ON L.id_aduana = A.id_aduana");
                    $db->setFields(array(
                        "L.id_licencia",
                        "L.patente",
                        "A.id_aduana",
                        "A.clave_aduana",
                        "A.nombre_aduana"
                    ));

                    $db->setParameters("L.id_cliente = ".(($cve != 0)?$cve:$idClient)." ORDER BY patente ");
                    $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                    $licencesSQL = $db->query();
                    
                    foreach ($licencesSQL as $licence) {
                        $db->setTable("general.casac_licusuario");
                        $db->setValues(array(
                            "id_usuario" => $idUser,
                            "id_licencia" => $licence["id_licencia"] ,
                        ));
                        $users = $db->insert();
                    }
                    
                } else {
                    foreach ($licences as $licenc) {
                        $liceSelected = $licenc->selected;
                        
                        if ($liceSelected == true) {
                            $db->setTable("general.casac_licusuario");
                                $db->setValues(array(
                                    "id_usuario" => $idUser,
                                    "id_licencia" => $licenc->id_licencia,
                                ));
                            $users = $db->insert();
                        }
                    }
                    
                    foreach ($importers as $impo) {
                        $ImpoSelected = $impo->selected;
                        
                        if ($ImpoSelected == true) {
                            $db->setTable("previo.cprevc_usuimp");
                                $db->setValues(array(
                                    "id_usuario" => $idUser,
                                    "id_importador" => $impo->id_importador,
                                ));
                            $users = $db->insert();
                        }
                    }
                }
                return true;
                exit();
                
            } catch (Exception $exc) {
                var_dump($exc->getMessage());
                exit();
            }
        } else {
            if ($user["status_usuario"] == 2){
                try {
                    $db->setTable("previo.usuarios");
                    $db->setValues(array(
                        "nombre_usuario" => $userName,
                        "alias_usuario" => $alias,
                        "contrasena_usuario" =>  $userPassword,
                        "status_usuario" => 1,
                    ));
                    $db->setParameters("correo_usuario =  '$userEmail'");
                    $users = $db->update();
                    
                    return true;
                    exit();
                } catch (Exception $exc) {
                    var_dump($exc->getMessage());
                    exit();
                }
            } else {
                return false;
            }
        }
    }
    
    public function getImporters($idClient, $selectall, $query = null) {
        $db = new PgsqlQueries;
        
        $selected = 0;
        if ($selectall == "true") {
            $selected = 1;
        }

        if ($selectall == "false") {
            $selected = 0;
        }

        $db->setTable("'general'.casac_importadores I");
        $db->setFields(array(
            "$selected AS selected",
            "I.nombre_importador",
            "I.rfc_importador",
            "I.id_importador",
        ));
        
        if($query != null){
            $db->setParameters("I.id_cliente = $idClient AND I.status_importador = 1  AND (SP_ASCII(I.nombre_importador) iLIKE '%$query%') ORDER BY I.id_importador ASC");
        } else{
            $db->setParameters("I.id_cliente = $idClient AND I.status_importador = 1 ORDER BY I.id_importador ASC");
        }
               
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
    
        $importers = $db->query();
        
        if(count($importers) > 0) {
            return $importers;
        } else {
            return NULL;
        }
    }
    
    function deleteUser($idUser, $idClient, $idTypeuser){

        $db = new PgsqlQueries;
        $db->setTable("'previo'.usuarios U");
        $db->setFields(array(
            "U.id_tipousuario",
            "U.id_cliente"
        ));

        $db->setParameters("U.id_usuario = '" . $idUser . "'");
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $users = $db->query();

        if(count($users) > 0){
            if($users[0]["id_cliente"] == $idClient){
                if($users[0]["id_tipousuario"] >= $idTypeuser){
    
                    try { 
                        $db = new PgsqlQueries;
                        $db->setTable("previo.usuarios");
                        $db->setValues(array(
                            "status_usuario" => 2,
                        ));
                        $db->setParameters("id_usuario =  '$idUser'");
                        return $users = $db->update();
                    } catch (Exception $exc) {
                        return '0R';
                        exit();
                    }  
                }else{
                    return '0Q';
                    exit();
                }
            }else{
                return '0P';
                exit();
            }
        } else {
            return '00';
            exit();
        }
    }
    
    public function getUserImporters($idUser, $idClient, $query = null) {

        $db = new PgsqlQueries;

        if ($idUser == NULL) {
            $idUser = 1;
        }
        
        if ($idClient == NULL) {
            $idClient = 1;
        }
        

        $db->setTable("'general'.casag_licencias L");
        $db->setJoins("INNER JOIN 'general'.casag_licenciasistema LS ON L.id_licencia = LS.id_licencia");
        $db->setJoin("INNER JOIN 'general'.casag_licenciasimportador LI ON LS.id_licenciasistema = LI.id_licenciasistema");
        $db->setJoin("INNER JOIN 'general'.casac_importadores I ON LI.id_importador = I.id_importador");
        $db->setJoin("INNER JOIN 'general'.casac_aduanas A ON L.id_aduana = A.id_aduana");
        $db->setFields(array(
            "LI.id_licenciasimportador",
            "I.nombre_importador",
            "A.clave_aduana",
            "A.nombre_aduana"
        ));
        if($query != null){
            $db->setParameters("L.id_cliente = $idClient AND I.status_importador = 1 AND (SP_ASCII(I.nombre_importador) iLIKE '%$query%') ORDER BY I.id_importador ASC");
        } else {
            $db->setParameters("L.id_cliente = $idClient AND I.status_importador = 1 ORDER BY I.id_importador ASC");
        }
       
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $importers = $db->query();


        $db->setTable("'previo'.cprevc_usulicimp LI");
        $db->setJoins("");
        $db->setFields(array(
            "LI.id_licenciasimportador",
        ));
        $db->setParameters("LI.id_usuario = $idUser");
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $userimporters = $db->query();
        
       
        $tempArray = Array();
        $count = 0;
        foreach ($importers as $importer) {
      
            $tempArray2 = Array();
            $tempArray2 = $importer;

            foreach ($userimporters as $userimporter) {

                if ($userimporter["id_licenciasimportador"] == $importer["id_licenciasimportador"]) {
                    $tempArray2 = $importer;
                    $tempArray2["selected"] = 1;
                    $tempArray2["id_usuario"] = $idUser;
                    $tempArray2["nombre_aduana"] = $importers[$count]["nombre_aduana"];
                }
            }

            if (!isset($tempArray2["selected"])) {
                $tempArray2["selected"] = 0;
                $tempArray2["id_usuario"] = $idUser;
                $tempArray2["nombre_aduana"] = $importers[$count]["nombre_aduana"];
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
    
    public function selectAllImporters($idUser, $idClient) {
        $db = new PgsqlQueries;
        
        $db->setTable("'general'.casag_licencias L");
        $db->setJoins("INNER JOIN 'general'.casag_licenciasistema LS ON L.id_licencia = LS.id_licencia");
        $db->setJoin("INNER JOIN 'general'.casag_licenciasimportador LI ON LS.id_licenciasistema = LI.id_licenciasistema");
        $db->setJoin("INNER JOIN 'general'.casac_importadores I ON LI.id_importador = I.id_importador");
        $db->setJoin("INNER JOIN 'general'.casac_aduanas A ON L.id_aduana = A.id_aduana");
        $db->setFields(array(
            "LI.id_licenciasimportador",
            "I.nombre_importador",
            "A.clave_aduana",
            "A.nombre_aduana"
        ));

        $db->setParameters("L.id_cliente = $idClient AND I.status_importador = 1 ORDER BY I.id_importador ASC");
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $importers = $db->query();
       
        $db->setTable("'previo'.cprevc_usulicimp LI");
        $db->setJoins("");
        $db->setFields(array(
            "LI.id_licenciasimportador",
        ));
        $db->setParameters("LI.id_usuario = $idUser");
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $userimporters = $db->query();

        $tempArray = Array();

        foreach ($importers as $importer) {
            $tempArray2 = Array();
            $tempArray2 = $importer;

            foreach ($userimporters as $userimporter) {
                if ($userimporter["id_licenciasimportador"] == $importer["id_licenciasimportador"]) {
                    $tempArray2 = $importer;
                    $tempArray2["selected"] = 1;
                    $tempArray2["id_usuario"] = $idUser;
                }
            }
            
            if (!isset($tempArray2["selected"])) {
                $db->setTable("previo.cprevc_usulicimp");
                $db->setValues(array(
                    "id_usuario" => $idUser,
                    "id_licenciasimportador" => $importer["id_licenciasimportador"] ,
                ));
                $users = $db->insert();
            }
        }
    }
    
    public function selectAllLicences($idUser, $idClient, $idTypeuser) {

        $db = new PgsqlQueries;
        $db->setTable("'previo'.usuarios U");
        $db->setFields(array(
            "U.id_tipousuario",
            "U.id_cliente"
        ));

        $db->setParameters("U.id_usuario = '" . $idUser . "'");
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $users = $db->query();

        if(count($users) > 0){
            if($users[0]["id_cliente"] == $idClient){
                if($users[0]["id_tipousuario"] >= $idTypeuser){

                    $db->setTable("'general'.casag_licencias L");
                    $db->setJoin("INNER JOIN 'general'.casac_aduanas A ON L.id_aduana = A.id_aduana");
                    $db->setFields(array(
                        "L.id_licencia",
                        "A.clave_aduana",
                        "A.nombre_aduana"
                    ));
            
                    $db->setParameters("L.id_cliente = $idClient");
                    $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                    $licences = $db->query();
                    
                    $db->setTable("'general'.casac_licusuario LI");
                    $db->setJoins("");
                    $db->setFields(array(
                        "LI.id_licencia",
                    ));
                    $db->setParameters("LI.id_usuario = $idUser");
                    $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                    $userLicences = $db->query();
                    
                    $tempArray = Array();
            
                    foreach ($licences as $licence) {
                        $tempArray2 = Array();
                        $tempArray2 = $licence;
            
                        foreach ($userLicences as $userLicence) {
            
                            if ($userLicence["id_licencia"] == $licence["id_licencia"]) {
                                $tempArray2 = $licence;
                                $tempArray2["selected"] = 1;
                                $tempArray2["id_usuario"] = $idUser;
                            }
                        }
            
                        if (!isset($tempArray2["selected"])) {
                            $db->setTable("general.casac_licusuario");
                            $db->setValues(array(
                                "id_usuario" => $idUser,
                                "id_licencia" => $licence["id_licencia"] ,
                            ));
                            $users = $db->insert();
                        }
                    }
                }else{
                    return '0Q';
                    exit();
                }
            }else{
                return '0P';
                exit();
            }
        } else {
            return '00';
            exit();
        }
    }
    
    public function selectAllImportersPerm($idUser, $idClient, $idTypeuser) {
        $db = new PgsqlQueries;
        $db->setTable("'previo'.usuarios U");
        $db->setFields(array(
            "U.id_tipousuario",
            "U.id_cliente"
        ));

        $db->setParameters("U.id_usuario = '" . $idUser . "'");
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $users = $db->query();

        if(count($users) > 0){
            if($users[0]["id_cliente"] == $idClient){
                if($users[0]["id_tipousuario"] >= $idTypeuser){

                    $db->setTable("'general'.casac_importadores I");
                    $db->setFields(array(
                        "I.nombre_importador",
                        "I.rfc_importador",
                        "I.id_importador",
                        
                    ));
                    
                    $db->setParameters("i.id_cliente = $idClient");
                    $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                    $importers = $db->query();
                    
                    $db->setTable("'previo'.cprevc_usuimp UI");
                    $db->setFields(array(
                        "UI.id_importador"
                        
                    ));
                    $db->setParameters("UI.id_usuario = $idUser");
                    $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                    $userimporters= $db->query();
                    
                    $tempArray = Array();
                    
                    foreach ($importers as $importer) {
                        $tempArray2 = Array();
                        $tempArray2 = $importer;
            
                        if(count($userimporters) > 0){
                            foreach ($userimporters as $userimporter) {
                                if ($userimporter["id_importador"] == $importer["id_importador"]) {
                                    $tempArray2               = $importer;
                                    $tempArray2["selected"]   = 1;
                                    $tempArray2["id_usuario"] = $idUser;
                                }
                            }
                        }
                        
                        if (!isset($tempArray2["selected"])) {
                            $db->setTable("previo.cprevc_usuimp");
                            $db->setValues(array(
                                "id_usuario"    => $idUser,
                                "id_importador" => $importer["id_importador"] ,
                            ));
                            $usersimporterAll = $db->insert();
                        }
                    }
                }else{
                    return '0Q';
                    exit();
                }
            }else{
                return '0P';
                exit();
            }
        } else {
            return '00';
            exit();
        }
    }
    
    public function userImportersUpdate($idUser, $data){
        $db = new PgsqlQueries;
        
        try {
            if ($data->selected == true) {
                $db->setTable("previo.cprevc_usulicimp");
                $db->setValues(array(
                    "id_usuario" => $idUser,
                    "id_licenciasimportador" => $data->id_licenciasimportador,
                ));
                $importers = $db->insert();
                
            } else {
                $db->setTable("previo.cprevc_usulicimp");
                $db->setParameters("id_usuario = $idUser AND id_licenciasimportador = $data->id_licenciasimportador");
                $importers = $db->delete();
            }

            return true;
            
        } catch (\Exception $e) {
            return false;
        }
    }
    
    
    public function getLicencesUser($idClient, $idUser, $query){
        $db = new PgsqlQueries;

        if ($idUser == NULL) {
            $idUser = 1;
        }
        
        if ($idClient == NULL) {
            $idClient = 1;
        }
        
        $db->setTable("'general'.casag_licencias L");
        $db->setJoin("INNER JOIN 'general'.casac_aduanas A ON L.id_aduana = A.id_aduana");
        $db->setFields(array(
            "L.id_licencia",
            "L.patente",
            "A.id_aduana",
            "A.clave_aduana",
            "A.nombre_aduana"
        ));
        
        if($query != null){
            $db->setParameters("L.id_cliente = $idClient AND (SP_ASCII(L.patente) iLIKE '%$query%' OR SP_ASCII(A.clave_aduana) iLIKE '%$query%') ORDER BY patente");
        } else {
            $db->setParameters("L.id_cliente = $idClient ORDER BY patente");
        }
       
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $licencesAll = $db->query();
        
        $db->setTable("general.casac_licusuario LI");
        $db->setJoins("");
        $db->setFields(array(
            "LI.id_licencia",
        ));
        $db->setParameters("LI.id_usuario = $idUser");
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $userlicences= $db->query();
        
        $tempArray = Array();
        $count = 0;
        foreach ($licencesAll as $licence) {
            $tempArray2 = Array();
            $tempArray2 = $licence;

            if(count($userlicences) > 0){
                foreach ($userlicences as $userlicence) {
                    if ($userlicence["id_licencia"] == $licence["id_licencia"]) {
                        $tempArray2 = $licence;
                        $tempArray2["selected"] = 1;
                        $tempArray2["id_usuario"] = $idUser;
                        $tempArray2["id_licusuario"] = $count;
                    }
                }
            }

            if (!isset($tempArray2["selected"])) {
                $tempArray2["selected"] = 0;
                $tempArray2["id_usuario"] = $idUser;
                $tempArray2["id_licusuario"] = $count;
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
     
    public function getImporterPermUser($idClient, $idUser, $query){
        $db = new PgsqlQueries;

        if ($idUser == NULL) {
            $idUser = 1;
        }
        
        if ($idClient == NULL) {
            $idClient = 1;
        }
       
        $db->setTable("'general'.casac_importadores I");
        $db->setFields(array(
            "I.nombre_importador",
            "I.rfc_importador",
            "I.id_importador",
            
        ));
        
        if($query != null){
            $db->setParameters("I.id_cliente = $idClient AND I.status_importador = 1  AND (SP_ASCII(I.nombre_importador) iLIKE '%$query%') ORDER BY I.id_importador ASC");
        } else{
            $db->setParameters("I.id_cliente = $idClient AND I.status_importador = 1 ORDER BY I.id_importador ASC");
        }
        
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $importersAll = $db->query();
        
        $db->setTable("'previo'.cprevc_usuimp UI");
        $db->setFields(array(
            "UI.id_importador"
            
        ));
        $db->setParameters("UI.id_usuario = $idUser");
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $userimporters= $db->query();
        
        $tempArray = Array();
        $count     = 0;
        
        foreach ($importersAll as $importer) {
            $tempArray2 = Array();
            $tempArray2 = $importer;

            if(count($userimporters) > 0){
                foreach ($userimporters as $userimporter) {
                    if ($userimporter["id_importador"] == $importer["id_importador"]) {
                        $tempArray2               = $importer;
                        $tempArray2["selected"]   = 1;
                        $tempArray2["id_usuario"] = $idUser;
                        $tempArray2["id_usuimp"]  = $count;
                    }
                }
            }
          
            if (!isset($tempArray2["selected"])) {
                $tempArray2["selected"]   = 0;
                $tempArray2["id_usuario"] = $idUser;
                $tempArray2["id_usuimp"]  = $count;
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
    
    public function userLicenceUpdate($idUser, $data, $idClient, $idTypeuser){

        $db = new PgsqlQueries;
        $db->setTable("'previo'.usuarios U");
        $db->setFields(array(
            "U.id_tipousuario",
            "U.id_cliente"
        ));
    
        $db->setParameters("U.id_usuario = '" . $idUser . "'");
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $users = $db->query();

        if(count($users) > 0){
            if($users[0]["id_cliente"] == $idClient){
                if($users[0]["id_tipousuario"] >= $idTypeuser){
                    try {
                        if ($data->selected == true) {
                            $db->setTable("general.casac_licusuario");
                            $db->setValues(array(
                                "id_usuario" => $idUser,
                                "id_licencia" => $data->id_licencia,
                            ));
                            $importers = $db->insert();
                        } else {
                            $db->setTable("general.casac_licusuario");
                            $db->setParameters("id_usuario = $idUser AND id_licencia = $data->id_licencia");
                            $importers = $db->delete();
                        }
                        return true;
                    } catch (\Exception $e) {
                        return false;
                    }
                }else{
                    return '0Q';
                    exit();
                }
            }else{
                return '0P';
                exit();
            }
        } else {
            return '00';
            exit();
        }
    }
    
    public function userImporterPermUpdate($idUser, $data, $idClient, $idTypeuser){
        $db = new PgsqlQueries;
        $db->setTable("'previo'.usuarios U");
        $db->setFields(array(
            "U.id_tipousuario",
            "U.id_cliente"
        ));
    
        $db->setParameters("U.id_usuario = '" . $idUser . "'");
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $users = $db->query();

        if(count($users) > 0){
            if($users[0]["id_cliente"] == $idClient){
                if($users[0]["id_tipousuario"] >= $idTypeuser){
    
                    try {
                        if ($data->selected == true) {
                            $db->setTable("previo.cprevc_usuimp");
                            $db->setValues(array(
                                "id_usuario"  => $idUser,
                                "id_importador" => $data->id_importador,
                            ));
                            $importers = $db->insert();
                            
                        } else {
                            $db->setTable("previo.cprevc_usuimp");
                            $db->setParameters("id_usuario = $idUser AND id_importador = $data->id_importador");
                            $importers = $db->delete();
                        }
            
                        return true;
                        
                    } catch (\Exception $e) {
                        return false;
                    }
                }else{
                    return '0Q';
                    exit();
                }
            }else{
                return '0P';
                exit();
            }
        } else {
            return '00';
            exit();
        }
    }
    
    public function getLicencesAll($idClient, $selectall, $query = null) {
        $db = new PgsqlQueries;
        
        $selected = 0;
        if ($selectall == "true") {
            $selected = 1;
        }

        if ($selectall == "false") {
            $selected = 0;
        }

        $db->setTable("'general'.casag_licencias L");
        $db->setJoin("INNER JOIN 'general'.casac_aduanas A ON L.id_aduana = A.id_aduana");
        $db->setFields(array(
            "L.id_licencia",
            "A.clave_aduana",
            "A.nombre_aduana",
            "L.patente",
            "$selected AS selected"
        ));
        
        if($query != null){
            $db->setParameters("L.id_cliente = $idClient AND (SP_ASCII(A.clave_aduana) iLIKE '%$query%' OR SP_ASCII(L.patente) iLIKE '%$query%' ) ORDER BY L.patente");
        } else{
            $db->setParameters("L.id_cliente = $idClient ORDER BY L.patente");
        }
               
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
    
        $importers = $db->query();
    
        if(count($importers) > 0) {
            return $importers;
        } else {
            return NULL;
        }
    }
}
?>