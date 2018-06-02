<div class="trolly">
    <?php
    $cartArr = $kOrder->loadCart(0, $_SESSION['usr']['customerid']);
    $ctr = 0;
    if (!empty ($cartArr)) {
        foreach ($cartArr as $cartitems) {
            $ctr++;
        }
    }
    if ($ctr > 0) {
        ?>
        <a class="btn cart addcart" href="<?php echo __CUSTOMER_CART_URL__; ?>"><i class="fa fa-opencart"
                                                                                   aria-hidden="true"></i><?php echo($ctr > 0 ? '<span>' . $ctr . '</span>' : ''); ?>
        </a>
    <?php } ?>
</div>