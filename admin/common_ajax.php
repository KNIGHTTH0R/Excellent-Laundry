<?php
ob_start();
session_start();
ini_set("post_max_size","20M");
if( !defined( "__APP_PATH__" ) )
define( "__APP_PATH__", realpath( dirname( __FILE__ ) . "/../" ) );
require_once( __APP_PATH__ . "/includes/constants.php" );
require_once( __APP_PATH_CLASSES__ . "/common.class.php" );
require_once( __APP_PATH_CLASSES__ . "/user.class.php" );
require_once( __APP_PATH_CLASSES__ . "/product.class.php" );
$kCommon = new cCommon();
$kUser = new cUser();
$kDriver = new cDriver();
$kProduct = new cProduct();
$mode=sanitize_all_html_input($_POST['mode']);

if($mode == '__GET_STATES__'){
    $statesarr = $kCommon->loadState(0,$_POST['country']);
    if(!empty ($statesarr))
    {
        echo "SUCCESS||||";
        //include( __APP_PATH_VIEW_FILES__ . "/addUser.php" );
        ?>
        <div id="mystate" class="form-group <?php if($kUser->arErrorMessages['usState'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">State</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <select class="form-control custom-select" name="addUserAry[usState]" onchange="getcity();" id="states" onfocus="remove_formError(this.id,'true')">
                                        <option value="">States</option>
                                        <?php 
                                            foreach($statesarr as $states)
                                            {
                                                    $selected = ($states['id'] == $_POST['addUserAry']['usState'] ? 'selected="selected"' : '');	
                                                    echo '<option value="'.$states['id'].'" '.$selected.'>'.$states['name'].'</option>';
                                            }
                                        ?>
                                </select>
                            </div>
                            <?php if($kUser->arErrorMessages['usState'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usState']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
        <?php
    }
    else
    {
        echo 'ERROR||||';
        include( __APP_PATH_VIEW_FILES__ . "/addUser.php" );
        
    }
}

if($mode == '__GET_CITIES__'){
    $citysarr = $kCommon->loadCity(0,$_POST['state']);
    if(!empty ($citysarr))
    {
        echo "SUCCESS||||";
        //include( __APP_PATH_VIEW_FILES__ . "/addUser.php" );
        ?>
        <div id="mycity" class="form-group <?php if($kUser->arErrorMessages['usCity'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">City</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <select class="form-control custom-select" name="addUserAry[usCity]" id="cities" onfocus="remove_formError(this.id,'true')">
                                        <option value="">City</option>
                                        <?php 
                                            foreach($citysarr as $city)
                                            {
                                                    $selected = ($city['id'] == $_POST['addUserAry']['usCity'] ? 'selected="selected"' : '');	
                                                    echo '<option value="'.$city['id'].'" '.$selected.'>'.$city['name'].'</option>';
                                            }
                                        ?>
                                </select>
                            </div>
                            <?php if($kUser->arErrorMessages['usCity'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usCity']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
        <?php
    }
    else
    {
        echo 'ERROR||||';
        include( __APP_PATH_VIEW_FILES__ . "/addUser.php" );
        
    }
}

if($mode == '__ADD_NEW_USER__'){
    $newCustomerid = $kUser->addNewCustomer($_POST['addUserAry']);
    /*$allProdArr = $kProduct->loadProducts();
    if(!empty ($allProdArr)){
        foreach ($allProdArr as $prods){
            $kProduct->addPrice($prods['id'], '0.00', $newCustomerid);
        }
    }*/
    if($newCustomerid > '0')
    {
        $custarr = $kUser->loadCustomers($newCustomerid);
        $prodIdsArr = $kProduct->loadProducts();
        if(!empty ($prodIdsArr)){
            foreach ($prodIdsArr as $prods){
                $kProduct->addPrice($prods['id'], '0.00', $newCustomerid);
            }
        }
        
        echo "SUCCESS||||";
        include( __APP_PATH_VIEW_FILES__ . "/addUser.php" );
        ?>
        <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add New Customer Confirmation</h4>
                    </div>
                    <div class="modal-body">
                        <p class="alert alert-success"><i class="fa fa-check"></i> A new customer has been added successfully.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="redirect_url('<?php echo __VIEW_CUSTOMERS_LIST_URL__; ?>'); return false;" class="btn dark btn-outline">Close</button>
                    </div>
                </div>
            </div>
        </div>
        
        <?php
    }
    else
    {
        echo 'ERROR||||';
        include( __APP_PATH_VIEW_FILES__ . "/addUser.php" );
        
    }
}

if($mode == '__ADD_NEW_GROUP__'){
    if($_POST['addGroupAry']['usParentid'] > 0){
        $res = $kUser->addSubGroup($_POST['addGroupAry']);
    }else{
        $res = $kUser->addGroup($_POST['addGroupAry']);
    }
    if($res){
        echo "SUCCESS||||";
        include( __APP_PATH_VIEW_FILES__ . "/addGroup.php" );
        ?>
        <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add New Category Confirmation</h4>
                    </div>
                    <div class="modal-body">
                        <p class="alert alert-success"><i class="fa fa-check"></i> A new category has been added successfully.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="redirect_url('<?php echo __VIEW_GROUPS_LIST_URL__; ?>'); return false;" class="btn dark btn-outline">Close</button>
                    </div>
                </div>
            </div>
        </div>
        
        <?php
    }
    else
    {
        echo 'ERROR||||';
        include( __APP_PATH_VIEW_FILES__ . "/addGroup.php" );
        
    }
}

if($mode == '__CUSTOMER_DETAILS__')
{
    $customerId=$_POST['customerId'];
    
    echo "SUCCESS||||";
    $customerArr=$kUser->loadCustomers($customerId);
    if(!empty ($customerArr)){
    ?>
    <div id="clientStatus" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4><span class="caption-subject font-red-sunglo bold uppercase">Customer Details</span></h4>
                </div>
                <div class="modal-body">
                    
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <td><strong>Business Name</strong></td>
                                <td><?php echo $customerArr['0']['business_name'];?></td>
                            </tr>
                            <tr>
                                <td><strong>Contact Name</strong></td>
                                <td><?php echo $customerArr['0']['contact_name'];?></td>
                            </tr>
                            <tr>
                                <td><strong>Phone No.</strong></td>
                                <td><?php echo $customerArr['0']['phoneno'];?></td>
                            </tr>
                            <tr>
                                <td><strong>Mobile No.</strong></td>
                                <td><?php echo $customerArr['0']['mobileno'];?></td>
                            </tr>
                            <tr>
                                <td><strong>Contact Email</strong></td>
                                <td><?php echo $customerArr['0']['contact_email'];?> <label style="float: right; margin-bottom: 0px; margin-top: 2px" class="label label-info">Registration Email</label></td>
                            </tr>
                            <tr>
                                <td><strong>Business Email</strong></td>
                                <td><?php echo $customerArr['0']['business_email'];?> </td>
                            </tr>
                            <?php 
                            $addrArr = explode('$', $customerArr['0']['address']);
                            ?>
                            <tr>
                                <td><strong>Street Address 1</strong></td>
                                <td><?php echo $addrArr[0];?></td>
                            </tr>
                            <tr>
                                <td><strong>Street Address 2</strong></td>
                                <td><?php echo $addrArr[1];?></td>
                            </tr>
                            <tr>
                                <td><strong>Standard Order Customer</strong></td>
                                <td><?php echo ($customerArr[0]['soc']==1?'Yes':'No');?></td>
                            </tr>
                            <?php 
                            //$cityArr = $kCommon->loadCity($customerArr['0']['city']);
                            $stateArr = $kCommon->loadState($customerArr['0']['state']);
                            //$countryArr = $kCommon->loadCountry($stateArr['0']['country_id']);
                            ?>
<!--                           <tr>
                                <td><strong>City</strong></td>
                                <td><?php echo $cityArr['0']['name'];?></td>
                            </tr>-->
                            <tr>
                                <td><strong>State</strong></td>
                                <td><?php echo $stateArr['0']['name'];?></td>
                            </tr>
                            <tr>
                                <td><strong>Country</strong></td>
                                <td><?php //echo $countryArr['0']['name'];
                                echo "Australia";?></td>
                            </tr>
                            <tr>
                                <td><strong>Zip Code</strong></td>
                                <td><?php echo $customerArr['0']['postcode'];?></td>
                            </tr>
                            <tr>
                                <td><strong>Contract Start Date</strong></td>
                                <td><?php echo ($customerArr['0']['contract_start'] != '0000-00-00 00:00:00'?date(__DATE_FORMAT__,  strtotime($customerArr['0']['contract_start'])):"NA");?></td>
                            </tr>
                            <tr>
                                <td><strong>Contract End Date</strong></td>
                                <td><?php echo ($customerArr['0']['contract_end'] != '0000-00-00 00:00:00'?date(__DATE_FORMAT__,  strtotime($customerArr['0']['contract_end'])):"NA");?></td>
                            </tr>
                            <?php 
                                $grouparr = $kCommon->loadGroups($customerArr['0']['groupid']);
                            ?>
<!--                            <tr>
                                <td><strong>Group</strong></td>
                                <td><?php echo $grouparr['0']['name'];?></td>
                            </tr>-->
                            
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <?php
    }
}

if($mode == '__ADD_NEW_DRIVER__'){
    
    if($kUser->addDriver($_POST['addDriverAry']))
    {
        echo "SUCCESS||||";
        include( __APP_PATH_VIEW_FILES__ . "/addNewDriver.php" );
        ?>
        <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add New Driver Confirmation</h4>
                    </div>
                    <div class="modal-body">
                        <p class="alert alert-success"><i class="fa fa-check"></i> A new driver has been added successfully.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="redirect_url('<?php echo __MANAGE_DRIVER_URL__; ?>'); return false;" class="btn dark btn-outline">Close</button>
                    </div>
                </div>
            </div>
        </div>
        
        <?php
    }
    else
    {
        echo 'ERROR||||';
        include( __APP_PATH_VIEW_FILES__ . "/addNewDriver.php" );
        
    }
}

else if($mode == '__DELETE_DRIVER__')
{
   
    $driverId=$_POST['driverId']; 
    
    $carrierArr=$kUser->getAllDriver();
     echo "SUCCESS||||";
    include( __APP_PATH_VIEW_FILES__ . "/manageDriver.php" );
    ?>
     <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                       <h4 class="modal-title">Delete Driver Confirmation</h4>
                    </div>
                    <div class="modal-body">
                        <p class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> Are you sure you want to delete the selected Driver?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <button type="button" onclick="deleteDriverConfirmation('<?php echo $driverId;?>'); return false;" class="btn blue"><i class="fa fa-times"></i> Delete</button>
                    </div>
                </div>
            </div>
        </div>
   
    <?php
}

else if($mode == '__DELETE_DRIVER_CONFIRMATION__')
{
   if($kUser->deleteDriverById($_POST['driverId']))
    {
        $carrierArr=$kUser->getAllDriver();
        echo "SUCCESS||||";
        include( __APP_PATH_VIEW_FILES__ . "/manageDriver.php" );
        ?>
       <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                       <h4 class="modal-title">Delete Driver </h4>
                    </div>
                    <div class="modal-body">
                         <p class="alert alert-success"><i class="fa fa-check"></i> Driver has been deleted successfully.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="redirect_url('<?php echo __MANAGE_DRIVER_URL__; ?>'); return false;" class="btn dark btn-outline">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else
    {
        echo 'ERROR||||';
         include( __APP_PATH_VIEW_FILES__ . "/manageDriver.php" );
        
    }
    
    
    ?>
     
   
    <?php
}

if($mode == '__EDIT_DRIVER__'){
    
    if($kUser->editDriver($_POST['editDriverAry'],$_POST['driverId']))
    {
        echo "SUCCESS||||";
        include( __APP_PATH_VIEW_FILES__ . "/editDriver.php" );
        ?>
        <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Update Driver Confirmation</h4>
                    </div>
                    <div class="modal-body">
                        <p class="alert alert-success"><i class="fa fa-check"></i> A driver has been updated successfully.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="redirect_url('<?php echo __MANAGE_DRIVER_URL__; ?>'); return false;" class="btn dark btn-outline">Close</button>
                    </div>
                </div>
            </div>
        </div>
        
        <?php
    }
    else
    {
        echo 'ERROR||||';
        $driverId=$_POST['driverId'];
        include( __APP_PATH_VIEW_FILES__ . "/editDriver.php" );
        
    }
}

if($mode == '__ADD_RUN_SLOT__')
   {
    
    $submit=$kDriver->allAddRunSlot($_POST['day'],$_POST['addRunSlotAry']);
            
    if($submit)
    {
        echo "SUCCESS||||";
        $weekDaysAry=$kUser->getAllWeekDays();
        include( __APP_PATH_VIEW_FILES__ . "/editCustomersRunSlot.php" );
        ?>
        <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Customer Run Slot Confirmation</h4>
                    </div>
                    <div class="modal-body">
                        <p class="alert alert-success"><i class="fa fa-check"></i> A customer Run Slot has been added successfully.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="redirect_url('<?php echo __CUSTOMERS_RUN_SLOT_URL__;?>'); return false;" class="btn dark btn-outline">Close</button>
                    </div>
                </div>
            </div>
        </div>
        
        <?php
    }
    else
    {
        echo 'ERROR||||';
        $weekDaysAry=$kUser->getAllWeekDays();
        include( __APP_PATH_VIEW_FILES__ . "/editCustomersRunSlot.php" );
        
    }
}

if($mode == '__GET_CUSTOMER__')
{
    $weekId=$_POST['weekId']; 
    echo "SUCCESS||||";
    include( __APP_PATH_VIEW_FILES__ . "/scheduleDriver.php" );
    
}

if($mode == '__ADD_SCHEDULE_DRIVER__')
   {
    
        $scheduleDriver=$kDriver->scheduleDriverByCustomer($_POST['addWeekdayAry'],$_POST['weekCustomerId']);
        if($scheduleDriver)
        {
        echo "SUCCESS||||";
        $weekDaysAry=$kUser->getAllWeekDays();
        include( __APP_PATH_VIEW_FILES__ . "/scheduleDriver.php" );
        ?>
        <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Schedule Driver Confirmation</h4>
                    </div>
                    <div class="modal-body">
                        <p class="alert alert-success"><i class="fa fa-check"></i> A driver has been schedule successfully.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="redirect_url('<?php echo __MANAGE_SCHEDULE_DRIVER_URL__;?>'); return false;" class="btn dark btn-outline">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
   }
if($mode == '__CUSTOMER_WEEK_DAY_SLOT_DETAILS__')
{
    $customerId=$_POST['customerId']; 
    
    echo "SUCCESS||||";
    $customerWeekdayAry=$kDriver->getAllCustomersByWeekid('',$customerId,'','',true);
    if(!empty ($customerWeekdayAry)){
    ?>
    <div id="customerWeekDayStatus" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4><span class="caption-subject font-red-sunglo bold uppercase">Customer Week Day Details</span></h4>
                </div>
                <div class="modal-body">
                    
                    <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> Weekday </th>
                                <th> Slot</th>
                                <th> Driver </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i=1;
                            foreach($customerWeekdayAry as $customerWeekdayData)
                            {
                            ?>
                                <tr>
                                    <td> <?php echo $i++; ?> </td>
                                    <td>
                                        <?php 
                                        if($customerWeekdayData['weekid'])
                                        {
                                            $weekDays=$kDriver->loadWeekDays($customerWeekdayData['weekid']);
                                            echo $weekDays['day'];
                                            
                                        }
                                         
                                        ?>
                                    </td> 
                                    <td><?php echo $customerWeekdayData['slot'];?></td> 
                                    <td>
                                        <?php
                                        if($customerWeekdayData['driverid'])
                                        {
                                            $driverData=$kUser->loadDriver($customerWeekdayData['driverid']);
                                            echo $driverData['name'];
                                        }
                                        ?>
                                    </td> 
                                   
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <?php
    }
}

else if($mode == '__DELETE_CUSTOMER__')
{
   
     $customerId=$_POST['customerId'];
     $userId=$_POST['usreId'];
    
    echo "SUCCESS||||";
    $userArr=$kUser->loadCustomers();
    include( __APP_PATH_VIEW_FILES__ . "/viewUsersList.php" );
    ?>
     <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                       <h4 class="modal-title">Delete Customer Confirmation</h4>
                    </div>
                    <div class="modal-body">
                        <p class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> Are you sure you want to delete the selected Customer?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <button type="button" onclick="deleteCustomerConfirmation('<?php echo $customerId;?>','<?php echo $userId;?>'); return false;" class="btn blue"><i class="fa fa-times"></i> Delete</button>
                    </div>
                </div>
            </div>
        </div>
   
    <?php
}

else if($mode == '__DELETE_CUSTOMER_CONFIRMATION__')
{
   if($kUser->deleteCustomerById($_POST['customerId'],$_POST['userId']))
    {
        $userArr=$kUser->loadCustomers();
        echo "SUCCESS||||";
         include( __APP_PATH_VIEW_FILES__ . "/viewUsersList.php" );
        ?>
       <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                       <h4 class="modal-title">Delete Customer </h4>
                    </div>
                    <div class="modal-body">
                         <p class="alert alert-success"><i class="fa fa-check"></i> Customer has been deleted successfully.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="redirect_url('<?php echo __VIEW_CUSTOMERS_LIST_URL__; ?>'); return false;" class="btn dark btn-outline">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else
    {
        echo 'ERROR||||';
         include( __APP_PATH_VIEW_FILES__ . "/viewUsersList.php" );
        
    }
}
    
if($mode == '__EDIT_USER__'){
    
    if($kUser->editCustomer($_POST['editUserAry']))
    {
        echo "SUCCESS||||";
        include( __APP_PATH_VIEW_FILES__ . "/editCustomer.php" );
        ?>
        <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Update Customers Confirmation</h4>
                    </div>
                    <div class="modal-body">
                        <p class="alert alert-success"><i class="fa fa-check"></i> A customer has been updated successfully.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="redirect_url('<?php echo __VIEW_CUSTOMERS_LIST_URL__; ?>'); return false;" class="btn dark btn-outline">Close</button>
                    </div>
                </div>
            </div>
        </div>
        
        <?php
    }
    else
    {
        echo 'ERROR||||';
        $driverId=$_POST['driverId'];
        include( __APP_PATH_VIEW_FILES__ . "/editCustomer.php" );
        
    }
}
if($mode == '__EDIT_GET_STATES__'){
    $statesarr = $kCommon->loadState(0,$_POST['country']);
    if(!empty ($statesarr))
    {
        echo "SUCCESS||||";
        //include( __APP_PATH_VIEW_FILES__ . "/addUser.php" );
        ?>
        <div id="mystate" class="form-group <?php if($kUser->arErrorMessages['usState'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">State</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <select class="form-control custom-select" name="editUserAry[usState]" onchange="editGetCity();" id="states" onfocus="remove_formError(this.id,'true')">
                                        <option value="">States</option>
                                        <?php 
                                            foreach($statesarr as $states)
                                            {
                                                    $selected = ($states['id'] == $_POST['editUserAry']['usState'] ? 'selected="selected"' : '');	
                                                    echo '<option value="'.$states['id'].'" '.$selected.'>'.$states['name'].'</option>';
                                            }
                                        ?>
                                </select>
                            </div>
                            <?php if($kUser->arErrorMessages['usState'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usState']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
        <?php
    }
    else
    {
        echo 'ERROR||||';
        include( __APP_PATH_VIEW_FILES__ . "/editCustomer.php" );
        
    }
}

if($mode == '__EDIT_GET_CITIES__'){
    $citysarr = $kCommon->loadCity(0,$_POST['state']);
    if(!empty ($citysarr))
    {
        echo "SUCCESS||||";
        //include( __APP_PATH_VIEW_FILES__ . "/addUser.php" );
        ?>
        <div id="mycity" class="form-group <?php if($kUser->arErrorMessages['usCity'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">City</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <select class="form-control custom-select" name="editUserAry[usCity]" id="cities" onfocus="remove_formError(this.id,'true')">
                                        <option value="">City</option>
                                        <?php 
                                            foreach($citysarr as $city)
                                            {
                                                    $selected = ($city['id'] == $_POST['editUserAry']['usCity'] ? 'selected="selected"' : '');	
                                                    echo '<option value="'.$city['id'].'" '.$selected.'>'.$city['name'].'</option>';
                                            }
                                        ?>
                                </select>
                            </div>
                            <?php if($kUser->arErrorMessages['usCity'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usCity']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
        <?php
    }
    else
    {
        echo 'ERROR||||';
        include( __APP_PATH_VIEW_FILES__ . "/editCustomer.php" );
        
    }
}
else if($mode == '__DELETE_GROUP__')
{
   
     $groupId=$_POST['groupId'];
    
    echo "SUCCESS||||";
    $groupArr=$kCommon->loadGroups();
    include( __APP_PATH_VIEW_FILES__ . "/viewGroupList.php" );
    ?>
     <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                       <h4 class="modal-title">Delete Category Confirmation</h4>
                    </div>
                    <div class="modal-body">
                        <p class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> Are you sure you want to delete the selected category?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <button type="button" onclick="deleteGroupConfirmation('<?php echo $groupId;?>'); return false;" class="btn blue"><i class="fa fa-times"></i> Delete</button>
                    </div>
                </div>
            </div>
        </div>
   
    <?php
}

else if($mode == '__DELETE_GROUP_CONFIRMATION__')
{
   
   if($kCommon->deleteGroupById($_POST['groupId']))
    {
        echo "SUCCESS||||";
        $groupArr=$kCommon->loadGroups();
        include( __APP_PATH_VIEW_FILES__ . "/viewGroupList.php" );
        ?>
       <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                       <h4 class="modal-title">Delete Group </h4>
                    </div>
                    <div class="modal-body">
                         <p class="alert alert-success"><i class="fa fa-check"></i> Category has been deleted successfully.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="redirect_url('<?php echo __VIEW_GROUPS_LIST_URL__; ?>'); return false;" class="btn dark btn-outline">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else
    {
        echo 'ERROR||||';
         include( __APP_PATH_VIEW_FILES__ . "/manageDriver.php" );
        
    }
}
if($mode == '__EDIT_GROUP__'){
    
    if($kUser->UpadetGroup($_POST['editGroupAry']))
    {
        echo "SUCCESS||||";
        include( __APP_PATH_VIEW_FILES__ . "/editGroup.php" );
        ?>
        <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Update Category Confirmation</h4>
                    </div>
                    <div class="modal-body">
                        <p class="alert alert-success"><i class="fa fa-check"></i> A category has been updated successfully.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="redirect_url('<?php echo __VIEW_GROUPS_LIST_URL__; ?>'); return false;" class="btn dark btn-outline">Close</button>
                    </div>
                </div>
            </div>
        </div>
        
        <?php
    }
    else
    {
        echo 'ERROR||||';
        include( __APP_PATH_VIEW_FILES__ . "/editGroup.php" );
        
    }
}

if($mode == '__EDIT_SLOT__')
   {
    
    echo "SUCCESS||||";
    $userArr=$kUser->loadCustomers();		
    include( __APP_PATH_VIEW_FILES__ . "/viewCustomerRunSlotSummery.php" );
   }
   if($mode == '__SAVE_RUN_SLOT__')
   {
    
    $customerId=$_POST['customerId'];
    $data=array();
    $data['1']=$_POST['mon'];
    $data['2']=$_POST['tus'];
    $data['3']=$_POST['wed'];
    $data['4']=$_POST['thurs'];
    $data['5']=$_POST['fri'];
    $data['6']=$_POST['sat'];
    $data['7']=$_POST['sun'];
    $driverData=array();
    $driverData['1']=$_POST['monDriver'];
    $driverData['2']=$_POST['tuesDriver'];
    $driverData['3']=$_POST['wedDriver'];
    $driverData['4']=$_POST['thursDriver'];
    $driverData['5']=$_POST['friDriver'];
    $driverData['6']=$_POST['satDriver'];
    $driverData['7']=$_POST['sunDriver'];
    $submit=$kDriver->alleditRunSlot($data,$customerId,$driverData);
            
    if($submit)
    {
        echo "SUCCESS||||";
        $userArr=$kUser->loadCustomers();		
        include( __APP_PATH_VIEW_FILES__ . "/viewCustomerRunSlotSummery.php" );
    }
    
}

   if($mode == '__DELETE_RUN_SLOT__')
   {
    //echo "hello";
    $customerId=$_POST['customerId'];
    $data=array();
    $data['1']='';
    $data['2']='';
    $data['3']='';
    $data['4']='';
    $data['5']='';
    $data['6']='';
    $deleted=0;
    $submit=$kDriver->allDeleteRunSlot($data,$customerId,$deleted);
            
    if($submit)
    {
        echo "SUCCESS||||";
        $userArr=$kUser->loadCustomers();		
        include( __APP_PATH_VIEW_FILES__ . "/viewCustomerRunSlotSummery.php" );
    }
    
}
if($mode == '__GET_RUN_SLOT_SEARCH__')
{
    echo "SUCCESS||||";
    
    $userArr=$kUser->loadCustomers('','','','',$_POST['searchArr']);	
    include( __APP_PATH_VIEW_FILES__ . "/viewCustomerRunSlotSummery.php" );
}
if($mode == '__ADD_NEW_ADMIN_USER__'){
    if($kUser->addUser($_POST['addUserAry']))
    {
        
        echo "SUCCESS||||";
        include( __APP_PATH_VIEW_FILES__ . "/addAdminUser.php" );
        ?>
        <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add New User Confirmation</h4>
                    </div>
                    <div class="modal-body">
                        <p class="alert alert-success"><i class="fa fa-check"></i> A new user has been added successfully.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="redirect_url('<?php echo __VIEW_USER__; ?>'); return false;" class="btn dark btn-outline">Close</button>
                    </div>
                </div>
            </div>
        </div>
        
        <?php
    }
    else
    {
        echo 'ERROR||||';
        include( __APP_PATH_VIEW_FILES__ . "/addAdminUser.php" );
        
    }
}

else if($mode == '__DELETE_ADMIN_USER__')
{
   
     
     $userId=$_POST['usreId'];
    
    echo "SUCCESS||||";
    $AdminuserArr=$kUser->loadAdminUser();
    include( __APP_PATH_VIEW_FILES__ . "/viewAdminUser.php" );
    ?>
     <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                       <h4 class="modal-title">Delete User Confirmation</h4>
                    </div>
                    <div class="modal-body">
                        <p class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> Are you sure you want to delete the selected User?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <button type="button" onclick="deleteAdminUserConfirmation('<?php echo $userId;?>'); return false;" class="btn blue"><i class="fa fa-times"></i> Delete</button>
                    </div>
                </div>
            </div>
        </div>
   
    <?php
}

else if($mode == '__DELETE_ADMIN_USER_CONFIRMATION__')
{
   if($kUser->deleteAdminUser($_POST['userId']))
    {
       echo "SUCCESS||||";
        $AdminuserArr=$kUser->loadAdminUser();
         include( __APP_PATH_VIEW_FILES__ . "/viewAdminUser.php" );
        ?>
       <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                       <h4 class="modal-title">Delete User </h4>
                    </div>
                    <div class="modal-body">
                         <p class="alert alert-success"><i class="fa fa-check"></i> User has been deleted successfully.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="redirect_url('<?php echo __VIEW_USER__; ?>'); return false;" class="btn dark btn-outline">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else
    {
        echo 'ERROR||||';
         include( __APP_PATH_VIEW_FILES__ . "/viewAdminUser.php" );
        
    }
}
if($mode == '__CHANGE_PASSWORD__')
{
    
    if($kUser->changeAdminPassword($_POST['changePasswordAry']))
    {
        echo "SUCCESS||||";
        include( __APP_PATH_VIEW_FILES__ . "/myProfile.php");
        ?>
         
        <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                       <h4 class="modal-title">Change Password </h4>
                    </div>
                    <div class="modal-body">
                         <p class="alert alert-success"><i class="fa fa-check"></i> Password has been change successfully.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="redirect_url('<?php echo __USER_DASHBOARD_URL__; ?>'); return false;" class="btn dark btn-outline">Close</button>
                    </div>
                </div>
            </div>
        </div>
            
         <?php
    }
    else
    {
        echo "ERROR||||";
        include( __APP_PATH_VIEW_FILES__ . "/myProfile.php");
    }
}
if($mode=='__FORGOT_PASSWORD__')
{
    $szForgotEmail=$_POST['szForgotEmail']; 
    $successFlag=false;
    if($kUser->forgotPassword($szForgotEmail))
    {
        echo 'SUCCESS||||';
    }
    else
    {
        echo 'ERROR||||';
    }
}

if($mode == '__GET_SUBCATEGORIES__'){
    if($_POST['parentcat'] > 0){
        $matchCar = '';
        $newcode = '';
        $groupArr=$kCommon->loadGroups(0,$_POST['parentcat']);
        $mainCatDetArr = $kCommon->loadGroups($_POST['parentcat'],0,FALSE);
        if($mainCatDetArr[0]['name'] == 'F&B' || $mainCatDetArr[0]['name'] == 'F & B'){
            $matchCar = 'F';
        }elseif($mainCatDetArr[0]['name'] == 'Hotel' || $mainCatDetArr[0]['name'] == 'Hotels'){
            $matchCar = 'A';
        }
        
        $CodeArr = $kProduct->generateProdCode($matchCar);
        if(!empty ($CodeArr)){
            $codeSplitArr = explode($matchCar, $CodeArr[0]['name']);
            $newcode = $matchCar.($codeSplitArr[1]+1);
        }else{
            $newcode = $matchCar.'100';
        }
        if(!empty ($groupArr))
        {
            echo "SUCCESS||||";
            ?>
            <div id="subcat" class="form-group <?php if($kProduct->arErrorMessages['prGroup'] !=''){ ?> has-error <?php } ?>">
                            <label class="col-md-3 control-label">Sub Category</label>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-dot-circle-o"></i>
                                    </span>
                                    <select class="form-control custom-select" name="addProdAry[prGroup]"  id="prGroup" onfocus="remove_formError(this.id,'true')">
                                            <option value="">Sub Category</option>
                                            <?php foreach ($groupArr as $subcats){ ?>
                                            <option value="<?php echo $subcats['id'];?>"><?php echo $subcats['name'];?></option>
                                           <?php } ?>

                                    </select>
                                </div>
                                <?php if($kProduct->arErrorMessages['prGroup'] !=''){ ?> 
                                <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kProduct->arErrorMessages['prGroup']; ?></span>
                                <?php } ?>
                            </div>
                        </div>
            <?php
            echo '||||'.$newcode;
        }else{
            echo 'ERROR||||';
            include( __APP_PATH_VIEW_FILES__ . "/addUser.php" );

        }
    }else{
        echo "SUCCESS||||";
        ?>
        <div id="subcat" class="form-group <?php if($kProduct->arErrorMessages['prGroup'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Sub Category</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <select class="form-control custom-select" name="addProdAry[prGroup]"  id="prGroup" onfocus="remove_formError(this.id,'true')">
                                        <option value="">Sub Category</option>
                                        
                                </select>
                            </div>
                            <?php if($kProduct->arErrorMessages['prGroup'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kProduct->arErrorMessages['prGroup']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
    <?php }
    
}

if($mode == '__GET_SUBCATEGORIES_EDIT__'){
    if($_POST['parentcat'] > 0){
        $groupArr=$kCommon->loadGroups(0,$_POST['parentcat']);
        if(!empty ($groupArr))
        {
            echo "SUCCESS||||";
            ?>
            <div id="subcat" class="form-group <?php if($kProduct->arErrorMessages['prGroup'] !=''){ ?> has-error <?php } ?>">
                            <label class="col-md-3 control-label">Sub Category</label>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-dot-circle-o"></i>
                                    </span>
                                    <select class="form-control custom-select" name="editProdAry[prGroup]"  id="prGroup" onfocus="remove_formError(this.id,'true')">
                                            <option value="">Sub Category</option>
                                            <?php foreach ($groupArr as $subcats){ ?>
                                            <option value="<?php echo $subcats['id'];?>"><?php echo $subcats['name'];?></option>
                                           <?php } ?>

                                    </select>
                                </div>
                                <?php if($kProduct->arErrorMessages['prGroup'] !=''){ ?> 
                                <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kProduct->arErrorMessages['prGroup']; ?></span>
                                <?php } ?>
                            </div>
                        </div>
            <?php
        }else{
            echo 'ERROR||||';
            include( APP_PATH_VIEW_FILES . "/addUser.php" );

        }
    }else{
        echo "SUCCESS||||";
        ?>
        <div id="subcat" class="form-group <?php if($kProduct->arErrorMessages['prGroup'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Sub Category</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <select class="form-control custom-select" name="editProdAry[prGroup]"  id="prGroup" onfocus="remove_formError(this.id,'true')">
                                        <option value="">Sub Category</option>
                                        
                                </select>
                            </div>
                            <?php if($kProduct->arErrorMessages['prGroup'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kProduct->arErrorMessages['prGroup']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
    <?php }
    
}
if($mode == '__PRODUCT_CATEGORY_SEARCH__'){
    if($_POST['parentcat'] > 0)
    {
        $groupArr=$kCommon->loadGroups(0,$_POST['parentcat']);
        if(!empty ($groupArr))
        {
            echo "SUCCESS||||";
            ?>
              <div id="subcat">
                        <div class="col-md-3 custom">
                        <div class="form-group">
                            <label for="client">Sub Categories</label>
                                <select class="form-control custom-select" name="searchArr[prGroup]"  id="prGroup" onfocus="remove_formError(this.id,'true')">
                                            <option value="0">Select</option>
                                            <?php foreach ($groupArr as $subcats){ ?>
                                            <option value="<?php echo $subcats['id'];?>"><?php echo $subcats['name'];?></option>
                                           <?php } ?>

                                    </select>
                        </div>
                    </div>
                    </div>
            
            <?php
        }
        else{
            echo 'ERROR||||';
            include( __APP_PATH_VIEW_FILES__ . "/viewProductList.php" );

        }
    }else{
        echo "SUCCESS||||";
        ?>
         
           <div id="subcat">
                <div class="col-md-3 custom">
                    <div class="form-group">
                        <label for="client">Sub Categories</label>
                        <select class="form-control custom-select" name="searchArr[prGroup]"  id="prGroup" onfocus="remove_formError(this.id,'true')">
                            <option value="">Select</option>
                        </select>
                    </div>
                </div>
            </div>
        
        
    <?php }
    
}
if($mode == '__GET_WEEKDAY_SCHEDULE_REPORT__'){
    $getAllDriverArr = $kUser->getAllDriver();
    $dayVal = $_POST['weekdayval'];
    $today = '';
    switch ($dayVal) {
            case '1':
                $today = "Monday";
                break;
            case "2":
                $today = 'Tuesday';
                break;
            case "3":
                $today = 'Wednesday';
                break;
            case "4":
                $today = 'Thursday';
                break;
            case "5":
                $today = 'Friday';
                break;
            case "6":
                $today = 'Saturday';
                break;
            case "7":
                $today = 'Sunday';
                break;
        }
        echo "SUCCESS||||";
        include( __APP_PATH_VIEW_FILES__ . "/dailyScheduleReport.php" );
}elseif($mode == '__OPEN_FORGET_FORM__'){
        echo "SUCCESS||||";
        include( __APP_PATH_FILES__ . "/forgetPassword.php" );
}elseif($mode == '__OPEN_LOGIN_FORM__'){
        echo "SUCCESS||||";
        include( __APP_PATH_FILES__ . "/customersLogin.php" );
}elseif($mode=='__FORGOT_USER__'){
    $role="3";
    $szForgotEmail=$_POST['szForgotEmail']; 
    $successFlag=false;
    if($kUser->forgotPasswordUser($szForgotEmail,$role))
    {
        echo 'SUCCESS||||';
        session_start();
        $_SESSION['successFrogetUser'] = true;
        include( __APP_PATH_FILES__ . "/customersLogin.php" );
    }
    else
    {
        echo 'ERROR||||';
        include( __APP_PATH_FILES__ . "/forgetPassword.php" );
    }
}
elseif($mode == '__GET_RUN_DATA_REPORT__')
{
    
        $searchReport=array();
    if (!empty($_POST['searchArr']['dateFrom'])) {
        $start_date = getSqlFormattedDate($_POST['searchArr']['dateFrom']);
        $searchReport['searchArr']['usStartDate'] = $start_date;
        if(!empty($_POST['searchArr']['dateTo'])){
            $searchReport['searchArr']['usEndDate'] = date('Y-m-d', strtotime($start_date . ' + ' . $_POST['searchArr']['dateTo'] . ' days'));
        }else{
            $searchReport['searchArr']['usEndDate'] = '';
        }

    } else {
        $searchReport['searchArr']['usStartDate'] = '';
        $searchReport['searchArr']['usEndDate'] = date('Y-m-d', strtotime($searchReport['searchArr']['usStartDate'] . ' + ' . $_POST['searchArr']['dateTo'] . ' days'));
    }
        
        $szSearchText=$searchReport['searchArr'];
        $searchar = $szSearchText;
        
        $userArr=$kUser->loadCustomers('','','','','',$searchReport['searchArr']);
        if(!empty ($userArr))
        {
            echo "SUCCESS||||";
            include( __APP_PATH_VIEW_FILES__ . "/runDataReport.php" );
        }
        elseif($userArr == FALSE)
        {
            echo 'ERROR||||';
            $userArr=$kUser->loadCustomers();
            include( __APP_PATH_VIEW_FILES__ . "/runDataReport.php" );

        }
        elseif($userArr == TRUE){
            echo "SUCCESS||||";
            include( __APP_PATH_VIEW_FILES__ . "/runDataReport.php" );
        }
}
elseif($mode == '__EDIT_ADMIN_USER__'){
    if($kUser->updateUser($_POST['editUserAry']))
    {
        
        echo "SUCCESS||||";
        include( __APP_PATH_VIEW_FILES__ . "/editAdminUser.php" );
        ?>
        <div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit User Confirmation</h4>
                    </div>
                    <div class="modal-body">
                        <p class="alert alert-success"><i class="fa fa-check"></i> A user has been updated successfully.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="redirect_url('<?php echo __VIEW_USER__; ?>'); return false;" class="btn dark btn-outline">Close</button>
                    </div>
                </div>
            </div>
        </div>
        
        <?php
    }
    else
    {
        echo 'ERROR||||';
        include( __APP_PATH_VIEW_FILES__ . "/editAdminUser.php" );
    }
}
?>