<?php

/**
 * CasaLibraries LoadData
 * File LoadData.php
 * LoadData Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			SMV
 * @version    		Previo 1.0.0
 */
require_once 'Contenedores.php';
require_once 'Bultos.php';
require_once 'Ordcompras.php';
require_once 'CasaLibraries/WebServicePrevios/ProductDetails.php';
include 'CasaLibraries/Util/PHPMailer/class.phpmailer.php';

class LoadData
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
    public $ins_prev;

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
     * @var File[]
     */
    public $files;

    /**
     *
     * @var \Invoice[]
     */
    public $invoices;
    
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
     *
     * @var integer
     */
    private $nextIdPrevio;
    
    /**
     *
     * @var string
     */
    public $patente;

 /**
     * @return the $invoices
     */
    public function getInvoices()
    {
        return $this->invoices;
    }

 /**
     * @param Invoice[] $invoices
     */
    public function setInvoices($invoices)
    {
        $this->invoices = $invoices;
    }

    /**
     * @return the $patente
     */
    public function getPatente()
    {
        return $this->patente;
    }

 /**
     * @param string $patente
     */
    public function setPatente($patente)
    {
        $this->patente = $patente;
    }
 /**
     * 
     * @param integer $idclient
     * @param string $rfc_importador
     * @param string $aduana
     * @param string $num_refe
     * @param string $fec_soli
     * @param integer $fol_soli
     * @param integer $tot_bultr
     * @param string $rec_fisc
     * @param string $num_guia
     * @param string $edo_prev
     * @param string $dep_asigna
     * @param string $obs_prev
     * @param array $contenedores
     * @param array $bultos
     * @param array $ordcompras
     * @param array $files
     * @param array $invoices
     * @param integer $tot_bult
     * @param string $ins_prev
     */
    public function __construct($idclient, $rfc_importador, $aduana, $num_refe, $fec_soli, $fol_soli, $tot_bultr, $rec_fisc, $num_guia, $edo_prev, $dep_asigna, $obs_prev, $pes_brut, $hora_inicio, $hora_fin, $contenedores, $bultos, $ordcompras, $files, $invoices, $tot_bult, $ins_prev, $patente)
    {
        $this->setIdclient($idclient);
        $this->setRfc_importador($rfc_importador);
        $this->setAduana($aduana);
        $this->setNum_refe($num_refe);
        $this->setFec_soli($fec_soli);
        $this->setFol_soli($fol_soli);
        $this->setTot_bultr($tot_bultr);
        $this->setRec_fisc($rec_fisc);
        $this->setNum_guia($num_guia);
        $this->setEdo_prev($edo_prev);
        $this->setDep_asigna($dep_asigna);
        $this->setObs_prev($obs_prev);
        $this->setPes_brut($pes_brut);
        $this->setHora_inicio($hora_inicio);
        $this->setHora_fin($hora_fin);
        $this->setContenedores($contenedores);
        $this->setBultos($bultos);
        $this->setOrdcompras($ordcompras);
        $this->setFiles($files);
        $this->setInvoices($invoices);
        $this->setTot_bult($tot_bult);
        $this->setIns_prev($ins_prev);
        $this->setPatente($patente);
    }

    /*
     * This method load data the movil to web service
     */
    public function dataLoad()
    {
        $dbAdoP = ConnectionFactory::Connectpostgres();
        // validamos Cliente
        if ($this->getIdclient() == '' || $this->getRfc_importador() == '' || $this->getAduana() == '' || $this->getNum_refe() == '') {
            $this->setSuccess(false);
            $this->setMessageText("Datos requeridos incompletos.");
            return false;
        } else {
            $db = new PgsqlConnection();
            $db2 = new PgsqlQueries();
            $this->getPatente();
            try {
                $table = "'general'.casac_aduanas";
                $joins = "";
                $fieldsArray = array(
                    "id_aduana"
                );
                $parameters = "clave_aduana = '". $this->getAduana() . "'";
                $custome = $db->query($table, $fieldsArray, $joins, $parameters);
                $id_aduana = $custome[0]["id_aduana"];
                
                if ($id_aduana <= 0) {
                    throw new Exception("La aduana no está dada de alta");

                } 
                
                    
                $table = "'general'.casac_importadores";
                $joins = "";
                $fieldsArray = array(
                    "id_importador",
                    "nombre_importador",
                    "status_importador"
                );
                $parameters = "id_cliente = '" . $this->getIdclient() . "' AND rfc_importador = '" . $this->getRfc_importador() . "'";
                $importeters = $db->query($table, $fieldsArray, $joins, $parameters);
                $id_importador = $importeters[0]["id_importador"];

                $this->nombre_importador = $importeters[0]["nombre_importador"];
   
                if ($id_importador <= 0) {
                    throw new Exception("El importador no se encuentra dado de alta");
                }
                

                if(($this->getIdclient() == 3717) || ($this->getIdclient() == 3513) || ($this->getIdclient() == 3562) || ($this->getIdclient() == 114) || ($this->getIdclient() == 3414)){
                
                    $dbAdoP->beginTrans();  
                    
                    $table = "'previo'.cprevo_refe REFE";
                    $joins = "INNER JOIN 'previo'.cprevo_previos P ON REFE.id_prev = P.id_prev ";
                    $joins .= "INNER JOIN 'previo'.cprevo_factur F ON P.id_prev = F.id_prev ";
                    $joins .= "INNER JOIN 'previo'.cprevo_facpar FP ON FP.id_factur = F.id_factur ";
                    $joins .= "INNER JOIN 'general'.casag_licencias L ON REFE.id_licencia = L.id_licencia";
                    $fieldsArray = array(
                        "DISTINCT ON (cve_usua) cve_usua"
                    );
                    $parameters = "REFE.num_refe = '" . $this->getNum_refe() . "' AND L.id_cliente =  " . $this->getIdclient() . "";
                    $responsePart = $db->query($table, $fieldsArray, $joins, $parameters);
                    $countCveUsua = count($responsePart);
    
                    if($countCveUsua > 2){
                        throw new Exception("Error1: El previo contiene información con más de un usuario. No se permite enviar parcialidades a una referencia que tenga asignados distintos usuarios");
                    } else {
                        if($countCveUsua == 2){

                            $object = $this->getInvoices();
                            $array = json_decode(json_encode($object), true);
                            $cveUsuaFirstPart = $array[0]["products"][0]["cve_usua"];
                            
                            foreach($responsePart as $part){
                                $cve_usua = $part["cve_usua"];
                                $arrayAlmacenado[] = $cve_usua;
                                
                            }
 
                            foreach($array as $factura){
                                foreach($factura["products"] as $productos){
                                    $cve_usuario = $productos["cve_usua"];
                                    $arrayNuevo[] = $cve_usuario;
                                }
                            }

                            foreach($arrayAlmacenado as $nombre){
                                if ((!in_array($nombre, $arrayNuevo) && ($nombre != ""))) {
                                    
                                    throw new Exception("Error2: El previo contiene información con más de un usuario. No se permite enviar parcialidades a una referencia que tenga asignados distintos usuarios");
                                }
                            }

                            $photo = new File($this->getIdclient(), $this->getNum_refe(), $value->cons_foto, $value->nom_foto, $value->fileString, $this->getAduana());
                            $photo->deleteFile();
                            
                            $table = "'previo'.cprevo_refe CR";
                            $joins = "INNER JOIN 'general'.casag_licencias L ON CR.id_licencia = L.id_licencia";
                            $fieldsArray = array(
                                "CR.'id_prev'",
                                "L.id_licencia",
                                "L.patente"
                            );
                            $parameters = "CR.num_refe = '" . $this->getNum_refe() . "' AND L.id_cliente =  " . $this->getIdclient() . "";
                            $responseRefe = $db->query($table, $fieldsArray, $joins, $parameters);
                            $idprev = $responseRefe[0]["id_prev"];
                            $id_licencia = $responseRefe[0]["id_licencia"];
                            $patente = $responseRefe[0]["patente"];
                            
                            if(!$idprev){
                                throw new Exception("Error: La referencia no existe");
                            } else {
                                $table = "'previo'.cprevo_refe REFE";
                                $joins = "INNER JOIN 'previo'.cprevo_previos P ON REFE.id_prev = P.id_prev ";
                                $joins .= "INNER JOIN 'previo'.cprevo_factur F ON P.id_prev = F.id_prev ";
                                $joins .= "INNER JOIN 'previo'.cprevo_facpar FP ON FP.id_factur = F.id_factur ";
                                $joins .= "INNER JOIN 'general'.casag_licencias L ON REFE.id_licencia = L.id_licencia ";
                                $joins .= "INNER JOIN 'previo'.cprevo_fotos FOP ON FP.id_partida = FOP.id_partida";
                                
                                $fieldsArray = array(
                                    "FOP.id_partida"
                                );
                                $parameters = "REFE.num_refe = '" . $this->getNum_refe() . "' AND L.id_cliente =  " . $this->getIdclient() . "";
                                $responsePhotoParts = $db->query($table, $fieldsArray, $joins, $parameters);
                                
                                try {
                                    foreach ($responsePhotoParts as $part){
                                        $partId = $part["id_partida"];
                                        try {

                                            $sqlPhotosDelete = "DELETE FROM previo.cprevo_fotos WHERE id_partida = $partId";
                                            $photosDelete = $dbAdoP->Execute ( $sqlPhotosDelete );
                                        } catch (Exception $ex) {
                                            $dbAdoP->rollbackTrans();
                                            $this->setSuccess(false);
                                            return "Ocurrió un error al eliminar las fotos de las partidas.";
                                        }
                                        
                                    }
                                    
                                    $table = "'previo'.cprevo_refe REFE";
                                    $joins = "INNER JOIN 'previo'.cprevo_previos P ON REFE.id_prev = P.id_prev ";
                                    $joins .= "INNER JOIN 'general'.casag_licencias L ON REFE.id_licencia = L.id_licencia ";
                                    $joins .= "INNER JOIN 'previo'.cprevo_fotop FOP ON P.id_prev = FOP.id_prev";
                                    $fieldsArray = array(
                                        "FOP.id_fotop"
                                    );
                                    $parameters = "REFE.num_refe = '" . $this->getNum_refe() . "' AND L.id_cliente =  " . $this->getIdclient() . "";
                                    $responsePhotoPrevio = $db->query($table, $fieldsArray, $joins, $parameters);
                                    
                                    if(count($responsePhotoPrevio) > 0){

                                        try {

                                            $sqlPhotosDelete = "DELETE FROM previo.cprevo_fotop WHERE id_prev = $idprev";
                                            $photosDelete = $dbAdoP->Execute ( $sqlPhotosDelete );
                                        } catch (Exception $ex) {
                                            $dbAdoP->rollbackTrans();
                                            $this->setSuccess(false);
                                            $this->setMessageText("Ocurrió un error al eliminar las fotos de la referencia." . $ex->getMessage());
                                            return "Ocurrió un error al eliminar las fotos de la referencia.";
                                        }
            
                                    }
                                    
                                    try {

                                        $sqlPhotosDelete = "DELETE FROM previo.cprevo_refe WHERE id_prev = $idprev";
                                        $photosDelete = $dbAdoP->Execute ( $sqlPhotosDelete );
                                    } catch (Exception $ex) {
                                        $dbAdoP->rollbackTrans();
                                        $this->setSuccess(false);
                                        $this->setMessageText("Ocurrió un error al eliminar la referencia." .  $ex->getMessage());
                                        return "Ocurrió un error al eliminar la referencia.";
                                    }
                                    
                                   try {
                                        $num_refe = trim($this->getNum_refe());
                                        $licenseId = $responseRefe[0]["id_licencia"];
                                        $sqlNewReference = "INSERT INTO previo.cprevo_refe(id_licencia, num_refe)"
                                           . " VALUES($licenseId, '$num_refe') RETURNING id_prev";
                                        $saveReference = $dbAdoP->Execute ( $sqlNewReference );
                                        $id_prev = $saveReference->fields["id_prev"];

                                   } catch (Exception $ex) {
                                       $dbAdoP->rollbackTrans();
                                       $this->setSuccess(false);
                                       $this->setMessageText( "Ocurrió un error al guardar la referencia. " . $ex->getMessage());
                                       return "Ocurrió un error al guardar la referencia.";
                                   }
                                   
                                    try {
  
                                        $id_importador = $id_importador;
                                        $fec_soli= $this->getFec_soli();
                                        $fol_soli= $this->getFol_soli();
                                        $tot_bult= $this->getTot_bult();
                                        $tot_bultr= $this->getTot_bultr();
                                        $rec_fisc= $this->getRec_fisc();
                                        $num_guia= $this->getNum_guia();
                                        $edo_prev= $this->getEdo_prev();
                                        $ins_prev= $this->getIns_prev();
                                        $dep_asigna= $this->getDep_asigna();
                                        $obs_prev= $this->getObs_prev();
                                        $pes_brut= $this->getPes_brut();
                                        $hora_inicio= $this->getHora_inicio();
                                        $hora_fin = $this->getHora_fin();
                                        $flag_version = 2;

                                        $sqlNewPrev= "INSERT INTO previo.cprevo_previos(id_prev, id_importador, fec_soli, fol_soli, tot_bult, tot_bultr, rec_fisc,  num_guia,edo_prev,ins_prev, dep_asigna, obs_prev, pes_brut, hora_inicio, hora_fin, flag_version  )"
                                         . " VALUES($id_prev, $id_importador, '$fec_soli', '$fol_soli', $tot_bult, $tot_bultr, '$rec_fisc',  '$num_guia','$edo_prev','$ins_prev', '$dep_asigna', '$obs_prev', $pes_brut, '$hora_inicio', '$hora_fin', $flag_version)";

                                        $save_previo= $dbAdoP->Execute ( $sqlNewPrev );

                                    } catch (Exception $ex) {
                                        $dbAdoP->rollbackTrans();
                                        $this->setSuccess(false);
                                        $this->setMessageText("Ocurrió un error al guardar la información del previo." . $ex->getMessage());
                                        return "Ocurrió un error al guardar la información del previo." . $ex->getMessage();
                                    }
                        
                                    
                                    foreach ($this->getContenedores() as $key => $value) {
                                        try {
                                            $db->setTable('"general".casac_tipcon');
                                            $db->setFields(array(
                                                'id_tipcon'
                                            ));

                                            $db->setParameters("clave_tipcon = '" . $value->clave_tipcon . "'");
                                            $contentId = $db->query();

                                            $contentId = $contentId->id_tipcon;

                                            if(!$contentId){
                                                $contentId = 1;
                                            }

                                            $numero_contenedor= $value->numero_contenedor;
                                            $numero_candado1= $value->numero_candado1;
                                            $numero_candado2= $value->numero_candado2;
                                            $numero_candado3= $value->numero_candado3;
                                            $numero_candado4= $value->numero_candado4;
                                            $numero_candado5=  $value->numero_candado5;
                                            $obs_cont= $value->obs_cont;
                                            $sqlNewContent= "INSERT INTO previo.cop_conten(id_prev, id_tipcon, numero_contenedor,numero_candado1, numero_candado2, numero_candado3,numero_candado4, numero_candado5, obs_cont )"
                                                . " VALUES($id_prev, $contentId, '$numero_contenedor', '$numero_candado1', '$numero_candado2', '$numero_candado3','$numero_candado4', '$numero_candado5', '$obs_cont')";
                                            $save_content = $dbAdoP->Execute ( $sqlNewContent );


                                        } catch (Exception $ex) {
                                            $dbAdoP->rollbackTrans();
                                            $this->setSuccess(false);
                                            $this->setMessageText("Ocurrió un error al guardar la información de los contenedores" . $ex->getMessage());
                                            return "Ocurrió un error al guardar la información de los contenedores" . $ex->getMessage();
                                        }
                                    }
                                    
                                    
                                    
                                    foreach ($this->getBultos() as $key => $value) {
                                        if ($nextIdPrev> 0) {
                                            
                                            $db2->setTable('"general".casac_bultos');
                                            $db2->setFields(array(
                                                'id_bulto'
                                            ));
                                            $db2->setParameters("clave_bulto = '" . $value->clave_bulto . "' AND id_cliente = -1");
                                            $packageId = $db2->query();
                                            
                                            $packageId = $packageId->id_bulto;
                                            
                                            if(!$packageId){
                                                $packageId = 1;
                                            }
                                            try {

                                                $cons_bulto= $value->cons_bulto;
                                                $cant_bult= $value->cant_bult;
                                                $anc_bult= $value->anc_bult;
                                                $lar_bult= $value->lar_bult;
                                                $alt_bult= $value->alt_bult;
                                                $obs_bult= $value->obs_bult;
                                                $sqlNewbult= "INSERT INTO previo.cop_bultos(id_prev, id_bulto, cons_bulto,cant_bult, anc_bult, lar_bult,alt_bult, obs_bult)"
                                                    . " VALUES($id_prev, $packageId, $cons_bulto,$cant_bult, $anc_bult, $lar_bult,$alt_bult, $obs_bult)";
                                                $save_bult = $dbAdoP->Execute ( $sqlNewbult );

                                            } catch (Exception $ex) {
                                                $dbAdoP->rollbackTrans();
                                                $this->setSuccess(false);
                                                $this->setMessageText("Ocurrió un error al guardar la información de los bultos." . $ex->getMessage());
                                                return "Ocurrió un error al guardar la información de los bultos." . $ex->getMessage();

                                            }
                                        }
                                    }
                                    
                                    foreach ($this->getOrdcompras() as $value) {  
                                        try {

                                            $cons_orcom= $value->cons_orcom;
                                            $num_orcom = $value->num_orcom;

                                            $sqlNewOrcom= "INSERT INTO previo.cop_orcom(id_prev, cons_orcom, num_orcom)"
                                                . " VALUES($id_prev, $cons_orcom, '$num_orcom')";
                                            $save_orcom = $dbAdoP->Execute ( $sqlNewOrcom );

                                        } catch (Exception $ex) {
                                            $dbAdoP->rollbackTrans();
                                            $this->setSuccess(false);
                                            $this->setMessageText("Ocurrió un error al guardar la información de las ordenes de compra." . $ex->getMessage());
                                            return "Ocurrió un error al guardar la información de las ordenes de compra." . $ex->getMessage();
                                        }
                                      
                                    }
                                    
                                    foreach ($this->getFiles() as $key => $value) {
                                        try {
                                            $num_refe = trim($this->getNum_refe());
                                              
                                            $pathBase = 'files/EPrevious/' . $this->getIdclient() .'/'. $this->getAduana().'_'. $patente  . '/' . $num_refe . '/Fotos' . '/'.$value->nom_foto;

                                            $cons_foto = $value->cons_foto;
                                            $nom_foto =  $value->nom_foto;

                                            $sqlNewFotoPrev =  "INSERT INTO previo.cprevo_fotop(id_prev, cons_foto, nom_foto, url_foto)"
                                                . " VALUES($id_prev, $cons_foto, '$nom_foto', '$pathBase')";
                                            $save_fotos = $dbAdoP->Execute ( $sqlNewFotoPrev );



                                            $photo = new File($this->getIdclient(), $num_refe, $value->cons_foto, $value->nom_foto, $value->fileString, $this->getAduana(), $patente, null);
                                            $photo->loadFilePrevioOperation();

                                        } catch (Exception $ex) {

                                            $dbAdoP->rollbackTrans();
                                            $this->setSuccess(false);
                                            $this->setMessageText("Ocurrió un error al guardar la información de las fotos a nivel previo." . $ex->getMessage());
                                            return "Ocurrió un error al guardar la información de las fotos a nivel previo." . $ex->getMessage();
                                        }  
                                        
                                    }
                                    
                                    foreach ($this->getInvoices() as $key => $value) {
                                        try {

                                            $cons_fact =  $value->cons_fact;
                                            $num_fact = trim($value->num_fact);
                                            $fac_extra = $value->fac_extra;

                                            $sqlNewInvoice = "INSERT INTO previo.cprevo_factur(id_prev, cons_fact, num_fact, fac_extra)"
                                            . " VALUES($id_prev, $cons_fact, '$num_fact', $fac_extra) RETURNING id_factur";
                                            $save_factur = $dbAdoP->Execute ( $sqlNewInvoice );

                                            $id_factur = $save_factur->fields["id_factur"];

                                        } catch (Exception $ex) {
                                            $dbAdoP->rollbackTrans();
                                            $this->setSuccess(false);
                                            $this->setMessageText("Ocurrió un error al guardar la información de la factura." . $ex->getMessage());
                                            return "Ocurrió un error al guardar la información de la factura." . $ex->getMessage();
                                        }
                            
                                        foreach ($value->products as $product) {
                                            try {

                                                $cons_part = $product->cons_part;
                                                $num_part= trim($product->num_part);
                                                $desc_merc= $product->desc_merc;
                                                $pai_orig= $product->pai_orig;
                                                $uni_fact= $product->uni_fact;
                                                $can_fact= $product->can_fact;
                                                $can_factr= $product->can_factr;
                                                $edo_corr= $product->edo_corr;
                                                $obs_frac= $product->obs_frac;
                                                $cve_usua= $product->cve_usua;
                                                $inc_part= $product->inc_part;
                                                $uni_tari= $product->uni_tari;
                                                $can_tari= $product->can_tari;
                                                $pes_unit= $product->pes_unit;
                                            
                                                
                                                $nextVal = "SELECT setval('previo.cprevo_facpar_id_partida_seq', (SELECT MAX(id_partida) FROM previo.cprevo_facpar)+1)";
                                                $nextIdPrev= $dbAdoP->Execute ( $nextVal );
                                                $idNextPrev= json_decode(json_encode($nextIdPrev->fields), true);
                                                $id_partidaNext = $idNextPrev[0];

                                                $sqlNewPart =  "INSERT INTO previo.cprevo_facpar(id_partida, id_factur, cons_part, num_part, desc_merc, pai_orig, uni_fact, can_fact, can_factr, edo_corr,obs_frac,cve_usua, inc_part,  uni_tari, can_tari, pes_unit, estatus_part)"
                                                    . " VALUES($id_partidaNext, $id_factur, $cons_part, '$num_part',  '$desc_merc', '$pai_orig', '$uni_fact', $can_fact, $can_factr, $edo_corr,'$obs_frac','$cve_usua', '$inc_part',  $uni_tari, $can_tari, $pes_unit, 1) RETURNING id_partida";
                                                $save_part = $dbAdoP->Execute ( $sqlNewPart );
                                                $id_partida = $save_part->fields["id_partida"];
                                        
                                                /*$sqlNewPart =  "INSERT INTO previo.cprevo_facpar(id_factur, cons_part, desc_merc, pai_orig, uni_fact, can_fact, can_factr, edo_corr,obs_frac,cve_usua, inc_part,  uni_tari, can_tari, pes_unit)"
                                                    . " VALUES($id_factur, $cons_part, '$desc_merc', '$pai_orig', '$uni_fact', $can_fact, $can_factr, $edo_corr,'$obs_frac','$cve_usua', '$inc_part',  $uni_tari, $can_tari, $pes_unit) RETURNING id_partida";
                                                $save_part = $dbAdoP->Execute ( $sqlNewPart );
                                                $id_partida = $save_part->fields["id_partida"];*/
                                                
                                            } catch (Exception $ex) {
                                                $dbAdoP->rollbackTrans();
                                                $this->setSuccess(false);
                                                $this->setMessageText("Ocurrió un error al guardar la información de la partida." . $ex->getMessage());
                                                return "Ocurrió un error al guardar la información de la partida." . $ex->getMessage();
                                            }
                                            if(count($product->series) > 0) {
                                                foreach ($product->series as $serie) {
                                                    try {
                                                        $cons_seri =  $serie->cons_seri;
                                                        $num_part = trim($serie->num_part);
                                                        $mar_merc =  $serie->mar_merc;
                                                        $sub_mode = trim($serie->sub_mode);
                                                        $num_serie = trim($serie->num_seri);

                                                        if($num_serie == null){
                                                            $num_serie = '';
                                                        }


                                                        $sqlNewSerie =  "INSERT INTO previo.cprevo_series(id_partida, cons_seri, num_part, mar_merc, sub_mode, num_seri)"
                                                            . " VALUES($id_partida, $cons_seri, '$num_part', '$mar_merc', '$sub_mode', '$num_serie')";


                                                        $save_serie= $dbAdoP->Execute ( $sqlNewSerie );


                                                    } catch (Exception $ex) {
                                                        $dbAdoP->rollbackTrans();
                                                        $this->setSuccess(false);
                                                        $this->setMessageText("Ocurrió un error al guardar la información de la serie." . $ex->getMessage());
                                                        return "Ocurrió un error al guardar la información de la serie." . $ex->getMessage();
                                                    }  
                                                }
                                            }

                                            if(count($product->files) > 0) {
                                                foreach ($product->files as $file) {
                                                    try {
                                                        $id_partida =  $id_partida;
                                                        $cons_foto = $file->cons_foto;
                                                        $nom_foto =  $file->nom_foto;
                                                        $num_refe = trim($this->getNum_refe());
                                                        $pathBasePart = "/files/EPrevious/" .$this->getIdclient() ."/". $this->getAduana()."_". $patente  . "/" . $num_refe . "/" . $id_partida."/Fotos";


                                                        $sqlNewFoto=  "INSERT INTO previo.cprevo_fotos(id_partida, cons_foto, nom_foto, url_foto)"
                                                            . " VALUES($id_partida, $cons_foto, '$nom_foto', '$pathBasePart')";
                                                        $save_foto = $dbAdoP->Execute ( $sqlNewFoto );

                                                        $photo = new File($this->getIdclient(), $num_refe, $file->cons_foto, $file->nom_foto, $file->fileString, $this->getAduana(), $patente, $id_partida);
                                                        $photo->loadFilePartidaOperation();


                                                    } catch (Exception $ex) {
                                                        $dbAdoP->rollbackTrans();
                                                        $this->setSuccess(false);
                                                        $this->setMessageText("Ocurrió un error al guardar la información de las fotos a nivel partida." . $ex->getMessage());
                                                        return "Ocurrió un error al guardar la información de las fotos a nivel partida." . $ex->getMessage();
                                                    }  
                                                }
                                            }


                                        }
                                        
                                    }
                                    
                                } catch(Exception $e){
                                    throw new Exception("Ocurrió un error al eliminar la referencia: ". $e->getMessage());
                                }
                                
                            }
                        
     
                        $this->setSuccess(true);
                        $this->setMessageText("La operación es correcta");
                        return true;
                           
                           
                        } else {

                            $object = $this->getInvoices();
                            $array = json_decode(json_encode($object), true);
                            $cveUsuaFirstPart = $array[0]["products"][0]["cve_usua"];
    
                            if ($responsePart[0]["cve_usua"] == NULL || $responsePart[0]["cve_usua"] == $cveUsuaFirstPart) {
                                $photo = new File($this->getIdclient(), $this->getNum_refe(), $value->cons_foto, $value->nom_foto, $value->fileString, $this->getAduana());
                                $photo->deleteFile();
                                
                                $table = "'previo'.cprevo_refe CR";
                                $joins = "INNER JOIN 'general'.casag_licencias L ON CR.id_licencia = L.id_licencia";
                                $fieldsArray = array(
                                    "CR.'id_prev'",
                                    "L.id_licencia"
                                );
                                $parameters = "CR.num_refe = '" . $this->getNum_refe() . "' AND L.id_cliente =  " . $this->getIdclient() . "";
                                $responseRefe = $db->query($table, $fieldsArray, $joins, $parameters);
                                $idprev = $responseRefe[0]["id_prev"];
                                
                                if(!$idprev){
                                    throw new Exception("Error: La referencia no existe");
                                } else {
                                    
                                    
                                          
                                    $table = "'previo'.cprevo_refe REFE";
                                    $joins = "INNER JOIN 'previo'.cprevo_previos P ON REFE.id_prev = P.id_prev ";
                                    $joins .= "INNER JOIN 'previo'.cprevo_factur F ON P.id_prev = F.id_prev ";
                                    $joins .= "INNER JOIN 'previo'.cprevo_facpar FP ON FP.id_factur = F.id_factur ";
                                    $joins .= "INNER JOIN 'general'.casag_licencias L ON REFE.id_licencia = L.id_licencia ";
                                    $joins .= "INNER JOIN 'previo'.cprevo_fotos FOP ON FP.id_partida = FOP.id_partida";
                                    
                                    $fieldsArray = array(
                                        "FOP.id_partida"
                                    );
                                    $parameters = "REFE.num_refe = '" . $this->getNum_refe() . "' AND L.id_cliente =  " . $this->getIdclient() . "";
                                    $responsePhotoParts = $db->query($table, $fieldsArray, $joins, $parameters);

                                    try {
                                        foreach ($responsePhotoParts as $part){
                                            try {

                                                $sqlPhotosDeletePart = "DELETE FROM previo.cprevo_fotos WHERE id_partida = $partId";
                                                $photosDeletePart = $dbAdoP->Execute ( $sqlPhotosDeletePart );
                                            } catch (Exception $ex) {
                                                $dbAdoP->rollbackTrans();
                                                $this->setSuccess(false);
                                                $this->setMessageText("Ocurrió un error al eliminar las fotos de la partida." .$ex->getMessage() );
                                                return "Ocurrió un error al eliminar las fotos de la partida.";
                                            }
                                        }
                                      
                               
                                        
                                        $table = "'previo'.cprevo_refe REFE";
                                        $joins = "INNER JOIN 'previo'.cprevo_previos P ON REFE.id_prev = P.id_prev ";
                                        $joins .= "INNER JOIN 'general'.casag_licencias L ON REFE.id_licencia = L.id_licencia ";
                                        $joins .= "INNER JOIN previo.cprevo_fotop FOP ON P.id_prev = FOP.id_prev";
                                        $fieldsArray = array(
                                            "FOP.id_fotop"
                                        );
                                        $parameters = "REFE.num_refe = '" . $this->getNum_refe() . "' AND L.id_cliente =  " . $this->getIdclient() . "";
                                        $responsePhotoPrevio = $db->query($table, $fieldsArray, $joins, $parameters);
                                       
                                        if(count($responsePhotoPrevio) > 0){
                                            try {

                                                $sqlPhotosDelete = "DELETE FROM previo.cprevo_fotop WHERE id_prev = $idprev";
                                                $photosDelete = $dbAdoP->Execute ( $sqlPhotosDelete );
                                            } catch (Exception $ex) {
                                                $dbAdoP->rollbackTrans();
                                                $this->setSuccess(false);
                                                $this->setMessageText("Ocurrió un error al eliminar las fotos de la referencia. " . $ex->getMessage());
                                                return "Ocurrió un error al eliminar las fotos de la referencia.";
                                            }
                                        
                                        }
                            
                                        
                                        try {

                                            $sqlPhotosDelete = "DELETE FROM previo.cprevo_refe WHERE id_prev = $idprev";
                                            $photosDelete = $dbAdoP->Execute ( $sqlPhotosDelete );
                                        } catch (Exception $ex) {
                                            $dbAdoP->rollbackTrans();
                                            $this->setSuccess(false);
                                             $this->setMessageText("Ocurrió un error al eliminar la referencia." . $ex->getMessage());
                                            return "Ocurrió un error al eliminar la referencia.";
                                        }
                                             
                                        $licenseId = $responseRefe[0]["id_licencia"];
                                        try {
                                            $num_refe = trim($this->getNum_refe());

                                            $sqlNewReference = "INSERT INTO previo.cprevo_refe(id_licencia, num_refe)"
                                                . " VALUES($licenseId, '$num_refe') RETURNING id_prev";
                                            $saveReference = $dbAdoP->Execute ( $sqlNewReference );
                                            $id_prev = $saveReference->fields["id_prev"];

                                        } catch (Exception $ex) {
                                            $dbAdoP->rollbackTrans();
                                            $this->setSuccess(false);
                                            $this->setMessageText( "Ocurrió un error al guardar la referencia. " . $ex->getMessage());
                                            return "Ocurrió un error al guardar la referencia.";
                                        }
                                        
                                        try {
  
                                            $id_importador = $id_importador;
                                            $fec_soli= $this->getFec_soli();
                                            $fol_soli= $this->getFol_soli();
                                            $tot_bult= $this->getTot_bult();
                                            $tot_bultr= $this->getTot_bultr();
                                            $rec_fisc= $this->getRec_fisc();
                                            $num_guia= $this->getNum_guia();
                                            $edo_prev= $this->getEdo_prev();
                                            $ins_prev= $this->getIns_prev();
                                            $dep_asigna= $this->getDep_asigna();
                                            $obs_prev= $this->getObs_prev();
                                            $pes_brut= $this->getPes_brut();
                                            $hora_inicio= $this->getHora_inicio();
                                            $hora_fin = $this->getHora_fin();
                                            $flag_version = 2;

                                            $sqlNewPrev= "INSERT INTO previo.cprevo_previos(id_prev, id_importador, fec_soli, fol_soli, tot_bult, tot_bultr, rec_fisc,  num_guia,edo_prev,ins_prev, dep_asigna, obs_prev, pes_brut, hora_inicio, hora_fin, flag_version  )"
                                             . " VALUES($id_prev, $id_importador, '$fec_soli', '$fol_soli', $tot_bult, $tot_bultr, '$rec_fisc',  '$num_guia','$edo_prev','$ins_prev', '$dep_asigna', '$obs_prev', $pes_brut, '$hora_inicio', '$hora_fin', $flag_version)";

                                            $save_previo= $dbAdoP->Execute ( $sqlNewPrev );

                                        } catch (Exception $ex) {
                                            $dbAdoP->rollbackTrans();
                                            $this->setSuccess(false);
                                            $this->setMessageText("Ocurrió un error al guardar la información del previo." . $ex->getMessage());
                                            return "Ocurrió un error al guardar la información del previo." . $ex->getMessage();
                                        }
                    
                        
                                        
                                        foreach ($this->getContenedores() as $key => $value) {
                                            if ($id_prev > 0) {
                                                try {
                                                    $db->setTable('"general".casac_tipcon');
                                                    $db->setFields(array(
                                                        'id_tipcon'
                                                    ));

                                                    $db->setParameters("clave_tipcon = '" . $value->clave_tipcon . "'");
                                                    $contentId = $db->query();

                                                    $contentId = $contentId->id_tipcon;

                                                    if(!$contentId){
                                                        $contentId = 1;
                                                    }

                                                    $numero_contenedor= $value->numero_contenedor;
                                                    $numero_candado1= $value->numero_candado1;
                                                    $numero_candado2= $value->numero_candado2;
                                                    $numero_candado3= $value->numero_candado3;
                                                    $numero_candado4= $value->numero_candado4;
                                                    $numero_candado5=  $value->numero_candado5;
                                                    $obs_cont= $value->obs_cont;
                                                    $sqlNewContent= "INSERT INTO previo.cop_conten(id_prev, id_tipcon, numero_contenedor,numero_candado1, numero_candado2, numero_candado3,numero_candado4, numero_candado5, obs_cont )"
                                                        . " VALUES($id_prev, $contentId, '$numero_contenedor', '$numero_candado1', '$numero_candado2', '$numero_candado3','$numero_candado4', '$numero_candado5', '$obs_cont')";
                                                    $save_content = $dbAdoP->Execute ( $sqlNewContent );


                                                } catch (Exception $ex) {
                                                    $dbAdoP->rollbackTrans();
                                                    $this->setSuccess(false);
                                                    $this->setMessageText("Ocurrió un error al guardar la información de los contenedores" . $ex->getMessage());
                                                    return "Ocurrió un error al guardar la información de los contenedores" . $ex->getMessage();
                                                }
                        
                             
                                            }
                                        }
                                        
                                        foreach ($this->getBultos() as $key => $value) {
                                            if ($id_prev> 0) {
                                                
                                                $db2->setTable('general.casac_bultos');
                                                $db2->setFields(array(
                                                    'id_bulto'
                                                ));
                                                $db2->setParameters("clave_bulto = '" . $value->clave_bulto . "' AND id_cliente = -1");
                                                $packageId = $db2->query();
                                                
                                                $packageId = $packageId->id_bulto;
                                                
                                                if(!$packageId){
                                                    $packageId = 1;
                                                }

                                                try {

                                                    $cons_bulto= $value->cons_bulto;
                                                    $cant_bult= $value->cant_bult;
                                                    $anc_bult= $value->anc_bult;
                                                    $lar_bult= $value->lar_bult;
                                                    $alt_bult= $value->alt_bult;
                                                    $obs_bult= $value->obs_bult;
                                                    $sqlNewbult= "INSERT INTO previo.cop_bultos(id_prev, id_bulto, cons_bulto,cant_bult, anc_bult, lar_bult,alt_bult, obs_bult)"
                                                        . " VALUES($id_prev, $packageId, $cons_bulto,$cant_bult, $anc_bult, $lar_bult,$alt_bult, $obs_bult)";
                                                    $save_bult = $dbAdoP->Execute ( $sqlNewbult );

                                                } catch (Exception $ex) {
                                                    $dbAdoP->rollbackTrans();
                                                    $this->setSuccess(false);
                                                    $this->setMessageText("Ocurrió un error al guardar la información de los bultos." . $ex->getMessage());
                                                    return "Ocurrió un error al guardar la información de los bultos." . $ex->getMessage();

                                                }

                                            }
                                        }
                                        
                                        foreach ($this->getOrdcompras() as $value) {
                                            if ($id_prev > 0) {
                                                try {

                                                    $cons_orcom= $value->cons_orcom;
                                                    $num_orcom = $value->num_orcom;

                                                    $sqlNewOrcom= "INSERT INTO previo.cop_orcom(id_prev, cons_orcom, num_orcom)"
                                                        . " VALUES($id_prev, $cons_orcom, '$num_orcom')";
                                                    $save_orcom = $dbAdoP->Execute ( $sqlNewOrcom );

                                                } catch (Exception $ex) {
                                                    $dbAdoP->rollbackTrans();
                                                    $this->setSuccess(false);
                                                    $this->setMessageText("Ocurrió un error al guardar la información de las ordenes de compra." . $ex->getMessage());
                                                    return "Ocurrió un error al guardar la información de las ordenes de compra." . $ex->getMessage();
                                                }
                                            }
                                        }
                                        
                                        foreach ($this->getFiles() as $key => $value) {
                                            try {
                                                
                                                $num_refe = trim($this->getNum_refe());
                                                 
                                                 
                                                $pathBase = 'files/EPrevious/' . $this->getIdclient() .'/'. $this->getAduana().'_'. $patente  . '/' . $num_refe . '/Fotos' . '/'.$value->nom_foto;

                                                $cons_foto = $value->cons_foto;
                                                $nom_foto =  $value->nom_foto;

                                                $sqlNewFotoPrev =  "INSERT INTO previo.cprevo_fotop(id_prev, cons_foto, nom_foto, url_foto)"
                                                    . " VALUES($id_prev, $cons_foto, '$nom_foto', '$pathBase')";
                                                $save_fotos = $dbAdoP->Execute ( $sqlNewFotoPrev );



                                                $photo = new File($this->getIdclient(), $num_refe, $value->cons_foto, $value->nom_foto, $value->fileString, $this->getAduana(), $patente, null);
                                                $photo->loadFilePrevioOperation();

                                            } catch (Exception $ex) {
                                                $dbAdoP->rollbackTrans();
                                                $this->setSuccess(false);
                                                $this->setMessageText("Ocurrió un error al guardar la información de las fotos a nivel previo." . $ex->getMessage());
                                                return "Ocurrió un error al guardar la información de las fotos a nivel previo." . $ex->getMessage();
                                            }  
                            
                                        }
                                        
                                        foreach ($this->getInvoices() as $key => $value) {
                                            if ($id_prev > 0) {
                                                try {

                                                    $cons_fact =  $value->cons_fact;
                                                    $num_fact = trim($value->num_fact);
                                                    $fac_extra = $value->fac_extra;

                                                    $sqlNewInvoice = "INSERT INTO previo.cprevo_factur(id_prev, cons_fact, num_fact, fac_extra)"
                                                    . " VALUES($id_prev, $cons_fact, '$num_fact', $fac_extra) RETURNING id_factur";
                                                    $save_factur = $dbAdoP->Execute ( $sqlNewInvoice );

                                                    $id_factur = $save_factur->fields["id_factur"];

                                                } catch (Exception $ex) {
                                                    $dbAdoP->rollbackTrans();
                                                    $this->setSuccess(false);
                                                    $this->setMessageText("Ocurrió un error al guardar la información de los contenedores" . $ex->getMessage());
                                                    return "Ocurrió un error al guardar la información de la factura." . $ex->getMessage();
                                                }

                            
                                               foreach ($value->products as $product) {
                                                    if ($id_prev > 0) {
                                                        try {

                                                            $cons_part = $product->cons_part;
                                                            $num_part= trim($product->num_part);
                                                            $desc_merc= $product->desc_merc;
                                                            $pai_orig= $product->pai_orig;
                                                            $uni_fact= $product->uni_fact;
                                                            $can_fact= $product->can_fact;
                                                            $can_factr= $product->can_factr;
                                                            $edo_corr= $product->edo_corr;
                                                            $obs_frac= $product->obs_frac;
                                                            $cve_usua= $product->cve_usua;
                                                            $inc_part= $product->inc_part;
                                                            $uni_tari= $product->uni_tari;
                                                            $can_tari= $product->can_tari;
                                                            $pes_unit= $product->pes_unit;

                                                            
                                                            $nextVal = "SELECT setval('previo.cprevo_facpar_id_partida_seq', (SELECT MAX(id_partida) FROM previo.cprevo_facpar)+1)";
                                                            $nextIdPrev= $dbAdoP->Execute ( $nextVal );
                                                            $idNextPrev= json_decode(json_encode($nextIdPrev->fields), true);
                                                            $id_partidaNext = $idNextPrev[0];

                                                               
                                                            $sqlNewPart =  "INSERT INTO previo.cprevo_facpar(id_partida, id_factur, cons_part, num_part, desc_merc, pai_orig, uni_fact, can_fact, can_factr, edo_corr,obs_frac,cve_usua, inc_part,  uni_tari, can_tari, pes_unit, estatus_part)"
                                                                . " VALUES($id_partidaNext, $id_factur, $cons_part, '$num_part',  '$desc_merc', '$pai_orig', '$uni_fact', $can_fact, $can_factr, $edo_corr,'$obs_frac','$cve_usua', '$inc_part',  $uni_tari, $can_tari, $pes_unit, 1) RETURNING id_partida";
                                                            $save_part = $dbAdoP->Execute ( $sqlNewPart );
                                                            $id_partida = $save_part->fields["id_partida"];
                                                            
                                                            /*$sqlNewPart =  "INSERT INTO previo.cprevo_facpar(id_factur, cons_part, desc_merc, pai_orig, uni_fact, can_fact, can_factr, edo_corr,obs_frac,cve_usua, inc_part,  uni_tari, can_tari, pes_unit)"
                                                                . " VALUES($id_factur, $cons_part, '$desc_merc', '$pai_orig', '$uni_fact', $can_fact, $can_factr, $edo_corr,'$obs_frac','$cve_usua', '$inc_part',  $uni_tari, $can_tari, $pes_unit) RETURNING id_partida";
                                                            $save_part = $dbAdoP->Execute ( $sqlNewPart );
                                                            $id_partida = $save_part->fields["id_partida"];*/
                                                            
                                                        } catch (Exception $ex) {
                                                            $dbAdoP->rollbackTrans();
                                                            $this->setSuccess(false);
                                                            $this->setMessageText("Ocurrió un error al guardar la información de la partida." . $ex->getMessage());
                                                            return "Ocurrió un error al guardar la información de la partida." . $ex->getMessage();
                                                        }

                                                        
                                                        if(count($product->series) > 0) {
                                                            foreach ($product->series as $serie) {
                                                                try {
                                                                    $cons_seri =  $serie->cons_seri;
                                                                    $num_part = trim($serie->num_part);
                                                                    $mar_merc =  $serie->mar_merc;
                                                                    $sub_mode = trim($serie->sub_mode);
                                                                    $num_serie = trim($serie->num_seri);

                                                                    if($num_serie == null){
                                                                        $num_serie = '';
                                                                    }


                                                                    $sqlNewSerie =  "INSERT INTO previo.cprevo_series(id_partida, cons_seri, num_part, mar_merc, sub_mode, num_seri)"
                                                                        . " VALUES($id_partida, $cons_seri, '$num_part', '$mar_merc', '$sub_mode', '$num_serie')";


                                                                    $save_serie= $dbAdoP->Execute ( $sqlNewSerie );


                                                                } catch (Exception $ex) {
                                                                    $dbAdoP->rollbackTrans();
                                                                    $this->setSuccess(false);
                                                                     $this->setMessageText("Ocurrió un error al guardar la información de la partida." . $ex->getMessage());
                                                                    return "Ocurrió un error al guardar la información de la serie." . $ex->getMessage();
                                                                }   
                                                            }
                                                        }
                                                        
                                                        if(count($product->files) > 0) {
                                                            foreach ($product->files as $file) {
                                                                try {
                                                                    $num_refe = trim($this->getNum_refe());
                                                                        
                                                                    $id_partida =  $id_partida;
                                                                    $cons_foto = $file->cons_foto;
                                                                    $nom_foto =  $file->nom_foto;
                                                                    $pathBasePart = "/files/EPrevious/" .$this->getIdclient() ."/". $this->getAduana()."_". $patente  . "/" . $num_refe . "/" . $id_partida."/Fotos";


                                                                    $sqlNewFoto=  "INSERT INTO previo.cprevo_fotos(id_partida, cons_foto, nom_foto, url_foto)"
                                                                        . " VALUES($id_partida, $cons_foto, '$nom_foto', '$pathBasePart')";
                                                                    $save_foto = $dbAdoP->Execute ( $sqlNewFoto );

                                                                    $photo = new File($this->getIdclient(), $num_refe, $file->cons_foto, $file->nom_foto, $file->fileString, $this->getAduana(), $patente, $id_partida);
                                                                    $photo->loadFilePartidaOperation();


                                                                } catch (Exception $ex) {
                                                                    $dbAdoP->rollbackTrans();
                                                                    $this->setSuccess(false);
                                                                    $this->setMessageText("Ocurrió un error al guardar la información de las fotos a nivel partida." . $ex->getMessage());
                                                                    return "Ocurrió un error al guardar la información de las fotos a nivel partida." . $ex->getMessage();
                                                                }   
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                          
                                    } catch(Exception $e){
                                        throw new Exception("Ocurrió un error al eliminar la referencia: ". $e->getMessage());
                                    }
                                    
                                }
                                $this->setSuccess(true);
                                $this->setMessageText("La operación es correcta");
                                return "La operación es correcta";
                        
                                                          
                            } else {
                                throw new Exception("Error: El usuario no coincide. No se permite enviar parcialidades con un usuario distinto al original.");
                            }
                             
                        }
                       
                    }
                    $dbAdoP->commitTrans();  
                } else {
                    $dbAdoP->beginTrans();  

                    $table = "previo.cprevo_refe CR";
                    $joins = "INNER JOIN general.casag_licencias L ON CR.id_licencia = L.id_licencia";
                    $fieldsArray = array(
                        "CR.id_prev",
                        "patente"
                    );
                    $parameters = "CR.num_refe = '" . trim($this->getNum_refe()) . "' AND L.id_cliente =  " . $this->getIdclient() . "";
                    $response = $db->query($table, $fieldsArray, $joins, $parameters);
                   
              
                    $idprev = $response[0]["id_prev"];
                    $patente = $response[0]["patente"];
                    
                    if ($idprev > 0) {
                        $contenArray = $this->getContenedores();
                        
                        foreach ($contenArray as $key => $value) {
                            if ($value->clave_tipcon != '' || $value->clave_tipcon != NULL) {
                                
                                $table = "'general'.casac_tipcon";
                                $joins = "";
                                $fieldsArray = array(
                                    "id_tipcon"
                                );
                                $parameters = "clave_tipcon = '" . $value->clave_tipcon . "' ";
                                $typecontent = $db->query($table, $fieldsArray, $joins, $parameters);
                                $id_tipcon = $typecontent[0]["id_tipcon"];
                                
                                try {
                                    $table = "'previo'.cop_conten";
                                    $joins = "";
                                    $fieldsArray = array(
                                        "id_conten"
                                    );
                                    $parameters = "id_prev = $idprev AND numero_contenedor = '" . $value->numero_contenedor ."'";
                                    $existContent = $db->query($table, $fieldsArray, $joins, $parameters);
                                    $id_conten = $existContent[0]["id_conten"];
                                    
                                    if(!$id_conten){
                                        try {

                                            $numero_contenedor= $value->numero_contenedor;
                                            $numero_candado1= $value->numero_candado1;
                                            $numero_candado2= $value->numero_candado2;
                                            $numero_candado3= $value->numero_candado3;
                                            $numero_candado4= $value->numero_candado4;
                                            $numero_candado5=  $value->numero_candado5;
                                            $obs_cont= $value->obs_cont;
                                            $sqlNewContent= "INSERT INTO previo.cop_conten(id_prev, id_tipcon, numero_contenedor,numero_candado1, numero_candado2, numero_candado3,numero_candado4, numero_candado5, obs_cont )"
                                                . " VALUES($id_prev, $id_tipcon, '$numero_contenedor', '$numero_candado1', '$numero_candado2', '$numero_candado3','$numero_candado4', '$numero_candado5', '$obs_cont')";
                                            $save_content = $dbAdoP->Execute ( $sqlNewContent );


                                        } catch (Exception $ex) {
                                            $dbAdoP->rollbackTrans();
                                            $this->setSuccess(false);
                                            $this->setMessageText("Ocurrió un error al guardar la información de los contenedores" . $ex->getMessage());
                                            return "Ocurrió un error al guardar la información de los contenedores" . $ex->getMessage();
                                        }
                                        
                                        
                                    } else {
                                        $table = "previo.cop_conten";
                                        $values = Array(
                                            "id_prev" => $idprev,
                                            "id_tipcon" => $id_tipcon,
                                            "numero_contenedor" => $value->numero_contenedor,
                                            "numero_candado1" => $value->numero_candado1,
                                            "numero_candado2" => $value->numero_candado2,
                                            "numero_candado3" => $value->numero_candado3,
                                            "numero_candado4" => $value->numero_candado4,
                                            "numero_candado5" => $value->numero_candado5,
                                            "obs_cont" => $value->obs_cont
                                            );
                                        $params = "id_prev = $idprev AND numero_contenedor = '" . $value->numero_contenedor ."'";
                                        $update = $db->update($table, $values, $params);
                                    
                                    }
                                    
                                    
                                } catch (Exception $e){
                                    throw new Exception("Ocurrió un error al insertar el contenedor: $value->numero_contenedor \n". $e->getMessage());
                                }
                            }
                        }
                        $BultosArray = $this->getBultos();
                  

                        foreach ($BultosArray as $key => $value) {
                            if ($value->clave_bulto != '' || $value->clave_bulto != NULL) {
                                
                                $table = "'general'.casac_bultos";
                                $joins = "";
                                $fieldsArray = array(
                                    "id_bulto"
                                );
                                $parameters = "clave_bulto = '" . $value->clave_bulto . "' AND id_cliente = -1";
                                $packpage = $db->query($table, $fieldsArray, $joins, $parameters);
                                $id_bulto = $packpage[0]["id_bulto"];
                                
                                try {
                                    $table = "'previo'.cop_bultos";
                                    $joins = "";
                                    $fieldsArray = array(
                                        "id_bult"
                                    );
                                    $parameters = "id_prev = $idprev AND id_bulto = $id_bulto  AND cant_bult = " . $value->cant_bult
                                    . " AND anc_bult = " . $value->anc_bult . " AND lar_bult = " .  $value->lar_bult . " AND alt_bult = " .
                                    $value->alt_bult . " AND obs_bult = '" .  $value->obs_bult . "' ";
                                    $existBulto= $db->query($table, $fieldsArray, $joins, $parameters);
                                    $idBulto = $existBulto[0]["id_bult"];
                                    
                                    if(!$idBulto){
                                        $sqlBulto= "SELECT max(cons_bulto) AS maxbulto FROM previo.cop_bultos WHERE id_prev = '$idprev'";
                                        $maxConsecutiveBulto= $dbAdoP->Execute ( $sqlBulto );
                                        $maxConsecutiveBultoArray = json_decode(json_encode($maxConsecutiveBulto->fields), true);
                                        $maxBulto = $maxConsecutiveBultoArray["maxbulto"] + 1;

                                        try {

                                            $cons_bulto= $maxBulto;
                                            $cant_bult= $value->cant_bult;
                                            $anc_bult= $value->anc_bult;
                                            $lar_bult= $value->lar_bult;
                                            $alt_bult= $value->alt_bult;
                                            $obs_bult= $value->obs_bult;
                                            $sqlNewbult= "INSERT INTO previo.cop_bultos(id_prev, id_bulto, cons_bulto,cant_bult, anc_bult, lar_bult,alt_bult, obs_bult)"
                                                . " VALUES($idprev, $id_bulto, $cons_bulto,$cant_bult, $anc_bult, $lar_bult,$alt_bult, $obs_bult)";
                                            $save_bult = $dbAdoP->Execute ( $sqlNewbult );

                                        } catch (Exception $ex) {
                                            $dbAdoP->rollbackTrans();
                                            $this->setSuccess(false);
                                            $this->setMessageText("Ocurrió un error al guardar la información de los bultos." . $ex->getMessage());
                                            return "Ocurrió un error al guardar la información de los bultos." . $ex->getMessage();

                                        }
                                    }
                                    
                                } catch(Exception $e){
                                    throw new Exception("Ocurrió un error al insertar el bulto: $id_bulto". $e->getMessage());
                                }
                            }
                        }

                        $OrdcomprasArray = $this->getOrdcompras();
                   
                        foreach ($OrdcomprasArray as $key => $value) {
                            
                            if ($value->cons_orcom != '' || $value->cons_orcom != NULL && $value->num_orcom != '' || $value->num_orcom != NULL) {
                                try {
                                    $table = "'previo'.cop_orcom";
                                    $joins = "";
                                    $fieldsArray = array(
                                        "id_orcom"
                                    );
                                    $parameters = "id_prev = $idprev  AND num_orcom = '" . $value->num_orcom. "'";
                                    $existOrden= $db->query($table, $fieldsArray, $joins, $parameters);
                                    $idOrden = $existOrden[0]["id_orcom"];
                                    
                                    if(!$idOrden){
                                        
                                        $sqlOrder= "SELECT max(cons_orcom) AS maxorden FROM previo.cop_orcom WHERE id_prev = '$idprev'";
                                        $maxConsecutiveOrder= $dbAdoP->Execute ( $sqlOrder );
                                        $maxConsecutiveOrderArray = json_decode(json_encode($maxConsecutiveOrder->fields), true);
                                        $consOrden = $maxConsecutiveOrderArray["maxorden"] + 1;
                                        try {

                                            $num_orcom = trim($value->num_orcom);

                                            $sqlNewOrcom= "INSERT INTO previo.cop_orcom(id_prev, cons_orcom, num_orcom)"
                                                . " VALUES($idprev, $consOrden, '$num_orcom')";
                                            $save_orcom = $dbAdoP->Execute ( $sqlNewOrcom );

                                        } catch (Exception $ex) {
                                            $dbAdoP->rollbackTrans();
                                            $this->setSuccess(false);
                                            $this->setMessageText("Ocurrió un error al guardar la información de las ordenes de compra." . $ex->getMessage());

                                            return "Ocurrió un error al guardar la información de las ordenes de compra." . $ex->getMessage();
                                        }
                                    }
                                    
                                } catch (Exception $e){
                                    throw new Exception("Ocurrió un error al insertar la orden de compra: $value->num_orcom \n". $e->getMessage());
                                }
                                
                            }
                        }
                        
                        try {
                            $table = "previo.cprevo_previos";
                            $joins = "";
                            $fieldsArray = array(
                                "id_prev",
                                "flag_version"
                            );
                            $parameters = "id_prev = $idprev";
                            $prev = $db->query($table, $fieldsArray, $joins, $parameters);
                            $prevFlag= $prev[0]["flag_version"];
                
                            $tableP = "previo.cprevo_previos";
                            $values = Array(
                                "id_prev" => $idprev,
                                "fec_soli" => $this->fec_soli,
                                "tot_bultr" => $this->tot_bultr,
                                "rec_fisc" => $this->rec_fisc,
                                "num_guia" => $this->num_guia,
                                "edo_prev" => $this->edo_prev,
                                "dep_asigna" => $this->dep_asigna,
                                "obs_prev" => $this->obs_prev,
                                "pes_brut" => $this->pes_brut,
                                "hora_inicio" => $this->hora_inicio,
                                "hora_fin" => $this->hora_fin
                                );
                            $params = "id_prev = $idprev";
                            $update = $db->update($tableP, $values, $params);
                                
                        } catch(Exception $e){
                            throw new Exception("Ocurrió un error al actualizar el previo con la referencia: $this->num_refe \n" . $e->getMessage());
                        }
                             
     
                        $nextCons = 0;
                        //Insertar imagenes para Previo
                        foreach ($this->getFiles() as $key => $value) {
                            
                            try {
                                $tableF = "previo.cprevo_fotop";
                                $joins = "";
                                $fieldsArray = array(
                                    "id_fotop",
                                    "cons_foto",
                                    "nom_foto",
                                    //"url_foto"
                                );
                                $parameters = "nom_foto = '" .  $value->nom_foto . "' AND id_prev = $idprev";
                                $photos = $db->query($tableF, $fieldsArray, $joins, $parameters);
                                $id_foto = $photos[0]["id_fotop"];
                                    
                   
                                if(!$id_foto){             
                                    $sqlRederence = "SELECT max(cons_foto) AS consfoto FROM previo.cprevo_fotop WHERE id_prev = '$idprev'";
                                    $maxConsecutive= $dbAdoP->Execute ( $sqlRederence );
                                    $maxConsecutiveArray = json_decode(json_encode($maxConsecutive->fields), true);
                                    $nextCons = $maxConsecutiveArray["consfoto"] + 1;
                                    if($prevFlag == 2){
                                        $pathBase = 'files/EPrevious/' . $this->getIdclient() .'/'. $this->getAduana().'_'. $patente  . '/' . $this->getNum_refe() . '/Fotos' . '/'.$value->nom_foto;
                                        $photo = new File($this->getIdclient(), $this->getNum_refe(), $value->cons_foto, $value->nom_foto, $value->fileString, $this->getAduana(), $patente, null);
                                        $photo->loadFilePrevioOperation();
                             
                                        $sqlNewFotoPrev =  "INSERT INTO previo.cprevo_fotop(id_prev, cons_foto, nom_foto, url_foto)"
                                            . " VALUES($idprev, $nextCons, '$value->nom_foto', '$pathBase')";
                                        $save_fotos = $dbAdoP->Execute ( $sqlNewFotoPrev );
                                        
                                    } else {

                                        $photo = new File($this->getIdclient(), $this->getNum_refe(), $value->cons_foto, $value->nom_foto, $value->fileString, $this->getAduana());
                                        $photo->loadFileOperation();
                                        
                                        $sqlNewFotoPrev =  "INSERT INTO previo.cprevo_fotop(id_prev, cons_foto, nom_foto)"
                                            . " VALUES($idprev, $nextCons, '$value->nom_foto')";
                                        $save_fotos = $dbAdoP->Execute ( $sqlNewFotoPrev );
                                        
                                    }
                                }
                                
                            } catch (Exception $e){
                                throw new Exception("Ocurrió un error al insertar la foto $value->nom_foto \n". $e->getMessage());
                            }
                        }

                        foreach ($this->getInvoices() as $key => $value) {
                            if($value->fac_extra != 1){
                             
                                $num_factur = $value->num_fact;
                                $numFactur = trim($num_factur);
                                $tableFAC = "'previo'.cprevo_factur CF";
                                $joins = "INNER JOIN previo.cprevo_previos PP ON CF . id_prev = PP . id_prev";
                                $fieldsArray = array(
                                    "id_factur"
                                );

                                $parameters =  "CF.id_prev = '" . $idprev . "' AND cons_fact = '" . $value->cons_fact . "' AND num_fact = '" . $numFactur . "'";
                                $fact = $db->query($tableFAC, $fieldsArray, $joins, $parameters);
                                $idInvoice = $fact[0]["id_factur"];  

                                
                                //var_dump($idInvoice);
                               /*; if (strpos($value->num_fact, " ")){
                                    $sql = "
                                SELECT
                                CF.\"id_factur\"
                                FROM
                                \"previo\".cprevo_factur CF
                                INNER JOIN \"previo\" . cprevo_previos PP ON CF . id_prev = PP . id_prev
                                WHERE
                                CF.\"id_prev\" = '" . $idprev . "' AND cons_fact = '" . $value->cons_fact . "' AND num_fact LIKE  '%" . $value->num_fact . "%'";
                                    $response = $db->execute($sql);
                                } else {
                                    $sql = "
                                SELECT
                                CF.\"id_factur\"
                                FROM
                                \"previo\".cprevo_factur CF
                                INNER JOIN \"previo\" . cprevo_previos PP ON CF . id_prev = PP . id_prev
                                WHERE
                                CF.\"id_prev\" = '" . $idprev . "' AND cons_fact = '" . $value->cons_fact . "' AND num_fact = '" . $value->num_fact . "'";
                                    $response = $db->execute($sql);
                                }
                                
                          
                                $idInvoice = $response[0]["id_factur"];*/
                                
                          
                                foreach ($value->products as $product){
                                    $consPart = $product->cons_part;
                                    try {
                                        $table = "'previo'.cprevo_facpar";
                                        $joins = "";
                                        $fieldsArray = array(
                                            "can_factr",
                                            "inc_part",
                                            "can_factr_obs",
                                            "ban_obs",
                                            "cve_usua"
                                        );
                                        $parameters =  "id_factur = $idInvoice AND cons_part = $consPart";
                                        $cant_factrArray = $db->query($table, $fieldsArray, $joins, $parameters);
                                        $can_factr = $cant_factrArray[0]['can_factr'];
                                        $inc_part = $cant_factrArray[0]['inc_part'];
                                        $can_factr_obs = $cant_factrArray[0]['can_factr_obs'];
                                        $ban_obs = $cant_factrArray[0]['ban_obs'];
                                        $cve_usua = $cant_factrArray[0]['cve_usua'];

                                        $quantityChainUpdate = '['.$product->cve_usua.']:'. $product->can_factr;
                                        
                                        //var_dump($quantityChainUpdate);
                                  
                                        $table = "previo.cprevo_facpar";
                                        $valuesArray = Array(
                                            "'". $column ."'"=> $product->value,
                                            "obs_frac " => "ninguna observacion"
     
                                        );
                                        $params = "id_factur = $idInvoice AND cons_part = $consPart AND num_part = $num_part";
                                        $db->update($table, $valuesArray, $params);
                                        
                                    } catch (Exception $e){
                                        throw new Exception("Ocurrió un error al actualizar la mercancía: $product->num_part \n" . $e->getMessage());
                                    }
                                
                                    $table = "'previo'.'cprevo_facpar' CF";
                                    $joins = "INNER JOIN 'previo' . 'cprevo_factur' PP ON CF . 'id_factur' = PP . 'id_factur'";
                                    $fieldsArray = array(
                                        "CF.'id_partida'"
                                    );
                                    $parameters = "CF.id_factur = $idInvoice AND CF.cons_part = $consPart";
                                    $rs = $db->query($table, $fieldsArray, $joins, $parameters);
                                    $id_partida = $rs[0]['id_partida'];
                                    
                                    if($id_partida > 0){
                                        if($product->series > 0){
                                            foreach ($product->series as $serie) {
                                                try {
                                                    if($serie->num_part != '' || $serie->mar_merc != '' || $serie->sub_mode != '' || $serie->num_seri != '' ){
                                                        $table = "'previo'.cprevo_series";
                                                        $joins = "";
                                                        $fieldsArray = array(
                                                            "id_series"
                                                        );

                                                        $parameters =  "num_part = '" . $serie->num_part . "' AND mar_merc = '" .
                                                        $serie->mar_merc . "' AND sub_mode = '" . $serie->sub_mode . "' AND num_seri = '". $serie->num_seri ."' AND id_partida = $id_partida";
                                                        $seriesExit = $db->query($table, $fieldsArray, $joins, $parameters);
                                                        $id_serie = $seriesExit[0]['id_series'];

                                                        
                                                        if(!$id_serie){
                                                            $sqlSerie = "SELECT max(cons_seri) AS maxserie FROM previo.cprevo_series WHERE id_partida = '$id_partida'";
                                                            $maxConsecutiveSerie= $dbAdoP->Execute ( $sqlSerie );
                                                            $maxConsecutiveSerieArray = json_decode(json_encode($maxConsecutiveSerie->fields), true);
                                                            $consSerie = $maxConsecutiveSerieArray["maxserie"] + 1;

                                                            try {
                                                                $num_part = trim($serie->num_part);
                                                                $mar_merc =  $serie->mar_merc;
                                                                $sub_mode = trim($serie->sub_mode);
                                                                $num_serie = trim($serie->num_seri);

                                                                if($num_serie == null){
                                                                    $num_serie = '';
                                                                }
                                                                
                                                                if($mar_merc == null){
                                                                    $mar_merc = '';
                                                                }
                                                                
                                                                if($sub_mode == null){
                                                                    $sub_mode = '';
                                                                }
                                                                
                                                                if($num_serie == null){
                                                                    $num_serie = '';
                                                                }


                                                                $sqlNewSerie =  "INSERT INTO previo.cprevo_series(id_partida, cons_seri, num_part, mar_merc, sub_mode, num_seri)"
                                                                    . " VALUES($id_partida, $consSerie, '$num_part', '$mar_merc', '$sub_mode', '$num_serie')";


                                                                $save_serie= $dbAdoP->Execute ( $sqlNewSerie );


                                                            } catch (Exception $ex) {
                                                                $dbAdoP->rollbackTrans();
                                                                $this->setSuccess(false);
                                                                $this->setMessageText("Ocurrió un error al guardar la información de la serie." . $ex->getMessage());
                                                                return "Ocurrió un error al guardar la información de la serie." . $ex->getMessage();
                                                            }  
                                                                
                                                                
                                                        }
                                                    }     
                                                } catch(Exception $e){
                                                    throw new Exception("Ocurrió un error al insertar la serie: $serie->num_part \n". $e->getMessage());
                                                }
                                            }
                                        }
                                        if($product->files > 0){
                                            foreach ($product->files as $file) {
                                                try {
                                                    
                                                    $table = "'previo'.cprevo_fotos";
                                                    $joins = "";
                                                    $fieldsArray = array(
                                                        "id_fotos"
                                                    );
                                                    $parameters =  "id_partida = $id_partida AND nom_foto =  '" . $file->nom_foto . "' " ;
                                                    $photoPartida = $db->query($table, $fieldsArray, $joins, $parameters);
                                                    $id_photo = $photoPartida[0]['id_fotos'];
                                                    
                                                    
                                                    if(!$id_photo){
                                                        $sqlFotos = "SELECT max(cons_foto) AS maximofoto FROM previo.cprevo_fotos WHERE id_partida = '$id_partida'";
                                                        $maxConsecutiveFotos= $dbAdoP->Execute ( $sqlFotos );
                                                        $maxConsecutivePartArray = json_decode(json_encode($maxConsecutiveFotos->fields), true);
                                                        $maxPhotosPart = $maxConsecutivePartArray["maximofoto"] + 1;

                                                        if($prevFlag == 2){
                                                            $pathBasePart = "/files/EPrevious/" .$this->getIdclient() ."/". $this->getAduana()."_". $patente  . "/" . $this->getNum_refe() . "/" . $id_partida."/Fotos";

                                                            try {
                                                                $id_partida =  $id_partida;
                                                                $cons_foto = $maxPhotosPart;
                                                                $nom_foto =  $file->nom_foto;
                                                                $pathBasePart = $pathBasePart;


                                                                $sqlNewFoto=  "INSERT INTO previo.cprevo_fotos(id_partida, cons_foto, nom_foto, url_foto)"
                                                                    . " VALUES($id_partida, $cons_foto, '$nom_foto', '$pathBasePart')";
                                                                $save_foto = $dbAdoP->Execute ( $sqlNewFoto );


                                                                $photo = new File($this->getIdclient(),  $this->getNum_refe(), $file->cons_foto, $file->nom_foto, $file->fileString, $this->getAduana(), $patente, $id_partida);
       
            
                                                                $photo->loadFilePartidaOperation();


                                                            } catch (Exception $ex) {
                                                                $dbAdoP->rollbackTrans();
                                                                $this->setSuccess(false);
                                                                $this->setMessageText("Ocurrió un error al guardar la información de las fotos a nivel partida." . $ex->getMessage());
                                                                return "Ocurrió un error al guardar la información de las fotos a nivel partida." . $ex->getMessage();
                                                            }  

                                                        } else {
                                                            
                                                            $photo = new File($this->getIdclient(), $this->getNum_refe(), $file->cons_foto, $file->nom_foto, $file->fileString, $this->getAduana());
                                                            $photo->loadFileOperation();

                                                            $id_partida =  $id_partida;
                                                            $cons_foto = $maxPhotosPart;
                                                            $nom_foto =  $file->nom_foto;


                                                            $sqlNewFoto=  "INSERT INTO previo.cprevo_fotos(id_partida, cons_foto, nom_foto)"
                                                                . " VALUES($id_partida, $cons_foto, '$nom_foto')";
                                                            $save_foto = $dbAdoP->Execute ( $sqlNewFoto );
                                                        }
                                                    }
                                                    
                                                } catch(Exception $e){
                                                    throw new Exception("Ocurrió un error al insertar la foto: $file->nom_foto \n". $e->getMessage());
                                                }
                                            }
                                        }
                                    }
                    
                                }
                            } else {
                               
                                try {
                                    $sqlFactur = "SELECT max(cons_fact) AS maximofactur FROM previo.cprevo_factur WHERE id_prev = '$idprev'";
                                    $maxConsecutiveFactur= $dbAdoP->Execute ( $sqlFactur );
                                    $maxConsecutiveFacturArray = json_decode(json_encode($maxConsecutiveFactur->fields), true);
                                    $maxInvoice = $maxConsecutiveFacturArray["maximofactur"] + 1;
                                                            
                                                            
                                    
                                    $cons_fact =  $maxInvoice;
                                    $num_fact = trim($value->num_fact);

                                    $sqlNewInvoice = "INSERT INTO previo.cprevo_factur(id_prev, cons_fact, num_fact, fac_extra)"
                                    . " VALUES($idprev, $cons_fact, '$num_fact', $value->fac_extra) RETURNING id_factur";
                                    $save_factur = $dbAdoP->Execute ( $sqlNewInvoice );

                                    $id_factur = $save_factur->fields["id_factur"];
                                    
                                } catch (Exception $ex) {
                                    $dbAdoP->rollbackTrans();
                                    $this->setSuccess(false);
                                    $this->setMessageText("Ocurrió un error al guardar la información de la factura." . $ex->getMessage());

                                    return "Ocurrió un error al guardar la información de la factura." . $ex->getMessage();
                                }
                        
                                foreach ($value->products as $product) {
                                    try {

                                        $cons_part = $product->cons_part;
                                        $num_part= trim($product->num_part);
                                        $desc_merc= $product->desc_merc;
                                        $pai_orig= $product->pai_orig;
                                        $uni_fact= $product->uni_fact;
                                        $can_fact= $product->can_fact;
                                        $can_factr= $product->can_factr;
                                        $edo_corr= $product->edo_corr;
                                        $obs_frac= $product->obs_frac;
                                        $cve_usua= $product->cve_usua;
                                        $inc_part= $product->inc_part;
                                        $uni_tari= $product->uni_tari;
                                        $can_tari= $product->can_tari;
                                        $pes_unit= $product->pes_unit;
                                        
                                        
                                        $nextVal = "SELECT setval('previo.cprevo_facpar_id_partida_seq', (SELECT MAX(id_partida) FROM previo.cprevo_facpar)+1)";
                                        $nextIdPrev= $dbAdoP->Execute ( $nextVal );
                                        $idNextPrev= json_decode(json_encode($nextIdPrev->fields), true);
                                        $id_partidaNext = $idNextPrev[0];

                                        $sqlNewPart =  "INSERT INTO previo.cprevo_facpar(id_partida, id_factur, cons_part, num_part, desc_merc, pai_orig, uni_fact, can_fact, can_factr, edo_corr,obs_frac,cve_usua, inc_part,  uni_tari, can_tari, pes_unit, estatus_part)"
                                            . " VALUES($id_partidaNext, $id_factur, $cons_part, '$num_part',  '$desc_merc', '$pai_orig', '$uni_fact', $can_fact, $can_factr, $edo_corr,'$obs_frac','$cve_usua', '$inc_part',  $uni_tari, $can_tari, $pes_unit, 1) RETURNING id_partida";
                                        $save_part = $dbAdoP->Execute ( $sqlNewPart );
                                        $id_partida = $save_part->fields["id_partida"];
                                        
                                    } catch (Exception $ex) {
                                        $dbAdoP->rollbackTrans();
                                        $this->setSuccess(false);
                                        $this->setMessageText("Ocurrió un error al guardar la información de la partida." . $ex->getMessage());
                                        return "Ocurrió un error al guardar la información de la partida." . $ex->getMessage();
                                    }
                                

                                    if(count($product->series) > 0) {
                                        foreach ($product->series as $serie) {
                                            try {
                                                
                                                $cons_seri =  $serie->cons_seri;
                                                $num_part = trim($serie->num_part);
                                                $mar_merc =  $serie->mar_merc;
                                                $sub_mode = trim($serie->sub_mode);
                                                $num_serie = trim($serie->num_seri);

                                                if($num_serie == null){
                                                    $num_serie = '';
                                                }

                                                if($serie->num_part == null){
                                                    $serie->num_part = '';
                                                }
                                                
                                                if( $serie->mar_merc == null){
                                                     $serie->mar_merc = '';
                                                }
                                                
                                                if($serie->sub_mode == null){
                                                    $serie->sub_mode = '';
                                                }
                                                
                                                 $sqlNewSerie =  "INSERT INTO previo.cprevo_series(id_partida, cons_seri, num_part, mar_merc, sub_mode, num_seri)"
                                                    . " VALUES($id_partida, $cons_seri, '$num_part', '$mar_merc', '$sub_mode', '$num_serie') RETURNING id_series ";


                                                $save_serie= $dbAdoP->Execute ( $sqlNewSerie );
                                                $id_serie = $save_serie->fields["id_series"];
                                                
                                                //var_dump($id_serie);

                                            } catch (Exception $ex) {
                                                $dbAdoP->rollbackTrans();
                                                $this->setSuccess(false);
                                                $this->setMessageText("Ocurrió un error al guardar la información de la serie." . $ex->getMessage());
                                                return "Ocurrió un error al guardar la información de la serie." . $ex->getMessage();
                                            }  
                                        
                                        }
                                    }
                                        
                                    if(count($product->files) > 0) {
                                        foreach ($product->files as $file) {      
                                            $sqlFotos = "SELECT max(cons_foto) AS maximofoto FROM previo.cprevo_fotos WHERE id_partida = '$id_partida'";
                                            $maxConsecutiveFotos= $dbAdoP->Execute ( $sqlFotos );
                                            $maxConsecutivePartArray = json_decode(json_encode($maxConsecutiveFotos->fields), true);
                                            $maxPhotosPart = $maxConsecutivePartArray["maximofoto"] + 1;

                                            try {
                                                if($prevFlag == 2){
                                                    
                                                   $pathBasePart = "/files/EPrevious/" .$this->getIdclient() ."/". $this->getAduana()."_". $patente  . "/" . $this->getNum_refe() . "/" . $id_partida."/Fotos";
                                                    try {
                                                        $id_partida =  $id_partida;
                                                        $cons_foto = $maxPhotosPart;
                                                        $nom_foto =  $file->nom_foto;

                                                        $pathBasePart = $pathBasePart;
                                                        $sqlNewFoto=  "INSERT INTO previo.cprevo_fotos(id_partida, cons_foto, nom_foto, url_foto)"
                                                            . " VALUES($id_partida, $cons_foto, '$nom_foto', '$pathBasePart')";
                                                        $save_foto = $dbAdoP->Execute ( $sqlNewFoto );
                                                        $photo = new File($this->getIdclient(),  $this->getNum_refe(), $file->cons_foto, $file->nom_foto, $file->fileString, $this->getAduana(), $patente, $id_partida);
                                                        $photo->loadFilePartidaOperation();

                                                    } catch (Exception $ex) {
                                                        $dbAdoP->rollbackTrans();
                                                        $this->setSuccess(false);
                                                        $this->setMessageText("Ocurrió un error al guardar la información de las fotos a nivel partida." . $ex->getMessage());
                                                        return "Ocurrió un error al guardar la información de las fotos a nivel partida." . $ex->getMessage();
                                                    }  
                                                    

                                                } else {
                                                    $photo = new File($this->getIdclient(), $this->getNum_refe(), $file->cons_foto, $file->nom_foto, $file->fileString, $this->getAduana());
                                                    $photo->loadFileOperation();
                                                    $id_partida =  $id_partida;
                                                    $cons_foto = $maxPhotosPart;
                                                    $nom_foto =  $file->nom_foto;
                                                    $pathBasePart = $pathBasePart;

                                                    $sqlNewFoto=  "INSERT INTO previo.cprevo_fotos(id_partida, cons_foto, nom_foto)"
                                                        . " VALUES($id_partida, $cons_foto, '$nom_foto')";
                                                    $save_foto = $dbAdoP->Execute ( $sqlNewFoto );


                                                }
                                            } catch(Exception $e){
                                                throw new Exception("Ocurrió un error al insertar la foto: $file->nom_foto \n".$e->getMessage());
                                            }
                                        }
                                    }
                                    
                                    
                                }
                            }
                            
                        }
                        
                        $table = "previo.cprevo_refe CR";
                        $joins = "INNER JOIN previo.cprevo_previos P ON P.id_prev = CR.id_prev ";
                        $joins .= "INNER JOIN previo.cprevo_factur f on CR.id_prev = f.id_prev ";
                        $joins .= "INNER JOIN previo.cprevo_facpar fp on f.id_factur = fp.id_factur ";
                        $fieldsArray = array(
                            "CR.id_prev"
                        );
                        $parameters = "CR.id_prev = $idprev AND (fp.cve_usua = '')";
                        $responsePrevNoFinish = $db->query($table, $fieldsArray, $joins, $parameters);
                
                        if(count($responsePrevNoFinish) == 0){
                            $table = "previo.cprevo_refe";
                            $valuesArray = Array(
                                'estatus_refe' => 3
                            );
                            $params = "id_prev = $idprev";
                            $db->update($table, $valuesArray, $params);
                        }else {
                            $table = "previo.cprevo_refe";
                            $valuesArray = Array(
                                'estatus_refe' => 2
                            );
                            $params = "id_prev = $idprev";
                            $db->update($table, $valuesArray, $params);
                        }
                                    
                        
                        if($this->getIdclient() == 189 || $this->getIdclient() == 3698){
                            $this->sendmail(num_guia);
                        }
                        
                        $dbAdoP->commitTrans();     
                        $this->setSuccess(true);
                        $this->setMessageText("La operación es correcta");
                        return true;
                    } else {
                        
                        throw new Exception("La Referencia no Existe");
                    }
                   
                }
            
            } catch (Exception $e) {
                $this->setSuccess(false);
                $this->setMessageText("Error: " . $e->getMessage());
                return false;
            }
            
        }
    }
    
    
    
    
   public function sendmail() {
       
        $body = '<html><head><b>Solicitud de previo disponible</b></head><center><body>';
        $body .= "<p align='left'><b>REFERENCIA: </b>" . $this->getNum_refe(). "</p>";
        $body .= "<p align='left' ><b>DEPENDIENTE: </b>  " . $this->dep_asigna . "</p>";
        $body .= "<p align='left'><b>NOM. CLIENTE: </b>" .$this->nombre_importador . "</p>";
        $body .= "<p align='left' ><b>NÚM. DE GUÍA: </b>" . $this->num_guia."</p>"  ;

        $mail = new \PHPMailer();
        $mail->CharSet = 'UTF-8';
        $mail->IsSMTP();
        $mail->Mailer = "smtp";
        $mail->Host = "sistemascasa.com.mx";
        $mail->SMTPAuth = true;
        $mail->Username = "maileradmx@aduanas-mexico.com.mx";
        $mail->Password = "mailer4du4";
        $mail->From = 'maileradmx@aduanas-mexico.com.mx';
        $mail->Port = 587;
        $mail->FromName = 'Sistemas CASA';
        if($this->getIdclient() == 3698){
            $mail->AddAddress('meitra_opera@outlook.com'); 
            $mail->AddAddress('mijael.rangel@cranepi.com'); 
            $mail->AddAddress('oscar.resendiz@cranepi.com'); 
            $mail->AddAddress('Josue.Granados@cranepi.com'); 
        }else {
            $mail->AddAddress('aburela@sistemascasa.com.mx'); 
        }
      
        $mail->IsHTML(true);
        $mail->Subject = "Notificacion de previo disponible en Web. Cliente: " . $this->nombre_importador .", Referencia: " .$this->getNum_refe() ;
        $mail->Timeout = 3000;
        $mail->Body = $body;
        $mail->Send(); 
        
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
     * @return the $tot_bultr
     */
    public function getTot_bultr()
    {
        return $this->tot_bultr;
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
     * @return the $obs_prev
     */
    public function getObs_prev()
    {
        return $this->obs_prev;
    }
    
    /**
     * @return the $pes_brut
     */
    public function getPes_brut()
    {
        return $this->pes_brut;
    }
    
    /**
     * @return the $hora_inicio
     */
    public function getHora_inicio()
    {
        return $this->hora_inicio;
    }
    
    /**
     * @return the $hora_fin
     */
    public function getHora_fin()
    {
        return $this->hora_fin;
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
     * @return the $rfc_importador
     */
    public function getRfc_importador()
    {
        return $this->rfc_importador;
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
     * @return the $dep_asigna
     */
    public function getDep_asigna()
    {
        return $this->dep_asigna;
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
     * @return the $products
     */
    public function getProducts()
    {
        return $this->products;
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
     * @param number $tot_bultr            
     */
    public function setTot_bultr($tot_bultr)
    {
        $this->tot_bultr = $tot_bultr;
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
     * @param string $rfc_importador            
     */
    public function setRfc_importador($rfc_importador)
    {
        $this->rfc_importador = $rfc_importador;
    }

    /**
     *
     * @param number $rec_fisc            
     */
    public function setRec_fisc($rec_fisc)
    {
        $this->rec_fisc = $rec_fisc;
    }

    /**
     *
     * @param number $num_guia            
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
     * @param number $dep_asigna            
     */
    public function setDep_asigna($dep_asigna)
    {
        $this->dep_asigna = $dep_asigna;
    }

    /**
     *
     * @param number $obs_prev            
     */
    public function setObs_prev($obs_prev)
    {
        $this->obs_prev = $obs_prev;
    }
    
    /**
     * @param number $pes_brut
     */
    public function setPes_brut($pes_brut)
    {
        $this->pes_brut = $pes_brut;
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
     *
     * @param Contenedores[] $contenedores            
     */
    public function setContenedores($contenedores)
    {
        $this->contenedores = $contenedores;
    }

    /**
     *
     * @param Bultos[] $bultos            
     */
    public function setBultos($bultos)
    {
        $this->bultos = $bultos;
    }

    /**
     *
     * @param Ordcompras[] $ordcompras            
     */
    public function setOrdcompras($ordcompras)
    {
        $this->ordcompras = $ordcompras;
    }

    /**
     *
     * @param File[] $files            
     */
    public function setFiles($files)
    {
        $this->files = $files;
    }

    /**
     *
     * @param ProductDetails[] $products            
     */
    public function setProducts($products)
    {
        $this->products = $products;
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
     * @return the $nextIdPrevio
     */
    public function getNextIdPrevio()
    {
        return $this->nextIdPrevio;
    }
    
    /**
     * @param number $nextIdPrevio
     */
    public function setNextIdPrevio($nextIdPrevio)
    {
        $this->nextIdPrevio = $nextIdPrevio;
    }
    /**
     * @return the $ins_prev
     */
    public function getIns_prev()
    {
        return $this->ins_prev;
    }

    /**
     * @param string $ins_prev
     */
    public function setIns_prev($ins_prev)
    {
        $this->ins_prev = $ins_prev;
    }

    
    
}

?>