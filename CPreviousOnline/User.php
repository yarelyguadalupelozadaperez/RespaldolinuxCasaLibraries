
<?php

/**
 * CasaLibraries User class
 * File User.php
 * Connection to posgresql database
 *
 * @category     CasaLibraries
 * @package     CasaLibraries_CasaSkeketon
 * @copyright     Copyright (c) 2005-2013 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author         Jaime Santana Zaldivar
 * @version     User 1.0.0
 *
 */
include 'CasaLibraries/CasaDb/PgsqlQueries.php';

class User
{
    /**
     *
     * @var integer 
     */
    private $_id_usuario;

    /**
     *
     * @var integer
     */
    private $_id_cliente;

    /**
     *
     * @var integer
     */
    private $_id_clienteadmin;

    /**
     *
     * @var integer
     */
    private $_id_tipousuario;

    /**
     *
     * @var string
     */
    private $_alias_usuario;

    /**
     *
     * @var string
     */
    private $_contrasena_usuario;

    /**
     *
     * @var string
     */
    private $_nombre_usuario;

    /**
     *
     * @var string
     */
    private $_correo_usuario;

    /**
     *
     * @var string
     */
    private $_fechalta_usuario;

    /**
     *
     * @var integer
     */
    private $_status_usuario;

    /**
     *
     * @var string
     */
    private $_nombre_cliente;

