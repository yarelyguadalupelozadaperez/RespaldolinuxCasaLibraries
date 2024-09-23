<?php
/**
 * CasaLibraries CBodega
 * ResponseDownloadObject ResponseDownloadObject.php
 * ResponseDownloadObject Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_CBodega
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			Jaime Santana Zaldivar
 * @version    		CBodega 1.0.0
 */

class ResponseDownloadObject
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
     * @var DataDown[]
     */
    public $dataDown;
    
   /**
    * 
    * @param boolean $success
    * @param string $messageText
    * @param DataDown $downloadData
    */
    public function __construct($success, $messageText, $dataDown) {
        $this->setSuccess($success);
        $this->setMessageText($messageText);
        $this->setDataDown($dataDown);
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
     * @return the $dataDown
     */
    public function getDataDown()
    {
        return $this->dataDown;
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
     * @param DataDown $dataDown
     */
    public function setDataDown($dataDown)
    {
        $this->dataDown = $dataDown;
    }
   
}

?>