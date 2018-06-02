<?php
$customerDropArr=$kUser->loadCustomers();
?>
<div class="col-md-12">
    <div class="search-page search-content-2">
        <div class="search-bar bordered">
            <form id="userSearchForm" id="userSearchForm" method="post">
                <div class="row">
                   
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <label for="Business">Business Name :</label>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                           <select class="form-control" name="searchArr[usBusinessName]" id="usBusinessName" >
                                <option value="">Select</option>
                                <?php
                                if(!empty($customerDropArr))
                                {
                                    foreach($customerDropArr as $customerDropData)
                                    {
                                    ?>
                                        <option value="<?php echo $customerDropData['business_name']; ?>" <?php if($_POST['searchArr']['usBusinessName']==$customerDropData['business_name']) { ?> selected="selected" <?php } ?>><?php echo $customerDropData['business_name']; ?></option>
                                    <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-4 text-right">
                    <div class="form-group">
                        <button class="btn blue uppercase bold" type="button" onclick="getUserSearch();"><i class="fa fa-search"></i> Search</button>
                        &nbsp;
                        <!--<button class="btn red uppercase bold" type="button" onclick="resetClientSearch();"><i class="fa fa-refresh"></i>Reset</button>-->
                    </div>
                </div>
                   
                  
                    
                </div>
               
                
                
            </form>
        </div>
    </div>
    <div class="portlet box blue">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-users"></i>View Customers List
            </div>
             <div class="actions">
                <div data-toggle="buttons" class="btn-group btn-group-devided">
                    <button class="btn grey btn-sm active" onclick="ImportCustomerModal();">
                        <i class="fa fa-plus"></i> Import Customers
                    </button>
                    <button class="btn grey btn-sm active" onclick="redirect_url('<?php echo __ADD_NEW_CUSTOMER_URL__; ?>')">
                        <i class="fa fa-plus"></i> Add New Customer
                    </button>
                </div>
            </div>
        </div>
        
        <div class="portlet-body">
            <?php
            if(!empty($userArr))
            {
            ?>
                <div class="table-responsive customer-list">
                    <table id="sample_1" class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" role="grid" aria-describedby="sample_1_info">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th>Customer Code</th>
                                <th>Unique Code</th>
                                 <th <?php if($sortBy=='business_name' && $sortValue=='DESC'){?>class="sorting_asc" onclick="sortcustomersListing('business_name','ASC');" <?php }elseif($sortBy=='business_name' && $sortValue=='ASC') {?>class="sorting_desc" onclick="sortcustomersListing('business_name','DESC');" <?php }else{?>class="sorting" onclick="sortcustomersListing('business_name','ASC');"<?php }?>>
                                    Business Name
                                   </th>
                                <th> Contact Name </th>
                                <th> Mobile No. </th>
                                <th> Contact Email </th>
                                <th> Contract Start On </th>
                                <th> Contract End On </th>
                                <th>Action</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i=1;
                            foreach($userArr as $userData)
                            {
                                
                            ?>
                                <tr>
                                    <td> <?php echo $i++; ?> </td>
                                    <td>C-<?php echo $userData['id'];?></td>
                                    <td><?php echo $userData['uniquecode'];?></td>
                                    <td> 
                                        <a title="Part Details" class="btn blue black" href="javascript:void(0);" onclick="customerDetails('<?php echo $userData['id'];?>');">
                                            <?php echo $userData['business_name']; ?> 
                                        </a>
                                    </td>
                                    <td> <?php echo $userData['contact_name']; ?> </td>
                                    <td class="action-col"> <?php echo $userData['mobileno']; ?> </td>
                                    <td> <?php echo $userData['contact_email']; ?> </td>
                                    <td> <?php echo ($userData['contract_start'] != '0000-00-00 00:00:00'?date('d/m/Y',  strtotime($userData['contract_start'])):"NA"); ?> </td>
                                    <td> <?php echo ($userData['contract_end'] != '0000-00-00 00:00:00'?date('d/m/Y',  strtotime($userData['contract_end'])):"NA"); ?> </td>
                                   
                                    <td class="action-col action-style"><a title="Edit" class="btn btn-outline btn-sm purple" href="javascript:void(0);" onclick="redirect_url('<?php echo __EDIT_CUSTOMER_URL__;?><?php echo $userData['id'];?>/')">
                                              <i class="fa fa-edit"></i>
                                          </a>
                                        <?php
                                        if(!in_array($userData['id'],$ordCustlist)) {
                                            //if ($userData['deletable'] == '1') {
                                                ?>
                                                <a title="Delete" class="btn btn-outline red btn-sm black"
                                                   href="javascript:void(0);"
                                                   onclick="deleteCustomer('<?php echo $userData['id']; ?>','<?php echo $userData['userid']; ?>')">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            <?php //}
                                        }?>
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
                <h3>No Customer Found.</h3>
            <?php
            }
            ?>
        </div>
    </div>
</div>
<div id="popup"></div>
<script type="text/javascript">
    $('.date').pickmeup({
        format: 'm/d/Y'
    });
    $('.date1').pickmeup({
        format: 'm/d/Y'
    });

</script>
