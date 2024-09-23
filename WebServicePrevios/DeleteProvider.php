<?php
/**
 * CasaLibraries AddProvider
 * File AddProvider.php
 * AddProvider Class
 *
 * @category    CasaLibraries
 * @package       CasaLibraries_Previo
 * @copyright     Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author      AJPE
 * @version       Previo 1.0.0
 */
class DeleteProvider
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
    public $imp_exp;

    /**
     *
     * @var string
     */
    public $cve_pro;
    
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
     * @param string $cve_pro
     */
    
    public function __construct($idclient, $cve_pro, $imp_exp)
    {
        $this->setIdclient($idclient);
        $this->setCve_pro($cve_pro);
        $this->setImp_exp($imp_exp);
    }
    
    public function getDeleteProviders()
    {

        if ($this->getIdclient() == '' || $this->getCve_pro() == ''|| !is_numeric($this->getImp_exp())){
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

                        $db->setTable('"general".casac_proveedores');
                        $db->setFields(array(
                            'id_proveedor',
                            'cve_prov'
                        ));
            
                        $db->setParameters("id_cliente = " . $this->getIdclient() . " AND cve_prov = '" . $this->getCve_pro() . "' AND imp_exp = '" . $this->getImp_exp()  . "'");  
                        $provider = $db->query();

                        $id_proveedor = $provider->id_proveedor;
                        $cve_prov = $provider->cve_prov;
                       
                        if ($id_proveedor > 0) {
                            try {

                                $db->setTable("general.casac_proveedores");
                                $db->setValues(array(
                                    "status_prov" => 0
                                ));
                                $db->setParameters("id_proveedor =  $id_proveedor");
                                $updateLicencias = $db->update();
                                
                                $this->setSuccess(true);
                                $this->setMessageText("Se eliminó correctamente el Proveedor");
   
                            } catch (Exception $e) {

                                $this->setSuccess(false);
                                $this->setMessageText("Error: " . $e->getMessage());
                                return false;
                            }
                        }else{
                            throw new Exception('No existe el Proveedor por lo cual no se eliminó nada');
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
     * @return the $cve_pro
     */
    public function getCve_pro()
    {
        return $this->cve_pro;
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
     * @param string $cve_pro
     */
    public function setCve_pro($cve_pro)
    {
        $this->cve_pro = $cve_pro;
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

    /**
     * @return the $imp_exp
     */
    public function getImp_exp()
    {
        return $this->imp_exp;
    }

    /**
     * @param number $imp_exp
     */
    public function setImp_exp($imp_exp)
    {
        $this->imp_exp = $imp_exp;
    }
}

?>