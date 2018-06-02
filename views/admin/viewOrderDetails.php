<div class="col-md-12">
    <div class="portlet light bordered about-text" id="user_info">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-check icon-info font-green"></i><span class="caption-subject font-green bold uppercase">View Order Details</span>
            </div>

        </div>
        
        <div class="portlet-body alert">
            <div class="row">
                        <div class="col-sm-2 text-info bold">
                            Order Number: <span>#<?php echo $orderDetailsArry['0']['id'] ;?></span>
                        </div>
                        
                        <div class="col-sm-4 text-info bold">
                            Business Name: <span><?php echo $orderDetailsArry['0']['business_name'] ;?></span>
                        </div>
                       <div class="col-sm-3 text-info bold">
                           Order Date: <span><?php echo date('d/m/Y',  strtotime($orderDetailsArry['0']['createdon'])); ?></span>
                        </div>
                        <div class="col-sm-3 text-info bold">
                            Order Status:  <?php 
                                     if($orderDetailsArry['0']['status']=='1')
                                     {?>
                                        <a class="label label-sm label-warning">
                                            Ordered 
                                        </a>
                                     <?php
                                     }
                                     if($orderDetailsArry['0']['status']=='2')
                                     {?>
                                        <a class="label label-sm label-info">
                                            Pending 
                                        </a>
                                     <?php
                                     }
                                     if($orderDetailsArry['0']['status']=='3')
                                     {?>
                                        <a  class="label label-sm label-info">
                                            Dispatched 
                                        </a>
                                     <?php
                                     }
                                     if($orderDetailsArry['0']['status']=='4')
                                     {?>
<!--                                        <a  class="label label-sm label-success">
                                            Complete 
                                        </a>-->
                                     <?php
                                     }
                                     if($orderDetailsArry['0']['status']=='5')
                                     {?>
                                        <a  class="label label-sm label-danger">
                                            Canceled 
                                        </a>
                                     <?php
                                     }
                                     
                                     ?>
                        </div>
                        
                        
                
            </div>
            
        </div>
    </div>
        <div class="portlet box blue">
        <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-user"></i>Order Products Details </div>
            <div class="actions">
                <div data-toggle="buttons" class="btn-group btn-group-devided">

                    <button class="btn grey btn-sm active" onclick="redirect_url('<?php echo __VIEW_ORDER_LIST_URL__;?>');">
                        Back
                    </button>
                </div>
            </div>
            </div>
            <div class="portlet-body" id="prod-description">
                
            <?php
            
            foreach ($orderArr as $orderData)
            {
                ?>
                
            <div class="row static-info">
                    <div class="col-md-3 text-info detsec bold">Name: <span><?php echo $orderData['name'];?></span></div>

                     <div class="col-md-3 text-info detsec bold">Ordered Qty: <span><?php echo $orderData['quantity'];?></span></div>

                      <div class="col-md-3 text-info detsec bold">Dispatched Qty: <span><?php echo ($orderDetailsArry['0']['status']=='3'?$orderData['dispatched']:'0');?></div>

<!--                     <div class="col-md-2 text-info detsec bold">Size: <span><?php echo $orderData['size'];?></span></div>-->

                    <div class="col-md-3 text-info detsec bold">Color: <span><?php 
                                    $colorArr = explode(',', $orderData['color']);
                                    if(!empty ($colorArr)){
                                        foreach ($colorArr as $key => $value) {
                                            $loadColorArr = $kCommon->loadColors($value);
                                            if(!empty ($loadColorArr)){
                                                echo '<span class="product_colors" style="background-color:'.$loadColorArr['0']['code'].';">'.$loadColorArr['0']['name'].'</span>';
                                            }
                                        }
                                    }
                                       
                                    ?></span></div>
                    </div>
                
                
           
            <?php
            
            }
            
            
            ?>
                    </div>
                    
               </div>
            
            
            
             </div>
        </div>
  </div>
  </div>
  </div>

<?php

include_once(__APP_PATH_LAYOUT__."/footer.php");

?>	
