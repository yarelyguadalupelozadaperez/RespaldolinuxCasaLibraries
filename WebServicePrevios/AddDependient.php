<?php
/**
 * CasaLibraries AddDependient
 * File AddDependient.php
 * AddDependient Class
 *
 * @category        CasaLibraries
 * @package            CasaLibraries_Previo
 * @copyright          Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author            SMV
 * @version            Previo 1.0.0
 */
require_once 'CasaLibraries/CasaDb/ConnectionFactory.class.php';

class AddDependient {
    /**
     *
     * @var integer
     */
    public $id_cliente;

    /**
     *
     * @var string
     */
    public $clave_aduana;

     /**
     *
     * @var string
     */
    public $patente;

    /**
     *
     * @var string
     */
    public $cve_depe;

    /**
     *
     * @var string
     */
    public $nom_depe;

    /**
     *
     * @var string
     */
    public $rfc_depe;

    /**
     *
     * @var string
     */
    public $gaf_depe;


    /**
     *
     * @var string
     */
    public $alt_depe;

    /**
     *
     * @var string
     */
    public $des_depe;


    /**
     *
     * @var string
     */
    public $usu_depe;


    /**
     *
     * @var string
     */
    public $usu_pass;

    /**
     *
     * @var string
     */
    public $vig_depe;

    /**
     *
     * @var string
     */
    public $capt_fracc;
    
     /**
     *
     * @var string
     */
    public $ws_previo;
    
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
    * @param integer $id_dependiente
    * @param integer $id_prev
    * @param string  $nom_dependiente
    */

    function __construct($id_cliente, $clave_aduana, $patente, $cve_depe, $nom_depe, $rfc_depe, $gaf_depe, $alt_depe, $des_depe, $usu_depe, $usu_pass, $vig_depe, $ws_previo, $capt_fracc) {
        $this->cve_depe     = $cve_depe;
        $this->nom_depe     = $nom_depe;
        $this->rfc_depe     = $rfc_depe;
        $this->gaf_depe     = $gaf_depe;
        $this->alt_depe     = $alt_depe;
        $this->des_depe     = $des_depe;
        $this->usu_depe     = $usu_depe;
        $this->usu_pass     = $usu_pass;
        $this->id_cliente   = $id_cliente;
        $this->clave_aduana = $clave_aduana;
        $this->patente      = $patente;
        $this->vig_depe     = $vig_depe;
        $this->ws_previo    = $ws_previo;
        $this->capt_fracc    = $capt_fracc;
        
   
    }

    /**
     *
     * @param integer $id_dependiente
     * @param integer $id_prev
     */

