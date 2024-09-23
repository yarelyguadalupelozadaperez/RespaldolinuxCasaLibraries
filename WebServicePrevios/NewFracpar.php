<?php

/**
 * CasaLibraries NewFracpar
 * File NewFracpar.php
 * NewFracpar Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			AJPE
 * @version    		Previo 1.0.0
 */

require_once 'CtracFracpar.php';
require_once 'CasaLibraries/CasaDb/ConnectionFactory.class.php';
class NewFracpar
{
    /**
     *
     * @var integer
     */
    public $idclient;

    /**
     *
     * @var \CtracFracpar[]
     */
    public $ctracfracpar;

        public function __construct($idclient, $ctracfracpar)
    {
        $this->setIdclient($idclient);
        $this->setCtracfracpar($ctracfracpar);
    }

    public function NewFracpar()
    {
        try {
            $dbAdoP = ConnectionFactory::Connectpostgres();
            $ctracfracparArray = $this->getCtracfracpar();
            $id_cliente = $this->getIdclient();
    
            if ($this->getIdclient() == '' || empty($ctracfracparArray) == true)
            throw new Exception("Datos incompletos. \nPor favor enviar todos los datos requeridos.");
    
            $db = new PgsqlQueries();
            $db->setTable('general.casac_clientes');
            $db->setFields(array(
                'id_cliente'
            ));
            $db->setParameters("id_cliente = '" . $this->getIdclient() . "'");
            $response = $db->query();
    
            $id_cliente = $response[0]["id_cliente"];
    
            if (!$id_cliente>0) {
                throw new Exception("No existe el cliente");
            }
    
            $arrayImp = array();
            $arrayImpbad = array();
            $arrayImpbad1 = array();
            $arrayImpbad2 = array();
            $arrayImpbad3 = array();
            $arrayImpbad4 = array();
            $arrayImpbad5 = array();
            $arrayImpbad6 = array();
            $arrayImpbad7 = array();
            $arrayImpbad8 = array();
            $arrayImpbad9 = array();
            $arrayImpbad10 = array();
            $arrayImpbad11 = array();
            $repeatArray = array();
            $impArray = array();
            $proArray = array();
            $countRepeat = 0;
            $countTotal= 0;
            $countInsert = 0;
            $countErrorProveedor= 0;
            $countErrorImporter= 0;
            $id_proveedor = 0;
            foreach ($ctracfracparArray as $CtracFracParArray => $value) {
                $countTotal =  $countTotal+1;
                //$rfc_importador =  trim($value->rfc_importador);
                $num_part =  trim($value->num_part);
                $desc_merc =  trim($value->desc_merc);
                $num_fracc =  trim($value->num_fracc);
                $cve_nico =  trim($value->cve_nico);
                $cve_impo = trim($value->cve_impo);
                $cve_pro = trim($value->cve_pro);
                $tip_ope = $value->tip_ope;
                $val_part = $value->val_part;
                $uni_fact = $value->uni_fact; 
                $id_part = $value->id_part; 
                $filename = '../files/EPrevious/log/logClasif.log';
                $hour = date("G");
                $hour -= 1;

                $now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));
                $time = $now->format("Y-m-d ".$hour.":i:s.u");
               // file_put_contents($filename, $time.' - LOGGER-PHP - INFO - ENTRÉ A INSERTAR LA SIGUIENTE CLASIFICACIÓN. Cve_impo: '. $cve_impo . "- Tip ope: " . $value->tip_ope . " - Cve pro: " . $cve_pro .  "- Num_part: " . $num_part.  " - Num fracc: " . $num_fracc. "- Cve nico:" .  $cve_nico .  "- Desc merc: ". $desc_merc. " - Id part: " . $id_part. PHP_EOL.'', FILE_APPEND);
        
                if ($num_part == '' || $desc_merc == '' || $num_fracc == '' || $cve_nico == '' || $cve_impo = '' || $cve_pro == '' || $tip_ope == '' || $val_part == '' ) {
                    array_push($arrayImpbad1, " Num_part: ".$num_part);
                    array_push($arrayImpbad2, " Desc_merc: ".$desc_merc);
                    array_push($arrayImpbad3, " Num_fracc: ".$num_fracc);
                    array_push($arrayImpbad4, " Cve_nico: ".$cve_nico);
                    array_push($arrayImpbad6, " cve_impo: ".$cve_impo);
                    array_push($arrayImpbad7, " cve_pro: ".$cve_pro);
                    array_push($arrayImpbad8, " tip_ope: ".$tip_ope);
                    array_push($arrayImpbad9, " val_part: ".$val_part);
                    
                } else {
                    $num_part = trim($num_part);
                    $count = strlen($num_part);
                    if($count>50){
                        $num_part = substr($num_part, 0, 50);
                    }
                    
                    try {
                        $db->setTable('general.casac_importadores');
                        $db->setJoins("");
                        $db->setFields(array(
                            'id_importador',
                            'rfc_importador',
                            'status_importador'
                        ));
                        $db->setParameters("id_cliente = " . $this->getIdclient() . " AND clave_importador = '" . $value->cve_impo . "'  AND status_importador = 1");
                        $importer = $db->querySpecial();
                
    
                        if(count($importer) <= 0){
                            $countErrorImporter = $countErrorImporter+1;
                            file_put_contents($filename, $time . "- No se encontró importador con clave impo : " . $value->cve_impo . " al insertar el num part: " .$num_part. " del tipo de operación: " . $tip_ope . PHP_EOL.'', FILE_APPEND);
                            $stringImp = $value->cve_impo;
                            array_push($impArray, $stringImp);
                            $id_importer = 0;
        
                        }else{
                            $id_importer =  $importer[0]["id_importador"];
                        }
    
                    } catch (Exception $ex) {
                        $dbAdoP->rollbackTrans();
                        $this->setSuccess(false);
                        return "Ocurrió un error al consultar el importador. -  Clave_importador: " . $clave_importador  . " - Error: ". $ex->getMessage();
                    }
    
                  

                    $db->setTable('general.casac_proveedores');
                    $db->setFields(array(
                        'id_proveedor'
                    ));
                    $db->setParameters("id_cliente = " . $this->getIdclient() . " AND cve_prov = '" . $cve_pro . "' AND status_prov = 1");
                    $response = $db->querySpecial();


                    if (count($response) <= 0) {
                        $id_proveedor = 0;
                        $countErrorProveedor = $countErrorProveedor+1;
                        file_put_contents($filename, $time . "- No se encontró proveedor con clave prov : " . $value->cve_pro . " al insertar el num part: " .$num_part. " del tipo de operación: " . $tip_ope . PHP_EOL.'', FILE_APPEND);
                        $stringProv = $value->cve_pro;
                        array_push($proArray, $stringProv);
                        $value->id_fracpar =  0;

                    }else{
                        $id_proveedor = $response[0]["id_proveedor"];
                    }


                    if(($id_importer !== 0) && ($id_proveedor !== 0)){
                        try {

                           // file_put_contents($filename, $time.' - LOGGER-PHP - INFO - VOY A BUSCAR LA SIGUIENTE CLASIFICACIÓN. - Id importer:'. $id_importer . "- Tip ope: " . $tip_ope. " - Id proveedor: " . $id_proveedor .  "- Num_part: " .  " - Num fracc: " . $num_fracc. "- Cve nico:" .  $cve_nico .  "- Desc merc: ". $desc_merc .PHP_EOL.'', FILE_APPEND);
            
                            $db->setTable('previo.ctrac_fracpar');
                            $db->setFields(array(
                                'id_fracpar',
                                'cve_nico',
                                'desc_merc',
                                'num_part',
                                'tip_ope',
                                'uni_fact',
                                'val_part',
                                'id_importador',
                                'id_proveedor'

                            ));
            
                            $db->setParameters("id_cliente = " . $id_cliente . " AND id_importador=" . $id_importer . " AND tip_ope=" . $tip_ope . " AND id_proveedor=" . $id_proveedor . " AND num_part = '" . $num_part . "' AND num_fracc = '" . $num_fracc . "' AND cve_nico = '" . $cve_nico . "'"." AND desc_merc = '" . $desc_merc . "'");
                            $response = $db->query();
    
                        } catch (Exception $ex) {
                            $dbAdoP->rollbackTrans();
                            $this->setSuccess(false);
                            return "Ocurrió un error al consultar la información de ctrac_fracpar. - Id importador: " . $id_importer . "- Tip ope: " . $tip_ope. " - Id proveedor: " . $id_proveedor .  "- Num_part: " . $num_part ." - Num fracc: " . $num_fracc. "- Cve nico:" .  $cve_nico .  "- Desc merc: ". $desc_merc . " - Id part: " . $id_part. " - Error: ". $ex->getMessage();
                        }

                        if(count($response) > 0){
                            $id_fracpar = $response[0]["id_fracpar"];
                            $cve_nico = $response[0]["cve_nico"];
                            $desc_merc = $response[0]["desc_merc"];
                            $num_part = $response[0]["num_part"];
                            $tip_ope = $response[0]["tip_ope"];
                            $uni_fact = $response[0]["uni_fact"];
                            $val_part = $response[0]["val_part"];
                            $id_importador = $response[0]["id_importador"];
                            $id_proveedor = $response[0]["id_proveedor"];


                            $stringRepet = "id_fracpar: ". $id_fracpar. ", cve_nico: " .$cve_nico. ", desc_merc: ". $desc_merc . ", num_part: ".$num_part. ", tip_ope: ".$tip_ope. ", uni_fact: ".$uni_fact.", val_part: ".$val_part . ", id_importador: ". $id_importador .", id_proveedor: ". $id_proveedor ;
      
                            array_push($repeatArray, $stringRepet);

             
                            $value->id_fracpar =  $id_fracpar;
                            $countRepeat= $countRepeat+1;
                        } else {
                            try {
                                $num_partcove = $value->num_partcove;
                            
                                if($num_partcove == 1 || $num_partcove == 0) {
                                    $num_partcove = $num_partcove;
                                } else {
                                     $num_partcove = 0;
                                }

                                $sqlNewbult= "INSERT INTO previo.ctrac_fracpar(altfec_fracc, cve_nico, desc_merc, flag_fracc, num_fracc, num_part, num_partcove, origen_fracc, status_fracpar, val_part, id_cliente, id_importador, tip_ope, id_proveedor, uni_fact, id_part)"
                                . " VALUES('TODAY()', '$cve_nico', '$desc_merc', 0, '$num_fracc', '$num_part', $num_partcove, '0', '1', '$val_part', $id_cliente, $id_importer, '$tip_ope', $id_proveedor, '$uni_fact', $id_part)  RETURNING id_fracpar";
                                $save_bult = $dbAdoP->Execute ( $sqlNewbult );
                                
                                $id_fracparS = $save_bult->fields["id_fracpar"];
                            
                                $value->id_fracpar =  $id_fracparS;
                                $countInsert = $countInsert+1;

                                
                                $this->setCtracfracpar($ctracfracparArray);
                              
                            } catch (Exception $ex) {
                                $dbAdoP->rollbackTrans();
                                $this->setSuccess(false);
                                return "Ocurrió un error al guardar la información de ctrac_fracpar." . $ex->getMessage();
        
                            }
                        }
                    }
    
                    
                }
            }


