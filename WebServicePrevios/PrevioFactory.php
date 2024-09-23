<?php

/**
 * CasaLibraries Previo
 * File previoFactory.php
 * Previo Factory Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			SMV
 * @version    		Previo 1.0.0
 */


require_once 'CasaLibraries/WebServicePrevios/AddPrevious.php';
require_once 'CasaLibraries/WebServicePrevios/Response.php';
require_once 'CasaLibraries/WebServicePrevios/ResponseDownload.php';
require_once 'CasaLibraries/WebServicePrevios/ResponseDownloadObject.php';
require_once 'CasaLibraries/WebServicePrevios/Credentials.php';
require_once 'CasaLibraries/WebServicePrevios/DownloadPrevious.php';
require_once 'CasaLibraries/WebServicePrevios/DataDownPrevios.php';
require_once 'CasaLibraries/CasaDb/PgsqlConnection.php';
require_once 'CasaLibraries/CasaDb/PgsqlQueries.php';
require_once 'CasaLibraries/WebServicePrevios/LoadData.php';
require_once 'CasaLibraries/WebServicePrevios/DownloadData.php';
require_once 'CasaLibraries/WebServicePrevios/DataDown.php';
require_once 'CasaLibraries/WebServicePrevios/AddPreviousNoReference.php';
require_once 'CasaLibraries/WebServicePrevios/GetDataPrevious.php';
require_once 'CasaLibraries/WebServicePrevios/ResponsePrevious.php';
require_once 'CasaLibraries/WebServicePrevios/PendingPrevious.php';
require_once 'CasaLibraries/WebServicePrevios/PendingDownloadObject.php';
require_once 'CasaLibraries/WebServicePrevios/PrevioResponseDownloadObject.php';
require_once 'CasaLibraries/WebServicePrevios/ChangePrevious.php';
require_once 'CasaLibraries/WebServicePrevios/DownloadPreviousList.php';
require_once 'CasaLibraries/WebServicePrevios/DownloadPreviousListObject.php';
require_once 'CasaLibraries/WebServicePrevios/ChangesFieldDependient.php';
require_once 'CasaLibraries/WebServicePrevios/AddImporter.php';
require_once 'CasaLibraries/WebServicePrevios/DeleteImporter.php';
require_once 'CasaLibraries/WebServicePrevios/DataDownImporters.php';
require_once 'CasaLibraries/WebServicePrevios/DownloadImporters.php';
require_once 'CasaLibraries/WebServicePrevios/ResponseImporters.php';
require_once 'CasaLibraries/WebServicePrevios/ImportersDown.php';
require_once 'CasaLibraries/WebServicePrevios/DeleteReference.php';
require_once 'CasaLibraries/WebServicePrevios/MarkDownloadedPrevious.php';
require_once 'CasaLibraries/WebServicePrevios/AddDependient.php';
require_once 'CasaLibraries/WebServicePrevios/UpdateDataPart.php';
require_once 'CasaLibraries/WebServicePrevios/Invoice2.php';
require_once 'CasaLibraries/WebServicePrevios/ProductsUpdate.php';
require_once 'CasaLibraries/WebServicePrevios/NewFracpar.php';
require_once 'CasaLibraries/WebServicePrevios/CtracFracpar.php';
require_once 'CasaLibraries/WebServicePrevios/DeleteFracpar.php';
require_once 'CasaLibraries/WebServicePrevios/StatusDownloadPrev.php';
require_once 'CasaLibraries/WebServicePrevios/ResponseCtracfracpar.php';
require_once 'CasaLibraries/WebServicePrevios/AddProvider.php';
require_once 'CasaLibraries/WebServicePrevios/DeleteProvider.php';
require_once 'CasaLibraries/WebServicePrevios/UpdateFracpar.php';


class PrevioFactory {


