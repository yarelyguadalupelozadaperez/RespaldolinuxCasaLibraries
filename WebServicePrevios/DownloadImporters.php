<?php

/**
 * CasaLibraries DownloadImporters
 * File DownloadImporters.php
 * DownloadImporters Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			Yarely Guadalupe Lozada Pérez
 * @version    		Previo 1.0.0
 */
require_once 'DataDownImporters.php';
require_once 'Importers.php';

class DownloadImporters
{

    /**
     *
     * @var integer
     */
    public $idcliente;

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
     * DataDownImporters Object
     *
     * @var \DataDownImporters
     */
    private $dataDownImporters;

    /**
     *
     * @param integer $idcliente                                
     */
    public function __construct($idcliente)
    {
        $this->setIdcliente($idcliente);
    }

    /**
     * @throws Exception
     * @return boolean
     */
    public function getDataImporters()
    {
        if ($this->getIdcliente() == '') {
            $this->setSuccess(false);
            $this->setMessageText("Datos requeridos incompletos.");
            return false;
        } else {
           try {
                $db = new PgsqlConnection();
                $table = "'general'.casac_importadores I";
                $joins = "";
                $fieldsArray = array(
                    "I.id_importador",
                    "I.rfc_importador",
                    "I.nombre_importador"
                );
                $parameters = "I.id_cliente = '" . $this->getIdcliente() . "' AND  I.status_importador = 1 ORDER BY I.rfc_importador ASC";
                $importers = $db->query($table, $fieldsArray, $joins, $parameters);

                $generalArray = array();
                $DownloadImporters = new DataDownImporters();
                
                $count = 0;
                if($importers){
                    $importersArray = array();
                    foreach($importers as $value) {
                        $Importers = new Importers();
                        $Importers->setRfc_importador($value["rfc_importador"]);
                        $Importers->setNombre_importador($value["nombre_importador"]);
                        $importersArray[] = $Importers;
                    }
                    $DownloadImporters->setImporters($importersArray);
                    $generalArray[] = $DownloadImporters;
                    
                    $this->setSuccess(true);
                    $this->setMessageText("Descarga correcta");
                    $this->setDataDownImporters($generalArray);
         
                } else {
                    throw new Exception("No existen importadores para descargar");
                }
  
            } catch (Exception $e) {
                $this->setSuccess(false);
                $this->setMessageText("Error: " . $e->getMessage());
                return false;
            }
        }
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
    
    /**
     * @return the $idcliente
     */
    public function getIdcliente()
    {
        return $this->idcliente;
    }

    /**
     * @return the $dataDownImporters
     */
    public function getDataDownImporters()
    {
        return $this->dataDownImporters;
    }

    /**
     * @param number $idcliente
     */
    public function setIdcliente($idcliente)
    {
        $this->idcliente = $idcliente;
    }

    /**
     * @param DataDownImporters $dataDownImporters
     */
    public function setDataDownImporters($dataDownImporters)
    {
        $this->dataDownImporters = $dataDownImporters;
    }
    
}

?>
