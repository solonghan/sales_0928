<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'Office_excel_model.php';

class PhpSpreadsheet_excel_model extends Office_excel_model {

	public function __construct()
	{
		parent::__construct();
	}


/* For 每個套件必需要自我實踐的 fnction - Start */
	
	public function set_package_writer()
	{
		$this->w_obj = new PhpOffice\PhpSpreadsheet\Spreadsheet();
		// $this->w_obj = $this->w_obj->getActiveSheet();
	}

	public function set_package_reader($path)
	{
		
		if ($path == '')
		{
			return FALSE;
		}
		else
		{
			$this->f_path = $path;
			
		}

		$inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($this->f_path);
		// $this->r_obj   = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
		$this->r_obj   = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($this->f_path);

		
		$this->r_obj->setReadDataOnly(true);
		// print_r($this->r_obj );exit;
		$this->f_obj   = $this->r_obj->load($this->f_path);
		$this->f_obj   = $this->f_obj->getActiveSheet();

		// print_r($this->r_obj );exit;

		// print_r($this->f_obj);exit;
		// // 从文件中加载数据
		// 	$spreadsheet = $this->r_obj ->load($this->f_path);

		// 	// 选择工作表
		// 	$sheet = $spreadsheet->getActiveSheet();

		// 	// 读取单元格 A1 中的数据
		// 	$data = $sheet->getCell('B3')->getValue();
		// 	$worksheets = $spreadsheet->getAllSheets();
		// 	// $data = $sheet->getCell('B1')->getValue();
		// 	// 输出读取的数据
		// 	print_r($worksheets) ;
	}


	// 寫入一個cell
	protected function writer($position, $content)
	{
		$this->w_obj->getActiveSheet()->setCellValue($position, $content);
	}

	// 讀取一個cell
	protected function reader($position)
	{
		return $this->f_obj->getCell($position)->getValue();
	}


	// 儲存檔案
	public function s_file($file_name, $file_type, $is_client = FALSE)
	{
		$file_type_l = strtolower($file_type);
		$file_type_u = ucfirst($file_type_l);

		$file_name   = $file_name . '.' . $file_type_l;
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->w_obj, $file_type_u);

		if (($is_client == TRUE) AND is_bool($is_client))
		{
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="' . $file_name .'"');
			header('Cache-Control: max-age=0');

			$writer->save(iconv('php://output'));
		}
		else
		{
			$writer->save($file_name);
		}
	}
/* For 每個套件必須要自我實踐的 fnction - End */



/* Overwrite - Start */

	public function w_table($table, $start_row = '', $start_col = '')
	{
		$this->w_param_check($table, $start_row = '', $start_col = '');

		$this->w_obj->getActiveSheet()->fromArray($table, NULL, $this->get_current_w_col());
	}

	public function r_table($start_row = '', $start_col = '')
	{
		
		if ( ! $this->r_param_check($start_row = '', $start_col = ''))
			return FALSE;

		// 迭代方式讀取
		// foreach ($this->f_obj->getRowIterator() as $row)
		// {
		//     $cellIterator = $row->getCellIterator();
		//     $cellIterator->setIterateOnlyExistingCells(FALSE);
		//     foreach ($cellIterator as $cell)
		//     {
		//         $this->read_data[$this->read_row][$this->read_col] = $cell->getValue();
		//         $this->next_r_col();
		//     }
		// 	$this->r_col_init();
		// 	$this->read_row++ ;
		// }

		$start = $this->get_current_r_col();
		$end   = $this->f_obj->getHighestColumn() . $this->f_obj->getHighestRow();
		$point = $start . ':' . $end;

		return $this->read_data = $this->f_obj->rangeToArray($point, NULL, TRUE, TRUE, TRUE);
	}
/* Overwrite - End */
}