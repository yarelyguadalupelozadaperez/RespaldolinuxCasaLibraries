<?php

/**
 * CasaLibraries ProductDetails
 * ProductDetails ProductDetails.php
 * ProductDetails Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			SMV
 * @version    		Previo 1.0.0
 */

require_once 'CasaLibraries/WebServicePrevios/File.php';
require_once 'CasaLibraries/WebServicePrevios/Series.php';

class ProductDetails
{
    /**
     *
     * @var integer
     */
    public $cons_fact;
    
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
    public $des_merc;
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
     * @var integer
     */
    public $can_fact;
    /**
     *
     * @var integer
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
     * @var integer
     */
    public $can_tari;
    
    
    /**
     * 
     * @var File[]
     */
    public $files;
    
    /**
     *
     * @var Series[]
     */
    public $series;

    
    
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
     * @param number $can_tari
     */
    public function setCan_tari($can_tari)
    {
        $this->can_tari = $can_tari;
    }

 /**
     * @return the $series
     */
    public function getSeries()
    {
        return $this->series;
    }

 /**
     * @param Series[] $series
     */
    public function setSeries($series)
    {
        $this->series = $series;
    }

 /**
     * @return the $files
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param File[] $files
     */
    public function setFiles($files)
    {
        $this->files = $files;
    }

    /**
     * @return the $cons_fact
     */
    public function getCons_fact()
    {
        return $this->cons_fact;
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
     * @return the $des_merc
     */
    public function getDes_merc()
    {
        return $this->des_merc;
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
     * @param number $cons_fact
     */
    public function setCons_fact($cons_fact)
    {
        $this->cons_fact = $cons_fact;
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
     * @param string $des_merc
     */
    public function setDes_merc($des_merc)
    {
        $this->des_merc = $des_merc;
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
     * @param number $can_fact
     */
    public function setCan_fact($can_fact)
    {
        $this->can_fact = $can_fact;
    }

    /**
     * @param number $can_factr
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

   

}

?>