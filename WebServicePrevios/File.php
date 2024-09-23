<?php
/**
  * CasaLibraries Previo
  * File File.php
  * File Class
  *
  * @category        CasaLibraries
  * @package            CasaLibraries_wsPrevio
  * @copyright          Copyright (c) 2005-2015 Sistemas CASA, S.A. de 
C.V. sistemascasa.com.mx
  * @author            SMV
  * @version            Previo 1.0.0
  */

class File
{
    /**
     *
     * @var integer
     */
    private $_idClient;

    /**
     *
     * @var integer
     */
    private $_id_partida;

    /**
     *
     * @var string
     */
    private $num_refe;

    /**
     *
     * @var string
     */
    private $new_refe;

    /**
     *
     * @var integer
     */
    public $cons_foto;

    /**
     *
     * @var string
     */
    public $nom_foto;


    /**
     *
     * @var string
     */
    //public $fileString;

    /**
     *
     * @var string
     */
    private $aduana;

    /**
     *
     * @var string
     */
    public $url_foto;
    /**
     *
     * @var boolean
     */
    private $success;

    /**
    *
    * @param integer $idClient
    * @param string $num_refe
    * @param integer $cons_foto
    * @param striconng $nom_foto
    * @param string $fileString
    * @param string $aduana
    * @param string $patente
    * @param string $id_partida
    * @param string $url_foto
    * @param string $new_refe
    *
    */


    public function __construct($idClient, $num_refe, $cons_foto, $nom_foto, $aduana, $patente, $id_partida, $url_foto, $new_refe = NULL) {
        $this->setAduana($aduana);
        $this->setIdClient($idClient);
        $this->setNum_refe($num_refe);
        $this->setCons_foto($cons_foto);
        $this->setNom_foto($nom_foto);
        $this->setPatente($patente);
        $this->setId_partida($id_partida);
        $this->setUrl_foto($url_foto);
        //$this->setFileString($fileString);
        if ($new_refe != NULL) {
            $this->setNew_refe($new_refe);
        }

    }
     

    /**
      * This method loads a new file
      */
    public function loadFileOperation () {
        try {

            $rootPath = "../files/EPrevious/";

            if(!is_dir($rootPath)) {
                if(!mkdir($rootPath, 0777, true)) {
                    throw new Exception("La carpeta files no se ha podido crear.");
                    chmod($rootPath, 0777);
                }
            }

            $pathClient = $rootPath . $this->getIdClient();


            if(!is_dir($pathClient)) {
                if(!mkdir($pathClient, 0777, true)) {
                    throw new Exception("La carpeta del cliente no se ha podido crear.");
                    chmod($pathClient, 0777);
                }
            }


            $pathPatentCustom = $pathClient . '/' . $this->getAduana();


            if(!is_dir($pathPatentCustom)) {
                if(!mkdir($pathPatentCustom, 0777, true)) {
                    throw new Exception("La carpeta de la aduana no se ha podido crear.");
                    chmod($pathPatentCustom, 0777);
                }
            }


            $referencereplace = str_replace("/", "DIAGONAL", $this->getNum_refe());

            $pathReference = $pathPatentCustom . "/" . $referencereplace;



            if(!is_dir($pathReference)) {
                if(!mkdir($pathReference, 0777, true)) {
                    throw new Exception("La carpeta de la referencia no se ha podido crear.");
                    chmod($pathReference, 0777);
                }
            }

            $pathFile = $pathReference . "/" . $this->getNom_foto();
            $response = file_put_contents($pathFile, base64_decode($this->getFileString()));
            chmod($pathFile, 0777);
            $this->setSuccess(true);

            if ($response > 0) {
                return false;
            } else {
                return true;
            }

        } catch (Exception $e) {
            $this->setSuccess(false);
            var_dump($e->getMessage());
            exit;
        }
    }

     
    /**
     * This method loads a new file
     */
    public function loadFilePrevioOperation () {
        try {
            $rootPath = "../files/EPrevious/";

            if(!is_dir($rootPath)) {
                if(!mkdir($rootPath, 0777, true)) {
                    throw new Exception("La carpeta files no se ha podido crear.");
                    chmod($rootPath, 0777);
                }
            }

            $pathClient = $rootPath . $this->getIdClient();


            if(!is_dir($pathClient)) {
                if(!mkdir($pathClient, 0777, true)) {
                    throw new Exception("La carpeta del cliente no se ha podido crear.");
                    chmod($pathClient, 0777);
                }
            }


            $pathPatentCustom = $pathClient . '/' . $this->getAduana() . "_". $this->getPatente();


            if(!is_dir($pathPatentCustom)) {
                if(!mkdir($pathPatentCustom, 0777, true)) {
                    throw new Exception("La carpeta de la oficina no se ha podido crear.");
                    chmod($pathPatentCustom, 0777);
                }
            }


            $referencereplace = str_replace("/", "DIAGONAL", $this->getNum_refe());

            $pathReference = $pathPatentCustom . "/" . $referencereplace;



            if(!is_dir($pathReference)) {
                if(!mkdir($pathReference, 0777, true)) {
                    throw new Exception("La carpeta de la referencia no se ha podido crear.");
                    chmod($pathReference, 0777);
                }
            }


            $pathReferenceFotos = $pathPatentCustom . "/" . $referencereplace ."/Fotos";
            
            if(!is_dir($pathReferenceFotos)) {
                if(!mkdir($pathReferenceFotos, 0777, true)) {
                    throw new Exception("La carpeta de las fotos de la referencia no se ha podido crear.");
                    chmod($pathReferenceFotos, 0777);
                }
            }


            $pathFile = $pathReferenceFotos . "/" . $this->getNom_foto();
            $response = file_put_contents($pathFile, base64_decode($this->getFileString()));
            chmod($pathFile, 0777);
            $this->setSuccess(true);

           return true;

        } catch (Exception $e) {
            throw new Exception("Ocurrio un error al guardar las fotos de la referencia." . $e->getMessage());
        }
    }
     

