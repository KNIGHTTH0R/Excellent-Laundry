<?php 
/**
    * This file is the container of all functionality to manage and log all errors. 
    *
    * error.class.php
    *
    * @copyright Copyright (C) 2016 Whiz-Solutions
    * @author Whiz Solutions
    * @package Demo Project
 */
if( !defined( "__APP_PATH__" ) )
        define( "__APP_PATH__", realpath( dirname( __FILE__ ) . "/../" ) );
require_once( __APP_PATH__ . "/includes/constants.php" );


class cError
{
        var $error = false;
        var $errMessages = array(); 
        var $arErrorMessages;			

        // class constructor to initialise the class variables
        function __construct()
        {
                $this->error = false;
                $this->arErrorMessages = array();
                return true;
        }


        function CError()
        {
                $argcv = func_get_args();
                call_user_func_array( array( &$this, '__construct' ), $argcv );
        }

        // function to show the generated error on page in defined format
        function formatHTMLError( $message, $format = __VLD_ERROR_DISPLAY__ )
        {
                if( !empty( $message ) )
                {
                        if( is_array( $message) )
                        {
                                $message = implode( "<br>", $message );
                        }
        if(!empty($format))
                {
                return str_replace( "{ERROR}", $message, $format );
                }
        else
                {
                return $message;
        }
                }
                else
                {
                        return false;
                }
        }

        // function to add an manual error
        function addError( $error_field, $message )
        {
                $this->error = true;
                if( isset( $this->arErrorMessages[$error_field] ) )
                {
                        if( is_array( $this->arErrorMessages[$error_field] ) )
                        {
                                if( !in_array( $message, $this->arErrorMessages[$error_field] ) )
                                {
                                        $this->arErrorMessages[$error_field][] = $message;
                                }
                        }
                        else
                        {
                                if( $message != $this->arErrorMessages[$error_field] )
                                {
                                        $this->arErrorMessages[$error_field] = $this->arErrorMessages[$error_field]."<br>".$message;
                                }
                        }
                }
                else
                {
                        $this->arErrorMessages[$error_field] = $message;
                }
        }

function getErrorMessage($msg, $class)
{
    return "<div class='$class'>$msg</div>";
}

