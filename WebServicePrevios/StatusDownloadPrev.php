<?php

/**
 * CasaLibraries StatusDownloadPrev
 * File StatusDownloadPrev.php
 * StatusDownloadPrev Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			AJPE
 * @version    		Previo 1.0.0
 */

require_once 'Clasificaciones.php';
require_once 'CasaLibraries/CasaDb/ConnectionFactory.class.php';
class StatusDownloadPrev
{

    /**
     *
     * @var integer
     */
    public $idclient;

    /**
     *
     * @var integer
     */
    public $idprevious;

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
     * @var integer
     */
    public $id_proc;

    /**
     * 
     * @var string
     */
    public $num_refe;



        public function __construct($idclient, $idprevious, $idgdb, $tip_prev, $id_proc, $num_refe)
    {
        $this->setIdclient($idclient);
        $this->setIdprevious($idprevious);
        $this->setIdgdb($idgdb);
        $this->setTip_prev($tip_prev);
        $this->setId_proc($id_proc);
        $this->setNum_refe($num_refe);
    }


    public function StatusDownloadPrev()
    {
        $dbAdoP = ConnectionFactory::Connectpostgres();

        $id_cliente = $this->getIdclient();
        $idprevious = $this->getIdprevious();
        $idgdb = $this->getIdgdb();
        $tip_prev = $this->getTip_prev();
        $id_proc = $this->getId_proc();
        $num_refe = $this->getNum_refe();

        if($tip_prev == ''){
            $tip_prev = 0;
        }

        if ($id_proc == 2){
            if ($id_cliente == '' || $num_refe == '')
                throw new Exception("Datos incompletos. \nPor favor enviar todos los datos requeridos.");
        }else{
            if ($id_cliente == '' || $idprevious == '' || $idgdb == '')
                throw new Exception("Datos incompletos. \nPor favor enviar todos los datos requeridos.");
        }

        $db = new PgsqlQueries();
        $db->setTable('general.casac_clientes');
        $db->setFields(array('id_cliente'));
        $db->setParameters("id_cliente = '" . $this->getIdclient() . "'");
        $response = $db->query();
        $id_cliente = $response[0]["id_cliente"];

        if ($id_cliente<=0) {
            throw new Exception("No existe el cliente");
        }

        if ($id_proc != 2){
            $db = new PgsqlQueries();
            $db->setTable('previo.cprevo_refe');
            $db->setFields(array('id_prev'));
            $db->setParameters("id_prev = '" . $idprevious . "'");
            $response = $db->query();
            $idprevios = $response[0]["id_prev"];

            if ($idprevios<=0) {
                throw new Exception("No existe el previo");
            }
            
            $db->setTable('previo.cprevo_descar');
            $db->setFields(array('id_descarga'));
            $db->setParameters("id_prev = " . $idprevious . " AND id_gdb = '" . $idgdb . "' AND tip_prev_d = ".$tip_prev);
            $response = $db->query();
            $idDescT = $response[0]["id_descarga"];

            if ($idDescT >0) {
                $this->setSuccess(true);
                return "Operacion exitosa";
            } else {
                try {
                    $idPrevio = $this->getIdprevious();
                    $idGdb = $this->getIdgdb();
    
                    $sqlNewDownload1="SELECT setval ( 'previo.cprevo_descar_id_descarga_seq' , ( SELECT  MAX (id_descarga) FROM previo.cprevo_descar) + 1 )"; 
                    $saveDownload2 = $dbAdoP->Execute ( $sqlNewDownload1 );
    
                    $sqlNewDownload= "INSERT INTO previo.cprevo_descar(id_prev, fec_desca, id_gdb, tip_prev_d)"
                        . " VALUES($idprevious, 'TODAY()', $idgdb, $tip_prev) RETURNING id_descarga";
                    $saveDownload = $dbAdoP->Execute ( $sqlNewDownload );
                    $dbAdoP->commitTrans();
    
                    $this->setSuccess(true);
                    return "Operacion exitosa";
    
    
                } catch (Exception $ex) {
                    $dbAdoP->rollbackTrans();
                    $this->setSuccess(false);
                    return "Operacion fallida Error: ".$ex;
                }
            }
        }else{
            $db = new PgsqlQueries();
            $db->setTable('previo.cprevo_refe');
            $db->setFields(array('num_refe', 'id_prev'));
            $db->setParameters("num_refe = '" . $num_refe . "'");
            $response = $db->query();
            $numRefeExist = $response[0]["num_refe"];
            $idPrevExist = $response[0]["id_prev"];
            
            if ($numRefeExist=='') {
                throw new Exception("No existe la refrencia.");
            }

            $db->setTable('previo.cprevo_descar');
            $db->setFields(array('id_descarga'));
            $db->setParameters("id_prev = " . $idPrevExist . " AND id_gdb = 1");
            $response = $db->query();
            $idDescExist = $response[0]["id_descarga"];

            if($idDescExist!= ''){
                try {
                    $sqlNewReference = "DELETE FROM previo.cprevo_descar WHERE id_descarga = $idDescExist";
                    $response = $dbAdoP->Execute ( $sqlNewReference );
                    $this->setSuccess(true);
                    return "Se reactivó la descarga de la referencia satisfactoriamente.";
                } catch (Exception $ex) {
                    $this->setSuccess(false);
                    return $e->getMessage();
                }
            }else{
                $this->setSuccess(true);
                return "No existe la referencia.";
            }
        
            
        } 

    }
    
    /**
     * @param string $num_refe
     */
    public function setNum_refe($num_refe)
    {
        $this->num_refe = $num_refe;
    }

    /**
     *
     * @return the $num_refe
     */
    public function getNum_refe()
    {
        return $this->num_refe;
    }

    /**
     *
     * @param number $id_proc            
     */
    public function setId_proc($id_proc)
    {
        $this->id_proc = $id_proc;
    }

    /**
     *
     * @return the $id_proc
     */
    public function getId_proc()
    {
        return $this->id_proc;
    }

    /**
     *
     * @param number $tip_prev            
     */
    public function setTip_prev($tip_prev)
    {
        $this->tip_prev = $tip_prev;
    }

    /**
     *
     * @return the $tip_prev
     */
    public function getTip_prev()
    {
        return $this->tip_prev;
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
     * @return the $idprevious
     */
    public function getIdprevious()
    {
        return $this->idprevious;
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
     * @param number $idclient            
     */
    public function setIdclient($idclient)
    {
        $this->idclient = $idclient;
    }

    /**
     *
     * @param number $idprevious            
     */
    public function setIdprevious($idprevious)
    {
        $this->idprevious = $idprevious;
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
     * @param boolean $success
     */
    private function setSuccess($success)
    {
        $this->success = $success;
    }
}
?>
