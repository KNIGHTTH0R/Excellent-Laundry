<?php
/**
    * This file contains the all the functionality and HTML for User Login page
    * 
    * userLogin.php 
    * 
    * @copyright Copyright (C) 2016 Whiz-Solutions
    * @author Whiz Solutions
    * @package Demo Project
*/
ob_start();
session_start();

if( !defined( "__APP_PATH__" ) )
define( "__APP_PATH__", realpath( dirname( __FILE__ ) . "/../" ) );
require_once( __APP_PATH__ . "/includes/constants.php" );
require_once( __APP_PATH_CLASSES__ . "/user.class.php" );
// check for active user session
checkUserCookieSession();
$pagetitle=__SITE_TITLE__.'User Login';
$kUser = new cUser();
if(!empty($_POST['loginAry']))
{
    if($kUser->loginUser($_POST['loginAry']))
    {
        
        if(isset ($_POST['remember']) && $_POST['remember'] == '1'){
           	$cookieData = $_SESSION['usr']['id'] . "~" . $_SESSION['usr']['emailid']."~".$_SESSION['usr']['role'];
          	$encryptedC = $cookieData;
        	setcookie("__pass_user_eq","");
          	setcookie("__pass_user_eq", $encryptedC, time()+60*60*24*8, "/");
		ob_end_clean();
        }
        
        header("Location:".__URL_BASE_ADMIN__);
        die();
    }
}
include( __APP_PATH_VIEW_FILES__ . "/userLogin.php" );

?>
