<?php 
/**
    * This file contains the pagination functionality.
    * 
    * pagination.class.php
    *
    * @copyright Copyright (C) 2016 Whiz-Solutions
    * @author Whiz Solutions
    * @package Demo Project
 */
if( !defined( "__APP_PATH__" ) )
define( "__APP_PATH__", realpath( dirname( __FILE__ ) . "/../" ) );
require_once( __APP_PATH__ . "/includes/constants.php" );
class cPagination {
	
	var $php_self;
	var $rows_per_page = 10; //Number of records to display per page
	var $total_rows = 0; //Total number of rows returned by the query
	var $links_per_page = 5; //Number of links to display per page
	var $append = ""; //Paremeters to append to pagination links
	var $arr = "";
	var $debug = false;
	var $conn = false;
	var $page = 1;
	var $max_pages = 0;
	var $offset = 0;
	var $ajax_function;
 	var $iIncludeDots;
	/**
	 * Constructor
	 *
	 * @param integer $rows_per_page Number of records to display per page. Defaults to 10
	 * @param integer $links_per_page Number of links to display per page. Defaults to 5
	 * @param string $append Parameters to be appended to pagination links 
	 */
	
	function cPagination ( $arr, $rows_per_page = PAGE_PER_NO, $links_per_page = 5, $append = "")
	{
		$this->arr = $arr;
		if(is_numeric($this->arr))
		{
			//Find total number of rows
			$this->total_rows = $this->arr ;
		}
		else
		{
			$this->total_rows = count( $this->arr );
		}
		
		//Return FALSE if no rows found
		if ($this->total_rows == 0) 
		{
			return array();
		}
		
		$this->rows_per_page = (int)$rows_per_page;
		if (intval($links_per_page ) > 0)
		{
			$this->links_per_page = (int)$links_per_page;
		} 
		else 
		{
			$this->links_per_page = 5;
		}
		
		if (isset($_REQUEST['page'] )) 
		{
			$this->page = intval($_REQUEST['page'] );
		}
		
		$count = $this->total_rows;
		
		$paginationCount= ceil($count / $rows_per_page);
	    $paginationModCount= $count % $rows_per_page;
	    if(!empty($paginationModCount))
	    {
	       // $paginationCount++;
	    }
	    $this->paginationCount = $paginationCount ;
	    
		//Max number of pages
		$this->max_pages = floor($this->paginationCount);
		if ($this->links_per_page > $this->max_pages) {
			$this->links_per_page = $this->max_pages;
		}
		
		//Check the page value just in case someone is trying to input an aribitrary value
		if ($this->page > $this->max_pages || $this->page <= 0) {
			$this->page = 1;
		}		
	}
	
	/**
	 *
	 * @access public
	 * @return resource
	 */
	function paginate($func = 'showPage',$mode=false) 
	{		
		$this->func = sanitize_all_html_input($func);
		if(!empty($mode))
		{
			$this->szMode = sanitize_all_html_input($mode);		
		}	

		//Return FALSE if no rows found
		if ($this->total_rows == 0) 
		{
			return array();
		}
		
		return true;
	}
	
	
	/**
	 * Display the link to the first page
	 *
	 * @access public
	 * @param string $tag Text string to be displayed as the link. Defaults to 'First'
	 * @return string
	 */
	function renderFirst($tag = 'First') 
	{	
		if($this->iGlobalCatalogSearch==1)
		{
			return '<ul class="tsc_pagination tsc_paginationC tsc_paginationC01">
			    <li class="first link" id="first">
			        <a  href="javascript:void(0)" onclick="'.$this->func.'(\'0\',\'first\',\''.$this->szMode.'\',1)">'.$tag.'</a>
			    </li>';
		}
		else
		{
			return '<ul class="tsc_pagination tsc_paginationC tsc_paginationC01">
			    <li class="first link" id="first">
			        <a  href="javascript:void(0)" onclick="'.$this->func.'(\'0\',\'first\',\''.$this->szMode.'\')">'.$tag.'</a>
			    </li>';
		}
	}
	
	/**
	 * Display the link to the last page
	 *
	 * @access public
	 * @param string $tag Text string to be displayed as the link. Defaults to 'Last'
	 * @return string
	 */
	function renderLast($tag = 'Last') {
	
		$paginationCount = $this->paginationCount-1 ;
		if($this->iGlobalCatalogSearch==1)
		{
			return '<li class="last link" id="last">
				         <a href="javascript:void(0)" onclick="'.$this->func.'(\''.($paginationCount).'\',\'last\',\''.$this->szMode.'\',1)">'.$tag.'</a>
				    </li>
				    <li class="flash"></li>
				</ul>
			';
		}
		else
		{
			return '<li class="last link" id="last">
				         <a href="javascript:void(0)" onclick="'.$this->func.'(\''.($paginationCount).'\',\'last\',\''.$this->szMode.'\')">'.$tag.'</a>
				    </li>
				    <li class="flash"></li>
				</ul>
			';
		}
			
	}
	
