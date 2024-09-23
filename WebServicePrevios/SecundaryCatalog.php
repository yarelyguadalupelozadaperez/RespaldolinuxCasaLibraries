<?php

/**
 * CasaLibraries CBodega
 * SecundaryCatalog SecundaryCatalog.php
 * SecundaryCatalog Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_CBodega
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			Jaime Santana Zaldivar
 * @version    		CBodega 1.0.0
 */
class SecundaryCatalog
{

    /**
     * Identifier of client
     *
     * @var integer
     */
    public $idClient;
    
   /**
     *
     * @var string
     */
    public $keyField;

    /**
     *
     * @var string
     */
    public $data;
    
    /**
     *
     * @var string
     */
    public $operation;
    
    /**
     *
     * @var ArrayAccess
     */
    private $dataJson;
    
    /**
     *
     * @var boolean
     */
    private $success;
    
    /**
     *
     * @var string
     */
    private $targetTable;

    /**
     * Constructor of the class
     *
     * @param integer $idClient            
     * @param string $keyOrCatalog            
     * @param string $key            
     * @param string $description            
     */
    public function __construct($idClient, $data, $operation, $keyField)
    {
        $this->setIdClient($idClient);
        $this->setData($data);
        $this->setOperation($operation);
        $this->setKeyField(strtolower($keyField));
    }
    
    /**
     * This method inserts or updates an specific catalog
     * 
     */
    public function processCatalog ()
    {
        $db = new PgsqlConnection();
        $tableArray = Array();
        
        foreach ($this->getDataJson() as $keyTable => $valueTable) {
            $this->setTargetTable(strtolower($keyTable));
        }
        
        if($this->getOperation() == 1) {
            try {
                foreach ($this->getDataJson() as $table) {
                    $values = Array();
                    $values["idclient"] = $this->getIdClient();
                    $target = "cbodega." . $this->getTargetTable();
                    
                    foreach ($table as $field) {
                        $values["id"] = $this->getNewId();
                        foreach ($field as $key => $value) {
                            $values[strtolower($key)] = str_replace("'", " ", $value);
                        }
                        
                        $response = $db->insert($target, $values);
                    }
                }
                $this->setSuccess(true);
            } catch (Exception $e) {
                $this->setSuccess(false);
                return ($e->getMessage());
            }            
            
        } else {
            try {
                foreach ($this->getDataJson() as $table) {
                    $values = Array();
                    $target = "cbodega." . $this->getTargetTable();
            
                    foreach ($table as $field) {
                        foreach ($field as $key => $value) {
                            $values[strtolower($key)] = str_replace("'", " ", $value);
                        }
                        
                        $response = $db->update($target, $values, $params = "\"" . $this->getKeyField() . "\" = '" . $values[$this->getKeyField()] . "' AND idclient = " . $this->getIdClient());
                    }            
                }
                $this->setSuccess(true);
            } catch (Exception $e) {
                $this->setSuccess(false);
                return ($e->getMessage());
            }
        }
        
        return "Operacion exitosa.";
    }
    
    /**
     * This methos returns the new id of the relational table
     *
     * @param string $table
     * @return integer
     */
    private function getNewId()
    {
        try {
            $db = new PgsqlConnection();
            $sql = "SELECT MAX(id) AS maximum FROM cbodega." . $this->getTargetTable();
            $id = $db->execute($sql);
            return $id[0]["maximum"] + 1;
        } catch (\Exception $e) {
            $this->setSuccess(false);
            return ($e->getMessage());
        }
    }
    
    /**
     * This method returns the decode Json
     */
    public function decodeJson()
    {
        try {
            $this->setDataJson(json_decode($this->data, true));
        } catch (Exception $e) {
            $this->setSuccess(false);
            return ($e->getMessage());
        }
    }
    
    /**
     * @return the $idClient
     */
    public function getIdClient()
    {
        return $this->idClient;
    }

    /**
     * @return the $keyField
     */
    public function getKeyField()
    {
        return $this->keyField;
    }

    /**
     * @return the $data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return the $operation
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * @return the $dataJson
     */
    public function getDataJson()
    {
        return $this->dataJson;
    }

    /**
     * @return the $success
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * @return the $targetTable
     */
    public function getTargetTable()
    {
        return $this->targetTable;
    }

    /**
     * @param integer $idClient
     */
    public function setIdClient($idClient)
    {
        $this->idClient = $idClient;
    }

    /**
     * @param string $keyField
     */
    public function setKeyField($keyField)
    {
        $this->keyField = $keyField;
    }

    /**
     * @param string $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @param string $operation
     */
    public function setOperation($operation)
    {
        $this->operation = $operation;
    }

    /**
     * @param ArrayAccess $dataJson
     */
    public function setDataJson($dataJson)
    {
        $this->dataJson = $dataJson;
    }

    /**
     * @param boolean $success
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }

    /**
     * @param string $targetTable
     */
    public function setTargetTable($targetTable)
    {
        $this->targetTable = $targetTable;
    }

}
?>