    /**
     * This method returns the user from data base
     */
    public function getUser($language) {
        //$urlFiles = "../../";
        $urlFiles = "../files/EPrevious/";
        
        switch ($language) {
            case 2:
                include_once 'Dictionaries/English/Dictionary.php';
                break;

            default:
                include_once 'Dictionaries/Spanish/Dictionary.php';;
                break;
        }

        $db = new PgsqlQueries;
        $db->setTable("'previo'.usuarios U");
        $db->setJoins("INNER JOIN 'general'.casac_clientes C ON U.id_cliente = C.id_cliente");
        $db->setJoin("INNER JOIN 'general'.casag_licencias L ON U.id_cliente = L.id_cliente");
        $db->setJoin("INNER JOIN 'general'.'casag_configcliente' CS ON CS.id_cliente = C.id_cliente");
        $db->setJoin("INNER JOIN 'general'.casag_temas T ON T.id_tema = CS.id_tema");
        $db->setFields(array(
            "U.id_usuario",
            "U.id_cliente",
            "U.id_tipousuario",
            "U.alias_usuario",
            "U.contrasena_usuario",
            "U.nombre_usuario",
            "U.correo_usuario",
            "to_char(U.fechalta_usuario, #DD/MM/YYYY#::text) AS fechalta_usuario",
            "U.status_usuario",
            "T.nombre_tema",
            "T.pathcss_tema",
            "T.pathjs_tema",
            "T.pathcssmov_tema",
            "T.pathjsmov_tema",
            "CS.logo_configcliente",
            "CS.logo_configcliente",
            "CS.imgtitulo_configcliente",
            "CS.color_letter_configcliente",
            "CS.color_background_configcliente",
            "CS.color_botones",
            "CS.layout_cliente"
        ));
        $where = "U.correo_usuario = ? AND C.status_cliente = 1 AND T.status_tema = 1";
        $paramsArray = array($this->getUseremail());
        $db->setReturnType(PgsqlQueries::TYPE_OBJECT_ONE);
        $user = $db->queryParametrize($where, $paramsArray);

        if ($user) {
            $idClient   = $user->id_cliente;
            $idUser     = $user->id_usuario;
            $idTypeUser = $user->id_tipousuario;

            if($idTypeUser == 3){
                $db->setTable("'general'.casag_licencias L");
                $db->setJoins("INNER JOIN  general.casac_licusuario LU ON L.id_licencia = LU.id_licencia");
                $db->setJoin("INNER JOIN 'general'.casac_aduanas A ON L.id_aduana = A.id_aduana");
                $db->setFields(array(
                    "L.id_licencia",
                    "A.id_aduana",
                    "L.patente"
                ));
                $where = "LU.id_usuario = ?";
                $paramsArray = array($idUser);
                $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                $licences = $db->queryParametrize($where, $paramsArray);
                
///////////////////////////SE AGREGÓ IMPORTADORES (TABLA USUIMP)
                $db->setTable("'general'.casac_importadores I");
                $db->setJoins("INNER JOIN  previo.cprevc_usuimp UI ON I.id_importador = UI.id_importador");
                $db->setFields(array(
                    "I.id_importador",
                    "I.nombre_importador",
                    "I.rfc_importador"
                ));
                $where = "UI.id_usuario = ?";
                $paramsArray = array($idUser);
                $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                $importers = $db->queryParametrize($where, $paramsArray);
                
            } else {
                $db->setTable("'general'.casag_licencias L");
                $db->setJoins("INNER JOIN 'general'.casac_aduanas A ON L.id_aduana = A.id_aduana");
                $db->setFields(array(
                    "L.id_licencia",
                    "A.id_aduana",
                    "L.patente"
                ));
                $where = "L.id_cliente = ?";
                $paramsArray = array($idClient);
                $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                $licences = $db->queryParametrize($where, $paramsArray);
                
//////////////////////////SE AGREGÓ IMPORTADORES  
                $db->setTable("'general'.casac_importadores");
                $db->setJoins("");
                $db->setFields(array(
                    "nombre_importador",
                    "rfc_importador",
                    "id_importador"
                ));
                $where = "id_cliente = ? AND status_importador = 1";
                $paramsArray = array($idClient);
                $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                $importers = $db->queryParametrize($where, $paramsArray);
            }
            
            $db->setTable("'general'.casac_clientes C");
            $db->setJoins("INNER JOIN 'general'.casag_licencias L ON C.id_cliente = L.id_cliente");
            $db->setJoin("INNER JOIN 'general'.'casag_configcliente' CS ON CS.id_cliente = C.id_cliente");
            $db->setJoin("INNER JOIN 'general'.casag_temas T ON T.id_tema = CS.id_tema");
            $db->setFields(array(
                "T.nombre_tema",
                "T.pathcss_tema",
                "T.pathjs_tema",
                "T.pathcssmov_tema",
                "T.pathjsmov_tema",
                "CS.logo_configcliente",
                "CS.imgintro_configcliente",
                "CS.imgtitulo_configcliente",
                "CS.color_letter_configcliente",
                "CS.color_background_configcliente",
                "CS.color_botones",
                "CS.layout_cliente",
                "CS.color_name_user"
            ));
            $where = "C.id_cliente = ?";
            $paramsArray = array($idClient);
            $db->setReturnType(PgsqlQueries::TYPE_OBJECT_ONE);
            $client = $db->queryParametrize($where, $paramsArray);

            if($client) {
                $user->nombre_tema = $client->nombre_tema;
                $user->pathcss_tema = $client->pathcss_tema;
                $user->pathjs_tema = $client->pathjs_tema;
                $user->pathcssmov_tema = $client->pathcssmov_tema;
                $user->pathjsmov_tema = $client->pathjsmov_tema;
                $user->logo_configcliente = $client->logo_configcliente;
                $user->imgintro_configcliente = $client->imgintro_configcliente;
                $user->imgtitulo_configcliente = $client->imgtitulo_configcliente;
                $user->color_letter_configcliente = $client->color_letter_configcliente;
                $user->color_background_configcliente = $client->color_background_configcliente;
                $user->color_botones = $client->color_botones;
                $user->layout_cliente = $client->layout_cliente;
                $user->color_name_user = $client->color_name_user;
                $user->urlfiles = $urlFiles;
            }

            //$user->importers = null;
            $user->licences = $licences;
            $user->importers = $importers;

            $db->setTable("'previo'.cprevio_modulos M");
            $db->setJoins("INNER JOIN 'previo'.cprevio_supermodulos SM ON SM.id_supermodulo = M.id_supermodulo");
            $db->setFields(array(
                "M.id_modulo",
                "M.id_supermodulo",
                "SM.nombre_supermodulo",
                "M.nombre_modulo",
                "M.descrip_modulo",
                "M.controller_modulo",
                "M.orden_modulo",
                "M.icono_modulo",
            ));

            if($user->id_tipousuario ==  1) {
                $db->setParameters('TRUE ORDER BY M.orden_modulo ASC');
            } else if ($user->id_tipousuario ==  2){
                if($idClient == 3558){
                    $db->setParameters('M.id_modulo NOT IN (6,7) ORDER BY M.orden_modulo ASC');
                } else {
                    $db->setParameters('M.id_modulo NOT IN (5,6,7,8,9)  ORDER BY M.orden_modulo ASC');
                }
              
            } else {
                $db->setParameters('SM.id_supermodulo = 2 ORDER BY SM.id_supermodulo, M."orden_modulo" ASC');
            }

            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $applications = $db->query();


            $superModulesArray = Array();
            foreach ($applications as $app) {
                $superModulesArray[$app['id_supermodulo']] = constant('Dictionary::' . $app['nombre_supermodulo']);
            }

            $superModules = array_unique($superModulesArray);

            $appArray = Array ();
            foreach ($superModules as $key => $value) {
                $temporalArray = Array();
                $temporalArray['id'] = 'treepanel_' . $key;
                $temporalArray['xtype'] = 'treepanel';
                $temporalArray['rootVisible'] = false;
                $temporalArray['title'] = $value;
                $temporalArray['lines'] = false;

                if ($user->id_tipousuario ==  3 && $key == 1) {
                    $temporalArray['collapsed'] = true;
                }else{
                    $temporalArray['collapsed'] = false;
                }
                foreach ($applications as $app) {
                    if($key == $app['id_supermodulo']) {
                        $temporalArray['root']['expanded'] = true;
                        $temporalArray['root']['rootVisible'] = false;

                        $temporalArray2 = Array();
                        $temporalArray2['text'] = constant('Dictionary::' . $app['nombre_modulo']);
                        $temporalArray2['controller'] = $app['controller_modulo'];
                        $temporalArray2['iconCls'] = $app['icono_modulo'];
                        $temporalArray2['leaf'] = true;

                        $temporalArray['root']['children'][] = $temporalArray2;
                    }
                }
                $appArray[] = $temporalArray;
            }

            $user->applications = $appArray;

            $db->setTable("'general'.casag_lenguajes L");
            $db->setJoins('');
            $db->setFields(array(
                "L.id_lenguaje",
                "L.nombre_lenguaje",
                "L.archivojs_lenguaje",
                "L.diccionario_lenguaje",
            ));
            $where = "L.id_lenguaje = ? AND L.status_lenguaje = 1";
            $paramsArray = array($language);
            $db->setReturnType(PgsqlQueries::TYPE_OBJECT_ONE);
            $lang = $db->queryParametrize($where, $paramsArray);

            if($lang) {
                $user->id_lenguaje = $lang->id_lenguaje;
                $user->nombre_lenguaje = $lang->nombre_lenguaje;
                $user->archivojs_lenguaje = $lang->archivojs_lenguaje;
                $user->diccionario_lenguaje = $lang->diccionario_lenguaje;
            }

            return $user;
        } else {
            return NULL;
        }
    }

