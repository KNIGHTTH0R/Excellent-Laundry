<div class="col-md-12">
    <div class="search-page search-content-2">
        <div class="search-bar bordered">
            <form id="runSlotSearchForm" id="runSlotSearchForm" method="post">
            <div class="row">
                <div class="col-md-2">
                        <label for="Business">Week Day:</label>
                    </div>
                <div class="col-md-4">
                        <div class="form-group">
                            <select class="form-control" name="searchArr[usWeekDay]" id="usWeekDay" >
                                <option value="">Select Day</option>
                                <option value="1" <?php echo ($dayVal == '1'?'selected="selected"':'');?>>Monday</option>
                                <option value="2" <?php echo ($dayVal == '2'?'selected="selected"':'');?>>Tuesday</option>
                                <option value="3" <?php echo ($dayVal == '3'?'selected="selected"':'');?>>Wednesday</option>
                                <option value="4" <?php echo ($dayVal == '4'?'selected="selected"':'');?>>Thursday</option>
                                <option value="5" <?php echo ($dayVal == '5'?'selected="selected"':'');?>>Friday</option>
                                <option value="6" <?php echo ($dayVal == '6'?'selected="selected"':'');?>>Saturday</option>
                                <option value="7" <?php echo ($dayVal == '7'?'selected="selected"':'');?>>Sunday</option>
                            </select>
                        </div>
                    
                    </div>
                <div class="col-md-4 text-right">
                    <div class="form-group">
                        <button class="btn blue uppercase bold" type="button" onclick="WeekDayValue();"><i class="fa fa-search"></i> Search</button>
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
                <i class="fa fa-users"></i>Daily Schedule Report
            </div>
           <div class="actions">
                <div class="btn-group btn-group-devided" data-toggle="buttons">
                    <button onclick="redirect_url('<?php echo __VIEW_DAILY_SCHEDULE_PDF_REPORT_URL__.$dayVal.'/';?>','1')" class="btn grey btn-sm active">
                        <i class="fa fa-eye"></i> View PDF
                    </button>
                </div>
            </div>
        </div>
        <div class="portlet-body">
            <?php
            
                     
            if(!empty($getAllDriverArr))
            {
            ?>
                <div class="table-responsive">
                   
                            <?php
                                
                                    foreach ($getAllDriverArr as $singleDriver){ 
                                        $scheduleReportArr = $kDriver->getDailyCustomerReport($dayVal,$singleDriver['id']); 
                                        if(!empty ($scheduleReportArr)){ ?>
                                            <div><p style="text-align:center"><b>Driver Name:</b> <?php echo $singleDriver['name'];?></p></div>
                                                                <div><p style="text-align:center"><b>Week Day:</b> <?php echo $today; ?></p></div>

                                            <table id="sample_1" class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" role="grid" aria-describedby="sample_1_info">
                                                <thead>
                                                    <tr>
                                                        <th>Customer Name</th>
                                                        <th>Customer Address</th>
                                                        <th>Clean Bags Sent</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                            <?php foreach ($scheduleReportArr as $repo){
                                                $addressArr = explode('$', $repo['address']);
                                                $stateArr = $kCommon->loadState($repo['state']);
                                                ?>
                                        
                                        <tr>
                                            <td><?php echo $repo['business_name'];?></td>
                                            <td><?php echo 'Street Address 1: '.$addressArr[0].'<br />
                                                Street Address 2: '.$addressArr[1].'<br />
                                                State: '.$stateArr[0]['name'].'<br />
                                                Country: Australia<br />
                                                Post Code: '.$repo['postcode'].'<br />';?>
                                            </td>
                                            <td></td>
                                        </tr>
                                    <?php } ?>
                                               
                        </tbody>
                    </table>
                                       <?php }
                                    }
                                
                            ?>
                         
                </div>
            <?php 
            }
            else
            {
            ?>
                <h3>No Data Found.</h3>
            <?php
            }
            ?>
        </div>
    </div>
</div>
<div id="popup"></div>
