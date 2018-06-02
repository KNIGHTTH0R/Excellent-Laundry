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
require_once( __APP_PATH_CLASSES__ . "/product.class.php" );
require_once( __APP_PATH_CLASSES__ . "/common.class.php" );
require_once( __APP_PATH_CLASSES__ . "/order.class.php" );
set_time_logout();
checkAuthCustomer();

$kUser = new cUser();
$kCommon = new cCommon();
$kProduct = new cProduct();
$kOrder = new cOrder();
$pagetitle=__SITE_TITLE__.'Product Description';
$idUser=$_SESSION['usr']['id'];
$productId=$_GET['productId'];
if($_SESSION['newurl'] != $_SERVER['REQUEST_URI']){
    $_SESSION['oldurl'] = $_SESSION['newurl'];
}
$_SESSION['newurl'] = $_SERVER['REQUEST_URI'];
require_once( __APP_PATH_LAYOUT__ . "/customer_header_nav.php" );

$_POST['addCart']['size']=__PRODUCT_SIZE_SMALL__;
$_POST['addCart']['quantity']='1';
if($idUser!=='')
{   
    $customerAry=$kUser->loadCustomers('',$idUser);
    $customerId=$customerAry[0]['id'];
    $cutomerPriceSmall = $kProduct->getProdPriceByProdID((int)($productId),$customerId,__PRODUCT_SIZE_SMALL__);
    $price= $cutomerPriceSmall[0]['price'];
}
$productArr=$kProduct->loadProducts($productId);

?>
    <div class="page-title">
	    <h1>Product Name - <?php echo $productArr['0']['name'];?></h1>
    </div>
<?php
require_once( __APP_PATH_LAYOUT__ . "/trolly.php" );
?>
<div class="clearfix"></div>
    <div id='page_content'>
    <?php
            include( __APP_PATH_FILES__ . "/customerProductDescripton.php" );
    ?>
    </div>
		
        
<?php

include_once(__APP_PATH_LAYOUT__."/customer_footer.php");

?>	

