<?php
require_once ('adodb5/adodb-exceptions.inc.php');
require_once ('adodb5/adodb.inc.php'); // Carga el codigo comun de ADOdb
if (defined ( "ConnectionFactory1" ))
	return;
define ( "ConnectionFactory1", 1 );
/**
 * Connection Factory
 *
 * @category db
 * @package Sistemascasa_CasaDb
 * @subpackage Sistemascasa_CasaDb
 * @copyright Copyright (c) 2007-2009 Sistemas CASA
 *            (http://www.sistemascasa.com.mx)
 */
abstract class ConnectionFactory1 {
        
        public static function ConnectCasaInfo() {
		$db = NewADOConnection ( 'mysqli' );
		$db->Connect ( "sistemascasa.com.mx:44179", "casasql", "embeddedp@assprotect***", "c4s4_1nf0");
		return $db;
	}
        
        public static function ConnectCtraweb() {
            $db = NewADOConnection ( 'mysqli' );
            $db->Connect ( "sistemascasa.com.mx:44179", "root", "developmentall", "admon_ctraweb" );
            return $db;
        }
        
	public static function ConnectDbLicWeb() {
                $db = NewADOConnection ( 'mysqli' );
	        $db->Connect ( "sistemascasa.com.mx:44179", "casasql", "embeddedp@assprotect***", "dblic_web" );
                return $db;
        }
        public static function ConnectAdmonCtrawebMySQL() {
            $db = NewADOConnection ( 'mysqli' );
            $db->Connect ( "sistemascasa.com.mx:44179", "root", "developmentall", "admon_sice" );
            return $db;
        }
	
	public static function ConnectCtarnet() {
		$db = NewADOConnection ( 'mysqli' );
		$db->Connect ( "sistemascasa.com.mx:44179", "casasql", "embeddedp@assprotect***", "ctarnet" );
		return $db;
	}
	
	public static function Connect($idClient) {
		$db = ADONewConnection ( 'ibase' );
		$db->Connect ( "localhost:" . 'C:\\\ctraweb\cliconfig\\' . $idClient . '\\CASA.GDB', 'SYSDBA', 'masterkey' );
		return $db;
	}
     /*
     * Clase de conexion para proyecto de Estadisticas
     */
         public static function ConnectCtraweStatisticGraphsbMySQL() {
        	$db = NewADOConnection ( 'mysqli' );
	        $db->Connect ( "localhost:44179", "casasql", "embeddedp@assprotect***", "admon_ctraweb" );
        	//$db->Connect ( "localhost", "root", "developmentall", "admon_ctraweb" );
	        return $db;
        }
}

