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
require_once __APP_PATH__. '/phpexcel/Classes/PHPExcel.php';
set_time_logout();
checkAuthAdmin();
$pagetitle=__SITE_TITLE__.'View Product List';
$idUser=$_SESSION['usr']['id'];
unset($_SESSION['search']);
require_once( __APP_PATH_LAYOUT__ . "/header.php" );
require_once( __APP_PATH_LAYOUT__ . "/headerNavBar.php" );
if($_SESSION['usr']['role'] != '1' && $_SESSION['usr']['role']!='2' )
{
    ob_end_clean();
    header('Location:' . __BASE_URL__);
    die;
}
$kUser = new cUser();
$kCommon = new cCommon();
$kProduct = new cProduct();
$objPHPExcel = new PHPExcel();
$CustomerError = false;
$ProductError = false;
$prodImport = false;
$invalidfile = '';
$target_dir = __APP_PATH__."/uploads/";
$target_file = $target_dir . basename($_FILES["productPrice"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
$parentId=$_GET['parentId'];
$childId=$_GET['childId'];
$productid = $_GET['prodid'];
if(isset($_POST["importprice"]) && ($_POST["importprice"] == '1')) {
        $prodImport = TRUE;
    if($imageFileType == 'xlsx'){
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
                        $customerIdArr = $kUser->loadCustomers(0,0,'','',array(),array(),$customercode);
                        if($kUser->checkCustomerExist($customerIdArr[0]['id'])){
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
                        $customerIdArr = $kUser->loadCustomers(0,0,'','',array(),array(),$proddet['customercode']);
                        if(!empty ($prodNameArr) && !empty ($customerIdArr[0]['id']))
                        {
                            if($kProduct->updatePrice($prodNameArr[0]['id'], $productprice, $customerIdArr[0]['id'])){
                            }
                        }
                        
                    }
                }
                
            }
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
    }else{
        $invalidfile = "Invalid file uploaded. Only .xlsx file is allowed. Please try again.";
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
                    <?php 
                    if($prodImport){ ?>
                        <div id="static1" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                       <h4 class="modal-title">Import Customer Product Price Summary</h4>
                                    </div>
                                    <div class="modal-body">

                                        <div class="portlet-body form">
                                            <!-- BEGIN FORM-->

                                                    <div class="col-md-12">
                                                        <?php 
                                                        if($invalidfile == ''){
                                                            $errorsImport = 'successfully';
                                                            if($CustomerError && $ProductError){
                                                                $errorsImport = ' with following Customer code and Product code errors ';
                                                            }elseif($CustomerError){
                                                                $errorsImport = ' with following Customer code errors ';
                                                            }elseif($ProductError){
                                                                $errorsImport = ' with following Product code errors ';
                                                            }
                                                            ?>
                                                            <p>Product prices for each customer imported <?php echo $errorsImport; ?>.</p>  
                                                            <?php 
                                                            if($CustomerError){
                                                                   echo '<p style="color:red; margin-top:10px">These customer does not exist in the system</p>';
                                                                   foreach ($notCustomerArr as $key => $value) {
                                                                       echo '<p style="color:red; margin-top:10px">'.$value.'</p>';
                                                                   }
                                                            }
                                                            if($ProductError){
                                                               echo '<p style="color:red; margin-top:10px">These products does not exist in the system</p>';
                                                               foreach ($nonProdsArr as $key => $value) {
                                                                   echo '<p style="color:red; margin-top:10px">'.$value.'</p>';
                                                               }
                                                            }
                                                        }else{
                                                             echo '<p style="color:red; margin-top:10px">'.$invalidfile.'</p>';
                                                        }
                                                        ?>
                                                    </div>

                                                 </div>
                                        </div>
                                        <div class="modal-footer">
                                            <a class="btn default" href="<?php echo __VIEW_PRODUCTS_LIST_URL__; ?>">Close</a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                    <script type="text/javascript">
                    $(document).ready(function(){
                        $('#static1').modal("show");
                    });
                    </script>

                    <?php }
                    ?>
                    <!-- BEGIN PAGE BREADCRUMB -->
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="<?php echo __URL_BASE_ADMIN__; ?>">Home</a>
                            <i class="fa fa-circle"></i>
                        </li>
                        
                        <li>
                            <span class="active">View Products List</span>
                        </li>
                    </ul>
                    <!-- END PAGE BREADCRUMB -->
                    <!-- BEGIN PAGE BASE CONTENT -->
                    <div class="row" id='page_content'>
                        <?php
                        $orderProductListArr = $kProduct->orderProductList();
                        $ordprodlist = array();
                        foreach ($orderProductListArr as $ordProd){
                            array_push($ordprodlist,$ordProd['prodid']);
                        }
                        $recordLimitFlag = false;
                         if($parentId < '0' && $childId <'0')
                        {
                            $prodArr = $kProduct->loadProducts();
                            
                        }
			
                        if($parentId > '0' && $childId >'0')
                        {
                            
                            $searchArr=array();
                            $searchArr['prParentGroup']=(int)$parentId;
                            $searchArr['prGroup']=(int)$childId;	
                            $prodArr = $kProduct->loadProducts('','','',$searchArr);	
                        }
                        if($parentId > '0' && $childId =='')
                        {
                           
                            $searchArr=array();
                            $searchArr['prParentGroup']=(int)$parentId;
                            $prodArr = $kProduct->loadProducts('','','',$searchArr);

                        }
                        if($productid > 0){
                            $proddesArr = $kProduct->loadProducts($productid);
                        }
                        include( __APP_PATH_VIEW_FILES__ . "/viewProductsList.php" );
                        ?>
                    </div>
                </div>
            </div>
        </div>
<?php

//if($parentId > 0 || $childId > 0 || $productid > 0){ ?>
    <script type="text/javascript">
        jQuery(document).ready(function(){
            $( ".search-button div button" ).trigger( "click" );
        });
        $(document).keypress(function(e) {
            if(e.which == 13) {
                $( ".search-button div button" ).trigger( "click" );
            }
        });

    </script>
<?php //}
include_once(__APP_PATH_LAYOUT__."/footer.php");

?>