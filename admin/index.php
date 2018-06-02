<?php

/**
    * This file contains the all the functionality and HTML Index page of the site
    * 
    * index.php 
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

set_time_logout();
checkAuthAdmin();

if((int)$_SESSION['usr']['id'] > 0 && ((int)$_SESSION['usr']['role'] == '1' || (int)$_SESSION['usr']['role'] == '2' || (int)$_SESSION['usr']['role'] == '4' ))
{
    
        ob_end_clean();
        header('Location:' . __USER_DASHBOARD_URL__);
    
}elseif((int)$_SESSION['usr']['id'] > 0){
        unset($_SESSION['usr']);
        unset($_SESSION['usr']);
        header("Location:".__URL_BASE__);
}
?>
