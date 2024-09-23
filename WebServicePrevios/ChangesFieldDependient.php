<?php
/**
 * CasaLibraries ChangesFieldDependient
 * File ChangesFieldDependient.php
 * ChangesFieldDependient Class
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Previo
 * @copyright  		Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			Yarely Guadalupe Lozada Pérez
 * @version    		Previo 1.0.0
 */

class ChangesFieldDependient
{
    /**
     *
     * @var string
     */
    public $num_refe;
    
    /**
     *
     * @var string
     */
    public $dep_asigna;

    /**
     *
     * @var integer
     */
    public $idclient;
    
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
     * @param string $num_refe
     * @param string $dep_asigna
     */
    public function __construct($num_refe, $dep_asigna, $idclient)
    {
        $this->setNum_refe($num_refe);
        $this->setDep_asigna($dep_asigna);
        $this->setIdclient($idclient);
        
    }
    
    /**
     *
     * @param integer $idprevious
     * @param integer $idgdb
     */
    public function changefieldDependient()
    {
        if ($this->getNum_refe() == '' || $this->getDep_asigna() == '' || $this->getIdclient() == '') {
            $this->setSuccess(false);
            $this->setMessageText("Datos requeridos incompletos.");
            return false;
        } else {
           $db = new PgsqlQueries();
                
            try {
                
                $db->setTable('"previo".cprevo_refe R');
                $db->setJoins("INNER JOIN 'general'.casag_licencias L ON R.id_licencia = L.id_licencia");
                $db->setFields(array(
                    'R.id_prev'
                ));
                $db->setParameters("R.num_refe = '" . $this->getNum_refe() . "' AND L.id_cliente =  " . $this->getIdclient() . "");
                $db->setReturnType(PgsqlQueries::TYPE_OBJECT_ONE);
                $response = $db->query();
                $id_prev = $response->id_prev;
                
                if($id_prev > 0) {

                    try {
                        $db->setTable('previo.cprevo_previos');
                        $db->setValues(array(
                            'dep_asigna' => $this->getDep_asigna()
                        ));
                        $db->setParameters("id_prev = $id_prev");
                        $response = $db->update();
                    } catch (Exception $e) {
                        $this->setSuccess(false);
                        $this->setMessageText("Error: No se pudo actualizar el dependiente \n" . $e->getMessage());
                        return false;
                    }

                    try {
                        $db->setTable("previo.cprevo_dependientes");
                        $db->setParameters("id_prev = $id_prev");
                        $deletedependient = $db->delete();
                        
                        $dependient = $this->getDep_asigna();
                        $arrayDependient = explode(",", $dependient);
                   
                        foreach($arrayDependient as $dependient){
                     
                            $db->setSql('SELECT nextval(\'"previo".cprevo_dependientes_id_dependiente_seq\'::regclass)');
                            $nextId = $db->execute();
                            $lastIdDependient = $nextId[0]["nextval"];
                            
                            $db->setTable('previo.cprevo_dependientes');
                            $db->setValues(array(
                                'id_dependiente' => $lastIdDependient,
                                'id_prev' => $id_prev,
                                'nom_dependiente' => $dependient
        
                            ));
                            $response = $db->insert();
                        }
                    
                    } catch (Exception $e) {
                        $this->setSuccess(false);
                        $this->setMessageText("Error: No se pudo actualizar el nombre dep dependiente \n" . $e->getMessage());
                        return false;
                    }
                    
                    
                    $this->setSuccess(TRUE);
                    $this->setMessageText("Dependiente cambiado");
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
    /**
     * @return the $num_refe
     */
    public function getNum_refe()
    {
        return $this->num_refe;
    }

    /**
     * @return the $dep_asigna
     */
    public function getDep_asigna()
    {
        return $this->dep_asigna;
    }

    /**
     * @param string $num_refe
     */
    public function setNum_refe($num_refe)
    {
        $this->num_refe = $num_refe;
    }

    /**
     * @param string $dep_asigna
     */
    public function setDep_asigna($dep_asigna)
    {
        $this->dep_asigna = $dep_asigna;
    }
    /**
     * @return the $idclient
     */
    public function getIdclient()
    {
        return $this->idclient;
    }

    /**
     * @param number $idclient
     */
    public function setIdclient($idclient)
    {
        $this->idclient = $idclient;
    }

}

?>