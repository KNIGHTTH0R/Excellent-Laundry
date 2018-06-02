
<div class="col-md-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-equalizer font-grey-sunglo"></i>
                <span class="caption-subject font-grey-sunglo bold uppercase">Change Password</span>
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <form name="changePasswordForm" id="changePasswordForm" method="post" class="form-horizontal" >
                <div class="form-body">
                    <div class="form-group <?php if($kUser->arErrorMessages['usPassword'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Current password</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <input autocomplete="off" type="password" class="form-control" name="changePasswordAry[usPassword]" id='usPassword' onfocus="remove_formError(this.id,'true')"  value=''> 
                            </div>
                            <?php if($kUser->arErrorMessages['usPassword'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usPassword']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group <?php if($kUser->arErrorMessages['newPassword'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">New Password</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <input type="password" class="form-control" name="changePasswordAry[newPassword]" id='newPassword' onfocus="remove_formError(this.id,'true')" placeholder="" value=''>
                            </div>
                            <?php if($kUser->arErrorMessages['newPassword'] !=''){ ?> 
                                <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['newPassword']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group <?php if($kUser->arErrorMessages['rePassword'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Confirm Password</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <input autocomplete="off" type="password" class="form-control" name="changePasswordAry[rePassword]" id='usAdminPassword' onfocus="remove_formError(this.id,'true')" placeholder="" value=''> 
                            </div>
                            <?php if($kUser->arErrorMessages['rePassword'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['rePassword']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-4">
                            <button class="btn blue" onclick="changePassword(); return false;">Submit</button>
                            <a class="btn default" href="<?php echo __USER_DASHBOARD_URL__; ?>">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
            <!-- END FORM-->
        </div>
    </div>
</div>
