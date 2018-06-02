<div class="col-md-12">
    <div class="portlet box blue">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-user"></i>View Product Details
            </div>
            <?php
                $groupIdArr = $kProduct->loadProductGroup($productArr['0']['id']);
                $selectedCat = $kCommon->loadGroups($groupIdArr[0]['groupid'], 0, false);
                $childcategories = $kCommon->loadGroups($selectedCat[0]['parentid'], 0, false);
            $param1 = '';
            $param2 = '';
            $param3 = '';
            if($parentcatid > '0'){
                $param1 = $parentcatid.'/';
            }
            if($childcatid > '0'){
                $param2 = $childcatid.'/';
            }
            if($productId > '0'){
                $param3 = $productId.'/';
            }
            ?>
            <div class="actions">
                <div data-toggle="buttons" class="btn-group btn-group-devided">

                    <button class="btn grey btn-sm active" onclick="redirect_url('<?php echo __VIEW_PRODUCTS_LIST_URL__;?>');">
                        <!--<i class="fa fa-plus"></i>--> Back
                    </button>
                </div>
            </div>
        </div>
        <div class="portlet-body" id="prod-description">
            <div class="row static-info">
                <div class="col-md-6">
                    <div class="row static-info">
                        <div class="col-md-4 text-info">Product Name:</div>
                        <div class="col-md-8">
                            <?php echo $productArr['0']['description']; ?>
                        </div>
                    </div>
                    <div class="row static-info">
                        <div class="col-md-4 text-info">Product Code:</div>
                        <div class="col-md-8">

                            <?php echo $productArr['0']['name']; ?>

                        </div>
                    </div>
                    <div class="row static-info">
                        <div class="col-md-4 text-info">Category:</div>
                        <div class="col-md-8">
                            <?php

                            echo $childcategories[0]['name'] . ' <i class="fa fa-arrow-right"></i> ' . $selectedCat[0]['name'];
                            ?>
                        </div>
                    </div>
                    <div class="row static-info">
                        <div class="col-md-4 text-info">Colors:</div>
                        <div class="col-md-8"> <?php
                            $colorArr = explode(',', $productArr[0]['color']);
                            if (!empty ($colorArr)) {
                                foreach ($colorArr as $key => $value) {
                                    $loadColorArr = $kCommon->loadColors($value);
                                    if (!empty ($loadColorArr)) {
                                        echo '<span class="product_colors" style="background-color:' . $loadColorArr[0]['code'] . ';">' . $loadColorArr[0]['name'] . '</span>';
                                    }
                                }
                            }

                            ?></div>
                    </div>
                    <div class="row static-info">
                        <div class="col-md-4 text-info">Quantity:</div>
                        <div class="col-md-8">
                            <?php echo $productArr['0']['quantity']; ?>
                        </div>
                    </div>
                    <div class="row static-info">
                        <div class="col-md-4 text-info">Weight (Kg):</div>
                        <div class="col-md-8">
                            <?php echo $productArr['0']['weight']; ?>
                        </div>
                    </div>
                    <div class="row static-info">
                        <div class="col-md-4 text-info">Image:</div>
                        <div class="col-md-8">
                            <img class="product-thumb"
                                 src="../../../images/products/<?php echo($productArr[0]['image'] ? $productArr[0]['image'] : 'comming-soon.jpg'); ?>"/>
                        </div>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="row static-info">

                        <div class="col-md-12">
                            <?php
                            if((int)$_SESSION['usr']['role'] != 2){
                            ?>
                            <div class="row static-info group-head">
                                <div class="col-md-12 text-info"><h4>Associated Customer/Price</h4></div>
                            </div>
                            <?php

                            $customerArr = $kProduct->loadCustomers();
                            $productGroupArr = $kProduct->loadProductGroup1((int)($productId));
                            $groupArr = explode(',', $productGroupArr[0]['groupid']);
                            foreach ($groupArr as $key => $value) {
                                $customerByGroupArr = $kProduct->loadCustomersByGroupid($value);

                                foreach ($customerArr as $cust) {
                                    ?>
                                    <div class="row static-info group-head">
                                        <div class="col-md-8 assoc-cust"><?php echo $cust['business_name'];
                                            $cutomerPriceSmall = $kProduct->getProdPriceByProdID((int)($productId), $cust['id']);
                                            echo ': <span>$' . ($cutomerPriceSmall[0]['price']?$cutomerPriceSmall[0]['price']:'0.00') . '</span>';
                                            ?> </div>

                                    </div>
                                    <?php
                                }
                            }
                            }?>
                        </div>


                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
  

	
