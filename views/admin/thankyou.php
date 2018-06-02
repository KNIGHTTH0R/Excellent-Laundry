<h3>Thank you for placing the order, we received it and will start processing it.</h3>
 <?php 
    $CheckoutTime = date('Y-m-d h:i:s');
    $CheckoutLogoutTime = date('Y-m-d h:i:s', strtotime('+5 minutes', strtotime($CheckoutTime)));
    if(!isset ($_SESSION['usr']['CHECKOUTLOGOUT'])){
        $_SESSION['usr']['CHECKOUTLOGOUT'] = $CheckoutLogoutTime;
    }
    if(!isset($_COOKIE['__pass_user_eq'])){ ?>
<script>
setTimeout(function(){
    window.location = '<?php echo __CUSTOMER_LOGOUT_URL__;?>'; 
}, 300000);
</script>
    <?php } ?>
