<?php

/**
 * CasaLibraries AddPreviousNoReference
 * File AddPreviousNoReference.php
 * AddPreviousNoReference Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			YGLP
 * @version    		Previo 1.0.0
 */

require_once 'File.php';
require_once 'InvoicePhotos.php';


class AddPhotos
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
    public $num_guia;


    /**
     *
     * @var string
     */
    public $dep_asigna;
    
    /**
     *
     * @var string
     */
    public $hora_fin;

    
    /**
    *
    * @var \File[]
    */
    public $files;
    
    /**
     *
     * @var \InvoicePhotos[]
     */
    public $invoices;


    /**
     *
     * @var boolean
     */
    private $success;
    

 /**
     * Constructor of class
     *
     * @param integer $idclient            
     * @param string $aduana            
     * @param string $rfc_impo                       
     * @param string $num_guia                      
     * @param string $dep_asigna            
     * @param string $hora_fin            
     */
    public function __construct($idclient, $aduana, $rfc_impo, $num_guia, $dep_asigna, $hora_fin, $files, $invoices)
    {
        $this->setIdclient($idclient);
        $this->setAduana($aduana);
        $this->setRfc_impo($rfc_impo);
        $this->setNum_guia($num_guia);
        $this->setDep_asigna($dep_asigna);
        $this->setHora_fin($hora_fin);
        $this->setFiles($files);
        $this->setInvoices($invoices);
    }

    /**
     * This method adds all the information about previous in data base
     */
    public function addPhotos()
    {
        if ($this->getIdclient() == '' || $this->getAduana() == '' || $this->getRfc_impo() == "")
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
                'id_licencia',
                'status_licencia'
            ));
            
            $db->setParameters("id_cliente = " . $this->getIdclient() . " AND id_aduana = $id_aduana");
            $license = $db->query();
            
            $licenseId = $license->id_licencia;
            
        } else {
            throw new Exception("La aduana no se encuentra dada de alta");
        }
        
        if ($licenseId > 0) {
            
            if($license->status_licencia == 0){
                throw new Exception("La licencia del cliente se encuentra inactiva");
            }
            
            $db->setTable('"General".casac_importadores');
            $db->setFields(array(
                'id_importador',
                'status_importador'
            ));
            
            $db->setParameters("id_cliente = " . $this->getIdclient() . " AND rfc_importador = '" . $this->getRfc_impo() . "'");
            $importer = $db->query();
            $idImporter = $importer->id_importador;
           
           
        } else {
            throw new Exception("La licencia del cliente no se encuentra dada de alta");
        }
        
        
        if ($idImporter > 0) {
            if($importer->status_importador == 0){
                throw new Exception("El importador se encuentra inactivo");
            }
            
            $db->setTable('"General".casag_licenciasistema');
            $db->setFields(array(
                'id_licenciasistema'
            ));
            $db->setParameters("id_licencia = $licenseId" . " AND id_sistema = '2'");
            $licenceSystemId = $db->query();
            $licenceSystemId = $licenceSystemId->id_licenciasistema;
        } else {
            throw new Exception("El importador no se encuentra registrado");
        }

        try {
            if ($licenceSystemId > 0) {
                
                $db->setTable('"Previo".cprevo_previos');
                $db->setFields(array(
                    'id_prev'
                ));
                
                $db->setParameters("num_guia = '" . $this->getNum_guia() . "' AND id_importador = " . $idImporter . " AND dep_asigna = '" . $this->getDep_asigna() . "' AND hora_fin = '" . $this->getHora_fin() . "'");
                $previousExisting = $db->query();
                
                $idPreviousExisting = $previousExisting->id_prev;

                if(!$idPreviousExisting){
                    $this->setSuccess(false);
                    return "La referencia no existe";
                } else {

                    if ($idPreviousExisting > 0) {
                        try {
                            $db->setTable('"Previo".cprevo_refe');
                            $db->setFields(array(
                                'num_refe'
                            ));
                            
                            $db->setParameters("id_prev = $idPreviousExisting ");
                            $numRefeObject = $db->query();
                           
                            $numRefe = $numRefeObject->num_refe;
                        } catch (Exception $e) {
                            return 'Ocurrió un error al localizar la referencia del previo'. $e->getMessage();
                        }
                        
                        foreach ($this->getFiles() as $key => $value) {
                            try {
                                
                                $db->setTable('"Previo".cprevo_fotop');
                                $db->setFields(array(
                                    'id_fotop'
                                ));
                                
                                $db->setParameters("id_prev =  $idPreviousExisting AND cons_foto = " . $value->cons_foto . " AND nom_foto = '" . $value->nom_foto . "'");
                                $numPhotoPreviousObject = $db->query();

                                if($numPhotoPreviousObject->id_fotop < 0){
                                    $db->setTable('Previo.cprevo_fotop');
                                    $db->setValues(array(
                                        "id_prev" => $idPreviousExisting,
                                        "cons_foto" => $value->cons_foto,
                                        "nom_foto" => $value->nom_foto
                                    ));
                                    $response = $db->insert();
                                } 

                                $photo = new File($this->getIdclient(), $numRefe, $value->cons_foto, $value->nom_foto, $value->fileString, $this->getAduana());
                                $response = $photo->loadFileOperation();
                            } catch (Exception $e) {
                                return 'Ocurrió un error al insertar las fotos de el previo'. $e->getMessage();
                            }
                        }
                    
                        foreach ($this->getInvoices() as $key => $value) {
                            if ($idPreviousExisting > 0) {
                                try {
                                    $db->setTable('"Previo".cprevo_factur');
                                    $db->setFields(array(
                                        'id_factur'
                                    ));
                                    
                                    $db->setParameters("id_prev = " . $idPreviousExisting . " AND cons_fact = " . $value->cons_fact . " AND num_fact = '" . $value->num_fact . "' ");
                                    $numInvoiceObject = $db->query();
                                    $numInvoice = $numInvoiceObject->id_factur;
                                    
                                } catch (Exception $e) {
                                    return 'Ocurrió un error al localizar la factura'. $e->getMessage();
                                }
                                foreach ($value->products as $product) {
                                    if ($idPreviousExisting > 0) {
                                        try {

                                            $db->setTable('"Previo".cprevo_facpar');
                                            $db->setFields(array(
                                                'id_partida'
                                            ));
                                            
                                            $db->setParameters("id_factur = " . $numInvoice . " AND cons_part = " . $product->cons_part . " AND num_part = '" . $product->num_part . "' ");
                                            $numPart = $db->query();
                                            
                                            $idPart = $numPart->id_partida;
                   
                                        } catch (Exception $e) {
                                            return 'Ocurrió un error al localizar la mercancía'. $e->getMessage();
                                        }

                    
                                        if(count($product->files) > 0) {
                                            foreach ($product->files as $file) {
                                                if ($idPreviousExisting > 0) {
                                                    try {
                                                        
                                                        $db->setTable('"Previo".cprevo_fotos');
                                                        $db->setFields(array(
                                                            'id_fotos'
                                                        ));
                                                        
                                                        $db->setParameters("id_partida =  $idPart AND cons_foto = " . $file->cons_foto . " AND nom_foto = '" . $file->nom_foto . "'");
                                                        $numPhotosPartObject = $db->query();
                                                        $numPhotoPart = $numPhotosPartObject->id_fotos;
                                                        
                                                        if($numPhotoPart < 0) {
                                                            $db->setTable('Previo.cprevo_fotos');
                                                            $db->setValues(array(
                                                                'id_partida' => $idPart,
                                                                'cons_foto' => $file->cons_foto,
                                                                'nom_foto' => $file->nom_foto,
                                                            ));
                                                            $response = $db->insert();
                                                        }

                                                        $photo = new File($this->getIdclient(), $numRefe, $file->cons_foto, $file->nom_foto, $file->fileString, $this->getAduana());
                                                        $photo->loadFileOperation();
                                                    } catch (Exception $e) {
                                                        return 'Ocurrió un error al insertar las fotos de la partida'. $e->getMessage();
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $this->setSuccess(true);
                    return "La operación es correcta";
                }

            } else {
                $this->setSuccess(false);
                return "La licencia del importador no se encuentra dada de alta";
            }
            
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
     * @return the $num_guia
     */
    public function getNum_guia()
    {
        return $this->num_guia;
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
     * @param string $dep_asigna            
     */
    public function setDep_asigna($dep_asigna)
    {
        $this->dep_asigna = $dep_asigna;
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
     * @return the $hora_fin
     */
    public function getHora_fin()
    {
        return $this->hora_fin;
    }


    /**
     * @param string $hora_fin
     */
    public function setHora_fin($hora_fin)
    {
        $this->hora_fin = $hora_fin;
    }
    /**
     * @return boolean
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * @param string $num_guia
     */
    public function setNum_guia($num_guia)
    {
        $this->num_guia = $num_guia;
    }


    
    

}

?>