    /**
     * This method adds new Previous and returns an Object Response
     *
     * @param AddPrevious $previous
     * @return Response
     */ 
    public function newPrevious($previous) {

        
        try {
         
            $previous = (object) $previous;
            $idclient = $previous->idclient;
            $aduana = $previous->aduana;
            $patente = $previous->patente;
            $rfc_impo = $previous->rfc_impo;
            $cve_impo = $previous->cve_impo;
            $num_refe = $previous->num_refe;
            $fec_soli = $previous->fec_soli;
            $fol_soli = $previous->fol_soli;
            $tot_bult = $previous->tot_bult;
            $tot_bultr = $previous->tot_bultr;
            $rec_fisc = $previous->rec_fisc;
            $num_guia = $previous->num_guia;
            $edo_prev = $previous->edo_prev;
            $ins_prev = $previous->ins_prev;
            $dep_asigna = $previous->dep_asigna;
            $obs_prev = $previous->obs_prev;
            $pes_brut = $previous->pes_brut;
            $tip_refe =   $previous->tip_refe;
            $tip_ope =   $previous->tip_ope;
            $cve_impo =   $previous->cve_impo;
            $contenedores = $previous->contenedores;
            $bultos = $previous->bultos;
            $ordcompras = $previous->ordcompras;
            $invoices1 = $previous->invoice1;
            $previousObject = new AddPrevious($idclient, $aduana, $patente, $rfc_impo,  $num_refe, $fec_soli, $fol_soli, $tot_bult, $tot_bultr, $rec_fisc, $num_guia, $edo_prev, $ins_prev, $dep_asigna, $obs_prev, $pes_brut, $tip_refe, $tip_ope,$cve_impo, $contenedores, $bultos, $ordcompras, $invoices1);
            
            $response = $previousObject->addInDataBase();
            return new Response($previousObject->getSuccess(), $response);
        } catch (Exception $e) {
            return new Response(false, $e->getMessage());
        }
    }
  
    /**
     * This method download the new Previous and returns an Object Response
     * @param DownloadPrevious $downprevious
     * @return PrevioResponseDownloadObject
     */
    public function downloadPrevious($downprevious) {
        try {
            $downprevious = (object) $downprevious;
            $idprevious = $downprevious->idprevious;
            $nom_movil = $downprevious->nom_movil;
            
            $downPreviousObject = new DownloadPrevious($idprevious, $nom_movil);
            $response = $downPreviousObject->downDataPrevios();
            
            return new PrevioResponseDownloadObject($downPreviousObject->getSuccess(), $downPreviousObject->getMessageText(), $downPreviousObject->getDataDownPrevios());
        } catch (Exception $e) {
            return new PrevioResponseDownloadObject(false, $e->getMessage(), null);
        }
    }
    
    /**
     * This method load data from movil to the web service and returns an Object Response
     *
     * @param LoadData $dataload
     * @return Response
     */
    public function loadData($dataload) {
        try {
            $dataload = (object) $dataload;
            $idclient = $dataload->idclient;
            $rfc_importador = $dataload->rfc_importador;
            $aduana = $dataload->aduana;
            $num_refe = $dataload->num_refe;
            $fec_soli = $dataload->fec_soli;
            $fol_soli = $dataload->fol_soli;
            $tot_bultr = $dataload->tot_bultr;
            $rec_fisc = $dataload->rec_fisc;
            $num_guia = $dataload->num_guia;
            $edo_prev = $dataload->edo_prev;
            $dep_asigna = $dataload->dep_asigna;
            $obs_prev = $dataload->obs_prev;
            $pes_brut = $dataload->pes_brut;
            $hora_inicio = $dataload->hora_inicio;
            $hora_fin = $dataload->hora_fin;
            $contenedores = $dataload->contenedores;
            $bultos = $dataload->bultos;
            $ordcompras = $dataload->ordcompras;
            //$files = $dataload->files;
            $files = null;
            $invoices = $dataload->invoices;
            $tot_bult = $dataload->tot_bult;
            $ins_prev = $dataload->ins_prev;
            $patente = $dataload-> patente;
            
            
            $LoadDataObject = new LoadData($idclient, $rfc_importador, $aduana, $num_refe, $fec_soli, $fol_soli, $tot_bultr, $rec_fisc, $num_guia, $edo_prev, $dep_asigna, $obs_prev, $pes_brut, $hora_inicio, $hora_fin, $contenedores, $bultos, $ordcompras, $files, $invoices, $tot_bult, $ins_prev, $patente);
            $response = $LoadDataObject->dataLoad();
            return new Response($LoadDataObject->getSuccess(), $LoadDataObject->getMessageText());
        } catch (Exception $e) {
            return new Response(false, $e->getMessage());
        }
    }
    
    /**
     * This method download data of Previous and its photos and returns an Object Response
     *
     * @param DownloadData $downdata
     * @return ResponseDownloadObject
     */
    public function downloadData($downdata) {
        try {
            $downdata = (object) $downdata;
            $idclient = $downdata->idclient;
            $rfc_importador = $downdata->rfc_importador;
            $aduana = $downdata->aduana;
            $num_refe = $downdata->num_refe;
            
            $downDataObject = new DownloadData($idclient, $rfc_importador, $aduana, $num_refe);
            $response = $downDataObject->downDataFoto();
            
            return new ResponseDownloadObject($downDataObject->getSuccess(), $downDataObject->getMessageText(), $downDataObject->getDataDown());
        } catch (Exception $e) {
            return new Response(false, $e->getMessage());
        }
    }
    
