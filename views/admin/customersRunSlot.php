<div class="col-md-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-equalizer font-grey-sunglo"></i>
                <span class="caption-subject font-grey-sunglo bold uppercase">Customer Run Slot</span>
            </div>
        </div>
        <div class="portlet-body form">
            <form name="addRunSlotForm" id="addRunSlotForm" method="post" class="form-horizontal" >
                
                <div class="form-group <?php if($kDriver->arErrorMessages['customerId'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Customer</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <select class="form-control custom-select" name="addRunSlotAry[customerId]"  id="customerId" onfocus="remove_formError(this.id,'true')">
                                        <option value="">Customer</option>
                                        <?php 
                                        $CustomersAry = $kUser->loadCustomers();
                                        if(!empty($CustomersAry))
                                        {
                                                foreach($CustomersAry as $Customers)
                                                {
                                                        $selected = ($Customers['id'] == $_POST['addRunSlotAry']['customerId'] ? 'selected="selected"' : '');	
                                                        echo '<option value="'.$Customers['id'].'" '.$selected.'>'.$Customers['contact_name'].'</option>';
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
                
                <?php
               
                foreach($weekDaysAry as $weekDays)
                {
                  $weekDay=$weekDays['day'];
                  $weekId=$weekDays['id'];
                 ?>
                
                <div class="form-body">  
                    <div class="form-group <?php if($kDriver->arErrorMessages[$weekDay] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-2 control-label"><?php echo $weekDays['day'];?></label>
                        <label class="col-md-2 control-label"><input type="checkbox" <?php if ($_POST['day'][$weekDay] == $weekDays['id']) echo "checked='checked'"; ?> id='<?php echo $weekDays['day']?>' name="day[<?php echo $weekDays['day'];?>]" value='<?php echo $weekDays['id'] ;?>'></input></label>
                        <label class="col-md-2 control-label">Run time slot</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <input autocomplete="off" type="text" class="form-control" name="addRunSlotAry[<?php echo $weekDay;?>]" id="<?php echo $weekDay;?>" onfocus="remove_formError(this.id,'true')"  value='<?php echo $_POST['addRunSlotAry'][$weekDay]; ?>'> 
                            </div>
                            <?php if($kDriver->arErrorMessages[$weekDay] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kDriver->arErrorMessages[$weekDay]; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                   
                   
              <?php }
                ?>
                
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-4">
                            <button class="btn blue" onclick="addRunSlot(); return false;">Save</button>
                        </div>
                    </div>
                </div>
            </form>
    </div>
</div>

