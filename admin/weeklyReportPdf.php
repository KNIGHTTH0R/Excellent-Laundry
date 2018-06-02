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
$pagetitle=__SITE_TITLE__.'Weekly Schedule Report PDF';
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
$getAllDriverArr = $kUser->getAllDriver();
if(!empty($getAllDriverArr)){
    foreach ($getAllDriverArr as $singleDriver){ 
        $mondayArr = array();
        $tuesdayArr = array();
        $weddayArr = array();
        $thursdayArr = array();
        $fridayArr = array();
        $satdayArr = array();
        $sundayArr = array();
        
        $scheduleReportArr = $kDriver->getDailyCustomerReport(0,$singleDriver['id']); 
        if(!empty ($scheduleReportArr)){
            $pdf->AddPage();
            $html = '
            <style>
            </style>
            <a style="text-align:center" href="'.__URL_BASE_ADMIN__.'"><img style="width:145px" src="'.__BASE_URL_IMAGES__.'/EL-final-logo200.png" alt="logo" class="logo-default" /> </a>
            <div><p style="text-align:center; font-size:18px; color:#000"><b>Driver Name: </b>'.$singleDriver['name'].'</p></div>
            <table border="1" cellpadding="5" class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" role="grid" aria-describedby="sample_1_info">
                <thead>
                    <tr>
                        <th><b>Mon</b></th>
                        <th><b>Mon Clean Bags</b></th>
                        <th><b>Tues</b></th>
                        <th><b>Tues Clean Bags</b></th>
                        <th><b>Wed</b></th>
                        <th><b>Wed Clean Bags</b></th>
                        <th><b>Thurs</b></th>
                        <th><b>Thurs Clean Bags</b></th>
                        <th><b>Fri</b></th>
                        <th><b>Fri Clean Bags</b></th>
                        <th><b>Sat</b></th>
                        <th><b>Sat Clean Bags</b></th>
                        <th><b>Sun</b></th>
                        <th><b>Sun Clean Bags</b></th>

                    </tr>
                </thead>
                <tbody>';
            foreach ($scheduleReportArr as $repo){
                if($repo['weekid']=='1'){
                    array_push($mondayArr, $repo['business_name']);
                }
                if($repo['weekid']=='2'){
                    array_push($tuesdayArr, $repo['business_name']);
                }
                if($repo['weekid']=='3'){
                    array_push($weddayArr, $repo['business_name']);
                }
                if($repo['weekid']=='4'){
                    array_push($thursdayArr, $repo['business_name']);
                }
                if($repo['weekid']=='5'){
                    array_push($fridayArr, $repo['business_name']);
                }
                if($repo['weekid']=='6'){
                    array_push($satdayArr, $repo['business_name']);
                }
                if($repo['weekid']=='7'){
                    array_push($sundayArr, $repo['business_name']);
                }
            }
            $maxCount = 0;
            if(count($mondayArr)>$maxCount){
                $maxCount = count($mondayArr);
            }
            if(count($tuesdayArr)>$maxCount){
                $maxCount = count($tuesdayArr);
            }
            if(count($weddayArr)>$maxCount){
                $maxCount = count($weddayArr);
            }
            if(count($thursdayArr)>$maxCount){
                $maxCount = count($thursdayArr);
            }
            if(count($fridayArr)>$maxCount){
                $maxCount = count($fridayArr);
            }
            if(count($satdayArr)>$maxCount){
                $maxCount = count($satdayArr);
            }
            if(count($sundayArr)>$maxCount){
                $maxCount = count($sundayArr);
            }

            for($i=0;$i<$maxCount;$i++){
                $html .='<tr>
                           <td>'.(!empty ($mondayArr[$i])? $mondayArr[$i]:'').'</td>
                            <td> </td>
                            <td>'.(!empty ($tuesdayArr[$i])? $tuesdayArr[$i]:'').'</td>
                            <td> </td>
                            <td>'.(!empty ($weddayArr[$i])? $weddayArr[$i]:'').'</td>
                            <td> </td>
                            <td>'.(!empty ($thursdayArr[$i])? $thursdayArr[$i]:'').'</td>
                            <td> </td>
                            <td>'.(!empty ($fridayArr[$i])? $fridayArr[$i]:'').'</td>
                            <td> </td>
                            <td>'.(!empty ($satdayArr[$i])? $satdayArr[$i]:'').'</td>
                            <td> </td>
                            <td>'.(!empty ($sundayArr[$i])? $sundayArr[$i]:'').'</td>
                            <td> </td>
                        </tr> ';
            }
            $html .= '</tbody>
                    </table>';
            $pdf->writeHTML($html, true, false, true, false, '');
            $pdf->lastPage();
        }
    }
}
$pdf->Output('weekly_schedule_report.pdf', 'I');
?>
    
