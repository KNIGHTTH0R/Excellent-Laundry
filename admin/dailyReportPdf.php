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
require_once (__APP_PATH__."/tcpdf/tcpdf_import.php");
set_time_logout();
checkAuthAdmin();
$pagetitle=__SITE_TITLE__.'Daily Schedule Report';
$idUser=$_SESSION['usr']['id'];
if($_SESSION['usr']['role'] != '1')
{
    ob_end_clean();
    header('Location:' . __BASE_URL__);
    die;
}
$kUser = new cUser();
$kCommon = new cCommon();
$kProduct = new cProduct();
$kDriver =new cDriver();
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Excellent Laundry');
$pdf->SetTitle('Excellent Laundry');
$pdf->SetSubject('Weekly Schedule Report PDF');
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

// set font
$pdf->SetFont('times', '', 10);
$dayVal = $_GET['dayval'];
switch ($dayVal) {
    case '1':
        $today = "Monday";
        break;
    case "2":
        $today = 'Tuesday';
        break;
    case "3":
        $today = 'Wednesday';
        break;
    case "4":
        $today = 'Thursday';
        break;
    case "5":
        $today = 'Friday';
        break;
    case "6":
        $today = 'Saturday';
        break;
    case "7":
        $today = 'Sunday';
        break;
}
$getAllDriverArr = $kUser->getAllDriver();
$ctr = 0;
$checkCount = 0;
if(!empty ($getAllDriverArr)){
    foreach ($getAllDriverArr as $singleDriver){ 
        $ctr++;
        $scheduleReportArr = $kDriver->getDailyCustomerReport($dayVal,$singleDriver['id']); 
        if(!empty ($scheduleReportArr)){
            $pdf->AddPage();
            $html = '<a style="text-align:center" href="'.__URL_BASE_ADMIN__.'"><img style="width:145px" src="'.__BASE_URL_IMAGES__.'/EL-final-logo200.png" alt="logo" class="logo-default" /> </a>
                    <div style="margin-top:0px;line-height:10px;margin-bottom:0px"><p style="text-align:center; font-size:18px; margin-bottom:0px; line-height:20px; color:#000"><b>Driver Name:</b>'.$singleDriver['name'].'</p></div>
                    <div style="margin-top:0px;line-height:10px;margin-bottom:0px"><p style="text-align:center; margin-top:-20px; line-height:-20px;"><b>Week Day:</b>'.$today.'</p></div>
                    <table border="1" cellpadding="5" class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" role="grid" aria-describedby="sample_1_info">
                        <thead>
                            <tr>
                                <th><b>Customer Name</b></th>
                                <th><b>Customer Address</b></th>
                                <th><b>Clean Bags Sent</b></th>
                            </tr>
                        </thead>
                        <tbody>';
            foreach ($scheduleReportArr as $repo){
                $addressArr = explode('$', $repo['address']);
                $stateArr = $kCommon->loadState($repo['state']);
                $html .='<tr>
                            <td>'.$repo['business_name'].'</td>
                            <td>Street Address 1: '.$addressArr[0].'<br />
                                Street Address 2: '.$addressArr[1].'<br />
                                State: '.$stateArr[0]['name'].'<br />
                                Country: Australia<br />
                                Post Code: '.$repo['postcode'].'<br />
                            </td>
                            <td></td>
                        </tr>';
            }
            $html .='</tbody></table>';
            $pdf->writeHTML($html, true, false, true, false, '');
            $pdf->lastPage();
        }else{
            $checkCount++;

        }
    }
}
if($ctr == $checkCount){
        $pdf->AddPage();
        $html = '<a style="text-align:center" href="'.__URL_BASE_ADMIN__.'"><img style="width:145px" src="'.__BASE_URL_IMAGES__.'/EL-final-logo200.png" alt="logo" class="logo-default" /> </a>
                <h1 style="text-align:center;">No driver is scheduled for <u style="color:red;">'.$today.'</u></h1>';
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->lastPage();
        }
$pdf->Output('daily_schedule_report.pdf', 'I');
?>
    