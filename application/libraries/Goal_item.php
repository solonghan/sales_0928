<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Goal_item {

	public $goal_id = NULL;
	public $name 	= NULL;
	public $digital = NULL;
	public $year 	= NULL;
	public $month 	= NULL;
	public $diff 	= NULL;
	public $goal_estimate = 0;
	public $goal_deal     = 0;

	public $page_count = 10;
	public $total_page = 1;
	public $customer = array();

	public function __construct($goal_data = FALSE)
	{
		if (is_array($goal_data))
		{
			$this->goal_id 	= $goal_data['id'];
			$this->name 	= $goal_data['name'];
			$this->digital  = $goal_data['total_money'];
			$this->year 	= $goal_data['year'];
			$this->month 	= $goal_data['month'];
		}
	}

	public function set_name($name)
	{
		$this->name = $name;
	}

	public function set_digital($digital)
	{
		$this->digital = $digital;
	}

	public function set_year($year)
	{
		$this->year = $year;
	}

	public function set_month($month)
	{
		$this->month = $month;
	}

	public function add_customer($customer_data)
	{
		$this->customer[count($this->customer)] = array(
				'goal_id' 		 => $this->goal_id,
				'customer_name'  => $customer_data['customer_name'],
				'no' 			 => $customer_data['no'],
				'estimate_money' => $customer_data['estimate_money'],
				'deal_money' 	 => $customer_data['deal_money'],
				'is_complete' 	 => $customer_data['is_complete'],
				'is_delete' 	 => $customer_data['is_delete'],
			);		
	}

	public function set_customer($customer_data)
	{
		foreach ($customer_data as $key => $value)
		{
			$this->customer[count($this->customer)] = array(
					'id' 			 => $value['id'],
					'customer_name'  => $value['customer_name'],
					'no' 			 => $value['no'],
					'estimate_money' => $value['estimate_money'],
					'deal_money' 	 => $value['deal_money'],
					'is_complete' 	 => $value['is_complete'],
			);	
		}
	}

	public function get_customer()
	{
		return $this->customer;
	}

	public function count()
	{
		$this->goal_estimate = 0;
		$this->goal_deal = 0;
		foreach ($this->customer as $key => $value)
		{
			$this->goal_estimate += $value['estimate_money'];
			if ($value['is_complete'] == TRUE)
				$this->goal_deal += $value['deal_money'];
		}

		$this->diff = $this->digital - $this->goal_deal;
		$this->total_page = ceil(count($this->customer) / $this->page_count);
	}

	public function print_data($page = 1)
	{
		if ($page > $this->total_page)
			return array();

		$customer = array_slice($this->customer, (($page - 1) * $this->page_count), $this->page_count);

		return array(
			'goal_id' 			=> $this->goal_id,
			'goal_name' 		=> $this->name,
			'goal_total_money'  => $this->digital,
			'goal_diff' 		=> $this->diff,
			'goal_estimate' 	=> $this->goal_estimate,
			'goal_deal' 		=> $this->goal_deal,
			'goal_page' 		=> $page,
			'total_page' 		=> $this->total_page,
			'customers' 		=> $customer,
		);
	}
}