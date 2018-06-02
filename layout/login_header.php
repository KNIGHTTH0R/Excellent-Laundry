<?php
/**
    * This file is the header file for login page and it contains links of all the css and javascript used on that page.
    * 
    * login_header.php 
    * 
    * @copyright Copyright (C) 2016 Whiz-Solutions
    * @author Whiz Solutions
    * @package Demo Project
*/
?>

<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8" />
        <title><?php echo $pagetitle; ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="<?php echo __BASE_ASSETS_URL__; ?>/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo __BASE_ASSETS_URL__; ?>/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo __BASE_ASSETS_URL__; ?>/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo __BASE_ASSETS_URL__; ?>/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo __BASE_ASSETS_URL__; ?>/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="<?php echo __BASE_ASSETS_URL__; ?>/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo __BASE_ASSETS_URL__; ?>/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="<?php echo __BASE_ASSETS_URL__; ?>/global/css/components-md.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?php echo __BASE_ASSETS_URL__; ?>/global/css/plugins-md.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="<?php echo __BASE_ASSETS_URL__; ?>/pages/css/login-4.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="favicon.ico" /> 
        
        <script src="<?php echo __BASE_LAYOUT_JS__;?>/functions.js"></script>
        
       <script type="text/javascript">
            var __SITE_JS_ADMIN_PATH__='<?php echo __URL_BASE_ADMIN__;?>';
        </script>
        <style>
            .logo-content{
    background: rgba(255, 255, 255, 0.6) url("../img/bg-white-lock.png") repeat scroll 0 0;
    border-radius: 7px;
    margin: 0 auto;
    padding: 20px 30px 15px;
    width: 360px;
}
.wrong-cred{background-color: rgba(255, 255, 255, 0.9);
    border-radius: 3px;
    padding: 5px 10px;
    text-align: center;
    border: 1px solid #e73d4a}
@media (max-width:480px) {
	.logo-content{width:275px}
        .logo-content img{width:220px}
        .login .content .form-actions, .login .content h3{text-align:center}
        
}
        </style>
    </head>
    <!-- END HEAD -->

    <body class=" login">
        