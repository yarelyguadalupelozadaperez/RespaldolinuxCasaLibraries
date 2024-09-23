<?php

/**
 * CasaLibraries ProductsUpdate
 * File ProductsUpdate.php
 * ProductsUpdate Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			AJPE
 * @version    		Previo 1.0.0
 */
require_once 'ObjectNewValues.php';


class ProductsUpdate
{
    /**
     *
     * @var integer
     */
    public $cons_part;
    
    /**
     *
     * @var string
     */
    public $num_part;
    /**
     *
     * @var \ObjectNewValues[]
     */
    public $objectnewvalues;


     /**
     * @return the $cons_part
     */
    public function getCons_part()
    {
        return $this->cons_part;
    }
        /**
     * @return the $num_part
     */
    public function getNum_part()
    {
        return $this->num_part;
    }
     /**
     * @return the $objectnewvalues
     */
    public function getObjectnewvalues()
    {
        return $this->objectnewvalues;
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
     * @param number $cons_part
     */
    public function setCons_part($cons_part)
    {
        $this->cons_part = $cons_part;
    }
        /**
     * @param string $num_part
     */
    public function setNum_part($num_part)
    {
        $this->num_part = $num_part;
    }

     /**
     * @param ObjectNewValues[] $objectnewvalues
     */
    public function setObjectnewvalues($objectnewvalues)
    {
        $this->objectnewvalues = $objectnewvalues;
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