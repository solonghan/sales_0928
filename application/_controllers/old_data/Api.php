<?php
defined('BASEPATH') or exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
header("Content-Type:text/html; charset=utf-8");

class Api extends Base_Controller {

	private $login_url;
	private $status = FALSE;
	private $msg 	= '';


	public function __construct()
	{
		parent::__construct();

		$this->load->model('User_info_model');
		$this->load->model('Jwt_model');
		$this->login_url = base_url().'api/login';
	}

	// public function index()
	// {
	// 	echo "kljsadlkjd";
	// 	$data1 = $this->get_request_post(array(
	// 			['name', 			 '', '', 'text'],
	// 			['email', 			 '', '', 'text'],
	// 			['password', 		 '', '', 'text'],
	// 			['password_confirm', '', '', 'text'],
	// 	));

	// 	$data = array(
	// 		'email' => 'jkldjlksa',
	// 		'password' => 'sadasdas',
	// 	);

	// 	var_dump($data1);
	// 	echo "<br>";
	// 	var_dump($this->User_info_model->is_email_exist('jkldjlksa'));
	// 	echo "<br><br>";
	// 	var_dump($this->User_info_model->set_register($data));
	// 	echo "<br><br>";
	// 	var_dump($this->User_info_model->is_social_id_exist('g_id', 'adda'));
	// 	echo "<br>===================================================<br>";
	// }

	public function userinfo()
	{
		$user = $this->check_user_token();
		$data = $this->public_user_data($user);

		$this->output(TRUE, "取得資料成功", array(
			"data"   =>	$data
		));	
	}

	public function flow(){
		$user = $this->check_user_token(FALSE);
		$uri = $this->post("uri");
		if ($user !== FALSE) {
			$this->flow_record($uri, $user['id']);
		}else{
			$this->flow_record($uri);
		}
		$this->output(TRUE, "已紀錄");
	}



	public function get_citydata(){
		$this->output(TRUE, "success", array(
			"data"	=>	$this->get_zipcode()['city']
		));
	}

	public function img_upload(){
		$this->load->model("Pic_model");
		$path = $this->Pic_model->crop_img_upload_and_create_thumb("image", FALSE, 50);
		
		if ($path != "") {
			$this->output(TRUE, "上傳成功", array("path"=>$path));
		}else{
			$this->output(FALSE, "上傳圖片發生錯誤");
		}
	}













/* User information - Start */ 

	public function get_user_info()
	{
		$this->data = $this->get_request_post(array(
				['login_token' , '', 'token is not empty', 'text'],
		));

		$this->response_json(TRUE, '', array(
				'info' => $this->User_info_model->get_user_info($this->data['login_token']),
		));
	}

	public function get_user_notice()
	{
		$this->data = $this->get_request_post(array(
				['login_token' , '', 'token is not empty', 'text'],
		));

		$this->response_json(TRUE, '', array(
				'notice' => $this->User_info_model->get_user_notice($this->data['login_token']),
		));
	}

	public function set_user_notice_readed()
	{	
		$this->data = $this->get_request_post(array(
				['login_token'  , '', 'token is not empty'		, 'text'],
				['id' 			, '', 'notice id is not empty'	, 'text'],
		));

		if ($this->User_info_model->is_notice_confirmed($this->data['login_token'], $this->data['id']) == 1)
		{
			if ($this->User_info_model->set_user_notice_readed($this->data['login_token'], $this->data['id']))
			{
				$this->status 	= TRUE;
				$this->msg 		= '已讀修改成功';
			}
			else
			{
				$this->status = TRUE;
				$this->msg 		= '已讀修改失敗';
			}

			$this->response_json($this->status, $this->msg);
		}
		else
		{
			$this->response_json(FALSE, '此通知無法被此使用者更改');
		}
	}

	public function is_open_ai_interview()
	{
		$this->data = $this->get_request_post(array(
				['login_token' , '', 'token is not empty', 'text'],
		));

		$this->response_json(TRUE, '', array(
				'ai_interview' => $this->User_info_model->is_open_ai_interview($this->data['login_token']),
		));
	}

	public function upgrad()
	{
		$this->data = $this->get_request_post(array(
				['login_token' , '', 'login token is not empty', 'text'],
				['store' , '', 'store is not empty', 'text'],
				['plan'  , '', 'plan is not empty',  'text'],
		));

		if (( ! $store_obj_name = is_upgra_store_exists($this->data['store'])) OR 
			( ! is_upgra_plan_exists($this->data['plan'])))
		{
			$this->response_json(FALSE, '');
		}

		if ( ! $user_priv_date = $this->User_info_model->get_priv_date($this->data['login_token']))
			$this->response_json(FALSE, '');

		require_once(APPPATH . 'libraries/upgrad/' . $store_obj_name . '.php');
		$store_obj = new $store_obj_name();
		$upgrad_data = array_merge($store_obj->buy_upgrad($this->data['plan'], $user_priv_date['privilege_end_date']), array(
				'user_id' => $this->data['login_token'],
		));

		if ($this->User_info_model->add_upgrad($upgrad_data))
		{
			$this->response_json(TRUE, output_msg('sales_06'));
		}
		else
		{
			$this->response_json(FALSE, output_msg('sales_07'));
		}
	}
/* User information - End */



/* home page - Start */

