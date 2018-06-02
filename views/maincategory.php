<div class="btn-container">
    <?php 
    if(!empty ($MainCatArr)){
        foreach ($MainCatArr as $maincategory){
    ?>
    <a href="<?php echo __CUSTOMERS_SUB_CATEGORY_LIST_URL__.$maincategory['id'].'/';?>" class="btn"><?php echo $maincategory['name'];?></a>
    <?php 
        }
    } ?>
</div>
    
   