            $buildResponseRep = implode(" \n  ", $repeatArray);
            $buildImpRep = implode(", ", $impArray);
            $buildProRep = implode(", ", $proArray);
            $sum = $countErrorProveedor + $countErrorImporter;
           
            $this->setSuccess(true);

            if(count($impArray) > 0)
            {
              $imp = "\n No existen: " .count($impArray). " importadores: " . $buildImpRep;  
            } else {
                $imp = "";  
            }

            if(count($proArray) > 0)
            {
                $pro = "\n No existen: " .count($proArray). " proveedores: " . $buildProRep;  
            } else {
                $pro = "";  
            }

            if($sum > 0){
               $messageError =  "\n - No se insertaron algunos registros porque: $imp $pro" ;
               $this->setSuccess(false);
            } else {
                $messageError = "";
            }

            if($countRepeat > 0){
                $messageRepeatData = "\n - Repetidos:" .  $countRepeat . "-> \n ". $buildResponseRep;
                $this->setSuccess(false);
            }else {
                $messageRepeatData = "";
            }

            if (empty($arrayImpbad1)) {
                $messageIncompleteData = "";
            } else {
                $count = count($arrayImpbad1);
                $arrayResponse = array();
    
            
                for ($i=0; $i < $count; $i++) { 
                    array_push( $arrayResponse, $arrayImpbad1[$i].$arrayImpbad2[$i].$arrayImpbad3[$i].$arrayImpbad4[$i].$arrayImpbad5[$i].$arrayImpbad6[$i].$arrayImpbad6[$i].$arrayImpbad7[$i].$arrayImpbad8[$i].$arrayImpbad9[$i]);
                }
                $buildResponse = implode(" \n  ", $arrayResponse);
                $buildResponseRep = implode(" \n  ", $repeatArray);

                if($count > 0){
                    $messageIncompleteData = "\n - Incompletos: " .$count . " ->  \n". $buildResponse ;
                    $this->setSuccess(false);
                } else {
                    $messageIncompleteData = "";
                }
               
            }

