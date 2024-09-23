<?php

set_time_limit(3000);
ini_set('max_execution_time', 6000);
include 'CasaLibraries/CasaDb/PgsqlQueries3.php';
include 'CasaLibraries/CasaDb/PgsqlQueries.php';
require_once 'CasaLibraries/ExportationReports/ExportToExcel.php';
require_once 'CasaLibraries/CasaDb/ConnectionFactory.class.php';

if ($_SERVER['HTTP_HOST'] == "localhost") {
    $accessurl = $_SERVER["REQUEST_SCHEME"]. "://".$_SERVER['HTTP_HOST'];
} else {
    $accessurl = $_SERVER["REQUEST_SCHEME"]. "s://".$_SERVER['HTTP_HOST'];
}


class Listings {
    
    
    
    
    private $_connection;
    
    public function getTotalPictures($idClient, $reference) {
        $db =  $this->getConnection();
        $db->setTable("'previo'.cprevo_refe pcr");
        $db->setJoins("INNER JOIN 'general'.casag_licencias gcl ON pcr.id_licencia = gcl.id_licencia");
        $db->setFields(array("gcl.id_cliente"));
        $where = "pcr.num_refe = ?";
        $paramsArray = array($reference);
        $variable = $this->getConnection();
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ONE);
        $validateData = $db->queryParametrize($where, $paramsArray);
        
