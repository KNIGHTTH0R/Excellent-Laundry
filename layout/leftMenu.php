<?php
/**
    * This file contains all the functionallity and HTML of Left menu.
    * 
    * leftMenu.php 
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

$flag=$_GET['flag'];
$link=$_GET['link'];

?>
<!-- BEGIN SIDEBAR -->
            <div class="page-sidebar-wrapper">
                <!-- BEGIN SIDEBAR -->
                <div class="page-sidebar navbar-collapse collapse">
                    <!-- BEGIN SIDEBAR MENU -->
                    <ul class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
                         <?php
                        if($_SESSION['usr']['role']=='1')
                        {
                        ?>
                        <li class="nav-item <?php if($flag=='group'){ ?> open <?php } ?>">
                            <a href="javascript:void(0);" class="nav-link nav-toggle">
                                <i class="fa fa-sitemap"></i>
                                <span class="title">Manage Categories</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu" <?php if($flag=='group'){ ?> style="display: block;" <?php } ?> >
                                <li class="nav-item <?php if($link=='view_group'){ ?> active open <?php } ?>">
                                    <a href="<?php echo __VIEW_GROUPS_LIST_URL__;?>" class="nav-link">
                                        <i class="fa fa-empire"></i>
                                        <span class="title">View Categories List</span>
                                    </a>
                                </li>
                               
                                
                            </ul>
                        </li>
                        <?php }if($_SESSION['usr']['role']=='1')
                        {
                        ?>
                        <li class="nav-item <?php if($flag=='admin'){ ?> open <?php } ?>">
                            <a href="javascript:void(0);" class="nav-link nav-toggle">
                                <i class="fa fa-male"></i>
                                <span class="title">Manage Users</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu" <?php if($flag=='admin'){ ?> style="display: block;" <?php } ?> >
                                <li class="nav-item <?php if($link=='view_admin'){ ?> active open <?php } ?>">
                                    <a href="<?php echo __VIEW_USER__;?>" class="nav-link">
                                        <i class="fa fa-empire"></i>
                                        <span class="title">View Users List</span>
                                    </a>
                                </li>
                               
                                
                            </ul>
                        </li>			
                        <?php }
                       if($_SESSION['usr']['role']=='1')
                        {
                        ?>
                        <li class="nav-item <?php if($flag=='users'){ ?> open <?php } ?>">
                            <a href="javascript:void(0);" class="nav-link nav-toggle">
                                <i class="fa fa-group"></i>
                                <span class="title">Manage Customers</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu" <?php if($flag=='users'){ ?> style="display: block;" <?php } ?> >
                                <li class="nav-item <?php if($link=='view_users'){ ?> active open <?php } ?>">
                                    <a href="<?php echo __VIEW_CUSTOMERS_LIST_URL__;?>" class="nav-link">
                                        <i class="fa fa-empire"></i>
                                        <span class="title">View Customers List</span>
                                    </a>
                                </li>
                               
                                
                            </ul>
                        </li>
                        
						<li class="nav-item <?php if($flag=='delivery'){ ?> open <?php } ?>">
                            <a href="javascript:void(0);" class="nav-link nav-toggle">
                                <i class="fa fa-truck"></i>
                                <span class="title">Delivery Scheduling</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu" <?php if($flag=='delivery'){ ?> style="display: block;" <?php } ?> >
                                <li class="nav-item <?php if($link=='view_customer_run_slot'){ ?> active open <?php } ?>">
                                    <a href="<?php echo __VIEW_CUSTOMER_RUN_SLOT_URL__;?>" class="nav-link">
                                        <i class="fa fa-empire"></i>
                                        <span class="title">Drivers Run Sheet</span>
                                    </a>
                                </li>
<!--                                <li class="nav-item <?php if($link=='customer_run_slot'){ ?> active open <?php } ?>">
                                    <a href="<?php echo __CUSTOMERS_RUN_SLOT_URL__;?>" class="nav-link">
                                        <i class="fa fa-empire"></i>
                                        <span class="title">Customer Run Slot</span>
                                    </a>
                                </li>-->
                                
                                <li class="nav-item  <?php if($link=='manager_drivers'){ ?> active open <?php } ?>">
                                    <a class="nav-link " href="<?php echo __MANAGE_DRIVER_URL__;?>">
                                        <i class="fa fa-empire"></i>
                                        <span class="title">Manage Drivers </span>
                                    </a>
                                </li>
<!--                                <li class="nav-item  <?php if($link=='manage_schedule_driver'){ ?> active open <?php } ?>">
                                    <a class="nav-link " href="<?php echo __MANAGE_SCHEDULE_DRIVER_URL__;?>">
                                        <i class="fa fa-empire"></i>
                                        <span class="title"> Schedule Drivers</span>
                                    </a>
                                </li>-->
                                
                            </ul>
                        </li>
                        <?php }if($_SESSION['usr']['role']=='1' || $_SESSION['usr']['role']=='2' )
                        {?>
                        <li class="nav-item <?php if($flag=='products'){ ?> open <?php } ?>">
                            <a href="javascript:void(0);" class="nav-link nav-toggle">
                                <i class="fa fa-cubes"></i>
                                <span class="title">Manage Products</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu" <?php if($flag=='products'){ ?> style="display: block;" <?php } ?> >
                                <li class="nav-item <?php if($link=='view_products'){ ?> active open <?php } ?>">
                                    <a href="<?php echo __VIEW_PRODUCTS_LIST_URL__;?>" class="nav-link">
                                        <i class="fa fa-empire"></i>
                                        <span class="title">View Products List</span>
                                    </a>
                                </li>
                                
<!--                                <li class="nav-item  <?php if($link=='add_product_price'){ ?> active open <?php } ?>">
                                    <a class="nav-link " href="<?php echo __ADD_PRODUCT_PRICE_URL__;?>">
                                        <i class="fa fa-empire"></i>
                                        <span class="title">Product Price Management</span>
                                    </a>
                                </li>-->
                               <!-- <li class="nav-item  <?php if($link=='update_inventory'){ ?> active open <?php } ?>">
                                    <a class="nav-link " href="<?php echo __UPDATE_INVENTORY__;?>">
                                        <i class="fa fa-empire"></i>
                                        <span class="title">Update Inventory</span>
                                    </a>
                                </li>
                               -->
                            </ul>
                        </li>
                        <?php
                        }if($_SESSION['usr']['role']=='1' || $_SESSION['usr']['role']=='2'  || $_SESSION['usr']['role']=='4'  )
                        {?>
                        <li class="nav-item <?php if($flag=='order'){ ?> open <?php } ?>">
                            <a href="javascript:void(0);" class="nav-link nav-toggle">
                                <i class="fa fa-shopping-cart"></i>
                                <span class="title">Manage Order</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu" <?php if($flag=='order'){ ?> style="display: block;" <?php } ?> >
                                <li class="nav-item <?php if($link=='view_order'){ ?> active open <?php } ?>">
                                    <a href="<?php echo __VIEW_ORDER_LIST_URL__;?>" class="nav-link">
                                        <i class="fa fa-empire"></i>
                                        <span class="title">View order List</span>
                                    </a>
                                </li>
                                
                            </ul>
                        </li>
                        <?php
                        }if($_SESSION['usr']['role']=='1')
                        {
                        ?>
                       <li class="nav-item <?php if($flag=='report'){ ?> open <?php } ?>">
                            <a href="javascript:void(0);" class="nav-link nav-toggle">
                                <i class="fa fa-file-text"></i>
                                <span class="title">Manage Reports</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu" <?php if($flag=='report'){ ?> style="display: block;" <?php } ?> >
                                <li class="nav-item <?php if($link=='orders_report'){ ?> active open <?php } ?>">
                                    <a href="<?php echo __VIEW_ORDERS_REPORT_URL__;?>" class="nav-link">
                                        <i class="fa fa-empire"></i>
                                        <span class="title">View Orders Report</span>
                                    </a>
                                </li>
                                <li class="nav-item <?php if($link=='detail_report'){ ?> active open <?php } ?>">
                                    <a href="<?php echo __VIEW_ORDERS_DETAIL_REPORT_URL__;?>" class="nav-link">
                                        <i class="fa fa-empire"></i>
                                        <span class="title">Orders Detail Report</span>
                                    </a>
                                </li>
                                <li class="nav-item <?php if($link=='labels_report'){ ?> active open <?php } ?>">
                                    <a href="<?php echo __VIEW_LABELS_REPORT_URL__;?>" class="nav-link">
                                        <i class="fa fa-empire"></i>
                                        <span class="title">Labels Report</span>
                                    </a>
                                </li>
                                <li class="nav-item <?php if($link=='daily_report'){ ?> active open <?php } ?>">
                                    <a href="<?php echo __VIEW_DAILY_SCHEDULE_REPORT_URL__;?>" class="nav-link">
                                        <i class="fa fa-empire"></i>
                                        <span class="title">Daily Scheduling Report</span>
                                    </a>
                                </li>
                                <li class="nav-item <?php if($link=='weekly_report'){ ?> active open <?php } ?>">
                                    <a href="<?php echo __VIEW_WEEKLY_SCHEDULE_REPORT_URL__;?>" class="nav-link">
                                        <i class="fa fa-empire"></i>
                                        <span class="title">Weekly Scheduling Report</span>
                                    </a>
                                </li>
                                <li class="nav-item <?php if($link=='run_data_report'){ ?> active open <?php } ?>">
                                    <a href="<?php echo __VIEW_REPORT_RUN_DATA_REPORT_URL__;?>" class="nav-link">
                                        <i class="fa fa-empire"></i>
                                        <span class="title">Ending Contracts</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <?php }
                        ?>
                        
                    </ul>
                    <!-- END SIDEBAR MENU -->
                </div>
                <!-- END SIDEBAR -->
            </div>
            <!-- END SIDEBAR -->