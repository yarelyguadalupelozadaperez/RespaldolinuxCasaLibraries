<?php
/**
 * CasaLibraries Previos
 * ResponsePrevious ResponsePrevious.php
 * ResponsePrevious Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previos
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			Jaime Santana Zaldivar
 * @version    		Previos 1.0.0
 */
class ResponsePrevious
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
     * Previous object
     *
     * @var Previous
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
     * @return the $arrayPrevious
     */
    public function getArrayPrevious()
    {
        return $this->arrayPrevious;
    }

    /**
     * @param multitype:Previous  $arrayPrevious
     */
    public function setArrayPrevious($arrayPrevious)
    {
        $this->arrayPrevious = $arrayPrevious;
    }

    

    
}

?>