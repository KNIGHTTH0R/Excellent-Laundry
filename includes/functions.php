<?php

/**
 * This file contains the functions that are generic for the store and can be used by all the php files in the store website.
 *
 * functions.php
 *
 * @copyright Copyright (C) 2016 Whiz-Solutions
 * @author Whiz Solutions
 * @package Demo Project
 */

if( !defined( "__APP_PATH__" ) )
define( "__APP_PATH__", realpath( dirname( __FILE__ ) . "/../../" ) );

require_once( __APP_PATH__ . "/includes/constants.php" );
require_once( __APP_PATH_CLASSES__ . "/database.class.php" );
//require_once( __APP_PATH__ . "/tcpdf/tcpdf.php");
//require_once( __APP_PATH__ . "/tcpdf/config/tcpdf_config.php");

/**
 * Function to Sanitize all the HTML input
 * @param string $value
 * @return string
 */
function sanitize_all_html_input($value)
{
    if(!empty($value))
    {
            $value=strip_tags($value);
    }
    if(strpos($value,"'>") !== false)
            $value=str_replace("'>","",$value);
    if(strpos($value,'">') !== false)
            $value=str_replace('">',"",$value);
    if(strpos($value,'"') !== false)
            $value=str_replace('"',"",$value);
    return $value;
}

/**
 * Function to Sanitize Specific the HTML input
 * @param string $value
 * @return string
 */
function sanitize_specific_html_input($value)
{
    $allwedTags="<p><br><a><b><u><i>";

    if(!empty($value))
    {
            $value=strip_tags($value,$allwedTags);
    }
    return $value;
}

/**
 * Function to Write log in temp.log file
 * @param string $szLogString
 */
function write_log($szLogString)
{
    $szLogFileName = __APP_PATH_LOGS__ . "/temp.log";
    $file = fopen($szLogFileName, "a");
    fwrite($file, $value);
    fclose($file);
}

/**
 * Function to Encrypt any string with a key
 * @param string $string
 * @param string $key
 * @return string
 */
function encrypt($string, $key)
{
    return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key))));
}

/**
 * Function to Decrypt any string with the key
 * @param string $encrypted
 * @param string $key
 * @return string
 */
function decrypt($encrypted, $key)
{
    return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($encrypted), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
}

/**
 * Function to cut the string by a given limit
 * @param string $data
 * @param int $limit
 * @return string
 */
function returnLimitData($data,$limit)
{
    if(strlen($data)>$limit)
    {
        $szDisplayText=substr_replace($data,' ....',$limit);
    }
    else
    {
        $szDisplayText=$data;
    }

    return '<span title="'.$data.'">'.$szDisplayText.'</span>';
}
function sanitize_specific_tinymce_html_input($value)
{
	$html_tags_allowed='<a><p><img><br /><center><em><head><h1><h2><h3><h4><h5><h6><strong><style><span>
						<table><tbody><td><th><thead><tr><tt><u><ul><li><small><param><object><ol><q><s><html>
						<body><button><div><small><textarea><input>
						<big><title><embed><b><br><br/>';

	if(!empty($value))
	{
		$value=strip_tags($value,$html_tags_allowed);
	}

	return $value;
}
function sanitize_specific_tinymce_html_script_input($value)
{
	$html_tags_allowed='<script><a><p><img><br /><center><em><head><h1><h2><h3><h4><h5><h6><strong><style><span>
						<table><tbody><td><th><thead><tr><tt><u><ul><li><small><param><object><ol><q><s><html>
						<body><button><div><small><textarea><input>
						<big><title><embed><b>';

	if(!empty($value))
	{
		$value=strip_tags($value,$html_tags_allowed);
	}
	return $value;
}

function CleanTitle($Title)
{
	$Title = preg_replace('/\s+/', '_', $Title);
	$Title = preg_replace('/\W+/', '', $Title);
	$Title = preg_replace('/[\_]+/', '-', $Title);
	$Title = preg_replace('/\-$/', '', $Title);
	$Title = strtolower($Title);

	return $Title;
}

