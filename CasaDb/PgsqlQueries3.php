<?php
/**
 * CasaLibraries PgsqlConnection class
 * File PgsqlConnection.php
 * Connection to posgresql database
 *
 * @category 	CasaLibraries
 * @package 	CasaLibraries_CasaDb
 * @copyright 	Copyright (c) 2005-2013 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author 		Jaime Santana Zaldivar
 * @version 	PgsqlConnectionV2 1.0.0
 *
 */

/**
 *
 * @see Zend_Db
 */
include_once 'PgsqlConnectionV3.php';
include_once 'Table.php';

class PgsqlQueries3 extends PgsqlConnectionV3
{

    const TYPE_ARRAY_ONE = 'arrayOne';

    const TYPE_ARRAY_ALL = 'arrayAll';

    const TYPE_OBJECT_ONE = 'objectOne';
    
    const TYPE_OBJECT_ALL = 'objectAll';

    const TYPE_CLASS = 'class';

    /**
     * Target table
     *
     * @var string
     */
    private $_table;

    /**
     * Query fields
     *
     * @var array
     */
    private $_fields = Array();

    /**
     * Query joins
     *
     * @var string
     */
    private $_joins = "";

    /**
     * Query parameters
     *
     * @var string
     */
    private $_parameters = "";

    /**
     * Query values when is a insert or update
     *
     * @var array
     */
    private $_values = Array();

    /**
     * Query string
     *
     * @var string
     */
    private $_sql;

    /**
     * Return Type array|object|class
     *
     * @var string
     */
    private $_returnType = self::TYPE_ARRAY_ALL;

    /**
     * Class name when $returnType = 'class'
     *
     * @var string
     */
    private $_class = NULL;
    
    /**
     * Count row of the last query
     *
     * @var string
     */
    private $_countRow = 0;

    /**
     * Execute a query directly
     *
     * @param string $sql            
     */
    function execute()
    {
        return $this->getDb()->fetchAll($this->getSql());
    }
 
    /**
     * Return the created connection
     *
     * @return array
     */
    public function getDb()
    {
        return parent::getDb();
    }

    /**
     * Constructor of Querys with SELECT's and returns an array
     *
     * @return array result of the query
     */
    public function query()
    {
        $fields = "";
        if (count($this->getFields()) > 0) {
            foreach ($this->getFields() as $key => $value) {
                $value = str_replace("'", "\"", $value);
                $value = str_replace("|", "'", $value);
                $value = str_replace("#", "'", $value);
                $fields .= $value . ', ';
            }
            $fields = substr($fields, 0, - 2);
        } else {
            $fields = '*';
        }
        $table = str_replace("'", "\"", $this->getTable());
        $joins = str_replace("'", "\"", $this->getJoins());
        $parameters = str_replace("\'", "'", $this->getParameters());
        $parameters = str_replace('|', '"', $parameters);
        $sql = 'SELECT ' . $fields . ' FROM ' . $table . ' ' . $joins . ' WHERE ' . $parameters;
        
        try {
            switch ($this->getReturnType()) {
                case self::TYPE_OBJECT_ONE:
                    $stmt = $this->getDb()->query($sql);
                    $stmt->getDriverStatement()->setFetchMode(Zend_Db::FETCH_OBJ);
                    $result = $stmt->getDriverStatement()->fetch();
                    break;
                    
                case self::TYPE_OBJECT_ALL:
                    $this->getDb()->setFetchMode(PDO::FETCH_OBJ);
                    $result = $this->getDb()->fetchAll($sql);
                    break;
                    
                case self::TYPE_CLASS:
                    $stmt = $this->getDb()->query($sql);
                    
                    $table = new Table();
                    $table->setTableName($this->getTable());
                    
                    $stmt->getDriverStatement()->setFetchMode(Zend_Db::FETCH_INTO, $table);
                    $result = $stmt->getDriverStatement()->fetchAll();
                    break;
                
                case self::TYPE_ARRAY_ONE:
                    $stmt = $this->getDb()->query($sql);
                    $stmt->getDriverStatement()->setFetchMode(Zend_Db::FETCH_ASSOC);
                    $result = $stmt->getDriverStatement()->fetch();
                    break;
                
                default:
                    $this->getDb()->setFetchMode(PDO::FETCH_ASSOC);
                    $result = $this->getDb()->fetchAll($sql);
                    break;
            }
            
            $this->setCountRow($result);
            
            return $result;
        } catch (Exception $e) {
            print_r(utf8_decode($e->getMessage()));
            exit();
        }
    }