        if(intval($validateData["id_cliente"]) == $idClient){
            
            try {
                $tempArray = Array();
                
                $db =  $this->getConnection();
                $db = new PgsqlQueries;
                $db->setTable("previo.cprevo_refe");
                $db->setJoins('');
                $db->setFields(array("num_fotos"));
                $where = "num_refe = ?";
                $paramsArray = array($reference);
                $variable = $this->getConnection();
                $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                $totaFotosRef = $db->queryParametrize($where, $paramsArray);
                
                $tempArray["message"] = "Operación exitosa";
                $tempArray["success"] = "true";
                $tempArray["totalF"] = $totaFotosRef[0]["num_fotos"];
                return $tempArray;
            }catch(Exception $e){
                $tempArray["message"] = "Ocurrió un error al reactivar la descarga de la referencia en la tablet.";
                $tempArray["success"] = "false";
                return $tempArray;
            }
            
        }else{
            return '0P';
        }
    }
    
    public function getObsIns($idObsInc){
        $db =  $this->getConnection();
        $db->setTable("previo.cprevo_facpar");
        $db->setJoins("");
        $db->setFields(array("inc_part", "obs_frac"));
        $db->setParameters("id_partida = $idObsInc");
        
        $variable = $this->getConnection();
        $db->setReturnType($variable ::TYPE_ARRAY_ONE);
        
        $obsinc = $db->query();
        return $obsinc;
    }
    
    public function getColumnsListGrid($view) {
        $db = $this->getConnection();
        $db->setTable("information_schema.columns");
        $db->setFields(array(
            "column_name",
            "data_type"
        ));
        
        if($view == 'list'){
            $where = "table_name = ?";
            $paramsArray = array("listacolumnas");
        } else {
            $where = "table_name = ?";
            $paramsArray = array($view);
        }
        
        $variable = $this->getConnection();
        $db->setReturnType($variable ::TYPE_ARRAY_ALL);
        $columnsListGrid = $db->queryParametrize($where, $paramsArray);
        //$columnsListGrid = $db->query();
        
        if(count($columnsListGrid) > 0) {
            return $columnsListGrid;
        } else {
            return NULL;
        }
    }
    
    ///////
    public function getReferenceByIdPrev($idClient, $idPrevio) {
        $db =  $this->getConnection();
        $db->setTable("'previo'.cprevo_refe R");
        $db->setJoins("INNER JOIN 'general'.casag_licencias L ON R.id_licencia = L.id_licencia");
        $db->setFields(array(
            "R.num_refe"
            
        ));
        $where = "R.id_prev = ? AND L.id_cliente = ?";
        $paramsArray = array($idPrevio, $idClient);
        $variable = $this->getConnection();
        $db->setReturnType($variable ::TYPE_ARRAY_ONE);
        $prev = $db->queryParametrize($where, $paramsArray);
        
        return $prev;
    }
    ///////
    
    public function getImporters($idUser, $query, $idClient) {
        
        $query = strtoupper($query);
        $db = $this->getConnection();
        
        $db->setTable("general.casac_importadores");
        
        $db->setFields(array(
            "id_importador",
            "nombre_importador"
        ));
        
        if ($idUser == 1) {
            if ($query != "") {
                $db->setParameters("id_cliente = ".$idClient." AND UPPER(nombre_importador) LIKE '%$query%' ORDER BY id_importador ASC");
            } else {
                $db->setParameters("id_cliente = ".$idClient." ORDER BY id_importador ASC");
            }
        } else {
            if ($query != "") {
                $db->setParameters("id_cliente = ".$idClient." AND UPPER(nombre_importador) LIKE '%$query%' ORDER BY id_importador ASC");
            } else {
                $db->setParameters("id_cliente = ".$idClient." ORDER BY id_importador ASC");
            }
        }
        
        
        $variable = $this->getConnection();
        $db->setReturnType($variable ::TYPE_ARRAY_ALL);
        $importers = $db->query();
        if(count($importers) > 0) {
            return $importers;
        } else {
            return NULL;
        }
    }
    
    public function getDataGrid($select, $view, $idClient, $where, $start, $limit, $licences, $reference, $partida, $importers) {
        
        $extraParams = "";
        $whereImporter = "";
        $whereAduana = "";
        $whereDefault = "";
        $whereDefaultCustom = "";
        $wherelicence = "";
        $whereimporter = "";
        $whereDefaultImport = "";
        
        $db = $this->getConnection();
        
        
        if ($view == 'list') {
            if($licences AND count($licences)>0){
                
                foreach ($licences as $licence) {
                    $wherelicence .= $licence["id_licencia"] .',';
                }
                
                
            }else{
                $wherelicence .= '0,';
            }
            $wherelicence = substr($wherelicence, 0, -1);
            $extraParams .= ' AND L.id_licencia IN ('.$wherelicence.')';
            
            if($importers AND count($importers)>0){
                foreach ($importers as $importer) {
                    $whereimporter .= $importer["id_importador"] .',';
                }
                
                
            }else{
                $whereimporter .= '0,';
            }
            $whereimporter = substr($whereimporter, 0, -1);
            $extraParams .= ' AND id_importador IN ('.$whereimporter.')';
        }
        
        if (!$partida) {
            $partida = 0;
        }
        
        if ($where != 'TRUE') {
            if ($where == NULL || $where == ""){
            } else {
                
                foreach ($where as $key => $value) {
                    
                    if ($value != ''){
                        if ($key == "referencia_estilo"){
                        } else if ($key == "initialDate") {
                            $date = new DateTime($value);
                            $dateFilter = date_format($date, "Y-m-d");
                            $extraParams .= " AND fecha >= '$dateFilter'";
                        } else if ($key == "finalDate") {
                            $date = new DateTime($value);
                            $dateFilter = date_format($date, "Y-m-d");
                            $extraParams .= " AND fecha <= '$dateFilter'";
                        } else if ($key == "referencia" || $key == "guias" || $key == "no_contenedor" || $key == "orden_de_compra" || $key == "cliente" || $key == "recinto_fiscal" || $key == 'dependiente'){
                            $value = strtoupper($value);
                            $extraParams .= " AND " . $key . " = '$value'";
                        } else {
                            return 'ED2';
                        }
                    }
                }
            }
        }
        
        if($view == 'list'){
            
            
            $extraParamsN = str_replace("fecha","p.fec_soli", $extraParams);
            $extraParamsN = str_replace("id_aduana","a.id_aduana", $extraParamsN);
            $extraParamsN = str_replace("id_importador","i.id_importador", $extraParamsN);
            $extraParamsN = str_replace("guias","p.num_guia", $extraParamsN);
            $extraParamsN = str_replace("referencia","r.num_refe", $extraParamsN);
            $extraParamsN = str_replace("no_contenedor","cn.numero_contenedor", $extraParamsN);
            $extraParamsN = str_replace("dependiente","p.dep_asigna", $extraParamsN);
            $extraParamsN = str_replace("orden_de_compra","o.num_orcom", $extraParamsN);
            $extraParamsN = str_replace("recinto_fiscal","p.rec_fisc", $extraParamsN);
            $extraParamsN = str_replace("cliente","nombre_importador", $extraParamsN);
            
            
            
            $db =  $this->getConnection();
            $db->setTable("'previo'.cprevo_previos p");
            $db->setJoins("INNER JOIN 'previo'.cprevo_refe r ON p.id_prev = r.id_prev");
            $db->setJoin("INNER JOIN 'general'.casag_licencias l ON r.id_licencia = l.id_licencia");
            $db->setJoin("INNER JOIN 'general'.casac_aduanas a ON l.id_aduana = a.id_aduana");
            $db->setJoin("INNER JOIN 'general'.casac_clientes c ON l.id_cliente = c.id_cliente");
            $db->setJoin("LEFT JOIN 'previo'.cop_conten cn ON p.id_prev = cn.id_prev");
            $db->setJoin("LEFT JOIN 'previo'.cop_orcom o ON p.id_prev = o.id_prev");
            $db->setJoin("LEFT JOIN 'general'.casac_importadores i ON p.id_importador = i.id_importador");
            $db->setJoin("LEFT JOIN 'previo'.tipo_previo tp ON p.tip_prev = tp.clave");
            $db->setJoin("LEFT JOIN 'previo'.tipo_mercancia tm ON p.tip_merc = tm.clave");
            $db->setJoin("LEFT JOIN 'previo'.rev_autoridad ra ON p.rev_auto = ra.clave");
            
            $db->setFields(array(
                "p.id_prev AS id_previo",
                "c.id_cliente",
                "r.num_fotos",
                "p.id_importador",
                "a.id_aduana",
                "concat(#<font color=\"Blue\">#, r.num_refe, #</font>#) AS referencia_estilo",
                "r.num_refe AS referencia",
                "a.clave_aduana AS aduana",
                "p.fec_soli AS fecha",
                "i.nombre_importador AS cliente",
                "p.tot_bultr AS bultos",
                "p.num_guia AS guias",
                "getconsfactur(p.id_prev) AS consecutivo_factura",
                "getidfactur(p.id_prev) AS id_factur",
                "getfactur(p.id_prev) as numero_de_la_primera_factura",
                "p.fec_soli AS fecha_factura",
                "getconten(p.id_prev) AS no_contenedor",
                "p.rec_fisc AS recinto_fiscal",
                "getorder(p.id_prev) AS orden_de_compra",
                "p.obs_prev AS observaciones",
                "p.pes_brut AS peso_bruto",
                "p.dep_asigna AS dependiente",
                "to_char(p.hora_inicio, #HH:MI#::text) AS hora_inicio",
                "to_char(p.hora_fin, #DD-MM-YYYY HH:MI#::text) AS hora_fin",
                "l.patente",
                "r.estatus_refe",
                "tp.descripcion_previo",
                "tm.descripcion_mercancia",
                "ra.descripcion_rev_auto",
                "p.fol_soli",
                "r.tip_ope"
                /*"CASE r.tip_refe
                 WHEN 1 THEN #Trafico#::text
                 WHEN 2 THEN #Consolidado#::text
                 WHEN 3 THEN #Bodega#::text
                 ELSE #Trafico#::text
                 END AS tipo_previo"*/
            ));
            
            $db->setParameters("c.id_cliente = $idClient $extraParamsN $whereDefault ORDER BY p.id_prev ASC LIMIT $limit OFFSET $start");
            
        } else {
            $db =  $this->getConnection();
            $db->setTable("'previo'.$view");
            $db->setFields(array(
                $select
            ));
            
            if ($view == 'bulto') {
                $db->setParameters("id_cliente = $idClient AND referencia = '$reference' LIMIT $limit OFFSET $start");
            } else if ($view == 'merchandise') {
                $db->setParameters("id_cliente = $idClient AND referencia = '$reference' ORDER BY factura, consecutivo ASC LIMIT $limit OFFSET $start");
            } else if ($view == 'serie') {
                $db->setParameters("id_cliente = $idClient AND id_partida = $partida LIMIT $limit OFFSET $start");
            }
        }
        
        $variable = $this->getConnection();
        $db->setReturnType($variable ::TYPE_ARRAY_ALL);
        
        $dataGrid = $db->query();
        
        if($view == 'list'){
            $extraParamsN = str_replace("fecha","p.fec_soli", $extraParams);
            $extraParamsN = str_replace("id_aduana","a.id_aduana", $extraParamsN);
            $extraParamsN = str_replace("id_importador","i.id_importador", $extraParamsN);
            $extraParamsN = str_replace("guias","p.num_guia", $extraParamsN);
            $extraParamsN = str_replace("referencia","r.num_refe", $extraParamsN);
            $extraParamsN = str_replace("no_contenedor","cn.numero_contenedor", $extraParamsN);
            $extraParamsN = str_replace("dependiente","p.dep_asigna", $extraParamsN);
            $extraParamsN = str_replace("orden_de_compra","o.num_orcom", $extraParamsN);
            $extraParamsN = str_replace("recinto_fiscal","p.rec_fisc", $extraParamsN);
            $extraParamsN = str_replace("cliente","nombre_importador", $extraParamsN);
            
            
            $db =  $this->getConnection();
            $db->setTable("'previo'.cprevo_previos p");
            $db->setJoins("INNER JOIN 'previo'.cprevo_refe r ON p.id_prev = r.id_prev");
            $db->setJoin("INNER JOIN 'general'.casag_licencias l ON r.id_licencia = l.id_licencia");
            $db->setJoin("INNER JOIN 'general'.casac_aduanas a ON l.id_aduana = a.id_aduana");
            $db->setJoin("INNER JOIN 'general'.casac_clientes c ON l.id_cliente = c.id_cliente");
            $db->setJoin("LEFT JOIN 'previo'.cop_conten cn ON p.id_prev = cn.id_prev");
            $db->setJoin("LEFT JOIN 'previo'.cop_orcom o ON p.id_prev = o.id_prev");
            $db->setJoin("LEFT JOIN 'general'.casac_importadores i ON p.id_importador = i.id_importador");
            $db->setJoin("LEFT JOIN 'previo'.tipo_previo tp ON p.tip_prev = tp.clave");
            $db->setJoin("LEFT JOIN 'previo'.tipo_mercancia tm ON p.tip_merc = tm.clave");
            $db->setJoin("LEFT JOIN 'previo'.rev_autoridad ra ON p.rev_auto = ra.clave");
            
            $db->setFields(array(
                "*"
                /* "CASE r.tip_refe
                 WHEN 1 THEN #Trafico#::text
                 WHEN 2 THEN #Consolidado#::text
                 WHEN 3 THEN #Bodega#::text
                 ELSE #Trafico#::text
                 END AS tipo_previo"*/
            ));
            
            $db->setParameters("c.id_cliente = $idClient and l.id_licencia IN (SELECT distinct(l.id_licencia) FROM general.casag_licencias l INNER JOIN general.casac_aduanas s ON l.id_aduana = s.id_aduana WHERE l.id_cliente = '$idClient') AND i.id_importador IN (SELECT id_importador FROM general.casac_importadores WHERE id_cliente = '$idClient' AND status_importador = 1) $extraParamsN $whereDefault");
            
        } else {
            $db->setTable("'previo'.$view");
            $db->setFields(array(
                $select
            ));
            
            if ($view == 'bulto') {
                $db->setParameters("id_cliente = $idClient AND referencia = '$reference'");
            } else if ($view == 'merchandise') {
                $db->setParameters("id_cliente = $idClient AND referencia = '$reference'");
            } else if ($view == 'serie') {
                $db->setParameters("id_cliente = $idClient AND id_partida = $partida");
            }
        }
        
        $variable = $this->getConnection();
        $db->setReturnType($variable ::TYPE_ARRAY_ALL);
        
        $count = $db->query();
        
        $dataGrid['total'] = count($count);
        
        if(count($dataGrid) > 0) {
            return $dataGrid;
        } else {
            return NULL;
        }
    }
    
    public function getHeaderTitle($header, $idLanguage) {
        $headerTitle = "";
        if ($idLanguage == 1) {
            if ($header == 'id previo') {
                $headerTitle = 'ID';
            } else if ($header == 'id bulto') {
                $headerTitle = 'No.';
            } else if ($header == 'referencia estilo') {
                $headerTitle = 'Referencia';
            } else if ($header == 'aduana') {
                $headerTitle = 'Aduana';
            } else if ($header == 'fecha') {
                $headerTitle = 'Fecha Solictud';
            } else if ($header == 'cliente') {
                $headerTitle = 'Cliente';
            } else if ($header == 'bultos') {
                $headerTitle = 'Bultos Reales';
            } else if ($header == 'guias') {
                $headerTitle = 'Guías';
            } else if ($header == 'recinto fiscal') {
                $headerTitle = 'Recinto Fiscal';
            } else if ($header == 'numero de la primera factura') {
                $headerTitle = 'No. 1er factura';
            } else if ($header == 'fecha factura') {
                $headerTitle = 'Fecha factura';
            } else if ($header == 'no contenedor') {
                $headerTitle = 'No. de contenedor';
            } else if ($header == 'orden de compra') {
                $headerTitle = 'Orden de Compra';
            } else if ($header == 'cantidad') {
                $headerTitle = 'Cantidad';
            } else if ($header == 'tipo de bulto') {
                $headerTitle = 'Tipo de bulto';
            } else if ($header == 'descripcion del bulto') {
                $headerTitle = 'Descripción del bulto';
            } else if ($header == 'observaciones') {
                $headerTitle = 'Observaciones';
            } else if ($header == 'factura') {
                $headerTitle = 'Factura';
            } else if ($header == 'consecutivo') {
                $headerTitle = 'Partida';
            } else if ($header == 'numero de parte') {
                $headerTitle = 'No. de parte';
            } else if ($header == 'conteo') {
                $headerTitle = 'Conteo';
            } else if ($header == 'unidad') {
                $headerTitle = 'UMC';
            } else if ($header == 'peso') {
                $headerTitle = 'Peso';
            } else if ($header == 'pais de origen') {
                $headerTitle = 'País de Origen';
            } else if ($header == 'descripcion') {
                $headerTitle = 'Descripción de Mercancía';
            } else if ($header == 'hallazgoz') {
                $headerTitle = 'Observaciones';
            } else if ($header == 'id partida') {
                $headerTitle = 'id partida';
            } else if ($header == 'id serie') {
                $headerTitle = 'ID Serie';
            } else if ($header == 'no de parte') {
                $headerTitle = 'No. de parte';
            }  else if ($header == 'modelo') {
                $headerTitle = 'Modelo';
            }  else if ($header == 'marca') {
                $headerTitle = 'Marca';
            } else if ($header == 'serie') {
                $headerTitle = 'Serie';
            } else if ($header == 'referencia') {
                $headerTitle = 'Referencia';
            } else if ($header == 'observaciones bulto') {
                $headerTitle = 'Observaciones';
            } else if ($header == 'factura serie') {
                $headerTitle = 'Factura';
            } else if ($header == 'consecutivo serie') {
                $headerTitle = 'Consecutivo serie';
            } else if ($header == 'total fotos') {
                $headerTitle = 'No. fotos recibidas';
            } else if ($header == 'peso bruto') {
                $headerTitle = 'Peso Bruto';
            } else if ($header == 'dependiente') {
                $headerTitle = 'Dependiente';
            } else if ($header == 'incidencias') {
                $headerTitle = 'Incidencias';
            } else if ($header == 'dimenciones') {
                $headerTitle = 'Dimensiones (Largo, Ancho, Alto)';
            } else if ($header == 'hora inicio') {
                $headerTitle = 'Inicio';
            } else if ($header == 'hora fin') {
                $headerTitle = 'Fecha y Hora Fin';
            } else if ($header == 'Guias') {
                $headerTitle = 'Guías';
            } else if ($header == 'Referencia') {
                $headerTitle = 'Referencia';
            } else if ($header == 'Aduana') {
                $headerTitle = 'Customs';
            } else if ($header == 'fecha solicitud') {
                $headerTitle = 'Fecha solicitud';
            } else if ($header == 'observaciones partida') {
                $headerTitle = 'Observaciones partida';
            } else if ($header == 'dependientes asignados') {
                $headerTitle = 'Dependientes asignados';
            } else if ($header == 'recinto fiscalizado') {
                $headerTitle = 'Recinto fiscalizado';
            } else if ($header == 'partida') {
                $headerTitle = 'Partida';
            } else if ($header == 'umc') {
                $headerTitle = 'UMC';
            } else if ($header == 'umc') {
                $headerTitle = 'UMC';
            } else if ($header == 'consecutivo partida') {
                $headerTitle = 'Consecutivo partida';
            } else if ($header == 'sub modelo lote') {
                $headerTitle = 'Sub Modelo / Lote';
            } else if ($header == 'parte') {
                $headerTitle = 'No. Parte';
            } else if ($header == 'sub_mode') {
                $headerTitle = 'SubModelo/Lote';
            }else if ($header == 'estatus refe') {
                $headerTitle = 'Estatus referencia';
            }else if ($header == 'descripcion previo') {
                $headerTitle = 'Tipo previo';
            }else if ($header == 'descripcion mercancia') {
                $headerTitle = 'Tipo mercancia';
            }else if ($header == 'descripcion rev auto') {
                $headerTitle = 'Revisión con autoridad';
            }else if ($header == 'fraccion') {
                $headerTitle = 'Fracción';
            } else if ($header == 'nico') {
                $headerTitle = 'NICO';
            } else if ($header == 'tipo peso') {
                $headerTitle = 'Tipo peso';
            }else if ($header == 'tipo previo') {
                $headerTitle = 'Tipo referencia';
            }else if ($header == 'estatus partida') {
                $headerTitle = 'Estatus';
            } else if ($header == 'numero fotos esperadas') {
                $headerTitle = 'No. Fotos total';
            } else if ($header == 'nom prov') {
                $headerTitle = 'Proveedor';
            }
            
        } else {
            if ($header == 'id previo') {
                $headerTitle = 'ID';
            } else if ($header == 'id bulto') {
                $headerTitle = 'No.';
            } else if ($header == 'referencia estilo') {
                $headerTitle = 'Reference';
            } else if ($header == 'aduana') {
                $headerTitle = 'Custom house';
            } else if ($header == 'fecha') {
                $headerTitle = 'Date';
            } else if ($header == 'cliente') {
                $headerTitle = 'Client';
            } else if ($header == 'bultos') {
                $headerTitle = 'Package';
            } else if ($header == 'guias') {
                $headerTitle = 'Guides';
            } else if ($header == 'recinto fiscal') {
                $headerTitle = 'Fiscal Area';
            } else if ($header == 'numero de la primera factura') {
                $headerTitle = 'No. 1st bill';
            } else if ($header == 'fecha factura') {
                $headerTitle = 'Bill date';
            } else if ($header == 'no contenedor') {
                $headerTitle = 'No. of container';
            } else if ($header == 'orden de compra') {
                $headerTitle = 'Buy Order';
            } else if ($header == 'cantidad') {
                $headerTitle = 'Quantity';
            } else if ($header == 'tipo de bulto') {
                $headerTitle = 'Type of package';
            } else if ($header == 'descripcion del bulto') {
                $headerTitle = 'Description of package';
            } else if ($header == 'observaciones') {
                $headerTitle = 'Observations';
            } else if ($header == 'factura') {
                $headerTitle = 'Bill';
            } else if ($header == 'consecutivo') {
                $headerTitle = 'Heading';
            } else if ($header == 'numero de parte') {
                $headerTitle = 'No. of part';
            } else if ($header == 'conteo') {
                $headerTitle = 'Count';
            } else if ($header == 'unidad') {
                $headerTitle = 'UMC';
            } else if ($header == 'peso') {
                $headerTitle = 'Weight';
            } else if ($header == 'pais de origen') {
                $headerTitle = 'Country of origin';
            } else if ($header == 'descripcion') {
                $headerTitle = 'Description of Merchandise';
            } else if ($header == 'hallazgoz') {
                $headerTitle = 'Observations';
            } else if ($header == 'id partida') {
                $headerTitle = 'id part';
            } else if ($header == 'id serie') {
                $headerTitle = 'ID Serie';
            } else if ($header == 'no de parte') {
                $headerTitle = 'No. of part';
            }  else if ($header == 'modelo') {
                $headerTitle = 'Model';
            }  else if ($header == 'marca') {
                $headerTitle = 'Brand';
            } else if ($header == 'serie') {
                $headerTitle = 'Serie';
            } else if ($header == 'referencia') {
                $headerTitle = 'Reference';
            } else if ($header == 'observaciones bulto') {
                $headerTitle = 'Observations';
            } else if ($header == 'factura serie') {
                $headerTitle = 'Bill';
            } else if ($header == 'consecutivo serie') {
                $headerTitle = 'Consecutive serie';
            } else if ($header == 'total fotos') {
                $headerTitle = 'No. of photos received';
            } else if ($header == 'peso bruto') {
                $headerTitle = 'Gross Weight';
            } else if ($header == 'dependiente') {
                $headerTitle = 'Dependent';
            } else if ($header == 'incidencias') {
                $headerTitle = 'Incidents';
            } else if ($header == 'dimenciones') {
                $headerTitle = 'Dimensions (Length, Width, Heigth)';
            } else if ($header == 'hora inicio') {
                $headerTitle = 'Begining';
            } else if ($header == 'hora fin') {
                $headerTitle = 'Ending';
            } else if ($header == 'Guias') {
                $headerTitle = 'Guides';
            } else if ($header == 'Referencia') {
                $headerTitle = 'Reference';
            } else if ($header == 'Aduana') {
                $headerTitle = 'Aduana';
            } else if ($header == 'fecha solicitud') {
                $headerTitle = 'Application date';
            } else if ($header == 'recinto fiscalizado') {
                $headerTitle = 'Controlled enclosure';
            } else if ($header == 'fecha solicitud') {
                $headerTitle = 'Application date';
            } else if ($header == 'observaciones partida') {
                $headerTitle = 'Items Observations';
            } else if ($header == 'dependientes asignados') {
                $headerTitle = 'Dependents assigned';
            } else if ($header == 'partida') {
                $headerTitle = 'Item';
            } else if ($header == 'umc') {
                $headerTitle = 'UMC';
            } else if ($header == 'consecutivo partida') {
                $headerTitle = 'Consecutive part';
            } else if ($header == 'sub modelo lote') {
                $headerTitle = 'Sub Model / Lot';
            } else if ($header == 'parte') {
                $headerTitle = 'Part Number';
            } else if ($header == 'sub_mode') {
                $headerTitle = 'SubModel/Lot';
            }else if ($header == 'estatus_refe') {
                $headerTitle = 'Reference Status';
            }else if ($header == 'descripcion previo') {
                $headerTitle = 'Previous Type';
            }else if ($header == 'descripcion mercancia') {
                $headerTitle = 'Merchandise Type';
            }else if ($header == 'descripcion rev auto') {
                $headerTitle = 'Authority with Review';
            }else if ($header == 'fraccion') {
                $headerTitle = 'TARIFF FRACTION';
            } else if ($header == 'nico') {
                $headerTitle = 'NICO';
            } else if ($header == 'tipo peso') {
                $headerTitle = 'Weight type';
            }else if ($header == 'tipo previo') {
                $headerTitle = 'Type refe';
            } else if ($header == 'estatus partida') {
                $headerTitle = 'Status';
            } else if ($header == 'numero fotos esperadas') {
                $headerTitle = 'No. Total photos';
            } else if ($header == 'nom prov') {
                $headerTitle = 'Provider';
            }
            
        }
        return $headerTitle;
    }
    
    public function getGeneralData($reference, $idClient, $idLanguage){
        $db =  $this->getConnection();
        $db->setTable("'previo'.list");
        $db->setFields(array(
            "*",
        ));
        $where = "id_cliente = ? AND referencia = ?";
        $paramsArray = array($idClient, $reference);
        $variable = $this->getConnection();
        $db->setReturnType($variable ::TYPE_ARRAY_ALL);
        $generalDatas = $db->queryParametrize($where, $paramsArray);
        
        $htmlFirst = "";
        $htmlSecond = "";
        
        $html = array();
        if ($generalDatas) {
            
            if ($idLanguage == 1) {
                $htmlFirst .= "<P ALIGN=left style=\"background-color:#FFFFFF;\"><b>Datos Generales</b></P>";
            } else {
                $htmlFirst .= "<P ALIGN=left style=\"background-color:#FFFFFF;\"><b>General Data</b></P>";
            }
            
            $htmlFirst .= "<HR align=\"center\" size=\"2\" color=\"#008acb\" noshade>";
            $htmlFirst .= "<table  align = \"left\" border = \"0\" cellspacing = \"0\" cellpadding=\"0\">";
            
            if($idClient == 175) {
                $backgroundTable = '#dedfe8';
            } else if ($idClient == 2879) {
                $backgroundTable = '#f2dddd';
            } else if ($idClient == 3562) {
                $backgroundTable = '#dde1e6';
            } else {
                $backgroundTable = '#dfeaf2';
            }
            
            $htmlFirst .= "<tr>";
            
            foreach ($generalDatas[0] as $key => $value) {
                if ($key == 'aduana' || $key == 'bultos') {
                    $header = str_replace("_"," ", $key);
                    $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                    $htmlFirst .= "<td WIDTH = \"50%\" style=\"background-color:$backgroundTable;\">" . "<b>" . $headerTitle . "</b>" . "</td>";
                    $htmlFirst .= "<td WIDTH = \"50%\">"  . $value  ."</td>";
                }
            }
            
            $htmlFirst .= "</tr>";
            
            $htmlFirst .= "<tr>";
            
            foreach ($generalDatas[0] as $key => $value) {
                if ($key == 'guias' || $key == 'no_contenedor') {
                    $header = str_replace("_"," ", $key);
                    $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                    $htmlFirst .= "<td WIDTH = \"50%\" style=\"background-color:$backgroundTable;\">" . "<b>" . $headerTitle . "&nbsp;&nbsp;&nbsp;&nbsp;</b>" . "</td>";
                    $htmlFirst .= "<td WIDTH = \"50%\">"  . $value  ."</td>";
                }
            }
            
            $htmlFirst .= "</tr>";
            
            $htmlFirst .= "<tr>";
            
            foreach ($generalDatas[0] as $key => $value) {
                if ($key == 'orden_de_compra' || $key == 'referencia') {
                    $header = str_replace("_"," ", $key);
                    $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                    $htmlFirst .= "<td WIDTH = \"50%\" style=\"background-color:$backgroundTable;\">" . "<b>" . $headerTitle . "&nbsp;&nbsp;&nbsp;&nbsp;</b>" . "</td>";
                    $htmlFirst .= "<td WIDTH = \"50%\">"  . $value  ."</td>";
                }
            }
            
            $htmlFirst .= "</tr>";
            
            $htmlFirst .= "<tr>";
            
            foreach ($generalDatas[0] as $key => $value) {
                if ($key == 'recinto_fiscal' || $key == 'peso_bruto') {
                    $header = str_replace("_"," ", $key);
                    $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                    $htmlFirst .= "<td WIDTH = \"50%\" style=\"background-color:$backgroundTable;\">" . "<b>" . $headerTitle . "&nbsp;&nbsp;&nbsp;&nbsp;</b>" . "</td>";
                    $htmlFirst .= "<td WIDTH = \"50%\">" . $value  ."</td>";
                }
            }
            
            $htmlFirst .= "</tr>";
            
            $htmlFirst .= "<tr>";
            
            foreach ($generalDatas[0] as $key => $value) {
                if ($key == 'hora_inicio' || $key == 'hora_fin') {
                    $header = str_replace("_"," ", $key);
                    $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                    $htmlFirst .= "<td WIDTH = \"50%\" style=\"background-color:$backgroundTable;\">" . "<b>" . $headerTitle . "&nbsp;&nbsp;&nbsp;&nbsp;</b>" . "</td>";
                    $htmlFirst .= "<td WIDTH = \"50%\">"  . $value  ."</td>";
                }
            }
            
            $htmlFirst .= "</tr>";
            
            $htmlFirst .= "<tr>";
            
            foreach ($generalDatas[0] as $key => $value) {
                if ($key == 'dependiente' || $key == 'descripcion_previo') {
                    $header = str_replace("_"," ", $key);
                    $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                    $htmlFirst .= "<td WIDTH = \"50%\" style=\"background-color:$backgroundTable;\">" . "<b>" . $headerTitle . "&nbsp;&nbsp;&nbsp;&nbsp;</b>" . "</td>";
                    $htmlFirst .= "<td WIDTH = \"50%\">"  . $value ."</td>";
                }
            }
            
            $htmlFirst .= "</tr>";
            
            $htmlFirst .= "<tr>";
            
            foreach ($generalDatas[0] as $key => $value) {
                if ($key == 'descripcion_mercancia' || $key == 'descripcion_rev_auto') {
                    $header = str_replace("_"," ", $key);
                    $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                    $htmlFirst .= "<td WIDTH = \"50%\" style=\"background-color:$backgroundTable;\">" . "<b>" . $headerTitle . "&nbsp;&nbsp;&nbsp;&nbsp;</b>" . "</td>";
                    $htmlFirst .= "<td WIDTH = \"50%\">" . $value  ."</td>";
                }
            }
            
            $htmlFirst .= "</tr>";
            
            $htmlFirst .= "</table>";
            $html [0] = $htmlFirst;
            
            if ($idLanguage == 1) {
                $htmlSecond .= "<P ALIGN=left style=\"background-color:#FFFFFF;\"><b>Observaciones</b></P>";
            } else {
                $htmlSecond .= "<P ALIGN=left style=\"background-color:#FFFFFF;\"><b>Observations</b></P>";
            }
            
            $htmlSecond .= "<HR align=\"CENTER\" size=\"2\" color=\"#008acb\" noshade>";
            $htmlSecond .= "<table border = \"0\" cellspacing = \"2\">";
            
            foreach ($generalDatas[0] as $key => $value) {
                if ($key == 'observaciones') {
                    $htmlSecond .= "<tr><td class=\"obs\"><b>" .  $value . "</b></td>";
                    $htmlSecond .= "</tr>";
                }
            }
            
            $htmlSecond .= "</table>";
            $html [1] = $htmlSecond;
            
            return $html;
        } else {
            return NULL;
        }
        
    }
    
    //respaldo php
    public function getGeneralDataPHP($reference, $idClient, $idLanguage){
        $db =  $this->getConnection();
        $db->setTable("'previo'.list");
        $db->setFields(array(
            "*",
        ));
        $db->setParameters("id_cliente = $idClient AND referencia = '$reference'");
        $variable = $this->getConnection();
        $db->setReturnType($variable ::TYPE_ARRAY_ALL);
        $generalDatas = $db->query();
        
        $htmlFirst = "";
        $htmlSecond = "";
        
        $html = array();
        if ($generalDatas) {
            
            if ($idLanguage == 1) {
                $htmlFirst .= "<P ALIGN=left style=\"background-color:#FFFFFF;\"><b>Datos Generales</b></P>";
            } else {
                $htmlFirst .= "<P ALIGN=left style=\"background-color:#FFFFFF;\"><b>General Data</b></P>";
            }
            
            $htmlFirst .= "<HR align=\"top\" size=\"2\" color=\"008acb\" noshade>";
            $htmlFirst .= "<table  align = \"left\"  WIDTH = \"50%\" border = \"0\" cellspacing = \"0\" cellpadding=\"0\">";
            
            if($idClient == 175) {
                $backgroundTable = '#dedfe8';
            } else if ($idClient == 2879) {
                $backgroundTable = '#f2dddd';
            } else if ($idClient == 3562) {
                $backgroundTable = '#dde1e6';
            } else {
                $backgroundTable = '#dfeaf2';
            }
            
            $htmlFirst .= "<tr>";
            
            foreach ($generalDatas[0] as $key => $value) {
                if ($key == 'aduana' || $key == 'bultos') {
                    $header = str_replace("_"," ", $key);
                    $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                    $htmlFirst .= "<td WIDTH = \"50%\" style=\"background-color:$backgroundTable;\">" . "<font face = \"arial\" size = \"9\"  align = \"left\" margin=\"-2.5% 0\"><b>" . $headerTitle . "</b></font>" . "</td>";
                    $htmlFirst .= "<td WIDTH = \"50%\">" . "<font face = \"arial\" size = \"9\">" . $value . "</font>" ."</td>";
                }
            }
            
            $htmlFirst .= "</tr>";
            
            $htmlFirst .= "<tr>";
            
            foreach ($generalDatas[0] as $key => $value) {
                if ($key == 'guias' || $key == 'no_contenedor') {
                    $header = str_replace("_"," ", $key);
                    $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                    $htmlFirst .= "<td WIDTH = \"50%\" style=\"background-color:$backgroundTable;\">" . "<font face = \"arial\" size = \"9\"  align = \"left\" margin=\"-2.5% 0\"><b>" . $headerTitle . "&nbsp;&nbsp;&nbsp;&nbsp;</b></font>" . "</td>";
                    $htmlFirst .= "<td WIDTH = \"50%\">" . "<font face = \"arial\" size = \"9\">" . $value . "</font>" ."</td>";
                }
            }
            
            $htmlFirst .= "</tr>";
            
            $htmlFirst .= "<tr>";
            
            foreach ($generalDatas[0] as $key => $value) {
                if ($key == 'orden_de_compra' || $key == 'referencia') {
                    $header = str_replace("_"," ", $key);
                    $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                    $htmlFirst .= "<td WIDTH = \"50%\" style=\"background-color:$backgroundTable;\">" . "<font face = \"arial\" size = \"9\"  align = \"left\" margin=\"-2.5% 0\"><b>" . $headerTitle . "&nbsp;&nbsp;&nbsp;&nbsp;</b></font>" . "</td>";
                    $htmlFirst .= "<td WIDTH = \"50%\">" . "<font face = \"arial\" size = \"9\">" . $value . "</font>" ."</td>";
                }
            }
            
            $htmlFirst .= "</tr>";
            
            $htmlFirst .= "<tr>";
            
            foreach ($generalDatas[0] as $key => $value) {
                if ($key == 'recinto_fiscal' || $key == 'peso_bruto') {
                    $header = str_replace("_"," ", $key);
                    $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                    $htmlFirst .= "<td WIDTH = \"50%\" style=\"background-color:$backgroundTable;\">" . "<font face = \"arial\" size = \"9\"  align = \"left\" margin=\"-2.5% 0\"><b>" . $headerTitle . "&nbsp;&nbsp;&nbsp;&nbsp;</b></font>" . "</td>";
                    $htmlFirst .= "<td WIDTH = \"50%\">" . "<font face = \"arial\" size = \"9\">" . $value . "</font>" ."</td>";
                }
            }
            
            $htmlFirst .= "</tr>";
            
            $htmlFirst .= "<tr>";
            
            foreach ($generalDatas[0] as $key => $value) {
                if ($key == 'hora_inicio' || $key == 'hora_fin') {
                    $header = str_replace("_"," ", $key);
                    $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                    $htmlFirst .= "<td WIDTH = \"50%\" style=\"background-color:$backgroundTable;\">" . "<font face = \"arial\" size = \"9\"  align = \"left\" margin=\"-2.5% 0\"><b>" . $headerTitle . "&nbsp;&nbsp;&nbsp;&nbsp;</b></font>" . "</td>";
                    $htmlFirst .= "<td WIDTH = \"50%\">" . "<font face = \"arial\" size = \"9\">" . $value . "</font>" ."</td>";
                }
            }
            
            $htmlFirst .= "</tr>";
            
            $htmlFirst .= "<tr>";
            
            foreach ($generalDatas[0] as $key => $value) {
                if ($key == 'dependiente' || $key == 'descripcion_previo') {
                    $header = str_replace("_"," ", $key);
                    $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                    $htmlFirst .= "<td WIDTH = \"50%\" style=\"background-color:$backgroundTable;\">" . "<font face = \"arial\" size = \"9\"  align = \"left\" margin=\"-2.5% 0\"><b>" . $headerTitle . "&nbsp;&nbsp;&nbsp;&nbsp;</b></font>" . "</td>";
                    $htmlFirst .= "<td WIDTH = \"50%\">" . "<font face = \"arial\" size = \"9\">" . $value . "</font>" ."</td>";
                }
            }
            
            $htmlFirst .= "</tr>";
            
            $htmlFirst .= "<tr>";
            
            foreach ($generalDatas[0] as $key => $value) {
                if ($key == 'descripcion_mercancia' || $key == 'descripcion_rev_auto') {
                    $header = str_replace("_"," ", $key);
                    $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                    $htmlFirst .= "<td WIDTH = \"50%\" style=\"background-color:$backgroundTable;\">" . "<font face = \"arial\" size = \"9\"  align = \"left\" margin=\"-2.5% 0\"><b>" . $headerTitle . "&nbsp;&nbsp;&nbsp;&nbsp;</b></font>" . "</td>";
                    $htmlFirst .= "<td WIDTH = \"50%\">" . "<font face = \"arial\" size = \"9\">" . $value . "</font>" ."</td>";
                }
            }
            
            $htmlFirst .= "</tr>";
            
            $htmlFirst .= "</table>";
            $html [0] = $htmlFirst;
            
            if ($idLanguage == 1) {
                $htmlSecond .= "<P ALIGN=left style=\"background-color:#FFFFFF;\"><b>Observaciones</b></P>";
            } else {
                $htmlSecond .= "<P ALIGN=left style=\"background-color:#FFFFFF;\"><b>Observations</b></P>";
            }
            
            $htmlSecond .= "<HR align=\"CENTER\" size=\"2\" color=\"008acb\" noshade>";
            $htmlSecond .= "<table border = \"0\" cellspacing = \"2\">";
            
            foreach ($generalDatas[0] as $key => $value) {
                if ($key == 'observaciones') {
                    $htmlSecond .= "<tr><td><font face = \"arial\" size = \"9\" align = \"left\"><b>" .  $value . "</b></font></td>";
                    $htmlSecond .= "</tr>";
                }
            }
            
            $htmlSecond .= "</table>";
            $html [1] = $htmlSecond;
            
            return $html;
        } else {
            return NULL;
        }
        
    }
    
    //RESPALDO PHP
    public function getPackagePHP($reference, $idClient, $idLanguage){
        $db =  $this->getConnection();
        $db->setTable("'previo'.bulto");
        $db->setFields(array(
            "*",
        ));
        $db->setParameters("id_cliente = $idClient AND referencia = '$reference'");
        $variable = $this->getConnection();
        $db->setReturnType($variable ::TYPE_ARRAY_ALL);
        $generalDatas = $db->query();
        $general = array();
        $html = "";
        if ($generalDatas) {
            if ($idLanguage == 1) {
                $html .= "<P ALIGN=left style=\"background-color:#FFFFFF;\"><b>Bultos</b></P>";
            } else {
                $html .= "<P ALIGN=left style=\"background-color:#FFFFFF;\"><b>Packages</b></P>";
            }
            
            $html .= "<HR align=\"CENTER\" size=\"2\" color=\"008acb\" noshade>";
            $html .= "<table  align = \"center\"  WIDTH = \"100%\" border = \"0\" cellspacing = \"0\" cellpadding=\"0\">";
            
            if($idClient == 175) {
                $backgroundTable = '#dedfe8';
            } else if ($idClient == 2879) {
                $backgroundTable = '#f2dddd';
            } else if ($idClient == 3562) {
                $backgroundTable = '#dde1e6';
            } else {
                $backgroundTable = '#dfeaf2';
            }
            
            $html .= "<tr>";
            
            foreach ($generalDatas[0] as $key => $value) {
                if ($key == 'id_bulto' || $key == 'id_cliente' || $key == 'referencia') {
                } else {
                    $header = str_replace("_"," ", $key);
                    $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                    $html .= "<td WIDTH = \"20%\" style=\"background-color:$backgroundTable;\">" . "<font face = \"arial\" size = \"9\" align = \"center\" margin=\"-2.5% 0\"><b>" . $headerTitle . "</b></font>" . "</td>";
                }
            }
            $html .= "</tr>";
            
            foreach ($generalDatas as $general) {
                $html .= "<tr>";
                foreach ($general as $key => $value) {
                    if ($key == 'id_bulto' || $key == 'id_cliente' || $key == 'referencia') {
                    } else {
                        $html .= "<td><font face = \"arial\" size = \"9\" align = \"center\"><b>" .  $value . "</b></font></td>";
                    }
                }
                $html .= "</tr>";
            }
            
            $html .= "</table>";
            
            return $html;
        } else {
            return NULL;
        }
    }
    
    public function getPackage($reference, $idClient, $idLanguage){
        $db =  $this->getConnection();
        $db->setTable("'previo'.bulto");
        $db->setFields(array(
            "*",
        ));
        $where = "id_cliente = ? AND referencia = ?";
        $paramsArray = array($idClient, $reference);
        $variable = $this->getConnection();
        $db->setReturnType($variable ::TYPE_ARRAY_ALL);
        $generalDatas = $db->queryParametrize($where, $paramsArray);
        
        $general = array();
        $html = "";
        if ($generalDatas) {
            if ($idLanguage == 1) {
                $html .= "<P ALIGN=left style=\"background-color:#FFFFFF;\"><b>Bultos</b></P>";
            } else {
                $html .= "<P ALIGN=left style=\"background-color:#FFFFFF;\"><b>Packages</b></P>";
            }
            
            $html .= "<HR align=\"CENTER\" size=\"2\" color=\"#008acb\" noshade>";
            $html .= "<table  align = \"center\"  WIDTH = \"100%\" border = \"0\" cellspacing = \"0\" cellpadding=\"0\">";
            
            if($idClient == 175) {
                $backgroundTable = '#dedfe8';
            } else if ($idClient == 2879) {
                $backgroundTable = '#f2dddd';
            } else if ($idClient == 3562) {
                $backgroundTable = '#dde1e6';
            } else {
                $backgroundTable = '#dfeaf2';
            }
            
            $html .= "<tr>";
            
            foreach ($generalDatas[0] as $key => $value) {
                if ($key == 'id_bulto' || $key == 'id_cliente' || $key == 'referencia') {
                } else {
                    $header = str_replace("_"," ", $key);
                    $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                    $html .= "<td WIDTH = \"20%\" style=\"background-color:$backgroundTable;\">" . "<b>" . $headerTitle . "</b>" . "</td>";
                }
            }
            $html .= "</tr>";
            
            foreach ($generalDatas as $general) {
                $html .= "<tr>";
                foreach ($general as $key => $value) {
                    if ($key == 'id_bulto' || $key == 'id_cliente' || $key == 'referencia') {
                    } else {
                        $html .= "<td><b>" .  $value . "</b></td>";
                    }
                }
                $html .= "</tr>";
            }
            
            $html .= "</table>";
            
            return $html;
        } else {
            return NULL;
        }
    }
    
    //RESPALDO PHP
    public function getMerchandisePHP($reference, $idClient, $idLanguage){
        $db =  $this->getConnection();
        $db->setTable("'previo'.merchandise m");
        $db->setFields(array(
            "*",
        ));
        $db->setParameters("id_cliente = $idClient AND referencia = '$reference'");
        $variable = $this->getConnection();
        $db->setReturnType($variable ::TYPE_ARRAY_ALL);
        $generalDatas = $db->query();
        $general = array();
        $html = "";
        if ($generalDatas) {
            if ($idLanguage == 1) {
                $html .= "<P ALIGN=left style=\"background-color:#FFFFFF;\"><b>Mercancías</b></P>";
            } else {
                $html .= "<P ALIGN=left style=\"background-color:#FFFFFF;\"><b>Merchandise</b></P>";
            }
            
            
            
            if($idClient == 175) {
                $backgroundTable = '#dedfe8';
            } else if ($idClient == 2879) {
                $backgroundTable = '#f2dddd';
            } else if ($idClient == 3562) {
                $backgroundTable = '#dde1e6';
            } else {
                $backgroundTable = '#dfeaf2';
            }
            
            foreach ($generalDatas as $generalData) {
                $html .= "<HR align=\"CENTER\" size=\"2\" color=\"008acb\" noshade>";
                $html .= "<table  align = \"center\"  WIDTH = \"100%\" border = \"0\" cellspacing = \"0\" cellpadding=\"0\">";
                
                $html .= "<tr>";
                
                foreach ($generalData as $key => $value) {
                    
                    if ($key == 'factura' || $key == 'consecutivo' || $key == 'numero_de_parte' || $key == 'cantidad') {
                        $header = str_replace("_"," ", $key);
                        $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                        $html .= "<td WIDTH = \"12.5%\" style=\"background-color:$backgroundTable;\">" . "<font face = \"arial\" size = \"9\"  align = \"center\" margin=\"-2.5% 0\"><b>" . $headerTitle . "</b></font>" . "</td>";
                        $html .= "<td WIDTH = \"12.5%\">" . "<font face = \"arial\" size = \"9\">" . $value . "</font>" ."</td>";
                    }
                }
                
                $html .= "</tr>";
                
                $html .= "<tr>";
                
                foreach ($generalData as $key => $value) {
                    if ($key == 'conteo' || $key == 'unidad' || $key == 'peso' || $key == 'pais_de_origen') {
                        $header = str_replace("_"," ", $key);
                        $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                        if($headerTitle == "Peso"){
                            $headerTitle = "Peso ";
                        }
                        
                        $html .= "<td WIDTH = \"12.5%\" style=\"background-color:$backgroundTable;\">" . "<font face = \"arial\" size = \"9\"  align = \"center\" margin=\"-2.5% 0\"><b>" . $headerTitle . "</b></font>" . "</td>";
                        $html .= "<td WIDTH = \"12.5%\">" . "<font face = \"arial\" size = \"9\">" . $value . "</font>" ."</td>";
                    }
                }
                
                $html .= "</tr>";
                
                $html .= "<tr>";
                
                foreach ($generalData as $key => $value) {
                    if ($key == 'dependiente') {
                        $header = str_replace("_"," ", $key);
                        $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                        $html .= "<td WIDTH = \"12.5%\" style=\"background-color:$backgroundTable;\">" . "<font face = \"arial\" size = \"9\"  align = \"center\" margin=\"-2.5% 0\"><b>" . $headerTitle . "</b></font>" . "</td>";
                        $html .= "<td WIDTH = \"12.5%\">" . "<font face = \"arial\" size = \"9\">" . $value . "</font>" ."</td>";
                    } else if($key == 'incidencias'){
                        $header = str_replace("_"," ", $key);
                        $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                        $html .= "<td WIDTH = \"12.5%\" style=\"background-color:$backgroundTable;\">" . "<font face = \"arial\" size = \"9\"  align = \"center\" margin=\"-2.5% 0\"><b>" . $headerTitle . "</b></font>" . "</td>";
                        $html .= "<td WIDTH = \"50%\">" . "<font face = \"arial\" size = \"9\">" . $value . "</font>" ."</td>";
                    }
                }
                
                
                
                $html .= "</tr>";
                
                if($idClient == 3558){
                    
                    $html .= "<tr>";
                    
                    foreach ($generalData as $key => $value) {
                        
                        if ($key == 'fraccion' || $key == 'nico') {
                            $header = str_replace("_"," ", $key);
                            $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                            $html .= "<td WIDTH = \"12.5%\" style=\"background-color:$backgroundTable;\">" . "<font face = \"arial\" size = \"9\"  align = \"center\" margin=\"-2.5% 0\"><b>" . $headerTitle . "</b></font>" . "</td>";
                            $html .= "<td WIDTH = \"12.5%\">" . "<font face = \"arial\" size = \"9\">" . $value . "</font>" ."</td>";
                        } else if ($key == 'nom_prov' ) {
                            $header = str_replace("_"," ", $key);
                            $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                            $html .= "<td WIDTH = \"12.5%\" style=\"background-color:$backgroundTable;\">" . "<font face = \"arial\" size = \"9\"  align = \"center\" margin=\"-2.5% 0\"><b>" . $headerTitle . "</b></font>" . "</td>";
                            $html .= "<td WIDTH = \"40%\">" . "<font face = \"arial\" size = \"9\">" . $value . "</font>" ."</td>";
                        }
                        
                    }
                    
                    $html .= "</tr>";
                }
                
                foreach ($generalData as $key => $value) {
                    if ($key == 'descripcion') {
                        if ($value != null) {
                            $html .= "<tr>";
                            $header = str_replace("_"," ", $key);
                            $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                            $html .= "<td WIDTH = \"100%\" style=\"background-color:$backgroundTable;\">" . "<font face = \"arial\" size = \"9\"  align = \"left\" margin=\"-2.5% 0\"><b>&nbsp;&nbsp;&nbsp;" . $headerTitle . "</b></font>" . "</td>";
                            $html .= "</tr>";
                            $html .= "<tr>";
                            $html .= "<td WIDTH = \"100%\">" . "<font face = \"arial\"  align = \"left\" size = \"9\">&nbsp;&nbsp;&nbsp;" . $value . "</font>" ."</td>";
                            $html .= "</tr>";
                        }
                    } else if($key == 'hallazgoz'){
                        $html .= "<tr>";
                        $header = str_replace("_"," ", $key);
                        $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                        $html .= "<td WIDTH = \"100%\" style=\"background-color:$backgroundTable;\">" . "<font face = \"arial\" size = \"9\"  align = \"left\" margin=\"-2.5% 0\"><b>&nbsp;&nbsp;&nbsp;" . $headerTitle . "</b></font>" . "</td>";
                        $html .= "</tr>";
                        $html .= "<tr>";
                        $html .= "<td WIDTH = \"100%\">" . "<font face = \"arial\"  align = \"left\" size = \"9\">&nbsp;&nbsp;&nbsp;" . $value . "</font>" ."</td>";
                        $html .= "</tr>";
                    }
                }
                
                
                
                if($generalData["id_partida"] != NULL){
                    
                    $db->setTable("'previo'.serie");
                    $db->setFields(array(
                        "*",
                    ));
                    $db->setParameters("id_partida = " . $generalData["id_partida"]);
                    $variable = $this->getConnection();
                    $db->setReturnType($variable ::TYPE_ARRAY_ALL);
                    $series = $db->query();
                    
                    if (count($series) > 0) {
                        foreach ($series as $serie) {
                            $html .= "<tr>";
                            foreach ($serie as $key => $value) {
                                if ($key == 'serie' || $key == 'no_de_parte' || $key == 'marca' || $key == 'modelo' || $key == 'sub_modelo_lote') {
                                    $header = str_replace("_"," ", $key);
                                    $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                                    $html .= "<td WIDTH = \"12.5%\" style=\"background-color:$backgroundTable;\">" . "<font face = \"arial\" size = \"9\"  align = \"center\" margin=\"-2.5% 0\"><b>" . $headerTitle . "</b></font>" . "</td>";
                                    $html .= "<td WIDTH = \"12.5%\">" . "<font face = \"arial\" size = \"9\">" . $value . "</font>" ."</td>";
                                }
                            }
                            $html .= "</tr>";
                        }
                        
                    }
                }
                
                $html .= "</table><br/>";
            }
            
            return $html;
        } else {
            return NULL;
        }
    }
    
    public function getMerchandise($reference, $idClient, $idLanguage){
        $db =  $this->getConnection();
        $db->setTable("'previo'.merchandise m");
        $db->setFields(array(
            "*",
        ));
        $where = "id_cliente = ? AND referencia = ?";
        $paramsArray = array($idClient, $reference);
        $variable = $this->getConnection();
        $db->setReturnType($variable ::TYPE_ARRAY_ALL);
        $generalDatas = $db->queryParametrize($where, $paramsArray);
        
        $general = array();
        $html = "";
        if ($generalDatas) {
            if ($idLanguage == 1) {
                $html .= "<P ALIGN=left style=\"background-color:#FFFFFF;\"><b>Mercancías</b></P>";
            } else {
                $html .= "<P ALIGN=left style=\"background-color:#FFFFFF;\"><b>Merchandise</b></P>";
            }
            
            if($idClient == 175) {
                $backgroundTable = '#dedfe8';
            } else if ($idClient == 2879) {
                $backgroundTable = '#f2dddd';
            } else if ($idClient == 3562) {
                $backgroundTable = '#dde1e6';
            } else {
                $backgroundTable = '#dfeaf2';
            }
            
            foreach ($generalDatas as $generalData) {
                $html .= "<HR align=\"CENTER\" size=\"2\" color=\"#008acb\" noshade>";
                $html .= "<table  align = \"center\"  WIDTH = \"100%\" border = \"0\" cellspacing = \"0\" cellpadding=\"0\">";
                
                $html .= "<tr>";
                
                foreach ($generalData as $key => $value) {
                    //var_dump($key);
                    
                    if ($key == 'factura' || $key == 'consecutivo' || $key == 'numero_de_parte' || $key == 'cantidad') {
                        $header = str_replace("_"," ", $key);
                        $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                        $html .= "<td WIDTH = \"12.5%\" style=\"background-color:$backgroundTable;\">" . "<b>" . $headerTitle . "</b>" . "</td>";
                        $html .= "<td WIDTH = \"12.5%\">"  . $value  ."</td>";
                    }
                }
                //xit;
                $html .= "</tr>";
                
                $html .= "<tr>";
                
                foreach ($generalData as $key => $value) {
                    if ($key == 'conteo' || $key == 'unidad' || $key == 'peso' || $key == 'pais_de_origen') {
                        $header = str_replace("_"," ", $key);
                        $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                        
                        if($headerTitle == "Peso"){
                            $headerTitle = "Peso " . $generalData["tipo_peso"];
                        }
                        
                        $html .= "<td WIDTH = \"12.5%\" style=\"background-color:$backgroundTable;\">" . "<b>" . $headerTitle . "</b>" . "</td>";
                        $html .= "<td WIDTH = \"12.5%\">" . $value ."</td>";
                    }
                }
                
                $html .= "</tr>";
                
                $html .= "<tr>";
                
                foreach ($generalData as $key => $value) {
                    if ($key == 'dependiente') {
                        $header = str_replace("_"," ", $key);
                        $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                        $html .= "<td WIDTH = \"12.5%\" style=\"background-color:$backgroundTable;\">" . "<b>" . $headerTitle . "</b>" . "</td>";
                        $html .= "<td WIDTH = \"12.5%\">"  . $value  ."</td>";
                    } else if($key == 'incidencias'){
                        $header = str_replace("_"," ", $key);
                        $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                        $html .= "<td WIDTH = \"12.5%\" style=\"background-color:$backgroundTable;\">" . "<b>" . $headerTitle . "</b>" . "</td>";
                        $html .= "<td WIDTH = \"50%\">" . $value  ."</td>";
                    }
                }
                
                
                
                $html .= "</tr>";
                
                
                
                if($idClient == 3558){
                    $html .= "</table>";
                    
                    $html .= "<table  align = \"left\"  WIDTH = \"100%\" border = \"0\" cellspacing = \"0\" cellpadding=\"0\">";
                    
                    $html .= "<tr>";
                    
                    foreach ($generalData as $key => $value) {
                        
                        if ($key == 'fraccion' || $key == 'nico') {
                            $header = str_replace("_"," ", $key);
                            $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                            $html .= "<td WIDTH = \"12.5%\" style=\"background-color:$backgroundTable;\">" . "<b>" . $headerTitle . "</b>" . "</td>";
                            $html .= "<td WIDTH = \"12.5%\">" . $value  ."</td>";
                        } else if ($key == 'nom_prov' ) {
                            $header = str_replace("_"," ", $key);
                            $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                            $html .= "<td WIDTH = \"12.5%\" style=\"background-color:$backgroundTable;\">" . "<b>" . $headerTitle . "</b>" . "</td>";
                            $html .= "<td WIDTH = \"60%\">" . $value  ."</td>";
                        }
                        
                    }
                    
                    $html .= "</tr>";
                }
                
                
                $html .= "</table>";
                
                $html .= "<table  align = \"left\"  WIDTH = \"100%\" border = \"0\" cellspacing = \"0\" cellpadding=\"0\">";
                
                foreach ($generalData as $key => $value) {
                    if ($key == 'descripcion') {
                        if ($value != null) {
                            $html .= "<tr>";
                            $header = str_replace("_"," ", $key);
                            $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                            $html .= "<td class=\"obs\" style=\"background-color:$backgroundTable;\">" . "<b>" .  $headerTitle . "</b>" . "</td>";
                            $html .= "</tr>";
                            $html .= "<tr>";
                            $html .= "<td class=\"obs\">" . $value ."</td>";
                            $html .= "</tr>";
                        }
                    } else if($key == 'hallazgoz'){
                        $html .= "<tr>";
                        $header = str_replace("_"," ", $key);
                        $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                        $html .= "<td class=\"obs\" style=\"background-color:$backgroundTable;\">" . "<b>" .$headerTitle . "</b>" . "</td>";
                        $html .= "</tr>";
                        $html .= "<tr>";
                        $html .= "<td class=\"obs\">" .$value ."</td>";
                        $html .= "</tr>";
                    }
                }
                
                
                
                
                if($generalData["id_partida"] != NULL){
                    
                    $db->setTable("'previo'.serie");
                    $db->setFields(array(
                        "*",
                    ));
                    $db->setParameters("id_partida = " . $generalData["id_partida"]);
                    $variable = $this->getConnection();
                    $db->setReturnType($variable ::TYPE_ARRAY_ALL);
                    $series = $db->query();
                    
                    
                    
                    if (count($series) > 0) {
                        
                        $html .= "</table>";
                        $html .= "<table  align = \"center\"  WIDTH = \"100%\" border = \"0\" cellspacing = \"0\" cellpadding=\"0\">";
                        
                        foreach ($series as $serie) {
                            $html .= "<tr>";
                            foreach ($serie as $key => $value) {
                                if ($key == 'serie' || $key == 'no_de_parte' || $key == 'marca' || $key == 'modelo' || $key == 'sub_modelo_lote') {
                                    $header = str_replace("_"," ", $key);
                                    $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                                    $html .= "<td WIDTH = \"12.5%\" style=\"background-color:$backgroundTable;\">" . "<b>" . $headerTitle . "</b>" . "</td>";
                                    $html .= "<td WIDTH = \"12.5%\">"  . $value  ."</td>";
                                }
                            }
                            $html .= "</tr>";
                        }
                        
                    }
                }
                
                $html .= "</table><br/>";
            }
            
            return $html;
        } else {
            return NULL;
        }
    }
    
    public function getSerie($reference, $idClient, $idLanguage){
        $db =  $this->getConnection();
        $db->setTable("'previo'.merchandise");
        $db->setFields(array(
            "id_partida"
        ));
        $db->setParameters("id_cliente = $idClient AND referencia = '$reference'");
        $variable = $this->getConnection();
        $db->setReturnType($variable ::TYPE_ARRAY_ALL);
        $headings = $db->query();
        
        
        if ($headings) {
            $idHeading = "";
            foreach ($headings as $heading) {
                $idHeading .= $heading["id_partida"].',';
            }
            
            $idHeading = substr($idHeading, 0, -1);
            
            $db->setTable("'previo'.serie");
            $db->setFields(array(
                "*",
            ));
            $db->setParameters("id_partida IN($idHeading)");
            $variable = $this->getConnection();
            $db->setReturnType($variable ::TYPE_ARRAY_ALL);
            $generalDatas = $db->query();
            
            
            $general = array();
            $html = "";
            if ($generalDatas) {
                if ($idLanguage == 1) {
                    $html .= "<P ALIGN=left style=\"background-color:#FFFFFF;\"><b>Series</b></P>";
                } else {
                    $html .= "<P ALIGN=left style=\"background-color:#FFFFFF;\"><b>Series</b></P>";
                }
                
                $html .= "<HR align=\"CENTER\" size=\"2\" color=\"#008acb\" noshade>";
                $html .= "<table  align = \"left\"  WIDTH = \"83%\" border = \"0\" cellspacing = \"0\" cellpadding=\"0\">";
                
                if($idClient == 175) {
                    $backgroundTable = '#dedfe8';
                } else if ($idClient == 2879) {
                    $backgroundTable = '#f2dddd';
                } else if ($idClient == 3562) {
                    $backgroundTable = '#dde1e6';
                } else {
                    $backgroundTable = '#dfeaf2';
                }
                
                $html .= "<tr>";
                
                foreach ($generalDatas[0] as $key => $value) {
                    if ($key == 'id_serie' || $key == 'id_partida' || $key == 'id_cliente') {
                    } else {
                        $header = str_replace("_"," ", $key);
                        $headerTitle = $this->getHeaderTitle($header, $idLanguage);
                        $html .= "<td WIDTH = \"20%\" style=\"background-color:$backgroundTable;\">" . "<b>" . $headerTitle . "&nbsp;&nbsp;&nbsp;&nbsp;</b>" . "</td>";
                    }
                }
                $html .= "</tr>";
                
                foreach ($generalDatas as $general) {
                    $html .= "<tr>";
                    foreach ($general as $key => $value) {
                        if ($key == 'id_serie' || $key == 'id_partida' || $key == 'id_cliente') {
                        } else {
                            $html .= "<td><b>" .  $value . "</b></td>";
                        }
                    }
                    $html .= "</tr>";
                }
                
                $html .= "</table>";
                
                return $html;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }
    
 
    public function getFirms($reference, $idClient, $custom, $idLanguage){
        $db =  $this->getConnection();
        
        $db->setTable("'previo'.cprevo_refe r");
        $db->setJoins("INNER JOIN 'previo'.cprevo_previos p ON r.id_prev = p.id_prev");
        $db->setJoin("INNER JOIN 'general'.casac_importadores i ON p.id_importador = i.id_importador");
        $db->setFields(array(
            "p.dep_asigna"
        ));
        $where = "num_refe = ?";
        $paramsArray = array($reference);
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $depen = $db->queryParametrize($where, $paramsArray);
        
        $nomDepen = $depen[0]["dep_asigna"];
        $names     = explode(",", $nomDepen);
        $nameFirst = $names[0];
        
        
        $html = '<table>
            
                    <tr align="center">
                      <td align="center"> <p style = "font-size: 9px;"><b> FIRMA AUTÓGRAFA </b></p>_________________________<p style = "font-size: 9px;"><b> EJECUTIVO DE CUENTA </b></p> </td>
                      <td align="center"> <p style = "font-size: 9px;"><b> IMAGEN FIRMA </b></p>_________________________<br>'. $nameFirst .'<p style = "font-size: 9px;"><b> DEPENDIENTE QUE REALIZA EL PREVIO </b></p> </td>
                      <td align="center"> <p style = "font-size: 9px;"><b> FIRMA AUTÓGRAFA </b></p>_________________________<p style = "font-size: 9px;"><b> GLOSADOR </b></p> </td>
                    </tr>
                          
                </table> ';
        
        return $html;
    }
    
 
    
    public function getPhotos($reference, $idClient, $custom, $idLanguage, $urlfiles){
        if ($_SERVER['HTTP_HOST'] == "localhost") {
            $accessurl = $_SERVER["REQUEST_SCHEME"]. "://".$_SERVER['HTTP_HOST'];
        } else {
            $accessurl = $_SERVER["REQUEST_SCHEME"]. "s://".$_SERVER['HTTP_HOST'];
        }
        
        $accessurl = "http://localhost/";
        
        $db =  $this->getConnection();
        
        $db->setTable("'previo'.cprevo_refe");
        $db->setFields(array(
            "id_prev",
        ));
        $where = "num_refe = ?";
        $paramsArray = array($reference);
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $previo = $db->queryParametrize($where, $paramsArray);
        $idPrevio = $previo[0]["id_prev"];
        
        if($idPrevio != NULL ){
            $db->setTable("'previo'.cprevo_fotop");
            $db->setFields(array(
                "nom_foto",
                "url_foto",
                "cons_foto"
            ));
            $where = "id_prev = ? ORDER BY cons_foto";
            $paramsArray = array($idPrevio);
            $variable = $this->getConnection();
            $db->setReturnType($variable ::TYPE_ARRAY_ALL);
            $previousPhoto = $db->queryParametrize($where, $paramsArray);
            
            $countPrevious = count($previousPhoto);
            $countPre = $countPrevious+1;
            $html = "";
            
            if ($countPrevious > 0) {
                if ($idLanguage == 1) {
                    $html .= "<P ALIGN=left style=\"background-color:#FFFFFF;\"><b>Fotos Bultos</b></P>";
                } else {
                    $html .= "<P ALIGN=left style=\"background-color:#FFFFFF;\"><b>Packages Photos</b></P>";
                }
                
                $html .= "<HR align=\"CENTER\" size=\"2\" color=\"#008acb\" noshade>";
                $html .= "<br>";
                $html .= "<table  align = \"left\"  WIDTH = \"100%\" border = \"0\" cellspacing = \"0\" cellpadding=\"0\">";
                
                $html .= "<tr>";
                $html .= "<td>&nbsp;</td>";
                $html .= "<td>&nbsp;</td>";
                $html .= "</tr>";
                $countP = 1;
                $html .= "<tr>";
                foreach ($previousPhoto as $value) {
                    if($value["url_foto"] == null){
                        $reference = str_replace("/", "DIAGONAL", $reference);
                        $name = explode('.', $value["nom_foto"]);
                        $name = str_replace("_", " ", $name[0]);
                        $html .= "<tr>";
                        $html .= "<td WIDTH = \"50%\">"  . $name .'<br>' . '<img src="'. $accessurl ."/" .$urlfiles. '/respaldo/' .$idClient .'/'. $custom . '/' . $reference . '/' . $value["nom_foto"] . '" height="150" width="250">' . "</td>";
                        $html .= "</tr>";
                        
                    } else {
                        $reference = str_replace("/", "DIAGONAL", $reference);
                        $name = explode('.', $value["nom_foto"]);
                        $url_foto = str_replace(' ', "%20",$value["url_foto"]);
                        
                        $name = str_replace("_", " ", $name[0]);
                        
                        
                        if($countPrevious!=$countP){
                            
                            
                            if($countP % 2 != 0){
                                $html .= "<td align = \"center\" WIDTH = \"50%\">" . $name .'<br>' . '<center><img src="' . $accessurl ."/" .$url_foto. '" height="150" width="250"></center>' . "</td>";
                                
                            }else{
                                $html .= "<td align = \"center\" WIDTH = \"50%\">" . $name .'<br>' . '<center><img src="' . $accessurl ."/" .$url_foto. '" height="150" width="250"></center>' . "</td>";
                                $html .= "</tr>";
                                $html .= "<tr>";
                                $html .= "<td>&nbsp;</td>";
                                $html .= "<td>&nbsp;</td>";
                                $html .= "</tr>";
                                
                                $html .= "<tr>";
                                
                            }
                        }else{
                            if($countP % 2 != 0){
                                $html .= "<td align = \"center\" WIDTH = \"50%\">" . $name .'<br>' . '<center><img src="' . $accessurl ."/" .$url_foto. '" height="150" width="250"></center>' . "</td>";
                                $html .= "<td align = \"center\" WIDTH = \"50%\">&nbsp;</td>";
                                $html .= "</tr>";
                            }else{
                                $html .= "<td align = \"center\" WIDTH = \"50%\">" . $name .'<br>' . '<center><img src="' . $accessurl ."/" .$url_foto. '" height="150" width="250"></center>' . "</td>";
                                $html .= "</tr>";
                            }
                        }
                        
                    }
                    
                    $countP++;
                }
                
                $html .= "</table>";
            }
        }
        return $html;
    }
    
    
    //respaldo php
    public function getPhotosPHP($reference, $idClient, $custom, $idLanguage, $urlfiles){
        $db =  $this->getConnection();
        
        $db->setTable("'previo'.cprevo_refe");
        $db->setFields(array(
            "id_prev",
        ));
        $db->setParameters("num_refe = '$reference'");
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $previo = $db->query();
        
        $idPrevio = $previo[0]["id_prev"];
        
        if($idPrevio != NULL ){
            $db->setTable("'previo'.cprevo_fotop");
            $db->setFields(array(
                "nom_foto",
                "url_foto",
                "cons_foto"
            ));
            $db->setParameters("id_prev = '$idPrevio' ORDER BY cons_foto");
            $variable = $this->getConnection();
            $db->setReturnType($variable ::TYPE_ARRAY_ALL);
            $previousPhoto = $db->query();
            
            $countPrevious = count($previousPhoto);
            
            $html = "";
            
            if ($countPrevious > 0) {
                if ($idLanguage == 1) {
                    $html .= "<P ALIGN=left style=\"background-color:#FFFFFF;\"><b>Fotos Bultos</b></P>";
                } else {
                    $html .= "<P ALIGN=left style=\"background-color:#FFFFFF;\"><b>Packages Photos</b></P>";
                }
                
                $html .= "<HR align=\"CENTER\" size=\"2\" color=\"008acb\" noshade>";
                $html .= "<br>";
                $html .= "<br>";
                $html .= "<br>";
                $html .= "<table WIDTH = \"100%\" border = \"0\">";
                
                $html .= "<tr>";
                $html .= "<td>&nbsp;</td>";
                $html .= "<td>&nbsp;</td>";
                $html .= "</tr>";
                
                foreach ($previousPhoto as $value) {
                    if($value["url_foto"] == null){
                        $reference = str_replace("/", "DIAGONAL", $reference);
                        $name = explode('.', $value["nom_foto"]);
                        $name = str_replace("_", " ", $name[0]);
                        $html .= "<tr>";
                        $html .= "<td>&nbsp;</td>";
                        $html .= "<td WIDTH = \"50%\" rowspan=\"2\">" . '<img src="' .$urlfiles. $idClient .'/'. $custom . '/' . $reference . '/' . $value["nom_foto"] . '" height="150" width="250">' . "</td>";
                        $html .= "</tr>";
                        $html .= "<tr>";
                        $html .= "<td WIDTH = \"50%\">" . "<font face = \"arial\" size = \"9\">" . $name . "</font>" ."</td>";
                        $html .= "<td>&nbsp;</td>";
                        $html .= "</tr>";
                    } else {
                        $reference = str_replace("/", "DIAGONAL", $reference);
                        $name = explode('.', $value["nom_foto"]);
                        $name = str_replace("_", " ", $name[0]);
                        
                        
                        
                        $html .= "<tr>";
                        $html .= "<td>&nbsp;</td>";
                        $html .= "<td WIDTH = \"50%\" rowspan=\"2\">" . '<img src="' .'../'.$value["url_foto"]. '" height="150" width="250">' . "</td>";
                        $html .= "</tr>";
                        $html .= "<tr>";
                        $html .= "<td WIDTH = \"50%\">" . "<font face = \"arial\" size = \"9\">" . $name . "</font>" ."</td>";
                        $html .= "<td>&nbsp;</td>";
                        $html .= "</tr>";
                    }
                }
                $html .= "</table>";
            }
        }
        return $html;
    }
    
    
    public function getPhotosMerchandise($reference, $idClient, $custom, $idLanguage, $urlfiles){
        $db =  $this->getConnection();
        
        if ($_SERVER['HTTP_HOST'] == "localhost") {
            $accessurl = $_SERVER["REQUEST_SCHEME"]. "://".$_SERVER['HTTP_HOST'];
        } else {
            $accessurl = $_SERVER["REQUEST_SCHEME"]. "s://".$_SERVER['HTTP_HOST'];
            $accessurl = "http://localhost/";
        }
        
        $accessurl = "http://localhost/";
        
        $db->setTable("'previo'.cprevo_refe");
        $db->setFields(array(
            "id_prev",
        ));
        $db->setParameters("num_refe = '$reference'");
        $variable = $this->getConnection();
        $db->setReturnType($variable ::TYPE_ARRAY_ALL);
        $previo = $db->query();
        
        $idPrevio = $previo[0]["id_prev"];
        
        $db->setTable("'previo'.cprevo_refe T1");
        $db->setJoins("INNER JOIN 'previo'.cprevo_factur T2 ON T1.id_prev = T2.id_prev");
        $db->setJoin("INNER JOIN 'previo'.cprevo_facpar T3 ON T2.id_factur = T3.id_factur");
        $db->setJoin("INNER JOIN 'previo'.cprevo_fotos T4 ON T3.id_partida = T4.id_partida");
        $db->setJoin("INNER JOIN previo.cprevo_previos T5 ON T1.id_prev = T5.id_prev");
        $db->setJoin("INNER JOIN 'general'.casag_licencias L ON T1.id_licencia = L.id_licencia");
        $db->setFields(array(
            "T4.nom_foto",
            "T2.num_fact",
            "T3.cons_part",
            "T4.url_foto",
            "L.patente",
            "T5.flag_version",
            "T2.cons_fact",
            "T3.cons_part",
            "T4.cons_foto",
            "T3.id_partida"
        ));
        $where = "T1.num_refe = ? ORDER BY T2.cons_fact, T3.cons_part, T4.cons_foto";
        $paramsArray = array($reference);
        $variable = $this->getConnection();
        $db->setReturnType($variable ::TYPE_ARRAY_ALL);
        $headingPhoto = $db->queryParametrize($where, $paramsArray);
        
        $countHeading = count($headingPhoto);
        
        $html2 = "";
        
        if ($countHeading > 0) {
            $patente = $headingPhoto[0]["patente"];
            $flag_version = $headingPhoto[0]["flag_version"];
            
            if ($idLanguage == 1) {
                $html2 .= "<P ALIGN=left style=\"background-color:#FFFFFF;\"><b>Fotos Mercanc&iacute;a</b></P>";
            } else {
                $html2 .= "<P ALIGN=left style=\"background-color:#FFFFFF;\"><b>Merchandise Photos</b></P>";
            }
            
            $html2 .= "<HR align=\"CENTER\" size=\"2\" color=\"#008acb\" noshade>";
            
            $html2 .= "<table  align = \"left\"  WIDTH = \"100%\" border = \"0\" cellspacing = \"0\" cellpadding=\"0\">";
            
            $html2 .= "<tr>";
            $html2 .= "<td>&nbsp;</td>";
            $html2 .= "<td>&nbsp;</td>";
            $html2 .= "</tr>";
            
            $count = 1;
            $count3 = 1;
            
            if($flag_version == 1){
                foreach ($headingPhoto as $value) {
                    
                    $name = explode('.', $value["nom_foto"]);
                    $name = str_replace("_", " ", $name[0]);
                    $name_url_foto = str_replace(" ","%20", $name);
                    $reference = str_replace("/", "DIAGONAL", $reference);
                    
                    if ($count%3 == 0){
                        if ($count == $countHeading) {
                            $html2 .= "<td WIDTH = \"33%\">"  . $name .'<br/>'.'Factura: '.$value["num_fact"].'<br/>'.'Partida: '.$value["cons_part"]. "</font>" . '<center><img src="' .$accessurl. "/"  .$urlfiles. $idClient .'/'. $custom . '/' . $reference . '/' . $name_url_foto . '" height="133" width="170"></center>'."</td>";
                            $html2 .= "</tr>";
                        } else {
                            $html2 .= "<td WIDTH = \"33%\">"  . $name .'<br/>'.'Factura: '.$value["num_fact"].'<br/>'.'Partida: '.$value["cons_part"] . '<center><img src="' .$accessurl . "/" .$urlfiles. $idClient .'/'. $custom . '/' . $reference . '/' . $name_url_foto . '" height="133" width="170"></center>'."</td>";
                            $html2 .= "</tr>";
                            $html2 .= "<tr>";
                            $html2 .= "<td>&nbsp;</td>";
                            $html2 .= "<td>&nbsp;</td>";
                            $html2 .= "</tr>";
                        }
                    } else {
                        if ($count3 == 1) {
                            if ($count == $countHeading) {
                                $html2 .= "<tr>";
                                $html2 .= "<td WIDTH = \"33%\">"  . $name .'<br/>'.'Factura: '.$value["num_fact"].'<br/>'.'Partida: '.$value["cons_part"] . '<center><img src="' .$accessurl. "/".$urlfiles. $idClient .'/'. $custom . '/' . $reference . '/' . $name_url_foto . '" height="133" width="170"></center>'. "</td>";
                                $html2 .= "</tr>";
                            } else  {
                                $html2 .= "<tr>";
                                $html2 .= "<td WIDTH = \"33%\">" . $name .'<br/>'.'Factura: '.$value["num_fact"].'<br/>'.'Partida: '.$value["cons_part"] . '<center><img src="' .$accessurl. "/" .$urlfiles. $idClient .'/'. $custom . '_' . $patente .'/' . $reference . "/".$value["id_partida"] .'/Fotos/' . $name_url_foto. '" height="133" width="170"></center>'. "</td>";
                                $count3++;
                            }
                        } else {
                            if ($count == $countHeading) {
                                $html2 .= "<td WIDTH = \"33%\">" . $name .'<br/>'.'Factura: '.$value["num_fact"].'<br/>'.'Partida: '.$value["cons_part"]. '<center><img src="' .$accessurl. "/" .$urlfiles. $idClient .'/'. $custom . '/' . $reference . '/' . $name_url_foto . '" height="133" width="170"></center>'. "</td>";
                                $html2 .= "</tr>";
                            } else {
                                $html2 .= "<td WIDTH = \"33%\">" . $name .'<br/>'.'Factura: '.$value["num_fact"].'<br/>'.'Partida: '.$value["cons_part"] . '<center><img src="' .$accessurl. "/" .$urlfiles. $idClient .'/'. $custom . '/' . $reference . '/' . $name_url_foto. '" height="133" width="170"></center>'. "</td>";
                                $count3 = 1;
                            }
                            
                        }
                    }
                    $count++;
                }
            } else {
                foreach ($headingPhoto as $value) {
                    $name = explode('.', $value["nom_foto"]);
                    $name = str_replace("_", " ", $name[0]);
                    $name_url_foto = str_replace(" ","%20", $value["url_foto"] );
                    $name_photo = str_replace(" ","%20", $value["nom_foto"]);
                    $reference = str_replace("/", "DIAGONAL", $reference);
                    
                    if ($count%3 == 0){
                        if ($count == $countHeading) {
                            $html2 .= "<td WIDTH = \"33%\">" . $name .'<br/>'.'Factura: '.$value["num_fact"].'<br/>'.'Partida: '.$value["cons_part"] . '<center><img src="' .$accessurl. "/" . $name_url_foto. '/' . $name_photo . '" height="133" width="170"></center>'. "</td>";
                            $html2 .= "</tr>";
                        } else {
                            $html2 .= "<td WIDTH = \"33%\">" . $name .'<br/>'.'Factura: '.$value["num_fact"].'<br/>'.'Partida: '.$value["cons_part"] . '<center><img src="' .$accessurl. "/". $name_url_foto. '/' . $name_photo . '" height="133" width="170"></center>'. "</td>";
                            $html2 .= "</tr>";
                            $html2 .= "<tr>";
                            $html2 .= "<td>&nbsp;</td>";
                            $html2 .= "<td>&nbsp;</td>";
                            $html2 .= "</tr>";
                        }
                    } else {
                        if ($count3 == 1) {
                            if ($count == $countHeading) {
                                $html2 .= "<tr>";
                                $html2 .= "<td WIDTH = \"33%\">" . $name .'<br/>'.'Factura: '.$value["num_fact"].'<br/>'.'Partida: '.$value["cons_part"]. '<center><img src="' .$accessurl. "/". $name_url_foto . '/' . $name_photo. '" height="133" width="170"></center>'. "</td>";
                                $html2 .= "</tr>";
                            } else  {
                                $html2 .= "<tr>";
                                $html2 .= "<td WIDTH = \"33%\">"  . $name .'<br/>'.'Factura: '.$value["num_fact"].'<br/>'.'Partida: '.$value["cons_part"] . '<center><img src="' .$accessurl. "/".$name_url_foto . '/' . $name_photo . '" height="133" width="170"></center>'. "</td>";
                                $count3++;
                            }
                        } else {
                            if ($count == $countHeading) {
                                $html2 .= "<td WIDTH = \"33%\">" . $name .'<br/>'.'Factura: '.$value["num_fact"].'<br/>'.'Partida: '.$value["cons_part"] . '<center><img src="' .$accessurl. "/".$name_url_foto . '/' . $name_photo . '" height="133" width="170"></center>'. "</td>";
                                $html2 .= "</tr>";
                            } else {
                                $html2 .= "<td WIDTH = \"33%\">" . $name .'<br/>'.'Factura: '.$value["num_fact"].'<br/>'.'Partida: '.$value["cons_part"]. '<center><img src="' .$accessurl. "/". $name_url_foto. '/' . $name_photo. '" height="133" width="170"></center>'. "</td>";
                                $count3 = 1;
                            }
                        }
                    }
                    $count++;
                }
            }
            $html2 .= "</table>";
            return $html2;
            
        } else {
            return false;
        }
    }
    
    //original php pdf
    public function getPhotosMerchandisePHP($reference, $idClient, $custom, $idLanguage, $urlfiles){
        $db =  $this->getConnection();
        
        $db->setTable("'previo'.cprevo_refe");
        $db->setFields(array(
            "id_prev",
        ));
        $db->setParameters("num_refe = '$reference'");
        $variable = $this->getConnection();
        $db->setReturnType($variable ::TYPE_ARRAY_ALL);
        $previo = $db->query();
        
        $idPrevio = $previo[0]["id_prev"];
        
        $db->setTable("'previo'.cprevo_refe T1");
        $db->setJoins("INNER JOIN 'previo'.cprevo_factur T2 ON T1.id_prev = T2.id_prev");
        $db->setJoin("INNER JOIN 'previo'.cprevo_facpar T3 ON T2.id_factur = T3.id_factur");
        $db->setJoin("INNER JOIN 'previo'.cprevo_fotos T4 ON T3.id_partida = T4.id_partida");
        $db->setJoin("INNER JOIN previo.cprevo_previos T5 ON T1.id_prev = T5.id_prev");
        $db->setJoin("INNER JOIN 'general'.casag_licencias L ON T1.id_licencia = L.id_licencia");
        $db->setFields(array(
            "T4.nom_foto",
            "T2.num_fact",
            "T3.cons_part",
            "T4.url_foto",
            "L.patente",
            "T5.flag_version",
            "T2.cons_fact",
            "T3.cons_part",
            "T4.cons_foto"
        ));
        $db->setParameters("T1.num_refe = '$reference' ORDER BY T2.cons_fact, T3.cons_part, T4.cons_foto");
        $variable = $this->getConnection();
        $db->setReturnType($variable ::TYPE_ARRAY_ALL);
        $headingPhoto = $db->query();
        
        
        $countHeading = count($headingPhoto);
        
        $html2 = "";
        
        if ($countHeading > 0) {
            $patente = $headingPhoto[0]["patente"];
            $flag_version = $headingPhoto[0]["flag_version"];
            
            if ($idLanguage == 1) {
                $html2 .= "<P ALIGN=left style=\"background-color:#FFFFFF;\"><b>Fotos Mercanc&iacute;a</b></P>";
            } else {
                $html2 .= "<P ALIGN=left style=\"background-color:#FFFFFF;\"><b>Merchandise Photos</b></P>";
            }
            
            $html2 .= "<HR align=\"CENTER\" size=\"2\" color=\"008acb\" noshade>";
            
            $html2 .= "<table  align = \"left\"  WIDTH = \"100%\" border = \"0\" cellspacing = \"0\" cellpadding=\"0\">";
            
            $html2 .= "<tr>";
            $html2 .= "<td>&nbsp;</td>";
            $html2 .= "<td>&nbsp;</td>";
            $html2 .= "</tr>";
            
            $count = 1;
            $count3 = 1;
            
            if($flag_version == 1){
                foreach ($headingPhoto as $value) {
                    $name = explode('.', $value["nom_foto"]);
                    $name = str_replace("_", " ", $name[0]);
                    $reference = str_replace("/", "DIAGONAL", $reference);
                    
                    if ($count%3 == 0){
                        if ($count == $countHeading) {
                            $html2 .= "<td WIDTH = \"33%\">" . "<font face = \"arial\" size = \"9\">" . $name .'<br/>'.'Factura: '.$value["num_fact"].'<br/>'.'Partida: '.$value["cons_part"]. "</font>" . '<center><img src="' .$urlfiles. $idClient .'/'. $custom . '/' . $reference . '/' . $value["nom_foto"] . '" height="133" width="170"></center>'."</td>";
                            $html2 .= "</tr>";
                        } else {
                            $html2 .= "<td WIDTH = \"33%\">" . "<font face = \"arial\" size = \"9\">" . $name .'<br/>'.'Factura: '.$value["num_fact"].'<br/>'.'Partida: '.$value["cons_part"]. "</font>" . '<center><img src="' .$urlfiles. $idClient .'/'. $custom . '/' . $reference . '/' . $value["nom_foto"] . '" height="133" width="170"></center>'."</td>";
                            $html2 .= "</tr>";
                            $html2 .= "<tr>";
                            $html2 .= "<td>&nbsp;</td>";
                            $html2 .= "<td>&nbsp;</td>";
                            $html2 .= "</tr>";
                        }
                    } else {
                        if ($count3 == 1) {
                            if ($count == $countHeading) {
                                $html2 .= "<tr>";
                                $html2 .= "<td WIDTH = \"33%\">" . "<font face = \"arial\" size = \"9\">" . $name .'<br/>'.'Factura: '.$value["num_fact"].'<br/>'.'Partida: '.$value["cons_part"]. "</font>" . '<center><img src="' .$urlfiles. $idClient .'/'. $custom . '/' . $reference . '/' . $value["nom_foto"] . '" height="133" width="170"></center>'. "</td>";
                                $html2 .= "</tr>";
                            } else  {
                                $html2 .= "<tr>";
                                $html2 .= "<td WIDTH = \"33%\">" . "<font face = \"arial\" size = \"9\">" . $name .'<br/>'.'Factura: '.$value["num_fact"].'<br/>'.'Partida: '.$value["cons_part"]. "</font>" . '<center><img src="' .$urlfiles. $idClient .'/'. $custom . '_' . $patente .'/' . $reference . '/1248614/Fotos/' . $value["nom_foto"] . '" height="133" width="170"></center>'. "</td>";
                                $count3++;
                            }
                        } else {
                            if ($count == $countHeading) {
                                $html2 .= "<td WIDTH = \"33%\">" . "<font face = \"arial\" size = \"9\">" . $name .'<br/>'.'Factura: '.$value["num_fact"].'<br/>'.'Partida: '.$value["cons_part"]. "</font>" . '<center><img src="' .$urlfiles. $idClient .'/'. $custom . '/' . $reference . '/' . $value["nom_foto"] . '" height="133" width="170"></center>'. "</td>";
                                $html2 .= "</tr>";
                            } else {
                                $html2 .= "<td WIDTH = \"33%\">" . "<font face = \"arial\" size = \"9\">" . $name .'<br/>'.'Factura: '.$value["num_fact"].'<br/>'.'Partida: '.$value["cons_part"]. "</font>" . '<center><img src="' .$urlfiles. $idClient .'/'. $custom . '/' . $reference . '/' . $value["nom_foto"] . '" height="133" width="170"></center>'. "</td>";
                                $count3 = 1;
                            }
                        }
                    }
                    $count++;
                }
            } else {
                foreach ($headingPhoto as $value) {
                    $name = explode('.', $value["nom_foto"]);
                    $name = str_replace("_", " ", $name[0]);
                    $reference = str_replace("/", "DIAGONAL", $reference);
                    
                    if ($count%3 == 0){
                        if ($count == $countHeading) {
                            $html2 .= "<td WIDTH = \"33%\">" . "<font face = \"arial\" size = \"9\">" . $name .'<br/>'.'Factura: '.$value["num_fact"].'<br/>'.'Partida: '.$value["cons_part"]. "</font>" . '<center><img src="'. ".." . $value["url_foto"] . '/' . $value["nom_foto"] . '" height="133" width="170"></center>'. "</td>";
                            $html2 .= "</tr>";
                        } else {
                            $html2 .= "<td WIDTH = \"33%\">" . "<font face = \"arial\" size = \"9\">" . $name .'<br/>'.'Factura: '.$value["num_fact"].'<br/>'.'Partida: '.$value["cons_part"]. "</font>" . '<center><img src="' . "..". $value["url_foto"] . '/' . $value["nom_foto"] . '" height="133" width="170"></center>'. "</td>";
                            $html2 .= "</tr>";
                            $html2 .= "<tr>";
                            $html2 .= "<td>&nbsp;</td>";
                            $html2 .= "<td>&nbsp;</td>";
                            $html2 .= "</tr>";
                        }
                    } else {
                        if ($count3 == 1) {
                            if ($count == $countHeading) {
                                $html2 .= "<tr>";
                                $html2 .= "<td WIDTH = \"33%\">" . "<font face = \"arial\" size = \"9\">" . $name .'<br/>'.'Factura: '.$value["num_fact"].'<br/>'.'Partida: '.$value["cons_part"]. "</font>" . '<center><img src="' . "..". $value["url_foto"] . '/' . $value["nom_foto"] . '" height="133" width="170"></center>'. "</td>";
                                $html2 .= "</tr>";
                            } else  {
                                $html2 .= "<tr>";
                                $html2 .= "<td WIDTH = \"33%\">" . "<font face = \"arial\" size = \"9\">" . $name .'<br/>'.'Factura: '.$value["num_fact"].'<br/>'.'Partida: '.$value["cons_part"]. "</font>" . '<center><img src="' . "..".$value["url_foto"] . '/' . $value["nom_foto"] . '" height="133" width="170"></center>'. "</td>";
                                $count3++;
                            }
                        } else {
                            if ($count == $countHeading) {
                                $html2 .= "<td WIDTH = \"33%\">" . "<font face = \"arial\" size = \"9\">" . $name .'<br/>'.'Factura: '.$value["num_fact"].'<br/>'.'Partida: '.$value["cons_part"]. "</font>" . '<center><img src="' . "..". $value["url_foto"] . '/' . $value["nom_foto"] . '" height="133" width="170"></center>'. "</td>";
                                $html2 .= "</tr>";
                            } else {
                                $html2 .= "<td WIDTH = \"33%\">" . "<font face = \"arial\" size = \"9\">" . $name .'<br/>'.'Factura: '.$value["num_fact"].'<br/>'.'Partida: '.$value["cons_part"]. "</font>" . '<center><img src="' . "..". $value["url_foto"] . '/' . $value["nom_foto"] . '" height="133" width="170"></center>'. "</td>";
                                $count3 = 1;
                            }
                        }
                    }
                    $count++;
                }
            }
            $html2 .= "</table>";
            return $html2;
            
        } else {
            return false;
        }
    }
    
    
 
    
    public function getTotalPhotos($idPrevio, $idPartida){
        if($idPrevio != null || $idPrevio != ''){
            $db =  $this->getConnection();
            $db->setTable("'previo'.cprevo_fotop");
            $db->setFields(array(
                "count(*)",
            ));
            $db->setParameters("id_prev = '$idPrevio'");
            
            $var = $this->getConnection();
            $db->setReturnType($var ::TYPE_ARRAY_ALL);
            $total = $db->query();
            
            return $total;
            
        }else{
            $db =  $this->getConnection();
            $db->setTable("'previo'.cprevo_fotos");
            $db->setFields(array(
                "count(*)",
            ));
            $db->setParameters("id_partida = '$idPartida'");
            
            $var = $this->getConnection();
            $db->setReturnType($var ::TYPE_ARRAY_ALL);
            $total = $db->query();
            
            return $total;
            
        }
    }
    
    public function getTotalPhotosGalley($idPrevio, $idPartida){
        if($idPrevio != null || $idPrevio != ''){
            $db =  $this->getConnection();
            $db->setTable("'previo'.cprevo_fotop");
            $db->setFields(array(
                "count(*)",
            ));
            $db->setParameters("id_prev = '$idPrevio'");
            
            $var = $this->getConnection();
            $db->setReturnType($var ::TYPE_ARRAY_ALL);
            $total = $db->query();
            
            return $total;
        }else{
            $db =  $this->getConnection();
            $db->setTable("'previo'.cprevo_fotos");
            $db->setFields(array(
                "count(*)",
            ));
            $db->setParameters("id_partida = '$idPartida'");
            
            $var = $this->getConnection();
            $db->setReturnType($var ::TYPE_ARRAY_ALL);
            $total = $db->query();
            
            return $total;
        }
        
    }
    
    public function getSeePhotosGallery($flag, $idPrevio, $idClient, $custom, $reference, $idPartida, $urlfiles, $keyOne, $keyTwo){
        $db =  $this->getConnection();
        $routeRefus = $this->variableRoute();
        
        if($flag == 1) {
            
            $db->setTable("'previo'.cprevo_refe pcr");
            $db->setJoins("INNER JOIN 'general'.casag_licencias gcl ON pcr.id_licencia = gcl.id_licencia");
            $db->setFields(array("gcl.id_cliente"));
            $where = "pcr.id_prev = ?";
            $paramsArray = array($idPrevio);
            $variable = $this->getConnection();
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ONE);
            $validateData = $db->queryParametrize($where, $paramsArray);
            
            
            if($validateData["id_cliente"] == $idClient){
                $db->setTable("'previo'.cprevo_fotop");
                $db->setJoins("");
                $db->setFields(array(
                    "nom_foto",
                    "url_foto",
                    "cons_foto"
                ));
                $where = "id_prev = ? ORDER BY cons_foto";
                $paramsArray = array($idPrevio);
                $variable = $this->getConnection();
                $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                $nom_photo = $db->queryParametrize($where, $paramsArray);
                
                $count     = count($nom_photo);
                
                if($count == 0){
                    return false;
                    
                } else {
                    $finalRoute = str_replace($nom_photo[0]["nom_foto"], "", $nom_photo[0]["url_foto"]);
                    if (file_exists($routeRefus.$finalRoute)) {
                        
                        symlink($routeRefus.$finalRoute, '/var/www/html/TemporaryFiles/'.$keyOne);
                        
                    }
                    
                    $html      = '<div style="padding: 1px; height: 500px; width: 800px;">';
                    $info      = '<table id = "photos">';
                    $indicador = 0;
                    
                    foreach ($nom_photo as $value) {
                        if($indicador < 3){
                            $info .= '<td>';
                            $namePhoto = explode('/', $value["nom_foto"]);
                            $name      = explode('.', $namePhoto[count($namePhoto) - 1]);
                            $nameFile  = str_replace('.' . $name[count($name) - 1], '', $namePhoto[count($namePhoto) - 1]);
                            $reference = str_replace("/", "DIAGONAL", $reference);
                            
                            if($value["url_foto"] != null){
                                $info .= '<th><center><img src= "../'.'TemporaryFiles/'. '/'. $keyOne. '/'.$value["nom_foto"].'" title="'."$nameFile".'" height="200" width="230" ALT="photo;" ALIGN="LEFT"></br>'. $value["nom_foto"] .'</center></th>';
                            }
                            
                            $indicador++;
                            $info .= '</td>';
                            
                        }else{
                            $indicador = 0;
                            $info .= '<td>';
                            $namePhoto = explode('/', $value["nom_foto"]);
                            $name      = explode('.', $namePhoto[count($namePhoto) - 1]);
                            $nameFile  = str_replace('.' . $name[count($name) - 1], '', $namePhoto[count($namePhoto) - 1]);
                            $reference = str_replace("/", "DIAGONAL", $reference);
                            
                            if($value["url_foto"] != null){
                                $info .= '<th><center><img src= "../'.'TemporaryFiles/'. '/'. $keyOne. '/'.$value["nom_foto"].'" title="'."$nameFile".'" height="200" width="230" ALT="photo;" ALIGN="LEFT"></br>'. $value["nom_foto"] .'</center></th>';
                            }
                            $info .= '</td><tr></tr>';
                        }
                    }
                    $info .= '</table>';
                }
            }else{
                return '0P';
            }
        } else {
            
            $db->setTable("'previo'.cprevo_refe pcr");
            $db->setJoins("INNER JOIN 'general'.casag_licencias gcl ON pcr.id_licencia = gcl.id_licencia");
            $db->setFields(array("gcl.id_cliente"));
            $where = "pcr.num_refe = ?";
            $paramsArray = array($reference);
            $variable = $this->getConnection();
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ONE);
            $validateData = $db->queryParametrize($where, $paramsArray);
            
            if($validateData["id_cliente"] == $idClient){
                
                $db->setTable("'previo'.cprevo_fotos");
                $db->setFields(array(
                    "nom_foto",
                    "url_foto",
                    "cons_foto"
                ));
                $db->setJoins("");
                $where = "id_partida = ? ORDER BY cons_foto";
                $paramsArray = array($idPartida);
                $variable = $this->getConnection();
                $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                $nom_photo = $db->queryParametrize($where, $paramsArray);
                
                $count     = count($nom_photo);
                
                if($count == 0){
                    return false;
                    
                }else{
                    
                    if (file_exists($routeRefus.$nom_photo[0]["url_foto"])) {
                        
                        symlink($routeRefus.$nom_photo[0]["url_foto"], '/var/www/html/TemporaryFiles/'.$keyOne);
                    }
                    
                    $html      = '<div style="padding: 1px; height: 500px; width: 800px;">';
                    $info      = '<table id = "photos">';
                    $indicador = 0;
                    
                    foreach ($nom_photo as $value) {
                        if($indicador < 3){
                            $info .= '<td>';
                            $namePhoto = explode('/', $value["nom_foto"]);
                            $reference = str_replace("/", "DIAGONAL", $reference);
                            $name      = explode('.', $namePhoto[count($namePhoto) - 1]);
                            $nameFile  = str_replace('.' . $name[count($name) - 1], '', $namePhoto[count($namePhoto) - 1]);
                            
                            if($value["url_foto"] != null){
                                $info .= '<th><center><img src= "'.'/TemporaryFiles/'. '/'. $keyOne.'/' . $value["nom_foto"] .'" title="'."$nameFile".'" height="200" width="230" ALT="photo;" ALIGN="LEFT"></br>'. $value["nom_foto"] .'</center></th>';
                            }
                            $indicador++;
                            $info .= '</td>';
                            
                        }else{
                            $indicador=0;
                            $info .= '<td>';
                            $namePhoto = explode('/', $value["nom_foto"]);
                            $reference = str_replace("/", "DIAGONAL", $reference);
                            $name      = explode('.', $namePhoto[count($namePhoto) - 1]);
                            $nameFile  = str_replace('.' . $name[count($name) - 1], '', $namePhoto[count($namePhoto) - 1]);
                            
                            if($value["url_foto"] != null){
                                $info .= '<th><center><img src= "'.'/TemporaryFiles/'. '/'. $keyOne.'/'. $value["nom_foto"] .'" title="'."$nameFile".'" height="200" width="230" ALT="photo;" ALIGN="LEFT"></br>'. $value["nom_foto"] .'</center></th>';
                            }
                            
                            $info .= '</td><tr></tr>';
                        }
                    }
                    $info .= '</table>';
                }
            }else{
                return '0P';
            }
        }
        return $info;
    }
    
    public function getFacpar($reference, $idClient){
        
        $db =  $this->getConnection();
        $db->setTable("'previo'.cprevo_refe pcr");
        $db->setJoins("INNER JOIN 'general'.casag_licencias gcl ON pcr.id_licencia = gcl.id_licencia");
        $db->setFields(array("gcl.id_cliente"));
        $where = "pcr.num_refe = ?";
        $paramsArray = array($reference);
        $variable = $this->getConnection();
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ONE);
        $validateData = $db->queryParametrize($where, $paramsArray);
        
        if(intval($validateData["id_cliente"]) == $idClient){
            $db =  $this->getConnection();
            
            $db->setTable("'previo'.cprevo_refe R1");
            $db->setJoins("INNER JOIN 'previo'.cprevo_factur T2 ON R1.id_prev = T2.id_prev");
            $db->setJoin("INNER JOIN 'previo'.cprevo_facpar T3 ON T2.id_factur = T3.id_factur");
            $db->setJoin("INNER JOIN 'previo'.cprevo_previos P1 ON R1.id_prev = P1.id_prev");
            $db->setFields(array(
                "R1.num_refe",
                "T2.num_fact",
                "T3.cons_part",
                "T3.num_part"
            ));
            $where = "R1.num_refe = ? AND (T3.cve_usua = '' OR T3.cve_usua IS NULL) AND P1.fol_soli <> -1";
            $paramsArray = array($reference);
            $variable = $this->getConnection();
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $fapcar = $db->queryParametrize($where, $paramsArray);
            
            if(count($fapcar) > 0) {
                return $fapcar;
            } else {
                return false;
            }
        }else{
            return '0P';
        }
    }
    
    public function updateFacpar($invoice, $idClient){
        $db = new PgsqlQueries;
        $db->setTable("previo.cprevo_facpar");
        $db->setValues(array(
            "cve_usua" => 'USUTMP'
        ));
        $db->setParameters("id_factur = $invoice AND (cve_usua IS NULL OR cve_usua = '')");
        return $usersDatas = $db->update();
    }
    
    public function getInvoicesChange($reference, $idClient){
        $db = new PgsqlQueries;
        $db->setTable("'previo'.cprevo_refe R1");
        $db->setJoins("INNER JOIN 'previo'.cprevo_factur T2 ON R1.id_prev = T2.id_prev");
        $db->setJoin("INNER JOIN 'previo'.cprevo_facpar T3 ON T2.id_factur = T3.id_factur");
        $db->setJoin("INNER JOIN 'previo'.cprevo_previos P1 ON R1.id_prev = P1.id_prev");
        $db->setJoin("INNER JOIN 'general'.casag_licencias L ON R1.id_licencia = L.id_licencia");
        $db->setFields(array(
            "DISTINCT(T2.id_factur)",
            "T2.num_fact",
        ));
        $db->setParameters("R1.num_refe = '$reference' AND (T3.cve_usua = '' OR T3.cve_usua IS NULL) AND P1.fol_soli <> -1 AND L.id_cliente = $idClient");
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $fapcar = $db->query();
        return $fapcar;
    }
    
    
    public function getPendientInvoices($reference, $idClient){
        $db = new PgsqlQueries;
        $db->setTable("'previo'.cprevo_refe R1");
        $db->setJoins("INNER JOIN 'previo'.cprevo_factur T2 ON R1.id_prev = T2.id_prev");
        $db->setJoin("INNER JOIN 'previo'.cprevo_facpar T3 ON T2.id_factur = T3.id_factur");
        $db->setJoin("INNER JOIN 'previo'.cprevo_previos P1 ON R1.id_prev = P1.id_prev");
        $db->setJoin("INNER JOIN 'general'.casag_licencias L ON R1.id_licencia = L.id_licencia");
        
        $db->setFields(array(
            "DISTINCT(T2.id_factur)",
            "T2.num_fact",
        ));
        $db->setParameters("R1.num_refe = '$reference' AND (T3.cve_usua = '' OR T3.cve_usua IS NULL) AND P1.fol_soli <> -1 AND L.id_cliente = $idClient");
        
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        
        $fapcar = $db->query();
        
        if(count($fapcar)  >  0){
            return $fapcar;
        } else {
            return false;
        }
    }
    
    
    
    public function updateInvoicesArray($invoices) {
        $db = new PgsqlQueries;
        foreach ($invoices as $invoice) {
            $idInvoice = $invoice["id_factur"];
            if ($invoice["changeInvoiceStatus"] == true){
                $db->setTable("previo.cprevo_facpar");
                $db->setValues(array(
                    "cve_usua" => 'USUTMP'
                ));
                $db->setParameters("id_factur = $idInvoice AND (cve_usua IS NULL OR cve_usua = '')");
                $usersDatas = $db->update();
            }
        }
        return true;
    }
    
    public function getImagesTotal($client, $reference) {
        $db = new PgsqlQueries;
        $db->setTable("'previo'.merchandise");
        $db->setFields(array(
            "total_fotos",
        ));
        $db->setParameters("id_cliente = $client AND referencia = '$reference'");
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $merchandise = $db->query();
        $total = 0;
        
        foreach($merchandise as $merchan){
            $total = $merchan["total_fotos"] + $total;
        }
        
        return $total;
        
    }
    
    public function getExistDownloadTablet($idClient, $id_prev) {
        
        $db =  $this->getConnection();
        $db->setTable("previo.cprevo_refe pcr");
        $db->setJoins("INNER JOIN general.casag_licencias gcl ON pcr.id_licencia = gcl.id_licencia");
        $db->setFields(array("gcl.id_cliente,num_refe"));
        $where = "pcr.id_prev = ?";
        $paramsArray = array($id_prev);
        $variable = $this->getConnection();
        $db->setReturnType($variable ::TYPE_ARRAY_ONE);
        $prev = $db->queryParametrize($where, $paramsArray);
        
        if($prev["id_cliente"] == $idClient){
            
            
            $db = new PgsqlQueries;
            $db->setTable("'previo'.cprevo_descar");
            $db->setFields(array(
                "count(*) AS total",
            ));
            $db->setParameters("id_prev = '$id_prev'");
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $previous = $db->query();
            
            
            if(count($previous) > 0) {
                return $previous;
            } else {
                return NULL;
            }
        }else{
            return '0P';
            exit();
        }
    }
    
    public function getDownloadtablet($idClient, $id_prev, $user) {
        try {
            $tempArray = Array();
            
            $db = new PgsqlQueries;
            $db->setTable("'previo'.cprevo_descar d");
            $db->setJoins("INNER JOIN 'previo'.cprevo_refe r ON d.id_prev = r.id_prev");
            $db->setFields(array(
                "d.id_descarga",
                "d.id_prev",
                "r.estatus_refe"
            ));
            $where = "d.id_prev = ?";
            $paramsArray = array($id_prev);
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $previous = $db->queryParametrize($where, $paramsArray);
            
            
            if($previous){
                if($previous[0]["estatus_refe"] != 1) {
                    $tempArray["message"] = "No se puede reactivar la descarga porque el previo ya está iniciado.";
                    $tempArray["success"] = "false";
                    return $tempArray;
                    
                }
            }
            
            if($previous){
                foreach ($previous as $prev){
                    $idDownload = $prev["id_descarga"];
                    $idPrevio   = $prev["id_prev"];
                    
                    $db->setTable("previo.cprevo_descar");
                    $db->setParameters("id_prev = '" . $idPrevio  . "' AND id_descarga = '" . $idDownload  . "'" );
                    $download = $db->delete();
                    
                    if($download == 0){
                        $tempArray["message"] = "Ocurrió un error al reactivar la referencia en la tablet.";
                        $tempArray["success"] = "false";
                        return $tempArray;
                    }
                }
            }
   

            $num_refe = 's/a';
            $id_user = 1;
            $tip_accion = 4; //Reactivar descarga en tablet
            $comments = "Sin nombre";
            $nameusu = "Sin nombre";
            
            
            $response = $this->insertLogs($idClient, $id_prev, $num_refe, $num_refe, $id_user, $tip_accion, $comments, $nameusu);
 
            if($response == true){
                $tempArray["message"] = "Operación exitosa";
                $tempArray["success"] = "true";
                return $tempArray;
                
            } else {
                $tempArray["message"] = "Se reactivó la referencia corrrectamente pero ocurrió un error al insertar en el log.";
                $tempArray["success"] = "false";
                return $tempArray;
            }
            
        }catch(Exception $e){
            $tempArray["message"] = "Ocurrió un error al reactivar la descarga de la referencia en la tablet.";
            $tempArray["success"] = "false";
            return $tempArray;
        }
    }
    
    
    public function getValidateDownload($idClient, $id_prev){
        
        $db =  $this->getConnection();
        $db->setTable("previo.cprevo_refe pcr");
        $db->setJoins("INNER JOIN general.casag_licencias gcl ON pcr.id_licencia = gcl.id_licencia");
        $db->setFields(array("gcl.id_cliente"));
        $where = "pcr.id_prev = ?";
        $paramsArray = array($id_prev);
        $variable = $this->getConnection();
        $db->setReturnType($variable ::TYPE_ARRAY_ONE);
        $prev = $db->queryParametrize($where, $paramsArray);
        
        if($prev["id_cliente"] == $idClient){
            
            $db = new PgsqlQueries;
            $db->setTable("'previo'.cprevo_descar");
            $db->setFields(array(
                "count(*) AS total",
            ));
            $where = "id_prev = ? AND id_gdb is not null";
            $paramsArray = array($id_prev);
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $merchandise = $db->queryParametrize($where, $paramsArray);
            $total = $merchandise[0]["total"];
            
            return $total;
        }else{
            return '0P';
            exit();
        }
    }
    
    public function reactivateDownload($idClient, $id_prev, $id_user)
    {
        try {
            $db = new PgsqlQueries;
            $db->setTable("'previo'.cprevo_descar");
            $db->setFields(array(
                "id_descarga",
                "id_prev"
            ));
            $db->setParameters("id_prev = '$id_prev' AND id_gdb is not null");
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $previous = $db->query();
            
            if(!$previous){
                $tempArray["message"] = "La referencia ya se encuentra reactivada.";
                $tempArray["success"] = "true";
                return $tempArray;
            }
            
            foreach ($previous as $prev){
                $idDownload = $prev["id_descarga"];
                $idPrevio = $prev["id_prev"];
                $db->setTable("previo.cprevo_descar");
                $db->setParameters("id_prev = '" . $idPrevio  . "' AND id_descarga = '" . $idDownload  . "'" );
                $download = $db->delete();
            }
            
            $num_refe = 's/a';
            $id_user = 1;
            $tip_accion = 5; //Reactivar descarga en tablet
            $comments = "Sin nombre";
            $nameusu = "Sin nombre";
            
            
            if($download == 0){
                $tempArray["message"] = "Ocurrió un error al reactivar la referencia.";
                $tempArray["success"] = "false";
                return $tempArray;
            }
            
            
            $response = $this->insertLogs($idClient, $id_prev, $num_refe, $num_refe, $id_user, $tip_accion, $comments, $nameusu);
            
            if($response == true){
                $tempArray["message"] = "Operación exitosa";
                $tempArray["success"] = "true";
                return $tempArray;
                
            } else {
                $tempArray["message"] = "Se reactivó la referencia corrrectamente pero ocurrió un error al insertar en el log.";
                $tempArray["success"] = "false";
                return $tempArray;
            }
        } catch(Exception $e){
            return "false";
            exit;
        }
        
        
    }
    
    public function deleteReference($idClient, $id_prev, $id_user, $commentsdelete, $nameusu_delete, $selectdelete)
    {
        try {
            $db = new PgsqlQueries;
            $db->setTable("'previo'.cprevo_refe refe");
            $db->setJoins("INNER JOIN 'general'.casag_licencias l ON refe.id_licencia = l.id_licencia");
            $db->setFields(array(
                "id_prev",
                "refe.id_licencia",
                "num_refe"
                
            ));
            $db->setParameters("refe.id_prev = '$id_prev' AND l.id_cliente = $idClient");
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $prev = $db->query();
            
            $id_licencia = $prev[0]["id_licencia"];
            
            
            $db->setTable("previo.cprevo_descar");
            $db->setJoins("");
            $db->setFields(array(
                "id_prev"
                
            ));
            
            $where = "id_prev = ?";
            $paramsArray = array($id_prev);
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $prevDes = $db->queryParametrize($where, $paramsArray);
            
            
            if($prevDes){
                $db->setTable("previo.cprevo_descar");
                $db->setParameters("id_prev =  $id_prev");
                $downloaDELETE = $db->delete();
                if($downloaDELETE == 0){
                    $tempArray["message"] = "Ocurrió un error al eliminar la referencia.";
                    $tempArray["success"] = "false";
                    return $tempArray;
                }
            }
            
            $db->setTable("previo.cprevo_refe_email_lock");
            $db->setJoins("");
            $db->setFields(array(
                "id_prev"
                
            ));
            $wherePrev = "id_prev = ?";
            $paramsArray = array($id_prev);
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $prevEmailLock = $db->queryParametrize($wherePrev, $paramsArray);
            
            if($prevEmailLock){
                $db->setTable("previo.cprevo_refe_email_lock");
                $db->setParameters("id_prev =  $id_prev");
                $emailDELETE = $db->delete();
                if($emailDELETE == 0){
                    $tempArray["message"] = "Ocurrió un error al eliminar la referencia.";
                    $tempArray["success"] = "false";
                    return $tempArray;
                }
            }
            
            
            $db->setTable("previo.cprevo_logs");
            $db->setJoins("");
            $db->setFields(array(
                "id_prev"
                
            ));
            $wherePrevio = "id_prev = ?";
            $paramsArray = array($id_prev);
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $prevLogsLock = $db->queryParametrize($wherePrevio, $paramsArray);
            
            
            if($prevLogsLock){
                $db->setTable("previo.cprevo_logs");
                $db->setParameters("id_prev =  $id_prev");
                $logsDELETE = $db->delete();
                if($logsDELETE == 0){
                    $tempArray["message"] = "Ocurrió un error al eliminar la referencia.";
                    $tempArray["success"] = "false";
                    return $tempArray;
                }
            }
            
            
            $db->setTable("previo.cprevo_refe");
            $db->setParameters("id_prev = '" . $id_prev  . "'" );
            $deleteReference = $db->delete();
            
            
            if($deleteReference == 0){
                $tempArray["message"] = "Ocurrió un error al eliminar la referencia.";
                $tempArray["success"] = "false";
                return $tempArray;
            }
            
            $tip_accion = 1; //Eliminar referencia
            
            $num_refe = "s/n";
            $num_refe_orig = "s/n";
            
            $responseLogs = $this->insertLogs($idClient, $id_prev, $num_refe, $prev[0]["num_refe"], $id_user, $tip_accion, $commentsdelete, $nameusu_delete);
            
            
            if($responseLogs == true){
                $tempArray["message"] = "Operación exitosa";
                $tempArray["success"] = "true";
                return $tempArray;
                
            } else {
                $tempArray["message"] = "Se dio eliminó la referencia correctamente pero ocurrió un error al insertar en el log.";
                $tempArray["success"] = "false";
                return $tempArray;
            }
        } catch(Exception $e){
            var_dump($e->getMessage());
            return "false";
            exit;
        }
        
    }
    
    public function getColumnGrid($view) {
        $db =  $this->getConnection();
        $db->setTable("information_schema.columns");
        $db->setFields(array(
            "column_name",
            "data_type"
        ));
        
        $db->setParameters("table_schema = 'previo' AND table_name = '$view'");
        $variable = $this->getConnection();
        $db->setReturnType($variable ::TYPE_ARRAY_ALL);
        $columnsListGrid = $db->query();
        
        if(count($columnsListGrid) > 0) {
            return $columnsListGrid;
        } else {
            return NULL;
        }
    }
    
    public function getModelGrid($view) {
        $db =  $this->getConnection();
        $db->setTable("information_schema.columns");
        $db->setFields(array(
            "column_name",
            "data_type"
        ));
        
        $db->setParameters("table_schema = 'previo' AND table_name = '$view'");
        $variable = $this->getConnection();
        $db->setReturnType($variable ::TYPE_ARRAY_ALL);
        $modelGrid = $db->query();
        
        if(count($modelGrid) > 0) {
            return $modelGrid;
        } else {
            return NULL;
        }
    }
    
    public function getClienteReport($idClient, $view, $where) {
        
        $db =  $this->getConnection();
        try {
            
            
            $db->setTable("information_schema.columns");
            $db->setFields(array(
                "column_name",
                "data_type"
            ));
            $db->setParameters("table_schema = 'previo' AND table_name = '$view' ORDER BY ordinal_position");
            $variable = $this->getConnection();
            $db->setReturnType($variable ::TYPE_ARRAY_ALL);
            $viewGrid = $db->query();
            
            
            $column = '';
            foreach ($viewGrid as $row) {
                $column .= $row["column_name"] . ", ";
            }
            
            $column = substr($column, 0, - 2);
            
            
            $db->setTable("'previo'.\"$view\"");
            $db->setFields(array(
                "$column"
            ));
            
            $db->setParameters("$where");
            $variable = $this->getConnection();
            $db->setReturnType($variable ::TYPE_ARRAY_ALL);
            
            $clientReport = $db->query();
            
            $counter = 0;
            
            
            foreach ($clientReport as $client) {
                foreach ($viewGrid as $item) {
                    if (isset($client[$item["column_name"]])) {
                        $clienteReports[$counter][$item["column_name"]] = $client[$item["column_name"]];
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
    
    
    public function exportExcelReport ($file, $view, $idClient, $header, $where, $idLanguage, $clientColor, $nameFile, $reference, $idPrev)
    {
        
        try {
            
            $db =  $this->getConnection();
            
            $db->setTable("'previo'.\"$view\"");
            $db->setJoins("");
            $db->setFields(array(
                $header
                
            ));
            
            $db->setParameters("$where");
            $variable = $this->getConnection();
            $db->setReturnType($variable ::TYPE_ARRAY_ALL);
            
            $clientReport = $db->query();
            
        } catch (Exception $e) {
            var_dump($e->getMessage());
            exit();
        }
        
        if($clientReport != NULL) {
            
            $counter = 0;
            $columns = explode(",", $header);
            
            
            foreach ($clientReport as $row) {
                foreach ($columns as $item){
                    $clienteReports [$counter][$item]= $row[$item];
                }
                $counter++;
            }
            
            $records = $clienteReports;
            $headers = str_replace("_"," ",$header);
            $headersArray = explode(",", $headers);
            $recordSet = Array();
            $counter = 0;
            
            foreach ($records as $key => $rec) {
                $counter2 = 0;
                foreach ($rec as $key2 => $value) {
                    $recordSet[$counter][$headersArray[$counter2]] = $value;
                    $counter2++;
                }
                $counter++;
            }
            
            $db->setTable("information_schema.columns");
            $db->setJoins("");
            $db->setFields(array(
                "column_name",
                "data_type"
                
            ));
            
            $db->setParameters("table_schema = 'previo' AND table_name = '$view' ORDER BY ordinal_position");
            $variable = $this->getConnection();
            $db->setReturnType($variable ::TYPE_ARRAY_ALL);
            $structure = $db->query();
            
            
            $formatArray = Array();
            $counter = 0;
            $counter2 = 0;
            foreach ($structure as $value) {
                
                if ($counter2 > 0) {
                    
                    if ($value["data_type"] == "integer" || $value["data_type"] == "smallint") {
                        @$formatArray[$headersArray[$counter]] = "NUMBER";
                        
                        
                    } else if ($value["data_type"] == "double precision") {
                        $formatArray[$headersArray[$counter]] = "NUMBER_00";
                        
                        
                    } else if ($value["data_type"] == "timestamp without time zone") {
                        $formatArray[$headersArray[$counter]] = "DATE";
                        
                        
                    } else if ($value["data_type"] == "date") {
                        $formatArray[$headersArray[$counter]] = "DATE";
                        
                    } else if ($value["data_type"] == "character varying") {
                        $formatArray[$headersArray[$counter]] = "TEXT";
                    } else {
                        $formatArray[$headersArray[$counter]] = "GENERAL";
                        
                    }
                    
                    if ($value  ["column_name"] == "anio_validacion") {
                        @$formatArray[$headersArray[$counter]] = "GENERAL";
                    }
                    
                    if ($value  ["column_name"] == "fletes" || $value  ["column_name"] == "seguros" || $value  ["column_name"] == "embalajes" || $value  ["column_name"] == "otros" ||  $value  ["column_name"] == "iva" || $value  ["column_name"] == "dta" || $value  ["column_name"] == "adv" || $value  ["column_name"] == "prev") {
                        @$formatArray[$headersArray[$counter]] = "NUMBER";
                    }
                    
                    if ($value  ["column_name"] == "hora_de_ingreso") {
                        @$formatArray[$headersArray[$counter]] = "TEXT";
                    }
                    
                    
                    $counter++;
                }
                $counter2++;
            }
            
            
            $arrayAll = $this->getSeries($reference, $idPrev);
            
            
            $auth = new \ExportToExcel();
            $export = $auth::exportaToExcelPrevios($recordSet, $headersArray, $formatArray, $file, $view, $idLanguage, $clientColor, $nameFile, $arrayAll);
            
        }
        
    }
    
    public function getSeries($reference, $idPrev){
        
        try {
            
            $db =  $this->getConnection();
            $arrayseries = Array();
            
            $view = "serieall";
            $where = "id_prev = ". $idPrev . " order by id_partida";
            $listing = new \Listings;
            $columns = $listing->getColumnGrid($view);
            
            $header = "";
            $counter = 0;
            
            foreach ($columns as $row) {
                if ($counter > 0) {
                    $header .= $row["column_name"] . ",";
                }
                $counter ++;
            }
            
            $header = substr($header, 0, - 1);
            
            
            $db->setTable("'previo'.serieall");
            $db->setJoins("");
            $db->setFields(array(
                $header
                
            ));
            
            $db->setParameters("$where");
            $variable = $this->getConnection();
            $db->setReturnType($variable ::TYPE_ARRAY_ALL);
            
            $clientReport = $db->query();
            
            
            
        } catch (Exception $e) {
            var_dump($e->getMessage());
            exit();
        }
        
        
        if($clientReport != NULL) {
            
            $columns = explode(",", $header);
            $counter = 0;
            
            foreach ($clientReport as $row) {
                foreach ($columns as $item){
                    $clienteReports [$counter][$item]= $row[$item];
                }
                $counter++;
            }
            
            
            
            $records = $clienteReports;
            $headers = str_replace("_"," ",$header);
            $headersArray = explode(",", $headers);
            $recordSet = Array();
            $counter = 0;
            
            foreach ($records as $key => $rec) {
                $counter2 = 0;
                foreach ($rec as $key2 => $value) {
                    $recordSet[$counter][$headersArray[$counter2]] = $value;
                    $counter2++;
                }
                $counter++;
            }
            
            $db->setTable("information_schema.columns");
            $db->setJoins("");
            $db->setFields(array(
                "column_name",
                "data_type"
                
            ));
            
            $db->setParameters("table_schema = 'previo' AND table_name = '$view' ORDER BY ordinal_position");
            $variable = $this->getConnection();
            $db->setReturnType($variable ::TYPE_ARRAY_ALL);
            $structure = $db->query();
            
            $formatArray = Array();
            $counter = 0;
            $counter2 = 0;
            foreach ($structure as $value) {
                
                if ($counter2 > 0) {
                    
                    if ($value["data_type"] == "integer" || $value["data_type"] == "smallint") {
                        @$formatArray[$headersArray[$counter]] = "NUMBER";
                        
                        
                    } else if ($value["data_type"] == "double precision") {
                        $formatArray[$headersArray[$counter]] = "NUMBER_00";
                        
                        
                    } else if ($value["data_type"] == "timestamp without time zone") {
                        $formatArray[$headersArray[$counter]] = "DATE";
                        
                        
                    } else if ($value["data_type"] == "date") {
                        $formatArray[$headersArray[$counter]] = "DATE";
                        
                    } else if ($value["data_type"] == "character varying") {
                        $formatArray[$headersArray[$counter]] = "TEXT";
                    } else {
                        $formatArray[$headersArray[$counter]] = "GENERAL";
                        
                    }
                    
                    if ($value  ["column_name"] == "anio_validacion") {
                        @$formatArray[$headersArray[$counter]] = "GENERAL";
                    }
                    
                    if ($value  ["column_name"] == "fletes" || $value  ["column_name"] == "seguros" || $value  ["column_name"] == "embalajes" || $value  ["column_name"] == "otros" ||  $value  ["column_name"] == "iva" || $value  ["column_name"] == "dta" || $value  ["column_name"] == "adv" || $value  ["column_name"] == "prev") {
                        @$formatArray[$headersArray[$counter]] = "NUMBER";
                    }
                    
                    if ($value  ["column_name"] == "hora_de_ingreso") {
                        @$formatArray[$headersArray[$counter]] = "TEXT";
                    }
                    
                    
                    $counter++;
                }
                $counter2++;
            }
            
            
            $arrayseries["registros"] = $recordSet;
            $arrayseries["headersseries"] = $headersArray;
            $arrayseries["formato"] = $formatArray;
            
            
        }
        return $arrayseries;
    }
    
    
    public function getReference($reference, $idClient) {
        
        $db =  $this->getConnection();
        $db->setTable("'previo'.cprevo_refe pcr");
        $db->setJoins("INNER JOIN 'general'.casag_licencias gcl ON pcr.id_licencia = gcl.id_licencia");
        $db->setFields(array("gcl.id_cliente"));
        $where = "pcr.num_refe = ?";
        $paramsArray = array($reference);
        $variable = $this->getConnection();
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ONE);
        $validateData = $db->queryParametrize($where, $paramsArray);
        
        if(intval($validateData["id_cliente"]) == $idClient){
            $db->setTable("'previo'.cprevo_refe refe");
            $db->setJoins("INNER JOIN 'general'.casag_licencias l ON refe.id_licencia = l.id_licencia");
            $db->setFields(array(
                "id_prev"
            ));
            
            $db->setParameters("refe.num_refe = '$reference' AND l.id_cliente = $idClient");
            
            $variable = $this->getConnection();
            $db->setReturnType($variable ::TYPE_ARRAY_ONE);
            
            $prev = $db->query();
            return $prev;
            
        }else{
            return '0P';
        }
    }
    
    
    public function validateDeleteReference($idClient, $id_prev)
    {
        $tempArray = Array();
        
        try {
            
            $db = new PgsqlQueries;
            $db->setTable("'previo'.cprevo_refe refe");
            $db->setJoins("INNER JOIN 'general'.casag_licencias l ON refe.id_licencia = l.id_licencia");
            $db->setFields(array(
                "id_prev",
                "refe.id_licencia",
                "refe.estatus_refe"
                
            ));
            
            $wherePrevio = "refe.id_prev = ? AND l.id_cliente = ?";
            $paramsArray = array($id_prev, $idClient);
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $prev = $db->queryParametrize($wherePrevio, $paramsArray);
            
   
            if(count($prev) == 0){
                $tempArray["message"] = "El previo ya está eliminado.";
                $tempArray["success"] = "false";
                $tempArray["folio"] = null;
                return $tempArray;
            }
            
            $id_licencia = $prev[0]["id_licencia"];
            
            $db = new PgsqlQueries;
            $db->setTable("'previo'.cprevo_refe refe");
            $db->setJoins("INNER JOIN previo.cprevo_previos pre ON refe.id_prev = pre.id_prev");
            $db->setFields(array(
                "refe.id_prev",
                "fol_soli",
                
            ));
            $wherePrevFol = "refe.id_prev = ?";
            $paramsArray = array($id_prev);
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $prevDFol = $db->queryParametrize($wherePrevFol, $paramsArray);
            
            
            if($prev[0]["estatus_refe"] != 1){
                $message = "El previo ya está iniciado, si lo elimina perderá cualquier avance y deberá iniciar desde cero la captura de la información. Para continuar se recomienda elimine su previo en el dispositivo móvil.";
                $response =  "true";
                $tempArray["message"] = $message;
                $tempArray["success"] = $response;
                $tempArray["folio"] = $prevDFol[0]["fol_soli"];
                
                return $tempArray;
            }
            
            $db = new PgsqlQueries;
            $db->setTable("'previo'.cprevo_refe refe");
            $db->setJoins("INNER JOIN previo.cprevo_descar d ON refe.id_prev = d.id_prev");
            $db->setFields(array(
                "refe.id_prev"
                
            ));
            
            $wherePrevMov = "refe.id_prev = ? AND nom_movil IS NOT NULL";
            $paramsArray = array($id_prev);
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $prevDes = $db->queryParametrize($wherePrevMov, $paramsArray);
            
            if(count ($prevDes) >= 1){
                $message = "El previo ya fue descargado en algún dispositivo móvil, por lo que el avance que lleve en este se perderá y ya no se podrá recuperar. Para continuar, por favor, debe eliminar el previo en el dispositivo móvil descargado previamente y en CTRAWIN+ en caso de que quiera volver a solicitar el previo.";
                $response =  "true";
                $tempArray["message"] = $message;
                $tempArray["success"] = $response;
                $tempArray["folio"] = $prevDFol[0]["fol_soli"];
                return $tempArray;
            } else {
                $message = "El previo se eliminará completamente de la web.";
                $response =  "true";
                $tempArray["message"] = $message;
                $tempArray["success"] = $response;
                $tempArray["folio"] = $prevDFol[0]["fol_soli"];
                return $tempArray;
            }
            
            
            $tempArray["message"] = "null";
            $tempArray["success"] = "true";
            return $tempArray;
        } catch(Exception $e){
            return "false";
            exit;
        }
        
        
    }
    
    public function downloadPDF($reference){
        $zip = new ZipArchive();
        
        try {
            $filename = "../files/EPrevious/PDF/PDF_Previo_$reference.zip";
            
            $res = $zip->open($filename, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            $zip->addFile("../files/EPrevious/PDF/Previo_$reference.pdf", "Previo_$reference.pdf");
            $zip->close();
            
            return true;
        }catch(Exception $e){
            return false;
        }
    }
    
    public function downloadPDFPHP($reference){
        $zip = new ZipArchive();
        
        try {
            $filename = "../files/EPrevious/PDF/PDF_Previo_$reference.zip";
            
            $res = $zip->open($filename, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            $zip->addFile("../files/EPrevious/PDF/Previo_$reference.pdf", "Previo_$reference.pdf");
            $zip->close();
            
            return true;
        }catch(Exception $e){
            return false;
        }
    }
    
    public function downloadPhotos($idClient, $id_prev, $overwrite = false){
        
        
        $db =  $this->getConnection();
        $db->setTable("previo.cprevo_refe pcr");
        $db->setJoins("INNER JOIN general.casag_licencias gcl ON pcr.id_licencia = gcl.id_licencia");
        $db->setFields(array("gcl.id_cliente"));
        $where = "pcr.id_prev = ?";
        $paramsArray = array($id_prev);
        $variable = $this->getConnection();
        $db->setReturnType($variable ::TYPE_ARRAY_ONE);
        $prev = $db->queryParametrize($where, $paramsArray);
        
        
        if ($_SERVER['HTTP_HOST'] == "localhost") {
            $raiz = "/var/www/html";
        } else {
            $raiz = "/var/aMLn2nKpaiNb1IvGl8skjicOYRmgITH2";
        }
        
        
        if($prev["id_cliente"] == $idClient){
            $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
            $localWindows = 'C:\inetpub\wwwroot\\';
            $accessurl = $protocol. $_SERVER['HTTP_HOST'];
            $day = date('d');
            
            $db = new PgsqlQueries;
            $db->setTable("'previo'.cprevo_refe refe");
            $db->setJoin("INNER JOIN 'general'.casag_licencias l ON refe.id_licencia = l.id_licencia");
            $db->setFields(array(
                "id_prev",
                "refe.num_refe",
                "l.id_cliente"
                
            ));
            $where = "refe.id_prev = ? AND l.id_cliente = ?";
            $paramsArray = array($id_prev, $idClient);
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $prev = $db->queryParametrize($where, $paramsArray);
            
            # $numRefe = str_replace("/", "_", $prev[0]["num_refe"]);
            $numRefe = str_replace(array("\\","/", ":", "*", "?", "\"", "<", ">", "|"), '_', $prev[0]["num_refe"]);
            
            $db->setTable("'previo'.cprevo_fotop fp");
            $db->setJoins("");
            $db->setFields(array(
                "id_prev",
                "url_foto",
                "nom_foto"
                
            ));
            $db->setParameters("fp.id_prev = '$id_prev'");
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $urlPhoto = $db->query();
            $routeFotos = null;
            
            if(count($urlPhoto) <= 0){
                $db->setTable("previo.cprevo_factur F");
                $db->setJoins("INNER JOIN previo.cprevo_facpar P ON F.id_factur = P.id_factur");
                $db->setJoin("INNER JOIN previo.cprevo_fotos fp  on P.id_partida = fp.id_partida");
                $db->setFields(array(
                    "F.id_prev",
                    "url_foto",
                    "nom_foto",
                    "fp.id_partida"
                    
                ));
                $where = "F.id_prev = ?";
                $paramsArray = array($id_prev);
                $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
                $urlPhoto = $db->queryParametrize($where, $paramsArray);
                
                if(count($urlPhoto) <= 0){
                    $urlPhoto = null;
                }else{
                    $routeFotos = str_replace("/".$urlPhoto[0]["id_partida"].'/Fotos/', '', $urlPhoto[0]["url_foto"]);
                }
            }else{
                $routeFotos = str_replace('/Fotos/'.$urlPhoto[0]["nom_foto"], '', $urlPhoto[0]["url_foto"]);
            }
            
            
            if($routeFotos != null){
                $zip = new ZipArchive();
                $numero_aleatorio = mt_rand(0,5000000);
                //abrimos el archivo y lo preparamos para agregarle archivos
                
                //indicamos cual es la carpeta que se quiere comprimir esta es produccion
                // $origen = realpath("/var/aMLn2nKpaiNb1IvGl8skjicOYRmgITH2".$routeFotos);
                
                //indicamos cual es la carpeta que se quiere comprimir esta es local
                
                $origen = realpath($raiz.$routeFotos);

                if(!is_dir($origen)) {
                    $response["success"] = false;
                    $response["message"] = "No se encontraron las fotos de esta referencia";
                    return $response;
                }
                
                $zip->open("/var/www/html/TemporaryFiles/".$numRefe.".zip", \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
                
                //Ahora usando funciones de recursividad vamos a explorar todo el directorio y a enlistar todos los archivos contenidos en la carpeta
                $files = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($origen),
                    \RecursiveIteratorIterator::LEAVES_ONLY
                    );
                
                
                //Ahora recorremos el arreglo con los nombres los archivos y carpetas y se adjuntan en el zip
                foreach ($files as $name => $file){
                    if (!$file->isDir()){
                        $filePath = $file->getRealPath();
                        $relativePath = substr($filePath, strlen($origen) + 1);
                        $zip->addFile($filePath, $relativePath);
                        
                    }
                }
                
                //Se cierra el Zip
                $zip->close();
                
                $response["success"] = true;
                $response["message"] = "Operación exitosa";
                $response["reference"] = $numRefe;
                $response["day"] = $day;
                return $response;
            }else{
                
                $response["success"] = false;
                $response["message"] = "No existen fotos para esta referencia";
                $response["ruta"] = "";
                return $response;
            }
        }else{
            return '0P';
            exit();
        }
        
    }
    
    
    public function deleteZip($reference, $id_client){
        $day = date('d');
        $ruta =  '../TemporaryFiles/'.$reference.'.zip';
        chmod($ruta, 0777);
        
        if (strpos($reference, '../') !== false) {
            echo 'No es posible ingresar a un direcctorio distinto.';
            exit();
        }else{
            if(file_exists($ruta)){
                $ruta = str_replace("/\\", "/", $ruta);
                $responseDelete = unlink($ruta);
                $response["success"] = $responseDelete;
                return $response;
            }else{
                echo 'Error en la operación';
                exit();
            }
        }
    }
    
    public function statusReference($id_previo, $idClient){
        
        $db =  $this->getConnection();
        $db->setTable("previo.cprevo_refe pcr");
        $db->setJoins("INNER JOIN general.casag_licencias gcl ON pcr.id_licencia = gcl.id_licencia");
        $db->setFields(array("gcl.id_cliente"));
        $where = "pcr.id_prev = ?";
        $paramsArray = array($id_previo);
        $variable = $this->getConnection();
        $db->setReturnType($variable ::TYPE_ARRAY_ONE);
        $prev = $db->queryParametrize($where, $paramsArray);
        
        if($prev == true){
            if($prev["id_cliente"] == $idClient){
                $db =  $this->getConnection();
                $db->setTable("previo.cprevo_refe F");
                $db->setJoins("");
                $db->setFields(array(
                    "estatus_refe"
                ));
                $where = "id_prev = ?";
                $paramsArray = array($id_previo);
                $variable = $this->getConnection();
                $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ONE);
                $previo = $db->queryParametrize($where, $paramsArray);

                if($previo == true){
                    $response["success"] = "true";
                    $response["estatus"] = $previo["estatus_refe"];
                    return $response;
                } else {
                    $response["success"] = "false";
                    $response["estatus"] = "Sin estatus";
                    return $response;
                }
                
            }else{
                return '0P';
                exit();
            }
        } else {
            return '0P';
            exit();
        }
       
    }
    
    public function finishPrevpro($id_previo, $idClient,  $id_user, $commentsfinish, $nameusu_finish){
        $db =  $this->getConnection();
        $db->setTable("previo.cprevo_refe pcr");
        $db->setJoins("INNER JOIN general.casag_licencias gcl ON pcr.id_licencia = gcl.id_licencia");
        $db->setFields(array("gcl.id_cliente"));
        $where = "pcr.id_prev = ?";
        $paramsArray = array($id_previo);
        $variable = $this->getConnection();
        $db->setReturnType($variable ::TYPE_ARRAY_ONE);
        $prev = $db->queryParametrize($where, $paramsArray);
        
        if($prev["id_cliente"] == $idClient){
            $db =  $this->getConnection();
            $db = new PgsqlQueries;
            $db->setTable("previo.cprevo_refe");
            $db->setValues(array(
                "estatus_refe" => "3"
            ));
            $db->setParameters("id_prev = $id_previo");
            $previo = $db->update();
            
            if($previo == 0){
                $tempArray["message"] = "Ocurrió un error al cambiar el estatus de la referencia.";
                $tempArray["success"] = "false";
                return $tempArray;
            }
            
            
            $date    = date('Y-m-d H:i:s');
            $newHour = strtotime ( '-1 hour' , strtotime ($date) ) ;
            $newDate = date ( 'Y-m-d H:i:s' , $newHour);
            
            $db->setTable("previo.cprevo_previos");
            $db->setValues(array(
                "hora_fin" => "$newDate"
            ));
            $db->setParameters("id_prev = $id_previo");
            $prevFin = $db->update();
            
            if($prevFin == 0){
                $tempArray["message"] = "Ocurrió un error al insertar la hora fin.";
                $tempArray["success"] = "false";
                return $tempArray;
            }
            
            $db->setTable("previo.cprevo_refe F");
            $db->setFields(array(
                "num_refe","estatus_refe"
            ));
            $db->setParameters("id_prev =$id_previo");
            $variable = $this->getConnection();
            $db->setReturnType($variable ::TYPE_ARRAY_ALL);
            $previoRef = $db->query();
            
            $tip_accion = 3; //Finalizar prev
            
            $num_refe = "s/n";
            
            $responseLog = $this->insertLogs($idClient, $id_previo, $num_refe, $previoRef[0]["num_refe"], $id_user, $tip_accion, $commentsfinish, $nameusu_finish);
            
            if($responseLog == true){
                $tempArray["message"] = "Operación exitosa";
                $tempArray["success"] = "true";
                return $tempArray;
                
            } else {
                $tempArray["message"] = "Se dio por finalizada la referencia corrrectamente pero ocurrió un error al insertar en el log.";
                $tempArray["success"] = "false";
                return $tempArray;
            }
            
            
        } else{
            $tempArray["message"] = "Ocurrió un error al dar por finalizada la referencia.";
            $tempArray["success"] = "false";
            return $tempArray;
        }
    }
    
    public function finishPrevio($id_prev, $idClient) {
        
        $db =  $this->getConnection();
        $db->setTable("previo.cprevo_refe pcr");
        $db->setJoins("INNER JOIN general.casag_licencias gcl ON pcr.id_licencia = gcl.id_licencia");
        $db->setFields(array("gcl.id_cliente"));
        $where = "pcr.id_prev = ?";
        $paramsArray = array($id_prev);
        $variable = $this->getConnection();
        $db->setReturnType($variable ::TYPE_ARRAY_ONE);
        $prev = $db->queryParametrize($where, $paramsArray);

        if(count($prev) == 0){
            $response["success"] = false;
            return $response;
        }
        
        if($prev["id_cliente"] == $idClient){
            $db =  $this->getConnection();
            $db->setTable("previo.cprevo_factur F");
            $db->setJoins("INNER JOIN previo.cprevo_facpar P ON F.id_factur = P.id_factur");
            $db->setJoin("INNER JOIN previo.cprevo_refe R ON F.id_prev = R.id_prev");
            $db->setJoin("INNER JOIN general.casag_licencias L ON R.id_licencia = L.id_licencia");
            $db->setJoin("INNER JOIN general.casac_clientes C ON L.id_cliente = C.id_cliente");
            $db->setFields(array(
                "id_partida",
                "estatus_part",
                "num_refe"
            ));
            
            $where = "F.id_prev = ? AND (P.estatus_part is null OR P.estatus_part = 0)";
            $paramsArray = array($id_prev);
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $previo = $db->queryParametrize($where, $paramsArray);
            
            $db =  $this->getConnection();
            $db->setTable("previo.cprevo_previos");
            $db->setFields(array(
                "dep_asigna", "fol_soli"
            ));
            
            $whereF = "id_prev = ?";
            $paramsArray = array($id_prev);
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $previoFol = $db->queryParametrize($whereF, $paramsArray);
            
            $response["success"] = true;
            $response["folsode"] = $previo;
            $response["numDep"] = count($previo);
            return $response;
        }else{
            $response["success"] = false;
            return $response;
        }
    }
    
    public function getDependents($idClient, $query, $id_prev) {
        if($id_prev == null){ $id_prev = 0; }
        else {
            $db = new PgsqlQueries;
            $db->setTable("previo.cprevo_dependientes");
            $db->setFields(array(
                "id_dependiente",
                "nom_dependiente",
                "id_prev"
            ));
            $db->setParameters("id_prev = $id_prev ORDER BY id_dependiente");
            $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
            $dependents = $db->query();
            
            if(count($dependents) > 0) { return $dependents; }
            else { return NULL; }
        }
    }
    
    public function saveDependent($idClient, $idPrev, $id_dependiente, $nom_dependiente, $commentsfinish, $nameusu_finish, $id_user) {
        $db = new PgsqlQueries;
        $db->setTable("previo.cprevo_factur F");
        $db->setJoins("INNER JOIN previo.cprevo_facpar P ON F.id_factur = P.id_factur");
        $db->setJoin("INNER JOIN previo.cprevo_refe R ON F.id_prev = R.id_prev");
        $db->setJoin("INNER JOIN general.casag_licencias L ON R.id_licencia = L.id_licencia");
        $db->setJoin("INNER JOIN general.casac_clientes C ON L.id_cliente = C.id_cliente");
        $db->setFields(array( "id_partida", "estatus_part", "cve_usua", "num_refe", "estatus_refe" ));
        $db->setParameters("F.id_prev = '$idPrev' AND (P.estatus_part is null OR P.estatus_part = 0)" );
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $dependents = $db->query();
        
        $date    = date('Y-m-d H:i:s');
        $newHour = strtotime( '-1 hour' , strtotime ($date) ) ;
        $newDate = date( 'Y-m-d H:i:s' , $newHour);
        
        if(count($dependents) > 0){
            foreach ($dependents as $depend) {
                $idPart       = $depend["id_partida"];
                $cve_usua     = $depend["cve_usua"];
                $estatus_refe = $depend["estatus_refe"];
                
                $db->setTable("previo.cprevo_facpar");
                $db->setValues(array(
                    "cve_usua" => "$nom_dependiente",
                    "estatus_part" => "1",
                ));
                $db->setParameters("id_partida = $idPart");
                $facPar = $db->update();
                
                if($facPar == 0){
                    $tempArray["message"] = "Operación fallida. Ocurrió un error al cambiar el estatus de la partida.";
                    $tempArray["success"] = "false";
                    return $tempArray;
                }
                
            }
            
            $db->setTable("previo.cprevo_previos");
            $db->setValues(array( "hora_fin" => "$newDate" ));
            $db->setParameters("id_prev = $idPrev");
            $prev = $db->update();
            
            if($prev == 0){
                $tempArray["message"] = "Operación fallida. Ocurrió un error al insertar la hora de fin.";
                $tempArray["success"] = "false";
                return $tempArray;
            }
            
            
            if($estatus_refe != 3) {
                $db->setTable("previo.cprevo_refe");
                $db->setValues(array( "estatus_refe" => "3" ));
                $db->setParameters("id_prev = $idPrev");
                $refe = $db->update();
                
                if($refe == 0){
                    $tempArray["message"] = "Ocurrió un error al actualizar el estatus de la referencia.";
                    $tempArray["success"] = "false";
                    return $tempArray;
                }
                
            }
            
        } else {
            $db->setTable("previo.cprevo_previos");
            $db->setValues(array( "hora_fin" => "$newDate" ));
            $db->setParameters("id_prev = $idPrev");
            $prev = $db->update();
            
            if($prev == 0){
                $tempArray["message"] = "Ocurrió un error al actualizar la hora de fin.";
                $tempArray["success"] = "false";
                return $tempArray;
            }
            
            if($estatus_refe != 3) {
                $db->setTable("previo.cprevo_refe");
                $db->setValues(array( "estatus_refe" => "3" ));
                $db->setParameters("id_prev = $idPrev");
                $refe = $db->update();
                
                if($refe == 0){
                    $tempArray["message"] = "Ocurrió un error al actualizar el estatus de la referencia.";
                    $tempArray["success"] = "false";
                    return $tempArray;
                }
                
            }
        }
        
        
        $tip_accion = 3; //Terminar prev
        
        $num_refe = "s/n";
        $num_refe_orig = "s/n";
        
        
        $response = $this->insertLogs($idClient, $idPrev, $num_refe, $dependents[0]["num_refe"], $id_user, $tip_accion, $commentsfinish, $nameusu_finish);

        
        if($response == true){
            $tempArray["message"] = "Operación exitosa";
            $tempArray["success"] = "true";
            return $tempArray;
            
        } else {
            $tempArray["message"] = "Se dio por finalizada la referencia corrrectamente pero ocurrió un error al insertar en el log.";
            $tempArray["success"] = "false";
            return $tempArray;
        }
        
    }
    
    function unavailableFile($keyIdFolder){
        
        return unlink('/var/www/html/TemporaryFiles/'.$keyIdFolder);
    }
    
    //Crear nuevos directorios completos
    function full_copy( $source, $target ) {
        if ( is_dir( $source ) ) {
            @mkdir( $target );
            $d = dir( $source );
            while ( FALSE !== ( $entry = $d->read() ) ) {
                if ( $entry == '.' || $entry == '..' ) {
                    continue;
                }
                $Entry = $source . '/' . $entry;
                if ( is_dir( $Entry ) ) {
                    full_copy( $Entry, $target . '/' . $entry );
                    continue;
                }
                copy( $Entry, $target . '/' . $entry );
            }
            
            $d->close();
        }else {
            copy( $source, $target );
        }
        return true;
    }
    
    function rmDir_rf($carpeta){
        foreach(glob($carpeta . "/*") as $archivos_carpeta){
            if (is_dir($archivos_carpeta)){
                $this->rmDir_rf($archivos_carpeta);
            } else {
                unlink($archivos_carpeta);
            }
        }
        rmdir($carpeta);
    }
    
    function chmod_r($path) {
        $dir = new DirectoryIterator($path);
        foreach ($dir as $item) {
            chmod($item->getPathname(), 0777);
            if ($item->isDir() && !$item->isDot()) {
                $this->chmod_r($item->getPathname());
            }
        }
    }
    
    /**
     *
     * @return the $_connection
     */
    public function getConnection(){
        return new PgsqlQueries3;
    }
    
    public function setConnection($_connection){
        $this->_connection =  $this->getConnection();
    }
    
    function dirToArray($dir_path) {
        $result = array();
        $path = realpath($dir_path);
        $objects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path), \RecursiveIteratorIterator::SELF_FIRST);
        foreach($objects as $name => $object) {
            if( $object->getFilename() !== "." && $object->getFilename() !== "..") {
                $result[] = $object;
            }
        }
        return $result;
    }
    function getNameClient($num_refe){
        
        $db =  $this->getConnection();
        
        $db->setTable("previo.cprevo_refe pcr");
        $db->setJoins("INNER JOIN previo.cprevo_previos pcp ON pcr.id_prev = pcp.id_prev INNER JOIN general.casac_importadores gci ON pcp.id_importador = gci.id_importador");
        $db->setFields(array("gci.nombre_importador"));
        $where = "num_refe = ?";
        $paramsArray = array($num_refe);
        $db->setReturnType(PgsqlQueries::TYPE_ARRAY_ALL);
        $client = $db->queryParametrize($where, $paramsArray);
        
        return $client[0]["nombre_importador"];
    }
    
    function variableRoute(){

        if ($_SERVER['HTTP_HOST'] == "localhost") {
            return '/var/www/html/';
        } else {
            return '/var/aMLn2nKpaiNb1IvGl8skjicOYRmgITH2/';
        } 
    }
    
    
    public function renameReference($idClient, $id_prev, $num_refe, $num_refe_orig, $id_user, $comments, $nameusu_rename)
    {
        try {
            $db = new PgsqlQueries;
            $db->setTable("previo.cprevo_refe");
            $db->setValues(array(
                "num_refe" => $num_refe
            ));
            $db->setParameters("id_prev =  $id_prev");
            $renameDatas = $db->update();
            
            
            if($renameDatas == 0){
                $tempArray["message"] = "Ocurrió un error al renombrar la referencia.";
                $tempArray["success"] = "false";
                return $tempArray;
            }
            
            $tip_accion = 2; //Renombrar ref
            
            $response = $this->insertLogs($idClient, $id_prev, $num_refe, $num_refe_orig, $id_user, $tip_accion, $comments, $nameusu_rename);

            if($response == true){
                $tempArray["message"] = "Operación exitosa";
                $tempArray["success"] = "true";
                return $tempArray;
                
            } else {
                $tempArray["message"] = "Se renombró corrrectamente la referencia pero ocurrió un error al insertar en el log.";
                $tempArray["success"] = "false";
                return $tempArray;
            }
            
        
        } catch(Exception $e){
            $tempArray["message"] = "Ocurrió un error al renombrar la referencia";
            $tempArray["success"] = "false";
            return $tempArray;
        }
    }
    
    public function insertLogs($idClient, $id_prev, $num_refe, $num_refe_orig, $id_user, $tip_accion, $comments, $nameusu_rename)
    {
        $dbAdoP = ConnectionFactory::Connectpostgres();
        try {
            
            if (getenv('HTTP_CLIENT_IP')) {
                $ip = getenv('HTTP_CLIENT_IP');
            } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
                $ip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_X_FORWARDED')) {
                $ip = getenv('HTTP_X_FORWARDED');
            } elseif (getenv('HTTP_FORWARDED_FOR')) {
                $ip = getenv('HTTP_FORWARDED_FOR');
            } elseif (getenv('HTTP_FORWARDED')) {
                $ip = getenv('HTTP_FORWARDED');
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
            
            
            $db =  $this->getConnection();
            
            /*$nextVal = "SELECT setval('previo.cprevo_logs_id_log_seq', (SELECT MAX(id_log) FROM previo.cprevo_logs)+1)";
            $nextIdLog= $dbAdoP->Execute ( $nextVal );
            $idLogPrev= json_decode(json_encode($nextIdLog->fields), true);
            $id_logNext = $idLogPrev[0];
            
            
            $db->setTable("previo.cprevo_logs");
            $db->setValues(array(
                "id_log" =>$id_logNext,
                "id_prev" => $id_prev,
                "id_usuario"=> $id_user,
                //"fec_act" => date('Y-m-d H:i:s'),
               // "fec_act" => date('m-d-Y H:i:s'),
                "ip_origen" => $ip,
                "nom_orig_refe" => $num_refe_orig,
                "nom_new_refe" => $num_refe,
                "tip_accion" =>  $tip_accion,
                "motivo_accion" =>$comments,
                "nom_usu_accion" => $nameusu_rename
                
            ));
            
            $logs = $db->insert();*/

            $nextValLog = "SELECT setval('previo.cprevo_logs_id_log_seq', COALESCE((SELECT MAX(id_log) FROM previo.cprevo_logs), 0) + 1) AS nextId";
            $nextIdLog= $dbAdoP->Execute ( $nextValLog );
            $id_LogNext = $nextIdLog->fields["nextid"];

            $dateNow = date('m-d-Y H:i:s');
            $insertDefault =    "INSERT INTO previo.cprevo_logs(id_log, id_prev, id_usuario, fec_act, ip_origen, nom_orig_refe, nom_new_refe, tip_accion, motivo_accion, nom_usu_accion)
                            VALUES ($id_LogNext, $id_prev, $id_user,  '$dateNow', '$ip',  '$num_refe_orig', '$num_refe', $tip_accion,'$comments','$nameusu_rename')";
            
            $resultInsertLog= $dbAdoP->Execute ( $insertDefault );

            if (isset($resultInsertLog)) {
                return true;
            } 

            return false;
            
           /* if($logs == 1){
                $filename = '../files/EPrevious/log/eventsLogs.log';
                $hour = date("G");
                $hour -= 1;
                $now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));
                $time = $now->format("Y-m-d ".$hour.":i:s.u");
                file_put_contents($filename, $time ." La persona con nombre :   " .  $nameusu_rename. " y número de ID ". $id_user . " realizó el evento: " .  $tip_accion. " en la referencia". $num_refe. " con id de prev: ". $id_prev. " por el motivo: " . $comments. " desde la IP: ". $ip . " en la fecha: ". date("Y-m-d H:i:s") . PHP_EOL.'', FILE_APPEND);
               
                return true;
            } else {
                return false;
            }*/
        } catch (Exception $exc) {
            return false;
            
        }
    }
}
?>



