<?php 
/**
    * This file is the container for all user related functionality.
    * All functionality related to user details should be contained in this class.
    *
    * user.class.php
    *
    * @copyright Copyright (C) 2016 Whiz-Solutions
    * @author Whiz Solutions
    * @package Demo Project
 */
if( !defined( "__APP_PATH__" ) )
define( "__APP_PATH__", realpath( dirname( __FILE__ ) . "/../../" ) );

require_once( __APP_PATH__ . "/includes/constants.php" );
require_once( __APP_PATH_CLASSES__ . "/database.class.php" );
require_once( __APP_PATH_CLASSES__ . "/user.class.php" );

$kUser = new cUser();


Class cOrder extends cDatabase
{
	function __construct()
	{
		parent::__construct();
		// establish a Database connection.
		//$this->connect( __DBC_USER__, __DBC_PASSWD__, __DBC_SCHEMATA__, __DBC_HOST__);
		return true;
	}
	
   
    
   function loadOrder($id=0,$search=array(),$customerId=0)
   {
       $searchQuery='';
      
        if(!empty($search))
        {
            foreach($search as $key=>$searchData)
            {
                if($key == 'startcreatedon' || $key == 'endcreatedon')
                {
                    if(!empty($searchData))
                    {
                        $searchData=  getSqlFormattedDate($searchData);
                    }
                    if(!empty($searchData))
                    {
                        if($key == 'startcreatedon')
                        {
                            $startcreatedon=$searchData;
                            $searchQuery .="
                                AND
                                    o.createdon >= '".sql_real_escape_string($searchData)." 00:00:00'
                                ";
                        }
                    }
                    
                    if($key == 'endcreatedon')
                    { 
                        if(!empty($searchData))
                        {
                            $endcreatedon=$searchData;
                            if($endcreatedon != '')
                            {
                                if(strtotime($startcreatedon) > strtotime($endcreatedon))
                                {
                                    $this->addError("endcreatedon","To Date should be greater than From Date.");
                                    return false;
                                }
                            }
                            $searchQuery .="
                                AND
                                    o.createdon <= '".sql_real_escape_string($searchData)." 23:59:59'
                                ";
                        }
                    }
                    
                         
                }
                
                if($key == 'businessname'){
                    if(!empty ($searchData)){
                        $searchQuery .="
                        AND
                            c.business_name LIKE '%".sql_real_escape_string($searchData)."%'
                                ";
                    }
                }
                if($key == 'orderstat'){
                    if(!empty ($searchData)){
                        $searchQuery .="
                        AND
                            o.status = ".(int)($searchData);
                    }
                }
                if($searchData != '')
                {
                    if($key =='order_number')
                    {
                        $searchQuery .="
                        AND
                            o.id LIKE '%".sql_real_escape_string($searchData)."%'
                                ";
                    }
                    if($key =='size')
                    {
                        $searchQuery .="
                        AND
                            size LIKE '%".sql_real_escape_string($searchData)."%'
                                ";
                    }
                    if($key =='status')
                    {
                        $searchQuery .="
                        AND
                            status LIKE '%".sql_real_escape_string($searchData)."%'
                                ";
                    }
                    if($key =='business_name')
                    {
                        $searchQuery .="
                        AND
                            c.business_name LIKE '%".sql_real_escape_string($searchData)."%'
                                ";
                    }
                    }
                }
            }

       $subQuery='';
       if($id>0)
        {
            $subQuery .="AND 
                    o.id = ".(int)$id;
        }
       if($customerId>0)
        {
            $subQuery .="AND 
                    o.customerid = ".(int)$customerId;
        }
       
        $query="
                SELECT
                    o.id,
                    o.customerid,
                    o.price,
                    o.transactionid,
                    o.status,
                    o.createdon,
                    o.paidon,
                    o.dispatchedon,
                    o.completedon,
                    o.cancledon,
                    o.xeroprocessed,
                    o.XeroIDnumber,
                    c.business_name,
                    c.business_email,
                    c.phoneno,
                    c.mobileno,
                    c.address,
                    c.state,
                    c.postcode
                FROM
                    ".__DBC_SCHEMATA_ORDER__." as o 
                 INNER JOIN
                        ".__DBC_SCHEMATA_CUSTOMER_DETAILS__." as c
                ON
                    o.customerid = c.id
                WHERE
                    o.isdeleted = 0 
                    $searchQuery
                    $subQuery
                ORDER BY o.createdon DESC
                ";
        //echo $query;
        if($result = $this->exeSQL($query))
        {
            if($this->iNumRows > 0)
            {
                 while($res = $this->getAssoc( $result )){
                    $resarr[] = $res;
                 }
                 return $resarr;
            }
            else
            {
                return false;
            }
        }
        else
        {
            $this->error = true;
            $szErrorMessage = __CLASS__ . "::" . __FUNCTION__ .  "() failed to load because of a mysql error. SQL: " . $query . " MySQL Error: " . sql_error();
            $this->logError( "mysql", $szErrorMessage, "PHP", __CLASS__, __FUNCTION__, __LINE__, "critical");
            return false;
        }
   }
   
    function changeOrderStaus($orderStatus,$orderId)
    {
        $updatefield='';
        if($orderStatus=='1')
        {
            $updatefield='createdon';
        }
        if($orderStatus=='2')
        {
             $updatefield='paidon';
        }
        if($orderStatus=='3')
        {
             $updatefield='dispatchedon';
        }
        if($orderStatus=='4')
        {
             $updatefield='completedon';
        }
        if($orderStatus=='5')
        {
             $updatefield='cancledon';
        }
        $query="
            UPDATE
                ".__DBC_SCHEMATA_ORDER__."
            SET 
                    status = ".(int)$orderStatus.",
                    $updatefield   =  '".date('Y-m-d h:i:s')."' 
            WHERE id = ".$orderId;
     
       if($result = $this->exeSQL($query))
       {
           $subResult = $this->loadProductOrder($orderId);
           $finalprice = 0;
           if (!empty($subResult)) {
               foreach ($subResult as $ordprods) {
                   $finalprice = (double)$finalprice +((double)$ordprods['price'] * $ordprods['dispatched']);
               }
           }
           $query1="
            UPDATE
                ".__DBC_SCHEMATA_ORDER__."
            SET 
                    price = ".(double)$finalprice." 
            WHERE id = ".$orderId;
           if($result1 = $this->exeSQL($query1))
           {
               if($orderStatus == '3'){
                   if(!empty($subResult)){
                       foreach ($subResult as $orddet){
                           $this->updateInventoryFront($orddet['productid'], $orddet['dispatched']);
                       }
                   }
               }
               return true;
           }
       }
       else
       {
           return false;
       }
   }
   
   function loadStatus($id=0){
        $subQuery='';
        if((int)$id > 0)
        {
                $subQuery="
                      WHERE id=".(int)$id."
                ";
        }

        $query="
                SELECT
                    id,
                    status
                   
                FROM
                    ".__DBC_SCHEMATA_STATUS__." 
                $subQuery ORDER BY id ASC				
        ";
        //echo $query;
        if($result = $this->exeSQL($query))
        {
            if($this->iNumRows > 0)
            {
                 while($res = $this->getAssoc( $result )){
                    $resarr[] = $res;
                 }
                 return $resarr;
            }
            else
            {
                return false;
            }
        }
        else
        {
            $this->error = true;
            $szErrorMessage = __CLASS__ . "::" . __FUNCTION__ .  "() failed to load because of a mysql error. SQL: " . $query . " MySQL Error: " . sql_error();
            $this->logError( "mysql", $szErrorMessage, "PHP", __CLASS__, __FUNCTION__, __LINE__, "critical");
            return false;
        }
   }
   function createOrder($customerid, $price)
   {
         
       $this->set_prPrice(sanitize_all_html_input(trim($price)));
       
        if($this->error)
           return false;

       $query="
               INSERT INTO
                   ".__DBC_SCHEMATA_ORDER__."
               (
                   
                   customerid,
                   price,
                   status,
                   createdon
               )
               VALUES
               (
                    ".(int)$customerid.",
                    '".sql_real_escape_string($this->prPrice)."',
                    '1',
                    '".sql_real_escape_string(date('Y-m-d h:i:s'))."'
               )	
       ";

          //echo $query;
       if($result = $this->exeSQL($query))
       {
           $idord=$this->iLastInsertID;
           return $idord;    
       }
       else
       {
           return false;
       }
   }
   
   function addOrderDetails($data)
   {
         
       $this->set_prPrice(sanitize_all_html_input(trim($data['prPrice'])));
       $this->set_prQuantity(sanitize_all_html_input(trim($data['prQuantity'])));
       $this->set_prcolor(sanitize_all_html_input(trim($data['prColor'])));
       
        if($this->error)
           return false;

       $query="
                   INSERT INTO
                       ".__DBC_SCHEMATA_ORDER_DETAIL__."
                   (

                       orderid,
                       productid,
                       price,
                       quantity,
                       color
                   )
                   VALUES
                   (
                        ".(int)$data['ordid'].",
                        ".(int)$data['prId'].",
                        '".sql_real_escape_string($this->prPrice)."',
                        '".sql_real_escape_string($this->prQuantity)."',
                        ".(int)$this->prcolor."
                   )	
                ";
       if($result = $this->exeSQL($query))
       {
           /*if($this->updateInventoryFront((int)$data['prId'], $this->prSize, $this->prQuantity)){
            return TRUE;
           }else{
               return FALSE;
           }*/
        return TRUE;
           
       }
       else
       {
           return false;
       }
   }
   
   function addCart($data)
   {
      
       $this->set_prPrice(sanitize_all_html_input(trim($data['price'])));
       $this->set_prQuantity(sanitize_all_html_input(trim($data['quantity'])));
       $this->set_prcolor(sanitize_all_html_input(trim($data['colorId'])));
       $addProductExist=$this->loadCardProductByProductId($data['customerId'],$data['productId']);
        if($this->error)
           return false;
       if(!empty($addProductExist))
       {
          $price=$this->prPrice+$addProductExist['0']['price'];
          $quantity=$this->prQuantity+$addProductExist['0']['quantity'];
          $query="
            UPDATE
                ".__DBC_SCHEMATA_CART__."
            SET 
                    price = '".sql_real_escape_string($price)."',
                    quantity =  '".sql_real_escape_string($quantity)."'
            WHERE id = ".(int)$addProductExist['0']['id'];
       }
       else
       {
           $query="
               INSERT INTO
                   ".__DBC_SCHEMATA_CART__."
               (
                   
                   customerid,
                   productid,
                   price,
                   quantity,
                   color,
                   addedon
               )
               VALUES
               (
                    ".(int)$data['customerId'].",
                    ".(int)$data['productId'].",
                    '".sql_real_escape_string($this->prPrice)."',
                    '".sql_real_escape_string($this->prQuantity)."',
                    ".(int)$data['colorId'].",
                    '".sql_real_escape_string(date('Y-m-d h:i:s'))."'
               )	
       ";

       }
       //echo $query;
       if($result = $this->exeSQL($query))
       {
           return true;
       }
       else
       {
           return false;
       }

       
   }
   
   function loadCart($id=0,$customerid=0)
   {
      
      
       $subQuery='';
       if($id>0)
        {
            $subQuery .="AND 
                    cart.id = ".(int)$id;
        }
        if($customerid > 0){
            $subQuery .="AND 
                    cart.customerid = ".(int)$customerid;
        }
        $query="
                SELECT
                    cart.id,
                    cart.customerid,
                    cart.productid,
                    cart.price,
                    cart.quantity,
                    cart.color,
                    cart.addedon, 	
                    product.name,
                    product.image,
                    product.description,
                    customer.business_name,
                    customer.business_email,
                    customer.address,
                    customer.state,
                    customer.postcode
                FROM
                    ".__DBC_SCHEMATA_CART__." as cart 
                INNER JOIN
                    ".__DBC_SCHEMATA_PRODUCT__." as product
                ON
                    cart.productid = product.id
                INNER JOIN
                        ".__DBC_SCHEMATA_CUSTOMER_DETAILS__." as customer
                ON
                    cart.customerid = customer.id
               
                    WHERE customer.isDeleted = 0 $subQuery
                ";
        //echo $query;
        if($result = $this->exeSQL($query))
        {
            if($this->iNumRows > 0)
            {
                 while($res = $this->getAssoc( $result )){
                    $resarr[] = $res;
                 }
                 return $resarr;
            }
            else
            {
                return false;
            }
        }
        else
        {
            $this->error = true;
            $szErrorMessage = __CLASS__ . "::" . __FUNCTION__ .  "() failed to load because of a mysql error. SQL: " . $query . " MySQL Error: " . sql_error();
            $this->logError( "mysql", $szErrorMessage, "PHP", __CLASS__, __FUNCTION__, __LINE__, "critical");
            return false;
        }
   }
   function updateCart($data)
   {
       
        $this->set_prPrice(sanitize_all_html_input(trim($data['prPrice'])));
        $this->set_prQuantity(sanitize_all_html_input(trim($data['prQuantity'])));
        if($this->error)
           return false;
        $query="
            UPDATE
                ".__DBC_SCHEMATA_CART__."
            SET 
                    price = '".sql_real_escape_string($this->prPrice)."',
                    quantity =  '".sql_real_escape_string($this->prQuantity)."'
            WHERE id = ".(int)$data['id'];
       
          //echo $query;
       if($result = $this->exeSQL($query))
       {
           return true;
       }
       else
       {
           return false;
       }

       
   }
   
   function deleteCartById($customerid,$id=0)
    {
       $subq = '';
       if($id>0){
           $subq = " AND id = ".(int)$id;
       }
        $query = "DELETE 
                    FROM ".__DBC_SCHEMATA_CART__." 
                WHERE
                    customerid = ".(int)$customerid.$subq;

        if( ($result = $this->exeSQL($query )) )
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }

    }
   
    function loadProductOrder($id=0,$search='')
   {

       $searchQuery = '';
       $subQuery = '';
       if($id>0)
        {
            $subQuery .=" AND 
                    o.orderid = ".(int)$id;
        }
       if(!empty($search)){
           $searchQuery = " AND p.name LIKE '%".$search."%'";
       }
        
       $query="
                SELECT
                    o.id,
                    o.orderid,
                    o.productid,
                    o.price,
                    o.quantity,
                    o.color,
                    o.dispatched,
                    p.name,
                    p.image,
                    p.description,
                    p.quantity as availqty,
                    p.weight
                    
                FROM
                    ".__DBC_SCHEMATA_ORDER_DETAIL__." as o 
                INNER JOIN
                    ".__DBC_SCHEMATA_PRODUCT__." as p
                ON
                    o.productid = p.id
                     WHERE p.isdeleted = 0 
                    $subQuery
                    $searchQuery
                      ORDER BY p.name ASC
                ";
        //echo $query;
        if($result = $this->exeSQL($query))
        {
            if($this->iNumRows > 0)
            {
                 while($res = $this->getAssoc( $result )){
                    $resarr[] = $res;
                 }
                 return $resarr;
            }
            else
            {
                return false;
            }
        }
        else
        {
            $this->error = true;
            $szErrorMessage = __CLASS__ . "::" . __FUNCTION__ .  "() failed to load because of a mysql error. SQL: " . $query . " MySQL Error: " . sql_error();
            $this->logError( "mysql", $szErrorMessage, "PHP", __CLASS__, __FUNCTION__, __LINE__, "critical");
            return false;
        }
   }
    function updateXero($id,$xeroidnumber)
    {
        $query="
            UPDATE
                ".__DBC_SCHEMATA_ORDER__."
            SET 
                    xeroprocessed = '1',
                    XeroIDnumber =  '".sql_real_escape_string($xeroidnumber)."'
            WHERE id = ".(int)$id;

        //echo $query;
        if($result = $this->exeSQL($query))
        {
            return true;
        }
        else
        {
            return false;
        }


    }
   function updateInventoryFront($productid,$quantity)
   {
       
        
        $query="
            UPDATE
                ".__DBC_SCHEMATA_PRODUCT__."
            SET 
                    quantity = quantity - ".(int)$quantity."
            WHERE id = ".(int)$productid;
       //die($query);
          //echo $query;
       if($result = $this->exeSQL($query))
       {
           return true;
       }
       else
       {
           return false;
       }

       
   }
   
   function updateOrder($prodid,$qty,$ordid)
   {
       if($prodid>0 && $qty > 0 && $ordid > 0){
            $query="
                UPDATE
                    ".__DBC_SCHEMATA_ORDER_DETAIL__."
                SET 
                        dispatched = dispatched + ".(int)($qty)."
                WHERE orderid = ".(int)$ordid." AND productid = ".(int)$prodid;
           //die($query);
              //echo $query;
           if($result = $this->exeSQL($query))
           {
               return true;
           }
           else
           {
               return false;
           }
       }

   }
   
   function ImportOrder($customerid, $price, $ordercode, $time='')
   {
         
       $this->set_prPrice(sanitize_all_html_input(trim($price)));
       
        if($this->error)
           return false;
        $subQ = "'".sql_real_escape_string(date('Y-m-d h:i:s'))."'";
        if($time != ''){
            $subQ = "'".sql_real_escape_string(date('Y-m-d h:i:s',  strtotime($time)))."'";
        }
       $query="
               INSERT INTO
                   ".__DBC_SCHEMATA_ORDER__."
               (
                   
                   customerid,
                   price,
                   status,
                   ordercode,
                   createdon
               )
               VALUES
               (
                    ".(int)$customerid.",
                    '".sql_real_escape_string($this->prPrice)."',
                    '1',
                    '".sql_real_escape_string($ordercode)."',
                    $subQ
               )	
       ";

          //echo $query;
       if($result = $this->exeSQL($query))
       {
           $idord=$this->iLastInsertID;
           return $idord;    
       }
       else
       {
           return false;
       }
   }
    
   function loadImportedOrder($id=0,$ordercode=''){
        $subQuery='';
        if((int)$id > 0)
        {
                $subQuery="
                      WHERE id=".(int)$id."
                ";
        }
        if($ordercode != '')
        {
                $subQuery ="
                      WHERE ordercode ='".sql_real_escape_string($ordercode)."'";
        }
        if((int)$id > 0 && $ordercode != '')
        {
                $subQuery="
                      WHERE id=".(int)$id." AND ordercode ='".sql_real_escape_string($ordercode)."'";
        }

        $query="
                SELECT
                    id,
                    customerid,
                    price,
                    ordercode
                   
                FROM
                    ".__DBC_SCHEMATA_ORDER__." 
                $subQuery ORDER BY id ASC				
        ";
        //echo $query;
        if($result = $this->exeSQL($query))
        {
            if($this->iNumRows > 0)
            {
                 while($res = $this->getAssoc( $result )){
                    $resarr[] = $res;
                 }
                 return $resarr;
            }
            else
            {
                return false;
            }
        }
        else
        {
            $this->error = true;
            $szErrorMessage = __CLASS__ . "::" . __FUNCTION__ .  "() failed to load because of a mysql error. SQL: " . $query . " MySQL Error: " . sql_error();
            $this->logError( "mysql", $szErrorMessage, "PHP", __CLASS__, __FUNCTION__, __LINE__, "critical");
            return false;
        }
   }
   
   function ImportOrderPriceUpdate($ordid,$price)
   {
       if($ordid > 0){
            $query="
                UPDATE
                    ".__DBC_SCHEMATA_ORDER__."
                SET 
                        price = price + ".(double)($price)."
                WHERE id = ".(int)$ordid;
           if($result = $this->exeSQL($query))
           {
               return TRUE;
           }
           else
           {
               return false;
           }
       }

   }
   
   function LoadOrderDetailByDetailID($id){
       $query="
                SELECT
                    id,
                    orderid,
                    productid,
                    price,
                    quantity
                FROM
                    ".__DBC_SCHEMATA_ORDER_DETAIL__." 
                WHERE id = ".(int)$id;
        //echo $query;
        if($result = $this->exeSQL($query))
        {
            if($this->iNumRows > 0)
            {
                 while($res = $this->getAssoc( $result )){
                    $resarr[] = $res;
                 }
                 return $resarr;
            }
            else
            {
                return false;
            }
        }
        else
        {
            $this->error = true;
            $szErrorMessage = __CLASS__ . "::" . __FUNCTION__ .  "() failed to load because of a mysql error. SQL: " . $query . " MySQL Error: " . sql_error();
            $this->logError( "mysql", $szErrorMessage, "PHP", __CLASS__, __FUNCTION__, __LINE__, "critical");
            return false;
        }
   }
   
   function DeductOrderPriceOnProdRemove($ordid,$price)
   {
       if($ordid > 0){
            $query="
                UPDATE
                    ".__DBC_SCHEMATA_ORDER__."
                SET 
                        price = price - ".(double)($price)."
                WHERE id = ".(int)$ordid;
           if($result = $this->exeSQL($query))
           {
               return TRUE;
           }
           else
           {
               return false;
           }
       }

   }
   
   function DeleteOrderProduct($ordDetid)
   {
       if($ordDetid > 0){
            $query="
                DELETE FROM
                    ".__DBC_SCHEMATA_ORDER_DETAIL__."
                WHERE id = ".(int)$ordDetid;
           if($result = $this->exeSQL($query))
           {
               return TRUE;
           }
           else
           {
               return false;
           }
       }

   }
   function loadCardProductByProductId($customerId,$productId){
    $query="
                SELECT
                    id,
                    customerid,
                    productid,
                    price,
                    quantity
                FROM
                    ".__DBC_SCHEMATA_CART__." 
                WHERE customerid = ".(int)$customerId." AND productid = ".(int)$productId;
                
        //echo $query;
        if($result = $this->exeSQL($query))
        {
            if($this->iNumRows > 0)
            {
                 while($res = $this->getAssoc( $result )){
                    $resarr[] = $res;
                 }
                 return $resarr;
            }
            else
            {
                return false;
            }
        }
        else
        {
            $this->error = true;
            $szErrorMessage = __CLASS__ . "::" . __FUNCTION__ .  "() failed to load because of a mysql error. SQL: " . $query . " MySQL Error: " . sql_error();
            $this->logError( "mysql", $szErrorMessage, "PHP", __CLASS__, __FUNCTION__, __LINE__, "critical");
            return false;
        }
   }
   
   
    function set_prPrice($value)
    {
        $this->prPrice = $this->validateInput( $value, __VLD_CASE_ANYTHING__, "prPrice", "Price Name", false, 50, true );
    }
    function set_prSize($value)
    {
        $this->prSize = $this->validateInput( $value, __VLD_CASE_ANYTHING__, "prSize", "Size Name", false, 50, true );
    }
  function set_prQuantity($value)
    {
        $this->prQuantity = $this->validateInput( $value, __VLD_CASE_ANYTHING__, "prQuantity", "Quantity Name", false, 50, true );
    }
    function set_prcolor($value)
    {
        $this->prcolor = $this->validateInput( $value, __VLD_CASE_ANYTHING__, "prcolor", "Color Name", false, 50, true );
    }
  
}

?>
