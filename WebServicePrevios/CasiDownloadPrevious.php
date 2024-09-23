<?php

/**
 * CasaLibraries AddPrevious
 * File DownloadPrevious.php
 * DownloadPrevious Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			SMV
 * @version    		Previo 1.0.0
 */

require_once 'ConsultReference.php';

class DownloadPrevious
{
    /**
     *
     * @var integer
     */
    public $idclient;
    
    /**
     *
     * @var string
     */
    public $rfc;
    
    /**
     *
     * @var string
     */
    public $aduana;
    
    /**
     *
     * @var string
     */
    public $nom_movil;
    
    /**
     *
     * @var boolean
     */
    private $success;
    
    /**
    * Text of messegae
    *
    * @var string
    */
    private $messageText;
    
    /**
     * Array of Previous objects
     *
     * @var array
     */
    private $arrayPrevious;
    
     /**
     * 
     * @param integer $idclient
     * @param string $patente
     * @param string $aduana
     * @param string $nom_movil
     */
    public function __construct($idclient,$rfc,$aduana,$nom_movil) 
    {
            $this->setIdclient($idclient);
            $this->setRfc($rfc);
            $this->setAduana($aduana);
            $this->setNom_movil($nom_movil);
    }

    /**
     *
     * @param integer $idclient            
     * @param string $patente            
     * @param string $aduana            
     * @param string $nom_movi            
     * @throws Exception
     * @return string
     */
    public function downData()
    {
        // validamos Cliente
        if ($this->getIdclient() == '' || $this->getRfc() == '' || $this->getAduana() == '' || $this->getNom_movil() == '') {
            $this->setSuccess(false);
            $this->setMessageText("Datos requeridos incompletos.");
            return false;
        } else {
            try {
                $db = new PgsqlConnection();
                
                $sql = "
                SELECT
                    \"id_aduana\"
                FROM
                    \"General\".\"casac_aduanas\" 
                WHERE
                     \"clave_aduana\" = '" . $this->getAduana() . "'
                ";
                $response = $db->execute($sql);
                $id_aduana = $response[0]["id_aduana"];
                
                if ($id_aduana > 0) {
                $sql = "
                    SELECT
                        \"id_licencia\"
                    FROM
                        \"General\".\"casag_licencias\"
                    WHERE
                         \"id_cliente\" = '" . $this->getIdclient() . "' AND \"id_aduana\" = $id_aduana AND \"status_licencia\" = 1 ";
                
                $response = $db->execute($sql);
                $id_licencia = $response[0]["id_licencia"];
                } else {
                    throw new Exception("Licencia Cliente Vencida");
                }
                
                if ($id_licencia > 0) {
                    
                    $sql = "
                    SELECT
                        \"id_importador\"
                    FROM
                        \"General\".\"casac_importadores\"
                    WHERE
                         \"id_cliente\" = '" . $this->getIdclient() .  "' AND \"rfc_importador\" = '" . $this->getRfc() . "'";
                    
                    $response = $db->execute($sql);
                    $id_importador = $response[0]["id_importador"];
                    
                    
                    $table = "'Previo'.'cprevo_refe' CR";
                    $joins = "INNER JOIN 'Previo'.'cprevo_previos' CP ON CR.'id_prev' = CP.'id_prev'";
                    $fieldsArray = array(
                        "CR.id_prev",
                        "CR.num_refe",
                        "CP.id_importador",
                        "CP.fec_soli",
                        "CP.fol_soli",
                        "CP.tot_bult",
                        "CP.tot_bultr",
                        "CP.rec_fisc",
                        "CP.num_guia",
                        "CP.edo_prev",
                        "CP.ins_prev",
                        "CP.dep_asigna",
                        "CP.obs_prev"
                    );
                    $parameters = "CP.\"id_importador\" = " . $id_importador . " ";
                    $response = $db->queryWs($table, $fieldsArray, $joins, $parameters);
                    
                    
                    
                    if (! is_array($response)) {
                        throw new Exception($response);
                    }
                    
                    $generalArray = array();
                    
                    foreach ($response as $rs) {
                        $table = "'Previo'.'cprevo_descar' PD";
                        $joins = "";
                        $fieldsArray = array(
                            "PD.id_prev "
                        );
                        $parameters = "PD.\"id_prev\" = " . $rs["id_prev"] . " AND PD.\"nom_movil\" = '" . $this->getNom_movil() . "'";
                        $responseValidation = $db->queryWs($table, $fieldsArray, $joins, $parameters);
                        
                        if (! is_array($responseValidation)) {
                            throw new Exception($responseValidation);
                        }
                        
                        if (count($responseValidation) == 0) {
                            
                            $consultRef = new ConsultReference($rs["num_refe"],$rs["id_importador"], $rs["fec_soli"], $rs["fol_soli"], $rs["tot_bult"], $rs["tot_bultr"], $rs["rec_fisc"], $rs["num_guia"], $rs["edo_prev"], $rs["ins_prev"], $rs["dep_asigna"],$rs["obs_prev"], array());
                            
                            $table = "'Previo'.'cprevo_factur' CF";
                            $joins = "";
                            $fieldsArray = array(
                                "CF.id_factur ",
                                "CF.cons_fact ",
                                "CF.num_fact"
                            );
                            $parameters = "CF.\"id_prev\" = " . $rs["id_prev"] . " ";
                            $response2 = $db->queryWs($table, $fieldsArray, $joins, $parameters);
                            $idFactur = $response2[0]['id_factur'];
                            
                            if (! is_array($response2)) {
                                throw new Exception($response2);
                            }
                            
                            $invoicesArray = array();
                            foreach ($response2 as $rs2) {
                                $invoice = new Invoice();
                                $invoice->setNum_fact($rs2["id_factur"]);
                                $invoice->setCons_fact($rs2["cons_fact"]);
                                $invoice->setNum_fact($rs2["num_fact"]);
                                
                                
                                $table = "'Previo'.'cprevo_facpar' PF";
                                $joins = "";
                                $fieldsArray = array(
                                    "PF.id_partida",
                                    "PF.cons_part",
                                    "PF.num_part",
                                    "PF.desc_merc",
                                    "PF.pai_orig",
                                    "PF.uni_fact",
                                    "PF.can_fact",
                                    "PF.can_factr",
                                    "PF.edo_corr",
                                    "PF.obs_frac",
                                    "PF.cve_usua",
                                    "PF.inc_part",
                                    "PF.uni_tari",
                                    "PF.can_tari"
                                );
                                $parameters = "PF.\"id_factur\" = " . $idFactur . " ";
                                $response3 = $db->queryWs($table, $fieldsArray, $joins, $parameters);
                                
                                if (! is_array($response3)) {
                                    throw new Exception($response3);
                                }
                                
                                $productsArray = array();
                                
                                foreach ($response3 as $rs3) {
                                    $products = new Products();
                                    $products->setCons_part($rs3["cons_part"]);
                                    $products->setNum_part($rs3["num_part"]);
                                    $products->setDesc_merc($rs3["desc_merc"]);
                                    $products->setPai_orig($rs3["pai_orig"]);
                                    $products->setUni_fact($rs3["uni_fact"]);
                                    $products->setCan_fact($rs3["can_fact"]);
                                    $products->setCan_factr($rs3["can_factr"]);
                                    $products->setEdo_corr($rs3["edo_corr"]);
                                    $products->setObs_frac(utf8_encode($rs3["obs_frac"]));
                                    $products->setCve_usua($rs3["cve_usua"]);
                                    $products->setInc_part($rs3["inc_part"]);
                                    $products->setUni_tari($rs3["uni_tari"]);
                                    $products->setCan_tari($rs3["can_tari"]);
                                    
                                    
                                    $table = "'Previo'.'cprevo_facpar' FP";
                                    $joins = "";
                                    $fieldsArray = array(
                                        "FP.id_factur ",
                                        "FP.id_partida "
                                    );
                                    $parameters = "FP.\"id_factur\" = " . $idFactur. " ";
                                    $response4 = $db->queryws($table, $fieldsArray, $joins, $parameters);
                                    $id_partida = $response4[0]['id_partida'];
                                    
                                    if (! is_array($response4)) {
                                        throw new Exception($response4);
                                    }
                                    
                                    $table = "'Previo'.'cprevo_series' PS";
                                    $joins = "";
                                    $fieldsArray = array(
                                        "PS.id_partida",
                                        "PS.cons_seri",
                                        "PS.num_part",
                                        "PS.mar_merc",
                                        "PS.sub_mode",
                                        "PS.num_seri"
                                        
                                    );
                                    $parameters = "PS.\"id_partida\" = " . $id_partida . " ";
                                    $response5 = $db->queryWs($table, $fieldsArray, $joins, $parameters);
                                    
                                    if (! is_array($response5)) {
                                        throw new Exception($response5);
                                    }
                                    
                                    $seriesArray = array();
                                    
                                    foreach ($response5 as $rs5) {
                                        $series = new Series();
                                        $series->setCons_seri($rs5["cons_seri"]);
                                        $series->setNum_part($rs5["num_part"]);
                                        $series->setMar_merc($rs5["mar_merc"]);
                                        $series->setSub_mode($rs5["sub_mode"]);
                                        $series->setNum_seri($rs5["num_seri"]);
                                    
                                    }
                                    
                                    
                                    
                                    $productsArray[] = $products;
                                }
                                
                                
                                
                                
                                
                                $invoice->setProducts($productsArray);
                                
                                $invoicesArray[] = $invoice;
                                
                            }
                            
                            $consultRef->setInvoices($invoicesArray);
                            
                            $generalArray[] = $consultRef;
                            
                            $table = "Previo.cprevo_descar";
                            $values = Array(
                                "id_prev" => $rs["id_prev"],
                                "nom_movil" => $this->getNom_movil(),
                                "fec_desca" => "NOW()"
                            );
                            
                            $db->insert($table, $values);
                        }
                    }
                }
                
                $this->setSuccess(true);
                $this->setMessageText("La operación es correcta");
                $this->setArrayPrevious($generalArray);
                return true;
            } catch (Exception $e) {
                $this->setSuccess(false);
                $this->setMessageText("Error: " . $e->getMessage());
                return false;
            }
        }
    }
    
