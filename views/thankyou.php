<h4>Thank you for placing the order.</h4>
<a href="<?php echo __CUSTOMERS_CATEGORY_LIST_URL__;?>" class="btn">Continue Ordering</a>
<p style="margin-bottom: 0px">Want to order more? Click on <span class="order-more">Continue Ordering</span> </p>
<p>or, <span class="order-more">Press < </span> button on the top left side to return to Application Home Page.</p>
<p class="order-more">Please note, all orders placed after the advised time of 10AM will be delivered on your next delivery day.</p>
<p class="order-more">Any changes or additions to orders need to be placed 48 hours prior to delivery day.</p>
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
