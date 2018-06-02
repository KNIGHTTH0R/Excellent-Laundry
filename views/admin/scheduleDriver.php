
<div class="col-md-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-equalizer font-grey-sunglo"></i>
                <span class="caption-subject font-grey-sunglo bold uppercase">Schedule Driver</span>
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
             <form name="weekDaySchedule" id="weekDaySchedule" method="post" class="form-horizontal" >
                <div class="form-group <?php if($kDriver->arErrorMessages['customerId '] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Weekday</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <select class="form-control custom-select" name="addWeekAry[weekId]"  id="weekId" onchange="getWeekId(this.value);" onfocus="remove_formError(this.id,'true')">
                                        <option value="">Select</option>
                                        <?php 
                                        $weekDaysAry=$kUser->getAllWeekDays();
                                        if(!empty($weekDaysAry))
                                        {
                                                foreach($weekDaysAry as $weekDays)
                                                {
                                                        $selected = ($weekDays['id'] == $_POST['addWeekAry']['weekId'] ? 'selected="selected"' : '');	
                                                        echo '<option value="'.$weekDays['id'].'" '.$selected.'>'.$weekDays['day'].'</option>';
                                                }
                                        }
                                        ?>
                                </select>
                            </div>
                            <?php if($kDriver->arErrorMessages['customerId'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kDriver->arErrorMessages['customerId']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                 </form>
           
             <form name="addCustomerSchedule" id="addCustomerSchedule" method="post" class="form-horizontal" >
            
            <?php 
            
           if(!empty($weekId))
           {
                 $i='0';
                $customerAry=$kDriver->getAllCustomersByWeekid($weekId,'','','',true);
                foreach ($customerAry as $customerData)
                {
                    $i++;
                     $customerName=$kUser->loadCustomers($customerData['custid']);
                     $weekIdName='weekId'.$i;
                     
                     
                    ?>
            
                    <div class="form-group <?php if($kDriver->arErrorMessages[$weekIdName] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label"><?php echo $customerName[0]['business_name'];?> </label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <select class="form-control custom-select" name="addWeekdayAry[<?php echo $weekIdName;?>]"  id="<?php echo $weekIdName;?>" onfocus="remove_formError(this.id,'true')">
                                        <option value="">Select</option>
                                        <?php 
                                        $DriverAry=$kUser->getAllDriver();
                                        if(!empty($DriverAry))
                                        {
                                                foreach($DriverAry as $DriverData)
                                                {
                                                        $selected = ($DriverData['id'] == $_POST['addWeekdayAry']['weekId'] ? 'selected="selected"' : '');	
                                                        echo '<option value="'.$DriverData['id'].'" '.$selected.'>'.$DriverData['name'].'</option>';
                                                }
                                        }
                                        ?>
                                </select>
                            </div>
                            <?php if($kDriver->arErrorMessages[$weekIdName] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kDriver->arErrorMessages[$weekIdName]; ?></span>
                            <?php } ?>
                        </div>
                        <input type="hidden" name="weekCustomerId[<?php echo $weekIdName;?>]" id="<?php echo $weekIdName;?>" value="<?php echo $customerData['custid'];?>"></input>
                    </div>
                    
            <?php   
            }
                
           }
            
            ?>
                 <input type="hidden" name="addWeekdayAry['totalCount']" value="<?php echo $i; ?>">
                 <input type="hidden" name="addWeekdayAry[weekId]" value="<?php echo $weekId; ?>">
                  <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-4">
                            <button class="btn blue" onclick="addScheduleDriver(); return false;">Save</button>
                        </div>
                    </div>
                </div>
            </form>
            <!-- END FORM-->
        </div>
    </div>
</div>

