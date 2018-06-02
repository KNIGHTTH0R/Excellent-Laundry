
<div class="col-md-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-equalizer font-grey-sunglo"></i>
                <span class="caption-subject font-grey-sunglo bold uppercase">Add New Customer</span>
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <form name="addNewUserForm" id="addNewUserForm" method="post" class="form-horizontal" >
                <div class="form-body">
                    <div class="form-group <?php if($kUser->arErrorMessages['usUniqueCode'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Unique Code</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <input autocomplete="off" type="text" class="form-control" name="addUserAry[usUniqueCode]" id='usUniqueCode' onfocus="remove_formError(this.id,'true')" placeholder="Unique Code" value='<?php echo $_POST['addUserAry']['usUniqueCode']; ?>'> 
                            </div>
                            <?php if($kUser->arErrorMessages['usUniqueCode'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usUniqueCode']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group <?php if($kUser->arErrorMessages['usBusinessName'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Business Name</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <input autocomplete="off" type="text" class="form-control" name="addUserAry[usBusinessName]" id='usBusinessName' onfocus="remove_formError(this.id,'true')" placeholder="Business Name" value='<?php echo $_POST['addUserAry']['usBusinessName']; ?>'> 
                            </div>
                            <?php if($kUser->arErrorMessages['usBusinessName'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usBusinessName']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group <?php if($kUser->arErrorMessages['usContactName'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Contact Name</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <input autocomplete="off" type="text" class="form-control" name="addUserAry[usContactName]" id='usContactName' onfocus="remove_formError(this.id,'true')" placeholder="Contact Name" value='<?php echo $_POST['addUserAry']['usContactName']; ?>'> 
                            </div>
                            <?php if($kUser->arErrorMessages['usContactName'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usContactName']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group <?php if($kUser->arErrorMessages['usphone'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Phone Number</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <input autocomplete="off" type="text" class="form-control" name="addUserAry[usphone]" id='usphone' onfocus="remove_formError(this.id,'true')" placeholder="Phone Number" value='<?php echo $_POST['addUserAry']['usphone']; ?>'> 
                            </div>
                            <?php if($kUser->arErrorMessages['usphone'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usphone']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group <?php if($kUser->arErrorMessages['usmobile'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Mobile Number</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <input autocomplete="off" type="text" class="form-control" name="addUserAry[usmobile]" id='usmobile' onfocus="remove_formError(this.id,'true')" placeholder="Mobile Number" value='<?php echo $_POST['addUserAry']['usmobile']; ?>'> 
                            </div>
                            <?php if($kUser->arErrorMessages['usmobile'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usmobile']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group <?php if($kUser->arErrorMessages['usEmail'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Contact Email</label>
                        <label id="reg-label" class="reg-label label label-info">Registration Email</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <input type="email" class="form-control" name="addUserAry[usEmail]" id='usEmail' onfocus="remove_formError(this.id,'true')" placeholder="Contact Email Address" value='<?php echo $_POST['addUserAry']['usEmail']; ?>'>
                            </div>
                            <?php if($kUser->arErrorMessages['usEmail'] !=''){ ?> 
                                <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usEmail']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group <?php if($kUser->arErrorMessages['usBusinessEmail'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Business Email</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <input type="email" class="form-control" name="addUserAry[usBusinessEmail]" id='usBusinessEmail' onfocus="remove_formError(this.id,'true')" placeholder="Business Email Address" value='<?php echo $_POST['addUserAry']['usBusinessEmail']; ?>'>
                            </div>
                            <?php if($kUser->arErrorMessages['usBusinessEmail'] !=''){ ?> 
                                <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usBusinessEmail']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group <?php if($kUser->arErrorMessages['usStAddress1'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Street Address 1</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <input type="text" class="form-control" name="addUserAry[usStAddress1]" id='usStAddress1' onfocus="remove_formError(this.id,'true')" placeholder="Street Address 1" value='<?php echo $_POST['addUserAry']['usStAddress1']; ?>'>
                            </div>
                            <?php if($kUser->arErrorMessages['usStAddress1'] !=''){ ?> 
                                <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usStAddress1']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group <?php if($kUser->arErrorMessages['usStAddress2'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Street Address 2</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <input type="text" class="form-control" name="addUserAry[usStAddress2]" id='usStAddress1' onfocus="remove_formError(this.id,'true')" placeholder="Street Address 2" value='<?php echo $_POST['addUserAry']['usStAddress2']; ?>'>
                            </div>
                            <?php if($kUser->arErrorMessages['usStAddress2'] !=''){ ?> 
                                <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usStAddress2']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div id="mycountry" class="form-group <?php if($kUser->arErrorMessages['usCountry'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Country</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <select class="form-control custom-select" name="addUserAry[usCountry]"  id="countries" onfocus="remove_formError(this.id,'true')">
                                        <option value="1">Australia</option>
                                        
                                </select>
                            </div>
                            <?php if($kUser->arErrorMessages['usCountry'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usCountry']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div id="mystate" class="form-group <?php if($kUser->arErrorMessages['usState'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">State</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <select class="form-control custom-select" name="addUserAry[usState]" id="states" onfocus="remove_formError(this.id,'true')">
                                        <option value="">States</option>
                                        <?php 
                                        $statesarr = $kCommon->loadState();
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
                    
                    <div class="form-group <?php if($kUser->arErrorMessages['usPostcode'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Post Code</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <input autocomplete="off" type="text" class="form-control" name="addUserAry[usPostcode]" id='usPostcode' onfocus="remove_formError(this.id,'true')" placeholder="Post Code" value='<?php echo $_POST['addUserAry']['usPostcode']; ?>'> 
                            </div>
                            <?php if($kUser->arErrorMessages['usPostcode'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usPostcode']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group <?php if($kUser->arErrorMessages['soc'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Standard Order Customer</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input autocomplete="off" type="checkbox" class="form-control" <?php echo (isset($_POST['addUserAry']['soc']) && $_POST['addUserAry']['soc']=='1'?'checked':'');?> name="addUserAry[soc]" id='ussoc' onfocus="remove_formError(this.id,'true')" value='1'>
                            </div>
                            <?php if($kUser->arErrorMessages['soc'] !=''){ ?>
                                <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['soc']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group <?php if($kUser->arErrorMessages['usStartDate'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Contract Start Date</label>
                        <div class="col-md-4">
                            <div class="input-group input-large date-picker input-daterange" data-date="<?php echo date('d/m/Y')?>" data-date-format="dd/mm/yyyy">
                               <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <input type="text" class="form-control date" name="addUserAry[usStartDate]" id="strtdt" placeholder="Start Date" value="<?php echo $_POST['addUserAry']['usStartDate']?>">
                            </div>
                            <?php if($kUser->arErrorMessages['usStartDate'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usStartDate']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group <?php if($kUser->arErrorMessages['usEndDate'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Contract End Date</label>
                        <div class="col-md-4">
                            <div class="input-group input-large date-picker input-daterange" data-date="<?php echo date('d/m/Y')?>" data-date-format="dd/mm/yyyy">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <input type="text" class="form-control date1" name="addUserAry[usEndDate]" id="enddt" placeholder="End Date" value="<?php echo $_POST['addUserAry']['usEndDate']?>">
                            </div>
                            <?php if($kUser->arErrorMessages['usEndDate'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usEndDate']; ?></span>
                            <?php } ?>
                        </div>
                    </div>
<!--                    <div id="mygroup" class="form-group <?php if($kUser->arErrorMessages['usgroup'] !=''){ ?> has-error <?php } ?>">
                        <label class="col-md-3 control-label">Group</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <select class="form-control custom-select" name="addUserAry[usgroup]" id="groups" onfocus="remove_formError(this.id,'true')">
                                        <option value="">Group</option>
                                        <?php 
                                        $GroupAry = $kCommon->loadGroups();
                                        if(!empty($GroupAry))
                                        {
                                                foreach($GroupAry as $group)
                                                {
                                                        $selected = ($group['id'] == $_POST['addUserAry']['usgroup'] ? 'selected="selected"' : '');	
                                                        echo '<option value="'.$group['id'].'" '.$selected.'>'.$group['name'].'</option>';
                                                }
                                        }
                                        ?>
                                </select>
                            </div>
                            <?php if($kUser->arErrorMessages['usgroup'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['usgroup']; ?></span>
                            <?php } ?>
                        </div>
                    </div>-->
                    
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-4">
                            <button class="btn blue" onclick="addNewUser(); return false;">Save</button>
                            <a class="btn default" href="<?php echo __VIEW_CUSTOMERS_LIST_URL__; ?>">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
            <!-- END FORM-->
        </div>
    </div>
</div>
<script type="text/javascript">
    $('.date').pickmeup({
        format: 'd/m/Y'
    });
    $('.date1').pickmeup({
        format: 'd/m/Y'
    });

</script>
