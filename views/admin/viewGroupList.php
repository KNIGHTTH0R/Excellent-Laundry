<div class="col-md-12">
    <div class="portlet box blue">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-users"></i>View Categories
            </div>
            <div class="actions">
                <div data-toggle="buttons" class="btn-group btn-group-devided">
                    <button class="btn grey btn-sm active" onclick="redirect_url('<?php echo __ADD_NEW_GROUP_URL__; ?>')">
                        <i class="fa fa-plus"></i> Add New Category
                    </button>
                </div>
            </div>
        </div>
        <div class="portlet-body">
            <?php
            if(!empty($groupArr))
            {
            ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> Category Name </th>
                                <th> Description </th>
                                <th> Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i=1;
                            foreach($groupArr as $group)
                            {
                                $groupSubAry=$kCommon->loadGroups(0,$group['id']);
                            ?>
                                <tr>
                                    <td> <?php echo $i++; ?> </td>
                                    <td> 
                                        <?php echo $group['name'];?> 
                                    </td>
                                    <td> <?php echo $group['description']; ?> </td>
                                    <td> 
                                        <a title="edit" class="btn btn-outline btn-sm purple" href="javascript:void(0);" onclick="redirect_url('<?php echo __EDIT_GROUP_URL__ ;?><?php echo $group['id'];?>/<?php echo ($_GET['groupid']>0?$_GET['groupid']:'0');?>/')">
                                              <i class="fa fa-edit"></i>
                                          </a>
                                        <?php if(!isset ($_GET['groupid'])){?>
                                        <a title="Add Subcatgory" class="btn btn-outline btn-sm green" href="javascript:void(0);" onclick="redirect_url('<?php echo __ADD_NEW_GROUP_URL__.$group['id'] ;?>/')">
                                              <i class="fa fa-sitemap"></i>
                                          </a>
                                        <a title="Manage Products" class="btn btn-outline btn-sm blue" href="javascript:void(0);" onclick="redirect_url('<?php echo __VIEW_PRODUCTS_LIST_URL__.$group['id'] ;?>/')">
                                              <i class="fa fa-eye"></i>
                                          </a>
                                        <?php } ?>
                                        <a title="Delete" class="btn btn-outline red btn-sm black" href="javascript:void(0);" onclick="deleteGroup('<?php echo $group['id'];?>')">
                                              <i class="fa fa-trash"></i>
                                          </a>
                                        </td>
                                </tr>
                                <?php
                                foreach($groupSubAry as $groupSubData)
                                {
                                    
                                ?>
                                    <tr>
                                    <td> <?php   ?> </td>
                                    <td> 
                                        <?php 
                                        
                                            echo "&nbsp &nbsp--";
                                            echo $groupSubData['name'];
                                      
                                        
                                        ?> </td>
                                    <td> <?php echo $groupSubData['description']; ?> </td>
                                    <td> 
                                        <a title="edit" class="btn btn-outline btn-sm purple" href="javascript:void(0);" onclick="redirect_url('<?php echo __EDIT_GROUP_URL__ ;?><?php echo $groupSubData['id'];?>/<?php echo $group['id'];?>/')">
                                              <i class="fa fa-edit"></i>
                                        </a>
                                         <a title="Manage Products" class="btn btn-outline btn-sm blue" href="javascript:void(0);" onclick="redirect_url('<?php echo __VIEW_PRODUCTS_LIST_URL__.$group['id'].'/'.$groupSubData['id'] ;?>/')">
                                              <i class="fa fa-eye"></i>
                                          </a>
                                        <a title="Delete" class="btn btn-outline red btn-sm black" href="javascript:void(0);" onclick="deleteGroup('<?php echo $groupSubData['id'];?>')">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                                
                                }
                                
                                ?>
                                
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
                <h3>No Category Found.</h3>
            <?php
            }
            ?>
        </div>
    </div>
</div>

