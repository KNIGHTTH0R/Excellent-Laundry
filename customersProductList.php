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
$pagetitle=__SITE_TITLE__.'Product List';
$idUser=$_SESSION['usr']['id'];
$prodcatid = $_GET['prodcatid'];
if($_SESSION['newurl'] != $_SERVER['REQUEST_URI']){
    $_SESSION['oldurl'] = $_SESSION['newurl'];
}
$_SESSION['newurl'] = $_SERVER['REQUEST_URI'];
require_once( __APP_PATH_LAYOUT__ . "/customer_header_nav.php" );
$ThisCatArr = $kCommon->loadGroups((int)$prodcatid,0,false);
$ParentCatArr = $kCommon->loadGroups((int)$ThisCatArr[0]['parentid'],0,false);
?>
    
    <div class="page-title">
         
        
	<h2><a href="<?php echo __CUSTOMERS_CATEGORY_LIST_URL__;?>">Home</a> >> <a href="<?php echo __CUSTOMERS_SUB_CATEGORY_LIST_URL__.$ThisCatArr[0]['parentid'].'/';?>"><?php echo $ParentCatArr[0]['name'];?></a> >> <?php echo $ThisCatArr[0]['name'];?></h2>
    </div>
<?php
require_once( __APP_PATH_LAYOUT__ . "/trolly.php" );
?>
<div class="clearfix"></div>
    <div id='page_content'>
    <?php
        $iNumberOfPage = 0;
        $show_pagination = false;
        $iPageNumber = 1;
        $customerProdArr = $kProduct->viewProductByGroupID($prodcatid);
         $iTotalRecords = count($customerProdArr);
        if($iTotalRecords > __TOTAL_ROW_PER_PAGE__)
        {
	    $show_pagination = true;
	    $iNumberOfPage = ceil($iTotalRecords/__TOTAL_ROW_PER_PAGE__);
	    if($iPageNumber > $iNumberOfPage) $iPageNumber = $iNumberOfPage;
            $customerProdArr = $kProduct->viewProductByGroupID($prodcatid,$iPageNumber,__TOTAL_ROW_PER_PAGE__);
        }
        include( __APP_PATH_FILES__ . "/customerProductList.php" );
    ?>
    </div>
<?php
    include_once(__APP_PATH_LAYOUT__."/customer_footer.php");
?>	

