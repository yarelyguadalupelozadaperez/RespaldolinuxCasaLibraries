<?php

/**
 * CasaLibraries DeleteFracpar
 * File DeleteFracpar.php
 * DeleteFracpar Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			AJPE
 * @version    		Previo 1.0.0
 */

require_once 'CtracFracpar.php';
require_once 'CasaLibraries/CasaDb/ConnectionFactory.class.php';
class DeleteFracpar
{

    /**
     *
     * @var integer
     */
    public $idclient;

    /**
     *
     * @var \CtracFracpar[]
     */
    public $ctracfracpar;

        public function __construct($idclient, $ctracfracpar)
    {
        $this->setIdclient($idclient);
        $this->setCtracfracpar($ctracfracpar);
    }


    public function DeleteFracpar()
    {
        $dbAdoP = ConnectionFactory::Connectpostgres();
        $ctracfracparArray = $this->getCtracfracpar();
        $id_cliente = $this->getIdclient();

        if ($this->getIdclient() == '' || empty($ctracfracparArray) == true)
        throw new Exception("Datos incompletos. \nPor favor enviar todos los datos requeridos.");

        $db = new PgsqlQueries();
        $db->setTable('general.casac_clientes');
        $db->setFields(array(
            'id_cliente'
        ));
        
        $db->setParameters("id_cliente = '" . $this->getIdclient() . "'");
        $response = $db->query();

        $id_cliente = $response[0]["id_cliente"];

        if ($id_cliente>0) {
        } else {
            throw new Exception("No existe el cliente");
        }

        $arrayImp = array();
        $arrayIdFracPAr = array();
        $arrayImpbad1 = array();

        
        foreach ($ctracfracparArray as $CtracFracParArray => $value) {
            $id_fracpar =  $value->id_fracpar;          

            if ($id_fracpar == '') {

                array_push($arrayImpbad1, " id_fracpar: ".$id_fracpar);
                
            } else {

                $db->setTable('previo.ctrac_fracpar');
                $db->setFields(array(
                    'id_fracpar'
                ));
                $db->setParameters("id_fracpar = '" . $id_fracpar . "'");
                $response = $db->query();
                $id_fracpar = $response[0]["id_fracpar"];


                if ($id_fracpar != '') {
                    try {
                    	$dbu = new PgsqlConnection();
		            	$sql = "UPDATE previo." . "ctrac_fracpar" . " SET status_fracpar = 2 WHERE id_fracpar = " . $id_fracpar;
			            $dbu->execute($sql);
			        } catch (Exception $ex) {
                        $dbAdoP->rollbackTrans();
                        $this->setSuccess(false);
                        return "Ocurrió un error al eliminar la información de ctrac_fracpar." . $ex->getMessage();
                    }
                }else{
                    array_push($arrayIdFracPAr, " id_fracpar: ".$value->id_fracpar);
                }
            }

        }
        if (empty($arrayImpbad1)) {
            if (empty($arrayIdFracPAr)) {
                $this->setSuccess(true);
                return "Operacion exitosa";
            }else{
                $count = count($arrayIdFracPAr);
                $arrayResponse = array();

                for ($i=0; $i < $count; $i++) { 
                    array_push($arrayResponse, $arrayIdFracPAr[$i]);
                }

                $buildResponse = implode(" \n ", $arrayResponse);
                $this->setSuccess(true);
                return "Operación exitosa. No se encontró la clasificación con los datos:\n". $buildResponse;
            }

            
        } else {

            $count = count($arrayImpbad1);
            $arrayResponse = array();

            for ($i=0; $i < $count; $i++) { 
                array_push($arrayResponse, $arrayIdFracPAr[$i]);
            }

            $buildResponse = implode(" \n ", $arrayResponse);
            $this->setSuccess(false);
            return "Datos incompletos. \n". $buildResponse;
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
     * @param number $idclient            
     */
    public function setIdclient($idclient)
    {
        $this->idclient = $idclient;
    }


        /**
     *
     * @return the $ctracfracpar
     */
    public function getCtracfracpar()
    {
        return $this->ctracfracpar;
    }
        /**
     *
     * @param CtracFracpar[] $ctracfracpar            
     */
    public function setCtracfracpar($ctracfracpar)
    {
        $this->ctracfracpar = $ctracfracpar;
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
     * @param boolean $success
     */
    private function setSuccess($success)
    {
        $this->success = $success;
    }
}
?>