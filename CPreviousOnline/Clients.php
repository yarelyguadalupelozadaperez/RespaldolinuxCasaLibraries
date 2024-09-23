
<?php

/**
 * CasaLibraries Clients class
 * File Clients.php
 * Connection to posgresql database
 *
 * @category     CasaLibraries
 * @package     CasaLibraries_CasaSkeketon
 * @copyright     Copyright (c) 2005-2013 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author         AJPE
 * @version     Clients 1.0.0
 *
 */
include 'CasaLibraries/CasaDb/PgsqlQueries.php';
require_once 'CasaLibraries/ExportationReports/ExportToExcel.php';

$accessurl = $_SERVER["REQUEST_SCHEME"]. "://".$_SERVER['HTTP_HOST'];

class Clients{

    public function getStyles($idClient){
        $db = new PgsqlQueries;

        $db->setTable('general.casag_configcliente gcc');
        $db->setJoins("INNER JOIN general.casag_temas gct ON gcc.id_tema = gct.id_tema");
        $db->setFields(array(
            '*'
        ));
        $db->setParameters("id_cliente = " .$idClient."");
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $responseTotal = $db->query();
        $responseTotal['total'] = count($responseTotal);

        return $responseTotal;
        exit();

    }

    public function UpdateClients($newName, $idClient, $newTheme, $newLogo){

        $db = new PgsqlQueries;
        if ($newName != '') {
            try {
                $db->setTable('general.casac_clientes');
                $db->setValues(array(
                    'nombre_cliente' => $newName
                ));
                $db->setParameters("id_cliente = $idClient");
                $response = $db->update();
                
            } catch (Exception $exc) {
                var_dump($exc->getMessage());
                exit();
            }
        }
     
        if ($newTheme != '' || $newLogo != '') {
            try {
                $accessurl = $_SERVER["REQUEST_SCHEME"]. "://".$_SERVER['HTTP_HOST'];
                
                $nameRealImage = str_replace('.jpg', '.png', $newLogo);
                $nameRealImg = str_replace('.png', '', $nameRealImage);

                $date = date("m_j_Y_G_i_s");  

                $db->setTable('general.casag_configcliente');
                $db->setValues(array('logo_configcliente' => 'themes/default/images/logos/'.$newLogo,
                                     'imgintro_configcliente' => $accessurl . '/ClientCustomization/'.$idClient.'/'.$nameRealImg.$date.'.png',
                                     'layout_cliente' => $idClient.'.png',
                                     'id_tema' => $newTheme
                ));                    
                
                $db->setParameters("id_cliente = $idClient");
                $response = $db->update();

                return true;
                exit();
                
            } catch (Exception $exc) {
                var_dump($exc->getMessage());
                exit();
            }
        }

        return true;
        exit();
    }

    public function saveStyles($idcliente, $idtema, $logoconfigcliente, $imgintro_configcliente, $imgtitulo_configcliente, $color_letter_configcliente, $color_background_configcliente, $header_color, $color_botones, $layout_cliente, $color_name_user, $ruta_plantillla_manual, $color_download_manual){
        $db = new PgsqlQueries;
        try {

            $db->setTable('general.casag_configcliente');
            $db->setValues(array(
                'id_cliente'                      => $idcliente,
                'id_tema'                         => $idtema,
                'logo_configcliente'              => $logoconfigcliente,
                'imgintro_configcliente'          => $imgintro_configcliente,
                'imgtitulo_configcliente'         => $imgtitulo_configcliente,
                'color_letter_configcliente'      => $color_letter_configcliente,
                'color_background_configcliente'  => $color_background_configcliente,
                'header_color'                    => $header_color,
                'color_botones'                   => $color_botones,
                'layout_cliente'                  => $layout_cliente,
                'color_name_user'                 => $color_name_user,
                'ruta_plantillla_manual'          => $ruta_plantillla_manual,
                'color_download_manual'           => $color_download_manual
            ));
            $clientStyl = $db->insert();

            return true;
            exit();
        } catch (Exception $exc) {
            var_dump($exc->getMessage());
            exit();
        }
    }


