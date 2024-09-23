<?php

/**
 * CasaLibraries MySqlConnection
 * 
 * Mysql Database Connection
 *
 * @category CasaLibraries
 * @package CasaLibraries_CasaDb_MySqlConnection
 * @copyright (c) 2005-2013, Sistemas CASA, S.A. de C.V. http://www.sistemascasa.com.mx
 * @author Jesús Eduardo Flores Alejandro <jflores@sistemascasa.com.mx>
 * @version CasaLibraries 1.0.0
 */
include_once 'Zend/Db.php';

class MySqliConnection {

    public $PDOdb;

    /**
     * Driver to connect with postgres data base
     *
     * @var object
     */
    private $db;

    /**
     * Direction Host to connectsqlVista
     *
     * @var string
     */
    public $host = 'localhost';

    /**
     * User Name to access
     *
     * @var string
     */
    public $username = 'root';

    /**
     * Password to access
     *
     * @var string
     */
    public $password = 'developmentall';

    /**
     * Data Base to access
     *
     * @var string
     */
    public $dbname = 'casainfo';

    /**
     * Constructor of the class
     *
     * @return Zend_Db_Adapter_Exception Zend_Exception
     */
    function __construct($host = FALSE, $userName = FALSE, $pass = FALSE, $dbName = FALSE) {

        $this->host = $host ? $host : $this->host;
        $this->username = $userName ? $userName : $this->username;
        $this->password = $pass ? $pass : $this->password;
        $this->dbname = $dbName ? $dbName : $this->dbname;

        try {
            if (!$this->PDOdb) {
                try {
                    $this->PDOdb = Zend_Db::factory('Pdo_Mysqli', array(
                                'host' => $this->host,
                                'username' => $this->username,
                                'password' => $this->password,
                                'dbname' => $this->dbname
                    ));
                    return $this->PDOdb;
                } catch (Zend_Db_Adapter_Exception $e) {
                    return $e;
                }
            }
        } catch (Zend_Exception $e) {
            return $e;
        }
    }

}