<?php
/**
 * DBAO
 */
require_once 'DBAO.php';
/**
 * Clase que representa un catalogo general
 *
 * @category   db
 * @package    Sistemascasa_CasaDb
 * @subpackage Sistemascasa_CasaDb
 * @copyright  Copyright (c) 2007-2009 Sistemas CASA (http://www.sistemascasa.com.mx)
 */
class Catalog
{
    /**
     * Propiedad que representa el objeto db.
     * @var Zend_Db_Adapter_Abstract Objeto Zend_Db_Adapter_Abstract
     */
    protected $db;
    /**
     * Constructor de la clase catalogo
     */
    public function Catalog ()
    {
        $this->db = DBAO::Database();
    }
}
?>
