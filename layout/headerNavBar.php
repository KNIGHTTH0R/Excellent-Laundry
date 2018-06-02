<?php
/**
    * This file contains all the functionallity and HTML of navigation bar used in header.
    * 
    * headerNavBar.php 
    * 
    * @copyright Copyright (C) 2016 Whiz-Solutions
    * @author Whiz Solutions
    * @package Demo Project
*/
ob_start();
session_start();
if( !defined( "__APP_PATH__" ) )
define( "__APP_PATH__", realpath( dirname( __FILE__ ) . "/../../" ) );
require_once( __APP_PATH__ . "/includes/constants.php" );
$userId=$_SESSION['usr']['id'];
$kUser = new cUser();
$userData = $kUser->loadUser($userId);
?>
<!-- BEGIN HEADER -->
    <div class="page-header navbar navbar-fixed-top">
        <!-- BEGIN HEADER INNER -->
        <div class="page-header-inner ">
            <!-- BEGIN LOGO -->
            <div class="page-logo">
                <a href="<?php echo __URL_BASE_ADMIN__; ?>">
                    <img src="<?php echo __BASE_URL_IMAGES__; ?>/EL-final-logo200.png" alt="logo" class="logo-default" /> </a>
                <div class="menu-toggler sidebar-toggler">
                    <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
                </div>
            </div>
            <!-- END LOGO -->
            <!-- BEGIN RESPONSIVE MENU TOGGLER -->
            <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
            <!-- END RESPONSIVE MENU TOGGLER -->
            
            <!-- BEGIN PAGE TOP -->
            <div class="page-top">
                
                <!-- BEGIN TOP NAVIGATION MENU -->
                <div class="top-menu">
                    <ul class="nav navbar-nav pull-right">
                         <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                        <li class="dropdown dropdown-user dropdown-dark">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <span>Hi! <?php echo $_SESSION['usr']['username'];?></span>
                                <span class="username username-hide-on-mobile"> <?php echo $userData['szFirstName']; ?> </span>
                                <!-- DOC: Do not remove below empty space(&nbsp;) as its purposely used -->
                                <img alt="" class="img-circle" src="<?php echo __BASE_URL_IMAGES__; ?>/user-icon.png" /> 
                            </a>
                            
                            <ul class="dropdown-menu dropdown-menu-default">
                                <li>
                                    <a href="<?php echo __MY_PROFILE_URL__; ?>">
                                        <i class="icon-key"></i> Change Password </a>
                                </li>
                                <li>
                                    <a href="<?php echo __USER_LOGOUT_URL__; ?>">
                                        <i class="icon-key"></i> Log Out </a>
                                </li>
                            </ul>
                        </li>
                        <!-- END USER LOGIN DROPDOWN -->
                        
                    </ul>
                </div>
                <!-- END TOP NAVIGATION MENU -->
            </div>
            <!-- END PAGE TOP -->
        </div>
        <!-- END HEADER INNER -->
    </div>
    <!-- END HEADER -->
