<?php
/**
 * CasaLibraries AddImporters
 * File AddImporters.php
 * AddImporters Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			SMV
 * @version    		Previo 1.0.0
 */
class AddImporter
{
    

    /**
     *
     * @var integer
     */
    public $idclient;

    /**
     *
     * @var string
     */
    public $rfc_importador;

    /**
     *
     * @var string
     */
    public $clave_import;

    /**
     *
     * @var string
     */
    public $nombre_import;

    /**
     *
     * @var string
     */
    public $domicilio_import;

    /**
     *
     * @var boolean
     */
    private $success;

    /**
     * Text of messegae
     *
     * @var string
     */
    private $messageText;

    /**
     *
     * @param integer $idclient            
     * @param string  $rfc_importador    
     * @param integer $clave_import  
     * @param string  $nombre_import  
     * @param string  $domicilio_import          
     */
    public function __construct($idclient, $rfc_importador, $clave_import, $nombre_import, $domicilio_import)
    {
        $this->setIdclient($idclient);
        $this->setRfc_importador($rfc_importador);
        $this->setClave_import($clave_import);
        $this->setNombre_import($nombre_import);
        $this->setDomicilio_import($domicilio_import);
    }

    /**
     *
     * @param integer $idprevious            
     * @param integer $idgdb            
     */
    public function getAddImporters()
    {
        if ($this->getIdclient() == '' || $this->getRfc_importador() == '' || $this->getClave_import() == '') {
            $this->setSuccess(false);
            $this->setMessageText("Datos requeridos incompletos.");
            return false;
        } else {
            
            try {
                $db = new PgsqlQueries();
                $db->setTable('general.casac_clientes');
                $db->setJoins("");
                $db->setFields(array(
                    'id_cliente',
                    'nombre_cliente'
                ));
                $db->setParameters("id_cliente = '" . $this->getIdclient() . "'");
                $db->setReturnType(PgsqlQueries::TYPE_OBJECT_ONE);
                $response = $db->query();
                $id_cliente = $response->id_cliente;
                $nombre_cliente = $response->nombre_cliente;
                
                if ($id_cliente > 0) {
                    $db->setTable("general.casag_licencias L");
                    $db->setJoins("");
                    $db->setFields(array(
                        "L.id_licencia"
                    ));
                    $db->setParameters("L.id_cliente = '" . $this->getIdclient() . "'");
                    $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                    $licencessystems = $db->query();
                    $id_licencia = $licencessystems[0]['id_licencia'];
                    
                    if($id_licencia != NULL){
                        $db->setTable('general.casac_importadores');
                        $db->setJoins("");
                        $db->setFields(array(
                            'id_importador',
                            'clave_importador',
                            'rfc_importador',
                            'status_importador'
                        ));

                        $rfc     = $this->getRfc_importador();
                        $rfcTrim = trim($rfc);
                        
                        $db->setParameters("id_cliente = " . $this->getIdclient() . " AND rfc_importador = '" . $rfcTrim . "'");
                        $importer = $db->query();
                    
                        $idImporter = $importer[0]['id_importador'];

                        if(!$importer){
                            try {
                                $db->setSql('SELECT nextval(\'general.casac_importadores_id_importador_seq\'::regclass)');
                                $nextId = $db->execute();
                                $lastIdImporter = $nextId[0]["nextval"];
                                
                                $rfcImp     = $this->rfc_importador;
                                $rfcImpTrim = trim($rfcImp);
                                
                                $db->setTable("general.casac_importadores");
                                $db->setValues(array(
                                    "id_importador" => $lastIdImporter,
                                    "id_cliente" => $this->idclient,
                                    "rfc_importador" => $rfcImpTrim,
                                    "nombre_importador" => trim($this->nombre_import),
                                    "domicilio_importador" => trim($this->domicilio_import),
                                    "clave_importador" => trim($this->clave_import),
                                    "status_importador" => 1
                                ));
                            
                                $addImporters = $db->insert();
                                
                                $db->setTable('general.casag_licencias L');
                                $db->setJoins("INNER JOIN general.casac_aduanas A ON L.id_aduana = A.id_aduana");
                                $db->setFields(array(
                                    'L.id_licencia',
                                    'A.clave_aduana'
                                ));
                                
                                $db->setParameters("L.id_cliente = " . $this->getIdclient() ."");
                                $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                                $array = $db->query();

                                foreach ($array as $value){
                                    $idLicence = $value["id_licencia"];
                                    $custom = $value["clave_aduana"];
                                    $resultLicences .= $custom . ", ";
                                    
                                    $db->setTable('general.casag_licenciasistema');
                                    $db->setJoins("");
                                    $db->setFields(array(
                                        'id_licenciasistema'
                                    ));
                                    
                                    $db->setParameters("id_licencia = $idLicence AND id_sistema = 2");
                                    $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                                    $arraylicenseSistema = $db->query();
                                }
                                
                                $licencesimporters = substr($resultLicences, 0, - 2);
                                $this->setSuccess(true);
                                $this->setMessageText("Se agrego correctamente el Importador en la web");
                                return true;
                                
                            } catch (Exception $e) {
                                $this->setSuccess(false);
                                return $e->getMessage();
                            }
                        } else {
                            if($importer[0]['status_importador'] != 1){

                                if($importer[0]['status_importador'] == $this->clave_import){

                                    try {
                                        $db->setTable("general.casac_importadores");
                                        $db->setValues(array(
                                            "status_importador" => 1
                                        ));
                                    
                                        $db->setParameters("id_importador = $idImporter");
                                        $updateImporters = $db->update();
                                        
                                        $db->setTable('general.casag_licencias L');
                                            $db->setJoins("INNER JOIN general.casac_aduanas A ON L.id_aduana = A.id_aduana");
                                        $db->setFields(array(
                                            'L.id_licencia',
                                            'A.clave_aduana'
                                        
                                        ));
                                        
                                        $db->setParameters("L.id_cliente = " . $this->getIdclient() ."");
                                        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                                        $array = $db->query();
                                        
                                        foreach ($array as $value){
                                            $idLicence = $value["id_licencia"];
                                            $custom = $value["clave_aduana"];
                                            $resultLicences .= $custom . ", ";
                                            
                                            $db->setTable('general.casag_licenciasistema');
                                            $db->setJoins("");
                                            $db->setFields(array(
                                                'id_licenciasistema'
                                            ));
                                            
                                            $db->setParameters("id_licencia = $idLicence AND id_sistema = 2");
                                            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                                            $arraylicenseSistema = $db->query();
                                        
                                            $count = 0;
                                        
                                        }
                                    
                                        $this->setSuccess(true);
                                        $this->setMessageText("Se agrego correctamente el Importador en la web");
                                        return true;
                                    
                                    } catch (Exception $e) {
                                        throw new Exception("Ocurrió un error al insertar el importador en la web/n ".$e->getMessage());
                                    }
                                }else{
                                    try {
                                        $db->setSql('SELECT nextval(\'general.casac_importadores_id_importador_seq\'::regclass)');
                                        $nextId = $db->execute();
                                        $lastIdImporter = $nextId[0]["nextval"];
                                        
                                        $rfcImp     = $this->rfc_importador;
                                        $rfcImpTrim = trim($rfcImp);
                                        
                                        $db->setTable("general.casac_importadores");
                                        $db->setValues(array(
                                            "id_importador" => $lastIdImporter,
                                            "id_cliente" => $this->idclient,
                                            "rfc_importador" => $rfcImpTrim,
                                            "nombre_importador" => trim($this->nombre_import),
                                            "domicilio_importador" => trim($this->domicilio_import),
                                            "clave_importador" => trim($this->clave_import),
                                            "status_importador" => 1
                                        ));
                                    
                                        $addImporters = $db->insert();
                                        
                                        $db->setTable('general.casag_licencias L');
                                        $db->setJoins("INNER JOIN general.casac_aduanas A ON L.id_aduana = A.id_aduana");
                                        $db->setFields(array(
                                            'L.id_licencia',
                                            'A.clave_aduana'
                                        ));
                                        
                                        $db->setParameters("L.id_cliente = " . $this->getIdclient() ."");
                                        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                                        $array = $db->query();
        
                                        foreach ($array as $value){
                                            $idLicence = $value["id_licencia"];
                                            $custom = $value["clave_aduana"];
                                            $resultLicences .= $custom . ", ";
                                            
                                            $db->setTable('general.casag_licenciasistema');
                                            $db->setJoins("");
                                            $db->setFields(array(
                                                'id_licenciasistema'
                                            ));
                                            
                                            $db->setParameters("id_licencia = $idLicence AND id_sistema = 2");
                                            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                                            $arraylicenseSistema = $db->query();
                                        }
                                        
                                        $licencesimporters = substr($resultLicences, 0, - 2);
                                        $this->setSuccess(true);
                                        $this->setMessageText("Se agrego correctamente el Importador en la web");
                                        return true;
                                        
                                    } catch (Exception $e) {
                                        $this->setSuccess(false);
                                        return $e->getMessage();
                                    }
                                }

                            }else{
                                $this->setSuccess(true);
                                $this->setMessageText("Se agrego correctamente el importador en la web");
                                return true;
                            }
                        }
                        
                    }
            
                } else {
                    throw new Exception('No existe el cliente');
                }
                
                
            } catch (Exception $e) {      
                $this->setSuccess(false);
                $this->setMessageText("Error: " . $e->getMessage());
            }
        }
}

