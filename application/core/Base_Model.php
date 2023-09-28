<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Base_Model extends CI_Model {

	protected $page_count = 25; 

	protected $flow_record_table 			= 'flow_record';
	protected $mgr_action_table 			= 'mgr_action';
	protected $mgr_menu_table 				= 'mgr_menu';
	protected $mgr_privilege_table 			= 'mgr_privilege';
	protected $mgr_privilege_menu_table		= 'mgr_privilege_menu';
	protected $member_table 				= 'member';
	protected $user_table 					= 'user';

	protected $login_token_table 			= 'login_token';
	protected $notice_table 				= 'notice';
	protected $year_goal_table 				= 'year_goal';
	protected $month_goal_table 			= 'month_goal';
	protected $customer_manage_table 		= 'customer_mgr';
	protected $user_customer_source_table 	= 'user_customer_source';
	protected $user_customer_field_table  	= 'user_customer_field';
	protected $customer_mgr_field_table   	= 'customer_mgr_field';
	protected $upgrad_table 				= 'upgrad';
	protected $memo_table 					= 'memo';
	protected $schedule_item_table 			= 'user_schedule_item';
	protected $schedule_table 				= 'schedule';
	protected $goal_table 					= 'goal';
	protected $goal_customer_table 			= 'goal_customer';
	protected $master_minor_relation_table 	= 'master_minor_relation';

	public function __construct()
    {
		parent::__construct();
	}

	public function compute_total_page($total)
	{

		return ($total % $this->page_count == 0) ? floor(($total)/$this->page_count) : floor(($total)/$this->page_count) + 1;
	}

	// 只想從 model output TRUE or FALSE 就靠此函數
	protected function db_output_tf($output, $flag = FALSE)
	{
		switch (gettype($output))
		{
			case 'boolean':
				return $output;

			case 'NULL':
				if ($flag == FALSE)
					return NULL;
				else
					return FALSE;

			case 'object':
				return TRUE;

			case 'resource':
				return TRUE;

			case 'integer':
				if ($flag == FALSE)
				{
					if ($output == 0)
						return FALSE;
					else
						return $output;
				}
				elseif ($flag == TRUE)
				{
					if ($output == 0)
						return FALSE;
					else
						return TRUE;
				}

			case 'double':
				if ($flag == FALSE)
				{
					if ($output == 0.0)
						return FALSE;
					else
						return $output;
				}
				elseif ($flag == TRUE)
				{
					if ($output == 0.0)
						return FALSE;
					else
						return TRUE;
				}

			case 'string':
				if ($flag == FALSE)
				{
					if ($output == '')
						return FALSE;
					else
						return $output;
				}
				elseif ($flag == TRUE)
				{
					if ($output == '')
						return FALSE;
					else
						return TRUE;
				}

			case 'array':
				if ($flag == FALSE)
				{
					if (empty($output) == TRUE)
						return FALSE;
					else
						return $output;
				}
				elseif ($flag == TRUE)
				{
					if (empty($output) == TRUE)
						return FALSE;
					else
						return TRUE;
				}

			default:
				return FALSE;
		}
	}

	protected function db_ut_filter($output, $type, $flag = FALSE)
	{
		switch (gettype($output))
		{
			case 'boolean':
				return $output;

			case 'NULL':
				if ($flag == FALSE)
					return NULL;
				else
					return FALSE;

			case 'object':
				return TRUE;

			case 'resource':
				return TRUE;

			case 'integer':
				if ($flag == FALSE)
				{
					if ($output == 0)
						return FALSE;
					else
						return $output;
				}
				elseif ($flag == TRUE)
				{
					if ($output == 0)
						return FALSE;
					else
						return TRUE;
				}

			case 'double':
				if ($flag == FALSE)
				{
					if ($output == 0.0)
						return FALSE;
					else
						return $output;
				}
				elseif ($flag == TRUE)
				{
					if ($output == 0.0)
						return FALSE;
					else
						return TRUE;
				}

			case 'string':
				if ($flag == FALSE)
				{
					if ($output == '')
						return FALSE;
					else
						return $output;
				}
				elseif ($flag == TRUE)
				{
					if ($output == '')
						return FALSE;
					else
						return TRUE;
				}

			case 'array':
				if ($flag == FALSE)
				{
					if (empty($output) == TRUE)
						return FALSE;
					else
						return $output;
				}
				elseif ($flag == TRUE)
				{
					if (empty($output) == TRUE)
						return FALSE;
					else
						return TRUE;
				}

			default:
				return FALSE;
		}
		switch ($type)
		{
			case 'boolean':
				return $output;

			case 'NULL':
				if ($flag == FALSE)
					return NULL;
				else
					return FALSE;

			case 'object':
				return TRUE;

			case 'resource':
				return TRUE;

			case 'integer':
				if ($flag == FALSE)
				{
					if ($output == 0)
						return FALSE;
					else
						return $output;
				}
				elseif ($flag == TRUE)
				{
					if ($output == 0)
						return FALSE;
					else
						return TRUE;
				}

			case 'double':
				if ($flag == FALSE)
				{
					if ($output == 0.0)
						return FALSE;
					else
						return $output;
				}
				elseif ($flag == TRUE)
				{
					if ($output == 0.0)
						return FALSE;
					else
						return TRUE;
				}

			case 'string':
				if ($flag == FALSE)
				{
					if ($output == '')
						return FALSE;
					else
						return $output;
				}
				elseif ($flag == TRUE)
				{
					if ($output == '')
						return FALSE;
					else
						return TRUE;
				}

			case 'array':
				if ($flag == FALSE)
				{
					if (empty($output) == TRUE)
						return FALSE;
					else
						return $output;
				}
				elseif ($flag == TRUE)
				{
					if (empty($output) == TRUE)
						return FALSE;
					else
						return TRUE;
				}

			default:
				return FALSE;
		}
	}
}