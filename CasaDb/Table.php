<?php

/**
 * CasaLibraries Table class
 * File Table.php
 * Connection to posgresql database
 *
 * @category 	CasaLibraries
 * @package 	CasaLibraries_CasaDb
 * @copyright 	Copyright (c) 2005-2013 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author 		Jaime Santana Zaldivar
 * @version 	Table 1.0.0
 *
 */
class Table
{

    protected $columns;

    /**
     *
     * @var string
     */
    private $tableName;

    /**
     * 
     * @param string $name
     * @param string $value
     */
    function __set($name, $value)
    {
        $this->columns[$name] = $value;
    }

    function __get($name)
    {
        return $this->columns[$name];
    }

    /**
     *
     * @return the $tableName
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     *
     * @param string $tableName            
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }
}

?>