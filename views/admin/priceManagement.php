
<div class="col-md-12">
     <div class="portlet light bordered about-text" id="user_info">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-check icon-info font-gray"></i><span class="caption-subject font-gray bold uppercase">Product Details</span>
            </div>
            
        </div>
        
        <div class="portlet-body alert">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-sm-4 text-info bold">
                            <lable>Name</lable>
                        </div>
                        <div class="col-sm-8">
                          <p> <?php echo $productArr['0']['name'];?> </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 text-info bold">
                            <lable>Colors</lable>
                        </div>
                        <div class="col-sm-8">
                          <p> <?php 
                                $colorArr = explode(',', $productArr[0]['color']);
                                if(!empty ($colorArr)){
                                foreach ($colorArr as $key => $value) {
                                            $loadColorArr = $kCommon->loadColors($value);
                                            if(!empty ($loadColorArr)){
                                                echo '<span class="product_colors" style="background-color:'.$loadColorArr[0]['code'].';">'.$loadColorArr[0]['name'].'</span>';
                                            }
                                        }
                                    }
                                       
                                    ?> </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 text-info bold">
                            <lable>Description</lable>
                        </div>
                        <div class="col-sm-8">
                          <p> <?php echo $productArr['0']['description'];?> </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 text-info bold">
                            <lable>Category</lable>
                        </div>
                        <div class="col-sm-8">
                          <p> <?php $groupIdArr = $kProduct->loadProductGroup($productArr['0']['id']);
                                    $selectedCat = $kCommon->loadGroups($groupIdArr[0]['groupid'],0,false);
                                    $childcategories = $kCommon->loadGroups($selectedCat[0]['parentid'],0,false);
                                    echo $childcategories[0]['name'].' <i class="fa fa-arrow-right"></i> '.$selectedCat[0]['name']; ?> </p>
                        </div>
                    </div>
                     
                </div>
                <div class="col-md-6">
                    <div class="row">
                        
                        <div class="col-sm-8">
                             <img class="product-thumb" src="../../../images/products/<?php echo ($productArr[0]['image']?$productArr[0]['image']:'comming-soon.jpg'); ?>" />
                        </div>
                    </div>
                </div>
                
            </div>
            
        </div>
     </div>
    
    <div class="portlet box blue">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-money"></i>Product Price Management
            </div>
            <div class="actions">
                <div data-toggle="buttons" class="btn-group btn-group-devided">

                    <button class="btn grey btn-sm active" onclick="redirect_url('<?php echo __VIEW_PRODUCTS_LIST_URL__;?>');">
                       Back
                    </button>
                </div>
            </div>
        </div>
        <div class="portlet-body">
            <?php
            
                     
            if($productId > 0)
            {
            ?>
                <div class="table-responsive">
                   <table id="sample_1" class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" role="grid" aria-describedby="sample_1_info">
                        <thead>
                            <tr>
                                <th> # </th>
				<th> Business Name </th>
                                <th> Price </th>
<!--                                <th> Medium</th>
                                <th> Large </th>-->
                                <th> Action </th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i=1;
                            $customerArr = $kProduct->loadCustomers();
                            if(!empty ($customerArr)){
                            foreach ($customerArr as $cust){
                                ?>
                                <tr>
                                    <td> <?php echo $i++; ?> </td>
                                    <td><?php echo $cust['business_name'];?></td>
                                    <td>
                                        $
                                        <?php  $cutomerPriceSmall = $kProduct->getProdPriceByProdID((int)($productId),$cust['id']);
                                                echo $cutomerPriceSmall[0]['price'];
                                        ?>
                                    </td>
                               
                                    <td><a title="Edit" class="btn btn-outline btn-sm purple" href="javascript:void(0);" onclick="priceManagement('<?php echo $productId;?>','<?php echo $cust['id'];?>')">
                                              <i class="fa fa-edit"></i>
                                          </a></td>
                                </tr>
                              <?php  }
                            
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
                <h3>No Product Found.</h3>
            <?php
            }
            ?>
        </div>
    </div>
</div>
<div id="popup"></div>
