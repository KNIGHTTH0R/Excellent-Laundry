
<div class="col-md-12">
    
    <div class="portlet box blue">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-users"></i>View Users List
            </div>
            <div class="actions">
                <div data-toggle="buttons" class="btn-group btn-group-devided">
                   
                    <button class="btn grey btn-sm active" onclick="redirect_url('<?php echo __ADD_USER__; ?>')">
                        <i class="fa fa-plus"></i> Add New User
                    </button>
                </div>
            </div>
        </div>
        <div class="portlet-body">
            <?php
            if(!empty($AdminuserArr))
            {
            ?>
                <div class="table-responsive">
                    <table id="sample_1" class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" role="grid" aria-describedby="sample_1_info">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> Name </th>
                                <th> Email </th>
                                <th> Role </th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i=1;
                            foreach($AdminuserArr as $adminUser)
                            {
                                $arrRole = $kCommon->loadRoals($adminUser['role']);
                            ?>
                                <tr>
                                    <td> <?php echo $i++; ?> </td>
                                    <td> <?php echo $adminUser['username']; ?> </td>
                                    <td> <?php echo $adminUser['emailid']; ?> </td>
                                    <td> <?php echo $arrRole[0]['role']; ?> </td>
                                    <td>
                                        <a title="Edit" class="btn btn-outline btn-sm purple" href="javascript:void(0);" onclick="redirect_url('<?php echo __EDIT_USER__.$adminUser['id'] ?>/')">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a title="Delete" class="btn btn-outline red btn-sm black" href="javascript:void(0);" onclick="deleteAdminUser('<?php echo $adminUser['id'];?>','<?php echo $userData['userid'];?>')">
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
                <h3>No User Found.</h3>
            <?php
            }
            ?>
        </div>
    </div>
</div>
<div id="popup"></div>