function display_form_validation_error_message($formId,$validationErrorAry)
{
	if(!empty($validationErrorAry))
	{//print_r($validationErrorAry);
		foreach($validationErrorAry as $key=>$validationErrorArys)
		{
			if($key =='iAgree')
			{
				$key = 'term_error';
			}
			?>
				<script type="text/javascript">
					displayFormFieldIsRequired('<?php echo $formId; ?>', '<?php echo $key; ?>', '<?php echo $validationErrorArys;?>');
				</script>
	<?php 	
	    }
	}
}
function createEmail($email_template, $replace_ary, $to, $subject, $reply_to, $idUser=0, $from=__SUPPORT_EMAIL__,$flag=0,$pdfAry=array(),$fileNameAry=array())
{ 
    $email_template = trim($email_template);
    $kDatabase = new cDatabase();
   // $kDatabase->connect( __DBC_USER__, __DBC_PASSWD__, __DBC_SCHEMATA__, __DBC_HOST__);

    $query = "
        SELECT
            section_description,
            subject
        FROM
            ".__DBC_SCHEMATA_EMAIL_CMS_SECTION__."
        WHERE
            section_title = '".sql_real_escape_string($email_template)."'
    ";
	//echo "<br>".$query;
	
    ob_start();  
    include(__APP_PATH_ROOT__.'/layout/email_header.php');
    $message = ob_get_clean();
    //$message = file_get_contents(__BASE_URL_SECURE__.'/templates/email-header.php');
    if ($result = $kDatabase->exeSQL($query))
    {
        if ($kDatabase->iNumRows > 0)
        {
            $row = $kDatabase->getAssoc($result);
            $message .= $row['section_description'];
            if(!empty($row['subject']))
            {
            	$subject=$row['subject'];
            }
        }
    }
    else
    {
        $kDatabase->error = true;
        $szErrorMessage = __CLASS__ . "::" . __FUNCTION__ .  "() failed because of a mysql error. SQL: " . $query . " MySQL Error: " . mysql_error();
        $kDatabase->logError( "input", $szErrorMessage, "PHP", __CLASS__, __FUNCTION__, __LINE__, "critical");
        return false;
    }
   
    if (count($replace_ary) > 0)
    {
        foreach ($replace_ary as $replace_key => $replace_value)
        {
            $message = str_replace($replace_key, $replace_value, $message);
            $subject= str_replace($replace_key, $replace_value, $subject);
        }
    }


    /*$message .= file_get_contents(__APP_PATH_ROOT__.'/templates/email-footer.php');*/
    ob_start();
    include(__APP_PATH_ROOT__.'/layout/email_footer.php');
    $message .= ob_get_clean();
	
  	//echo $message;
   
	sendEmail($to,$from,$subject,$message,$reply_to, $idUser,$pdfAry,$fileNameAry);    
   
}


function sendEmail($to,$from=__SUPPORT_EMAIL__,$subject,$message,$reply_to=__SUPPORT_EMAIL__,$idUser=0,$pdfAry='',$file_NameAry='',$cc=false,$bcc=false,$content_type=false,$encoding=false,$textHTML=false)
{
        
    if(!empty($pdfAry))
    {
            $separator = md5(time());
            $eol = PHP_EOL;
            $from = '"GSI FREIGHT" <'.$from.'>';			
            // main header (multipart mandatory)
            $headers = "From: $from" . $eol;
            $headers .= "MIME-Version: 1.0" . $eol;
            $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
            $headers .= "Content-Transfer-Encoding: 7bit";
            //$headers .= "This is a MIME encoded message.".$eol.$eol;

            // message
            $body = "--".$separator.$eol;
            $body .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol;
            $body .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
            $body .= $message.$eol.$eol;

            // attachment
            for($i=0;$i<count($pdfAry);$i++)
            {
                $filename = $pdfAry[$i];
                $handle = fopen($filename, 'rb');
                $contents = fread($handle,filesize($filename));
                //echo $contents;
                fclose($handle);
                $encoded = chunk_split(base64_encode($contents));

                $body .= "--".$separator.$eol;
                $body .= "Content-Type: application/octet-stream; name=\"".$file_NameAry[$i]."\"".$eol; 
                $body .= "Content-Transfer-Encoding: base64".$eol;
                $body .= "Content-Disposition: attachment".$eol.$eol;
                $body .= $encoded.$eol.$eol;
            }

            //echo "<br/>".$to;

            //print_r($headers);
            $mail_sent=@mail($to,$subject,$body, $headers);

        if($mail_sent)
        {
            $success = 1;

        }
        else
        {
            $success = 0;
        }
    }
    else
    {
        $from = '"GSI FREIGHT" <'.$from.'>';		
        $headers .= "From: ".$from."\r\n";
        if($cc)
        {
            $headers .= "cc: ".$cc."\r\n";
        }
            if($bcc)
        {
            $headers .= "bcc: ".$bcc."\r\n";
        }

        $headers .= "Reply-To: ".$from."\r\n";
        $headers .= "Subject: ".$subject."\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= ($content_type ? $content_type : "Content-type: text/html; charset=iso-8859-1\r\n");
            $headers .= ($encoding ? "" : "Content-Transfer-Encoding: 8bit\r\n");	

            if($textHTML)
            {
                    $message .= "\n\n------=_NextPart_000_002C_01BFABBF.4A7D6BA0--";
            }

        $body = str_replace('\\r', '', trim($message));
            //$to = 'pranav@whiz-solutions.com';
        $mail_sent = @mail( $to, $subject, $body, $headers);

        /*$handle = fopen(__EMAIL_LOG_PATH__."/email.log", "w");
            fwrite($handle, "#################################".$subject."################################\n\nTo: ".$to."\n".$headers."\r\n".$body."\n\nStatus = ".$mail_sent);
            fclose($handle);
*/
        if($mail_sent)
        {
            $success = 1;

        }
        else
        {
            $success = 0;
        }
    }
    logEmails($idUser,$message,$subject,$to,$success,$flag=false);
}

