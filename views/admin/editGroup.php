<?php
if(empty($_POST['editGroupAry']))
    { 
        $_POST['editGroupAry']['usGroupName'] =$groupAry[0]['name'];
        $_POST['editGroupAry']['usDescription']=$groupAry[0]['description'];
        $_POST['editGroupAry']['id'] = $groupAry[0]['id'];
    }
?>
<div class="col-md-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-equalizer font-grey-sunglo"></i>
                <span class="caption-subject font-grey-sunglo bold uppercase">Edit Category</span>
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <form name="editGroupForm" id="editGroupForm" method="post" class="form-horizontal" >
                <div class="form-body">
                    <div class="form-group <?php if($kUser->arErrorMessages['usGroupName'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Category Name</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <input autocomplete="off" type="text" class="form-control" name="editGroupAry[usGroupName]" id='usGroupName' onfocus="remove_formError(this.id,'true')" placeholder="Category Name" value='<?php echo $_POST['editGroupAry']['usGroupName']; ?>'> 
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
                                <textarea class="form-control" name="editGroupAry[usDescription]" id='usDescription' onfocus="remove_formError(this.id,'true')" placeholder="Description"><?php echo $_POST['editGroupAry']['usDescription']; ?></textarea>
                            </div>
                            <?php if($kUser->arErrorMessages['usDescription'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usDescription']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    
                </div>
                <input autocomplete="off" type="hidden" class="form-control" name="editGroupAry[id]" id='id' onfocus="remove_formError(this.id,'true')" value='<?php echo $_POST['editGroupAry']['id']; ?>'> 
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-4">
                            <button class="btn blue" onclick="editGroup(); return false;">Save</button>
                            <a class="btn default" href="<?php echo __VIEW_GROUPS_LIST_URL__; ?>">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
            <!-- END FORM-->
        </div>
    </div>
</div>

