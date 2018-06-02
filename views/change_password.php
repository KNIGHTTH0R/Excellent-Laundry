<div class="container-full">
    <ul class="nav nav-tabs">
        <li class="active" id="addOrderHistoryInfoTab">
            <a data-toggle="tab" class="btn" aria-expanded="false" onclick="expandOrderHistoryInfoDetai();">Order History</a>
        </li>
        <li id="changePasswordTab">
            <a data-toggle="tab" class="btn" onclick="expandChangePassword();">Change Password</a>
        </li>
    </ul>
        <?php
        if($_SESSION['success_password'])
        {
            $_SESSION['success_password']=false;
            unset($_SESSION['success_password']);
         ?>
        <h3>Your password is successfully Changed...</h3> 
        <?php
        
        }
        ?>
	   
	
        <form class="login-form" id="changePasswordForm" name="changePasswordForm" method="post" autocomplete="off">
	<div class="login-box">
	    <h2>Change Password</h2>
            <div class="form-group <?php if($kUser->arErrorMessages['usPassword'] !=''){ ?> has-error <?php } ?>">
                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                    <div class="input-box">
                        <input class="form-control placeholder-no-fix" autocomplete="off" id="usPassword" name="changePassword[usPassword]"  type="password" placeholder="Password" onfocus="remove_formError('usPassword')" value="" /> 
                    </div>
                    <?php if($kUser->arErrorMessages['usPassword'] !=''){ ?> 
                    <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usPassword']; ?></span>
                    <?php } ?>
                </div>
            <div class="form-group <?php if($kUser->arErrorMessages['rePassword'] !=''){ ?> has-error <?php } ?>">
                   
                    <div class="input-box">
                        <input class="form-control placeholder-no-fix" autocomplete="off" id="rePassword" name="changePassword[rePassword]" type="password"  placeholder="Re-enter Password" value='' onfocus="remove_formError('rePassword')" /> 
                    </div>
                    <?php if($kUser->arErrorMessages['rePassword'] !=''){ ?> 
                    <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['rePassword']; ?></span>
                    <?php } ?>
                </div>
	        <div class="login-btn-row clearfix">
                    <div class="login-btn"><button class="btn" onclick="updatePassword();return false;"> Update Password </button></div>
		</div>
	    </div>
            </form>
            
			
</div>
<div id="popup"></div>
