<?php

/**
 * AdminWeb_CasaLibraries FileUploader
 * 
 * File Uploader
 *
 * @category AdminWeb_CasaLibraries
 * @package AdminWeb_CasaLibraries_Util_FileUploader
 * @copyright (c) 2005-2013, Sistemas CASA, S.A. de C.V. http://www.sistemascasa.com.mx
 * @author Jesús Eduardo Flores Alejandro <jflores@sistemascasa.com.mx>
 * @version AdminWeb 1.0.0
 */
class FileUploader {

    /**
     * File Name
     * @var string 
     */
    public $archivo;
    
    /**
     * Directory Name
     * @var string 
     */
    public $directorio;
    
    /**
     * Valid File Types
     * @var array 
     */
    public $extPermitidas;
    
    /**
     * File Mime Type
     * @var string 
     */
    public $mime;
    
    /**
     * File Extention
     * @var string
     */
    public $extArchivo;
    
    /**
     * File Size
     * @var float 
     */
    public $tamanoArchivo;
    
    /**
     * Final File Name
     * @var string
     */
    public $nombre;
    
    /**
     * Error Number
     * @var integer 
     */
    public $error;
    
    /**
     * Old File Name
     * @var string 
     */
    public $oldName;
    
    /**
     * New File Name
     * @var string
     */
    public $newName;
    
    /**
     * Folder Files
     * @var array 
     */
    public $fileList = array();


    /**
     * Temporal File Name
     * @var string
     */
    private $_nombreTemp;
    
    /**
     * Max File Size
     * @var integer
     */
    private $_tamanoMaximo;

    /**
     * Class construct
     */
    public function __construct() {
        
    }

    /**
     * Get File Extension
     * @param string $archivo
     * @return string
     */
    private function _getFileExtension($archivo) {
        if ($archivo != '') {
            return $extension = end(explode('.', $archivo));
        } else {
            return false;
        }
    }

    /**
     * Check if is a valid File Type
     * @return boolean
     */
    private function _checkType() {
        if ($this->extArchivo) {
            return in_array($this->mime, $this->extPermitidas);
        } else {
            return false;
        }
    }

    /**
     * Check a valid File Size
     * @return boolean
     */
    private function _checkSize() {
        if ($this->tamanoArchivo > $this->_tamanoMaximo) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Check existing Directory
     * @return boolean
     */
    private function _checkDir() {
        if (!is_dir($this->directorio)) {
            mkdir($this->directorio, 0755, TRUE);           
            chmod($this->directorio, 0755);
            return $this->_checkDir();            
        } else {              
            return TRUE;
        }
    }
    
    /**
     * Make Directory
     * @param string $mkdir
     * @return boolean
     */
    public function makeDir($mkdir){
        $this->directorio = $mkdir;
        return $this->_checkDir();
    }

    /**
     * Upload File
     * @param string $dir
     * @param array $file
     * @param array $extPerm
     * @param string $nombre
     * @param integer $tamPermitido
     * @return integer 
     */
    public function uploadFile($dir, $file = array(), $extPerm = array(), $nombre = '', $tamPermitido = '') {
        $this->archivo = $file['name'];
        $this->directorio = $dir;
        $this->extArchivo = $this->_getFileExtension($file['name']);
        $this->mime = $file['type'];
        $this->error = $file['error'];
        $this->tamanoArchivo = $file['size'];
        $this->_nombreTemp = $file['tmp_name'];
        $this->extPermitidas = $extPerm;
        $this->nombre = empty($nombre) ? str_replace('.' . $this->extArchivo, '', $file['name']) : $nombre;
        $this->_tamanoMaximo = empty($tamPermitido) ? trim(ini_get('upload_max_filesize') * 1048576) : $tamPermitido;

        if ($this->error > 0) {
            return $this->error;
        } else {
            if (file_exists($this->directorio . $this->archivo)) {
                return $this->error = 9;
            }
            if ($this->_checkDir() == false) {
                return $this->error = 10;
            }
            if ($this->_checkType() == false) {
                return $this->error = 11;
            }
            if ($this->_checkSize() == false) {
                return $this->error = 1;
            }
            if ($this->error == 0) {                
                $loadFile = $this->directorio . $this->nombre . '.' . $this->extArchivo;
                if (move_uploaded_file($this->_nombreTemp, $loadFile)) {
                    chmod($loadFile, 0755);
                    return 0;
                } else {
                    return $this->error = 5;
                }
            }
        }
    }

    /**
     * Delete File
     * @param string $directorio
     * @param string $archivo
     * @return boolean
     */
    public static function delFile($directorio, $archivo) {
        if (file_exists($directorio . $archivo)) {
            unlink($directorio . $archivo);
            return true;
        }
        return false;
    }

    /**
     * Rename File
     * @param string $directorio
     * @param string $oldFileName
     * @param string $newFileName
     * @return boolean
     */
    public static function renameFile($directorio, $oldFileName, $newFileName) {
        if (file_exists($directorio . $oldFileName)) {
            $oldName = $directorio . $oldFileName;
            $newName = $directorio . $newFileName;
            if (rename($oldName, $newName)) {
                return TRUE;
            }
            return false;
        }
        return false;
    }
    
    /**
     * FolderFiles
     * @param string $path
     */
    public function folderFiles($path) {
        if ($handle = @opendir($path)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {

                    $newdir = "";
                    $filetext = "";

                    if (!is_file($path . "/" . $file) or is_dir($path)) {
                        $newdir.= $path . "/" . $file . "/";
                        $this->folderFiles($newdir);
                        if (is_file($path . $file)) {
                            $text = str_replace('//', '/', "" . $path . $file . chr(13) . chr(10));
                            $text = str_replace('../', '', $text);
                            if ($text[0] != '_') {
                                $this->fileList[] = trim($text);                                  
                            }
                        }
                    }
                }
            }
            closedir($handle);
        }
    }

    /**
     * Get Error Message
     * @param integer $err_num
     * @return string
     */
    public function errorMessage($err_num) {
        $this->tamanoMaximo = ini_get('upload_max_filesize');
        
        $bSuccess = $err_num!=0?FALSE:TRUE;
        
        
        $error[0] = "El archivo se carg&oacute; correctamente.";
        $error[1] = "El tama&ntilde;o del archivo sobrepasa el permitido que es de " . $this->_tamanoMaximo;
        $error[2] = "El tama&ntilde;o del archivo sobrepasa el permitido que es de " . $this->_tamanoMaximo;
        $error[3] = "El archivo se carg&oacute; parcialmente";
        $error[4] = "No se carg&oacute; el archivo, intente de nuevo";
        $error[5] = "Ha ocurrido un error al cargar el archivo";
        $error[6] = "Error en el directorio temporal";
        $error[7] = "Error de escritura en disco";
        $error[8] = "Error al cargar el archivo, contacte a su administrador. ";
        $error[9] = "El archivo ya existe.";
        $error[10] = "El directorio no existe.";
        $error[11] = "Extensi&oacute;n no permitida. <br> El formato detectado es: " . $this->mime;

        if($bSuccess){            
            return '{ success: true, msg: "'.$error[$err_num].'"}';
        } 
        return '{ success: false, msg: "'.$error[$err_num].'"}';
    }

}