    public function getAddDependient() {
        if ($this->getId_cliente() == '' ||$this->getClave_aduana() == '' || $this->getPatente() == '' || $this->getCve_depe() == '' || $this->getUsu_depe() == '') {
            $this->setSuccess(false);
            $this->setMessageText("Datos requeridos incompletos.  - ID Cliente: " . $this->getId_cliente() . " - Clave Aduana: " . $this->getClave_aduana() . " - Patente:  " .  $this->getPatente() . " - Clave dependiente: " . $this->getCve_depe() . " - Usuario: " . $this->getUsu_depe());
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
                $db->setParameters("id_cliente = '" . $this->getId_cliente() . "'");
                $db->setReturnType(PgsqlQueries::TYPE_OBJECT_ONE);
                $responseCliente = $db->query();


                $id_cliente = $responseCliente->id_cliente;

                if ($id_cliente > 0) {
                    $db = new PgsqlQueries();
                    $db->setTable('general.casac_aduanas');
                    $db->setJoins("");
                    $db->setFields(array(
                        'id_aduana',
                        'clave_aduana',
                        'nombre_aduana'
                    ));
                    $db->setParameters("clave_aduana = '" . $this->getClave_aduana() . "'");
                    $db->setReturnType(PgsqlQueries::TYPE_OBJECT_ONE);
                    $responseAduana = $db->query();

                    $id_aduana = $responseAduana->id_aduana;

                    if($id_aduana > 0){
                        $db->setTable("general.casag_licencias L");
                        $db->setJoins("");
                        $db->setFields(array(
                            "L.id_licencia"
                        ));
                        $db->setParameters("L.id_cliente = '" . $this->getId_cliente() . "' AND L.id_Aduana = '$id_aduana' AND L.patente = '".$this->getPatente()."'");
                        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                        $licencessystems = $db->query();

                        $id_licencia = $licencessystems[0]['id_licencia'];

                        if($id_licencia != NULL){
                            $db->setTable('general.ctrac_depen');
                            $db->setJoins("");
                            $db->setFields(array(
                                'id_depe',
                                'cve_depe',
                                'nom_depe',
                                'rfc_depe',
                                'gaf_depe',
                                'vig_depe',
                                'alt_depe',
                                'des_depe',
                                'usu_depe',
                                'usu_pass',
                                'estatus',
                                'id_licencia',
                                'ws_previo',
                                'capt_fracc'
                            ));
                            $db->setParameters("cve_depe = '" . $this->getCve_depe() . "' AND id_licencia = $id_licencia");
                            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                            $responseDependiente = $db->query();
                            
                            if(count($responseDependiente) > 0){
                                $id_depe  = $responseDependiente[0]["id_depe"];
                                $vig_depe = $this->getVig_depe();
                                $ws_previo = $this->getWs_previo();
                                
                                if($ws_previo == 'B'){
                                    $status = 2 ;
                                    $message = "Se dio de baja el dependiente en web";
                                    $error = "Ocurrió un error al dar de baja el dependiente en web /n ";

                                    if($vig_depe == ''){
                                        $vig_depen = null;
                                        
                                    }else{
                                        $vig_depen = $this->getVig_depe();
                                        
                                    }
                                } else {
                                    if ($vig_depe == ''){
                                        $vig_depen = null;
                                    } else{
                                        $vig_depen = $this->getVig_depe();
                                    }
                                    $status = 1;
                                    $message = "Se agregó correctamente el dependiente en web. /n";
                                    $error = "Ocurrió un error al insertar el dependiente en web /n ";
                                    
                                }
                                
                                $nom_depe = $this->getNom_depe();
                                $rfc_depe = $this->getRfc_depe();
                                $des_depe = $this->getDes_depe();
                                $usu_depe = $this->getUsu_depe();
                                $usu_pass = $this->getUsu_pass();
                                $gaf_depe = $this->getGaf_depe();
                                $capt_fracc = $this->getCapt_fracc();
                                
                                if($capt_fracc == null) {
                                    $capt_fracc = $responseDependiente[0]["capt_fracc"];
                                    if($capt_fracc == null){
                                        $capt_fracc = 0;
                                    }
                                } 
                               
                                try {
                                    $db->setTable("general.ctrac_depen");
                                    $db->setValues(array(
                                        "nom_depe" => $nom_depe,
                                        "rfc_depe" => $rfc_depe,
                                        "vig_depe" => $vig_depe,
                                        "vig_depe" => $vig_depen,
                                        "des_depe" => $des_depe,
                                        "usu_depe" => $usu_depe,
                                        "usu_pass" => $usu_pass,
                                        "estatus" => $status,
                                        "gaf_depe" => $gaf_depe,
                                        "ws_previo" => $ws_previo,
                                        "capt_fracc" => $capt_fracc
                                    ));

                                    $db->setParameters("id_depe = $id_depe");

                                    $updateDependients = $db->update();

                                    $this->setSuccess(true);
                                    $this->setMessageText($message);
                                    return true;

                                } catch (Exception $e) {
                                    throw new Exception($error .$e->getMessage());
                                }
                                
                            } else {
                                try {
                                    if($this->getWs_previo() == 'B'){
                                        $ws_previo = 'B';
                                        $vig_dependient = $this->getVig_depe();
                                        $status = 2;
                                        $mensaje = "Se agrego correctamente el dependiente con estatus de baja en web.";
                                        if($vig_dependient == ''){
                                            $vig_depen = null;
                                        }else{
                                            $vig_depen = $this->getVig_depe();
                                        }
                                        
                                    }else{
                                        $vig_dependient = $this->getVig_depe();
                                        $mensaje = "Se agrego correctamente el dependiente en web.";
                                        $ws_previo = 'A';
                                        if ($vig_dependient == ''){
                                            $vig_depen = null;
                                        } else{
                                            $vig_depen = $this->getVig_depe();
                                        }
                                        $status = 1;
                                        
                                    }
                                    
                                    $capt_fracc = $this->getCapt_fracc();
                                
                                    if($capt_fracc == null) {
                                        $capt_fracc = 0;
                                    } 
                                    
                                    $db->setTable("general.ctrac_depen");
                                    $db->setValues(array(
                                        "cve_depe"    => $this->cve_depe,
                                        "nom_depe"    => $this->nom_depe,
                                        "rfc_depe"    => $this->rfc_depe,
                                        "gaf_depe"    => $this->gaf_depe,
                                        "alt_depe"    => $this->alt_depe,
                                        "usu_depe"    => $this->usu_depe,
                                        "usu_pass"    => $this->usu_pass,
                                        "estatus"     => $status,
                                        "id_licencia" => $id_licencia,
                                        "vig_depe"    => $vig_depen,
                                        "des_depe"    => $this->des_depe,
                                        "ws_previo"   => $ws_previo,
                                        "capt_fracc"  => $capt_fracc
                                        
                                    ));
                                   
                                    $addDependients = $db->insert();
                                     
                                    $this->setSuccess(true);
                                    $this->setMessageText($mensaje);
                                    return true;

                                } catch (Exception $e) {
                                    $this->setSuccess(false);
                                    $this->setMessageText("Ocurrio un error al agregar el dependiente en web. " . $e->getMessage());
                                    return $e->getMessage();
                                }
                            }

                        }else{
                             throw new Exception('No existe la licencia');
                        }

                    }else{
                        throw new Exception('No existe la aduana');
                    }

                }else{
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
     * @return the $patente
     */
    function getPatente() {
        return $this->patente;
    }

    /**
     *
     * @param string $patente
     */
    function setPatente($patente) {
        $this->patente = $patente;
    }

          /**
     *
     * @return the $clave_aduana
     */
    function getClave_aduana() {
        return $this->clave_aduana;
    }

      /**
     *
     * @param string $clave_aduana
     */
    function setClave_aduana($clave_aduana) {
        $this->clave_aduana = $clave_aduana;
    }


      /**
     *
     * @return the $id_cliente
     */
    function getId_cliente() {
        return $this->id_cliente;
    }

       /**
     *
     * @param int $id_cliente
     */
    function setId_cliente($id_cliente) {
        $this->id_cliente = $id_cliente;
    }


      /**
     *
     * @return the $cve_depe
     */
     function getCve_depe() {
         return $this->cve_depe;
     }
   /**
     *
     * @return the $nom_depe
     */
     function getNom_depe() {
         return $this->nom_depe;
     }
   /**
     *
     * @return the $rfc_depe
     */
     function getRfc_depe() {
         return $this->rfc_depe;
     }
   /**
     *
     * @return the $gaf_depe
     */
     function getGaf_depe() {
         return $this->gaf_depe;
     }
   /**
     *
     * @return the $alt_depe
     */
     function getAlt_depe() {
         return $this->alt_depe;
     }
   /**
     *
     * @return the $des_depe
     */
     function getDes_depe() {
         return $this->des_depe;
     }
   /**
     *
     * @return the $usu_depe
     */
     function getUsu_depe() {
         return $this->usu_depe;
     }
   /**
     *
     * @return the $usu_pass
     */
     function getUsu_pass() {
         return $this->usu_pass;
     }
     /**
     *
     * @param the $vig_depe
     */
     function getVig_depe() {
        return $this->vig_depe;
     }
     
    /**
     *
     * @return the $ws_previo
     */
     function getWs_previo() {
         return $this->ws_previo;
     }
     
   /**
     *
     * @param the $id_depe
     */
     function setId_depe($id_depe) {
         $this->id_depe = $id_depe;
     }
   /**
     *
     * @param the $cve_depe
     */
     function setCve_depe($cve_depe) {
         $this->cve_depe = $cve_depe;
     }
   /**
     *
     * @param the $nom_depe
     */
     function setNom_depe($nom_depe) {
         $this->nom_depe = $nom_depe;
     }
   /**
     *
     * @param the $rfc_depe
     */
     function setRfc_depe($rfc_depe) {
         $this->rfc_depe = $rfc_depe;
     }
   /**
     *
     * @param the $gaf_depe
     */
     function setGaf_depe($gaf_depe) {
         $this->gaf_depe = $gaf_depe;
     }
   /**
     *
     * @param the $alt_depe
     */
     function setAlt_depe($alt_depe) {
         $this->alt_depe = $alt_depe;
     }
   /**
     *
     * @param the $des_depe
     */
     function setDes_depe($des_depe) {
         $this->des_depe = $des_depe;
     }
   /**
     *
     * @param the $usu_depe
     */
     function setUsu_depe($usu_depe) {
         $this->usu_depe = $usu_depe;
     }
   /**
     *
     * @param the $usu_pass
     */
     function setUsu_pass($usu_pass) {
         $this->usu_pass = $usu_pass;
     }
    /**
     *
     * @param the $vig_depe
     */
     function setVig_depe($vig_depe) {
         $this->vig_depe = $vig_depe;
     }
     
     /**
     *
     * @param the $ws_previo
     */
     function setWs_previo($ws_previo) {
         $this->nws_previo = $ws_previo;
     }
     
    /**
     *
     * @param the $capt_fracc
     */
     function setCapt_fracc($capt_fracc) {
         $this->capt_fracc = $capt_fracc;
     }
     
    /**
     *
     * @param the $capt_fracc
     */
     function getCapt_fracc() {
        return $this->capt_fracc;
     }
     
    /**
     *
     * @return boolean $success
     */
    public function getSuccess() {
        return $this->success;
    }

    /**
     *
     * @return the $messageText
     */

    public function getMessageText() {
        return $this->messageText;
    }
    /**
     *
     * @param string $success
     */
    public function setSuccess($success) {
        $this->success = $success;
    }

    /**
     *
     * @param string $messageText
     */
    public function setMessageText($messageText) {
        $this->messageText = $messageText;
    }

}
?>
