<?php
$i = '0';
if (!empty ($customerProdArr)) {
    $arrayProductExist = array();
    foreach ($customerProdArr as $productDetails) {
        ?>
        <div class="product-list clearfix">
            <form name="addCartForm" id="addCartForm" method="post">
                <div class="product-img">
                    <p><img src="../../images/products/<?php echo $productDetails['image']; ?>" alt=""></p>
                    <div class="product-color">
                        <?php
                        $allColorsArr = explode(',', $productDetails['color']);
                        if (!empty ($allColorsArr)) {
                            foreach ($allColorsArr as $key => $value) {
                                $colorArr = $kCommon->loadColors($value);
                                if (!empty ($colorArr)) {
                                    ?>
                                    <a href="javascript:void(0)"
                                       onclick="addColorCart('<?php echo $colorArr[0]['id']; ?>','<?php echo $productDetails['id']; ?>'); return false;"><span
                                            id="col<?php echo $productDetails['id'] . '-' . $colorArr[0]['id']; ?>"
                                            class="prodcolor<?php echo $productDetails['id']; ?>"
                                            style="background-color: <?php echo $colorArr[0]['code']; ?>"></span></a>
                                <?php }
                            }
                        }
                        ?>

                    </div>
                </div>
                <div class="product-info">
                    <!--				<h3>--><?php //echo $productDetails['name'];
                    ?><!--</h3>-->
                    <p><?php echo $productDetails['description']; ?></p>
                    <h4 class="prod-price">Price: $
                        <?php
                        $cutomerPriceSmall = $kProduct->getProdPriceByProdID((int)($productDetails['id']), $_SESSION['usr']['customerid']);
                        echo $cutomerPriceSmall[0]['price'];
                        ?>
                    </h4>
                    <div class="input-box"><input type="text" name="addCart[quantity]"
                                                  id="quantity<?php echo $productDetails['id']; ?>"
                                                  placeholder="Quantity"
                                                  value="<?php echo $_POST['addCart']['quantity']; ?>"></div>
                    <p class="cartbtn">
                        <button class="btn cart addcart"
                                onclick="addToCart('<?php echo $productDetails['id']; ?>'); return false;"><i
                                class="fa fa-opencart" aria-hidden="true"></i></button>
                    </p>
                    <div class="clearfix"></div>
                    <p class="error error<?php echo $productDetails['id']; ?>">Enter numbers only.</p>

                </div>
                <input type="hidden" id="productId<?php echo $productDetails['id']; ?>" name="addCart[productId]"
                       value="<?php echo $productDetails['id']; ?>">
                <input type="hidden" id="price<?php echo $productDetails['id']; ?>" name="addCart[price]"
                       value="<?php echo $cutomerPriceSmall[0]['price']; ?>">
                <input type="hidden" id="customerId<?php echo $productDetails['id']; ?>" name="addCart[customerId]"
                       value="<?php echo $_SESSION['usr']['customerid']; ?>">
                <?php
                $allColorsArr = explode(',', $productDetails['color']);
                if (!empty ($allColorsArr)) {
                    $colorid = $allColorsArr[0];
                }
                ?>
                <input type="hidden" id="colorId<?php echo $productDetails['id']; ?>" name="addCart[colorId]"
                       value="<?php echo $colorid; ?>">
        </div>
        <style>
            #col<?php echo $productDetails['id'].'-'.$colorid?> {
                border: #000 1px solid
            }
        </style>

    <?php }


}

?>
<?php
if ($show_pagination) {
    show_pagination($iPageNumber, $iNumberOfPage);
}

?>
