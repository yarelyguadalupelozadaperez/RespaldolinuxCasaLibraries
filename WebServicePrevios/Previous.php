<?php

/**
 * CasaLibraries Previous
 * File Previous.php
 * Previous Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			SMV
 * @version    		Previo 1.0.0
 */
class Previous
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
    public $cve_importador;
    
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
     * @var string
     */
    public $hora_inicio;
    
    /**
     *
     * @var string
     */
    public $hora_fin;
    
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
     * @var integer
     */
   public $tip_refe;
    
   /**
     *
     * @var integer
     */
    public $tip_ope;

    
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
     *
     * @var \File[]
     */
    public $files;

    /**
     *
     * @var boolean
     */
    private $success;
    
    
    /**
     *
     * @var string
     */
    public $patente;
    
    /**
     *
     * @var string
     */
    public $clave_aduana;
                          
                          
 /**
     * Previous Class construct
     * 
     * @param string $num_refe            
     * @param string $rfc_importador            
     * @param string $fec_soli            
     * @param integer $fol_soli            
     * @param integer $tot_bult            
     * @param integer $tot_bultr            
     * @param string $rec_fisc            
     * @param string $num_guia            
     * @param string $edo_prev            
     * @param string $ins_prev            
     * @param string $dep_asigna            
     * @param string $obs_prev
     * @param double $pes_brut 
     * @param string $hora_inicio             
     * @param array $hora_fin  
     * @param string $tip_prev
     * @param string $tip_merc
     * @param string $rev_auto 
     * @param array $clave_aduana 
     * @param string $patente 
     * @param integer $tip_refe    
     * @param integer $tip_ope        
     * @param array $contenedores
     * @param array $bultos 
     * @param array $ordcompras            
     * @param array $files         
     * @param array $invoices   
 
     */

    /**
     * @return string
     */
    public function getCve_importador()
    {
        return $this->cve_importador;
    }

    /**
     * @param string $cve_importador
     */
    public function setCve_importador($cve_importador)
    {
        $this->cve_importador = $cve_importador;
    }

    public function __construct($num_refe, $rfc_importador, $fec_soli, $fol_soli, $tot_bult, $tot_bultr, $rec_fisc, $num_guia, $edo_prev, $ins_prev, $dep_asigna, $obs_prev, $pes_brut,
     $hora_inicio, $hora_fin, $tip_prev, $tip_merc, $rev_auto, $clave_aduana, $patente, $tip_refe, $cve_importador,  $tip_ope, $contenedores, $bultos, $ordcompras, $files, $invoices)
   // public function __construct($num_refe, $rfc_importador, $fec_soli, $fol_soli, $tot_bult, $tot_bultr, $rec_fisc, $num_guia, $edo_prev, $ins_prev, $dep_asigna, $obs_prev, $pes_brut, $hora_inicio, $hora_fin, $tip_prev, $tip_merc, $rev_auto, $clave_aduana, $patente, $contenedores, $bultos, $ordcompras, $files, $invoices)
    
    
    {
            
        $this->setNum_refe($num_refe);
        $this->setRfc_importador($rfc_importador);
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
        $this->setPes_brut($pes_brut);
        $this->setHora_inicio($hora_inicio);
        $this->setHora_fin($hora_fin);
        $this->setTip_prev($tip_prev);
        $this->setTip_merc($tip_merc);
        $this->setRev_auto($rev_auto);
        $this->setClave_aduana($clave_aduana);
        $this->setPatente($patente);
        $this->setTip_refe($tip_refe);
        $this->setCve_importador($cve_importador);
        $this->setTip_ope($tip_ope);
        $this->setContenedores($contenedores);
        $this->setBultos($bultos);
        $this->setOrdcompras($ordcompras);
        $this->setFiles($files);
        $this->setInvoices($invoices);

    }

    
    /**
     *
     * @return the $num_refe
     */
    public function getNum_refe()
    {
        return $this->num_refe;
    }

    /**
     *
     * @return the $rfc_importador
     */
    public function getRfc_importador()
    {
        return $this->rfc_importador;
    }

    /**
     *
     * @return the $fec_soli
     */
    public function getFec_soli()
    {
        return $this->fec_soli;
    }

    /**
     *
     * @return the $fol_soli
     */
    public function getFol_soli()
    {
        return $this->fol_soli;
    }

    /**
     *
     * @return the $tot_bult
     */
    public function getTot_bult()
    {
        return $this->tot_bult;
    }

    /**
     *
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
     * @return the $ins_prev
     */
    public function getIns_prev()
    {
        return $this->ins_prev;
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
     *
     * @return the $files
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     *
     * @return the $invoices
     */
    public function getInvoices()
    {
        return $this->invoices;
    }

    /**
     *
     * @return the $success
     */
    public function getSuccess()
    {
        return $this->success;
    }
    
    /**
     *
     * @param string $num_refe            
     */
    public function setNum_refe($num_refe)
    {
        $this->num_refe = $num_refe;
    }

    /**
     *
     * @param string $rfc_importador            
     */
    public function setRfc_importador($rfc_importador)
    {
        $this->rfc_importador = $rfc_importador;
    }

    /**
     *
     * @param string $fec_soli            
     */
    public function setFec_soli($fec_soli)
    {
        $this->fec_soli = $fec_soli;
    }

    /**
     *
     * @param string $fol_soli            
     */
    public function setFol_soli($fol_soli)
    {
        $this->fol_soli = $fol_soli;
    }

    /**
     *
     * @param string $tot_bult            
     */
    public function setTot_bult($tot_bult)
    {
        $this->tot_bult = $tot_bult;
    }

    /**
     *
     * @param string $tot_bultr            
     */
    public function setTot_bultr($tot_bultr)
    {
        $this->tot_bultr = $tot_bultr;
    }

    /**
     *
     * @param string $rec_fisc            
     */
    public function setRec_fisc($rec_fisc)
    {
        $this->rec_fisc = $rec_fisc;
    }

    /**
     *
     * @param string $num_guia            
     */
    public function setNum_guia($num_guia)
    {
        $this->num_guia = $num_guia;
    }

    /**
     *
     * @param string $edo_prev            
     */
    public function setEdo_prev($edo_prev)
    {
        $this->edo_prev = $edo_prev;
    }

    /**
     *
     * @param string $ins_prev            
     */
    public function setIns_prev($ins_prev)
    {
        $this->ins_prev = $ins_prev;
    }

    /**
     *
     * @param string $dep_asigna            
     */
    public function setDep_asigna($dep_asigna)
    {
        $this->dep_asigna = $dep_asigna;
    }

    /**
     *
     * @param string $obs_prev            
     */
    public function setObs_prev($obs_prev)
    {
        $this->obs_prev = $obs_prev;
    }

    /**
     *
     * @param multitype:Contenedores $contenedores            
     */
    public function setContenedores($contenedores)
    {
        $this->contenedores = $contenedores;
    }

    /**
     *
     * @param multitype:Bultos $bultos            
     */
    public function setBultos($bultos)
    {
        $this->bultos = $bultos;
    }

    /**
     *
     * @param multitype:Ordcompras $ordcompras            
     */
    public function setOrdcompras($ordcompras)
    {
        $this->ordcompras = $ordcompras;
    }

    /**
     *
     * @param multitype:File $files            
     */
    public function setFiles($files)
    {
        $this->files = $files;
    }

    /**
     *
     * @param multitype:Invoice $invoices            
     */
    public function setInvoices($invoices)
    {
        $this->invoices = $invoices;
    }

    /**
     *
     * @param boolean $success            
     */
    public function setSuccess($success)
    {
        $this->success = $success;
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
     * @return the $hora_inicio
     */
    public function getHora_inicio()
    {
        return $this->hora_inicio;
    }
    
    /**
     * @return the $patente
     */
    public function getPatente()
    {
        return $this->patente;
    }

    /**
     * @return the $hora_fin
     */
    public function getHora_fin()
    {
        return $this->hora_fin;
    }

    /**
     * @param string $hora_inicio
     */
    public function setHora_inicio($hora_inicio)
    {
        $this->hora_inicio = $hora_inicio;
    }

    /**
     * @param string $hora_fin
     */
    public function setHora_fin($hora_fin)
    {
        $this->hora_fin = $hora_fin;
    }

    /**
     * @param string $patente
     */
    public function setPatente($patente)
    {
        $this->patente = $patente;
    }
    
     /**
     * @return the  $clave_aduana
     */
    function getClave_aduana() {
        return $this->clave_aduana;
    }

     /**
     * @param string $clave_aduana
     */
    function setClave_aduana($clave_aduana) {
        $this->clave_aduana = $clave_aduana;
    }

    /**
     *
     * @return the $tip_prev
     */
    public function getTip_prev()
    {
        return $this->tip_prev;
    }
    
    /**
     * @param string $tip_prev
     */
    function setTip_prev($tip_prev) {
        $this->tip_prev = $tip_prev;
    }
    
    /**
     *
     * @return the $tip_merc
     */
    public function getTip_merc()
    {
        return $this->tip_merc;
    }
    
    /**
     * @param string $tip_merc
     */
    function setTip_merc($tip_merc) {
        $this->tip_merc = $tip_merc;
    }
    
    /**
     *
     * @return the $rev_auto
     */
    public function getRev_auto()
    {
        return $this->rev_auto;
    }
    
    /**
     * @param string $rev_auto
     */
    function setRev_auto($rev_auto) {
        $this->rev_auto = $rev_auto;
    }
    
    
    /**
     *
     * @return the $tip_refe
     */
    public function getTip_refe()
    {
        return $this->tip_refe;
    }
    
    /**
     * @param integer $tip_refe
     */
    function setTip_refe($tip_refe) {
        $this->tip_refe = $tip_refe;
    }
    
    
    /**
     *
     * @return the $tip_ope
     */
    public function getTip_ope()
    {
        return $this->tip_ope;
    }
    
    /**
     * @param integer $tip_ope
     */
    function setTip_ope($tip_ope) {
        $this->tip_ope = $tip_ope;
    }
    


}

?>
