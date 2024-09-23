<?php
/**
 * CasaLibraries Previo
 * File Credentials.php
 * Credentials Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			SMV
 * @version    		Previo 1.0.0
 */

class Credentials
{
    /**
     * Identifier of Client
     * 
     * @var integer
     */
    public $Username;
    
    /**
     * token identifer of Client
     * 
     * @var string
     */
    public $Password;
    
    /**
     * 
     * @param integer $idClient
     * @param string $token
     */
    public function __construct($Username, $Password) {
        $this->setUsername($Username);
        $this->setPassword($Password);
    }
    
    public function checkCredentials () {
        try {

            $db = new PgsqlConnection();
            $sql = "SELECT id FROM \"Previo\".\"CLIENTS\" WHERE id = " . $this->getUsername() . " AND \"clienttoken\" = '" . $this->getPassword() . "'";
            $response = $db->execute($sql);
            if($response[0]["id"] > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            var_dump($e->getMessage());
            exit;
        }
    
    }
    /**
     * @return the $Username
     */
    public function getUsername()
    {
        return $this->Username;
    }

    /**
     * @return the $Password
     */
    public function getPassword()
    {
        return $this->Password;
    }

    /**
     * @param number $Username
     */
    public function setUsername($Username)
    {
        $this->Username = $Username;
    }

    /**
     * @param string $Password
     */
    public function setPassword($Password)
    {
        $this->Password = $Password;
    }

    
    
    
    
}

?>