function logEmails($idUser,$message,$subject,$to,$success,$flag=false)
{
    $kDatabase = new cDatabase();
    $query = "
    	INSERT INTO
    		".__DBC_SCHEMATA_EMAIL_LOG__."
    		(
    			idUser,
    			iMode,
    			szEmailBody,
    			szEmailSubject,
    			szToAddress,
    			dtSent,
    			iSuccess
    		)
    	VALUES
    		(
    			".(int)$idUser.",
    			".(int)$flag.",
    			'".sql_real_escape_string($message)."',
    			'".sql_real_escape_string($subject)."',
    			'".sql_real_escape_string($to)."',
    			NOW(),
    			".(int)$success."
    		)
    ";
	//echo $query;
	if ($kDatabase->exeSQL($query))
	{
		return true;
	}
	else
	{
		$kDatabase->error = true;
		$szErrorMessage = __CLASS__ . "::" . __FUNCTION__ .  "() failed because of a mysql error. SQL: " . $query . " MySQL Error: " . mysql_error();
		$kDatabase->logError( "input", $szErrorMessage, "PHP", __CLASS__, __FUNCTION__, __LINE__, "critical");
		return false;
	}
}

function create_login_key()
{
	$chars = "234567890abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$i = 0;
	$login_key = "";
	while ($i <= 20)
	{
		$login_key .= $chars{mt_rand(0,strlen($chars)-1)};
		$i++;
	}
	return $login_key;
}

function sql_real_escape_string($str)
{
      global $dbh_sess;
      return mysqli_real_escape_string($dbh_sess, $str); }
 
function sql_error()
{
      global $dbh_sess;
      return $dbh_sess->error;
}

/**
 * Function to check for active user session
 */
function checkUserCookieSession()
{
    if((int)$_SESSION['usr']['id']>0 &&   ((int)$_SESSION['usr']['role'] == '1' || (int)$_SESSION['usr']['role'] == '2' || (int)$_SESSION['usr']['role'] == '4' ))
    {
        ?>
            <script type="text/javascript">
                window.location.replace("<?php echo __USER_DASHBOARD_URL__; ?>");
            </script>
        <?php
        die();
    }
}

function checkCustomerCookieSession()
{
    if((int)$_SESSION['usr']['id']>0) {
        if ((int)$_SESSION['usr']['role'] == '3') { ?>
            <script type="text/javascript">
                window.location.replace("<?php echo __URL_BASE__; ?>");
            </script>
            <?php
            die();
        }
    }
}

/**
* This function used to checked authentication of logged in user
* @access public
* @param string $ajax_flag
* @return boolean
* @author Pranav
*/

