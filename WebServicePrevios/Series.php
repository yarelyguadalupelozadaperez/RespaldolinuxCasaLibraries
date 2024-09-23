<?php
/**
 * CasaLibraries Previo
 * File series.php
 * File Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_wsPrevio
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			SMV
 * @version    		Previo 1.1.0
 */

class Series
{
    
    /**
     *
     * @var integer
     */
    public $cons_seri;
    
    /**
     *
     * @var string
     */
    public $num_part;
    
    /**
     * 
     * @var string
     */
    public $mar_merc;
    
    /**
     * 
     * @var string
     */
    public $sub_mode;
    
    /**
     * 
     * @var string
     */
    public $num_seri;
    
    /**
     * 
     * @var boolean
     */
    private $success;
    
    
  /**
   * @param integer $cons_seri
   * @param string $num_part
   * @param string $mar_merc
   * @param string $sub_mode
   * @param string $num_seri
   */
    public function __construct($cons_seri,$num_part,$mar_merc,$sub_mode,$num_seri)
    {
    
        $this->setCons_seri($cons_seri);
        $this->setNum_part($num_part);
        $this->setMar_merc($mar_merc);
        $this->setSub_mode($sub_mode);
        $this->setNum_seri($num_seri);
    
    }
    
    /**
     * carga datos a series
     */
    
    public function seriesload()
    {
        var_dump("En series");
        exit();
    }
    
    /**
     * @return the $cons_seri
     */
    public function getCons_seri()
    {
        return $this->cons_seri;
    }
    
    /**
     * @return the $num_part
     */
    public function getNum_part()
    {
        return $this->num_part;
    }
    
    /**
     * @return the $mar_merc
     */
    public function getMar_merc()
    {
        return $this->mar_merc;
    }
    
    /**
     * @return the $sub_mode
     */
    public function getSub_mode()
    {
        return $this->sub_mode;
    }
    
    /**
     * @return the $num_seri
     */
    public function getNum_seri()
    {
        return $this->num_seri;
    }
    
    /**
     * @return the $success
     */
    public function getSuccess()
    {
        return $this->success;
    }
    
    /**
     * @param number $cons_seri
     */
    public function setCons_seri($cons_seri)
    {
        $this->cons_seri = $cons_seri;
    }
    
    /**
     * @param string $num_part
     */
    public function setNum_part($num_part)
    {
        $this->num_part = $num_part;
    }
    
    /**
     * @param string $mar_merc
     */
    public function setMar_merc($mar_merc)
    {
        $this->mar_merc = $mar_merc;
    }
    
    /**
     * @param string $sub_mode
     */
    public function setSub_mode($sub_mode)
    {
        $this->sub_mode = $sub_mode;
    }
    
    /**
     * @param string $num_seri
     */
    public function setNum_seri($num_seri)
    {
        $this->num_seri = $num_seri;
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