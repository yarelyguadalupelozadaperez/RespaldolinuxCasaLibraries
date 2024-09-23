<?php

/**
 * CasaLibraries UpdateDataPart
 * File UpdateDataPart.php
 * UpdateDataPart Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			AJPE
 * @version    		Previo 1.0.0
 */
require_once 'Invoice2.php';
require_once 'CasaLibraries/CasaDb/ConnectionFactory.class.php';

class UpdateDataPart {

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
    public $num_refe;

    /**
     *
     * @var string
     */
    public $obs_prev;

    /**
     *
     * @var \Invoice2[]
     */
    public $invoice2;

    public function __construct($idclient, $aduana, $patente, $num_refe, $obs_prev, $invoice2) {
        $this->setIdclient($idclient);
        $this->setAduana($aduana);
        $this->setPatente($patente);
        $this->setNum_refe($num_refe);
        $this->setObs_prev($obs_prev);
        $this->setInvoice2($invoice2);
    }

    public function updateDataPartidas() {
        $dbAdoP = ConnectionFactory::Connectpostgres();
        $id_cliente = $this->getIdclient();
        $InvoiceArray = $this->getInvoice2();
        $arrayImbad = array();
        $arrayImbad1 = array();
        $arrayImbad2 = array();
        $arrayImbad3 = array();
        $arrayImbad5 = array();
        $arrayImbad6 = array();
        $arrayImbad7 = array();
        $arrayImbad8 = array();
        $arrayImbad9 = array();
        $flagA = 0;
        $flagB = 0;

        if ($this->getIdclient() == '' || $this->getAduana() == '' || $this->getPatente() == "" || $this->getNum_refe() == "" || empty($InvoiceArray) == true)
            throw new Exception("Datos incompletos. \nPor favor enviar todos los datos requeridos.");

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
        $num_reference = $this->getNum_refe();

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

            if ($id_licencia > 0) {
                $status = $response->status_licencia;
                if ($status == 0) {
                    throw new Exception("La licencia del cliente se encuentra inactiva");
                }
            } else {
                throw new Exception("La licencia del cliente no se encuentra dada de alta. ID Cliente" . $this->getIdclient() . " - Patente: " . $patente . " - Aduana: " . $this->getAduana() . " - ID Aduana: " . $id_aduana . " - ID Licencia: " . $id_licencia . " - Estatus Licencia: " . $status);
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

            if ($id_prev > 0) {

                $db->setTable('previo.cprevo_previos');
                $db->setFields(array(
                    'id_importador'
                ));

                $db->setParameters("id_prev = '" . $id_prev . "'");
                $response = $db->query();


                try {
                    $dbu = new PgsqlConnection();
                    $sql = "UPDATE previo." . "cprevo_previos" . " SET obs_prev = '" . $this->getObs_prev() . "' WHERE id_prev = " . $id_prev;
                    $response = $dbu->execute($sql);
                } catch (Exception $e) {
                    $this->setSuccess(false);
                    return "Operación fallida. No se pudieron actualizar las observaciones: " . $e->getMessage();
                   // return false;
                }


                $id_importador = $response[0]["id_importador"];
            } else {
                throw new Exception("La referencia no existe num_refe: " . $num_reference);
            }
            $arrayExeption = array();
            foreach ($InvoiceArray as $key => $value) {
                $cons_fact = $value->cons_fact;
                $num_fact = trim($value->num_fact);
                $InvoiceProductsUpdate = $value->productsupdate;

                $db->setTable('previo.cprevo_factur');
                $db->setFields(array(
                    'id_factur'
                ));

                $db->setParameters("id_prev = '" . $id_prev . "'  AND cons_fact = '" . $cons_fact . "' ");
                $responseFac = $db->query();

                $id_factur = $responseFac[0]["id_factur"];

                if ($id_factur > 0) {
                    
                } else {
                    throw new Exception("No existe la factura. cons_fact: " . $cons_fact);
                }

                $arrayPart = array();
                $arrayPart2 = array();
                foreach ($InvoiceProductsUpdate as $productosUpdate => $value) {

                    $cons_part = $value->cons_part;
                    $num_part = $value->num_part;

                    $InvoiceObjectNew = $value->objectnewvalues;
                    $InvoiceClasificaciones = $value->clasificaciones;

                    $db->setTable('previo.cprevo_facpar');
                    $db->setFields(array(
                        'id_partida'
                    ));

                    $db->setParameters("id_factur = '" . $id_factur . "'  AND cons_part = '" . $cons_part . "'");
                    $responsePart = $db->query();
     
     
                    if (!empty($responsePart)) {
                        $id_partida = $responsePart[0]["id_partida"];

                        $arrayColumn = array();
                        $arrayValue = array();
                        $arrayFusion = array();
                  
                        foreach ($InvoiceObjectNew as $keyION => $objectNew) {
                            $column = $objectNew->column;
                            $column = strtolower($column);
                            $value = trim($objectNew->value);

                            $db->setTable('information_schema.columns');
                            $db->setFields(array(
                                'column_name'
                            ));

                            $db->setParameters("table_schema='previo' AND table_name='cprevo_facpar' AND column_name = '" . $column . "'");
                            $response = $db->query();

                            $column_name = $response[0]["column_name"];

                            if (!empty($column_name)) {
                                array_push($arrayColumn, $column_name);
                                array_push($arrayValue, $value);
                            } else {
                                array_push($arrayExeption, $column);
                            }

                            
                        }

                            $count = count($arrayColumn);
                            
                            for ($i = 0; $i < $count; $i++) {
                                $buildUpdate = $arrayColumn[$i] . " = '" . $arrayValue[$i] . "'";
                                array_push($arrayFusion, $buildUpdate);
                            }
                            
                            $buildUpdateQuery = implode(" , ", $arrayFusion);
                            

                            if($count == 0){
                            }else{
                                try {
                                    $dbu = new PgsqlConnection();
                                    $sql = "UPDATE previo." . "cprevo_facpar" . " SET " . $buildUpdateQuery . " WHERE id_partida = " . $id_partida;
                                    $response = $dbu->execute($sql);
                                } catch (Exception $e) {
                                    $this->setSuccess(false);
                                    return "Operación fallida. No se pudo actualizar la partida: " . $e->getMessage();
                                    return false;
                                }
                            }
                            
                    } else {

                        array_push($arrayPart, $cons_part);
                        array_push($arrayPart2, $num_part);
                    }
                }
            }
          
            $buildce = implode(" , ", $arrayExeption);
            if (empty($arrayPart) && empty($arrayExeption) && empty($arrayImbad)) {
                $this->setSuccess(true);
                return "El registro fue actualizado correctamente.";
            } else if (!empty($arrayPart)) {
                $this->setSuccess(false);
                return "La partida no existe. cons_part : " . $cons_part . " num_part : " . $num_part;
            } else if (!empty($arrayExeption)) {
                $this->setSuccess(false);
                return "Esta columna no se encuentra en la tabla de previo.cprevo_facpar en la web. Por favor notificarlo a Desarrollo Web: colums: " . $buildce;
            }else {
                var_dump("hoalal2ssss222ala11111");
                exit;
            }
        } else {
            throw new Exception("La aduana no existe.");
        }
    }