    /**
     * This method loads a new file
     */
    public function loadFilePartidaOperation () {
        try {

            $rootPath = "../files/EPrevious/";

            if(!is_dir($rootPath)) {
                if(!mkdir($rootPath, 0777, true)) {
                    throw new Exception("La carpeta files no se ha podido crear.");
                    chmod($rootPath, 0777);
                }
            }

            $pathClient = $rootPath . $this->getIdClient();


            if(!is_dir($pathClient)) {
                if(!mkdir($pathClient, 0777, true)) {
                    throw new Exception("La carpeta del cliente no se ha podido crear.");
                    chmod($pathClient, 0777);
                }
            }


            $pathPatentCustom = $pathClient . '/' . $this->getAduana() . "_". $this->getPatente();


            if(!is_dir($pathPatentCustom)) {
                if(!mkdir($pathPatentCustom, 0777, true)) {
                    throw new Exception("La carpeta de la oficina no se ha podido crear.");
                    chmod($pathPatentCustom, 0777);
                }
            }


            $referencereplace = str_replace("/", "DIAGONAL", $this->getNum_refe());

            $pathReference = $pathPatentCustom . "/" . $referencereplace;


            if(!is_dir($pathReference)) {
                if(!mkdir($pathReference, 0777, true)) {
                    throw new Exception("La carpeta de la referencia no se ha podido crear.");
                    chmod($pathReference, 0777);
                }
            }

            $pathPart= $pathPatentCustom . "/" . $referencereplace . "/".$this->getId_partida();

            if(!is_dir($pathPart)) {
                if(!mkdir($pathPart, 0777, true)) {
                    throw new Exception("La carpeta de la partida no se ha podido crear.");
                    chmod($pathPart, 0777);
                }
            }


            $pathPartFotos = $pathPart. "/Fotos";

            if(!is_dir($pathPartFotos)) {
                if(!mkdir($pathPartFotos, 0777, true)) {
                    throw new Exception("La carpeta de las fotos de la partida no se ha podido crear.");
                    chmod($pathPartFotos, 0777);
                }
            }


            $pathFile = $pathPartFotos . "/" . $this->getNom_foto();
            $response = file_put_contents($pathFile, base64_decode($this->getFileString()));
            chmod($pathFile, 0777);
            $this->setSuccess(true);

            /*if ($response > 0) {
                return false;
            } else {
                return true;
            }*/
            return true;
            
        } catch (Exception $e) {
            /*$this->setSuccess(false);

            exit;*/
            throw new Exception("Ocurrio un error al guardar las fotos de la partida." . $e->getMessage());
        }
    }
     

