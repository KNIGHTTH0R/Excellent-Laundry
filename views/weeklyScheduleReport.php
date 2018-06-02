<div class="col-md-12">
    
    <div class="portlet box blue">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-users"></i>Weekly Schedule Report
            </div>
           <div class="actions">
                <div class="btn-group btn-group-devided" data-toggle="buttons">
                    <button onclick="redirect_url('<?php echo __VIEW_WEEKLY_SCHEDULE_PDF_REPORT_URL__;?>','1')" class="btn grey btn-sm active">
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
                                        $mondayArr = array();
                                        $tuesdayArr = array();
                                        $weddayArr = array();
                                        $thursdayArr = array();
                                        $fridayArr = array();
                                        $satdayArr = array();
                                        $sundayArr = array();
                                        $scheduleReportArr = $kDriver->getDailyCustomerReport(0,$singleDriver['id']); 
                                        $ctr = 1;
                                        if(!empty ($scheduleReportArr)){ ?>
                                            <div><p style="text-align:center"><b>Driver Name:</b> <?php echo $singleDriver['name'];?></p></div>
                                            <table id="sample_<?php echo $ctr;?>" class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" role="grid" aria-describedby="sample_1_info">
                                                <thead>
                                                    <tr>
                                                        <th>Mon</th>
                                                        <th>Mon Clean Bags</th>
                                                        <th>Tues</th>
                                                        <th>Tues Clean Bags</th>
                                                        <th>Wed</th>
                                                        <th>Wed Clean Bags</th>
                                                        <th>Thurs</th>
                                                        <th>Thurs Clean Bags</th>
                                                        <th>Fri</th>
                                                        <th>Fri Clean Bags</th>
                                                        <th>Sat</th>
                                                        <th>Sat Clean Bags</th>
                                                        <th>Sun</th>
                                                        <th>Sun Clean Bags</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                            <?php foreach ($scheduleReportArr as $repo){
                                                    if($repo['weekid']=='1'){
                                                        array_push($mondayArr, $repo['business_name']);
                                                    }
                                                    if($repo['weekid']=='2'){
                                                        array_push($tuesdayArr, $repo['business_name']);
                                                    }
                                                    if($repo['weekid']=='3'){
                                                        array_push($weddayArr, $repo['business_name']);
                                                    }
                                                    if($repo['weekid']=='4'){
                                                        array_push($thursdayArr, $repo['business_name']);
                                                    }
                                                    if($repo['weekid']=='5'){
                                                        array_push($fridayArr, $repo['business_name']);
                                                    }
                                                    if($repo['weekid']=='6'){
                                                        array_push($satdayArr, $repo['business_name']);
                                                    }
                                                    if($repo['weekid']=='7'){
                                                        array_push($sundayArr, $repo['business_name']);
                                                    }
                                                }
                                    $maxCount = 0;
                                    if(count($mondayArr)>$maxCount){
                                        $maxCount = count($mondayArr);
                                    }
                                    if(count($tuesdayArr)>$maxCount){
                                        $maxCount = count($tuesdayArr);
                                    }
                                    if(count($weddayArr)>$maxCount){
                                        $maxCount = count($weddayArr);
                                    }
                                    if(count($thursdayArr)>$maxCount){
                                        $maxCount = count($thursdayArr);
                                    }
                                    if(count($fridayArr)>$maxCount){
                                        $maxCount = count($fridayArr);
                                    }
                                    if(count($satdayArr)>$maxCount){
                                        $maxCount = count($satdayArr);
                                    }
                                    if(count($sundayArr)>$maxCount){
                                        $maxCount = count($sundayArr);
                                    }
                                    
                                    for($i=0;$i<$maxCount;$i++){ ?>
                                       <tr>
                                           <td><?php echo (!empty ($mondayArr[$i])? $mondayArr[$i]:'');?></td>
                                            <td> </td>
                                            <td><?php echo (!empty ($tuesdayArr[$i])? $tuesdayArr[$i]:'');?></td>
                                            <td> </td>
                                            <td><?php echo (!empty ($weddayArr[$i])? $weddayArr[$i]:'');?></td>
                                            <td> </td>
                                            <td><?php echo (!empty ($thursdayArr[$i])? $thursdayArr[$i]:'');?></td>
                                            <td> </td>
                                            <td><?php echo (!empty ($fridayArr[$i])? $fridayArr[$i]:'');?></td>
                                            <td> </td>
                                            <td><?php echo (!empty ($satdayArr[$i])? $satdayArr[$i]:'');?></td>
                                            <td> </td>
                                            <td><?php echo (!empty ($sundayArr[$i])? $sundayArr[$i]:'');?></td>
                                            <td> </td>
                                        </tr> 
                                    <?php } ?>
                                               
                        </tbody>
                    </table>
                                       <?php 
                                       $ctr++;
                                       }
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