    /**
     * This method upload the data Previous and photos without a reference and returns an Object Response
     *
     * @param AddPreviousNoReference $previous
     * @return Response
     */
    public function AddPreviousNoReference($previous) {
        try {
            $previous = (object) $previous;
            $idclient = $previous->idclient;
            $aduana = $previous->aduana;
            $patente = $previous->patente;
            $rfc_impo = $previous->rfc_impo;
            $fec_soli = $previous->fec_soli;
            $tot_bult = $previous->tot_bult;
            $tot_bultr = $previous->tot_bultr;
            $rec_fisc = $previous->rec_fisc;
            $num_guia = $previous->num_guia;
            $edo_prev = $previous->edo_prev;
            $ins_prev = $previous->ins_prev;
            $dep_asigna = $previous->dep_asigna;
            $obs_prev = $previous->obs_prev;
            $pes_brut = $previous->pes_brut;
            $hora_inicio = $previous->hora_inicio;
            $hora_fin = $previous->hora_fin;
            $contenedores = $previous->contenedores;
            $bultos = $previous->bultos;
            $ordcompras = $previous->ordcompras;
            //$files = $previous->files;
            $files = null;
            $invoices = $previous->invoices;
            
            $previousObject = new AddPreviousNoReference($idclient, $aduana, $patente, $rfc_impo, $fec_soli, $tot_bult, $tot_bultr, $rec_fisc, $num_guia, $edo_prev, $ins_prev, $dep_asigna, $obs_prev, $pes_brut, $hora_inicio, $hora_fin, $contenedores, $bultos, $ordcompras, $files, $invoices);
            
            $response = $previousObject->addInDataBase();
            
            return new Response($previousObject->getSuccess(), $response);
        } catch (Exception $e) {
            return new Response(false, $e->getMessage());
        }
    }
    
    /**
     * This method get the files pending.
     *
     * @param PendingPrevious $pendinprevious
     * @return PendingDownloadObject
     */
    public function pendingPrevious($pendinprevious)
    {
        try {
            
            $pendinprevious = (object) $pendinprevious;
            $idclient = $pendinprevious->idclient;
            $aduana = $pendinprevious->aduana;
            $patente = $pendinprevious->patente;
            $idgdb = $pendinprevious->idgdb;
            $flag = $pendinprevious->flag;
            $tip_prev = $pendinprevious->tip_prev;
            
            $pendigFilesObject = new PendingPrevious($idclient, $aduana,  $idgdb, $patente, $flag, $tip_prev);
            $response = $pendigFilesObject->pendingFilesData();
            
            return new PendingDownloadObject(true, "Operación exitosa.",$pendigFilesObject->pendingFilesData());
            
        } catch (Exception $e) {
            
            return new PendingDownloadObject(false, $e->getMessage() . '');
        }
    }
    
    
    /**
     * This method recover the datas of pending previous
     *
     * @param GetDataPrevious $previous
     * @return ResponsePrevious
     */
    public function GetDataPrevious($previous) {
        try {
            $previous = (object) $previous;
            $idprevious = $previous->idprevious;
            $idgdb = $previous->idgdb;
            $addDownload = $previous->addDownload;
            
            $downPreviousObject = new GetDataPrevious($idprevious, $idgdb, $addDownload);
            
            $response = $downPreviousObject->getDataPrevious();
            $response1 = new ResponsePrevious($downPreviousObject->getSuccess(), $downPreviousObject->getMessageText(), $response);

            return new ResponsePrevious($downPreviousObject->getSuccess(), $downPreviousObject->getMessageText(), $response);
        } catch (Exception $e) {
            return new ResponsePrevious(false, $e->getMessage(), '');
        }
    }
    
    /**
     * This method update the previous load with tablet
     *
     * @param ChangePrevious $previous
     * @return Response
     */
    public function ChangePrevious($previous) {
        try {
            $previous = (object) $previous;
            $num_refeTemp = $previous->num_refeTemp;
            $num_refe = $previous->num_refe;
            
            $downPreviousObject = new ChangePrevious($num_refeTemp, $num_refe);
            
            $response = $downPreviousObject->getChangePrevious();
            
            return new Response($downPreviousObject->getSuccess(), $downPreviousObject->getMessageText());
        } catch (Exception $e) {
            return new Response(false, $e->getMessage());
        }
    }
    
