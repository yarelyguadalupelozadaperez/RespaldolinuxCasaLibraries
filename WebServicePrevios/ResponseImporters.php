<?php
/**
 * CasaLibraries WebservicePrevios
 * ResponseImporters ResponseImporters.php
 * ResponseImporters Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			Yarely Guadalupe Lozada Pérez
 * @version    		Previo 1.0.0
 */
class ResponseImporters
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
     *  Array of Previous objects
     *
     * @var \ImportersDown[]
     */
    public $importersDown;
    
    /**
     *
     * @param boolean $success
     * @param string $messageText
     * @param ImportersDown $importersDown
     */
    public function __construct($success, $messageText, $importersDown) {
        $this->setSuccess($success);
        $this->setMessageText($messageText);
        $this->setImportersDown($importersDown);
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
     * @return the $importersDown
     */
    public function getImportersDown()
    {
        return $this->importersDown;
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
     * @param ImportersDown  $importersDown
     */
    public function setImportersDown($importersDown)
    {
        $this->importersDown = $importersDown;
    }
    
}

?>