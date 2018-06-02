<?php
ob_start();
session_start();

if( !defined( "__APP_PATH__" ) )
define( "__APP_PATH__", realpath( dirname( __FILE__ ) . "/../" ) );
require_once( __APP_PATH__ . "/includes/constants.php" );
require_once( __APP_PATH_CLASSES__ . "/common.class.php" );
require_once( __APP_PATH_CLASSES__ . "/product.class.php" );
ini_set('max_execution_time', 5000);
header( 'Content-type: text/html; charset=utf-8' );

$searchAry=array();

if(isset ($_GET['usStartDate']) && !empty ($_GET['usStartDate'])){
    $searchAry['searchArr']['usStartDate'] = $_GET['usStartDate'];
}
if(isset ($_GET['usEndDate']) && !empty ($_GET['usEndDate'])){
    $searchAry['searchArr']['usEndDate'] = $_GET['usEndDate'];
}

$kUser = new cUser();
$userArr=$kUser->loadCustomers('','','','','',$searchAry['searchArr']);
$data[0]['sn'] ='#';
$data[0]['business_name'] ='Business Name';
$data[0]['contract_start'] ='Contract start date';
$data[0]['contract_end'] ='Contract Finish Date';
$data[0]['phoneno'] ='Customer Contact';
$data[0]['contact_name'] ='Name Customer';
$data[0]['contact_email'] ='Contact Email';
$data[0]['mobileno'] ='Customer Contact Number ';

$i=1;
if(!empty($userArr))
        {
            foreach ($userArr as $userData)
            {
                if($userData['contract_start'] != '0000-00-00 00:00:00'){
                    $data[$i]['sn'] =$i;
                    $data[$i]['business_name'] =$userData['business_name'];
                    $data[$i]['contract_start'] =date('d/m/Y',  strtotime($userData['contract_start']));
                    $data[$i]['contract_end'] =date('d/m/Y',  strtotime($userData['contract_end']));
                    $data[$i]['phoneno'] =$userData['phoneno'];
                    $data[$i]['contact_name'] =$userData['contact_name'];
                    $data[$i]['contact_email'] =$userData['contact_email'];
                    $data[$i]['mobileno'] =$userData['mobileno'];
                    $i++;
                }
               
            }
        }
header('Content-type: text/csv','charset=utf-8');
header("Content-Disposition: attachment;filename=RunDateCsv".date('m-d-Y-h-i-s').".csv");            
$f  =   fopen('php://output', 'w+');
if(!empty($data))
{
        foreach ($data as $fields) 
        {
                $download= fputcsv($f, $fields);    
        }
}

 

?>