    public function getSuperUser($idClient, $language)
    {
        switch ($language) {
            case 2:
                include_once 'Dictionaries/English/Dictionary.php';
            break;

            default:
                include_once 'Dictionaries/Spanish/Dictionary.php';;
            break;
        }

        //$urlFiles = "../../";
        $urlFiles = "../files/EPrevious/";

        $db = new PgsqlQueries;
        $db->setTable("'previo'.usuarios U");
        $db->setFields(array(
            "U.id_usuario",
            "U.id_cliente",
            "U.id_tipousuario",
            "U.alias_usuario",
            "U.contrasena_usuario",
            "U.nombre_usuario",
            "U.correo_usuario",
            "to_char(U.fechalta_usuario, #DD/MM/YYYY#::text) AS fechalta_usuario",
            "U.status_usuario",

        ));
        $where = "U.correo_usuario = ?";
        $paramsArray = array($this->getUseremail());
        $db->setReturnType(PgsqlQueries::TYPE_OBJECT_ONE);
        $user = $db->queryParametrize($where, $paramsArray);

        $db->setTable("'general'.casac_clientes C");
        $db->setJoins("INNER JOIN 'general'.casag_licencias L ON C.id_cliente = L.id_cliente");
        $db->setJoin("INNER JOIN 'general'.casag_configcliente CS ON CS.id_cliente = C.id_cliente");
        $db->setJoin("INNER JOIN 'general'.casag_temas T ON T.id_tema = CS.id_tema");
        $db->setFields(array(
            "L.id_cliente",
            "T.nombre_tema",
            "T.pathcss_tema",
            "T.pathjs_tema",
            "T.pathcssmov_tema",
            "T.pathjsmov_tema",
            "CS.logo_configcliente",
            "CS.imgintro_configcliente",
            "CS.imgtitulo_configcliente",
            "CS.color_letter_configcliente",
            "CS.color_background_configcliente",
            "CS.color_botones",
            "CS.layout_cliente",
            "CS.color_name_user"

        ));
        $where = "C.id_cliente = ?";
        $paramsArray = array($idClient);
        $client = $db->queryParametrize($where, $paramsArray);

        if($client) {
            $user->id_clienteadmin = $idClient;
            $user->nombre_tema = $client->nombre_tema;
            $user->nombre_tema = $client->nombre_tema;
            $user->pathcss_tema = $client->pathcss_tema;
            $user->pathjs_tema = $client->pathjs_tema;
            $user->pathcssmov_tema = $client->pathcssmov_tema;
            $user->pathjsmov_tema = $client->pathjsmov_tema;
            $user->logo_configcliente = $client->logo_configcliente;
            $user->imgintro_configcliente = $client->imgintro_configcliente;
            $user->imgtitulo_configcliente = $client->imgtitulo_configcliente;
            $user->color_letter_configcliente = $client->color_letter_configcliente;
            $user->color_background_configcliente = $client->color_background_configcliente;
            $user->color_botones = $client->color_botones;
            $user->layout_cliente = $client->layout_cliente;
            $user->color_name_user = $client->color_name_user;
            $user->urlfiles= $urlFiles;
        }

        $db->setTable("'general'.casag_lenguajes L");
        $db->setJoins('');
        $db->setFields(array(
            "L.id_lenguaje",
            "L.nombre_lenguaje",
            "L.archivojs_lenguaje",
            "L.diccionario_lenguaje",
        ));
        $where = "L.id_lenguaje = ? AND L.status_lenguaje = 1";
        $paramsArray = array($language);
        $lang = $db->queryParametrize($where, $paramsArray);

        if($lang) {
            $user->id_lenguaje = $lang->id_lenguaje;
            $user->nombre_lenguaje = $lang->nombre_lenguaje;
            $user->archivojs_lenguaje = $lang->archivojs_lenguaje;
            $user->diccionario_lenguaje = $lang->diccionario_lenguaje;
        }

        if ($user) {
            $idUser = $user->id_usuario;
            $db->setTable("'general'.casag_licencias L");
            $db->setJoins("INNER JOIN 'general'.casac_aduanas A ON L.id_aduana = A.id_aduana");
            $db->setFields(array(
                "L.id_licencia",
                "L.id_aduana",
                "L.patente"
            ));
            $where = "L.id_cliente = ?";
            $paramsArray = array($idClient);
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $licences = $db->queryParametrize($where, $paramsArray);

//////////////////////////////SE AGREGÓ IMPORTERS
            $db->setTable("'general'.casac_importadores");
            $db->setJoins("");
            $db->setFields(array(
                "nombre_importador",
                "rfc_importador",
                "id_importador"
            ));
            $where = "id_cliente = ? AND status_importador = 1";
            $paramsArray = array($idClient);
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $importers = $db->queryParametrize($where, $paramsArray);

            //$user->importers = null;
            $user->licences = $licences;
            $user->importers = $importers;

            $db->setTable("'previo'.cprevio_modulos M");
            $db->setJoins("INNER JOIN 'previo'.cprevio_supermodulos SM ON SM.id_supermodulo = M.id_supermodulo");
            $db->setFields(array(
                "M.id_modulo",
                "M.id_supermodulo",
                "SM.nombre_supermodulo",
                "M.nombre_modulo",
                "M.descrip_modulo",
                "M.controller_modulo",
                "M.orden_modulo",
                "M.icono_modulo",
            ));

            if($user->id_tipousuario ==  1) {
                $db->setParameters('TRUE ORDER BY M.id_supermodulo, M.orden_modulo ASC');
            }

            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $applications = $db->query();

            $superModulesArray = Array();
            foreach ($applications as $app) {
                $superModulesArray[$app['id_supermodulo']] = constant('Dictionary::' . $app['nombre_supermodulo']);
            }

            $superModules = array_unique($superModulesArray);

            $appArray = Array ();
            foreach ($superModules as $key => $value) {
                $temporalArray = Array();
                $temporalArray['id'] = 'treepanel_' . $key;
                $temporalArray['xtype'] = 'treepanel';
                $temporalArray['rootVisible'] = false;
                $temporalArray['title'] = $value;
                $temporalArray['collapsed'] = true;
                $temporalArray['lines'] = false;

                foreach ($applications as $app) {
                    if($key == $app['id_supermodulo']) {
                        $temporalArray['root']['expanded'] = true;
                        $temporalArray['root']['rootVisible'] = false;

                        $temporalArray2 = Array();
                        $temporalArray2['text'] = constant('Dictionary::' . $app['nombre_modulo']);
                        $temporalArray2['controller'] = $app['controller_modulo'];
                        $temporalArray2['iconCls'] = $app['icono_modulo'];
                        $temporalArray2['leaf'] = true;

                        $temporalArray['root']['children'][] = $temporalArray2;
                    }
                }
                $appArray[] = $temporalArray;
            }

            $user->applications = $appArray;
            return $user;
        } else {
            return NULL;
        }
    }

