<?php

/**
 * CasaLibraries AddPrevious
 * File AddPrevious.php
 * AddPrevious Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			SMV
 * @version    		Previo 1.0.0
 */

require_once 'Products1.php';

class Invoice1
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
     * @var string
     */
    public $cve_pro;

    
    /**
     *
     * @var \Products1[]
     */
    public $products1;
       
    

 /**
     * @return the $products
     */
    public function getProducts()
    {
        return $this->products;
    }

 /**
     * @param Products[] $products
     */
    public function setProducts($products)
    {
        $this->products = $products;
    }

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
     * @return the $cve_pro
     */
    public function getCve_pro()
    {
        return $this->cve_pro;
    }

 /**
     * @param string $cve_pro
     */
    public function setCve_pro($cve_pro)
    {
        $this->num_fact = utf8_decode($cve_pro);
    }

    
}




?>