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
require_once( __APP_PATH_CLASSES__ . "/product.class.php" );


Class cCommon extends cDatabase
{
	function __construct()
	{
		parent::__construct();
		// establish a Database connection.
		//$this->connect( __DBC_USER__, __DBC_PASSWD__, __DBC_SCHEMATA__, __DBC_HOST__);
		return true;
	}
   function loadCountry($id=0){
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
                    sortname,
                    name
                FROM
                    ".__DBC_SCHEMATA_COUNTRIES__." 
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
   
   function loadState($id=0,$countryid=0){
        $subQuery='';
        if((int)$id > 0)
        {
                $subQuery="
                      WHERE id=".(int)$id."
                ";
        }
        if((int)$countryid > 0)
        {
                $subQuery="
                      WHERE country_id=".(int)$countryid."
                ";
        }

       $query="
                SELECT
                    id,
                    name
                    
                FROM
                    ".__DBC_SCHEMATA_STATE__." 
                $subQuery 				
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
   
   function loadCity($id=0,$stateid=0){
        $subQuery='';
        if((int)$id > 0)
        {
                $subQuery="
                      WHERE id=".(int)$id."
                ";
        }
        if((int)$stateid > 0)
        {
                $subQuery="
                      WHERE state_id=".(int)$stateid."
                ";
        }

        $query="
                SELECT
                    id,
                    name,
                    state_id
                FROM
                    ".__DBC_SCHEMATA_CITIES__." 
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
   
   function loadGroups($id=0, $parentid=0, $withparent=true){
        $subQuery='';
        if($withparent == TRUE){
            $subQuery="
                      WHERE parentid=".(int)$parentid."
                ";
        }elseif((int)$id > 0){
            $subQuery="
                      WHERE id=".(int)$id."
                ";
        }
        if((int)$id > 0 && $withparent == TRUE)
        {
                $subQuery .="
                      AND id=".(int)$id."
                ";
        }
        
        $query="
                SELECT
                    id,
                    name,
                    description,
                    parentid
                FROM
                    ".__DBC_SCHEMATA_GROUP__." 
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
   
   function deleteGroupById($id)
    {
       
       $childCats = $this->loadGroups(0,$id);
       if(!empty ($childCats)){
           foreach ($childCats as $childcategory){
               
               $this->DeleteThisCategory($childcategory['id']);
           }
       }
       if($this->DeleteThisCategory($id)){
           return true;
       }else{
           return false;
       }
    }
     
    
   function loadColors($id=0){
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
                    name,
                    code
                FROM
                    ".__DBC_SCHEMATA_COLORS__." 
                $subQuery ORDER BY name ASC				
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
   
   function loadRoals($id=0){
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
                    role
                FROM
                    ".__DBC_SCHEMATA_ROLE__." 
                $subQuery 				
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
   
   function DeleteThisCategory($catid){
       $kProduct = new cProduct();
       $getThisCatProdArr = $kProduct->viewProductByGroupID($catid);
       if(!empty ($getThisCatProdArr)){
           foreach ($getThisCatProdArr as $prodDet){
               $kProduct->deleteProductById($prodDet['id']);
           }
       }
       $query = "DELETE 
                        FROM ".__DBC_SCHEMATA_GROUP__." 
                    WHERE
                        id = ".(int)$catid;

            if( ($result = $this->exeSQL($query )) )
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
   }
}


?>