	public function get_year_goal_data()
	{
		$this->data = $this->get_request_post(array(
				['login_token'  , '', 'token is not empty', 'text'],
				['year' 		, '', 'year is not empty' , 'text'],
		));

		$result = $this->User_info_model->get_year_goal_data($this->data['login_token'], $this->data['year']);
		if ($result == FALSE)
			$result = array();

		for ($i = 0; $i < count($result); $i++)
		{
			unset($result[$i]['user_id']);
			unset($result[$i]['year']);
			unset($result[$i]['create_date']);
			unset($result[$i]['is_delete']);
		}

		if ($result == FALSE)
		{
			$this->status = TRUE;
			$this->msg = '取得成功';
			$data = array();
			$total_percent = 0;
		}
		else
		{
			$this->status = TRUE;
			$this->msg = '取得成功';
			$data = $this->count_item_goal_percent($result);
			$total_percent = $this->count_total_percent($result);

			// For 前端固定目標(寫死)
			foreach ($data as $key => $value)
			{
				if ($value['name'] == 'FYP')
					$data[$key]['type'] = 1;
				elseif ($value['name'] == 'FYC' OR $value['name'] == '增員')
					$data[$key]['type'] = 2;
				else
					$data[$key]['type'] = 3;
			}
		}

		// For 固定目標(寫死)
		$fix_data = array(
				0 => array(
						'id' 		=> NULL,
						'name' 		=> 'FYP',
						'now_num' 	=> 0,
						'total_num' => 0,
						'percent' 	=> 0,
						'type' 		=> 1,
				),
				1 => array(
						'id' 		=> NULL,
						'name' 		=> 'FYC',
						'now_num' 	=> 0,
						'total_num' => 0,
						'percent' 	=> 0,
						'type' 		=> 2,
				),
				2 => array(
						'id' 		=> NULL,
						'name' 		=> '增員',
						'now_num' 	=> 0,
						'total_num' => 0,
						'percent' 	=> 0,
						'type' 		=> 2,
				),
		);
		$fyp_exist = FALSE;
		$fyc_exist = FALSE;
		$add_exist = FALSE;
		for ($i = 0; $i < count($data); $i++)
		{
			if ($data[$i]['name'] == 'FYP')	
				$fyp_exist = TRUE;
			elseif ($data[$i]['name'] == 'FYC')
				$fyc_exist = TRUE;
			elseif ($data[$i]['name'] == '增員')
				$add_exist = TRUE;
		}

		for ($i = 0; $i < count($fix_data); $i++)
		{ 
			if ( ! $fyp_exist AND $i == 0)
			{
				$data = array_merge(array($fix_data[0]), $data);
				continue;
			}
			elseif ( ! $fyc_exist AND $i == 1)
			{
				$data = array_merge(array($fix_data[1]), $data);
				continue;
			}
			elseif ( ! $add_exist AND $i == 2)
			{
				$data = array_merge(array($fix_data[2]), $data);
				continue;
			}
		}
		// For 固定目標(寫死) - End

		$this->response_json($this->status, $this->msg, array(
				'data' => $data,
				'total_percent' => $total_percent,
		));
	}

	public function get_month_goal_data()
	{
		$this->data = $this->get_request_post(array(
				['login_token'  , '', 'token is not empty' , 'text'],
				['year' 		, '', 'year is not empty'  , 'text'],
				['month' 		, '', 'month is not empty' , 'text'],
		));

		$result = $this->User_info_model->get_month_goal_data($this->data['login_token'], $this->data['year'], $this->data['month']);
		if ($result == FALSE)
			$result = array();

		for ($i = 0; $i < count($result); $i++)
		{ 
			unset($result[$i]['user_id']);
			unset($result[$i]['year']);
			unset($result[$i]['create_date']);
			unset($result[$i]['is_delete']);
		}
		if ($result == FALSE)
		{
			$this->status = TRUE;
			$this->msg = '取得成功';
			$data = array();
			$total_percent = 0;
		}
		else
		{
			$this->status = TRUE;
			$this->msg = '取得成功';
			$data = $this->count_item_goal_percent($result);
			$total_percent = $this->count_total_percent($result);

			// For 前端固定目標(寫死)
			foreach ($data as $key => $value)
			{
				if ($value['name'] == 'FYP')
					$data[$key]['type'] = 1;
				elseif ($value['name'] == 'FYC' OR $value['name'] == '增員')
					$data[$key]['type'] = 2;
				else
					$data[$key]['type'] = 3;
			}
		}

		// For 固定目標(寫死)
		$fix_data = array(
				0 => array(
						'id' 		=> NULL,
						'month' 	=> $this->data['month'],
						'name' 		=> 'FYP',
						'now_num' 	=> 0,
						'total_num' => 0,
						'percent' 	=> 0,
						'type' 		=> 1,
				),
				1 => array(
						'id' 		=> NULL,
						'month' 	=> $this->data['month'],
						'name' 		=> 'FYC',
						'now_num' 	=> 0,
						'total_num' => 0,
						'percent' 	=> 0,
						'type' 		=> 2,
				),
				2 => array(
						'id' 		=> NULL,
						'month' 	=> $this->data['month'],
						'name' 		=> '增員',
						'now_num' 	=> 0,
						'total_num' => 0,
						'percent' 	=> 0,
						'type' 		=> 2,
				),
		);
		$fyp_exist = FALSE;
		$fyc_exist = FALSE;
		$add_exist = FALSE;
		for ($i = 0; $i < count($data); $i++)
		{
			if ($data[$i]['name'] == 'FYP')	
				$fyp_exist = TRUE;
			elseif ($data[$i]['name'] == 'FYC')
				$fyc_exist = TRUE;
			elseif ($data[$i]['name'] == '增員')
				$add_exist = TRUE;
		}

		for ($i = 0; $i < count($fix_data); $i++)
		{ 
			if ( ! $fyp_exist AND $i == 0)
			{
				$data = array_merge(array($fix_data[0]), $data);
				continue;
			}
			elseif ( ! $fyc_exist AND $i == 1)
			{
				$data = array_merge(array($fix_data[1]), $data);
				continue;
			}
			elseif ( ! $add_exist AND $i == 2)
			{
				$data = array_merge(array($fix_data[2]), $data);
				continue;
			}
		}
		// For 固定目標(寫死) - End

		$this->response_json($this->status, $this->msg, array(
				'data' 	  => $data,
				'total_percent' => $total_percent,
		));
	}

	public function set_month_goal_data()
	{
		$data = $this->get_request_post(array(
				['login_token'  , '', 'token is not empty' , 'text'],
				['id' 			, '', '', 'text'],
				['name' 		, '', 'name is not empty', 'text'],
				['now_num' 		, '', '', 'text'],
				['total_num' 	, '', '', 'text'],
		));
		$data['year']  = date("Y");
		$data['month'] = date("n");

		if ($data['id'] == '')
		{
			$option = 'add';
			$data['user_id'] = $data['login_token'];
			unset($data['login_token']);
			unset($data['id']);
			if ($this->User_info_model->is_month_goal_exist($data['user_id'], $data['name'], $data['year'], $data['month']) != 0)
				$this->response_json(FALSE, '名稱重複, 請重新命名!');
		}
		else
		{
			$option = 'edit';
			$data['user_id'] = $data['login_token'];
			unset($data['login_token']);
		}

		if ($this->User_info_model->set_month_goal_data($data, $option))
		{
			$this->response_json(TRUE, '成功', array('data' => $data));
		}
		else
		{
			$this->response_json(FALSE, '失敗');
		}
	}