            if($this->getSuccess() == true){
                $resultOperation = "Operación exitosa. \n";
            } else {
                $resultOperation = "Ocurrieron errores en la inserción, se recomienda verificar o volver a realizar la carga. \n";
            }
        
            file_put_contents($filename, $time . $resultOperation . " - " .  $countInsert. " insertados de " .  $countTotal.  $messageError.  $messageRepeatData . $messageIncompleteData, PHP_EOL.'', FILE_APPEND);
            $sum = $countErrorProveedor + $countErrorImporter;
            return  $resultOperation . " - " .  $countInsert. " insertados de " .  $countTotal.  $messageError.  $messageRepeatData . $messageIncompleteData."\n" ;


        } catch (Exception $ex) {
            $dbAdoP->rollbackTrans();
            $this->setSuccess(false);
            return "Ocurrió un error al agregar la información de ctrac_fracpar. - Error: ". $ex->getMessage();
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
     * @return the $ctracfracpar
     */
    public function getCtracfracpar()
    {
        return $this->ctracfracpar;
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
     * @param CtracFracpar[] $ctracfracpar            
     */
    public function setCtracfracpar($ctracfracpar)
    {
        $this->ctracfracpar = $ctracfracpar;
    }
    
    /**
     * @param boolean $success
     */
    private function setSuccess($success)
    {
        $this->success = $success;
    }
}
?>
