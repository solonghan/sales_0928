<?php
defined('BASEPATH') or exit('No direct script access allowed');

abstract class Plan {

	protected $day_num;
	protected $name;
	public $priv_end_date;

	public function __construct($priv_end_date)
	{
		if ($priv_end_date < date('Y-m-d H:i:s'))
			$priv_end_date = date('Y-m-d H:i:s');

		$this->priv_end_date = $priv_end_date;
	}

	abstract public function get_name();

	public function set_create()
	{
		return date('Y-m-d H:i:s');
	}

	public function set_start()
	{
		return $this->priv_end_date;
	}

	public function set_end()
	{
		return date('Y-m-d H:i:s', strtotime($this->priv_end_date . "+{$this->day_num}" . ' days'));
	}
}