<?php
/**
 * CasaLibraries AddPrevious
 * File Contenedores.php
 * Contenedores Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			SMV
 * @version    		Previo 1.0.0
 */



class Contenedores
{

/**
 *
 * @var string
 */
public $clave_tipcon;    

/**
 *
 * @var string
 */
public $numero_contenedor;

/**
 *
 * @var string
 */
public $numero_candado1;

/**
 *
 * @var string
 */
public $numero_candado2;

/**
 *
 * @var string
 */
public $numero_candado3;

/**
 *
 * @var string
 */
public $numero_candado4;

/**
 *
 * @var string
 */
public $numero_candado5;

/**
 *
 * @var string
 */
public $obs_cont;

/**
 *
 * @var boolean
 */
private $success;
/**
     * @return the $clave_tipcon
     */
    public function getClave_tipcon()
    {
        return $this->clave_tipcon;
    }

/**
     * @return the $numero_contenedor
     */
    public function getNumero_contenedor()
    {
        return $this->numero_contenedor;
    }

/**
     * @return the $numero_candado1
     */
    public function getNumero_candado1()
    {
        return $this->numero_candado1;
    }

/**
     * @return the $numero_candado2
     */
    public function getNumero_candado2()
    {
        return $this->numero_candado2;
    }

/**
     * @return the $numero_candado3
     */
    public function getNumero_candado3()
    {
        return $this->numero_candado3;
    }

/**
     * @return the $numero_candado4
     */
    public function getNumero_candado4()
    {
        return $this->numero_candado4;
    }

/**
     * @return the $numero_candado5
     */
    public function getNumero_candado5()
    {
        return $this->numero_candado5;
    }

/**
     * @return the $obs_cont
     */
    public function getObs_cont()
    {
        return $this->obs_cont;
    }

/**
     * @return the $success
     */
    public function getSuccess()
    {
        return $this->success;
    }

/**
     * @param string $clave_tipcon
     */
    public function setClave_tipcon($clave_tipcon)
    {
        $this->clave_tipcon = $clave_tipcon;
    }

/**
     * @param string $numero_contenedor
     */
    public function setNumero_contenedor($numero_contenedor)
    {
        $this->numero_contenedor = $numero_contenedor;
    }

/**
     * @param string $numero_candado1
     */
    public function setNumero_candado1($numero_candado1)
    {
        $this->numero_candado1 = $numero_candado1;
    }

/**
     * @param string $numero_candado2
     */
    public function setNumero_candado2($numero_candado2)
    {
        $this->numero_candado2 = $numero_candado2;
    }

/**
     * @param string $numero_candado3
     */
    public function setNumero_candado3($numero_candado3)
    {
        $this->numero_candado3 = $numero_candado3;
    }

/**
     * @param string $numero_candado4
     */
    public function setNumero_candado4($numero_candado4)
    {
        $this->numero_candado4 = $numero_candado4;
    }

/**
     * @param string $numero_candado5
     */
    public function setNumero_candado5($numero_candado5)
    {
        $this->numero_candado5 = $numero_candado5;
    }

/**
     * @param string $obs_cont
     */
    public function setObs_cont($obs_cont)
    {
        $this->obs_cont = $obs_cont;
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