function checkAuthAdmin($ajax_flag=false)
{
    $demoUserAry = array();
    $demoUserAry = get_admin_session_data();

    if(isset($demoUserAry['idDemoUser']))
    {
        if(($demoUserAry['idDemoUser'])<= 0)
        {
            // If login session is not exists then we just redirect user to login page.
            $_SESSION['szHttpReferer'] = $_SERVER['REQUEST_URI'];
            if($ajax_flag)
            {
                ?>
                <script type="text/javascript">
                        window.location.replace("<?php echo __USER_LOGOUT_URL__; ?>");
                </script>
                <?php
            }
            else
            {
                ob_end_clean();
                header('Location:' . __USER_LOGOUT_URL__);
                die;
            }
        }
    }
    else
    {
        ob_end_clean();
        header('Location:' . __USER_LOGIN_URL__);
    }
} 

function checkAuthCustomer($ajax_flag=false)
{
    $demoCustomerAry = array();
    $demoCustomerAry = get_customer_session_data();

    if(isset($demoCustomerAry['idDemoUser']))
    {
        if(($demoCustomerAry['idDemoUser'])<= 0)
        {
            // If login session is not exists then we just redirect user to login page.
            $_SESSION['szHttpReferer'] = $_SERVER['REQUEST_URI'];
            if($ajax_flag)
            {
                ?>
                <script type="text/javascript">
                        window.location.replace("<?php echo __CUSTOMER_LOGOUT_URL__; ?>");
                </script>
                <?php
                die;
            }
            else
            {
                ob_end_clean();
                header('Location:' . __CUSTOMER_LOGOUT_URL__);
                die;
            }
        }
    }
    else
    {
        ob_end_clean();
        header('Location:' . __CUSTOMERS_LOGIN_URL__);
    }
}

function openPanelByCapability(){
    if($_SESSION['usr']['idcheck'] > 0)
    {
        if($_SESSION['usr']['activecheck'] == '0')
        {
            ob_end_clean();
            header('Location:' . __BASE_URL__);
            die;
        }elseif($_SESSION['usr']['iAdmin'] != $_SESSION['usr']['iAdmincheck']){
            $_SESSION['usr']['iAdmin'] = $_SESSION['usr']['iAdmincheck'];
            if($_SESSION['usr']['iAdmincheck'] == '1'){
            ob_end_clean();
            header('Location:' . __VIEW_CLIENT_LIST_URL__);
            die;
            }  else {
                ob_end_clean();
                header('Location:' . __BASE_URL__);
                die;
            }
        }elseif($_SESSION['usr']['superuser'] != $_SESSION['usr']['superusercheck']){
            $_SESSION['usr']['superuser'] = $_SESSION['usr']['superusercheck'];
            if($_SESSION['usr']['superusercheck'] == '1'){
                ob_end_clean();
                header('Location:' . __VIEW_PARTS_LIST_URL__);
                die;
            }else {
                ob_end_clean();
                header('Location:' . __BASE_URL__);
                die;
            }
        }elseif($_SESSION['usr']['receive'] != $_SESSION['usr']['receivecheck']){
            $_SESSION['usr']['receive'] = $_SESSION['usr']['receivecheck'];
            if($_SESSION['usr']['receivecheck'] == '1'){
                ob_end_clean();
                header('Location:' . __FIND_INVENTORY_PRODUCT_URL__);
                die;
            }else {
                ob_end_clean();
                header('Location:' . __BASE_URL__);
                die;
            }
        }
    }else{
        ob_end_clean();
            header('Location:' . __USER_LOGOUT_URL__);
            die;
    }
}

/**
* This function is used to get session id of logged in user.
* @access public
* @param string, string
* @return string
* @author Pranav
*/
function get_admin_session_data()
{
    if((int)$_SESSION['usr']['id']>0)
    {
        
        $demoUserAry = array();
        $demoUserAry['idDemoUser'] = $_SESSION['usr']['id'] ;
        return $demoUserAry;
    }
}
function get_customer_session_data()
{
    if((int)$_SESSION['usr']['id'] > 0 && (int)$_SESSION['usr']['role'] == '3' )
    {
        $demoUserAry = array();
        $demoUserAry['idDemoUser'] = $_SESSION['usr']['id'] ;
        return $demoUserAry;
    }
}

