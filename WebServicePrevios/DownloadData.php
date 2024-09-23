<?php



/**
  * CasaLibraries AddPrevious
  * File DownloadData.php
  * DownloadData Class
  *
  * @category        CasaLibraries
  * @package            CasaLibraries_Previo
  * @copyright          Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
  * @author            SMV
  * @version            Previo 1.0.0
  */

require_once 'DataDown.php';
require_once 'Contenedores.php';
require_once 'Bultos.php';
require_once 'Ordcompras.php';
require_once 'File.php';
require_once 'Invoice.php';



if ($_SERVER['HTTP_HOST'] == "localhost") {
    $protocolo = "http://";
} else {
    $protocolo = "https://";
}

class DownloadData
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
    public $rfc_importador;

    /**
     *
     * @var string
     */
    public $aduana;

    /**
     *
     * @var string
     */
    public $num_refe;

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
     * DataDown Object
     *
     * @var \DataDown
     */
    private $dataDown;

    /**
     *
     * @param integer $idclient
     * @param string $rfc_importador
     * @param string $aduana
     * @param string $num_refe
     */
    public function __construct($idclient, $rfc_importador, $aduana, $num_refe)
    {
        $this->setIdclient($idclient);
        $this->setRfc_importador($rfc_importador);
        $this->setAduana($aduana);
        $this->setNum_refe($num_refe);
    }

    /**
     *
     * @param integer $idclient
     * @param string $rfc_importador
     * @param string $aduana
     * @param string $num_refe
     * @throws Exception
     * @return string
     */
    public function downDataFoto()
    {
        // $host = $_SERVER["HTTP_HOST"];

        // if ($_SERVER['HTTP_HOST'] == "localhost") {
        //     $protocolo = "http://";
        // } else {
        //     $protocolo = "https://";
        // }



        $host = $_SERVER["HTTP_HOST"];
        //$host = "10.40.65.25";
        //$host = "eprevios.sistemascasa.com:4443/";

        if($host == "localhost"){
            $host = "10.40.65.25";
        }
        
        if ($host == "10.40.65.25") {
            $protocolo = "http://";
        } else {
            $protocolo = "https://";
        }
   
        // validamos Cliente
        if ($this->getIdclient() == '' || $this->getRfc_importador() == '' || $this->getAduana() == '' || $this->getNum_refe() == '') {
            $this->setSuccess(false);
            $this->setMessageText("Datos requeridos incompletos.");
            return false;
        } else {
            try {
                $db = new PgsqlConnection();

                $table = "'general'.casac_aduanas";
                $joins = "";
                $fieldsArray = array(
                    "id_aduana"
                );
                $parameters = "clave_aduana = '". $this->getAduana()."'";
                $response = $db->query($table, $fieldsArray, $joins, $parameters);
                $id_aduana = $response[0]["id_aduana"];

                if ($id_aduana > 0) {
                    $table = "'general'.casag_licencias";
                    $joins = "";
                    $fieldsArray = array(
                        "id_licencia",
                        'status_licencia'
                    );
                    $parameters = "id_cliente = " . $this->getIdclient() . " AND \"id_aduana\" = $id_aduana";
                    $response = $db->query($table, $fieldsArray, $joins, $parameters);
                    $id_licencia = $response[0]["id_licencia"];

                    if ($id_licencia > 0) {

                        if($response[0]["status_licencia"] == 0){
                            throw new Exception("La licencia del cliente se encuentra inactiva");
                        }
                        $table = "'previo'.cprevo_refe CR";
                        $joins = "INNER JOIN 'general'.casag_licencias L ON CR.id_licencia = L.id_licencia";
                        $fieldsArray = array(
                            "CR.id_prev",
                            "L.patente",
                            "CR.estatus_refe",
                            "tip_ope"
                        );
                        $parameters = "CR.num_refe = '" . $this->getNum_refe() . "' AND L.id_cliente =  " . $this->getIdclient() . "";
                        $response = $db->query($table, $fieldsArray, $joins, $parameters);

                        $idprev = $response[0]["id_prev"];
                        $patente = $response[0]["patente"];
                        $tip_ope = $response[0]["tip_ope"];
                        

                        if ($idprev > 0) {
                            
                            if($response[0]["estatus_refe"] != 3){
                                throw new Exception("El Previo está incompleto, estatus no terminado.");
                            }
                            
                            $table = "'previo'.cprevo_factur F";
                            $joins = "INNER JOIN 'previo'.cprevo_facpar P ON F.id_factur = P.id_factur";
                            $fieldsArray = array(
                                'F.id_factur',
                                'F.cons_fact',
                                'F.num_fact',
                                'P.cve_usua',
                                'P.estatus_part'

                            );
                            $parameters = "F.id_prev = $idprev order by id_partida";
                            $validation = $db->query($table, $fieldsArray, $joins, $parameters);

                                
                            if($validation) {
                                foreach ($validation as $part){
                                    if($part["cve_usua"] == null || $part["cve_usua"] == '' || $part["estatus_part"] == 0 || $part["estatus_part"] == null || $part["estatus_part"] == ''){
                                        throw new Exception("El Previo está incompleto.");
                                    }
                                }
                                

                                $table = "'previo'.'cprevo_previos'";
                                $joins = "";
                                $fieldsArray = array(
                                    "tot_bultr",
                                    "rec_fisc",
                                    "num_guia",
                                    "edo_prev",
                                    "dep_asigna",
                                    "obs_prev",
                                    "pes_brut",
                                    "hora_inicio",
                                    "hora_fin",
                                    "tip_prev",
                                    "tip_merc",
                                    "rev_auto",
                                    "id_importador"
                                );
                                $parameters = "id_prev = " . $idprev . "";
                                $generals = $db->query($table, $fieldsArray, $joins, $parameters);

                                if (!is_array($response)) {
                                    throw new Exception($response);
                                }

                                $generalArray = array();

                                foreach ($generals as $general) {
                                    $dataDown = new DataDown();
                                    $dataDown->setIdprevious($idprev);
                                    $dataDown->setTot_bultr($general["tot_bultr"]);
                                    $dataDown->setRec_fisc($general["rec_fisc"]);
                                    $dataDown->setNum_guia($general["num_guia"]);
                                    $dataDown->setEdo_prev($general["edo_prev"]);
                                    $dataDown->setDep_asigna($general["dep_asigna"]);
                                    $dataDown->setObs_prev($general["obs_prev"]);
                                    $dataDown->setPes_brut($general["pes_brut"]);
                                    $dataDown->setHora_fin($general["hora_fin"]);
                                    $dataDown->setHora_inicio($general["hora_inicio"]);
                                    $dataDown->setTip_prev($general["tip_prev"]);
                                    $dataDown->setTip_merc($general["tip_merc"]);
                                    $dataDown->setRev_auto($general["rev_auto"]);

                                    $table = "'previo'.'cop_conten' C";
                                    $joins = "INNER JOIN 'general'.'casac_tipcon' TC ON C.id_tipcon = TC.id_tipcon";
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
                                    $parameters = "C.id_prev = " . $idprev . "";
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
                                        $Contenedores->setObs_cont($container["obs_cont"]);
                                        $containerArray[] = $Contenedores;
                                    }

                                    $dataDown->setContenedores($containerArray);

                                    $table = "'previo'.'cop_bultos' B";
                                    $joins = "INNER JOIN 'general'.casac_bultos TB ON B.id_bulto = TB.id_bulto";
                                    $fieldsArray = array(
                                        "TB.'clave_bulto'",
                                        "B.'cons_bulto'",
                                        "B.'cant_bult'",
                                        "B.'anc_bult'",
                                        "B.'lar_bult'",
                                        "B.'alt_bult'",
                                        "B.'obs_bult'"
                                    );
                                    $parameters = "B.id_prev = " . $idprev . "";
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

                                        $packageArray [] = $Bultos;
                                    }

                                    $dataDown->setBultos($packageArray);

                                    $table = "'previo'.'cop_orcom' O";
                                    $joins = "";
                                    $fieldsArray = array(
                                        "O.'cons_orcom'",
                                        "O.'num_orcom'"
                                    );
                                    $parameters = "O.id_prev = " . $idprev . "";
                                    $buyOrders = $db->query($table, $fieldsArray, $joins, $parameters);

                                    $buyOrderArray = array();

                                    foreach ($buyOrders as $buyOrder) {
                                        $Ordcompras = new Ordcompras();
                                        $Ordcompras->setCons_orcom($buyOrder["cons_orcom"]);
                                        $Ordcompras->setNum_orcom($buyOrder["num_orcom"]);

                                        $buyOrderArray [] = $Ordcompras;
                                    }

                                    $dataDown->setOrdcompras($buyOrderArray);

                                    $table = "'previo'.'cprevo_fotop' F";
                                    $joins = "";
                                    $fieldsArray = array(
                                        "F.'cons_foto'",
                                        "F.'nom_foto'",
                                        "F.'url_foto'"
                                    );
                                    $parameters = "F.id_prev = " . $idprev . "";
                                    $generalFiles = $db->query($table, $fieldsArray, $joins, $parameters);

                                    $generalFilesArray = array();

                                    

                                    if(count($generalFiles) > 0) {

                                        $newUrl = str_replace("/".$generalFiles[0]["nom_foto"],"",$generalFiles[0]["url_foto"]);
                                        $numero_aleatorio = mt_rand(0,5000000);
                                        symlink('/var/aMLn2nKpaiNb1IvGl8skjicOYRmgITH2'.$newUrl, '/var/www/html/TemporaryFiles/'.$idprev. $numero_aleatorio );

                                        foreach ($generalFiles as $generalFile) {

                                            if($generalFile["url_foto"] == null){
                                                $generalFilesObj = new File($this->getIdclient(), $this->getNum_refe(),
                                                $generalFile["cons_foto"], $generalFile["nom_foto"], null, $this->getAduana(),
                                                $id_licencia[0]["patente"], null, null);
                                                $url = $protocolo . $host .$generalFile["url_foto"];
                                                $generalFilesObj->setUrl_foto($protocolo.$host."/TemporaryFiles/".$idprev. $numero_aleatorio."/".$generalFile["nom_foto"]);
                                                $generalFilesArray [] = $generalFilesObj;
                                            }else {
                                                
                                                $id_partida = null;
                                                $generalFilesObj = new File($this->getIdclient(), $this->getNum_refe(),
                                                $generalFile["cons_foto"], $generalFile["nom_foto"], null, $this->getAduana(),
                                                $id_licencia[0]["patente"], null, $generalFile["url_foto"]);
                                                //$generalFilesObj->setFileString($generalFilesObj->extractFileUrlPart());
                                                $url = $protocolo . $host .$generalFile["url_foto"];          
                                                $generalFilesObj->setUrl_foto($protocolo.$host."/TemporaryFiles/".$idprev. $numero_aleatorio."/".$generalFile["nom_foto"]);
                                                $generalFilesArray [] = $generalFilesObj;
                                            }
                                        }
                                    }

                                    $dataDown->setFiles($generalFilesArray);

                                    $table = "'previo'.'cprevo_factur' f";
                                    $joins = "LEFT JOIN general.casac_proveedores p ON f.id_proveedor = p.id_proveedor";

                                    $fieldsArray = array(
                                        'id_factur',
                                        'cons_fact',
                                        'num_fact',
                                        'fac_extra',
                                        'cve_prov',
                                        'p.id_proveedor'
                                    );
                                    $parameters = "id_prev = '" . $idprev . "'";
                                    $invoices = $db->query($table, $fieldsArray, $joins, $parameters);
                                    
                                    $invoicesArray = Array();
                      
                                    foreach ($invoices as $invoice) {
                                        $Invoice = new Invoice();
                                        $Invoice->setCons_fact($invoice["cons_fact"]);
                                        $Invoice->setNum_fact($invoice["num_fact"]);
                                        $Invoice->setCve_pro($invoice["cve_prov"]);

                                        if($invoice["fac_extra"] != 1){
                                            $Invoice->setFac_extra(0);
                                        } else {
                                            $Invoice->setFac_extra($invoice["fac_extra"]);
                                        }

                                        $idInvoice = $invoice["id_factur"];

                                        $table = "'previo'.'cprevo_facpar' fp ";
                                        $joins = "";
                                        $fieldsArray = array(
                                            'id_partida',
                                            'cons_part',
                                            'fp.num_part',
                                            'fp.desc_merc',
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
                                            'pes_unit',
                                            'ban_obs',
                                            'can_factr_obs',
                                            'tip_pes',
                                            'part_extra',
                                            'num_fracc',
                                            'cve_nico'
                                            
                                        );
                                        $parameters = "id_factur = '" . $idInvoice . "'";
                                        $products = $db->query($table, $fieldsArray, $joins, $parameters);

                                        $productsArray = Array ();
                                    
                                        foreach ($products as $product) {
                                            $Products = new Products();
                                            $can_factr_obs = $product['can_factr_obs'];
                                            $ban_obs = $product['ban_obs'];
                                            $cve_usua = $product['cve_usua'];
                                            $can_factr = $product['can_factr'];
                                            $inc_part = $product['inc_part'];

                                            //Aquí se concatena el valor de $inc_part con $can_factr_obs
                                            //Se forma cadena por primera vez, cuando no se ha actualizado las mercancías
                                            if($ban_obs == 1) {
                                                $incidentChainFinal = $inc_part.'        '.$can_factr_obs;
                                            } //Se forma cadena después de la primera vez, cuando ya alguien más ha actualizado las mercancías
                                            else {
                                            //Aquí se validó que el usuario no viniera vacío porque sino tendríamos un resultado como el siguiente: []:
                                                if($cve_usua != '' || $cve_usua != null){
                                                    $incidentChainFinal = '['.$cve_usua.']: '.$inc_part." ".'['. $cve_usua .']: '.$can_factr;
                                                } else {
                                                    $incidentChainFinal = $product['inc_part'];
                                                }

                                            }
                                            $numFracc = $product["num_fracc"];
                                            if($numFracc == null){
                                                $numFracc = '00000000'; 
                                            }
                                            
                                            $cveNico= $product["cve_nico"];
                                            if($cveNico == null){
                                                $cveNico = '00'; 
                                            }
                                            
                                            $num_part = trim($product["num_part"]);
                                            $desc_merc = trim($product["desc_merc"]);
                                            
                                            $Products->setCons_part($product["cons_part"]);
                                            $Products->setNum_part($num_part);
                                            $Products->setCan_fact($product["can_fact"]);
                                            $Products->setCan_factr($product["can_factr"]);
                                            $Products->setUni_tari($product["uni_tari"]);
                                            $Products->setDesc_merc($desc_merc);
                                            $Products->setPai_orig($product["pai_orig"]);
                                            $Products->setUni_fact($product["uni_fact"]);
                                            $Products->setCan_factr($product["can_factr"]);
                                            $Products->setEdo_corr($product["edo_corr"]);
                                            $Products->setObs_frac($product["obs_frac"]);
                                            $Products->setCve_usua($product["cve_usua"]);
                                            $Products->setInc_part($incidentChainFinal);
                                            $Products->setUni_tari($product["uni_tari"]);
                                            $Products->setCan_tari($product["can_tari"]);
                                            $Products->setPes_unit($product["pes_unit"]);
                                            $Products->setCve_nico($cveNico);
                                            $Products->setNum_fracc($numFracc);


                                            if($product["part_extra"] == null){
                                                $Products->setPart_extra(0); 

                                            } else {
                                                $Products->setPart_extra($product["part_extra"]); 
                                            }
                                                
                                            if($product["tip_pes"] == null){
                                                $Products->setTip_pes(1); 
                                            } else {
                                                $Products->setTip_pes($product["tip_pes"]); 
                                            }


                                            $table = "previo.ctrac_fracpar";
                                            $joins = "";
                                            $fieldsArray = array(
                                                'num_partcove',
                                                'val_part',
                                                'id_fracpar' 
                                            );
                                            $parameters = "num_part = '" . $num_part. "' AND desc_merc = '"   .  $desc_merc . "' AND num_fracc = '" . $numFracc   . "' AND id_cliente = " . $this->getIdclient() . " AND id_importador = " . $generals[0]["id_importador"] . " AND id_proveedor = " . $invoice["id_proveedor"] . " AND tip_ope = " . $tip_ope;
                                            $fracpar = $db->query($table, $fieldsArray, $joins, $parameters);
                                        
                                            if(count($fracpar) > 0) {
                                                $Products->setVal_part($fracpar[0]["val_part"]);
                                                $Products->setNum_partcove($fracpar[0]["num_partcove"]);
                                            }

                                         
                                            if($Products->getPart_extra() == 1){
                                                $Products->setId_fracpar($fracpar[0]["id_fracpar"]);
                                            }


                                            $idProduct = $product["id_partida"];

                                            $table = "'previo'.'cprevo_fotos'";
                                            $joins = "";
                                            $fieldsArray = array(
                                                'cons_foto',
                                                'nom_foto',
                                                'url_foto'
                                            );

                                            $parameters = "id_partida = '" . $idProduct . "'";

                                            $files = $db->query($table, $fieldsArray, $joins, $parameters);
                                            $filesArray = Array();

                                            if(count($files) > 0){

                                                $numero_aleatorio = mt_rand(0,5000000);
                                                symlink('/var/aMLn2nKpaiNb1IvGl8skjicOYRmgITH2/'.$files[0]['url_foto'], '/var/www/html/TemporaryFiles/'.$idProduct.$numero_aleatorio);
                                                
                                                foreach ($files as $generalFilePart) {

                                                    if($generalFilePart["url_foto"] == null){
                                                        $generalFilesObj = new File($this->getIdclient(), $this->getNum_refe(),
                                                        $generalFilePart["cons_foto"], $generalFilePart["nom_foto"], null, $this->getAduana(),
                                                        $id_licencia[0]["patente"], null, null);
                                                        $url = $protocolo . $host . $generalFilePart["url_foto"] . $generalFilePart["nom_foto"];
                                                        $generalFilesObj->setUrl_foto($protocolo.$host."/TemporaryFiles/".$idProduct.$numero_aleatorio."/".$generalFilePart['nom_foto']);
                                                        //$generalFilesObj->setFileString($generalFilesObj->extractFile());
                                                        $filesArray [] = $generalFilesObj;
                                                    }else {

                                                        $id_partida = null;
                                                        $generalFilesObjPart = new File($this->getIdclient(), $this->getNum_refe(),
                                                        $generalFilePart["cons_foto"], $generalFilePart["nom_foto"], null, $this->getAduana(),
                                                        $id_licencia[0]["patente"], $product["id_partida"], $generalFilePart["url_foto"]);
                                                        // $generalFilesObjPart->setFileString($generalFilesObjPart->extractFileUrlPart2());
                                                        $url = $protocolo . $host . $generalFilePart["url_foto"]. $generalFilePart["nom_foto"];
                                                        $generalFilesObjPart->setUrl_foto($protocolo.$host."/TemporaryFiles/".$idProduct.$numero_aleatorio."/".$generalFilePart['nom_foto']);
                                                        $filesArray [] = $generalFilesObjPart;

                                                    }

                                                }
                                                $Products->setFiles($filesArray);

                                            }

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
                                                $Series = new Series($serie["cons_seri"], $serie["num_part"],
                                                $serie["mar_merc"], $serie["sub_mode"], $serie["num_seri"]);
                                                $seriesArray [] = $Series;

                                            }
                                            $Products->setSeries($seriesArray);
                                            
                                            $productsArray [] = $Products;


                                        }

                                        $Invoice->setProducts($productsArray);
                                        $invoicesArray [] = $Invoice;

                                    }
                                    $dataDown->setInvoices($invoicesArray);
                                    $generalArray[] = $dataDown;
                                }



                            } else {
                                throw new Exception("El Previo está incompleto.");
                            }

                        } else {
                            $this->setMessageText("No existe la Referencia");
                        }

                        $this->setDataDown($generalArray);
                        $this->setSuccess(true);


                    } else {
                         throw new Exception("La licencia del cliente no está dada de alta");
                    }
                } else {
                     throw new Exception("La aduana no está dada de alta");
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
     * @return the $idclient
     */
    public function getIdclient()
    {
        return $this->idclient;
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
     * @return the $aduana
     */
    public function getAduana()
    {
        return $this->aduana;
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
     * @param number $idclient
     */
    public function setIdclient($idclient)
    {
        $this->idclient = $idclient;
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
     * @param string $aduana
     */
    public function setAduana($aduana)
    {
        $this->aduana = $aduana;
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
     *
     * @return the $dataDown
     */
    public function getDataDown()
    {
        return $this->dataDown;
    }

    /**
     *
     * @param DataDown $dataDown
     */
    public function setDataDown($dataDown)
    {
        $this->dataDown = $dataDown;
    }
}

?>
