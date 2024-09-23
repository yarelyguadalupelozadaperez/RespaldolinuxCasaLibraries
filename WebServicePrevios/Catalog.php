<?php

/**
 * CasaLibraries CBodega
 * File Catalog.php
 * Catalog Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_CBodega
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			Jaime Santana Zaldivar
 * @version    		CBodega 1.0.0
 */
class Catalog
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
    public $keyOrCatalog;

    /**
     *
     * @var string
     */
    public $key;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var string
     */
    public $deregisterDate;

    /**
     *
     * @var boolean
     */
    private $success;

    /**
     *
     * @var string
     */
    private $table;

    /**
     * Constructor of the class
     *
     * @param integer $idClient            
     * @param string $keyOrCatalog            
     * @param string $key            
     * @param string $description            
     */
    public function __construct($idClient, $keyOrCatalog, $key, $description, $deregisterDate)
    {
        $this->setIdClient($idClient);
        $this->setKeyOrCatalog($keyOrCatalog);
        $this->setKey($key);
        $this->setDescription($description);
        $this->setDeregisterDate($deregisterDate);
    }

    /**
     * This method adds a new register in catalog and returns a string
     *
     * @return string
     */
    public function addInCatalog()
    {
        $db = new PgsqlConnection();
        
        switch ($this->getKeyOrCatalog()) {
            case 'ICO':
                $this->setTable('cat_ico');
                if ($this->getDeregisterDate() == NULL || $this->getDeregisterDate() == "") {
                    $sql = "INSERT INTO cbodega." . $this->getTable() . " VALUES (" . $this->getNewId() . ", '" . $this->getKey() . "', '" . $this->getDescription() . "', NULL)";
                } else {
                    $sql = "INSERT INTO cbodega." . $this->getTable() . " VALUES (" . $this->getNewId() . ", '" . $this->getKey() . "', '" . $this->getDescription() . "', '" . $this->getDeregisterDate() . "')";
                }
                break;
            
            case 'VIN':
                $this->setTable('cat_vinculacion');
                if ($this->getDeregisterDate() == NULL || $this->getDeregisterDate() == "") {
                    $sql = "INSERT INTO cbodega." . $this->getTable() . " VALUES (" . $this->getNewId() . ", '" . $this->getKey() . "', '" . $this->getDescription() . "', NULL)";
                } else {
                    $sql = "INSERT INTO cbodega." . $this->getTable() . " VALUES (" . $this->getNewId() . ", '" . $this->getKey() . "', '" . $this->getDescription() . "', '" . $this->getDeregisterDate() . "')";
                }
                break;
            
            case 'VAL':
                $this->setTable('cat_valor');
                if ($this->getDeregisterDate() == NULL || $this->getDeregisterDate() == "") {
                    $sql = "INSERT INTO cbodega." . $this->getTable() . " VALUES (" . $this->getNewId() . ", '" . $this->getKey() . "', '" . $this->getDescription() . "', NULL)";
                } else {
                    $sql = "INSERT INTO cbodega." . $this->getTable() . " VALUES (" . $this->getNewId() . ", '" . $this->getKey() . "', '" . $this->getDescription() . "', '" . $this->getDeregisterDate() . "')";
                }
                break;
            
            case 'TIPBULT':
                $this->setTable('cat_bulto');
                if ($this->getDeregisterDate() == NULL || $this->getDeregisterDate() == "") {
                    $sql = "INSERT INTO cbodega." . $this->getTable() . " VALUES (" . $this->getNewId() . ", " . $this->getIdClient() . ", '" . $this->getKey() . "', '" . $this->getDescription() . "', NULL)";
                } else {
                    $sql = "INSERT INTO cbodega." . $this->getTable() . " VALUES (" . $this->getNewId() . ", " . $this->getIdClient() . ", '" . $this->getKey() . "', '" . $this->getDescription() . "', '" . $this->getDeregisterDate() . "')";
                }
                break;
            
            case 'TRANSP':
                $this->setTable('cat_transportistabodega');
                if ($this->getDeregisterDate() == NULL || $this->getDeregisterDate() == "") {
                    $sql = "INSERT INTO cbodega." . $this->getTable() . " VALUES (" . $this->getNewId() . ", " . $this->getIdClient() . ", '" . $this->getKey() . "', '" . $this->getDescription() . "', NULL)";
                } else {
                    $sql = "INSERT INTO cbodega." . $this->getTable() . " VALUES (" . $this->getNewId() . ", " . $this->getIdClient() . ", '" . $this->getKey() . "', '" . $this->getDescription() . "', '" . $this->getDeregisterDate() . "')";
                }
                break;
                
            case 'TRAN':
                $this->setTable('cat_transportista');
                if ($this->getDeregisterDate() == NULL || $this->getDeregisterDate() == "") {
                    $sql = "INSERT INTO cbodega." . $this->getTable() . " VALUES (" . $this->getNewId() . ", " . $this->getIdClient() . ", '" . $this->getKey() . "', '" . $this->getDescription() . "', NULL)";
                } else {
                    $sql = "INSERT INTO cbodega." . $this->getTable() . " VALUES (" . $this->getNewId() . ", " . $this->getIdClient() . ", '" . $this->getKey() . "', '" . $this->getDescription() . "', '" . $this->getDeregisterDate() . "')";
                }
                break;
            
            case 'EQUP':
                $this->setTable('cat_equipo');
                if ($this->getDeregisterDate() == NULL || $this->getDeregisterDate() == "") {
                    $sql = "INSERT INTO cbodega." . $this->getTable() . " VALUES (" . $this->getNewId() . ", " . $this->getIdClient() . ", '" . $this->getKey() . "', '" . $this->getDescription() . "', NULL)";
                } else {
                    $sql = "INSERT INTO cbodega." . $this->getTable() . " VALUES (" . $this->getNewId() . ", " . $this->getIdClient() . ", '" . $this->getKey() . "', '" . $this->getDescription() . "', '" . $this->getDeregisterDate() . "')";
                }
                break;
            
            default:
                $this->setTable(strtolower($this->getKeyOrCatalog()));
                if ($this->getDeregisterDate() == NULL || $this->getDeregisterDate() == "") {
                    $sql = "INSERT INTO cbodega." . $this->getTable() . " VALUES (" . $this->getNewId() . ", " . $this->getIdClient() . ", '" . $this->getKey() . "', '" . $this->getDescription() . "', NULL)";
                } else {
                    $sql = "INSERT INTO cbodega." . $this->getTable() . " VALUES (" . $this->getNewId() . ", " . $this->getIdClient() . ", '" . $this->getKey() . "', '" . $this->getDescription() . "', '" . $this->getDeregisterDate() . "')";
                }
                break;
        }
        
        try {
            $response = $db->execute($sql);
            $this->setSuccess(true);
            return "El registro fue agregado correctamente.";
        } catch (Exception $e) {
            $this->setSuccess(false);
            return ($e->getMessage());
        }
    }

    /**
     * This method adds a new register in catalog and returns a string
     *
     * @return string
     */
    public function updateInCatalog()
    {
        $db = new PgsqlConnection();
        $table = "";
        
        switch ($this->getKeyOrCatalog()) {
            case 'ICO':
                $this->setTable('cat_ico');
                $fields = $this->getFields();
                $id = $this->getIdOfRegister();
                if ($this->getDeregisterDate() == NULL || $this->getDeregisterDate() == "") {
                    $sql = "UPDATE cbodega." . $this->getTable() . " SET " . $fields[1] . " = '" . $this->getKey() . "', " . $fields[2] . " = '" . $this->getDescription() . "', " . $fields[3] . " = NULL WHERE id = " . $id;
                } else {
                    $sql = "UPDATE cbodega." . $this->getTable() . " SET " . $fields[1] . " = '" . $this->getKey() . "', " . $fields[2] . " = '" . $this->getDescription() . "', " . $fields[3] . " = '" . $this->getDeregisterDate() . "' WHERE id = " . $id;
                }
                break;
            
            case 'VINC':
                $this->setTable('cat_vinculacion');
                $fields = $this->getFields();
                $id = $this->getIdOfRegister();
                if ($this->getDeregisterDate() == NULL || $this->getDeregisterDate() == "") {
                    $sql = "UPDATE cbodega." . $this->getTable() . " SET " . $fields[1] . " = '" . $this->getKey() . "', " . $fields[2] . " = '" . $this->getDescription() . "', " . $fields[3] . " = NULL WHERE id = " . $id;
                } else {
                    $sql = "UPDATE cbodega." . $this->getTable() . " SET " . $fields[1] . " = '" . $this->getKey() . "', " . $fields[2] . " = '" . $this->getDescription() . "', " . $fields[3] . " = '" . $this->getDeregisterDate() . "' WHERE id = " . $id;
                }
                break;
            
            case 'VAL':
                $this->setTable('cat_valor');
                $fields = $this->getFields();
                $id = $this->getIdOfRegister();
                if ($this->getDeregisterDate() == NULL || $this->getDeregisterDate() == "") {
                    $sql = "UPDATE cbodega." . $this->getTable() . " SET " . $fields[1] . " = '" . $this->getKey() . "', " . $fields[2] . " = '" . $this->getDescription() . "', " . $fields[3] . " = NULL WHERE id = " . $id;
                } else {
                    $sql = "UPDATE cbodega." . $this->getTable() . " SET " . $fields[1] . " = '" . $this->getKey() . "', " . $fields[2] . " = '" . $this->getDescription() . "', " . $fields[3] . " = '" . $this->getDeregisterDate() . "' WHERE id = " . $id;
                }
                break;
            
            case 'TIPBULT':
                $this->setTable('cat_bulto');
                $fields = $this->getFields();
                $id = $this->getIdOfRegister();
                if ($this->getDeregisterDate() == NULL || $this->getDeregisterDate() == "") {
                    $sql = "UPDATE cbodega." . $this->getTable() . " SET " . $fields[1] . " = " . $this->getIdClient() . ", " . $fields[2] . " = '" . $this->getKey() . "', " . $fields[3] . " = '" . $this->getDescription() . "', " . $fields[4] . " = NULL WHERE id = " . $id;
                } else {
                    $sql = "UPDATE cbodega." . $this->getTable() . " SET " . $fields[1] . " = " . $this->getIdClient() . ", " . $fields[2] . " = '" . $this->getKey() . "', " . $fields[3] . " = '" . $this->getDescription() . "', " . $fields[4] . " = '" . $this->getDeregisterDate() . "' WHERE id = " . $id;
                }
                break;
            
            case 'TRANSP':
                $this->setTable('cat_equipo');
                $fields = $this->getFields();
                $id = $this->getIdOfRegister();
                if ($this->getDeregisterDate() == NULL || $this->getDeregisterDate() == "") {
                    $sql = "UPDATE cbodega." . $this->getTable() . " SET " . $fields[1] . " = " . $this->getIdClient() . ", " . $fields[2] . " = '" . $this->getKey() . "', " . $fields[3] . " = '" . $this->getDescription() . "', " . $fields[4] . " = NULL WHERE id = " . $id;
                } else {
                    $sql = "UPDATE cbodega." . $this->getTable() . " SET " . $fields[1] . " = " . $this->getIdClient() . ", " . $fields[2] . " = '" . $this->getKey() . "', " . $fields[3] . " = '" . $this->getDescription() . "', " . $fields[4] . " = '" . $this->getDeregisterDate() . "' WHERE id = " . $id;
                }
                break;
            
            case 'EQUI':
                $this->setTable('cat_transportista');
                $fields = $this->getFields();
                $id = $this->getIdOfRegister();
                if ($this->getDeregisterDate() == NULL || $this->getDeregisterDate() == "") {
                    $sql = "UPDATE cbodega." . $this->getTable() . " SET " . $fields[1] . " = " . $this->getIdClient() . ", " . $fields[2] . " = '" . $this->getKey() . "', " . $fields[3] . " = '" . $this->getDescription() . "', " . $fields[4] . " = NULL WHERE id = " . $id;
                } else {
                    $sql = "UPDATE cbodega." . $this->getTable() . " SET " . $fields[1] . " = " . $this->getIdClient() . ", " . $fields[2] . " = '" . $this->getKey() . "', " . $fields[3] . " = '" . $this->getDescription() . "', " . $fields[4] . " = '" . $this->getDeregisterDate() . "' WHERE id = " . $id;
                }
                break;
            
            default:
                $this->setTable(strtolower($this->getKeyOrCatalog()));
                $fields = $this->getFields();
                $id = $this->getIdOfRegister();
                if ($this->getDeregisterDate() == NULL || $this->getDeregisterDate() == "") {
                    $sql = "UPDATE cbodega." . $this->getTable() . " SET " . $fields[1] . " = " . $this->getIdClient() . ", " . $fields[2] . " = '" . $this->getKey() . "', " . $fields[3] . " = '" . $this->getDescription() . "', " . $fields[4] . " = NULL WHERE id = " . $id;
                } else {
                    $sql = "UPDATE cbodega." . $this->getTable() . " SET " . $fields[1] . " = " . $this->getIdClient() . ", " . $fields[2] . " = '" . $this->getKey() . "', " . $fields[3] . " = '" . $this->getDescription() . "', " . $fields[4] . " = '" . $this->getDeregisterDate() . "' WHERE id = " . $id;
                }
                break;
        }
        
        try {
            $response = $db->execute($sql);
            $this->setSuccess(true);
            return "El registro fue actualizado correctamente.";
        } catch (Exception $e) {
            $this->setSuccess(false);
            return ($e->getMessage());
        }
    }

    public function deleteInCatalog()
    {
        $db = new PgsqlConnection();
        $table = "";
        
        switch ($this->getKeyOrCatalog()) {
            case 'ICO':
                $this->setTable('cat_ico');
                break;
            
            case 'VINC':
                $this->setTable('cat_vinculacion');
                break;
            
            case 'VAL':
                $this->setTable('cat_valor');
                break;
            
            case 'TIPBULT':
                $this->setTable('cat_bulto');
                break;
            
            case 'TRANSP':
                $this->setTable('cat_equipo');
                break;
            
            case 'EQUI':
                $this->setTable('cat_transportista');
                break;
            
            default:
                $this->setTable(strtolower($this->getKeyOrCatalog()));
                break;
        }
        
        try {
            $fields = $this->getFields();
            $id = $this->getIdOfRegister();
            $sql = "DELETE FROM cbodega." . $this->getTable() . " WHERE id = " . $id;
            $response = $db->execute($sql);
            $this->setSuccess(true);
            return "El registro fue eliminado correctamente.";
        } catch (Exception $e) {
            $this->setSuccess(false);
            return ($e->getMessage());
        }
    }

    /**
     * Gets new Id from a table
     *
     * @return integer
     */
    private function getNewId()
    {
        try {
            $db = new PgsqlConnection();
            $sql = "SELECT MAX(id) AS maximum FROM cbodega." . $this->getTable();
            $id = $db->execute($sql);
            $idInteger = $id[0]["maximum"] + 1;
            return $idInteger;
        } catch (\Exception $e) {
            $this->setSuccess(false);
            return ($e->getMessage());
        }
    }

    /**
     * This method gets the fields of a table and returns an array of fields
     *
     * @return array
     */
    private function getFields()
    {
        try {
            $db = new PgsqlConnection();
            $sql = "SELECT column_name
                    FROM information_schema.columns c
                        LEFT JOIN information_schema.element_types e ON
                        c.table_catalog = e.object_catalog AND
                        c.table_schema = e.object_schema AND
                        c.table_name = e.object_name
                    WHERE c.table_name = '" . $this->getTable() . "'";
            
            $fields = $db->execute($sql);
            $fieldsArray = Array();
            
            foreach ($fields as $field) {
                $fieldsArray[] = $field["column_name"];
            }
            return $fieldsArray;
        } catch (\Exception $e) {
            $this->setSuccess(false);
            return ($e->getMessage());
        }
    }

    /**
     * This method get the id of a register and returns the id
     *
     * @return integer;
     */
    private function getIdOfRegister()
    {
        try {
            $db = new PgsqlConnection();
            $fields = $this->getFields();
            
            if (count($fields) == 3) {
                $sql = "SELECT id FROM cbodega." . $this->getTable() . " WHERE " . $fields[1] . " = '" . $this->getKey() . "'";
            } else {
                $sql = "SELECT id FROM cbodega." . $this->getTable() . " WHERE " . $fields[1] . " = '" . $this->getKey() . "' AND " . $fields[3] . " = " . $this->getIdClient();
            }
            
            $id = $db->execute($sql);
            
            return $id[0]["id"];
        } catch (\Exception $e) {
            $this->setSuccess(false);
            return ($e->getMessage());
        }
    }

    /**
     *
     * @return the $idClient
     */
    public function getIdClient()
    {
        return $this->idClient;
    }

    /**
     *
     * @return the $keyOrCatalog
     */
    public function getKeyOrCatalog()
    {
        return $this->keyOrCatalog;
    }

    /**
     *
     * @return the $key
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     *
     * @return the $description
     */
    public function getDescription()
    {
        return $this->description;
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
     * @param integer $idClient            
     */
    public function setIdClient($idClient)
    {
        $this->idClient = $idClient;
    }

    /**
     *
     * @param string $keyOrCatalog            
     */
    public function setKeyOrCatalog($keyOrCatalog)
    {
        $this->keyOrCatalog = $keyOrCatalog;
    }

    /**
     *
     * @param string $key            
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     *
     * @param string $description            
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
     * @return the $table
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     *
     * @param string $table            
     */
    public function setTable($table)
    {
        $this->table = $table;
    }

    /**
     *
     * @return the $deregisterDate
     */
    public function getDeregisterDate()
    {
        return $this->deregisterDate;
    }

    /**
     *
     * @param date $deregisterDate            
     */
    public function setDeregisterDate($deregisterDate)
    {
        $this->deregisterDate = $deregisterDate;
    }
}

?>