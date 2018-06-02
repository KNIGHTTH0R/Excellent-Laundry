<div class="col-md-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-equalizer font-grey-sunglo"></i>
                <span class="caption-subject font-grey-sunglo bold uppercase">Add New Order</span>
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <form name="addNewProductForm" id="addNewProductForm" method="post" class="form-horizontal" enctype="multipart/form-data">
                <div class="form-body">
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-4">
                            <button class="btn blue" onclick="addNewProduct(); return false;">Save</button>
                            <a class="btn default" href="<?php echo __VIEW_PRODUCTS_LIST_URL__; ?>">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
            <!-- END FORM-->
        </div>
    </div>
</div>
