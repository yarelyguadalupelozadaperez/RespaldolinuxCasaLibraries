<?php
/**
 * CasaLibraries AddProvider
 * File AddProvider.php
 * AddProvider Class
 *
 * @category    CasaLibraries
 * @package       CasaLibraries_Previo
 * @copyright     Copyright (c) 2005-2015 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author      AJPE
 * @version       Previo 1.0.0
 */
class AddProvider
{
    

    /**
     *
     * @var string
     */
    public $cve_pro;

    /**
     *
     * @var string
     */
    public $dir_pro;

    /**
     *
     * @var integer
     */
    public $imp_exp;

    /**
     *
     * @var string
     */
    public $nom_pro;

    /**
     *
     * @var string
     */
    public $pai_pro;

     /**
     *
     * @var string
     */
    public $tax_pro;

    /**
     *
     * @var string
     */
    public $tel_pro;

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
     *
     * @param string $cve_pro            
     * @param string  $dir_pro
     * @param integer $imp_exp
     * @param string  $nom_pro
     * @param string  $pai_pro 
     * @param string  $tax_pro
     * @param string  $tel_pro     
     * @param string  $idclient         
     */
    public function __construct($cve_pro, $dir_pro, $imp_exp, $nom_pro, $pai_pro, $tax_pro ,$tel_pro, $idclient)
    {
        $this->setCve_pro($cve_pro);
        $this->setDir_pro($dir_pro);
        $this->setImp_exp($imp_exp);
        $this->setNom_pro($nom_pro);
        $this->setPai_pro($pai_pro);
        $this->setTax_pro($tax_pro);
        $this->setTel_pro($tel_pro);
        $this->setIdclient($idclient);
    }

    /**
     *
     * @param string $cve_pro      trim      
     * @param string  $dir_pro      trim
     * @param integer $imp_exp
     * @param string  $nom_pro      trim
     * @param string  $pai_pro      
     * @param string $tax_pro     trim
     * @param string  $tel_pro 
     * @param integer  $idclient            
     */
    public function getAddProviders()
    {
      if ($this->getCve_pro() == '' || $this->getImp_exp() == '' || $this->getNom_pro() == ''  || $this->getIdclient() == '') {
            $this->setSuccess(false);
            $this->setMessageText("Datos requeridos incompletos.");
            return false;
        }else{

            $filename = '../files/EPrevious/log/logProv.log';
            $hour = date("G");
            $hour -= 1;
            
            $now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));
            $time = $now->format("Y-m-d ".$hour.":i:s.u");
            file_put_contents($filename, $time.' - LOGGER-PHP - INFO - ENTRÉ A INSERTAR EL PROVEEDOR SIGUIENTE. Cve_proveedor: '. $this->getCve_pro() . "- Dir prov: " . $this->getDir_pro() . " - Nomb pro: " . $this->getNom_pro() .  "- Tax pro: " . $this->getTax_pro() . PHP_EOL.'', FILE_APPEND);
    

            $cve_proT = trim($this->getCve_pro());
            $dir_proT = trim($this->getDir_pro());
            $nom_proT = trim($this->getNom_pro());
            $tax_proT = trim($this->getTax_pro());

