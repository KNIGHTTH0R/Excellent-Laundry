<?php
/**
    * This file contains the all the functionality related to auto load class files, read/write/destroy session for the store
    * 
    * preload_session.php 
    * 
    * @copyright Copyright (C) 2016 Whiz-Solutions
    * @author Whiz Solutions
    * @package Demo Project
*/
if( !defined( "__APP_PATH__" ) )
define( "__APP_PATH__", realpath( dirname( __FILE__ ) . "/../" ) );
//$scenarios_flag='1';
require_once( __APP_PATH__ . "/includes/constants.php" );
require_once( __APP_PATH__ . "/includes/functions.php" );

$file_name='';
function __autoload($class_name)
{
    switch ($class_name)
    {
        case "cDatabase":
            $file_name = __APP_PATH_CLASSES__."/database.class.php";
            break;
        case "CError":
            $file_name = __APP_PATH_CLASSES__."/error.class.php";
            break; 
        case "cUser":
            $file_name = __APP_PATH_CLASSES__."/user.class.php";
            break;
        case "cDriver":           
        	$file_name = __APP_PATH_CLASSES__."/driver.class.php";
            break;
        case "cMaster":           
        	$file_name = __APP_PATH_CLASSES__."/master.class.php";
            break;
         case "cPagination":
            $file_name = __APP_PATH_CLASSES__."/pagination.class.php";
            break;   
    }
	if(!empty($file_name))
   {
    	require_once($file_name);
   }
}

$successful_connection = false;

function on_session_start($save_path, $session_name)
{
    global $dbh_sess, $successful_connection;

	if ($dbh_sess = new mysqli(__DBC_HOST__, __DBC_USER__,__DBC_PASSWD__,__DBC_SCHEMATA__))
    {
        $successful_connection = true;
        $dbh_sess->set_charset("utf8");
        return true;
    }

    return false;
}

function on_session_end()
{
	global $dbh_sess;
    return mysqli_close($dbh_sess);
}

function on_session_read($key)
{
    global $dbh_sess;

    $query = "
      SELECT
        session_data
      FROM
        ".__DBC_SCHEMATA_SESSIONS__."
      WHERE
        session_id = '".sql_real_escape_string($key)."'
      AND
        session_expiration > NOW()
      LIMIT
         1
    ";

    if ($result = $dbh_sess->query($query) or die($dbh_sess->error))
    {
     	if ($result->num_rows)
        {
            $record = $result->fetch_assoc();
            return $record['session_data'];
        }
    }
    return '';
}

function on_session_write($key, $val)
{
    global $dbh_sess;
    $dbh_sess = new mysqli(__DBC_HOST__, __DBC_USER__,__DBC_PASSWD__);

    $query = "
        INSERT INTO
            ".__DBC_SCHEMATA_SESSIONS__."
            (
                session_id,
                session_data,
                session_expiration,
                idUser
            )
        VALUES
            (
                '".sql_real_escape_string($key)."',
                '".sql_real_escape_string($val)."',
                '".date('Y-m-d H:i:s', strtotime(__SESSION_LIFETIME__))."',
                ".(int)$_SESSION['user_id']."
            )
        ON DUPLICATE KEY UPDATE
            session_data = '".sql_real_escape_string($val)."',
            session_expiration = '".date('Y-m-d H:i:s', strtotime(__SESSION_LIFETIME__))."',
            idUser = ".(int)$_SESSION['user_id']."
    ";

    return $dbh_sess->query($query) or die($dbh_sess->error);
}

function on_session_destroy($key)
{
    global $dbh_sess;

    $query = "
        DELETE FROM
            ".__DBC_SCHEMATA_SESSIONS__."
        WHERE
            session_id = '".sql_real_escape_string($key)."'
    ";

	 return $dbh_sess->query($query) or die($dbh_sess->error);
}

function on_session_gc($max_lifetime)
{
    global $dbh_sess;

    $query = "
        DELETE FROM
            ".__DBC_SCHEMATA_SESSIONS__."
        WHERE
            session_expiration < NOW()
    ";

	 return $dbh_sess->query($query);
}

function strip_tags_array($data, $tags = null)
{
    $stripped_data = array();
    foreach ($data as $key => $value)
    {
        if (is_array($value))
        {
            $stripped_data[$key] = strip_tags_array($value, $tags);
        } 
        else 
        {
            while (($tmpval = url_decode_custom($value)) !== false)
            {
                $value = $tmpval;
            }
            while (($tmpval = html_decode_custom($value)) !== false)
            {
                $tmpval2 = $tmpval;
                while (($tmpval3 = url_decode_custom($tmpval2)) !== false)
                {
                    $tmpval2 = $tmpval3;
                }
                $value = $tmpval2;
            }
            $stripped_data[$key] = strip_tags($value, $tags);
        }
    }
    return $stripped_data;
}

function url_decode_custom($value)
{
    $url_decoded = urldecode($value);
    if ($url_decoded != $value)
    {
        return $url_decoded;
    }
    return false;
}

function html_decode_custom($value)
{
    $html_decoded = html_entity_decode($value);
    if ($html_decoded != $value)
    {
        return $html_decoded;
    }
    return false;
}


if (get_magic_quotes_gpc()) {
    function stripslashes_gpc(&$value)
    {
        $value = stripslashes($value);
    }
    array_walk_recursive($_GET, 'stripslashes_gpc');
    array_walk_recursive($_POST, 'stripslashes_gpc');
    array_walk_recursive($_COOKIE, 'stripslashes_gpc');
    array_walk_recursive($_REQUEST, 'stripslashes_gpc');
}
/*
 * use the below to debug the strip tags implementation.
$_GET['test']['yes']['owen'] = array('owen' => 'yes');

echo "<pre>";
print_r($_GET);
$_GET = strip_tags_array($_GET);
print_r($_GET);
echo "\n-----------------------------\n";
print_r($_POST);
$_POST = strip_tags_array($_POST);
print_r($_POST);
echo "\n-----------------------------\n";
print_r($_COOKIE);
$_COOKIE = strip_tags_array($_COOKIE);
print_r($_COOKIE);
echo "\n-----------------------------\n";
print_r($_REQUEST);
$_REQUEST = strip_tags_array($_REQUEST);
print_r($_REQUEST);
die();
*/

$_GET = strip_tags_array($_GET);
//$_POST = strip_tags_array($_POST);
$_COOKIE = strip_tags_array($_COOKIE);
$_REQUEST = strip_tags_array($_REQUEST);

// Set the save handlers
session_set_save_handler("on_session_start", "on_session_end", "on_session_read", "on_session_write", "on_session_destroy", "on_session_gc");
session_start();

if ($successful_connection === false)
{
    if ($dbh_sess = new mysqli(__DBC_HOST__, __DBC_USER__,__DBC_PASSWD__))
    {
        return ($dbh_sess->errno ?  die($dbh_sess->error) : true);
    }
}
?>
