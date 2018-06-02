<div class="col-md-12">

    <div class="portlet box blue">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-truck"></i>Xero Invoice Information
            </div>
        </div>

        <div class="portlet-body">
           <?php
           if (!empty($orderDetArr)) {
               define('XERO_KEY','LL1PFXTPUJGOG5WXLTKVQC7GMUUY2P');
               define('XERO_SECRET','0HNKVD2DDTMDAIMOFMWSRZRYV2N8ON');
               $xero = new Xero(XERO_KEY, XERO_SECRET, __APP_PATH__ . '/includes/publickey.cer', __APP_PATH__ . '/includes/privatekey.pem', 'xml' );
                   if ($orderDetArr[0]['xeroprocessed'] == '0') {
                       $subResult = $kOrder->loadProductOrder($orderDetArr[0]['id']);

                       $lineItems = array();
                       if (!empty($subResult)) {
                           foreach ($subResult as $ordprods) {
                               $lienItem = array(
                                   "Description" => $ordprods['description'],
                                   "Quantity" => $ordprods['quantity'],
                                   "UnitAmount" => $ordprods['price'],
                                   "LineAmount" => $ordprods['price'] * $ordprods['quantity'],
                                   "AccountCode" => "41000"
                               );
                               array_push($lineItems, $lienItem);
                           }
                       }
                       $new_invoice = array(
                           array(
                               "Type" => "ACCREC",
                               "Contact" => array(
                                   "Name" => $orderDetArr[0]['business_name']
                               ),
                               "Date" => $orderDetArr[0]['createdon'],
                               "DueDate" => date('Y-m-d H:i:s', strtotime($orderDetArr[0]['createdon'] . ' +30 day')),
                               "Reference" => "Ordering System",
                               "Status" => "AUTHORISED",
                               "LineAmountTypes" => "Exclusive",
                               "LineItems" => array(
                                   "LineItem" => $lineItems
                               )
                           )
                       );
                       $invoice_result = $xero->Invoices($new_invoice);
                       $result = $xero->Accounts(false, false, array("Name" => $orderDetArr[0]['business_name']));
                       $org_invoices = $xero->Invoices;

                       $invoice_count = sizeof($org_invoices->Invoices->Invoice);

                       //$invoice_index = rand(0,$invoice_count);
                       $invoice_index = $invoice_count-1;
                       $invoice_id = (string) $org_invoices->Invoices->Invoice[$invoice_index]->InvoiceID;
                       $invoice_number = (string) $org_invoices->Invoices->Invoice[$invoice_index]->InvoiceNumber;
                       if($result){
                           if($kOrder->updateXero($orderDetArr[0]['id'],$invoice_number)){
                               echo "<div class='note note-success'  style='text-align:center'><h3 class='block'>XERO invoice is created with Invoice No. <b>".$invoice_number."</b> for Order ID <b>#".$orderDetArr[0]['id']."</b></h3>
                               <p><a class='btn green' href='".__URL_BASE_ADMIN__."'>DONE</a></p></div>";
                           }
                       }
                       if(!$invoice_id) {
                           echo "You will need some invoices for this...";
                       }

                   }
           }
           ?>
        </div>
    </div>
</div>
