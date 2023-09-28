<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . 'libraries/upgrad/Plan.php');

class Full_1_plan extends Plan {

	public function __construct($priv_end_date)
	{
		parent::__construct($priv_end_date);

		$this->name = 'full_1';
		$this->day_num = 30;
	}

	public function get_name()
	{
		return $this->name;
	}
}