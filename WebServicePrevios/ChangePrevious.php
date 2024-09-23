<?php

include_once 'File.php';

class ChangePrevious
{
    /**
     *
     * @var string
     */
    public $num_refeTemp;
    
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
     * Array of Previous objects
     *
     * @var array
     */
    private $arrayPrevious;
    
    
    /**
     *
     * @param integer $idprevious
     * @param integer $idgdb
     */
    public function __construct($num_refeTemp, $num_refe)
    {
        $this->setNum_refeTemp($num_refeTemp);
        $this->setNum_refe($num_refe);
    }
    
    /**
     *
     * @param integer $idprevious
     * @param integer $idgdb
     */
    public function getChangePrevious()
    {
        if ($this->getNum_refeTemp() == '' || $this->getNum_refe() == '') {
            $this->setSuccess(false);
            $this->setMessageText("Datos requeridos incompletos.");
            return false;
        } else {
                $db = new PgsqlQueries();
                
                $db->setTable('"previo".cprevo_refe');
                $db->setJoins("");
                $db->setFields(array(
                    'id_prev'
                ));
                $db->setParameters("num_refe = '" . $this->getNum_refeTemp() . "'");
                $db->setReturnType(PgsqlQueries::TYPE_OBJECT_ONE);
                $response = $db->query();
                $id_prev = $response->id_prev;
                
            try {
                if($id_prev > 0) {
                    $db->setTable('"previo".cprevo_refe RF');
                    $db->setJoins("INNER JOIN 'general'.casag_licencias L ON RF.id_licencia = L.id_licencia");
                    $db->setJoin("INNER JOIN 'general'.casac_aduanas A ON L.id_aduana = A.id_aduana");
                    $db->setFields(array(
                        'L.id_cliente',
                        'A.clave_aduana'
                    ));
                    $db->setParameters("num_refe = '" . $this->getNum_refeTemp() . "'");
                    $db->setReturnType(PgsqlQueries::TYPE_OBJECT_ONE);
                    $response = $db->query();
                    
                    $file = new File($response->id_cliente, $this->getNum_refeTemp(), 0 , '', '', $response->clave_aduana, $this->getNum_refe());
                    $file->chageUrlFile();
                    
                    $db->setTable('previo.cprevo_refe');
                    $db->setValues(array(
                        'num_refe' => $this->getNum_refe()
                    ));
                    $db->setParameters("id_prev = $id_prev");
                    $response = $db->update();
                    
                    $this->setSuccess(TRUE);
                    $this->setMessageText("Referencia cambiada");
                } else {
                    throw new Exception("No se encontró el previo");
                    
                }
            
            } catch (Exception $e) {
                $this->setSuccess(false);
                $this->setMessageText("Error: " . $e->getMessage());
                return false;
            }
            
            
            
            
        }
        
    }
    
    /**
     * @return the $num_refeTemp
     */
    public function getNum_refeTemp()
    {
        return $this->num_refeTemp;
    }
    
    /**
     * @return the $num_refe
     */
    public function getNum_refe()
    {
        return $this->num_refe;
    }
    
    /**
     * @param number $num_refeTemp
     */
    public function setNum_refeTemp($num_refeTemp)
    {
        $this->num_refeTemp = $num_refeTemp;
    }
    
    /**
     * @param number $num_refe
     */
    public function setNum_refe($num_refe)
    {
        $this->num_refe = $num_refe;
    }

    /**
     * @return the $success
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * @return the $messageText
     */
    public function getMessageText()
    {
        return $this->messageText;
    }

    /**
     * @return the $arrayPrevious
     */
    public function getArrayPrevious()
    {
        return $this->arrayPrevious;
    }


    /**
     * @param boolean $success
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }

    /**
     * @param string $messageText
     */
    public function setMessageText($messageText)
    {
        $this->messageText = $messageText;
    }

    /**
     * @param multitype: $arrayPrevious
     */
    public function setArrayPrevious($arrayPrevious)
    {
        $this->arrayPrevious = $arrayPrevious;
    }
    
    
    

    
    
    
}

?>