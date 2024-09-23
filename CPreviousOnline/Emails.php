<?php

include 'CasaLibraries/CasaDb/PgsqlQueries3.php';
include 'CasaLibraries/CasaDb/PgsqlQueries.php';
require_once 'CasaLibraries/ExportationReports/ExportToExcel.php';


class Emails {
    public function deselectAllImporters($idClient, $id_correo, $id_aduana, $id_licencia, $patente) {
        try {
            $db = new PgsqlQueries;
            
            $db->setTable("'general'.casac_importadores I");
            $db->setJoins("INNER JOIN 'general'.casag_licencias L ON I.id_cliente = L.id_cliente");
            $db->setJoin("INNER JOIN 'general'.casac_aduanas A ON L.id_aduana = A.id_aduana");
            $db->setFields(array(
                "DISTINCT(I.id_importador)",
                "I.nombre_importador",
                "I.rfc_importador",
                "A.id_aduana",
                "L.patente"
            ));
            $db->setParameters("I.id_cliente = $idClient AND A.id_aduana = $id_aduana AND L.patente = '$patente'");
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $importers = $db->query();

            
            $db->setTable("'previo'.correos_licenciaimportador CLI");
            $db->setJoins("INNER JOIN 'general'.casac_importadores I ON CLI.id_importador = I.id_importador");
            $db->setJoin("INNER JOIN 'previo'.correos CO ON CLI.id_correo = CO.id_correo");
            $db->setJoin("INNER JOIN 'general'.casag_licencias L ON CLI.id_licencia = L.id_licencia");
            $db->setJoin("INNER JOIN 'general'.casac_aduanas A ON L.id_aduana = A.id_aduana");
            $db->setFields(array(
                "DISTINCT(I.id_importador)",
                "CLI.id_licencia",
                "CLI.id_correo",
                "A.id_aduana"
            ));
            $db->setParameters("I.id_cliente = $idClient AND CLI.id_correo = $id_correo AND A.id_aduana = $id_aduana AND L.patente = '$patente'");
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $emailslicimpo= $db->query();

            $tempArray = Array();

            foreach ($importers as $importer) {
                $tempArray2 = Array();
                $tempArray2 = $importer;

                if(count($emailslicimpo) > 0){
                    
                    foreach ($emailslicimpo as $emaillicimpo) {
                        if ($emaillicimpo["id_importador"] == $importer["id_importador"]) {
                            $tempArray2 = $importer;
                            $tempArray2["selected"] = 0;
                            
                            if($tempArray2["selected"] == 0) {
                                $db->setTable("previo.correos_licenciaimportador");
                                $db->setParameters("id_correo = $id_correo AND id_licencia = $id_licencia");
                                $importers = $db->delete();
                            }
                        }
                    }
                }
            }
            
        }catch(Exception $e){
            return false;
            exit;
        }
    }
    
    public function selectAllImporters($idClient, $id_correo, $id_aduana, $id_licencia, $patente) {
        try {
            $db = new PgsqlQueries;
        
            $db->setTable("'general'.casac_importadores I");
            $db->setJoins("INNER JOIN 'general'.casag_licencias L ON I.id_cliente = L.id_cliente");
            $db->setJoin("INNER JOIN 'general'.casac_aduanas A ON L.id_aduana = A.id_aduana");
            $db->setFields(array(
                "DISTINCT(I.id_importador)",
                "I.nombre_importador",
                "I.rfc_importador",
                "A.id_aduana",
                "L.patente"
            ));
            $db->setParameters("I.id_cliente = $idClient AND A.id_aduana = $id_aduana AND L.patente = '$patente'");
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $importers = $db->query();

            $db->setTable("'previo'.correos_licenciaimportador CLI");
            $db->setJoins("INNER JOIN 'general'.casac_importadores I ON CLI.id_importador = I.id_importador");
            $db->setJoin("INNER JOIN 'previo'.correos CO ON CLI.id_correo = CO.id_correo");
            $db->setJoin("INNER JOIN 'general'.casag_licencias L ON CLI.id_licencia = L.id_licencia");
            $db->setJoin("INNER JOIN 'general'.casac_aduanas A ON L.id_aduana = A.id_aduana");
            $db->setFields(array(
                "DISTINCT(I.id_importador)",
                "CLI.id_licencia",
                "CLI.id_correo",
                "A.id_aduana"
            ));
            $db->setParameters("I.id_cliente = $idClient AND CLI.id_correo = $id_correo AND A.id_aduana = $id_aduana AND L.patente = '$patente'");
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $emailslicimpo= $db->query();

           $tempArray = Array();

            foreach ($importers as $importer) {
                $tempArray2 = Array();
                $tempArray2 = $importer;

                if(count($emailslicimpo) > 0){
                    foreach ($emailslicimpo as $emaillicimpo) {
                        if ($emaillicimpo["id_importador"] == $importer["id_importador"]) {
                            $tempArray2 = $importer;
                            $tempArray2["selected"] = 1;
                            $tempArray2["id_correo"] = $id_correo;
                            $tempArray2["id_licencia"] = $id_licencia;
                        }
                    }
                }

                if (!isset($tempArray2["selected"])) {
                    $db->setTable("previo.correos_licenciaimportador");
                    $db->setValues(array(
                        "id_correo" => $id_correo,
                        "id_licencia" => $id_licencia,
                        "id_importador" => $importer["id_importador"]
                    ));
                    $emailsimporterAll = $db->insert();
                }
            }
            
        }catch(Exception $e){
            return false;
            exit;
        }
    }
    
