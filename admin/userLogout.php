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
define( "__APP_PATH__", realpath( dirname( __FILE__ ) . "/../" ) );
require_once( __APP_PATH__ . "/includes/constants.php" );

unset($_SESSION['usr']);
if($_COOKIE['__pass_user_eq']){
    $_COOKIE['__pass_user_eq'] = '';
    unset($_COOKIE['__pass_user_eq']);
    setcookie( '__pass_user_eq', NULL, -1, __COOKIE_PATH__);
}

header("Location:".__USER_LOGIN_URL__);
die();
?>