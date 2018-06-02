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
require_once( __APP_PATH_CLASSES__ . "/common.class.php" );


Class cProduct extends cUser
{
	function __construct()
	{
		parent::__construct();
		// establish a Database connection.
		//$this->connect( __DBC_USER__, __DBC_PASSWD__, __DBC_SCHEMATA__, __DBC_HOST__);
		return true;
	}
	
   
    
   function loadProducts($id=0,$orderBy='',$sortValue='',$data='')
   {
         $kCommon = new cCommon();
       $searchQuery;
       if($data)
        {
           if($data['szSearchText'])
           {
               $searchQuery .="
                        AND
                            p.name LIKE '%".sql_real_escape_string($data['szSearchText'])."%'
                                ";
           }
           if($data['prGroup'])
           {
               $searchQuery .="
                        AND
                            g.groupid =".(int)$data['prGroup']."
                                ";
           }
           if($data['prParentGroup'] && $data['prGroup']=='')
           {
                if($data['prParentGroup'])
                {
                    $childcategories = $kCommon->loadGroups(0,$data['prParentGroup']);
                    foreach ($childcategories as $childcategoriesData)
                    {
                        $childIdAry[]=$childcategoriesData['id'];
                    }
                      $subCatId=implode(',', $childIdAry); 
                }           
                   
                    $searchQuery .="
                                    AND
                                        g.groupid IN(".$subCatId.")
                                    ";
           }
            
            
        }
        $subQuery='';
        if((int)$id > 0)
        {
                $subQuery="
                      AND p.id='".(int)$id."'
                ";
        }
        $sortQuery='';
		if($orderBy!='' && $sortValue!='')
                {
                     $sortQuery="
                        ORDER BY
                        $orderBy $sortValue 
                    ";
                }else{
                    $sortQuery="
                        ORDER BY p.id ASC";
                }
                 $query="
			SELECT 
		            p.id,
                            p.name,
                            p.description,
                            p.image,
                            p.weight,
                            p.quantity,
                            p.color,
                            g.productid,
                            g.groupid
			FROM
				".__DBC_SCHEMATA_PRODUCT__." as p
                        INNER JOIN
				".__DBC_SCHEMATA_PRODUCT_GROUP__." as g
			ON
				p.id = g.productid
			WHERE 
                                p.isactive = '1' AND p.isdeleted = '0'  
                        $subQuery
                        $searchQuery
                        $sortQuery
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
   
   function addSizeQuantity($small,$medium,$large,$prodid){
       $query="
               INSERT INTO
                   ".__DBC_SCHEMATA_SIZE__."
               (
                   
                   small,
                   medium,
                   large,
                   productid
               )
               VALUES
               (
                   ".(int)$small.",
                   ".(int)$medium.",
                   ".(int)$large.",
                   ".(int)$prodid."
               )	
       ";

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
   function addSizeWeight($small,$medium,$large,$prodid){
       $query="
               INSERT INTO
                   ".__DBC_SCHEMATA_WEIGHT__."
               (
                   
                   small,
                   medium,
                   large,
                   productid
               )
               VALUES
               (
                   ".(int)$small.",
                   ".(int)$medium.",
                   ".(int)$large.",
                   ".(int)$prodid."
               )	
       ";
       if($result = $this->exeSQL($query))
       {
            return true;
       }
       else
       {
           return false;
       }
   }
   function addProductGroup($prodid,$groupid){
	   
	   
        $query="
               INSERT INTO
                   ".__DBC_SCHEMATA_PRODUCT_GROUP__."
               (
                   groupid,
                   productid
                   
               )
               VALUES
               (
			         '".sql_real_escape_string($groupid)."',
                   ".(int)$prodid."
                  
               )	
       ";
       if($result = $this->exeSQL($query))
       {
            return true;
       }
       else
       {
           return false;
       }
   }
   
    function addNewProduct($data,$colors)
   {
         
       $this->set_prGroup(sanitize_all_html_input(trim($data['prGroup'])));
       $this->set_prName(sanitize_all_html_input(trim($data['prName'])));
       $this->set_prDescription(sanitize_all_html_input(trim($data['prDescription'])));
       $this->set_prImage1(sanitize_all_html_input(trim($data['prImage1'])));
       $this->set_prSmall(sanitize_all_html_input(trim($data['prSmall'])));
       $this->set_prSmallWeight(sanitize_all_html_input(trim($data['prSmallWeight'])));
       if($colors == 'null'){
           $this->addError("prColor","Product color is required.");
       }
       
       if($this->error)
           return false;

       $query="
               INSERT INTO
                   ".__DBC_SCHEMATA_PRODUCT__."
               (
                   
                   name,
                   description,
                   image,
                   color,
                   quantity,
                   weight,
                   createdon
               )
               VALUES
               (
                   '".sql_real_escape_string($this->prDescription)."',
                   '".sql_real_escape_string($this->prDescription)."',
                   '".sql_real_escape_string($this->prImage1)."',
                   '".sql_real_escape_string($colors)."',
                   ".(int)($this->prSmall).",
                   ".(int)($this->prSmallWeight).",
                   '".sql_real_escape_string(date('Y-m-d h:i:s'))."'
               )	
       ";

          //echo $query;
       if($result = $this->exeSQL($query))
       {
            $prodid=$this->iLastInsertID;
                
                    if($this->prGroup > 0){
                            $this->addProductGroup($prodid,  $this->prGroup);
                                $customerArr = $this->loadCustomers();
                                if(!empty ($customerArr)){
                                    foreach ($customerArr as $custo){
                                            $this->addPrice($prodid, '0.00', $custo['id']);
                                    }
                                }
                            
                    }
				
            
            return true;
       }
       else
       {
           return false;
       }
   }
    
    
  function GetProductCustomers($customerid=0,$prodid=0){
      $subq = '';
      if($customerid > '0'){
          $subq = "c.id = ".(int)$customerid;
      }
      if($prodid > '0'){
          $subq = "p.productid = ".(int)$prodid;
      }
		$query="
			SELECT 
				c.id as id,
				c.userid as userid,
				c.business_name as business_name,
				c.contact_name as contact_name
			FROM
				".__DBC_SCHEMATA_CUSTOMER_DETAILS__." as c
                        INNER JOIN
				".__DBC_SCHEMATA_PRODUCT_GROUP__." as p
			ON
				p.groupid = c.groupid
			WHERE 
                                ".$subq." AND c.isDeleted = '0'  
			ORDER BY c.id DESC
		";
		//echo $query;
		if($result = $this->exeSQL($query))
		{
			if($this->iNumRows > 0)
			{
				while($row = $this->getAssoc( $result ))
				{
					$data[] = $row;;
				}
				return $data;
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
        
        function getProdPriceByProdID($prodid=0,$custid=0){
        $subQuery='';
        if((int)$prodid > 0)
        {
                $subQuery="
                      WHERE productid = ".(int)$prodid;
        }
        if((int)$custid > 0)
        {
                $subQuery="
                      WHERE customerid = ".(int)$custid;
        }
        if((int)$prodid > 0 && (int)$custid > 0){
            $subQuery="
                      WHERE customerid = ".(int)$custid." AND productid = ".(int)$prodid;
        }

          $query="
                SELECT
                    id,
                    productid,
                    price,
                    customerid
                FROM
                    ".__DBC_SCHEMATA_PRICE__." 
                $subQuery ORDER BY id DESC				
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
        
        function addProductPrice($data){
            $totalCount = $data['totalCustomerCount'];
            $this->set_prProduct(sanitize_all_html_input(trim($data['prProduct'])));
            
            if($this->error)
           return false;
            $sucesscount = 0;
            for($j = 0; $j<$totalCount;$j++){
                if($data['update'] == '0'){
                    if($this->addPrice($this->prProduct,  $data['prCustomer'.$j], $data['prCustomerName'.$j])){
                        $sucesscount++;
                    }
                }elseif($data['update'] == '1'){
                    if($this->updatePrice($this->prProduct,  $data['prCustomer'.$j], $data['prCustomerName'.$j])){
                        $sucesscount++;
                    }
                }
                
            }
            if($sucesscount == $totalCount){
                return TRUE;
            }
            
        }
    function updatePrice($prodid,$price,$customerid){
        
       $query="
               UPDATE
                   ".__DBC_SCHEMATA_PRICE__." SET price = ".$price." WHERE productid = ".$prodid." AND customerid = ".$customerid;
       if($result = $this->exeSQL($query))
       {
           return true;
       }
       else
       {
           return false;
       }
   }
    
    function addPrice($prodid,$price,$customerid){
        
       $query="
               INSERT INTO
                   ".__DBC_SCHEMATA_PRICE__."
               (
                   
                   productid,
                   price,
                   customerid
               )
               VALUES
               (
                   ".(int)$prodid.",
                   '".sql_real_escape_string($price)."',
                   ".(int)$customerid."
                   
               )	
       ";
       if($result = $this->exeSQL($query))
       {
           return true;
       }
       else
       {
           return false;
       }
   }
   
   function CheckPriceAssign($id=0)
   {
        $subQuery='';
        if((int)$id > 0)
        {
                $subQuery="
                      WHERE productid = ".(int)$id;
        }

        $query="
                SELECT
                    id,
                    productid,
                    price,
                    customerid
                FROM
                    ".__DBC_SCHEMATA_PRICE__." 
                $subQuery ORDER BY id DESC				
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
    
    function loadSizeQuantity($productid)
   {
         $query="
                SELECT
                    id,
                    small,
                    medium,
                    large
                   
                FROM
                    ".__DBC_SCHEMATA_SIZE__." 
     WHERE productid='".(int)$productid."'";
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

    function orderProductList()
    {
        $query="
                SELECT
                    DISTINCT(productid) as prodid
                   
                FROM
                    ".__DBC_SCHEMATA_ORDER_DETAIL__;
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
    function orderCustomerList()
    {
        $query="
                SELECT
                    DISTINCT(customerid) as custid
                   
                FROM
                    ".__DBC_SCHEMATA_ORDER__;
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
function loadSizeWeight($productid)
   {
        

         $query="
                SELECT
                    id,
                    small,
                    medium,
                    large
                   
                FROM
                    ".__DBC_SCHEMATA_WEIGHT__." 
     WHERE productid='".(int)$productid."'";
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
    
   function loadProductGroup($productid){
       
       $query="
                SELECT
                    pg.id as id,
                    pg.groupid as groupid,
                    pg.productid as productid,
                    p.name as name
                   
                   
                FROM
                    ".__DBC_SCHEMATA_PRODUCT_GROUP__." as pg
               INNER JOIN
				".__DBC_SCHEMATA_GROUP__." as p
			ON
				pg.groupid = p.id
			WHERE pg.productid=".(int)$productid;
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
   
   function loadProductCustomerPrice($productid){
       
       $query="
                SELECT
                    pc.price as price,
                    c.id as id,
                    c.business_name as business_name
                   
                   
                FROM
                    ".__DBC_SCHEMATA_PRICE__." as pc
               INNER JOIN
				".__DBC_SCHEMATA_CUSTOMER_DETAILS__." as c
			ON
				pc.customerid = c.id
			WHERE pc.productid=".(int)$productid." AND c.isDeleted = 0";
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
   
   function updateProduct($data,$colors)
   {
         
       $this->set_prGroup(sanitize_all_html_input(trim($data['prGroup'])));
       $this->set_prName(sanitize_all_html_input(trim($data['prName'])));
       $this->set_prDescription(sanitize_all_html_input(trim($data['prDescription'])));
       $this->set_prImage1(sanitize_all_html_input(trim($data['prImage1'])));
       $this->set_prSmall(sanitize_all_html_input(trim($data['prSmall'])));
       $this->set_prSmallWeight(sanitize_all_html_input(trim($data['prSmallWeight'])));
	   $prodid=$data['id'];
       if($colors == 'null'){
           $this->addError("prColor","Product color is required.");
       }
       if($this->error)
           return false;
	   
	    $query = "UPDATE
                        ".__DBC_SCHEMATA_PRODUCT__." 
                SET 
                    name = '".sql_real_escape_string($this->prDescription)."',
					description =  '".sql_real_escape_string($this->prDescription)."',
					image       =  '".sql_real_escape_string($this->prImage1)."',
					color       =  '".sql_real_escape_string($colors)."',
                                        quantity = ".(int) $this->prSmall.",
                                        weight = ".(int) $this->prSmallWeight.",
					updatedon   =    '".sql_real_escape_string(date('Y-m-d h:i:s'))."'
                WHERE
                    id ='".sql_real_escape_string(trim($prodid))."'
                
        ";

        if($result = $this->exeSQL($query))
       {
            if($this->prGroup > 0){
                if($this->updateProductGroup($prodid, (int)$this->prGroup)){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
       }
       else
       {
           return false;
       }
   }
    function UpadetSizeQuantity($small,$medium,$large,$prodid){
	  
	  $query = "UPDATE
                        ".__DBC_SCHEMATA_SIZE__." 
                SET 
                    small = ".(int)$small.",
					medium =".(int)$medium.",
					large   =   ".(int)$large."
				WHERE
                    productid = ".(int)$prodid."
                
        ";
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
    function updateSizeWeight($small,$medium,$large,$prodid){
	  
	    $query = "UPDATE
                        ".__DBC_SCHEMATA_WEIGHT__." 
                SET 
                    small = ".(int)$small.",
					medium =".(int)$medium.",
					large   =   ".(int)$large."
				WHERE
                    productid = ".(int)$prodid."
                
        ";
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
   
   function updateProductGroup($prodid,$groupid){
	   
	   $query = "UPDATE
                        ".__DBC_SCHEMATA_PRODUCT_GROUP__." 
                SET 
                    groupid =  '".sql_real_escape_string($groupid)."'
				WHERE
                    productid = ".(int)$prodid."
                
        ";
	    if($result = $this->exeSQL($query))
        {
            return true;
        }
       else
       {
           return false;
       }
   }
   function deleteProductById($id)
    {
        $query = "UPDATE ".__DBC_SCHEMATA_PRODUCT__." 
                    SET 
                        isdeleted = 1
                          
                   WHERE
                        id = ".(int)$id;

        if( ($result = $this->exeSQL($query )) )
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }

    }
	function getGroupNameById($id)
   {
        $query="
                SELECT
                    id,
                    name,
                    description
                FROM
                    ".__DBC_SCHEMATA_GROUP__." 
     WHERE id='".(int)$id."'";
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
    
   function updateQuantity($data){
        
       $query="
               UPDATE
                   ".__DBC_SCHEMATA_PRODUCT__." 
                       
                SET 
                    quantity = ".(int)$data['priSmall']."
                    WHERE id = ".(int)$data['priProduct'];
       if($result = $this->exeSQL($query))
       {
           return true;
       }
       else
       {
           return false;
       }
   }
   
   function loadProductGroup1($productid){
       
       $query="
                SELECT
                    id,
                    groupid
                FROM
                    ".__DBC_SCHEMATA_PRODUCT_GROUP__."
			WHERE productid=".(int)$productid;
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
   
   function loadCustomersByGroupid($groupid){
       
       $query="
                SELECT
                    id,
                    userid,
                    business_name
                FROM
                    ".__DBC_SCHEMATA_CUSTOMER_DETAILS__."
			WHERE groupid=".(int)$groupid." AND isdeleted=0";
       
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
   
   
   function changePrice($data)
   {
       if($this->updatePrice($data['productId'], $data['priSmall'], $data['customerId'])){
           return true;
       }else{
           return false;
       }    
    }

function viewProductForCustomer($customerid,$iPageNumber=0,$iRowPerPage=1){
       
           $query1 = "SELECT 
                            DISTINCT(p.id)
                      FROM 
                            ".__DBC_SCHEMATA_PRODUCT__." as p 
                      INNER JOIN 
                            ".__DBC_SCHEMATA_PRICE__." as pr on p.id = pr.productid
                      WHERE
                            p.isdeleted = 0 AND pr.customerid = ".(int)$customerid." ORDER BY pr.price ASC
                " . ((int)$iPageNumber > 0 ? "LIMIT " . (($iPageNumber - 1)*$iRowPerPage) . ", $iRowPerPage" : "") . "
    	";
           //$res1 = mysql_query($query1);
            if($result1 = $this->exeSQL($query1))
            {
                if($this->iNumRows > 0)
                {
                     while($res = $this->getAssoc( $result1 )){
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
   
   function getCustomerPriceBySize($customerid, $productid){
       $query = "SELECT 
                        price
                 FROM ".__DBC_SCHEMATA_PRICE__."
                 WHERE 
                        productid = ".(int)$productid."
                 AND 
                        customerid = ".(int)$customerid;
       if($result = $this->exeSQL($query)){
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
   
   function getProdByGroupID($groupid){
       $query = "SELECT 
                                    productid 
                             FROM 
                                    ".__DBC_SCHEMATA_PRODUCT_GROUP__." 
                             WHERE 
                                    groupid LIKE '%,".(int)$groupid."' 
                             OR 
                                    groupid LIKE '".(int)$groupid.",%'
                             OR 
                                    groupid = '".(int)$groupid."' 
                             OR 
                                    groupid LIKE '%,".(int)$groupid.",%'";
       if($result1 = $this->exeSQL($query))
            {
                if($this->iNumRows > 0)
                {
                     while($res = $this->getAssoc( $result1 )){
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
   
   function viewProductByGroupID($groupid,$iPageNumber=0,$iRowPerPage=1){
       
           $query1 = "SELECT 
                            p.id,
                            p.name,
                            p.description,
                            p.image,
                            p.weight,
                            p.quantity,
                            p.color
                      FROM 
                            ".__DBC_SCHEMATA_PRODUCT__." as p 
                      INNER JOIN 
                            ".__DBC_SCHEMATA_PRODUCT_GROUP__." as g on p.id = g.productid
                      WHERE
                            p.isdeleted = 0 AND g.groupid = ".(int)$groupid." ORDER BY p.id DESC
                " . ((int)$iPageNumber > 0 ? "LIMIT " . (($iPageNumber - 1)*$iRowPerPage) . ", $iRowPerPage" : "") . "
    	";
           //$res1 = mysql_query($query1);
            if($result1 = $this->exeSQL($query1))
            {
                if($this->iNumRows > 0)
                {
                     while($res = $this->getAssoc( $result1 )){
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
   
   function generateProdCode($matchCar){
       $query = "SELECT name FROM ".__DBC_SCHEMATA_PRODUCT__." WHERE isdeleted = 0 AND name LIKE '".$matchCar."%' ORDER BY name DESC";
       if($result1 = $this->exeSQL($query))
        {
            if($this->iNumRows > 0)
            {
                 while($res = $this->getAssoc( $result1 )){
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
   
   function loadProductIDbyName($productname)
   {
        

         $query="
                SELECT
                    id
                   
                FROM
                    ".__DBC_SCHEMATA_PRODUCT__." 
     WHERE name='".trim($productname)."'";
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
   function ajax_search_products($search_text,$orderid)
   {
        if(!empty ($search_text))
        {
            $query="
                SELECT
                    p.id,
                    p.name,
                    p.description
                FROM
                    ".__DBC_SCHEMATA_PRODUCT__." as p
                WHERE
                    description LIKE '".sql_real_escape_string($search_text)."%'
				AND
					id NOT IN (SELECT o.productid FROM ".__DBC_SCHEMATA_ORDER_DETAIL__." as o WHERE o.orderid = ".(int)$orderid.")
			";
			if($result = $this->exeSQL($query))
			{
				if($this->iNumRows > 0)
				{
					while($row = $this->getAssoc( $result ))
					{
						if($string)
							$string .= '<li class="divider"></li>';
						$string .= '<li><a href="javascript:void(0)" onclick="fillPartIdOnInput(\''.$row['id'].'\',\''.$row['description'].'\')"><b>'.$row['description']."</b></li>";
						
					 }
					 return $string;
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
   }
   
   function set_prName($value)
    {
        $this->prName = $this->validateInput( $value, __VLD_CASE_ANYTHING__, "prName", "Product Code", false, 50, true );
    }
   function set_prDescription($value)
    {
        $this->prDescription = $this->validateInput( $value, __VLD_CASE_ANYTHING__, "prDescription", "Product Name", false, 255, true );
    }
    function set_prImage1($value)
    {
        $this->prImage1 = $this->validateInput( $value, __VLD_CASE_ANYTHING__, "prImage1", "Product Image", false, 255, false );
    }
    function set_prColor($value)
    {
        $this->prColor = $this->validateInput( $value, __VLD_CASE_ANYTHING__, "prColor", "Colors", false, 255, true );
    }
    function set_prSmall($value)
    {
        $this->prSmall = $this->validateInput( $value, __VLD_CASE_NUMERIC__, "prSmall", "Quantity", 0, 1000, false );
    }
    function set_prMedium($value)
    {
        $this->prMedium = $this->validateInput( $value, __VLD_CASE_NUMERIC__, "prMedium", "Quantity", 0, 1000, false );
    }
    function set_prLarge($value)
    {
        $this->prLarge = $this->validateInput( $value, __VLD_CASE_NUMERIC__, "prLarge", "Quantity", 0, 1000, false );
    }
    function set_prSmallWeight($value)
    {
        $this->prSmallWeight = $this->validateInput( $value, __VLD_CASE_NUMERIC__, "prSmallWeight", "Weight", 0, false, false );
    }
    function set_prMediumWeight($value)
    {
        $this->prMediumWeight = $this->validateInput( $value, __VLD_CASE_NUMERIC__, "prMediumWeight", "Weight", 0, false, false );
    }
    function set_prLargeWeight($value)
    {
        $this->prLargeWeight = $this->validateInput( $value, __VLD_CASE_NUMERIC__, "prLargeWeight", "Weight", 0, false, false );
    }
    
   function set_prProduct($value)
    {
        $this->prProduct = $this->validateInput( $value, __VLD_CASE_NUMERIC__, "prProduct", "Product", 1, 100, false );
    }
    
    function set_prProductPrice($value,$i)
    {
        $this->prCustomer.$i = $this->validateInput( $value, __VLD_CASE_ANYTHING__, "prCustomer".$i, "Price", 0, 100, false );
    }
    function set_prGroup($value)
    {
        $this->prGroup = $this->validateInput( $value, __VLD_CASE_NUMERIC__, "prGroup", "Category", 1, false, true );
    }
    

}

?>
