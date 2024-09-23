<?php

/**
 * CasaLibraries AddPreviousNoReference
 * File AddPreviousNoReference.php
 * AddPreviousNoReference Class
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
require_once 'File.php';
require_once 'Invoice.php';


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
     */
    public function __construct($idclient, $aduana, $rfc_impo, $fec_soli, $tot_bult, $tot_bultr, $rec_fisc, $num_guia, $edo_prev, $ins_prev, $dep_asigna, $obs_prev, $contenedores, $bultos, $ordcompras, $files, $invoices)
    {
        $this->setIdclient($idclient);
        $this->setAduana($aduana);
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
        $this->setContenedores($contenedores);
        $this->setBultos($bultos);
        $this->setOrdcompras($ordcompras);
        $this->setFiles($files);
        $this->setInvoices($invoices);
    }

    /**
     * This method adds all the information about previous in data base
     */
    public function addInDataBase()
    {
        if ($this->getIdclient() == '' || $this->getAduana() == '' || $this->getRfc_impo() == "" || $this->getNum_refe() == "")
            throw new Exception("Datos incompletos");
        $db = new PgsqlQueries();
        
        $db->setTable('"General".casac_aduanas');
        $db->setFields(array(
            'id_aduana'
        ));
        
        $db->setParameters("clave_aduana = '" . $this->getAduana() . "'");
        $db->setReturnType(PgsqlQueries::TYPE_OBJECT_ONE);
        $response = $db->query();
        
        $id_aduana = $response->id_aduana;
        
        if ($id_aduana > 0) {
            $db->setTable('"General".casag_licencias');
            $db->setFields(array(
                'id_licencia'
            ));
            
            $db->setParameters("id_cliente = " . $this->getIdclient() . " AND id_aduana = $id_aduana AND status_licencia = 1");
            $license = $db->query();
            
            $licenseId = $license->id_licencia;
        }
        
        if ($licenseId > 0) {
            
            $db->setTable('"General".casac_importadores');
            $db->setFields(array(
                'id_importador'
            ));
            
            $db->setParameters("id_cliente = " . $this->getIdclient() . " AND rfc_importador = '" . $this->getRfc_impo() . "'");
            $importer = $db->query();
            
            $idImporter = $importer->id_importador;
        } else {
            throw new Exception("Licencia Cliente Vencida");
        }
        
        if ($idImporter > 0) {
            $db->setTable('"General".casag_licenciasistema');
            $db->setFields(array(
                'id_licenciasistema'
            ));
            
            $db->setParameters("id_licencia = $licenseId" . " AND id_sistema = '2'");
            $licenceSystemId = $db->query();
            
            $licenceSystemId = $licenceSystemId->id_licenciasistema;
        } else {
            
            throw new Exception("Licencia Importador Vencida");
        }
        
        if ($licenceSystemId > 0) {
            $db->setTable('"General".casag_licenciasimportador');
            $db->setFields(array(
                'id_licenciasimportador'
            ));
            
            $db->setParameters("id_licenciasistema = $licenceSystemId" . " AND id_importador = $idImporter");
            $licenceSystemImporter = $db->query();
            
            $licenceSystemImporter = $licenceSystemImporter->id_licenciasimportador;
        } else {
            throw new Exception("Licencia Importador Vencida");
        }
        
        try {
            
            if ($licenceSystemImporter > 0) {
                $db->setTable('Previo.cprevo_refe');
                $db->setValues(array(
                    'id_prev' => $this->getNextIdPrevio(),
                    'id_licencia' => $licenseId,
                    'num_refe' => $this->getNum_refe(),
                  
                ));
                
                $db->setParameters("id_licencia = $licenseId" . " AND id_sistema = '2'");
                $response = $db->insert();
            
                if ($this->getNextIdPrevio() > 0) {
                    $db->setTable('Previo.cprevo_previos');
                    $db->setValues(array(
                        'id_prev' => $this->getNextIdPrevio(),
                        'id_importador' => $idImporter,
                        'fec_soli'=> $this->getFec_soli(),
                        'fol_soli'=> $this->getFol_soli(),
                        'tot_bult'=> $this->getTot_bult(),
                        'tot_bultr'=> $this->getTot_bultr(),
                        'rec_fisc'=> $this->getRec_fisc(),
                        'num_guia'=> $this->getNum_guia(),
                        'edo_prev'=> $this->getEdo_prev(),
                        'ins_prev'=> $this->getIns_prev(),
                        'dep_asigna'=> $this->getDep_asigna(),
                        'obs_prev'=> $this->getObs_prev(),
                    
                    ));
                    $response = $db->insert();
                    
                    foreach ($this->getContenedores() as $key => $value) {
                        if ($this->getNextIdPrevio() > 0) {
                            $db->setTable('"General".casac_tipcon');
                            $db->setFields(array(
                                'id_tipcon'
                            ));
                            
                            $db->setParameters("clave_tipcon = '" . $value->clave_tipcon . "'");
                            $contentId = $db->query();
                            
                            $contentId = $contentId->id_tipcon;
                            
                            $db->setTable('Previo.cop_conten');
                            $db->setValues(array(
                                'id_prev' => $this->getNextIdPrevio(),
                                'id_tipcon' => $contentId,
                                'numero_contenedor'=> $value->numero_contenedor,
                                'numero_candado1'=> $value->numero_candado1,
                                'numero_candado2'=> $value->numero_candado2,
                                'numero_candado3'=> $value->numero_candado3,
                                'numero_candado4'=> $value->numero_candado4,
                                'numero_candado5'=> $value->numero_candado5,
                                'obs_cont'=> $value->obs_cont
                            ));
                            $response = $db->insert();
                        }
                    }

                    foreach ($this->getBultos() as $key => $value) {
                        if ($this->getNextIdPrevio() > 0) {
                            
                            $db->setTable('"General".casac_bultos');
                            $db->setFields(array(
                                'id_bulto'
                            ));
                            $db->setParameters("clave_bulto = '" . $value->clave_bulto . "'");
                            $packageId = $db->query();
                            
                            $packageId = $packageId->id_bulto;
                            
                            $db->setTable('Previo.cop_bultos');
                            $db->setValues(array(
                                'id_prev' => $this->getNextIdPrevio(),
                                'id_bulto' => $packageId,
                                'cons_bulto'=> $value->cons_bulto,
                                'cant_bult'=> $value->cant_bult,
                                'anc_bult'=> $value->anc_bult,
                                'lar_bult'=> $value->lar_bult,
                                'alt_bult'=> $value->alt_bult,
                                'obs_bult'=> $value->obs_bult
                            
                            ));
                            $response = $db->insert();
                        }
                    }
                    
                    foreach ($this->getFiles() as $key => $value) {
                        $db->setTable('Previo.cprevo_fotop');
                        $db->setValues(array(
                            "id_prev" => $this->getNextIdPrevio(),
                            "cons_foto" => $value->cons_foto,
                            "nom_foto" => $value->nom_foto
                        ));
                        $response = $db->insert();
                        
                        $photo = new File($this->getIdclient(), $this->getNum_refe(), $value->cons_foto, $value->nom_foto, $value->fileString, $this->getAduana());
                        $photo->loadFileOperation();
                    }
                    
                    foreach ($this->getInvoices() as $key => $value) {
                        if ($this->getNextIdPrevio() > 0) {
                            $db->setSql('SELECT nextval(\'"Previo".cprevo_factur_id_factur_seq\'::regclass)');
                            $nextId = $db->execute();
                            $nextId = $nextId[0]['nextval'];
                            
                            $db->setTable('Previo.cprevo_factur');
                            $db->setValues(array(
                                'id_factur' => $nextId,
                                'id_prev' => $this->getNextIdPrevio(),
                                'cons_fact'=> $value->cons_fact,
                                'num_fact'=> $value->num_fact,
                               
                            ));
                            $response = $db->insert();
                            
                            foreach ($value->products as $product) {
                                if ($this->getNextIdPrevio() > 0) {
                                    $db->setSql('SELECT nextval(\'"Previo".cprevo_facpar_id_partida_seq\'::regclass)');
                                    $nextProductId = $db->execute();
                                    $nextProductId = $nextProductId[0]['nextval'];
                                    
                                    $db->setTable('Previo.cprevo_facpar');
                                    $db->setValues(array(
                                        'id_partida' => $nextProductId,
                                        'id_factur' => $nextId,
                                        'cons_part' => $product->cons_part,
                                        'num_part'=> $product->num_part,
                                        'desc_merc'=> $product->desc_merc,
                                        'pai_orig'=> $product->pai_orig,
                                        'uni_fact'=> $product->uni_fact,
                                        'can_fact'=> $product->can_fact,
                                        'can_factr'=> $product->can_factr,
                                        'edo_corr'=> $product->edo_corr,
                                        'obs_frac'=> $product->obs_frac,
                                        'cve_usua'=> $product->cve_usua,
                                        'inc_part'=> $product->inc_part,
                                        'uni_tari'=> $product->uni_tari,
                                        'can_tari'=> $product->can_tari,
                                         
                                    ));
                                    $response = $db->insert();
                
                                    if(count($product->series) > 0) {
                                        foreach ($product->series as $serie) {
                                            if ($this->getNextIdPrevio() > 0) {
                                                $db->setTable('Previo.cprevo_series');
                                                $db->setValues(array(
                                                    'id_partida' => $nextProductId,
                                                    'cons_seri' => $serie->cons_seri,
                                                    'num_part' => $serie->num_part,
                                                    'mar_merc'=> $serie->mar_merc,
                                                    'sub_mode'=> $serie->sub_mode,
                                                    'num_seri'=> $serie->num_seri,

                                                ));
                                                $response = $db->insert();
                                            }
                                        }
                                    }
                                    
                                    if(count($product->files) > 0) {
                                        foreach ($product->files as $file) {
                                            if ($this->getNextIdPrevio() > 0) {
                                                $db->setTable('Previo.cprevo_fotos');
                                                $db->setValues(array(
                                                    'id_partida' => $nextProductId,
                                                    'cons_foto' => $file->cons_foto,
                                                    'nom_foto' => $file->nom_foto,
                                                ));
                                                $response = $db->insert();
                                            
                                                $photo = new File($this->getIdclient(), $this->getNum_refe(), $file->cons_foto, $file->nom_foto, $file->fileString, $this->getAduana());
                                                $photo->loadFileOperation();
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
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
        $db->setSql('SELECT nextval(\'"Previo".cprevo_refe_id_prev_seq\'::regclass)	');
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

}

?>