function sendMsgEmail($replace_ary)
{
    $kDatabase = new cDatabase();

    //$to = $replace_ary['usEmail'];
    $to='julie@excellentlaundry.com.au' ;
    //$to='prashant12it@gmail.com' ;
    $subject = "Customer login details";

    $message = "
<html>
<head>
<title>HTML email</title>
</head>
<body>
<p>Hi, ".$replace_ary['usContactName']."</p>
<p>Welcome to Excelent Laundry</p>
<h2>Your Login Details</h2>
<p>Email Id: ".$replace_ary['usEmail']."</p>
<p>Password: ".$replace_ary['password']."</p>
<p>To change your password, please login to the ordering system and click on My Account.</p>
<p>Thanks</p>
<p>Excelent Laundry Team</p>
<p><img src='".__BASE_URL_IMAGES__."/EL-final-logo200.png' style='width:200px' /></p>
</body>
</html>
";

// Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
    $headers .= 'From: <'.__SUPPORT_EMAIL__.'>' . "\r\n";

    $mail_sent = mail($to,$subject,$message,$headers);

    /*$to = $replace_ary['usEmail'];
    $subject = "Login Details";

    $message = "<html>
<head>
<title>HTML email</title>
</head>
<body><p>Hi, ".$replace_ary['usContactName']."</p>
                              <p>Welcome to Excelent Laundry</p>
                              <h2>Your Login Details</h2>
                              <p>Email Id: ".$replace_ary['usEmail']."</p>
                              <p>Password: ".$replace_ary['password']."</p>
                              <p>To change your password, please login to the ordering system and click on My Account.</p>
                              <p>Thanks</p>
                              <p>Excelent Laundry Team</p>
                              <p><img src='".__BASE_URL_IMAGES__."/EL-final-logo200.png' style='width:200px' /></p></body>
</html>
";
// Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
    $headers .= 'From: <prashant12it@gmail.com>' . "\r\n";


    $mail_sent = mail($to,$subject,$message,$headers);*/
    /*$from=__SUPPORT_EMAIL__ ;
    $subject="Login Details";
    $to=$replace_ary['usEmail'] ;
    //$to='fawadamin89@hotmail.com' ;
    $headers ='';
    $headers .= "From: ".$from."\r\n";
    $cc = '';
    $bcc = '';
    $content_type = 'Content-type: text/plain; charset=utf-8\r\n';
    $encoding = '';
    $textHTML = '';
    $message = '';
    if($cc)
    {
        $headers .= "cc: ".$cc."\r\n";
    }
    if($bcc)
    {
        $headers .= "bcc: ".$bcc."\r\n";
    }

    $headers .= "Reply-To: ".$from."\r\n";
    $headers .= "Subject: ".$subject."\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= ($content_type ? $content_type : "Content-type: text/html; charset=iso-8859-1\r\n");
    $headers .= ($encoding ? "" : "Content-Transfer-Encoding: 8bit\r\n");

    if($textHTML)
    {
        $message .= "\n\n------=_NextPart_000_002C_01BFABBF.4A7D6BA0--";
    }

    $body =  "<p>Hi, ".$replace_ary['usContactName']."</p>
                              <p>Welcome to Excelent Laundry</p>
                              <h2>Your Login Details</h2>
                              <p>Email Id: ".$replace_ary['usEmail']."</p>
                              <p>Password: ".$replace_ary['password']."</p>
                              <p>To change your password, please login to the ordering system and click on My Account.</p>
                              <p>Thanks</p>
                              <p>Excelent Laundry Team</p>
                              <p><img src='".__BASE_URL_IMAGES__."/EL-final-logo200.png' style='width:200px' /></p>";
    $mail_sent = mail( $to, $subject, $body,$headers);*/
    if($mail_sent){
        $query = "
               INSERT INTO
                   " . __DBC_SCHEMATA_EMAIL_LOG__ . "
               (
                   
                   toemail,
                   fromemail,
                   subject,
                   content,
                   sendtime
               )
               VALUES
               (
                   '" . sql_real_escape_string($to) . "',
                   '" . sql_real_escape_string(__SUPPORT_EMAIL__) . "',
                   '" . sql_real_escape_string($subject) . "',
                   '" . sql_real_escape_string($message) . "',
                   '" . sql_real_escape_string(date('Y-m-d h:i:s')) . "'
               )	
       ";
        if ($result = $kDatabase->exeSQL($query)) {
            return $mail_sent;
        } else {
            return $mail_sent;
        }
    }
}
/**
 * Function to trim extra spaces in between string
 * @param string $input_string
 * @return string
 */
