<?php
require_once 'Previous.php';
require_once 'Bultos.php';
require_once 'Contenedores.php';
require_once 'File.php';
require_once 'Invoice.php';
require_once 'Ordcompras.php';
require_once 'CasaLibraries/CasaDb/ConnectionFactory.class.php';

class GetDataPrevious
{
    /**
     *
     * @var integer
     */
    public $idprevious;
    
    /**
     *
     * @var integer
     */
    public $idgdb;
    
    
    /**
     *
     * @var integer
     */
    public $addDownload;
    
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
     * @param integer $idprevious
     * @param integer $idgdb
     * @param integer $addDownload
     */
    public function __construct($idprevious, $idgdb, $addDownload)
    //public function __construct($idprevious, $idgdb)
    {
        $this->setIdprevious($idprevious);
        $this->setIdgdb($idgdb);
        $this->setAddDownload($addDownload);
        
    }
    
    /**
     *
     * @param integer $idprevious
     * @param integer $idgdb
     * @param integer $addDownload
     */
    public function getDataPrevious()
    {
        if ($this->getIdprevious() == '' || $this->getIdgdb() == '') {
            $this->setSuccess(false);
            $this->setMessageText("Datos requeridos incompletos.");
            return false;
        } else {
            //$fraccArray = Array();
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
            
            $dbAdoP = ConnectionFactory::Connectpostgres();
            
            $dbAdoP->beginTrans();
            
            try {
                $db = new PgsqlQueries();
                $db->setTable('"previo".cprevo_previos P');
                $db->setJoins("INNER JOIN 'general'.casac_importadores I ON P.id_importador = I.id_importador");
                $db->setJoin("INNER JOIN 'previo'.cprevo_refe R ON P.id_prev = R.id_prev");
                $db->setJoin("INNER JOIN 'general'.casag_licencias L ON R.id_licencia = L.id_licencia");
                $db->setJoin("INNER JOIN 'general'.casac_aduanas A ON L.id_aduana = A.id_aduana");
                $db->setFields(array(
                    'L.id_cliente',
                    'R.num_refe',
                    'I.rfc_importador',
                    'P.fec_soli',
                    'P.fol_soli',
                    'P.tot_bult',
                    'P.tot_bultr',
                    'P.rec_fisc',
                    'P.num_guia',
                    'P.edo_prev',
                    'P.ins_prev',
                    'P.dep_asigna',
                    'P.obs_prev',
                    'P.pes_brut',
                    'P.hora_inicio',
                    'P.hora_fin',
                    'P.tip_prev',
                    'P.tip_merc',
                    'P.rev_auto',
                    'A.clave_aduana',
                    'L.patente',
                    'tip_refe',
                    'tip_ope',
                    'I.clave_importador',
                    'I.id_importador',
                ));
                
                $db->setParameters("P.id_prev = '" . $this->getIdprevious() . "'");
                $db->setReturnType(PgsqlQueries::TYPE_OBJECT_ONE);
                $previousGeneralData = $db->query(); 

                if($previousGeneralData >0) {

                    $hora_inicio = $previousGeneralData->hora_inicio;
                    $hora_fin    = $previousGeneralData->hora_fin;
                    
                    if($hora_inicio != null && $hora_fin != null) {
                        try {
                            $db->setTable('"previo".cop_bultos PB');
                            $db->setJoins("INNER JOIN 'general'.casac_bultos GB ON PB.id_bulto = GB.id_bulto");
                            $db->setFields(array(
                                'GB.clave_bulto',
                                'PB.cons_bulto',
                                'PB.cant_bult',
                                'PB.anc_bult',
                                'PB.lar_bult',
                                'PB.alt_bult',
                                'PB.obs_bult',
                            ));
                            $db->setParameters("id_prev = '" . $this->getIdprevious() . "'");
                            $db->setReturnType(PgsqlQueries::TYPE_OBJECT_ALL);
                            $packages = $db->query();
                            
                            $db->setTable('"previo".cop_orcom PB');
                            $db->setJoins("");
                            $db->setFields(array(
                                'cons_orcom',
                                'num_orcom',
                            ));
                            $db->setParameters("id_prev = " . $this->getIdprevious());
                            $db->setReturnType(PgsqlQueries::TYPE_OBJECT_ALL);
                            $orders = $db->query();
                            
                            $orderArray = Array();
                            if(count($orders) > 0){
                                foreach ($orders as $order) {
                                    $orderObj = new Ordcompras();
                                    $orderObj->setCons_orcom($order->cons_orcom);
                                    $orderObj->setNum_orcom($order->num_orcom);
                                    
                                    $orderArray [] = $orderObj;
                                }
                            }
                            
                            $packageArray = Array();
                            if(count($packages) > 0) {
                                foreach ($packages as $package) {
                                    $packageObj = new Bultos();
                                    $packageObj->setClave_bulto($package->clave_bulto);
                                    $packageObj->setCons_bulto($package->cons_bulto);
                                    $packageObj->setCant_bult($package->cant_bult);
                                    $packageObj->setAnc_bult($package->anc_bult);
                                    $packageObj->setLar_bult($package->lar_bult);
                                    $packageObj->setAlt_bult($package->alt_bult);
                                    $packageObj->setObs_bult($package->obs_bult);
                                    
                                    $packageArray [] = $packageObj;
                                }
                            }
                            
                            $db->setTable('"previo".cop_conten CP');
                            $db->setJoins("INNER JOIN 'general'.casac_tipcon T ON CP.id_tipcon = T.id_tipcon");
                            $db->setFields(array(
                                'T.clave_tipcon',
                                'CP.numero_contenedor',
                                'CP.numero_candado1',
                                'CP.numero_candado2',
                                'CP.numero_candado3',
                                'CP.numero_candado4',
                                'CP.numero_candado5',
                                'CP.obs_cont'
                            ));
                            $db->setParameters("id_prev = '" . $this->getIdprevious() . "'");
                            $containers = $db->query();
                            
                            $containersArray = Array();
                            if(count($containers) > 0) {
                                foreach ($containers as $container) {
                                    $containerObj = new Contenedores();
                                    
                                    $containerObj->setClave_tipcon($container->clave_tipcon);
                                    $containerObj->setNumero_contenedor($container->numero_contenedor);
                                    $containerObj->setNumero_candado1($container->numero_candado1);
                                    $containerObj->setNumero_candado2($container->numero_candado2);
                                    $containerObj->setNumero_candado3($container->numero_candado3);
                                    $containerObj->setNumero_candado4($container->numero_candado4);
                                    $containerObj->setNumero_candado5($container->numero_candado5);
                                    $containerObj->setObs_cont($container->obs_cont);
                                    
                                    $containersArray [] = $containerObj;
                                }
                            }
                            
                            $db->setTable('"previo".cprevo_fotop');
                            $db->setJoins("");
                            $db->setFields(array(
                                'cons_foto',
                                'nom_foto',
                                'url_foto'
                            ));
                            $db->setParameters("id_prev = '" . $this->getIdprevious() . "'");
                            $generalFiles = $db->query();
                            
                            $generalFilesArray = Array();
                            if(count($generalFiles) > 0) {
                                $newUrl = str_replace("/".$generalFiles[0]->nom_foto,"",$generalFiles[0]->url_foto);

                                $numero_aleatorio = mt_rand(0,5000000);
                                symlink('/var/aMLn2nKpaiNb1IvGl8skjicOYRmgITH2'.$newUrl, '/var/www/html/TemporaryFiles/'.$this->getIdprevious(). $numero_aleatorio );

                                foreach ($generalFiles as $generalFile) {
                                    if ($generalFile->url_foto == null) {

                                        $generalFilesObj = new File($previousGeneralData->id_cliente, $previousGeneralData->num_refe, $generalFile->cons_foto, $generalFile->nom_foto, null, $previousGeneralData->clave_aduana, $previousGeneralData->patente, null, null);
                                        //$generalFilesObj->setFileString($generalFilesObj->extractFile());
                                        $url = $protocolo . $host .$generalFile->url_foto;
                                        $generalFilesObj->setUrl_foto($protocolo.$host."/TemporaryFiles/".$this->getIdprevious(). $numero_aleatorio."/".$generalFile->nom_foto);
                                        $generalFilesArray [] = $generalFilesObj;
                                    } else {
                                        $id_partida = null;
                                        $generalFilesObj = new File($previousGeneralData->id_cliente, $previousGeneralData->num_refe, $generalFile->cons_foto, $generalFile->nom_foto, null, $previousGeneralData->clave_aduana, $previousGeneralData->patente, $id_partida, $generalFile->url_foto);
                                        //$generalFilesObj->setFileString($generalFilesObj->extractFileUrlPart());
                                        $url = $protocolo . $host .$generalFile->url_foto;
                                        $generalFilesObj->setUrl_foto($protocolo.$host."/TemporaryFiles/".$this->getIdprevious(). $numero_aleatorio."/".$generalFile->nom_foto);
                                        $generalFilesArray [] = $generalFilesObj;
                                    }
                                }
                                
                            }
                            
                            $db->setTable('"previo".cprevo_factur f');
                            $db->setJoins("LEFT JOIN general.casac_proveedores p ON f.id_proveedor = p.id_proveedor");
                            $db->setFields(array(
                                'id_factur',
                                'cons_fact',
                                'num_fact',
                                'cve_prov',
                                'p.id_proveedor'
                            ));
                            $db->setParameters("id_prev = " . $this->getIdprevious());
                            $invoices = $db->query();

                            $invoicesArray = Array();

                            if(count($invoices) > 0) {
                                foreach ($invoices as $invoice) {
                                    $invoicesObj = new Invoice();
                                    $invoicesObj->setCons_fact($invoice->cons_fact);
                                    $invoicesObj->setNum_fact($invoice->num_fact);

                                    if($invoice->cve_prov == null){
                                        $invoicesObj->setCve_pro('S/A');
                                    }else {
                                        $invoicesObj->setCve_pro($invoice->cve_prov);
                                    }
                                    
                                    if($invoice->fac_extra == null){
                                        $invoicesObj->setFac_extra(0);
                                    } else {
                                        $productObj->setFac_extra($invoice->fac_extra);
                                    }
                                    $idInvoice = $invoice->id_factur;

                                    $db->setTable('"previo".cprevo_facpar fp');
                                    $db->setJoins("");
                                    $db->setFields(array(
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
                                        'estatus_part',
                                        'part_extra',
                                        'cve_nico',
                                        'num_fracc',
                                        'tip_pes'
                                    ));

                                   //$db->setParameters(" id_partida = 742162 AND id_factur = " . $idInvoice);
                                    $db->setParameters("id_factur = " . $idInvoice);
                                    $products = $db->query();

                                    $productsArray = Array ();
                                    if(count($products) > 0) {
                                        foreach ($products as $product) {
                                            $num_part = trim($product->num_part);
                                            $desc_merc = trim($product->desc_merc);
                                            $productObj = new Products();
                                            $productObj->setCons_part($product->cons_part);
                                            $productObj->setNum_part($num_part);
                                            $productObj->setDesc_merc(trim($product->desc_merc));
                                            $productObj->setPai_orig($product->pai_orig);
                                            $productObj->setUni_fact($product->uni_fact);
                                            $productObj->setCan_fact($product->can_fact);
                                            $productObj->setCan_factr($product->can_factr);
                                            $productObj->setEdo_corr($product->edo_corr);
                                            $productObj->setObs_frac($product->obs_frac);
                                            $productObj->setCve_usua($product->cve_usua);
                                            $productObj->setInc_part($product->inc_part);
                                            $productObj->setUni_tari($product->uni_tari);
                                            $productObj->setCan_tari($product->can_tari);
                                            $productObj->setPes_unit($product->pes_unit);
                                         

                                            $idProduct = $product->id_partida;
                                            
                                            if($product->part_extra == null){
                                                $productObj->setPart_extra(0);
                                            } else {
                                                $productObj->setPart_extra($product->part_extra);
                                            }
                                            
                                            if($product->num_fracc == null){
                                                $productObj->setNum_fracc('00000000');
                                            } else {
                                                $productObj->setNum_fracc(trim($product->num_fracc));
                                            }

                                            if($product->cve_nico == null){
                                                $productObj->setCve_nico('00');
                                            } else {
                                                $productObj->setCve_nico(trim($product->cve_nico));
                                            }


                                            if($product->tip_pes == null){
                                                $productObj->setTip_pes(1);
                                            } else {
                                                $productObj->setTip_pes($product->tip_pes);
                                            }
                                  
                                            $id_proveedor = $invoice->id_proveedor;
                                            if($id_proveedor == null){
                                                $id_proveedor = 1;
                                            } 

                                            $tip_ope  = $previousGeneralData->tip_ope;
                                   
                                            if($tip_ope == NULL) {
                                                $tip_ope = 0;
                                            }
                                           
                                            $id_proveedor = $invoice->id_proveedor;
                                            if($id_proveedor == null){
                                                $id_proveedor = 1;
                                            } 

                                            $num_fracc = $product->num_fracc ;
                                            if($num_fracc == null){
                                                $num_fracc = '00000000';
                                            } 
                                            $num_part = trim($product->num_part);
                                           
                                            $db->setTable('"previo".ctrac_fracpar');
                                            $db->setJoins("");
                                            $db->setFields(array(
                                                'num_partcove',
                                                'val_part',
                                                'id_fracpar'
                                            ));
                                            $db->setParameters("num_part = '" . $num_part. "' AND desc_merc = '"   .  $desc_merc . "' AND num_fracc = '" . $num_fracc   . "' AND id_cliente = " . $previousGeneralData->id_cliente . " AND id_importador = " . $previousGeneralData->id_importador . " AND id_proveedor = " . $id_proveedor  . " AND tip_ope = " . $tip_ope);
                                            $fracpar =  $db->query();
                                    

                                            if(count($fracpar) > 0) {
                                                $productObj->setVal_part($fracpar[0]->val_part);
                                                $productObj->setNum_partcove($fracpar[0]->num_partcove);
  
                                                $productObj->setId_fracpar($fracpar[0]->id_fracpar);

                                            }

                                            $db->setTable('"previo".cprevo_fotos');
                                            $db->setJoins("");
                                            $db->setFields(array(
                                                'cons_foto',
                                                'nom_foto',
                                                'url_foto'
                                            ));
                                            $db->setParameters("id_partida = '" . $idProduct . "'");
                                            $files = $db->query();
                                            
                                            $db->setTable('"previo".cprevo_series');
                                            $db->setJoins("");
                                            $db->setFields(array(
                                                'cons_seri',
                                                'num_part',
                                                'mar_merc',
                                                'sub_mode',
                                                'num_seri',
                                            ));
                                            $db->setParameters("id_partida = '" . $idProduct . "'");
                                            $series= $db->query();
                                            
                                            $filesArray = Array();
                                            if(count($files) > 0) {
                                                $numero_aleatorio = mt_rand(0,5000000);
                                                symlink('/var/aMLn2nKpaiNb1IvGl8skjicOYRmgITH2/'.$files[0]->url_foto, '/var/www/html/TemporaryFiles/'.$idProduct.$numero_aleatorio);

                                                foreach ($files as $file) {
                                                    if ($file->url_foto != null) {

                                                        $filesObj = new File($previousGeneralData->id_cliente, $previousGeneralData->num_refe, $file->cons_foto, $file->nom_foto, null, $previousGeneralData->clave_aduana, $previousGeneralData->patente, $idProduct, $file->url_foto, $previousGeneralData->num_refe);
                                                        //$filesObj->setFileString($filesObj->extractFileUrlPart3());
                                                        $url = $protocolo . $host .$file->url_foto .  $file->nom_foto;
                                                        $filesObj->setUrl_foto($protocolo.$host."/TemporaryFiles/".$idProduct.$numero_aleatorio."/".$file->nom_foto);
                                                        $filesArray [] = $filesObj;
                                                       // var_dump($url);
                                                       // exit();
                                                    } else {
                                                        $filesObj = new File($previousGeneralData->id_cliente, $previousGeneralData->num_refe, $file->cons_foto, $file->nom_foto, null, $previousGeneralData->clave_aduana, $previousGeneralData->patente, $idProduct, null, $previousGeneralData->num_refe);
                                                        //$filesObj->setFileString($filesObj->extractFile());
                                                        $url = $protocolo . $host .$file->url_foto .  $file->nom_foto;
                                                        $filesObj->setUrl_foto($protocolo.$host."/TemporaryFiles/".$idProduct.$numero_aleatorio."/".$file->nom_foto);
                                                        $filesArray [] = $filesObj;
                                                    }
                                                }
                                                
                                            }
                                            $seriesArray = Array();
                                            if(count($series) > 0) {
                                                foreach ($series as $serie) {
                                                    $seriesObj = new Series($serie->cons_seri, $serie->num_part, $serie->mar_merc, $serie->sub_mode, $serie->num_seri);
                                                    
                                                    $seriesArray [] = $seriesObj;
                                                }
                                            }
                                            $productObj->setSeries($seriesArray);
                                            $productObj->setFiles($filesArray);
                                            $productsArray [] = $productObj;
                                            
                                        }

                                        $invoicesObj->setProducts($productsArray);
                                        $invoicesArray [] = $invoicesObj;
                                    }
                                }
                            }
                            $tip_refe = $previousGeneralData->tip_refe;
                            
                            if($tip_refe == null || $tip_refe == ''){
                            $tip_refe = 1;
                            }
                            

                            $previous = new Previous(
                                $previousGeneralData->num_refe,
                                $previousGeneralData->rfc_importador,
                                $previousGeneralData->fec_soli,
                                $previousGeneralData->fol_soli,
                                $previousGeneralData->tot_bult,
                                $previousGeneralData->tot_bultr,
                                $previousGeneralData->rec_fisc,
                                $previousGeneralData->num_guia,
                                $previousGeneralData->edo_prev,
                                $previousGeneralData->ins_prev,
                                $previousGeneralData->dep_asigna,
                                $previousGeneralData->obs_prev,
                                $previousGeneralData->pes_brut,
                                $previousGeneralData->hora_inicio,
                                $previousGeneralData->hora_fin,
                                $previousGeneralData->tip_prev,
                                $previousGeneralData->tip_merc,
                                $previousGeneralData->rev_auto,
                                $previousGeneralData->clave_aduana,
                                $previousGeneralData->patente,
                                $tip_refe,
                                $previousGeneralData->clave_importador,
                                $previousGeneralData->tip_ope,
                                $containersArray,
                                $packageArray,
                                $orderArray,
                                $generalFilesArray,
                                $invoicesArray
                                );
                     

                            try {
                                $idPrevio = $this->getIdprevious();
                                $idGdb = $this->getIdgdb();

                                $addDownload = $this->getAddDownload();
                                
                                if($addDownload == null || $addDownload == NULL ){
                                
                                    $db->setTable('previo.cprevo_descar');
                                    $db->setFields(array(
                                    'id_descarga'
                                    ));
                                    $db->setParameters("id_prev = " . $idPrevio . " AND id_gdb = '" . $idGdb . "'");
                                    $response = $db->query();
                                    
                                    $idDescT = $response[0]->id_descarga;
                                    if ($idDescT > 0) {
                                    
                                    } else {
                                    $sqlNewDownload1="SELECT setval ( 'previo.cprevo_descar_id_descarga_seq' , ( SELECT  MAX (id_descarga) FROM previo.cprevo_descar) + 1 )";
                                    $saveDownload2 = $dbAdoP->Execute ( $sqlNewDownload1 );
                                    
                                    $sqlNewDownload= "INSERT INTO previo.cprevo_descar(id_prev, fec_desca, id_gdb)"
                                    . " VALUES($idPrevio, 'TODAY()', $idGdb) RETURNING id_descarga";
                                    $saveDownload = $dbAdoP->Execute ( $sqlNewDownload );
                                    $dbAdoP->commitTrans();
                                    }
                                
                                } else {
                                
                                    if($addDownload == 1){
                                    } else {
                                        $db->setTable('previo.cprevo_descar');
                                        $db->setFields(array(
                                        'id_descarga'
                                        ));
                                        $db->setParameters("id_prev = " . $idPrevio . " AND id_gdb = '" . $idGdb . "'");
                                        $response = $db->query();
                                        $idDescT = $response[0]->id_descarga;
                                        
                                        if ($idDescT >0) {
                                        
                                        } else {
                                            $sqlNewDownload1="SELECT setval ( 'previo.cprevo_descar_id_descarga_seq' , ( SELECT  MAX (id_descarga) FROM previo.cprevo_descar) + 1 )";
                                            $saveDownload2 = $dbAdoP->Execute ( $sqlNewDownload1 );
                                            
                                            $sqlNewDownload= "INSERT INTO previo.cprevo_descar(id_prev, fec_desca, id_gdb)"
                                            . " VALUES($idPrevio, 'TODAY()', $idGdb) RETURNING id_descarga";
                                            $saveDownload = $dbAdoP->Execute ( $sqlNewDownload );
                                            $dbAdoP->commitTrans();
                                        }
                                    }
                                }
                                     
                            } catch (Exception $ex) {
                                $dbAdoP->rollbackTrans();
                                $this->setSuccess(false);
                                $this->setMessageText( "Ocurrió un error al guardar la descarga de la referencia: " . $previousGeneralData->num_refe .  " - Error:" .$ex->getMessage());
                                return false;
                            }
                            
                            $this->setSuccess(true);
                            $this->setMessageText("La operación es correcta");
                         
                            
                            return $previous;
                            
                        } catch (Exception $e) {
                            $dbAdoP->rollbackTrans();
                            $this->setSuccess(false);
                            $this->setMessageText("Ocurrió un error al descargar la referencia: " . $idPrevio ."Error: " . $e->getMessage());
                            return false;
                        }
                    }else if($hora_inicio == null && $hora_fin != null) {
                        $dbAdoP->rollbackTrans();
                        $this->setSuccess(false);
                        $this->setMessageText("La fecha inicio está vacía y no se permite descargar el previo.");
                        return false;
                        
                    }else if($hora_inicio != null && $hora_fin == null){
                        $dbAdoP->rollbackTrans();
                        $this->setSuccess(false);
                        $this->setMessageText("La fecha fin está vacía y no se permite descargar el previo.");
                        return false;
                        
                    }else {
                        $dbAdoP->rollbackTrans();
                        $this->setSuccess(false);
                        $this->setMessageText("La fecha inicio y la fecha fin estan vacías y no se permite descargar el previo.");
                        return false;
                    }
                } else {
                    $dbAdoP->rollbackTrans();
                    $this->setSuccess(false);
                    $this->setMessageText("No se encontró el previo");
                    return false;
                    
                }
            } catch (Exception $ex) {
                $dbAdoP->rollbackTrans();
                $this->setSuccess(false);
                $this->setMessageText("Ocurrió un error al descargar la referencia." + "Error: " . $e->getMessage());
                return "Ocurrió un error al descargar la referencia." + "Error: " . $e->getMessage();
            }
        }
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
     * @param multitype: $arrayPrevious
     */
    public function setArrayPrevious($arrayPrevious)
    {
        $this->arrayPrevious = $arrayPrevious;
    }
    
    /**
     * @return the $idgdb
     */
    public function getIdgdb()
    {
        return $this->idgdb;
    }
    
    /**
     * @param number $idgdb
     */
    public function setIdgdb($idgdb)
    {
        $this->idgdb = $idgdb;
    }
    
    /**
     * @return the $addDownload
     */
     public function getAddDownload()
     {
     return $this->addDownload;
     }
     
     /**
     * @param number $addDownload
     */
     public function setAddDownload($addDownload)
     {
     $this->addDownload = $addDownload;
     }
    
    
}

?>
