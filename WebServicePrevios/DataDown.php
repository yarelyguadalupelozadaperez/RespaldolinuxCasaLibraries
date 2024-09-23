<?php
/**
 * CasaLibraries Previo
 * File DataDown.php
 * File Class
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
require_once 'File.php';

class DataDown
{

    /**
     * 
     * @var integer
     */
    public $idprevious;

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
    public $dep_asigna;
    
    /**
     * 
     * @var string
     */
    public $obs_prev;
    
    /**
     *
     * @var string
     */
    public $hora_fin;
    
    /**
     *
     * @var string
     */
    public $hora_inicio;
    
    /**
     *
     * @var double
     */
    public $pes_brut;
    
       /**
     *
     * @var string
     */
    public $tip_prev;
    
    /**
     *
     * @var string
     */
    public $tip_merc;
    
    /**
     *
     * @var string
     */
    public $rev_auto;
    
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
     * @var \File[]
     */
    public $files;
    
    /**
     *
     * @var \Invoice[]
     */
    public $invoices;


     /**
     * @param number $idprevious
     */
    public function setIdprevious($idprevious)
    {
        $this->idprevious = $idprevious;
    }

 /**
     * @return the $idprevious
     */
    public function getIdprevious()
    {
        return $this->idprevious;
    }
    
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
     * @return the $files
     */
    public function getFiles()
    {
        return $this->files;
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
     * @param Files[] $files
     */
    public function setFiles($files)
    {
        $this->files = $files;
    }
    
    /**
     * @param Invoice[] $invoices
     */
    public function setInvoices($invoices)
    {
        $this->invoices = $invoices;
    }
    /**
     * @return the $hora_fin
     */
    public function getHora_fin()
    {
        return $this->hora_fin;
    }

    /**
     * @return the $hora_inicio
     */
    public function getHora_inicio()
    {
        return $this->hora_inicio;
    }

    /**
     * @param string $hora_fin
     */
    public function setHora_fin($hora_fin)
    {
        $this->hora_fin = $hora_fin;
    }

    /**
     * @param string $hora_inicio
     */
    public function setHora_inicio($hora_inicio)
    {
        $this->hora_inicio = $hora_inicio;
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
    
    /**
     * @return the $tip_merc
     */
    public function getTip_merc()
    {
        return $this->tip_merc;
    }
    
    /**
     * @param string $tip_merc
     */
    public function setTip_merc($tip_merc)
    {
        $this->tip_merc = $tip_merc;
    }
    
    /**
     * @return the $rev_auto
     */
    public function getRev_auto()
    {
        return $this->rev_auto;
    }
    
    /**
     * @param string $rev_auto
     */
    public function setRev_auto($rev_auto)
    {
        $this->rev_auto = $rev_auto;
    }
} 

?>