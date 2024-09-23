<?php

/**
 * CasaLibraries CtracFracpar
 * File CtracFracpar.php
 * CtracFracpar Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			AJPE
 * @version    		Previo 1.0.0
 */


class CtracFracpar
{

    /**
     *
     * @var integer
     */
    public $id_part;


    /**
     *
     * @var integer
     */
    public $id_fracpar;

    /**
     *
     * @var string
     */
    public $num_part;
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
     * @var string
     */
    public $desc_merc;
    
        
    /**
     *
     * @var integer
     */
    public $num_partcove;

 
     /**
     *
     * @var integer
     */
    public $flag_fracc;
    
    
    /**
     *
     * @var integer
     */
    public $origen_fracc;
    
    /**
     *
     * @var String
     */
    public $cve_impo;
    
    /**
     *
     * @var String
     */
    public $cve_pro;
    
    /**
     *
     * @var String
     */
    public $rfc_importador;

    /**
     *
     * @var integer
     */
    public $tip_ope;

    /**
     *
     * @var integer
     */
    public $val_part;

    /**
     *
     * @var integer
     */
    public $uni_fact;





    /**
     * @return tip_ope
     */
    public function getUni_fact()
    {
        return $this->uni_fact;
    }

    /**
     * @param integer $uni_fact
     */
    public function setUni_fact($uni_fact)
    {
        $this->uni_fact = $uni_fact;
    }

    /**
     * @return tip_ope
     */
    public function getVal_part()
    {
        return $this->val_part;
    }

    /**
     * @param integer $val_part
     */
    public function setVal_part($val_part)
    {
        $this->val_part = $val_part;
    }

    /**
     * @return tip_ope
     */
    public function getTip_ope()
    {
        return $this->tip_ope;
    }

    /**
     * @param integer $tip_ope
     */
    public function setTip_ope($tip_ope)
    {
        $this->tip_ope = $tip_ope;
    }

    /**
     * @return rfc_importador
     */
    public function getRfc_importador()
    {
        return $this->rfc_importador;
    }

    /**
     * @param String $rfc_importador
     */
    public function setRfc_importador($rfc_importador)
    {
        $this->rfc_importador = $rfc_importador;
    }

    /**
     * @return cve_impo
     */
    public function getCve_impo()
    {
        return $this->cve_impo;
    }

    /**
     * @param String $cve_impo
     */
    public function setCve_impo($cve_impo)
    {
        $this->cve_impo = $cve_impo;
    }

    /**
     *
    /**
     * @return the $num_part
     */
    public function getNum_part()
    {
        return $this->num_part;
    }

    /**
     * @return the $cve_pro
     */
    public function getCve_pro()
    {
        return $this->cve_pro;
    }
        /**
     * @param string $cve_pro
     */
    public function setCve_pro($cve_pro)
    {
        $this->cve_pro = $cve_pro;
    }

     /**
     * @return the $num_fracc
     */
    public function getNum_fracc()
    {
        return $this->num_fracc;
    }
        /**
     * @return the $cve_nico
     */
    public function getCve_nico()
    {
        return $this->cve_nico;
    }
            /**
     * @return the $desc_merc
     */
    public function getDesc_merc()
    {
        return $this->desc_merc;
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
     * @param string $num_part
     */
    public function setNum_part($num_part)
    {
        $this->num_part = $num_part;
    }

     /**
     * @param number $num_fracc
     */
    public function setNum_fracc($num_fracc)
    {
        $this->num_fracc = $num_fracc;
    }
    /**
     * @param double $cve_nico
     */
    public function setCve_nico($cve_nico)
    {
        $this->cve_nico = $cve_nico;
    }
        /**
     * @param string $desc_merc
     */
    public function setDesc_merc($desc_merc)
    {
        $this->desc_merc = $desc_merc;
    }
    
    /**
     * @param boolean $success
     */
    private function setSuccess($success)
    {
        $this->success = $success;
    }
    
        /**
     * @param integer $num_partcove
     */
    public function setNum_partcove($num_partcove)
    {
        $this->num_partcove = $num_partcove;
    }
    
    /**
     * @return the $num_partcove
     */
    public function getNum_partcove()
    {
        return $this->num_partcove;
    }
    
    /**
     * @param integer $flag_fracc
     */
    public function setFlag_fracc($flag_fracc)
    {
        $this->flag_fracc = $flag_fracc;
    }
    
    /**
     * @return the $flag_fracc
     */
    public function getFlag_fracc()
    {
        return $this->flag_fracc;
    }
    
    /**
     * @param integer $origen_fracc
     */
    public function setOrigen_fracc($origen_fracc)
    {
        $this->origen_fracc = $origen_fracc;
    }
    
    /**
     * @return the $origen_fracc
     */
    public function getOrigen_fracc()
    {
        return $this->origen_fracc;
    }
    
     /**
     * @param integer $id_fracpar
     */
    public function setId_fracpar($id_fracpar)
    {
        $this->id_fracpar = $id_fracpar;
    }
    
    /**
     * @return the $id_fracpar
     */
    public function getId_fracpar()
    {
        return $this->id_fracpar;
    }


    /**
     * @param integer $id_part
     */
    public function setId_part($id_part)
    {
        $this->id_part = $id_part;
    }
    
    /**
     * @return the $id_part
     */
    public function getId_part()
    {
        return $this->id_part;
    }
    
}
?>