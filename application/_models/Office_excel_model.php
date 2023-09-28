<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

abstract class Office_excel_model extends Base_Model {

	protected $w_obj;
	protected $f_obj;
	protected $r_obj;
	protected $sheet;
	protected $f_path = '';

	protected $w_col_init  = 'A';
	protected $write_row   = '1';
	protected $write_col   = 'A';  		// 預設值是A的前一個, 這樣直接++就變成A開始
	protected $w_col_group = ''; 		// 若col超過Z, 則要放一個A在此變數, 讓之後的col = AA->AB...

	protected $r_col_init  = 'A';
	protected $read_row    = '1';
	protected $read_col    = 'A';
	protected $r_col_group = '';

	public $read_data = array();


	public function __construct()
	{
		parent::__construct();
	}


/* Abstract - Start */

	abstract protected function writer($position, $content);

	abstract protected function reader($position);

	abstract protected function set_package_writer();

	abstract protected function set_package_reader($path);

	abstract protected function s_file($file_name, $file_type, $is_client = FALSE);
/* Abstract - End */


/* Adapter - Start */

	public function write($table, $file_type, $file_name = '', $is_client = FALSE, $start_row = '', $start_col = '', $w_col_init = '')
	{
		if ($file_name == '')
			$file_name = 'uploads/excel_files/'.date("Ymd_His");

		$this->set_package_writer();

		if (( ! is_array($table)) OR ( ! is_string($start_row)) OR ( ! is_string($start_col)) OR ( ! is_string($w_col_init)))
			return FALSE;

		if ($start_row != '')
			$this->write_row = $start_row;

		if ($start_col != '')
		{
			$this->write_col = $start_col;
			$this->w_col_init  = $start_col;
		}

		if ($w_col_init != '')
			$this->w_col_init = $w_col_init;

		$this->w_table($table);
		$this->s_file($file_name, $file_type, $is_client);
	}

	public function read($file_path, $start_row = '', $start_col = '')
	{
		$this->set_package_reader($file_path);

		if ( ! $this->r_param_check($start_row, $start_col))
			return FALSE;

		$this->r_table();

		return $this->read_data;
	}

	// 請注意, 欲使用此方法, 需自行實作一個涵蓋excel_field_check方法的類別, 並當參數丟進來 = $obj
	public function read_verify_view($file_path, $obj, $user_id, $token, $start_row = '', $start_col = '')
	{
		$this->set_package_reader($file_path);

		if ( ! $this->r_param_check($start_row, $start_col))
			return FALSE;

		$this->r_table();

		$this->read_data = $obj->excel_field_check($user_id, $this->read_data);

		// call view excel table
		$this->excel_table_view($token);
	}
/* Adapter - Start */


// --------------------------------------------------------------------------------



/* Writer - Start */

	// 丟二維陣列寫成一份table
	public function w_table($table, $start_row = '', $start_col = '')
	{
		if ( ! $this->w_param_check($table, $start_row, $start_col))
			return FALSE;

		foreach ($table as $key => $value)
		{
			$this->w_line($value);
			$this->write_row++ ;
		}
	}

	// 寫入一整行,$start = 起始col, $line = array() 無須放key
	public function w_line($line, $start_row = '', $start_col = '')
	{
		if ( ! $this->w_param_check($line, $start_row, $start_col))
			return FALSE;

		foreach ($line as $key => $value)
		{
			$this->w_entry($this->get_current_w_col(), $value);
			$this->next_w_col();
		}
		$this->w_col_init();
	}

	// 寫入單一欄位可以不用按照目前的col、rol
	public function w_entry($position, $content)
	{
		if (( ! is_string($position)) OR ( ! is_string($content)))
			return FALSE;

		$this->writer($position, $content);
	}

	protected function w_param_check($array, $start_row = '', $start_col = '')
	{
		if (( ! is_array($array)) OR ( ! is_string($start_row)) OR ( ! is_string($start_col)))
			return FALSE;

		if ($start_row != '')
			$this->write_row = $start_row;

		if ($start_col != '')
		{
			$this->write_col  = $start_col;
			$this->w_col_init = $start_col;
		}

		return TRUE;
	}
/* Writer - End */



/* Reader - Start */
	
	public function r_table($start_row = '', $start_col = '')
	{
		if ( ! $this->r_param_check($start_row, $start_col))
			return FALSE;

		$h_r = $this->f_obj->getHighestRow();
		while ($this->read_row <= $h_r)
		{
			$this->r_line();
			$this->read_row++ ;
		}
	}

	public function r_line($start_row = '', $start_col = '')
	{
		if ( ! $this->r_param_check($start_row, $start_col))
			return FALSE;

		$h_c = $this->f_obj->getHighestColumn();
		while ($this->read_col <= $h_c)
		{
			$this->read_data[$this->read_row][$this->read_col] = $this->r_entry($this->get_current_r_col());
			$this->next_r_col();
		}
		$this->r_col_init();
	}

	public function r_entry($position)
	{
		if ( ! is_string($position))
			return FALSE;

		return $this->reader($position);
	}

	protected function r_param_check($start_row = '', $start_col = '')
	{
		if (( ! is_string($start_row)) OR ( ! is_string($start_col)))
			return FALSE;

		if ($start_row != '')
			$this->read_row   = $start_row;

		if ($start_col != '')
		{
			$this->read_col   = $start_col;
			$this->r_col_init = $start_col;
		}

		return TRUE;
	}
/* Reader - Start */



/* View - Start */

	public function excel_table_view($token)
	{
		$html = '';
		foreach ($this->read_data as $key => $value)
		{
			$html .= $this->load->view('excel_view_item', array('value' => $value, 'key' => $key), TRUE);
		}

		$this->load->view('excel_view', array('t_body' => $html, 'token' => $token));
	}
/* View - End */




/* Get - Start */

	// 輸出當前的col
	public function get_current_w_col()
	{
		return $this->w_col_group . $this->write_col . $this->write_row;
	}

	public function get_current_r_col()
	{
		return $this->r_col_group . $this->read_col . $this->read_row;
	}
/* Get - End */



/* Set for outside - Start */

	public function set_col()
	{
		echo "do something";
	}

	public function set_row()
	{
		echo "do something";
	}
/* Set for outside - End */



/* Self compute - Start */

	// 把col++
	protected function next_w_col()
	{
		$this->write_col++ ;

		if ($this->write_col > 90)
		{
			$this->w_col_init();
			$this->w_col_group .= 'A';
		}
	}

	protected function next_r_col()
	{
		$this->read_col++ ;

		if ($this->read_col > 90)
		{
			$this->r_col_init();
			$this->r_col_group .= 'A';
		}
	}

	protected function w_col_init()
	{
		$this->write_col = $this->w_col_init;
		$this->w_col_group = '';
	}

	protected function r_col_init()
	{
		$this->read_col = $this->r_col_init;
		$this->r_col_group = '';
	}
/* Self compute - End */
}	