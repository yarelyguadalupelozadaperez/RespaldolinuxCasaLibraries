<?php
/**
 * CasaLibraries CBodega
 * File Response.php
 * Response Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_CBodega
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			Jaime Santana Zaldivar
 * @version    		CBodega 1.0.0
 */

class Response
{
    /**
     * Status of the request
     * 
     * @var boolean
     */
    public $success;
    
    /**
     * Text of messegae
     * 
     * @var string
     */
    public $messageText;
    
    /**
     * 
     * @param string $referenceNumber
     * @param boolean $success
     */
    public function __construct($success, $result) {
        $this->success = $success;        
        $this->messageText = $result;
    }

    /**
     * @return the $messageText
     */
    public function getMessageText()
    {
        return $this->messageText;
    }
    /**
     * @param string $messageText
     */
    public function setMessageText($messageText)
    {
        $this->messageText = $messageText;
    }
    
}

?>