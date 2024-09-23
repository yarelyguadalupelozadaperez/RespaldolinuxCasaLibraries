<?php
/**
 * CasaLibraries PreviosWs
 * PrevioResponseDownloadObject.php
 * PrevioResponseDownloadObject Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_CBodega
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			Jaime Santana Zaldivar
 * @version    		CBodega 1.0.0
 */

class PrevioResponseDownloadObject
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
     * Array  of Previos Object 
     *
     * @var DataDownPrevios[]
     */
    public $dataDownPrevios;
    
 
 /**
    * 
    * @param boolean $success
    * @param string $messageText
    * @param DataDownPrevios $dataDownPrevios
    */
    public function __construct($success, $messageText, $dataDownPrevios) {
        $this->setSuccess($success);
        $this->setMessageText($messageText);
        $this->setDataDownPrevios($dataDownPrevios);
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
     * @return the $dataDownPrevios
     */
    public function getDataDownPrevios()
    {
        return $this->dataDownPrevios;
    }
    
    /**
     * @param DataDownPrevios[] $dataDownPrevios
     */
    public function setDataDownPrevios($dataDownPrevios)
    {
        $this->dataDownPrevios = $dataDownPrevios;
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

  
   
}

?>