          try {
            $db = new PgsqlQueries();
            $db->setTable('general.casac_clientes');
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

              $db->setTable('general.casac_proveedores');
              $db->setJoins("");
              $db->setFields(array(
                'id_proveedor',
                'tax_pro',
                'status_prov'
              ));
              $db->setParameters("cve_prov = '" . $cve_proT . "' AND imp_exp = '" . $this->getImp_exp() ."' AND id_cliente = '" . $this->getIdclient() . "'");
              $provider = $db->query();

              $id_proveedor = $provider->id_proveedor;


                if($id_proveedor == ''){
                  $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                  $db->setSql('SELECT nextval(\'general.casac_proveedores_id_proveedor_seq\'::regclass)');
                  $nextId = $db->execute();
                  $lastProvider = $nextId[0]["nextval"];


                  $db->setTable("general.casac_proveedores");
                  $db->setValues(array(
                       "id_proveedor" => $lastProvider,
                       "cve_prov" => $cve_proT,
                       "dir_pro" => $dir_proT,
                       "imp_exp" => $this->getImp_exp($imp_exp),
                       "nom_prov" => $nom_proT,
                       "pai_pro" => $this->getPai_pro($pai_pro),
                       "status_prov" => 1,
                       "tax_pro" => $tax_proT,
                       "tel_pro" => $this->getTel_pro($tel_pro),
                       "id_cliente" => $this->getIdclient($idclient)
                  ));
                  $addProviders = $db->insert();

                  $this->setSuccess(true);
                  $this->setMessageText("Se agrego correctamente el Proveedor");
                  file_put_contents($filename, $time.' - LOGGER-PHP - INFO - SE INSERTÓ CORRECTAMENTE EL PROVEEDOR SIGUIENTE. Cve_proveedor: '. $this->getCve_pro() . "- Dir prov: " . $this->getDir_pro() . " - Nomb pro: " . $this->getNom_pro() .  "- Tax pro: " . $this->getTax_pro() . PHP_EOL.'', FILE_APPEND);

                  return true;
                }else{
                  if ($provider->status_prov !=1) {

                    $db->setTable("general.casac_proveedores");
                    $db->setValues(array(
                       "status_prov" => 1
                    ));
                    $db->setParameters("id_proveedor = $id_proveedor");
                    $updateImporters = $db->update();
                    $this->setSuccess(true);
                    $this->setMessageText("Se agrego correctamente el Proveedor");
                    file_put_contents($filename, $time.' - LOGGER-PHP - INFO - SE INSERTÓ CORRECTAMENTE EL PROVEEDOR SIGUIENTE. Cve_proveedor: '. $this->getCve_pro() . "- Dir prov: " . $this->getDir_pro() . " - Nomb pro: " . $this->getNom_pro() .  "- Tax pro: " . $this->getTax_pro() . PHP_EOL.'', FILE_APPEND);

                    return true;
                  }

                  $this->setSuccess(true);
                  $this->setMessageText("Se agrego correctamente el Proveedor");
                  return true;
                }
            } else {
              throw new Exception('No existe el cliente');
            }
          } catch (Exception $e) { 
            file_put_contents($filename, $time.' - LOGGER-PHP - INFO - OCURRIÓ UN ERROR AL INSERTAR EL PROVEEDOR SIGUIENTE. Cve_proveedor: '. $this->getCve_pro() . "- Dir prov: " . $this->getDir_pro() . " - Nomb pro: " . $this->getNom_pro() .  "- Tax pro: " . $this->getTax_pro() ." - Error: " . $e->getMessage(). PHP_EOL.'', FILE_APPEND);
     
              $this->setSuccess(false);
              $this->setMessageText("Error: " . $e->getMessage());
          }

        }
    }

    /**
     *
     * @return the $cve_pro
     */
    public function getCve_pro()
    {
        return $this->cve_pro;
    }
    /**
     *
     * @param string $cve_pro            
     */
    public function setCve_pro($cve_pro)
    {
        $this->cve_pro = $cve_pro;
    }

    /**
     *
     * @return the $dir_pro
     */
    public function getDir_pro()
    {
        return $this->dir_pro;
    }
    /**
     *
     * @param string $dir_pro            
     */
    public function setDir_pro($dir_pro)
    {
        $this->dir_pro = $dir_pro;
    }

    /**
     *
     * @return the $imp_exp
     */
    public function getImp_exp()
    {
        return $this->imp_exp;
    }
    /**
     *
     * @param number $imp_exp            
     */
    public function setImp_exp($imp_exp)
    {
        $this->imp_exp = $imp_exp;
    }

    /**
     *
     * @return the $nom_pro
     */
    public function getNom_pro()
    {
        return $this->nom_pro;
    }
    /**
     *
     * @param string $nom_pro            
     */
    public function setNom_pro($nom_pro)
    {
        $this->nom_pro = $nom_pro;
    }

    /**
     *
     * @return the $pai_pro
     */
    public function getPai_pro()
    {
        return $this->pai_pro;
    }
    /**
     *
     * @param string $pai_pro            
     */
    public function setPai_pro($pai_pro)
    {
        $this->pai_pro = $pai_pro;
    }

    /**
     *
     * @return the $tax_pro
     */
    public function getTax_pro()
    {
        return $this->tax_pro;
    }
    /**
     *
     * @param string $tax_pro            
     */
    public function setTax_pro($tax_pro)
    {
        $this->tax_pro = $tax_pro;
    }

    /**
     *
     * @return the $tel_pro
     */
    public function getTel_pro()
    {
        return $this->tel_pro;
    }
    /**
     *
     * @param string $tel_pro            
     */
    public function setTel_pro($tel_pro)
    {
        $this->tel_pro = $tel_pro;
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
     * @param number $idclient            
     */
    public function setIdclient($idclient)
    {
        $this->idclient = $idclient;
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
}
?>