    /**
     *
     * @return the $idclient
     */
    public function getIdclient()
    {
        return $this->idclient;
    }


    /**
     *
     * @return the $rfc_importador
     */
    public function getRfc_importador()
    {
        return $this->rfc_importador;
    }

    /**
     *
     * @return the $clave_import
     */
    public function getClave_import()
    {
        return $this->clave_import;
    }

    /**
     *
     * @return the $nombre_import
     */
    public function getNombre_import()
    {
        return $this->nombre_import;
    }

    /**
     *
     * @return the $domicilio_import
     */
    public function getDomicilio_import()
    {
        return $this->domicilio_import;
    }

    /**
     *
     * @return the $success
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     *
     * @return the $messageText
     */
    public function getMessageText()
    {
        return $this->messageText;
    }

    /**
     *
     * @param number $idclient            
     */
    public function setIdclient($idclient)
    {
        $this->idclient = $idclient;
    }


    /**
     *
     * @param string $rfc_importador            
     */
    public function setRfc_importador($rfc_importador)
    {
        $this->rfc_importador = $rfc_importador;
    }

    /**
     *
     * @param string $clave_import            
     */
    public function setClave_import($clave_import)
    {
        $this->clave_import = $clave_import;
    }

    /**
     *
     * @param string $nombre_import            
     */
    public function setNombre_import($nombre_import)
    {
        $this->nombre_import = $nombre_import;
    }

    /**
     *
     * @param string $domicilio_import            
     */
    public function setDomicilio_import($domicilio_import)
    {
        $this->domicilio_import = $domicilio_import;
    }

    /**
     *
     * @param boolean $success            
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }

    /**
     *
     * @param string $messageText            
     */
    public function setMessageText($messageText)
    {
        $this->messageText = $messageText;
    }
}
?>
