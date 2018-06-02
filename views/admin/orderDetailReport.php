
<div class="col-md-12">
    <div class="search-page search-content-2">
        <div class="search-bar bordered">
            <form id="orderSearchForm" id="orderSearchForm" method="post">
                <div class="row">
                    
                    <div class="col-md-2">
                        <div class="form-group datefrom <?php if($kOrder->arErrorMessages['startcreatedon'] !=''){ ?> has-error <?php } ?>">
                            <div class="form-group" data-date="<?php echo date('d/m/Y')?>" data-date-format="dd/mm/yyyy">
                                <input type="text" class="form-control date1" name="searchArr[startcreatedon]" id="enddt" placeholder="From Date" value="<?php echo $_POST['searchArr']['startcreatedon']?>">
                                 <?php if($kOrder->arErrorMessages['startcreatedon'] !=''){ ?> 
                            <span class="help-block"><?php echo $kOrder->arErrorMessages['startcreatedon']; ?></span>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div class="form-group dateto <?php if($kOrder->arErrorMessages['endcreatedon'] !=''){ ?> has-error <?php } ?>">
                          <div class="form-group" data-date="<?php echo date('d/m/Y')?>" data-date-format="dd/mm/yyyy">
                                <input type="text" class="form-control date1" name="searchArr[endcreatedon]" id="endcreatedon" placeholder="To Date" value="<?php echo $_POST['searchArr']['endcreatedon']?>">
                                 <?php if($kOrder->arErrorMessages['endcreatedon'] !=''){ ?> 
                            <span class="help-block"><?php echo $kOrder->arErrorMessages['endcreatedon']; ?></span>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="form-group" >
                                <input type="text" class="form-control orderno" name="searchArr[prod-name]" id="prod-name" placeholder="Product" value="<?php echo $_POST['searchArr']['prod-name']?>">
                                <?php if($kOrder->arErrorMessages['prod-name'] !=''){ ?>
                                    <span class="help-block"><?php echo $kOrder->arErrorMessages['prod-name']; ?></span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
<!--                        <div class="form-group">
                          <div class="form-group" >
                                <input type="text" class="form-control businessname" name="searchArr[businessname]" id="businessname" placeholder="Customer" value="<?php echo $_POST['searchArr']['businessname']?>">
                                 <?php if($kOrder->arErrorMessages['businessname'] !=''){ ?> 
                            <span class="help-block"><?php echo $kOrder->arErrorMessages['businessname']; ?></span>
                            <?php } ?>
                            </div>
                        </div>-->
                        <div class="form-group input-group customerlist">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <select class="form-control custom-select" name="searchArr[businessname]"  id="customer" onfocus="remove_formError(this.id,'true')">
                                        <option value="">Customer</option>
                                        <?php 
                                        $customerArr = $kUser->loadCustomers();
                                        if(!empty ($customerArr)){
                                            foreach ($customerArr as $customerdetail){
                                                $selected = '';
                                                if($_POST['searchArr']['businessname'] == $customerdetail['business_name']){
                                                    $selected = 'selected="selected"';
                                                }
                                                echo '<option value="'.$customerdetail['business_name'].'" '.$selected.'>'.$customerdetail['business_name'].'</option>';
                                            }
                                        }
                                        ?>
                                </select>
                            </div>
                    </div>
                    <!--<div class="col-md-1">
                        <div class="form-group">
                          <div class="form-group" >
                                <input type="text" class="form-control orderno" name="searchArr[order_number]" id="orderno" placeholder="Ord#" value="<?php /*echo $_POST['searchArr']['order_number']*/?>">
                                 <?php /*if($kOrder->arErrorMessages['order_number'] !=''){ */?>
                            <span class="help-block"><?php /*echo $kOrder->arErrorMessages['order_number']; */?></span>
                            <?php /*} */?>
                            </div>
                        </div>
                    </div>-->

                    <!--<div class="col-md-2">
                        <div class="form-group input-group customerlist">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <select class="form-control custom-select" name="searchArr[orderstat]"  id="orderstat" onfocus="remove_formError(this.id,'true')">
                                        <option value="">Status</option>
                                        <option value="1">Ordered</option>
                                        <option value="2">Pending</option>
                                        <option value="3">Dispatched</option>
                                        <option value="5">Canceled</option>
                                </select>
                            </div>
                    </div>-->
                     <div class="col-md-2">
                    <div class="form-group">
                        <button class="btn blue uppercase bold reposearch" type="button" onclick="getOrderDeatilReportSearch();"><i class="fa fa-search"></i> Search</button>
                        &nbsp;
                        <!--<button class="btn red uppercase bold" type="button" onclick="resetClientSearch();"><i class="fa fa-refresh"></i>Reset</button>-->
                    </div>
                </div>
                   
                    
                    
                </div>
               
                
                
            </form>
        </div>
    </div>
    <?php 
        $searchvals = '';
         foreach ($szSearchText as $key => $value) {
             if(!empty ($value)){
                    $searchvals .= $key.'='.$value.'&';
             }
         }
         $searchvals = substr($searchvals, 0, -1);
    if(!empty($orderArr))
    {
        $prodname = '';
        $_POST['searchArr']['prod-name'] = trim($_POST['searchArr']['prod-name']);
        if(!empty($_POST['searchArr']['prod-name'])){
            $prodname = $_POST['searchArr']['prod-name'];
        }
        $orderItemDetArr = array();
    foreach($orderArr as $orderData) {
        $orderProdArr = $kOrder->loadProductOrder($orderData['id'], $prodname);
        if (!empty ($orderProdArr)) {
            foreach ($orderProdArr as $prodDet){
                $addOutside = true;
                if(!empty($orderItemDetArr)) {

                    foreach ($orderItemDetArr as $key1 => $orderitemdet) {
                        if ($orderItemDetArr[$key1]['id'] == $prodDet['productid']) {
                            $orderItemDetArr[$key1]['qtyorder'] = $orderItemDetArr[$key1]['qtyorder'] + $prodDet['quantity'];
                            $orderItemDetArr[$key1]['qtydispatch'] = $orderItemDetArr[$key1]['qtydispatch'] + $prodDet['dispatched'];
                            $addOutside = false;
                        }
                    }
                    if($addOutside){
                        $newItemArr = array('id' => $prodDet['productid'], 'name' => $prodDet['description'], 'qtyorder' => $prodDet['quantity'], 'qtydispatch' => $prodDet['dispatched'], 'weight' => $prodDet['weight']);
                        array_push($orderItemDetArr,$newItemArr);
                    }
                }else{
                    $arrOrdVals = array('id' => $prodDet['productid'], 'name' => $prodDet['description'], 'qtyorder' => $prodDet['quantity'], 'qtydispatch' => $prodDet['dispatched'], 'weight' => $prodDet['weight']);
                    $orderItemDetArr = array(
                        $arrOrdVals
                    );
                }

            }

        }
    }
        ?>
    <div class="portlet box blue">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-users"></i>Detailed Order Report
            </div>
            <div class="actions">
                <div data-toggle="buttons" class="btn-group btn-group-devided">
                    <!--<button class="btn grey btn-sm active" onclick="redirect_url('<?php /*echo __VIEW_DETAIL_ORDER_REPORT_PDF_URL__.(!empty ($searchvals)?'?'.$searchvals:'');*/?>','1');">
                        <i class="fa fa-eye"></i> View PDF
                    </button>-->
                    <button class="btn grey btn-sm active" onclick="redirect_url('<?php echo __VIEW_DETAIL_ORDER_REPORT_CSV_URL__.(!empty ($searchvals)?'?'.$searchvals:'');?>','1');">
                        <i class="fa fa-eye"></i> Export CSV
                    </button>
                </div>
            </div>
        </div>
        
        <div class="portlet-body">
<?php if(!empty($orderItemDetArr)) { ?>
                <div class="table-responsive">
                    <table id="sample_1" class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" role="grid" aria-describedby="sample_1_info">
                        <thead>
                            <tr>
                                <th> # </th>
                                <!--<th> Customer </th>
                                <th> Order date </th>-->
                                <th> Product Name</th>
                                <th> Ordered Qty. </th>
                                <th> Dispatched Qty. </th>
                                <th> Weight (Kg)</th>
                                <th> Total Weight (Kg)</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i=1;

                            foreach($orderItemDetArr as $orderItemData)
                            {
                            ?>
                                <tr>
                                    <td> <?php echo $i++; ?> </td>
                                    <!--<td> <?php /*echo $orderData['business_name']; */?> </td>
                                    <td> <?php /*echo date('d/m/Y',  strtotime($orderData['createdon'])); */?> </td>-->
                                    <td><?php echo $orderItemData['name'];?></td>
                                    <td><?php echo $orderItemData['qtyorder'];?></td>
                                    <td><?php echo $orderItemData['qtydispatch'];?></td>
                                    <td><?php echo $orderItemData['weight'];?></td>
                                    <td><?php echo $orderItemData['weight']*$orderItemData['qtydispatch'];?></td>
                                  <!--<td> <?php /*echo '$'.($prodDet['price']*$prodDet['dispatched']); */?> </td>
                                    <td> <?php /*
                                     if($orderData['status']=='1')
                                     {*/?>
                                        <a title="Order Status" class="label label-sm label-warning">
                                            Ordered 
                                        </a>
                                     <?php
/*                                     }
                                     if($orderData['status']=='2')
                                     {*/?>
                                        <a title="Order Status" class="label label-sm label-success">
                                            Pending 
                                        </a>
                                     <?php
/*                                     }
                                     if($orderData['status']=='3')
                                     {*/?>
                                        <a title="Order Status" class="label label-sm label-info">
                                            Dispatched 
                                        </a>
                                     <?php
/*                                     }
                                     if($orderData['status']=='4')
                                     {*/?>
                                        <a title="Order Status" class="label label-sm label-success">
                                            Complete 
                                        </a>
                                     <?php
/*                                     }
                                     if($orderData['status']=='5')
                                     {*/?>
                                        <a title="Order Status" class="label label-sm label-danger">
                                            Canceled 
                                        </a>
                                     <?php
/*                                     }
                                     
                                     */?></td>-->
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
<?php }else{
    echo '<h3>No Product Found.</h3>';
} ?>
        </div>
    </div>
<?php
}elseif($showNoProd){ ?>
    <div class="portlet box blue">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-users"></i>Detailed Order Report
            </div>
        </div>

        <div class="portlet-body">
            <h3>No Product Found.</h3>
        </div>
    </div>
    <?php }

?>
</div>
<div id="popup"></div>
<script type="text/javascript">
    $('.date').pickmeup({
        format: 'd/m/Y'
    });
    $('.date1').pickmeup({
        format: 'd/m/Y'
    });

</script>
