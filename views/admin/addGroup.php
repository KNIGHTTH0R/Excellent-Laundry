
<div class="col-md-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-equalizer font-grey-sunglo"></i>
                <span class="caption-subject font-grey-sunglo bold uppercase">Add New <?php echo ($_GET['groupid']?'Sub ':'');?>Category</span>
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <form name="addNewGroupForm" id="addNewGroupForm" method="post" class="form-horizontal" >
                <div class="form-body">
                    <div class="form-group <?php if($kUser->arErrorMessages['usGroupName'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label"><?php echo ($_GET['groupid']?'Sub ':'');?>Category Name</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <input autocomplete="off" type="text" class="form-control" name="addGroupAry[usGroupName]" id='usGroupName' onfocus="remove_formError(this.id,'true')" placeholder="<?php echo ($_GET['groupid']?'Sub ':'');?>Category Name" value='<?php echo $_POST['addGroupAry']['usGroupName']; ?>'>
                            </div>
                            <?php if($kUser->arErrorMessages['usGroupName'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usGroupName']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group <?php if($kUser->arErrorMessages['usDescription'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Description</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <textarea class="form-control" name="addGroupAry[usDescription]" id='usDescription' onfocus="remove_formError(this.id,'true')" placeholder="Description"><?php echo $_POST['addGroupAry']['usDescription']; ?></textarea>
                            </div>
                            
                            <input type="hidden" name="addGroupAry[usParentid]" id="usParentid" value="<?php echo ($_GET['groupid']?$_GET['groupid']:($_POST['addGroupAry']['usParentid']?$_POST['addGroupAry']['usParentid']:'0'));?>" />
                            <?php if($kUser->arErrorMessages['usDescription'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usDescription']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-4">
                            <button class="btn blue" onclick="addNewGroup(); return false;">Save</button>
                            <a class="btn default" href="<?php echo __VIEW_GROUPS_LIST_URL__; ?>">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
            <!-- END FORM-->
        </div>
    </div>
</div>

