<?php

/**
 * CasaLibraries Exportation
 * File StaticsUserReports.php
 * Performe the exportation to Excel
 *
 * @category		CasaLibraries
 * @package    		CasaLibraries_Exportation
 * @copyright  		Copyright (c) 2005-2013 Sistemas CASA, S.A. de C.V. sistemascasa.com.mx
 * @author		Tux's Team
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

class StatisticsUsersReports {

    /**
     * This method build a excel file
     *
     * @param array $resultSet        	
     * @param array $headersArray        	
     * @param array $formatArray        	
     * @param string $file        	
     * @return Excel file
     */
    
    function exportaToExcel($arrayCounters, $arrayLegislationCounter, $arrayUserCounter, $fecha, $arrayFractionCounter, $arrayUserFraCounter){

        /**
         * Error reporting
         */
        // error_reporting ( E_ALL );
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel ();

        // Set properties
        $objPHPExcel->getProperties()->setCreator("Sistemas Casa")->setLastModifiedBy("Sistemas Casa")->setTitle("Office 2007 XLSX Test Document")->setSubject("Office 2007 XLSX Test Document")->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")->setKeywords("office 2007 openxml php")->setCategory("Test result file");

        // Load the template file
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setIncludeCharts(TRUE);
        $objPHPExcel = $objReader->load('files/ReporteMensual.xlsx');

        
        // Set the first sheet for report
        $objPHPExcel->setActiveSheetIndex(1);
        

        // Creating Array of all the columns
        $columnasArray = array(
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

        // Select the first sheet
        $objPHPExcel->getActiveSheet();
        $currentDay = date(d);
        $month = date(m);
        $year = date(Y);
        $row = 10;
        $preMonth = date(m) - 1;
        if($month == 01){
            $year = date(Y) - 1;
            $preMonth = 12;
        }
        $a_date = "$currentDay-$preMonth-$year";
        $lastDay = date("t", strtotime($a_date));
       
        $lastDate = $lastDay . '/' . $preMonth. '/' . $year;
        $firstDate = '01' . '/' . $preMonth . '/' . $year;

        foreach ($arrayCounters as $key => $value) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $row, "$key");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $row, "$value");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . 6, "$firstDate");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . 7, "$lastDate ");
            $row++;
        }

        $rowUsers = 12;
        $key = 1;
        foreach ($arrayUserCounter as $arrayUserC) {

            switch ($arrayUserC["clientType"]) {
                case 1:
                    $arrayUserC["clientType"] = "Ctraweb";
                    break;
                case 2:
                    $arrayUserC["clientType"] = "CLAA";
                    break;
                case 3:
                    $arrayUserC["clientType"] = "America";
                    break;
                case 4:
                    $arrayUserC["clientType"] = "Csaaiwin";
                    break;
                case 5:
                    $arrayUserC["clientType"] = "7573055240";
                    break;
                case 6:
                    $arrayUserC["clientType"] = "25833033800";
                    break;
                case 7:
                    $arrayUserC["clientType"] = "343496470";
                    break;
                case 8:
                    $arrayUserC["clientType"] = "2923600520";
                    break;
                case 9:
                    $arrayUserC["clientType"] = "12220003470";
                    break;
                case 10:
                    $arrayUserC["clientType"] = "Sistemas CASA, S.A de C.V.";
                    break;
                case 11:
                    $arrayUserC["clientType"] = "Casaonline";
                    break;
                default:
                    $arrayUserC["clientType"] = "Sistemas CASA, S.A de C.V.";
                    break;
            }

            $objPHPExcel->setActiveSheetIndex(1)->setCellValue('A' . $rowUsers, $key);
            $objPHPExcel->setActiveSheetIndex(1)->setCellValue('B' . $rowUsers, $arrayUserC["userLogin"]);
            $objPHPExcel->setActiveSheetIndex(1)->setCellValue('C' . $rowUsers, $arrayUserC["userName"]);
            $objPHPExcel->setActiveSheetIndex(1)->setCellValue('D' . $rowUsers, $arrayUserC["clientType"]);
            $objPHPExcel->setActiveSheetIndex(1)->setCellValue('E' . $rowUsers, $arrayUserC["counter"]);
            $objPHPExcel->setActiveSheetIndex(1)->setCellValue('F' . 5, " $firstDate");
            $objPHPExcel->setActiveSheetIndex(1)->setCellValue('F' . 6, " $lastDate");

            $rowUsers ++;
            $key++;
        }

        $rowLaw = 9;
        $key = 1;
        foreach ($arrayLegislationCounter as $srrayLegislation) {

            $objPHPExcel->setActiveSheetIndex(2)->setCellValue('A' . $rowLaw, $key);
            $objPHPExcel->setActiveSheetIndex(2)->setCellValue('B' . $rowLaw, $srrayLegislation["category"]);
            $objPHPExcel->setActiveSheetIndex(2)->setCellValue('C' . $rowLaw, $srrayLegislation["subcategory"]);
            $objPHPExcel->setActiveSheetIndex(2)->setCellValue('D' . $rowLaw, $srrayLegislation["legislation"]);
            $objPHPExcel->setActiveSheetIndex(2)->setCellValue('E' . $rowLaw, $srrayLegislation["article"]);
            $objPHPExcel->setActiveSheetIndex(2)->setCellValue('F' . $rowLaw, $srrayLegislation["cuenta"]);
            $objPHPExcel->setActiveSheetIndex(2)->setCellValue('G' . 5, " $firstDate");
            $objPHPExcel->setActiveSheetIndex(2)->setCellValue('G' . 6, " $lastDate");


            $rowLaw++;
            $key++;
        }
        
        
        $rowUsers = 12;
        $key = 1;
        foreach ($arrayUserFraCounter as $arrayUserFrac) {

            switch ($arrayUserFrac["clientType"]) {
                case 1:
                    $arrayUserFrac["clientType"] = "Ctraweb";
                    break;
                case 2:
                    $arrayUserFrac["clientType"] = "CLAA";
                    break;
                case 3:
                    $arrayUserFrac["clientType"] = "America";
                    break;
                case 4:
                    $arrayUserFrac["clientType"] = "Csaaiwin";
                    break;
                case 5:
                    $arrayUserFrac["clientType"] = "7573055240";
                    break;
                case 6:
                    $arrayUserFrac["clientType"] = "25833033800";
                    break;
                case 7:
                    $arrayUserFrac["clientType"] = "343496470";
                    break;
                case 8:
                    $arrayUserFrac["clientType"] = "2923600520";
                    break;
                case 9:
                    $arrayUserFrac["clientType"] = "12220003470";
                    break;
                case 10:
                    $arrayUserFrac["clientType"] = "Sistemas CASA, S.A de C.V.";
                    break;
                case 11:
                    $arrayUserFrac["clientType"] = "Casaonline";
                    break;
            }

            $objPHPExcel->setActiveSheetIndex(3)->setCellValue('A' . $rowUsers, $key);
            $objPHPExcel->setActiveSheetIndex(3)->setCellValue('B' . $rowUsers, $arrayUserFrac["userLogin"]);
            $objPHPExcel->setActiveSheetIndex(3)->setCellValue('C' . $rowUsers, $arrayUserFrac["userName"]);
            $objPHPExcel->setActiveSheetIndex(3)->setCellValue('D' . $rowUsers, $arrayUserFrac["clientType"]);
            $objPHPExcel->setActiveSheetIndex(3)->setCellValue('E' . $rowUsers, $arrayUserFrac["counter"]);
            $objPHPExcel->setActiveSheetIndex(3)->setCellValue('F' . 5, " $firstDate");
            $objPHPExcel->setActiveSheetIndex(3)->setCellValue('F' . 6, " $lastDate");

            $rowUsers ++;
            $key++;
        }
        
        
        $rowLaw = 9;
        $key = 1;
        foreach($arrayFractionCounter as $arrayFraction){
            $objPHPExcel->setActiveSheetIndex(4)->setCellValue('A' . $rowLaw, $key);
            $objPHPExcel->setActiveSheetIndex(4)->setCellValue('B' . $rowLaw, $arrayFraction["tariffChapterCode"]);
            $objPHPExcel->setActiveSheetIndex(4)->setCellValue('C' . $rowLaw, $arrayFraction["tariffChapterDescription"]);
            $objPHPExcel->setActiveSheetIndex(4)->setCellValue('D' . $rowLaw, $arrayFraction["tariffHeadingCode"]);
            $objPHPExcel->setActiveSheetIndex(4)->setCellValue('E' . $rowLaw, $arrayFraction["tariffHeadingDescription"]);
            $objPHPExcel->setActiveSheetIndex(4)->setCellValue('F' . $rowLaw, $arrayFraction["tariffSubheadingCode"]);
            $objPHPExcel->setActiveSheetIndex(4)->setCellValue('G' . $rowLaw, $arrayFraction["tariffSubheadingDescription"]);
            $objPHPExcel->setActiveSheetIndex(4)->setCellValue('H' . $rowLaw, $arrayFraction["tariffFractionCode"]);
            $objPHPExcel->setActiveSheetIndex(4)->setCellValue('I' . $rowLaw, $arrayFraction["tariffFractionDescription"]);
            $objPHPExcel->setActiveSheetIndex(4)->setCellValue('J' . $rowLaw, $arrayFraction["counter"]);
            $objPHPExcel->setActiveSheetIndex(4)->setCellValue('J' . 5, " $firstDate");
            $objPHPExcel->setActiveSheetIndex(4)->setCellValue('J' . 6, " $lastDate");

            $rowLaw++;
            $key++;
        }
        
        $lastDateA = $lastDay . '-' . $preMonth. '-' . $year;
        $objPHPExcel->setActiveSheetIndex(0);
        
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->setIncludeCharts(TRUE);
        $objWriter->save("files/ReporteMensualLegislativo.xlsx");
        //"Content-Disposition: attachment;filename=\"ReporteMensual_$lastDate.xlsx\""
    }

    

}

?>