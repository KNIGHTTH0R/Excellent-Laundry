<?php
ob_start();
session_start();

if( !defined( "__APP_PATH__" ) )
define( "__APP_PATH__", realpath( dirname( __FILE__ ) . "/../" ) );
require_once( __APP_PATH__ . "/includes/constants.php" );
require_once( __APP_PATH_CLASSES__ . "/common.class.php" );
require_once( __APP_PATH_CLASSES__ . "/product.class.php" );
require_once( __APP_PATH_CLASSES__ . "/order.class.php" );

$kOrder = new cOrder();
$kUser = new cUser();
$kCommon = new cCommon();
$kProduct = new cProduct();
$kDriver =new cDriver();

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

$data[0]['sn'] ='#';
/*$data[0]['id'] ='Customer';
$data[0]['createdon'] ='Order date';*/
$data[0]['name'] ='Product';
$data[0]['quantity'] ='Ordered Qty.';
$data[0]['dispatched'] ='Dispatched Qty.';
$data[0]['weight'] ='Weight (Kg)';
$data[0]['totalweight'] ='Total Weight (Kg)';
$data[0]['orderno'] = 'Order No.';
/*$data[0]['price'] ='Cost';
$data[0]['status'] ='Status';*/


$i=1;
if(!empty($orderArr))
        {
            $prodname = '';
            $_POST['searchArr']['prod-name'] = trim($_POST['searchArr']['prod-name']);
            if(!empty($_POST['searchArr']['prod-name'])){
                $prodname = $_POST['searchArr']['prod-name'];
            }
             foreach ($orderArr as $orderData){ 
        $orderProdArr = $kOrder->loadProductOrder($orderData['id'],$prodname);
            if(!empty ($orderProdArr)){
                foreach ($orderProdArr as $prodDet){
                    
                    $data[$i]['sn'] =$i;
                    /*$data[$i]['id'] =$orderData['business_name'];
                    $data[$i]['createdon'] =date('d/m/Y',  strtotime($orderData['createdon']));*/
                    $data[$i]['name'] =$prodDet['name'];
                    $data[$i]['quantity'] =$prodDet['quantity'];
                    $data[$i]['dispatched'] =$prodDet['dispatched'];
                    $data[$i]['weight'] =$prodDet['weight'];
                    $data[$i]['totalweight'] =$prodDet['weight']*$prodDet['dispatched'];
                    $data[$i]['orderno'] = '#'.$orderData['id'];
                    /*$data[$i]['price'] ='$'.($prodDet['price']*$prodDet['dispatched']);
                    $data[$i]['status'] =($orderData['status']=='1'?'Ordered':($orderData['status']=='2'?'Pending':($orderData['status']=='3'?'Dispatched':($orderData['status']=='5'?'Canceled':''))));*/
                    $i++;
                    
                    
                }
            }
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