    /**
     * This method get the previous pendings.
     *
     * @param DownloadPreviousList $downloadpreviouslist
     * @return DownloadPreviousListObject
     */
    public function DownloadPreviousList($downloadpreviouslist) {
        try {
            $downloadpreviouslist = (object) $downloadpreviouslist;
            $idclient = $downloadpreviouslist->idclient;
            $aduana = $downloadpreviouslist->aduana;
            $nom_movil = $downloadpreviouslist->nom_movil;
            $dep_asigna = $downloadpreviouslist->dep_asigna;
            
            $downloadPreviousList = new DownloadPreviousList($idclient, $aduana, $nom_movil, $dep_asigna);
            $response = $downloadPreviousList->downloadPreviousListData();
            return new DownloadPreviousListObject($downloadPreviousList->getSuccess(), $downloadPreviousList->getMessageText(), $downloadPreviousList->downloadPreviousListData());
        } catch (Exception $e) {
            return new DownloadPreviousListObject(false, $e->getMessage() . '');
        }
    }
    
    /**
     * This method update the previous data
     *
     * @param ChangesFieldDependient $previousdata
     * @return Response
     */
    public function ChangesFieldDependient($previousdata) {
        try {
            $previousdata = (object) $previousdata;
            $num_refe = $previousdata->num_refe;
            $dep_asigna = $previousdata->dep_asigna;
            $idclient = $previousdata->idclient;
            
            $previousObject = new ChangesFieldDependient($num_refe, $dep_asigna, $idclient);
            
            $response = $previousObject->changefieldDependient();
            
            return new Response($previousObject->getSuccess(), $previousObject->getMessageText());
        } catch (Exception $e) {
            return new Response(false, $e->getMessage());
        }
    }
    
    /** Este metodo agrega un importador dependiendo las licencias adquiridas
     *
     * @param AddImporter $addImporter
     * @return Response
     */
    public function AddImporter($addImporter) {
        try {
            $addImporter = (object) $addImporter;
            $idclient = $addImporter->idclient;
            $rfc_importador = $addImporter->rfc_importador;
            $clave_import = $addImporter->clave_import;
            $nombre_import = $addImporter->nombre_import;
            $domicilio_import = $addImporter->domicilio_import;
            
            $addImportersObject = new AddImporter($idclient, $rfc_importador, $clave_import, $nombre_import, $domicilio_import);
            
            $response = $addImportersObject->getAddImporters();
            
            return new Response($addImportersObject->getSuccess(), $addImportersObject->getMessageText());
        } catch (Exception $e) {
            return new Response(false, $e->getMessage());
        }
    }
    
     /**
     * Este metodo elimina los importadores
     *
     * @param DeleteImporter $deleteImporter
     * @return Response
     */
    public function DeleteImporter($deleteImporter) {
        try {
            $deleteImporter = (object) $deleteImporter;
            $idcliente = $deleteImporter->idclient;
            $rfc_importador = $deleteImporter->rfc_importador;
            $clave_import = $deleteImporter->clave_import;
            
            $deleteImportersObject = new DeleteImporter($idcliente, $rfc_importador, $clave_import);
            
            $response = $deleteImportersObject->getDeleImporters();
            return new Response($deleteImportersObject->getSuccess(), $deleteImportersObject->getMessageText());
        } catch (Exception $e) {
            return new Response(false, $e->getMessage());
        }
    }
    
    /**
     * This method download the active importers
     *
     * @param DownloadImporters $importers
     * @return ResponseImporters
     */
    public function DownloadImporters($importers) {
        try {
            $importers = (object) $importers;
            $idcliente = $importers->idcliente;
            
            $downImportersObject = new DownloadImporters($idcliente);
            
            $response = $downImportersObject->getDataImporters();
            
            return new ResponseImporters($downImportersObject->getSuccess(), $downImportersObject->getMessageText(), $downImportersObject->getDataDownImporters());
        } catch (Exception $e) {
            return new ResponseImporters(false, $e->getMessage(), '');
        }
    }
    
