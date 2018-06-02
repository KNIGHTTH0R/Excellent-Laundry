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
error_reporting(1);
if( !defined( "__APP_PATH__" ) )
define( "__APP_PATH__", realpath( dirname( __FILE__ ) . "/../" ) );
require_once( __APP_PATH__ . "/includes/constants.php" );
require_once( __APP_PATH_CLASSES__ . "/common.class.php" );
require_once( __APP_PATH_CLASSES__ . "/product.class.php" );
require_once( __APP_PATH_CLASSES__ . "/order.class.php" );
require_once __APP_PATH__. '/phpexcel/Classes/PHPExcel.php';
set_time_logout();
checkAuthAdmin();

$pagetitle=__SITE_TITLE__.'Import Order List';
$idUser=$_SESSION['usr']['id'];
require_once( __APP_PATH_LAYOUT__ . "/header.php" );
require_once( __APP_PATH_LAYOUT__ . "/headerNavBar.php" );
if($_SESSION['usr']['role'] != '1' && $_SESSION['usr']['role']!='2'  && $_SESSION['usr']['role']!='4' )
{
    ob_end_clean();
    header('Location:' . __BASE_URL__);
    die;
}
$kUser = new cUser();
$kCommon = new cCommon();
$kProduct = new cProduct();
$kOrder  = new cOrder();
$objPHPExcel = new PHPExcel();
$target_dir = __APP_PATH__."/uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
//    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
//    if($check !== false) {
//        echo "File is an image - " . $check["mime"] . ".";
//        $uploadOk = 1;
//    } else {
//        echo "File is not an image.";
//        $uploadOk = 0;
//    }
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        $objReader = new PHPExcel_Reader_Excel2007();
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load( __APP_PATH__. '/uploads/'.basename( $_FILES["fileToUpload"]["name"]) );
        
        $rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();
        $array_data = array();
foreach($rowIterator as $row){
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
    if(1 == $row->getRowIndex ()) continue;//skip first row
    $rowIndex = $row->getRowIndex ();
    $array_data[$rowIndex] = array('ordercode'=>'', 'customeremail'=>'','order_date'=>'','quantity'=>'','color'=>'','productcode'=>'');
     
    foreach ($cellIterator as $cell) {
        if('A' == $cell->getColumn()){
            $array_data[$rowIndex]['ordercode'] = $cell->getCalculatedValue();
        } else if('B' == $cell->getColumn()){
            $array_data[$rowIndex]['customeremail'] = $cell->getCalculatedValue();
        } else if('C' == $cell->getColumn()){
            $array_data[$rowIndex]['order_date'] = PHPExcel_Style_NumberFormat::toFormattedString($cell->getCalculatedValue(), 'YYYY-MM-DD');
        } else if('D' == $cell->getColumn()){
            $array_data[$rowIndex]['quantity'] = $cell->getCalculatedValue();
        }else if('E' == $cell->getColumn()){
            $array_data[$rowIndex]['color'] = $cell->getCalculatedValue();
        }else if('F' == $cell->getColumn()){
            $array_data[$rowIndex]['productcode'] = $cell->getCalculatedValue();
        }
    }
}

if(!empty ($array_data)){
    $ordercode = array();
    $customerid = 0;
    $productid = 0;
    $prodprice = 0.00;
    $newOrder = array();
    foreach ($array_data as $key => $row){
        $ordercode[$key] = $row['ordercode'];
    }
    array_multisort($ordercode, SORT_ASC, $array_data);
    
    foreach ($array_data as $orderdata){
        $data['prQuantity'] = $orderdata['quantity'];
        $data['prColor'] = $orderdata['color'];
        $getuserId = $kUser->loadCustomersByEmails($orderdata['customeremail']);
        if(!empty ($getuserId)){
            $customerArr = $kUser->loadCustomers(0,$getuserId['id']);
            if(!empty ($customerArr)){
                $customerid = $customerArr[0]['id'];
                //echo $orderdata['customeremail'].'-- '.$getuserId['id'].' --customer id -----'.$customerid.'<br />';
                $prodIdsArr = $kProduct->loadProductIDbyName($orderdata['productcode']);
                if(!empty ($prodIdsArr)){
                    $productid = $prodIdsArr[0]['id'];
                    //echo 'Product id -----'.$productid.'<br />';
                    $getProdPriceArr = $kProduct->getCustomerPriceBySize($customerid, $productid);
                    if(!empty ($getProdPriceArr)){
                        $prodprice = $getProdPriceArr[0]['price'];
                        $data['prPrice'] = (double)$prodprice;
                        $subtotalPrice = ((double)$prodprice * $orderdata['quantity']);
                        if(!in_array($orderdata['ordercode'], $newOrder)){
                            array_push($newOrder, $orderdata['ordercode']);
                            if($kOrder->ImportOrder($customerid, $subtotalPrice, $orderdata['ordercode'], $orderdata['order_date'])){
                                $orderIDArr = $kOrder->loadImportedOrder(0, $orderdata['ordercode']);
                                if(!empty ($orderIDArr)){
                                    $data['ordid'] = $orderIDArr[0]['id'];
                                    $data['prId'] = $productid;
                                    //echo 'In array ---- '.$data['ordid'].'----'.$data['prId'].'<br />';
                                    $kOrder->addOrderDetails($data);
                                }
                            }
                        }else{
                            $orderIDArr = $kOrder->loadImportedOrder(0, $orderdata['ordercode']);
                            if(!empty ($orderIDArr)){
                                if($kOrder->ImportOrderPriceUpdate($orderIDArr[0]['id'], $subtotalPrice)){
                                    $data['ordid'] = $orderIDArr[0]['id'];
                                    $data['prId'] = $productid;
                                    //echo 'Out array ---- '.$data['ordid'].'----'.$data['prId'].'<br />';
                                    $kOrder->addOrderDetails($data);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}


    } else {
        echo "Sorry, there was an error uploading your file.";
    }
    
}
?>
    
        <!-- BEGIN HEADER & CONTENT DIVIDER -->
        <div class="clearfix"> </div>
        <!-- END HEADER & CONTENT DIVIDER -->
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <?php 
                require_once( __APP_PATH_LAYOUT__ . "/leftMenu.php" );
            ?>
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    
                    <!-- BEGIN PAGE BREADCRUMB -->
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="<?php echo __URL_BASE_ADMIN__; ?>">Home</a>
                            <i class="fa fa-circle"></i>
                        </li>
                        
                        <li>
                            <span class="active">Import Order List</span>
                        </li>
                    </ul>
                    <!-- END PAGE BREADCRUMB -->
                    <!-- BEGIN PAGE BASE CONTENT -->
                    <div class="row" id='page_content'>
                        <form enctype="multipart/form-data" class="form-horizontal" method="post" id="addNewProductForm" name="addNewProductForm">
                            <input type="file" name="fileToUpload" id="fileToUpload">
                            <input type="submit" value="Upload" name="submit">
                        </form>
                        <?php
                       
			//$orderArr = $kOrder->loadOrder();
                        //include( __APP_PATH_VIEW_FILES__ . "/viewOrderList.php" );
                        ?>
                    </div>
                </div>
            </div>
        </div>
<?php

include_once(__APP_PATH_LAYOUT__."/footer.php");

?>	
