<?php

include_once(__APP_PATH_LAYOUT__."/login_header.php");
?>
<!-- BEGIN LOGO -->
        <div class="logo">
            <div class="logo-content">
            <a href="<?php echo __BASE_URL__; ?>">
                <img src="<?php echo __BASE_URL_IMAGES__; ?>/EL-final-logo300.png" title="Laundry" alt="GSI Freight"/> </a>
                </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN LOGIN -->
        <div class="content">
            <!-- BEGIN LOGIN FORM -->
            <form class="login-form" id="loginUser" name="loginUser" method="post" autocomplete="off">
                <h3 class="form-title">Login to your account</h3>
                <div class="alert alert-success hide" id="forgot_success">
                    <button class="close" data-close="alert"></button>
                    <span> Forgot Password email has been successfully sent to you. </span>
                </div>
                <div class="form-group <?php if($kUser->arErrorMessages['usEmail'] !=''){ ?> has-error <?php } ?>">
                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                    <label class="control-label visible-ie8 visible-ie9">Email address</label>
                    <div class="input-icon">
                        <i class="fa fa-user"></i>
                        <input class="form-control placeholder-no-fix" autocomplete="off" id="usEmail" name="loginAry[usEmail]"  type="text" placeholder="Email address" onfocus="remove_formError('usEmail')" value="<?php if(!empty($_POST['loginAry']['usEmail'])){ echo $_POST['loginAry']['usEmail']; } ?>" onkeypress="ajaxKeyUpLogin(this,event);" /> 
                    </div>
                    <?php if($kUser->arErrorMessages['usEmail'] !=''){ ?> 
                    <span class="help-block wrong-cred"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usEmail']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group <?php if($kUser->arErrorMessages['usPassword'] !=''){ ?> has-error <?php } ?>">
                    <label class="control-label visible-ie8 visible-ie9">Password</label>
                    <div class="input-icon">
                        <i class="fa fa-lock"></i>
                        <input class="form-control placeholder-no-fix" autocomplete="off" id="usPassword" name="loginAry[usPassword]" type="password"  placeholder="Password" onfocus="remove_formError('usPassword')" onkeypress="ajaxKeyUpLogin(this,event);" /> 
                    </div>
                    <?php if($kUser->arErrorMessages['usPassword'] !=''){ ?> 
                    <span class="help-block wrong-cred"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usPassword']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-actions">
                    <label class="checkbox">
                        <input type="checkbox" name="remember" value="1" /> Remember me </label>
                    <button class="btn blue pull-right" onclick="userLogin();"> Login </button>
                </div>
                <div class="forget-password">
                    <h4>Forgot your password ?</h4>
                    <p> No worries, click
                        <a href="javascript:void(0);" id="forget-password" onclick="open_forgot_password_form();"> here </a> to reset your password. </p>
                </div>
            </form>
            <!-- END LOGIN FORM -->
            <!-- BEGIN FORGOT PASSWORD FORM -->
            <form class="forget-form" id="forgotPassword" name="forgotPassword" method="post" autocomplete="off">
                <h3>Forget Password ?</h3>
                <p> Enter your e-mail address below to reset your password. </p>
                <div class="form-group">
                    <div class="input-icon">
                        <i class="fa fa-envelope"></i>
                        <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="forgotPasswordAry[szForgotEmail]" id="szForgotEmail" onfocus="remove_formError('szForgotEmail')" onkeypress="ajaxKeyUpLogin(this,event);"/> 
                    </div>
                    <span class="help-block hide"><i class="icon icon-remove-sign"></i> <span id="forgot_email_error"></span></span>
                </div>
                <div class="form-actions">
                    <button type="button" id="back-btn" class="btn red" onclick="open_login_form();">Back </button>
                    <button class="btn green pull-right" onclick="forgot_password(); return false;"> Submit </button>
                </div>
            </form>
            <!-- END FORGOT PASSWORD FORM -->
            
        </div>

<?php

include_once(__APP_PATH_LAYOUT__."/login_footer.php");

?>	