<?php
if(empty($_POST['editUserAry']))
{ 
    $_POST['editUserAry']['usAdminName'] =$adminUserArr[0]['username'];
    $_POST['editUserAry']['usAdminEmail']=$adminUserArr[0]['emailid'];
    $_POST['editUserAry']['usAdminPassword'] =$adminUserArr[0]['password'];
    $_POST['editUserAry']['usAdminRole']=$adminUserArr[0]['role'];
    $_POST['editUserAry']['usAdminId']=$adminUserArr[0]['id'];
}
?>
<div class="col-md-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-equalizer font-grey-sunglo"></i>
                <span class="caption-subject font-grey-sunglo bold uppercase">Edit User</span>
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <form name="editAdminUserForm" id="editAdminUserForm" method="post" class="form-horizontal" >
                <div class="form-body">
                    <div class="form-group <?php if($kUser->arErrorMessages['usAdminName'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Name</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <input autocomplete="off" type="text" class="form-control" name="editUserAry[usAdminName]" id='usAdminName' onfocus="remove_formError(this.id,'true')" placeholder="Name" value='<?php echo $_POST['editUserAry']['usAdminName']; ?>'> 
                            </div>
                            <?php if($kUser->arErrorMessages['usAdminName'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usAdminName']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group <?php if($kUser->arErrorMessages['usAdminEmail'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Email ID</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <input type="email" class="form-control" name="editUserAry[usAdminEmail]" id='usAdminEmail' onfocus="remove_formError(this.id,'true')" placeholder="Email Address" value='<?php echo $_POST['editUserAry']['usAdminEmail']; ?>'>
                            </div>
                            <?php if($kUser->arErrorMessages['usAdminEmail'] !=''){ ?> 
                                <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usAdminEmail']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div id="mygroup" class="form-group <?php if($kUser->arErrorMessages['usAdminRole'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Role</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <select class="form-control custom-select" name="editUserAry[usAdminRole]" id="usAdminRole" onfocus="remove_formError(this.id,'true')">
                                        <option value="4" <?php if($_POST['editUserAry']['usAdminRole']=="4") echo 'selected="selected"'; ?> >Normal User</option>
                                        <option value="2" <?php if($_POST['editUserAry']['usAdminRole']=="2") echo 'selected="selected"'; ?>>Super User</option>
                                        <option value="1" <?php if($_POST['editUserAry']['usAdminRole']=="1") echo 'selected="selected"'; ?>>Admin</option>
                                </select>
                            </div>
                            <?php if($kUser->arErrorMessages['usAdminRole'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usAdminRole']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <input type="hidden" class="form-control" name="editUserAry[usAdminId]" id='usAdminId'  value='<?php echo $_POST['editUserAry']['usAdminId']; ?>'>
                    
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-4">
                            <button class="btn blue" onclick="editAdminUser(); return false;">Submit</button>
                            <a class="btn default" href="<?php echo __VIEW_USER__; ?>">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
            <!-- END FORM-->
        </div>
    </div>
</div>
