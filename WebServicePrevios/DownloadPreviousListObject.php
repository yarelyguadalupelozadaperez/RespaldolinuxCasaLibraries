<?php
/**
 * CasaLibraries WebservicePrevios
 * DownloadPreviousListObject DownloadPreviousListObject.php
 * DownloadPreviousListObject Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			Yarely Guadalupe Lozada Pérez
 * @version    		Previo 1.0.0
 */
class DownloadPreviousListObject
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
     * @var PreviousList[]
     */
    public $previousList;
    
    /**
     *
     * @param boolean $success
     * @param string $messageText
     * @param PendingDown $pendingData
     */
    public function __construct($success, $messageText, $previousList) {
        $this->setSuccess($success);
        $this->setMessageText($messageText);
        $this->setPreviousList($previousList);
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
     * @return the $previousList
     */
    public function getPreviousList()
    {
        return $this->previousList;
    }

    /**
     * @param multitype:PreviousList  $previousList
     */
    public function setPreviousList($previousList)
    {
        $this->previousList = $previousList;
    }


    
}

?>