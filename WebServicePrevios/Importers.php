<?php
/**
 * CasaLibraries AddPrevious
 * File Contenedores.php
 * Contenedores Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			Yarely Guadalupe Lozada Pérez
 * @version    		Previo 1.0.0
 */



class Importers
{

/**
 *
 * @var string
 */
public $rfc_importador;    

/**
 *
 * @var string
 */
public $nombre_importador;

/**
 * @return the $rfc_importador
 */
public function getRfc_importador()
{
    return $this->rfc_importador;
}

/**
 * @param string $rfc_importador
 */
public function setRfc_importador($rfc_importador)
{
    $this->rfc_importador = $rfc_importador;
}

/**
 * @param string $nombre_importador
 */
public function setNombre_importador($nombre_importador)
{
    $this->nombre_importador = $nombre_importador;
}

}

?>