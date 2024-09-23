<?php

/**
 * CasaLibraries ObjectNewValues
 * File ObjectNewValues.php
 * ObjectNewValues Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			AJPE
 * @version    		Previo 1.0.0
 */


class ObjectNewValues
{

    /**
     *
     * @var string
     */
    public $column;
    
    /**
     *
     * @var string
     */
    public $value;


     /**
     * @return the $column
     */
    public function getColumn()
    {
        return $this->column;
    }
        /**
     * @return the $value
     */
    public function getValue()
    {
        return $this->value;
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
     * @param string $column
     */
    public function setColumn($column)
    {
        $this->column = $column;
    }
        /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
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