        function validateInput( $value, $validation, $error_field, $error_message, $min_length = false, $max_length = false, $required = false )
        {

                if(!is_array($value)) 
                        $value = trim( $value );
                $szErrorMessage = $error_message;
                $error = false;

                if( $required === true )
                {
                        if( empty( $value ) && $value !== "0" && $value !== 0 )
                        {
                                if( ( $validation != __VLD_CASE_BOOL__ || $validation != __VLD_CASE_STRICTBOOL__ ) && $value !== false )
                                {
                                        $error = true;
                                        $this->addError( $error_field, $szErrorMessage . " is required." );
                                        return false;
                                }
                        }
                }

                if( !empty( $value ) )
                {
                        switch( $validation )
                        {
                                case "NUMERIC":
                                        if( ( !is_numeric( $value )) )
                                        {
                                                $error = true;
                                                $this->addError( $error_field, $szErrorMessage . " must be only numbers." );
                                                return false;
                                        }
                                        else if( $min_length !== false || $max_length !== false )
                                        {

                                                $numericDollarArray=array('Deposit Amount');
                                                if( $min_length !== false && $value  < $min_length )
                                                {
                                                        if(in_array($szErrorMessage,$numericDollarArray))
                                                        {
                                                                $min_length="$".$min_length;
                                                        }
                                                        $error = true;
                                                        $this->addError( $error_field, $szErrorMessage . " must be " . $min_length . " or more." );
                                                        return false;
                                                }
                                                if( $max_length !== false && $value > $max_length )
                                                {
                                                        if(in_array($szErrorMessage,$numericDollarArray))
                                                        {
                                                                $max_length="$".$max_length;
                                                        }
                                                        $error = true;
                                                        $this->addError( $error_field, $szErrorMessage . " must be no more than " . $max_length . "." );
                                                        return false;
                                                }
                                        }
                                        break;
                                case "CARD":
                                        if( ( !is_numeric( $value ) && $required === true ) )
                                        {
                                                $error = true;
                                                $this->addError( $error_field, $szErrorMessage . " must be only numbers." );
                                                return false;
                                        }
                                        elseif( $min_length !== false || $max_length !== false )
                                        {
                                                if( $min_length !== false && strlen( (string)$value ) < $min_length )
                                                {
                                                        $error = true;
                                                        $this->addError( $error_field, $szErrorMessage . " must be " . $min_length . " digits" );
                                                        return false;
                                                }
                                                if( $max_length !== false && strlen( (string)$value ) > $max_length )
                                                {
                                                        $error = true;
                                                        $this->addError( $error_field, $szErrorMessage . " must be no more than " . $max_length . " digits." );
                                                        return false;
                                                }
                                        }
                                        break;
                                case "ALPHA":
                                        if( !preg_match( "/^[a-z]+$/i", $value ) )
                                        {
                                                $error = true;
                                                $this->addError( $error_field, $szErrorMessage . " must be only letters." );
                                                return false;
                                        }
                                        break;
                                case "ALPHANUMERIC":
                                        if( !preg_match( "/^[a-z0-9\-\_]+$/i", $value ) )
                                        {
                                                $error = true;
                                                $this->addError( $error_field, $szErrorMessage . " must be only letters and numbers." );
                                                return false;
                                        }
                                        break;
                                case "URI":
                                        if( !preg_match( "/^[a-z0-9\-\_\.]+$/i", $value ) )
                                        {
                                                $error = true;
                                                $this->addError( $error_field, $szErrorMessage . " must be only letters and numbers." );
                                                return false;
                                        }
                                        break;
                                case "EMAIL":
                                        if( !preg_match( "/^[_a-z0-9-\+\$\!\%\=\&\^]+(\.[_a-z0-9-\+\$\!\%\=\&\^]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z0-9]{2,4})$/i", $value ) )
                                        {
                                                $error = true;
                                                $this->addError( $error_field, $szErrorMessage . " must be a valid email address." );
                                                return false;
                                        }
                                        break;
                                case "BOOL":
                                        if( !is_bool( $value ) && !( $value != "true" || $value != "false" ) && !( $value != "1" || $value != "0" ) )
                                        {
                                                $error = true;
                                                $this->addError( $error_field, $szErrorMessage . " must be boolean value.");
                                                return false;
                                        }
                                        break;
                                case "STRICTBOOL":
                                        if( !is_bool( $value ) )
                                        {
                                                $error = true;
                                                $this->addError( $error_field, $szErrorMessage . " must be boolean value." );
                                                return false;
                                        }
                                        break;
                                case "ADDRESS":
                                case "NAME":
                                        if( !preg_match( "/^[a-z0-9\,\.\#\-\_\s\']+$/i", $value ) )
                                        {
                                                $error = true;
                                                $this->addError( $error_field, $szErrorMessage . " can only be letters, numbers, spaces, underscores, dashes, periods, commas, and pound signs." );
                                                return false;
                                        }
                                        break;
                                case "URL":
                                        if( !preg_match( "/(https|http|ftp)\:\/\/|([a-z0-9A-Z]+\.[a-z0-9A-Z]+\.[a-zA-Z]{2,4})|([a-z0-9A-Z]+\.[a-zA-Z]{2,4})|\?([a-zA-Z0-9]+[\&\=\#a-z]+)/i", $value ) )
                                        {
                                                $error = true;
                                                $this->addError( $error_field, $szErrorMessage . " must be a valid url." );
                                                return false;
                                        }
                                        break;
                                case "URL_WWW":
                                        if( !preg_match( "/^[a-zA-Z]+[:\/\/]+[A-Za-z0-9\-_]+\\.+[A-Za-z0-9\.\/%&=\?\-_]+$/i", $value ) )
                                        {
                                                $error = true;
                                                $this->addError( $error_field, $szErrorMessage . " must be a valid web address." );
                                                return false;
                                        }
                                        break;
                                case "ALL_VALID_URL":
                                        if(!preg_match( "/(https|http|ftp)\:\/\/|([a-z0-9A-Z]+\.[a-z0-9A-Z]+\.[a-zA-Z]{2,4})|([a-z0-9A-Z]+\.[a-zA-Z]{2,4})|\?([a-zA-Z0-9]+[\&\=\#a-z]+)/i", $value ))
                                        {
                                                $error = true;
                                                $this->addError( $error_field, $szErrorMessage . " must be a valid web address." );
                                                return false;	
                                        }
                                        break;
                                case "USERNAME":
                                        if( !preg_match( "/^[\S]+$/i", $value ) )
                                        {
                                                $error = true;
                                                $this->addError( $error_field, $szErrorMessage . " cannot contain any spaces or special characters." );
                                                return false;
                                        }
                                break;
                                case "PASSWORD":
                                        if( !preg_match( "/^[\S]+$/i", $value ) )
                                        {
                                                $error = true;
                                                $this->addError( $error_field, $szErrorMessage . " cannot contain any spaces." );
                                                return false;
                                        }
                                        break;
                                case "PASSWORD_STRONG":
                                        if( !preg_match("/^(?-i)(?=^.{6,}$)((?!.*\s)(?=.*[A-Z]))((?=(.*\d){1,})|(?=(.*\W){1,}))^.*$/",$value))
                                        {
                                                $error = true;
                                                $this->addError( $error_field, $szErrorMessage . " must be at least 6 characters in length, must contain at least one capital letter, and must contain at least one number or symbol." );
                                                return false;
                                        }
                                        break;
                                case "DATE":
                if( !strtotime( $value ) || strtotime( $value ) == -1 || !preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/',$value))
                                        {
                    $error = true;
                                                $this->addError( $error_field, $szErrorMessage . " must be a valid date [ie. DD/MM/YYYY]." );
                                                return false;
                                        }
                                        break;
                                case "PHONE":
                                        if( !preg_match( "/^\d{3}-\d{3}-\d{4}$/", $value ) )
                                        {
                                                $error = true;
                                                $this->addError( $error_field, $szErrorMessage . " must be a valid phone number." );
                                                return false;
                                        }
                                        break;
                                case "PHONE_2":
                                        if( !preg_match( "/^\d{10}$/", $value ) )
                                        {
                                                $error = true;
                                                $this->addError( $error_field, $szErrorMessage . " : enter 10 digit number." );
                                                return false;
                                        }                                                
                                        break;
                            case "POSTCODE":
                                if( !preg_match( "/^\d{4}$/", $value ) )
                                {
                                    $error = true;
                                    $this->addError( $error_field, $szErrorMessage . " : enter 4 digit number." );
                                    return false;
                                }
                                break;
                                case "DOLLARS":
                                case "MONEY_US":
                                        if( !preg_match( "/^[0-9]+(\.[0-9]{2})*$/", $value ) )
                                        {
                                                $error = true;
                                                $this->addError( $error_field, $szErrorMessage . " must be a valid money format. (Ex. 00.00 )" );
                                                return false;
                                        }
                                        break;
                            case "DECIMAL":
                                        if( !preg_match( "/^[0-9]+(\.[0-9]{2})*$/", $value ) )
                                        {
                                                $error = true;
                                                $this->addError( $error_field, $szErrorMessage . " -- format allowed =  00.00 , 22, 0.5" );
                                                return false;
                                        }
                                        break;
                            case "DECIMAL_POINTS": 
                                if( !preg_match( "/^[0-9]+(\.[0-9]{2})*(\.[0-9]{1})*$/", $value ) )
                                 {

                                     $error = true;
                                     $this->addError( $error_field, $szErrorMessage . " -- format allowed =  11 ,10.2, 22" );
                                     return false;
                                 }
                                 elseif( $min_length !== false || $max_length !== false )
                                 {

                                     if( $min_length !== false && $value  < $min_length )
                                     {
                                         $error = true;
                                         $this->addError( $error_field, $szErrorMessage . " must be " . $min_length . " or more." );
                                         return false;
                                     }
                                     if( $max_length !== false && $value > $max_length )
                                     {
                                         $error = true;
                                         $this->addError( $error_field, $szErrorMessage . " must be no more than " . $max_length . "." );
                                         return false;
                                     }
                                 }

                                 break;
                        case "CC_EXP":
                                if( !preg_match( "/^[0-9]{2}[-][0-9]{2}$/", $value ) )
                                {
                                        $error = true;
                                        $this->error = true;
                                        $this->addError( $error_field, $szErrorMessage . " must be MM/YY." );
                                        return false;
                                }
                                break;
            case "DD_NON_0" :
                if(!$value) {
                    $error = true;
                    $this->addError( $error_field, $szErrorMessage . " must be selected." );
                                                return false;
                }
                break;
            case "WHOLE_NUM" :
                if(!preg_match("/^[0-9]*$/",$value)) {
                    $error = true;
                    $this->addError( $error_field, $szErrorMessage . " must be numeric." );
                                                return false;
                }
                else
                {
                        if($min_length != false && $value < $min_length)
                        {
                                $error = true;
                                                        $this->addError( $error_field, $szErrorMessage . " must be  " . $min_length . " or more." );
                                                        return false;
                        }
                        if( $max_length !== false && $value > $max_length )
                                                {
                                                        $error = true;
                                                        $this->addError( $error_field, $szErrorMessage . " must be no more than " . $max_length . " ." );
                                                        //$this->addError( $error_field, $szErrorMessage . " must be no more than 4 digits." );
                                                        return false;
                                                }
                }
                break;
            case "IMAGE" :
                $allowed_type = array("image/jpeg", "image/png", "image/bmp", "image/jpg", "image/gif");

                if($required === true && $value['tmp_name'] == "")
                {
                    $error = true;
                    $this->addError( $error_field, $szErrorMessage . " is required." );
                                                return false;
                }
                if(!in_array($value['type'], $allowed_type))
                {
                    $error = true;
                    $this->addError( $error_field, $szErrorMessage . " file type not allowed." );
                                                return false;
                }
                if($value['error'])
                {
                    $error = true;
                    $this->addError( $error_field, $szErrorMessage . " upload error. Try Again" );
                                                return false;
                }
                break;
                                case "ANYTHING":
                                        break;
                                default:
                                        $error = true;
                                        $this->addError( "error", "Unknown validation type. I was sent this type: " . $validation );
                                        break;
                        }
                }
                else
    {
        switch( $validation )
                        {
            case "DD_NON_0" :
                if(!$value) {
                    $error = true;
                    $this->addError( $error_field, $szErrorMessage . " must be selected." );
                                                return false;
                }
                break;
            case "DECIMAL_POINTS": 
                   if( !preg_match( "/^[0-9]+(\.[0-9]{2})*(\.[0-9]{1})*$/", $value ) )
                    {

                        $error = true;
                        $this->addError( $error_field, $szErrorMessage . " -- format allowed =  11 ,10.2, 22" );
                        return false;
                    }
                    elseif( $min_length !== false || $max_length !== false )
                    {

                        if( $min_length !== false && $value  < $min_length )
                        {
                            $error = true;
                            $this->addError( $error_field, $szErrorMessage . " must be " . $min_length . " or more." );
                            return false;
                        }
                        if( $max_length !== false && $value > $max_length )
                        {
                            $error = true;
                            $this->addError( $error_field, $szErrorMessage . " must be no more than " . $max_length . "." );
                            return false;
                        }
                    }

                    break;
                        default:
                                        break;
        }
    }

                if( $min_length !== false && ( $validation == "NUMERIC" || $validation == "WHOLE_NUM" ) &&  $value!="" )
                {
                        if( $value < $min_length )
                        {
                                $error = true;
                                $this->addError( $error_field, $szErrorMessage . " must be " . $min_length . " or more." );
                                return false;
                        }
                }

                if( $min_length !== false && ( $validation != "NUMERIC" && $validation != "WHOLE_NUM" ) && !empty( $value ) )
                {
                        if( strlen( $value ) < $min_length )
                        {
                                $error = true;
                                $this->addError( $error_field, $szErrorMessage . " must be at least " . $min_length . " characters in length." );
                                return false;
                        }
                }

                if( $max_length !== false && $validation != "NUMERIC" && $validation != "WHOLE_NUM" && !empty( $value ) )
                {
                        if( strlen( $value ) > $max_length )
                        {
                                $error = true;
                                $this->addError( $error_field, $szErrorMessage . " must not be longer than " . $max_length . " characters in length." );
                                return false;
                        }
                }

                if( $error === true )
                {
                        $this->addError( "error", "Unknown error validating field. The field was: " . $error_field . ". Validation: " . $validation . ". Value: " . $value );
                        return false;
                }
                else
                {
                        return $value;
                }
        }

        function resetErrors()
        {
                $this->error = false;
                $this->arErrorMessages = array();
        }

        function formatError( $message, &$container )
        {
                $container .= str_replace( "{ERROR}", $message, __VLD_ERROR_DISPLAY__ );
        }

        function logError( $error_field, $message, $error_type, $class_name, $function, $line, $error_severity="critical")
        {
                $error_severity = strtolower(trim($error_severity));
                $class_name = strtolower(trim($class_name));
                $function = trim($function);
                $line = (int)$line;
                $message = trim($message);
                $error_field = trim($error_field);

                //show error on page
                if( __WWCS_ERROR_FLAG__ )
                {
                        echo "<p align='left'><font color='red'>$message</font></p>";
                }

                switch ($error_severity)
                {				
                        case "critical":
                                break;
                        default:
                                $log_file = "critical";
                                break;
                }

                $find_ary = array("{TIME}", "{SEVERITY}", "{ERROR_TYPE}", "{FILE}", "{FUNCTION}", "{LINE}", "{ERROR}");
                $replace_ary = array(date(__LOG_DATE_FORMAT__, time()), strtoupper($error_severity), $error_type, $class_name, $function, $line, $message);
                $error_string = str_replace($find_ary, $replace_ary, __LOG_LINE_FORMAT__);

                $log_file = __APP_PATH_LOGS__."/".$error_severity.".log";
                $log_file_class = __APP_PATH_LOG_CLASSES__."/".$class_name."_".$error_severity.".log";

                if ($error_severity == "stdout")
                {
                        echo $error_string;
                }
                else
                {
                        $handle = fopen($log_file, "a+");
                        fwrite($handle, $error_string);
                        fclose($handle);
                }
		if($_SESSION['usr']['role'] == '1' || $_SESSION['usr']['role']=='2'  || $_SESSION['usr']['role']=='4'){
                    header('location:'.__ADMIN_ERROR__);
                    die;
                }else{
                    header('location:'.__PAGE_ERROR__);
                    die;
                }
                return true;
        }
}
?>