    public function deleteInfoEmail($id_correo) {
        try {
            $db = new PgsqlQueries;

            $db->setTable("'previo'.correos C");
            $db->setJoins("INNER JOIN 'previo'.correos_licenciaimportador CLI ON C.id_correo = CLI.id_correo");
            $db->setFields(array(
                "*"
            ));
            $db->setParameters("C.id_correo = $id_correo");
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $emails = $db->query();
         
            foreach ($emails as $email) {
                $id_correo_licimpo = $email["id_correo_licenciaimportador"];
                $idCorreo         = $email["id_correo"];
                
                $db->setTable("previo.correos_licenciaimportador");
                $db->setParameters("id_correo_licenciaimportador = $id_correo_licimpo AND id_correo = $idCorreo");
                $deleteCLI = $db->delete();
            }
            
            $db->setTable("previo.correos");
            $db->setParameters("id_correo = $id_correo");
            $deleteC = $db->delete();
            
            return true;
            exit;
           
        }catch(Exception $e){
            return false;
            exit;
        }
    }
    
    public function getInfoEmail($nombre, $correo) {
        $db = new PgsqlQueries;
        
        $db->setTable("'previo'.correos");
        $db->setFields(array(
            "*"
        ));
        $db->setParameters("nombre_destinatario = '$nombre' AND correo_destinatario = '$correo'");
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ONE);
        $emails = $db->query();
        
        $id_correo = $emails["id_correo"];
        
