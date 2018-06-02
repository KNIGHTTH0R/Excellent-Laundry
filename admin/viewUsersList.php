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

if( !defined( "__APP_PATH__" ) )
define( "__APP_PATH__", realpath( dirname( __FILE__ ) . "/../" ) );
require_once( __APP_PATH__ . "/includes/constants.php" );
require_once( __APP_PATH_CLASSES__ . "/common.class.php" );
require_once(__APP_PATH_CLASSES__ . "/product.class.php");
require_once __APP_PATH__. '/phpexcel/Classes/PHPExcel.php';
set_time_logout();
checkAuthAdmin();
$pagetitle=__SITE_TITLE__.'View Customers List';
$idUser=$_SESSION['usr']['id'];
unset($_SESSION['prGroup']);
unset($_SESSION['prParentGroup']);
unset($_SESSION['szSearchText']);
unset($_SESSION['search']);
require_once( __APP_PATH_LAYOUT__ . "/header.php" );
require_once( __APP_PATH_LAYOUT__ . "/headerNavBar.php" );
if($_SESSION['usr']['role'] != '1')
{
    ob_end_clean();
    header('Location:' . __BASE_URL__);
    die;
}
$kUser = new cUser();
$kCommon = new cCommon();
$kProduct = new cProduct();
$objPHPExcel = new PHPExcel();
$customerImport = false;
$target_dir = __APP_PATH__."/uploads/";
$target_file = $target_dir . basename($_FILES["impcustomers"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
if(isset($_POST["importcustomers"]) && ($_POST["importcustomers"] == '1')) {

    $customerImport = TRUE;
    if($imageFileType == 'xlsx'){
        if (move_uploaded_file($_FILES["impcustomers"]["tmp_name"], $target_file)) {
            $objReader = new PHPExcel_Reader_Excel2007();
            $objReader->setReadDataOnly(true);
            $objPHPExcel = $objReader->load( __APP_PATH__. '/uploads/'.basename( $_FILES["impcustomers"]["name"]) );

            $errArr = array();
            $errorFlag = false;
            $sucessFlag = false;
            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                $worksheetTitle = $worksheet->getTitle();
                $highestRow = $worksheet->getHighestRow(); // e.g. 10
                $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
                $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                if ($worksheetTitle == 'Customer records' || $worksheetTitle == 'Customers'){
                    $fname = '';
                    $lname = '';
                    for ($row = 1; $row <= $highestRow; ++$row) {
                        if($row > 1){
                            for ($col = 0; $col < $highestColumnIndex; ++$col) {
                                $cell = $worksheet->getCellByColumnAndRow($col, $row);
                                $val = $cell->getValue();
                                if($col == 0){
                                    $_POST['addUserAry']['usUniqueCode'] = $val;
                                    $_POST['addUserAry']['usBusinessName'] = $val;
                                }elseif ($col == 1){
                                    $val = date('d/m/Y', PHPExcel_Shared_Date::ExcelToPHP($val));
                                    $_POST['addUserAry']['usStartDate'] = $val;

                                }elseif ($col == 2){
                                    $val = date('d/m/Y', PHPExcel_Shared_Date::ExcelToPHP($val));
                                    $_POST['addUserAry']['usEndDate'] = $val;
                                }elseif ($col == 3){
                                    $_POST['addUserAry']['usEmail'] = $val;
                                }elseif ($col == 4){
                                    if(!empty($val)){
                                        $_POST['addUserAry']['usBusinessEmail'] = $val;
                                    }else{
                                        $_POST['addUserAry']['usBusinessEmail'] = $_POST['addUserAry']['usEmail'];
                                    }

                                }elseif ($col == 5){
                                    $_POST['addUserAry']['usContactName'] = $val;
                                    $fname = $val;
                                }elseif ($col == 6){
                                    $_POST['addUserAry']['usContactName'] = $_POST['addUserAry']['usContactName'].' '.$val;
                                    $lname = $val;
                                }elseif ($col == 7){
                                    $_POST['addUserAry']['usphone'] = str_pad(preg_replace('/\s+/', '',$val), 10, '0', STR_PAD_LEFT);
                                }elseif ($col == 8){
                                    if(!empty($val)){
                                        $_POST['addUserAry']['usmobile'] = str_pad(preg_replace('/\s+/', '',$val), 10, '0', STR_PAD_LEFT);
                                    }else{
                                        $_POST['addUserAry']['usmobile'] = str_pad(preg_replace('/\s+/', '',$_POST['addUserAry']['usphone']), 10, '0', STR_PAD_LEFT);
                                    }
                                }elseif ($col == 10){
                                    $_POST['addUserAry']['usStAddress1'] = $val;
                                }elseif ($col == 11){
                                    $_POST['addUserAry']['usState'] = $val;
                                }elseif ($col == 12){
                                    $_POST['addUserAry']['usPostcode'] = $val;
                                }
                                $_POST['addUserAry']['usCountry'] = '1';
                            }
                            $newCustomerid = $kUser->addNewCustomer($_POST['addUserAry']);

                            if($newCustomerid > '0'){
                                $prodIdsArr = $kProduct->loadProducts();
                                if(!empty ($prodIdsArr)){
                                    foreach ($prodIdsArr as $prods){
                                        $kProduct->addPrice($prods['id'], '0.00', $newCustomerid);
                                    }
                                }
                            }elseif(!empty($_POST['addUserAry']['usUniqueCode']) && $newCustomerid <= '0'){
                                array_push($errArr,$_POST['addUserAry']);
                            }
                        }

                    }
                    if(!empty($errArr)){
                        $errorFlag = true;
                        $sucessFlag = false;
                        $errmsg = "Something wrong with the data stored in excel sheet. It seems that data doesn't support our system guidelines.
                                    Data with following customer codes are not imported.<br>";
                        foreach ($errArr as $errdet){
                            $errmsg .= $errdet['usUniqueCode'].'<br />';
                        }
                    }else{
                        $errorFlag = false;
                        $sucessFlag = true;
                    }
                }
            }

        } else {
            $errorFlag = true;
            $sucessFlag = false;
            $errmsg = "Sorry, there was an error uploading your file.";
        }
    }else{
        $errorFlag = true;
        $sucessFlag = false;
        $errmsg = "Invalid file uploaded. Only .xlsx file is allowed. Please try again.";
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
                            <span class="active">View Customers List</span>
                        </li>
                    </ul>
                    <!-- END PAGE BREADCRUMB -->
                    <!-- BEGIN PAGE BASE CONTENT -->
                    <div class="row" id='page_content'>
                        <?php
                        if($customerImport){ ?>
                            <div id="static1" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                            <h4 class="modal-title">Import Customer Summary</h4>
                                        </div>
                                        <div class="modal-body">

                                            <div class="portlet-body form">
                                                <!-- BEGIN FORM-->

                                                <div class="col-md-12">
                                                    <?php
                                                    if($errorFlag){
                                                        echo '<p style="color:red; margin-top:10px">'.$errmsg.'</p>';
                                                    }elseif($sucessFlag){
                                                        echo '<p style="color:green; margin-top:10px">Data imported successfully.</p>';
                                                    }
                                                    ?>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <a class="btn default" href="<?php echo __VIEW_CUSTOMERS_LIST_URL__; ?>">Cancel</a>
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
                        <?php
						//$type = $_GET['type'];
                        $orderCustomerListArr = $kProduct->orderCustomerList();
                        $ordCustlist = array();
                        foreach ($orderCustomerListArr as $ordProd){
                            array_push($ordCustlist,$ordProd['custid']);
                        }
                        $userArr=$kUser->loadCustomers();
                        include( __APP_PATH_VIEW_FILES__ . "/viewUsersList.php" );
                        ?>
                    </div>
                </div>
            </div>
        </div>
<script type="text/javascript">
    $(document).keypress(function(e) {
        if(e.which == 13) {
            $( "#userSearchForm .btn" ).trigger( "click" );
        }
    });

</script>
<?php

include_once(__APP_PATH_LAYOUT__."/footer.php");

?>	