    /**
     *
     * @return the $_id
     */
    public function getId()
    {
        return $this->_id_usuario;
    }

    /**
     *
     * @return the $_idclient
     */
    public function getIdclient()
    {
        return $this->_id_cliente;
    }

    /**
     *
     * @return the $_idclientadmin
     */
    public function getIdclientAdmin()
    {
        return $this->_id_clienteadmin;
    }

    /**
     *
     * @return the $_idusertype
     */
    public function getIdusertype()
    {
        return $this->_id_tipousuario;
    }

    /**
     *
     * @return the $_usernickname
     */
    public function getUsernickname()
    {
        return $this->_alias_usuario;
    }

    /**
     *
     * @return the $_userpassword
     */
    public function getUserpassword()
    {
        return $this->_contrasena_usuario;
    }

    /**
     *
     * @return the $_username
     */
    public function getUsername()
    {
        return $this->_nombre_usuario;
    }

    /**
     *
     * @return the $_useremail
     */
    public function getUseremail()
    {
        return $this->_correo_usuario;
    }

    /**
     *
     * @return the $_userregistrationdate
     */
    public function getUserregistrationdate()
    {
        return $this->_fechalta_usuario;
    }

    /**
     *
     * @return the $_userstatus
     */
    public function getUserstatus()
    {
        return $this->_status_usuario;
    }

