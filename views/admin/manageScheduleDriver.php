<div class="col-md-12">
    <div class="portlet box blue">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-users"></i> Schedule Drivers 
            </div>
            <div class="actions">
                <div data-toggle="buttons" class="btn-group btn-group-devided">
                    <button class="btn grey btn-sm active" onclick="redirect_url('<?php echo __SCHEDULE_DRIVER_URL__; ?>')">
                        <i class="fa fa-plus"></i> Schedule Driver
                    </button>
                </div>
            </div>
            
        </div>
        <div class="portlet-body">
            <?php
            $customerAry=$kDriver->getAllCustomersByWeekid($weekId,'','','',true);
            
          
           foreach($customerAry as $customerData)
            {
                $custid[] = $customerData['custid'];
               
            }
            $CustidAray = array_unique($custid);
            
            if(!empty ($CustidAray))
            {
            ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> Customers </th>
                                <th> Assigned Slot </th>
                                <th> Unassigned Slot </th>
                                <th> View Details</th>
                            </tr>
                        </thead>
                        <tbody>
                             <?php
                            $i=1;
                            foreach ($CustidAray as $CustidData)
                            {
                                $customerData=$kUser->loadCustomers($CustidData);
                               
                            ?>
                                <tr>
                                        <td> <?php echo $i++; ?> </td>
                                        <td>
                                            <?php 
                                                    $customerData=$kUser->loadCustomers($CustidData);
                                                    echo $customerData[0]['business_name'];
                                            ?>
                                        </td>
                                        <td> 
                                            <?php 
                                                    $assign=$kDriver->getAllCustomersByWeekid('',$CustidData,$assign='1','',true);
                                                   
                                                    if(!empty($assign))
                                                    {
                                                         echo sizeof($assign);
                                                    }
                                                    else
                                                    {
                                                        echo "0";
                                                    }
                                                    
                                            ?>
                                        </td>
                                        <td>
                                             <?php 
                                                    $unassign=$kDriver->getAllCustomersByWeekid('',$CustidData,'',$unassign='1',true);
                                                    if(!empty($unassign))
                                                    {
                                                         echo sizeof($unassign);
                                                    }
                                                    else
                                                    {
                                                        echo "0";
                                                    }
                                                    
                                            ?>
                                        </td>
                                        <td> <a title="Part Details" class="btn blue black" href="javascript:void(0);" onclick="customerWeekDaySlotDetails('<?php echo $CustidData;?>');">
                                            View Details
                                        </a></td>
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
                 <h3>No Schedule Found.</h3>
            <?php
            }
            ?>
        </div>
    </div>
</div>
<div id="popup"></div>
