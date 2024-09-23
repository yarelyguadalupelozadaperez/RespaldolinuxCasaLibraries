<?php
/**
 * AdminWeb_CasaLibraries SendMailCasa
 * 
 * Send Mail
 *
 * @category AdminWeb_CasaLibraries
 * @package AdminWeb_CasaLibraries_Util_SendMailCasa
 * @copyright (c) 2005-2013, Sistemas CASA, S.A. de C.V. http://www.sistemascasa.com.mx
 * @author Jesús Eduardo Flores Alejandro <jflores@sistemascasa.com.mx>
 * @version AdminWeb 1.0.0
 */


/**
 * @see class.phpmailer.php
 */
require_once 'PHPMailer/class.phpmailer.php';

/**
 * @see class.smtp.php
 */
require_once 'PHPMailer/class.smtp.php';

class SendMailCasa extends phpmailer {

    /**
     * Default mail address
     * @var string
     */
    public static $userName = 'maileradmx@aduanas-mexico.com.mx';
    
    /**
     * Mail password
     * @var string
     */
    public static $password = 'mailer4du4';
    
    /**
     * Host address
     * @var string
     */
    public static $hostName = 'mail.sistemascasa.com.mx';
    
    /**
     * Email account
     * @var string 
     */
    public static $fromMail = 'maileradmx@aduanas-mexico.com.mx';
    
    /**
     * Sender mail
     * @var string 
     */
    public static $fromName = 'Sistemas Casa, S.A. de C.V.';

    /**
     * Set User Name
     * @param string $userName
     */
    public static function setUserName($userName = '') {
        self::$userName = $userName;
    }

    /**
     * Set Password 
     * @param string $password
     */
    public static function setPassword($password = '') {
        self::$password = $password;
    }

    /**
     * Set Host Name
     * @param string $hostName
     */
    public static function setHostName($hostName = '') {
        self::$hostName = $hostName;
    }

    /**
     * Set Sender Email
     * @param string $fromMail
     */
    public static function setFromMail($fromMail = '') {
        self::$fromMail = $fromMail;
    }

    /**
     * Set Sender Name
     * @param string $fromName
     */
    public static function setFromName($fromName = '') {
        self::$fromName = $fromName;
    }

    /**
     * Send Email
     * @param string $body
     * @param string $to
     * @param string $subject
     * @param string $toCC
     * @param string $attachment
     * @return int
     */
    public static function sendEmail($body, $to, $subject, $toCC = FALSE, $attachment = FALSE, $isHtml = FALSE) {
        
        $isHtml = !$isHtml?true:false;
        
        $email = new PHPMailer();
        $email->IsSMTP();
        $email->SMTPAuth = true;
        $email->Username = self::$userName;
        $email->Password = self::$password;
        $email->Host = self::$hostName;
        $email->From = self::$fromMail;
        $email->FromName = self::$fromName;
        $email->CharSet = 'UTF-8';
        $email->Encoding = "quoted-printable";
        $email->Port = 587;
        $email->Subject = $subject;
        $email->IsHTML($isHtml);
        $email->WordWrap = 50;
        $email->Body = $body;
        $email->Timeout = 20;

        if ($toCC) {
            if (is_array($toCC)) {
                for ($i = 0; $i < count($toCC); $i++)
                    $email->AddCC($toCC[$i]);
            }
            else
                $email->AddCC($toCC);
        }

        if ($attachment) {
            if (is_array($attachment)) {
                for ($i = 0; $i < count($attachment); $i++)
                    $email->AddAttachment($attachment[$i]);
            }
            else
                $email->AddAttachment($attachment);
        }

        if (is_array($to)) {
            for ($i = 0; $i < count($to); $i++) {
                $email->AddAddress($to[$i]);
            }
        }
        else
            $email->AddAddress($to);

        if (!$email->Send()) {
            $rcorreo = 0;
            return $horror = $email->Host . ' ' . $email->ErrorInfo;
        }
        else
            $rcorreo = 1;
        $email->ClearAddresses();

        return $rcorreo;
    }

}