<?php
ob_start();
session_start();
ini_set("post_max_size", "20M");
if (!defined("__APP_PATH__"))
    define("__APP_PATH__", realpath(dirname(__FILE__) . "/../"));
require_once(__APP_PATH__ . "/includes/constants.php");
require_once(__APP_PATH_CLASSES__ . "/common.class.php");
require_once(__APP_PATH_CLASSES__ . "/product.class.php");
require_once(__APP_PATH_CLASSES__ . "/order.class.php");
require_once(__APP_PATH__ . "/includes/xero.php");
error_reporting(1);
$kCommon = new cCommon();
$kProduct = new cProduct();
$kUser = new cUser();
$kOrder = new cOrder();
$idUser = $_SESSION['usr']['id'];
$customerId = $_SESSION['usr']['customerid'];
$mode = sanitize_all_html_input($_POST['mode']);
$output_dir = __APP_PATH_PRODUCT_IMAGES_FILES__;
//die($output_dir);
$randomNum = $_REQUEST['iPhotoCount'];
if (isset($_FILES["myfile"])) {
    $ret = array();

    $error = $_FILES["myfile"]["error"];
    {

        if (!is_array($_FILES["myfile"]['name'])) //single file
        {
            $RandomNum = time();

            $ImageName = str_replace(' ', '-', strtolower($_FILES['myfile']['name']));
            $ImageType = $_FILES['myfile']['type']; //"image/png", image/jpeg etc.

            $ImageExt = substr($ImageName, strrpos($ImageName, '.'));
            $ImageExt = str_replace('.', '', $ImageExt);
            $ImageName = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);
            if ($ImageName > 10) {
                $ImageName = substr($ImageName, 0, 10);
            }
            if (strlen($ImageName) > 20) {
                $ImageName = substr_replace($ImageName, '', 20);
            }

            $NewImageName = date('dmyhis') . $ImageName . '-' . $RandomNum . '.' . $ImageExt;
            //die ($NewImageName);

            move_uploaded_file($_FILES["myfile"]["tmp_name"], $output_dir . '/' . $NewImageName);
            // echo $output_dir. $NewImageName;
            $randomNum = rand() . time();
            $ret['name'] = $NewImageName;
            $ret['rand_num'] = $randomNum;
            $ret['img_div'] = '<div id="photoDiv_' . $randomNum . '"><img class="file_preview_image" src="' . __BASE_URL_PRODUCT_IMAGES__ . '/' . $NewImageName . '" width="60" height="60"/>
                              <a href="javascript:void(0);" id="remove_btn_' . $randomNum . '" class="btn red-intense btn-sm" onclick="removeIncidentPhoto();">Remove</a>
                           </div>';
        } else {
            $fileCount = count($_FILES["myfile"]['name']);
            for ($i = 0; $i < $fileCount; $i++) {
                $RandomNum = time();

                $ImageName = str_replace(' ', '-', strtolower($_FILES['myfile']['name'][$i]));
                $ImageType = $_FILES['myfile']['type'][$i]; //"image/png", image/jpeg etc.

                $ImageExt = substr($ImageName, strrpos($ImageName, '.'));
                $ImageExt = str_replace('.', '', $ImageExt);
                $ImageName = preg_replace("/\.[^.\s]{3,4}$/", "", $ImageName);
                $NewImageName = 'H_n_S_' . $ImageName . '-' . $RandomNum . '.' . $ImageExt;

                $ret[$NewImageName] = $output_dir . '/' . $NewImageName;
                echo $_FILES["myfile"]["tmp_name"][$i];
                move_uploaded_file($_FILES["myfile"]["tmp_name"][$i], $output_dir . '/' . $NewImageName);
            }
        }
    }
    echo json_encode($ret);

}
if ($mode == '__ADD_NEW_PRODUCT__') {
    $colorArr = $_POST['colorval'];

    if ($kProduct->addNewProduct($_POST['addProdAry'], $colorArr)) {

        echo "SUCCESS||||";
        include(__APP_PATH_VIEW_FILES__ . "/addProduct.php");
        ?>
        <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add New Product Confirmation</h4>
                    </div>
                    <div class="modal-body">
                        <p class="alert alert-success"><i class="fa fa-check"></i> A new product has been added
                            successfully.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                                onclick="redirect_url('<?php echo __VIEW_PRODUCTS_LIST_URL__; ?>'); return false;"
                                class="btn dark btn-outline">Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <?php
    } else {
        echo 'ERROR||||';
        include(__APP_PATH_VIEW_FILES__ . "/addProduct.php");

    }
}

