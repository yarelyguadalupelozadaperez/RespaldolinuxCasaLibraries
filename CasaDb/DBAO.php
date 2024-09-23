<?php
/**
 * Zend_Db
 */
require_once 'Zend/Db.php';
/**
 * Clase que representa la abstraccion de nuestro objeto Zend_Db
 *
 * @category   db
 * @package    Sistemascasa_CasaDb
 * @subpackage Sistemascasa_CasaDb
 * @copyright  Copyright (c) 2007-2009 Sistemas CASA (http://www.sistemascasa.com.mx)
 */
class DBAO
{
    public static $config = null;
    /**
     * Regresa el objeto Zend_Db para los catalogos
     * @return Zend_Db_Adapter_Abstract Objeto Zend_Db_Adapter_Abstract para manejo de la Base de datos
     * @throws Exception No se ha configurado el parametro estático de la base de datos
     */
    public static function Database ()
    {
        if (DBAO::$config === null) {
            throw new Exception("No se ha configurado el parametro estático de la base de datos");
        }
        return Zend_Db::factory(DBAO::$config);
    }
}
?>
