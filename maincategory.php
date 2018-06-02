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

if (!defined("__APP_PATH__"))
    define("__APP_PATH__", realpath(dirname(__FILE__) . "/"));
require_once(__APP_PATH__ . "/includes/constants.php");
require_once(__APP_PATH_CLASSES__ . "/product.class.php");
require_once(__APP_PATH_CLASSES__ . "/common.class.php");
require_once(__APP_PATH_CLASSES__ . "/order.class.php");
set_time_logout();
checkAuthCustomer();

$kUser = new cUser();
$kCommon = new cCommon();
$kProduct = new cProduct();
$kOrder = new cOrder();
$pagetitle = __SITE_TITLE__ . 'Home';
$idUser = $_SESSION['usr']['id'];
$productId = $_GET['productId'];
$dontShowBackLink = true;
$_SESSION['oldurl'] = __BASE_URL__;
$_SESSION['newurl'] = __BASE_URL__;
require_once(__APP_PATH_LAYOUT__ . "/customer_header_nav.php");

?>
    <div class="page-title">
        <h1>Welcome</h1>
    </div>
<?php
require_once( __APP_PATH_LAYOUT__ . "/trolly.php" );
?>
<div class="clearfix"></div>
    <div class="container-full" id="shopbag">
        <?php
        $MainCatArr = $kCommon->loadGroups();
        include(__APP_PATH_FILES__ . "/maincategory.php");
        ?>
    </div>
    <div class="container-full" id="back-instruction">
        <p><span class="order-more">Press < </span> button on the top left side to return to Application Home Page</p>
    </div>
<?php

include_once(__APP_PATH_LAYOUT__ . "/customer_footer.php");

?>	