if ($mode == '__GET_CUSTOMERS__') {
    //$checkDataExist = $kProduct->CheckPriceAssign((int)($_POST['prodid']));
    $productGroupArr = $kProduct->loadProductGroup1((int)($_POST['prodid']));
    $groupArr = explode(',', $productGroupArr[0]['groupid']);
    if (!empty ($groupArr)) {
        //$checkDataExist = $kProduct->getProdPriceByProdID((int)($_POST['prodid']));

        echo "SUCCESS||||";
        $prodArr = $kProduct->loadProducts();
        include(__APP_PATH_VIEW_FILES__ . "/viewProductsList.php");
        $count = 0; ?>


        <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Pricing Management</h4>
                    </div>
                    <div class="modal-body">
                        <div class="portlet-body form">
                            <!-- BEGIN FORM-->
                            <div id="ajaxCustomerList">
                                <form name="ProductPriceForm" id="ProductPriceForm" method="post"
                                      class="form-horizontal">
                                    <?php

                                    foreach ($groupArr as $key => $value) {
                                        //$getProdPricesArr = $kProduct->getProdPriceByProdID(0,$customer['id']);
                                        $customerByGroupArr = $kProduct->loadCustomersByGroupid($value);
                                        foreach ($customerByGroupArr as $cust) {
                                            $cutomerPriceArr = $kProduct->getProdPriceByProdID((int)($_POST['prodid']), $cust['id']);
                                            ?>

                                            <div
                                                class="form-group <?php if ($kProduct->arErrorMessages['prCustomer' . $count] != '') { ?> has-error <?php } ?>">
                                                <label
                                                    class="col-md-3 control-label"><?php echo $cust['business_name']; ?></label>
                                                <div class="col-md-7">
                                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-dot-circle-o"></i>
                                            </span>
                                                        <input type="hidden"
                                                               name="addProdPriceArr[<?php echo 'prCustomerName' . $count; ?>]"
                                                               value="<?php echo $cust['id']; ?>"/>
                                                        <input autocomplete="off" type="text" class="form-control"
                                                               name="addProdPriceArr[<?php echo 'prCustomer' . $count; ?>]"
                                                               id='<?php echo 'prCustomer' . $count; ?>'
                                                               onfocus="remove_formError(this.id,'true')"
                                                               placeholder="Product Price"
                                                               value='<?php echo($_POST['addProdPriceArr']['prCustomer' . $count] ? $_POST['addProdPriceArr']['prCustomer' . $count] : $cutomerPriceArr[0]['price']); ?>'>

                                                    </div>
                                                    <?php if ($kProduct->arErrorMessages['prCustomer' . $count] != '') { ?>
                                                        <span class="help-block"><i
                                                                class="icon icon-remove-sign"></i> <?php echo $kProduct->arErrorMessages['prCustomer' . $count]; ?></span>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <?php
                                            $count++;
                                        }
                                    } ?>
                                    <input type="hidden" name="addProdPriceArr[update]" value="1"/>
                                    <input type="hidden" name="addProdPriceArr[totalCustomerCount]"
                                           value="<?php echo $count; ?>"/>
                                    <input type="hidden" name="addProdPriceArr[prProduct]"
                                           value="<?php echo (int)($_POST['prodid']); ?>"/>
                                </form>
                            </div>
                            <?php
                            ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn blue" onclick="addProductPrice(); return false;">Save</button>
                        <a class="btn default" href="<?php echo __VIEW_PRODUCTS_LIST_URL__; ?>">Cancel</a>
                    </div>
                </div>
            </div>
        </div>


    <?php } else {
        if ($_POST['prodid'] > '0') {

            $productGroupArr = $kProduct->loadProductGroup1((int)($_POST['prodid']));
            $groupArr = explode(',', $productGroupArr[0]['groupid']);
            //$allcustomer = count($CustomersArr);
            //$assignedPriceCustomer = count($getProdPricesArr);
            //echo $assignedPriceCustomer;
            //print_r($assignedPriceCustomer);
            //if( ($assignedPriceCustomer == '1') || ($allcustomer =! $assignedPriceCustomer)){
            echo "SUCCESS||||";
            include(__APP_PATH_VIEW_FILES__ . "/viewProductsList.php");
            $count = 0; ?>


            <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Pricing Management</h4>
                        </div>
                        <div class="modal-body">

                            <div class="portlet-body form">
                                <!-- BEGIN FORM-->
                                <div id="ajaxCustomerList">
                                    <div class="form-group">
                                        <div class="col-md-3 control-label sec-head"><h4>Customer Name</h4></div>
                                        <div class="col-md-1 control-label sec-head">
                                            <h4>Price</h4>
                                        </div>
                                    </div>
                                    <?php
                                    foreach ($groupArr as $key => $value) {
                                        $getProdPricesArr = $kProduct->getProdPriceByProdID(0, $customer['id']);
                                        $customerByGroupArr = $kProduct->loadCustomersByGroupid($value);
                                        foreach ($customerByGroupArr as $cust) {
                                            $cutomerPriceArr = $kProduct->getProdPriceByProdID(0, $cust['id']);
                                            ?>

                                            <div
                                                class="form-group <?php if ($kProduct->arErrorMessages['prCustomer' . $count] != '') { ?> has-error <?php } ?>">
                                                <label
                                                    class="col-md-3 control-label"><?php echo $cust['business_name']; ?></label>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-dot-circle-o"></i>
                                            </span>
                                                        <input type="hidden"
                                                               name="addProdPriceArr[<?php echo 'prCustomerName' . $count; ?>]"
                                                               value="<?php echo $cust['id']; ?>"/>
                                                        <input autocomplete="off" type="text" class="form-control"
                                                               name="addProdPriceArr[<?php echo 'prCustomer' . $count; ?>]"
                                                               id='<?php echo 'prCustomer' . $count; ?>'
                                                               onfocus="remove_formError(this.id,'true')"
                                                               placeholder="Product Price"
                                                               value='<?php echo($_POST['addProdPriceArr']['prCustomer' . $count] ? $_POST['addProdPriceArr']['prCustomer' . $count] : $cutomerPriceArr[0]['price']); ?>'/>

                                                    </div>
                                                    <?php if ($kProduct->arErrorMessages['prCustomer' . $count] != '') { ?>
                                                        <span class="help-block"><i
                                                                class="icon icon-remove-sign"></i> <?php echo $kProduct->arErrorMessages['prCustomer' . $count]; ?></span>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <?php
                                            $count++;

                                        }
                                    } ?>
                                    <input type="hidden" name="addProdPriceArr[update]" value="0"/>
                                    <input type="hidden" name="addProdPriceArr[totalCustomerCount]"
                                           value="<?php echo $count; ?>"/>
                                    <input type="hidden" name="addProdPriceArr[prProduct]"
                                           value="<?php echo (int)($_POST['prodid']); ?>"/>
                                </div>
                                <?php
                                ?>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn blue" onclick="addProductPrice(); return false;">Save</button>
                            <a class="btn default" href="<?php echo __VIEW_PRODUCTS_LIST_URL__; ?>">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>

            <?php //}
        }
    }
}

if ($mode == '__ADD_PRODUCT_PRICE__') {
    //echo $_POST['addProdPriceArr']['totalCustomerCount'];
    if ($kProduct->addProductPrice($_POST['addProdPriceArr'])) {

        echo "SUCCESS||||";
        $prodArr = $kProduct->loadProducts();
        include(__APP_PATH_VIEW_FILES__ . "/viewProductsList.php");
        ?>
        <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Product Price Management Confirmation</h4>
                    </div>
                    <div class="modal-body">
                        <p class="alert alert-success"><i class="fa fa-check"></i> Product prices for all listed
                            customers has been added successfully.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                                onclick="redirect_url('<?php echo __VIEW_PRODUCTS_LIST_URL__; ?>'); return false;"
                                class="btn dark btn-outline">Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <?php
    } else {
        echo 'ERROR||||';
        include(__APP_PATH_VIEW_FILES__ . "/viewProductsList.php");

    }
}
if ($mode == '__PRODUCT_DETAILS__') {
    $productId = $_POST['productId'];

    echo "SUCCESS||||";
    $productArr = $kProduct->loadProducts($productId);
    if (!empty ($productArr)) {
        ?>
        <div id="clientStatus" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4><span class="caption-subject font-red-sunglo bold uppercase">Product Details</span></h4>
                    </div>
                    <div class="modal-body">

                        <table class="table table-bordered table-striped">
                            <tbody>
                            <tr>
                                <td><strong>Name</strong></td>
                                <td><?php echo $productArr['0']['name']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Description</strong></td>
                                <td><?php echo $productArr['0']['description']; ?></td>
                            </tr>
                            <tr>
                                <td><strong>Image</strong></td>
                                <td><img class="product-thumb"
                                         src="../images/products/<?php echo $productArr[0]['image']; ?>"/></td>
                            </tr>
                            <tr>
                                <td><strong>Colors</strong></td>
                                <td>
                                    <?php
                                    $colorArr = explode(',', $productArr[0]['color']);
                                    if (!empty ($colorArr)) {
                                        foreach ($colorArr as $key => $value) {
                                            $loadColorArr = $kCommon->loadColors($value);
                                            if (!empty ($loadColorArr)) {
                                                echo '<span class="product_colors" style="background-color:' . $loadColorArr[0]['code'] . ';">' . $loadColorArr[0]['name'] . '</span>';
                                            }
                                        }
                                    }

                                    ?>
                                </td>
                            </tr>

                            <?php
                            $sizeQuantityArr = $kProduct->loadSizeQuantity($productArr['0']['id']);
                            if (!empty ($sizeQuantityArr)) {
                                ?>
                                <tr>
                                    <td colspan="2"><h3>Quantity By Size</h3></td>
                                </tr>
                                <tr>
                                    <td><strong>Small</strong></td>
                                    <td><?php echo $sizeQuantityArr['0']['small']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Medium</strong></td>
                                    <td><?php echo $sizeQuantityArr['0']['medium']; ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Large</strong></td>
                                    <td><?php echo $sizeQuantityArr['0']['large']; ?></td>
                                </tr>
                                <?php
                            }
                            $sizeWeightArr = $kProduct->loadSizeWeight($productArr['0']['id']);
                            if (!empty ($sizeWeightArr)) {
                                ?>
                                <tr>
                                    <td colspan="2"><h3>Weight By Size</h3></td>
                                </tr>
                                <tr>
                                    <td><strong>Small</strong></td>
                                    <td><?php echo $sizeWeightArr['0']['small']; ?> Kg</td>
                                </tr>
                                <tr>
                                    <td><strong>Medium</strong></td>
                                    <td><?php echo $sizeWeightArr['0']['medium']; ?> Kg</td>
                                </tr>
                                <tr>
                                    <td><strong>Large</strong></td>
                                    <td><?php echo $sizeWeightArr['0']['large']; ?> Kg</td>
                                </tr>
                                <?php
                            }

                            $groupsArr = $kProduct->loadProductGroup($productArr['0']['id']);
                            $groupIdAry = explode(',', $groupsArr[0]['groupid']);
                            if (!empty ($groupIdAry)) {


                                ?>
                                <tr>
                                    <td colspan="2"><h3>Associated Groups</h3></td>
                                </tr>
                                <?php
                                foreach ($groupIdAry as $groupIdData) {

                                    $groupName = $kProduct->getGroupNameById($groupIdData)

                                    ?>
                                    <tr>
                                        <td colspan="2"><?php echo $groupName[0]['name']; ?></td>
                                    </tr>
                                <?php }
                            }
                            $customerArr = $kProduct->loadProductCustomerPrice($productArr['0']['id']);
                            if (!empty ($customerArr)) {
                                ?>
                                <tr>
                                    <td colspan="2"><h3>Associated Customer/Price</h3></td>
                                </tr>
                                <?php
                                foreach ($customerArr as $customerDet) { ?>
                                    <tr>
                                        <td><?php echo $customerDet['business_name']; ?></td>
                                        <td>$<?php echo $customerDet['price']; ?></td>
                                    </tr>
                                <?php }
                            } ?>

                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
if ($mode == '__EDIT_PRODUCT__') {
    $colorArr = $_POST['colorval'];

    if ($kProduct->updateProduct($_POST['editProdAry'], $colorArr)) {

        echo "SUCCESS||||";
        include(__APP_PATH_VIEW_FILES__ . "/editProduct.php");
        ?>
        <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Product Confirmation</h4>
                    </div>
                    <div class="modal-body">
                        <p class="alert alert-success"><i class="fa fa-check"></i> The product has been updated successfully.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                                onclick="redirect_url('<?php echo __VIEW_PRODUCTS_LIST_URL__; ?>'); return false;"
                                class="btn dark btn-outline">Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <?php
    } else {
        echo 'ERROR||||';
        include(__APP_PATH_VIEW_FILES__ . "/editProduct.php");

    }
} else if ($mode == '__DELETE_PRODUCT__') {

    $productId = $_POST['productId'];
    $_POST['searchArr']['prGroup'] = $_SESSION['prGroup'];
    $_POST['searchArr']['prParentGroup'] = $_SESSION['prParentGroup'];
    $_POST['searchArr']['szSearchText'] = $_SESSION['szSearchText'];
    $szSearchText = $_POST['searchArr'];
    echo "SUCCESS||||";
    $prodArr = $kProduct->loadProducts('','','',$szSearchText);
    $orderProductListArr = $kProduct->orderProductList();
    $ordprodlist = array();
    foreach ($orderProductListArr as $ordProd){
        array_push($ordprodlist,$ordProd['prodid']);
    }
    include(__APP_PATH_VIEW_FILES__ . "/viewProductsList.php");
    ?>
    <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Delete Product Confirmation</h4>
                </div>
                <div class="modal-body">
                    <p class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> Are you sure you want to
                        delete the selected Product?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <button type="button"
                            onclick="deleteProductConfirmation('<?php echo $productId; ?>'); return false;"
                            class="btn blue"><i class="fa fa-times"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php
} else if ($mode == '__DELETE_PRODUCT_CONFIRMATION__') {
    if ($kProduct->deleteProductById($_POST['productId'])) {

        $prodArr = $kProduct->loadProducts();
        echo "SUCCESS||||";
        include(__APP_PATH_VIEW_FILES__ . "/viewProductsList.php");
        ?>
        <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Delete Product </h4>
                    </div>
                    <div class="modal-body">
                        <p class="alert alert-success"><i class="fa fa-check"></i> Product has been deleted
                            successfully.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                                onclick="redirect_url('<?php echo __VIEW_PRODUCTS_LIST_URL__; ?>'); return false;"
                                class="btn dark btn-outline">Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo 'ERROR||||';
        include(__APP_PATH_VIEW_FILES__ . "/manageDriver.php");

    }
}
if ($mode == '__UPDATE_QUANTITY__') {

    if ($kProduct->updateQuantity($_POST['addProdInventoryArr'])) {

        echo "SUCCESS||||";
        $prodArr = $kProduct->loadProducts();
        include(__APP_PATH_VIEW_FILES__ . "/viewProductsList.php");
        ?>
        <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Product Quantity Update Confirmation</h4>
                    </div>
                    <div class="modal-body">
                        <p class="alert alert-success"><i class="fa fa-check"></i> Product quantity for all sizes has
                            been updated successfully.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                                onclick="redirect_url('<?php echo __VIEW_PRODUCTS_LIST_URL__; ?>'); return false;"
                                class="btn dark btn-outline">Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <?php
    } else {
        echo 'ERROR||||';
        include(__APP_PATH_VIEW_FILES__ . "/inventryManagement.php");

    }
}

if ($mode == '__PRODUCT_SORTING__') {
    echo "SUCCESS||||";
    $sortBy = $_POST['sortBy'];
    $sortValue = $_POST['sortValue'];
    //$invoicesArr = $kClient->getAllInvoices($sortBy,$sortValue,$_POST['searchArr'],$_SESSION['allInvoiceFlag_'.$sessionKey]);
    $prodArr = $kProduct->loadProducts('', $sortBy, $sortValue);
    include(__APP_PATH_VIEW_FILES__ . "/viewProductsList.php");
}
if ($mode == '__CUSTOMERS_SORTING__') {
    echo "SUCCESS||||";
    $sortBy = $_POST['sortBy'];
    $sortValue = $_POST['sortValue'];
    $userArr = $kUser->loadCustomers('', '', $sortBy, $sortValue);
    include(__APP_PATH_VIEW_FILES__ . "/viewUsersList.php");
} else if ($mode == '__PRODUCT_INVONTORY__') {

    $productId = $_POST['productId'];
    $_POST['searchArr']['prGroup'] = $_SESSION['prGroup'];
    $_POST['searchArr']['prParentGroup'] = $_SESSION['prParentGroup'];
    $_POST['searchArr']['szSearchText'] = $_SESSION['szSearchText'];
    $szSearchText = $_POST['searchArr'];
    echo "SUCCESS||||";
    $prodArr1 = $kProduct->loadProducts((int)($productId));
    $prodArr = $kProduct->loadProducts('','','',$szSearchText);
    $orderProductListArr = $kProduct->orderProductList();
    $ordprodlist = array();
    foreach ($orderProductListArr as $ordProd){
        array_push($ordprodlist,$ordProd['prodid']);
    }
    include(__APP_PATH_VIEW_FILES__ . "/viewProductsList.php");
    ?>
    <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Inventory Management</h4>
                </div>
                <div class="modal-body">
                    <?php

                    if (!empty ($prodArr1))
                    {
                    ?>

                    <div class="portlet-body form">
                        <!-- BEGIN FORM-->
                        <form name="ProductinventoryForm" id="ProductinventoryForm" method="post"
                              class="form-horizontal">
                            <div
                                class="form-group <?php if ($kProduct->arErrorMessages['priSmall'] != '') { ?> has-error <?php } ?>">
                                <label class="col-md-3 control-label">Quantity</label>
                                <div class="col-md-7">
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-dot-circle-o"></i>
                                            </span>
                                        <input autocomplete="off" type="number" class="form-control"
                                               name="addProdInventoryArr[priSmall]" id='priSmall'
                                               onfocus="remove_formError(this.id,'true')"
                                               value='<?php echo $prodArr1[0]['quantity']; ?>'>
                                    </div>

                                </div>
                            </div>

                            <input autocomplete="off" type="hidden" class="form-control"
                                   name="addProdInventoryArr[priProduct]" id='priProduct'
                                   onfocus="remove_formError(this.id,'true')" value='<?php echo $productId; ?>'>
                    </div>
                    <div class="error">Quantity cannot be less than 1.</div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn blue" onclick="updateInventory(); return false;">Save</button>
                    <a class="btn default" href="<?php echo __VIEW_PRODUCTS_LIST_URL__; ?>">Cancel</a>
                </div>
                <?php }
                ?>
            </div>

        </div>
    </div>
    </div>

    <?php
}

if ($mode == '__GET_USER_SEARCH__') {
    echo "SUCCESS||||";

    $userArr = $kUser->loadCustomers('', '', '', '', $_POST['searchArr']);
    $orderCustomerListArr = $kProduct->orderCustomerList();
    $ordCustlist = array();
    foreach ($orderCustomerListArr as $ordProd){
        array_push($ordCustlist,$ordProd['custid']);
    }
    include(__APP_PATH_VIEW_FILES__ . "/viewUsersList.php");
}
if ($mode == '__GET_PRODUCT_SEARCH__') {
    echo "SUCCESS||||";
    if($_POST['searchArr']['prGroup'] == '0'){
        $_POST['searchArr']['prGroup'] = '';
    }
    $_SESSION['prGroup'] = $_POST['searchArr']['prGroup'];
    $_SESSION['prParentGroup'] = $_POST['searchArr']['prParentGroup'];
    $_SESSION['szSearchText'] = $_POST['searchArr']['szSearchText'];
    $szSearchText = $_POST['searchArr'];
    $orderProductListArr = $kProduct->orderProductList();
    $ordprodlist = array();
    foreach ($orderProductListArr as $ordProd){
        array_push($ordprodlist,$ordProd['prodid']);
    }
    $prodArr = $kProduct->loadProducts('', '', '', $szSearchText);
    include(__APP_PATH_VIEW_FILES__ . "/viewProductsList.php");
} else if ($mode == '__ORDER_STATUS__') {

    $orderId = $_POST['orderId'];
    $statusValue = $_POST['stausValue'];
    echo "SUCCESS||||";
    $orderArr = $kOrder->loadOrder();
    include(__APP_PATH_VIEW_FILES__ . "/viewOrderList.php");
    ?>
    <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Change Order Status</h4>
                </div>
                <div class="modal-body">

                    <div class="portlet-body form">
                        <!-- BEGIN FORM-->
                        <form name="changeOrderStatusForm" id="changeOrderStatusForm" method="post"
                              class="form-horizontal">
                            <div
                                class="form-group <?php if ($kProduct->arErrorMessages['priSmall'] != '') { ?> has-error <?php } ?>">
                                <label class="col-md-3 control-label">Status</label>
                                <div class="col-md-7">
                                    <div class="input-group">
                                        <div class="radio-list">
                                            <label><input type="radio" name="optionsRadios" id="optionsRadios1"
                                                          value="1" <?php if ($statusValue == '1') {
                                                    echo 'checked';
                                                } ?> > Ordered</label>
                                            <!--                                                <label><input type="radio" name="optionsRadios" id="optionsRadios2" value="2" <?php if ($statusValue == '2') {
                                                echo 'checked';
                                            } ?>> Paid </label>-->
                                            <label><input type="radio" name="optionsRadios" id="optionsRadios3"
                                                          value="3" <?php if ($statusValue == '3') {
                                                    echo 'checked';
                                                } ?>> Dispatched</label>
                                            <!--                                                <label><input type="radio" name="optionsRadios" id="optionsRadios4" value="4" <?php if ($statusValue == '4') {
                                                echo 'checked';
                                            } ?>> Complete </label>-->
                                            <label><input type="radio" name="optionsRadios" id="optionsRadios5"
                                                          value="5" <?php if ($statusValue == '5') {
                                                    echo 'checked';
                                                } ?> > Canceled</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input autocomplete="off" type="hidden" class="form-control" name="orderId" id='priProduct'
                                   onfocus="remove_formError(this.id,'true')" value='<?php echo $orderId; ?>'>
                    </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn blue" onclick="changeOrderStaus(); return false;">Save</button>
                    <a class="btn default" href="<?php echo __VIEW_ORDER_LIST_URL__; ?>">Cancel</a>
                </div>
            </div>
        </div>
    </div>
    </div>

    <?php
}

if ($mode == '__CHANGE_ORDER_STATUS__') {


    if ($kOrder->changeOrderStaus($_POST['optionsRadios'], $_POST['orderId'])) {

        echo "SUCCESS||||";
        $orderArr = $kOrder->loadOrder();
        include(__APP_PATH_VIEW_FILES__ . "/viewOrderList.php");
        ?>
        <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Order Status Confirmation</h4>
                    </div>
                    <div class="modal-body">
                        <p class="alert alert-success"><i class="fa fa-check"></i> Order Staus has been updated
                            successfully.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                                onclick="redirect_url('<?php echo __VIEW_ORDER_LIST_URL__; ?>'); return false;"
                                class="btn dark btn-outline">Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <?php
    }
}
if ($mode == '__GET_ORDER_SEARCH__') {

    $szSearchText = $_POST['searchArr'];
    $_SESSION['search']['business_name'] = $_POST['searchArr']['business_name'];
    $_SESSION['search']['order_number'] = $_POST['searchArr']['order_number'];
    $_SESSION['search']['status'] = $_POST['searchArr']['status'];
    $_SESSION['search']['startcreatedon'] = $_POST['searchArr']['startcreatedon'];
    $_SESSION['search']['endcreatedon'] = $_POST['searchArr']['endcreatedon'];
    $orderArr = $kOrder->loadOrder('', $_POST['searchArr']);
    if ($orderArr) {
        echo "SUCCESS||||";
        include(__APP_PATH_VIEW_FILES__ . "/viewOrderList.php");
    } else {
        echo 'ERROR||||';
        include(__APP_PATH_VIEW_FILES__ . "/viewOrderList.php");

    }
} else if ($mode == '__PRICE_MANAGEMENT__') {

    $productId = $_POST['productId'];
    $customerId = $_POST['customerId'];


    echo "SUCCESS||||";
    $productArr = $kProduct->loadProducts($productId);
    include(__APP_PATH_VIEW_FILES__ . "/priceManagement.php");
    $cutomerPriceSmall = $kProduct->getProdPriceByProdID((int)($productId), $customerId);
    ?>
    <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Price Management</h4>
                </div>
                <div class="modal-body">
                    <div class="portlet-body form">
                        <!-- BEGIN FORM-->
                        <form name="priceManagementForm" id="priceManagementForm" method="post" class="form-horizontal">
                            <div
                                class="form-group <?php if ($kProduct->arErrorMessages['priSmall'] != '') { ?> has-error <?php } ?>">
                                <label class="col-md-3 control-label">Price</label>
                                <div class="col-md-7">
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-dot-circle-o"></i>
                                            </span>
                                        <input autocomplete="off" type="text" class="form-control"
                                               name="priceManagementArr[priSmall]" id='priSmall'
                                               onfocus="remove_formError(this.id,'true')"
                                               value='<?php echo $cutomerPriceSmall[0]['price']; ?>'>

                                    </div>

                                </div>
                                <div class="error priceerr col-md-12" style="text-align: center">Enter valid price.</div>
                            </div>

                            <input autocomplete="off" type="hidden" class="form-control"
                                   name="priceManagementArr[productId]" id='productId'
                                   onfocus="remove_formError(this.id,'true')" value='<?php echo $productId; ?>'>
                            <input autocomplete="off" type="hidden" class="form-control"
                                   name="priceManagementArr[customerId]" id='customerId'
                                   onfocus="remove_formError(this.id,'true')" value='<?php echo $customerId; ?>'>

                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn blue" onclick="updatePrice(); return false;">Save</button>
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="btn dark btn-outline">Close
                    </button>
                </div>
            </div>

        </div>
    </div>
    </div>

    <?php
}

if ($mode == '__UPDATE_PRICE__') {


    $updatePrice = $kProduct->changePrice($_POST['priceManagementArr']);
    if ($updatePrice) {
        echo "SUCCESS||||";
        $productId = $_POST['priceManagementArr']['productId'];
        $productArr = $kProduct->loadProducts($productId);
        include(__APP_PATH_VIEW_FILES__ . "/priceManagement.php");
    }

    ?>
    <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Product Price Management Confirmation</h4>
                </div>
                <div class="modal-body">
                    <p class="alert alert-success"><i class="fa fa-check"></i> Product prices has been added
                        successfully.</p>
                </div>
                <div class="modal-footer">
                    <button type="button"
                            onclick="redirect_url('<?php echo __PRICE_MANAGEMENT_URL__ . $productId . '/'; ?>'); return false;"
                            class="btn dark btn-outline">Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php
}
if ($mode == '__GET_PRODUCT_PRICE__') {

    echo "SUCCESS||||";
    $productId = $_POST['addCart']['productId'];
    $quantity = $_POST['addCart']['quantity'];
    $colorId = $_POST['addCart']['colorId'];
    $size = $_POST['addCart']['size'];

    if ($idUser) {
        $customerAry = $kUser->loadCustomers('', $idUser);
        $customerId = $customerAry[0]['id'];
        $cutomerPrice = $kProduct->getProdPriceByProdID((int)($productId), $customerId, $_POST['addCart']['size']);
        $price = $quantity * $cutomerPrice[0]['price'];
        $price = number_format((float)$price, 2, '.', '');
        $getQuantityAvail = $kProduct->loadSizeQuantity((int)($productId));
        if ($quantity != '') {
            if ($getQuantityAvail[0][$size] < $quantity) {
                $error = 'You have entered more than available quantity.';
            }
        }
    }
    $productArr = $kProduct->loadProducts($productId);
    include(__APP_PATH_FILES__ . "/customerProductDescripton.php");
}
if ($mode == '__UPDATE_CART_QUANTITY__') {

    echo "SUCCESS||||";
    $productId = $_POST['addCart']['productId'];
    $quantity = $_POST['addCart']['quantity'];
    $colorId = $_POST['addCart']['colorId'];
    $size = $_POST['addCart']['size'];

    if ($idUser) {
        $customerAry = $kUser->loadCustomers('', $idUser);
        $customerId = $customerAry[0]['id'];
        $cutomerPrice = $kProduct->getProdPriceByProdID((int)($productId), $customerId, $_POST['addCart']['size']);
        $getQuantityAvail = $kProduct->loadSizeQuantity((int)($productId));
        if ($quantity != '') {
            $price = $quantity * $cutomerPrice[0]['price'];
            $price = number_format((float)$price, 2, '.', '');
            if ($getQuantityAvail[0][$size] < $quantity) {
                $error = 'You have entered more than available quantity.';
            }
        }

    }
    $productArr = $kProduct->loadProducts($productId);
    include(__APP_PATH_FILES__ . "/customerProductDescripton.php");
}
if ($mode == '__REFRESH_TROLLY__') {
    $cartArr = $kOrder->loadCart(0, $_SESSION['usr']['customerid']);
    $ctr = 0;
    if (!empty ($cartArr)) {
        foreach ($cartArr as $cartitems) {
            $ctr++;
        }
    }
    if ($ctr > 0) {
        echo "SUCCESS||||"
        ?>
        <a class="btn cart addcart" href="<?php echo __CUSTOMER_CART_URL__; ?>"><i class="fa fa-opencart"
                                                                                   aria-hidden="true"></i><?php echo($ctr > 0 ? '<span>' . $ctr . '</span>' : ''); ?>
        </a>
    <?php }
    die;
}
if ($mode == '__ADD_TO_CART__') {

    $_POST['addCart']['colorId'] = $_POST['colorval'];
    $_POST['addCart']['quantity'] = $_POST['qty'];
    $_POST['addCart']['customerId'] = $_POST['custid'];
    $_POST['addCart']['price'] = $_POST['price'] * $_POST['qty'];
    $_POST['addCart']['productId'] = $_POST['prodid'];
    if ($kOrder->addCart($_POST['addCart'])) {

        echo "SUCCESS||||";
        session_start();
        $_SESSION["addCartSuccess"] = true;
        echo __URL_BASE__;
        die();
    } else {
        echo "ERROR||||";
        include(__APP_PATH_FILES__ . "/customerProductList.php");
    }

}
if ($mode == '__ADD_COLOR_CART__') {

    echo "SUCCESS||||";
    $productId = $_POST['addCart']['productId'];
    $quantity = $_POST['addCart']['quantity'];
    $colorId = $_POST['colorId'];
    $size = $_POST['addCart']['size'];

    if ($idUser) {
        $customerAry = $kUser->loadCustomers('', $idUser);
        $customerId = $customerAry[0]['id'];
        $cutomerPrice = $kProduct->getProdPriceByProdID((int)($productId), $customerId, $_POST['addCart']['size']);
        $getQuantityAvail = $kProduct->loadSizeQuantity((int)($productId));
        if ($quantity != '') {
            $price = $quantity * $cutomerPrice[0]['price'];
            $price = number_format((float)$price, 2, '.', '');
            if ($getQuantityAvail[0][$size] < $quantity) {
                $error = 'You have entered more than available quantity.';
            }
        }

    }
    $productArr = $kProduct->loadProducts($productId);
    include(__APP_PATH_FILES__ . "/customerProductDescripton.php");
}

if ($mode == '__DELETE_CART_ITEM__') {
    $cartid = $_POST['cartid'];
    $customerid = $_POST['customerid'];

    if ($kOrder->deleteCartById($customerid, $cartid)) {

        echo "SUCCESS||||";
        include(__APP_PATH_FILES__ . "/shopbag.php");
        ?>
        <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Cart Item Remove Confirmation</h4>
                    </div>
                    <div class="modal-body">
                        <p class="alert alert-success"><i class="fa fa-check"></i> Selected item successfully removed
                            from your shopping bag.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                                onclick="redirect_url('<?php echo __CUSTOMER_CART_URL__; ?>'); return false;"
                                class="btn dark btn-outline">Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <?php
    } else {
        echo 'ERROR||||';
    }
}
if ($mode == '__VIEW_ORDER_HISTORY__') {
    $orderId = $_POST['orderId'];

    echo "SUCCESS||||";
    $orderArr = $kOrder->loadOrder();
    $laodOrderAry = $kOrder->loadProductOrder($orderId);
    $orderloadArr = $kOrder->loadOrder($orderId);

    if (!empty ($orderArr)) {
        ?>
        <div id="view-history" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Order Details</h4>
                    </div>
                    <div class="modal-body scroll">
                        <div class="view-history-row clearfix">
                            <span>Order ID:</span><span><?php echo '#' . $orderloadArr['0']['id']; ?></span>
                            <span>Status:</span>
                            <span>
                            <?php
                            if ($orderloadArr['0']['status'] == '1') {
                                ?>
                                <a title="Order Status" class="label label-sm label-warning" href="javascript:void(0);"
                                   onclick="orderStatus('<?php echo $orderData['id']; ?>','<?php echo $orderData['status']; ?>');">
                                            Ordered 
                                        </a>
                                <?php
                            }
                            if ($orderloadArr['0']['status'] == '2') {
                                ?>
                                <a title="Order Status" class="label label-sm label-info" href="javascript:void(0);"
                                   onclick="orderStatus('<?php echo $orderData['id']; ?>','<?php echo $orderData['status']; ?>');">
                                            Pending 
                                        </a>
                                <?php
                            }
                            if ($orderloadArr['0']['status'] == '3') {
                                ?>
                                <a title="Order Status" class="label label-sm label-info" href="javascript:void(0);"
                                   onclick="orderStatus('<?php echo $orderData['id']; ?>','<?php echo $orderData['status']; ?>');">
                                            Dispatched 
                                        </a>
                                <?php
                            }
                            if ($orderloadArr['0']['status'] == '4') {
                                ?>
                                <a title="Order Status" class="label label-sm label-success" href="javascript:void(0);"
                                   onclick="orderStatus('<?php echo $orderData['id']; ?>','<?php echo $orderData['status']; ?>');">
                                            Complete 
                                        </a>
                                <?php
                            }
                            if ($orderloadArr['0']['status'] == '5') {
                                ?>
                                <a title="Order Status" class="label label-sm label-danger" href="javascript:void(0);"
                                   onclick="orderStatus('<?php echo $orderData['id']; ?>','<?php echo $orderData['status']; ?>');">
                                            Canceled 
                                        </a>
                                <?php
                            }

                            ?>
                        </span>
                        </div>
                        <?php
                        if ($orderloadArr['0']['createdon'] !== '0000-00-00 00:00:00') {
                            ?>
                            <div class="view-history-row clearfix">
                                <span>Placed On:</span>
                                <span><?php echo date('d/m/Y', strtotime($orderloadArr['0']['createdon'])); ?></span>
                            </div>
                            <?php

                        }

                        /*if (($orderloadArr['0']['status'] != '2') && ($orderloadArr['0']['paidon'] !== '0000-00-00 00:00:00')) {
                            */?><!--
                            <div class="view-history-row clearfix">
                                <span>Paid On:</span>
                                <span><?php /*echo date('d/m/Y', strtotime($orderloadArr['0']['paidon'])); */?></span>
                            </div>
                            --><?php
/*
                        }*/

                        if ($orderloadArr['0']['dispatchedon'] !== '0000-00-00 00:00:00') {
                            ?>
                            <div class="view-history-row clearfix">
                                <span>Dispatched On:</span>
                                <span><?php echo date('d/m/Y', strtotime($orderloadArr['0']['dispatchedon'])); ?></span>
                            </div>
                            <?php

                        }
                        ?>
                        <?php
                        if ($orderloadArr['0']['completedon'] !== '0000-00-00 00:00:00' && $orderloadArr['0']['status'] == '4') {
                            ?>
                            <div class="view-history-row clearfix">
                                <span>Completed On:</span>
                                <span><?php echo date('d/m/Y', strtotime($orderloadArr['0']['completedon'])); ?></span>
                            </div>
                            <?php

                        }
                        ?>
                        <?php
                        if ($orderloadArr['0']['cancledon'] !== '0000-00-00 00:00:00' && $orderloadArr['0']['status'] == '5') {
                            ?>
                            <div class="view-history-row clearfix">
                                <span>Canceled On:</span>
                                <span><?php echo date('d/m/Y', strtotime($orderloadArr['0']['cancledon'])); ?></span>
                            </div>
                            <?php

                        }
                        ?>
                        <div class="view-history-row clearfix">
                            <h3 class="product-details">Product Details</h3>
                        </div>
                        <?php
                        foreach ($laodOrderAry as $laodOrderData) {
                            ?>
                            <div class="view-history-row clearfix">
                                <span>Product:</span>
                                <span>
                            <?php echo $laodOrderData['description']; ?>
                        </span>
                                <!--                        <span>Price/Quantity:</span>
                        <span>
                            <?php echo '$' . $laodOrderData['price']; ?>
                        </span>-->
                                <span>Quantity:</span>
                                <span>
                            <?php echo $laodOrderData['quantity']; ?>
                        </span>
                                <!--                        <span>Total Price:</span>
                        <span>
                            <?php echo '$' . ($laodOrderData['price'] * $laodOrderData['quantity']); ?>
                        </span>-->
                                <!--<span>color:</span>
                    <span>
                        <?php /*
                                    $colorArr = explode(',', $laodOrderData['color']);
                                    if(!empty ($colorArr)){
                                        foreach ($colorArr as $key => $value) {
                                            $loadColorArr = $kCommon->loadColors($value);
                                            if(!empty ($loadColorArr)){
                                                echo '<span class="product_colors" style="background-color:'.$loadColorArr['0']['code'].';">'.$loadColorArr['0']['name'].'</span>';
                                            }
                                        }
                                    }
                                       
                                    */
                                ?>
                    </span>-->

                            </div>


                            <?php

                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
if ($mode == 'EXANDD_ORDER_HISTORY_FORM') {

    echo "SUCCESS||||";
    $orderArr = $kOrder->loadOrder('', '', $customerId);
    include(__APP_PATH_FILES__ . "/myAccount.php");
}
if ($mode == 'EXANDD_CHANGE_PASSWORD_FORM') {

    echo "SUCCESS||||";
    include(__APP_PATH_FILES__ . "/change_password.php");
}
if ($mode == '__UPDATE_PASSWORD__') {


    if ($kUser->changePassword($_POST['changePassword'])) {

        echo "SUCCESS||||";
        $_SESSION['success_password'] = true;
        include(__APP_PATH_FILES__ . "/change_password.php");
    } else {
        echo 'ERROR||||';
        include(__APP_PATH_FILES__ . "/change_password.php");

    }
}

if ($mode == '__RE_ORDER_PRODUCT__') {
    $orderId = $_POST['orderId'];
    $laodOrderAry = $kOrder->loadProductOrder($orderId);
    foreach ($laodOrderAry as $laodOrderData) {
        $data = array();
        $price = $laodOrderData['price'] * $laodOrderData['quantity'];
        $data['price'] = $price;
        $data['size'] = $laodOrderData['size'];
        $data['quantity'] = $laodOrderData['quantity'];
        $data['colorId'] = $laodOrderData['color'];
        $data['customerId'] = $customerId;
        $data['productId'] = $laodOrderData['productid'];
        if (!empty($data)) {
            $addcart = $kOrder->addCart($data);
        }
    }
    echo "SUCCESS||||";
    echo __URL_BASE__ . '/shoppingbag/';
    die();


}

if ($mode == '__UPDATE_CART_QUANTITY_ON_CART__') {

    $productId = $_POST['prodid'];
    $quantity = $_POST['qty'];
    $size = $_POST['size'];
    $getQuantityAvail = $kProduct->loadSizeQuantity((int)($productId));
    if ($quantity > 0) {

        if ($getQuantityAvail[0][$size] < $quantity) {
            $error = 'You have entered more than available quantity.';
            echo "SUCCESS||||" . $error;

        }
    }

}
if ($mode == '__PRODUCT_PAGINATION__') {

    echo "SUCCESS||||";
    $iPageNumber = $_POST['pageId'];
    $iNumberOfPage = 0;
    $show_pagination = false;

    $customerProdArr = $kProduct->viewProductForCustomer($_SESSION['usr']['customerid']);
    $iTotalRecords = count($customerProdArr);
    if ($iTotalRecords > __TOTAL_ROW_PER_PAGE__) {
        $show_pagination = true;
        $iNumberOfPage = ceil($iTotalRecords / __TOTAL_ROW_PER_PAGE__);
        if ($iPageNumber > $iNumberOfPage) $iPageNumber = $iNumberOfPage;
        $customerProdArr = $kProduct->viewProductForCustomer($_SESSION['usr']['customerid'], $iPageNumber, __TOTAL_ROW_PER_PAGE__);

    }

    include(__APP_PATH_FILES__ . "/customerProductList.php");


} elseif ($mode == '__ORDER_EDIT_ADMIN__') {

    $orderid = $_POST['orderid'];

    echo "SUCCESS||||";
    $orderedProdDet = $kOrder->loadProductOrder((int)$orderid);
    $orderArr = $kOrder->loadOrder();
    $allProdArr = $kProduct->loadProducts();
    ?>
    <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog editorder-dialogue">
            <div class="modal-content">
                <div class="modal-header">

                    <button type="button" class="close"
                            onclick="redirect_url('<?php echo __VIEW_ORDER_LIST_URL__; ?>');" data-dismiss="modal"
                            aria-hidden="true"></button>
                    <h4 class="modal-title">Edit Order
                        <button onclick="AddOrderProd(); return false;" class="btn purple">ADD Product</button>
                    </h4>

                </div>
                <div class="modal-body">
                    <form name="OrderedinventoryForm" id="OrderedinventoryForm" method="post" class="form-horizontal">
                        <div class="portlet-body form">
                            <div class="form-group row add-order-form" id="add-order-prod-form">
                                <div class="">
                                    <label class="col-md-3 control-label mobile-ver"><strong>Product
                                            Name: </strong></label>
                                    <div class="col-md-5">
                                        <!--                                        <select name="add-ord-prod" id="add-ord-prod" onchange="GetAvailQtys();" class="form-control col-md-12">
                                            <option value="0">Select</option>
                                            <?php
                                        if (!empty ($allProdArr)) {
                                            foreach ($allProdArr as $prods) { ?>
                                            <option value="<?php echo $prods['id']; ?>"><?php echo $prods['name']; ?></option>
                                                <?php }
                                        }
                                        ?>
                                        </select>-->
                                        <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-ticket"></i>
                                                </span>
                                            <input type="text" class="form-control" name="add-ord-prod-view"
                                                   autocomplete="off" id='add-ord-prod-view' onkeyup="search_ajax_way()"
                                                   placeholder="Product Name"
                                                   value="<?php echo $_POST['add-ord-prod'] ?>">
                                            <input type="hidden" class="form-control" name="add-ord-prod"
                                                   id='add-ord-prod' value="<?php echo $_POST['add-ord-prod'] ?>">
                                        </div>
                                        <div class="btn-group open">
                                            <ul class="dropdown-menu" role="menu" id="display_results">
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        //                        $prodQuantityArr = $kProduct->loadSizeQuantity((int)($productId));
                        if (!empty ($orderedProdDet))
                        {
                        $ctr = 0;
                        $dispatchCtr = 0;
                        foreach ($orderedProdDet as $orddet) {
                            $prodid = $orddet['productid'];
                            $prdqty = $orddet['size'];
                            ?>

                            <div class="portlet-body form">
                                <!-- BEGIN FORM-->
                                <div
                                    class="form-group <?php if ($kOrder->arErrorMessages['prod' . $prodid] != '') { ?> has-error <?php } ?>">

                                    <h4 class="col-md-12 control-label mobile-ver1">Product:
                                        <strong><?php echo $orddet['description']; ?></strong></h4>

                                    <label
                                        class="col-md-3 control-label mobile-ver2"><strong>Ordered: </strong><?php echo $orddet['quantity']; ?>
                                    </label>

                                    <label
                                        class="col-md-2 control-label mobile-ver2"><strong>Available: </strong><?php echo $orddet['availqty']; ?>
                                    </label>

                                    <label class="col-md-2 control-label mobile-ver mobile-ver2"><strong>Dispatch
                                            Qty: </strong></label>
                                    <div class="col-md-2  mobile-ver mobile-ver2">
                                        <div class="input-group">

                                            <input type="hidden" name="changeOrdQty[origqty<?php echo $ctr; ?>]"
                                                   id="origqty<?php echo $ctr; ?>"
                                                   value="<?php echo $orddet['availqty']; ?>"/>
                                            <input type="hidden" name="changeOrdQty[prd<?php echo $ctr; ?>]"
                                                   value="<?php echo $prodid; ?>"/>
                                            <?php if ($orddet['dispatched'] > 0) {
                                                $dispatchCtr++;
                                                ?>
                                                <label
                                                    class="col-md-12 control-label"><?php echo $orddet['dispatched']; ?></label>
                                            <?php } else { ?>
                                                <span class="input-group-addon">
                                                    <i class="fa fa-dot-circle-o"></i>
                                                </span>
                                                <input autocomplete="off" type="text" class="form-control"
                                                       name="changeOrdQty[prod<?php echo $ctr; ?>]"
                                                       id='prod<?php echo $ctr; ?>'
                                                       onfocus="remove_formError(this.id,'true')" value=''>
                                            <?php } ?>
                                        </div>

                                    </div>
                                    <?php if ($orddet['dispatched'] == 0) { ?>
                                        <div class="col-md-1 mobile-ver2">
                                            <a onclick="deleteOrderProdConfirmation('<?php echo $orddet['id']; ?>','<?php echo $orderid; ?>')"
                                               href="javascript:void(0);"
                                               class="btn btn-outline red btn-sm black del-mobile-ver" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>
                                    <?php } ?>
                                </div>

                            </div>

                            <hr/>
                            <?php
                            $ctr++;
                        } ?>
                    </form>
                    <div class="portlet-body form">
                        <div class="error qtyerr">Input quantity is greater than available quantity.</div>
                        <div class="emptyerr error">Please enter dispatch quantity at least for one product.</div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn red-sunglo" onclick="cancelOrder(); return false;">Canceled</button>
                    <button class="btn label-success pending" onclick="pendingOrder(); return false;">Pending</button>
                    <button class="btn blue"
                            onclick="<?php echo(($ctr == $dispatchCtr) ? 'statusTodispatch(' . '\'' . $orderid . '\'); return false;' : 'dispatchOrder(); return false;'); ?>">
                        Dispatch
                    </button>
                    <a class="btn default" href="<?php echo __VIEW_ORDER_LIST_URL__; ?>">Cancel</a>
                </div>
                <?php
                }
                ?>
                <input type="hidden" id="ordid" value="<?php echo $orderid; ?>"/>
                <input type="hidden" id="totcount" value="<?php echo $ctr; ?>"/>
            </div>

        </div>
    </div>
    </div>
    <?php
}
if ($mode == '__DISPATCHED__') {

    $counter = $_POST['counter'];
    $ordid = $_POST['ordid'];
    $checkcount = 0;
    for ($i = 0; $i < $counter; $i++) {
        $prodid = $_POST['changeOrdQty']['prd' . $i];
        $qty = $_POST['changeOrdQty']['prod' . $i];
        if ($kOrder->updateOrder($prodid, $qty, $ordid)) {
            $checkcount++;
        }
    }

    if ($checkcount > 0) {
        if ($kOrder->changeOrderStaus('3', $ordid)) {
            $orderDetArr = $kOrder->loadOrder($ordid);
            if (!empty($orderDetArr)) {
                define('XERO_KEY','LL1PFXTPUJGOG5WXLTKVQC7GMUUY2P');
                define('XERO_SECRET','0HNKVD2DDTMDAIMOFMWSRZRYV2N8ON');
                $xero = new Xero(XERO_KEY, XERO_SECRET, __APP_PATH__ . '/includes/publickey.cer', __APP_PATH__ . '/includes/privatekey.pem', 'xml' );
                foreach ($orderDetArr as $orderDet) {
                    if ($orderDet['xeroprocessed'] == '0') {
                        $subResult = $kOrder->loadProductOrder($orderDet['id']);

                        $lineItems = array();
                        if (!empty($subResult)) {
                            foreach ($subResult as $ordprods) {
                                $lienItem = array(
                                    "Description" => $ordprods['description'],
                                    "Quantity" => $ordprods['dispatched'],
                                    "UnitAmount" => $ordprods['price'],
                                    "LineAmount" => $ordprods['price'] * $ordprods['dispatched'],
                                    "AccountCode" => "41000"
                                );
                                array_push($lineItems, $lienItem);
                            }
                        }
                        $new_invoice = array(
                            array(
                                "Type" => "ACCREC",
                                "Contact" => array(
                                    "Name" => $orderDet['business_name']
                                ),
                                "Date" => $orderDet['dispatchedon'],
                                "DueDate" => date('Y-m-d H:i:s', strtotime($orderDet['dispatchedon'] . ' +30 day')),
                                "Reference" => "Ordering System",
                                "Status" => "AUTHORISED",
                                "LineAmountTypes" => "Exclusive",
                                "LineItems" => array(
                                    "LineItem" => $lineItems
                                )
                            )
                        );
                        $invoice_result = $xero->Invoices($new_invoice);
                        $result = $xero->Accounts(false, false, array("Name" => $orderDet['business_name']));
                        $org_invoices = $xero->Invoices;

                        $invoice_count = sizeof($org_invoices->Invoices->Invoice);

                        //$invoice_index = rand(0,$invoice_count);
                        $invoice_index = $invoice_count-1;
                        $invoice_id = (string) $org_invoices->Invoices->Invoice[$invoice_index]->InvoiceID;
                        $invoice_number = (string) $org_invoices->Invoices->Invoice[$invoice_index]->InvoiceNumber;
                        if($result){
                            $kOrder->updateXero($orderDet['id'],$invoice_number);
                            echo "SUCCESS||||";
                            $orderArr = $kOrder->loadOrder();
                            include(__APP_PATH_VIEW_FILES__ . "/viewOrderList.php");
                            ?>
                            <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Order Status</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p class="alert alert-success"><i class="fa fa-check"></i> Your order has been dispatched successfully.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button"
                                                    onclick="redirect_url('<?php echo __VIEW_ORDER_LIST_URL__; ?>'); return false;"
                                                    class="btn dark btn-outline">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        if(!$invoice_id) {
                            echo "You will need some invoices for this...";
                        }

                        // Now retrieve that and display the pdf
                        /*$pdf_invoice = $xero->Invoices($invoice_id, '', '', '', 'pdf');
                        header('location:' . __CUSTOMER_THANK_URL__);
                        die;*/
                        /*header('Content-type: application/pdf');
                        header('Content-Disposition: inline; filename="the.pdf"');

                        echo ($pdf_invoice);*/
                    }
                }
            }


        }
    } else {
        echo 'ERROR||||';
        $orderArr = $kOrder->loadOrder();
        include(__APP_PATH_VIEW_FILES__ . "/viewOrderList.php");

    }
}
if ($mode == '__STATUS_TO_DISPATCHED__') {
    $ordid = $_POST['ordid'];
    if ($ordid > 0) {
        if ($kOrder->changeOrderStaus('3', $ordid)) {
            $orderDetArr = $kOrder->loadOrder($ordid);
            if (!empty($orderDetArr)) {
                define('XERO_KEY','LL1PFXTPUJGOG5WXLTKVQC7GMUUY2P');
                define('XERO_SECRET','0HNKVD2DDTMDAIMOFMWSRZRYV2N8ON');
                $xero = new Xero(XERO_KEY, XERO_SECRET, __APP_PATH__ . '/includes/publickey.cer', __APP_PATH__ . '/includes/privatekey.pem', 'xml' );
                foreach ($orderDetArr as $orderDet) {
                    if ($orderDet['xeroprocessed'] == '0') {
                        $subResult = $kOrder->loadProductOrder($orderDet['id']);

                        $lineItems = array();
                        if (!empty($subResult)) {
                            foreach ($subResult as $ordprods) {
                                $lienItem = array(
                                    "Description" => $ordprods['description'],
                                    "Quantity" => $ordprods['dispatched'],
                                    "UnitAmount" => $ordprods['price'],
                                    "LineAmount" => $ordprods['price'] * $ordprods['dispatched'],
                                    "AccountCode" => "41000"
                                );
                                array_push($lineItems, $lienItem);
                            }
                        }
                        $new_invoice = array(
                            array(
                                "Type" => "ACCREC",
                                "Contact" => array(
                                    "Name" => $orderDet['business_name']
                                ),
                                "Date" => $orderDet['dispatchedon'],
                                "DueDate" => date('Y-m-d H:i:s', strtotime($orderDet['dispatchedon'] . ' +30 day')),
                                "Reference" => "Ordering System",
                                "Status" => "AUTHORISED",
                                "LineAmountTypes" => "Exclusive",
                                "LineItems" => array(
                                    "LineItem" => $lineItems
                                )
                            )
                        );
                        $invoice_result = $xero->Invoices($new_invoice);
                        $result = $xero->Accounts(false, false, array("Name" => $orderDet['business_name']));
                        $org_invoices = $xero->Invoices;

                        $invoice_count = sizeof($org_invoices->Invoices->Invoice);

                        //$invoice_index = rand(0,$invoice_count);
                        $invoice_index = $invoice_count-1;
                        $invoice_id = (string) $org_invoices->Invoices->Invoice[$invoice_index]->InvoiceID;
                        $invoice_number = (string) $org_invoices->Invoices->Invoice[$invoice_index]->InvoiceNumber;
                        if($result){
                            $kOrder->updateXero($orderDet['id'],$invoice_number);
                            echo "SUCCESS||||";
                            $orderArr = $kOrder->loadOrder();
                            include(__APP_PATH_VIEW_FILES__ . "/viewOrderList.php");
                            ?>
                            <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Order Status</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p class="alert alert-success"><i class="fa fa-check"></i> Your order has been dispatched successfully.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button"
                                                    onclick="redirect_url('<?php echo __VIEW_ORDER_LIST_URL__; ?>'); return false;"
                                                    class="btn dark btn-outline">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        if(!$invoice_id) {
                            echo "You will need some invoices for this...";
                        }

                    }
                }
            }


        }
    } else {
        echo 'ERROR||||';
        $orderArr = $kOrder->loadOrder();
        include(__APP_PATH_VIEW_FILES__ . "/viewOrderList.php");

    }
}
if ($mode == '__GET_ORDER_REPORT_SEARCH__') {

    $szSearchText = $_POST['searchArr'];
    $searchar = $szSearchText;
    $orderArr = $kOrder->loadOrder('', $_POST['searchArr']);
    if ($orderArr) {
        echo "SUCCESS||||";
        include(__APP_PATH_VIEW_FILES__ . "/orderReports.php");
    } else {
        echo 'ERROR||||';
        include(__APP_PATH_VIEW_FILES__ . "/orderReports.php");

    }
}

if ($mode == '__GET_ORDER_DETAIL_REPORT_SEARCH__') {
    $_POST['searchArr']['orderstat'] = '3';
    $szSearchText = $_POST['searchArr'];
    $orderArr = $kOrder->loadOrder('', $_POST['searchArr']);
    if ($orderArr) {
        echo "SUCCESS||||";
        include(__APP_PATH_VIEW_FILES__ . "/orderDetailReport.php");
    } else {
        $showNoProd = true;
        echo 'ERROR||||';
        include(__APP_PATH_VIEW_FILES__ . "/orderDetailReport.php");

    }
}

if ($mode == '__GET_LABEL_REPORT__') {

    if (!empty ($_POST['searchArr']['customer']) && !empty ($_POST['searchArr']['labelcount']) && !empty ($_POST['searchArr']['labeldate'])) {
        $totalcount = $_POST['searchArr']['labelcount'];
        echo "SUCCESS||||";
        $content = '<div class="portlet box blue">
                 <div class="portlet-title">
                    <div class="caption" id="ord-docket">
                        <i class="fa fa-users"></i>Labels Report
                        </div>
                        <div class="actions">
                        <button class="btn green uppercase bold" type="button" onclick="orderDocketPrint()"><i class="fa fa-print"></i>Print</button>
                    </div>
                    
                </div>
        
                <div id="label-print" class="portlet-body">
                    <div class="row">';
        for ($count = 1; $count <= $totalcount; $count++) {
            $content .= '<div class="col-md-3 labelthumb" style="float:left; padding:0 15px; width: 98mm; height: 38mm">
                        <h4><b>' . $_POST['searchArr']['customer'] . '</b></h4>
                        <p>' . $count . ' of ' . $totalcount . '</p>
                        <p>' . date('d M Y', strtotime(getSqlFormattedDate($_POST['searchArr']['labeldate']))) . '</p>
                </div>';
        }
        $content .= '</div>
                </div>
             </div>';
        echo $content;
    } else {
        echo 'ERROR||||';

    }
}

if ($mode == '__PENDING__') {

    $counter = $_POST['counter'];
    $ordid = $_POST['ordid'];
    $checkcount = 0;
    for ($i = 0; $i < $counter; $i++) {
        $prodid = $_POST['changeOrdQty']['prd' . $i];
        $qty = $_POST['changeOrdQty']['prod' . $i];
        if ($kOrder->updateOrder($prodid, $qty, $ordid)) {
            $checkcount++;
        }
    }

    if ($checkcount > 0) {
        if ($kOrder->changeOrderStaus('2', $ordid)) {
            echo "SUCCESS||||";
            $orderArr = $kOrder->loadOrder();
            include(__APP_PATH_VIEW_FILES__ . "/viewOrderList.php");
            ?>
            <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Order status</h4>
                        </div>
                        <div class="modal-body">
                            <p class="alert alert-success"><i class="fa fa-check"></i> Your order has been successfully updated.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button"
                                    onclick="redirect_url('<?php echo __VIEW_ORDER_LIST_URL__; ?>'); return false;"
                                    class="btn dark btn-outline">Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <?php
        }
    } else {
        echo 'ERROR||||';
        $orderArr = $kOrder->loadOrder();
        include(__APP_PATH_VIEW_FILES__ . "/viewOrderList.php");

    }
}

if ($mode == '__CANCEL_ORDER__') {

    $ordid = $_POST['ordid'];


    if ($ordid > 0) {
        if ($kOrder->changeOrderStaus('5', $ordid)) {
            echo "SUCCESS||||";
            $orderArr = $kOrder->loadOrder();
            include(__APP_PATH_VIEW_FILES__ . "/viewOrderList.php");
            ?>
            <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Order Cancellation Confirmation</h4>
                        </div>
                        <div class="modal-body">
                            <p class="alert alert-success"><i class="fa fa-check"></i> Order canceled successfully.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button"
                                    onclick="redirect_url('<?php echo __VIEW_ORDER_LIST_URL__; ?>'); return false;"
                                    class="btn dark btn-outline">Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <?php
        }
    } else {
        echo 'ERROR||||';
        $orderArr = $kOrder->loadOrder();
        include(__APP_PATH_VIEW_FILES__ . "/viewOrderList.php");

    }
}
elseif ($mode == '__IMPORT_MODAL__') {


    echo "SUCCESS||||";
    $prodArr = $kProduct->loadProducts();
    include(__APP_PATH_VIEW_FILES__ . "/viewProductsList.php");
    ?>
    <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Import Customer Product Price</h4>
                </div>
                <form name="ProductimportForm" id="ProductimportForm" method="post" action="" class="form-horizontal"
                      enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="portlet-body form">
                            <!-- BEGIN FORM-->
                            <div class="col-md-7">
                                <div class="input-group">
                                    <input autocomplete="off" type="file" name="productPrice" id='productPrice'
                                           onfocus="remove_formError(this.id,'true')">
                                    <input type="hidden" name="importprice" value="1"/>

                                </div>

                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <input type="submit" name="pricesimport" value="Import" class="btn blue"/>
                        <a class="btn default" href="<?php echo __VIEW_PRODUCTS_LIST_URL__; ?>">Cancel</a>
                    </div>
                </form>
            </div>

        </div>
    </div>
    </div>

    <?php
}
elseif ($mode == '__IMPORT_CUSTOMER_MODAL__') {


    echo "SUCCESS||||";
    $userArr=$kUser->loadCustomers();
    include(__APP_PATH_VIEW_FILES__ . "/viewUsersList.php");
    ?>
    <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Import Customers</h4>
                </div>
                <form name="ProductimportForm" id="ProductimportForm" method="post" action="" class="form-horizontal"
                      enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="portlet-body form">
                            <!-- BEGIN FORM-->
                            <div class="col-md-7">
                                <div class="input-group">
                                    <input autocomplete="off" type="file" name="impcustomers" id='impcustomers'
                                           onfocus="remove_formError(this.id,'true')">
                                    <input type="hidden" name="importcustomers" value="1"/>

                                </div>

                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <input type="submit" name="pricesimport" value="Import" class="btn blue"/>
                        <a class="btn default" href="<?php echo __VIEW_CUSTOMERS_LIST_URL__; ?>">Cancel</a>
                    </div>
                </form>
            </div>

        </div>
    </div>
    </div>

    <?php
}

if ($mode == '__DELETE_ORDER_PRODUCT__') {

    $orderDetID = $_POST['orderDetId'];
    $ordid = $_POST['orderid'];
    $orderDetArr = $kOrder->LoadOrderDetailByDetailID($orderDetID);
    if (!empty ($orderDetArr)) {
        $deductPrice = ($orderDetArr[0]['price'] * $orderDetArr[0]['quantity']);
        if ($kOrder->DeductOrderPriceOnProdRemove($orderDetArr[0]['orderid'], $deductPrice)) {
            if ($kOrder->DeleteOrderProduct($orderDetID)) {
                echo "SUCCESS||||";
                $orderArr = $kOrder->loadOrder();
                include(__APP_PATH_VIEW_FILES__ . "/viewOrderList.php");
                ?>
                <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Product Delete From Order Confirmation</h4>
                            </div>
                            <div class="modal-body">
                                <p class="alert alert-success"><i class="fa fa-check"></i> Selected product has been
                                    deleted successfully from this order.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" onclick="editorderadmin('<?php echo $ordid; ?>');"
                                        data-dismiss="modal" class="btn dark btn-outline">Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
            } else {
                echo 'ERROR1||||';
                $orderArr = $kOrder->loadOrder();
                include(__APP_PATH_VIEW_FILES__ . "/viewOrderList.php");

            }
        } else {
            echo 'ERROR2||||';
            $orderArr = $kOrder->loadOrder();
            include(__APP_PATH_VIEW_FILES__ . "/viewOrderList.php");

        }
    } else {
        echo 'ERROR3||||';
        $orderArr = $kOrder->loadOrder();
        include(__APP_PATH_VIEW_FILES__ . "/viewOrderList.php");

    }
}
elseif ($mode == '__GET_AVAIL_QTY__') {
    if ($_POST['prodid'] > 0) {
        $prodid = $_POST['prodid'];
        $AllProdArr = $kProduct->loadProducts((int)$prodid);
        if (!empty ($AllProdArr)) {
            echo "SUCCESS||||";
            ?>
            <div class="form-group add-order-values" id="add-order-values-ajax">
                <div class="form-group ">
                    <label
                        class="col-md-2 col-xs-12 col-sm-2 control-label mobile-ver2"><strong>Ordered: </strong></label>
                    <div class="col-md-2  col-xs-12 col-sm-6 mobile-ver mobile-ver2">
                        <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-dot-circle-o"></i>
                        </span>
                            <input type="text" value="" onfocus="remove_formError(this.id,'true')" id="new-ord-prd"
                                   name="new-ord-prd" class="form-control" autocomplete="off">
                        </div>

                    </div>
                    <label
                        class="col-md-2 col-xs-12 col-sm-3 control-label save-new-ord-prd-xs"><strong>Available: </strong><?php echo $AllProdArr[0]['quantity']; ?>
                    </label>
                    <label class="col-md-2 col-xs-12 col-sm-2 control-label save-new-ord-prd-xs tab-clear"><strong>Dispatch
                            Qty: </strong></label>
                    <div class="col-md-2 col-xs-12 col-sm-6 mobile-ver mobile-ver2 save-new-ord-prd-sm">
                        <div class="input-group">

                            <input type="hidden" value="<?php echo $AllProdArr[0]['quantity']; ?>" id="avail-qty"
                                   name="avail-qty">
                            <span class="input-group-addon">
                            <i class="fa fa-dot-circle-o"></i>
                        </span>
                            <input type="text" value="" onfocus="remove_formError(this.id,'true')"
                                   id="new-ord-prod-dispatch" name="new-ord-prod-dispatch" class="form-control"
                                   autocomplete="off">
                        </div>

                    </div>
                    <div class="col-md-2  col-xs-12 col-sm-12 save-new-ord-prd">
                        <button onclick="AddNewOrdProd(); return false;" class="btn blue">Save</button>
                    </div>
                </div>
                <div class="form-group add-ord-prod-err">
                    <p class="error"></p>
                </div>
            </div>
            <?php
        } else {
            echo 'ERROR||||';
        }
    } else {
        echo "ERROR||||";
    }
} elseif ($mode == '__SAVE_NEW_ORDER_PROD__') {
    $data = array();
    $ordid = $_POST['ordid'];
    $ordered = $_POST['ordered'];
    $dispatched = $_POST['dispatched'];
    $avail = $_POST['avail'];
    $prodid = $_POST['prodid'];
    $ordDetArr = $kOrder->loadOrder($ordid);
    if (!empty ($ordDetArr)) {
        $customerId = $ordDetArr[0]['customerid'];
        $pricesArr = $kProduct->getProdPriceByProdID($prodid, $customerId);
        if (!empty ($pricesArr)) {
            $priceForThisProd = ($ordered * $pricesArr[0]['price']);

            $data['newordProd']['ordid'] = $ordid;
            $data['newordProd']['prId'] = $prodid;
            $data['newordProd']['prPrice'] = $pricesArr[0]['price'];
            $data['newordProd']['prColor'] = '1';
            $data['newordProd']['prQuantity'] = $ordered;

            if ($kOrder->addOrderDetails($data['newordProd'])) {
                if ($kOrder->ImportOrderPriceUpdate($ordid, $priceForThisProd)) {
                    if ($dispatched > 0) {
                        $kOrder->updateOrder($prodid, $dispatched, $ordid);
                    }
                    echo "SUCCESS||||";
                    ?>
                    <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Add New Product to Order Confirmation</h4>
                                </div>
                                <div class="modal-body">
                                    <p class="alert alert-success"><i class="fa fa-check"></i> A new product
                                        successfully added to this order.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button"
                                            onclick="editorderadmin('<?php echo $ordid; ?>'); return false;"
                                            class="btn dark btn-outline">Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                } else {
                    echo 'ERROR||||';
                }
            } else {
                echo 'ERROR||||';
            }
        } else {
            echo 'ERROR||||';
        }
    } else {
        echo 'ERROR||||';
    }
} elseif ($mode == '__DELETE_ORDER_PRODUCT_CONFIRMATION__') {

    $orderDetID = $_POST['orderDetId'];
    $orderID = $_POST['orderid'];
    echo "SUCCESS||||";
    $orderArr = $kOrder->loadOrder();
    include(__APP_PATH_VIEW_FILES__ . "/viewOrderList.php");
    ?>
    <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" onclick="editorderadmin('<?php echo $orderID; ?>');"
                            data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Delete Order Product Confirmation</h4>
                </div>
                <div class="modal-body">
                    <p class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> Are you sure you want to
                        delete the selected Product from this order?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline"
                            onclick="editorderadmin('<?php echo $orderID; ?>');" data-dismiss="modal">Close
                    </button>
                    <button type="button"
                            onclick="deleteOrderProd('<?php echo $orderDetID; ?>','<?php echo $orderID; ?>'); return false;"
                            class="btn blue"><i class="fa fa-times"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php
} elseif ($mode == '__AJAX_SEARCH_PRODUCT__') {
    $search_text = strip_tags(substr($_POST['searchit'], 0, 100));
    $orderid = $_POST['order_id'];
    if ($search_text) {
        $string = $kProduct->ajax_search_products($search_text, $orderid);

        if (empty($string)) {
            $string = "No products found!";
        }

        echo $string;
    }
}
?>