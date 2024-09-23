<?php
/**
 * CasaLibraries CBodega
 * ResponseDownload ResponseDownload.php
 * ResponseDownload Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_CBodega
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			Jaime Santana Zaldivar
 * @version    		CBodega 1.0.0
 */

class ResponseDownload
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
     * Array of Previous objects
     *
     * @var ConsultReference[]
     */
    public $arrayPrevious;
    
    /**
     * @param boolean $success
     * @param string $messageText
     * @param array $arrayPrevious
     */
    public function __construct($success, $messageText, $arrayPrevious) {
        $this->setSuccess($success);
        $this->setMessageText($messageText);
        $this->setArrayPrevious($arrayPrevious);
    }
    
    /**
     * @return the $success
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * @return the $messageText
     */
    public function getMessageText()
    {
        return $this->messageText;
    }

    /**
     * @return the $arrayPrevious
     */
    public function getArrayPrevious()
    {
        return $this->arrayPrevious;
    }

    /**
     * @param boolean $success
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }

    /**
     * @param string $messageText
     */
    public function setMessageText($messageText)
    {
        $this->messageText = $messageText;
    }

    /**
     * @param array $arrayPrevious
     */
    public function setArrayPrevious($arrayPrevious)
    {
        $this->arrayPrevious = $arrayPrevious;
    }
    
}

?>