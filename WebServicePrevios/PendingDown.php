<?php
/**
 * CasaLibraries Previo
 * File PendingDown.php
 * File Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			SMV
 * @version    		Previo 1.0.0
 */
class PendingDown
{
    /**
     *
     * @var integer
     */
    public  $id_prev;
    
    /**
     *
     * @var string
     */
    public $num_refe;
    
    /**
     *
     * @var string
     */
    public $num_guia;
    
    /**
     *
     * @var string
     */
    public $rfc_importador;

    /**
     *
     * @var string
     */
    public $tip_prev;


    public function __construct($id_prev, $num_refe, $num_guia, $rfc_importador, $tip_prev)
    {
        $this->setId_prev($id_prev);
        $this->setNum_refe($num_refe);
        $this->setNum_guia($num_guia);
        $this->setRfc_importador($rfc_importador);
        $this->setTip_prev($tip_prev);
    }
    /**
     * @return the $id_prev
     */
    public function getId_prev()
    {
        return $this->id_prev;
    }

    /**
     * @return the $num_refe
     */
    public function getNum_refe()
    {
        return $this->num_refe;
    }

    /**
     * @return the $num_guia
     */
    public function getNum_guia()
    {
        return $this->num_guia;
    }

    /**
     * @return the $rfc_importador
     */
    public function getRfc_importador()
    {
        return $this->rfc_importador;
    }

    /**
     * @param number $id_prev
     */
    public function setId_prev($id_prev)
    {
        $this->id_prev = $id_prev;
    }

    /**
     * @param string $num_refe
     */
    public function setNum_refe($num_refe)
    {
        $this->num_refe = $num_refe;
    }

    /**
     * @param string $num_guia
     */
    public function setNum_guia($num_guia)
    {
        $this->num_guia = $num_guia;
    }

    /**
     * @param string $rfc_importador
     */
    public function setRfc_importador($rfc_importador)
    {
        $this->rfc_importador = $rfc_importador;
    }


    /**
     * @return the $tip_prev
     */
    public function getTip_prev()
    {
        return $this->tip_prev;
    }

    /**
     * @param string $tip_prev
     */
    public function setTip_prev($tip_prev)
    {
        $this->tip_prev = $tip_prev;
    }
    
}

?>