	public function set_year_goal_data()
	{
		$data = $this->get_request_post(array(
				['login_token'  , '', 'token is not empty' , 'text'],
				['id' 			, '', '', 'text'],
				['name' 		, '', '', 'text'],
				['now_num' 		, '', '', 'text'],
				['total_num' 	, '', '', 'text'],
		));
		$data['year'] = date("Y");

		if ($data['id'] == '')
		{
			$option = 'add';
			$data['user_id'] = $data['login_token'];
			unset($data['login_token']);
			unset($data['id']);
			if ($this->User_info_model->is_year_goal_exist($data['user_id'], $data['name'], $data['year']) != 0)
				$this->response_json(FALSE, '名稱重複, 請重新命名!');
		}
		else
		{
			$option = 'edit';
			$data['user_id'] = $data['login_token'];
			unset($data['login_token']);
		}
		$data['year'] = date("Y");

		if ($this->User_info_model->set_year_goal_data($data, $option))
		{
			$this->response_json(TRUE, '成功', array('data' => $data));
		}
		else
		{
			$this->response_json(FALSE, '失敗');
		}
	}

	public function get_goals($year, $month, $id = FALSE, $page = 1)
	{
		$data = $this->get_request_post(array(
				['login_token'  , '', 'token is not empty', 'text', 'token'],
		));

		if ( ! (is_numeric($year) AND (4 == strlen(floor($year))) AND is_numeric($month) AND (2 >= strlen(floor($month)))))
			$this->response_json(FALSE, '格式不支援');

		$where_data = array(
				'user_id' => $data['login_token'],
				'year' => $year,
				'month' => $month,
		);

		if (is_numeric($id) )
		{
			$where_data = array_merge($where_data, array(
					'id' => $id,
			));
		}


		$goal_data = $this->User_info_model->get_goal($where_data);

		$flag_ary = array('A&H' => FALSE, '躉繳' => FALSE, '壽險期繳追蹤名單' => FALSE);
		require_once(APPPATH . 'libraries/Goal_item.php');
		$all_goal_data = array();
		foreach ($goal_data as $key => $value)
		{
			$Goal = new Goal_item($value);
			if ($goal_customer_data = $this->User_info_model->get_goal_customer($value['id']))
			{
				$Goal->set_customer($goal_customer_data);
				$Goal->count();
			}

			if (array_key_exists($value['name'], $flag_ary))
				$flag_ary[$value['name']] = $key;

			$all_goal_data[$key] = $Goal->print_data($page);
		}

		$goals = array();
		// for 固定的3個目標 (寫死)
		foreach ($flag_ary as $key => $value)
		{
			if ($value !== FALSE)
			{
				array_unshift($goals, $all_goal_data[$value]);
				unset($all_goal_data[$value]);
				continue;
			}

			$Goal = new Goal_item();
			$Goal->set_name($key);
			array_unshift($goals, $Goal->print_data());
		}
		$goals = array_merge($goals, $all_goal_data);

		$this->response_json(TRUE, '', array(
				'data' => $goals,
		));
	}

	public function customer_goal($opt = FALSE)
	{
		if ($opt == 'add')
		{
			$data = $this->get_request_post(array(
					['login_token'   , '', 'token is not empty' 		, 'text', 'token'],
					['goal_id' 		 , '', 'goal id is not empty'		, 'text'],
					['customer_name' , '', 'customer name is not empty' , 'text'],
					['no' 			 , '', '', 'text'],
					['estimate_money', '', '', 'text'],
					['deal_money' 	 , '', '', 'text'],
			));

			unset($data['login_token']);

			if ($insert_id = $this->User_info_model->add_customer_goal($data))
				$this->response_json(TRUE, '新增成功', array('customer_id' => $insert_id));
		}
		elseif ($opt == 'edit') 			// 可能會有漏洞
		{
			$data = $this->get_request_post(array(
					['login_token'   , '', 'token is not empty' 		, 'text', 'token'],
					['goal_id' 		 , '', 'goal id is not empty'		, 'text'],
					['customer_id' , '', 'customer id is not empty'		, 'text'],
					['customer_name' , '', 'customer name is not empty' , 'text'],
					['no' 			 , '', '', 'text'],
					['estimate_money', '', '', 'text'],
					['deal_money' 	 , '', '', 'text'],
			));

			$where_data = array(
					'id' => $data['customer_id'],
					'goal_id' => $data['goal_id'],
			);

			unset($data['login_token']);
			unset($data['goal_id']);
			unset($data['customer_id']);

			if ($this->User_info_model->modify_customer_goal($where_data, $data))
				$this->response_json(TRUE, '修改成功');
		}
		elseif ($opt == 'del')
		{
			$data = $this->get_request_post(array(
					['login_token'   , '', 'token is not empty' , 'text', 'token'],
					['goal_id' 		 , '', 'goal id is not empty' , 'text'],
					['customer_id' 	 , '', 'customer id is not empty'		, 'text'],
			));

			$where_data = array(
					'id' => $data['customer_id'],
					'goal_id' => $data['goal_id'],
			);

			$del_data = array(
					'is_delete' => 1,
			);

			if ($this->User_info_model->modify_customer_goal($where_data, $del_data))
				$this->response_json(TRUE, '刪除成功');
		}
	}

