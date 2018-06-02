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

if (!defined("__APP_PATH__"))
    define("__APP_PATH__", realpath(dirname(__FILE__) . "/../"));
require_once(__APP_PATH__ . "/includes/constants.php");
require_once(__APP_PATH_CLASSES__ . "/common.class.php");
require_once(__APP_PATH_CLASSES__ . "/order.class.php");
require_once (__APP_PATH__."/tcpdf/tcpdf_import.php");
set_time_logout();
checkAuthAdmin();
$pagetitle = __SITE_TITLE__ . 'Order Dispatch  Docket';
$idUser = $_SESSION['usr']['id'];
$orderId = $_GET['orderId'];
if ($_SESSION['usr']['role'] != '1' && $_SESSION['usr']['role'] != '2' && $_SESSION['usr']['role'] != '4') {
    ob_end_clean();
    header('Location:' . __BASE_URL__);
    die;
}
$kUser = new cUser();
$kCommon = new cCommon();
$kOrder = new cOrder();
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$orderArr = $kOrder->loadOrder($orderId);
$orderProductArr = $kOrder->loadProductOrder($orderId);
$addressAry = explode('$', $orderArr[0]['address']);
$stateId = $kCommon->loadState($orderArr[0]['state']);
$status = '';
if ($orderArr['0']['status'] == '1') {
    $status = 'Unpaid';
} elseif ($orderArr['0']['status'] == '2') {
    $status = 'Paid';
} elseif ($orderArr['0']['status'] == '3') {
    $status = 'Dispatched';
} elseif ($orderArr['0']['status'] == '4') {
    $status = 'Complete';
} elseif ($orderArr['0']['status'] == '5') {
    $status = 'Canceled';
}
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Excellent Laundry');
$pdf->SetTitle('Excellent Laundry');
$pdf->SetSubject('Delivery Docket PDF');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// remove default header/footer
$pdf->setPrintHeader(false);


$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP-18, PDF_MARGIN_RIGHT);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}
$pdf->SetFont('times', '', 10);
if(!empty($orderArr)){
    $pdf->AddPage();
    $html = '<table>
<tr>
<td width="700">
            <img src="' . __BASE_URL_IMAGES__ . '/EL-final-logo200.png" alt="logo" class="logo-default"/></td>
<td><b>Address:</b> 2 Bachell Avenue, Lidcombe NSW 2141<br />
<b>Phone:</b> (02) 8964 4580<br />
<b>Phone:</b> (02) 8964 4590<br />
<b>Website:</b> www.linenhireservices.com.au
</td>
</tr>
<tr>
<td width="950" align="center" colspan="2">
<br />
<br />
<b><h2>Delivery Docket</h2></b>
<br />
<br />
</td>
</tr>
<tr>
<td width="700"><b>Order Number:</b> ' . $orderArr['0']['id'] . '</td>
<td><b>Street Address 1:</b> ' . $addressAry[0] . '</td>
</tr>
<tr>
<td width="700"><b>Business Name:</b> ' . $orderArr['0']['business_name'] . '</td>
<td><b>Street Address 2:</b> ' . $addressAry[1] . '</td>
</tr>
<tr>
<td width="700"><b>Order Date:</b> ' . date('d/m/Y', strtotime($orderArr['0']['createdon'])) . '</td>
<td><b>Country:</b> Australia</td>
</tr>
<tr>
<td width="700"><b>Order Status:</b> ' . $status . '</td>
<td><b>State:</b> ' . $stateId[0]['name'] . ' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <b>Post Code:</b> ' . $orderArr[0]['postcode'] . '</td>
</tr>

<tr>
<td align="left" colspan="2">
<br />
<br />
<b>Product Details</b>
<br />
<br />
</td>
</tr>
</table>
<table border="0">
<tr>
<td style="width:25%"></td>
<td style="width:50%">
<table border="1" cellpadding="10" width="450" align="left" bordercolor="black">
<tr>
<td><b>Item</b></td>
<td><b>Quantity Ordered</b></td>
<td><b>Quantity Dispatched</b></td>
</tr>';
    if(!empty($orderArr))
    {
        $i=1;
        foreach($orderProductArr as $orderProductData)
        {
            $colorname = '';
            $colorArr = explode(',', $orderProductData['color']);
            if(!empty ($colorArr)){
                foreach ($colorArr as $key => $value) {
                    $colorname = '';
                    $loadColorArr = $kCommon->loadColors($value);
                    if(!empty ($loadColorArr)){
                        $colorname = $loadColorArr['0']['name'];
                    }
                }
            }
            $html .= '<tr>
        <td>'.$orderProductData['name'].'</td>
        <td>'.$orderProductData['quantity'].'</td>
        <td>'.$orderProductData['dispatched'].'</td>
        </tr>';
            $i++;
        }
    }

    $html .= '</table>
</td>
<td style="width:25%"></td>
</tr>
</table>';
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->lastPage();
}else{
    $pdf->AddPage();
    $html = '<h3>No Details Found.</h3>';
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->lastPage();
}
$pdf->Output('order_docket_report.pdf', 'I');
?>