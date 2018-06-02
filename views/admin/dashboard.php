<div class="col-md-12">

    <div class="portlet box blue">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-truck"></i>Recent Order List
            </div>
        </div>

        <div class="portlet-body">
            <?php


            if (!empty($orderArr)) {
                $check = false;
                foreach ($orderArr as $orderData) {
                    if ($orderData['status'] == '1') {
                        $check = TRUE;
                    }
                }
                ?>
                <div class="table-responsive">
                    <?php if ($check) { ?>
                        <table id="sample_1"
                               class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer"
                               role="grid" aria-describedby="sample_1_info">
                            <thead>
                            <tr>
                                <th> #</th>
                                <th> Order Number</th>
                                <th> Business Name</th>
                                <th> Status</th>
                                <th> Order date</th>
                                <th> Xero Invoice No.</th>


                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $i = 1;
                            foreach ($orderArr as $orderData) {
                                if ($orderData['status'] == '1') {
                                    ?>
                                    <tr>
                                        <td> <?php echo $i++; ?> </td>
                                        <td> <?php echo '#' . $orderData['id']; ?> </td>
                                        <td>
                                            <?php echo $orderData['business_name']; ?>

                                        </td>
                                        <td> <?php
                                            if ($orderData['status'] == '1') {
                                                ?>
                                                <a title="Order Status" class="label label-sm label-warning">
                                                    Ordered
                                                </a>
                                                <?php
                                            }
                                            ?></td>
                                        <td> <?php echo date('d/m/Y', strtotime($orderData['createdon'])); ?> </td>
                                        <td>
                                            <?php echo(!empty($orderData['XeroIDnumber']) ? $orderData['XeroIDnumber'] : 'Not Available <!--<br /><a href="'.__CREATE_XERO_INVOICE__.$orderData["id"].'/">Create Xero Invoice</a>-->'); ?>
                                        </td>

                                    </tr>

                                    <?php

                                }
                                ?>

                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <h3>No Recent Order.</h3>
                    <?php } ?>
                </div>
                <?php
            } else {
                ?>
                <h3>No Recent Order.</h3>
                <?php
            }
            ?>
        </div>
    </div>
</div>
<div id="popup"></div>
<script type="text/javascript">
    $('.date').pickmeup({
        format: 'm/d/Y'
    });
    $('.date1').pickmeup({
        format: 'm/d/Y'
    });

</script>