	public function goals($opt = FALSE)
	{
		if ($opt == 'add')
		{
			$goal_data = $this->get_request_post(array(
					['login_token'   , '', 'token is not empty' 		, 'text', 'token'],
					['name' 		 , '', 'name is not empty'			, 'text'],
					['year' 		 , '', 'year is not empty'			, 'text'],
					['month' 		 , '', 'month is not empty'			, 'text'],
					['total_money' 	 , '', 'total money is not empty'   , 'text'],
			));

			$customer_data = $this->get_request_post(array(
					['customer_name' , '', '', 'text'],
					['no' 			 , '', '', 'text'],
					['estimate_money', '', '', 'text'],
					['deal_money' 	 , '', '', 'text'],
			));

			$goal_data['user_id'] = $goal_data['login_token'];
			unset($goal_data['login_token']);

			if ($this->User_info_model->add_goal($goal_data, $customer_data))
				$this->response_json(TRUE, '新增成功');
		}
		elseif ($opt == 'edit')
		{
			$goal_data = $this->get_request_post(array(
					['login_token'   , '', 'token is not empty' 		, 'text', 'token'],
					['goal_id' 		 , '', 'goal id is not empty'		, 'text'],
					['name' 		 , '', 'name is not empty'			, 'text'],
					['total_money' 	 , '', 'total money is not empty'   , 'text'],
			));

			$where_data = array(
					'user_id' => $goal_data['login_token'],
					'id' => $goal_data['goal_id'],
			);

			unset($goal_data['login_token']);
			unset($goal_data['goal_id']);

			if ($this->User_info_model->modify_goal($where_data, $goal_data))
				$this->response_json(TRUE, '修改成功');
			else
				$this->response_json(TRUE, '發生錯誤');
		}
	}
/* home page - End */



/* Customer manage - Start */

	public function get_all_customer_manage()
	{
		$this->load->model('Customer_manage_model');
		$this->load->helper('tw_zipcode');
		$data = $this->get_request_post(array(
				['login_token' , '', 'token is not empty', 'text'],
				['filter' , json_encode(array()), '', 'text'],
		));

		if ( ! (is_string($data['filter']) AND ((json_last_error() == JSON_ERROR_NONE) ? TRUE : FALSE)))
			$this->response_json(FALSE, 'filter 類型不支援');

		$where_data = array();
		foreach (json_decode($data['filter'], TRUE) as $key => $value)
		{
			if ( ! empty($value) AND $key != 'status')
				$where_data[$key] = $value;

			if ( ! empty($value) AND $key == 'status')
				$where_data[$key] = customer_status_tf_str($value);
		}

		$where_data = array_merge($where_data, array(
				'user_id' => $data['login_token'],
				'is_delete' => 0,
		));

		$user_customer_info   = $this->Customer_manage_model->get_user_all_customer($data['login_token'], $where_data);
		$user_customer_source = $this->Customer_manage_model->get_user_all_source($data['login_token']);
		// $user_customer_field  = $this->Customer_manage_model->get_user_all_field_name($data['login_token']);

		if ( ! isset($user_customer_info))
			$user_customer_info = array();
		
		if ( ! isset($user_customer_source))
			$user_customer_source = array();

		$customer = array();
		foreach ($user_customer_info as $key => $value)
		{
			for ($i = 0; $i < count($user_customer_source); $i++)
			{
				if ($user_customer_source[$i]['id'] == $value['source_id'])
					$value['source'] = $user_customer_source[$i]['source_name'];
			}

			if ($value['photo'] != NULL)
				$value['photo'] = base_url() . $value['photo'];

			$field = $this->Customer_manage_model->get_customer_field_value_by_customer($value['id']);
			$extra_field = array();

			for ($i = 0; $i < count($field); $i++)
			{
				$extra_field[$i] = $field[$i]['field_value'];
			}

			$customer[$key] =  [
					'id' 			=> $value['id'],
					'customer_no' 	=> $value['customer_no'],
					'connection' 	=> 'high',
					'name' 			=> $value['name'],
					'photo' 		=> $value['photo'],
					'status' 		=> customer_status_tf($value['status']),
					'level' 		=> $value['level'],
					'relation'  	=> $value['relation'],
					'source' 		=> $value['source'],
					'extra_field' 	=> $extra_field,
					'city' 			=> $value['city'],
					'dist' 			=>$value['dist'],
			];
		}

		$this->response_json(TRUE, '', array(
				'data' => $customer,
		));
	}

	public function get_customer_mgr_info()
	{
		$this->load->model('Customer_manage_model');
		$this->load->helper('tw_zipcode');
		$data = $this->get_request_post(array(
				['login_token' , '', 'token is not empty', 'text'],
				['id' , '', 'id no is not empty', 'text'],
		));

		$user_customer_info = $this->Customer_manage_model->get_mgr_customer_info($data['login_token'], $data['id']);

		if ( ! isset($user_customer_info))
		{
			$user_customer_info = array();
		}
		else
		{
			$user_customer_info['source'] = $this->Customer_manage_model->get_customer_source($data['login_token'], $user_customer_info['source_id'])['source_name'];
			$user_customer_info['status'] = customer_status_tf($user_customer_info['status']);
			$field = $this->Customer_manage_model->get_customer_field_value_by_customer($user_customer_info['id']);
			if ($user_customer_info['photo'] != NULL)
				$user_customer_info['photo'] = base_url() . $user_customer_info['photo'];

			$user_customer_info['extra_field'] = array();
			foreach ($field as $key => $value)
			{	
				$user_customer_info['extra_field'][$key]['field_name']  = $value['field_name'];
				$user_customer_info['extra_field'][$key]['field_value'] = $value['field_value'];
			}
		}

		$this->response_json(TRUE, '', array(
				'data' => $user_customer_info,
		));
	}

	public function get_user_source_list()
	{
		$this->load->model('Customer_manage_model');
		$data = $this->get_request_post(array(
				['login_token' , '', 'token is not empty', 'text'],
		));
		$user_custome_source_list = array();
		$user_source_list = $this->Customer_manage_model->get_user_source_list($data['login_token']);
		if ( ! isset($user_source_list))
			$user_source_list = array();

		foreach ($user_source_list as $key => $value)
		{	
			$user_custome_source_list[$key]['id'] = $value['id'];
			$user_custome_source_list[$key]['source_name'] = $value['source_name'];
		}

		$this->response_json(TRUE, '', array(
				'data' => $user_custome_source_list,
		));
	}

