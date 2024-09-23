<?php

/**
 * AdminWeb_CasaLibraries CommmonHtmlClass.php
 * 
 * Html Email Template
 *
 * @category AdminWeb_CasaLibraries
 * @package AdminWeb_CasaLibraries_Exportation_CommonHtmlClass.php
 * @copyright (c) 2005-2013, Sistemas CASA, S.A. de C.V. http://www.sistemascasa.com.mx
 * @author Jesús Eduardo Flores Alejandro <jflores@sistemascasa.com.mx>
 * @version AdminWeb 1.0.0
 */

class CommonHtmlClass {

    /**
     * Html Content
     * @var string 
     */
    public $sHtml;
    
    /**
     * Page Title
     * @var string
     */
    public $pageTitle;
    
    /**
     * Css
     * @var string
     */
    public $htmlStyles;
    
    /**
     * Add Css
     * @var string
     */
    public $addHtmlStyles;
    
    /**
     * Months of the Year
     * @var array 
     */
    public $meses = array ('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre' );

    /**
     * Class Constructor
     * @param string $sHtml
     * @param string $htmlStyles
     */
    public function __construct($sHtml = FALSE, $htmlStyles = FALSE) {

        $this->sHtml = $sHtml ? $sHtml : '';
        $this->htmlStyles = $htmlStyles ? $htmlStyles : $this->renderHtmlStyles();
    }

    /**
     * Css Template
     * @return string
     */
    public function renderHtmlStyles() {
        $htmlStyles = '            
                div#wrap {
                    color: #353E4F;
                    background-color: #FFFFFF;
                    height: 100%;
                    width: 95%;
                    vertical-align: top;
                    margin: 0 auto;
                }
                div#content{
                    padding: 0px 0px 0px 0px;
                    background-color: #FFFFFF;
                }
                h1 {
                    font-size: 26px;
                    font-style: normal;
                    font-weight: bold;
                }
                p{
                    font-family: arial,  helvetica, sans-serif;
                    font-size: 25px;
                    color: #353E4F;
                    overflow: hidden;
                }
                .trHeader{
                    background-color: #99BBE8;
                    font-family: arial, helvetica, sans-serif;
                    font-size: 25px;
                    font-weight: bold;
                    color: #FFFFFF;
                    text-align: center;                    
                }
                .trHeaderL2{
                    background-color: #99BBE8;
                    font-family: arial, helvetica, sans-serif;
                    font-size: 25px;
                    font-weight: bold;
                    color: #353E4F;
                    text-align: center;                        
                }                        
            ';
        return $htmlStyles;
    }

    /**
     * Header Renderer
     * @return string
     */
    public function renderHtmlHeader() {
        $htmlHeader = '
            <!DOCTYPE html>
            <html>
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                    <title>' . $this->pageTitle . '</title>
                    <style type="text/css">
                        ' . $this->htmlStyles . $this->addHtmlStyles .'
                    </style> 
                </head>                   
        ';
        return $htmlHeader;
    }

    /**
     * Body Renderer
     * @return string
     */
    public function renderHtmlBody() {
        $htmlBody = '
                <body>
                     <div id="wrap">
                         <div id="content">
                         ' . $this->sHtml . '
                         </div>
                    </div>
                </body>
            

        ';
        return $htmlBody;
    }

    /**
     * Footer Renderer
     * @return string
     */
    public function renderHtmlFooter() {
        $htmlFooter = '                        
            </html>
        ';
        return $htmlFooter;
    }

    /**
     * Content Render
     * @return type
     */
    public function renderHtmlContent() {
        return $this->renderHtmlHeader() . $this->renderHtmlBody() . $this->renderHtmlFooter();
    }

}