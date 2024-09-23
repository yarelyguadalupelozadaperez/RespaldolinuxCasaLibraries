<?php

/**
  * CasaLibraries AddPreviousNoReference
  * File AddPreviousNoReference.php
  * AddPreviousNoReference Class
  *
  * @category        CasaLibraries
  * @package            CasaLibraries_Previo
  * @copyright          Copyright (c) 2005-2015 Sistemas CASA, S.A. de 
C.V. sistemascasa.com.mx
  * @author            SMV
  * @version            Previo 1.0.0
  */

require_once 'Contenedores.php';
require_once 'Bultos.php';
require_once 'Ordcompras.php';
require_once 'File.php';
require_once 'Invoice.php';
require_once 'CasaLibraries/CasaDb/ConnectionFactory.class.php';


class AddPreviousNoReference
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
    public $patente;

    /**
     *
     * @var string
     */
    public $rfc_impo;

    /**
     *
     * @var string
     */
    private $num_refe;

    /**
     *
     * @var string
     */
    public $fec_soli;

    /**
     *
     * @var integer
     */
    private $fol_soli;

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
    * @var \File[]
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
     *
     * @var integer
     */
    private $nextIdPrevio;



    /**
    * Constructor of class
    *
    * @param integer $idclient
    * @param string $aduana
    * @param string $patente
    * @param string $rfc_impo
    * @param string $fec_soli
    * @param integer $tot_bult
    * @param integer $tot_bultr
    * @param string $rec_fisc
    * @param string $num_guia
    * @param string $edo_prev
    * @param string $ins_prev
    * @param string $dep_asigna
    * @param string $obs_prev
    * @param double $pes_brut
    * @param string $hora_inicio
    * @param string $hora_fin
    */
     
    public function __construct($idclient, $aduana, $patente, $rfc_impo, $fec_soli, $tot_bult, $tot_bultr, $rec_fisc, $num_guia, $edo_prev, $ins_prev, $dep_asigna, $obs_prev, $pes_brut, $hora_inicio, $hora_fin, $contenedores, $bultos, $ordcompras, $files, $invoices)
    {
        $this->setIdclient($idclient);
        $this->setAduana($aduana);
        $this->setPatente($patente);
        $this->setRfc_impo($rfc_impo);
        $this->setNum_refe();
        $this->setFec_soli($fec_soli);
        $this->setFol_soli(-1);
        $this->setTot_bult($tot_bult);
        $this->setTot_bultr($tot_bultr);
        $this->setRec_fisc($rec_fisc);
        $this->setNum_guia($num_guia);
        $this->setEdo_prev($edo_prev);
        $this->setIns_prev($ins_prev);
        $this->setDep_asigna($dep_asigna);
        $this->setObs_prev($obs_prev);
        $this->setPes_brut($pes_brut);
        $this->setHora_fin($hora_fin);
        $this->setHora_inicio($hora_inicio);
        $this->setContenedores($contenedores);
        $this->setBultos($bultos);
        $this->setOrdcompras($ordcompras);
        $this->setFiles($files);
        $this->setInvoices($invoices);
    }

    /**
     * This method adds all the information about previous in data base
     */
    public function addInDataBase() {
        $dbAdoP = ConnectionFactory::Connectpostgres();

        if ($this->getIdclient() == '' || $this->getAduana() == '' || $this->getRfc_impo() == "" || $this->getNum_refe() == "")
            throw new Exception("Datos incompletos");
            $db = new PgsqlQueries();

            $db->setTable('"general".casac_aduanas');
            $db->setFields(array(
                'id_aduana'
            ));

            $db->setParameters("clave_aduana = '" . $this->getAduana() . "'");
            $db->setReturnType(PgsqlQueries::TYPE_OBJECT_ONE);
            $response = $db->query();

            $id_aduana = $response->id_aduana;

            if ($id_aduana <= 0) {
                 throw new Exception("La aduana no se encuentra dada de alta");
            }

            if (empty( $this->getPatente())) {
                $patente = '4444';
            } else {
                $patente = $this->getPatente();
            }

            $db->setTable('"general".casag_licencias');
            $db->setFields(array(
                'id_licencia',
                'status_licencia'
            ));

            $db->setParameters("id_cliente = " . $this->getIdclient() . "AND id_aduana = $id_aduana AND patente = " . "'$patente'");
            $license = $db->query();
            $licenseId = $license->id_licencia;

            if ($licenseId > 0) {

                if($license->status_licencia == 0){
                    throw new Exception("La licencia del cliente se encuentra inactiva");
                }

                $db->setTable('"general".casac_importadores');
                $db->setFields(array(
                    'id_importador',
                    'nombre_importador',
                    'status_importador'
                ));

                $db->setParameters("id_cliente = " . $this->getIdclient() . " AND rfc_importador = '" . $this->getRfc_impo() . "'");
                $importer = $db->query();

                $idImporter = $importer->id_importador;
                $this->nombre_importador = $importer->nombre_importador;
            } else {
                throw new Exception("La licencia del cliente no se encuentra dada de alta");
            }


            if ($idImporter > 0) {

                if($importer->status_importador == 0){
                    throw new Exception("El importador se encuentra inactivo");
                }

            } else {
                throw new Exception("El importador no se encuentra registrado");
            }


            try {

            $depasign = $this->getDep_asigna();
            $sqlSearchReference = "SELECT id_prev FROM previo.cprevo_previos WHERE num_guia = '" . $this->getNum_guia() . "'AND id_importador =  $idImporter AND dep_asigna = '" . $depasign . "'AND hora_fin = '" . $this->getHora_fin() . "'";
            $activePrev= $dbAdoP->Execute ( $sqlSearchReference );
            $idActivePrev= json_decode(json_encode($activePrev->fields), true);

            if(isset($idActivePrev["id_prev"])){
                $this->setSuccess(true);
                return "La operación es correcta";
            } else {
                $dbAdoP->beginTrans();
                
                try {
                    $num_refe = $this->getNum_refe();

                    $sqlNewReference = "INSERT INTO previo.cprevo_refe(id_licencia, num_refe, estatus_refe)" . " VALUES($licenseId, '$num_refe', 3) RETURNING id_prev";
                    $saveReference = $dbAdoP->Execute ( $sqlNewReference );
                    $id_prev = $saveReference->fields["id_prev"];

                } catch (Exception $ex) {
                    $dbAdoP->rollbackTrans();
                    $this->setSuccess(false);
                    return "Ocurrió un error al guardar la referencia.";
                }

                try {
                    $referenceUpdate = 'TABLET_W' . $id_prev;
                    $sqlUpdateReference = "UPDATE previo.cprevo_refe" . " SET num_refe = '$referenceUpdate' WHERE id_prev = $id_prev";
                    $updateReference = $dbAdoP->Execute ( $sqlUpdateReference );

                } catch (Exception $ex) {
                    $dbAdoP->rollbackTrans();
                    $this->setSuccess(false);
                    return "Ocurrió un error al guardar la referencia." . $ex->getMessage();
                }

                if ($id_prev > 0) {
                    try {
                        $id_importador = $idImporter;
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
                        $status_prev  = 2;

                        $sqlNewPrev= "INSERT INTO previo.cprevo_previos(id_prev, id_importador, fec_soli, fol_soli, tot_bult, tot_bultr, rec_fisc,  num_guia,edo_prev,ins_prev, dep_asigna, obs_prev, pes_brut, hora_inicio, hora_fin, flag_version, flag_update)"
                        . " VALUES($id_prev, $id_importador, '$fec_soli', '$fol_soli', $tot_bult, $tot_bultr, '$rec_fisc', '$num_guia','$edo_prev','$ins_prev', '$dep_asigna', '$obs_prev', $pes_brut, '$hora_inicio', '$hora_fin', $flag_version, 1)";

                        $save_previo= $dbAdoP->Execute ( $sqlNewPrev );

                    } catch (Exception $ex) {
                        $dbAdoP->rollbackTrans();
                        $this->setSuccess(false);
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
                            $numero_candado5= $value->numero_candado5;
                            $obs_cont= $value->obs_cont;
                            $sqlNewContent= "INSERT INTO previo.cop_conten(id_prev, id_tipcon, numero_contenedor,numero_candado1, numero_candado2, numero_candado3,numero_candado4, numero_candado5, obs_cont )"
                                . " VALUES($id_prev, $contentId, '$numero_contenedor', '$numero_candado1', '$numero_candado2', '$numero_candado3','$numero_candado4', '$numero_candado5', '$obs_cont')";
                            $save_content = $dbAdoP->Execute ( $sqlNewContent );


                        } catch (Exception $ex) {
                            $dbAdoP->rollbackTrans();
                            $this->setSuccess(false);
                            return "Ocurrió un error al guardar la información de los contenedores" . $ex->getMessage();
                        }
                    }


                    foreach ($this->getBultos() as $key => $value) {
                        if ($this->getNextIdPrevio() > 0) {

                            $db->setTable('"general".casac_bultos');
                            $db->setFields(array(
                                'id_bulto'
                            ));
                            $db->setParameters("clave_bulto = '" . $value->clave_bulto . "' AND id_cliente = -1");
                            $packageId = $db->query();

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
                            return "Ocurrió un error al guardar la información de las ordenes de compra." . $ex->getMessage();
                        }

                    }

                    foreach ($this->getFiles() as $key => $value) {

                        try {
                            $pathBase = 'files/EPrevious/' . $this->getIdclient() .'/'. $this->getAduana().'_'. $patente . '/' . $referenceUpdate . '/Fotos' . '/'.$value->nom_foto;

                            $cons_foto = $value->cons_foto;
                            $nom_foto =  $value->nom_foto;

                            $sqlNewFotoPrev =  "INSERT INTO previo.cprevo_fotop(id_prev, cons_foto, nom_foto, url_foto)"
                                . " VALUES($id_prev, $cons_foto, '$nom_foto', '$pathBase')";
                            $save_fotos = $dbAdoP->Execute ( $sqlNewFotoPrev );


                            $photo = new File($this->getIdclient(), $referenceUpdate, $value->cons_foto, $value->nom_foto, $value->fileString, $this->getAduana(), $patente, null);
                            $response = $photo->loadFilePrevioOperation();

                        } catch (Exception $ex) {

                            $dbAdoP->rollbackTrans();
                            $this->setSuccess(false);
                            return "Ocurrió un error al guardar la información de las fotos a nivel previo." . $ex->getMessage();
                        }
                    }

                    foreach ($this->getInvoices() as $key => $value) {
                        try {

                            $cons_fact =  $value->cons_fact;
                            $num_fact = trim($value->num_fact);

                            $sqlNewInvoice = "INSERT INTO previo.cprevo_factur(id_prev, cons_fact, num_fact)"
                            . " VALUES($id_prev, $cons_fact, '$num_fact') RETURNING id_factur";
                            $save_factur = $dbAdoP->Execute ( $sqlNewInvoice );

                            $id_factur = $save_factur->fields["id_factur"];

                        } catch (Exception $ex) {
                            $dbAdoP->rollbackTrans();
                            $this->setSuccess(false);
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
                                
                                $sqlNewPart =  "INSERT INTO previo.cprevo_facpar(id_partida, id_factur, cons_part, num_part, desc_merc, pai_orig, uni_fact, can_fact, can_factr, edo_corr,obs_frac,cve_usua, inc_part,  uni_tari, can_tari, pes_unit)"
                                    . " VALUES($id_partidaNext, $id_factur, $cons_part, '$num_part',  '$desc_merc', '$pai_orig', '$uni_fact', $can_fact, $can_factr, $edo_corr,'$obs_frac','$cve_usua', '$inc_part',  $uni_tari, $can_tari, $pes_unit) RETURNING id_partida";
                                $save_part = $dbAdoP->Execute ( $sqlNewPart );
                                $id_partida = $save_part->fields["id_partida"];
                                
                            } catch (Exception $ex) {
                                $dbAdoP->rollbackTrans();
                                $this->setSuccess(false);
                                return "Ocurrió un error al guardar la información de la partida." . $ex->getMessage();
                            }

                            if(count($product->series) > 0) {
                                foreach ($product->series as $serie) {
                                    try {
                                        $cons_seri = $serie->cons_seri;
                                        $num_part = trim($serie->num_part);
                                        $mar_merc = $serie->mar_merc;
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
                                        return "Ocurrió un error al guardar la información de la serie." . $ex->getMessage();
                                    }

                                }
                            }

                            if(count($product->files) > 0) {
                                foreach ($product->files as $file) {
                                    try {
                                        $id_partida = $id_partida;
                                        $cons_foto = $file->cons_foto;
                                        $nom_foto = $file->nom_foto;
                                        $pathBasePart = "/files/EPrevious/" .$this->getIdclient() ."/". $this->getAduana()."_". $patente  . "/" . $referenceUpdate . "/" . $id_partida."/Fotos";


                                        $sqlNewFoto=  "INSERT INTO previo.cprevo_fotos(id_partida, cons_foto, nom_foto, url_foto)"
                                            . " VALUES($id_partida, $cons_foto, '$nom_foto', '$pathBasePart')";
                                        $save_foto = $dbAdoP->Execute ( $sqlNewFoto );

                                        $photo = new File($this->getIdclient(), $referenceUpdate, $file->cons_foto, $file->nom_foto, $file->fileString, $this->getAduana(), $patente, $id_partida);
                                        $photo->loadFilePartidaOperation();

                                    } catch (Exception $ex) {
                                        $dbAdoP->rollbackTrans();
                                        $this->setSuccess(false);
                                        return "Ocurrió un error al guardar la información de las fotos a nivel partida." . $ex->getMessage();
                                    }
                                }
                            }

                        }

                    }
                    $dbAdoP->commitTrans();

                    $this->setSuccess(true);
                    return "La operación es correcta";
                } else {
                    $this->setSuccess(false);
                    return "No se pudo agregar la referencia.";
                }

                if($this->getIdclient() == 189 || $this->getIdclient() == 3698){
                     $this->sendmail();
                }
                $this->setSuccess(true);
                return "La operación es correcta";
            }


        } catch (Exception $e) {
            $this->setSuccess(false);
            return $e->getMessage();
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
            $mail->AddAddress('ylozada@sistemascasa.com.mx');
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
     * @return the $patente
     */
    public function getPatente()
    {
        return $this->patente;
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
     * @return the $dep_asigna
     */
    public function getDep_asigna()
    {
        return $this->dep_asigna;
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
     * @param string patente
     */
    public function setPatente($patente)
    {
        $this->patente = $patente;
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
    public function setNum_refe()
    {
        $db = new PgsqlQueries();
        $db->setSql('SELECT nextval(\'"previo".cprevo_refe_id_prev_seq\'::regclass)    ');
        $nextId = $db->execute();
        $lastId = $nextId[0]["nextval"];

        $this->setNextIdPrevio($lastId);

        $newReference = 'TABLET_W' . $lastId;

        $this->num_refe = $newReference;
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
     * @param string $edo_prev
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
     * @param string $dep_asigna
     */
    public function setDep_asigna($dep_asigna)
    {
        $this->dep_asigna = $dep_asigna;
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
     * @return the $success
     */
    public function getSuccess()
    {
        return $this->success;
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
     * @return the $contenedores
     */
    public function getContenedores()
    {
        return $this->contenedores;
    }

    /**
     * @param multitype:Contenedores  $contenedores
     */
    public function setContenedores($contenedores)
    {
        $this->contenedores = $contenedores;
    }

    /**
     * @return the $bultos
     */
    public function getBultos()
    {
        return $this->bultos;
    }

    /**
     * @param multitype:Bultos  $bultos
     */
    public function setBultos($bultos)
    {
        $this->bultos = $bultos;
    }

    /**
     * @return the $ordcompras
     */
    public function getOrdcompras()
    {
        return $this->ordcompras;
    }

    /**
     * @param multitype:Ordcompras  $ordcompras
     */
    public function setOrdcompras($ordcompras)
    {
        $this->ordcompras = $ordcompras;
    }

    /**
     * @return the $files
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param multitype:File  $files
     */
    public function setFiles($files)
    {
        $this->files = $files;
    }
    /**
     * @return the $invoices
     */
    public function getInvoices()
    {
        return $this->invoices;
    }

    /**
     * @param multitype:Invoice  $invoices
     */
    public function setInvoices($invoices)
    {
        $this->invoices = $invoices;
    }

    /**
     * @return the $pes_brut
     */
    public function getPes_brut()
    {
        return $this->pes_brut;
    }


    /**
     * @param number $pes_brut
     */
    public function setPes_brut($pes_brut)
    {
        $this->pes_brut = $pes_brut;
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




}

?>