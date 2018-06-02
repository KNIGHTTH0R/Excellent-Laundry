<?php
/**
    * This file is the footer file of the site.
    * 
    * login_header.php 
    * 
    * @copyright Copyright (C) 2016 Whiz-Solutions
    * @author Whiz Solutions
    * @package Demo Project
*/
?>

    <!-- BEGIN COPYRIGHT -->
        <div class="copyright"> 2017 &copy; EXCELLENT LAUNDRY. </div>
        <!-- END COPYRIGHT -->
        <!--[if lt IE 9]>
<script src="<?php echo __BASE_ASSETS_URL__; ?>/global/plugins/respond.min.js"></script>
<script src="<?php echo __BASE_ASSETS_URL__; ?>/global/plugins/excanvas.min.js"></script> 
<![endif]-->
         <!-- BEGIN CORE PLUGINS -->
<!--		<script src='<?php echo __BASE_ASSETS_URL__; ?>/customselect/src/jquery-customselect.js'></script>-->
<!--        <script src="<?php echo __BASE_ASSETS_URL__; ?>/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>-->
        <script src="<?php echo __BASE_ASSETS_URL__; ?>/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?php echo __BASE_ASSETS_URL__; ?>/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="<?php echo __BASE_ASSETS_URL__; ?>/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="<?php echo __BASE_ASSETS_URL__; ?>/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="<?php echo __BASE_ASSETS_URL__; ?>/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="<?php echo __BASE_ASSETS_URL__; ?>/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
        <script src="<?php echo __BASE_ASSETS_URL__; ?>/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        

<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->



        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="<?php echo __BASE_ASSETS_URL__; ?>/global/scripts/app.min.js" type="text/javascript"></script>
<!--		<script type="text/javascript" src="<?php echo __BASE_ASSETS_URL__; ?>/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
		<script type="text/javascript" src="<?php echo __BASE_ASSETS_URL__; ?>/global/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>-->

        <!-- END THEME GLOBAL SCRIPTS -->
        
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
<!--		<script src="<?php echo __BASE_ASSETS_URL__; ?>/global/scripts/metronic.js" type="text/javascript"></script>-->
        <script src="<?php echo __BASE_ASSETS_URL__; ?>/layouts/layout4/scripts/layout.min.js" type="text/javascript"></script>
        <script src="<?php echo __BASE_ASSETS_URL__; ?>/layouts/layout4/scripts/demo.min.js" type="text/javascript"></script>
        <script src="<?php echo __BASE_ASSETS_URL__; ?>/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
<!--		<script src="<?php echo __BASE_ASSETS_URL__; ?>/pages/scripts/components-pickers.js"></script>-->
		<script>
			jQuery(document).ready(function() {       
			   // initiate layout and plugins
				//Metronic.init(); // init metronic core components
				//Demo.init(); // init demo features
				//ComponentsPickers.init();
                                
			});
                        
		</script>
        <!-- END THEME LAYOUT SCRIPTS -->
    </body>
</html>