<?php

/**
 * CasaLibraries Clasificaciones
 * File Clasificaciones.php
 * Clasificaciones Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			AJPE
 * @version    		Previo 1.0.0
 */


class Clasificaciones
{

    /**
     *
     * @var string
     */
    public $num_part;
    /**
     *
     * @var string
     */
    public $num_fracc;
    /**
     *
     * @var string
     */
    public $cve_nico;
    
    /**
     *
     * @var string
     */
    public $desc_merc;
    
    
    /**
     *
     * @var integer
     */
    public $num_partcove;

 
     /**
     *
     * @var integer
     */
    public $flag_fracc;
    
    
    /**
     *
     * @var integer
     */
    public $origen_fracc;

    /**
     * @param string $num_part
     */
    public function setNum_part($num_part)
    {
        $this->num_part = $num_part;
    }
    
    /**
     * @return the $num_part
     */
    public function getNum_part()
    {
        return $this->num_part;
    }
     /**
     * @return the $num_fracc
     */
    public function getNum_fracc()
    {
        return $this->num_fracc;
    }
        /**
     * @return the $cve_nico
     */
    public function getCve_nico()
    {
        return $this->cve_nico;
    }
            /**
     * @return the $desc_merc
     */
    public function getDesc_merc()
    {
        return $this->desc_merc;
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
     * @param number $num_fracc
     */
    public function setNum_fracc($num_fracc)
    {
        $this->num_fracc = $num_fracc;
    }
    /**
     * @param double $cve_nico
     */
    public function setCve_nico($cve_nico)
    {
        $this->cve_nico = $cve_nico;
    }
        /**
     * @param string $desc_merc
     */
    public function setDesc_merc($desc_merc)
    {
        $this->desc_merc = $desc_merc;
    }
    
    /**
     * @param boolean $success
     */
    private function setSuccess($success)
    {
        $this->success = $success;
    }
    
    /**
     * @param integer $num_partcove
     */
    public function setNum_partcove($num_partcove)
    {
        $this->num_partcove = $num_partcove;
    }
    
    /**
     * @return the $num_partcove
     */
    public function getNum_partcove()
    {
        return $this->num_partcove;
    }
    
    /**
     * @param integer $flag_fracc
     */
    public function setFlag_fracc($flag_fracc)
    {
        $this->flag_fracc = $flag_fracc;
    }
    
    /**
     * @return the $flag_fracc
     */
    public function getFlag_fracc()
    {
        return $this->flag_fracc;
    }
    
    /**
     * @param integer $origen_fracc
     */
    public function setOrigen_fracc($origen_fracc)
    {
        $this->origen_fracc = $origen_fracc;
    }
    
    /**
     * @return the $origen_fracc
     */
    public function getOrigen_fracc()
    {
        return $this->origen_fracc;
    }
}
?>
