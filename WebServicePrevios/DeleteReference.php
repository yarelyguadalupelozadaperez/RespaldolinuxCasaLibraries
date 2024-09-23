<?php

class DeleteReference
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
    public $flag;
    
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
     * @param integer $flag
     */
    
    public function __construct($idclient, $num_refe, $flag)
    {
        $this->setIdclient($idclient);
        $this->setNum_refe($num_refe);
        $this->setFlag($flag);
    }
    
    public function getDeleteReference()
    {
        
        if ($this->getIdclient() == '' || $this->getNum_refe() == '' || $this->getFlag() == ''){
            $this->setSuccess(false);
            $this->setMessageText("Datos requeridos incompletos.");
            return false;
        } else {
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
                                if($this->getFlag() == 2) {
                                    $db->setTable('"previo".cprevo_descar D');
                                    $db->setJoins("");
                                    $db->setFields(array(
                                        "count(D.id_descarga) AS counterDownload"
                                    ));
                                    $db->setParameters("D.id_prev = $idprev");
                                    $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ONE);
                                    $responseDownload = $db->query();

                                    if($responseDownload["counterdownload"] > 0){
                                        throw new Exception("El previo no se puede eliminar porque ya se encuentra descargado en alguna tablet");
                                    }
                                    
                                } else if ($this->getFlag() == 1) {
                                    $db->setTable('"previo".cprevo_descar D');
                                    $db->setJoins("");
                                    $db->setFields(array(
                                        "count(D.id_descarga) AS counterDownload"
                                    ));
                                    $db->setParameters("D.id_prev = $idprev");
                                    $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ONE);
                                    $responseDownload = $db->query();

                                    if($responseDownload["counterdownload"] > 0){
                                        throw new Exception("El previo no se puede cancelar en web porque ya se encuentra descargado en alguna tablet. Por favor comunicarse con el administrador del sistema Web.");
                                    }
                                    
                                    $db->setTable('"previo".cprevo_previos CP');
                                    $db->setJoins("INNER JOIN 'previo'.'cprevo_refe' CR ON CP.id_prev = CR.id_prev");
                                    $db->setJoin("INNER JOIN 'previo'.cprevo_factur F ON CP.id_prev = F.id_prev");
                                    $db->setJoin("INNER JOIN 'previo'.cprevo_facpar FP ON F.id_factur = FP.id_factur");
                                    $db->setFields(array(
                                        "DISTINCT ON (CR.id_prev) CR.id_prev"
                                    ));
                                    
                                    $db->setParameters("CP.id_prev = $idprev AND (FP.cve_usua <> '')");
                                    $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ONE);
                                    $initialized = $db->query();
                                   
                                    if($initialized){
                                        throw new Exception("El previo no se puede cancelar porque ya  ha sido iniciado.");
                                    } 
                                    
                                    
                                } else {
                                    throw new Exception("El estatus del flag no es válido");
                                }
                                 
                                $db->setTable('"previo".cprevo_refe REFE');
                                $db->setJoins("INNER JOIN 'previo'.cprevo_previos P ON REFE.id_prev = P.id_prev");
                                $db->setJoin("INNER JOIN 'previo'.cprevo_factur F ON P.id_prev = F.id_prev");
                                $db->setJoin("INNER JOIN 'previo'.cprevo_facpar FP ON FP.id_factur = F.id_factur");
                                $db->setJoin("INNER JOIN 'general'.casag_licencias L ON REFE.id_licencia = L.id_licencia");
                                $db->setJoin("INNER JOIN 'general'.casac_aduanas A ON L.id_aduana = A.id_aduana");
                                $db->setJoin("INNER JOIN 'previo'.cprevo_fotos FOP ON FP.id_partida = FOP.id_partida");
                                $db->setFields(array(
                                  "FOP.id_partida",
                                  "FOP.cons_foto",
                                  "FOP.nom_foto",
                                  "L.id_cliente",
                                  "A.clave_aduana"
                                ));
                                $db->setParameters("REFE.num_refe = '" . $this->getNum_refe() . "' AND L.id_cliente =  " . $this->getIdclient() . "");
                                $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                                $responsePhotoParts = $db->query();
                            
                                try {
                                    foreach ($responsePhotoParts as $part){
                                      try{
                                          $partId = $part["id_partida"];
                                          $db->setTable("previo.cprevo_fotos");
                                          $db->setParameters("id_partida = '" . $partId. "'");
                                          $deletePhotoPrev = $db->delete();
                                      } catch(Exeption $e){
                                          throw new Exception("Error: Ocurrió un error al eliminar las fotografias. $e");
                                      } 
                                    }
                                
                                    $db->setTable('"previo".cprevo_refe REFE');
                                    $db->setJoins("INNER JOIN 'previo'.cprevo_previos P ON REFE.id_prev = P.id_prev");
                                    $db->setJoin("INNER JOIN 'general'.casag_licencias L ON REFE.id_licencia = L.id_licencia");
                                    $db->setJoin("INNER JOIN 'general'.casac_aduanas A ON L.id_aduana = A.id_aduana");
                                    $db->setJoin("INNER JOIN 'previo'.cprevo_fotop FOP ON P.id_prev = FOP.id_prev");
                                    $db->setFields(array(
                                      "FOP.id_fotop",
                                      "FOP.cons_foto",
                                      "FOP.nom_foto",
                                      "L.id_cliente",
                                      "A.clave_aduana"
                                    ));
                                    $db->setParameters("REFE.num_refe = '" . $this->getNum_refe() . "' AND L.id_cliente =  " . $this->getIdclient() . "");
                                    $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                                    $responsePhotoPrevio = $db->query();
                                    $custome = $responsePhotoPrevio[0]["clave_aduana"];
                               
                                    if(count($responsePhotoPrevio) > 0){
                                     
                                      try{
                                          $db->setTable("previo.cprevo_fotop");
                                          $db->setParameters("id_prev = '" . $idprev. "'");
                                          $deletePhotoPrev = $db->delete();
                                         
                                      } catch(Exeption $e){
                                          throw new Exception("Error: Ocurrió un error al eliminar las fotografias a nivel previo. $e");
                                          
                                      }
                                    }
                                  
                                  
                                    $photo = new File($part["id_cliente"], $this->getNum_refe(), null, null, null, $custome);
                                    $photo->deleteFile();
                                    
                                    try{
                                      $db->setTable("previo.cprevo_refe");
                                      $db->setParameters("id_prev = '" . $idprev. "'");
                                      $deleteReference = $db->delete();
                                    
                                    } catch(Exeption $e){
                                      throw new Exception("Error: Ocurrió un error al eliminar la referencia. $e->getMessage()");
                                      
                                    }
                                    
                                    $this->setSuccess(true);
                                    $this->setMessageText("Se eliminó correctamente la referencia");
                                  
                                } catch(Exception $e){
                                  throw new Exception("Ocurrió un error al eliminar la referencia: ". $e->getMessage());
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
     * @return number
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * @return boolean
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * @param number $flag
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;
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
    
    
}

?>