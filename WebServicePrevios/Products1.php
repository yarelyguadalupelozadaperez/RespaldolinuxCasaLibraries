<?php

/**
 * CasaLibraries Products
 * File Products.php
 * Products Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			SMV
 * @version    		Previo 1.0.0
 */

require_once 'Series.php';

class Products1
{
    /**
     *
     * @var integer
     */
    public $cons_part;
    
    /**
     *
     * @var string
     */
    public $num_part;
    /**
     *
     * @var string
     */
    public $desc_merc;
    /**
     *
     * @var string
     */
    public $pai_orig;
    /**
     *
     * @var integer
     */
    public $uni_fact;
    /**
     *
     * @var double
     */
    public $can_fact;
    /**
     *
     * @var double
     */
    public $can_factr;
    /**
     *
     * @var integer
     */
    public $edo_corr;
    /**
     *
     * @var string
     */
    public $obs_frac;
    /**
     *
     * @var string
     */
    public $cve_usua;
    
    /**
     *
     * @var string
     */
    public $inc_part;
    
    /**
     *
     * @var integer
     */
    public $uni_tari;
    
    /**
     *
     * @var double
     */
    public $can_tari;
    
    /**
     *
     * @var double
     */
    public $pes_unit;
   
    
    /**
     *
     * @var integer
     */
    public $tip_pes;
    
    
    /**
     *
     * @var string
     */
    public $num_fracc;
    
    /**
     *
     * @var string
     */
    public $cve_nico;

    /**
     *
     * @var \Series[]
     */
    public $series;
    

    /**
     * @return num_fracc
     */
    public function getNum_fracc()
    {
        return $this->num_fracc;
    }

    /**
     * @return cve_nico
     */
    public function getCve_nico()
    {
        return $this->cve_nico;
    }


    /**
     * @param string $num_fracc
     */
    public function setNum_fracc($num_fracc)
    {
        $this->num_fracc = $num_fracc;
    }

    /**
     * @param string $cve_nico
     */
    public function setCve_nico($cve_nico)
    {
        $this->cve_nico = $cve_nico;
    }


    /**
     * @return the $inc_part
     */
    public function getInc_part()
    {
        return $this->inc_part;
    }

 /**
     * @return the $uni_tari
     */
    public function getUni_tari()
    {
        return $this->uni_tari;
    }

 /**
     * @return the $can_tari
     */
    public function getCan_tari()
    {
        return $this->can_tari;
    }

 /**
     * @return the $series
     */
    public function getSeries()
    {
        return $this->series;
    }

 /**
     * @param string $inc_part
     */
    public function setInc_part($inc_part)
    {
        $this->inc_part = $inc_part;
    }

 /**
     * @param number $uni_tari
     */
    public function setUni_tari($uni_tari)
    {
        $this->uni_tari = $uni_tari;
    }

 /**
     * @param double $can_tari
     */
    public function setCan_tari($can_tari)
    {
        $this->can_tari = $can_tari;
    }

 /**
     * @param Series[] $series
     */
    public function setSeries($series)
    {
        $this->series = $series;
    }

 /**
     * @return the $cons_part
     */
    public function getCons_part()
    {
        return $this->cons_part;
    }

    /**
     * @return the $num_part
     */
    public function getNum_part()
    {
        return $this->num_part;
    }

    /**
     * @return the $desc_merc
     */
    public function getDesc_merc()
    {
        return $this->desc_merc;
    }

    /**
     * @return the $pai_orig
     */
    public function getPai_orig()
    {
        return $this->pai_orig;
    }

    /**
     * @return the $uni_fact
     */
    public function getUni_fact()
    {
        return $this->uni_fact;
    }

    /**
     * @return the $can_fact
     */
    public function getCan_fact()
    {
        return $this->can_fact;
    }

     /**
     * @return the $can_factr
     */
    public function getCan_factr()
    {
        return $this->can_factr;
    }

     /**
     * @return the $edo_corr
     */
    public function getEdo_corr()
    {
        return $this->edo_corr;
    }

    /**
     * @return the $obs_frac
     */
    public function getObs_frac()
    {
        return $this->obs_frac;
    }

    /**
     * @return the $cve_usua
     */
    public function getCve_usua()
    {
        return $this->cve_usua;
    }

    /**
     * @param number $cons_part
     */
    public function setCons_part($cons_part)
    {
        $this->cons_part = $cons_part;
    }

    /**
     * @param string $num_part
     */
    public function setNum_part($num_part)
    {
        $this->num_part = $num_part;
    }

    /**
     * @param string $desc_merc
     */
    public function setDesc_merc($desc_merc)
    {
        $this->desc_merc = $desc_merc;
    }

     /**
     * @param string $pai_orig
     */
    public function setPai_orig($pai_orig)
    {
        $this->pai_orig = $pai_orig;
    }

    /**
     * @param number $uni_fact
     */
    public function setUni_fact($uni_fact)
    {
        $this->uni_fact = $uni_fact;
    }

    /**
     * @param double $can_fact
     */
    public function setCan_fact($can_fact)
    {
        $this->can_fact = $can_fact;
    }

    /**
     * @param double $can_factr
     */
    public function setCan_factr($can_factr)
    {
        $this->can_factr = $can_factr;
    }

    /**
     * @param number $edo_corr
     */
    public function setEdo_corr($edo_corr)
    {
        $this->edo_corr = $edo_corr;
    }

    /**
     * @param string $obs_frac
     */
    public function setObs_frac($obs_frac)
    {
        $this->obs_frac = $obs_frac;
    }

    /**
     * @param string $cve_usua
     */
    public function setCve_usua($cve_usua)
    {
        $this->cve_usua = $cve_usua;
    }

    /**
     * @param boolean $success
     */
    private function setSuccess($success)
    {
        $this->success = $success;
    }
    /**
     * @return the $pes_unit
     */
    public function getPes_unit()
    {
        return $this->pes_unit;
    }

    /**
     * @param number $pes_unit
     */
    public function setPes_unit($pes_unit)
    {
        $this->pes_unit = $pes_unit;
    }
    
    /**
     * @return the $tip_pes
     */
    public function getTip_pes()
    {
        return $this->tip_pes;
    }

    /**
     * @param number $tip_pes
     */
    public function setTip_pes($tip_pes)
    {
        $this->tip_pes = $tip_pes;
    }


    
}

?>