    /**
     * Este metodo elimina las referencias cuando se cancelan en CTRAWIN
     *
     * @param DeleteReference $deleteReference
     * @return Response
     */
    public function DeleteReference($deleteReference) {
        try {
            $deleteReference = (object) $deleteReference;
            $idcliente = $deleteReference->idclient;
            $reference = $deleteReference->num_refe;
            $flag = $deleteReference->flag;
            
            $deleteReferenceObject = new DeleteReference($idcliente, $reference, $flag);
            
            $response = $deleteReferenceObject->getDeleteReference();
            return new Response($deleteReferenceObject->getSuccess(), $deleteReferenceObject->getMessageText());
        } catch (Exception $e) {
            return new Response(false, $e->getMessage());
        }
    }
    
    /**
     * Este método registra el previo como descargado
     *
     * @param MarkDownloadedPrevious $markDownloadedPrevious
     * @return Response
     */
    public function MarkDownloadedPrevious($markDownloadedPrevious) {
        try {
            $markDownloadedPrevious = (object) $markDownloadedPrevious;
            $idcliente = $markDownloadedPrevious->idclient;
            $reference = $markDownloadedPrevious->num_refe;
            $idgdb = $markDownloadedPrevious->idgdb;
            
            $markDownloadedPreviousObject = new MarkDownloadedPrevious($idcliente, $reference, $idgdb);
            
            $response = $markDownloadedPreviousObject->markDownloadedPrevious();
            return new Response($markDownloadedPreviousObject->getSuccess(), $markDownloadedPreviousObject->getMessageText());
        } catch (Exception $e) {
            return new Response(false, $e->getMessage());
        }
    }
    
    /** Este metodo agrega un dependiente
     *
     * @param AddDependient $addDependient
     * @return Response
     */
    public function AddDependient($addDependient) {
        try {
            $addDependient = (object) $addDependient;
            $alt_depe      = $addDependient->alt_depe;
            $cve_depe      = $addDependient->cve_depe;
            $des_depe      = $addDependient->des_depe;
            $gaf_depe      = $addDependient->gaf_depe;
            $nom_depe      = $addDependient->nom_depe;
            $rfc_depe      = $addDependient->rfc_depe;
            $usu_depe      = $addDependient->usu_depe;
            $usu_pass      = $addDependient->usu_pass;
            $clave_aduana  = $addDependient->clave_aduana;
            $id_cliente    = $addDependient->id_cliente;
            $patente       = $addDependient->patente;
            $vig_depe      = $addDependient->vig_depe;
            $ws_previo     = $addDependient->ws_previo;
            $capt_fracc    = $addDependient->capt_fracc;
            
            $addDependientObject = new AddDependient($id_cliente, $clave_aduana, $patente, $cve_depe, $nom_depe, $rfc_depe, $gaf_depe, $alt_depe, $des_depe, $usu_depe, $usu_pass, $vig_depe, $ws_previo, $capt_fracc);

            $response = $addDependientObject->getAddDependient();
            
            return new Response($addDependientObject->getSuccess(), $addDependientObject->getMessageText());
        } catch (Exception $e) {
            return new Response(false, $e->getMessage());
        }
    }
    