    public function queryParametrize($where, $paramsArray){
        $fields = "";
        if (count($this->getFields()) > 0) {
            foreach ($this->getFields() as $key => $value) {
                $value = str_replace("'", "\"", $value);
                $value = str_replace("|", "'", $value);
                $value = str_replace("#", "'", $value);
                $fields .= $value . ', ';
            }
            $fields = substr($fields, 0, - 2);
        } else {
            $fields = '*';
        }
        $table = str_replace("'", "\"", $this->getTable());
        $joins = str_replace("'", "\"", $this->getJoins());
        $parameters = str_replace("\'", "'", $this->getParameters());
        $parameters = str_replace('|', '"', $parameters);
        $sql = 'SELECT ' . $fields . ' FROM ' . $table . ' ' . $joins . ' WHERE ' . $where;
        
        try {
            switch ($this->getReturnType()) {
                case self::TYPE_OBJECT_ONE:
                    $stmt = $this->getDb()->query($sql,$paramsArray);
                    $stmt->getDriverStatement()->setFetchMode(Zend_Db::FETCH_OBJ);
                    $result = $stmt->getDriverStatement()->fetch();
                    break;
                    
                case self::TYPE_OBJECT_ALL:
                    $this->getDb()->setFetchMode(PDO::FETCH_OBJ);
                    $result = $this->getDb()->fetchAll($sql, $paramsArray);
                    break;
                    
                case self::TYPE_CLASS:
                    $stmt = $this->getDb()->query($sql, $paramsArray);
                    
                    $table = new Table();
                    $table->setTableName($this->getTable());
                    
                    $stmt->getDriverStatement()->setFetchMode(Zend_Db::FETCH_INTO, $table);
                    $result = $stmt->getDriverStatement()->fetchAll();
                    break;
                
                case self::TYPE_ARRAY_ONE:
                    $stmt = $this->getDb()->query($sql, $paramsArray);
                    $stmt->getDriverStatement()->setFetchMode(Zend_Db::FETCH_ASSOC);
                    $result = $stmt->getDriverStatement()->fetch();
                    break;
                
                default:
                    $this->getDb()->setFetchMode(PDO::FETCH_ASSOC);
                    $result = $this->getDb()->fetchAll($sql, $paramsArray);
                    break;
            }
            
            $this->setCountRow($result);
            
            return $result;
        } catch (Exception $e) {
            print_r(utf8_decode($e->getMessage()));
            exit();
        }
    }

    /**
     * Debug of the constructed query
     * sqlVista
     *
     * @return string
     */
    function queryDebug()
    {
        $fields = "";
        if (count($this->getFields()) > 0) {
            foreach ($this->getFields() as $key => $value) {
                $value = str_replace("'", "\"", $value);
                $value = str_replace("|", "'", $value);
                $fields .= $value . ', ';
            }
            $fields = substr($fields, 0, - 2);
        } else {
            $fields = '*';
        }
        $table = str_replace("'", "\"", $this->getTable());
        $joins = str_replace("'", "\"", $this->getJoins());
        $parameters = str_replace("\'", "'", $this->getParameters());
        $parameters = str_replace('|', '"', $parameters);
        $sql = 'SELECT ' . $fields . ' FROM ' . $table . ' ' . $joins . ' WHERE ' . $parameters;
        
        print_r($sql);
    }

