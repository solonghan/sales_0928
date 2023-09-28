<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Excel_import extends Base_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('PhpSpreadsheet_excel_model', 'Csv');
		$this->load->model('Customer_manage_model', 'Customer');
		
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

	public function index()
	{
		// 使可以上傳 excel 
	}

	// 顯示 excel
	public function view_excel($file_name = 'uploads/excel_files/xdddd.csv')
	{
		$data = $this->Csv->read($file_name, '3');
		$c_no = $this->Customer->get_all_customer_no(16);
		var_dump($c_no);
		$html = '';
		// foreach ($data as $key => $value)
		// {
		// 	$html .= $this->load->view('view_excel_item', array('value' => $value, 'key' => $key), TRUE);
		// }
		// $this->load->view('view_excel', array('t_body' => $html));
	}

	public function upload()
	{
		$p_data = $this->input->post();
		$this->Csv->set_package_reader('uploads/excel_files/xdddd.csv');

		foreach ($p_data as $key => $value)
		{
			if ($value == 'on')
			{
				$this->Csv->r_line("$key");
			}
		}

		$data = $this->Csv->read_data;
		var_dump($data);
	}
}