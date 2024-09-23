<?php
/**
 * CasaLibraries AddPrevious
 * File Bultos.php
 * Bultos Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			SMV
 * @version    		Previo 1.0.0
 */



class Bultos
{

    /**
     *
     * @var string
     */
    public $clave_bulto;
    
    /**
     *
     * @var integer
     */
    public $cons_bulto;

    /**
     *
     * @var integer
     */
    public $cant_bult;

    /**
     *
     * @var double
     */
    public $anc_bult;

    /**
     *
     * @var double
     */
    public $lar_bult;

    /**
     *
     * @var double
     */
    public $alt_bult;

    /**
     *
     * @var string
     */
    public $obs_bult;

    /**
     *
     * @var boolean
     */
    private $success;
    
 /**
     * @return the $clave_bulto
     */
    public function getClave_bulto()
    {
        return $this->clave_bulto;
    }

 /**
     * @return the $cons_bulto
     */
    public function getCons_bulto()
    {
        return $this->cons_bulto;
    }

 /**
     * @return the $cant_bult
     */
    public function getCant_bult()
    {
        return $this->cant_bult;
    }

 /**
     * @return the $anc_bult
     */
    public function getAnc_bult()
    {
        return $this->anc_bult;
    }

 /**
     * @return the $lar_bult
     */
    public function getLar_bult()
    {
        return $this->lar_bult;
    }

 /**
     * @return the $alt_bult
     */
    public function getAlt_bult()
    {
        return $this->alt_bult;
    }

 /**
     * @return the $obs_bult
     */
    public function getObs_bult()
    {
        return $this->obs_bult;
    }

 /**
     * @return the $success
     */
    public function getSuccess()
    {
        return $this->success;
    }

 /**
     * @param string $clave_bulto
     */
    public function setClave_bulto($clave_bulto)
    {
        $this->clave_bulto = $clave_bulto;
    }

 /**
     * @param number $cons_bulto
     */
    public function setCons_bulto($cons_bulto)
    {
        $this->cons_bulto = $cons_bulto;
    }

 /**
     * @param number $cant_bult
     */
    public function setCant_bult($cant_bult)
    {
        $this->cant_bult = $cant_bult;
    }

 /**
     * @param number $anc_bult
     */
    public function setAnc_bult($anc_bult)
    {
        $this->anc_bult = $anc_bult;
    }

 /**
     * @param number $lar_bult
     */
    public function setLar_bult($lar_bult)
    {
        $this->lar_bult = $lar_bult;
    }

 /**
     * @param number $alt_bult
     */
    public function setAlt_bult($alt_bult)
    {
        $this->alt_bult = $alt_bult;
    }

 /**
     * @param string $obs_bult
     */
    public function setObs_bult($obs_bult)
    {
        $this->obs_bult = $obs_bult;
    }

 /**
     * @param boolean $success
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }

}

?>