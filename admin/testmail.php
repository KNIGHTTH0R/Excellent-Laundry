<?php
if( !defined( "__APP_PATH__" ) )
    define( "__APP_PATH__", realpath( dirname( __FILE__ ) . "/../../" ) );

//require_once( __APP_PATH__ . "/includes/constants.php" );
// the message
$from='prashant12it@gmail.com' ;
$subject="Login Details";
//$to=$replace_ary['usEmail'] ;
$to='prashant21it3@gmail.com' ;
$headers ='';
$headers .= "From: ".$from."\r\n";
$cc = '';
$bcc = '';
$content_type = '';
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
$headers .= ($content_type ? $content_type : "Content-type: text/html; charset=UTF-8\r\n");
/*if($textHTML)
{
    $message .= "\n\n------=_NextPart_000_002C_01BFABBF.4A7D6BA0--";
}*/

$body =  "<p>Hi, abc</p>
                              <p>Welcome to Excelent Laundry</p>
                              <h2>Your Login Details</h2>
                              <p>Email Id: ".$to."</p>
                              <p>Password: qwerty</p>
                              <p>To change your password, please login to the ordering system and click on My Account.</p>
                              <p>Thanks</p>
                              <p>Excelent Laundry Team</p>
                              <p><img src='http://dev.mobileconnekt.com.au/images/EL-final-logo200.png' style='width:200px' /></p>";
$mail_sent = mail( $to, $subject, $body,$headers);
if($mail_sent){
    echo 'mail sent';
}
?>