    public function getColors(){
        $db = new PgsqlQueries;

        $db->setTable('general.casag_temas');
        $db->setJoins("");
        $db->setFields(array(
            'id_tema',
            'nombre_tema',
            'pathcss_tema',
            'pathjs_tema',
            'pathcssmov_tema',
            'pathjsmov_tema',
            'status_tema',
            'colors'
        ));
        $db->setParameters("TRUE ORDER BY id_tema");
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $responseTotal = $db->query();


        
        $responseTotal['total'] = count($responseTotal);

        if(count($responseTotal) > 1) {
            return $responseTotal;
        } else {
            return NULL;
        }

    }



    public function saveImage($imageObj, $idcliente, $nameImage, $flag, $num, $mainRoute){
        

        if ($flag == 1) {
            //Define la ruta donde se almacena el logo del cliente
            $path = $mainRoute.'/public/themes/default/images/logos/';
            if (!file_exists($path)) {
                mkdir($path, 0777);
                chmod($path, 0777);
            } 
            try {
            //Imagen en base 64
            $data = base64_decode($imageObj);
            //Formamos ruta y nambre de imagen
            $name = $path . $idcliente . '_' . $nameImage;
            //Se crea la imagen
            file_put_contents($name, $data);
            //Se asigana permisos a la imagen
            chmod($name, 0777);

                return true;
                exit();
            } catch (Exception $exc) {
                var_dump($exc->getMessage());
                exit();
            }
        } else if($flag == 2){
            $pathCarpeta = $mainRoute.'/ClientCustomization/'.$idcliente.'/';
            try {
                if (!file_exists($pathCarpeta)) {
                    mkdir($pathCarpeta, 0777);
                    chmod($pathCarpeta, 0777);
                } 
                
                $data = base64_decode($imageObj);
                $nameRealImage = str_replace('.jpg', '.png', $nameImage);
                //Imagen en base 64
                //Formamos ruta y nambre de imagen
                $name = $pathCarpeta . $idcliente . '_' . $nameRealImage;
                //Se crea la imagen
                file_put_contents($name, $data);
                //Se asigana permisos a la imagen
                chmod($name, 0777);

                return true;
                exit();
            } catch (Exception $exc) {
                var_dump($exc->getMessage());
                exit();
            }
            
        }else if($flag == 3){
            $pathCarpeta = $mainRoute.'/ClientCustomization/'.$idcliente.'/';
            try {
                $date = date("m_j_Y_G_i_s"); 
                if (!file_exists($pathCarpeta)) {
                    mkdir($pathCarpeta, 0777);
                    chmod($pathCarpeta, 0777);
                } 
                array_map('unlink', glob($pathCarpeta."*.png"));
                $data = base64_decode($imageObj);
                $nameRealImage = str_replace('.jpg', '.png', $nameImage);
                $nameRealIm = str_replace('.png', '', $nameRealImage);
                //Imagen en base 64
                //Formamos ruta y nambre de imagen
                $name = $pathCarpeta . $nameRealIm.$date.'.png';
                //Se crea la imagen
                file_put_contents($name, $data);
                //Se asigana permisos a la imagen
                chmod($name, 0777);

                return true;
                exit();
            } catch (Exception $exc) {
                var_dump($exc->getMessage());
                exit();
            }
            
        }else if ($flag == 4) {
            //Define la ruta donde se almacena el logo del cliente
            $path = $mainRoute.'/ClientCustomization/prueba/'.$idcliente.'/';
            if (!file_exists($path)) {
                    mkdir($path, 0777);
                    chmod($path, 0777);
                } 
            try {
            //Imagen en base 64
            $data = base64_decode($imageObj);
            //Formamos ruta y nambre de imagen
            $name = $path . $idcliente . '_' . $nameImage;
            //Se crea la imagen
            file_put_contents($name, $data);
            //Se asigana permisos a la imagen
            chmod($name, 0777);

                return true;
                exit();
            } catch (Exception $exc) {
                var_dump($exc->getMessage());
                exit();
            }
        }else if($flag == 5){
            $pathCarpeta = $mainRoute.'/ClientCustomization/prueba/'.$idcliente.'/';
            if (!file_exists($pathCarpeta)) {
                    mkdir($pathCarpeta, 0777);
                    chmod($pathCarpeta, 0777);
                } 
            try {
                $date = date("m_j_Y_G:i:s"); 
                $data = base64_decode($imageObj);
                $nameRealImage = str_replace('.jpg', '.png', $nameImage);
                $nameRealIm = str_replace('.png', '', $nameRealImage);
                //Imagen en base 64
                //Formamos ruta y nambre de imagen
                $name = $pathCarpeta .$idcliente.$num.'.png';
                //Se crea la imagen
                file_put_contents($name, $data);
                //Se asigana permisos a la imagen
                chmod($name, 0777);

                return true;
                exit();
            } catch (Exception $exc) {
                var_dump($exc->getMessage());
                exit();
            }
            
        }else{
            $pathCarpeta = $mainRoute.'/public/img/';
            try {   
                $data = base64_decode($imageObj);
                //Imagen en base 64
                //Formamos ruta y nambre de imagen
                $name = $pathCarpeta . $idcliente .'.png';
                //Se crea la imagen
                file_put_contents($name, $data);
                //Se asigana permisos a la imagen
                chmod($name, 0777);

                return true;
                exit();
            } catch (Exception $exc) {
                var_dump($exc->getMessage());
                exit();
            }
        }
    }

