<?php
/**
    * This file is the header file for the all pages except login page and it contains links of all the css and javascript used.
    * 
    * header.php 
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
        
        <link href="<?php echo __BASE_LAYOUT_CSS__; ?>/custom_style.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo __BASE_LAYOUT_CSS__;?>/typeahead.css" rel="stylesheet">
        
        <link href="<?php echo __BASE_ASSETS_URL__; ?>/pages/css/search.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo __BASE_ASSETS_URL__; ?>/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo __BASE_ASSETS_URL__; ?>/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo __BASE_ASSETS_URL__; ?>/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo __BASE_ASSETS_URL__; ?>/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="<?php echo __BASE_ASSETS_URL__; ?>/global/css/components-md.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?php echo __BASE_ASSETS_URL__; ?>/global/css/plugins-md.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="<?php echo __BASE_ASSETS_URL__; ?>/layouts/layout4/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo __BASE_ASSETS_URL__; ?>/layouts/layout4/css/themes/light.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="<?php echo __BASE_ASSETS_URL__; ?>/layouts/layout4/css/custom.min.css" rel="stylesheet" type="text/css" />
        <link type="text/css" rel="stylesheet" href="<?php echo __BASE_ASSETS_URL__; ?>/pages/css/error.min.css">
<!--		<link href='<?php echo __BASE_ASSETS_URL__; ?>/customselect/src/jquery-customselect.css' rel='stylesheet' />-->
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="favicon.ico" /> 
        <script src="<?php echo __BASE_ASSETS_URL__; ?>/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="<?php echo __BASE_LAYOUT_JS__;?>/functions.js"></script>
         <script src="<?php echo __BASE_LAYOUT_JS__;?>/typeahead.js"></script>
        <link rel="stylesheet" href="<?php echo __BASE_ASSETS_URL__; ?>/datepick/css/pickmeup.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo __BASE_LAYOUT_CSS__; ?>/uploadfilemulti.css" type="text/css" />
        <script type="text/javascript" src="<?php echo __BASE_ASSETS_URL__; ?>/datepick/js/jquery.pickmeup.js"></script>
        <script type="text/javascript">
            var __SITE_JS_ADMIN_PATH__='<?php echo __URL_BASE_ADMIN__;?>';
        </script>
        
    </head>
    <!-- END HEAD -->

    <body class="page-container-bg-solid page-header-fixed page-sidebar-closed-hide-logo page-md">
        <div id="loader" style="display:none">
            <div id="popup-bg" style="z-index: 9999998;"></div>
            <div id="popup-container" style="z-index: 9999999;">
                <div class="popup-doc clearfix">
                    <div class="popup_text">
                        <i class="fa fa-spinner fa-spin fa-5x fa-fw margin-bottom"></i>
                        <br>
                        <br>
                        <br>
                         <span class="help-block"><h4>Loading</h4></span>
                    </div>
                </div>
            </div>
        </div>