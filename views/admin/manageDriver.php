<div class="col-md-12">
    <div class="portlet box blue">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-users"></i>Manage Drivers 
            </div>
            <div class="actions">
                <div data-toggle="buttons" class="btn-group btn-group-devided">
                    <button class="btn grey btn-sm active" onclick="redirect_url('<?php echo __ADD_NEW_DRIVER_URL__; ?>')">
                        <i class="fa fa-plus"></i> Add New Driver
                    </button>
                </div>
            </div>
        </div>
        <div class="portlet-body">
            <?php
            $driverAry = $kUser->getAllDriver();
            if(!empty ($driverAry))
            {
            ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th> # </th>
                            <th> Driver Code </th>
                            <th> Driver Details </th>
                            <th> Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i=1;
                            foreach ($driverAry as $driverData)
                            {
                            ?>
                                 <tr>
                                        <td> <?php echo $i++; ?> </td>
                                        <td>
                                            <?php echo $driverData['name'];?>
                                        </td>
                                        <td> 
                                            <?php echo $driverData['detail'];?>
                                        </td>
                                        <td>
                                            <a title="Edit" class="btn btn-outline btn-sm purple" href="javascript:void(0);" onclick="redirect_url('<?php echo __EDIT_DRIVER_URL__;?><?php echo $driverData['id'];?>/')">
                                              <i class="fa fa-edit"></i>
                                          </a>
                                          <a title="Delete" class="btn btn-outline red btn-sm black" href="javascript:void(0);" onclick="deleteDriver('<?php echo $driverData['id'];?>')">
                                              <i class="fa fa-trash"></i>
                                          </a>
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
                 <h3>No Driver Found.</h3>
            <?php
            }
            ?>
        </div>
    </div>
</div>

