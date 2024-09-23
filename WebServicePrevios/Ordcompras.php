<?php
/**
 * CasaLibraries AddPrevious
 * File Ordcompras.php
 * Ordcompras Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			SMV
 * @version    		Previo 1.0.0
 */



class Ordcompras
{

    /**
     *
     * @var integer
     */
    public $cons_orcom;

    /**
     *
     * @var string
     */
    public $num_orcom;

    /**
     *
     * @var boolean
     */
    private $success;
 /**
     * @return the $cons_orcom
     */
    public function getCons_orcom()
    {
        return $this->cons_orcom;
    }

 /**
     * @return the $num_orcom
     */
    public function getNum_orcom()
    {
        return $this->num_orcom;
    }

 /**
     * @return the $success
     */
    public function getSuccess()
    {
        return $this->success;
    }

 /**
     * @param number $cons_orcom
     */
    public function setCons_orcom($cons_orcom)
    {
        $this->cons_orcom = $cons_orcom;
    }

 /**
     * @param string $num_orcom
     */
    public function setNum_orcom($num_orcom)
    {
        $this->num_orcom = $num_orcom;
    }

 /**
     * @param boolean $success
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }


}