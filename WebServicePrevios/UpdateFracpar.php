<?php

/**
 * CasaLibraries UpdateFracpar
 * File UpdateFracpar.php
 * UpdateFracpar Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			AJPE
 * @version    		Previo 1.0.0
 */


require_once 'CasaLibraries/CasaDb/ConnectionFactory.class.php';
class UpdateFracpar
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
    public $id_fracpar;


    /**
     *
     * @var integer
     */
    public $uni_fact;

    /**
     *
     * @var string
     */
    public $desc_merc;

    /**
     *
     * @var integer
     */
    public $val_part;
    


        public function __construct($idclient, $id_fracpar, $uni_fact, $desc_merc, $val_part)
    {
        $this->setIdclient($idclient);
        $this->setId_fracpar($id_fracpar);
        $this->setUni_fact($uni_fact);
        $this->setDesc_merc($desc_merc);
        $this->setVal_part($val_part);
        
    }

    public function UpdateFracpar()
    {
    	$dbAdoP = ConnectionFactory::Connectpostgres();

        if ($this->getIdclient() == '' || $this->getId_fracpar() == '')
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

		$db->setTable('previo.ctrac_fracpar');
		$db->setFields(array( 'id_fracpar',  'uni_fact', 'desc_merc'));
		$db->setParameters("id_cliente = " . $this->getIdclient() . " AND id_fracpar = '" . $this->getId_fracpar() . "'");
		$fracpar = $db->query();
		$idFracpar = $fracpar[0]["id_fracpar"];

		if ($idFracpar>0) {
			try {

                if($this->getUni_fact() == ''  || $this->getUni_fact() == 0){
                    $uni_fact =  $fracpar[0]["uni_fact"];
                } else {
                    $uni_fact = $this->getUni_fact();
                }

                if($this->getDesc_merc() != ''){
                    $desc_merc = $this->getDesc_merc();
                } else {
                    $desc_merc =  $fracpar[0]["desc_merc"]; 
                }

                if($this->getVal_part() != ''){
                    $val_part = $this->getVal_part();
                } else {
                    $val_part =  $fracpar[0]["val_part"]; 
                }
                
				$db->setTable("previo.ctrac_fracpar");
	            $db->setValues(array( 
	            	"uni_fact" => $uni_fact,
	            	"desc_merc" => $desc_merc,
                    "val_part" =>  $val_part
	             ));
	            $db->setParameters("id_fracpar =  $idFracpar AND id_cliente = $id_cliente");
	            $updateLicencias = $db->update();

				$this->setSuccess(true);
				return "Operacion exitosa";
			} catch (Exception $ex) {
				$dbAdoP->rollbackTrans();
				$this->setSuccess(false);
				return "Ocurrió un error al guardar la información de ctrac_fracpar." . $ex->getMessage();
			}
			
		} else {
		    throw new Exception("No existe la clasificación con el ID: ".$this->getId_fracpar());
		}
    }

    /**
     * @return the $desc_merc
     */
    public function getDesc_merc()
    {
        return $this->desc_merc;
    }
     /**
     * @param string $desc_merc
     */
    public function setDesc_merc($desc_merc)
    {
        $this->desc_merc = $desc_merc;
    }

    /**
     * @return the $uni_fact
     */
    public function getUni_fact()
    {
        return $this->uni_fact;
    }
     /**
     * @param string $uni_fact
     */
    public function setUni_fact($uni_fact)
    {
        $this->uni_fact = $uni_fact;
    }

    /**
     *
     * @return the $id_fracpar
     */
    public function getId_fracpar()
    {
        return $this->id_fracpar;
    }
    /**
     *
     * @param number $id_fracpar            
     */
    public function setId_fracpar($id_fracpar)
    {
        $this->id_fracpar = $id_fracpar;
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
     * @param boolean $success
     */
    private function setSuccess($success)
    {
        $this->success = $success;
    }

      /**
     *
     * @return the $val_part
     */
    public function getVal_part()
    {
        return $this->val_part;
    }
    /**
     *
     * @param number $val_part            
     */
    public function setVal_part($val_part)
    {
        $this->val_part = $val_part;
    }

}
?>