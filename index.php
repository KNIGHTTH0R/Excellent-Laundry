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
define( "__APP_PATH__", realpath( dirname( __FILE__ ) . "/" ) );
require_once( __APP_PATH__ . "/includes/constants.php" );

set_time_logout(true);
checkAuthCustomer();

if((int)$_SESSION['usr']['id'] > 0 && (int)$_SESSION['usr']['role'] == '3' ){
    
        ob_end_clean();
        header('Location:' . __CUSTOMERS_CATEGORY_LIST_URL__);
    
}
elseif((int)$_SESSION['usr']['id'] > 0 && (int)$_SESSION['usr']['role'] != '3' )
{
        unset($_SESSION['usr']);
        header("Location:".__CUSTOMER_LOGOUT_URL__);
       
}else{
    header("Location:".__CUSTOMERS_LOGIN_URL__);
}
?>
