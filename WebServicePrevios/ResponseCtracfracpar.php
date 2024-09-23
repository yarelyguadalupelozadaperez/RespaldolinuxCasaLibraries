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

class ResponseCtracfracpar
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
     * @var \CtracFracpar[]
     */
    public $ctracfracpar;


    /**
     * 
     * @param string $referenceNumber
     * @param boolean $success
     * @param CtracFracpar[] $ctracfracpar 
     */
    public function __construct($success, $result, $ctracfracpar) {
        $this->success = $success;        
        $this->messageText = $result;
        $this->ctracfracpar = $ctracfracpar;
    }
    
}

?>