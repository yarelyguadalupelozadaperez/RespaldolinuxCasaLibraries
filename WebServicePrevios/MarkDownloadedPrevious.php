<?php

class MarkDownloadedPrevious
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
     * @var integer
     */
    public $idgdb;
    
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
     * @param string $num_refe
     * @param integer $idgdb
     */
    
    public function __construct($idclient, $num_refe, $idgdb)
    {
        $this->setIdclient($idclient);
        $this->setNum_refe($num_refe);
        $this->setIdgdb($idgdb);
        
    }
    
    public function markDownloadedPrevious()
    {
        
        if ($this->getIdclient() == '' || $this->getNum_refe() == '' || $this->getIdgdb() == ''){
            $this->setSuccess(false);
            $this->setMessageText("Datos requeridos incompletos.");
            return false;
        } else{
           
            $db = new PgsqlQueries();
            $db->setTable('"general".casac_clientes');
            $db->setJoins("");
            $db->setFields(array(
                'id_cliente',
                'nombre_cliente'
            ));
            $db->setParameters("id_cliente = '" . $this->getIdclient() . "'");
            $db->setReturnType(PgsqlQueries::TYPE_OBJECT_ONE);
            $response = $db->query();
            $id_cliente = $response->id_cliente;
            $nombre_cliente = $response->nombre_cliente;

            if ($id_cliente > 0) {
                $db->setTable('"general".casag_licencias');
                $db->setJoin("");
                $db->setFields(array(
                    'id_licencia'
                ));
                $db->setParameters("id_cliente = " . $this->getIdclient() . " AND status_licencia = 1");
                $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ONE);
                $response = $db->query();
                $id_licencia = $response["id_licencia"];
                   
                if ($id_licencia > 0) {
                    try {
                        $db->setTable('"previo".cprevo_refe CR');
                        $db->setJoin("INNER JOIN 'general'.casag_licencias L ON CR.id_licencia = L.id_licencia");
                        $db->setFields(array(
                          "CR.id_prev",
                          "L.id_licencia"
                        ));
                        $db->setParameters("CR.num_refe = '" . $this->getNum_refe() . "' AND L.id_cliente =  " . $this->getIdclient() . "");
                        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ONE);
                        $responseRefe = $db->query();
                        $idprev = $responseRefe["id_prev"];
                          
                        if(!$idprev){
                          throw new Exception("La referencia no existe");
                        } else {
                            try {
                              
                              $hour = date(G).':'.date(H).':'.date(i);
                            
                              $db->setTable('previo.cprevo_descar');
                              $db->setValues(array(
                                  'id_prev' => $idprev,
                                  'fec_desca' => 'TODAY()',
                                  'id_gdb' => $this->getIdgdb(),
                                  'hora_desca'=> $hour,
                              ));
                              $response = $db->insert();
                              
                              $this->setSuccess(true);
                              $this->setMessageText("Se registró correctamente el previo como descargado");
                            
                            } catch(Exception $e){
                              throw new Exception("Ocurrió un error al agregar el previo como descargado: ". $e->getMessage());
                            }
                        }

                    } catch (Exception $e) {
                        $this->setSuccess(false);
                        $this->setMessageText("Error: " . $e->getMessage());
                        return false;
                    }
                    
                } else {
                    throw new Exception('La licencia no existe, favor de verificar');
                }
                
            } else {
                throw new Exception("No existe el Cliente");
            }
        }
    }            
    
    /**
     * @return the $idclient
     */
    public function getIdclient()
    {
        return $this->idclient;
    }

    /**
     * @return the $num_refe
     */
    public function getNum_refe()
    {
        return $this->num_refe;
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
     * @param number $idclient
     */
    public function setIdclient($idclient)
    {
        $this->idclient = $idclient;
    }


    /**
     * @param string $num_refe
     */
    public function setNum_refe($num_refe)
    {
        $this->num_refe = $num_refe;
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
     * @return the $idgdb
     */
    public function getIdgdb()
    {
        return $this->idgdb;
    }

    /**
     * @param number $idgdb
     */
    public function setIdgdb($idgdb)
    {
        $this->idgdb = $idgdb;
    }

    
}
?>