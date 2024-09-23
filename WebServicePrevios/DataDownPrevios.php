<?php
/**
 * CasaLibraries Previo
 * File DataDownPrevios.php
 * DataDownPrevios Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			SMV
 * @version    		Previo 1.0.0
 */

require_once 'Contenedores.php';
require_once 'Bultos.php';
require_once 'Ordcompras.php';
require_once 'Invoice.php';

class DataDownPrevios
{
    /**
     *
     * @var string
     */
    public $num_refe;
    
    /**
     *
     * @var string
     */
    public $rfc_importador;
    
    /**
     *
     * @var string
     */
    public $fec_soli;
    
    /**
     *
     * @var integer
     */
    public $fol_soli;
    
    /**
     * 
     * @var integer
     */
    public $tot_bult;
    
    /**
     *
     * @var integer
     */
    public $tot_bultr;
    
    
    /**
     * 
     * @var string
     */
    public $rec_fisc;
    
    /**
     * 
     * @var string
     */
    public $num_guia;
    
    /**
     * 
     * @var string
     */
    public $edo_prev;
    
    /**
     *
     * @var string
     */
    public $ins_prev;
    
    
    /**
     * 
     * @var string
     */
    public $dep_asigna;
    
    /**
     * 
     * @var string
     */
    public $obs_prev;
    
    /**
     *
     * @var double
     */
    public $pes_brut;
    
    
    /**
     *
     * @var \Contenedores[]
     */
    public $contenedores;
    
    /**
     *
     * @var \Bultos[]
     */
    public $bultos;
    
    /**
     *
     * @var \Ordcompras[]
     */
    public $ordcompras;
    
    /**
     *
     * @var \Invoice[]
     */
    public $invoices;
    
    /**
     * @return the $pes_brut
     */
    public function getPes_brut()
    {
        return $this->pes_brut;
    }

 /**
     * @param number $pes_brut
     */
    public function setPes_brut($pes_brut)
    {
        $this->pes_brut = $pes_brut;
    }

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
     * @return the $num_refe
     */
    public function getNum_refe()
    {
        return $this->num_refe;
    }


 /**
     * @return the $fec_soli
     */
    public function getFec_soli()
    {
        return $this->fec_soli;
    }

 /**
     * @return the $fol_soli
     */
    public function getFol_soli()
    {
        return $this->fol_soli;
    }

 /**
     * @return the $tot_bult
     */
    public function getTot_bult()
    {
        return $this->tot_bult;
    }

 /**
     * @return the $ins_prev
     */
    public function getIns_prev()
    {
        return $this->ins_prev;
    }

 /**
     * @param string $num_refe
     */
    public function setNum_refe($num_refe)
    {
        $this->num_refe = $num_refe;
    }


 /**
     * @param string $fec_soli
     */
    public function setFec_soli($fec_soli)
    {
        $this->fec_soli = $fec_soli;
    }

 /**
     * @param number $fol_soli
     */
    public function setFol_soli($fol_soli)
    {
        $this->fol_soli = $fol_soli;
    }

 /**
     * @param number $tot_bult
     */
    public function setTot_bult($tot_bult)
    {
        $this->tot_bult = $tot_bult;
    }

 /**
     * @param string $ins_prev
     */
    public function setIns_prev($ins_prev)
    {
        $this->ins_prev = $ins_prev;
    }

 /**
     * @return the $tot_bultr
     */
    public function getTot_bultr()
    {
        return $this->tot_bultr;
    }
    
    /**
     *
     * @return the $rec_fisc
     */
    public function getRec_fisc()
    {
        return $this->rec_fisc;
    }

    /**
     *
     * @return the $num_guia
     */
    public function getNum_guia()
    {
        return $this->num_guia;
    }

    /**
     *
     * @return the $edo_prev
     */
    public function getEdo_prev()
    {
        return $this->edo_prev;
    }

    /**
     *
     * @return the $dep_asigna
     */
    public function getDep_asigna()
    {
        return $this->dep_asigna;
    }

    /**
     *
     * @return the $obs_prev
     */
    public function getObs_prev()
    {
        return $this->obs_prev;
    }

    /**
     *
     * @return the $contenedores
     */
    public function getContenedores()
    {
        return $this->contenedores;
    }

    /**
     *
     * @return the $bultos
     */
    public function getBultos()
    {
        return $this->bultos;
    }

    /**
     *
     * @return the $ordcompras
     */
    public function getOrdcompras()
    {
        return $this->ordcompras;
    }
    
    /**
     * @return the $invoices
     */
    public function getInvoices()
    {
        return $this->invoices;
    }
    
    /**
     * @param string $tot_bultr
     */
    public function setTot_bultr($tot_bultr)
    {
        $this->tot_bultr = $tot_bultr;
    }
    
    /**
     *
     * @param number $rec_fisc            
     */
    public function setRec_fisc($rec_fisc)
    {
        $this->rec_fisc = $rec_fisc;
    }

    /**
     *
     * @param number $num_guia            
     */
    public function setNum_guia($num_guia)
    {
        $this->num_guia = $num_guia;
    }

    /**
     *
     * @param number $edo_prev            
     */
    public function setEdo_prev($edo_prev)
    {
        $this->edo_prev = $edo_prev;
    }

    /**
     *
     * @param number $dep_asigna            
     */
    public function setDep_asigna($dep_asigna)
    {
        $this->dep_asigna = $dep_asigna;
    }

    /**
     *
     * @param number $obs_prev            
     */
    public function setObs_prev($obs_prev)
    {
        $this->obs_prev = $obs_prev;
    }

    /**
     *
     * @param Contenedores[] $contenedores            
     */
    public function setContenedores($contenedores)
    {
        $this->contenedores = $contenedores;
    }

    /**
     *
     * @param Bultos[] $bultos            
     */
    public function setBultos($bultos)
    {
        $this->bultos = $bultos;
    }

    /**
     *
     * @param Ordcompras[] $ordcompras            
     */
    public function setOrdcompras($ordcompras)
    {
        $this->ordcompras = $ordcompras;
    }
    
    
    /**
     * @param Invoice[] $invoices
     */
    public function setInvoices($invoices)
    {
        $this->invoices = $invoices;
    }
    
    
    
    
} 

?>