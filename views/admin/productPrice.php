
<?php 
$grouparr = $kCommon->loadGroups();
$prodArr = $kProduct->loadProducts()
?>
<div class="col-md-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-equalizer font-grey-sunglo"></i>
                <span class="caption-subject font-grey-sunglo bold uppercase">Product Price Management</span>
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <form name="ProductPriceForm" id="ProductPriceForm" method="post" class="form-horizontal">
                <div class="form-body">
                    <div id="Productselect" class="form-group <?php if($kProduct->arErrorMessages['prProduct'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Product</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <select class="form-control custom-select" name="addProdPriceArr[prProduct]" onchange="getCustomers();" id="prProduct" onfocus="remove_formError(this.id,'true')">
                                        <option value="">Select Product</option>
                                        <?php 
                                        if(!empty($prodArr))
                                        {
                                                foreach($prodArr as $product)
                                                {
                                                        $selected = ($product['id'] == $_POST['addProdPriceArr']['prProduct'] ? 'selected="selected"' : '');	
                                                        echo '<option value="'.$product['id'].'" '.$selected.'>'.$product['name'].'</option>';
                                                }
                                        }
                                        ?>
                                </select>
                            </div>
                            <?php if($kProduct->arErrorMessages['prProduct'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kProduct->arErrorMessages['prProduct']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                 </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-4">
                            <button class="btn blue" onclick="addProductPrice(); return false;">Save</button>
                            <a class="btn default" href="<?php echo __VIEW_PRODUCTS_LIST_URL__; ?>">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
            <!-- END FORM-->
        </div>
    </div>
</div>
