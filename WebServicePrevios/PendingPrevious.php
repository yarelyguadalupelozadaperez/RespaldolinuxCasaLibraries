<?php

/**
 * CasaLibraries AddPrevious
 * File PendingPrevious.php
 * PendingPrevious Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			SMV
 * @version    		Previo 1.0.0
 */

require_once 'CasaLibraries/WebServicePrevios/PendingDown.php';

class PendingPrevious
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
    public $aduana;
    
    
    /**
     *
     * @var string
     */
    public $patente;


    /**
     *
     * @var integer
     */
    public $idgdb;

    /**
     *
     * @var integer
     */
    public $tip_prev;

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
     * @param string $aduana            
     * @param string $idgdb 
     * @param integer $patente  
     * @param integer $flag
     * @param integer $tip_prev           
     */
    public function __construct($idclient, $aduana, $idgdb, $patente, $flag, $tip_prev)
    {
        $this->setIdclient($idclient);
        $this->setAduana($aduana);
        $this->setIdgdb($idgdb);
        $this->setPatente($patente);
        $this->setTip_prev($tip_prev);
    }

    public function pendingFilesData()
    {
        if ($this->getIdclient() == '' || $this->getAduana() == '' || $this->getIdgdb() == "" ) {
            throw new Exception("Datos incompletos");
        } else {
            $db = new PgsqlQueries();
            
            $db->setTable('"general".casac_aduanas');
            $db->setFields(array(
                'id_aduana'
            ));
            
            $db->setParameters("clave_aduana = '" . $this->getAduana() . "'");
            $db->setReturnType(PgsqlQueries::TYPE_OBJECT_ONE);
            $response = $db->query();
            
            $id_aduana = $response->id_aduana;
            
            if ($id_aduana > 0) {
                $db->setTable('"general".casag_licencias');
                $db->setFields(array(
                    'id_licencia',
                    'status_licencia'
                ));
                $db->setParameters("id_cliente = " . $this->getIdclient() . " AND id_aduana = $id_aduana" . " AND patente = '" . $this->getPatente() . "'");
                //$db->setParameters("id_cliente = " . $this->getIdclient() . " AND id_aduana = $id_aduana");

                $license = $db->query();
               
                
                $licenseId = $license->id_licencia;
                
                if ($licenseId > 0) {
                    
                    if($license->status_licencia == 0){
                        throw new Exception("La licencia del cliente se encuentra inactiva");
                    }

         
                    if($this->getTip_prev() != NULL || $this->getTip_prev() != 0){
                        if ($this->getTip_prev() == 1)   {
                            //"Enviaré la lista de los previos pendientes por descargar con solicitud.");
                            $db->setTable('"previo".cprevo_refe R');
                            $db->setJoins('INNER JOIN "previo".cprevo_previos P ON R.id_prev = P.id_prev');
                            $db->setJoin('INNER JOIN "general".casac_importadores I ON P.id_importador = I.id_importador');
                            $db->setJoin('LEFT JOIN "previo".cprevo_descar D ON P.id_prev = D.id_prev AND D.id_gdb = ' . $this->getIdgdb());
                            
                            $db->setFields(array(
                                'R.id_prev',
                                'R.num_refe',
                                'P.num_guia',
                                'I.rfc_importador',
                                'D.id_gdb',
                                'P.flag_version',
                                'R.estatus_refe'
                            ));

                            $db->setParameters("R.id_licencia = " . $licenseId . " AND P.fol_soli <> -1 AND D.id_gdb IS NULL AND R.estatus_refe = 3 ORDER BY R.id_prev");
                            
                            $db->setReturnType(PgsqlQueries::TYPE_OBJECT_ALL);
                            $pendingPrevious = $db->query();
                        }

                    } else {
                        //"Enviaré la lista de los previos pendientes por descargar sin solicitud.");

                        $db->setTable('"previo".cprevo_refe R');
                        $db->setJoins('INNER JOIN "previo".cprevo_previos P ON R.id_prev = P.id_prev');
                        $db->setJoin('INNER JOIN "general".casac_importadores I ON P.id_importador = I.id_importador');
                        $db->setJoin('LEFT JOIN "previo".cprevo_descar D ON P.id_prev = D.id_prev AND D.id_gdb = ' . $this->getIdgdb());
                        
                        $db->setFields(array(
                            'R.id_prev',
                            'R.num_refe',
                            'P.num_guia',
                            'I.rfc_importador',
                            'D.id_gdb',
                            'P.flag_version',
                            'R.estatus_refe'
                        ));
    
                        if($this->getIdclient() == 820 && $this->getAduana() == 520){
                            $db->setParameters("R.id_licencia = " . $licenseId . " AND P.fec_soli > '2019-09-30' AND P.fol_soli = -1 AND D.id_gdb IS NULL AND R.estatus_refe = 3 ORDER BY R.id_prev");
                        } else {
                          
                            $db->setParameters("R.id_licencia = " . $licenseId . " AND P.fol_soli = -1 AND D.id_gdb IS NULL AND R.estatus_refe = 3 ORDER BY R.id_prev");
                        }
                        
                        $db->setReturnType(PgsqlQueries::TYPE_OBJECT_ALL);
                        $pendingPrevious = $db->query();
                    }
            
                    if(!$pendingPrevious){
                        throw new Exception("No existen archivos a Descargar");
                        exit;
                    }
                    
                    $resultArray = Array();
                    foreach ($pendingPrevious as $value) {
                        $valuesObj = new PendingDown($value->id_prev, $value->num_refe, $value->num_guia, $value->rfc_importador);
                        $resultArray [] = $valuesObj;
                    }
                    
                    if($resultArray){
                        return $resultArray;
                    } else {
                        throw new Exception("No existen referencias para este cliente y aduana");
                    }
                    
                    
                } else {
                    throw new Exception("La licencia no se encuentra dada de alta");
                }
            } else {
                throw new Exception("La aduana no existe");
            }
            
            exit();
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
     * @return the $aduana
     */
    public function getAduana()
    {
        return $this->aduana;
    }

    /**
     *
     * @return the $patente
     */
    public function getPatente()
    {
        return $this->patente;
    }
    
    /**
     *
     * @return the $idgdb
     */
    public function getIdgdb()
    {
        return $this->idgdb;
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
     * @param string $aduana            
     */
    public function setAduana($aduana)
    {
        $this->aduana = $aduana;
    }
    
    /**
     *
     * @param string $patente           
     */
    public function setPatente($patente)
    {
        $this->patente = $patente;
    }

    /**
     *
     * @param number $idgdb            
     */
    public function setIdgdb($idgdb)
    {
        $this->idgdb = $idgdb;
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
     * Get the value of tip_prev
     *
     * @return  integer
     */ 
    public function getTip_prev()
    {
        return $this->tip_prev;
    }

    /**
     * Set the value of tip_prev
     *
     * @param  integer  $tip_prev
     *
     * @return  self
     */ 
    public function setTip_prev($tip_prev)
    {
        $this->tip_prev = $tip_prev;
        return $this;
    }
}

?>