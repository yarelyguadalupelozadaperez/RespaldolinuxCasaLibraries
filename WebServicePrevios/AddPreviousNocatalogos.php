<?php

/**
 * CasaLibraries AddPrevious
 * File AddPrevious.php
 * AddPrevious Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			SMV
 * @version    		Previo 1.0.0
 */
require_once 'Invoice.php';

class AddPrevious
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
    public $aduana;

    /**
     *
     * @var string
     */
    public $rfc_impo;

    /**
     *
     * @var string
     */
    public $num_refe;

    /**
     *
     * @var string
     */
    public $clave_tipcon;

    /**
     *
     * @var string
     */
    public $clave_bulto;

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
     * @var string
     */
    public $numero_contenedor;

    /**
     *
     * @var string
     */
    public $numero_candado1;

    /**
     *
     * @var string
     */
    public $numero_candado2;

    /**
     *
     * @var string
     */
    public $numero_candado3;

    /**
     *
     * @var string
     */
    public $numero_candado4;

    /**
     *
     * @var string
     */
    public $numero_candado5;

    /**
     *
     * @var string
     */
    public $obs_cont;

    /**
     *
     * @var integer
     */
    public $cons_bulto;

    /**
     *
     * @var integer
     */
    public $cant_bult;

    /**
     *
     * @var integer
     */
    public $anc_bult;

    /**
     *
     * @var integer
     */
    public $lar_bult;

    /**
     *
     * @var integer
     */
    public $alt_bult;

    /**
     *
     * @var string
     */
    public $obs_bult;

    /**
     *
     * @var integer
     */
    public $cons_orcom;

    /**
     *
     * @var string
     */
    public $num_orcom;

    /**
     *
     * @var \Invoice[]
     */
    public $invoices;

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
     * @param Invoice[] $invoices            
     */
    public function setInvoices($invoices)
    {
        $this->invoices = $invoices;
    }

    /**
     *
     * @var boolean
     */
    private $success;

    public function __construct($idclient, $aduana, $rfc_impo, $num_refe, $clave_tipcon, $clave_bulto, $fec_soli, $fol_soli, $tot_bult, $tot_bultr, $rec_fisc, $num_guia, $edo_prev, $ins_prev, $dep_asigna, $obs_prev, $numero_contenedor, $numero_candado1, $numero_candado2, $numero_candado3, $numero_candado4, $numero_candado5, $obs_cont, $cons_bulto, $cant_bult, $anc_bult, $lar_bult, $alt_bult, $obs_bult, $cons_orcom, $num_orcom, $invoices)
    {
        $this->setIdclient($idclient);
        $this->setAduana($aduana);
        $this->setRfc_impo($rfc_impo);
        $this->setNum_refe($num_refe);
        $this->setClave_tipcon($clave_tipcon);
        $this->setClave_bulto($clave_bulto);
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
        $this->setNumero_contenedor($numero_contenedor);
        $this->setNumero_candado1($numero_candado1);
        $this->setNumero_candado2($numero_candado2);
        $this->setNumero_candado3($numero_candado3);
        $this->setNumero_candado4($numero_candado4);
        $this->setNumero_candado5($numero_candado5);
        $this->setObs_cont($obs_cont);
        $this->setCons_bulto($cons_bulto);
        $this->setCant_bult($cant_bult);
        $this->setAnc_bult($anc_bult);
        $this->setLar_bult($lar_bult);
        $this->setAlt_bult($alt_bult);
        $this->setObs_bult($obs_bult);
        $this->setCons_orcom($cons_orcom);
        $this->setNum_orcom($num_orcom);
        $this->setInvoices($invoices);
    }
    
    // addInDataBase
    public function addInDataBase()
    
    {
        
        // validamos Cliente
        if ($this->getIdclient() == '' || $this->getAduana() == '' || $this->getRfc_impo() == "" || $this->getNum_refe() == "" || $this->getClave_tipcon() == "" || $this->getClave_bulto() == "")
            throw new Exception("Datos incompletos");
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
                     \"id_cliente\" = '" . $this->getIdclient() . "' AND \"rfc_importador\" = '" . $this->getRfc_impo() . "'";
            
            $response = $db->execute($sql);
            $id_importador = $response[0]["id_importador"];
        } else {
            throw new Exception("Licencia Cliente Vencida");
        }
        
        if ($id_importador > 0) {
            
            $sql = "
                   SELECT
                   \"id_licenciasistema\"
                   FROM
                   \"General\".\"casag_licenciasistema\"
                   WHERE
                   \"id_licencia\" = ' $id_licencia ' AND \"id_sistema\" = '2'";
            
            $response = $db->execute($sql);
            $id_licenciasistema = $response[0]["id_licenciasistema"];
        } else {
            
            throw new Exception("Licencia Importador Vencida");
        }
        
        if ($id_licenciasistema > 0) {
            
            $sql = "
                     SELECT
                     \"id_licenciasimportador\"
                     FROM
                     \"General\".\"casag_licenciasimportador\"
                     WHERE
                     \"id_licenciasistema\" = ' $id_licenciasistema ' AND \"id_importador\" = $id_importador ";
            
            $response = $db->execute($sql);
            $id_licenciasimportador = $response[0]["id_licenciasimportador"];
        } else {
            throw new Exception("Licencia Importador Vencida");
        }
        
        try {
            
            if ($id_licenciasimportador > 0) {
                
                $sql = "SELECT nextval('\"Previo\".\"cprevo_refe_id_prev_seq\"') maximo";
                $response = $db->execute($sql);
                
                // id_prev
                $ultimo = $response[0]["maximo"];
                
                $sql = "INSERT INTO \"Previo\".\"cprevo_refe\" (\"id_prev\",\"id_licencia\",\"num_refe\") VALUES (
                  " . $ultimo . ", " . $id_licencia . ",'" . $this->getNum_refe() . "')";
                $response = $db->execute($sql);
                
                if ($ultimo > 0) {
                    
                    $sql = "INSERT INTO \"Previo\".\"cprevo_previos\" (\"id_cprevo\",\"id_prev\",\"id_importador\",\"fec_soli\", \"fol_soli\", \"tot_bult\", \"tot_bultr\", \"rec_fisc\", \"num_guia\", \"edo_prev\", \"ins_prev\",\"dep_asigna\", \"obs_prev\")
                        VALUES (nextval('\"Previo\".\"cprevo_previos_id_cprevo_seq\"'),
                      " . $ultimo . "," . $id_importador . ",'" . $this->getFec_soli() . "'," . $this->getFol_soli() . "," . $this->getTot_bult() . ", " . $this->getTot_bultr() . ",'" . $this->getRec_fisc() . "','" . $this->getNum_guia() . "','" . $this->getEdo_prev() . "','" . $this->getIns_prev() . "','" . $this->getDep_asigna() . "','" . $this->getObs_prev() . "')";
                    $response = $db->execute($sql);
                    
                    $sql1 = "
                    SELECT
                    \"id_tipcon\"
                    FROM
                    \"General\".\"casac_tipcon\"
                    WHERE
                    \"clave_tipcon\" = '" . $this->getClave_tipcon() . "'";
                    
                    $response = $db->execute($sql1);
                    $id_tipcon = $response[0]["id_tipcon"];
                    
                    $sql2 = "INSERT INTO \"Previo\".\"cop_conten\" (\"id_conten\",\"id_prev\",\"id_tipcon\",\"numero_contenedor\", \"numero_candado1\", \"numero_candado2\", \"numero_candado3\", \"numero_candado4\", \"numero_candado5\", \"obs_cont\")
                        VALUES (nextval('\"Previo\".\"cop_conten_id_conten_seq\"'),
                      " . $ultimo . "," . $id_tipcon . ",'" . $this->getNumero_contenedor() . "'," . $this->getNumero_candado1() . "," . $this->getNumero_candado2() . ", " . $this->getNumero_candado3() . ",'" . $this->getNumero_candado4() . "','" . $this->getNumero_candado5() . "','" . $this->getObs_cont() . "')";
                    $response = $db->execute($sql2);
                    
                    $sql3 = "
                    SELECT
                    \"id_bulto\"
                    FROM
                    \"General\".\"casac_bultos\"
                    WHERE
                    \"clave_bulto\" = '" . $this->getClave_bulto() . "'";
                    
                    $response = $db->execute($sql3);
                    $id_bulto = $response[0]["id_bulto"];
                    
                    $sql4 = "INSERT INTO \"Previo\".\"cop_bultos\" (\"id_bult\",\"id_prev\",\"id_bulto\",\"cons_bulto\", \"cant_bult\", \"anc_bult\", \"lar_bult\", \"alt_bult\", \"obs_bult\")
                        VALUES (nextval('\"Previo\".\"cprevo_bultos_id_bult_seq\"'),
                      " . $ultimo . "," . $id_bulto . "," . $this->getCons_bulto() . "," . $this->getCant_bult() . "," . $this->getAnc_bult() . ", " . $this->getLar_bult() . "," . $this->getAlt_bult() . ",'" . $this->getObs_bult() . "')";
                    $response = $db->execute($sql4);
                    
                    $sql5 = "INSERT INTO \"Previo\".\"cop_orcom\" (\"id_orcom\",\"id_prev\",\"cons_orcom\",\"num_orcom\")
                        VALUES (nextval('\"Previo\".\"cprevo_orcom_id_orcom_seq\"'),
                      " . $ultimo . "," . $this->getCons_orcom() . ",'" . $this->getNum_orcom() . "')";
                    $response = $db->execute($sql5);
                }
                
                $InvoiceArray = $this->getInvoices();
                
                foreach ($InvoiceArray as $key => $value) {
                    if ($ultimo > 0) {
                        $sql = "INSERT INTO \"Previo\".\"cprevo_factur\" (\"id_factur\",\"id_prev\",\"cons_fact\", \"num_fact\") VALUES (nextval('\"Previo\".\"cprevo_factur_id_factur_seq\"'),
                         " . $ultimo . ", " . $value->cons_fact . ",'" . $value->num_fact . "')";
                        $response = $db->execute($sql);
                        
                        foreach ($response as $rs) {
                            $sql = "SELECT max(id_factur) FROM \"Previo\".\"cprevo_factur\"";
                            $response = $db->execute($sql);
                            $idfactur = $response[0]["max"];
                            
                            $InvoiceProducts = $value->products;
                            
                            foreach ($InvoiceProducts as $productos => $value) {
                                if ($ultimo > 0) {
                                    $sql = "INSERT INTO \"Previo\".\"cprevo_facpar\" (\"id_partida\",\"id_factur\", \"cons_part\", \"num_part\", \"desc_merc\", \"pai_orig\", \"uni_fact\", \"can_fact\",\"can_factr\",\"edo_corr\",\"obs_frac\",\"cve_usua\",\"inc_part\",\"uni_tari\",\"can_tari\")
                                     VALUES (nextval('\"Previo\".\"cprevo_facpar_id_partida_seq\"'),
                                 " . $idfactur . "," . $value->cons_part . ",'" . $value->num_part . "','" . $value->desc_merc . "','" . $value->pai_orig . "'," . $value->uni_fact . "," . $value->can_fact . "," . $value->can_factr . "," . $value->edo_corr . ",'" . $value->obs_frac . "','" . $value->cve_usua . "','" . $value->inc_part . "'," . $value->uni_tari . "," . $value->can_tari . ")";
                                    $response = $db->execute($sql);
                                }
                                ;
                                
                                $ProductsSeries = $value->series;
                                
                                foreach ($ProductsSeries as $series => $value) {
                                    
                                    $sql = "SELECT max(id_partida) FROM \"Previo\".\"cprevo_facpar\"";
                                    $response = $db->execute($sql);
                                    $id_partida = $response[0]["max"];
                                    
                                    if ($ultimo > 0) {
                                        $sql = "INSERT INTO \"Previo\".\"cprevo_series\" (\"id_series\",\"id_partida\", \"cons_seri\", \"num_part\", \"mar_merc\", \"sub_mode\", \"num_seri\")
                                     VALUES (nextval('\"Previo\".\"cprevo_series_id_series_seq\"'),
                                 " . $id_partida . "," . $value->cons_seri . ",'" . $value->num_part . "','" . $value->mar_merc . "','" . $value->sub_mode . "','" . $value->num_seri . "')";
                                        $response = $db->execute($sql);
                                    }
                                    ;
                                }
                            }
                        }
                        ;
                    }
                    ;
                }
            } else {
                $this->setSuccess(false);
                return "Licencia Inactiva";
            }
            
            $this->setSuccess(true);
            return "La operación es correcta";
        } catch (Exception $e) {
            $this->setSuccess(false);
            return $e->getMessage();
        }
    }

    /**
     *
     * @return the $idclient
     */
    public function getIdclient()
    {
        return $this->idclient;
    }

    /**
     *
     * @return the $aduana
     */
    public function getAduana()
    {
        return $this->aduana;
    }

    /**
     *
     * @return the $rfc_impo
     */
    public function getRfc_impo()
    {
        return $this->rfc_impo;
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
     * @return the $obs_prev
     */
    public function getObs_prev()
    {
        return $this->obs_prev;
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
     * @param number $idclient            
     */
    public function setIdclient($idclient)
    {
        $this->idclient = $idclient;
    }

    /**
     *
     * @param string $aduana            
     */
    public function setAduana($aduana)
    {
        $this->aduana = $aduana;
    }

    /**
     *
     * @param string $rfc_impo            
     */
    public function setRfc_impo($rfc_impo)
    {
        $this->rfc_impo = $rfc_impo;
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
     * @param string $fec_soli            
     */
    public function setFec_soli($fec_soli)
    {
        $this->fec_soli = $fec_soli;
    }

    /**
     *
     * @param number $fol_soli            
     */
    public function setFol_soli($fol_soli)
    {
        $this->fol_soli = $fol_soli;
    }

    /**
     *
     * @param number $tot_bult            
     */
    public function setTot_bult($tot_bult)
    {
        $this->tot_bult = $tot_bult;
    }

    /**
     *
     * @param number $tot_bultr            
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
     * @param number $edo_prev            
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
     * @param string $obs_prev            
     */
    public function setObs_prev($obs_prev)
    {
        $this->obs_prev = $obs_prev;
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
     * @param string $dep_asigna            
     */
    public function setDep_asigna($dep_asigna)
    {
        $this->dep_asigna = $dep_asigna;
    }

    /**
     *
     * @return the $clave_tipcon
     */
    public function getClave_tipcon()
    {
        return $this->clave_tipcon;
    }

    /**
     *
     * @return the $clave_bulto
     */
    public function getClave_bulto()
    {
        return $this->clave_bulto;
    }

    /**
     *
     * @param string $clave_tipcon            
     */
    public function setClave_tipcon($clave_tipcon)
    {
        $this->clave_tipcon = $clave_tipcon;
    }

    /**
     *
     * @param string $clave_bulto            
     */
    public function setClave_bulto($clave_bulto)
    {
        $this->clave_bulto = $clave_bulto;
    }

    /**
     *
     * @return the $numero_contenedor
     */
    public function getNumero_contenedor()
    {
        return $this->numero_contenedor;
    }

    /**
     *
     * @return the $numero_candado1
     */
    public function getNumero_candado1()
    {
        return $this->numero_candado1;
    }

    /**
     *
     * @return the $numero_candado2
     */
    public function getNumero_candado2()
    {
        return $this->numero_candado2;
    }

    /**
     *
     * @return the $numero_candado3
     */
    public function getNumero_candado3()
    {
        return $this->numero_candado3;
    }

    /**
     *
     * @return the $numero_candado4
     */
    public function getNumero_candado4()
    {
        return $this->numero_candado4;
    }

    /**
     *
     * @return the $numero_candado5
     */
    public function getNumero_candado5()
    {
        return $this->numero_candado5;
    }

    /**
     *
     * @return the $obs_cont
     */
    public function getObs_cont()
    {
        return $this->obs_cont;
    }

    /**
     *
     * @param string $numero_contenedor            
     */
    public function setNumero_contenedor($numero_contenedor)
    {
        $this->numero_contenedor = $numero_contenedor;
    }

    /**
     *
     * @param string $numero_candado1            
     */
    public function setNumero_candado1($numero_candado1)
    {
        $this->numero_candado1 = $numero_candado1;
    }

    /**
     *
     * @param string $numero_candado2            
     */
    public function setNumero_candado2($numero_candado2)
    {
        $this->numero_candado2 = $numero_candado2;
    }

    /**
     *
     * @param string $numero_candado3            
     */
    public function setNumero_candado3($numero_candado3)
    {
        $this->numero_candado3 = $numero_candado3;
    }

    /**
     *
     * @param string $numero_candado4            
     */
    public function setNumero_candado4($numero_candado4)
    {
        $this->numero_candado4 = $numero_candado4;
    }

    /**
     *
     * @param string $numero_candado5            
     */
    public function setNumero_candado5($numero_candado5)
    {
        $this->numero_candado5 = $numero_candado5;
    }

    /**
     *
     * @param string $obs_cont            
     */
    public function setObs_cont($obs_cont)
    {
        $this->obs_cont = $obs_cont;
    }

    /**
     *
     * @return the $cons_bulto
     */
    public function getCons_bulto()
    {
        return $this->cons_bulto;
    }

    /**
     *
     * @return the $cant_bult
     */
    public function getCant_bult()
    {
        return $this->cant_bult;
    }

    /**
     *
     * @return the $anc_bult
     */
    public function getAnc_bult()
    {
        return $this->anc_bult;
    }

    /**
     *
     * @return the $lar_bult
     */
    public function getLar_bult()
    {
        return $this->lar_bult;
    }

    /**
     *
     * @return the $alt_bult
     */
    public function getAlt_bult()
    {
        return $this->alt_bult;
    }

    /**
     *
     * @return the $obs_bult
     */
    public function getObs_bult()
    {
        return $this->obs_bult;
    }

    /**
     *
     * @param number $cons_bulto            
     */
    public function setCons_bulto($cons_bulto)
    {
        $this->cons_bulto = $cons_bulto;
    }

    /**
     *
     * @param number $cant_bult            
     */
    public function setCant_bult($cant_bult)
    {
        $this->cant_bult = $cant_bult;
    }

    /**
     *
     * @param number $anc_bult            
     */
    public function setAnc_bult($anc_bult)
    {
        $this->anc_bult = $anc_bult;
    }

    /**
     *
     * @param number $lar_bult            
     */
    public function setLar_bult($lar_bult)
    {
        $this->lar_bult = $lar_bult;
    }

    /**
     *
     * @param number $alt_bult            
     */
    public function setAlt_bult($alt_bult)
    {
        $this->alt_bult = $alt_bult;
    }

    /**
     *
     * @param string $obs_bult            
     */
    public function setObs_bult($obs_bult)
    {
        $this->obs_bult = $obs_bult;
    }

    /**
     *
     * @return the $cons_orcom
     */
    public function getCons_orcom()
    {
        return $this->cons_orcom;
    }

    /**
     *
     * @return the $num_orcom
     */
    public function getNum_orcom()
    {
        return $this->num_orcom;
    }

    /**
     *
     * @param number $cons_orcom            
     */
    public function setCons_orcom($cons_orcom)
    {
        $this->cons_orcom = $cons_orcom;
    }

    /**
     *
     * @param string $num_orcom            
     */
    public function setNum_orcom($num_orcom)
    {
        $this->num_orcom = $num_orcom;
    }

    /**
     *
     * @param boolean $success            
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }
}

?>