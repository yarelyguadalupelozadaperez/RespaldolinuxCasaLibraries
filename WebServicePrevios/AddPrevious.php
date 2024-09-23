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
require_once 'Contenedores.php';
require_once 'Bultos.php';
require_once 'Ordcompras.php';
require_once 'Invoice1.php';
require_once 'CasaLibraries/CasaDb/ConnectionFactory.class.php';


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
    public $cve_impo;


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
     * @var integer
     */
    public $tip_refe;

        
    /**
     *
     * @var integer
     */
    public $tip_ope;


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
     * @var \Invoice1[]
     */
    public $invoice1;

    /**
     * @return string
     */
    public function getCve_impo()
    {

        return $this->cve_impo;
    }

    /**
     * @return \Invoice1[]
     */
    public function getInvoice1()
    {
     
        return $this->invoice1;
    }

    /**
     * @return boolean
     */
    public function isSuccess()
    {

        return $this->success;
    }

    /**
     * @param string $cve_impo
     */
    public function setCve_impo($cve_impo)
    {

        $this->cve_impo = $cve_impo;
    }

    /**
     * @param \Invoice1[] $invoice1
     */
    public function setInvoice1($invoice1)
    {
        $this->invoice1 = $invoice1;
    }
  
       
    public function __construct($idclient, $aduana, $patente, $rfc_impo,  $num_refe, $fec_soli, $fol_soli, $tot_bult, $tot_bultr, $rec_fisc, $num_guia, $edo_prev, $ins_prev, $dep_asigna, $obs_prev, $pes_brut, $tip_refe = null, $tip_ope, $cve_impo, $contenedores, $bultos, $ordcompras, $invoice1)

    {

        $this->setIdclient($idclient);
        $this->setAduana($aduana);
        $this->setPatente($patente);
        $this->setRfc_impo($rfc_impo);
        $this->setCve_impo($cve_impo);
        $this->setNum_refe($num_refe);
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
        $this->setPes_brut($pes_brut);
        $this->setTip_refe($tip_refe);
        $this->setTip_ope($tip_ope);
        $this->setCve_impo($cve_impo);
        $this->setContenedores($contenedores);
        $this->setBultos($bultos);
        $this->setOrdcompras($ordcompras);
        $this->setInvoices1($invoice1);
     
        
        
    }
    
    

    public function addInDataBase()
    {

        
        $dbAdoP = ConnectionFactory::Connectpostgres();

        if ($this->getIdclient() == '' || $this->getAduana() == '' || $this->getRfc_impo() == "" || $this->getNum_refe() == "")
            throw new Exception("Datos incompletos");
        
        $db = new PgsqlQueries();

       
        $db->setTable('general.casac_aduanas');
        $db->setJoins("");
        $db->setFields(array(
            'id_aduana'
        ));
        
        $db->setParameters("clave_aduana = '" . $this->getAduana() . "'");
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $response = $db->query();
        
        $id_aduana = $response[0]["id_aduana"];
        $patente = $this->getPatente();

        if ($id_aduana > 0) {
            
            $db->setTable('general.casag_licencias L');
            $db->setJoins("");
            $db->setFields(array(
                'L.id_licencia',
                'L.status_licencia'
            ));
            
            $db->setParameters("L.id_cliente = " . $this->getIdclient() . " AND L.id_aduana = " . $id_aduana . " AND L.patente = " . "'$patente'" . " ");
            $db->setReturnType(PgsqlQueries::TYPE_OBJECT_ONE);
            $response = $db->query();
            
            $id_licencia = $response->id_licencia;
            
            if($id_licencia > 0){
                $status = $response->status_licencia;
                if($status == 0){
                    throw new Exception("La licencia del cliente se encuentra inactiva");
                }
            } else {
                throw new Exception("La licencia del cliente no se encuentra dada de alta. ID Cliente" . $this->getIdclient() . " - Patente: " . $patente . " - Aduana: " . $this->getAduana() .  " - ID Aduana: " . $id_aduana. " - ID Licencia: " . $id_licencia . " - Estatus Licencia: ". $status);
            }

            $db->setTable('previo.cprevo_refe');
            $db->setJoins("");
            $db->setFields(array(
                'id_prev'
            ));
            
            $db->setParameters("id_licencia = '" . $id_licencia . "'  AND num_refe = '" . $this->getNum_refe() . "'");
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $response = $db->query();
        
            $id_prev = $response[0]["id_prev"];
            
            if ($id_prev <= 0) {
   
                $db->setTable('general.casac_importadores');
                $db->setFields(array(
                    'id_importador',
                    'status_importador',
                    'clave_importador'
                ));
                
                //$db->setParameters("id_cliente = " . $this->getIdclient() . " AND rfc_importador = '" . $this->getRfc_impo() . "'");
                $db->setParameters("id_cliente = " . $this->getIdclient() . " AND clave_importador = '" . $this->getCve_impo() . "'");
                $response = $db->query();


                if(count($response) > 0){
                    if(count($response) == 1){
                        $id_importador = $response[0]["id_importador"];

                    } else {
                        $db->setTable('general.casac_importadores');
                        $db->setFields(array(
                            'id_importador',
                            'status_importador',
                            'clave_importador'
                        ));
                        
                        $db->setParameters("id_cliente = " . $this->getIdclient() . " AND rfc_importador = '" . $this->getRfc_impo() . "'");
                        $response1 = $db->query();
                       
                        if(count($response1) == 0){
                            $id_importador = $response[0]["id_importador"];
                        } else {
                            $id_importador = $response1[0]["id_importador"];
                        }
                    }

                    
                } else {
                    
                    $db->setTable('general.casac_importadores');
                    $db->setFields(array(
                        'id_importador',
                        'status_importador',
                        'clave_importador'
                    ));
                    
                    $db->setParameters("id_cliente = " . $this->getIdclient() . " AND rfc_importador = '" . $this->getRfc_impo() . "'");
                    $response1 = $db->query();
                    
                    if(count($response1) == 0){
                        throw new Exception("El importador no se encuentra dado de alta.");
                    } else {
                        $id_importador = $response1[0]["id_importador"];
                    }
                    
                  
                }

                
  
               

            } else {
                throw new Exception("Ya Existe una Referencia con ese nombre");
            }
        } else {
            throw new Exception("La aduana no se encuentra dada de alta");
        }

        try {
            $ultimo = null;
            $num_refe = $this->getNum_refe();
            $dbAdoP->beginTrans();  
            try {
                $tip_refe = $this->tip_refe;
                $tip_ope = $this->tip_ope;

                if($tip_refe == null){
                    $tip_refe = 1;
                }

                if($tip_ope == null){
                    $tip_ope = 1;
                }
                $sqlNewReference = "INSERT INTO previo.cprevo_refe(id_licencia, num_refe, estatus_refe, tip_refe, tip_ope)"
                    . " VALUES($id_licencia, '$num_refe', 1, $tip_refe, $tip_ope) RETURNING id_prev";
                $saveReference = $dbAdoP->Execute ( $sqlNewReference );
                $ultimo = $saveReference->fields["id_prev"];
                

            } catch (Exception $ex) {
                $dbAdoP->rollbackTrans();
                $this->setSuccess(false);
                return "Ocurrió un error al guardar la referencia.";
            }


  
            if ($ultimo > 0) {
                try {
  
                    $id_importador = $id_importador;
                    $fec_soli= $this->getFec_soli();
 
                    $fol_soli= $this->getFol_soli();
                    $tot_bult= $this->getTot_bult();
                    $tot_bultr= $this->getTot_bultr();
                    $rec_fisc= $this->getRec_fisc();
                    $num_guia= $this->getNum_guia();
                    $edo_prev= $this->getEdo_prev();
                    $ins_prev = str_replace("'", "''", $this->getIns_prev());
                    $dep_asigna= $this->getDep_asigna();
                    $obs_prev = str_replace("'", "''", $this->getObs_prev());
                    $pes_brut= $this->getPes_brut();

                    $sqlNewPrev= "INSERT INTO previo.cprevo_previos(id_prev, id_importador, fec_soli, fol_soli, tot_bult, tot_bultr, rec_fisc,  num_guia,edo_prev,ins_prev, dep_asigna, obs_prev, pes_brut, flag_version, flag_update)"
                     . " VALUES($ultimo, $id_importador, '$fec_soli', '$fol_soli', $tot_bult, $tot_bultr, '$rec_fisc',  '$num_guia','$edo_prev','$ins_prev', '$dep_asigna', '$obs_prev', $pes_brut, 2, 0)";
                  
                    $save_previo= $dbAdoP->Execute ( $sqlNewPrev );

                } catch (Exception $ex) {
                    $dbAdoP->rollbackTrans();
                    $this->setSuccess(false);
                    return "Ocurrió un error al guardar la información del previo. POR FAVOR VUELVALO A INTENTAR.   -  Error:" . $ex->getMessage();
                }
                
                
                try {
                    $dependient = $this->getDep_asigna();
                    $arrayDependient = explode(",", $dependient);


                    foreach($arrayDependient as $dependient){
                            $sqlNewDepen= "INSERT INTO previo.cprevo_dependientes(id_prev, nom_dependiente )"
                        . " VALUES($ultimo, '$dependient')";
                        $save_depen = $dbAdoP->Execute ( $sqlNewDepen );
                    }

                } catch (Exception $ex) {
                    $dbAdoP->rollbackTrans();
                    $this->setSuccess(false);
                    return "Ocurrió un error al guardar la información de los dependientes" . $ex->getMessage();
                }
                        

                $ContenedoresArray = $this->getContenedores();
                

                foreach ($ContenedoresArray as $key => $value) {
                    $db->setTable('general.casac_tipcon');
                    $db->setFields(array(
                        'id_tipcon'
                    ));

                    $db->setParameters("clave_tipcon = '" . $value->clave_tipcon . "'");
                    $contentId = $db->query();

                    $id_tipcon = $contentId[0]["id_tipcon"];

                    if(!$id_tipcon){
                        $id_tipcon = 1;
                    }
                    try {
                        $numero_contenedor= $value->numero_contenedor;
                        $numero_candado1= $value->numero_candado1;
                        $numero_candado2= $value->numero_candado2;
                        $numero_candado3= $value->numero_candado3;
                        $numero_candado4= $value->numero_candado4;
                        $numero_candado5=  $value->numero_candado5;
                        $obs_cont = str_replace("'", "''", $value->obs_cont);
                        $sqlNewContent= "INSERT INTO previo.cop_conten(id_prev, id_tipcon, numero_contenedor,numero_candado1, numero_candado2, numero_candado3,numero_candado4, numero_candado5, obs_cont )"
                            . " VALUES($ultimo, $id_tipcon, '$numero_contenedor', '$numero_candado1', '$numero_candado2', '$numero_candado3','$numero_candado4', '$numero_candado5', '$obs_cont')";
                        $save_content = $dbAdoP->Execute ( $sqlNewContent );


                    } catch (Exception $ex) {
                        $dbAdoP->rollbackTrans();
                        $this->setSuccess(false);
                        return "Ocurrió un error al guardar la información de los contenedores" . $ex->getMessage();
                    }
                    
                }

                
                $BultosArray = $this->getBultos();
                foreach ($BultosArray as $key => $value) {
                        $db->setTable('general.casac_bultos');
                        $db->setFields(array(
                            'id_bulto'
                        ));
                        $db->setParameters("clave_bulto = '" . $value->clave_bulto . "' AND id_cliente = -1");
                        $packageId = $db->query();
                        
                        $id_bulto = $packageId[0]["id_bulto"];
                        
                        if(!$id_bulto){
                            $id_bulto = 1;
                        }
                        
                        try {
                            $id_bulto = $id_bulto;
                            $cons_bulto= $value->cons_bulto;
                            $cant_bult= $value->cant_bult;
                            $anc_bult= $value->anc_bult;
                            $lar_bult= $value->lar_bult;
                            $alt_bult= $value->alt_bult;
                            $obs_bult = str_replace("'", "''", $value->obs_bult);
                            $sqlNewbult= "INSERT INTO previo.cop_bultos(id_prev, id_bulto, cons_bulto,cant_bult, anc_bult, lar_bult,alt_bult, obs_bult)"
                                . " VALUES($ultimo, $id_bulto, $cons_bulto,$cant_bult, $anc_bult, $lar_bult,$alt_bult, '$obs_bult')";

                            $save_bult = $dbAdoP->Execute ( $sqlNewbult );

                        } catch (Exception $ex) {
                            $dbAdoP->rollbackTrans();
                            $this->setSuccess(false);
                            return "Ocurrió un error al guardar la información de los bultos." . $ex->getMessage();

                        }
                    
                    
                }
                
                $OrdcomprasArray = $this->getOrdcompras();

                if(isset($OrdcomprasArray)){

                    foreach ($OrdcomprasArray as $key => $value) {
                        try {

                            $cons_orcom= $value->cons_orcom;
                            $num_orcom = $value->num_orcom;

                            $sqlNewOrcom= "INSERT INTO previo.cop_orcom(id_prev, cons_orcom, num_orcom)"
                                . " VALUES($ultimo, $cons_orcom, '$num_orcom')";
                            $save_orcom = $dbAdoP->Execute ( $sqlNewOrcom );

                        } catch (Exception $ex) {
                            $dbAdoP->rollbackTrans();
                            $this->setSuccess(false);
                            return "Ocurrió un error al guardar la información de las ordenes de compra." . $ex->getMessage();
                        }
                    }
                }

                
                $InvoiceArray = $this->getInvoices1();
                if(isset($InvoiceArray)){
                    foreach ($InvoiceArray as $key => $value) {
                        if ($ultimo > 0) {
                            try {
                                try {

                                    $id_prev = $ultimo;
                                    $cons_fact =  $value->cons_fact;
                                    $num_fact = trim($value->num_fact);
                                    $cve_pro  = trim($value->cve_pro);
                  
                                    $db->setTable('general.casac_proveedores');
                                    $db->setFields(array(
                                        'id_proveedor'
                                    ));
                                    $db->setParameters("cve_prov = '" . $cve_pro . "' AND id_cliente = " . $this->getIdclient());
                                    $idProv = $db->query();
                                    
                                    $id_proveedor = $idProv[0]["id_proveedor"];
                                    
                                    if(!$id_proveedor){
                                        $id_proveedor = 1;
                                    }
                                    

                                    $sqlNewInvoice = "INSERT INTO previo.cprevo_factur(id_prev, cons_fact, num_fact, id_proveedor, fac_extra)"
                                    . " VALUES($ultimo, $cons_fact, '$num_fact', '$id_proveedor', 0) RETURNING id_factur";
                                    $save_factur = $dbAdoP->Execute ( $sqlNewInvoice );

                                    $id_factur = $save_factur->fields["id_factur"];

                                } catch (Exception $ex) {
                                    $dbAdoP->rollbackTrans();
                                    $this->setSuccess(false);
                                    return "Ocurrió un error al guardar la información de la factura." . $ex->getMessage();
                                }


                                $InvoiceProducts = $value->products1;
                                foreach ($InvoiceProducts as $productos => $value) {
                                    try {
                                        $des_merc_replace = str_replace("'", "''", $value->desc_merc); 
                                        
                                        $cons_part = $value->cons_part;
                                        $num_part= trim($value->num_part);
                                        $desc_merc= $des_merc_replace;
                                        $pai_orig= trim($value->pai_orig);
                                        $uni_fact= $value->uni_fact;
                                        $can_fact= $value->can_fact;
                                        $can_factr= $value->can_factr;
                                        $edo_corr= $value->edo_corr;
                                        $obs_frac = str_replace("'", "''", $value->obs_frac);
                                        $cve_usua= $value->cve_usua;
                                        $inc_part = str_replace("'", "''", $value->inc_part);
                                        $uni_tari= $value->uni_tari;
                                        $can_tari= $value->can_tari;
                                        $pes_unit= $value->pes_unit;
                                        $num_fracc = trim($value->num_fracc);
                                        $cve_nico = trim($value->cve_nico);
                                        
                                        $id_cliente = $this->getIdclient();

                                            
                                        $nextVal = "SELECT setval('previo.cprevo_facpar_id_partida_seq', (SELECT MAX(id_partida) FROM previo.cprevo_facpar)+1)";
                                        $nextIdPrev= $dbAdoP->Execute ( $nextVal );
                                        $idNextPrev= json_decode(json_encode($nextIdPrev->fields), true);
                                        $id_partidaNext = $idNextPrev[0];
                                        
                                        if($id_partidaNext == NULL){
                                            $id_partidaNext = 1;
                                        }
                                        
                                        $tip_pes = $value->tip_pes;
                                    
                                        if($tip_pes == null || $tip_pes == NULL || isset($tip_pes) == false){
                                            $tip_pes = 1;
                                        }

                                        if($num_fracc == null || $num_fracc == NULL || isset($num_fracc) == false){
                                            $num_fracc = '00000000';
                                        }
                                        
                                        if($cve_nico == null || $cve_nico == NULL || isset($cve_nico) == false){
                                            $cve_nico = '00';
                                        }
                                        
                                        
                                        $sqlNewPart =  "INSERT INTO previo.cprevo_facpar(id_partida, id_factur, cons_part, num_part, desc_merc, pai_orig, uni_fact, can_fact, can_factr, edo_corr,obs_frac,cve_usua, inc_part,  uni_tari, can_tari, pes_unit, tip_pes, part_extra, num_fracc, cve_nico )"
                                            . " VALUES($id_partidaNext, $id_factur, $cons_part, '$num_part',  '$desc_merc', '$pai_orig', '$uni_fact', $can_fact, $can_factr, $edo_corr,'$obs_frac','$cve_usua', '$inc_part',  $uni_tari, $can_tari, $pes_unit, $tip_pes, 0, '$num_fracc', '$cve_nico' ) RETURNING id_partida";
                                    
                                        $save_part = $dbAdoP->Execute ( $sqlNewPart );
                                 
                                        $id_partida = $save_part->fields["id_partida"];
                                      
                                    } catch (Exception $ex) {
                                        $dbAdoP->rollbackTrans();
                                        $this->setSuccess(false);
                                        return "Ocurrió un error al guardar la información de la partida." . $ex->getMessage();
                                    }


                                    $ProductsSeries = $value->series;

                                    foreach ($ProductsSeries as $series => $value) {
                                        if($value->num_part != '' || $value->mar_merc != '' || $value->sub_mode != '' || $value->num_seri != '' ){
                                            try {
                                                $cons_seri =  $value->cons_seri;
                                                $num_part = trim($value->num_part);
                                                $mar_merc =  $value->mar_merc;
                                                $sub_mode = trim($value->sub_mode);
                                                $num_serie = trim($value->num_seri);

                                                if($num_serie == null){
                                                    $num_serie = '';
                                                }


                                                $sqlNewSerie =  "INSERT INTO previo.cprevo_series(id_partida, cons_seri, num_part, mar_merc, sub_mode, num_seri)"
                                                    . " VALUES($id_partida, $cons_seri, '$num_part', '$mar_merc', '$sub_mode', '$num_serie')";


                                                $save_factur = $dbAdoP->Execute ( $sqlNewSerie );


                                            } catch (Exception $ex) {
                                                $dbAdoP->rollbackTrans();
                                                $this->setSuccess(false);
                                                return "Ocurrió un error al guardar la información de la serie." . $ex->getMessage();
                                            }    

                                        } 

                                    }

                                }

                            } catch (Exception $e) {
                                $dbAdoP->rollbackTrans();
                                $this->setSuccess(false);

                                return 'Ocurrió un error al insertar las facturas' . $e->getMessage();
                            }
                        }
                    }
                }

                $dbAdoP->commitTrans();
                $this->setSuccess(true);
                return "La operación es correcta";
            } else {
                $dbAdoP->rollbackTrans();
                $this->setSuccess(false);
                return "No se pudo agregar la referencia.";
            }
          
            

        } catch (Exception $e) {
            $dbAdoP->rollbackTrans();
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
     * @param string $patente            
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
     * @param string $patente            
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
     * @return the $pes_brut
     */
    public function getPes_brut()
    {
        return $this->pes_brut;
    }

    /**
     *
     * @param number $pes_brut            
     */
    public function setPes_brut($pes_brut)
    {
        $this->pes_brut = $pes_brut;
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
     * @param Ordcompras[] $ordcompras            
     */
    public function setOrdcompras($ordcompras)
    {
        $this->ordcompras = $ordcompras;
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
     * @param Bultos[] $bultos            
     */
    public function setBultos($bultos)
    {
        $this->bultos = $bultos;
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
     * @param Contenedores[] $contenedores            
     */
    public function setContenedores($contenedores)
    {
        $this->contenedores = $contenedores;
    }

    /**
     *
     * @var boolean
     */
    private $success;

    /**
     *
     * @return the $invoices1
     */
    public function getInvoices1()
    {
        return $this->invoices1;
    }

    /**
     *
     * @param Invoice[] $invoices1            
     */
    public function setInvoices1($invoices1)
    {
        $this->invoices1 = $invoices1;
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
     * @return the $tip_refe
     */
    public function getTip_refe()
    {
        return $this->tip_refe;
    }


     
    /**
     *
     * @return the $tip_ope
     */
    public function getTip_ope()
    {
        return $this->tip_ope;
    }

    /**
     *
     * @param number $tip_refe           
     */
    public function setTip_refe($tip_refe)
    {
        $this->tip_refe = $tip_refe;
    }
    
        /**
     *
     * @param number $tip_ope          
     */
    public function setTip_ope($tip_ope)
    {
        $this->tip_ope = $tip_ope;
    }
    
    
}

?>