    /**
     *
     * @return the $idclient
     */
    public function getIdclient() {
        return $this->idclient;
    }

    /**
     *
     * @return the $aduana
     */
    public function getAduana() {
        return $this->aduana;
    }

    /**
     *
     * @param string $patente            
     */
    public function getPatente() {
        return $this->patente;
    }

    /**
     *
     * @return the $num_refe
     */
    public function getNum_refe() {
        return $this->num_refe;
    }

    /**
     *
     * @return the $invoice2
     */
    public function getInvoice2() {
        return $this->invoice2;
    }

    /**
     *
     * @return the $success
     */
    public function getSuccess() {
        return $this->success;
    }

    /**
     *
     * @param number $idclient            
     */
    public function setIdclient($idclient) {
        $this->idclient = $idclient;
    }

    /**
     *
     * @param string $aduana            
     */
    public function setAduana($aduana) {
        $this->aduana = $aduana;
    }

    /**
     *
     * @param string $patente            
     */
    public function setPatente($patente) {
        $this->patente = $patente;
    }

    /**
     *
     * @param string $num_refe            
     */
    public function setNum_refe($num_refe) {
        $this->num_refe = $num_refe;
    }

    /**
     *
     * @param Invoice2[] $invoice2            
     */
    public function setInvoice2($invoice2) {
        $this->invoice2 = $invoice2;
    }

    /**
     *
     * @param boolean $success            
     */
    public function setSuccess($success) {
        $this->success = $success;
    }

    /**
     *
     * @return the $obs_prev
     */
    public function getObs_prev() {
        return $this->obs_prev;
    }

    /**
     *
     * @param string $obs_prev           
     */
    public function setObs_prev($obs_prev ) {
        $this->obs_prev  = $obs_prev ;
    }


}

?>
