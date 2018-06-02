<div class="col-md-12">
    <div class="search-page search-content-2">
        <div class="search-bar bordered">
            <form id="orderSearchForm" id="orderSearchForm" method="post">
                <div class="row">

                    <div class="col-md-3">
                        <div
                            class="form-group <?php if ($kOrder->arErrorMessages['startcreatedon'] != '') { ?> has-error <?php } ?>">
                            <div class="form-group" data-date="<?php echo date('d/m/Y') ?>"
                                 data-date-format="dd/mm/yyyy">
                                <input type="text" class="form-control date1" name="searchArr[startcreatedon]"
                                       id="enddt" placeholder="Start Order Date"
                                       value="<?php echo $_POST['searchArr']['startcreatedon'] ?>">
                                <?php if ($kOrder->arErrorMessages['startcreatedon'] != '') { ?>
                                    <span
                                        class="help-block"><?php echo $kOrder->arErrorMessages['startcreatedon']; ?></span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div
                            class="form-group <?php if ($kOrder->arErrorMessages['endcreatedon'] != '') { ?> has-error <?php } ?>">
                            <div class="form-group" data-date="<?php echo date('d/m/Y') ?>"
                                 data-date-format="dd/mm/yyyy">
                                <input type="text" class="form-control date1" name="searchArr[endcreatedon]"
                                       id="endcreatedon" placeholder="End Order Date"
                                       value="<?php echo $_POST['searchArr']['endcreatedon'] ?>">
                                <?php if ($kOrder->arErrorMessages['endcreatedon'] != '') { ?>
                                    <span
                                        class="help-block"><?php echo $kOrder->arErrorMessages['endcreatedon']; ?></span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <!--                        <div class="form-group">
                          <div class="form-group" >
                                <input type="text" class="form-control businessname" name="searchArr[businessname]" id="businessname" placeholder="Customer" value="<?php echo $_POST['searchArr']['businessname'] ?>">
                                 <?php if ($kOrder->arErrorMessages['businessname'] != '') { ?>
                            <span class="help-block"><?php echo $kOrder->arErrorMessages['businessname']; ?></span>
                            <?php } ?>
                            </div>
                        </div>-->
                        <div class="form-group input-group customerlist">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                            <select class="form-control custom-select" name="searchArr[businessname]" id="customer"
                                    onfocus="remove_formError(this.id,'true')">
                                <option value="">Customer</option>
                                <?php
                                $customerArr = $kUser->loadCustomers();
                                if (!empty ($customerArr)) {
                                    foreach ($customerArr as $customerdetail) {
                                        $selected = '';
                                        if ($_POST['searchArr']['businessname'] == $customerdetail['business_name']) {
                                            $selected = 'selected="selected"';
                                        }
                                        echo '<option value="' . $customerdetail['business_name'] . '" ' . $selected . '>' . $customerdetail['business_name'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group input-group customerlist">
                            <span class="input-group-addon">
                                <i class="fa fa-dot-circle-o"></i>
                            </span>
                            <select class="form-control custom-select" name="searchArr[orderstat]" id="orderstat"
                                    onfocus="remove_formError(this.id,'true')">
                                <option value="">Status</option>
                                <option value="1">Ordered</option>
                                <option value="2">Pending</option>
                                <option value="3">Dispatched</option>
                                <option value="5">Canceled</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <button class="btn blue uppercase bold reposearch" type="button"
                                    onclick="getOrderReportSearch();"><i class="fa fa-search"></i></button>
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
    ?>
    <div class="portlet box blue">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-users"></i>View Orders Report
            </div>
            <div class="actions">
                <div data-toggle="buttons" class="btn-group btn-group-devided">
                    <button class="btn grey btn-sm active"
                            onclick="redirect_url('<?php echo __VIEW_ORDER_REPORT_PDF_REPORT_URL__ . (!empty ($searchvals) ? '?' . $searchvals : ''); ?>','1');">
                        <i class="fa fa-eye"></i> View PDF
                    </button>
                    <button class="btn grey btn-sm active"
                            onclick="redirect_url('<?php echo __VIEW_ORDER_REPORT_CSV_REPORT_URL__ . (!empty ($searchvals) ? '?' . $searchvals : ''); ?>','1');">
                        <i class="fa fa-eye"></i> Export CSV
                    </button>
                </div>
            </div>
        </div>

        <div class="portlet-body">
            <?php


            if (!empty($orderArr)) {
                ?>
                <div class="table-responsive">
                    <table id="sample_1"
                           class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer"
                           role="grid" aria-describedby="sample_1_info">
                        <thead>
                        <tr>
                            <th> #</th>
                            <th> Customer</th>
                            <th> Order date</th>
                            <th> Order #</th>
                            <th> No. of products</th>
                            <th> Order Cost</th>
                            <th> Status</th>
                            <th>Xero Invoice No.</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = 1;
                        foreach ($orderArr as $orderData) {
                            $orderProdArr = $kOrder->loadProductOrder($orderData['id']);
                            ?>
                            <tr>
                                <td> <?php echo $i++; ?> </td>
                                <td>
                                    <?php echo $orderData['business_name']; ?>

                                </td>
                                <td> <?php echo date('d/m/Y', strtotime($orderData['createdon'])); ?> </td>
                                <td> <?php echo '#' . $orderData['id']; ?> </td>

                                <td><?php echo count($orderProdArr); ?></td>


                                <td> <?php echo '$' . $orderData['price']; ?> </td>
                                <td> <?php
                                    if ($orderData['status'] == '1') {
                                        ?>
                                        <a title="Order Status" class="label label-sm label-warning">
                                            Ordered
                                        </a>
                                        <?php
                                    }
                                    if ($orderData['status'] == '2') {
                                        ?>
                                        <a title="Order Status" class="label label-sm label-success">
                                            Pending
                                        </a>
                                        <?php
                                    }
                                    if ($orderData['status'] == '3') {
                                        ?>
                                        <a title="Order Status" class="label label-sm label-info">
                                            Dispatched
                                        </a>
                                        <?php
                                    }
                                    if ($orderData['status'] == '4') {
                                        ?>
                                        <!--                                        <a title="Order Status" class="label label-sm label-success">
                                                                                    Complete
                                                                                </a>-->
                                        <?php
                                    }
                                    if ($orderData['status'] == '5') {
                                        ?>
                                        <a title="Order Status" class="label label-sm label-danger">
                                            Canceled
                                        </a>
                                        <?php
                                    }

                                    ?></td>
                                <td>
                                    <?php echo(!empty($orderData['XeroIDnumber']) ? $orderData['XeroIDnumber'] : 'Not Available <!--<br /><a href="'.__CREATE_XERO_INVOICE__.$orderData["id"].'/">Create Xero Invoice</a>-->'); ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <?php
            } else {
                ?>
                <h3>No Order Found.</h3>
                <?php
            }
            ?>
        </div>
    </div>
</div>
<div id="popup"></div>
<script type="text/javascript">
    $('.date').pickmeup({
        format: 'd/m/Y'
    });
    $('.date1').pickmeup({
        format: 'd/m/Y'
    });

</script>
