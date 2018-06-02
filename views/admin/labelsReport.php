
<div class="col-md-12">
    <div class="search-page search-content-2">
        <div class="search-bar bordered">
            <form id="labelReportForm" id="labelReportForm" method="post">
                <div class="row">
                    
                    <div class="col-md-3">
                            <div class="form-group input-group customerlist">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                                <select class="form-control custom-select" name="searchArr[customer]"  id="customer" onfocus="remove_formError(this.id,'true')">
                                        <option value="">Customer</option>
                                        <?php 
                                        $customerArr = $kUser->loadCustomers();
                                        if(!empty ($customerArr)){
                                            foreach ($customerArr as $customerdetail){
                                                $selected = '';
                                                if($_POST['searchArr']['customer'] == $customerdetail['business_name']){
                                                    $selected = 'selected="selected"';
                                                }
                                                echo '<option value="'.$customerdetail['business_name'].'" '.$selected.'>'.$customerdetail['business_name'].'</option>';
                                            }
                                        }
                                        ?>
                                </select>
                            </div>
                            <?php if($kUser->arErrorMessages['customer'] !=''){ ?> 
                            <span class="help-block"><i class="icon icon-remove-sign"></i> <?php echo $kUser->arErrorMessages['customer']; ?></span>
                            <?php } ?>
                        </div>
                    <div class="col-md-3">
                        <div class="form-group">
                          <div class="form-group" >
                                <input type="number" class="form-control labelcount" name="searchArr[labelcount]" id="labelcount" placeholder="Number of labels" value="<?php echo $_POST['searchArr']['labelcount']?>">
                                 <?php if($kOrder->arErrorMessages['labelcount'] !=''){ ?> 
                            <span class="help-block"><?php echo $kOrder->arErrorMessages['labelcount']; ?></span>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group label-date">
                          <div class="form-group" data-date="<?php echo date('d/m/Y')?>" data-date-format="dd/mm/yyyy">
                                <input type="text" class="form-control date1" name="searchArr[labeldate]" id="labeldate" placeholder="Date" value="<?php echo $_POST['searchArr']['labeldate']?>">
                                 <?php if($kOrder->arErrorMessages['labeldate'] !=''){ ?> 
                            <span class="help-block"><?php echo $kOrder->arErrorMessages['labeldate']; ?></span>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                    
                     <div class="col-md-3">
                    <div class="form-group">
                        <button class="btn blue uppercase bold" type="button" onclick="getLabelReport();"><i class="fa fa-search"></i> GO</button>
                        &nbsp;
                        <!--<button class="btn red uppercase bold" type="button" onclick="resetClientSearch();"><i class="fa fa-refresh"></i>Reset</button>-->
                    </div>
                </div>
                   
                    
                    
                </div>
               
                
                
            </form>
        </div>
    </div>
    <div id="popup"></div>
</div>

<script type="text/javascript">
    $('.date').pickmeup({
        format: 'd/m/Y'
    });
    $('.date1').pickmeup({
        format: 'd/m/Y'
    });

</script>
