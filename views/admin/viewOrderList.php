<?php
if(isset($_SESSION['search'])){
    if(!empty($_SESSION['search']['business_name'])){
        $_POST['searchArr']['business_name'] = $_SESSION['search']['business_name'];
    }
    if(!empty($_SESSION['search']['order_number'])){
        $_POST['searchArr']['order_number'] = $_SESSION['search']['order_number'];
    }
    if(!empty($_SESSION['search']['status'])){
        $_POST['searchArr']['status'] = $_SESSION['search']['status'];
    }
    if(!empty($_SESSION['search']['startcreatedon'])){
        $_POST['searchArr']['startcreatedon'] = $_SESSION['search']['startcreatedon'];
    }
    if(!empty($_SESSION['search']['endcreatedon'])){
        $_POST['searchArr']['endcreatedon'] = $_SESSION['search']['endcreatedon'];
    }
}
?>
<div class="col-md-12">
    <div class="search-page search-content-2">
        <div class="search-bar bordered">
            <form id="orderSearchForm" id="orderSearchForm" method="post">

                <div class="row">
                    <div class="col-md-4">
                        <!--                        <div class="form-group <?php if ($kOrder->arErrorMessages['businessName'] != '') { ?> has-error <?php } ?>">
                            
                            <div class="form-group">
                                <input type="text" class="form-control" name="searchArr[business_name]" id="customerid" placeholder="Business Name " value="<?php echo $_POST['searchArr']['business_name'] ?>">
                                 <?php if ($kOrder->arErrorMessages['customerName'] != '') { ?>
                            <span class="help-block"><?php echo $kOrder->arErrorMessages['businessName']; ?></span>
                            <?php } ?>
                            </div>
                            
                        </div>-->
                        <div class="form-group input-group customerlist">
                                <span class="input-group-addon">
                                    <i class="fa fa-dot-circle-o"></i>
                                </span>
                            <select class="form-control custom-select" name="searchArr[business_name]" id="customer"
                                    onfocus="remove_formError(this.id,'true')">
                                <option value="">Business Name</option>
                                <?php
                                $customerArr = $kUser->loadCustomers();
                                if (!empty ($customerArr)) {
                                    foreach ($customerArr as $customerdetail) {
                                        $selected = '';
                                        if ($_POST['searchArr']['business_name'] == $customerdetail['business_name']) {
                                            $selected = 'selected="selected"';
                                        }
                                        echo '<option value="' . $customerdetail['business_name'] . '" ' . $selected . '>' . $customerdetail['business_name'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div
                            class="form-group <?php if ($kOrder->arErrorMessages['order_number'] != '') { ?> has-error <?php } ?>">

                            <div class="form-group">
                                <input type="text" class="form-control" name="searchArr[order_number]" id="enddt"
                                       placeholder="Order Number"
                                       value="<?php echo $_POST['searchArr']['order_number'] ?>">
                                <?php if ($kOrder->arErrorMessages['order_number'] != '') { ?>
                                    <span
                                        class="help-block"><?php echo $kOrder->arErrorMessages['order_number']; ?></span>
                                <?php } ?>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <select class="form-control" name="searchArr[status]" id="status">
                                <option value="">Status</option>
                                <?php

                                $statusArray = $kOrder->loadStatus();
                                if (!empty($statusArray)) {
                                    foreach ($statusArray as $statusData) {
                                        if ($statusData['id'] != '4') {
                                            ?>
                                            <option
                                                value="<?php echo $statusData['id']; ?>" <?php if ($_POST['searchArr']['status'] == $statusData['id']) { ?> selected="selected" <?php } ?>><?php echo $statusData['status']; ?></option>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="row">

                    <div class="col-md-4">
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

                    <div class="col-md-4">
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <button class="btn blue uppercase bold" type="button" onclick="getOrderSearch();"><i
                                    class="fa fa-search"></i> Search
                            </button>
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
                <i class="fa fa-users"></i>View Order List
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
                            <th> Order Number</th>
                            <th> Business Name</th>
                            <th> Order date</th>
                            <!--                                <th> Price </th>-->
                            <th> Status</th>
                            <th> Order Details</th>
                            <th> Edit Order</th>
                            <th> Delivery Docket</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = 1;
                        foreach ($orderArr as $orderData) {
                            ?>
                            <tr>
                                <td> <?php echo $i++; ?> </td>
                                <td> <?php echo '#' . $orderData['id']; ?> </td>
                                <td>
                                    <?php echo $orderData['business_name']; ?>

                                </td>
                                <td> <?php echo date('d/m/Y', strtotime($orderData['createdon'])); ?> </td>
                                <!--                                    <td> <?php echo $orderData['price']; ?> </td>-->
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

                                <td><a title="View Order Deatils" class="btn dark btn-sm btn-outline sbold uppercase"
                                       href="javascript:void(0);"
                                       onclick="redirect_url('<?php echo __VIEW_ORDER_DETAILS_URL__; ?><?php echo $orderData['id']; ?>/')">
                                        <i class="fa fa-eye"></i>
                                    </a></td>
                                <td>
                                    <?php if ($orderData['status'] == '1' || $orderData['status'] == '2') { ?>
                                        <a title="Edit Order" class="btn dark btn-sm btn-outline purple uppercase"
                                           href="javascript:void(0);"
                                           onclick="editorderadmin('<?php echo $orderData['id']; ?>')">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if ($orderData['status'] == '3') { ?>
                                        <a title="Produce docket" class="btn dark btn-sm btn-outline blue uppercase"
                                           href="javascript:void(0);"
                                           onclick="redirect_url('<?php echo __VIEW_ORDER_DOCKET_URL__; ?><?php echo $orderData['id']; ?>/')">
                                            <i class="fa fa-print"></i>
                                        </a>
                                    <?php } ?>
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
