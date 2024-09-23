<?php
/**
 * CasaLibraries MyPdf
 * 
 * Create an Pdf from HTML content
 *
 * @category CasaLibraries
 * @package CasaLibraries_Exportation_MyPdf
 * @copyright (c) 2005-2013, Sistemas CASA, S.A. de C.V. http://www.sistemascasa.com.mx
 * @author Jesús Eduardo Flores Alejandro jflores@sistemascasa.com.mx
 * @version CasaLibraries 1.0.0
 */

/**
 * @see eng.php
 */
require_once 'tcpdf/config/lang/eng.php';

/**
 * @see tcpdf.php
 */
require_once 'tcpdf/tcpdf.php';

class MyPdfSat extends TCPDF {

    /**
     * Company Name
     * @var string 
     */
    public $companyName;
    
    /**
     * Powered
     * @var string
     */
    public $powered;
    
    /**
     * Html content
     * @var string
     */
    public $sHtml;

    /**
     * Pdf Margin Left
     * @var integer
     */
    public $pdfMarginLeft = PDF_MARGIN_LEFT;
    
    /**
     * Pdf Margin Top
     * @var integer
     */
    public $pdfMarginTop = PDF_MARGIN_TOP;
    
    /**
     * Pgf Margin Right
     * @var integer
     */
    public $pdfMarginRight = PDF_MARGIN_RIGHT;
    
    /**
     * File Header
     */
    public function Header() {
        $this->SetFont('helvetica', 'B', 15);
        $this->SetTextColor(53, 72, 79);        
        $this->Cell(0, 10, $this->companyName, 0, false, 'C', 0, '', 1, false, 'T', 'M');
        
        $bMargin = $this->getBreakMargin();
        $auto_page_break = $this->AutoPageBreak;
        $this->SetAutoPageBreak(false);
        $this->Image($this->background, 0, 0, 217, 284, '', '', '', false, 300, '', false, false, 0);
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        $this->setPageMark();
    }

    /**
     * File Body
     */
    public function doPDF() {

        $this->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        $this->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $this->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $this->SetMargins($this->pdfMarginLeft, $this->pdfMarginTop, $this->pdfMarginRight);
        $this->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->SetFooterMargin(PDF_MARGIN_FOOTER);

        $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        $this->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $this->SetFont('helvetica', '', 8);
        
        $this->writeHTMLCell(0, 0, '', '', $this->sHtml, 0, 1, 0, true, '', true);

        $this->Output($this->namePDF, 'D');
    }
    
    /**
     * File Footer
     */
    public function Footer() {

        $this->SetFont('helvetica', 'B', 12);
        $this->SetTextColor(255, 255, 255);
        $this->SetY(-17);
        $this->SetX(-20);
        //$this->Cell(0, 10, 'Pagina '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');        
        $this->SetFont('helvetica', 'B', 12);
        $this->SetY(-17);
        $this->SetX(20);
        $this->Cell(0, 10, $this->powered, 0, false, 'P', 0, '', 0, false, 'T', 'B');
    }

}