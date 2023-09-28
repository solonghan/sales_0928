<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Excel_import extends Base_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('PhpSpreadsheet_excel_model', 'Csv');
		$this->load->model('Customer_manage_model', 'Customer');
		$this->load->model('Jwt_model', 'Jwt');
		
		$this->data['active'] = 'Testxdd';
	}

	public function xddddd()
	{
		$w = [
				['this is A1', '', 'C1', '',   'E1'],
				['this is A2', '', 'C2', '',   'E2'],
				['this is A3', '', '',   'D3', 'E3'],
				['this is A4', '', '',   '',   '', 'E4'],
		];

		$this->Csv->write($w, 'xls');
		var_dump($this->Csv->read('uploads/excel_files/20210514_144150.xls'));
	}

	public function index($token)
	{
		// 使可以上傳 excel
		$excel_import_token = $this->Jwt->verify_token($token);

		// 接收檔案, 並取出檔案名稱加入token(含user_id), then 導頁到 Excel_import/view_excle
		if ($this->input->post())
		{
			echo "do something";
		}
	}

	// 測試用, 顯示 excel
	public function view_excel($token)
	{
		// print 123;exit;
		// $excel_import_token = $this->Jwt->verify_token($token);
		// $this->Csv->read_verify_view($excel_import_token['file_name'], $this->Customer, $excel_import_token['user_id'], '3');
		

		$this->Csv->read_verify_view('uploads/excel_files/xddddd.csv', $this->Customer, 16, $token, '3');
	}

	// 測試用
	public function upload($token)
	{
		$verified_data = $this->input->post();
		unset($verified_data['example_length']);

		// $excel_import_token = $this->Jwt->verify_token($token);
		// $this->Csv->set_package_reader($excel_import_token['file_name']);
		$this->Csv->set_package_reader('uploads/excel_files/xddddd.csv');

		foreach ($verified_data as $key => $value)
		{
			if ($value == 'on')
			{
				$this->Csv->r_line("$key");
			}
		}

		$data = $this->Csv->read_data;

		// 寫入DB
	}

	public function close_windon()
	{
		echo "<script>window.close();</script>";
	}
}