	public function set_user_customer_info()
	{
		$this->load->model('Customer_manage_model');
		$data = $this->get_request_post(array(
				['login_token'  , '', 		'token is not empty', 		'text'],
				['option' 		, '', 		'option is not empty', 		'text'],
				['name' 		, '', 		'name is not empty', 		'text'],
				['source_id' 	, '', 		'source_id is not empty', 	'text'],
				['relation' 	, '', 		'relation is not empty', 	'text'],
				['level' 		, '', 		'level is not empty', 		'text'],
				['job' 			, NULL, 	'', 'text'],
				['birthday' 	, NULL, 	'', 'text'],
				['city' 		, 0, 		'', 'text'],
				['dist' 		, 100, 		'', 'text'],
				['address' 		, NULL, 	'', 'text'],
				['text' 		, NULL, 	'', 'text'],
				['photo' 		, NULL, 	'', 'text'],
				['extra_field' 	, json_encode(array()),  '', 'array'],
		));
		$option = $data['option'];
		$extra_field = json_decode($data['extra_field'], TRUE);

		if ($option == 'add' OR $option == 'edit')
		{
			unset($data['option']);
			$data['user_id'] = $data['login_token'];
			unset($data['login_token']);
			unset($data['extra_field']);
			$data['customer_no'] = $this->Customer_manage_model->get_customer_num($data['user_id']);
		}

		if ($option == 'edit')
		{
			$id = $this->get_request_post(array(
					['id', '', 'id is not empty', 'text'],
			));
			$data = array_merge($data, $id);
		}

		if ($insert_id = $this->Customer_manage_model->set_user_customer_info($data, $option))
		{
			if ($option == 'edit')
				$insert_id = $data['id'];

			if ( ! empty($extra_field))
			{	
				$field_data = array();
				$count = 0 ;
				foreach ($extra_field as $key => $value)
				{
					$field_data[$count]['customer_id'] = $insert_id;
					$field_data[$count]['field_name']  = $key;
					$field_data[$count]['field_value'] = $value;
					$count++;
				}

				if ( ! $this->Customer_manage_model->set_customer_field($field_data, $option, $insert_id))
					$this->response_json(FALSE, '新增/編輯自訂欄位失敗!');
			}
			$this->response_json(TRUE, '新增/編輯成功');
		}
		else
		{
			$this->response_json(FALSE, '新增/編輯客戶失敗!');
		}
	}

	public function set_customer_source()
	{
		$this->load->model('Customer_manage_model');
		$data = $this->get_request_post(array(
				['login_token'  , '', 'token is not empty',  'text'],
				['source_name' 	, '', 'source name is not empty', 'text'],
				['option' 		, '', 'option is not empty', 'text'],
		));
		$option = $data['option'];
		$data['user_id'] = $data['login_token'];
		unset($data['login_token']);
		unset($data['option']);
		$this->Customer_manage_model->set_customer_source($data, $option);
		$this->response_json(TRUE, '新增成功');
	}

	public function tw_zipcode()
	{	
		$this->load->helper('tw_zipcode');

		$this->response_json(TRUE, '', array(
				'data' => get_zipcode(),
		));
	}

	public function customer_manage_job()
	{
		$this->load->model('Customer_manage_model');
		$data = $this->get_request_post(array(
				['login_token'  , '', 'token is not empty' , 'text', 'token'],
		));

		$out_data = array();
		foreach ($this->Customer_manage_model->get_customer_job($data['login_token']) as $key => $value)
		{
			if ( ! in_array($value['job'], $out_data))
				array_push($out_data, $value['job']);
		}

		$this->response_json(TRUE, '修改成功', array(
				'data' => $out_data,
		));
	}

	public function upload_img()
	{
		$data = $this->get_request_post(array(
				['login_token', '', 'token is not empty' 	, 'text', 'token'],
				['dir_name', 	'', 'dir name is not empty' , 'text'],
		));

		// $config['upload_path']          = $data['dir_name'];
		// // $config['file_name'] 			= uniqid() .'.jpg';
		// $config['allowed_types']        = 'gif|jpg|png';
		// $config['max_size']             = 100;
		// $config['max_width']            = 1024;
		// $config['max_height']           = 1024;
		// $this->upload->initialize($config);
		$this->load->library('upload');

        if ( ! $this->upload->do_upload('img'))
        {
            $error = array('error' => $this->upload->display_errors());
            $this->response_json(TRUE, '', array(
					'error' => $error,
			));
        }
        else
        {
            $this->response_json(TRUE, '', array(
					'img_src' => $data['dir_name'] .'/'. $this->upload->data()['file_name'],
			));
        }

        // Base64法, 前端不喜歡。
		// $img  = base64_decode($data['img']);
		// $uni  = uniqid();
		// $file = __DIR__ . '/../../uploads/customer_manage/'. $uni .'.jpg';

		// if (file_put_contents($file, $img))
		// {
		// 	$this->response_json(TRUE, '', array(
		// 			'url' => $file,
		// 	));
		// }
	}

	// Get mothed
	public function create_excel_token()
	{
		$this->load->model('Jwt_model', 'Jwt');
		$data = $this->get_request_post(array(
				// ['login_token', '', 'token is not empty' 	, 'text', 'token'],
				['file_name', '', '' 	, 'text'],
		));

		// $token = $this->Jwt->excel_import_token($data['user_id'], $data['file_name']);
		$token = $this->Jwt->excel_import_token(16, $data['file_name']);
		$this->response_json(TRUE, 'token建立成功', array(
				'token' => $token,
		));
	}

	// public function uploads_excel($token)
	// {
	// 	// if success => header view_excel api
	// 	if ($this->input->post('file_name'))
	// 	{
	// 		$this->load->model('Jwt_model', 'Jwt');
	// 		$data = $this->get_request_post(array(
	// 				['file_name', '', 'file name is not empty' 	, 'text'],
	// 		));
	// 		if ($user = $this->Jwt->verify_token($token))
	// 		{
	// 			$token = $this->Jwt->excel_import_token($user['user_id'], $data['file_name']);
	// 			header("Location: " . base_url() . 'Api/view_excel/' .  $token);
	// 		}
	// 		else
	// 		{
	// 			$this->response_json(TRUE, 'token驗證失敗');
	// 		}
	// 	}
	// 	else
	// 	{
	// 		// ajax call upload_img apic
	// 		// post to uploads_excel/$token ( file_name
	// 	}
	// }

	// public function view_excel($token)
	// {
	// 	$this->load->model('PhpSpreadsheet_excel_model', 'Csv');
	// 	$this->load->model('Customer_manage_model', 'Customer');
	// 	$this->load->model('Jwt_model', 'Jwt');
	// 	$excel_import_token = $this->Jwt->verify_token($token);
	// 	$this->Csv->read_verify_view($excel_import_token['file_name'], $this->Customer, $excel_import_token['user_id'], $token, '3');
	// 	// var_dump($excel_import_token);
	// }