        if(count($id_correo) > 0) {
            return $id_correo;
        } else {
            return NULL;
        }
    }
    
    public function getLicensesUpdate($idClient, $id_correo) {
        $db = new PgsqlQueries;
        
        if($id_correo == null){
            $id_correo = 0;
            
        }else {
            $db->setTable("'general'.casag_licencias L");
            $db->setJoins("INNER JOIN 'general'.casac_aduanas A ON L.id_aduana = A.id_aduana");
            $db->setJoin("INNER JOIN 'general'.casac_clientes C ON L.id_cliente = C.id_cliente");
            $db->setFields(array(
                "DISTINCT(L.id_licencia)",
                "L.patente",
                "A.id_aduana",
                "A.clave_aduana"
            ));
            $db->setParameters("C.id_cliente = $idClient ORDER BY L.patente");
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $licenses = $db->query();
            
            $db->setTable("'previo'.correos_licenciaimportador CLI");
            $db->setJoins("INNER JOIN 'general'.casag_licencias L ON CLI.id_licencia = L.id_licencia");
            $db->setJoin("INNER JOIN 'previo'.correos CO ON CLI.id_correo = CO.id_correo");
            $db->setFields(array(
                "DISTINCT(L.id_licencia)",
                "CLI.id_importador",
                "CLI.id_correo"

            ));
            $db->setParameters("L.id_cliente = $idClient AND CLI.id_correo = $id_correo");
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $licensesemails= $db->query();
           
            $tempArray = Array();
            $count     = 0;

            foreach ($licenses as $license) {
                $tempArray2 = Array();
                $tempArray2 = $license;

                if(count($licensesemails) > 0){
                    foreach ($licensesemails as $licenseemail) {
                        if ($licenseemail["id_licencia"] == $license["id_licencia"]) {
                            $tempArray2 = $license;
                            
                            $tempArray2["selected"]                     = 1;
                            $tempArray2["id_correo"]                    = $id_correo;
                            $tempArray2["id_correo_licenciaimportador"] = $count;
                        }
                    }
                }

                if (!isset($tempArray2["selected"])) {
                    $tempArray2["selected"]                     = 0;
                    $tempArray2["id_correo"]                    = $id_correo;
                    $tempArray2["id_correo_licenciaimportador"] = $count;
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
    
    public function importersAdd($data, $id_correo, $id_licencia, $patente, $id_aduana) {
        $db = new PgsqlQueries;

        try {
            $db->setTable("'general'.casag_licencias");
            $db->setFields(array(
                "*",
            ));
            $db->setParameters("id_licencia = $id_licencia AND patente = '$patente'");
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $customs = $db->query();
            
            $idLicencia = $customs[0]["id_licencia"];
           
            if ($data->selected == true) {
                $db->setTable("previo.correos_licenciaimportador");
                $db->setValues(array(
                    "id_correo" => $id_correo,
                    "id_licencia" => $idLicencia,
                    "id_importador" => $data->id_importador
                ));
                
                $importers = $db->insert();
       
            } else if($data->selected == false) {
                $db->setTable("previo.correos_licenciaimportador");
                $db->setParameters("id_correo = $id_correo AND id_licencia = $idLicencia AND id_importador = $data->id_importador");
                $importers = $db->delete();
            }

            return true;
            
        } catch (\Exception $e) {
            return false;
        }
    }
    
    public function importersUpdate($data, $id_correo, $id_licencia, $patente, $id_aduana) {
        $db = new PgsqlQueries;
  
        try {
            $db->setTable("'general'.casag_licencias");
            $db->setFields(array(
                "*",
            ));
            $db->setParameters("id_licencia = $id_licencia AND patente = '$patente'");
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $customs = $db->query();
            
            $idLicencia = $customs[0]["id_licencia"];
           
            if ($data->selected == true) {
                $db->setTable("previo.correos_licenciaimportador");
                $db->setValues(array(
                    "id_correo" => $id_correo,
                    "id_licencia" => $idLicencia,
                    "id_importador" => $data->id_importador
                ));
                
                $importers = $db->insert();
       
            } else if($data->selected == false) {
                $db->setTable("previo.correos_licenciaimportador");
                $db->setParameters("id_correo = $id_correo AND id_licencia = $idLicencia AND id_importador = $data->id_importador");
                $importers = $db->delete();
            }

            return true;
            
        } catch (\Exception $e) {
            return false;
        }
    }
    
    public function getImportersUpdate($idClient, $query = null, $id_correo, $id_aduana, $patente) {
        $db = new PgsqlQueries;
      
        if($id_correo == null){
            $id_correo = 0;
            
        }else {
            if($id_aduana == null) {
                $id_aduana = 0;
            } else {
                $db->setTable("'general'.casac_importadores I");
                $db->setJoins("INNER JOIN 'general'.casag_licencias L ON I.id_cliente = L.id_cliente");
                $db->setJoin("INNER JOIN 'general'.casac_aduanas A ON L.id_aduana = A.id_aduana");
                $db->setFields(array(
                    "DISTINCT(I.id_importador)",
                    "I.nombre_importador",
                    "I.rfc_importador",
                    "A.id_aduana",
                    "L.patente"
                ));
                /*$db->setParameters("I.id_cliente = $idClient AND A.id_aduana = $id_aduana ORDER BY I.id_importador ASC");
                $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                $importers = $db->query();*/
                
                if($query != null){
                    $db->setParameters("I.id_cliente = $idClient AND A.id_aduana = $id_aduana AND L.patente = '$patente' AND (SP_ASCII(I.nombre_importador) iLIKE '%$query%') ORDER BY I.id_importador ASC");
                } else {
                    $db->setParameters("I.id_cliente = $idClient AND A.id_aduana = $id_aduana AND L.patente = '$patente' ORDER BY I.id_importador ASC");
                }
                $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                $importers = $db->query();

                
                $db->setTable("'previo'.correos_licenciaimportador CLI");
                $db->setJoins("INNER JOIN 'general'.casac_importadores I ON CLI.id_importador = I.id_importador");
                $db->setJoin("INNER JOIN 'previo'.correos CO ON CLI.id_correo = CO.id_correo");
                $db->setJoin("INNER JOIN 'general'.casag_licencias L ON CLI.id_licencia = L.id_licencia");
                $db->setJoin("INNER JOIN 'general'.casac_aduanas A ON L.id_aduana = A.id_aduana");
                $db->setFields(array(
                    "DISTINCT(I.id_importador)",
                    "I.nombre_importador",
                    "CLI.id_licencia",
                    "CLI.id_correo",
                    "A.id_aduana"
                ));
                $db->setParameters("I.id_cliente = $idClient AND CLI.id_correo = $id_correo AND A.id_aduana = $id_aduana AND L.patente = '$patente'");
                $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                $importersemails= $db->query();
                 
                $tempArray = Array();
                $count     = 0;

                foreach ($importers as $importer) {
                    $tempArray2 = Array();
                    $tempArray2 = $importer;

                    if(count($importersemails) > 0){
                        foreach ($importersemails as $importeremail) {
                            if ($importeremail["id_importador"] == $importer["id_importador"]) {
                                $tempArray2 = $importer;

                                $tempArray2["selected"]                     = 1;
                                $tempArray2["id_correo"]                    = $id_correo;
                                $tempArray2["id_correo_licenciaimportador"] = $count;
                            }
                        }
                    }

                    if (!isset($tempArray2["selected"])) {
                        $tempArray2["selected"]                     = 0;
                        $tempArray2["id_correo"]                    = $id_correo;
                        $tempArray2["id_correo_licenciaimportador"] = $count;
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
    }
    
    public function getEmailUpdate($id_correo) {
        $db = new PgsqlQueries;
        
        $db->setTable("'previo'.correos CO");
        $db->setJoins("INNER JOIN 'previo'.correos_licenciaimportador CLI ON CO.id_correo = CLI.id_correo");
        $db->setFields(array(
            "*"
        ));
        $db->setParameters("CO.id_correo = $id_correo");
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ONE);
        $emails = $db->query();

        if(count($emails) > 0) {
            return $emails;
        } else {
            return NULL;
        }
    }
    
    public function getImporters($idClient, $query, $id_correo, $id_aduana, $patente, $id_licencia) {
        $db = new PgsqlQueries;
        
        if($id_correo == null){
            $id_correo = 0;
            
        }else {
            if($id_aduana == null) {
                $id_aduana = 0;
            } else {
                $db->setTable("'general'.casac_importadores I");
                $db->setJoins("INNER JOIN 'general'.casag_licencias L ON I.id_cliente = L.id_cliente");
                $db->setJoin("INNER JOIN 'general'.casac_aduanas A ON L.id_aduana = A.id_aduana");
                $db->setFields(array(
                    "DISTINCT(I.id_importador)",
                    "I.nombre_importador",
                    "I.rfc_importador",
                    "A.id_aduana",

                    "L.patente"
                ));

                if($query != null){
                    $db->setParameters("I.id_cliente = $idClient AND A.id_aduana = $id_aduana AND (SP_ASCII(I.nombre_importador) iLIKE '%$query%') ORDER BY I.id_importador ASC");
                } else {
                    $db->setParameters("I.id_cliente = $idClient AND A.id_aduana = $id_aduana ORDER BY I.id_importador ASC");
                }
                $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                $importers = $db->query();

                $db->setTable("'previo'.correos_licenciaimportador CLI");
                $db->setJoins("INNER JOIN 'general'.casac_importadores I ON CLI.id_importador = I.id_importador");
                $db->setJoin("INNER JOIN 'previo'.correos CO ON CLI.id_correo = CO.id_correo");
                $db->setJoin("INNER JOIN 'general'.casag_licencias L ON CLI.id_licencia = L.id_licencia");
                $db->setJoin("INNER JOIN 'general'.casac_aduanas A ON L.id_aduana = A.id_aduana");
                $db->setFields(array(
                    "DISTINCT(I.id_importador)",
                    "CLI.id_licencia",
                    "CLI.id_correo",
                    "A.id_aduana"
                ));
                $db->setParameters("I.id_cliente = $idClient AND CLI.id_correo = $id_correo AND A.id_aduana = $id_aduana AND L.patente = '$patente'");
                $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                $importersemails= $db->query();

                $tempArray = Array();
                $count     = 0;

                foreach($importers as $importer){
                    $tempArray2 = Array();
                    $tempArray2 = $importer;

                    if(count($importersemails) > 0){
                        foreach ($importersemails as $importeremail) {
                            if ($importeremail["id_importador"] == $importer["id_importador"]) {
                                $tempArray2 = $importer;
                                $tempArray2["selected"]                     = 1;
                                $tempArray2["id_correo"]                    = $id_correo;
                                $tempArray2["id_licencia"]                  =  $id_licencia;
                                $tempArray2["id_correo_licenciaimportador"] = $count;
                            }
                        }
                    }

                     if (!isset($tempArray2["selected"])) {
                        $tempArray2["selected"]                     = 0;
                        $tempArray2["id_correo"]                    = $id_correo;
                        $tempArray2["id_licencia"]                  =  $id_licencia;
                        $tempArray2["id_correo_licenciaimportador"] = $count;
                    }

                    $count++;
                    $tempArray[] = $tempArray2;
                }

                if(count($tempArray) > 0) {
                    return $tempArray;
                } else {
                    return NULL;
                }
            }
        }
    }
    
    public function getEmails($idUser, $idTypeuser, $idClient, $start, $limit) {
        $db = new PgsqlQueries;

        $db->setTable("'previo'.correos CO");
        $db->setJoins("INNER JOIN 'general'.casac_clientes C ON CO.id_cliente = C.id_cliente");
        $db->setFields(array(
            "CO.id_correo",
            "CO.nombre_destinatario",
            "CO.correo_destinatario",
            "CO.id_cliente",
        ));

        if($idTypeuser == 1){ 
            $db->setParameters("CO.id_cliente = $idClient  LIMIT $limit OFFSET $start");
        } else {
            $db->setParameters("CO.id_cliente = $idClient  LIMIT $limit OFFSET $start");
        }

        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $emails = $db->query();

        $db->setTable("'previo'.correos CO");
        $db->setJoins("INNER JOIN 'general'.casac_clientes C ON CO.id_cliente = C.id_cliente");
        $db->setFields(array(
            "CO.id_correo",
            "CO.nombre_destinatario",
            "CO.correo_destinatario",
            "CO.id_cliente",
        ));

        if($idTypeuser == 1){
            $db->setParameters("CO.id_cliente = $idClient");
        } else {
            $db->setParameters("CO.id_cliente = $idClient");
        }

        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $emails2 = $db->query();

        $emails['total'] = count($emails2);

        if(count($emails) > 1) {
            return $emails;
        } else {
            return NULL;
        }
    }
    
    public function deleteEmail ($id_email, $id_license, $id_importer){
        try {
            $db = new PgsqlQueries;

            $db->setTable("'previo'.correos C");
            $db->setJoins("INNER JOIN 'previo'.correos_licenciaimportador CLI ON C.id_correo = CLI.id_correo");
            $db->setFields(array(
                "*"
            ));
            $db->setParameters("CLI.id_correo = $id_email");
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $emails = $db->query();

            foreach ($emails as $email) {
                $id_correo_licimpo = $email["id_correo_licenciaimportador"];
                $id_correo         = $email["id_correo"];
                
                $db->setTable("previo.correos_licenciaimportador");
                $db->setParameters("id_correo_licenciaimportador = $id_correo_licimpo AND id_correo = $id_correo");
                $deleteCLI = $db->delete();
            }
            
            $db->setTable("previo.correos");
            $db->setParameters("id_correo = $id_email");
            $deleteC = $db->delete();
            
            return true;
            exit;
           
        }catch(Exception $e){
            return false;
            exit;
        }
    }
    
    public function saveEmail($nombre, $correo, $idClient){
        $db = new PgsqlQueries;
        $db->setTable("'previo'.correos CO");
        $db->setJoins("INNER JOIN 'general'.casac_clientes C ON CO.id_cliente = C.id_cliente");
        $db->setFields(array(
            "*",
        ));
        $db->setParameters("CO.correo_destinatario = '$correo' AND CO.id_cliente = $idClient");
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ONE);
        $emails = $db->query();
     
        if(count($emails) != 1) {
            return null;
        } else {
            try {
                $db->setTable("previo.correos");
                $db->setValues(array(
                    "nombre_destinatario" => $nombre,
                    "correo_destinatario" => $correo,
                    "id_cliente" => $idClient
                ));
                $email = $db->insert();
                
                return true;
                exit();
                
            } catch (Exception $e) {
                return false;
                exit;
            }
        }
    }
    
    public function getLicenses($idUser, $idTypeuser, $idClient, $query) {
        $db = new PgsqlQueries;
        
        $selected = 0;
        
        $db->setTable("'general'.casag_licencias L");
        $db->setJoins("INNER JOIN 'general'.casac_aduanas A ON L.id_aduana = A.id_aduana");
        $db->setJoin("INNER JOIN 'general'.casac_clientes C ON L.id_cliente = C.id_cliente");
        $db->setFields(array(
            "DISTINCT(L.id_licencia)",
            "L.patente",
            "A.id_aduana",
            "A.clave_aduana",
            "$selected AS selected"
        ));
        
        if($query != null){
            $db->setParameters("C.id_cliente = $idClient AND (SP_ASCII(L.patente) iLIKE '%$query%' OR SP_ASCII(A.clave_aduana) iLIKE '%$query%') ORDER BY L.patente");
        } else {
            $db->setParameters("C.id_cliente = $idClient ORDER BY L.patente");
        }
        
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $licenses = $db->query();
        
        if(count($licenses) > 0) {
            return $licenses;
        } else {
            return NULL;
        }
    }
}



?>

