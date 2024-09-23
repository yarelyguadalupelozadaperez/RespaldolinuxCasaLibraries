<?php

/**
 * CasaLibraries DownloadPreviousList
 * File DownloadPreviousList.php
 * DownloadPreviousList Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			Yarely Guadalupe Lozada Pérez
 * @version    		Previo 1.0.0
 */

require_once 'CasaLibraries/WebServicePrevios/PreviousList.php';

class DownloadPreviousList
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
    public $nom_movil;
    
    
    /**
     *
     * @var string
     */
    public $dep_asigna;
    
    
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
     * @param integer $idclient                      
     * @param string $aduana
     * @param string $nom_movil      
     * @param string $dep_asigna      
     */
    public function __construct($idclient, $aduana, $nom_movil, $dep_asigna)
    {
        $this->setIdclient($idclient);
        $this->setAduana($aduana);
        $this->setNom_movil($nom_movil);
        $this->setDep_asigna($dep_asigna);
    }

    public function downloadPreviousListData()
    {
    
        if ($this->getIdclient() == '' || $this->getAduana() == '' || $this->getNom_movil() == "" || $this->getDep_asigna() == "") {
            throw new Exception("Datos incompletos");
        } else {
            $db = new PgsqlQueries();
           try {
             
                
                $db->setTable("'general'.casac_aduanas");
                $db->setJoins("");
                $db->setFields(array(
                    "id_aduana"
                ));
                $db->setParameters("clave_aduana = '" . $this->getAduana() . "'");
                $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                $custome = $db->query();
                $id_aduana = $custome[0]["id_aduana"];
         
                if ($id_aduana > 0) {
                    
                    $db->setTable("'general'.casag_licencias");
                    $db->setJoins("");
                    $db->setFields(array(
                        "id_licencia",
                        "status_licencia"
                    ));
                    $db->setParameters("id_cliente = " . $this->getIdclient() . " AND id_aduana = $id_aduana");
                    $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                    $licenses = $db->query();
                    $id_licencia = $licenses[0]["id_licencia"];

                    if ($id_licencia > 0) {
                        
                        if(
                                [0]["status_licencia"] == 0){
                            throw new Exception("La licencia del cliente se encuentra inactiva");
                        }
                            
                        $db->setTable("'previo'.cprevo_refe CR");
                        $db->setJoins("INNER JOIN 'previo'.cprevo_previos P ON P.id_prev = CR.id_prev");
                        $db->setJoin("INNER JOIN general.casag_licencias L ON CR.id_licencia = L.id_licencia");
                        $db->setFields(array(
                            "CR.id_prev"
                        ));
                        $db->setParameters("L.id_cliente = " . $this->getIdclient() . " AND L.id_aduana = $id_aduana AND P.fol_soli <> -1");
                        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                        $previous = $db->query();
                        $idprev = $previous[0]["id_prev"];

                        if ($idprev > 0) {
                            $db->setTable("'previo'.cprevo_descar");
                            $db->setJoins("");
                            $db->setFields(array(
                                 "id_prev"
                            ));
                            $db->setParameters("nom_movil = '" . $this->getNom_movil() . "'");
                            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                            $data = $db->query();

                            if (count($data) == 0) {
                                $notIn = '';
                            } else {
                                foreach ($data as $value) {
                                    $not .= $value["id_prev"] . ',';
                                }

                                $not = substr($not, 0, -1);

                                $notIn = ' AND CP.id_prev NOT IN (' . $not .')';
                            }


                            $db->setTable('"previo".cprevo_previos CP');
                            $db->setJoins("INNER JOIN 'previo'.'cprevo_refe' CR ON CP.id_prev = CR.id_prev");
                            $db->setJoin("LEFT JOIN 'general'.casac_importadores I ON CP.id_importador = I.id_importador");
                            $db->setJoin("LEFT JOIN 'previo'.cprevo_dependientes D ON CP.id_prev = D.id_prev");
                            $db->setJoin("INNER JOIN 'previo'.cprevo_factur F ON CP.id_prev = F.id_prev");
                            $db->setJoin("INNER JOIN 'previo'.cprevo_facpar FP ON F.id_factur = FP.id_factur");
                            $db->setJoin("INNER JOIN general.casag_licencias L ON CR.id_licencia = L.id_licencia");
                            $db->setFields(array(
                                "DISTINCT ON (CR.id_prev) CR.id_prev",
                                "CR.num_refe",
                                "I.rfc_importador",
                                "I.nombre_importador",
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
                            ));
                            if($this->getDep_asigna() ==  'CPREVIOS'){
                                $db->setParameters("L.id_cliente = " . $this->getIdclient() . " AND L.id_aduana = $id_aduana AND CP.fol_soli <> -1 AND (FP.cve_usua = '' OR FP.cve_usua IS NULL) " . $notIn );
                            } else{
                                $db->setParameters("L.id_cliente = " . $this->getIdclient() . " AND L.id_aduana = $id_aduana AND CP.fol_soli <> -1 AND (D.nom_dependiente = '". $this->getDep_asigna()."' OR CP.dep_asigna LIKE '%". $this->getDep_asigna()."%' ) AND (FP.cve_usua = '' OR FP.cve_usua IS NULL)". $notIn);
                            }

                            $db->setReturnType(PgsqlQueries::TYPE_OBJECT_ALL);
                            $previouspending = $db->query();

                            if (!$previouspending) { 
                                throw new Exception("No existen datos para descargar");
                            } else {

                                $resultArray = Array();
                                foreach ($previouspending as $value) {
                                    $valuesObj = new PreviousList($value->id_prev, $value->num_refe, $value->num_guia, $value->rfc_importador , $value->nombre_importador);
                                    $resultArray [] = $valuesObj;
                                }
                                if($resultArray){

                                    $this->setSuccess(true);
                                    return $resultArray;
                                } else {
                                    throw new Exception("No existen referencias para este cliente y aduana");
                                }   
                            } 
                        } else {
                            throw new Exception("No existen referencias para descargar");
                        }
                            
                        
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
     * @return the $aduana
     */
    public function getAduana()
    {
        return $this->aduana;
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
     * @return the $nom_movil
     */
    public function getNom_movil()
    {
        return $this->nom_movil;
    }

    /**
     * @return the $dep_asigna
     */
    public function getDep_asigna()
    {
        return $this->dep_asigna;
    }

    /**
     * @param string $nom_movil
     */
    public function setNom_movil($nom_movil)
    {
        $this->nom_movil = $nom_movil;
    }

    /**
     * @param string $dep_asigna
     */
    public function setDep_asigna($dep_asigna)
    {
        $this->dep_asigna = $dep_asigna;
    }

}

?>