	// public function import_excel_for_view_excel($token)
	// {
	// 	$this->load->model('PhpSpreadsheet_excel_model', 'Csv');
	// 	$verified_data = $this->input->post();
	// 	unset($verified_data['example_length']);

	// 	$excel_import_token = $this->Jwt->verify_token($token);
	// 	$this->Csv->set_package_reader($excel_import_token['file_name']);
	// 	// $this->Csv->set_package_reader('uploads/excel_files/xddddd.csv');

	// 	foreach ($verified_data as $key => $value)
	// 	{
	// 		if ($value == 'on')
	// 		{
	// 			$this->Csv->r_line("$key");
	// 		}
	// 	}

	// 	$data = $this->Csv->read_data;

	// 	// 寫入DB
	// }
/* Customer manage - End */



/* Calendar & memo - Start */

	public function memo($opt = FALSE, $id = FALSE)
	{
		$this->load->model('Calendar_model');

		if ($opt == 'add')
		{
			$data = $this->get_request_post(array(
					['login_token' , '', 'token is not empty', 'text'],
					['color' , '', 'color is not empty', 'text'],
					['text' , '', '', 'text'],
			));

			$data['user_id'] = $data['login_token'];
			unset($data['login_token']);

			if ($this->Calendar_model->add_memo($data))
				$this->response_json(TRUE, '新增成功');
			else
				$this->response_json(FALSE, '發生錯誤');
		}
		elseif (is_numeric($opt))
		{
			$data = $this->get_request_post(array(
					['login_token' , '', 'token is not empty', 'text'],
					['color' , '', 'color is not empty', 'text'],
					['text' , '', '', 'text'],
			));

			$where_data = array('id' => $opt, 'user_id' => $data['login_token']);
			unset($data['login_token']);

			if ($this->Calendar_model->modify_memo($data, $where_data))
				$this->response_json(TRUE, '修改成功');
			else
				$this->response_json(FALSE, '發生錯誤');
		}
		elseif ($opt == 'del' AND is_numeric($id))
		{
			$data = $this->get_request_post(array(
					['login_token' , '', 'token is not empty', 'text'],
			));

			$where_data = array('id' => $id, 'user_id' => $data['login_token']);

			$del_data = array('is_delete' => 1);

			if ($this->Calendar_model->modify_memo($del_data, $where_data))
				$this->response_json(TRUE, '修改成功');
			else
				$this->response_json(FALSE, '發生錯誤');
		}
	}

	public function get_memo()
	{
		$this->load->model('Calendar_model');

		$data = $this->get_request_post(array(
					['login_token' , '', '', 'text', 'token'],
		));

		$o_data = $this->Calendar_model->get_memo($data['login_token']);
		if (isset($o_data))
		{
			$this->response_json(TRUE, '', array(
					'data' => $o_data,
			));
		}
		else
		{
			$this->response_json(FALSE, '發生錯誤');
		}
	}

	public function schedule_item($opt = FALSE, $id = FALSE)
	{
		$this->load->model('Calendar_model');

		if ($opt == 'add')
		{
			$data = $this->get_request_post(array(
					['login_token' , '', 'token is not empty', 'text'],
					['item_name' , '', 'item name is not empty', 'text'],
			));

			$data['user_id'] = $data['login_token'];
			unset($data['login_token']);

			if ($insert_id = $this->Calendar_model->add_schedule_item($data))
			{
				$this->response_json(TRUE, '新增成功', array(
						'item_id' => $insert_id,
				));
			}
			else
				$this->response_json(FALSE, '發生錯誤');
		}
		elseif (is_numeric($opt))
		{
			$data = $this->get_request_post(array(
					['login_token' , '', 'token is not empty', 'text'],
					['item_name' , '', 'item name is not empty', 'text'],
			));

			$where_data = array('id' => $opt, 'user_id' => $data['login_token']);
			unset($data['login_token']);

			if ($this->Calendar_model->modify_schedule_item($data, $where_data))
				$this->response_json(TRUE, '修改成功');
			else
				$this->response_json(FALSE, '發生錯誤');
		}
		elseif ($opt == 'del') //  AND is_numeric($id)
		{
			$data = $this->get_request_post(array(
					['login_token' , '', 'token is not empty', 'text'],
					['delete' , '', 'delete[] array is not empty', 'array'],
			));

			$where_data = array('user_id' => $data['login_token']);

			$del_data = array('is_delete' => 1);

			if ($this->Calendar_model->del_schedule_item($del_data, $where_data, $data['delete']))
				$this->response_json(TRUE, '修改成功');
			else
				$this->response_json(FALSE, '發生錯誤或是部分刪除失敗');
		}
	}

	public function get_schedule_item()
	{
		$this->load->model('Calendar_model');

		$data = $this->get_request_post(array(
					['login_token' , '', '', 'text', 'token'],
		));

		$f_data = $this->Calendar_model->get_schedule_item_fixed();
		$s_data = $this->Calendar_model->get_schedule_item($data['login_token']);
		if ($f_data)
		{
			$this->response_json(TRUE, '', array(
					'fixed_data' => $f_data,
					'self_data' => $s_data,
			));
		}
		else
		{
			$this->response_json(FALSE, '發生錯誤');
		}
	}

	public function get_customer_name()
	{
		$this->load->model('Calendar_model');

		$data = $this->get_request_post(array(
					['login_token' , '', '', 'text', 'token'],
					['name' , '', 'name is not empty', 'text'],
		));

		$like_data = array(
				'name' => $data['name'],
		);

		$this->response_json(TRUE, '', array(
				'data' => $this->Calendar_model->get_customer_name($data['login_token'], $like_data),
		));
	}

