<?php
/**
 * CasaLibraries ExportToExcel
 * File ExportToExcel.php
 * Performe the exportation to Excel
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Exportation
 * @copyright  		Copyright (c) 2005-2013 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author			Jaime Santana Zaldivar
 * @version    		AdminWeb 1.0.0
 */

/**
 *
 * @see CasaLibraries_Exportation_PHPExcel
 */
require_once 'CasaLibraries/ExportationReports/PHPExcel.php';

/**
 *
 * @see CasaLibraries_Exportation_IOFactory
 */
require_once 'CasaLibraries/ExportationReports/PHPExcel/IOFactory.php';
class ExportToExcel {
    /**
     * This method build a excel file
     *
     * @param array $resultSet
     * @param array $headersArray
     * @param array $formatArray
     * @param string $file
     * @return Excel file
     */
    function exportaToExcel($resultSet, $headersArray, $formatArray, $file, $view) {
        
        /**
         * Error reporting
         */
        // error_reporting ( E_ALL );
        
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel ();
        
        // Set properties
        $objPHPExcel->getProperties ()->setCreator ( "Sistemas Casa" )->setLastModifiedBy ( "Sistemas Casa" )->setTitle ( "Office 2007 XLSX Test Document" )->setSubject ( "Office 2007 XLSX Test Document" )->setDescription ( "Test document for Office 2007 XLSX, generated using PHP classes." )->setKeywords ( "office 2007 openxml php" )->setCategory ( "Test result file" );
        
        // Creating Array of all the columns
        $columnasArray = array (
            0 => "A",
            1 => "B",
            2 => "C",
            3 => "D",
            4 => "E",
            5 => "F",
            6 => "G",
            7 => "H",
            8 => "I",
            9 => "J",
            10 => "K",
            11 => "L",
            12 => "M",
            13 => "N",
            14 => "O",
            15 => "P",
            16 => "Q",
            17 => "R",
            18 => "S",
            19 => "T",
            20 => "U",
            21 => "V",
            22 => "W",
            23 => "X",
            24 => "Y",
            25 => "Z",
            26 => "AA",
            27 => "AB",
            28 => "AC",
            29 => "AD",
            30 => "AE",
            31 => "AF",
            32 => "AG",
            33 => "AH",
            34 => "AI",
            35 => "AJ",
            36 => "AK",
            37 => "AL",
            38 => "AM",
            39 => "AN",
            40 => "AO",
            41 => "AP",
            42 => "AQ",
            43 => "AR",
            44 => "AS",
            45 => "AT",
            46 => "AU",
            47 => "AV",
            48 => "AW",
            49 => "AX",
            50 => "AY",
            51 => "AZ",
            
            52=> "BA",
            53 => "BB",
            54 => "BC",
            55 => "BD",
            56 => "BE",
            57 => "BF",
            58 => "BG",
            59 => "BH",
            60 => "BI",
            61 => "BJ",
            62 => "BK",
            63 => "BL",
            64 => "BM",
            65 => "BN",
            66 => "BO",
            67 => "BP",
            68 => "BQ",
            69 => "BR",
            70 => "BS",
            71 => "BT",
            72 => "BU",
            73 => "BV",
            74 => "BW",
            75 => "BX",
            76 => "BY",
            77 => "BZ",
            
            78 => "CA",
            79 => "CB",
            80 => "CC",
            81 => "CD",
            82 => "CE",
            83 => "CF",
            84 => "CG",
            85 => "CH",
            86 => "CI",
            87 => "CJ",
            88 => "CK",
            89 => "CL",
            90 => "CM",
            91 => "CN",
            92 => "CO",
            93 => "CP",
            94 => "CQ",
            95 => "CR",
            96 => "CS",
            97 => "CT",
            98 => "CU",
            99 => "CV",
            100 => "CW",
            101 => "CX",
            102 => "CY",
            103 => "CZ",
            
        );
        $numColumnas = count ( $headersArray ) - 1;
        
        // Building principal header
        $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( "A1", "$file" );
        $objPHPExcel->setActiveSheetIndex ( 0 )->mergeCells ( "A1:$columnasArray[$numColumnas]2" );
        $objPHPExcel->getActiveSheet ()->getStyle ( "A1:$columnasArray[$numColumnas]2" )->applyFromArray ( array (
            'fill' => array (
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array (
                    'argb' => 'ff3300'
                )
            ),
            'alignment' => array (
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'font' => array (
                'bold' => true,
                'color' => array (
                    'argb' => 'FFFFFF'
                ),
                'size' => '14'
            )
        ) );
        
        // Block of Headers
        foreach ( $headersArray as $key => $header ) {
            //var_dump($header);
            $header = str_replace("referencia","Referencia", $header);
            $header = str_replace("cliente","Cliente", $header);
            $header = str_replace("caja","Caja", $header);
            $header = str_replace("pedimento","Pedimento", $header);
            $header = str_replace("fecha de despacho","Fecha de Despacho", $header);
            $header = str_replace("transportista","Transportista", $header);
            $header = str_replace("guia","Guía", $header);
            $header = str_replace("primer reconocimiento","Primer Reconocimiento", $header);
            $header = str_replace("clave de documento","Clave de Documento", $header);
            $header = str_replace("observaciones cruce","Observaciones", $header);
            $header = str_replace("orden de compra","Orden de Compra", $header);
            $header = str_replace("peso total","Peso Total", $header);
            $header = str_replace("factura","Factura", $header);
            $header = str_replace("fecha factura","Fecha de Factura", $header);
            $header = str_replace("proveedor","Proveedor", $header);
            $header = str_replace("valor factura","Valor Factura", $header);
            
            $header = str_replace("tipo de cambio","Tipo de Cambio", $header);
            $header = str_replace("fecha de pago","Fecha de Pago", $header);
            $header = str_replace("referencia de almacen","Referencia de almacén", $header);
            $header = str_replace("fecha de entrada","Fecha de Entrada", $header);
            $header = str_replace("peso total","Peso Total", $header);
            $header = str_replace("referencia de almacen","Referencia de almacén", $header);
            $header = str_replace("numero de parte","Número de Parte", $header);
            $header = str_replace("cantidad","Cantidad", $header);
            $header = str_replace("precio unitario","Precio Unitario", $header);
            $header = str_replace("pais origen","País Origen", $header);
            $header = str_replace("observaciones","Observaciones", $header);
            $header = str_replace("descripcion","Descripción", $header);
            $header = str_replace("fraccion","Fracción", $header);
            $header = str_replace("aduana","Aduana", $header);
            $header = str_replace("patente","Patente", $header);
            $header = str_replace("clave de documento","Clave de documento", $header);
            $header = str_replace("enviado","Enviado", $header);
            $header = str_replace("vendido","Vendido", $header);
            $header = str_replace("fecha Factura","Fecha de factura", $header);
            $header = str_replace($header, ucfirst($header), $header);
            $header = str_replace("numero","número", $header);
            $header = str_replace("pais","país", $header);
            $header = str_replace("incoterm","INCOTERM", $header);
            $header = str_replace("Unidad umt","Unidad UMT", $header);
            $header = str_replace("Cantidad umt","Cantidad UMT", $header);
            $header = str_replace("Anio validacion","Año validación", $header);
            $header = str_replace("Num parte","Número de parte", $header);
            $header = str_replace("Iva","IVA", $header);
            $header = str_replace("Dta","DTA", $header);
            $header = str_replace("Adv","ADV", $header);
            $header = str_replace("Cove","COVE", $header);
            $header = str_replace("Fp cc","FP_CC", $header);
            $header = str_replace("Fp dta","FP_DTA", $header);
            $header = str_replace("Fp iva","FP_IVA", $header);
            $header = str_replace("Fp prev","FP_PREV", $header);
            $header = str_replace("Fp adv","FP_ADV", $header);
            $header = str_replace("Rfc","RFC", $header);
            $header = str_replace("Rfc","RFC", $header);
            $header = str_replace("Cons fra","Consecutivo de la fracción", $header);
            $header = str_replace("Cons part","Consecutivo de la partida", $header);
            $header = str_replace("Oc","Orden de compra", $header);
            $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( "$columnasArray[$key]3", "$header" );
            $objPHPExcel->getActiveSheet ()->getColumnDimension ( "$columnasArray[$key]" )->setVisible ( true );
            $objPHPExcel->getActiveSheet ()->getColumnDimension ( "$columnasArray[$key]" )->setAutoSize ( true );
            $objPHPExcel->getActiveSheet ()->getStyle ( "A3:$columnasArray[$numColumnas]3" )->applyFromArray ( array (
                /*'fill' => array (
                 'type' => PHPExcel_Style_Fill::FILL_SOLID,
                 'color' => array (
                 'argb' => 'D8E5F3'
                 )
                 ),*/
                'font' => array (
                    'bold' => true,
                    'color' => array (
                        'argb' => 'ff3300'
                    ),
                    'size' => '12'
                )
            ) );
        }
        //exit;
        $objPHPExcel->getActiveSheet ()->freezePane ( 'A4' );
        $objPHPExcel->getActiveSheet()->getColumnDimension ("A")->setVisible(false);
        $objPHPExcel->getActiveSheet()->getColumnDimension ("B")->setVisible(false);
        
        foreach($objPHPExcel->getActiveSheet()->getColumnDimension() as $col) {
            $col->setAutoSize(true);
        }
        $objPHPExcel->getActiveSheet()->calculateColumnWidths();
        
        //$objPHPExcel->getActiveSheet()->setCellValue('A3', 29);
        $objPHPExcel->getActiveSheet()->getStyle('L')->getNumberFormat()->setFormatCode('0000');
        foreach(range('C','O') as $columnID)
        {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        //$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(100);
        //$objPHPExcel->getRowDimension('1')->setRowHeight(-1);
        //$objPHPExcel->getActiveSheet()->getRowDimension(0)->setRowHeight(-1);
        //exit;
        header ( "Content-Type: text/plain" );
        // Add data
        $Row = 4;
        foreach ( $resultSet as $rs ) {
            foreach ( $headersArray as $key => $header ) {
                
                if(($header == 'clave cliente') || ($header == 'linea fletera') || ($header == 'oc') || ($header == 'fecha de cruce') || ($header == 'typeimporter') || ($header == 'Typeimporter') || ($header == 'idimpoexpo') || ($header == 'fecha de fin')){
                    
                    $objPHPExcel->getActiveSheet()->getColumnDimension ($columnasArray[$key])->setVisible(false);
                }
                
                if ($view == "arribospartreport" && $header == 'referencia' || ($header == 'edo cruce')){
                    $objPHPExcel->getActiveSheet()->getColumnDimension ($columnasArray[$key])->setVisible(false);
                }
                
                if ($view == "arribospartreport" && (($header == 'linea fletera') || ($header == 'oc'))){
                    $objPHPExcel->getActiveSheet()->getColumnDimension ($columnasArray[$key])->setVisible(true);
                }
                //var_dump($formatArray [$header]);
                
                switch ($formatArray [$header]) {
                    
                    case "GENERAL" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", trim ( $rs [$header] ) );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_GENERAL );
                        break;
                        
                    case "NUMBER" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER );
                        break;
                        
                    case "NUMBER_00" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00 );
                        break;
                        
                    case "DATE" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::toFormattedString('DD/MM/YYYY'));
                        break;
                        
                    case "DATETIME" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYY );
                        break;
                        
                    case "TEXT" :
                        $text = strip_tags ( $rs ["$header"] );
                        $objPHPExcel->getActiveSheet ()->setCellValueExplicit ( "$columnasArray[$key]" . "$Row", $text, PHPExcel_Cell_DataType::TYPE_STRING );
                        break;
                        
                    default :
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        break;
                }
                
                if ($Row % 2 != 0) {
                    $objPHPExcel->getActiveSheet ()->getStyle ( "A$Row:$columnasArray[$numColumnas]$Row" )->applyFromArray ( array (
                        'fill' => array (
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array (
                                'argb' => 'EFEFEF'
                            )
                        )
                    ) );
                } else {
                    $objPHPExcel->getActiveSheet ()->getStyle ( "A$Row:$columnasArray[$numColumnas]$Row" )->applyFromArray ( array (
                        'fill' => array (
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array (
                                'argb' => 'FFFFFF'
                            )
                        )
                    ) );
                }
            }
            $numColumnas = count ( $headersArray ) - 1;
            $Row ++;
        }
        
        
        // Rename sheet
        $objPHPExcel->getActiveSheet ()->setTitle ( $file );
        
        // Set active sheet index to the first sheet, so Excel opens this as the
        // first sheet
        
        
        $objPHPExcel->setActiveSheetIndex ( 0 );
        $file = str_replace(" ","",$file);
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( "Content-Disposition: attachment;filename=\"$file.xlsx\"" );
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' );
        $objWriter->save ( 'php://output' );
        exit ();
    }
    
    function exportaToExcelCtarnet($resultSet, $headersArray, $formatArray, $file, $dateHour) {
        /**
         * Error reporting
         */
        // error_reporting ( E_ALL );
        
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel ();
        // Set properties
        $objPHPExcel->getProperties ()->setCreator ( "Sistemas Casa" )->setLastModifiedBy ( "Sistemas Casa" )->setTitle ( "Office 2007 XLSX Test Document" )->setSubject ( "Office 2007 XLSX Test Document" )->setDescription ( "Test document for Office 2007 XLSX, generated using PHP classes." )->setKeywords ( "office 2007 openxml php" )->setCategory ( "Test result file" );
        
        $color = '3892D3';
        
        // Creating Array of all the columns
        $columnasArray = array (
            0 => "A",
            1 => "B",
            2 => "C",
            3 => "D",
            4 => "E",
            5 => "F",
            6 => "G",
            7 => "H",
            8 => "I",
            9 => "J",
            10 => "K",
            11 => "L",
            12 => "M",
            13 => "N",
            14 => "O",
            15 => "P",
            16 => "Q",
            17 => "R",
            18 => "S",
            19 => "T",
            20 => "U",
            21 => "V",
            22 => "W",
            23 => "X",
            24 => "Y",
            25 => "Z",
            26 => "AA",
            27 => "AB",
            28 => "AC",
            29 => "AD",
            30 => "AE",
            31 => "AF",
            32 => "AG",
            33 => "AH",
            34 => "AI",
            35 => "AJ",
            36 => "AK",
            37 => "AL",
            38 => "AM",
            39 => "AN",
            40 => "AO",
            41 => "AP",
            42 => "AQ",
            43 => "AR",
            44 => "AS",
            45 => "AT",
            46 => "AU",
            47 => "AV",
            48 => "AW",
            49 => "AX",
            50 => "AY",
            51 => "AZ",
            52 => "BA",
            53 => "BB",
            54 => "BC",
            55 => "BD",
            56 => "BE",
            57 => "BF",
            58 => "BG",
            59 => "BH",
            60 => "BI",
            61 => "BJ",
            62 => "BK",
            63 => "BL",
            64 => "BM",
            65 => "BN",
            66 => "BO",
            67 => "BP",
            68 => "BQ",
            69 => "BR",
            70 => "BS",
            71 => "BT",
            72 => "BU",
            73 => "BV",
            74 => "BW",
            75 => "BX",
            76 => "BY",
            77 => "BZ"
        );
        $numColumnas = count ( $headersArray ) - 1;
        
        $objPHPExcel->getActiveSheet ()->getStyle ( "A1:$columnasArray[$numColumnas]1" )->applyFromArray ( array (
            'alignment' => array (
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'font' => array (
                'bold' => true,
                'color' => array (
                    'argb' => '000000'
                ),
                'size' => '14'
            )
        ));
        
        $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( "A2", "Fecha y hora: ".$dateHour );
        $objPHPExcel->setActiveSheetIndex ( 0 )->mergeCells ( "A2:$columnasArray[$numColumnas]2" );
        $objPHPExcel->getActiveSheet ()->getStyle ( "A2:$columnasArray[$numColumnas]2" )->applyFromArray ( array (
            'alignment' => array (
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'font' => array (
                'bold' => true,
                'color' => array (
                    'argb' => '000000'
                ),
                'size' => '12'
            )
        ));
        
        $objPHPExcel->getActiveSheet ()->getStyle ( "A3:$columnasArray[$numColumnas]3" )->applyFromArray ( array (
            'alignment' => array (
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'font' => array (
                'bold' => true,
                'color' => array (
                    'argb' => '000000'
                ),
                'size' => '14'
            )
        ));
        
        $fileTitle = str_replace('_', ' ', $file);
        $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( "A5", "$fileTitle" );
        $objPHPExcel->setActiveSheetIndex ( 0 )->mergeCells ( "A5:$columnasArray[$numColumnas]6" );
        $objPHPExcel->getActiveSheet ()->getStyle ( "A5:$columnasArray[$numColumnas]6" )->applyFromArray ( array (
            'fill' => array (
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array (
                    'argb' => $color
                )
            ),
            'alignment' => array (
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'font' => array (
                'bold' => true,
                'color' => array (
                    'argb' => 'FFFFFF'
                ),
                'size' => '14'
            )
        ));
        
        // Block of Headers
        foreach ( $headersArray as $key => $header ) {
            $header = str_replace("Contrasenia","Contraseña", $header);
            $header = str_replace("Correo electronico","Correo electrónico", $header);
            $header = str_replace("Ocupacion","Ocupación", $header);
            
            $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( "$columnasArray[$key]7", "$header" );
            $objPHPExcel->getActiveSheet ()->getColumnDimension ( "$columnasArray[$key]" )->setVisible ( true );
            $objPHPExcel->getActiveSheet ()->getColumnDimension ( "$columnasArray[$key]" )->setAutoSize ( true );
            $objPHPExcel->getActiveSheet ()->getStyle ( "A7:$columnasArray[$numColumnas]7" )->applyFromArray ( array (
                'fill' => array (
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array (
                        'argb' => 'D8E5F3'
                    )
                ),
                'font' => array (
                    'bold' => true,
                    'color' => array (
                        'argb' => '297FD5'
                    ),
                    'size' => '12'
                )
            ) );
        }
        $objPHPExcel->getActiveSheet ()->freezePane ( 'A4' );
        
        header ( "Content-Type: text/plain" );
        // Add data
        $Row = 8;
        foreach ( $resultSet as $rs ) {
            foreach ( $headersArray as $key => $header ) {
                switch ($formatArray [$header]) {
                    case "GENERAL" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", trim ( $rs [$header] ) );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_GENERAL );
                        break;
                        
                    case "NUMBER" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER );
                        break;
                        
                    case "NUMBER_00" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER );
                        break;
                        
                    case "DATE" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY );
                        break;
                        
                    case "DATETIME" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_DATE_DATETIME );
                        break;
                        
                    case "TEXT" :
                        $text = strip_tags ( $rs ["$header"] );
                        $objPHPExcel->getActiveSheet ()->setCellValueExplicit ( "$columnasArray[$key]" . "$Row", $text, PHPExcel_Cell_DataType::TYPE_STRING );
                        break;
                        
                    default :
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        break;
                }
            }
            $numColumnas = count ( $headersArray ) - 1;
            $Row ++;
        }
        // Rename sheet
        $objPHPExcel->getActiveSheet ()->setTitle ( $file );
        
        // Set active sheet index to the first sheet, so Excel opens this as the
        // first sheet
        $objPHPExcel->setActiveSheetIndex ( 0 );
        
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( "Content-Disposition: attachment;filename=\"$file.xlsx\"" );
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' );
        $objWriter->save ( 'php://output' );
        exit ();
    }
    
    function exportaToExcelSat($resultSet, $headersArray, $formatArray, $file, $dateHour, $userLogin, $period) {
        /**
		 * Error reporting
		 */
		// error_reporting ( E_ALL );
		
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel ();
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		// Set properties
		$objPHPExcel->getProperties ()->setCreator ( "Sistemas Casa" )->setLastModifiedBy ( "Sistemas Casa" )->setTitle ( "Office 2007 XLSX Test Document" )->setSubject ( "Office 2007 XLSX Test Document" )->setDescription ( "Test document for Office 2007 XLSX, generated using PHP classes." )->setKeywords ( "office 2007 openxml php" )->setCategory ( "Test result file" );
		
                $objDrawing->setPath('./resources/images/logos-SHCP-Y-SAT2.png'); 
                $colorHeader = '36783c';
                $color = '1db54c';
                
                $objDrawing->setCoordinates('A1');
                $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
                
		// Creating Array of all the columns
		$columnasArray = array (
				0 => "A",
				1 => "B",
				2 => "C",
				3 => "D",
				4 => "E",
				5 => "F",
				6 => "G",
				7 => "H",
				8 => "I",
				9 => "J",
				10 => "K",
				11 => "L",
				12 => "M",
				13 => "N",
				14 => "O",
				15 => "P",
				16 => "Q",
				17 => "R",
				18 => "S",
				19 => "T",
				20 => "U",
				21 => "V",
				22 => "W",
				23 => "X",
				24 => "Y",
				25 => "Z",
                                26 => "AA",
                                27 => "AB",
                                28 => "AC",
                                29 => "AD",
                                30 => "AE",
                                31 => "AF",
                                32 => "AG",
                                33 => "AH",
                                34 => "AI",
                                35 => "AJ",
                                36 => "AK",
                                37 => "AL",
                                38 => "AM",
                                39 => "AN",
                                40 => "AO",
                                41 => "AP",
                                42 => "AQ",
                                43 => "AR",
                                44 => "AS",
                                45 => "AT",
                                46 => "AU",
                                47 => "AV",
                                48 => "AW",
                                49 => "AX",
                                50 => "AY",
                                51 => "AZ",
                                52 => "BA",
                                53 => "BB",
                                54 => "BC",
                                55 => "BD",
                                56 => "BE",
                                57 => "BF",
                                58 => "BG",
                                59 => "BH",
                                60 => "BI",
                                61 => "BJ",
                                62 => "BK",
                                63 => "BL",
                                64 => "BM",
                                65 => "BN",
                                66 => "BO",
                                67 => "BP",
                                68 => "BQ",
                                69 => "BR",
                                70 => "BS",
                                71 => "BT",
                                72 => "BU",
                                73 => "BV",
                                74 => "BW",
                                75 => "BX",
                                76 => "BY",
                                77 => "BZ"
		);
		$numColumnas = count ( $headersArray ) - 1;
		
                $objPHPExcel->getActiveSheet ()->getStyle ( "A1:$columnasArray[$numColumnas]1" )->applyFromArray ( array (
				'fill' => array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array (
								'argb' => $colorHeader 
						) 
				),
				'alignment' => array (
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER 
				),
				'font' => array (
						'bold' => true,
						'color' => array (
								'argb' => '000000' 
						),
						'size' => '14' 
				) 
		));
                
                $objPHPExcel->getActiveSheet ()->getStyle ( "A2:$columnasArray[$numColumnas]2" )->applyFromArray ( array (
				'fill' => array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array (
								'argb' => $colorHeader 
						) 
				),
				'alignment' => array (
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER 
				),
				'font' => array (
						'bold' => true,
						'color' => array (
								'argb' => '000000' 
						),
						'size' => '14' 
				) 
		));
                
                $objPHPExcel->getActiveSheet ()->getStyle ( "A3:$columnasArray[$numColumnas]3" )->applyFromArray ( array (
				'fill' => array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array (
								'argb' => $colorHeader 
						) 
				),
				'alignment' => array (
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER 
				),
				'font' => array (
						'bold' => true,
						'color' => array (
								'argb' => '000000' 
						),
						'size' => '14' 
				) 
		));
                
		// Building principal header
                $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( "B1", " Usuario: ".$userLogin );
		$objPHPExcel->setActiveSheetIndex ( 0 )->mergeCells ( "B1:$columnasArray[$numColumnas]1" );
		$objPHPExcel->getActiveSheet ()->getStyle ( "B1:$columnasArray[$numColumnas]1" )->applyFromArray ( array (
				'fill' => array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array (
								'argb' => $colorHeader 
						) 
				),
				'alignment' => array (
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER 
				),
				'font' => array (
						'bold' => true,
						'color' => array (
								'argb' => 'FFFFFF' 
						),
						'size' => '12',
                                                'name' => 'Soberana Sans Light'
				) 
		));
                
                $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( "B2", " Fecha y hora: ".$dateHour );
		$objPHPExcel->setActiveSheetIndex ( 0 )->mergeCells ( "B2:$columnasArray[$numColumnas]2" );
		$objPHPExcel->getActiveSheet ()->getStyle ( "B2:$columnasArray[$numColumnas]2" )->applyFromArray ( array (
				'fill' => array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array (
								'argb' => $colorHeader 
						) 
				),
				'alignment' => array (
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER 
				),
				'font' => array (
						'bold' => true,
						'color' => array (
								'argb' => 'FFFFFF' 
						),
						'size' => '12',
                                                'name' => 'Soberana Sans Light'
				) 
		));
                
                $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( "B3", " Periodo: ".$period );
		$objPHPExcel->setActiveSheetIndex ( 0 )->mergeCells ( "B3:$columnasArray[$numColumnas]3" );
		$objPHPExcel->getActiveSheet ()->getStyle ( "B3:$columnasArray[$numColumnas]3" )->applyFromArray ( array (
				'fill' => array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array (
								'argb' => $colorHeader 
						) 
				),
				'alignment' => array (
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER 
				),
				'font' => array (
						'bold' => true,
						'color' => array (
								'argb' => 'FFFFFF' 
						),
						'size' => '12',
                                                'name' => 'Soberana Sans Light'
				) 
		));
                
		$objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( "A5", "Reporte de Consultas de Usuarios" );
		$objPHPExcel->setActiveSheetIndex ( 0 )->mergeCells ( "A5:$columnasArray[$numColumnas]6" );
		$objPHPExcel->getActiveSheet ()->getStyle ( "A5:$columnasArray[$numColumnas]6" )->applyFromArray ( array (
				'fill' => array (
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array (
								'argb' => $color 
						) 
				),
				'alignment' => array (
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER 
				),
				'font' => array (
						'bold' => true,
						'color' => array (
								'argb' => 'FFFFFF' 
						),
						'size' => '14',
                                                'name' => 'Soberana Sans Light'
				) 
		));
                
		// Block of Headers
		foreach ( $headersArray as $key => $header ) {
                    $header = str_replace("userLogin","Usuario                       ", $header);
                    $header = str_replace("userName","Nombre de Usuario", $header);
                    $header = str_replace("consultationTypeName","Consulta", $header);
                    $header = str_replace("consultationType","Tipo de búsqueda", $header);
                    $header = str_replace("consultationResponseTime","Tiempo de respuesta a la consulta (s)", $header);
                    $header = str_replace("consultationBeginDate","Fecha y hora de inicio de la consulta", $header);
                    $header = str_replace("sesionLogEndDate","Fecha y hora de término de la consulta", $header);
                    $header = str_replace("consultationDuration","Duración de la consulta (s)", $header);
                    
                    $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( "$columnasArray[$key]7", "$header" );
                    $objPHPExcel->getActiveSheet ()->getColumnDimension ( "$columnasArray[$key]" )->setVisible ( true );
                    $objPHPExcel->getActiveSheet ()->getColumnDimension ( "$columnasArray[$key]" )->setAutoSize ( true );
                    $objPHPExcel->getActiveSheet ()->getStyle ( "A7:$columnasArray[$numColumnas]7" )->applyFromArray ( array (
                                    'fill' => array (
                                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                                    'color' => array (
                                                                    'argb' => 'd7e4bd' 
                                                    ) 
                                    ),
                                    'font' => array (
                                                    'bold' => true,
                                                    'color' => array (
                                                                    'argb' => '00b050' 
                                                    ),
                                                    'size' => '12',
                                                    'name' => 'Soberana Sans Light'
                                    ) 
                    ) );
		}
		$objPHPExcel->getActiveSheet ()->freezePane ( 'A4' );
		
		header ( "Content-Type: text/plain" );
		// Add data
		$Row = 8;
		foreach ( $resultSet as $rs ) {
			foreach ( $headersArray as $key => $header ) {
				
				switch ($formatArray [$header]) {
					case "GENERAL" :
						PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
						$objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", trim ( $rs [$header] ) );
						$objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_GENERAL );
                                                $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->applyFromArray ( array (
                                                    'font' => array (
                                                                    'size' => '12',
                                                                    'name' => 'Soberana Sans Light'
                                                    ) 
                                                ) );
						break;
					
					case "NUMBER" :
						PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
						$objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
						$objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER );
						$objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->applyFromArray ( array (
                                                    'font' => array (
                                                                    'size' => '12',
                                                                    'name' => 'Soberana Sans Light'
                                                    ) 
                                                ) );
                                                break;
					
					case "NUMBER_00" :
						PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
						$objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
						$objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00 );
						$objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->applyFromArray ( array (
                                                    'font' => array (
                                                                    'size' => '12',
                                                                    'name' => 'Soberana Sans Light'
                                                    ) 
                                                ) );
                                                break;
					
					case "DATE" :
						PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
						$objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
						$objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY );
						$objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->applyFromArray ( array (
                                                    'font' => array (
                                                                    'size' => '12',
                                                                    'name' => 'Soberana Sans Light'
                                                    ) 
                                                ) );
                                                break;
					
					case "DATETIME" :
						PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
						$objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
						$objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_DATE_DATETIME );
						$objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->applyFromArray ( array (
                                                    'font' => array (
                                                                    'size' => '12',
                                                                    'name' => 'Soberana Sans Light'
                                                    ) 
                                                ) );
                                                break;
					
					case "TEXT" :
						$text = strip_tags ( $rs ["$header"] );
						$objPHPExcel->getActiveSheet ()->setCellValueExplicit ( "$columnasArray[$key]" . "$Row", $text, PHPExcel_Cell_DataType::TYPE_STRING );
						$objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->applyFromArray ( array (
                                                    'font' => array (
                                                                    'size' => '12',
                                                                    'name' => 'Soberana Sans Light'
                                                    ) 
                                                ) );
                                                break;
					
					default :
						$objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
						$objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->applyFromArray ( array (
                                                    'font' => array (
                                                                    'size' => '12',
                                                                    'name' => 'Soberana Sans Light'
                                                    ) 
                                                ) );
                                                break;
				}
			}
			$numColumnas = count ( $headersArray ) - 1;
			$Row ++;
		}
		// Rename sheet
		$objPHPExcel->getActiveSheet ()->setTitle ( $file );
		
		// Set active sheet index to the first sheet, so Excel opens this as the
		// first sheet
		$objPHPExcel->setActiveSheetIndex ( 0 );
		$file = str_replace(" ","",$file);
		header ( 'Content-Type: application/vnd.ms-excel' );
		header ( "Content-Disposition: attachment;filename=\"$file.xlsx\"" );
		header ( 'Cache-Control: max-age=0' );
		$objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' );
		$objWriter->save ( 'php://output' );
		exit ();
    }
    
    /**
     * This method build a excel file with pending folios
     *
     * @param array $resultSet
     * @param array $headersArray
     * @param array $formatArray
     * @param array $resultSet2
     * @param array $headersArray2
     * @param array $formatArray2
     * @param string $file
     * @return Excel file
     */
    public function exportToExcelPendingFolios($resultSet, $headersArray, $formatArray, $resultSet2, $headersArray2, $formatArray2, $file) {
        
        /**
         * Error reporting
         */
        error_reporting ( E_ALL );
        
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel ();
        
        // Set properties
        $objPHPExcel->getProperties ()->setCreator ( "Sistemas Casa" )->setLastModifiedBy ( "Sistemas Casa" )->setTitle ( "Office 2007 XLSX Test Document" )->setSubject ( "Office 2007 XLSX Test Document" )->setDescription ( "Test document for Office 2007 XLSX, generated using PHP classes." )->setKeywords ( "office 2007 openxml php" )->setCategory ( "Test result file" );
        
        // Creating Array of all the columns
        $columnasArray = array (
            0 => "A",
            1 => "B",
            2 => "C",
            3 => "D",
            4 => "E",
            5 => "F",
            6 => "G",
            7 => "H",
            8 => "I",
            9 => "J",
            10 => "K",
            11 => "L",
            12 => "M",
            13 => "N",
            14 => "O",
            15 => "P",
            16 => "Q",
            17 => "R",
            18 => "S",
            19 => "T",
            20 => "U",
            21 => "V",
            22 => "W",
            23 => "X",
            24 => "Y",
            25 => "Z"
        );
        $numColumnas = count ( $headersArray ) - 1;
        
        // Building principal header
        $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( "A1", "$file" );
        $objPHPExcel->setActiveSheetIndex ( 0 )->mergeCells ( "A1:$columnasArray[$numColumnas]2" );
        $objPHPExcel->getActiveSheet ()->getStyle ( "A1:$columnasArray[$numColumnas]3" )->applyFromArray ( array (
            'fill' => array (
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array (
                    'argb' => '0000FF'
                )
            ),
            'alignment' => array (
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'font' => array (
                'bold' => true,
                'color' => array (
                    'argb' => 'FFFFFF'
                ),
                'size' => '14'
            )
        ) );
        
        // Date of generation
        $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( $columnasArray [$numColumnas] . "3", "Fecha: " . date ( "d/m/Y" ) );
        $objPHPExcel->getActiveSheet ()->getStyle ( $columnasArray [$numColumnas] . "3" )->applyFromArray ( array (
            'alignment' => array (
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
            ),
            'font' => array (
                'size' => '10'
            )
        ) );
        $objPHPExcel->getActiveSheet ()->freezePane ( 'A5' );
        
        // Block of Headers
        foreach ( $headersArray as $key => $header ) {
            $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( "$columnasArray[$key]4", "$header" );
            $objPHPExcel->getActiveSheet ()->getColumnDimension ( "$columnasArray[$key]" )->setVisible ( true );
            $objPHPExcel->getActiveSheet ()->getColumnDimension ( "$columnasArray[$key]" )->setAutoSize ( true );
            $objPHPExcel->getActiveSheet ()->getStyle ( "A4:$columnasArray[$numColumnas]4" )->applyFromArray ( array (
                'fill' => array (
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array (
                        'argb' => 'D8E5F3'
                    )
                ),
                'font' => array (
                    'bold' => true,
                    'color' => array (
                        'argb' => '297FD5'
                    ),
                    'size' => '12'
                )
            ) );
        }
        
        header ( "Content-Type: text/plain" );
        // Add data
        $Row = 5;
        
        $lastFolio = "";
        foreach ( $resultSet as $rs ) {
            foreach ( $headersArray as $key => $header ) {
                
                switch ($formatArray [$header]) {
                    case "GENERAL" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_GENERAL );
                        break;
                        
                    case "NUMBER" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER );
                        break;
                        
                    case "NUMBER_00" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00 );
                        break;
                        
                    case "DATE" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY );
                        break;
                        
                    case "DATETIME" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_DATE_DATETIME );
                        break;
                        
                    case "TEXT" :
                        $content = strip_tags ( $rs ["$header"] );
                        
                        if ($header == "Folio de Registro" || $header == "Folio de Factura") {
                            if ($lastFolio == "") {
                                $content = $rs ["$header"];
                            } else {
                                if ($lastFolio == $rs ["$header"])
                                    $content = "";
                            }
                            
                            $lastFolio = $rs ["$header"];
                        }
                        
                        $objPHPExcel->getActiveSheet ()->setCellValueExplicit ( "$columnasArray[$key]" . "$Row", $content, PHPExcel_Cell_DataType::TYPE_STRING );
                        if ($header == "Folio de Registro" && $content != "") {
                            $objPHPExcel->getActiveSheet ()->getStyle ( "A$Row:$columnasArray[$numColumnas]$Row" )->applyFromArray ( array (
                                'fill' => array (
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array (
                                        'argb' => 'EFEFEF'
                                    )
                                ),
                                'font' => array (
                                    'size' => '12'
                                )
                            ) );
                            
                            $Row ++;
                        } else {
                            $objPHPExcel->getActiveSheet ()->getStyle ( "A$Row:$columnasArray[$numColumnas]$Row" )->applyFromArray ( array (
                                'fill' => array (
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array (
                                        'argb' => 'FFFFFF'
                                    )
                                )
                            ) );
                        }
                        break;
                        
                    default :
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        break;
                }
                
                if ($Row % 2 == 0) {
                    $objPHPExcel->getActiveSheet ()->getStyle ( "A$Row:$columnasArray[$numColumnas]$Row" )->applyFromArray ( array (
                        'fill' => array (
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array (
                                'argb' => 'EFEFEF'
                            )
                        )
                    ) );
                } else {
                    $objPHPExcel->getActiveSheet ()->getStyle ( "A$Row:$columnasArray[$numColumnas]$Row" )->applyFromArray ( array (
                        'fill' => array (
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array (
                                'argb' => 'FFFFFF'
                            )
                        )
                    ) );
                }
            }
            $numColumnas = count ( $headersArray ) - 1;
            $Row ++;
        }
        // Rename sheet
        $objPHPExcel->getActiveSheet ()->setTitle ( "Folios" );
        
        $objPHPExcel->createSheet ( 1 );
        $objPHPExcel->setActiveSheetIndex ( 1 );
        $objPHPExcel->getActiveSheet ()->setTitle ( 'Referencias' );
        
        $numColumnas = count ( $headersArray2 ) - 1;
        
        // Building principal header
        
        $objPHPExcel->setActiveSheetIndex ( 1 )->setCellValue ( "A1", "$file" );
        $objPHPExcel->setActiveSheetIndex ( 1 )->mergeCells ( "A1:$columnasArray[$numColumnas]2" );
        $objPHPExcel->getActiveSheet ()->getStyle ( "A1:$columnasArray[$numColumnas]3" )->applyFromArray ( array (
            'fill' => array (
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array (
                    'argb' => '0000FF'
                )
            ),
            'alignment' => array (
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'font' => array (
                'bold' => true,
                'color' => array (
                    'argb' => 'FFFFFF'
                ),
                'size' => '14'
            )
        ) );
        
        // Date of generation
        $objPHPExcel->setActiveSheetIndex ( 1 )->setCellValue ( $columnasArray [$numColumnas] . "3", "Fecha: " . date ( "d/m/Y" ) );
        $objPHPExcel->getActiveSheet ()->getStyle ( $columnasArray [$numColumnas] . "3" )->applyFromArray ( array (
            'alignment' => array (
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
            ),
            'font' => array (
                'size' => '10'
            )
        ) );
        
        // Block of Headers
        foreach ( $headersArray2 as $key => $header ) {
            $objPHPExcel->setActiveSheetIndex ( 1 )->setCellValue ( "$columnasArray[$key]4", "$header" );
            $objPHPExcel->getActiveSheet ()->getColumnDimension ( "$columnasArray[$key]" )->setVisible ( true );
            $objPHPExcel->getActiveSheet ()->getColumnDimension ( "$columnasArray[$key]" )->setAutoSize ( true );
            $objPHPExcel->getActiveSheet ()->getStyle ( "A4:$columnasArray[$numColumnas]4" )->applyFromArray ( array (
                'fill' => array (
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array (
                        'argb' => 'D8E5F3'
                    )
                ),
                'font' => array (
                    'bold' => true,
                    'color' => array (
                        'argb' => '297FD5'
                    ),
                    'size' => '12'
                )
            ) );
        }
        
        header ( "Content-Type: text/plain" );
        // Add data
        $Row = 5;
        
        $lastFolio = "";
        foreach ( $resultSet2 as $rs ) {
            
            foreach ( $headersArray2 as $key => $header ) {
                
                switch ($formatArray2 [$header]) {
                    case "GENERAL" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_GENERAL );
                        break;
                        
                    case "NUMBER" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER );
                        break;
                        
                    case "NUMBER_00" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00 );
                        break;
                        
                    case "DATE" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY );
                        break;
                        
                    case "DATETIME" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_DATE_DATETIME );
                        break;
                        
                    case "TEXT" :
                        
                        $content = $rs ["$header"];
                        
                        if ($header == "Folio de Solicitud" || $header == "Folio de Factura") {
                            if ($lastFolio == "") {
                                $content = $rs ["$header"];
                            } else {
                                if ($lastFolio == $rs ["$header"])
                                    $content = "";
                            }
                            
                            $lastFolio = $rs ["$header"];
                        }
                        
                        $objPHPExcel->getActiveSheet ()->setCellValueExplicit ( "$columnasArray[$key]" . "$Row", $content, PHPExcel_Cell_DataType::TYPE_STRING );
                        if (($header == "Folio de Solicitud" || $header == "Folio de Factura") && $content != "") {
                            $objPHPExcel->getActiveSheet ()->getStyle ( "A$Row:$columnasArray[$numColumnas]$Row" )->applyFromArray ( array (
                                'fill' => array (
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array (
                                        'argb' => 'EFEFEF'
                                    )
                                ),
                                'font' => array (
                                    'size' => '12'
                                )
                            ) );
                            
                            $Row ++;
                        } else {
                            $objPHPExcel->getActiveSheet ()->getStyle ( "A$Row:$columnasArray[$numColumnas]$Row" )->applyFromArray ( array (
                                'fill' => array (
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array (
                                        'argb' => 'FFFFFF'
                                    )
                                )
                            ) );
                            $objPHPExcel->getActiveSheet ()->getRowDimension ( $Row )->setOutlineLevel ( 1 );
                            $objPHPExcel->getActiveSheet ()->getRowDimension ( $Row )->setCollapsed ( true );
                            $objPHPExcel->getActiveSheet ()->getRowDimension ( $Row )->setVisible ( true );
                            $objPHPExcel->getActiveSheet ()->freezePane ( 'A5' );
                        }
                        
                        break;
                        
                    default :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_GENERAL );
                        break;
                }
            }
            $numColumnas = count ( $headersArray2 ) - 1;
            $Row ++;
        }
        
        // Set active sheet index to the first sheet, so Excel opens this as the
        // first sheet
        $objPHPExcel->setActiveSheetIndex ( 0 );
        
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( "Content-Disposition: attachment;filename=\"$file.xlsx\"" );
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' );
        $objWriter->save ( 'php://output' );
        exit ();
    }
    
    /**
     * This method build a excel file with paid folios
     *
     * @param array $resultSet
     * @param array $headersArray
     * @param array $formatArray
     * @param array $resultSet2
     * @param array $headersArray2
     * @param array $formatArray2
     * @param string $file
     * @return Excel file
     */
    public function exportToExcelPaidFolios($resultSet, $headersArray, $formatArray, $resultSet2, $headersArray2, $formatArray2, $file) {
        
        /**
         * Error reporting
         */
        error_reporting ( E_ALL );
        
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel ();
        
        // Set properties
        $objPHPExcel->getProperties ()->setCreator ( "Sistemas Casa" )->setLastModifiedBy ( "Sistemas Casa" )->setTitle ( "Office 2007 XLSX Test Document" )->setSubject ( "Office 2007 XLSX Test Document" )->setDescription ( "Test document for Office 2007 XLSX, generated using PHP classes." )->setKeywords ( "office 2007 openxml php" )->setCategory ( "Test result file" );
        
        // Creating Array of all the columns
        $columnasArray = array (
            0 => "A",
            1 => "B",
            2 => "C",
            3 => "D",
            4 => "E",
            5 => "F",
            6 => "G",
            7 => "H",
            8 => "I",
            9 => "J",
            10 => "K",
            11 => "L",
            12 => "M",
            13 => "N",
            14 => "O",
            15 => "P",
            16 => "Q",
            17 => "R",
            18 => "S",
            19 => "T",
            20 => "U",
            21 => "V",
            22 => "W",
            23 => "X",
            24 => "Y",
            25 => "Z"
        );
        $numColumnas = count ( $headersArray ) - 1;
        
        // Building principal header
        $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( "A1", "$file" );
        $objPHPExcel->setActiveSheetIndex ( 0 )->mergeCells ( "A1:$columnasArray[$numColumnas]2" );
        $objPHPExcel->getActiveSheet ()->getStyle ( "A1:$columnasArray[$numColumnas]3" )->applyFromArray ( array (
            'fill' => array (
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array (
                    'argb' => '0000FF'
                )
            ),
            'alignment' => array (
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'font' => array (
                'bold' => true,
                'color' => array (
                    'argb' => 'FFFFFF'
                ),
                'size' => '14'
            )
        ) );
        
        // Date of generation
        $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( $columnasArray [$numColumnas] . "3", "Fecha: " . date ( "d/m/Y" ) );
        $objPHPExcel->getActiveSheet ()->getStyle ( $columnasArray [$numColumnas] . "3" )->applyFromArray ( array (
            'alignment' => array (
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
            ),
            'font' => array (
                'size' => '10'
            )
        ) );
        $objPHPExcel->getActiveSheet ()->freezePane ( 'A5' );
        
        // Block of Headers
        foreach ( $headersArray as $key => $header ) {
            $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( "$columnasArray[$key]4", "$header" );
            $objPHPExcel->getActiveSheet ()->getColumnDimension ( "$columnasArray[$key]" )->setVisible ( true );
            $objPHPExcel->getActiveSheet ()->getColumnDimension ( "$columnasArray[$key]" )->setAutoSize ( true );
            $objPHPExcel->getActiveSheet ()->getStyle ( "A4:$columnasArray[$numColumnas]4" )->applyFromArray ( array (
                'fill' => array (
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array (
                        'argb' => 'D8E5F3'
                    )
                ),
                'font' => array (
                    'bold' => true,
                    'color' => array (
                        'argb' => '297FD5'
                    ),
                    'size' => '12'
                )
            ) );
        }
        
        header ( "Content-Type: text/plain" );
        // Add data
        $Row = 5;
        
        $lastFolio = "";
        foreach ( $resultSet as $rs ) {
            foreach ( $headersArray as $key => $header ) {
                
                switch ($formatArray [$header]) {
                    case "GENERAL" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_GENERAL );
                        break;
                        
                    case "NUMBER" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER );
                        break;
                        
                    case "NUMBER_00" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00 );
                        break;
                        
                    case "DATE" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY );
                        break;
                        
                    case "DATETIME" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_DATE_DATETIME );
                        break;
                        
                    case "TEXT" :
                        $content = strip_tags ( $rs ["$header"] );
                        
                        if ($header == "Folio de Registro") {
                            if ($lastFolio == "") {
                                $content = $rs ["$header"];
                            } else {
                                if ($lastFolio == $rs ["$header"])
                                    $content = "";
                            }
                            
                            $lastFolio = $rs ["$header"];
                        }
                        
                        $objPHPExcel->getActiveSheet ()->setCellValueExplicit ( "$columnasArray[$key]" . "$Row", $content, PHPExcel_Cell_DataType::TYPE_STRING );
                        if ($header == "Folio de Registro" && $content != "") {
                            $objPHPExcel->getActiveSheet ()->getStyle ( "A$Row:$columnasArray[$numColumnas]$Row" )->applyFromArray ( array (
                                'fill' => array (
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array (
                                        'argb' => 'EFEFEF'
                                    )
                                ),
                                'font' => array (
                                    'size' => '12'
                                )
                            ) );
                            
                            $Row ++;
                        } else {
                            $objPHPExcel->getActiveSheet ()->getStyle ( "A$Row:$columnasArray[$numColumnas]$Row" )->applyFromArray ( array (
                                'fill' => array (
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array (
                                        'argb' => 'FFFFFF'
                                    )
                                )
                            ) );
                            $objPHPExcel->getActiveSheet ()->getRowDimension ( $Row )->setOutlineLevel ( 1 );
                            $objPHPExcel->getActiveSheet ()->getRowDimension ( $Row )->setCollapsed ( true );
                            $objPHPExcel->getActiveSheet ()->getRowDimension ( $Row )->setVisible ( true );
                            $objPHPExcel->getActiveSheet ()->freezePane ( 'A5' );
                        }
                        break;
                        
                    default :
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        break;
                }
                
                $objPHPExcel->getActiveSheet ()->getStyle ( "A$Row:$columnasArray[$numColumnas]$Row" )->applyFromArray ( array (
                    'fill' => array (
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array (
                            'argb' => 'FFFFFF'
                        )
                    )
                ) );
            }
            $numColumnas = count ( $headersArray ) - 1;
            $Row ++;
        }
        // Rename sheet
        $objPHPExcel->getActiveSheet ()->setTitle ( "Folios" );
        
        $objPHPExcel->createSheet ( 1 );
        $objPHPExcel->setActiveSheetIndex ( 1 );
        $objPHPExcel->getActiveSheet ()->setTitle ( 'Referencias' );
        
        $numColumnas = count ( $headersArray2 ) - 1;
        
        // Building principal header
        
        $objPHPExcel->setActiveSheetIndex ( 1 )->setCellValue ( "A1", "$file" );
        $objPHPExcel->setActiveSheetIndex ( 1 )->mergeCells ( "A1:$columnasArray[$numColumnas]2" );
        $objPHPExcel->getActiveSheet ()->getStyle ( "A1:$columnasArray[$numColumnas]3" )->applyFromArray ( array (
            'fill' => array (
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array (
                    'argb' => '0000FF'
                )
            ),
            'alignment' => array (
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'font' => array (
                'bold' => true,
                'color' => array (
                    'argb' => 'FFFFFF'
                ),
                'size' => '14'
            )
        ) );
        
        // Date of generation
        $objPHPExcel->setActiveSheetIndex ( 1 )->setCellValue ( $columnasArray [$numColumnas] . "3", "Fecha: " . date ( "d/m/Y" ) );
        $objPHPExcel->getActiveSheet ()->getStyle ( $columnasArray [$numColumnas] . "3" )->applyFromArray ( array (
            'alignment' => array (
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
            ),
            'font' => array (
                'size' => '10'
            )
        ) );
        
        // Block of Headers
        foreach ( $headersArray2 as $key => $header ) {
            $objPHPExcel->setActiveSheetIndex ( 1 )->setCellValue ( "$columnasArray[$key]4", "$header" );
            $objPHPExcel->getActiveSheet ()->getColumnDimension ( "$columnasArray[$key]" )->setVisible ( true );
            $objPHPExcel->getActiveSheet ()->getColumnDimension ( "$columnasArray[$key]" )->setAutoSize ( true );
            $objPHPExcel->getActiveSheet ()->getStyle ( "A4:$columnasArray[$numColumnas]4" )->applyFromArray ( array (
                'fill' => array (
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array (
                        'argb' => 'D8E5F3'
                    )
                ),
                'font' => array (
                    'bold' => true,
                    'color' => array (
                        'argb' => '297FD5'
                    ),
                    'size' => '12'
                )
            ) );
        }
        
        header ( "Content-Type: text/plain" );
        // Add data
        $Row = 5;
        
        $lastFolio = "";
        $lastRecord = "";
        foreach ( $resultSet2 as $rs ) {
            
            foreach ( $headersArray2 as $key => $header ) {
                
                switch ($formatArray2 [$header]) {
                    case "GENERAL" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_GENERAL );
                        break;
                        
                    case "NUMBER" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER );
                        break;
                        
                    case "NUMBER_00" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00 );
                        break;
                        
                    case "DATE" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY );
                        break;
                        
                    case "DATETIME" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_DATE_DATETIME );
                        break;
                        
                    case "TEXT" :
                        
                        $content = $rs ["$header"];
                        
                        if ($header == "Folio de Registro") {
                            if ($lastRecord == "") {
                                $content = $rs ["$header"];
                            } else {
                                if ($lastRecord == $rs ["$header"])
                                    $content = "";
                            }
                            
                            $lastRecord = $rs ["$header"];
                        }
                        
                        if ($header == "Folio de Solicitud" || $header == "Folio de Factura") {
                            if ($lastFolio == "") {
                                $content = $rs ["$header"];
                            } else {
                                if ($lastFolio == $rs ["$header"])
                                    $content = "";
                            }
                            
                            $lastFolio = $rs ["$header"];
                        }
                        
                        $objPHPExcel->getActiveSheet ()->setCellValueExplicit ( "$columnasArray[$key]" . "$Row", $content, PHPExcel_Cell_DataType::TYPE_STRING );
                        
                        if ($header == "Folio de Registro" && $content != "") {
                            $objPHPExcel->getActiveSheet ()->getStyle ( "A$Row:$columnasArray[$numColumnas]$Row" )->applyFromArray ( array (
                                'fill' => array (
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array (
                                        'argb' => 'E6E6FA'
                                    )
                                ),
                                'font' => array (
                                    'size' => '12'
                                )
                            ) );
                            
                            $Row ++;
                        }
                        
                        if (($header == "Folio de Solicitud" || $header == "Folio de Factura") && $content != "") {
                            $objPHPExcel->getActiveSheet ()->getStyle ( "A$Row:$columnasArray[$numColumnas]$Row" )->applyFromArray ( array (
                                'fill' => array (
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array (
                                        'argb' => 'EFEFEF'
                                    )
                                ),
                                'font' => array (
                                    'size' => '12'
                                )
                            ) );
                            
                            $Row ++;
                        } else {
                            $objPHPExcel->getActiveSheet ()->getStyle ( "A$Row:$columnasArray[$numColumnas]$Row" )->applyFromArray ( array (
                                'fill' => array (
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array (
                                        'argb' => 'FFFFFF'
                                    )
                                )
                            ) );
                            $objPHPExcel->getActiveSheet ()->getRowDimension ( $Row )->setOutlineLevel ( 1 );
                            $objPHPExcel->getActiveSheet ()->getRowDimension ( $Row )->setCollapsed ( true );
                            $objPHPExcel->getActiveSheet ()->getRowDimension ( $Row )->setVisible ( true );
                            $objPHPExcel->getActiveSheet ()->freezePane ( 'A5' );
                        }
                        
                        break;
                        
                    default :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_GENERAL );
                        break;
                }
            }
            $numColumnas = count ( $headersArray2 ) - 1;
            $Row ++;
        }
        
        // Set active sheet index to the first sheet, so Excel opens this as the
        // first sheet
        $objPHPExcel->setActiveSheetIndex ( 0 );
        
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( "Content-Disposition: attachment;filename=\"$file.xlsx\"" );
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' );
        $objWriter->save ( 'php://output' );
        exit ();
    }
    
    /**
     * This method build a excel file
     *
     * @param array $resultSet
     * @param array $headersArray
     * @param array $formatArray
     * @param string $file
     * @return Excel file
     */
    function createCbodegaReportExcel($resultSet, $headersArray, $formatArray, $file) {
        
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel ();
        
        // Set properties
        $objPHPExcel->getProperties ()->setCreator ( "Sistemas Casa" )->setLastModifiedBy ( "Sistemas Casa" )->setTitle ( "Office 2007 XLSX Test Document" )->setSubject ( "Office 2007 XLSX Test Document" )->setDescription ( "Test document for Office 2007 XLSX, generated using PHP classes." )->setKeywords ( "office 2007 openxml php" )->setCategory ( "Test result file" );
        
        // Creating Array of all the columns
        $columnasArray = array (
            0 => "A",
            1 => "B",
            2 => "C",
            3 => "D",
            4 => "E",
            5 => "F",
            6 => "G",
            7 => "H",
            8 => "I",
            9 => "J",
            10 => "K",
            11 => "L",
            12 => "M",
            13 => "N",
            14 => "O",
            15 => "P",
            16 => "Q",
            17 => "R",
            18 => "S",
            19 => "T",
            20 => "U",
            21 => "V",
            22 => "W",
            23 => "X",
            24 => "Y",
            25 => "Z",
            26 => "AA",
            27 => "AB",
            28 => "AC",
            29 => "AD",
            30 => "AE",
            31 => "AF",
            32 => "AG",
            33 => "AH",
            34 => "AI",
            35 => "AJ",
            36 => "AK",
            37 => "AL",
            38 => "AM",
            39 => "AN",
            40 => "AO",
            41 => "AP",
            42 => "AQ",
            43 => "AR",
            44 => "AS",
            45 => "AT",
            46 => "AU",
            47 => "AV",
            48 => "AW",
            49 => "AX",
            50 => "AY",
            51 => "AZ",
            52 => "BA",
            53 => "BB",
            54 => "BC",
            55 => "BD",
            56 => "BE",
            57 => "BF",
            58 => "BG",
            59 => "BH",
            60 => "BI",
            61 => "BJ",
            62 => "BK",
            63 => "BL",
            64 => "BM",
            65 => "BN",
            66 => "BO",
            67 => "BP",
            68 => "BQ",
            69 => "BR",
            70 => "BS",
            71 => "BT",
            72 => "BU",
            73 => "BV",
            74 => "BW",
            75 => "BX",
            76 => "BY",
            77 => "BZ"
        );
        
        $counter = 0;
        
        foreach ($resultSet as $principalHeader => $sections) {
            $numColumnas = count ( $headersArray[$principalHeader] ) - 1;
            $objPHPExcel->setActiveSheetIndex ( $counter );
            
            // Building principal header
            $objPHPExcel->setActiveSheetIndex ( $counter )->setCellValue ( "A1", "$principalHeader" );
            $objPHPExcel->setActiveSheetIndex ( $counter )->mergeCells ( "A1:$columnasArray[$numColumnas]2" );
            $objPHPExcel->getActiveSheet ()->getStyle ( "A1:$columnasArray[$numColumnas]2" )->applyFromArray ( array (
                'fill' => array (
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array (
                        'argb' => 'ff3300'
                    )
                ),
                'alignment' => array (
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                ),
                'font' => array (
                    'bold' => true,
                    'color' => array (
                        'argb' => 'FFFFFF'
                    ),
                    'size' => '14'
                )
            ) );
            
            // Block of Headers
            foreach ( $headersArray[$principalHeader] as $key => $header ) {
                $header = str_replace("á", "Á", $header);
                $header = str_replace("é", "É", $header);
                $header = str_replace("í", "Í", $header);
                $header = str_replace("ó", "Ó", $header);
                $header = str_replace("ú", "Ú", $header);
                
                $objPHPExcel->setActiveSheetIndex ( $counter )->setCellValue ( "$columnasArray[$key]3", strtoupper(str_replace("_", " ", "$header" )));
                $objPHPExcel->getActiveSheet ()->getColumnDimension ( "$columnasArray[$key]" )->setVisible ( true );
                $objPHPExcel->getActiveSheet ()->getColumnDimension ( "$columnasArray[$key]" )->setAutoSize ( true );
                $objPHPExcel->getActiveSheet ()->getStyle ( "A3:$columnasArray[$numColumnas]3" )->applyFromArray ( array (
                    'fill' => array (
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array (
                            'argb' => 'D8E5F3'
                        )
                    ),
                    'font' => array (
                        'bold' => true,
                        'color' => array (
                            'argb' => 'ff3300'
                        ),
                        'size' => '12'
                    )
                ) );
            }
            
            $objPHPExcel->getActiveSheet ()->freezePane ( 'A4' );
            header ( "Content-Type: text/plain" );
            
            // Add data
            $Row = 4;
            
            foreach ( $resultSet[$principalHeader] as $rs ) {
                foreach ( $headersArray[$principalHeader] as $key => $header ) {
                    //$objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", trim ( $rs[$header] ) );
                    
                    switch ($formatArray[$principalHeader][$header]) {
                        case "integer" :
                            PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                            $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                            $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER );
                            break;
                            
                        case "double precision" :
                            PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                            $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                            $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00 );
                            break;
                            
                        case "date" :
                        case "timestamp without time zone" :
                            PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                            $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                            $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY );
                            break;
                            
                        case "character varying" :
                        case "text" :
                            $objPHPExcel->getActiveSheet ()->setCellValueExplicit ( "$columnasArray[$key]" . "$Row", $rs [$header], PHPExcel_Cell_DataType::TYPE_STRING );
                            
                            break;
                            
                        default :
                            PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                            $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                            $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_GENERAL );
                            break;
                    }
                    
                    if ($Row % 2 != 0) {
                        $objPHPExcel->getActiveSheet ()->getStyle ( "A$Row:$columnasArray[$numColumnas]$Row" )->applyFromArray ( array (
                            'fill' => array (
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array (
                                    'argb' => 'EFEFEF'
                                )
                            )
                        ) );
                    } else {
                        $objPHPExcel->getActiveSheet ()->getStyle ( "A$Row:$columnasArray[$numColumnas]$Row" )->applyFromArray ( array (
                            'fill' => array (
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array (
                                    'argb' => 'FFFFFF'
                                )
                            )
                        ) );
                    }
                }
                $Row ++;
                
                // Rename sheet
                $objPHPExcel->getActiveSheet ()->setTitle ( $principalHeader );
                
            }
            $counter++;
            $numColumnas = count ( $headersArray ) - 1;
            if ($counter <= $numColumnas)
                $objPHPExcel->createSheet ( $counter );
        }
        
        $objPHPExcel->setActiveSheetIndex ( 0 );
        
        /*header ( 'Content-Type: application/vnd.ms-excel' );
         header ( "Content-Disposition: attachment;filename=\"$file.xlsx\"" );
         header ( 'Cache-Control: max-age=0' );
         $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' );
         $objWriter->save ( 'php://output' );
         exit ();*/
        
        // Set active sheet index to the first sheet, so Excel opens this as the
        // first sheet
        $objPHPExcel->setActiveSheetIndex ( 0 );
        $file = str_replace(" ","",$file);
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' );
        $objWriter->save ( "/var/www/html/files/$file.xlsx");
        
    }
    function exportaToExcelEvalCasa($recordSet, $headersArray, $formatArray, $view, $file, $clave_nombre_aduana, $totalQuestions, $evaluacion) {
        /**
         * Error reporting
         */
        // error_reporting ( E_ALL );
        
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel ();
        
        // Set properties
        $objPHPExcel->getProperties ()->setCreator ( "Sistemas Casa" )->setLastModifiedBy ( "Sistemas Casa" )->setTitle ( "Office 2007 XLSX Test Document" )->setSubject ( "Office 2007 XLSX Test Document" )->setDescription ( "Test document for Office 2007 XLSX, generated using PHP classes." )->setKeywords ( "office 2007 openxml php" )->setCategory ( "Test result file" );
        
        // Creating Array of all the columns
        $columnasArray = array (
            0 => "A",
            1 => "B",
            2 => "C",
            3 => "D",
            4 => "E",
            5 => "F",
            6 => "G",
            7 => "H",
            8 => "I",
            9 => "J",
            10 => "K",
            11 => "L",
            12 => "M",
            13 => "N",
            14 => "O",
            15 => "P",
            16 => "Q",
            17 => "R",
            18 => "S",
            19 => "T",
            20 => "U",
            21 => "V",
            22 => "W",
            23 => "X",
            24 => "Y",
            
            
        );
        $numColumnas = count ( $headersArray ) - 1;
        
        // Building principal header
        $fileHeader = str_replace("Evaluacion_",'', $file);
        $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( "A1", "Evaluación: " . $evaluacion );
        $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( "D1", "Preguntas: " . $totalQuestions );
        $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( "E1", "Aduana: " . $clave_nombre_aduana );
        $objPHPExcel->getActiveSheet ()->getStyle ( "A1:$columnasArray[$numColumnas]2" )->applyFromArray ( array (
            'fill' => array (
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array (
                    'argb' => '008fb3'
                )
            ),
            'alignment' => array (
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'font' => array (
                'bold' => true,
                'color' => array (
                    'argb' => 'FFFFFF'
                ),
                'size' => '14'
            )
        ) );
       
        // Block of Headers
        foreach ( $headersArray as $key => $header ) {
            // $header = str_replace($column["column_name"], ucfirst($column["column_name"]), $column["column_name"]);
            $header = str_replace($header, ucfirst($header), $header);
            $header = str_replace("Fecha de aplicacion","Fecha de aplicación", $header);
            $header = str_replace("Tiempo usado","Tiempo utilizado (min)", $header);
            $header = str_replace("Porcentaje sistema","Porcentaje uso sistema", $header);
            $header = str_replace("Status flag","Ingresó", $header);
            $header = str_replace("Entre 30 60","De 30 - 60", $header);
            $header = str_replace("Entre 0 30","De 0 - 30", $header);
            $header = str_replace("Numberaswers","Aplicó Evaluación", $header);
            
         
            
            $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( "$columnasArray[$key]3", "$header" );
            $objPHPExcel->getActiveSheet ()->getColumnDimension ( "$columnasArray[$key]" )->setVisible ( true );
            $objPHPExcel->getActiveSheet ()->getColumnDimension ( "$columnasArray[$key]" )->setAutoSize ( true );
            $objPHPExcel->getActiveSheet ()->getStyle ( "A3:$columnasArray[$numColumnas]3" )->applyFromArray ( array (
                'fill' => array (
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array (
                        'argb' => 'D8E5F3'
                    )
                ),
                
                'alignment' => array (
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                ),
                'font' => array (
                    'bold' => true,
                    'color' => array (
                        'argb' => '003f4e'
                    ),
                    'size' => '12'
                )
            ) );
        }
        //exit;
        $objPHPExcel->getActiveSheet ()->freezePane ( 'A4' );
        $objPHPExcel->getActiveSheet()->getColumnDimension ("H")->setVisible(false);
        $objPHPExcel->getActiveSheet()->getColumnDimension ("J")->setVisible(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension ("K")->setVisible(false);
        
        foreach($objPHPExcel->getActiveSheet()->getColumnDimension() as $col) {
            $col->setAutoSize(true);
        }
        $objPHPExcel->getActiveSheet()->calculateColumnWidths();
        
        //$objPHPExcel->getActiveSheet()->setCellValue('A3', 29);
        $objPHPExcel->getActiveSheet()->getStyle('L')->getNumberFormat()->setFormatCode('0000');
        foreach(range('C','O') as $columnID)
        {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        //$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(100);
        //$objPHPExcel->getRowDimension('1')->setRowHeight(-1);
        //$objPHPExcel->getActiveSheet()->getRowDimension(0)->setRowHeight(-1);
        //exit;
        header ( "Content-Type: text/plain" );
        // Add data
        $Row = 4;
        foreach ( $recordSet as $rs ) {
            foreach ( $headersArray as $key => $header ) {
                
                if(($header == 'id examdetalle') || ($header == 'id evaluacion') || ($header == 'id cliente') || ($header == 'id aduana') || ($header == 'id tipo usuario')  || ($header == 'clave nombre aduana')   || ($header == 'pregsistema exam') || ($header == 'pregaduanal exam')){
                    
                    $objPHPExcel->getActiveSheet()->getColumnDimension ($columnasArray[$key])->setVisible(false);
                }
                
                //var_dump($formatArray [$header]);
                
                switch ($formatArray [$header]) {
                    
                    case "GENERAL" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", trim ( $rs [$header] ) );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_GENERAL );
                        break;
                        
                    case "NUMBER" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER );
                        break;
                        
                    case "NUMBER_00" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00 );
                        break;
                        
                    case "DATE" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::toFormattedString('DD/MM/YYYY'));
                        break;
                        
                    case "DATETIME" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYY );
                        break;
                        
                    case "TEXT" :
                        $text = strip_tags ( $rs ["$header"] );
                        $objPHPExcel->getActiveSheet ()->setCellValueExplicit ( "$columnasArray[$key]" . "$Row", $text, PHPExcel_Cell_DataType::TYPE_STRING );
                        break;
                        
                    default :
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        break;
                }
                
                if ($Row % 2 != 0) {
                    $objPHPExcel->getActiveSheet ()->getStyle ( "A$Row:$columnasArray[$numColumnas]$Row" )->applyFromArray ( array (
                        'fill' => array (
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array (
                                'argb' => 'ebfbff'
                            )
                        ),
                        'alignment' => array (
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                        )/*,'borders' => array(
                            'right' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('rgb' => '000000')
                            )
                            )*/
                    ) );
                } else {
                    $objPHPExcel->getActiveSheet ()->getStyle ( "A$Row:$columnasArray[$numColumnas]$Row" )->applyFromArray ( array (
                        'fill' => array (
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array (
                                'argb' => 'FFFFFF'
                            )
                        ),'alignment' => array (
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                        )/*,'borders' => array(
                            'right' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('rgb' => '000000')
                            )
                            )*/
                    ) );
                }
            }
            $numColumnas = count ( $headersArray ) - 1;
            $Row ++;
        }
        
        
        // Rename sheet
        $objPHPExcel->getActiveSheet ()->setTitle ( $file );
        
        // Set active sheet index to the first sheet, so Excel opens this as the
        // first sheet
        
        
        $objPHPExcel->setActiveSheetIndex ( 0 );
        $file = str_replace(" ","",$file);
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( "Content-Disposition: attachment;filename=\"$file.xlsx\"" );
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' );
        $objWriter->save ( 'php://output' );
        exit ();
    }
    
    function exportaToExcelCbodegaNew($resultSet, $headersArray, $formatArray, $file, $view, $idLanguage, $clientColor) {
        $clientColor = str_replace('#', '', $clientColor);
        /**
         * Error reporting
         */
        error_reporting ( E_ALL );
        
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel ();
        
        // Set properties
        $objPHPExcel->getProperties ()->setCreator ( "Sistemas Casa" )->setLastModifiedBy ( "Sistemas Casa" )->setTitle ( "Office 2007 XLSX Test Document" )->setSubject ( "Office 2007 XLSX Test Document" )->setDescription ( "Test document for Office 2007 XLSX, generated using PHP classes." )->setKeywords ( "office 2007 openxml php" )->setCategory ( "Test result file" );
        
        // Creating Array of all the columns
        $columnasArray = array (
            0 => "A",
            1 => "B",
            2 => "C",
            3 => "D",
            4 => "E",
            5 => "F",
            6 => "G",
            7 => "H",
            8 => "I",
            9 => "J",
            10 => "K",
            11 => "L",
            12 => "M",
            13 => "N",
            14 => "O",
            15 => "P",
            16 => "Q",
            17 => "R",
            18 => "S",
            19 => "T",
            20 => "U",
            21 => "V",
            22 => "W",
            23 => "X",
            24 => "Y",
            25 => "Z",
            26 => "AA",
            27 => "AB",
            28 => "AC",
            29 => "AD",
            30 => "AE",
            31 => "AF",
            32 => "AG",
            33 => "AH",
            34 => "AI",
            35 => "AJ",
            36 => "AK",
            37 => "AL",
            38 => "AM",
            39 => "AN",
            40 => "AO",
            41 => "AP",
            42 => "AQ",
            43 => "AR",
            44 => "AS",
            45 => "AT",
            46 => "AU",
            47 => "AV",
            48 => "AW",
            49 => "AX",
            50 => "AY",
            51 => "AZ",
            
            52=> "BA",
            53 => "BB",
            54 => "BC",
            55 => "BD",
            56 => "BE",
            57 => "BF",
            58 => "BG",
            59 => "BH",
            60 => "BI",
            61 => "BJ",
            62 => "BK",
            63 => "BL",
            64 => "BM",
            65 => "BN",
            66 => "BO",
            67 => "BP",
            68 => "BQ",
            69 => "BR",
            70 => "BS",
            71 => "BT",
            72 => "BU",
            73 => "BV",
            74 => "BW",
            75 => "BX",
            76 => "BY",
            77 => "BZ",
            
            78 => "CA",
            79 => "CB",
            80 => "CC",
            81 => "CD",
            82 => "CE",
            83 => "CF",
            84 => "CG",
            85 => "CH",
            86 => "CI",
            87 => "CJ",
            88 => "CK",
            89 => "CL",
            90 => "CM",
            91 => "CN",
            92 => "CO",
            93 => "CP",
            94 => "CQ",
            95 => "CR",
            96 => "CS",
            97 => "CT",
            98 => "CU",
            99 => "CV",
            100 => "CW",
            101 => "CX",
            102 => "CY",
            103 => "CZ",
            
        );
        $numColumnas = count ( $headersArray ) - 1;
        
        // Building principal header
        $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( "A1", "$file" );
        $objPHPExcel->setActiveSheetIndex ( 0 )->mergeCells ( "A1:$columnasArray[$numColumnas]2" );
        $objPHPExcel->getActiveSheet ()->getStyle ( "A1:$columnasArray[$numColumnas]2" )->applyFromArray ( array (
            'fill' => array (
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array (
                    'argb' => $clientColor
                )
            ),
            'alignment' => array (
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'font' => array (
                'bold' => true,
                'color' => array (
                    'argb' => 'FFFFFF'
                ),
                'size' => '14'
            )
        ) );
        
        // Block of Headers
        foreach ( $headersArray as $key => $header ) {
            $generateReports = new \GenerateReportsCbodega;
            $header = str_replace($header, ucfirst($header), $header);
            $headerTitle = $generateReports->getHeaderTitle($header, $idLanguage, $view);
            $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( "$columnasArray[$key]3", "$headerTitle" );
            $objPHPExcel->getActiveSheet ()->getColumnDimension ( "$columnasArray[$key]" )->setVisible ( true );
            $objPHPExcel->getActiveSheet ()->getColumnDimension ( "$columnasArray[$key]" )->setAutoSize ( true );
            $objPHPExcel->getActiveSheet ()->getStyle ( "A3:$columnasArray[$numColumnas]3" )->applyFromArray ( array (
                'font' => array (
                    'bold' => true,
                    'color' => array (
                        'argb' => $clientColor
                    ),
                    'size' => '12'
                )
            ) );
        }
        $objPHPExcel->getActiveSheet ()->freezePane ( 'A4' );
        
        
        foreach($objPHPExcel->getActiveSheet()->getColumnDimension() as $col) {
            $col->setAutoSize(true);
        }
        $objPHPExcel->getActiveSheet()->calculateColumnWidths();
        
        $objPHPExcel->getActiveSheet()->getStyle('L')->getNumberFormat()->setFormatCode('0000');
        foreach(range('C','O') as $columnID)
        {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        
        header ( "Content-Type: text/plain" );
        // Add data
        $Row = 4;
        foreach ( $resultSet as $rs ) {
            foreach ( $headersArray as $key => $header ) {
                
                if(($header == 'clave cliente') || ($header == 'linea fletera') || ($header == 'oc') || ($header == 'fecha de cruce') || ($header == 'typeimporter') || ($header == 'Typeimporter') || ($header == 'idimpoexpo') || ($header == 'fecha de fin') ||  ($header == 'salida cobonb') || ($header == 'cve idr') || ($header == 'idproveedor')){
                    
                    $objPHPExcel->getActiveSheet()->getColumnDimension ($columnasArray[$key])->setVisible(false);
                }
                
                if (($view == "arribospartreport" || $view  == "arribospartreportonate") && $header == 'referencia' || ($header == 'edo cruce')){
                    $objPHPExcel->getActiveSheet()->getColumnDimension ($columnasArray[$key])->setVisible(false);
                }
                
                if (($view == "arribospartreport" || $view  == "arribospartreportonate") && (($header == 'linea fletera') || ($header == 'oc'))){
                    $objPHPExcel->getActiveSheet()->getColumnDimension ($columnasArray[$key])->setVisible(true);
                }
                if ($view == "DesglosePedimentos" &&  $header == "fecha de ingreso" ){
                    $objPHPExcel->getActiveSheet()->getColumnDimension ($columnasArray[$key])->setVisible(false);
                }
                
                switch ($formatArray [$header]) {
                    
                    case "GENERAL" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", trim ( $rs [$header] ) );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_GENERAL );
                        break;
                        
                    case "NUMBER" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER );
                        break;
                        
                    case "NUMBER_00" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00 );
                        break;
                        
                    case "DATE" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::toFormattedString('DD/MM/YYYY'));
                        break;
                        
                    case "DATETIME" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYY );
                        break;
                        
                    case "TEXT" :
                        $text = strip_tags ( $rs ["$header"] );
                        $objPHPExcel->getActiveSheet ()->setCellValueExplicit ( "$columnasArray[$key]" . "$Row", $text, PHPExcel_Cell_DataType::TYPE_STRING );
                        break;
                        
                    default :
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        break;
                }
                
            }
            $numColumnas = count ( $headersArray ) - 1;
            
            $Row ++;
        }
        
        
        $objPHPExcel->getActiveSheet ()->setTitle ( $file );
        
        
        $objPHPExcel->setActiveSheetIndex ( 0 );
        
        $file = str_replace(' ', '', $file);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header ( "Content-Disposition: attachment;filename=\"$file.xlsx\"" );
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit ();
    }
    
    function exportaToExcelUserDetails($recordSet, $headersArray, $formatArray, $view, $file) {
        $objPHPExcel = new PHPExcel ();
        $objPHPExcel->getProperties ()->setCreator ( "Sistemas Casa" )->setLastModifiedBy ( "Sistemas Casa" )->setTitle ( "Office 2007 XLSX Test Document" )->setSubject ( "Office 2007 XLSX Test Document" )->setDescription ( "Test document for Office 2007 XLSX, generated using PHP classes." )->setKeywords ( "office 2007 openxml php" )->setCategory ( "Test result file" );
        
        $columnasArray = array (
            0 => "A",
            1 => "B",
            2 => "C",
            3 => "D",
            4 => "E",
            5 => "F"
        );
        
        $numColumnas = count ( $headersArray ) - 1;
        $fileHeader = str_replace("Evaluacion_",'', $file);
        $usuario = $recordSet[0]["nombre usu"];
        $empresa = $recordSet[0]["nombre cliente"];
        $aduana = $recordSet[0]["nombre aduana"];
        $examen = $recordSet[0]["nombre examen"];
        $aplic = $recordSet[0]["fecaplic usuarioexam"];
        $tiempo = $recordSet[0]["tiempo usuarioexam"];
        $prom = $recordSet[0]["prom usuarioexam"];
        
        $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( "A1", "Detalle de los resultados de la evaluación: " . $fileHeader );
        $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( "A3", "Usuario: " . $usuario ."               " . "Empresa: " . $empresa ."               " . "Aduana: " . $aduana ."               " . "Fecha de aplicación: " . $aplic ."               " . "Tiempo utilizado: " . $tiempo. " minutos" ."               " . "Promedio obtenido: " . $prom);
        $objPHPExcel->setActiveSheetIndex ( 0 )->mergeCells ( "A1:F2" );
        $objPHPExcel->setActiveSheetIndex ( 0 )->mergeCells ( "A3:F3" );
        
        $objPHPExcel->getActiveSheet ()->getStyle ( "A1:F2" )->applyFromArray ( array (
            'fill' => array (
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array (
                    'argb' => '005f77'
                )
            ),
            'alignment' => array (
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'font' => array (
                'bold' => true,
                'color' => array (
                    'argb' => 'FFFFFF'
                ),
                'size' => '15'
            )
        ) );
        
        $objPHPExcel->getActiveSheet ()->getStyle ( "A3:F3" )->applyFromArray ( array (
            'fill' => array (
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array (
                    'argb' => '008fb3'
                )
            ),
            'alignment' => array (
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'font' => array (
                'bold' => true,
                'color' => array (
                    'argb' => 'FFFFFF'
                ),
                'size' => '11'
            )
        ) );
        
        $aux = 0;
        foreach ( $headersArray as $key => $header ) {
            if($key > 8 && $key < 15){
                $header = str_replace('numpregunta respuestasusu', 'Consecutivo', $header);
                $header = str_replace('nombre tipo', 'Tipo', $header);
                $header = str_replace('nombre pregunta', 'Pregunta', $header);
                $header = str_replace('respuesta respuestausu', 'Respuesta Usuario', $header);
                $header = str_replace('respcorrec pregunta', 'Respuesta Correcta', $header);
                $header = str_replace('aciertos', '¿Acertó?', $header);
                
                $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( "$columnasArray[$aux]4", "$header" );
                $objPHPExcel->getActiveSheet ()->getColumnDimension ( "$columnasArray[$aux]" )->setVisible ( true );
                //$objPHPExcel->getActiveSheet ()->getColumnDimension ( "$columnasArray[$aux]" )->setAutoSize ( true );
                $aux++;
            }
            
        }
        
        $objPHPExcel->getActiveSheet ()->getStyle ( "A4:F4" )->applyFromArray ( array (
            'fill' => array (
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array (
                    'argb' => 'D8E5F3'
                )
            ),
            'alignment' => array (
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'font' => array (
                'bold' => true,
                'color' => array (
                    'argb' => '003f4e'
                ),
                'size' => '12'
            )
        ) );
        
        $objPHPExcel->getActiveSheet ()->freezePane ( 'A5' );
        $objPHPExcel->getActiveSheet()->calculateColumnWidths();
        
        foreach(range('A','F') as $columnID)
        {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        
        header ( "Content-Type: text/plain" );
        
        $Row = 5;
        foreach ( $recordSet as $rs ) {
            $auxiliar = 0;
            foreach ( $headersArray as $key => $header ) {
                if($key > 8 && $key < 15){
                    switch ($formatArray [$header]) {
                        case "GENERAL" :
                            PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                            $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$auxiliar]" . "$Row", trim ( $rs [$header] ) );
                            $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$auxiliar]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_GENERAL );
                            break;
                            
                        case "NUMBER" :
                            PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                            $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$auxiliar]" . "$Row", $rs [$header] );
                            $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$auxiliar]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER );
                            break;
                            
                        case "NUMBER_00" :
                            PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                            $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$auxiliar]" . "$Row", $rs [$header] );
                            $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$auxiliar]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00 );
                            break;
                            
                        case "DATE" :
                            PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                            $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$auxiliar]" . "$Row", $rs [$header] );
                            $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$auxiliar]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::toFormattedString('DD/MM/YYYY'));
                            break;
                            
                        case "DATETIME" :
                            PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                            $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$auxiliar]" . "$Row", $rs [$header] );
                            $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$auxiliar]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYY );
                            break;
                            
                        case "TEXT" :
                            $text = strip_tags ( $rs ["$header"] );
                            $objPHPExcel->getActiveSheet ()->setCellValueExplicit ( "$columnasArray[$auxiliar]" . "$Row", $text, PHPExcel_Cell_DataType::TYPE_STRING );
                            break;
                            
                        default :
                            $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$auxiliar]" . "$Row", $rs [$header] );
                            break;
                    }
                    
                    if($key == 14){
                        if($rs["respuesta respuestausu"] == $rs["respcorrec pregunta"])
                            $text = 'Sí';
                            else
                                $text = 'No';
                                
                                $objPHPExcel->getActiveSheet ()->setCellValueExplicit ( "F" . "$Row", $text, PHPExcel_Cell_DataType::TYPE_STRING );
                    }
                    $auxiliar++;
                }
                
                
                if ($Row % 2 != 0) {
                    $objPHPExcel->getActiveSheet ()->getStyle ( "A$Row:F$Row" )->applyFromArray ( array (
                        'fill' => array (
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array (
                                'argb' => 'ebfbff'
                            )
                        ),
                        'alignment' => array (
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY,// HORIZONTAL_CENTER,
                            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                        )/*,'borders' => array(
                            'right' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('rgb' => '000000')
                            )
                            )*/
                    ) );
                } else {
                    $objPHPExcel->getActiveSheet ()->getStyle ( "A$Row:F$Row" )->applyFromArray ( array (
                        'fill' => array (
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array (
                                'argb' => 'FFFFFF'
                            )
                        ),'alignment' => array (
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY,
                            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                        )/*,'borders' => array(
                            'right' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('rgb' => '000000')
                            )
                            )*/
                    ) );
                }
            }
            $numColumnas = count ( $headersArray ) - 1;
            $Row ++;
        }
        
        $objPHPExcel->getActiveSheet ()->setTitle ('Resultados');
        $objPHPExcel->setActiveSheetIndex ( 0 );
        $file = str_replace(" ","",$file);
        $usuario = str_replace(" ","",$usuario);
        $examen = str_replace(" ","",$examen);
        $examen = str_replace("_","",$examen);
        
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( "Content-Disposition: attachment;filename=\"$usuario _ $examen.xlsx\"" );
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' );
        $objWriter->save ( 'php://output' );
        exit ();
    }
    
    function exportaToExcelPaids($paids, $file){
        $objPHPExcel = new PHPExcel();
        
        header ( "Content-Type: text/plain" );
        $objPHPExcel->getProperties ()->setCreator ( "Sistemas Casa" )->setLastModifiedBy ( "Sistemas Casa" )->setTitle ( "Office 2007 XLSX Test Document" )->setSubject ( "Office 2007 XLSX Test Document" )->setDescription ( "Test document for Office 2007 XLSX, generated using PHP classes." )->setKeywords ( "office 2007 openxml php" )->setCategory ( "Test result file" );
        $counter = 2;
        foreach ($paids as $paid){
            
            if($paid["cfdiStatus"] == 3) {
                $status = "Pagado";
            } else {
                $status = "Facturado";
            }
            
            $date = explode(" ", $paid["cfdiRegistrationDate"]);
            
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Folio')
            ->setCellValue('B1', 'ID Cliente')
            ->setCellValue('C1', 'Cliente')
            ->setCellValue('D1', 'Patente')
            ->setCellValue('E1', 'RFC')
            ->setCellValue('F1', 'Correo')
            ->setCellValue('G1', 'Fecha')
            ->setCellValue('H1', 'Estado')
            ->setCellValueExplicit ( 'A'.$counter, $paid["cfdiFolio"], PHPExcel_Cell_DataType::TYPE_STRING )
            ->setCellValueExplicit ( 'B'.$counter, $paid["clientKey"], PHPExcel_Cell_DataType::TYPE_STRING )
            ->setCellValueExplicit ( 'C'.$counter, $paid["clientName"], PHPExcel_Cell_DataType::TYPE_STRING )
            ->setCellValueExplicit ( 'D'.$counter, $paid["cfdiPatent"], PHPExcel_Cell_DataType::TYPE_STRING )
            ->setCellValueExplicit ( 'E'.$counter, $paid["clientRfc"], PHPExcel_Cell_DataType::TYPE_STRING )
            ->setCellValueExplicit ( 'F'.$counter, $paid["cfdiEmail"], PHPExcel_Cell_DataType::TYPE_STRING )
            ->setCellValue('G'.$counter, $date[0])
            ->setCellValue('H'.$counter, $status);
            
            /*Formato de celdass*/
            //$objPHPExcel->getActiveSheet (0)->getColumnDimension ('A1:H15')->setAutoSize ( true );
            //$objPHPExcel->getActiveSheet()->getStyle('A' . $counter . ':' . 'H' . $counter)->getAlignment()->setWrapText(true);
            $styleArray = array(
                'font' => array(
                    'bold' => true,
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY,
                ),
                'borders' => array(
                    'top' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    ),
                ),
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation' => 90,
                    'startcolor' => array(
                        'argb' => 'FFA0A0A0',
                    ),
                    'endcolor' => array(
                        'argb' => 'FFFFFFFF',
                    ),
                ),
            );
            $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($styleArray);
            $counter ++;
        }
        
        foreach($objPHPExcel->getActiveSheet()->getColumnDimension() as $col) {
            $col->setAutoSize(true);
        }
        $objPHPExcel->getActiveSheet()->calculateColumnWidths();
        
        //$objPHPExcel->getActiveSheet()->setCellValue('A3', 29);
        $objPHPExcel->getActiveSheet()->getStyle('L')->getNumberFormat()->setFormatCode('0000');
        foreach(range('A','O') as $columnID)
        {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        
        // Renombrar Hoja
        $objPHPExcel->getActiveSheet()->setTitle('Pagadas');
        // Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
        $objPHPExcel->setActiveSheetIndex(0);
        
        $objPHPExcel->setActiveSheetIndex ( 0 );
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( "Content-Disposition: attachment;filename=\"$file.xlsx\"" );
        header ( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel2007' );
        $objWriter->save ( 'php://output' );
        $objWriter->save ( '../files/Cfdi/' . $file . '.xlsx');
        exit ();
        
    }

    
    function exportaToExcelPrevios($recordSet, $headersArray, $formatArray, $file, $view, $idLanguage, $clientColor, $nameFile, $arrayAll) {

        $clientColor = str_replace('#', '', $clientColor);
        /**
         * Error reporting
         */
        error_reporting ( E_ALL );
        
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel ();
        
        // Set properties
        $objPHPExcel->getProperties ()->setCreator ( "Sistemas Casa" )->setLastModifiedBy ( "Sistemas Casa" )->setTitle ( "Office 2007 XLSX Test Document" )->setSubject ( "Office 2007 XLSX Test Document" )->setDescription ( "Test document for Office 2007 XLSX, generated using PHP classes." )->setKeywords ( "office 2007 openxml php" )->setCategory ( "Test result file" );
        
        // Creating Array of all the columns
        $columnasArray = array (
            0 => "A",
            1 => "B",
            2 => "C",
            3 => "D",
            4 => "E",
            5 => "F",
            6 => "G",
            7 => "H",
            8 => "I",
            9 => "J",
            10 => "K",
            11 => "L",
            12 => "M",
            13 => "N",
            14 => "O",
            15 => "P",
            16 => "Q",
            17 => "R",
            18 => "S",
            19 => "T",
            20 => "U",
            21 => "V",
            22 => "W",
            23 => "X",
            24 => "Y",
            25 => "Z",
            26 => "AA",
            27 => "AB",
            28 => "AC",
            29 => "AD",
            30 => "AE",
            31 => "AF",
            32 => "AG",
            33 => "AH",
            34 => "AI",
            35 => "AJ",
            36 => "AK",
            37 => "AL",
            38 => "AM",
            39 => "AN",
            40 => "AO",
            41 => "AP",
            42 => "AQ",
            43 => "AR",
            44 => "AS",
            45 => "AT",
            46 => "AU",
            47 => "AV",
            48 => "AW",
            49 => "AX",
            50 => "AY",
            51 => "AZ",
            
           
            
        );
        $numColumnas = count ( $headersArray ) - 1;
        
        // Building principal header
        $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( "A1", "$nameFile" );
        $objPHPExcel->setActiveSheetIndex ( 0 )->mergeCells ( "A1:$columnasArray[$numColumnas]2" );
        $objPHPExcel->getActiveSheet ()->getStyle ( "A1:$columnasArray[$numColumnas]2" )->applyFromArray ( array (
            'fill' => array (
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array (
                    'argb' => $clientColor
                )
            ),
            'alignment' => array (
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'font' => array (
                'bold' => true,
                'color' => array (
                    'argb' => 'FFFFFF'
                ),
                'size' => '14'
            )
        ) );
        
        // Block of Headers
        foreach ( $headersArray as $key => $header ) {
            $generateReports = new \Listings;
            $headerTitle = $generateReports->getHeaderTitle($header, $idLanguage, $view);
            $objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( "$columnasArray[$key]3", "$headerTitle" );
            $objPHPExcel->getActiveSheet ()->getColumnDimension ( "$columnasArray[$key]" )->setVisible ( true );
            $objPHPExcel->getActiveSheet ()->getColumnDimension ( "$columnasArray[$key]" )->setAutoSize ( true );
            $objPHPExcel->getActiveSheet ()->getStyle ( "A3:$columnasArray[$numColumnas]3" )->applyFromArray ( array (
                'font' => array (
                    'bold' => true,
                    'color' => array (
                        'argb' => $clientColor
                    ),
                    'size' => '12'
                )
            ) );
        }
        $objPHPExcel->getActiveSheet ()->freezePane ( 'A4' );
        
        
        foreach($objPHPExcel->getActiveSheet()->getColumnDimension() as $col) {
            $col->setAutoSize(true);
        }
        $objPHPExcel->getActiveSheet()->calculateColumnWidths();
        
        $objPHPExcel->getActiveSheet()->getStyle('L')->getNumberFormat()->setFormatCode('0000');
        foreach(range('C','O') as $columnID)
        {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        
        header ( "Content-Type: text/plain" );
        // Add data
        $Row = 4;
        foreach ( $recordSet as $rs ) {
           
            foreach ( $headersArray as $key => $header ) {
                
                if(($header == 'id partida') || ($header == 'id cliente') || ($header == 'id prev')){
                    
                    $objPHPExcel->getActiveSheet()->getColumnDimension ($columnasArray[$key])->setVisible(false);
                }
        
                
                switch ($formatArray [$header]) {
                    
                    case "GENERAL" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", trim ( $rs [$header] ) );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_GENERAL );
                        break;
                        
                    case "NUMBER" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER );
                        break;
                        
                    case "NUMBER_00" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00 );
                        break;
                        
                    case "DATE" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::toFormattedString('DD/MM/YYYY'));
                        break;
                        
                    case "DATETIME" :
                        PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        $objPHPExcel->getActiveSheet ()->getStyle ( "$columnasArray[$key]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYY );
                        break;
                        
                    case "TEXT" :
                        $text = strip_tags ( $rs ["$header"] );
                        $objPHPExcel->getActiveSheet ()->setCellValueExplicit ( "$columnasArray[$key]" . "$Row", $text, PHPExcel_Cell_DataType::TYPE_STRING );
                        break;
                        
                    default :
                        $objPHPExcel->getActiveSheet ()->setCellValue ( "$columnasArray[$key]" . "$Row", $rs [$header] );
                        break;
                }
                
            }
            $numColumnas = count ( $headersArray ) - 1;
            
            $Row ++;
        }
        
        
        $objPHPExcel->getActiveSheet ()->setTitle ( $nameFile );
        
     
       
        if(count($arrayAll) != 0) {
            
            $numColumnas2 = count ( $arrayAll["headersseries"] ) - 1;
   
            $columnasArray2 = array (
                0 => "A",
                1 => "B",
                2 => "C",
                3 => "D",
                4 => "E",
                5 => "F",
                6 => "G",
                7 => "H",
                8 => "I",
                9 => "J",
                10 => "K",
                11 => "L",
            );

            $Row = 4;
            $objWorksheet1 = $objPHPExcel->createSheet(1);
            $objWorksheet1->setTitle('Series');
            $objWorksheet1->setCellValue ( "A1", "SERIES" );
            $objWorksheet1->mergeCells ( "A1:$columnasArray2[$numColumnas2]2" );
            $objWorksheet1->getStyle ( "A1:$columnasArray2[$numColumnas2]2" )->applyFromArray ( array (
                'fill' => array (
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array (
                        'argb' => $clientColor
                    )
                ),
                'alignment' => array (
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                ),
                'font' => array (
                    'bold' => true,
                    'color' => array (
                        'argb' => 'FFFFFF'
                    ),
                    'size' => '14'
                )
            ) );

                    // Block of Headers
            foreach ($arrayAll["headersseries"] as $key2 => $header2 ) {
                $generateReports = new \Listings;
                $headerTitle = $generateReports->getHeaderTitle($header2, $idLanguage, $view);
                $objWorksheet1->setCellValue ( "$columnasArray2[$key2]3", "$headerTitle" );
                $objWorksheet1->getColumnDimension ( "$columnasArray2[$key2]" )->setVisible ( true );
                $objWorksheet1->getColumnDimension ( "$columnasArray2[$key2]" )->setAutoSize ( true );
                $objWorksheet1->getStyle ( "A3:$columnasArray2[$numColumnas2]3" )->applyFromArray ( array (
                    'font' => array (
                        'bold' => true,
                        'color' => array (
                            'argb' => $clientColor
                        ),
                        'size' => '12'
                    )
                ) );
            }
            $objWorksheet1->freezePane ( 'A4' );

                foreach ( $arrayAll["registros"] as $rs ) {

                foreach ( $arrayAll["headersseries"]  as $key2 => $header2 ) {

                    if(($header2 == 'num refe') || ($header2 == 'id partida') || ($header2 == 'id cliente') || ($header2 == 'id prev')){

                        $objWorksheet1->getColumnDimension ($columnasArray2[$key2])->setVisible(false);
                    }


                    switch ($arrayAll["formato"] [$header2]) {

                        case "GENERAL" :
                            PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                            $objWorksheet1->setCellValue ( "$columnasArray2[$key2]" . "$Row", trim ( $rs [$header2] ) );
                            $objWorksheet1->getStyle ( "$columnasArray2[$key2]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_GENERAL );
                            break;

                        case "NUMBER" :
                            PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                            $objWorksheet1->setCellValue ( "$columnasArray2[$key2]" . "$Row", $rs [$header2] );
                            $objWorksheet1->getStyle ( "$columnasArray2[$key2]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER );
                            break;

                        case "NUMBER_00" :
                            PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                            $objWorksheet1->setCellValue ( "$columnasArray2[$key2]" . "$Row", $rs [$header2] );
                            $objWorksheet1->getStyle ( "$columnasArray2[$key2]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00 );
                            break;

                        case "DATE" :
                            PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                            $objWorksheet1->setCellValue ( "$columnasArray2[$key2]" . "$Row", $rs [$header2] );
                            $objWorksheet1->getStyle ( "$columnasArray2[$key2]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::toFormattedString('DD/MM/YYYY'));
                            break;

                        case "DATETIME" :
                            PHPExcel_Cell::setValueBinder ( new PHPExcel_Cell_AdvancedValueBinder () );
                            $objWorksheet1->setCellValue ( "$columnasArray2[$key2]" . "$Row", $rs [$header2] );
                            $objWorksheet1->getStyle ( "$columnasArray2[$key2]" . "$Row" )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYY );
                            break;

                        case "TEXT" :
                            $text = strip_tags ( $rs ["$header2"] );
                            $objWorksheet1->setCellValueExplicit ( "$columnasArray2[$key2]" . "$Row", $text, PHPExcel_Cell_DataType::TYPE_STRING );
                            break;

                        default :
                            $objWorksheet1->setCellValue ( "$columnasArray2[$key2]" . "$Row", $rs [$header2] );
                            break;
                    }

                }
                $numColumnas2 = count (  $arrayAll["headersseries"] ) - 1;

                $Row ++;
            }
        
        } 
        
        $objPHPExcel->setActiveSheetIndex ( 0 );
        
        $file = str_replace(' ', '', $nameFile);
        
        header ( 'Content-Type: application/vnd.ms-excel' );
        header ( "Content-Disposition: attachment;filename=\"$file.xlsx\"" );
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        ob_end_clean();
        $objWriter->save('php://output');
        exit ();
    }
    
}
?>