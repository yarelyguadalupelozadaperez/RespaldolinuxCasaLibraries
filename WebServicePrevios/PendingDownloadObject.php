<?php
/**
 * CasaLibraries WebservicePrevios
 * PendingDownloadObject PendingDownloadObject.php
 * PendingDownloadObject Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_CBodega
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			Jaime Santana Zaldivar
 * @version    		CBodega 1.0.0
 */
class PendingDownloadObject
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
     * @var PendingDown[]
     */
    public $pendingDown;
    
    /**
     *
     * @param boolean $success
     * @param string $messageText
     * @param PendingDown $pendingData
     */
    public function __construct($success, $messageText, $pendingDown) {
        $this->setSuccess($success);
        $this->setMessageText($messageText);
        $this->setPendingDown($pendingDown);
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
     * @return the $pendingDown
     */
    public function getPendingDown()
    {
        return $this->pendingDown;
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
     * @param multitype:PendingDown  $pendingDown
     */
    public function setPendingDown($pendingDown)
    {
        $this->pendingDown = $pendingDown;
    }
    
}

?>