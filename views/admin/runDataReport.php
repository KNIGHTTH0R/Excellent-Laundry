<div class="col-md-12">
    <div class="search-page search-content-2">
        <div class="search-bar bordered">
            <form id="runDataSearchForm" id="runDataSearchForm" method="post">
                <div class="row">

                    <!--<div class="col-md-5">
                        <div
                            class="form-group label-date <?php /*if ($kUser->arErrorMessages['dateFrom'] != '') { */?> has-error <?php /*} */?>">
                            <div class="form-group" data-date="<?php /*echo date('d/m/Y') */?>"
                                 data-date-format="dd/mm/yyyy">
                                <input type="text" class="form-control date1" name="searchArr[dateFrom]" id="dateFrom"
                                       placeholder="Start Date From" onfocus="remove_formError(this.id,'true')"
                                       value="<?php /*echo $_POST['searchArr']['dateFrom'] */?>">
                                <?php /*if ($kUser->arErrorMessages['dateFrom'] != '') { */?>
                                    <span class="help-block"><?php /*echo $kUser->arErrorMessages['dateFrom']; */?></span>
                                <?php /*} */?>
                            </div>
                        </div>
                    </div>-->

                    <div class="col-md-5">

                        <div class="form-group">
                            <select class="form-control" name="searchArr[dateTo]" id="dateupto" >
                                <option value="">Contract Ending Within</option>
                                <option value="60" <?php if($_POST['searchArr']['dateTo']=='60') { ?> selected="selected" <?php } ?>>60 Days</option>
                                <option value="90" <?php if($_POST['searchArr']['dateTo']=='90') { ?> selected="selected" <?php } ?>>90 Days</option>

                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <button class="btn blue uppercase bold" type="button" onclick="getRunDataReport();"><i
                                    class="fa fa-search"></i> GO
                            </button>
                            &nbsp;
                            <!--<button class="btn red uppercase bold" type="button" onclick="resetClientSearch();"><i class="fa fa-refresh"></i>Reset</button>-->
                        </div>
                    </div>


                </div>


            </form>
        </div>
    </div>
    <?php
    $searchvals = '';
    foreach ($searchar as $key => $value) {
        if (!empty ($value)) {
            $searchvals .= $key . '=' . $value . '&';
        }
    }
    $searchvals = substr($searchvals, 0, -1);
    if (!empty($userArr) && $userArr[0]['id'] > 0) {
    ?>
    <div class="portlet box blue">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-users"></i>Ending Contracts
            </div>
            <div class="actions">
                <div data-toggle="buttons" class="btn-group btn-group-devided">
                    <button class="btn grey btn-sm active"
                            onclick="redirect_url('<?php echo __VIEW_RUN_DATA_REPORT_PDF_URL__ . (!empty ($searchvals) ? '?' . $searchvals : ''); ?>','1');">
                        <i class="fa fa-eye"></i> View PDF
                    </button>
                    <button class="btn grey btn-sm active"
                            onclick="redirect_url('<?php echo __VIEW_RUN_DATA_REPORT_CSV_URL__ . (!empty ($searchvals) ? '?' . $searchvals : ''); ?>','1');">
                        <i class="fa fa-eye"></i> Export CSV
                    </button>
                </div>
            </div>
        </div>
        <div class="portlet-body">
                <div class="table-responsive">

                    <table id="sample"
                           class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer"
                           role="grid" aria-describedby="sample_1_info">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Business Name</th>
                            <th>Contract Start Date</th>
                            <th>Contract Finish Date</th>
                            <th>Customer Phone Number</th>
                            <th>Customer Name</th>
                            <th>Contact Email</th>
                            <th>Customer Mobile Number</th>

                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        $i = 1;
                        foreach ($userArr as $userData) {

                            if ($userData['contract_start'] != '0000-00-00 00:00:00') {
                                ?>
                                <tr>
                                    <td> <?php echo $i++; ?> </td>
                                    <td><?php echo $userData['business_name']; ?></td>
                                    <td> <?php echo date('d/m/Y', strtotime($userData['contract_start'])); ?> </td>
                                    <td> <?php echo date('d/m/Y', strtotime($userData['contract_end'])); ?> </td>
                                    <td><?php echo $userData['phoneno']; ?></td>
                                    <td><?php echo $userData['contact_name']; ?></td>
                                    <td><?php echo $userData['contact_email']; ?></td>
                                    <td><?php echo $userData['mobileno']; ?></td>


                                </tr>
                            <?php }
                        }
                        ?>


                        </tbody>
                    </table>
                    <?php
                    $ctr++;
                    ?>
                </div>

        </div>
    </div>
    <?php } ?>
</div>

<script type="text/javascript">
    $('.date').pickmeup({
        format: 'd/m/Y'
    });
    $('.date1').pickmeup({
        format: 'd/m/Y'
    });

</script>