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
require_once 'DataDownPrevios.php';

class DownloadPrevious
{

    /**
     *
     * @var integer
     */
    public $idprevious;

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
     * DataDownPrevios Object
     *
     * @var \DataDownPrevios
     */
    private $dataDownPrevios;

    /**
     *
     * @param integer $idprevious                      
     * @param string $nom_movil            
     */
    public function __construct($idprevious, $nom_movil)
    {
        $this->setIdprevious($idprevious);
        $this->setNom_movil($nom_movil);
    }

    /**
     * @throws Exception
     * @return boolean
     */
    public function downDataPrevios()
    {
        if ($this->getIdprevious() == '' || $this->getNom_movil() == '') {
            $this->setSuccess(false);
            $this->setMessageText("Datos requeridos incompletos.");
            return false;
        } else {
           try {
                $db = new PgsqlConnection();
                $table = "'previo'.cprevo_previos CP";
                $joins = "INNER JOIN 'previo'.'cprevo_refe' CR ON CP.id_prev = CR.id_prev";
                $joins .= " LEFT JOIN 'general'.casac_importadores I ON CP.id_importador = I.id_importador";
                $fieldsArray = array(
                    "CR.id_prev",
                    "CR.num_refe",
                    "I.rfc_importador",
                    "CP.fec_soli",
                    "CP.fol_soli",
                    "CP.tot_bult",
                    "CP.tot_bultr",
                    "CP.rec_fisc",
                    "CP.num_guia",
                    "CP.edo_prev",
                    "CP.ins_prev",
                    "CP.dep_asigna",
                    "CP.obs_prev",
                    "CP.pes_brut"
                );
                $parameters = "CP.id_prev = '" . $this->getIdprevious() . "'";
                $generals = $db->query($table, $fieldsArray, $joins, $parameters);

                if ($generals) {
                    $generalArray = array();
                
                    foreach ($generals as $general) {
                        $idprev = $general['id_prev'];
                        $dataDown = new DataDownPrevios();
                        $dataDown->setNum_refe($general["num_refe"]);
                        $dataDown->setRfc_importador($general["rfc_importador"]);
                        $dataDown->setFec_soli($general["fec_soli"]);
                        $dataDown->setFol_soli($general["fol_soli"]);
                        $dataDown->setTot_bult($general["tot_bult"]);
                        $dataDown->setTot_bultr($general["tot_bultr"]);
                        $dataDown->setRec_fisc($general["rec_fisc"]);
                        $dataDown->setNum_guia($general["num_guia"]);
                        $dataDown->setIns_prev($general["ins_prev"]);
                        $dataDown->setEdo_prev($general["edo_prev"]);
                        $dataDown->setDep_asigna($general["dep_asigna"]);
                        $dataDown->setObs_prev($general["obs_prev"]);
                        $dataDown->setPes_brut($general["pes_brut"]);
                
                        $table = "'previo'.cop_conten C";
                        $joins = "INNER JOIN 'general'.casac_tipcon TC ON C.id_tipcon = TC.id_tipcon";
                        $fieldsArray = array(
                            "TC.clave_tipcon",
                            "C.numero_contenedor",
                            "C.numero_candado1",
                            "C.numero_candado2",
                            "C.numero_candado3",
                            "C.numero_candado4",
                            "C.numero_candado5",
                            "C.obs_cont"
                        );
                        $parameters = "C.id_prev = " . $this->getIdprevious() . "";
                        $containers = $db->query($table, $fieldsArray, $joins, $parameters);
                
                        $containerArray = array();
                
                        foreach ($containers as $container) {
                            $Contenedores = new Contenedores();
                            $Contenedores->setClave_tipcon($container["clave_tipcon"]);
                            $Contenedores->setNumero_contenedor($container["numero_contenedor"]);
                            $Contenedores->setNumero_candado1($container["numero_candado1"]);
                            $Contenedores->setNumero_candado2($container["numero_candado2"]);
                            $Contenedores->setNumero_candado3($container["numero_candado3"]);
                            $Contenedores->setNumero_candado4($container["numero_candado4"]);
                            $Contenedores->setNumero_candado5($container["numero_candado5"]);
                            $containerArray[] = $Contenedores;
                        }
                
                        $dataDown->setContenedores($containerArray);
                
                        $table = "'previo'.cop_bultos B";
                        $joins = "INNER JOIN 'general'.casac_bultos TB ON B.id_bulto = TB.id_bulto";
                        $fieldsArray = array(
                            "TB.clave_bulto",
                            "B.cons_bulto",
                            "B.cant_bult",
                            "B.anc_bult",
                            "B.lar_bult",
                            "B.alt_bult",
                            "B.obs_bult"
                        );
                        $parameters = "B.id_prev = " . $this->getIdprevious() . "";
                        $packages = $db->query($table, $fieldsArray, $joins, $parameters);
                
                        $packageArray = array();
                
                        foreach ($packages as $package) {
                            $Bultos = new Bultos();
                            $Bultos->setClave_bulto($package["clave_bulto"]);
                            $Bultos->setCons_bulto($package["cons_bulto"]);
                            $Bultos->setCant_bult($package["cant_bult"]);
                            $Bultos->setAnc_bult($package["anc_bult"]);
                            $Bultos->setLar_bult($package["lar_bult"]);
                            $Bultos->setAlt_bult($package["alt_bult"]);
                            $Bultos->setObs_bult($package["obs_bult"]);
                
                            $packageArray[] = $Bultos;
                        }
                
                        $dataDown->setBultos($packageArray);
                
                        $table = "'previo'.cop_orcom O";
                        $joins = "";
                        $fieldsArray = array(
                            "O.cons_orcom",
                            "O.num_orcom"
                        );
                        $parameters = "O.id_prev = " . $this->getIdprevious() . "";
                        $buyOrders = $db->query($table, $fieldsArray, $joins, $parameters);
                
                        $buyOrderArray = array();
                
                        foreach ($buyOrders as $buyOrder) {
                            $Ordcompras = new Ordcompras();
                            $Ordcompras->setCons_orcom($buyOrder["cons_orcom"]);
                            $Ordcompras->setNum_orcom($buyOrder["num_orcom"]);
                
                            $buyOrderArray[] = $Ordcompras;
                        }
                
                        $dataDown->setOrdcompras($buyOrderArray);
                
                        $table = "'previo'.'cprevo_factur'";
                        $joins = "";
                        $fieldsArray = array(
                            'id_factur',
                            'cons_fact',
                            'num_fact'
                        );
                        $parameters = "id_prev = '" . $this->getIdprevious() . "'";
                        $invoices = $db->query($table, $fieldsArray, $joins, $parameters);
                         
                        $invoicesArray = Array();
                
                        foreach ($invoices as $invoice) {
                            $Invoice = new Invoice();
                            $Invoice->setCons_fact($invoice["cons_fact"]);
                            $Invoice->setNum_fact($invoice["num_fact"]);
                
                            $idInvoice = $invoice["id_factur"];
                
                            $table = "'previo'.'cprevo_facpar'";
                            $joins = "";
                            $fieldsArray = array(
                                'id_partida',
                                'cons_part',
                                'num_part',
                                'desc_merc',
                                'pai_orig',
                                'uni_fact',
                                'can_fact',
                                'can_factr',
                                'edo_corr',
                                'obs_frac',
                                'cve_usua',
                                'inc_part',
                                'uni_tari',
                                'can_tari',
                                'pes_unit'
                            );
                            $parameters = "id_factur = '" . $idInvoice . "'";
                            $products = $db->query($table, $fieldsArray, $joins, $parameters);
                
                            $productsArray = Array();
                
                            foreach ($products as $product) {
                                $Products = new Products();
                                $Products->setCons_part($product["cons_part"]);
                                $Products->setNum_part($product["num_part"]);
                                $Products->setDesc_merc($product["desc_merc"]);
                                $Products->setPai_orig($product["pai_orig"]);
                                $Products->setUni_fact($product["uni_fact"]);
                                $Products->setCan_fact($product["can_fact"]);
                                $Products->setCan_factr($product["can_factr"]);
                                $Products->setEdo_corr($product["edo_corr"]);
                                $Products->setObs_frac($product["obs_frac"]);
                                $Products->setCve_usua($product["cve_usua"]);
                                $Products->setInc_part($product["inc_part"]);
                                $Products->setUni_tari($product["uni_tari"]);
                                $Products->setCan_tari($product["can_tari"]);
                                $Products->setPes_unit($product["pes_unit"]);
                
                                $idProduct = $product["id_partida"];
                
                                $table = "'previo'.'cprevo_series'";
                                $joins = "";
                                $fieldsArray = array(
                                    'cons_seri',
                                    'num_part',
                                    'mar_merc',
                                    'sub_mode',
                                    'num_seri'
                                );
                                $parameters = "id_partida = '" . $idProduct . "'";
                                $series = $db->query($table, $fieldsArray, $joins, $parameters);
                
                                $seriesArray = Array();
                
                                foreach ($series as $serie) {
                                    $Series = new Series($serie["cons_seri"], $serie["num_part"], $serie["mar_merc"], $serie["sub_mode"], $serie["num_seri"]);
                                    $seriesArray[] = $Series;
                                }
                
                                $Products->setSeries($seriesArray);
                
                                $productsArray[] = $Products;
                            }
                
                            $Invoice->setProducts($productsArray);
                            $invoicesArray[] = $Invoice;
                        }
                
                    $dataDown->setInvoices($invoicesArray);
                    $generalArray[] = $dataDown;
                        
                    $nom_movil = $this->getNom_movil();
            
                    $sql = "SELECT CURRENT_TIMESTAMP::time without time zone";
                    $hour = $db->execute($sql);
            
                    //$now = $hour[0]['now'];
                    //$now = substr($now, 0, - 7);
                    
                    //$now = mb_substr($now, 0, -7, 'utf8');
                    $table = "previo.cprevo_descar";
                    $valuesArray = Array(
                        "id_prev" => $this->getIdprevious(),
                        "fec_desca" => 'TODAY()',
                        "nom_movil" => $this->getNom_movil(),
                        //"hora_desca" => $now
                        );
                    $insertDescar = $db->insert($table, $valuesArray);

                
                    $this->setMessageText('Se ha enviado correctamente');
                    $this->setSuccess(true);
                    $this->setDataDownPrevios($generalArray);
                    return true;
                    }
                    
                } else {
                    throw new Exception("No existen datos para descargar");
                }
                    
            } catch (Exception $e) {
                $this->setSuccess(false);
                $this->setMessageText("Error: " . $e->getMessage());
                return false;
            }
        }
    }

    /**
     *
     * @return the $nom_movil
     */
    public function getNom_movil()
    {
        return $this->nom_movil;
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
     * @return the $messageText
     */
    public function getMessageText()
    {
        return $this->messageText;
    }



    /**
     *
     * @param string $nom_movil            
     */
    public function setNom_movil($nom_movil)
    {
        $this->nom_movil = $nom_movil;
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
     *
     * @param string $messageText            
     */
    public function setMessageText($messageText)
    {
        $this->messageText = $messageText;
    }


    /**
     * @return the $idprevious
     */
    public function getIdprevious()
    {
        return $this->idprevious;
    }

    /**
     * @param number $idprevious
     */
    public function setIdprevious($idprevious)
    {
        $this->idprevious = $idprevious;
    }
    /**
     * @return the $dataDownPrevios
     */
    public function getDataDownPrevios()
    {
        return $this->dataDownPrevios;
    }

    /**
     * @param DataDownPrevios $dataDownPrevios
     */
    public function setDataDownPrevios($dataDownPrevios)
    {
        $this->dataDownPrevios = $dataDownPrevios;
    }


    
}

?>