    /**
     * @return the $idclient
     */
    public function getIdclient()
    {
        return $this->idclient;
    }

   

    /**
     * @return the $aduana
     */
    public function getAduana()
    {
        return $this->aduana;
    }

    /**
     * @return the $nom_movil
     */
    public function getNom_movil()
    {
        return $this->nom_movil;
    }

    /**
     * @return the $success
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * @return the $messageText
     */
    public function getMessageText()
    {
        return $this->messageText;
    }

    /**
     * @return the $arrayPrevious
     */
    public function getArrayPrevious()
    {
        return $this->arrayPrevious;
    }

    /**
     * @param number $idclient
     */
    public function setIdclient($idclient)
    {
        $this->idclient = $idclient;
    }

  

    /**
     * @param string $aduana
     */
    public function setAduana($aduana)
    {
        $this->aduana = $aduana;
    }

    /**
     * @param string $nom_movil
     */
    public function setNom_movil($nom_movil)
    {
        $this->nom_movil = $nom_movil;
    }

    /**
     * @param boolean $success
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }

    /**
     * @param string $messageText
     */
    public function setMessageText($messageText)
    {
        $this->messageText = $messageText;
    }
    
    /**
     * @return the $rfc
     */
    public function getRfc()
    {
        return $this->rfc;
    }
    
    /**
     * @param string $rfc
     */
    public function setRfc($rfc)
    {
        $this->rfc = $rfc;
    }

    /**
     * @param ArrayObject $arrayPrevious
     */
    public function setArrayPrevious(ArrayObject $arrayPrevious)
    {
        $this->arrayPrevious = $arrayPrevious;
    }
    
}

?>