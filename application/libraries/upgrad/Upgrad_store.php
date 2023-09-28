<?php

defined('BASEPATH') or exit('No direct script access allowed');



abstract class Upgrad_store {



	public $plan;

	protected $store_name;



	// 產生方案

	abstract public function create_upgrad($type, $priv_end_date);



	abstract public function get_name();



	// 購買方案

	public function buy_upgrad($type, $priv_end_date)

	{

		$this->plan = $this->create_upgrad($type, $priv_end_date);

		

		$data = array(

				'upgrad_stroe'  => $this->get_name(),

				'upgrad_plans'  => $this->plan->get_name(),

				'create_date'	=> $this->plan->set_create(),

				'start_date'	=> $this->plan->set_start(),

				'end_date' 		=> $this->plan->set_end(),

		);



		return $data;

	}

}