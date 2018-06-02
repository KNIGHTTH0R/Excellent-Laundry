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
define( "__APP_PATH__", realpath( dirname( __FILE__ ) . "/" ) );
require_once( __APP_PATH__ . "/includes/constants.php" );
require_once( __APP_PATH_CLASSES__ . "/user.class.php" );
//require_once( __APP_PATH_LAYOUT__ . "/header.php" );
// check for active user session
checkCustomerCookieSession();

$pagetitle=__SITE_TITLE__.'User Login';
$kUser = new cUser();
if(!empty($_POST['loginAry']))
{
    if($kUser->loginUser($_POST['loginAry']))
    {
        $ActiveLoginTime = date('Y-m-d h:i:s');
        $inactiveLogoutTime = date('Y-m-d h:i:s', strtotime('+3 minutes', strtotime($ActiveLoginTime)));
        if(!isset ($_SESSION['usr']['INACTIVELOGOUT'])){
            $_SESSION['usr']['INACTIVELOGOUT'] = $inactiveLogoutTime;
        }
        $customerArr = $kUser->loadCustomers(0, $_SESSION['usr']['id']);
        $_SESSION['usr']['customerid'] = $customerArr[0]['id'];
        $_SESSION['usr']['customergroup'] = $customerArr[0]['groupid'];
        if(isset ($_POST['remember']) && $_POST['remember'] == '1'){
           	$cookieData = $_SESSION['usr']['id'] . "~" . $_SESSION['usr']['emailid']."~".$_SESSION['usr']['role'];
          	$encryptedC = $cookieData;
        	setcookie("__pass_user_eq_customer","");
          	setcookie("__pass_user_eq_customer", $encryptedC, time()+60*60*8, "/");
		ob_end_clean();
        }
        header("Location:".__URL_BASE__);
        die();
    }
}
?>
<div id="customer-login">
<?php
    include( __APP_PATH_FILES__ . "/customersLogin.php" );
?>
</div>
