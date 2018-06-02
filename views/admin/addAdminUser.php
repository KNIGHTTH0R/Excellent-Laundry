
<div class="col-md-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-equalizer font-grey-sunglo"></i>
                <span class="caption-subject font-grey-sunglo bold uppercase">Add New User</span>
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <form name="addNewAdminUserForm" id="addNewAdminUserForm" method="post" class="form-horizontal" >
                <div class="form-body">
                    <div class="form-group <?php if($kUser->arErrorMessages['usAdminName'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Name</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <input autocomplete="off" type="text" class="form-control" name="addUserAry[usAdminName]" id='usAdminName' onfocus="remove_formError(this.id,'true')" placeholder="Name" value='<?php echo $_POST['addUserAry']['usAdminName']; ?>'> 
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
                                <input type="email" class="form-control" name="addUserAry[usAdminEmail]" id='usAdminEmail' onfocus="remove_formError(this.id,'true')" placeholder="Email Address" value='<?php echo $_POST['addUserAry']['usAdminEmail']; ?>'>
                            </div>
                            <?php if($kUser->arErrorMessages['usAdminEmail'] !=''){ ?> 
                                <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usAdminEmail']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group <?php if($kUser->arErrorMessages['usAdminPassword'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Password</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <input autocomplete="off" type="password" class="form-control" name="addUserAry[usAdminPassword]" id='usAdminPassword' onfocus="remove_formError(this.id,'true')" placeholder="Password" value='<?php echo $_POST['addUserAry']['usAdminPassword']; ?>'> 
                            </div>
                            <?php if($kUser->arErrorMessages['usAdminPassword'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usAdminPassword']; ?></span>
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
                                <select class="form-control custom-select" name="addUserAry[usAdminRole]" id="usAdminRole" onfocus="remove_formError(this.id,'true')">
                                        <option value="4">Normal User</option>
                                        <option value="2">Super User</option>
                                        <option value="1">Admin</option>
                                </select>
                            </div>
                            <?php if($kUser->arErrorMessages['usAdminRole'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usAdminRole']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-4">
                            <button class="btn blue" onclick="addNewAdminUser(); return false;">Add</button>
                            <a class="btn default" href="<?php echo __VIEW_USER__; ?>">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
            <!-- END FORM-->
        </div>
    </div>
</div>
