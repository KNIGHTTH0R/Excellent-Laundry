
<div class="col-md-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-equalizer font-grey-sunglo"></i>
                <span class="caption-subject font-grey-sunglo bold uppercase">Add New Driver</span>
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
             <form name="addNewDriverForm" id="addNewDriverForm" method="post" class="form-horizontal" >
                <div class="form-body">
                    <div class="form-group <?php if($kUser->arErrorMessages['usDriverCode'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Driver Code</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <input autocomplete="off" type="text" class="form-control" name="addDriverAry[usDriverCode]" id='usDriverCode' onfocus="remove_formError(this.id,'true')" placeholder="Driver Code" value='<?php echo $_POST['addDriverAry']['usDriverCode']; ?>'> 
                            </div>
                            <?php if($kUser->arErrorMessages['usDriverCode'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usDriverCode']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group <?php if($kUser->arErrorMessages['usDriverDetils'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Driver Details</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <textarea class="form-control" name="addDriverAry[usDriverDetils]" id='usDriverDetils' onfocus="remove_formError(this.id,'true')" placeholder="Driver Details"><?php echo $_POST['addDriverAry']['usDriverDetils']; ?></textarea>
                            </div>
                            <?php if($kUser->arErrorMessages['usDriverDetils'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usDriverDetils']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-4">
                            <button class="btn blue" onclick="addNewDriver(); return false;">Save</button>
                            <a class="btn default" href="<?php echo __MANAGE_DRIVER_URL__;?>">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
           
            <!-- END FORM-->
        </div>
    </div>
</div>

