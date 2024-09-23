<?php
/**
 * CasaLibraries Previo
 * File DataDownImporters.php
 * DataDownImporters Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			Yarely Guadalupe Lozada Pérez
 * @version    		Previo 1.0.0
 */


class DataDownImporters
{
    /**
     *
     * @var \Importers[]
     */
    public $importers;
    
    /**
     * @return the $importers
     */
    public function getImporters()
    {
        return $this->importers;
    }

    /**
     * @param multitype:Importers  $importers
     */
    public function setImporters($importers)
    {
        $this->importers = $importers;
    }

} 

?>