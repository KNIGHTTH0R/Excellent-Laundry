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
error_reporting(1);
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
$pagetitle = __SITE_TITLE__ . 'Order Details';
$dontShowBackLink = true;
$idUser = $_SESSION['usr']['id'];
$productId = $_GET['productId'];
if($_SESSION['newurl'] != $_SERVER['REQUEST_URI']){
    $_SESSION['oldurl'] = $_SESSION['newurl'];
}
$_SESSION['newurl'] = $_SERVER['REQUEST_URI'];
require_once(__APP_PATH_LAYOUT__ . "/customer_header_nav.php");
if (isset ($_POST['totalitem']) && $_POST['totalitem'] > 0) {
    $totalitems = $_POST['totalitem'];
    for ($i = 0; $i < $totalitems; $i++) {
        $price = $_POST['price' . $i];
        $qty = $_POST['cartqty' . $i];
        $_POST['updatecart']['prQuantity'] = $qty;
        $_POST['updatecart']['id'] = $_POST['cartid' . $i];
        $_POST['updatecart']['prPrice'] = $price * (int)$qty;
        $kOrder->updateCart($_POST['updatecart']);
    }
}
if (isset ($_POST['ordcount']) && $_POST['ordcount'] > 0) {
    $totalitems = $_POST['ordcount'];
    $customerid = $_POST['customerid'];
    $orderprice = $_POST['priceOrd'];
    $orderid = $kOrder->createOrder($customerid, $orderprice);
    if ($orderid && $orderid > 0) {
        for ($i = 0; $i < $totalitems; $i++) {
            $productid = $_POST['ordprodid' . $i];
            $price = $_POST['ordprice' . $i];
            $qty = $_POST['ordqty' . $i];
            $color = $_POST['ordcolor' . $i];
            $_POST['placeord']['prQuantity'] = $qty;
            $_POST['placeord']['prPrice'] = $price;
            $_POST['placeord']['prColor'] = $color;
            $_POST['placeord']['prId'] = $productid;
            $_POST['placeord']['ordid'] = $orderid;
            $kOrder->addOrderDetails($_POST['placeord']);
        }
        $kOrder->deleteCartById($customerid);

    }
    header('location:' . __CUSTOMER_THANK_URL__.$customerid.'/');
    die;

}
$cartArr = $kOrder->loadCart(0,$_SESSION['usr']['customerid']);
?>
    <div class="page-title">
        <?php if(!empty($cartArr)){ ?>
            <h1>Order Details</h1>
        <?php } ?>
    </div>
<?php
require_once( __APP_PATH_LAYOUT__ . "/trolly.php" );
?>
    <div class="clearfix"></div>
    <div class="container-full" id="shopbag">
        <?php
        include(__APP_PATH_FILES__ . "/shopbag.php");
        ?>
    </div>


<?php

include_once(__APP_PATH_LAYOUT__ . "/customer_footer.php");

?>