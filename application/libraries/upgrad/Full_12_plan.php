<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . 'libraries/upgrad/Plan.php');

class Full_12_plan extends Plan {

	public function __construct($priv_end_date)
	{
		parent::__construct($priv_end_date);

		$this->name = 'full_12';
		$this->day_num = 1;
	}

	public function get_name()
	{
		return $this->name;
	}

	public function set_end()
	{
		return date('Y-m-d H:i:s', strtotime($this->priv_end_date . "+{$this->day_num}" . ' years'));
	}
}