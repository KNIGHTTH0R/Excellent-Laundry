
<?php 
$prodArr = $kProduct->loadProducts()
?>
<div class="col-md-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-equalizer font-grey-sunglo"></i>
                <span class="caption-subject font-grey-sunglo bold uppercase">Inventory Management</span>
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <form name="ProductinventoryForm" id="ProductinventoryForm" method="post" class="form-horizontal">
                <div class="form-body"  id="Productselect">
                    <div class="form-group <?php if($kProduct->arErrorMessages['priProduct'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Product</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <select class="form-control custom-select" name="addProdInventoryArr[priProduct]" onchange="getQuantity();" id="priProduct" onfocus="remove_formError(this.id,'true')">
                                        <option value="">Select Product</option>
                                        <?php 
                                        if(!empty($prodArr))
                                        {
                                                foreach($prodArr as $product)
                                                {
                                                        $selected = ($product['id'] == $_POST['addProdInventoryArr']['priProduct'] ? 'selected="selected"' : '');	
                                                        echo '<option value="'.$product['id'].'" '.$selected.'>'.$product['name'].'</option>';
                                                }
                                        }
                                        ?>
                                </select>
                            </div>
                            <?php if($kProduct->arErrorMessages['priProduct'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kProduct->arErrorMessages['priProduct']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                 </div>
                
            </form>
            <!-- END FORM-->
        </div>
    </div>
</div>
