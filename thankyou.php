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
$pagetitle = __SITE_TITLE__ . 'Thank You';
$dontShowBackLink = true;
$idUser = $_SESSION['usr']['id'];
if($_SESSION['newurl'] != $_SERVER['REQUEST_URI']){
    $_SESSION['oldurl'] = $_SESSION['newurl'];
}
$_SESSION['newurl'] = $_SERVER['REQUEST_URI'];
require_once(__APP_PATH_LAYOUT__ . "/customer_header_nav.php");


?>
<div class="page-title">
    <h1><br></h1>
</div>
<div class="container-full thank-message" id="shopbag">

    <?php
    include(__APP_PATH_FILES__ . "/thankyou.php");

    ?>
</div>
<!--<div class="container-full" id="back-instruction">
    <span> Please access the main application by pressing <b> < </b> button on the top left side.</span>
</div>-->

<?php

include_once(__APP_PATH_LAYOUT__ . "/customer_footer.php");

?>	