	public function schedule($opt = FALSE, $id = FALSE)
	{
		$this->load->model('Calendar_model');

		if ($opt == 'add')
		{
			$data = $this->get_request_post(array(
					['login_token' , '', '', 'text', 'token'],
					['customer_id' , '', 'customer id is not empty', 'text'],
					['item_id' , '', 'item id is not empty', 'text'],
					['alert' , 0, '', 'text'],
					['note' , 0, '', 'text'],
					['start_date' , date('Y-m-d H:i:s'), 'start date is not empty', 'text'],
					['end_date' , date('Y-m-d H:i:s', strtotime('+1 hour')), 'end date is not empty', 'text'],
			));

			if ($this->Calendar_model->is_schedule_can_add($data['login_token'], $data['start_date'], $data['end_date']) > 0)
			{
				$this->response_json(FALSE, '此時段已有其他行程,請檢察您的行程或是其他時段', array(
					'is_overlapped' => TRUE,
					'title' 		=> '行程重疊',
				));
			}

			$data['user_id'] = $data['login_token'];
			unset($data['login_token']);

			if ($this->Calendar_model->add_schedule($data))
			{
				$this->response_json(TRUE, '新增成功', array(
						'is_overlapped' => FALSE,
				));
			}
			else
			{
				$this->response_json(TRUE, '新增失敗', array(
						'is_overlapped' => FALSE,
				));
			}
		}
		elseif (is_numeric($opt))
		{
			$data = $this->get_request_post(array(
					['login_token' , '', '', 'text', 'token'],
					['customer_id' , '', 'customer id is not empty', 'text'],
					['item_id' , '', 'item id is not empty', 'text'],
					['alert' , 0, '', 'text'],
					['note' , 0, '', 'text'],
					['start_date' , date('Y-m-d H:i:s'), 'start date is not empty', 'text'],
					['end_date' , date('Y-m-d H:i:s', strtotime('+1 hour')), 'end date is not empty', 'text'],
			));

			if ($this->Calendar_model->is_schedule_can_edit($data['login_token'], $data['start_date'], $data['end_date'], $opt) > 0)
			{
				$this->response_json(FALSE, '此時段已有其他行程,請檢察您的行程或是其他時段', array(
					'is_overlapped' => TRUE,
					'title' 		=> '行程重疊',
				));
			}

			$where_data = array('id' => $opt, 'user_id' => $data['login_token']);
			unset($data['login_token']);

			if ($this->Calendar_model->modify_schedule($data, $where_data))
			{
				$this->response_json(TRUE, '修改成功', array(
						'is_overlapped' => FALSE,
				));
			}
			else
			{
				$this->response_json(TRUE, '修改失敗', array(
						'is_overlapped' => FALSE,
				));
			}
		}
		elseif ($opt == 'del' AND is_numeric($id))
		{
			$data = $this->get_request_post(array(
					['login_token' , '', '', 'text', 'token'],
			));

			$del_data = array('is_delete' => 1);
			$where_data = array('id' => $id, 'user_id' => $data['login_token']);

			if ($this->Calendar_model->modify_schedule($del_data, $where_data))
			{
				$this->response_json(TRUE, '刪除成功');
			}
			else
			{
				$this->response_json(TRUE, '刪除失敗');
			}
		}
	}

	public function get_schedule()
	{
		$this->load->model('Calendar_model');

		$data = $this->get_request_post(array(
					['login_token' , '', '', 'text', 'token'],
					['start_time' , '', 'start time is not empty', 'text'],
					['end_time' , '', 'end time is not empty', 'text'],
		));

		$where_data = array(
			'start_time'  => $data['start_time'],
			'end_time'	  => $data['end_time'],
		);

		$schedule = $this->Calendar_model->get_schedule($data['login_token'], $where_data);

		$settle = [0 => ['新增' => 0], 1 => ['約訪' => 0], 2 => ['面談' => 0], 3 => ['建議書' => 0], 4 => ['簽約' => 0], 5 => ['收費' => 0], 6 => ['合計' => 0]];

		for ($i = 0; $i < count($schedule); $i++)
		{
			if ($schedule[$i]['item_id'] <= 6)
			{
				$settle[$schedule[$i]['item_id'] - 1][$schedule[$i]['item_name']] ++;
				$settle[6]['合計'] ++;
			}
		}

		$this->response_json(TRUE, '', array(
				'schedule' => $schedule,
				'settle'   => $settle,
		));
	}

	public function schedule_complete()
	{
		$this->load->model('Calendar_model');

		$data = $this->get_request_post(array(
					['login_token' , '', 'login_token is not empty', 'text', 'token'],
					['schedule_id' , '', 'schedule id is not empty', 'text'],
					['is_complete' , 0, '', 'text'],
		));

		$where_data = array(
				'id' 		=> $data['schedule_id'],
				'user_id' 	=> $data['login_token'],
		);

		$complete_data = array('is_complete' => $data['is_complete']);

		if ($this->Calendar_model->set_schedule_complete($where_data, $complete_data))
		{
			$this->response_json(TRUE, '成功');
		}
	}
/* Calendar & memo - End */



/* Register or Login - Start */
	
