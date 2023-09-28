<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Customer_manage_model extends Base_Model
{

	public function __construct()
	{
		parent::__construct();
	}


	public function get_user_all_customer($user_id, $where_data, $order_by, $like_data, $page = 1, $total_page = 1, $join_note = FALSE, $join_schedule = FALSE, $schedule_where = '', $index = 0)
	{
		$this->db->select('C.*' . ($join_note ? ', N.note as n_note' : '') . ($join_schedule ? ', S.end_date' : ''))->from($this->customer_manage_table . ' as C');
		if ($join_note) $this->db->join('(SELECT CN.* FROM `condition_note` as CN WHERE CN.is_delete = 0) as N', 'C.user_id = N.minor_id AND C.id = N.customer_id', 'left');
		if ($join_schedule) $this->db->join('(SELECT * FROM schedule WHERE user_id = ' . $user_id . ' AND is_delete = 0 AND is_complete = 1 ' . $schedule_where . ' GROUP BY customer_id) as S', 'C.id = S.customer_id', 'left');


		//快速下拉特定筆數
		if ($index != 0) {


			return $this->db->where($where_data)
				->like($like_data)
				->group_by('id')
				->order_by($order_by)
				->limit($this->page_count + 1)
				->get()
				->result_array();
		} else {
			return $this->db->where($where_data)
				->like($like_data)
				->group_by('id')
				->order_by($order_by)
				->limit($this->page_count, ($page - 1) * $this->page_count)
				->get()
				->result_array();
		}
	}



	public function get_user_all_customer_nopage($user_id, $where_data, $order_by)
	{
		$this->db->select('C.*')->from($this->customer_manage_table . ' as C');
		return $this->db->where($where_data)
			->order_by($order_by)
			->get()
			->result_array();
	}

	public function is_customer_no_exist_2($user_id, $customer_no)
	{
		if (
			0 < $this->db->select('id')
			->from($this->customer_manage_table)
			->where(array('customer_no' => $customer_no, 'is_delete' => 0, 'user_id' => $user_id))
			->get()
			->num_rows()
		) {
			return TRUE;
		} else return FALSE;
	}
	public function get_lower($user_id, $where_data, $like_data, $join_note = FALSE, $join_schedule = FALSE, $schedule_where = '')
	{
		$this->db->select('*')->from($this->customer_manage_table . ' as C');
		if ($join_note) $this->db->join('(SELECT CN.* FROM `condition_note` as CN WHERE CN.is_delete = 0) as N', 'C.user_id = N.minor_id AND C.id = N.customer_id', 'left');
		if ($join_schedule) $this->db->join('(SELECT * FROM schedule WHERE user_id = ' . $user_id . ' AND is_delete = 0 AND is_complete = 1 ' . $schedule_where . ' GROUP BY customer_id) as S', 'C.id = S.customer_id', 'left');




		return $this->db->where($where_data)
			->like($like_data)
			->get()
			->num_rows();
	}

	//取得底下客戶最大cuntomer_no
	public function get_max_no($id)
	{
		return 	$this->db->select('customer_no')
			->from($this->customer_manage_table)
			->where('user_id', $id)
			->order_by('customer_no', 'DESC')
			->get()
			->row_array();
	}
	public function get_user_all_customer_num($user_id, $where_data, $like_data, $join_note = FALSE, $join_schedule = FALSE, $schedule_where = '')
	{
		$this->db->select('*')->from($this->customer_manage_table . ' as C');
		if ($join_note) $this->db->join('(SELECT CN.* FROM `condition_note` as CN WHERE CN.is_delete = 0) as N', 'C.user_id = N.minor_id AND C.id = N.customer_id', 'left');
		if ($join_schedule) $this->db->join('(SELECT * FROM schedule WHERE user_id = ' . $user_id . ' AND is_delete = 0 AND is_complete = 1 ' . $schedule_where . ' GROUP BY customer_id) as S', 'C.id = S.customer_id', 'left');




		return $this->db->where($where_data)
			->like($like_data)
			->get()
			->num_rows();
	}

	public function get_user_all_customer_num_nopage($user_id, $where_data)
	{
		$this->db->select('*')->from($this->customer_manage_table . ' as C');
		return $this->db->where($where_data)
			->get()
			->num_rows();
	}

	public function get_user_all_source($user_id)
	{
		return $this->db->select('*')
			->from($this->user_customer_source_table)
			->where(array('user_id' => $user_id, 'is_delete' => '0'))
			->or_group_start()
			->where('user_id', '0')
			->group_end()
			->get()
			->result_array();
	}

	public function get_user_all_field_name($user_id)
	{
		return $this->db->select('*')
			->from($this->user_customer_field_table)
			->where(array('user_id' => $user_id, 'is_delete' => '0'))
			->get()
			->result_array();
	}

	public function customer_mgr_field($customer_id)
	{
		return $this->db->select('*')
			->from($this->user_customer_field_table)
			->where(array('customer_id' => $customer_id, 'is_delete' => '0'))
			->get()
			->result_array();
	}

	public function get_customer_field_value_by_customer($customer_id)
	{
		return $this->db->select('*')
			->from($this->customer_mgr_field_table)
			->where(array('customer_id' => $customer_id, 'is_delete' => '0'))
			->get()
			->result_array();
	}

	public function get_mgr_customer_info($user_id, $customer_no)
	{
		return $this->db->select('*')
			->from($this->customer_manage_table)
			->where(array('user_id' => $user_id, 'id' => $customer_no))
			->get()
			->row_array();
	}
	public function get_mgr_custome_info_when_add($insert_id)
	{
		return $this->db->select()
			->from($this->customer_manage_table)
			->where('id', $insert_id)
			->get()
			->row_array();
	}
	public function get_customer_note($user_id, $id)
	{
		return $this->db->select('C.note, U.name')
			->from('condition_note as C')
			->join($this->user_table . ' as U', 'U.id = C.user_id', 'left')
			->where(array('C.minor_id' => $user_id, 'C.customer_id' => $id, 'C.is_delete' => 0))
			->get()
			->result_array();
	}

	public function get_customer_source($user_id, $source_id)
	{
		return $this->db->select('source_name')
			->from($this->user_customer_source_table)
			->where(array('user_id' => $user_id, 'id' => $source_id))
			->or_group_start()
			->where(array('user_id' => 0, 'id' => $source_id))
			->group_end()
			->get()
			->row_array();
	}

	public function get_user_source_list($user_id)
	{
		return $this->db->select('id, source_name')
			->from($this->user_customer_source_table)
			->where(array('user_id' => $user_id, 'is_delete' => 0))
			->group_by('source_name')
			->get()
			->result_array();
	}
	public function get_add_customer_info($user_id, $id)
	{

		return $this->db->select('*')
			->from($this->customer_manage_table)
			->where(array('id' => $id, 'user_id' =>	$user_id))
			->get()
			->row_array();
	}
	public function set_user_customer_info($data, $option)
	{
		if ($option == 'add') {
			if ($this->db->insert($this->customer_manage_table, $data))
				return $this->db->insert_id();
			else
				return FALSE;
		} elseif ($option == 'edit') {
			return $this->db->where(array('id' => $data['id'], 'user_id' => $data['user_id']))
				->update($this->customer_manage_table, $data);
		}
	}

	public function set_customer_field($data, $option, $customer_id = FALSE)
	{
		if ($option == 'add') {
			var_dump($data);
			return $this->db->insert_batch($this->customer_mgr_field_table, $data);
		} elseif ($option == 'edit') {
			$del_data = array('is_delete' => 1);
			$this->db->where('customer_id', $customer_id)->update($this->customer_mgr_field_table, $del_data);

			if (empty($data)) return TRUE;
			else return $this->db->insert_batch($this->customer_mgr_field_table, $data);
		}
	}

	public function set_customer_source($data, $option)
	{
		if ($option == 'add') {
			$this->db->insert($this->user_customer_source_table, $data);
			return $this->db->insert_id();
		}
	}

	public function get_customer_num($user_id)
	{
		return $this->db->select('id')
			->from($this->customer_manage_table)
			->where(array('user_id' => $user_id, 'is_delete' => 0))
			->get()
			->num_rows();
	}

	public function get_customer_job($user_id)
	{
		return $this->db->select('job')
			->from($this->customer_manage_table)
			->where(array('user_id' => $user_id, 'job !=' => NULL))
			->get()
			->result_array();
	}

	public function get_all_customer_no($id)
	{

		return $this->db->select('customer_no')
			->from($this->customer_manage_table)
			->where('user_id', $id)
			->get()
			->result_array();
	}

	public function is_customer_no_exist($id, $no)
	{
		return $this->db->select('id')
			->from($this->customer_manage_table)
			->where(array('user_id' => $id, 'customer_no' => $no))
			->get()
			->num_rows();
	}

	public function get_all_customer_source($user_id)
	{
		return $this->db->select('source_name, id')
			->from($this->user_customer_source_table)
			//->where(array('user_id' => $user_id))
			// ->or_where(array('user_id' => 0))
			->get()
			->result_array();
	}

	public function reset_customer_number($user_id, $customer_no)
	{
		$customer = $this->db->select('*')
			->from('customer_mgr')
			->where(array('user_id' => $user_id))
			->order_by('customer_no DESC')
			->get()
			->first_row();

		$this->db->where(array('user_id' => $user_id, 'id' => $customer->id))->update($this->customer_manage_table, array('customer_no' => $customer_no));
	}

	public function excel_field_tf_db($data, $user_id)
	{
		//取得所有客戶ID
		$customer_no_list = $this->get_all_customer_no($user_id);
		$customer_no 	  = array();

		// var_dump($user_id);
		// exit;
		//echo 'user_id'.$user_id;
		foreach ($customer_no_list as $customer_no_item) {
			array_push($customer_no, $customer_no_item['customer_no']);
		}
		//取得所有SOURCE_ID
		$source_name_list = $this->get_all_customer_source($user_id);
		$source_name 	  = array();
		foreach ($source_name_list as $key => $value) {
			$source_name[$value['id']] = $value['source_name'];
		}

		$db_data = array();
		foreach ($data as $key => $value) {
			if (in_array($value['A'], $customer_no)) {
				return FALSE;
			}

			$db_data[$key]['user_id'] 		= $user_id;
			$db_data[$key]['customer_no'] 	= $value['A'];
			$db_data[$key]['name'] 			= $value['B'];
			$db_data[$key]['status'] 		= $this->excel_status_tf($value);
			$db_data[$key]['source_id']		= array_search($value['C'], $source_name);
			$db_data[$key]['relation'] 		= $value['D'];
			$db_data[$key]['level'] 		= $value['E'];
			$db_data[$key]['job']			= $value['K'];
			$db_data[$key]['birthday']		= $value['L'];
			$db_data[$key]['text'] 			= $value['M'];
		}
		return $db_data;
	}

	public function add_customer_batch($data)
	{
		return $this->db->insert_batch($this->customer_manage_table, $data);
	}

	public function get_all_customer_manage_status($user_id)
	{
		return $this->db->select('status')
			->from($this->customer_manage_table)
			->where(array('is_delete' => 0, 'user_id' => $user_id))
			->get()
			->result_array();
	}

	public function is_customer_mine($user_id, $customer_id)
	{
		if (!empty($row = $this->db->select('*')
			->from($this->customer_manage_table)
			->where(array('user_id' => $user_id, 'id' => $customer_id))
			->get()
			->row_array())) {
			return $row;
		} else return FALSE;
	}

	public function del_customer($user_id, $customer_id)
	{
		$update_data = array('is_delete' => 1);
		$this->db->where(array('user_id' => $user_id, 'id' => $customer_id))->update($this->customer_manage_table, $update_data);
	}





	private function excel_status_tf($value)
	{
		// J I H G F
		// 'inform','reservation','visit','propose','deal'
		if (!($value['J'] == NULL or empty($value['J']))) {
			return 'deal';
		} elseif (!($value['I'] == NULL or empty($value['I']))) {
			return 'propose';
		} elseif (!($value['H'] == NULL or empty($value['H']))) {
			return 'visit';
		} elseif (!($value['G'] == NULL or empty($value['G']))) {
			return 'reservation';
		} elseif (!($value['F'] == NULL or empty($value['F']))) {
			return 'inform';
		} else {
			return 'inform';
		}
	}

	// For excel 匯入檢查頁面
	public function excel_field_check($user_id, $data)
	{
		//var_dump($user_id);
		//var_dump($data);

		//取得該使用者客戶編號列表
		$customer_no_list = $this->get_all_customer_no($user_id);
		$customer_no 	  = array();
		// var_dump($customer_no_list);
		// exit;

		//取得所有客戶欄位
		foreach ($customer_no_list as $customer_no_item) {
			array_push($customer_no, $customer_no_item['customer_no']);
		}

		//var_dump($customer_no);

		//抓USER_ID為0的資料
		$source_name_list = $this->get_all_customer_source($user_id);
		// var_dump($source_name_list);
		// exit;
		$source_name 	  = array();
		foreach ($source_name_list as $source_name_item) {
			array_push($source_name, $source_name_item['source_name']);
		}

		foreach ($data as $key => $value) {
			$data[$key]['is_no'] = FALSE;

			// 欄位為空
			if (empty($value['A'])) {
				$data[$key]['A'] = "<span style='background-color:Tomato;color:white'>不可為空</span></span>";
				$data[$key]['is_no'] = TRUE;
			}
			//非數字
			elseif (!is_numeric($value['A'])) {
				$data[$key]['A'] = "<span style='background-color:Orange;color:white'>{$value['A']}</span> - <span style='background-color:Tomato;color:white'>格式錯誤</span>";
				$data[$key]['is_no'] = TRUE;
			}
			//已經存在DB
			elseif (in_array($value['A'], $customer_no)) {
				$data[$key]['A'] = "<span style='background-color:Orange;color:white'>{$value['A']}</span> - <span style='background-color:Tomato;color:white'>已存在</span>";
				$data[$key]['is_no'] = TRUE;
			}

			// C
			if (empty($value['C'])) {
				$data[$key]['C'] = "<span style='background-color:Tomato;color:white'>不可為空</span></span>";
				$data[$key]['is_no'] = TRUE;
			}
			//用戶自行新增種類
			elseif (!in_array($value['C'], $source_name)) {
				$data[$key]['C'] = "<span style='background-color:Orange;color:white'>{$value['C']}</span> - <span style='background-color:Tomato;color:white'>尚未定義</span>";
				$data[$key]['is_no'] = TRUE;
			}

			// D
			if (empty($value['D'])) {
				$data[$key]['D'] = "<span style='background-color:Tomato;color:white'>不可為空</span></span>";
				$data[$key]['is_no'] = TRUE;
			} elseif (!is_relation_exists($value['D'])) {
				$data[$key]['D'] = "<span style='background-color:Orange;'>{$value['D']}</span> - <span style='background-color:Tomato;color:white'>格式錯誤</span>";
				$data[$key]['is_no'] = TRUE;
			}

			// E
			if (empty($value['E'])) {
				$data[$key]['E'] = "<span style='background-color:Tomato;color:white'>不可為空</span></span>";
				$data[$key]['is_no'] = TRUE;
			} elseif (!is_relation_exists($value['E'])) {
				$data[$key]['E'] = "<span style='background-color:Orange;color:white'>{$value['E']}</span> - <span style='background-color:Tomato;color:white'>格式錯誤</span>";
				$data[$key]['is_no'] = TRUE;
			}

			// L
			$is_brithday = strtotime($value['L']) ? strtotime($value['L']) : FALSE;
			if ((!$is_brithday) and (!empty($value['L']))) {
				$data[$key]['L'] = "<span style='background-color:Orange;color:white'>{$value['L']}</span> - <span style='background-color:Tomato;color:white'>格式錯誤</span>";
				$data[$key]['is_no'] = TRUE;
			}

			if (!$this->excel_status_tf($value)) {
				$data[$key]['F'] = "<span style='background-color:Tomato;color:white'>若狀態皆為空,預設將為'告知'</span>";
			}
		}

		return $data;
	}


	// For 地址轉經緯度
	public function addr_tf_location($address = '台北市中山區中山北路二段79號11樓')
	{
		$url 		= 'https://maps.googleapis.com/maps/api/geocode/json?';
		$aut 		= 'key=AIzaSyA_KLZdTl9YO1Efp04HcAuEdfmqjzcWQGk';
		$addr 		= '&address=' . urlencode($address);
		$com 		= '&components=country:TW%7C';
		$lan 		= '&language=zh-TW';

		$curl 		= curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL 			 => $url . $aut . $addr . $com . $lan,
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

		if ($err) {
			return $err;
		} else {
			return json_decode($response, TRUE);
		}
	}

	public function get_map_location($user_id)
	{
		if (is_array($res = $this->db->select('id, customer_no, formatted_address, name, location_lat, location_lng')
			->from($this->customer_manage_table)
			->where(array('location_lat !=' => '', 'location_lng !=' => '', 'is_delete' => 0, 'user_id' => $user_id))
			->get()
			->result_array())) {
			return $res;
		} else return FALSE;
	}

	public function is_customers_num_less_200($user_id)
	{
		if (
			200 <= $this->db->select('id')
			->from('customer_mgr')
			->where(array('is_delete' => 0, 'user_id' => $user_id))
			->get()
			->num_rows()
		) {
			return FALSE;
		} else return TRUE;
	}

	public function is_source_id_can_del($where, $user_id)
	{
		$where_data = 'user_id = ' . $user_id;
		if (is_array($where) and count($where) > 1) {
			for ($i = 0; $i < count($where); $i++) {
				if ($i == 0) $where_data .= ' AND (source_id = ' . $where[$i];
				else if ($i == count($where) - 1) $where_data .= ' OR source_id = ' . $where[$i] . ')';
				else $where_data .= ' OR source_id = ' . $where[$i];
			}
		} else if (is_array($where)) $where_data .= ' AND source_id = ' . $where[0];
		else $where_data .= ' AND source_id = ' . $where;

		if (
			0 < $this->db->select('id')
			->from($this->customer_manage_table)
			->where($where_data)
			->get()
			->num_rows()
		) {
			return FALSE;
		} else return TRUE;
	}

	public function del_source_id($user_id, $where)
	{
		$where_data = 'user_id = ' . $user_id;
		if (is_array($where) and count($where) > 1) {
			for ($i = 0; $i < count($where); $i++) {
				if ($i == 0) $where_data .= ' AND (id = ' . $where[$i];
				else if ($i == count($where) - 1) $where_data .= ' OR id = ' . $where[$i] . ')';
				else $where_data .= ' OR id = ' . $where[$i];
			}
		} else if (is_array($where)) $where_data .= ' AND id = ' . $where[0];
		else $where_data .= ' AND id = ' . $where;

		$this->db->where($where_data)->update($this->user_customer_source_table, array('is_delete' => 1));
	}

	public function set_status($user_id, $customer_id, $status)
	{
		$sta = customer_status_tf_str($status);

		$this->db->where(array('user_id' => $user_id, 'id' => $customer_id))->update($this->customer_manage_table, array('status' => $sta));
	}

	public function compose_condition_where($where_data)
	{
		$data = '';
		$data = 'M.user_id = ' . $where_data['minor_id'] . ' AND M.is_delete = 0 ';
		unset($where_data['login_token']);
		unset($where_data['minor_id']);
		unset($where_data['title']);

		foreach ($where_data as $key => $value) {
			$substr = '';
			$flag = FALSE;
			if (is_array($value)) {
				for ($i = 0; $i < count($value); $i++) {
					$va = ($key === 'status' ? customer_status_tf_str($value[$i]) : $value[$i]);
					if (!empty($va)) {
						if ($i != 0 and $flag) $substr .= ' OR ';
						$substr .= $key . ' = "' . $va . '"';
						$flag = TRUE;
					} else $flag = FALSE;
				}
				if (!empty($substr)) $data .= ' AND ( ' . $substr . ' )';
			} else {
				if (empty($value)) continue;
				else {
					$substr .= ' AND (' . $value . ' ) ';
				}
			}
		}
		return $data;
	}

	public function compose_condition_sql($where_data)
	{
		$sql['sql_count'] = $this->db->select('COUNT(id) as count, user_id')
			->from($this->customer_manage_table . ' as M')
			->where($where_data)
			->get_compiled_select();

		$sql['sql_all'] = $this->db->select('M.*, N.note as n_note')
			->from($this->customer_manage_table . ' as M')
			->join('(SELECT * FROM `condition_note` WHERE is_delete = 0) as N', 'ON M.user_id = N.minor_id AND M.id = N.customer_id', 'left')
			->where($where_data)
			->get_compiled_select();

		return $sql;
	}

	public function add_condition($user_id, $minor_id, $title, $sql, $get_selected)
	{
		$sql['sql_count'] = str_replace('\n', ' ', $sql['sql_count']);
		$sql['sql_count'] = str_replace('\"', '"', $sql['sql_count']);
		$sql['sql_all'] = str_replace('\n', ' ', $sql['sql_all']);
		$sql['sql_all'] = str_replace('\"', '"', $sql['sql_all']);

		return $this->db->insert('minor_condition', array(
			'user_id' => $user_id,
			'minor_id' => $minor_id,
			'title' => $title,
			'sql_count' => $sql['sql_count'],
			'sql_all' => $sql['sql_all'],
			'get_selected' => $get_selected
		));
	}

	public function update_condition($condition_id, $user_id, $minor_id, $title, $sql, $get_selected)
	{
		$sql['sql_count'] = str_replace('\n', ' ', $sql['sql_count']);
		$sql['sql_count'] = str_replace('\"', '"', $sql['sql_count']);
		$sql['sql_all'] = str_replace('\n', ' ', $sql['sql_all']);
		$sql['sql_all'] = str_replace('\"', '"', $sql['sql_all']);

		return $this->db->where(array('id' => $condition_id, 'user_id' => $user_id, 'minor_id' => $minor_id))
			->update('minor_condition', array(
				'title' => $title,
				'sql_count' => $sql['sql_count'],
				'sql_all' => $sql['sql_all'],
				'get_selected' => $get_selected,
			));
	}

	public function del_condition($condition_id, $user_id, $minor_id)
	{
		return $this->db->where(array('id' => $condition_id, 'user_id' => $user_id, 'minor_id' => $minor_id))
			->update('minor_condition', array('is_delete' => 1));
	}

	public function get_conditions($user_id, $minor_id)
	{
		$res = $this->db->select('id, title, sql_count, note_num,customer_no_json')
			->from('minor_condition')
			->where(array('user_id' => $user_id, 'minor_id' => $minor_id, 'is_delete' => 0))
			->get()
			->result_array();

		if (count($res) == 0) {
			$data =	array();
			return $data;
		} else {
			$data[] = array();

			for ($i = 0; $i < count($res); $i++) {
				$data[$i]['id']			=	$res[$i]['id'];
				$data[$i]['title']		=	$res[$i]['title'];

				//有編輯過
				if ($res[$i]['customer_no_json'] != '') {

					$data[$i]['customer_num']	=	count(json_decode($res[$i]['customer_no_json']));
				} else {
					$customer_num = $this->db->query($res[$i]['sql_count'])->row_array();
					$data[$i]['customer_num']	=	intval($customer_num['count']);
				}

				$data[$i]['note_num']	=	$res[$i]['note_num'];
			}
		}

		//20220520 保留註解寫法

		// $sql = '';
		// for ($i = 0; $i < count($res); $i++) {
		// 	if ($i != 0) $sql .= ',';
		// 	$sql .= 'C' . $i . '.count as c' . $i . '_count';
		// }
		// $this->db->select($sql)->from($this->master_minor_relation_table . ' as M');
		// $this->db->where(array('master_id' => $user_id, 'minor_id' => $minor_id));
		// foreach ($res as $key => $value) {
		// 	$this->db->join('(' . $value['sql_count'] . ') as C' . $key, 'M.minor_id = C' . $key . '.user_id', 'left');
		// }
		// $result = $this->db->get()->row_array();

		// $data = array();
		// for ($i = 0; $i < count($res); $i++) {
		// 	$data[$i]['id'] 			= $res[$i]['id'];
		// 	$data[$i]['title'] 			= $res[$i]['title'];
		// 	$data[$i]['customer_num'] 	= ($result['c' . $i . '_count'] ?: 0);
		// 	$data[$i]['note_num'] 		= $res[$i]['note_num'];
		// }

		return $data;
	}

	public function get_condition_count($condition_id, $user_id, $minor_id)
	{

		$condition = $this->db->select()
			->from('minor_condition')
			->where(array('id' => $condition_id, 'user_id' => $user_id, 'is_delete' => 0, 'minor_id' => $minor_id))
			->get()
			->row_array();




		// $sql = $this->db->select('sql_count')
		// 	->from('minor_condition')
		// 	->where(array('user_id' => $user_id, 'id' => $condition_id, 'minor_id' => $minor_id, 'is_delete' => 0))
		// 	->get()
		// 	->row_array();

		if (empty($condition)) {
			return 0;
		}


		//檢查是否經過編輯
		if (!empty($condition['customer_no_json'])) {

			$customerNoArray	=	json_decode($condition['customer_no_json'], true);

			return count($customerNoArray);
		} else {
			return $this->db->query($condition['sql_count'])->row_array()['count'];
		}
	}

	public function get_condition($condition_id, $user_id, $minor_id, $page)
	{
		if (empty(($sql = $this->db->select('sql_all,customer_no_json')
			->from('minor_condition')
			->where(array('user_id' => $user_id, 'id' => $condition_id, 'minor_id' => $minor_id, 'is_delete' => 0))
			->get()
			->row_array())['sql_all'])) {
			return 0;
		}


		// 先判斷有無編輯過


	



		// var_dump($sql['customer_no_json']);
		// exit;

		//名單經過編輯
		if (!empty($sql['customer_no_json'])) {

			$query =$sql ['sql_all']  . ' ORDER BY `customer_no` ';

			//原始客戶名單
			$originArray  = $this->db->query($query)->result_array();



			//經過編輯後的
			$processArray = array();
			// $customerNoArray	=	json_decode($sql['customer_no_json'],true); 
			$tmp =  str_replace(array('[', ']', '"'), "", $sql['customer_no_json']);

			$customerNoArray 	 =  explode(',', $tmp);
			// var_dump($customerNoArray);
			// var_dump($originArray);
			// exit;
			//存customer_no
			// $tmpArray 			= 	$customerNoArray['customerNoJson'];

			$count = 0;

			//	把customer_no 相符的丟進陣列
			for ($i = 0; $i < count($originArray); $i++) {
				// var_dump($originArray[$i]['id']);
				for ($j = 0; $j < count($customerNoArray); $j++) {

					if ($originArray[$i]['customer_no'] == $customerNoArray[$j]) {
						$processArray[$count++]	=	array_merge($processArray, $originArray[$i]);
						break;
					}
				}
			}


			$resultArray = array() ;
			$resultIndex = 0  ;

			$startIndex  = ($page-1)*$this->page_count ;
			
			if($startIndex > count($processArray) )return $resultArray  ;

			for ($k = $startIndex ; ($resultIndex < $this->page_count)  && ($k <count($processArray) ); $k++  ){
				$resultArray[$resultIndex++] =     $processArray[$k] ;
			}	
			

			return $resultArray;
		} else {
			$query = $sql['sql_all'] . ' ORDER BY `customer_no` ASC LIMIT ' . ($page - 1) * $this->page_count . ', ' . $this->page_count;
			return	 $this->db->query($query)->result_array();
		}
	}

	public function add_condition_note($insert_dta)
	{
		return $this->db->insert('condition_note', $insert_dta);
	}

	public function del_condition_note($user_id, $minor_id, $customer_id)
	{
		return $this->db->where(array('user_id' => $user_id, 'minor_id' => $minor_id, 'customer_id' => $customer_id))
			->update('condition_note', array('is_delete' => 1));
	}

	public function get_user_source_list_all($user_id)
	{
		return $this->db->select('id, source_name')
			->from($this->user_customer_source_table)
			->where(array('is_delete' => 0))
			->group_start()
			->or_where(array('user_id' => $user_id))
			->or_where(array('user_id' => 0))
			->group_end()
			->group_by('source_name')
			->get()
			->result_array();
	}

	public function compose_condition_selected($user_id, $data)
	{
		$status 	= array(1 => '告知', 2 => '約訪', 3 => '拜訪', 4 => '建議', 5 => '成交');
		$source_id 	= $this->get_user_source_list_all($user_id);
		$relation 	= array('S' => 'S', 'A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D');
		$level 		= array('S' => 'S', 'A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D');

		$output 	= array();
		if ($data['status'] == '') 		$data['status'] 	= array();
		if ($data['source_id'] == '') 	$data['source_id'] 	= array();
		if ($data['relation'] == '') 	$data['relation'] 	= array();
		if ($data['level'] == '') 		$data['level'] 		= array();

		foreach ($status as $key => $value) {
			$output['status'][] = array('key' => $key, 'title' => $value, 'is_selected' => (in_array($key, $data['status']) ? TRUE : FALSE));
		}

		foreach ($source_id as $key => $value) {
			$output['source_id'][] =
				array('key' => $value['id'], 'title' => $value['source_name'], 'is_selected' => (in_array($value['id'], $data['source_id']) ? TRUE : FALSE));
		}

		foreach ($relation as $key => $value) {
			$output['relation'][] = array('key' => $key, 'title' => $value, 'is_selected' => (in_array($key, $data['relation']) ? TRUE : FALSE));
		}

		foreach ($level as $key => $value) {
			$output['level'][] = array('key' => $key, 'title' => $value, 'is_selected' => (in_array($key, $data['level']) ? TRUE : FALSE));
		}

		return json_encode($output);
	}

	public function get_minor_condition($user_id, $minor_id, $condition_id)
	{
		$res = $this->db->select('get_selected')
			->from('minor_condition')
			->where(array('id' => $condition_id, 'user_id' => $user_id, 'minor_id' => $minor_id, 'is_delete' => 0))
			->get()
			->row_array();

		$res['data'] = json_decode($res['get_selected'], TRUE);
		unset($res['get_selected']);

		return $res;
	}

	public function get_connection_level($user_id, $customer_id)
	{

		// 距離 一年內最後一次會面 多久 
		$res = $this->db->select('*')
			->from($this->schedule_table)
			->where(array(
				'user_id' => $user_id,
				'customer_id' => $customer_id,
				'is_delete' => 0,
				'is_complete' => 1,
				'end_date <=' => Date('Y-m-d H:i:s'),
				'end_date >=' => Date('Y-m-d H:i:s', strtotime('-1 year'))
			))
			->order_by('end_date DESC')
			->limit(1, 0)
			->get()
			->row_array();

		$time = strtotime($res['end_date']);

		if (empty($res)) $connection = 'blue';
		else if ($time >= strtotime(Date('Y-m-d H:i:s', strtotime('-1 month')))) $connection = 'red';
		else if ($time >= strtotime(Date('Y-m-d H:i:s', strtotime('-6 month')))) $connection = 'orange';
		else $connection = 'yellow';

		return $connection;
	}

	//	===================2022 新增===============================


	//	編輯概況名單時db會存customer_no 
	public function editMinorCondition($data, $customerNoArray)
	{

		$syntax = array(
			'user_id'		=>	$data['login_token'],
			'minor_id'		=>	$data['minor_id'],
			'id'			=>	$data['condition_id'],
			'is_delete'		=>	0
		);

		$customer_no_json	=	json_encode($customerNoArray);

		// var_dump($data['login_token']);

		// exit;

		// var_dump($customer_no_json);
		// exit;
		return 	  $this->db->set('customer_no_json', $customer_no_json)
			->where($syntax)
			->update('minor_condition');
	}
}
