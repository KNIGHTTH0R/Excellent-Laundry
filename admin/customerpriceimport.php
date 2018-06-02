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

$pagetitle=__SITE_TITLE__.'Import Customer Price';
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
$CustomerError = false;
$ProductError = false;
$target_dir = __APP_PATH__."/uploads/";
$target_file = $target_dir . basename($_FILES["productPrice"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["importprice"])) {
//    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
//    if($check !== false) {
//        echo "File is an image - " . $check["mime"] . ".";
//        $uploadOk = 1;
//    } else {
//        echo "File is not an image.";
//        $uploadOk = 0;
//    }
    if (move_uploaded_file($_FILES["productPrice"]["tmp_name"], $target_file)) {
        $objReader = new PHPExcel_Reader_Excel2007();
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load( __APP_PATH__. '/uploads/'.basename( $_FILES["productPrice"]["name"]) );
        $rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();
        $customerArr = array();
        $notCustomerArr = array();
        $validCustomer = 0;
        $totalcustomer = 0;
        $validProduct = 0;
        $totalProduct = 0;
        $customerArrIndex = 1;
        $validProdsArr = array();
        $nonProdsArr = array();
        $excludingCellsArr = array();
        $excludingProdsArr = array();
        foreach($rowIterator as $row){
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
            $rowIndex = $row->getRowIndex ();
            if(1 == $row->getRowIndex ()){
                foreach ($cellIterator as $cell) {
                    $customercode = $cell->getCalculatedValue();
                    $cellIndex = $cell->getColumn();
                    if('A' != $cell->getColumn() && !empty ($customercode)){
                        $customerArr[$rowIndex][$cellIndex] = $customercode;
                        $customerIdArr = explode('-', $customercode);
                        if($kUser->checkCustomerExist($customerIdArr[1])){
                            $validCustomer++;
                        }else{
                            array_push($notCustomerArr, $customercode);
                            array_push($excludingCellsArr, $cell->getColumn());
                        }
                        $totalcustomer++;
                    }
                }
                if($validCustomer != $totalcustomer){
                    $CustomerError = true;
                }
            }else{
                foreach ($cellIterator as $cell) {
                    $prodname = $cell->getCalculatedValue();
                    if('A' == $cell->getColumn() && !empty ($prodname)){
                        $prodarr = $kProduct->loadProductIDbyName($prodname);
                        if(!empty ($prodarr) && $prodarr[0]['id'] > 0){
                            array_push($validProdsArr, $prodname);
                            $validProduct++;
                        }else{
                            array_push($nonProdsArr, $prodname);
                             array_push($excludingProdsArr, $rowIndex);
                        }
                        $totalProduct++;
                    } 
                }
                if($validProduct != $totalProduct){
                    $ProductError = TRUE;
                }
            }
        }
        $array_data = array();
        foreach($rowIterator as $newrow){
            $cellIterator = $newrow->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
            $rowIndex1 = $newrow->getRowIndex();
            if((1 != $newrow->getRowIndex()) && (!in_array($rowIndex1, $excludingProdsArr))){
                $prodcode = '';
                foreach ($cellIterator as $Newcell) {
                    $collIndex = $Newcell->getColumn();
                    if('A' == $Newcell->getColumn()){
                        $prodcode = $Newcell->getCalculatedValue();
                        if(!empty ($prodcode)){
                            $array_data[$rowIndex1][$collIndex]['productcode'] = $Newcell->getCalculatedValue();
                        }
                    } else if(!in_array($collIndex, $excludingCellsArr)){
                        $customercode = $customerArr[$customerArrIndex][$collIndex];
                        if(!empty ($prodcode) && !empty ($customercode)){
                            $array_data[$rowIndex1][$collIndex]['customercode'] = $customercode;
                            $array_data[$rowIndex1][$collIndex]['price'] = $Newcell->getCalculatedValue();
                        }
                    }
                }
            }
        }
        if(!empty ($array_data)){
            foreach ($array_data as $proddata){
                $prodNameArr = array();
                if(!empty ($proddata)){
                    foreach ($proddata as $proddet){
                        $productprice = 0;
                        if($proddet['price'] > 0){
                            $productprice = $proddet['price'];
                        }
                        if(!empty ($proddet['productcode'])){
                            $prodNameArr = $kProduct->loadProductIDbyName($proddet['productcode']);
                        }
                        $customerIdArr = explode('-', $proddet['customercode']);
                        if(!empty ($prodNameArr) && !empty ($customerIdArr[1])){
                            //echo '<br /> prod id -- '.$prodNameArr[0]['id'].' price -- '.$productprice.' customer id -- '.$customerIdArr[1].'<br>';
                            $kProduct->updatePrice($prodNameArr[0]['id'], $productprice, $customerIdArr[1]);
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
                            <span class="active">Import Customer Price</span>
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
                       if($CustomerError){
                           echo 'These customer does not exist in the system<br />';
                           foreach ($notCustomerArr as $key => $value) {
                               echo $value.'<br>';
                           }
                       }
                       if($ProductError){
                           echo 'These products does not exist in the system<br />';
                           foreach ($nonProdsArr as $key => $value) {
                               echo $value.'<br>';
                           }
                       }
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
