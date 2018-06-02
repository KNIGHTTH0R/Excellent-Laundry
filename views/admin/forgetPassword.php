<?php

include_once(__APP_PATH_LAYOUT__."/customer_header.php");
?>

    <div class="container-full">
        <form class="login-form" id="forgetUser" name="forgetUser" method="post" autocomplete="off">
	<div class="login-box">
	    <h2>Forget Password</h2>
            <div class="form-group <?php if($kUser->arErrorMessages['szEmail'] !=''){ ?> has-error <?php } ?>">
                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                    <div class="input-box">
                        <input class="form-control placeholder-no-fix" autocomplete="off" id="szEmail" name="szEmail"  type="text" placeholder="Email ID" onfocus="remove_formError('szEmail')" value="<?php  echo $_POST['szEmail'];  ?>" /> 
                    </div>
                    <?php if($kUser->arErrorMessages['szEmail'] !=''){ ?> 
                    <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['szEmail']; ?></span>
                    <?php } ?>
                </div>
                <div class="login-btn-row clearfix">
                    <div class="login-btn"><a href="javascript:void(0)" onclick="userForget();" class="btn" > Submit </a></div>
                </div>
                <div class="user-forget">
                  <p> <a href="javascript:void(0)" onclick="UserLogin();">click</a> here return to Login Page.</p>
                </div>
                 
	    </div>
            
            </form>
    </div>
<?php

include_once(__APP_PATH_LAYOUT__."/customer_footer.php");

?>	