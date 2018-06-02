<?php 
/**
    * This file is the container for all functionality related to communication with data base.
    * This file follows PDO and Prepared Statement to communicate with mYsql database.
    *
    * database.class.php
    *
    * @copyright Copyright (C) 2016 Whiz-Solutions
    * @author Whiz Solutions
    * @package Demo Project
 */
	if( !defined( "__APP_PATH__" ) )
		define( "__APP_PATH__", realpath( dirname( __FILE__ ) . "/../" ) );
		
	require_once( __APP_PATH__ . "/includes/constants.php" );
	require_once( __APP_PATH_CLASSES__ . "/error.class.php" );

	
	class cDatabase extends cError
	{
	
                var $mysqli;
		 
		/** @var string */
		var $szSQL;
		/** @var int */
		var $iLastInsertID;
		/** @var int */
		var $iNumRows;
		/** @var string */
		var $szTableName;
		/** @var string */
		var $szDatabase;
		/** @var int */
		var $iMySQLError;
		/** @var string */
		var $szMySQLError;

		function __construct()
		{
			parent::__construct();
			return true;
		}
		
		function cDatabase()
		{
			$argcv = func_get_args();
			call_user_func_array( array( &$this, '__construct' ), $argcv );
		}
		
		function connect($username, $password, $select_db, $host = 'localhost', $type="normal", $return_type="bool")
		{
                    $dbh_sess = new mysqli($host, $username, $password, $select_db);
                    
                    return true;   
		}
		
		function initDatabase( $schema, $table )
		{
			$this->szDatabase = $schema;
			$this->szTableName = $table;
		}

		function setPagination($page, $pageMax)
                {
                    $this->PageMax = $pageMax;
                    $this->Page = ($page-1) * $pageMax;
                }

		function setSQL( $sql )
		{
			$this->szSQL = trim( (string)$sql );
		}
		
		function exeSQL( $sql = false, $resource=false )
		{
                    global $dbh_sess;
                    $this->setSQL( $sql );
                  
                    //$this->connect(__DBC_USER__, __DBC_PASSWD__, __DBC_SCHEMATA__, __DBC_HOST__);
                    $result = $dbh_sess->query( $sql );

                    $this->iMySQLError = $dbh_sess->errno;
                    $this->szMySQLError = $dbh_sess->error; 


                    if( $this->iMySQLError != 0 )
                    {			
                            $message = "MySQL Error: " . $this->szMySQLError . "\n\n" . $sql;

                            // log the message
                            $this->logError( "database", $message, "MySQL", __CLASS__, __FUNCTION__, __LINE__, "critical");							

                            //redirect the header to a default page if the flag is set
                            if( !__WWCS_ERROR_FLAG__ )	
                            {			
                                    if(($_SERVER['REQUEST_URI']!="") && (!(strpos($_SERVER['REQUEST_URI'],"/admin/")===false)))
                                    {
                                            ob_end_clean();
                                            header('location:'.__BASE_URL__.'/admin/error.php');
                                            die;
                                    }
                                    else
                                    {
                                            ob_end_clean();
                                            header('location:'.__BASE_URL__.'/error.php');
                                            die;
                                    }					
                            }
                            die;
                    }
                    else
                    {
                            if( preg_match( '/^INSERT INTO/i', $this->szSQL ) )
                            {
                                  $this->iLastInsertID = $dbh_sess->insert_id;                            
                                  return true;
                            }
                            elseif( preg_match( '/^SELECT/i', $this->szSQL ) )
                            {
                                  $this->iNumRows = $result->num_rows;
                            }

                            return $result;
                    }
		}

                function getAssoc( $result )
                {
                      return $result->fetch_assoc();
                }
 
                function getRowCnt()
                {
                     global $dbh_sess;
                     return $dbh_sess->affected_rows;
                }
	}
?>
