<?php

defined('BASEPATH') or exit('No direct script access allowed');



require_once(APPPATH . 'libraries/upgrad/Upgrad_store.php');

require_once(APPPATH . 'libraries/upgrad/Full_1_plan.php');

require_once(APPPATH . 'libraries/upgrad/Full_3_plan.php');

require_once(APPPATH . 'libraries/upgrad/Full_12_plan.php');



class Part_upgrad_store extends Upgrad_store {



	public function __construct()

	{

		$this->store_name = 'part_features';

	}



	public function get_name()

	{

		return $this->store_name;

	}



	public function create_upgrad($type, $priv_end_date)

	{

		if ( ! strtotime($priv_end_date))

			return FALSE;

		

		switch ($type)

		{

			case 'full_1':

				return new Full_1_plan($priv_end_date);

			case 'full_3':

				return new Full_3_plan($priv_end_date);

			case 'full_12':

				return new Full_12_plan($priv_end_date);

			

			default:

				return FALSE;

		}

	}

}