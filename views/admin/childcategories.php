<div class="btn-container">
    <?php 
    if(!empty ($ChildCatArr)){
        foreach ($ChildCatArr as $childcat){
    ?>
    <a href="<?php echo __CUSTOMERS_PRODUCT_LIST_URL__.$childcat['id'].'/';?>" class="btn"><?php echo $childcat['name'];?></a>
    <?php 
        }
    } ?>
</div>
    
   

