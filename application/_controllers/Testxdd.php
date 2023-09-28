<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testxdd extends Base_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->data['active'] = 'Testxdd';

		$this->load->model('Member_info_model');
		$this->load->model('Member_menu_model');
		$this->load->model('Member_verify_model');
		$this->load->model('Jwt_model');
	}

	public function textttt()
	{
		$this->Flow_record_model->set_flow_record($this->data['active'], $this->get_client_ip());

		$this->data = array_merge($this->data,
        		array(
        				'title' => 'collections manage',
        				'tool_btns' => [
				            	['新增年度', base_url()."mgr/collections_mgr/add_year", "btn-primary"]
				        ]
        		)
    	);
    	var_dump($this->data);
	}

	public function excel_test()
	{
		$w = [
				['this is A1', '', 'C1', '',   'E1'],
				['this is A2', '', 'C2', '',   'E2'],
				['this is A3', '', '',   'D3', 'E3'],
				['this is A4', '', '',   '',   '', 'E4'],
		];

		$this->load->model('PhpSpreadsheet_excel_model', 'Csv');
		$this->Csv->write($w, 'xls');
		// var_dump($this->Csv->read('uploads/excel_files/20210514_144150.xls'));
	}

	public function test_token()
	{
		$this->response_json(TRUE, '', array(
			'data' => $this->Jwt_model->verify_token('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoxLCJjcmVhdGVfdGltZSI6IjIwMjEtMDYtMDcgMDY6NDE6MzEiLCJleHBpcmVkX3RpbWUiOiIyMDIxLTA2LTA3IDA2OjQxOjMxIn0.mlcHDE8Dk6Qg0z0hbhLsZUGk4bM5ndouX5XMScqomVk'),
		));
	}

	public function test_addr()
	{
		$address 	= ($this->input->get('address') ? : '台北市中山區中山北路二段79號11樓');

		$url 		= 'https://maps.googleapis.com/maps/api/geocode/json';
		$aut 		= 'AIzaSyA_KLZdTl9YO1Efp04HcAuEdfmqjzcWQGk';

		$curl 		= curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL 			 => $url . '?key=' . $aut . '&address=' . $address,
		  CURLOPT_RETURNTRANSFER => TRUE,
		  CURLOPT_ENCODING 		 => '',
		  CURLOPT_MAXREDIRS 	 => 10,
		  CURLOPT_TIMEOUT 		 => 30,
		  CURLOPT_HTTP_VERSION 	 => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST  => 'GET',
		  CURLOPT_POSTFIELDS 	 => '',
		));
		$response 	= curl_exec($curl);
		$err 		= curl_error($curl);
		curl_close($curl);

		if ($err) 
		{
			$this->response_json(FALSE, '發生錯誤');
		}
		else
		{
			$this->response_json(TRUE, '', array(
					'data' => json_decode($response, TRUE),
			));
		}
	}
}