	public function login()
	{
		$this->data = $this->get_request_post(array(
				/* login_type: 
				 * 		1. normal
				 * 		2. MOBILE (無註冊登錄, 請給mobile_id)
				 *  	3. GOOGLE (google登錄, 請給g_id)
				 * 		4. FB 	  (.., 給fb_id)
				 *  	5. APPLE  (.., 給apple_id)
				 */
				['login_type' 	, 'normal'  , 'email is not empty' 		, 'text'],
				['email'		, ''		, 'email is not empty'	  	, 'text'],
				['push_token'	, ''		, 'push token is not empty' , 'text'],
				['os'			, ''		, 'OS is not empty'  		, 'text'],
		));

		if ( ! is_os_type_exists($this->data['os']))
		{
			$this->response_json(FALSE, '不支援的作業系統');
		}

		$is_new = FALSE;

		// 帳號驗證
		if ($this->data['login_type'] === 'normal')
		{
			$this->data = array_merge($this->data, $this->get_request_post(array(
					['password'	, '', 'password is not empty' , 'text'],  	// normal 登錄用
			)));

			if ( ! $this->User_info_model->is_email_exist($this->data['email']))
			{
				$this->response_json(FALSE, '查無此帳號');
			}
			if ( ! $this->User_info_model->pwd_confirm($this->data['email'], $this->data['password']))
			{
				$this->response_json(FALSE, '密碼輸入錯誤');
			}
			
			$this->data['user_id'] = $this->User_info_model->get_user_id_by_email($this->data['email']);
		}
		elseif (is_login_type_exists($this->data['login_type']))
		{
			$this->data = array_merge($this->data, $this->get_request_post(array(
					['social_id' , '', 'social id is not empty' , 'text'],
			)));

			$login_type = login_type_trf_db_name($this->data['login_type']);
			if ($this->User_info_model->is_email_exist($this->data['email']))
			{
				if ($this->User_info_model->is_social_id_exist($this->data['email'], $login_type))
				{
					if ( ! $this->User_info_model->social_id_confirm($this->data['email'], $login_type, $this->data['social_id']))
					{
						$this->response_json(FALSE, 'Social id 不正確');
					}
				}
				else
				{
					$data[$login_type] = $this->data['social_id'];
					$this->User_info_model->add_soical_id_in_account($this->data['email'], $data);
				}

				$this->data['user_id'] = $this->User_info_model->get_user_id_by_email($this->data['email']);
			}
			else
			{
				$is_new = TRUE;
			}
		}
		else
		{
			$this->response_json(FALSE, '登錄類型不支援');
		}

		// For 社群登入, 丟去帳號註冊
		if ($is_new)
		{	
			// 丟去社群註冊
			$this->data['user_id'] = $this->social_register($login_type, $this->data);

			if ($this->data['user_id'] === FALSE)
			{
				$this->response_json(FALSE, '註冊失敗');
			}
		}

		$this->data['token'] = $this->Jwt_model->login_token($this->data['user_id']);

		unset($this->data['social_id']);
		unset($this->data['email']);
		unset($this->data['password']);
		unset($this->data['login_type']);

		// 將登入紀錄存進 login token table
		$set_token = $this->User_info_model->set_login_token($this->data, date('Y/m/d H:i:s', strtotime("-12 hours")));
		if ($set_token === FALSE)
		{
			$this->response_json(TRUE, '新增token失敗, 請重新嘗試');
		}
		elseif ($set_token === TRUE)
		{
			$this->response_json(TRUE, '登錄成功', array(
					'login_token'  =>	$this->data['token'],
			));
		}
		else
		{
			$this->response_json(TRUE, '登錄成功', array(
					'login_token'  =>	$set_token,
			));
		}
	}

	public function normal_register()
	{
		$data = $this->get_request_post(array(
				['name'				, '', ''								, 'text'],  	// normal 登錄用
				['email'			, '', 'email is not empty'				, 'text'],  	// normal 登錄用, 當帳號
				['password'			, '', 'password is not empty'			, 'text'],  	// normal 登錄用
				['password_confirm' , '', 'password confirm is not empty'	, 'text'],
				['is_face'			, '', 'is face is not empty' 			, 'text'],
		));

		if ($this->User_info_model->is_email_exist($data['email']))
			$this->response_json(FALSE, '此帳號已被註冊');

		if ($data['password'] !== $data['password_confirm'])
			$this->response_json(FALSE, '兩次輸入密碼不相同');

		$data['password'] = $this->encryption->encrypt(md5($data['password']));
		unset($data['password_confirm']);

		if ($this->User_info_model->add_register($data) === TRUE)
		{
			$this->response_json(TRUE, "註冊成功");
		}
		else
		{
			$this->response_json(FALSE, '發生錯誤,註冊失敗');
		}
	}

	private function social_register($login_type, $data)
	{
		$res_data[$login_type]  = $data['social_id'];
		$res_data['email']		= $data['email'];
		return $this->User_info_model->add_register($res_data);
	}
/* Register or Login - End */



/* Private function - Start */

	// For year goal and month goal
	private function count_item_goal_percent($datas)
	{
		foreach ($datas as $key => $value)
		{
			if (isset($value['total_num']) AND ( ! empty($value['total_num'])) AND ($value['total_num'] != 0))
				$datas[$key]['percent'] = ($value['now_num'] / $value['total_num']) * 100;
			else
				$datas[$key]['percent'] = 0;
		}

		return $datas;
	}

	// For year goal and month goal
	private function count_total_percent($datas)
	{
		$all_t = 0.0;
		$all_n = 0.0;
		foreach ($datas as $value)
		{
			$all_t += $value['total_num'];
			$all_n += $value['now_num'];
		}
		return ($all_n / $all_t) * 100;
	}
/* Private function - Start */


/* For customer only (no work in system) - Start */
/* For customer only (no work in system) - End */



/* Base function - Start */

	private function get_request_post(array $key_array)
	{
		foreach ($key_array as $value)
		{
			$data[$value[0]] = $this->input->post($value[0]);

			if (isset($value[4]) AND $value[4] == 'token')
			{
				$header = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
				$token = sscanf($header, 'Sales %s');

				$user = $this->Jwt_model->verify_token($token[0]);
				if ($user['status'])
				{
					$data['login_token'] = $user['user_id'];
					$this->Flow_record_model->set_flow_record($this->data['active'], $this->get_client_ip(), $user['user_id']);
				}
				else
				{
					$this->response_json(FALSE, '此 token 已過期,請重新登入', array(
							'url' => $this->login_url,
					));
				}
			}
			elseif ( ! $data[$value[0]])
			{
				$data[$value[0]] = $value[1];
			}
			elseif ($value[0] == 'login_token')
			{
				//丟去 check token 判斷此token 是否過期
				$user = $this->Jwt_model->verify_token($data[$value[0]]);
				if ($user['status'])
				{
					$data[$value[0]] = $user['user_id'];
					$this->Flow_record_model->set_flow_record($this->data['active'], $this->get_client_ip(), $user['user_id']);
				}
				else
				{
					$this->response_json(FALSE, '此 token 已過期,請重新登入', array(
							'url' => $this->login_url,
					));
				}
			}

			if ($value[2] != '')
			{
				if ($value[3] == 'text' && $data[$value[0]] == '')
					$this->response_json(FALSE, $value[2]);
				elseif ($value[3] == 'number' && $data[$value[0]] == 0)
					$this->response_json(FALSE, $value[2]);
				elseif ($value[3] == 'array' && empty($data[$value[0]]))
					$this->response_json(FALSE, $value[2]);
			}
		}
		return $data;
	}

	private function public_user_data($user)
	{
		$data = array();
		$fields = ['id', 'atid', 'email', 'name', 'avator', 'privilege_end_date', 'ai_Interview'];

		foreach ($fields as $field)
		{
			$data[$field] = $user[$field];
		}
		if ($user['avatar'] != '')
		{
			$data['avatar'] = base_url().$user['avatar'];
		}

		return $data;
	}
/* Base function - End */
}
