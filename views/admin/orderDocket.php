<?php
    $addressAry = explode('$', $orderArr[0]['address']);
    //$stateId=$kCommon->loadCity($orderArr[0]['city']);
    $stateId=$kCommon->loadState($orderArr[0]['state']);
   
?>
<div class="col-md-12">
        <div class="portlet box blue">
        <div class="portlet-title">
                <div class="caption" id="ord-docket">
                    <i class="fa fa-user"></i>Delivery Docket </div>
            <div class="actions">
                <div data-toggle="buttons" class="btn-group btn-group-devided">

                    <button class="btn grey btn-sm active" onclick="redirect_url('<?php echo __VIEW_ORDER_LIST_URL__;?>');">
                        Back
                    </button>
                </div>
                <button class="btn green uppercase bold" type="button" onclick="redirect_url('<?php echo __VIEW_ORDER_DOCKET_DOC_URL__.$orderId.'/'; ?>','1')"><i class="fa fa-print"></i>Print</button>
            </div>
            </div>
            <div id="label-print" class="portlet-body">
            <div class="portlet-body" id="prod-description">
                <div class="row static-info">
                    <div class="col-md-12">
                <div class="row static-info">
                    <div class="col-md-2 text-info"><b>Order Number:</b> <span><?php echo $orderArr['0']['id'] ;?></span></div>

            <div class="col-md-3 text-info"><b>Business Name:</b> <span><?php echo $orderArr['0']['business_name'] ;?></span></div>

                     <div class="col-md-3 text-info"><b>Order Date:</b> <span><?php echo date('d/m/Y',  strtotime($orderArr['0']['createdon'])); ?></span></div>

                      <div class="col-md-3 text-info"><b>Order Status:</b> <span>
                      <?php 
                                     if($orderArr['0']['status']=='1')
                                     {?>
                                        <a class="label label-sm label-warning">
                                            Unpaid 
                                        </a>
                                     <?php
                                     }
                                     if($orderArr['0']['status']=='2')
                                     {?>
                                        <a class="label label-sm label-info">
                                            Paid 
                                        </a>
                                     <?php
                                     }
                                     if($orderArr['0']['status']=='3')
                                     {?>
                                        <a  class="label label-sm label-info">
                                            Dispatched 
                                        </a>
                                     <?php
                                     }
                                     if($orderArr['0']['status']=='4')
                                     {?>
                                        <a  class="label label-sm label-success">
                                            Complete 
                                        </a>
                                     <?php
                                     }
                                     if($orderArr['0']['status']=='5')
                                     {?>
                                        <a  class="label label-sm label-danger">
                                            Canceled 
                                        </a>
                                     <?php
                                     }
                                     
                                     ?></span></div>

                 </div>
                </div>
                <div class="col-md-12">
                <div class="row static-info">
                    <div class="col-md-5 text-info notdark"><b>Street Address 1:</b> <span><?php echo $addressAry[0];?></span></div>

                    <div class="col-md-3 text-info"><b>Street Address 2:</b> <span><?php  echo $addressAry[1];?></span></div>

                    <div class="col-md-3 text-info"><b>Country:</b> <span><?php 
                      //$country=$kCommon->loadCountry($countryId[0]['country_id']);
                      //echo $country[0]['name'];
                    echo "Australia";
                      ?></span></div>

                 </div>
                </div>
                
                <div class="col-md-12">
                <div class="row static-info">
                    <div class="col-md-3 text-info notdark"><b>State:</b> <span><?php
                      echo $stateId[0]['name'];
                      ?></span></div>

<!--                    <div class="col-md-3 text-info"><b>City:</b> <span> <?php 
                      $city=$kCommon->loadCity($orderArr[0]['city']);
                      
                       echo $city[0]['name'];
                      ?></span></div>-->

                    <div class="col-md-3 text-info"><b>Post Code:</b> <span><?php echo $orderArr[0]['postcode'];?></span></div>

                 </div>
                </div>
                <div class="col-md-12">
                    <div class="row static-info">
                        <div class="col-md-12">
                        <h3 class="product-details">Product Details</h3>
                        </div>
                    </div>
                 </div>
                
               
                    
                </div>
                
                </div>
           
        
        
        <div class="portlet-body">
            <?php
            
                     
            if(!empty($orderArr))
            {
            ?>
                <div class="table-responsive">
                    <table id="sample_1" class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" role="grid" aria-describedby="sample_1_info">
                        <thead>
                            <tr>
<!--                                <th> # </th>-->
<!--                                <th> Image </th>-->
				<th> Item </th>
<!--                                <th> Price </th>-->
                                <th> Quantity Ordered </th>
                                <th> Quantity Dispatched</th>
<!--                                <th> Colors</th>-->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i=1;
                            foreach($orderProductArr as $orderProductData)
                            {
                            ?>
                                <tr>
<!--                                    <td>--><?php //echo $i++; ?><!--</td>-->
<!--                                    <td><img class="product-image" src="../../../images/products/<?php echo $orderProductData['image']; ?>" /></td>-->
                                    <td><?php echo $orderProductData['name'];?></td>
<!--                                    <td> $<?php echo $orderProductData['price'];?></td>-->
                                    <td><?php echo $orderProductData['quantity'];?></td>
                                    <td><?php echo $orderProductData['dispatched'];?></td>
                                    <!--<td><?php /*
                                    $colorArr = explode(',', $orderProductData['color']);
                                    if(!empty ($colorArr)){
                                        foreach ($colorArr as $key => $value) {
                                            $loadColorArr = $kCommon->loadColors($value);
                                            if(!empty ($loadColorArr)){
                                                echo '<span class="product_colors" style="background-color:'.$loadColorArr['0']['code'].';">'.$loadColorArr['0']['name'].'</span>';
                                            }
                                        }
                                    }
                                       
                                    */?></td>-->
                                    
                                    
                                     
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php 
            }
            else
            {
            ?>
                <h3>No Order Product Found.</h3>
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
</div>
  

<?php

include_once(__APP_PATH_LAYOUT__."/footer.php");

?>	
