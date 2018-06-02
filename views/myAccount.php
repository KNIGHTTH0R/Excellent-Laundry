<?php if(isset($_GET['changepass'])){?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script type="text/javascript">
        expandChangePassword();
    </script>
<?php }?>
<div class="container-full">
    <ul class="nav nav-tabs">
        <li class="active" id="addOrderHistoryInfoTab">
            <a data-toggle="tab" class="btn" aria-expanded="false" onclick="expandOrderHistoryInfoDetai();">Order History</a>
        </li>
        <li id="changePasswordTab">
            <a data-toggle="tab" class="btn" onclick="expandChangePassword();">Change Password</a>
        </li>
    </ul>
    <div class="tab-content">
	<div id="history" class="tab-pane fade in active">
            <?php
                  foreach($orderArr as $orderData)
                  {
                    ?>
                    <div class="history-row">
		        <p class="small">Order Date: <?php echo date('d/m/Y',strtotime($orderData['createdon']));?></p>
<!--                        <p class="small">Price: <?php echo '$'.$orderData['price'];?></p>-->
                        <a title="re-order" class="btn delete" href="javascript:void(0);" onclick="reOrderProduct('<?php echo $orderData['id'];?>')">
                            <i class="fa fa-history" aria-hidden="true"></i>
                        </a>
		       <a title="View History" class="btn delete" href="javascript:void(0);" onclick="viewOrderHistory('<?php echo $orderData['id'];?>')">
                            <i class="fa fa-calendar"></i>
                        </a>
	            </div>
                  <?php
                  
                  }
            ?>
	</div>
</div>
			
</div>
<div id="popup"></div>

