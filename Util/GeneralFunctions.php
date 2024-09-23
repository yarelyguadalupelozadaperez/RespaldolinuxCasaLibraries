<?php

/**
 * CasaLibraries GeneralFunctions
 * 
 * General Functions
 *
 * @category CasaLibraries
 * @package CasaLibraries_Util_GeneralFuncions
 * @copyright (c) 2005-2013, Sistemas CASA, S.A. de C.V. http://www.sistemascasa.com.mx
 * @author Jesús Eduardo Flores Alejandro jflores@sistemascasa.com.mx
 * @version CasaLibraries 1.0.0
 */
class GeneralFunctions {

    /**
     * Clear blank spaces
     * @param string $string
     * @return string
     */
    public static function trimString($string) {
        $string = trim($string);
        $strinRegExpr = preg_replace('/\s\s+/', ' ', $string);

        return $strinRegExpr;
    }

    /**
     * Sanitize an string
     * @param string $theValue
     * @param string $theType
     * @param string $theDefinedValue
     * @param string $theNotDefinedValue
     * @return string
     */
    public static function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {

        switch ($theType) {
            case "text":
                $theValue = trim(strip_tags($theValue));
                $theValue = self::trimString($theValue);
                $theValue = (get_magic_quotes_gpc()) ? $theValue : addslashes($theValue);
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "search":
                $theValue = trim(strip_tags($theValue));
                $theValue = (get_magic_quotes_gpc()) ? $theValue : addslashes($theValue);
                $theValue = ($theValue != "") ? "'%" . $theValue . "%'" : "NULL";
                break;
            case "clean":
                $theValue = self::trimString($theValue);
                $theValue = trim(strip_tags($theValue));
                $theValue = (get_magic_quotes_gpc()) ? $theValue : addslashes($theValue);
                $theValue = ($theValue != "") ? $theValue : "NULL";
                break;
            case "long":
            case "int":
                $theValue = ($theValue !== "") ? intval($theValue) : "NULL";
                break;
            case "double":
                $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
                break;
            case "date":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "HTML":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "defined":
                $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
                break;
            case "email":
                $theValue = self::GetSQLValueString($theValue, 'clean');
                $theValue = (filter_var($theValue, FILTER_VALIDATE_EMAIL)) ? $theValue : FALSE;
        }
        return $theValue;
    }

    /**
     * Generate a random string with an specified lenght
     * @param integer $length
     * @return string
     */
    public static function randomText($length) {

        $pattern = "#$%&@.*-_/1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $key = '';
        self::_rand_time();
        for ($i = 0; $i < $length; $i++) {
            $key .= $pattern{rand(0, strlen($pattern))};
        }
        return $key;
    }

    /**
     * Random number generator
     * @return float
     */
    private static function _rand_time() {
        $t = (int) (microtime(true) * 0xFFFF);
        return srand($t);
    }

    public static function reverseDate($date) {
        $aDate = explode('/', $date);
        $dd = $aDate[0];
        $mm = $aDate[1];
        $yy = $aDate[2];
        return $yy . '/' . $mm . '/' . $dd;
    }

    /**
     * Function to delete duplicate data from an assosiative array
     * @param array $aData
     * @param string $field
     * @param boolean $format
     * @return array|string
     */
    public function arrayUnique($aData, $field, $format = FALSE) {
        $list = array();

        foreach ($aData as $data) {
            array_push($list, $data[$field]);
        }

        if ($format) {
            return implode(array_unique($list), ',');
        }

        $tList = array_unique($list);

        $kList = array();
        $i = 0;
        foreach ($tList as $l) {
            $kList[$i] = $l;
            $i++;
        }

        $lImp = array();
        for ($i = 0; $i < count($kList); $i++) {

            foreach ($aData as $data) {
                if ($kList[$i] == $data['rfc']) {
                    $lImp[$i] = $data;
                    continue;
                }
            }
        }
        return $lImp;
    }

    public static function getCliPatAdu($cliPatAdu) {

        $licCliPaAdu = array();

        $licCliPaAdu['licAduana'] = self::extraePorcionFinalCadena($cliPatAdu, 3);
        $cliPatAdu = self::cortaFinalCadena($cliPatAdu, 3);

        $licCliPaAdu['licPatente'] = self::extraePorcionFinalCadena($cliPatAdu, 4);
        $cliPatAdu = self::cortaFinalCadena($cliPatAdu, 4);

        $licCliPaAdu['licCliente'] = $cliPatAdu;

        return $licCliPaAdu;
    }

    public static function extraePorcionFinalCadena($cadena, $fin) {
        return substr($cadena, strlen($cadena) - $fin, strlen($cadena));
    }

    public static function reemplazaCaracteresCadena($search, $replace, $subject) {
        return str_replace($search, $replace, $subject);
    }

    public static function cortaFinalCadena($cadena, $numCaract) {

        return substr($cadena, 0, strlen($cadena) - $numCaract);
    }

}