    /**
     * This method adds new Previous and returns an Object Response
     *
     * @param UpdateDataPart $udpartidas            
     * @return Response
     */
    public function updateDataPartidas($udpartidas) {
        try {
            $udpartidas = (object) $udpartidas;
            $idclient = $udpartidas->idclient;
            $aduana = $udpartidas->aduana;
            $patente = $udpartidas->patente;
            $num_refe = $udpartidas->num_refe;
            $obs_prev = $udpartidas->obs_prev;
            $invoice2 = $udpartidas->invoice2;


            $udpartidasObject = new UpdateDataPart($idclient, $aduana, $patente, $num_refe, $obs_prev , $invoice2);
            $response = $udpartidasObject->updateDataPartidas();
 
            return new Response($udpartidasObject->getSuccess(), $response);
        } catch (Exception $e) {
            return new Response(false, $e->getMessage());
        }
    }
    
/**
     * This method adds new Previous and returns an Object Response
     *
     * @param NewFracpar $nfpartidas
     * @return ResponseCtracfracpar
     */ 
    public function NewFracpar($nfpartidas) {
        try {
            $nfpartidas = (object) $nfpartidas;
            $idclient = $nfpartidas->idclient;
            $ctracfracpar = $nfpartidas->ctracfracpar;
            
            
            $nfpartidasObject = new NewFracpar($idclient, $ctracfracpar);

            $response = $nfpartidasObject->NewFracpar();
            return new ResponseCtracfracpar($nfpartidasObject->getSuccess(), $response,$nfpartidasObject->getCtracfracpar());
        } catch (Exception $e) {
            return new Response(false, $e->getMessage());
        }
    }
    
/**
     * This method adds DeleteFracpar and returns an Object Response
     *
     * @param DeleteFracpar $nfpartidas            
     * @return Response
     */
    public function DeleteFracpar($dfpartidas) {
        try {
            $dfpartidas = (object) $dfpartidas;
            $idclient = $dfpartidas->idclient;
            $ctracfracpar = $dfpartidas->ctracfracpar;


            $dfpartidasObject = new DeleteFracpar($idclient, $ctracfracpar);
            $response = $dfpartidasObject->DeleteFracpar();
            return new Response($dfpartidasObject->getSuccess(), $response);
        } catch (Exception $e) {
            return new Response(false, $e->getMessage());
        }
    }
    
/**
     * This method adds DeleteFracpar and returns an Object Response
     *
     * @param StatusDownloadPrev $previous            
     * @return Response
     */
    public function StatusDownloadPrev($previous) {
        try {
            $previous = (object) $previous;
            $idclient = $previous->idclient;
            $idprevious = $previous->idprevious;
            $idgdb = $previous->idgdb;
            $tip_prev = $previous->tip_prev;
            $id_proc = $previous->id_proc;
            $num_refe = $previous->num_refe;


            $sDownloadPrevObject = new StatusDownloadPrev($idclient, $idprevious, $idgdb, $tip_prev, $id_proc, $num_refe);
            $response = $sDownloadPrevObject->StatusDownloadPrev();
            return new Response($sDownloadPrevObject->getSuccess(), $response);
        } catch (Exception $e) {
            return new Response(false, $e->getMessage());
        }
    }

/** Este metodo agrega un importador dependiendo las licencias adquiridas
     *
     * @param AddProvider $addProvider
     * @return Response
     */
    public function AddProvider($addProvider) {
        try {
            $addProvider = (object) $addProvider;
            $cve_pro = $addProvider->cve_pro;
            $dir_pro = $addProvider->dir_pro;
            $imp_exp = $addProvider->imp_exp;
            $nom_pro = $addProvider->nom_pro;
            $pai_pro = $addProvider->pai_pro;
            $tax_pro = $addProvider->tax_pro;
            $tel_pro = $addProvider->tel_pro;
            $idclient = $addProvider->idclient;
            
            
            $addProvidersObject = new AddProvider($cve_pro, $dir_pro, $imp_exp, $nom_pro, $pai_pro ,$tax_pro ,$tel_pro, $idclient);
            
            $response = $addProvidersObject->getAddProviders();
            
            return new Response($addProvidersObject->getSuccess(), $addProvidersObject->getMessageText());
        } catch (Exception $e) {
            return new Response(false, $e->getMessage());
        }
    }

/**
     * Este metodo elimina los importadores
     *
     * @param DeleteProvider $deleteProvider
     * @return Response
     */
    public function DeleteProvider($deleteProvider) {
        try {
            $deleteProvider = (object) $deleteProvider;
            $idcliente = $deleteProvider->idclient;
            $cve_pro = $deleteProvider->cve_pro;
            $imp_exp = $deleteProvider->imp_exp;
            
            
            $deleteProvidersObject = new DeleteProvider($idcliente, $cve_pro, $imp_exp);
            
            $response = $deleteProvidersObject->getDeleteProviders();
            return new Response($deleteProvidersObject->getSuccess(), $deleteProvidersObject->getMessageText());
        } catch (Exception $e) {
            return new Response(false, $e->getMessage());
        }
    }


/**
     * Este metodo elimina los importadores
     *
     * @param UpdateFracpar $updateFracpar
     * @return Response
     */
    public function UpdateFracpar($updateFracpar) {
        try {
            $updateFracpar = (object) $updateFracpar;
            $idcliente = $updateFracpar->idclient;
            $id_fracpar = $updateFracpar->id_fracpar;
            $uni_fact = $updateFracpar->uni_fact;
            $desc_merc = $updateFracpar->desc_merc;
            $val_part = $updateFracpar->val_part;
            
            $dupdateFracparsObject = new UpdateFracpar($idcliente, $id_fracpar, $uni_fact, $desc_merc, $val_part);
            
            $response = $dupdateFracparsObject->UpdateFracpar();
            return new Response($dupdateFracparsObject->getSuccess(), $response);
        } catch (Exception $e) {
            return new Response(false, $e->getMessage());
        }
    }
    
}

?>
