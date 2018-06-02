<?php 
$grouparr = $kCommon->loadGroups();
if($parentId >'0' && $childId>'0')
{
    $_POST['searchArr']['prParentGroup']=$parentId;
    $_POST['searchArr']['prGroup']=$childId;
}
if($parentId >'0' && $childId == '')
{
    $_POST['searchArr']['prParentGroup']=$parentId;
}
if(!empty($proddesArr)){
    $_POST['searchArr']['szSearchText'] = $proddesArr[0]['description'];
}
if(isset($_SESSION['prGroup']) && !empty($_SESSION['prGroup'])){
    $_POST['searchArr']['prGroup'] = $_SESSION['prGroup'];
}
if(isset($_SESSION['prParentGroup']) && !empty($_SESSION['prParentGroup'])){
    $_POST['searchArr']['prParentGroup'] = $_SESSION['prParentGroup'];
}
if(isset($_SESSION['szSearchText']) && !empty($_SESSION['szSearchText'])){
    $_POST['searchArr']['szSearchText'] = $_SESSION['szSearchText'];
}
?>
<div class="col-md-12">
    <div class="search-page search-content-2">
        <div class="search-bar bordered">
              <form name="searcProduct" id="searcProduct" method="post" class="form-horizontal" enctype="multipart/form-data">
                <div class="form-body">
                    <div class="row">
                    <div class="col-md-3 custom">
                        <div class="form-group">
                            <label for="Search Client">Product Name</label>
                            <input type="text" class="form-control" name="searchArr[szSearchText]" id="szSearchText" placeholder="Product Name" value="<?php echo $_POST['searchArr']['szSearchText'];?>">
                        </div>
                    </div>
                <div id="parentcat">
                    
                <div class="col-md-3 custom">
                   
                        <div class="form-group">
                            <label for="client">Categories</label>
                            <select class="form-control" name="searchArr[prParentGroup]" id="prParentGroup" onchange="GetSubcategoriesSearch(this.value);">
                                <option value="0">Select</option>
                                <?php
                                if(!empty($grouparr))
                                {
                                    foreach($grouparr as $group)
                                    {
                                    ?>
                                    <option value="<?php echo $group['id']; ?>" <?php if($_POST['searchArr']['prParentGroup']==$group['id']) { ?> selected="selected" <?php } ?>><?php echo $group['name']; ?></option>
                                    <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                         </div>
                    </div>
                       
                    
                    <div id="subcat">
                        <div class="col-md-3 custom">
                        <div class="form-group">
                            <label for="client">Sub Categories</label>
                            <select class="form-control custom-select" name="searchArr[prGroup]"  id="prGroup" onfocus="remove_formError(this.id,'true')">
                                <option value="0">Select</option>
                                <?php 
                                if(!($_POST['searchArr']['prParentGroup']) == '')
                                {
                                    $childcategories = $kCommon->loadGroups(0,$_POST['searchArr']['prParentGroup']);
                                    if(!empty($childcategories))
                                    {
                                        foreach($childcategories as $child)
                                        {
                                            if($_POST['searchArr']['prGroup'])
                                            {
                                                $selected = ($child['id'] == $_POST['searchArr']['prGroup'] ? 'selected="selected"' : '');}
                                           ?>
                                                 <option value="<?php echo $child['id']; ?>" <?php echo $selected;?> ><?php echo $child['name']; ?></option>
					<?php													
                                        }
                                    }
                                }
                                    
                                ?>
                            </select>
                        </div>
                    </div>
                    </div>
                        <div class="col-md-2 search-button text-right">
                    <div class="form-group">
                        <button class="btn blue uppercase bold" type="button" onclick="searchProduct();"><i class="fa fa-search"></i> Search</button>
                        &nbsp;
                        <!--<button class="btn red uppercase bold" type="button" onclick="resetClientSearch();"><i class="fa fa-refresh"></i>Reset</button>-->
                    </div>
                </div>
                
            </div>
        </div>
    </form>
        </div>
    </div>
    <div class="portlet box blue">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-users"></i>View Products List
            </div>
            <div class="actions">
                <?php if((int)$_SESSION['usr']['role'] != 2){?>
                <div data-toggle="buttons" class="btn-group btn-group-devided">
                    <button class="btn grey btn-sm active" onclick="ImportPriceModal();">
                        <i class="fa fa-plus"></i> Import Price
                    </button>
                    <button class="btn grey btn-sm active" onclick="redirect_url('<?php echo __ADD_NEW_PRODUCT_URL__; ?>')">
                        <i class="fa fa-plus"></i> Add New Product
                    </button>
                </div>
                <?php } ?>
            </div>
        </div>
        <div class="portlet-body">
            <?php
            
                     
            if(!empty($prodArr))
            {
                
            ?>
                <div class="table-responsive">
                   <table id="sample_1" class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" role="grid" aria-describedby="sample_1_info">
                        <thead>
                            <tr>
                                <th> # </th>
				     <!--<th <?php /*if($sortBy=='name' && $sortValue=='DESC'){*/?>class="sorting_asc" onclick="sortProductListing('name','ASC');" <?php /*}elseif($sortBy=='name' && $sortValue=='ASC') {*/?>class="sorting_desc" onclick="sortProductListing('name','DESC');" <?php /*}else{*/?>class="sorting" onclick="sortProductListing('name','ASC');"<?php /*}*/?>>
                                    Code
                                   </th>-->
                                
                                <th> Product Name </th>
                                <th>Categories</th>
                                <th>Sub Categories</th>
                                <th> Image </th>
                                <th> Color</th>
                                <th> Action </th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i=1;
                            foreach($prodArr as $productdata)
                            {
                                $selected = $kCommon->loadGroups($productdata['groupid'],0,false);
                            ?>
                                <tr>
                                    <td> <?php echo $i++; ?> </td>
                                    <!--<td>
                                      <?php /*echo $productdata['name']; */?>
                                        
                                    </td>-->
                                    <td><?php echo $productdata['name']; ?> </td>
                                    <td>
                                        <?php 
                                            $selectedMain= $kCommon->loadGroups($selected[0]['parentid'],0,false);
                                            echo $selectedMain[0]['name'];
                                        ?>
                                    </td>
                                     <td>
                                        <?php 
                                            $selectedSubCat= $kCommon->loadGroups($selected[0]['id'],0,false);
                                            echo $selectedSubCat[0]['name'];
                                        ?>
                                    </td>
                                   
                                    <td> <img class="product-thumb" src="<?php echo __PRODUCT_IMAGE_PATH_URL__;?><?php echo ($productdata['image']?$productdata['image']:'comming-soon.jpg'); ?>" /> </td>
                                    <td> 
                                    <?php 
                                    $colorArr = explode(',', $productdata['color']);
                                    if(!empty ($colorArr)){
                                        foreach ($colorArr as $key => $value) {
                                            $loadColorArr = $kCommon->loadColors($value);
                                            if(!empty ($loadColorArr)){
                                                echo '<span class="product_colors" style="background-color:'.$loadColorArr[0]['code'].';">'.$loadColorArr[0]['name'].'</span>';
                                            }
                                        }
                                    }
                                       
                                    ?>
                                        </td>
                                    <td>
                                        <?php if($_SESSION['usr']['role']=='1' || $_SESSION['usr']['role']=='2'){?>
                                        <a title="View Products " class="btn dark btn-sm btn-outline sbold uppercase" href="javascript:void(0);" onclick="viewProdsearch('<?php  echo __VIEW_PRODUCTS_DESCRIPTION__.$productdata['id'].'/';?>')">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        <?php }if($_SESSION['usr']['role']=='1'){ ?>
                                        <a title="Edit" class="btn btn-outline btn-sm purple" href="javascript:void(0);" onclick="redirect_url('<?php echo __EDIT_PRODUCT_URL__;?><?php echo $productdata['id'];?>/')">
                                              <i class="fa fa-edit"></i>
                                          </a>
                                        <?php }if($_SESSION['usr']['role']=='1' || $_SESSION['usr']['role']=='2'){?>
                                        <a title="Inventory" class="btn btn-outline btn-sm gray" href="javascript:void(0);" onclick="productInventory('<?php echo $productdata['id'];?>')">
                                              <i class="fa fa-cubes"></i>
                                          </a>
                                        <?php } if($_SESSION['usr']['role']=='1'){?>
                                         <a title="Pricing" class="btn btn-outline btn-sm green" href="javascript:void(0);" onclick="redirect_url('<?php  echo __PRICE_MANAGEMENT_URL__.$productdata['id'].'/';?>')">
                                              <i class="fa fa-usd"></i>
                                          </a>
                                            <?php if(!in_array($productdata['id'],$ordprodlist)){?>
                                          <a title="Delete" class="btn btn-outline red btn-sm black" href="javascript:void(0);" onclick="deleteProduct('<?php echo $productdata['id'];?>')">
                                              <i class="fa fa-trash"></i>
                                          </a>
                                                <?php }?>
                                        <?php } ?>
                                    </td>
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
                <h3>No Product Found.</h3>
            <?php
            }
            ?>
        </div>
    </div>
</div>
<div id="popup"></div>
