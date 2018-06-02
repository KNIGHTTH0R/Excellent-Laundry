<?php
ob_start();
session_start();

if( !defined( "__APP_PATH__" ) )
define( "__APP_PATH__", realpath( dirname( __FILE__ ) . "/../" ) );
require_once( __APP_PATH__ . "/includes/constants.php" );
require_once( __APP_PATH_CLASSES__ . "/common.class.php" );
require_once( __APP_PATH_CLASSES__ . "/product.class.php" );
require_once( __APP_PATH_CLASSES__ . "/order.class.php" );
ini_set('max_execution_time', 5000);
header( 'Content-type: text/html; charset=utf-8' );

if(isset ($_GET['startcreatedon']) && !empty ($_GET['startcreatedon'])){
    $_POST['searchArr']['startcreatedon'] = $_GET['startcreatedon'];
}
if(isset ($_GET['endcreatedon']) && !empty ($_GET['endcreatedon'])){
    $_POST['searchArr']['endcreatedon'] = $_GET['endcreatedon'];
}
if(isset ($_GET['businessname']) && !empty ($_GET['businessname'])){
    $_POST['searchArr']['businessname'] = $_GET['businessname'];
}
if(isset ($_GET['orderstat']) && !empty ($_GET['orderstat'])){
    $_POST['searchArr']['orderstat'] = $_GET['orderstat'];
}


$kOrder = new cOrder();
if(!empty ($_POST['searchArr'])){
$orderArr = $kOrder->loadOrder('',$_POST['searchArr']);
}else{
    $orderArr = $kOrder->loadOrder();
}
$data[0]['sn'] ='#';
$data[0]['business_name'] ='Customer';
$data[0]['id'] ='Order #';
$data[0]['orderProdArr'] ='No. of products';
$data[0]['createdon'] ='Order date';
$data[0]['price'] ='Order Cost';
$data[0]['status'] ='Status';


$i=1;
if(!empty($orderArr))
        {
            foreach ($orderArr as $orderData)
            {
                $orderProdArr = $kOrder->loadProductOrder($orderData['id']);
                
                    $data[$i]['sn'] =$i;
                    $data[$i]['business_name'] =$orderData['business_name'];
                    $data[$i]['id'] =$orderData['id'];
                    $data[$i]['orderProdArr'] =count($orderProdArr);
                    $data[$i]['createdon'] =date('d/m/Y',  strtotime($orderData['createdon']));
                    $data[$i]['price'] ='$'.$orderData['price'];
                    $data[$i]['status'] =($orderData['status']=='1'?'Ordered':($orderData['status']=='2'?'Pending':($orderData['status']=='3'?'Dispatched':($orderData['status']=='5'?'Canceled':''))));
                    $i++;
               
            }
        }
header('Content-type: text/csv','charset=utf-8');
header("Content-Disposition: attachment;filename=OrderReportCsv".date('m-d-Y-h-i-s').".csv");            
$f  =   fopen('php://output', 'w+');
if(!empty($data))
{
        foreach ($data as $fields) 
        {
                $download= fputcsv($f, $fields);    
        }
}

 

?>