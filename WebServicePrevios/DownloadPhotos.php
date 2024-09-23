<?php

/**
 * CasaLibraries AddPrevious
 * File DownloadData.php
 * DownloadData Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			SMV
 * @version    		Previo 1.0.0
 */

require_once 'PhotosDown.php';
require_once 'File.php';
require_once 'Invoice.php';

class DownloadPhotos
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
     * PhotosDown Object
     *
     * @var \PhotosDown
     */
    private $photosDown;
    
    /**
     *
     * @param integer $idclient
     * @param string $num_refe
     */
    public function __construct($idclient, $num_refe)
    {
        $this->setIdclient($idclient);
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
    public function downloadPhotos()
    {
        // validamos Cliente
        if ($this->getIdclient() == ''  || $this->getNum_refe() == '') {
            $this->setSuccess(false);
            $this->setMessageText("Datos requeridos incompletos.");
            return false;
        } else {
            $db = new PgsqlQueries();

            $db->setTable("'General'.casac_clientes");
            $db->setJoins("");
            $db->setFields(array(
                'id_cliente'
            ));
            $db->setParameters("id_cliente = '" . $this->getIdclient() . "'");
            $db->setReturnType(PgsqlQueries::TYPE_OBJECT_ONE);
            $detailClient = $db->query();
            
            $id_cliente = $detailClient->id_cliente;

            try {
                
                if ($id_cliente > 0) {

                    $db->setTable("'Previo'.cprevo_refe CR");
                    $db->setJoins("INNER JOIN 'General'.casag_licencias L ON CR.id_licencia = L.id_licencia");
                    $db->setJoin("INNER JOIN 'General'.casac_aduanas A ON L.id_aduana = A.id_aduana");
                    $db->setFields(array(
                        'CR.id_prev',
                        'A.clave_aduana'
                    ));
                    $db->setParameters("CR.num_refe = '" . $this->getNum_refe() . "' AND L.id_cliente =  " . $this->getIdclient() . "");
                    $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                    $detailReference = $db->query();

                    $idprev = $detailReference[0]["id_prev"];
                    
                    
                    if ($idprev > 0) {
                        $keyCustome = $detailReference[0]["clave_aduana"];
                        
                        $db->setTable("'Previo'.cprevo_factur F");
                        $db->setJoins("INNER JOIN 'Previo'.cprevo_facpar P ON F.id_factur = P.id_factur");
                        $db->setFields(array(
                            'F.id_factur',
                            'F.cons_fact',
                            'F.num_fact',
                            'P.cve_usua'
                        ));
                        $db->setParameters("F.id_prev = $idprev");
                        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                        $validation = $db->query();
                        
                    
                        if($validation) {
                           
                            if($validation[0]["cve_usua"] != NULL || $validation[0]["cve_usua"] != ''){

                                if (!is_array($detailReference)) {
                                    throw new Exception($response);
                                }
                                
                                $generalArray = array();

                                $photosDown = new PhotosDown();
                                
                                $db->setTable("'Previo'.cprevo_fotop F");
                                $db->setJoins("");
                                $db->setFields(array(
                                    "F.'cons_foto'",
                                    "F.'nom_foto'"
                                ));
                                $db->setParameters("F.id_prev = " . $idprev . "");
                                $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                                $generalFiles = $db->query();
                
                                $generalFilesArray = array();
                                
                                foreach ($generalFiles as $generalFile) {
                                    $previoFile = new File();
                                    $previoFile->setIdClient($this->getIdclient());
                                    $previoFile->setAduana($keyCustome);
                                    $previoFile->setNum_refe($this->getNum_refe());
                                    $previoFile->setCons_foto($generalFile["cons_foto"]);
                                    $previoFile->setNom_foto($generalFile["nom_foto"]);
                                    $previoFile->setFileString($previoFile->extractFile());
                                    
                                    $generalFilesArray [] = $previoFile;
                                }
                                
                              
                                $photosDown->setFiles($generalFilesArray);
                                
                                
                                $db->setTable("'Previo'.cprevo_factur");
                                $db->setJoins("");
                                $db->setFields(array(
                                    'id_factur',
                                    'cons_fact',
                                    'num_fact',
                                    'fac_extra'
                                ));
                                $db->setParameters("id_prev = '" . $idprev . "'");
                                $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                                $invoices = $db->query();

                                $invoicesArray = Array();
                                
                                foreach ($invoices as $invoice) {
                                    $Invoice = new Invoice();
                                    $Invoice->setCons_fact($invoice["cons_fact"]);
                                    $Invoice->setNum_fact($invoice["num_fact"]);
                                    if($invoice["fac_extra"] != 1){
                                        $Invoice->setFac_extra(0);
                                    } else {
                                        $Invoice->setFac_extra($invoice["fac_extra"]);
                                    }
                                    
                                    
                                    $idInvoice = $invoice["id_factur"];
                                    

                                    $db->setTable("'Previo'.cprevo_facpar");
                                    $db->setJoins("");
                                    $db->setFields(array(
                                        'id_partida',
                                        'cons_part',
                                        'num_part'
                                    ));
                                    $db->setParameters("id_factur = '" . $idInvoice . "'");
                                    $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                                    $products = $db->query();

                                    $productsArray = Array ();
                                    
                                    foreach ($products as $product) {
                                        $Products = new Products();
                                        
                                        $Products->setCons_part($product["cons_part"]);
                                        $Products->setNum_part($product["num_part"]);

                                        $idProduct = $product["id_partida"];

                                        $db->setTable("'Previo'.cprevo_fotos");
                                        $db->setJoins("");
                                        $db->setFields(array(
                                            'cons_foto',
                                            'nom_foto'
                                        ));
                                        $db->setParameters("id_partida = '" . $idProduct . "'");
                                        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                                        $files = $db->query();

                                        $filesArray = Array();
                                        foreach ($files as $file) {
                                            $File = new File($this->getIdclient(), $this->getNum_refe(), $file["cons_foto"], $file["nom_foto"], null, $keyCustome);
                                            $File->setFileString($File->extractFile());
                                            $filesArray [] = $File;
                                        }
                                        
                                        $Products->setFiles($filesArray);
                                        
                                        $productsArray [] = $Products;
                                        
                                    }
                                    
                                    $Invoice->setProducts($productsArray);
                                    $invoicesArray [] = $Invoice;
                                    
                                }
                                
                                $photosDown->setInvoices($invoicesArray);

                                
                                $generalArray[] = $photosDown;
                           
                                
                            } else {
                                throw new Exception("El Previo está incompleto.");
                            }
                            
                        } else {
                            throw new Exception("El Previo está incompleto.");
                        }
                        
                    } else {
                        $this->setMessageText("No existe la Referencia");
                    }
                    
                    $this->setPhotosDown($generalArray);
                    $this->setSuccess(true);
                    
                    return true;
                    
                } else {
                    $this->setSuccess(false);
                    return "El cliente no existe";
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
     * @return boolean
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * @return PhotosDown
     */
    public function getPhotosDown()
    {
        return $this->photosDown;
    }

    /**
     * @param PhotosDown $photosDown
     */
    public function setPhotosDown($photosDown)
    {
        $this->photosDown = $photosDown;
    }

    

}

?>