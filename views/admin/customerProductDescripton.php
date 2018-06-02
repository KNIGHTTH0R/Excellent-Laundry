<div class="product-detail">
	<p><img src="<?php echo __URL_BASE__ ;?>/images/products/<?php echo $productArr[0]['image']; ?>" alt=""></p>
	<p><?php echo $productArr['0']['description'];?>.</p>
	<h4>Price: $<?php echo $price;?></h4>
         <div class="row">
            <div class="col-xs-10">
                
                <div class="product-color">
                <?php 
                    $allColorsArr = explode(',', $productArr[0]['color']);
                    if(!empty ($allColorsArr)){
                    foreach ($allColorsArr as $key => $value) {
                        $colorArr = $kCommon->loadColors($value);
                        if(!empty ($colorArr)){
                        ?>
                        <a href="javascript:void(0)" onclick="addColorCart('<?php echo $colorArr[0]['id'];?>'); return false;"><span id="col<?php echo $colorArr[0]['id'];?>" style="background-color: <?php echo $colorArr[0]['code'];?>"></span></a>
                        <?php }
                        }
                    }
                ?>
                </div>
            </div>
         </div>
        <form name="addCartForm" id="addCartForm" method="post">
            <div class="row">
	    <div class="col-xs-6">
		<label>Size</label>
		    <div class="input-box">
                        <select name="addCart[size]" id="size" onchange="getPrice();">
                            
                            <option value="<?php echo __PRODUCT_SIZE_SMALL__; ?>" <?php if($_POST['addCart']['size']==__PRODUCT_SIZE_SMALL__) { ?> selected="selected" <?php } ?>>Small</option>
                            <option value="<?php echo __PRODUCT_SIZE_MEDIUM__; ?>" <?php if($_POST['addCart']['size']==__PRODUCT_SIZE_MEDIUM__) { ?> selected="selected" <?php } ?>>Medium</option>
                            <option value="<?php echo __PRODUCT_SIZE_LARGE__; ?>" <?php if($_POST['addCart']['size']==__PRODUCT_SIZE_LARGE__) { ?> selected="selected" <?php } ?>>Large</option>
			</select>
                        
                    </div>
	    </div>
	</div>
           
         <div class="row">
	    <div class="col-xs-6">
		<label>Quantity</label>
		    <div class="input-box"><input type="text" name="addCart[quantity]" id="quantity" placeholder="1" value="<?php echo $_POST['addCart']['quantity'];?>" onkeyup="updateQuantity()"></div>
                    <div class="input-box error"><?php echo $error;?></div>
	    </div>
            <?php 
            if(!empty ($error)){ ?>
             <style>
                 .addcart{
/*                     pointer-events:none;*/
                 }
             </style>
            <?php }else{?>
                <style>
                 .addcart{
/*                     pointer-events:inherit;*/
                 }
             </style>
            <?php } ?>
             <style>
                 #col<?php echo $colorId?>{border:#000 1px solid}
             </style>
            
	    <div class="col-xs-6">
		<label>&nbsp;</label>
                 <p><button class="btn cart addcart" onclick="addToCart(); return false;">Add to Cart <i class="fa fa-opencart" aria-hidden="true"></i></button></p>
		
	    </div>
	</div>
            
            <input type="hidden" id ="productId" name="addCart[productId]" value="<?php echo $productId;?>">
            <input type="hidden" id ="price" name="addCart[price]" value="<?php echo $price;?>">
            <input type="hidden" id ="customerId" name="addCart[customerId]" value="<?php echo $customerId;?>">
            <input type="hidden" id ="colorId" name="addCart[colorId]" value="<?php echo $colorId;?>">
            
        </form>
	
	
    </div>
    
   