    public function savePatentAduana($id_cliente, $aduanast, $patente){
        $db = new PgsqlQueries;

        $db->setTable('general.casag_licencias');
        $db->setFields(array(
            "id_licencia"
        ));
        $db->setParameters("id_cliente = '$id_cliente' AND id_aduana = '$aduanast' AND patente = '$patente'");
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ONE);
        $clients = $db->query();

        if (!$clients) {
            try {
                $db->setTable('general.casag_licencias');
                $db->setValues(array(
                    'id_cliente' => $id_cliente,
                    'id_aduana' => $aduanast,
                    'status_licencia' => 1,
                    'patente' => $patente

                ));
                $clientsi = $db->insert();
                //busca la licencia


                $db->setTable('general.casag_licencias');
                $db->setJoins("");
                $db->setFields(array(
                    'id_licencia'
                ));
                $db->setParameters("id_cliente = '$id_cliente' AND id_aduana = '$aduanast' AND patente = '$patente'");
                $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                $id_lic = $db->query();
                $id_licencia = $id_lic[0]["id_licencia"];

                //inserta en licencias
                $db->setTable('general.casag_licenciasistema');
                $db->setValues(array(
                    'id_licencia' => $id_licencia,
                    'id_sistema' => 2

                ));
                $licencias = $db->insert();


                return true;
                exit();
            } catch (Exception $exc) {
                var_dump($exc->getMessage());
                exit();
            }
        }
        else{
            return false;
        }
        
    }

    public function getAduana($start, $limit, $idUser, $idClient, $idTypeuser){
        $db = new PgsqlQueries;

      //esta surve para sacar el conteo total porque el paginador tiene un total

        $db->setTable('general.casac_aduanas');
        $db->setJoins("");
        $db->setFields(array(
            'id_aduana',
            'clave_aduana',
            'nombre_aduana'
        ));
        $db->setParameters("TRUE ORDER BY id_aduana");
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $responseTotal = $db->query();


        
         $responseTotal['total'] = count($responseTotal);
   
        if(count($responseTotal) > 1) {
            return $responseTotal;
        } else {
            return NULL;
        }
    }

    public function getPatent($start, $limit, $idCli){
        $db = new PgsqlQueries;
      //esta surve para sacar el conteo total porque el paginador tiene un total

        $db->setTable('general.casag_licencias cgl');
        $db->setJoins('INNER JOIN general.casac_aduanas cca ON cgl.id_aduana = cca.id_aduana');
        $db->setFields(array(
            'id_licencia', 
            'id_cliente', 
            'cgl.id_aduana', 
            'status_licencia', 
            'patente', 
            'cca.clave_aduana', 
            'cca.nombre_aduana'
        ));
        $db->setParameters("id_cliente = $idCli ORDER BY id_cliente");
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $responseTotal = $db->query();
        
         $responseTotal['total'] = count($responseTotal);
   
        if(count($responseTotal) > 1) {
            return $responseTotal;
        } else {
            return NULL;
        }
    }

    public function getClients($start, $limit, $idUser, $idClient, $idTypeuser, $query) {

      //esta surve para ir paginando
        $db = new PgsqlQueries;

        $db->setTable('general.casac_clientes');
        $db->setJoins("");
        $db->setFields(array(
            'id_cliente',
            'nombre_cliente',
            'tax_cliente',
            'fechareg_cliente',
            'accesso_cliente',
            'status_cliente'
        ));
        if (empty($query)) {
            $db->setParameters("status_cliente = 1 OR status_cliente = 0 ORDER BY id_cliente LIMIT $limit OFFSET $start");
        } else {
            if (is_numeric($query)) {
                $db->setParameters("(status_cliente = 1 OR status_cliente = 0) AND id_cliente = $query ORDER BY id_cliente LIMIT $limit OFFSET $start");
            } else {
                $db->setParameters("(status_cliente = 1 OR status_cliente = 0) AND nombre_cliente iLIKE SP_ASCII(CONCAT('%$query%')) ORDER BY id_cliente LIMIT $limit OFFSET $start");
            }
        }
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $response = $db->query();
      //esta surve para sacar el conteo total porque el paginador tiene un total

        $db->setTable('general.casac_clientes'); 
        $db->setJoins("");
        $db->setFields(array(
            'id_cliente',
            'nombre_cliente',
            'tax_cliente',
            'fechareg_cliente',
            'accesso_cliente',
            'status_cliente'
        ));
        if (empty($query)) {
            $db->setParameters("status_cliente = 1 OR status_cliente = 0 ORDER BY id_cliente");
        } else {
            if (is_numeric($query)) {
                $db->setParameters("(status_cliente = 1 OR status_cliente = 0) AND id_cliente  = $query ORDER BY id_cliente");
            } else {
                $db->setParameters("(status_cliente = 1 OR status_cliente = 0) AND nombre_cliente iLIKE SP_ASCII(CONCAT('%$query%')) ORDER BY id_cliente");
            }
        }
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $responseTotal = $db->query();


        
        $response['total'] = count($responseTotal);
   
        if(count($response) > 1) {
            return $response;
        } else {
            return NULL;
        }
        
    }

    public function saveClients($idcliente, $nom_client, $fullDate, $h_styles){
        $db = new PgsqlQueries;
        
        
        $db->setTable('general.casac_clientes');
        $db->setFields(array(
            "id_cliente"
        ));
        $db->setParameters("id_cliente = '$idcliente'");
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ONE);
        $clients = $db->query();

        if (!$clients && $h_styles == 'false') {
            try {
                $db->setTable('general.casac_clientes');
                $db->setValues(array(
                    "id_cliente" => $idcliente,
                    "nombre_cliente" => $nom_client,
                    "fechareg_cliente" => $fullDate,
                    "status_cliente" =>  1
                ));
                $clientsi = $db->insert();
                return true;
                exit();
            } catch (Exception $exc) {
                var_dump($exc->getMessage());
                exit();
            }
        }else if (!$clients && $h_styles == 'true') {
            try {
                $db->setTable('general.casac_clientes');
                $db->setValues(array(
                    "id_cliente" => $idcliente,
                    "nombre_cliente" => $nom_client,
                    "fechareg_cliente" => $fullDate,
                    "status_cliente" =>  1
                ));
                $clientsi = $db->insert();


                $db->setTable('general.casag_configcliente');
                $db->setValues(array(
                    "id_cliente" => $idcliente,
                    "id_tema" => 1,
                    "logo_configcliente" => 'themes/default/images/logos/IMAGENPREVIO.png',
                    "imgintro_configcliente" =>  'https://e-casa.com.mx//ClientCustomization/SistemasCasa/headerscasaasE-Previos.png',
                    "imgtitulo_configcliente" => '<center><img src= public/themes/default/images/home/homeSC.png width="70%"></center>',
                    "color_letter_configcliente" => '#1f80cc',
                    "color_background_configcliente" => '#dfeaf2',
                    "header_color" => '#4169E1',
                    "color_botones" => '<ul class="typcn-effect-11">',
                    "layout_cliente" => 'SistemasCasa.png',
                    "color_name_user" => '#1f80cc',
                    "ruta_plantillla_manual" => '',
                    "color_download_manual" => '#f2dddd'
                ));
                $clientStyle = $db->insert();

                return true;
                exit();
            } catch (Exception $exc) {
                var_dump($exc->getMessage());
                exit();
            }

        }
        else{
            return false;
        }
    }

    public function deleteClients($idCliente){

        try { 
            $db = new PgsqlQueries;
            $db->setTable('general.casac_clientes');
            $db->setValues(array(
                "status_cliente" => 2
            ));
            $db->setParameters("id_cliente =  $idCliente");

            return $classification = $db->update();
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }    
    }

    public function deleteDemo($idCliente){
        $path = '../e-Previos/public/themes/default/images/logos/prueba/'.$idCliente;
        $pathCarpeta = '../ClientCustomization/prueba/'.$idCliente;

        if (file_exists($path)) {
            array_map('unlink', glob($path."/*.png"));
            rmdir($path); 
        }
        if (file_exists($pathCarpeta)) {
            array_map('unlink', glob($pathCarpeta."/*.png"));
            rmdir($pathCarpeta); 
        }
        return true;
    }



    public function getClienteReport($datei, $datef) {
        
        $db = new PgsqlQueries;
        try {
            $array = ["id_cliente", "nombre_cliente", "fecha_registro", "ultima_operacion", "numero_operaciones"];

            $column = '';
            foreach ($array as $row) {
                $column .= $row . ", ";
            }
            $column = substr($column, 0, - 1);    
            
            $db->setTable('previo.cprevo_refe refe');
            $db->setFields(array(
                'l.id_cliente', 
                'cli.nombre_cliente', 
                'fechareg_cliente as fecha_registro', 
                'getlastoperation(l.id_cliente) as ultima_operacion', 
                'count(refe.id_prev) as numero_operaciones'
            ));
            $db->setJoins("INNER JOIN general.casag_licencias l on refe.id_licencia = l.id_licencia
                            INNER JOIN general.casac_clientes cli on l.id_cliente = cli.id_cliente
                            INNER JOIN previo.cprevo_previos prev on refe.id_prev = prev.id_prev");


            $where = "fec_soli  > ? AND fec_soli  <= ? GROUP BY l.id_cliente,  cli.nombre_cliente, fechareg_cliente ORDER BY numero_operaciones DESC";
            $paramsArray = array($datei, $datef);
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $clientReport = $db->queryParametrize($where, $paramsArray);
            
            $counter = 0;
            
            
            foreach ($clientReport as $client) {
                foreach ($array as $item) {
                    if (isset($client[$item])) {
                        $clienteReports[$counter][$item] = $client[$item];
                    }
                }
                $counter ++;
            }
            $clienteReports["total"] = count($clientReport);
        } catch (Exception $e) {
            var_dump($e->getMessage());
            exit();
        }
        return $clienteReports;
        
    }
    
}
?>