    /**
     *
     * @return the $_clientname
     */
    public function getClientname()
    {
        return $this->_nombre_cliente;
    }

    /**
     *
     * @param integer $_id
     */
    public function setId($_id)
    {
        $this->_id_usuario = $_id;
    }

    /**
     *
     * @param integer $_idclientadmin
     */
    public function setIdclientAdmin($_idclientadmin)
    {
        $this->_id_clienteadmin = $_idclientadmin;
    }

    /**
     *
     * @param integer $_idclient
     */
    public function setIdclient($_idclient)
    {
        $this->_id_cliente = $_idclient;
    }

    /**
     *
     * @param integer $_idusertype
     */
    public function setIdusertype($_idusertype)
    {
        $this->_id_tipousuario = $_idusertype;
    }

    /**
     *
     * @param string $_usernickname
     */
    public function setUsernickname($_usernickname)
    {
        $this->_alias_usuario = $_usernickname;
    }

    /**
     *
     * @param string $_userpassword
     */
    public function setUserpassword($_userpassword)
    {
        $this->_contrasena_usuario = $_userpassword;
    }

    /**
     *
     * @param string $_username
     */
    public function setUsername($_username)
    {
        $this->_nombre_usuario = $_username;
    }

    /**
     *
     * @param string $_useremail
     */
    public function setUseremail($_useremail)
    {
        $this->_correo_usuario = $_useremail;
    }

    /**
     *
     * @param string $_userregistrationdate
     */
    public function setUserregistrationdate($_userregistrationdate)
    {
        $this->_fechalta_usuario = $_userregistrationdate;
    }

    /**
     *
     * @param integer $_userstatus
     */
    public function setUserstatus($_userstatus)
    {
        $this->_status_usuario = $_userstatus;
    }

    /**
     *
     * @param string $_clientname
     */
    public function setClientname($_clientname)
    {
        $this->_nombre_cliente = $_clientname;
    }
}

?>

