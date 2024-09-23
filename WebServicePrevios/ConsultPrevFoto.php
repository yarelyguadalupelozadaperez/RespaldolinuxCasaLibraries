<?php

/**
 * CasaLibraries ConsultPrevFoto.php
 * File ConsultPrevFoto.php
 * AddPrevious Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			SMV
 * @version    		Previo 1.0.0
 */

require_once 'Invoice.php';

class ConsultPrevFoto
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
    public $fec_soli;
    
    /**
     *
     * @var integer
     */
    public $fol_soli;
    
    /**
     *
     * @var string
     */
    public $cve_impo;
    
    /**
     *
     * @var string
     */
    public $nom_clie;
    
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
     * @var \Invoice[]
     */
    public $invoices;
    
    /**
     * 
     * @param string $num_refe
     * @param string $fec_soli
     * @param integer $fol_soli
     * @param string $cve_impo
     * @param string $nom_clie
     * @param integer $tot_bult
     * @param integer $tot_bultr
     * @param string $rec_fisc
     * @param string $num_guia
     * @param integer $edo_prev
     * @param string $ins_prev
     * @param array $invoices
     */
    public function __construct($num_refe, $fec_soli,$fol_soli,$cve_impo,$nom_clie,$tot_bult,$tot_bultr,$rec_fisc,$num_guia,$edo_prev,$ins_prev,$invoices)
    {
        $this->setNum_refe($num_refe);
        $this->setFec_soli($fec_soli);
        $this->setFol_soli($fol_soli);
        $this->setCve_impo($cve_impo);
        $this->setNom_clie($nom_clie);
        $this->setTot_bult($tot_bult);
        $this->setTot_bultr($tot_bultr);
        $this->setRec_fisc($rec_fisc);
        $this->setNum_guia($num_guia);
        $this->setEdo_prev($edo_prev);
        $this->setIns_prev($ins_prev);
        $this->setInvoices($invoices);
    }
    
   

}

?>