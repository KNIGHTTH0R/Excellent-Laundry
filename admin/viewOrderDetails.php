<?php
/**
    * This file contains the all the functionality to view all Clients in the system
    * 
    * viewClientList.php 
    * 
    * @copyright Copyright (C) 2016 Whiz-Solutions
    * @author Whiz Solutions
    * @package GSI Freight
*/
ob_start();
session_start();

if( !defined( "__APP_PATH__" ) )
define( "__APP_PATH__", realpath( dirname( __FILE__ ) . "/../" ) );
require_once( __APP_PATH__ . "/includes/constants.php" );
require_once( __APP_PATH_CLASSES__ . "/common.class.php" );
require_once( __APP_PATH_CLASSES__ . "/order.class.php" );
set_time_logout();
checkAuthAdmin();
$pagetitle=__SITE_TITLE__.'View Product List';
$idUser=$_SESSION['usr']['id'];
$orderId=$_GET['orderId'];
require_once( __APP_PATH_LAYOUT__ . "/header.php" );
require_once( __APP_PATH_LAYOUT__ . "/headerNavBar.php" );
if($_SESSION['usr']['role'] != '1' && $_SESSION['usr']['role']!='2'  && $_SESSION['usr']['role']!='4' )
{
    ob_end_clean();
    header('Location:' . __BASE_URL__);
    die;
}
$kUser = new cUser();
$kCommon = new cCommon();
$kOrder = new cOrder();
?>
    
        <!-- BEGIN HEADER & CONTENT DIVIDER -->
        <div class="clearfix"> </div>
        <!-- END HEADER & CONTENT DIVIDER -->
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <?php 
                require_once( __APP_PATH_LAYOUT__ . "/leftMenu.php" );
            ?>
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    
                    <!-- BEGIN PAGE BREADCRUMB -->
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="<?php echo __URL_BASE_ADMIN__; ?>">Home</a>
                            <i class="fa fa-circle"></i>
                        </li>
                         <li>
                            <a href="<?php echo __VIEW_ORDER_LIST_URL__; ?>">View order List</a>
                            <i class="fa fa-circle"></i>
                        </li>
                        <li>
                            <span class="active">View Order Details</span>
                        </li>
                    </ul>
                    <!-- END PAGE BREADCRUMB -->
                    <!-- BEGIN PAGE BASE CONTENT -->
                    <div class="row" id='page_content'>
                        <?php
						//$type = $_GET['type'];
                        /*echo $orderId;
                        die;*/
                         $orderDetailsArry = $kOrder->loadOrder($orderId);
                          $orderArr=$kOrder->loadProductOrder($orderId);
                         
                          include( __APP_PATH_VIEW_FILES__ . "/viewOrderDetails.php" );
                        ?>
                    </div>
                </div>
            </div>
        </div>
<?php

include_once(__APP_PATH_LAYOUT__."/footer.php");

?>	
