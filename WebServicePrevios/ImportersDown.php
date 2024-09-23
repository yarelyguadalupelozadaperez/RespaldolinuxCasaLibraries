<?php
/**
 * CasaLibraries Previo
 * File Importers.php
 * File Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			Yarely Guadalupe Lozada Pérez
 * @version    		Previo 1.0.0
 */


require_once 'Importers.php';

class ImportersDown
{

   /**
     *
     * @var Importers[]
     */
    public $importers;

    /**
     *
     * @var boolean
     */
    private $success;
    
    /**
     * @return the $importers
     */
    public function getImporters()
    {
        return $this->importers;
    }

    /**
     * @return the $success
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * @param multitype:Importers  $importers
     */
    public function setImporters($importers)
    {
        $this->importers = $importers;
    }

    /**
     * @param boolean $success
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }

}

?>