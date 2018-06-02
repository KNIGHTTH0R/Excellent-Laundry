<?php

include_once(__APP_PATH_LAYOUT__."/customer_header.php");
if($_SESSION['successFrogetUser'])
{
    $_SESSION['successFrogetUser']=false;
    unset($_SESSION['successFrogetUser']);
    ?>
     <h4 class="forgetUserMsg">Forgot Password email has been successfully sent to you. </h4>
<?php
}
if(($kUser->arErrorMessages['usEmail'] !='') || ($kUser->arErrorMessages['usPassword'] !='')){ ?>
     <style>
         .navbar-brand a img{max-width: 60%}
         .help-block{font-size: 14px; color: red}
     </style>
<?php } ?>


    <div class="container-full">
        <form class="login-form" id="loginUser" name="loginUser" method="post" autocomplete="off">
	<div class="login-box">
	    <h2>Login</h2>
            <div class="form-group <?php if($kUser->arErrorMessages['usEmail'] !=''){ ?> has-error <?php } ?>">
                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                    <div class="input-box">
                        <input class="form-control placeholder-no-fix" autocomplete="off" id="usEmail" name="loginAry[usEmail]"  type="email" placeholder="Email ID" onfocus="remove_formError('usEmail')" value="<?php if(!empty($_POST['loginAry']['usEmail'])){ echo $_POST['loginAry']['usEmail']; } ?>" onkeypress="ajaxKeyUpLogin(this,event);" />
                    </div>
                    <?php if($kUser->arErrorMessages['usEmail'] !=''){ ?>
                <div class="input-box">
                    <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usEmail']; ?></span>
                    </div>
                    <?php } ?>
                </div>
            <div class="form-group <?php if($kUser->arErrorMessages['usPassword'] !=''){ ?> has-error <?php } ?>">
                   
                    <div class="input-box">
                        <input class="form-control placeholder-no-fix" autocomplete="off" id="usPassword" name="loginAry[usPassword]" type="password"  placeholder="Password" onfocus="remove_formError('usPassword')" onkeypress="ajaxKeyUpLogin(this,event);" /> 
                    </div>
                    <?php if($kUser->arErrorMessages['usPassword'] !=''){ ?>
                <div class="input-box">
                    <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usPassword']; ?></span>
                    </div>
                    <?php } ?>
                </div>
	        <div class="login-btn-row clearfix">
                    <div class="login-btn"><button class="btn" onclick="userLogin();"> Login </button></div>
		    <div class="checkbox-container"><input type="checkbox" name="remember" value="1">Remember me</div>
		</div>
                <div class="user-forget">
                  <p> <a href="javascript:void(0)" onclick="userForgetForm();">click</a> here to reset your password.</p>
                </div> 
	    </div>
            
            </form>
    </div>
<?php

include_once(__APP_PATH_LAYOUT__."/customer_footer.php");

?>	