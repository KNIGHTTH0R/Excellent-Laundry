<?php
/**
    * This file contains the all the functionality for logging out user from the system
    * 
    * userLogout.php 
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
require_once( __APP_PATH_CLASSES__ . "/product.class.php" );

$kProduct = new cProduct();
//$kProduct->DropCustomerView($_SESSION['usr']['customerid']);
unset($_SESSION['usr']);
unset($_SESSION['oldurl']);
unset($_SESSION['newurl']);
if($_COOKIE['__pass_user_eq_customer']){
    $_COOKIE['__pass_user_eq_customer'] = '';
    unset($_COOKIE['__pass_user_eq_customer']);
    setcookie( '__pass_user_eq_customer', NULL, -1, __COOKIE_PATH__);
}

header("Location:".__CUSTOMERS_LOGIN_URL__);
die();
?>