    /**
     * Inserts the values in the indicated table and returns the generated id
     *
     * @param string $currval            
     * @return bool
     */
    function insert($currval = "")
    {
        if ($this->getDb()->insert($this->getTable(), $this->getValues())) {
            // return the last value generated by an auto-increment column
            if ($currval != "") {
                $id = $this->execute("SELECT CURRVAL('$currval') AS result");
                return $id[0]["result"];
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * Update the values in the indicated table
     */
    function update()
    {
        return $this->getDb()->update($this->getTable(), $this->getValues(), $this->getParameters());
    }

    /**
     * Delete rows into indicated table taking the indicated params
     */
    function delete()
    {
        return $this->getDb()->delete($this->getTable(), $this->getParameters());
    }

    /**
     * Build a transaction with a not-number of queries
     *
     * @param array $queries            
     */
    function transaction($queries)
    {
        $this->getDb()->beginTransaction();
        try {
            foreach ($queries as $key => $query) {
                $this->getDb()->query($query);
            }
            $this->getDb()->commit();
        } catch (Exception $e) {
            $this->getDb()->rollBack();
            return $e->getMessage();
        }
    }

    /**
     *
     * @return the $table
     */
    public function getTable()
    {
        return $this->_table;
    }

    /**
     *
     * @return the $_fields
     */
    public function getFields()
    {
        return $this->_fields;
    }

    /**
     *
     * @return the $joins
     */
    public function getJoins()
    {
        return $this->_joins;
    }

    /**
     *
     * @return the $parameters
     */
    public function getParameters()
    {
        return $this->_parameters;
    }

    /**
     *
     * @param string $table            
     */
    public function setTable($_table)
    {
        $this->_table = $_table;
    }

    /**
     *
     * @param array $fields            
     */
    public function setFields($_fields)
    {
        $this->_fields = $_fields;
    }

    /**
     *
     * @param array $joins            
     */
    public function setJoins($_joins)
    {
        $this->_joins = $_joins;
    }

    /**
     *
     * @param array $parameters            
     */
    public function setParameters($_parameters)
    {
        $this->_parameters = $_parameters;
    }

    public function setField($field)
    {
        $this->_fields[] = $field;
    }

    public function setJoin($join)
    {
        $this->_joins .= " " . $join;
    }

    public function setParameter($operator, $field)
    {
        $this->_parameters .= " $operator " . $field;
    }

    /**
     *
     * @return the $sql
     */
    public function getSql()
    {
        return $this->_sql;
    }

    /**
     *
     * @param string $sql            
     */
    public function setSql($_sql)
    {
        $this->_sql = $_sql;
    }

    /**
     *
     * @return the $returnType
     */
    public function getReturnType()
    {
        return $this->_returnType;
    }

    /**
     *
     * @param string $returnType            
     */
    public function setReturnType($_returnType)
    {
        $this->_returnType = $_returnType;
    }

    /**
     *
     * @return the $values
     */
    public function getValues()
    {
        return $this->_values;
    }

    /**
     *
     * @param array $values            
     */
    public function setValues($_values)
    {
        $this->_values = $_values;
    }

    /**
     *
     * @return the $_class
     */
    public function getClass()
    {
        return $this->_class;
    }

    /**
     *
     * @param string $_class            
     */
    public function setClass($_class)
    {
        $this->_class = $_class;
    }
    
    /**
     * @return the $_countRow
     */
    public function getCountRow()
    {
        return $this->_countRow;
    }

    /**
     * @param string $_countRow
     */
    public function setCountRow($_countRow)
    {
        $this->_countRow = count($_countRow);
    }

    public function querySpecial()

 

    {
 
  
 
        $fields = "";
 
  
 
        if (count($this->getFields()) > 0) {
 
  
 
            foreach ($this->getFields() as $key => $value) {
 
  
 
                $value = str_replace("'", "\"", $value);
 
  
 
                $value = str_replace("#", "'", $value);
 
  
 
                $fields .= $value . ', ';
 
  
 
            }
 
  
 
            $fields = substr($fields, 0, - 2);
 
  
 
        } else {
 
  
 
            $fields = '*';
 
  
 
        }
 
  
 
        $table = str_replace("'", "\"", $this->getTable());
 
  
 
        $joins = str_replace("'", "\"", $this->getJoins());
 
  
 
        $parameters = str_replace("\'", "'", $this->getParameters());
 
  
 
        $sql = 'SELECT ' . $fields . ' FROM ' . $table . ' ' . $joins . ' WHERE ' . $parameters;
 
  
 
        
 
  
 
        try {
 
  
 
            switch ($this->getReturnType()) {
 
  
 
                case self::TYPE_OBJECT_ONE:
 
  
 
                    $stmt = $this->getDb()->query($sql);
 
  
 
                    $stmt->getDriverStatement()->setFetchMode(Zend_Db::FETCH_OBJ);
 
  
 
                    $result = $stmt->getDriverStatement()->fetch();
 
  
 
                    break;
 
  
 
                    
 
  
 
                case self::TYPE_OBJECT_ALL:
 
  
 
                    $this->getDb()->setFetchMode(PDO::FETCH_OBJ);
 
  
 
                    $result = $this->getDb()->fetchAll($sql);
 
  
 
                    break;
 
  
 
                    
 
  
 
                case self::TYPE_CLASS:
 
  
 
                    $stmt = $this->getDb()->query($sql);
 
  
 
                    
 
  
 
                    $table = new Table();
 
  
 
                    $table->setTableName($this->getTable());
 
  
 
                    
 
  
 
                    $stmt->getDriverStatement()->setFetchMode(Zend_Db::FETCH_INTO, $table);
 
  
 
                    $result = $stmt->getDriverStatement()->fetchAll();
 
  
 
                    break;
 
  
 
                
 
  
 
                case self::TYPE_ARRAY_ONE:
 
  
 
                    $stmt = $this->getDb()->query($sql);
 
  
 
                    $stmt->getDriverStatement()->setFetchMode(Zend_Db::FETCH_ASSOC);
 
  
 
                    $result = $stmt->getDriverStatement()->fetch();
 
  
 
                    break;
 
  
 
                
 
  
 
                default:
 
  
 
                    $this->getDb()->setFetchMode(PDO::FETCH_ASSOC);
 
  
 
                    $result = $this->getDb()->fetchAll($sql);
 
  
 
                    break;
 
  
 
            }

 
            $this->setCountRow($result);

            return $result;
 
  
 
        } catch (Exception $e) {
 
  
 
            print_r(utf8_decode($e->getMessage()));
 
  
 
            exit();
 
  
 
        }
 
  
 
    }

    
}

?>
