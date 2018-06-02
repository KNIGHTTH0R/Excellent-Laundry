<?php
$customerDropArr=$kUser->loadCustomers();
?>
<div class="col-md-12">
    <div class="search-page search-content-2">
        <div class="search-bar bordered">
            <form id="runSlotSearchForm" id="runSlotSearchForm" method="post">
            <div class="row">
                <div class="col-md-2">
                        
                            <label for="Business">Contact Name :</label>
                          
                        
                    </div>
                <div class="col-md-4">
                        <div class="form-group">
                            <select class="form-control" name="searchArr[usCustomerName]" id="usCustomerName" >
                                <option value="">Select</option>
                                <?php
                                if(!empty($customerDropArr))
                                {
                                    foreach($customerDropArr as $customerDropData)
                                    {
                                    ?>
                                        <option value="<?php echo $customerDropData['contact_name']; ?>" <?php if($_POST['searchArr']['usCustomerName']==$customerDropData['contact_name']) { ?> selected="selected" <?php } ?>><?php echo $customerDropData['business_name']; ?></option>
                                    <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    
                    </div>
                <div class="col-md-4 text-right">
                    <div class="form-group">
                        <button class="btn blue uppercase bold" type="button" onclick="searchRunSlot();"><i class="fa fa-search"></i> Search</button>
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
                <i class="fa fa-users"></i>Drivers Run Sheet
            </div>
           
        </div>
        <div class="portlet-body">
            <?php
            
                     
            if(!empty($userArr))
            {
            ?>
                <p>Please enter run slot numbers in weekdays columns</p>
                <div class="table-responsive customer-run-slot">
                   <table id="sample_1" class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" role="grid" aria-describedby="sample_1_info">
                        <thead>
                        <tr>
                            <th> # </th>
                            <th>Customer Name</th>
                            <th> Mon</th>
                            <th>Mon Drivers</th>
                            <th> Tues</th>
                            <th>Tues Drivers</th>
                            <th> Wed</th>
                            <th>Wed Drivers</th>
                            <th> Thurs</th>
                            <th>Thurs Drivers</th>
                            <th> Fri</th>
                            <th>Fri Drivers</th>
                            <th> Sat</th>
                            <th>Sat Drivers</th>
                            <th> Sun</th>
                            <th>Sun Drivers</th>
                            <th> Edit </th>
                            <th> Delete </th>

                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            $driversDropDown = '';
                            $DriverAry=$kUser->getAllDriver();
                            
                            $i=1;
                            foreach($userArr as $userData)
                            {
                                $errorMsg='';
                                $msg='';
                                $customerAry=$kDriver->getAllCustomersByWeekid(0,$userData['id'],'','',true);
                                if(empty($customerAry))
                                {
                                    $msg="no slots entered";
                                    $errorMsg="($msg)";
                                }
                                
                                
                            ?>
                                <tr id="detail_<?php echo $userData['id'] ;?>" class="detail_hide">
                                    <td> <?php echo $i ?> </td>
                                   <td>
                                        <?php echo $userData['business_name']; ?></br><span class="empty"><?php echo $errorMsg; ?> </span></td>
                                   <td>
                                       <?php
                                        $MonRunSlot=$kDriver->getRunSlotcustIdWeekId($userData['id'],'1');
                                        if($MonRunSlot['0']['slot']!=='0')
                                        {
                                            echo $MonRunSlot['0']['slot'];
                                        }
                                        
                                       ?>
                                   </td>
                                   <td>
                                       <?php 
                                       $MonRunSlot=$kDriver->getRunSlotcustIdWeekId($userData['id'],'1');
                                       if(!empty ($MonRunSlot)){
                                           $driverdetMon = $kUser->loadDriver($MonRunSlot[0]['driverid']);
                                           if(!empty ($driverdetMon)){
                                               echo $driverdetMon['name'];
                                           }
                                       }
                                       ?>
                                   </td>
                                    <td>
                                        <?php
                                       $TusRunSlot=$kDriver->getRunSlotcustIdWeekId($userData['id'],'2');
                                       if($TusRunSlot['0']['slot']!=='0')
                                        {
                                             echo $TusRunSlot['0']['slot'];
                                        }
                                      
                                       ?>
                                    </td>
                                    <td>
                                        <?php 
                                       $TuesRunSlot=$kDriver->getRunSlotcustIdWeekId($userData['id'],'2');
                                       if(!empty ($TuesRunSlot)){
                                           $driverdetTues = $kUser->loadDriver($TuesRunSlot[0]['driverid']);
                                           if(!empty ($driverdetTues)){
                                               echo $driverdetTues['name'];
                                           }
                                       }
                                       ?>
                                    </td>
                                    <td>
                                        <?php
                                       $WedRunSlot=$kDriver->getRunSlotcustIdWeekId($userData['id'],'3');
                                       if($WedRunSlot['0']['slot']!=='0')
                                        {
                                             echo $WedRunSlot['0']['slot'];
                                        }
                                       
                                       ?>
                                    </td>
                                    <td>
                                        <?php 
                                       $WedRunSlot=$kDriver->getRunSlotcustIdWeekId($userData['id'],'3');
                                       if(!empty ($WedRunSlot)){
                                           $driverdetWed = $kUser->loadDriver($WedRunSlot[0]['driverid']);
                                           if(!empty ($driverdetWed)){
                                               echo $driverdetWed['name'];
                                           }
                                       }
                                       ?>
                                    </td>
                                    <td>
                                        <?php
                                       $ThursRunSlot=$kDriver->getRunSlotcustIdWeekId($userData['id'],'4');
                                       if($ThursRunSlot['0']['slot']!=='0')
                                        {
                                             echo $ThursRunSlot['0']['slot'];
                                        }
                                      
                                       ?>
                                    </td>
                                    <td>
                                        <?php 
                                       $ThursRunSlot=$kDriver->getRunSlotcustIdWeekId($userData['id'],'4');
                                       if(!empty ($ThursRunSlot)){
                                           $driverdetThurs = $kUser->loadDriver($ThursRunSlot[0]['driverid']);
                                           if(!empty ($driverdetThurs)){
                                               echo $driverdetThurs['name'];
                                           }
                                       }
                                       ?>
                                    </td>
                                    <td>
                                        <?php
                                       $FriRunSlot=$kDriver->getRunSlotcustIdWeekId($userData['id'],'5');
                                       if($FriRunSlot['0']['slot']!=='0')
                                        {
                                             echo $FriRunSlot['0']['slot'];
                                        }
                                      
                                       ?>
                                    </td>
                                    <td>
                                        <?php 
                                       $FriRunSlot=$kDriver->getRunSlotcustIdWeekId($userData['id'],'5');
                                       if(!empty ($FriRunSlot)){
                                           $driverdetFri = $kUser->loadDriver($FriRunSlot[0]['driverid']);
                                           if(!empty ($driverdetFri)){
                                               echo $driverdetFri['name'];
                                           }
                                       }
                                       ?>
                                    </td>
                                    <td>
                                        <?php
                                       $SatRunSlot=$kDriver->getRunSlotcustIdWeekId($userData['id'],'6');
                                       if($SatRunSlot['0']['slot']!=='0')
                                        {
                                             echo $SatRunSlot['0']['slot'];
                                        }
                                       ?>
                                    </td>
                                    <td>
                                        <?php 
                                       $SatRunSlot=$kDriver->getRunSlotcustIdWeekId($userData['id'],'6');
                                       if(!empty ($SatRunSlot)){
                                           $driverdetSat = $kUser->loadDriver($SatRunSlot[0]['driverid']);
                                           if(!empty ($driverdetSat)){
                                               echo $driverdetSat['name'];
                                           }
                                       }
                                       ?>
                                    </td>
                                    <td>
                                        <?php
                                       $SunRunSlot=$kDriver->getRunSlotcustIdWeekId($userData['id'],'7');
                                       if($SunRunSlot['0']['slot']!=='0')
                                        {
                                             echo $SunRunSlot['0']['slot'];
                                        }
                                       ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $SunRunSlot=$kDriver->getRunSlotcustIdWeekId($userData['id'],'7');
                                        if(!empty ($SunRunSlot)){
                                           $driverdetSun = $kUser->loadDriver($SunRunSlot[0]['driverid']);
                                           if(!empty ($driverdetSun)){
                                               echo $driverdetSun['name'];
                                           }
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <a title="Edit" class="btn btn-outline btn-sm purple" href="javascript:void(0);" onclick="editSlot('<?php echo $userData['id'];?>')">
                                              Edit
                                          </a>
                                        
                                    </td>
                                    <td>
                                        <a title="Delete" class="btn btn-outline red btn-sm black" href="javascript:void(0);" onclick="deleteSlot('<?php echo $userData['id'];?>')">
                                              <i class="fa fa-trash"></i>
                                          </a>
                                    </td>
                                    
                                    
                                    
                                </tr>
                                <tr id="input_<?php echo $userData['id'] ;?>" class="custom_hide">
                                    <td> <?php echo $i; ?> </td>
                                   <td> <?php echo $userData['business_name'];
                                   
                                   ?> </td>
                                   <td>
                                       <?php
                                        $MonRunSlot=$kDriver->getRunSlotcustIdWeekId($userData['id'],'1');
                                       ?>
                                       <input class="table_input" type="number" min="1" max="999" id="mon_<?php echo $userData['id'];?>" name="mon_<?php echo $userData['id'];?>" value="<?php echo $MonRunSlot['0']['slot'] ;?>"/>
                                   </td>
                                   <td>
                                        <select name="mon_driver_<?php echo $userData['id'];?>" class="day-driver" id="mon-driver-<?php echo $userData['id'];?>">
                                            <option value="">Select</option>
                                            <?php
                                                $MonRunSlot=$kDriver->getRunSlotcustIdWeekId($userData['id'],'1');
                                                if(!empty($DriverAry))
                                                {
                                                    foreach($DriverAry as $DriverData)
                                                    {
                                                        $selected = '';
                                                        if($MonRunSlot[0]['driverid'] == $DriverData['id']){
                                                            $selected = 'selected="selected"';
                                                        }
                                                        echo '<option value="'.$DriverData['id'].'" '.$selected.'>'.$DriverData['name'].'</option>';
                                                    }
                                                }?>
                                        </select>
                                    </td>
                                    <td>
                                        <?php
                                       $TusRunSlot=$kDriver->getRunSlotcustIdWeekId($userData['id'],'2');
                                      
                                       ?>
                                        <input class="table_input"  type="number" min="1" max="999" id="tus_<?php echo $userData['id'];?>" name="tus_<?php echo $userData['id'];?>" value="<?php echo $TusRunSlot['0']['slot'];?>"/>
                                    </td>
                                    <td>
                                        <select name="tues_driver_<?php echo $userData['id'];?>" class="day-driver" id="tues-driver-<?php echo $userData['id'];?>">
                                            <option value="">Select</option>
                                            <?php
                                                $TuesRunSlot=$kDriver->getRunSlotcustIdWeekId($userData['id'],'2');
                                                if(!empty($DriverAry))
                                                {
                                                    foreach($DriverAry as $DriverData)
                                                    {
                                                        $selected = '';
                                                        if($TuesRunSlot[0]['driverid'] == $DriverData['id']){
                                                            $selected = 'selected="selected"';
                                                        }
                                                        echo '<option value="'.$DriverData['id'].'" '.$selected.'>'.$DriverData['name'].'</option>';
                                                    }
                                                }?>
                                        </select>
                                    </td>
                                    <td>
                                        <?php
                                       $WedRunSlot=$kDriver->getRunSlotcustIdWeekId($userData['id'],'3');
                                       
                                       ?>
                                       <input class="table_input"  type="number" min="1" max="999" id="wed_<?php echo $userData['id'];?>" name="wed_<?php echo $userData['id'];?>" value="<?php echo $WedRunSlot['0']['slot'];?>"/>
                                    </td>
                                    <td>
                                        <select name="wed_driver_<?php echo $userData['id'];?>" class="day-driver" id="wed-driver-<?php echo $userData['id'];?>">
                                            <option value="">Select</option>
                                            <?php
                                                $WedRunSlot=$kDriver->getRunSlotcustIdWeekId($userData['id'],'3');
                                                if(!empty($DriverAry))
                                                {
                                                    foreach($DriverAry as $DriverData)
                                                    {
                                                        $selected = '';
                                                        if($WedRunSlot[0]['driverid'] == $DriverData['id']){
                                                            $selected = 'selected="selected"';
                                                        }
                                                        echo '<option value="'.$DriverData['id'].'" '.$selected.'>'.$DriverData['name'].'</option>';
                                                    }
                                                }?>
                                        </select>
                                    </td>
                                    <td>
                                         <?php
                                       $ThursRunSlot=$kDriver->getRunSlotcustIdWeekId($userData['id'],'4');
                                       
                                       ?>
                                       <input class="table_input"  type="number" min="1" max="999" id="thurs_<?php echo $userData['id'];?>" name="thurs_<?php echo $userData['id'];?>" value="<?php echo $ThursRunSlot['0']['slot']; ?>"/>
                                    </td>
                                    <td>
                                        <select name="thurs_driver_<?php echo $userData['id'];?>" class="day-driver" id="thurs-driver-<?php echo $userData['id'];?>">
                                            <option value="">Select</option>
                                            <?php
                                                $ThursRunSlot=$kDriver->getRunSlotcustIdWeekId($userData['id'],'4');
                                                if(!empty($DriverAry))
                                                {
                                                    foreach($DriverAry as $DriverData)
                                                    {
                                                        $selected = '';
                                                        if($ThursRunSlot[0]['driverid'] == $DriverData['id']){
                                                            $selected = 'selected="selected"';
                                                        }
                                                        echo '<option value="'.$DriverData['id'].'" '.$selected.'>'.$DriverData['name'].'</option>';
                                                    }
                                                }?>
                                        </select>
                                    </td>
                                    <td>
                                         <?php
                                            $FriRunSlot=$kDriver->getRunSlotcustIdWeekId($userData['id'],'5');
                                       
                                       ?>
                                       <input class="table_input"  type="number" min="1" max="999" id="fri_<?php echo $userData['id'];?>" name="fri_<?php echo $userData['id'];?>" value="<?php echo $FriRunSlot['0']['slot']?>"/>
                                    </td>
                                    <td>
                                        <select name="fri_driver_<?php echo $userData['id'];?>" class="day-driver" id="fri-driver-<?php echo $userData['id'];?>">
                                            <option value="">Select</option>
                                            <?php
                                                $FriRunSlot=$kDriver->getRunSlotcustIdWeekId($userData['id'],'5');
                                                if(!empty($DriverAry))
                                                {
                                                    foreach($DriverAry as $DriverData)
                                                    {
                                                        $selected = '';
                                                        if($FriRunSlot[0]['driverid'] == $DriverData['id']){
                                                            $selected = 'selected="selected"';
                                                        }
                                                        echo '<option value="'.$DriverData['id'].'" '.$selected.'>'.$DriverData['name'].'</option>';
                                                    }
                                                }?>
                                        </select>
                                    </td>
                                    <td>
                                        <?php
                                       $satRunSlot=$kDriver->getRunSlotcustIdWeekId($userData['id'],'6');
                                      
                                       ?>
                                       <input class="table_input"  type="number" min="1" max="999" id="sat_<?php echo $userData['id'];?>" name="sat_<?php echo $userData['id'];?>" value="<?php  echo $satRunSlot['0']['slot'];?>"/>
                                    </td>
                                    <td>
                                        <select name="sat_driver_<?php echo $userData['id'];?>" class="day-driver" id="sat-driver-<?php echo $userData['id'];?>">
                                            <option value="">Select</option>
                                            <?php
                                                $SatRunSlot=$kDriver->getRunSlotcustIdWeekId($userData['id'],'6');
                                                if(!empty($DriverAry))
                                                {
                                                    foreach($DriverAry as $DriverData)
                                                    {
                                                        $selected = '';
                                                        if($SatRunSlot[0]['driverid'] == $DriverData['id']){
                                                            $selected = 'selected="selected"';
                                                        }
                                                        echo '<option value="'.$DriverData['id'].'" '.$selected.'>'.$DriverData['name'].'</option>';
                                                    }
                                                }?>
                                        </select>
                                    </td>
                                    <td>
                                        <?php
                                       $sunRunSlot=$kDriver->getRunSlotcustIdWeekId($userData['id'],'7');
                                      
                                       ?>
                                       <input class="table_input"  type="number" min="1" max="999" id="sun_<?php echo $userData['id'];?>" name="sun_<?php echo $userData['id'];?>" value="<?php  echo $sunRunSlot['0']['slot'];?>"/>
                                    </td>
                                    <td>
                                        <select name="sun_driver_<?php echo $userData['id'];?>" class="day-driver" id="sun-driver-<?php echo $userData['id'];?>">
                                            <option value="">Select</option>
                                            <?php
                                                $SunRunSlot=$kDriver->getRunSlotcustIdWeekId($userData['id'],'7');
                                                if(!empty($DriverAry))
                                                {
                                                    foreach($DriverAry as $DriverData)
                                                    {
                                                        $selected = '';
                                                        if($SunRunSlot[0]['driverid'] == $DriverData['id']){
                                                            $selected = 'selected="selected"';
                                                        }
                                                        echo '<option value="'.$DriverData['id'].'" '.$selected.'>'.$DriverData['name'].'</option>';
                                                    }
                                                }?>
                                        </select>
                                    </td>
                                    <td>
                                        <a title="Save" class="btn btn-outline btn-sm purple" href="javascript:void(0);" onclick="saveSlot('<?php echo $userData['id'];?>')">
                                            Save
                                        </a>
                                        
                                    </td>
                                    <td>
                                        <a title="Delete" class="btn btn-outline red btn-sm black" href="javascript:void(0);" onclick="deleteSlot('<?php echo $userData['id'];?>')">
                                              <i class="fa fa-trash"></i>
                                          </a>
                                    </td>
                                </tr>
                                
                            <?php
                              $i++;
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
                <h3>No Customer Run Slot Summary  Found.</h3>
            <?php
            }
            ?>
        </div>
    </div>
</div>
<div id="popup"></div>