    /**
     * return files photos Previo
     */

    public function extractFile(){
        try {

            $rootPath = "../files/EPrevious/";
            $pathClient = $rootPath . $this->getIdClient();
            $pathPatentCustom = $pathClient . '/' . $this->getAduana();

            $referencereplace = str_replace("/", "DIAGONAL", $this->getNum_refe());

            $pathReference = $pathPatentCustom . "/" . $referencereplace;
            $nom_foto = $this->getNom_foto();

            if (!is_dir($pathReference))
                 throw new Exception ("El directorio: " . $pathClient . '/' . $this->getAduana() . ' no existe.');

            if (file_exists($pathReference. '/' .$nom_foto)) {

                $fileContent = base64_encode (file_get_contents($pathReference . '/'.$nom_foto));

                return $fileContent;

            }else {
                throw new Exception ("El archivo: $nom_foto no existe ");
            }

        } catch (Exception $e) {
            throw new Exception ($e->getMessage());
        }
    }


    /**
     * return files photos Previo
     */

    public function extractFileUrl(){
        try {

            $nom_foto = $this->getNom_foto();

            if (file_exists("../" .$this->getUrl_foto())) {

                $fileContent = base64_encode (file_get_contents("../" .$this->getUrl_foto()));

                return $fileContent;

            }else {
                throw new Exception ("El archivo1: $nom_foto no existe ");
            }

        } catch (Exception $e) {
            throw new Exception ($e->getMessage());
        }
    }

    /**
     * return files photos Part
     */
    public function extractFileUrlPart(){
        try {

            $nom_foto = $this->getNom_foto();

            if (file_exists("../" .$this->getUrl_foto())) {   
                $fileContent = base64_encode (file_get_contents("../" .$this->getUrl_foto()));
                return $fileContent;
            }else {
                throw new Exception ("El archivo1: $nom_foto no existe ");
            }

        } catch (Exception $e) {
            throw new Exception ($e->getMessage());
        }
    }
    
    /**
     * return files photos Part2
     */
        public function extractFileUrlPart2(){
        try {

            $nom_foto = $this->getNom_foto();

            if (file_exists("../" .$this->getUrl_foto())) { 
                $fileContent = base64_encode (file_get_contents("../" .$this->getUrl_foto() . "/".$this->getNom_foto()));
                return $fileContent;
            }else {
                throw new Exception ("El archivo : $nom_foto no existe ");
            }

        } catch (Exception $e) {
            throw new Exception ($e->getMessage());
        }
    }

    
        /**
     * return files photos Part2
     */
        public function extractFileUrlPart3(){
        try {

            $nom_foto = $this->getNom_foto();
            if (file_exists(".." .$this->getUrl_foto(). "/".$this->getNom_foto())) { 

                $fileContent = base64_encode (file_get_contents(".." . $this->getUrl_foto()  . "/".$this->getNom_foto()));
              
                return $fileContent;
            }else {
                throw new Exception ("El archivo : $nom_foto no existe ");
            }

        } catch (Exception $e) {
            throw new Exception ($e->getMessage());
        }
    }
    
 

    /**
     * change the photo url
     */

    public function chageUrlFile(){
        try {

            $rootPath = "../files/EPrevious/";
            $pathClient = $rootPath . $this->getIdClient();
            $pathPatentCustom = $pathClient . '/' . $this->getAduana();
            $pathReference = $pathPatentCustom . "/" . $this->getNum_refe();
            $pathNewReference = $pathPatentCustom . "/" . $this->getNew_refe();

            if (file_exists($pathReference)) {
                rename($pathReference, $pathNewReference);
            }

        } catch (Exception $e) {
            throw new Exception ($e->getMessage());
        }
    }

     
    public function deleteFile(){
        try {

            $rootPath = "../files/EPrevious/";
            $pathClient = $rootPath . $this->getIdClient();
            $pathPatentCustom = $pathClient . '/' . $this->getAduana();

            $referencereplace = str_replace("/", "_", $this->getNum_refe());

            $pathReference = $pathPatentCustom . "/" . $referencereplace;


            if (is_dir($pathReference)){
                $dir= "$pathReference";

                $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
                $files = new RecursiveIteratorIterator($it,
                    RecursiveIteratorIterator::CHILD_FIRST);

                foreach($files as $file) {
                    if ($file->isDir()){
                        rmdir($file->getRealPath());
                    } else {
                        unlink($file->getRealPath());
                    }
                }

                if(!rmdir($dir))
                {
                    echo ("No se pudo eliminar el directorio: $dir");
                }

            }

        } catch (Exception $e) {
            throw new Exception ($e->getMessage());
        }
    }


