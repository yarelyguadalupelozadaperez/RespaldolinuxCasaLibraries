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
require_once 'CasaLibraries/Exportation/PHPExcel.php';

/**
 *
 * @see CasaLibraries_Exportation_IOFactory
 */
require_once 'CasaLibraries/Exportation/PHPExcel/IOFactory.php';
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
	function exportaToExcel($resultSet, $headersArray, $formatArray, $file) {
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
				25 => "Z" 
		);
		$numColumnas = count ( $headersArray ) - 1;
		
		// Building principal header
		$objPHPExcel->setActiveSheetIndex ( 0 )->setCellValue ( "A1", "$file" );
		$objPHPExcel->setActiveSheetIndex ( 0 )->mergeCells ( "A1:$columnasArray[$numColumnas]2" );
		$objPHPExcel->getActiveSheet ()->getStyle ( "A1:$columnasArray[$numColumnas]2" )->applyFromArray ( array (
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
		
		// Block of Headers
		foreach ( $headersArray as $key => $header ) {
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
		$Row = 4;
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
}
?>