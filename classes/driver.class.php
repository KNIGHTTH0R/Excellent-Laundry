<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of driver
 *
 * @author WHIZ 18
 */

if( !defined( "__APP_PATH__" ) )
define( "__APP_PATH__", realpath( dirname( __FILE__ ) . "/../../" ) );

require_once( __APP_PATH__ . "/includes/constants.php" );
require_once( __APP_PATH_CLASSES__ . "/database.class.php" );

Class cDriver extends cDatabase
{
    function __construct()
    {
	parent::__construct();
	// establish a Database connection.
	//$this->connect( __DBC_USER__, __DBC_PASSWD__, __DBC_SCHEMATA__, __DBC_HOST__);
	return true;
    }
    
    function allAddRunSlot($weakId,$slotValue,$customerId)
    {
       
        if(!empty($weakId))
        {
            $addSlot=$this->addRunSlot($weakId,$slotValue,$customerId);
        }
        
        if($addSlot)
        {
            return TRUE;
        }
        else
        {
           return FALSE;
        }
        
    }
        
    /**
    * Function to Add User
    * @param Array $data
    * @return boolean
    */
    function addRunSlot($weekId,$slotValue,$customerId)
    {
        //$this->set_runSlotMonday(sanitize_all_html_input(trim($data['runSlotMonday'])));
       
         $query="
               INSERT INTO
                   ".__DBC_SCHEMATA_CUSTOMER_WEEKDAY_SLOT__."
               (
                   custid,
                   weekid,
                   slot,
                   isdeleted
               )
               VALUES
               (
                    '".sql_real_escape_string($customerId)."',
                   '".sql_real_escape_string($weekId)."',
                   '".sql_real_escape_string($slotValue)."',
                   '1'
               )	
       ";

          //echo $query;
       if($result = $this->exeSQL($query)){
           return TRUE;
       }else{
           return FALSE;
       }
    }
    
    
    /**
    * Function to Get All Customers By Week Id
    * @param Array $data
    * @return Array
    */
    function getAllCustomersByWeekid($weekId=0,$custId=0,$assign=0,$unassign=0,$delted)
    {
        $subQuery='';
        if((int)$weekId > 0)
        {
                $subQuery="
                   AND   
                        week_slot.weekid = '".$weekId."'
                    AND
                        week_slot.driverid='0'
                ";
        }
        $deletedQuery='';
        if((int)$delted > 0)
        {
                $deletedQuery="
                   AND   
                        week_slot.isdeleted = '0'
                   
                ";
        }
         $subQueryCustId='';
        if((int)$custId > 0)
        {
                $subQuery="
                    AND   
                       week_slot.custid = '".$custId."'
                ";
        }
        $subQueryassign='';
        if((int)$assign > 0)
        {
                $subQuery="
                   AND   
                        week_slot.custid = '".$custId."'
                    AND
                       week_slot.driverid >='1'
                   
                ";
        }
        $subQueryunassign='';
        if((int)$unassign > 0)
        {
                $subQuery="
                    AND   
                        week_slot.custid = '".$custId."'
                    AND
                        week_slot.driverid <'1'
                   
                ";
        }
          $query="
                    SELECT
                        DISTINCT(week_slot.custid),
                        cust.id,
                        cust.userid,
                        cust.business_name,
                        cust.contact_name,
                        cust.phoneno,
                        cust.mobileno,
                        cust.contact_email,
                        cust.business_email 	,
                        cust.address,
                        cust.state,
                        cust.postcode,
                        cust.contract_start,
                        cust.contract_end,
                        cust.groupid,
                        cust.isDeleted,
                        week_slot.custid,
                        week_slot.weekid,
                        week_slot.slot,
                        week_slot.driverid 	
                    FROM
                        ".__DBC_SCHEMATA_CUSTOMER_DETAILS__." as cust
                    JOIN
                        ".__DBC_SCHEMATA_CUSTOMER_WEEKDAY_SLOT__." as week_slot
                    ON
                        week_slot.custid=cust.id
                    WHERE
                        cust.isDeleted = '0'
                    $subQuery
                    $subQueryassign
                    $subQueryunassign
                    $subQueryCustId
                    $deletedQuery
                    ORDER BY week_slot.slot ASC
                 
                ";

            if( ($result = $this->exeSQL($query )) )
            {
                if($this->iNumRows > 0)
                {
                    while($res = $this->getAssoc( $result ))
                    {
                        $resarr[] = $res;
                    }
                    return $resarr;
                }
                else
                {
                    return false;
                }
            }	
    
    }
    
    function scheduleDriverByCustomer($data,$customerData)
    {
        foreach($data as $key=>$value)
        {
           $driverId=$data[$key];
           $customerId=$customerData[$key];
           $weekDayId=$data['weekId'];
           $addDriver=$this->addScheduleDrive($driverId,$customerId,$weekDayId);
        }
        
        if($addDriver)
        {
            return TRUE;
        }
        else
        {
           return FALSE;
        }
        
    }
        
    /**
    * Function to Add Schedule Driver
    * @param Array $data
    * @return boolean
    */
    function addScheduleDrive($driverId,$customerId,$weekDayId)
    {
        //$this->set_runSlotMonday(sanitize_all_html_input(trim($data['runSlotMonday'])));
       
        $query = "UPDATE
                        ".__DBC_SCHEMATA_CUSTOMER_WEEKDAY_SLOT__." 
                SET 
                     driverid = '".sql_real_escape_string($driverId)."'
                WHERE
                    custid ='".sql_real_escape_string(trim($customerId))."'
                AND
                    weekid ='".sql_real_escape_string(trim($weekDayId))."'
        ";
        //echo $query;
       if($result = $this->exeSQL($query)){
           return TRUE;
       }else{
           return FALSE;
       }
    }
    
    /**
    * Function to Get All Customers By Week Id
    * @param Array $data
    * @return Array
    */
    function getAllScheduleUser($weekId)
    {
       
        $query="
                SELECT 
                    id,
                    custid,
                    weekid,
                    slot,
                    driverid
                    
                FROM 
                    ".__DBC_SCHEMATA_CUSTOMER_WEEKDAY_SLOT__."
                WHERE   
                     weekid = '".$weekId."'
                 AND
                    driverid='0'
                 
                ";

            if( ($result = $this->exeSQL($query )) )
            {
                if($this->iNumRows > 0)
                {
                    while($res = $this->getAssoc( $result ))
                    {
                        $resarr[] = $res;
                    }
                    return $resarr;
                }
                else
                {
                    return false;
                }
            }	
    
    }
    
       /**
     * Function to get  weekDays by id
     * @return Array
     */
    function loadWeekDays($id)
    {
       $query="
                SELECT 
                    id,
                    day
                FROM 
                    ".__DBC_SCHEMATA_WEEKDAYS__."
                Where
                
                  id='".$id."'
               
                ";
            if($result = $this->exeSQL($query))
            {
                if($this->iNumRows > 0)
                {
                    $row = $this->getAssoc($result);
                    return $row;
                }
                else
                {
                    return false;
                }
            }
        }  
    function chekRunSlotAlreadtExit($custid=0,$weekid=0)
    {
        $subQuery='';
        if((int)$custid > 0 && (int)$weekid > 0 )
        {
                $subQuery="
                    WHERE   
                        custid = '".$custid."'
                    AND
                       weekid = '".$weekid."'
                   
                ";
        }
        $query="
                SELECT 
                    id,
                    custid,
                    weekid,
                    slot,
                    driverid
                FROM 
                    ".__DBC_SCHEMATA_CUSTOMER_WEEKDAY_SLOT__."
                $subQuery
               
                ";
            if($result = $this->exeSQL($query))
            {
                if($this->iNumRows > 0)
                {
                    $row = $this->getAssoc($result);
                    return true;
                }
                else
                {
                    return false;
                }
        }
        }
        
        function getRunSlotcustIdWeekId($custid=0,$weekid=0)
        {
            $subQuery='';
            if((int)$custid > 0 && (int)$weekid > 0 )
            {
                $subQuery="
                    WHERE   
                        custid = '".$custid."'
                    AND
                       weekid = '".$weekid."'
                   
                ";
        }
        if((int)$custid > 0 && (int)$weekid =='' )
        {
                $subchekQuery="
                    WHERE   
                        custid = '".$custid."'
                ";
        }
         $query="
                SELECT 
                    id,
                    custid,
                    weekid,
                    slot,
                    driverid
                FROM 
                    ".__DBC_SCHEMATA_CUSTOMER_WEEKDAY_SLOT__."
                $subQuery
                $subchekQuery
               
                ";
           if( ($result = $this->exeSQL($query )) )
            {
                if($this->iNumRows > 0)
                {
                    while($res = $this->getAssoc( $result ))
                    {
                        $resarr[] = $res;
                    }
                    return $resarr;
                }
                else
                {
                    return false;
                }
            }	
        }
        
    function allEditRunSlot($data,$customerId,$driverData)
    {
       
        for ($i = 1; $i <= 7; $i++) 
        {
            if($data[$i]!=='0' && $data[$i]!=='')
            {
                
                $editSlot=$this->editRunSlot($i,$data[$i],$customerId,$driverData[$i]);
            }
            
        } 
        
        if($editSlot)
        {
            return TRUE;
        }
        else
        {
           return FALSE;
        }
        
    }
    
    function allDeleteRunSlot($data,$customerId,$deleted)
    {
       
        for ($i = 1; $i <= 7; $i++) 
        {
            $editSlot=$this->editRunSlot($i,$data[$i],$customerId,$deleted);
        } 
        
        if($editSlot)
        {
            return TRUE;
        }
        else
        {
           return FALSE;
        }
        
    }
        
    /**
    * Function to Add User
    * @param Array $data
    * @return boolean
    */
    function editRunSlot($weekId,$slotValue,$customerId,$driverid=0)
    {
         $subQuery='';
        if($driverid > 0){
                $subQuery="
                  driverid = ".(int)$driverid.",";
        }else{
            $subQuery="
                driverid = '0',";
        }
        if($slotValue > 0){
            $subQuery .= "isdeleted = '0'";
        }else{
            $subQuery .= "isdeleted = '1'";
        }
        //$this->set_runSlotMonday(sanitize_all_html_input(trim($data['runSlotMonday'])));
       
         $query = "UPDATE
                        ".__DBC_SCHEMATA_CUSTOMER_WEEKDAY_SLOT__." 
                SET 
                    slot = '".sql_real_escape_string($slotValue)."',
                    $subQuery
                WHERE
                    custid ='".sql_real_escape_string(trim($customerId))."'
                AND
                    weekid ='".sql_real_escape_string(trim($weekId))."'
               
                    
        ";

          //echo $query;
       if($result = $this->exeSQL($query)){
           
           return TRUE;
       }else{
           return FALSE;
       }
    }
    
    function updateScheduleDriverDetails(){
        $query = "UPDATE
                        ".__DBC_SCHEMATA_CUSTOMER_WEEKDAY_SLOT__." 
                SET 
                    slot = '".sql_real_escape_string($slotValue)."',
                    $subQuery
                    isdeleted = '".$delete."'
                WHERE
                    custid ='".sql_real_escape_string(trim($customerId))."'
                AND
                    weekid ='".sql_real_escape_string(trim($weekId))."'
               
                    
        ";
    }
    
    function getDailyCustomerReport($weekId=0,$driverid=0,$delted=0)
    {
        $subQuery='';
        if((int)$weekId > 0)
        {
                $subQuery="
                   AND   
                        week_slot.weekid = ".(int)$weekId;
        }
        $driverQuery = '';
        if((int)$driverid > 0)
        {
                $driverQuery="
                   AND   
                        week_slot.driverid = ".(int)$driverid;
        }
          $query="
                    SELECT
                        cust.id,
                        cust.userid,
                        cust.business_name,
                        cust.contact_name,
                        cust.phoneno,
                        cust.mobileno,
                        cust.contact_email,
                        cust.business_email 	,
                        cust.address,
                        cust.state,
                        cust.postcode,
                        cust.contract_start,
                        cust.contract_end,
                        cust.groupid,
                        cust.isDeleted,
                        week_slot.custid,
                        week_slot.weekid,
                        week_slot.slot,
                        week_slot.driverid 	
                    FROM
                        ".__DBC_SCHEMATA_CUSTOMER_DETAILS__." as cust
                    JOIN
                        ".__DBC_SCHEMATA_CUSTOMER_WEEKDAY_SLOT__." as week_slot
                    ON
                        week_slot.custid=cust.id
                    WHERE
                        cust.isDeleted = '0'
                    $subQuery
                    $driverQuery 
                    AND
                         week_slot.isdeleted = ".(int)$delted."
                    ORDER BY week_slot.slot ASC
                ";

            if( ($result = $this->exeSQL($query )) )
            {
                if($this->iNumRows > 0)
                {
                    while($res = $this->getAssoc( $result ))
                    {
                        $resarr[] = $res;
                    }
                    return $resarr;
                }
                else
                {
                    return false;
                }
            }	
    
    }
    
    function set_runSlotMonday($value)
    {
        $this->runSlotMonday = $this->validateInput( $value, __VLD_CASE_ANYTHING__, "runSlotMonday", "Run Slot", false, 80, true );
    }
    function set_customerId($value)
    {
        $this->customerId = $this->validateInput( $value, __VLD_CASE_ANYTHING__, "customerId", "customer", false, 80, true );
    }
}
