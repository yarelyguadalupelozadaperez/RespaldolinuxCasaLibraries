<?php

class DeleteImporter
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
     * @param string $rfc_importador
     * @param string $clave_import
     */
    
    public function __construct($idclient, $rfc_importador, $clave_import)
    {
        $this->setIdclient($idclient);
        $this->setRfc_importador($rfc_importador);
        $this->setClave_import($clave_import);
    }
    
    public function getDeleImporters()
    {
        if ($this->getIdclient() == '' || $this->getRfc_importador() == '' || $this->getClave_import() == ''){
            $this->setSuccess(false);
            $this->setMessageText("Datos requeridos incompletos.");
            return false;
        }else{
            
            $db = new PgsqlQueries();
            $db->setTable('"general".casac_clientes');
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
            
            
                    $db->setTable('"general".casag_licencias');
                    $db->setJoins("");
                    $db->setFields(array(
                        'id_licencia'
                    ));
                    $db->setParameters("id_cliente = " . $this->getIdclient() . " AND status_licencia = 1");
                    $db->setReturnType(PgsqlQueries::TYPE_OBJECT_ONE);
                    $response = $db->query();
                    $id_licencia = $response->id_licencia;

                    if ($id_licencia > 0) {
            
                        $db->setTable('"general".casac_importadores');
                        $db->setFields(array(
                            'id_importador',
                            'clave_importador'
                        ));
            
                        $db->setParameters("id_cliente = " . $this->getIdclient() . " AND clave_importador = '" . $this->getClave_import() . "'");

                        $importer = $db->query();

                        $idImporter = $importer->id_importador;
                        $clave_importador = $importer->clave_importador;
                       
                        if ($idImporter > 0) {
                            try {

                                $db->setTable("general.casac_importadores");
                                $db->setValues(array(
                                    "status_importador" => 0
                                ));
                                $db->setParameters("id_importador =  $idImporter");
                                $updateLicencias = $db->update();
                                
                                $db->setTable("'general'.casag_licencias L");
                                $db->setJoins("INNER JOIN 'general'.casag_licenciasistema LS ON L.id_licencia = LS.id_licencia");
                                $db->setFields(array(
                                    "LS.id_licenciasistema"
                                ));
                                $db->setParameters("L.id_cliente = '" . $this->getIdclient() . "'");
                                $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                                $licencessystems = $db->query();
                                $this->setSuccess(true);
                                $this->setMessageText("Se eliminó correctamente el Importador");
   
                            } catch (Exception $e) {
                                //var_dump($e->getMessage());
                                //exit;
                                 $this->setSuccess(false);
                                $this->setMessageText("Error: " . $e->getMessage());
                                return false;
                            }
                        }else{
                            throw new Exception('No existe el Importador por lo cual no se eliminó nada');
                        }
                    } else {
                        throw new Exception('Licencia Inactiva');
                    }
                
            } else {
                throw new Exception("No existe el Cliente");
            }
        }
    }            
    
    /**
     * @return the $idclient
     */
    public function getIdclient()
    {
        return $this->idclient;
    }

    /**
     * @return the $rfc_importador
     */
    public function getRfc_importador()
    {
        return $this->rfc_importador;
    }

    /**
     * @return the $clave_import
     */
    public function getClave_import()
    {
        return $this->clave_import;
    }

    /**
     * @return the $success
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * @return the $messageText
     */
    public function getMessageText()
    {
        return $this->messageText;
    }

    /**
     * @param number $idclient
     */
    public function setIdclient($idclient)
    {
        $this->idclient = $idclient;
    }


    /**
     * @param string $rfc_importador
     */
    public function setRfc_importador($rfc_importador)
    {
        $this->rfc_importador = $rfc_importador;
    }

    /**
     * @param string $clave_import
     */
    public function setClave_import($clave_import)
    {
        $this->clave_import = $clave_import;
    }

    /**
     * @param boolean $success
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }

    /**
     * @param string $messageText
     */
    public function setMessageText($messageText)
    {
        $this->messageText = $messageText;
    }
}

?>