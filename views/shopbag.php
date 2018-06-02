<!--<div class="error">Enter Numeric Value.</div>-->
<?php
if(!empty ($cartArr)){
?>
<form name="updatecart" id="updatecart" action="" method="post">
<table class="GridMain" cellspacing="0" cellpadding="0" id="CartItems">
        <tbody>
            
                <tr class="GridHeader">
<!--                        <th class="SrNo" scope="col"></th>-->
                        <th scope="col">Product</th>
                        <th class="QtyColumn" scope="col">Qty.</th>
                        <th class="PriceColumn" scope="col">Price ($)</th>
                        <th class="PriceColumn" scope="col">Total ($)</th>
                        <th scope="col">&nbsp;</th>
                </tr>
                <?php 
                $total = '';
                $counter = 0;
                    foreach ($cartArr as $cartitem){
                        $customerPriceBySize = $kProduct->getCustomerPriceBySize($_SESSION['usr']['customerid'],$cartitem['productid']);
                ?>
                <tr class="GridRow">
<!--                        <td class="SrNo"><img class="prod-img" src="../images/products/<?php echo $cartitem['image'];?>" alt="<?php echo $cartitem['name'];?>" /></td>-->
                        <td class="CartLink" data-title="Item Name"><?php echo $cartitem['description'];?></td>
                        <td class="QtyColumn" data-title="Qty.">
                            <input type="text" name="cartqty<?php echo $counter?>" id="Quantity<?php echo $counter?>" class="txtBox"  maxlength="4" value="<?php echo $cartitem['quantity'];?>">
                        </td>
                        <td class="PriceColumn" data-title="Price ($)"><?php echo $customerPriceBySize[0]['price'];?></td>
                        <td class="PriceColumn" data-title="Total ($)"><?php echo $cartitem['price'];?></td>
                        <td class="PriceColumn">
                            <a onclick="deleteFromCart('<?php echo $_SESSION['usr']['customerid'];?>','<?php echo $cartitem['id'];?>');" title="Delete Item" class="btn delete"><i class="fa fa-times"></i></a>
                            <input type="hidden" name="cartid<?php echo $counter?>" value="<?php echo $cartitem['id']; ?>" />
                            <input type="hidden" name="price<?php echo $counter?>" value="<?php echo $customerPriceBySize[0]['price'];?>" />
                        </td>
                        
                </tr>
                <?php 
                $counter++;
                $total = (double)$total + (double)$cartitem['price'];
                } ?>
<!--                <tr class="GridAlternateRow">
                        <td class="SrNo"><img src="assets/images/product-img2.jpg" alt="product_sm_size" /></td>
                        <td class="CartLink"><a href="product-details.html" title="Product Name">Product Name</a></td>
                        <td class="QtyColumn"><input type="text" name="Quantity1" id="Quantity1" class="txtBox" maxlength="3" value="1"></td>
                        <td class="PriceColumn">0.00</td>
                        <td class="PriceColumn">0.00</td>
                        <td class="PriceColumn"><a href="#" title="Delete Item" class="btn delete"><i class="fa fa-times"></i></a></td>
                </tr>-->
        </tbody>
</table>
    
<table cellspacing="0" cellpadding="0" class="GridMain" id="CartTotals">
        <tbody>

                <tr class="GridRow">
                        <td class="AlignRight"><b>Total EXL GST($)</b><br />
                            <span style="font-size: 13px">10% GST will be added in your invoice.</span> </td>
                        <td class="PriceColumn"><?php echo number_format((float)$total, 2, '.', '');?></td>
<!--                        <td class="PriceColumn">&nbsp;</td>-->
                </tr>
        </tbody>
</table>
    <input type="hidden" name="totalitem" id="totalitem" value="<?php echo $counter;?>" />
    </form>
<form name="placeorder" id="placeorder" action="" method="post" />
<?php 
        $grandtotal = '';
        $count = 0;
            foreach ($cartArr as $cartitem){
                $customerPriceBySize = $kProduct->getCustomerPriceBySize($_SESSION['usr']['customerid'],$cartitem['productid']); ?>
<input type="hidden" id="ordprodid<?php echo $count;?>" name="ordprodid<?php echo $count;?>" value="<?php echo $cartitem['productid'];?>" />
<input type="hidden" id="ordprice<?php echo $count;?>" name="ordprice<?php echo $count;?>" value="<?php echo $customerPriceBySize[0]['price'];?>" />
<input type="hidden" id="ordqty<?php echo $count;?>" name="ordqty<?php echo $count;?>" value="<?php echo $cartitem['quantity'];?>" />
<input type="hidden" id="ordcolor<?php echo $count;?>" name="ordcolor<?php echo $count;?>" value="<?php echo $cartitem['color'];?>" />
            <?php 
            $count++;
            $grandtotal = (double)$grandtotal + (double)$cartitem['price'];
            }
            
        ?>
<input type="hidden" name="customerid" value="<?php echo $_SESSION['usr']['customerid'];?>" />
<input type="hidden" name="priceOrd" value="<?php echo $grandtotal;?>" />
<input type="hidden" name="ordcount" value="<?php echo $count;?>" />
</form>
<?php } ?>

<div class="btn-container thank-message">
    <?php if(!empty ($cartArr)){?>
    <a onclick="updateFrontCart();" class="btn actions updatecart">Update Cart</a>
    <?php } ?>
    <a href="<?php echo __CUSTOMERS_CATEGORY_LIST_URL__;?>" class="btn">Continue Ordering</a>
    <p class="order-more">Want to order more? Click on Continue Ordering.</p>
    <?php if(!empty ($cartArr)){?>
    <a class="btn actions" id="orderchekout" onclick="placeOrder();">Checkout</a>
    <?php } ?>
</div>
<input type="hidden" id="checkvalcount" name="checkvalcount" value="0" />
    
   

