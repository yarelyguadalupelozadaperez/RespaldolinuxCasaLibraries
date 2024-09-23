<?php
/**
 * CasaLibraries Invoice2
 * File Invoice2.php
 * Invoice2 Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			AJPE
 * @version    		Previo 1.0.0
 */

require_once 'ProductsUpdate.php';

class Invoice2
{


    /**
     *
     * @var integer
     */
    public $cons_fact;
    
    /**
     *
     * @var string
     */
    public $num_fact;

    /**
     *
     * @var \ProductsUpdate[]
     */
    public $productsupdate;

    

 	/**
     * @return the $cons_fact
     */
    public function getCons_fact()
    {
        return $this->cons_fact;
    }

	 /**
     * @return the $num_fact
     */
    public function getNum_fact()
    {
        return $this->num_fact;
    }

 /**
     * @return the $productsupdate
     */
    public function getProductsupdate()
    {
        return $this->productsupdate;
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
     * @param number $cons_fact
     */
    public function setCons_fact($cons_fact)
    {
        $this->cons_fact = utf8_decode($cons_fact);
    }

 	/**
     * @param string $num_fact
     */
    public function setNum_fact($num_fact)
    {
        $this->num_fact = utf8_decode($num_fact);
    }

     /**
     * @param boolean $success
     */
    private function setSuccess($success)
    {
        $this->success = $success;
    }

     /**
     * @param ProductsUpdate[] $productsupdate
     */
    public function setProductsupdate($productsupdate)
    {
        $this->productsupdate = $productsupdate;
    }
}
?>