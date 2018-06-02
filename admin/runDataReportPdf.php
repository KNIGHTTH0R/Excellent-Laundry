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
$searchAry=array();

if(isset ($_GET['usStartDate']) && !empty ($_GET['usStartDate'])){
    $searchAry['searchArr']['usStartDate'] = $_GET['usStartDate'];
}
if(isset ($_GET['usEndDate']) && !empty ($_GET['usEndDate'])){
    $searchAry['searchArr']['usEndDate'] = $_GET['usEndDate'];
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
$userArr=$kUser->loadCustomers('','','','','',$searchAry['searchArr']);
if(!empty($userArr)){
   
        $pdf->AddPage();
            $html = '
            <style>
            </style>
            <a style="text-align:center" href="'.__URL_BASE_ADMIN__.'"><img style="width:145px" src="'.__BASE_URL_IMAGES__.'/EL-final-logo200.png" alt="logo" class="logo-default" /> </a>
            <div><p style="text-align:center; font-size:18px; color:#000"><b>Report Run date</p></div>
            <table border="1" cellpadding="5" class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" role="grid" aria-describedby="sample_1_info">
                <thead>
                    <tr>
                        <th><b>#</b></th>
                        <th><b>Business Name</b></th>
                        <th><b>Contract Start Date</b></th>
                        <th><b>Contract Finish Date</b></th>
                        <th><b>Customer Phone Number</b></th>
                        <th><b>Customer Name</b></th>
                        <th><b>Contact Email</b></th>
                        <th><b>Customer Mobile Number</b></th>
                    </tr>
                </thead>
                <tbody>';
            
           
              $i=1;
            foreach($userArr as $userData){
                if($userData['contract_start'] != '0000-00-00 00:00:00'){
                $html .='<tr>
                           <td>'.$i.'</td>
                           <td>'.$userData['business_name'].'</td>
                            <td>'.date('d/m/Y',  strtotime($userData['contract_start'])).'</td>
                           <td>'.date('d/m/Y',  strtotime($userData['contract_end'])).'</td>
                           <td>'.$userData['phoneno'].'</td>
                            <td>'.$userData['contact_name'].'</td>
                             <td>'.$userData['contact_email'].'</td>
                            <td>'.$userData['mobileno'].'</td>
                         </tr> ';
                 $i++;
                }
            }
            $html .= '</tbody>
                    </table>';
            $pdf->writeHTML($html, true, false, true, false, '');
            $pdf->lastPage();
        }

$pdf->Output('runDataReportPdf.pdf', 'I');
?>
    