    public function deleteFileNew(){
        try {

            $rootPath = "../files/EPrevious/";
            $pathClient = $rootPath . $this->getIdClient();
            $pathPatentCustom = $pathClient . '/' . $this->getAduana() . "_". $this->getPatente();

            $referencereplace = str_replace("/", "DIAGONAL", $this->getNum_refe());

            $pathReference = $pathPatentCustom . "/" . $referencereplace;


            if (is_dir($pathReference)){
                $dir= "$pathReference";

                $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
                $files = new RecursiveIteratorIterator($it,
                    RecursiveIteratorIterator::CHILD_FIRST);

                foreach($files as $file) {
                    if ($file->isDir()){
                        rmdir($file->getRealPath());
                    } else {
                        unlink($file->getRealPath());
                    }
                }

                if(!rmdir($dir))
                {
                    echo ("No se pudo eliminar el directorio: $dir");
                }

            }

        } catch (Exception $e) {
            throw new Exception ($e->getMessage());
        }
    }

    /**
     * @return the $_idClient
     */
    public function getIdClient()
    {
        return $this->_idClient;
    }

    /**
     * @return the $num_refe
     */
    public function getNum_refe()
    {
        return $this->num_refe;
    }

    /**
     * @return the $new_refe
     */
    public function getNew_refe()
    {
        return $this->new_refe;
    }

    /**
     * @return the $cons_foto
     */
    public function getCons_foto()
    {
        return $this->cons_foto;
    }

    /**
     * @return the $nom_foto
     */
    public function getNom_foto()
    {
        return $this->nom_foto;
    }

    /**
     * @return the $fileString
     */
   /* public function getFileString()
    {
        return $this->fileString;
    }*/

    /**
     * @return the $success
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * @param number $_idClient
     */
    public function setIdClient($_idClient)
    {
        $this->_idClient = $_idClient;
    }

    /**
     * @param string $num_refe
     */
    public function setNum_refe($num_refe)
    {
        $this->num_refe = $num_refe;
    }

    /**
     * @param string $new_refe
     */
    public function setNew_refe($new_refe)
    {
        $this->new_refe = $new_refe;
    }

    /**
     * @param number $cons_foto
     */
    public function setCons_foto($cons_foto)
    {
        $this->cons_foto = $cons_foto;
    }

    /**
     * @param string $nom_foto
     */
    public function setNom_foto($nom_foto)
    {
        $this->nom_foto = $nom_foto;
    }

    /**
     * @param string $fileString
     */
    /*public function setFileString($fileString)
    {
        $this->fileString = $fileString;
    }*/

    /**
     * @param boolean $success
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }

    /**
     * @return the $aduana
     */
    public function getAduana()
    {
        return $this->aduana;
    }

    /**
     * @return the $url_foto
     */
    public function getUrl_foto()
    {
        return $this->url_foto;
    }


    /**
     * @return the $patente
     */
    public function getPatente()
    {
        return $this->patente;
    }

    /**
     * @return the id_partida
     */
    public function getId_partida()
    {
        return $this->id_partida;
    }

    /**
     * @param string $aduana
     */
    public function setAduana($aduana)
    {
        $this->aduana = $aduana;
    }

    /**
     * @param string $patente
     */
    public function setPatente($patente)
    {
        $this->patente = $patente;
    }


    /**
     * @param string $id_partida
     */
    public function setId_partida($id_partida)
    {
        $this->id_partida = $id_partida;
    }

    /**
     * @param string $url_foto
     */
    public function setUrl_foto($url_foto)
    {
        $this->url_foto = $url_foto;
    }

 


}

?>
