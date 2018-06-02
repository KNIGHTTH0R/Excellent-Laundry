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
$pagetitle=__SITE_TITLE__.'Error 404';
require_once( __APP_PATH_LAYOUT__ . "/customer_header_nav.php" );

?>
    <div class="page-title">
			<h1>Page Not Found</h1>
    </div>
    <div class="container-full" id="shopbag">
    <?php
            include( __APP_PATH_FILES__ . "/pageerror.php" );
    ?>
    </div>
		
        
<?php

include_once(__APP_PATH_LAYOUT__."/customer_footer.php");

?>	

