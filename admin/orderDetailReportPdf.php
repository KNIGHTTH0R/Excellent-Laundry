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
error_reporting(0);
if( !defined( "__APP_PATH__" ) )
define( "__APP_PATH__", realpath( dirname( __FILE__ ) . "/../" ) );
require_once( __APP_PATH__ . "/includes/constants.php" );
require_once( __APP_PATH_CLASSES__ . "/common.class.php" );
require_once( __APP_PATH_CLASSES__ . "/product.class.php" );
require_once( __APP_PATH_CLASSES__ . "/order.class.php" );
require_once (__APP_PATH__."/tcpdf/tcpdf_import.php");
set_time_logout();
checkAuthAdmin();
$pagetitle=__SITE_TITLE__.'Detailed Order Report PDF';
$idUser=$_SESSION['usr']['id'];
if($_SESSION['usr']['role'] != '1')
{
    ob_end_clean();
    header('Location:' . __BASE_URL__);
    die;
}
$kOrder = new cOrder();
$kUser = new cUser();
$kCommon = new cCommon();
$kProduct = new cProduct();
$kDriver =new cDriver();
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Excellent Laundry');
$pdf->SetTitle('Excellent Laundry');
$pdf->SetSubject('Detailed Order Report PDF');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// remove default header/footer
$pdf->setPrintHeader(false);


$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT-10, PDF_MARGIN_TOP-18, PDF_MARGIN_RIGHT-10);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------
if(isset ($_GET['startcreatedon']) && !empty ($_GET['startcreatedon'])){
    $_POST['searchArr']['startcreatedon'] = $_GET['startcreatedon'];
}
if(isset ($_GET['endcreatedon']) && !empty ($_GET['endcreatedon'])){
    $_POST['searchArr']['endcreatedon'] = $_GET['endcreatedon'];
}
if(isset ($_GET['businessname']) && !empty ($_GET['businessname'])){
    $_POST['searchArr']['businessname'] = $_GET['businessname'];
}
if(isset ($_GET['order_number']) && !empty ($_GET['order_number'])){
    $_POST['searchArr']['order_number'] = $_GET['order_number'];
}
if(isset ($_GET['orderstat']) && !empty ($_GET['orderstat'])){
    $_POST['searchArr']['orderstat'] = $_GET['orderstat'];
}
if(isset ($_GET['prod-name']) && !empty ($_GET['prod-name'])){
    $_POST['searchArr']['prod-name'] = $_GET['prod-name'];
}
if(!empty ($_POST['searchArr'])){
$orderArr = $kOrder->loadOrder('',$_POST['searchArr']);
}else{
    $orderArr = $kOrder->loadOrder();
}
// set font
$pdf->SetFont('times', '', 10);
if(!empty($orderArr)){
    $pdf->AddPage();
            $html = '
            <style>
            </style>
            <a style="text-align:center" href="'.__URL_BASE_ADMIN__.'"><img style="width:145px" src="'.__BASE_URL_IMAGES__.'/EL-final-logo200.png" alt="logo" class="logo-default" /> </a>
            <div><p style="text-align:center; font-size:18px; color:#000"><b>Detailed Order Report </b></p></div>
            <table border="1" cellpadding="5" class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" role="grid" aria-describedby="sample_1_info">
                <thead>
                    <tr>
                        <th><b>#</b></th>
                        <th><b>Customer</b></th>
                        <th><b>Order date</b></th>
                        <th><b>Product</b></th>
                        <th><b>Ordered Qty. </b></th>
                        <th> <b>Dispatched Qty.</b> </th>
                        <th> <b>Cost </b></th>
                        <th> <b>Status </b></th>
                    </tr>
                </thead>
                <tbody>';
    $i=1;
    $prodname = '';
    $_POST['searchArr']['prod-name'] = trim($_POST['searchArr']['prod-name']);
    if(!empty($_POST['searchArr']['prod-name'])){
        $prodname = $_POST['searchArr']['prod-name'];
    }
    foreach ($orderArr as $orderData){ 
        $orderProdArr = $kOrder->loadProductOrder($orderData['id'],$prodname);
            if(!empty ($orderProdArr)){
                foreach ($orderProdArr as $prodDet){
                    $html .='<tr>
                               <td>'.$i.'</td>
                                <td>'.$orderData['business_name'].'</td>
                                <td>'.date('d/m/Y',  strtotime($orderData['createdon'])).'</td>
                                <td> '.$prodDet['name'].'</td>
                                <td> '.$prodDet['quantity'].'</td>
                                <td> '.$prodDet['dispatched'].'</td>
                                <td> $'.($prodDet['price']*$prodDet['dispatched']).'</td>
                                <td>'.($orderData['status']=='1'?'Ordered':($orderData['status']=='2'?'Pending':($orderData['status']=='3'?'Dispatched':($orderData['status']=='5'?'Canceled':'')))).'</td>
                            </tr> ';
                    $i++;
                }
            }
    }
    $html .= '</tbody>
                    </table>';
            $pdf->writeHTML($html, true, false, true, false, '');
            $pdf->lastPage();
}else{
    $pdf->AddPage();
            $html = '<h3>No Order Found.</h3>';
            $pdf->writeHTML($html, true, false, true, false, '');
            $pdf->lastPage();
}
$pdf->Output('detail_order_report.pdf', 'I');
?>