	/**
	 * Display the next link
	 *
	 * @access public
	 * @param string $tag Text string to be displayed as the link. Defaults to '>>'
	 * @return string
	 */
	function renderNext($tag = '&gt;&gt;') {
		if ($this->total_rows == 0)
			return FALSE;
			
		$next = (int)$this->page;
		if ($this->page < $this->max_pages) {
			if($this->iGlobalCatalogSearch==1)
			{
				return '<li class="link" id="next">
				         <a href="javascript:void(0)" onclick="'.$this->func.'(\''.$next.'\',\'next\',\''.$this->szMode.'\',1)">'.$tag.'</a>
				    </li>';	
			}
			else
			{
				return '<li class="link" id="next">
				         <a href="javascript:void(0)" onclick="'.$this->func.'(\''.$next.'\',\'next\',\''.$this->szMode.'\')">'.$tag.'</a>
				    </li>';	
			}
				
		}
	}
	
	
	/**
	 * Display the page links
	 *
	 * @access public
	 * @return string
	 */
	function renderNav() 
	{
	  $paginationCount = $this->paginationCount ;
	  
		if ($this->total_rows == 0)
			return FALSE;
		
		$batch = ceil($this->page / $this->links_per_page );
		$end = $batch * $this->links_per_page;
		if ($end == $this->page) {
			//$end = $end + $this->links_per_page - 1;
		//$end = $end + ceil($this->links_per_page/2);
		}
		if ($end > $this->max_pages) {
			$end = $this->max_pages;
		}
		$start = $end - $this->links_per_page + 1;
		$links = '';
		//echo $_REQUEST['page'];
		for($i = $start; $i <= $end; $i ++) {
			
			if($_REQUEST['page']>0 && $_REQUEST['page']==$this->page)
			{
				$this->page=$this->page+1;
			}
			if ($i == $this->page) 
			{		
				$pageValue=$i-1;		
				if($this->iGlobalCatalogSearch==1)
				{
					$links .= '<li id="'.$pageValue.'_no" class="link">
				          <a  href="javascript:void(0)" class="In-active current" onclick="'.$this->func.'(\''.$pageValue.'\',\''.$pageValue.'_no\',\''.$this->szMode.'\',1)">
				              '.($i).'
				          </a>
				    </li>';
				}
				else
				{
					$links .= '<li id="'.$pageValue.'_no" class="link">
				          <a  href="javascript:void(0)" class="In-active current" onclick="'.$this->func.'(\''.$pageValue.'\',\''.$pageValue.'_no\',\''.$this->szMode.'\')">
				              '.($i).'
				          </a>
				    </li>';
				}
				
			}
			else
			{
				$pageValue=$i-1;
				if($this->iGlobalCatalogSearch==1)
				{
					$links .= '<li id="'.$pageValue.'_no" class="link">
				          <a  href="javascript:void(0)" onclick="'.$this->func.'(\''.$pageValue.'\',\''.$pageValue.'_no\',\''.$this->szMode.'\',1)">
				              '.($i).'
				          </a>
				    </li>';
				}
				else
				{
					$links .= '<li id="'.$pageValue.'_no" class="link">
				          <a  href="javascript:void(0)" onclick="'.$this->func.'(\''.$pageValue.'\',\''.$pageValue.'_no\',\''.$this->szMode.'\')">
				              '.($i).'
				          </a>
				    </li>';
				}
			}
		}
		return $links;
	}
	
/**
	 * Display the previous link
	 *
	 * @access public
	 * @param string $tag Text string to be displayed as the link. Defaults to '<<'
	 * @return string
	 */
	function renderPrev($tag = '<<') {
	
		if($_REQUEST['page']>0)
		{
			$this->page=$this->page+1;
		}
		if ($this->total_rows == 0)
			return FALSE;
		if ($this->page > 1) 
		{
			$prev =(int)$this->page-2;
			if($this->iGlobalCatalogSearch==1)
			{
				return '<li class="link" id="previous">
				         <a href="javascript:void(0)" '.$class_name.' onclick="'.$this->func.'(\''.$prev.'\',\'previous\',\''.$this->szMode.'\',1)">'.$tag.'</a>
				    </li>';	
			}
			else
			{
				return '<li class="link" id="previous">
				         <a href="javascript:void(0)" '.$class_name.' onclick="'.$this->func.'(\''.$prev.'\',\'previous\',\''.$this->szMode.'\')">'.$tag.'</a>
				    </li>';		
			}					
		} 
	}
	
	/**
	 * Display full pagination navigation
	 *
	 * @access public
	 * @return string
	 */
	function renderFullNav() 
	{
		return $this->renderFirst() . ' ' . $this->renderPrev() . ' ' . $this->renderNav() . ' ' . $this->renderNext() . ' ' . $this->renderLast();
	}

	/**
	 * Set debug mode
	 *
	 * @access public
	 * @param bool $debug Set to TRUE to enable debug messages
	 * @return void
	 */
	function setDebug($debug) {
		$this->debug = $debug;
	}
}
?>
