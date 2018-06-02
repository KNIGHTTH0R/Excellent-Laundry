<?php
/**
    * This file contains the all the functionality and HTML for User Dashboard page
    * 
    * user_dashboard.php 
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
require_once( __APP_PATH_CLASSES__ . "/order.class.php" );
require_once( __APP_PATH_CLASSES__ . "/common.class.php" );
set_time_logout();
checkAuthCustomer();
/*if($_SESSION['usr']['id'] > 0)
{
    ob_end_clean();
    header('Location:' . __MANAGE_CLIENT_URL__);
    die;
}*/
$kUser = new cUser();
$kCommon = new cCommon();
$kOrder = new cOrder();
$pagetitle=__SITE_TITLE__.'My Account';
$dontShowBackLink = true;
$idUser=$_SESSION['usr']['id'];
$customerId=$_SESSION['usr']['customerid'];
if($_SESSION['newurl'] != $_SERVER['REQUEST_URI']){
    $_SESSION['oldurl'] = $_SESSION['newurl'];
}
$_SESSION['newurl'] = $_SERVER['REQUEST_URI'];
require_once( __APP_PATH_LAYOUT__ . "/customer_header_nav.php" );

?>
    
    <div class="page-title">
	<h1>My Account</h1>
    </div>
<?php
require_once( __APP_PATH_LAYOUT__ . "/trolly.php" );
?>
<div class="clearfix"></div>
    <div id="myProfilePageBody">
    <?php
        $orderArr = $kOrder->loadOrder('','',$customerId);
        include( __APP_PATH_FILES__ . "/myAccount.php" );
    ?>
    </div>
<?php
    include_once(__APP_PATH_LAYOUT__."/customer_footer.php");
?>	

