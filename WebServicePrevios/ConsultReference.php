<?php

/**
 * CasaLibraries ConsultReference.php
 * File ConsultReference.php
 * AddPrevious Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			SMV
 * @version    		Previo 1.0.0
 */

require_once 'Invoice.php';
require_once 'Products.php';
require_once 'Series.php';

class ConsultReference
{
    /**
     * 
     * @var string
     */
    public $num_refe;
    
    /**
     *
     * @var integer
     */
    public $id_importador;
    
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
     * @var integer
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
     * @var \Invoice[]
     */
    public $invoices;
    

public function __construct($num_refe,$id_importador,$fec_soli,$fol_soli,$tot_bult,$tot_bultr,$rec_fisc,$num_guia,$edo_prev,$ins_prev,$dep_asigna,$obs_prev,$invoices){
     
         $this->setNum_refe($num_refe);
         $this->setId_importador($id_importador);
         $this->setFec_soli($fec_soli);
         $this->setFol_soli($fol_soli);
         $this->setTot_bult($tot_bult);
         $this->setTot_bultr($tot_bultr);
         $this->setRec_fisc($rec_fisc);
         $this->setNum_guia($num_guia);
         $this->setEdo_prev($edo_prev);
         $this->setIns_prev($ins_prev);
         $this->setDep_asigna($dep_asigna);
         $this->setObs_prev($obs_prev);
         $this->setInvoices($invoices);
         
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
     * @return the $tot_bultr
     */
    public function getTot_bultr()
    {
        return $this->tot_bultr;
    }

     /**
     * @return the $rec_fisc
     */
    public function getRec_fisc()
    {
        return $this->rec_fisc;
    }

     /**
     * @return the $num_guia
     */
    public function getNum_guia()
    {
        return $this->num_guia;
    }

     /**
     * @return the $edo_prev
     */
    public function getEdo_prev()
    {
        return $this->edo_prev;
    }

     /**
     * @return the $ins_prev
     */
    public function getIns_prev()
    {
        return $this->ins_prev;
    }

     /**
     * @return the $fec_envio
     */
    public function getFec_envio()
    {
        return $this->fec_envio;
    }

     /**
     * @return the $fec_fin
     */
    public function getFec_fin()
    {
        return $this->fec_fin;
    }

    /**
     * @return the $fec_inicio
     */
    public function getFec_inicio()
    {
        return $this->fec_inicio;
    }

     /**
     * @return the $obs_prev
     */
    public function getObs_prev()
    {
        return $this->obs_prev;
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
     * @param number $tot_bultr
     */
    public function setTot_bultr($tot_bultr)
    {
        $this->tot_bultr = $tot_bultr;
    }

    /**
     * @param string $rec_fisc
     */
    public function setRec_fisc($rec_fisc)
    {
        $this->rec_fisc = $rec_fisc;
    }

    /**
     * @param string $num_guia
     */
    public function setNum_guia($num_guia)
    {
        $this->num_guia = $num_guia;
    }

    /**
     * @param number $edo_prev
     */
    public function setEdo_prev($edo_prev)
    {
        $this->edo_prev = $edo_prev;
    }

    /**
     * @param string $ins_prev
     */
    public function setIns_prev($ins_prev)
    {
        $this->ins_prev = $ins_prev;
    }

    /**
     * @param string $fec_envio
     */
    public function setFec_envio($fec_envio)
    {
        $this->fec_envio = $fec_envio;
    }

     /**
     * @param string $fec_fin
     */
    public function setFec_fin($fec_fin)
    {
        $this->fec_fin = $fec_fin;
    }

     /**
     * @param string $fec_inicio
     */
    public function setFec_inicio($fec_inicio)
    {
        $this->fec_inicio = $fec_inicio;
    }

     /**
     * @param string $obs_prev
     */
    public function setObs_prev($obs_prev)
    {
        $this->obs_prev = $obs_prev;
    }

     /**
     * @return the $invoices
     */
    public function getInvoices()
    {
        return $this->invoices;
    }
    
    /**
     * @return the $id_importador
     */
    public function getId_importador()
    {
        return $this->id_importador;
    }
    
    /**
     * @param number $id_importador
     */
    public function setId_importador($id_importador)
    {
        $this->id_importador = $id_importador;
    }
    
    /**
     * @return the $dep_asigna
     */
    public function getDep_asigna()
    {
        return $this->dep_asigna;
    }
    
    /**
     * @param string $dep_asigna
     */
    public function setDep_asigna($dep_asigna)
    {
        $this->dep_asigna = $dep_asigna;
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