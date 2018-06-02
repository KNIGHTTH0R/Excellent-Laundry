<?php
if (!isset($_COOKIE['__pass_user_eq_customer'])) {
    if ((isset ($_SESSION['usr']['INACTIVELOGOUT'])) && ($_SESSION['usr']['INACTIVELOGOUT'] <= date('Y-m-d h:i:s'))) {
        unset($_SESSION['usr']);
        header("Location:" . __CUSTOMER_LOGOUT_URL__);
    } else {
        $ActiveLoginTime = date('Y-m-d h:i:s');
        $inactiveLogoutTime = date('Y-m-d h:i:s', strtotime('+5 minutes', strtotime($ActiveLoginTime)));
        $_SESSION['usr']['INACTIVELOGOUT'] = $inactiveLogoutTime;
    }
    if ((isset ($_SESSION['usr']['CHECKOUTLOGOUT'])) && ($_SESSION['usr']['CHECKOUTLOGOUT'] <= date('Y-m-d h:i:s'))) {
        unset($_SESSION['usr']);
        header("Location:" . __CUSTOMER_LOGOUT_URL__);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, follow">
    <title>Excellent Laundry</title>
    <link href="<?php echo __BASE_ASSETS_URL__; ?>/images/gt_favicon.png" rel="shortcut icon"/>
    <link href="<?php echo __BASE_ASSETS_URL__; ?>/css/bootstrap.min.css " rel="stylesheet"/>
    <link href="<?php echo __BASE_ASSETS_URL__; ?>/css/font-awesome.min.css" rel="stylesheet"/>

    <!-- Custom styles for our template -->

    <link href="<?php echo __BASE_ASSETS_URL__; ?>/css/style.css" rel="stylesheet"/>
    <link href="<?php echo __BASE_ASSETS_URL__; ?>/css/responsive.css" rel="stylesheet"/>
    <script>
        var __SITE_JS_ADMIN_PATH__ = '<?php echo __URL_BASE_ADMIN__;?>';
    </script>
    <script src="<?php echo __BASE_LAYOUT_JS__; ?>/functions.js"></script>


    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="assets/js/html5shiv.js"></script>
    <script src="assets/js/respond.min.js"></script>
    <![endif]-->
</head>

<body class="subpage">
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Internet Connection Status</h4>
            </div>
            <div class="modal-body">
                <p>Some text in the modal.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<header>
    <div class="container-full dropdown">
        <?php
        require_once( __APP_PATH_LAYOUT__ . "/backlink.php" );
        ?>
        <a href="javascript:void(0);" class="menu-bar" data-toggle="dropdown"><i class="fa fa-bars"
                                                                                 aria-hidden="true"></i></a>
        <ul class="dropdown-menu">
            <li><a href="<?php echo __URL_BASE__; ?>/my_account/">Order History</a></li>
            <li><a href="<?php echo __URL_BASE__; ?>/my_account/?changepass=1">Change Password</a></li>
            <li><a href="<?php echo __CUSTOMER_CART_URL__; ?>">Order</a></li>
            <li><a href="<?php echo __URL_BASE__; ?>/logout/">Logout</a></li>
        </ul>

    </div>
    <div class="navbar-brand"><a href="<?php echo __URL_BASE__; ?>"><img
                src="<?php echo __BASE_ASSETS_URL__; ?>/images/logo.png" alt="The Law Offices of Janet Contero"></a>
    </div>
</header>
<script>
    CheckInternetConnection();
</script>