function trim_spaces($input_string)
{
    $cleanStr = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $input_string)));
    return $cleanStr;
}


function clear_ie_cache()
{
	header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	//header("Pragma: no-cache");
}
function create_login_password()
{
	$chars = "234567890abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$i = 0;
	$login_key = "";
	while ($i <= 5)
	{
		$login_key .= $chars{mt_rand(0,strlen($chars)-1)};
		$i++;
	}
	return $login_key;
}

function set_time_logout($customerCookie = false)
{
    
	$session_life = '';
	$inactive = 57600;
	$current_time = time();
    if($customerCookie){
        check_customer_cookie();
    }else{
        check_users_cookie();
    }

	if(!empty($_SESSION['usr']['timeout']))
	{	
		$session_life = $current_time - $_SESSION['usr']['timeout'];
		if($session_life > $inactive)
		{
                    
			$_SESSION['time_out']="Sorry, you have been logged out due to inactivity.  Please login again.";
		 	$_SESSION['usr']=array();
		 	unset($_SESSION['usr']);
			setcookie('__pass_user_eq', '');
			setcookie('__pass_user_eq', '', time()-60*60*8, "/");
			unset($_COOKIE['__pass_user_eq']);
		}
		else
		{	
			unset($_SESSION['time_out']);
			$_SESSION['usr']['timeout']=$current_time;
		}
	}
	else
	{	
		unset($_SESSION['time_out']);
		$_SESSION['usr']['timeout']=$current_time;
	}
}

function check_users_cookie(){
    if(isset($_COOKIE['__pass_user_eq']))
    {
        $_SESSION['usr']['timeout'] = time();
        list($_SESSION['usr']['id'],$_SESSION['usr']['emailid'],$_SESSION['usr']['role']) = explode("~", $_COOKIE["__pass_user_eq"]);
        return true;
    }
}
function check_customer_cookie(){
    if(isset($_COOKIE['__pass_user_eq_customer']))
    {
        $_SESSION['usr']['timeout'] = time();
        list($_SESSION['usr']['id'],$_SESSION['usr']['emailid'],$_SESSION['usr']['role']) = explode("~", $_COOKIE["__pass_user_eq_customer"]);
        return true;
    }
}

function generateReportPdf($str,$name)
{
	if(!empty($str))
	{
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Warehouse');
		$pdf->SetTitle('Report');
		$pdf->SetSubject('Report');
		$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

		//define('PDF_HEADER_LOGO',__APP_PATH__."/images/colego_logo_email.png");
		//echo "logo".K_PATH_IMAGES;

		// set default header data
		$pdf->SetHeaderData('', '', '', '','',array(255,255,255));

		// set header and footer fonts
		//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
		//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}

		// ---------------------------------------------------------

		// set default font subsetting mode

		$pdf->setFontSubsetting(true);
		//$fontname=$pdf->addTTFfont(__APP_PATH_FILE__.'/Arial.ttf','TrueTypeUnicode', '', 32);

		// set fonttimes
		//$pdf->SetFont('freeserif', '', 12);
		//$pdf->SetFont($fontname, '', 11);
		$pdf->SetFont('arial', '', 11);

		// add a page
		$pdf->AddPage();
		//$pdf->Image('/images/colego_logo.png', 50, 50, 40, 40, '', '', '', true, 300, '', false, false, 1, false, false, false);

		/*$path=$_SERVER['REQUEST_URI'];
		$Arpath=explode('/',$path);
		$cvr='10000297';
		$pdfData='';
		$idinvoice='';*/

		//$pdfPreview=showOrderInvoicehtmlFormat($cvr);
			
		//echo $pdfPreview;
		if(!empty($str))
		{
			$filename=$name.'.pdf';
			$filename1=__APP_PATH_ROOT__.'/pdf_reports/'.$filename;

			$pdf->writeHTML($str, true, false, true, false, '');
			$output=$pdf->Output($filename, 'S');
			$fp =fopen($filename1,'wa+');
			fwrite($fp,$output);
			fclose($fp);
			//die();
		}
	}
}

function paginationBlock($count,$jsfunction,$link,$para1=false,$para2=false,$para3=false)
{
	$pageNum = ceil($count / __RECORD_PER_PAGE__);
	if($pageNum > 1)
	{
		
	?>
		<div style="text-align:right">
			<ul class="pagination">
				<?php
				for($i=1;$i<=$pageNum;$i++)
				{
					if($jsfunction)
					{
						$jsF = 'onclick="hitpagination('.$jsfunction.',\''.$i.'\',\''.$para1.'\',\''.$para2.'\',\''.$para3.'\')"';
					}
					if($link != 'javascript:void(0);' && $link != '')
					{
						$new_link = $link.$i.'/';
					}
					else
						$new_link = $link; 
					?>
					<li><a href="<?php echo $new_link?>" <?php echo $jsF ?>><?php echo $i?> </a></li>
				<?php }?>
			</ul>
		</div>
	<?php
	}
}

function getSqlFormattedDate($unFormatted_date)
{
    $dateAry=explode('/', $unFormatted_date);
    $formattedDate=$dateAry['2'].'-'.$dateAry['1'].'-'.$dateAry['0'];
    return $formattedDate;
    
}
function show_pagination($iPageNumber,$iNumberOfPage)
 {
   
 	$iPageRemainder = $iPageNumber % __TOTAL_PAGE_LINK_ON_LIST__;
 	$iPageDivident = (int)($iPageNumber/__TOTAL_PAGE_LINK_ON_LIST__); 	
 	$iStratIndex = ($iPageRemainder == 0 ? ((($iPageDivident-1)*__TOTAL_PAGE_LINK_ON_LIST__)+1) : ((($iPageDivident)*__TOTAL_PAGE_LINK_ON_LIST__)+1));
 	$iEndIndex = ($iStratIndex - 1) + __TOTAL_PAGE_LINK_ON_LIST__;
 	if($iEndIndex > $iNumberOfPage) $iEndIndex = $iNumberOfPage;
 	?>
 	<div class="container-full pagination-container">
            <ul class="pagination">
	    <?php if($iPageNumber > 1) {?>
		<li><a href="javascript:void(0);" onclick="productPagination('<?php echo $iPageNumber - 1;?>')">&#8249;</a></li>
		<?php } else {?>
		<li><a href="javascript:void(0);">&#8249;</a></li>
		<?php } for($i = $iStratIndex; $i <= $iEndIndex; $i++){?>
		<li<?=($i == $iPageNumber ? ' class="active"' : '')?>><a href="javascript:void(0);" onclick="productPagination('<?php echo $i;?>')"><?=$i?></a></li>
		<?php } if($iPageNumber < $iNumberOfPage) {?>
		<li><a href="javascript:void(0);" onclick="productPagination('<?php echo $iPageNumber + 1;?>')">&#8250;</a></li>
		<?php } else {?>
		<li><a href="javascript:void(0);">&#8250;</a></li>
		<?php } ?>
            </ul>
</div>
	<?php
 }
 
 function sendForgetEmail($replace_ary)
{ 
  
    $from=__SUPPORT_EMAIL__ ;
    $subject="Forget Password Details";
    $to=$replace_ary['email'] ;
    //$to = 'prashant12it@gmail.com';
    $headers = '';
    $headers .= "From: ".$from."\r\n";

    $headers .= "Reply-To: ".$from."\r\n";
    $headers .= "Subject: ".$subject."\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
    $headers .= "Content-Transfer-Encoding: 8bit\r\n";

    $body =  $formcontent = "<p>Hi </p>
                              <p>Welcome to Excelent Laundry</p>
                              <h2>Your New Password</h2>
                              <p>Email Id: ".$replace_ary['email']."</p>
                              <p>Password: ".$replace_ary['password']."</p>
                              <p>Thanks</p>
                              <p>Excelent Laundry Team</p>
                              <p><img src='".__BASE_URL_IMAGES__."/EL-final-logo200.png' style='width:200px' /></p>"; 
                              $mail_sent = @mail( $to, $subject, $body, $headers);
    if($mail_sent){
        return true;
    }else{
        return false;
    }
}

?>