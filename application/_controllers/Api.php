<?php
defined('BASEPATH') or exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
header("Content-Type:text/html; charset=utf-8");

class Api extends Base_Controller
{

	private $login_url;
	private $status = FALSE;
	private $msg 	= '';


	public function __construct()
	{
		parent::__construct();

		$this->load->model('User_info_model');
		$this->load->model('Jwt_model');
		$this->load->model('Goal_model');
		$this->login_url = base_url() . 'api/login';
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

	public function flow()
	{
		$user = $this->check_user_token(FALSE);
		$uri = $this->post("uri");
		if ($user !== FALSE) {
			$this->flow_record($uri, $user['id']);
		} else {
			$this->flow_record($uri);
		}
		$this->output(TRUE, "已紀錄");
	}



	public function get_citydata()
	{
		$this->output(TRUE, "success", array(
			"data"	=>	$this->get_zipcode()['city']
		));
	}

	public function img_upload()
	{
		$this->load->model("Pic_model");
		$path = $this->Pic_model->crop_img_upload_and_create_thumb("image", FALSE, 50);

		if ($path != "") {
			$this->output(TRUE, "上傳成功", array("path" => $path));
		} else {
			$this->output(FALSE, "上傳圖片發生錯誤");
		}
	}













	/* User information - Start */

	public function get_user_info()
	{
		$this->data = $this->get_request_post(array(
			['login_token', '', 'token is not empty', 'text'],
		));

		$this->response_json(TRUE, '', array(
			'info' => $this->User_info_model->get_user_info($this->data['login_token']),
		));
	}

	public function get_user_notice()
	{
		$this->data = $this->get_request_post(array(
			['login_token', '', 'token is not empty', 'text'],
		));

		$notifys = $this->User_info_model->get_user_notice($this->data['login_token']);

		foreach ($notifys as $key => $value) {
			$notifys[$key]['create_date'] = date('Y-m-d H:m', strtotime($value['create_date']));

			if ($value['class'] === 'system') $notifys[$key]['class'] = '[系統公告]';
			else if ($value['class'] === 'supervisor') $notifys[$key]['class'] = '[主管留言]';
		}

		$this->response_json(TRUE, '', array(
			'notice' => $notifys,
		));
	}
	//生日提醒(月壽星)
	public function get_user_birthday_notice()
	{
		$this->data = $this->get_request_post(array(
			['login_token', '', 'token is not empty', 'text'],
		));

		$notifys = $this->User_info_model->get_user_birthday_notice($this->data['login_token']);

		foreach ($notifys as $key => $value) {
			$notifys[$key]['create_date'] = date('Y-m-d H:m', strtotime($value['create_date']));

			if ($value['class'] === 'system') $notifys[$key]['class'] = '[系統公告]';
			else if ($value['class'] === 'supervisor') $notifys[$key]['class'] = '[主管留言]';
		}

		$this->response_json(TRUE, '', array(
			'notice' => $notifys,
		));
	}

	public function set_user_notice_readed()
	{
		$this->data = $this->get_request_post(array(
			['login_token', '', 'token is not empty', 'text'],
			['id', '', 'notice id is not empty', 'text'],
		));

		if ($this->User_info_model->is_notice_confirmed($this->data['login_token'], $this->data['id']) == 1) {
			if ($this->User_info_model->set_user_notice_readed($this->data['login_token'], $this->data['id'])) {
				$this->status 	= TRUE;
				$this->msg 		= '已讀修改成功';
			} else {
				$this->status = TRUE;
				$this->msg 		= '已讀修改失敗';
			}

			$this->response_json($this->status, $this->msg);
		} else {
			$this->response_json(FALSE, '此通知無法被此使用者更改');
		}
	}

	public function is_open_ai_interview()
	{
		// 開啟後 寫入排程 自動新增約訪行事曆 每周六
		$this->data = $this->get_request_post(array(
			['login_token', '', 'token is not empty', 'text'],
		));

		$this->set_smart_cron($this->data['login_token']);

		$this->response_json(TRUE, '', array(
			'ai_interview' => $this->User_info_model->is_open_ai_interview($this->data['login_token']),
		));
	}
	public function schedule_can_add($user_id,$date_start,$date_end){

		if($this->Calendar_model->is_schedule_can_add($user_id, $date_start, $date_end) > 0){
			$flag=0;
			$date_start=date('Y-m-d H:i:s',strtotime('+30 Minutes',strtotime($date_start)));
			$date_end=date('Y-m-d H:i:s',strtotime('+30 Minutes',strtotime($date_start)));
			$last_data=$this->schedule_can_add($user_id,$date_start,$date_end);
			// print_r($last_data);
			if($last_data['flag']==1){
				return $last_data;
				exit;
			}

		}else{
			$flag=1;
			$data['start']=$date_start;
			$data['end']=$date_end;
			$data['flag']=$flag;
			// print_r($data['start']);
			// print 333;
			return $data;
		}
		// $data['start']=$date_start;
		// 	$data['end']=$date_end;
		// return $data;
		
	}
	public function set_smart_cron(){
		$this->load->model('Calendar_model');
		// $str="select * from user where is_delete=0 AND ai_Interview =1 ";
		$str="select * from user where is_delete=0 AND  id=104";
		$res=$this->db->query($str)->result_array();
		// print_r($res);exit;
		foreach($res as $r){
			// print_r($r);
			$user = $this->User_info_model->get_user_info($r['id']);
			$where_data = array();
			$where_data = array_merge($where_data, array(
				'C.user_id' 	=> $r['id'],
				'C.is_delete' 	=> 0,
			));
			$this->load->model('Customer_manage_model');
			$user_customer_info   = $this->Customer_manage_model->get_user_customer_list($r['id'], $where_data, 'C.id DESC');
			// print_r($user_customer_info);exit;
			
			$start_time=date('Y-m-d H:i:s', strtotime('+1 hour'));
			// print 123;exit;
			// $date=date('Y-m-d')."06:00:00";
			// print_R($user_customer_info);exit;
			$data=array();
			foreach($user_customer_info as $item){
				// print_r($item['id']);
				// print_R($date);
				
				// $date_start=date('Y-m-d H:i:s',strtotime($date));
				// $date_end=date('Y-m-d H:i:s',strtotime('+30 Minutes',strtotime($date_start)));
				// // if($this->schedule_can_add($user_id,$date_start,$date_end))
				// $date_list=$this->schedule_can_add($user_id,$date_start,$date_end);
				// // print_r($date_list['start']);exit;
				

				$data_not= $this->Customer_manage_model->get_connection_hot($item['user_id'], $item['id']);
				// print_r($data_not);
				// exit;
				///////////////////////////
				// 優先順序
				if($data_not['connection']=='blue'){
					$sort=1;
				}elseif($data_not['connection']=='yellow'){
					$sort=2;
				}elseif($data_not['connection']='orange'){
					$sort=3;
				}elseif($data_not['connection']='red'){
					$sort=4;
				}
				$data[]=array(
					"user_id"		=>$r['id'],
					"customer_id"	=>$item['id'],
					"item_id"		=>2	,
					"note"			=>'',
					// "start_date"	=>$date_list['start'],
					// "end_date"		=>$date_list['end'],
					"schedule_type"	=>'customer',
					"sort"	=>$sort,
				);
				// $this->Calendar_model->add_schedule($data);
				
				// $max=0; break;
				
				// $date=$date_list['start'];
				
				// if($num==9){
				// 	// print_r($num);
				// 	break;
				// }
				// print_r($data);exit;
	
			}
			//依照優先順序排序
			$data_sort=array_column($data,'sort');
			array_multisort($data_sort,SORT_ASC,SORT_NUMERIC,$data);
			$num=0;
			$date=date('Y-m-d')."06:00:00";
			// print_r($data);
			foreach($data as $d){
				$date_start=date('Y-m-d H:i:s',strtotime($date));
				$date_end=date('Y-m-d H:i:s',strtotime('+30 Minutes',strtotime($date_start)));
				// if($this->schedule_can_add($user_id,$date_start,$date_end))
				$date_list=$this->schedule_can_add($d['user_id'],$date_start,$date_end);
				// print_r($date_list['start']);exit;
				
				$d['start_date']=$date_list['start'];
				$d['end_date']=$date_list['end'];
				unset($d['sort']);
				// 新增行事曆
				print_r($d);
				// $this->Calendar_model->add_schedule($d);
				$date=$date_list['start'];
				
				if($num==9){
					print_r($num);
					// exit;
					break;
				}
				$num++;
				// print_r($data);exit;
				// print_r($d);
				// exit;
			}
			
			// print_r($r['id']);
			// exit;
			/////////////////////////////////////////////////////////
		}

		
		exit;
		

		// print_R($data);exit;
		
		// $user_custome_list=$this->Customer_manage_model->get_all_customer_manage($user_id)

		print_r($user_customer_info);exit;

	}
	public function add_date_30($date){

		return $date;
	}

	public function upgrad()
	{
		// print 1231;exit;
		$this->data = $this->get_request_post(array(
			['login_token', '', 'login token is not empty', 'text'],
			['store', '', 'store is not empty', 'text'],
			['plan', '', 'plan is not empty',  'text'],
		));
		
		// $test=is_upgra_store_exists($this->data['store']);
		// print_r($test);exit;
		if ((!$store_obj_name = is_upgra_store_exists($this->data['store'])) or
			(!is_upgra_plan_exists($this->data['plan']))
		) {
			$this->response_json(FALSE, '');
		}

		if (!$user_priv_date = $this->User_info_model->get_priv_date($this->data['login_token']))
			$this->response_json(FALSE, '');

		

		require_once(APPPATH . 'libraries/upgrad/' . $store_obj_name . '.php');
		$store_obj = new $store_obj_name();
		
		$upgrad_data = array_merge($store_obj->buy_upgrad($this->data['plan'], $user_priv_date['privilege_end_date']), array(
			'user_id' => $this->data['login_token'],
		));
		// print_r($upgrad_data);exit;
		if ($this->User_info_model->add_upgrad($upgrad_data)) {
			$this->response_json(TRUE, output_msg('sales_06'));
		} else {
			$this->response_json(FALSE, output_msg('sales_07'));
		}
	}

	public function change_pwd()
	{
		$this->load->model('User_info_model');
		$data = $this->get_request_post(array(
			['login_token', 	'', 'token is not empty', 'text', 'token'],
			['old_pwd', 		'', 'old pwd is not empty', 'text'],
			['new_pwd', 		'', 'new pwd is not empty', 'text'],
			['new_confirm_pwd', '', 'new  confirm pwd is not empty', 'text'],
		));

		$email = $this->User_info_model->get_email_by_user_id($data['login_token']);

		if ($this->User_info_model->forgetpwd_confirm($email['email'], $data['old_pwd'])) {
			if ($data['new_pwd'] == $data['new_confirm_pwd']) {

				if ($this->User_info_model->change_pwd($data['login_token'], $data['new_pwd'])) {
					$this->response_json(TRUE, '修改成功');
				} else {
					$this->response_json(FALSE, '修改失敗');
				}
			} else {
				$this->response_json(FALSE, '新密碼與新密碼確認不一致');
			}
		} else {
			$this->response_json(FALSE, '舊密碼驗證錯誤');
		}
	}
	public function change_pwd_new()
	{
		$this->load->model('User_info_model');
		$data = $this->get_request_post(array(
			['login_token', 	'', 'token is not empty', 'text', 'token'],
			['name', 			'', 'name is not empty', 'text', 'token'],
			['old_pwd', 		'', '', 'text'],
			['new_pwd', 		'', '', 'text'],
			['new_confirm_pwd', '', '', 'text'],
		));
// print 123;exit;
		$email = $this->User_info_model->get_email_by_user_id($data['login_token']);
		// print_r($data['login_token']);exit;

		if($data['old_pwd']=='' && $data['new_pwd']=='' && $data['new_confirm_pwd']==''){
			// print 555;exit;
			if ($this->User_info_model->change_name($data['login_token'], $data['name'])) {
				$this->response_json(TRUE, '修改成功');
			} else {
				$this->response_json(FALSE, '修改失敗');
			}
		}else{
			
			if($data['old_pwd']=='') $this->response_json(FALSE, '舊密碼不可為空');
			if($data['new_pwd']=='') $this->response_json(FALSE, '新密碼不可為空');
			if($data['new_confirm_pwd']=='') $this->response_json(FALSE, '再次輸入新密碼不可為空');
			// print 123;
			// exit;
			if ($this->User_info_model->pwd_confirm($email['email'], $data['old_pwd'])) {
				if ($data['new_pwd'] == $data['new_confirm_pwd']) {
	
					if ($this->User_info_model->change_pwd($data['login_token'], $data['new_pwd'])) {
						$this->User_info_model->change_name($data['login_token'], $data['name']);

						$this->response_json(TRUE, '修改成功');
					} else {
						$this->response_json(FALSE, '修改失敗');
					}
				} else {
					$this->response_json(FALSE, '新密碼與新密碼確認不一致');
				}
			} else {
				$this->response_json(FALSE, '舊密碼驗證錯誤');
			}
		}

		
	}
	//查看統計圖表 活動計分
	public function statistical_all_active()
	{
		$this->load->model('Calendar_model');
		$this->load->model('Customer_manage_model');

		$data = $this->get_request_post(array(
			['login_token', 	'', 'token is not empty', 'text', 'token'],
			['start_time', '', 'start time is not empty', 'text'],
			['end_time', '', 'end time is not empty', 'text'],
			['minor_id', 0, '', 'text'],
		));

		// for 主管瀏覽
		if ($data['minor_id'] !== 0) {
			// 檢查是否可以檢視
			if (!$this->User_info_model->is_owner_user($data['login_token'], $data['minor_id']))
				$this->response_json(FALSE, '您無權查看此頁面');

			$data['login_token'] = $data['minor_id'];
		}

		$user = $this->User_info_model->get_user_info($data['login_token']);

		$str="select *  from master_minor_relation  where is_delete=0 order by id desc";
		$res_minor=$this->db->query($str)->result_array();
		
		foreach($res_minor as $r_minor){
			$str="select *  from user  where is_delete=0  AND id=$r_minor[minor_id]";
			$res=$this->db->query($str)->row_array();
			$res_user[]=$res;
		}
		// print_r($res_user);exit;
		// $user_all=
		$where_data = array(
			'start_time'  => $data['start_time'],
			'end_time'	  => $data['end_time'],
		);
		foreach($res_user as $r_uesr){
			$schedule = $this->Calendar_model->get_schedule($r_uesr['id'], $where_data);
			$status  = $this->Customer_manage_model->get_all_customer_manage_status($r_uesr['id']);
			
			//item_name 對照陣列
		$item_array = $this->Calendar_model->get_schedule_item_name();
		$active = array();
		$settle = [0 => ['新增' => 0], 1 => ['約訪' => 0], 2 => ['面談' => 0], 3 => ['建議書' => 0], 4 => ['簽約' => 0], 5 => ['收費' => 0], 6 => ['合計' => 0]];
		$score_fix = array(1, 1, 2, 3, 4, 5);
		for ($i = 0; $i < count($schedule); $i++) {
			// if ($schedule[$i]['item_id'] <= 6 and $schedule[$i]['schedule_type'] !== 'text') {
			// 	$settle[$schedule[$i]['item_id'] - 1][$schedule[$i]['item_name']]++;
			// 	$settle[6]['合計']++;
			// }

			$id_array = explode(',', $schedule[$i]['item_id']);

			foreach ($id_array as $ia) {

				// var_dump($ia);
				// exit;
				if ($ia < 7 and $schedule[$i]['schedule_type'] !== 'text') {

					// var_dump( $item_array[$ia-1]['item_name']);

					// exit;
					$settle[$ia - 1][$item_array[$ia - 1]['item_name']]++;


					$settle[6]['合計']++;
				}
			}
		}
		
		//20220415 次數乘上加權
		$total_times = 0;

		// print_r($score_fix[0]);exit;
		// For 前端要求的格式..
		for ($i = 0; $i < 7; $i++) {
			foreach ($settle[$i] as $key => $value) {
				// print_r($value);exit;
				$active[$i]['title']  = $key;
				//$active[$i]['number'] = $value;
				if ($i == 6) {
					// $total = 0;
					// for ($j = 0; $j < 6; $j++) {
					// 	$total += $active[$j]['percent'];
					// }
					// $active[$i]['percent']  = $total;
					$active[$i]['number']	= $total_times;
				} else {
					// $active[$i]['number']	= $value * $score_fix[$i];
					// $total_times			= $total_times + $value * $score_fix[$i];
					$active[$i]['number']	= $value ;
					$total_times			= $total_times + $value;
					// if ($total_times == 0) $active[$i]['percent']  = 0;
					// else $active[$i]['percent']  = round($active[$i]['number'] / $total_times * 100);
					
				}
				// print($active[$i]['number']);
			}
			
		}
		// print_r($active);exit;
		for ($i = 0; $i < 7; $i++) {
			
			if ($total_times == 0) $active[$i]['percent']  = 0;
			else $active[$i]['percent']  = round($active[$i]['number'] / $total_times * 100);
					
		}
		// exit;
		$title_all=$active[6]['title'];
		$percent_all=$active[6]['percent'];
		$number_all=$active[6]['number'];
		// print($percent_all);exit;
		unset($active[6]);

		// print_r($active[6]['title']);exit;
		$active_data[]=array(
			'avatar' 	=>($r_uesr['avatar']=="" ||$r_uesr['avatar']==null )? "":base_url().$r_uesr['avatar'],
			'person' 	=> $r_uesr['name'],
			'customer_num' 	=> count($status),
			'title'			=>$title_all,
			'percent'			=>$percent_all,
			'number'			=>$number_all,
			'active' 		=> $active,
		);
			// print_r($r_uesr['name']);
			// print_r(count($status));
			// print_r($work);
			// print "_";
		}
		// exit;
		
		// $where_data = array(
		// 	'start_time'  => $data['start_time'],
		// 	'end_time'	  => $data['end_time'],
		// );
		// $schedule = $this->Calendar_model->get_schedule($data['login_token'], $where_data);

		
		
		
		
	

		$this->response_json(TRUE, '', array(
			// 'person' 	=> $user['name'],
			// 'customer_num' 	=> count($status),
			// 'work' 		=> $work,
			'data'    => $active_data,
		));
	}
	//查看統計圖表 工作進度
	public function statistical_all_work()
	{
		$this->load->model('Calendar_model');
		$this->load->model('Customer_manage_model');

		$data = $this->get_request_post(array(
			['login_token', 	'', 'token is not empty', 'text', 'token'],
			['start_time', '', 'start time is not empty', 'text'],
			['end_time', '', 'end time is not empty', 'text'],
			['minor_id', 0, '', 'text'],
		));

		// for 主管瀏覽
		if ($data['minor_id'] !== 0) {
			// 檢查是否可以檢視
			if (!$this->User_info_model->is_owner_user($data['login_token'], $data['minor_id']))
				$this->response_json(FALSE, '您無權查看此頁面');

			$data['login_token'] = $data['minor_id'];
		}

		// $user = $this->User_info_model->get_user_info($data['login_token']);

		// $str="select *  from user  where is_delete=0 order by id asc";
		// $res_user=$this->db->query($str)->result_array();
		$str="select *  from master_minor_relation  where is_delete=0 order by id desc";
		$res_minor=$this->db->query($str)->result_array();
		
		foreach($res_minor as $r_minor){
			$str="select *  from user  where is_delete=0  AND id=$r_minor[minor_id]";
			$res=$this->db->query($str)->row_array();
			$res_user[]=$res;
		}
		
		foreach($res_user as $r_uesr){
			
			// $customer_manage = $this->Customer_manage_model->get_all_customer_manage_status($r_uesr['id']);
			// $relation=$this->Customer_manage_model->get_all_customer_manage_relation($r_uesr['id']);
			// $level=$this->Customer_manage_model->get_all_customer_manage_level($r_uesr['id']);
			$status=$this->Customer_manage_model->get_all_customer_manage_status($r_uesr['id']);
			
			$settle2 = [0 => ['未開發' => 0],1 => ['告知' => 0], 2 => ['約訪' => 0], 3 => ['拜訪' => 0], 4 => ['建議' => 0], 5 => ['成交' => 0]];
			$result = array_reduce($status, function ($result, $value) {
				return array_merge($result, array_values($value));
			}, array());

			$result = array_count_values($result);
			// print_r($result); exit;
			// For 前端要求的格式..
			$work = array();
			$settle_total = 0;
			for ($i = 0; $i < count($settle2); $i++) {
				foreach ($settle2[$i] as $key => $value) {
					$work[] = array('title' => $key, 'number' => (isset($result[status_ch_tf_en($key)]) ? $result[status_ch_tf_en($key)] : 0));
					$settle_total += (isset($result[status_ch_tf_en($key)]) ? $result[status_ch_tf_en($key)] : 0);
				}
			}
			// print_r($work); exit;
			for ($j = 0; $j < count($settle2); $j++) {
				if ($settle_total == 0) $work[$j]['percent'] = 0;
				else $work[$j]['percent'] = round($work[$j]['number'] / $settle_total * 100);
			}
			// print_r($r_uesr['name']);
			// print_r(count($status));
			// print_r($work);
			$work_data[]=array(
				'avatar' 	=>($r_uesr['avatar']=="" ||$r_uesr['avatar']==null )? "":base_url().$r_uesr['avatar'],
				'person' 	=> $r_uesr['name'],
				'customer_num' 	=> count($status),
				'work' 		=> $work,
			);
			// $name_all[]=$r_uesr['name'];
			// $status_all[]=count($status);
			// $work_all[]=$work;
			// print "_";
		}
		// exit;
		

		$this->response_json(TRUE, '', array(
			'data' 	=> $work_data,
			// 'customer_num' 	=> $status_all,
			// 'work' 		=> $work_all,
			// 'active'    => $active,
		));
	}
	//查看統計圖表
	public function statistical_report()
	{
		$this->load->model('Calendar_model');
		$this->load->model('Customer_manage_model');

		$data = $this->get_request_post(array(
			['login_token', 	'', 'token is not empty', 'text', 'token'],
			['start_time', '', 'start time is not empty', 'text'],
			['end_time', '', 'end time is not empty', 'text'],
			['minor_id', 0, '', 'text'],
		));

		// for 主管瀏覽
		if ($data['minor_id'] !== 0) {
			// 檢查是否可以檢視
			if (!$this->User_info_model->is_owner_user($data['login_token'], $data['minor_id']))
				$this->response_json(FALSE, '您無權查看此頁面');

			$data['login_token'] = $data['minor_id'];
		}

		$user = $this->User_info_model->get_user_info($data['login_token']);

		
		
		$where_data = array(
			'start_time'  => $data['start_time'],
			'end_time'	  => $data['end_time'],
		);
		$schedule = $this->Calendar_model->get_schedule($data['login_token'], $where_data);

		
		
		//item_name 對照陣列
		$item_array = $this->Calendar_model->get_schedule_item_name();
		$active = array();
		// 舊的寫死
		// $settle = [0 => ['新增' => 0], 1 => ['約訪' => 0], 2 => ['面談' => 0], 3 => ['建議書' => 0], 4 => ['簽約' => 0], 5 => ['收費' => 0], 6 => ['合計' => 0]];
		// $score_fix = array(1, 1, 2, 3, 4, 5);
		// 新的抓DB
		$settle  	= $this->User_info_model->get_user_schedule_item('item_name');
		$score_fix 	= $this->User_info_model->get_user_schedule_item('score_fix');
		for ($i = 0; $i < count($schedule); $i++) {
			// if ($schedule[$i]['item_id'] <= 6 and $schedule[$i]['schedule_type'] !== 'text') {
			// 	$settle[$schedule[$i]['item_id'] - 1][$schedule[$i]['item_name']]++;
			// 	$settle[6]['合計']++;
			// }

			$id_array = explode(',', $schedule[$i]['item_id']);

			foreach ($id_array as $ia) {

				// var_dump($ia);
				// exit;
				if ($ia < 7 and $schedule[$i]['schedule_type'] !== 'text') {

					// var_dump( $item_array[$ia-1]['item_name']);

					// exit;
					$settle[$ia - 1][$item_array[$ia - 1]['item_name']]++;


					$settle[6]['合計']++;
				}
			}
		}
		
		//20220415 次數乘上加權
		$total_times = 0;


		// For 前端要求的格式..
		for ($i = 0; $i < 7; $i++) {
			foreach ($settle[$i] as $key => $value) {
				$active[$i]['title']  = $key;
				//$active[$i]['number'] = $value;
				if ($i == 6) {
					$total = 0;
					for ($j = 0; $j < 6; $j++) {
						$total += $active[$j]['percent'];
					}
					$active[$i]['percent']  = $total;
					$active[$i]['number']	= $total_times;
				} else {
					$active[$i]['number']	= $value * $score_fix[$i];
					$total_times			= $total_times + $value * $score_fix[$i];
					$active[$i]['percent']  = $value * $score_fix[$i];
					// $active[$i]['percent']  = $value * $score_fix[$i];
				}
			}
		}
		// print_r($total_times);exit;
		
		$status  = $this->Customer_manage_model->get_all_customer_manage_status($data['login_token']);
		// print_r($status); exit;
		// $settle2 = [0 => ['告知' => 0], 1 => ['約訪' => 0], 2 => ['拜訪' => 0], 3 => ['建議' => 0], 4 => ['成交' => 0]];
		$settle2 = [0 => ['未開發' => 0],1 => ['告知' => 0], 2 => ['約訪' => 0], 3 => ['拜訪' => 0], 4 => ['建議' => 0], 5 => ['成交' => 0]];
		$result = array_reduce($status, function ($result, $value) {
			return array_merge($result, array_values($value));
		}, array());
		$result = array_count_values($result);
		// print_r($result); exit;
		// For 前端要求的格式..
		$work = array();
		$settle_total = 0;
		for ($i = 0; $i < count($settle2); $i++) {
			foreach ($settle2[$i] as $key => $value) {
				$work[] = array('title' => $key, 'number' => (isset($result[status_ch_tf_en($key)]) ? $result[status_ch_tf_en($key)] : 0));
				$settle_total += (isset($result[status_ch_tf_en($key)]) ? $result[status_ch_tf_en($key)] : 0);
			}
		}
		// print_r($work); exit;
		for ($j = 0; $j < count($settle2); $j++) {
			if ($settle_total == 0) $work[$j]['percent'] = 0;
			else $work[$j]['percent'] = round($work[$j]['number'] / $settle_total * 100);
		}

		$this->response_json(TRUE, '', array(
			'person' 	=> $user['name'],
			'customer_num' 	=> count($status),
			'work' 		=> $work,
			'active'    => $active,
		));
	}

	public function add_minor_code()
	{
		$data = $this->get_request_post(array(
			['login_token', '', 'token is not empty', 'text', 'token'],
			['seal_code',  '', 'seal code is not empty', 'text'],
		));
		if (!$this->User_info_model->can_view_supervisor($data['login_token'])) $this->response_json(TRUE, '您無權查看此頁面');

		if ($id = $this->User_info_model->get_user_id_by_seal_code($data['seal_code'])) {
			if ($this->User_info_model->is_minor_exist($data['login_token'], $id['id']) == 0) {
				$this->User_info_model->add_minor_user($data['login_token'], $id['id']);
				$this->response_json(TRUE, '加入成功');
			} else $this->response_json(TRUE, '已存在');
		} else {
			$this->response_json(TRUE, '此認證ID查無帳號, 請確認在嘗試一次');
		}
	}
	public function del_minor_code()
	{
		$data = $this->get_request_post(array(
			['login_token', '', 'token is not empty', 'text', 'token'],
			['minor_id', '', 'minor_id is not empty', 'array'],
		));

		// print_r($data['minor_id']);exit;
		foreach($data['minor_id'] as $minor_id){
			// print $minor_id;
		
		// exit;
		// if (!$this->User_info_model->can_view_supervisor($data['login_token'])) $this->response_json(TRUE, '您無權查看此頁面');
			if ($this->User_info_model->is_minor_exist($data['login_token'], $minor_id) > 0) {
				$this->User_info_model->del_minor_user($data['login_token'], $minor_id);
				
			} else $this->response_json(TRUE, '業務不存在');
		}
		$this->response_json(TRUE, '刪除成功');
	}
	/* User information - End */
	public function del_user()
	{
		// print 123;exit;
		$data = $this->get_request_post(array(
			['login_token', '', 'token is not empty', 'text', 'token'],
			
		));
		$res=$this->User_info_model->get_user_info($data['login_token']);
		// print_r($res['email']);exit;
		// print_r($data['minor_id']);exit;
		
			// print $minor_id;
		
		// exit;
		// if (!$this->User_info_model->can_view_supervisor($data['login_token'])) $this->response_json(TRUE, '您無權查看此頁面');
			if ($this->User_info_model->is_email_exist($res['email']) > 0) {
				$this->User_info_model->del_user($data['login_token']);
				
			} else $this->response_json(TRUE, '帳號不存在');

		$this->response_json(TRUE, '刪除成功');
	}


	/* home page - Start */
	//取得全部年目標
	public function get_year_goal_data_all()
	{
		$this->data = $this->get_request_post(array(
			['login_token', '', 'token is not empty', 'text'],
			['year', '', 'year is not empty', 'text'],
			['minor_id', 0, '', 'text'],
		));

		// for 主管瀏覽
		if ($this->data['minor_id'] !== 0) {
			if (!$this->User_info_model->can_view_supervisor($this->data['login_token']))
				$this->response_json(FALSE, '請升級後繼續');

			// 檢查是否可以檢視
			if (!$this->User_info_model->is_owner_user($this->data['login_token'], $this->data['minor_id']))
				$this->response_json(FALSE, '您無權查看此頁面');

			$this->data['login_token'] = $this->data['minor_id'];
		}
		// $str="select *  from user  where is_delete=0 order by id asc";
		// $res_user=$this->db->query($str)->result_array();
		$str="select *  from master_minor_relation  where is_delete=0 order by id desc";
		$res_minor=$this->db->query($str)->result_array();
		
		foreach($res_minor as $r_minor){
			$str="select *  from user  where is_delete=0  AND id=$r_minor[minor_id]";
			$res=$this->db->query($str)->row_array();
			$res_user[]=$res;
		}

		// print_r($res_user);exit;
		$data_all=array();
		foreach($res_user as $r_user){
			$result = $this->User_info_model->get_year_goal_data($r_user['id'], $this->data['year']);
			// print_r($result);exit;
			if ($result == FALSE)
				$result = array();

			for ($i = 0; $i < count($result); $i++) {
				unset($result[$i]['user_id']);
				unset($result[$i]['year']);
				unset($result[$i]['create_date']);
				unset($result[$i]['is_delete']);
			}

			if ($result == FALSE) {
				$this->status = TRUE;
				$this->msg = '取得成功';
				$data = array();
				$total_percent = 0;
			} else {
				$this->status = TRUE;
				$this->msg = '取得成功';
				$data = $this->count_item_goal_percent($result);
				$total_percent = $this->count_total_percent($data);
				$data_all['data']	= $data;
				$data_all['total_percent']	= $total_percent;
				$data_all['name']	= $r_user['name'];
				$data_all['avatar']	= ($r_user['avatar']=="" ||$r_user['avatar']==null )? "":base_url().$r_user['avatar'];
				$data_all_in[]=$data_all;
				// print_r($data);exit;
				// 
			}

		}
		
	

		$this->response_json($this->status, $this->msg, array(
			'data_all' => $data_all_in,
		));
	}
	//取得年度目標
	public function get_year_goal_data()
	{
		$this->data = $this->get_request_post(array(
			['login_token', '', 'token is not empty', 'text'],
			['year', '', 'year is not empty', 'text'],
			['minor_id', 0, '', 'text'],
		));

		// for 主管瀏覽
		if ($this->data['minor_id'] !== 0) {
			if (!$this->User_info_model->can_view_supervisor($this->data['login_token']))
				$this->response_json(FALSE, '請升級後繼續');

			// 檢查是否可以檢視
			if (!$this->User_info_model->is_owner_user($this->data['login_token'], $this->data['minor_id']))
				$this->response_json(FALSE, '您無權查看此頁面');

			$this->data['login_token'] = $this->data['minor_id'];
		}

		$result = $this->User_info_model->get_year_goal_data($this->data['login_token'], $this->data['year']);
		if ($result == FALSE)
			$result = array();

		for ($i = 0; $i < count($result); $i++) {
			unset($result[$i]['user_id']);
			unset($result[$i]['year']);
			unset($result[$i]['create_date']);
			unset($result[$i]['is_delete']);
		}

		if ($result == FALSE) {
			$this->status = TRUE;
			$this->msg = '取得成功';
			$data = array();
			$total_percent = 0;
		} else {
			$this->status = TRUE;
			$this->msg = '取得成功';
			$data = $this->count_item_goal_percent($result);
			$total_percent = $this->count_total_percent($data);

			// For 前端固定目標(寫死)
			// foreach ($data as $key => $value)
			// {
			// 	if ($value['name'] == 'FYP')
			// 		$data[$key]['type'] = 1;
			// 	elseif ($value['name'] == 'FYC' OR $value['name'] == '增員')
			// 		$data[$key]['type'] = 2;
			// 	else
			// 		$data[$key]['type'] = 3;
			// }
		}

		// For 固定目標(寫死)
		// $fix_data = array(
		// 		0 => array(
		// 				'id' 		=> NULL,
		// 				'name' 		=> 'FYP',
		// 				'now_num' 	=> 0,
		// 				'total_num' => 0,
		// 				'percent' 	=> 0,
		// 				'type' 		=> 1,
		// 		),
		// 		1 => array(
		// 				'id' 		=> NULL,
		// 				'name' 		=> 'FYC',
		// 				'now_num' 	=> 0,
		// 				'total_num' => 0,
		// 				'percent' 	=> 0,
		// 				'type' 		=> 2,
		// 		),
		// 		2 => array(
		// 				'id' 		=> NULL,
		// 				'name' 		=> '增員',
		// 				'now_num' 	=> 0,
		// 				'total_num' => 0,
		// 				'percent' 	=> 0,
		// 				'type' 		=> 2,
		// 		),
		// );
		// $fyp_exist = FALSE;
		// $fyc_exist = FALSE;
		// $add_exist = FALSE;
		// for ($i = 0; $i < count($data); $i++)
		// {
		// 	if ($data[$i]['name'] == 'FYP')	
		// 		$fyp_exist = TRUE;
		// 	elseif ($data[$i]['name'] == 'FYC')
		// 		$fyc_exist = TRUE;
		// 	elseif ($data[$i]['name'] == '增員')
		// 		$add_exist = TRUE;
		// }

		// for ($i = 0; $i < count($fix_data); $i++)
		// { 
		// 	if ( ! $fyp_exist AND $i == 0)
		// 	{
		// 		$data = array_merge(array($fix_data[0]), $data);
		// 		continue;
		// 	}
		// 	elseif ( ! $fyc_exist AND $i == 1)
		// 	{
		// 		$data = array_merge(array($fix_data[1]), $data);
		// 		continue;
		// 	}
		// 	elseif ( ! $add_exist AND $i == 2)
		// 	{
		// 		$data = array_merge(array($fix_data[2]), $data);
		// 		continue;
		// 	}
		// }
		// For 固定目標(寫死) - End

		$this->response_json($this->status, $this->msg, array(
			'data' => $data,
			'total_percent' => $total_percent,
		));
	}

	public function delete_year_goal()
	{

		$data = $this->get_request_post(array(
			['login_token', '', 'token is not empty', 'text'],
			['id', '', 'id is not empty', 'text']
		));


		$data['user_id'] = $data['login_token'];
		unset($data['login_token']);

		$res = $this->User_info_model->get_year_goal_by_id($data['user_id'], $data['id']);



		if ($res) {
			$this->User_info_model->delete_year_goal($data['user_id'], $data['id']);


			$this->response_json(TRUE, '成功');
		} else {
			$this->response_json(FALSE, '目標id不存在');
		}
	}
	//取得全部月度目標
	public function get_month_goal_data_all()
	{
		$this->data = $this->get_request_post(array(
			['login_token', '', 'token is not empty', 'text'],
			['year', '', 'year is not empty', 'text'],
			['month', '', 'month is not empty', 'text'],
			['minor_id', 0, '', 'text'],
		));

		// for 主管瀏覽
		if ($this->data['minor_id'] !== 0) {
			if (!$this->User_info_model->can_view_supervisor($this->data['login_token']))
				$this->response_json(FALSE, '請升級後繼續');

			// 檢查是否可以檢視
			if (!$this->User_info_model->is_owner_user($this->data['login_token'], $this->data['minor_id']))
				$this->response_json(FALSE, '您無權查看此頁面');

			$this->data['login_token'] = $this->data['minor_id'];
		}
		// $str="select *  from user  where is_delete=0 order by id asc";
		// $res_user=$this->db->query($str)->result_array();
		$str="select *  from master_minor_relation  where is_delete=0 order by id desc";
		$res_minor=$this->db->query($str)->result_array();
		
		foreach($res_minor as $r_minor){
			$str="select *  from user  where is_delete=0  AND id=$r_minor[minor_id]";
			$res=$this->db->query($str)->row_array();
			$res_user[]=$res;
		}
		$data_all=array();
		foreach($res_user as $r_user){
			// print_r($r_user['name']);
			// exit;

			$result = $this->User_info_model->get_month_goal_data($r_user['id'], $this->data['year'], $this->data['month']);
			if($result!='' || $result!=null){
				// $res[]['name']=$r_user['name'];
			
				if ($result == FALSE)
					$result = array();

				for ($i = 0; $i < count($result); $i++) {
					// $result[$i]['name']=$r_user['name'];
					unset($result[$i]['user_id']);
					unset($result[$i]['year']);
					unset($result[$i]['create_date']);
					unset($result[$i]['is_delete']);
				}
				// $result['name']=$r_user['name'];
				$this->status = TRUE;
				$this->msg = '取得成功';
				// $data_all[]=	$r_user['name'];
				$data = $this->count_item_goal_percent($result);
				$total_percent= $this->count_total_percent($data);
				$data_all['data']	= $data;
				$data_all['total_percent']	= $total_percent;
				$data_all['name']	= $r_user['name'];
				$data_all['avatar']	= ($r_user['avatar']=="" ||$r_user['avatar']==null )? "":base_url().$r_user['avatar'];
				$data_all_in[]=$data_all;
				// print_r($data_all);exit;
				
			}

		}
	
	
		$this->response_json($this->status, $this->msg, array(
			'data_all' 	  => $data_all_in,
		));
	}

	public function get_month_goal_data()
	{
		$this->data = $this->get_request_post(array(
			['login_token', '', 'token is not empty', 'text'],
			['year', '', 'year is not empty', 'text'],
			['month', '', 'month is not empty', 'text'],
			['minor_id', 0, '', 'text'],
		));

		// for 主管瀏覽
		if ($this->data['minor_id'] !== 0) {
			if (!$this->User_info_model->can_view_supervisor($this->data['login_token']))
				$this->response_json(FALSE, '請升級後繼續');

			// 檢查是否可以檢視
			if (!$this->User_info_model->is_owner_user($this->data['login_token'], $this->data['minor_id']))
				$this->response_json(FALSE, '您無權查看此頁面');

			$this->data['login_token'] = $this->data['minor_id'];
		}

		$result = $this->User_info_model->get_month_goal_data($this->data['login_token'], $this->data['year'], $this->data['month']);
		if ($result == FALSE)
			$result = array();

		for ($i = 0; $i < count($result); $i++) {
			unset($result[$i]['user_id']);
			unset($result[$i]['year']);
			unset($result[$i]['create_date']);
			unset($result[$i]['is_delete']);
		}
		if ($result == FALSE) {
			$this->status = TRUE;
			$this->msg = '取得成功';
			$data = array();
			$total_percent = 0;
		} else {
			$this->status = TRUE;
			$this->msg = '取得成功';
			$data = $this->count_item_goal_percent($result);
			$total_percent = $this->count_total_percent($data);

			// For 前端固定目標(寫死)
			// foreach ($data as $key => $value)
			// {
			// 	if ($value['name'] == 'FYP')
			// 		$data[$key]['type'] = 1;
			// 	elseif ($value['name'] == 'FYC' OR $value['name'] == '增員')
			// 		$data[$key]['type'] = 2;
			// 	else
			// 		$data[$key]['type'] = 3;
			// }
		}

		// For 固定目標(寫死)
		// $fix_data = array(
		// 		0 => array(
		// 				'id' 		=> NULL,
		// 				'month' 	=> $this->data['month'],
		// 				'name' 		=> 'FYP',
		// 				'now_num' 	=> 0,
		// 				'total_num' => 0,
		// 				'percent' 	=> 0,
		// 				'type' 		=> 1,
		// 		),
		// 		1 => array(
		// 				'id' 		=> NULL,
		// 				'month' 	=> $this->data['month'],
		// 				'name' 		=> 'FYC',
		// 				'now_num' 	=> 0,
		// 				'total_num' => 0,
		// 				'percent' 	=> 0,
		// 				'type' 		=> 2,
		// 		),
		// 		2 => array(
		// 				'id' 		=> NULL,
		// 				'month' 	=> $this->data['month'],
		// 				'name' 		=> '增員',
		// 				'now_num' 	=> 0,
		// 				'total_num' => 0,
		// 				'percent' 	=> 0,
		// 				'type' 		=> 2,
		// 		),
		// );
		// $fyp_exist = FALSE;
		// $fyc_exist = FALSE;
		// $add_exist = FALSE;
		// for ($i = 0; $i < count($data); $i++)
		// {
		// 	if ($data[$i]['name'] == 'FYP')	
		// 		$fyp_exist = TRUE;
		// 	elseif ($data[$i]['name'] == 'FYC')
		// 		$fyc_exist = TRUE;
		// 	elseif ($data[$i]['name'] == '增員')
		// 		$add_exist = TRUE;
		// }

		// for ($i = 0; $i < count($fix_data); $i++)
		// { 
		// 	if ( ! $fyp_exist AND $i == 0)
		// 	{
		// 		$data = array_merge(array($fix_data[0]), $data);
		// 		continue;
		// 	}
		// 	elseif ( ! $fyc_exist AND $i == 1)
		// 	{
		// 		$data = array_merge(array($fix_data[1]), $data);
		// 		continue;
		// 	}
		// 	elseif ( ! $add_exist AND $i == 2)
		// 	{
		// 		$data = array_merge(array($fix_data[2]), $data);
		// 		continue;
		// 	}
		// }
		// For 固定目標(寫死) - End

		$this->response_json($this->status, $this->msg, array(
			'data' 	  => $data,
			'total_percent' => $total_percent,
		));
	}

	public function set_month_goal_data()
	{
		$data = $this->get_request_post(array(
			['login_token', '', 'token is not empty', 'text'],
			['id', '', '', 'text'],
			['name', '', 'name is not empty', 'text'],
			['now_num', '', '', 'text'],
			['total_num', '', '', 'text'],
			['year', '', '', 'text'],
			['month', '', '', 'text'],
		));
		// $data['year']  = date("Y");
		// $data['month'] = date("n");
		print_r($data['month']);exit;
		if ($data['id'] == '') {
			$option = 'add';
			$data['user_id'] = $data['login_token'];
			unset($data['login_token']);
			unset($data['id']);
			if ($this->User_info_model->is_month_goal_exist($data['user_id'], $data['name'], $data['year'], $data['month']) != 0)
				$this->response_json(FALSE, '名稱重複, 請重新命名!');
		} else {
			$option = 'edit';
			$data['user_id'] = $data['login_token'];
			unset($data['login_token']);
		}

		if ($this->User_info_model->set_month_goal_data($data, $option)) {
			$this->response_json(TRUE, '成功', array('data' => $data));
		} else {
			$this->response_json(FALSE, '失敗');
		}
	}

	public function delete_month_goal()
	{

		$data = $this->get_request_post(array(
			['login_token', '', 'token is not empty', 'text'],
			['id', '', 'id is not empty', 'text']
		));


		$data['user_id'] = $data['login_token'];
		unset($data['login_token']);

		$res = $this->User_info_model->get_montyh_goal_by_id($data['user_id'], $data['id']);



		if ($res) {
			$this->User_info_model->delete_month_goal($data['user_id'], $data['id']);


			$this->response_json(TRUE, '成功');
		} else {
			$this->response_json(FALSE, '目標id不存在');
		}
	}


	public function set_year_goal_data()
	{
		$data = $this->get_request_post(array(
			['login_token', '', 'token is not empty', 'text'],
			['id', '', '', 'text'],
			['name', '', '', 'text'],
			['now_num', '', '', 'text'],
			['total_num', '', '', 'text'],
			['year', '', '', 'text'],
		));
		// $data['year'] = date("Y");//針對今年
		// $data['year'] = date("Y",strtotime('+ 1 year'));//針對明年
		print_r($data['year']);exit;
		if ($data['id'] == '') {
			$option = 'add';
			$data['user_id'] = $data['login_token'];
			unset($data['login_token']);
			unset($data['id']);
			if ($this->User_info_model->is_year_goal_exist($data['user_id'], $data['name'], $data['year']) != 0)
				$this->response_json(FALSE, '名稱重複, 請重新命名!');
		} else {
			$option = 'edit';
			$data['user_id'] = $data['login_token'];
			unset($data['login_token']);
		}
		$data['year'] = date("Y");

		if ($this->User_info_model->set_year_goal_data($data, $option)) {
			$this->response_json(TRUE, '成功', array('data' => $data));
		} else {
			$this->response_json(FALSE, '失敗');
		}
	}


	// 取得目標與客戶

	public function get_goals($year, $month, $id = FALSE, $page = 1)
	{
		$data = $this->get_request_post(array(
			['login_token', '', 'token is not empty', 'text', 'token'],
			['goal_keyword', '', '', 'text'],
			['customer_keyword', '', '', 'text'],
			['minor_id', 0, '', 'text'],
		));

		if (!(is_numeric($year) and (4 == strlen(floor($year))) and is_numeric($month) and (2 >= strlen(floor($month)))))
			$this->response_json(FALSE, '格式不支援');

		$goal_like_data = array(
			'name' => $data['goal_keyword']
		);
		$customer_keyword = array(
			'customer_name' => $data['customer_keyword']
		);




		// for 主管瀏覽
		// ['minor_id' , 0, '', 'text'],
		if ($data['minor_id'] !== 0) {
			if (!$this->User_info_model->can_view_supervisor($data['login_token']))
				$this->response_json(FALSE, '請升級後繼續');

			// 檢查是否可以檢視
			if (!$this->User_info_model->is_owner_user($data['login_token'], $data['minor_id']))
				$this->response_json(FALSE, '您無權查看此頁面');

			$data['login_token'] = $data['minor_id'];
		}
		$where_data = array(
			'user_id' 		=> $data['login_token'],
			'year' 			=> $year,
			'month' 		=> $month,
			'is_delete'		=> 0
		);
		if (is_numeric($id)) {
			$where_data = array_merge($where_data, array(
				'id' 		=> $id,
			));
		}
		$tmp_where = $where_data;
		$goal_data = $this->User_info_model->get_goal($where_data, $goal_like_data);

		$tmp_sql = $this->db->last_query();

		// $flag_ary = array('A&H' => FALSE, '躉繳' => FALSE, '壽險期繳追蹤名單' => FALSE);
		require_once(APPPATH . 'libraries/Goal_item.php');
		$all_goal_data = array();
		foreach ($goal_data as $key => $value) {
			$Goal = new Goal_item($value);
			if ($goal_customer_data = $this->User_info_model->get_goal_customer($value['id'], $customer_keyword)) {
				$Goal->set_customer($goal_customer_data);
				$Goal->count();
			}

			// if (array_key_exists($value['name'], $flag_ary))
			// 	$flag_ary[$value['name']] = $key;

			$all_goal_data[$key] = $Goal->print_data($page);
		}

		// $goals = array();
		// for 固定的3個目標 (寫死)
		// foreach ($flag_ary as $key => $value)
		// {
		// 	if ($value !== FALSE)
		// 	{
		// 		array_unshift($goals, $all_goal_data[$value]);
		// 		unset($all_goal_data[$value]);
		// 		continue;
		// 	}

		// 	$Goal = new Goal_item();
		// 	$Goal->set_name($key);
		// 	array_unshift($goals, $Goal->print_data());
		// }
		// $goals = array_merge($goals, $all_goal_data);

		$this->response_json(TRUE, '', array(
			'data' 		=> $all_goal_data,
		));
	}

	public function customer_goal($opt = FALSE)
	{
		if ($opt == 'add') {
			$data = $this->get_request_post(array(
				['login_token', '', 'token is not empty', 'text', 'token'],
				['goal_id', '', 'goal id is not empty', 'text'],
				['customer_name', '', 'customer name is not empty', 'text'],
				['no', '', '', 'text'],
				['estimate_money', '', '', 'text'],
				['deal_money', '', '', 'text'],
				// ['goal_diff' , 0, '', 'text'],
				['goal_estimate', 0, '', 'text'],
				// ['goal_deal' , 0, '', 'text'],
			));

			unset($data['login_token']);
			$goal_estimate = $data['goal_estimate'];
			unset($data['goal_estimate']);

			if ($insert_id = $this->User_info_model->add_customer_goal($data))
				$this->response_json(TRUE, '新增成功', array('customer_id' => $insert_id, 'goal_estimate' => ($goal_estimate + $data['estimate_money'])));
		} elseif ($opt == 'edit') 			// 可能會有漏洞
		{
			$data = $this->get_request_post(array(
				['login_token', '', 'token is not empty', 'text', 'token'],
				['goal_id', '', 'goal id is not empty', 'text'],
				['customer_id', '', 'customer id is not empty', 'text'],
				['customer_name', '', 'customer name is not empty', 'text'],
				['no', '', '', 'text'],
				['estimate_money', '', '', 'text'],
				['deal_money', '', '', 'text'],
				['goal_total_money', 0, '', 'text'],
				['goal_estimate', 0, '', 'text'],
				['goal_deal', 0, '', 'text'],
			));

			$where_data = array(
				'id' => $data['customer_id'],
				'goal_id' => $data['goal_id'],
			);
			$res = $this->User_info_model->get_goal_customer_info($data['login_token'], $data['goal_id'], $data['customer_id']);

			unset($data['login_token']);
			unset($data['goal_id']);
			unset($data['customer_id']);

			$goal_total_money 	= $data['goal_total_money'];
			$goal_estimate 		= $data['goal_estimate'];
			$goal_deal 			= $data['goal_deal'];
			unset($data['goal_estimate']);
			unset($data['goal_total_money']);
			unset($data['goal_deal']);

			$goal_deal_offset 		= $data['deal_money'] - $res['deal_money'];
			$goal_estimate_offset 	= $data['estimate_money'] - $res['estimate_money'];
			$goal_deal = ($res['is_complete'] == 1 ? $goal_deal + $goal_deal_offset : $goal_deal);
			$goal_diff = $goal_total_money - $goal_deal;

			if ($this->User_info_model->modify_customer_goal($where_data, $data)) {
				$this->response_json(TRUE, '修改成功', array(
					'goal_estimate' => ($goal_estimate + $goal_estimate_offset),
					'goal_deal' 	=> ($goal_deal),
					'goal_diff' 	=> ($goal_diff),
				));
			}
		} elseif ($opt == 'del') {
			$data = $this->get_request_post(array(
				['login_token', '', 'token is not empty', 'text', 'token'],
				['goal_id', '', 'goal id is not empty', 'text'],
				['customer_id', '', 'customer id is not empty', 'text'],
				['goal_total_money', 0, '', 'text'],
				['goal_estimate', 0, '', 'text'],
				['goal_deal', 0, '', 'text'],
			));
			$res = $this->User_info_model->get_goal_customer_info($data['login_token'], $data['goal_id'], $data['customer_id']);

			$where_data = array(
				'id' => $data['customer_id'],
				'goal_id' => $data['goal_id'],
			);

			$del_data = array(
				'is_delete' => 1,
			);

			$goal_total_money 	= $data['goal_total_money'];
			$goal_estimate 		= $data['goal_estimate'];
			$goal_deal 			= $data['goal_deal'];
			unset($data['goal_estimate']);
			unset($data['goal_total_money']);
			unset($data['goal_deal']);

			$goal_estimate -= $res['estimate_money'];
			$goal_deal = ($res['is_complete'] == 1 ? ($goal_deal - $res['deal_money']) : $goal_deal);
			$goal_diff = $goal_total_money - $goal_deal;

			if ($this->User_info_model->modify_customer_goal($where_data, $del_data)) {
				$this->response_json(TRUE, '刪除成功', array(
					'goal_estimate' => $goal_estimate,
					'goal_deal' 	=> $goal_deal,
					'goal_diff' 	=> $goal_diff,
				));
			}
		}
	}

	public function goals($opt = FALSE)
	{
		if ($opt == 'add') {
			$goal_data = $this->get_request_post(array(
				['login_token', '', 'token is not empty', 'text', 'token'],
				['name', '', 'name is not empty', 'text'],
				['year', '', 'year is not empty', 'text'],
				['month', '', 'month is not empty', 'text'],
				['total_money', '', 'total money is not empty', 'text'],
			));

			$customer_data = $this->get_request_post(array(
				['customer_name', '', '', 'text'],
				['no', '', '', 'text'],
				['estimate_money', '', '', 'text'],
				['deal_money', '', '', 'text'],
			));

			$goal_data['user_id'] = $goal_data['login_token'];
			unset($goal_data['login_token']);

			if (!$this->User_info_model->can_view_supervisor($goal_data['user_id'])) $this->response_json(FALSE, '新增失敗, 請加值後繼續');

			if ($this->User_info_model->add_goal($goal_data, $customer_data))
				$this->response_json(TRUE, '新增成功');
		} elseif ($opt == 'edit') {
			$goal_data = $this->get_request_post(array(
				['login_token', '', 'token is not empty', 'text', 'token'],
				['goal_id', '', 'goal id is not empty', 'text'],
				['name', '', 'name is not empty', 'text'],
				['total_money', '', 'total money is not empty', 'text'],
			));

			$where_data = array(
				'user_id' 	=> $goal_data['login_token'],
				'id' 		=> $goal_data['goal_id'],
				'is_delete'	=> 0
			);

			unset($goal_data['login_token']);
			unset($goal_data['goal_id']);

			if ($this->User_info_model->modify_goal($where_data, $goal_data))
				$this->response_json(TRUE, '修改成功');
			else
				$this->response_json(TRUE, '發生錯誤');
		} elseif ($opt == 'del') {

			$goal_data = $this->get_request_post(array(
				['login_token', '', 'token is not empty', 'text', 'token'],
				['goal_id', '', 'goal_id is not empty', 'text'],
			));

			$isGoalExist = $this->Goal_model->getGoals($goal_data['login_token'], $goal_data['goal_id']);

			if ($isGoalExist) {

				$isDeleteGoalSuccess = $this->Goal_model->delGoal($goal_data['goal_id']);

				if ($isDeleteGoalSuccess)
					$this->response_json(true, '刪除成功');
				else
					$this->response_json(false, '操作錯誤');
			} else $this->response_json(false, '目標不存在');
		}
	}

	public function goal_notify()
	{
		$this->load->model('User_info_model');

		$this->response_json(TRUE, $this->User_info_model->get_goal_notify());
	}

	public function goal_customer_complete()
	{
		$customer = $this->get_request_post(array(
			['login_token', '', 'token is not empty', 'text', 'token'],
			['goal_id', '', 'goal id is not empty', 'text'],
			['customer_id', '', 'customer id is not empty', 'text'],
			['is_complete', 0, '', 'text'],
			['goal_total_money', 0, '', 'text'],
			['goal_deal', 0, '', 'text'],
		));
		if (!(is_numeric($customer['is_complete']) and ($customer['is_complete'] == 1 or $customer['is_complete'] == 0)))
			$this->response_json(TRUE, '失敗', $customer);

		$res = $this->User_info_model->get_goal_customer_info($customer['login_token'], $customer['goal_id'], $customer['customer_id']);
		$goal_total_money 	= $customer['goal_total_money'];
		$goal_deal 			= $customer['goal_deal'];

		if ($this->User_info_model->the_goal_is_mine($customer['login_token'], $customer['goal_id'])) {
			$this->User_info_model->set_goal_customer_complete($customer['goal_id'], $customer['customer_id'], $customer['is_complete']);

			if ($customer['is_complete'] == 1) $goal_deal += $res['deal_money'];
			else $goal_deal -= $res['deal_money'];

			$this->response_json(TRUE, '成功', array(
				'goal_total_money' => $goal_total_money,
				'goal_deal' => $goal_deal,
				'goal_diff' => $goal_total_money - $goal_deal
			));
		} else $this->response_json(TRUE, '修改失敗');
	}

	public function my_avatar()
	{
		$data = $this->get_request_post(array(
			['login_token', '', 'token is not empty', 'text', 'token'],
			['avatar', '', '', 'text'],
		));

		$this->User_info_model->set_avatar($data['login_token'], $data['avatar']);
		$this->response_json(TRUE, '成功');
	}
	/* home page - End */



	/* Customer manage - Start */

	public function get_all_customer_manage()
	{
		$this->load->model('Customer_manage_model');
		$this->load->helper('tw_zipcode');
		$data = $this->get_request_post(array(
			['login_token', '', 'token is not empty', 'text'],
			['filter', json_encode(array()), '', 'text'],
			['page', 1, '', 'text'],
			['minor_id', 0, '', 'text'],
			['keyword', '', '', 'text'],
		));

		if (!(is_string($data['filter']) and ((json_last_error() == JSON_ERROR_NONE) ? TRUE : FALSE)))
			$this->response_json(FALSE, 'filter 類型不支援');

		$where_data = array();
		$join_note = FALSE;
		$join_schedule = FALSE;
		$schedule_where = '';
		// print_r($data['filter']);exit;
		foreach (json_decode($data['filter'], TRUE) as $key => $value) {
			// print 123;exit;
			if ($value != '不限' && $value != '全部') {

				if (!empty($value) and $key !== 'status' and $key !== 'is_master_note' and $key !== 'connection')
					$where_data['C.' . $key] = $value;

				if (!empty($value) and $key === 'status')
					$where_data['C.' . $key] = customer_status_tf_str($value);

				if (!empty($value) and $key === 'is_master_note') {
					$where_data['N.note !='] = '';
					$join_note = TRUE;
				}

				if (!empty($value) and $key === 'connection') {
					$join_schedule = TRUE;
					if ($value === 'red') {
						$schedule_where = ' AND end_date >= "' . Date('Y-m-d H:i:s', strtotime('-1 month')) . '" AND end_date <= "' . Date('Y-m-d H:i:s') . '"';
						$where_data['S.end_date <='] = Date('Y-m-d H:i:s');
					} else if ($value === 'orange') {
						$schedule_where = ' AND end_date >= "' . Date('Y-m-d H:i:s', strtotime('-6 month')) . '" AND end_date <= "' . Date('Y-m-d H:i:s', strtotime('-1 month')) . '"';
						$where_data['S.end_date <='] = Date('Y-m-d H:i:s', strtotime('-1 month'));
					} else if ($value === 'yellow') {
						$schedule_where = ' AND end_date >= "' . Date('Y-m-d H:i:s', strtotime('-1 year')) . '" AND end_date <= "' . Date('Y-m-d H:i:s', strtotime('-6 month')) . '"';
						$where_data['S.end_date <='] = Date('Y-m-d H:i:s', strtotime('-6 month'));
					} else if ($value === 'blue') {
						$schedule_where = ' AND end_date <= "' . Date('Y-m-d H:i:s', strtotime('-1 year')) . '"';
						$where_data['S.end_date <='] = Date('Y-m-d H:i:s', strtotime('-1 year'));
					}
				}
			}
		}

		$like_data = array('C.name' => $data['keyword']);

		// for 主管瀏覽
		if ($data['minor_id'] !== 0) {
			// 檢查是否可以檢視
			if (!$this->User_info_model->is_owner_user($data['login_token'], $data['minor_id']))
				$this->response_json(FALSE, '您無權查看此頁面');

			$data['login_token'] = $data['minor_id'];
		}

		$where_data = array_merge($where_data, array(
			'C.user_id' 	=> $data['login_token'],
			'C.is_delete' 	=> 0,
		));

		$total = $this->Customer_manage_model->get_user_all_customer_num($data['login_token'], $where_data, $like_data, $join_note, $join_schedule, $schedule_where);
		// print_r($total);exit;
		$total_page  = $this->Customer_manage_model->compute_total_page($total);

		$order_by = ' `customer_no` ASC';
		$user_customer_info   = $this->Customer_manage_model->get_user_all_customer($data['login_token'], $where_data, $order_by, $like_data, $data['page'], $join_note, $join_schedule, $schedule_where);
		$user_customer_source = $this->Customer_manage_model->get_user_all_source($data['login_token']);
		// $user_customer_field  = $this->Customer_manage_model->get_user_all_field_name($data['login_token']);
		// $this->response_json(TRUE,'',array(
		// 	'source'	=>	$user_customer_source
		// ));
		if (!isset($user_customer_info))
			$user_customer_info = array();

		if (!isset($user_customer_source))
			$user_customer_source = array();

		// print_r($user_customer_info);exit;
		$customer = array();
		foreach ($user_customer_info as $key => $value) {
			for ($i = 0; $i < count($user_customer_source); $i++) {
				if ($user_customer_source[$i]['id'] == $value['source_id'])
					$value['source'] = $user_customer_source[$i]['source_name'];
			}

			if ($value['photo'] != NULL)
				$value['photo'] = base_url() . $value['photo'];
			$field = $this->Customer_manage_model->get_customer_field_value_by_customer($value['id']);
			$connection = $this->Customer_manage_model->get_connection_level($data['login_token'], $value['id']);
			// print_r($connection);exit;

			// $birthday_str = '生日  ' . (empty($value['birthday']) ? '' : $value['birthday']);
			unset($extra_field);
			// $extra_field[] = $birthday_str;
			$extra_field = array();
			for ($i = 0; $i < count($field); $i++) {
				if (empty($field[$i]['field_value'])) continue;

				$extra_field[$i] = $field[$i]['field_name'] . '  ' . $field[$i]['field_value'];
			}

			// $c_d = get_addr_str($value['city'],$value['dist']);
			$customer[$key] =  [
				'id' 			=> $value['id'],
				'customer_no' 	=> $value['customer_no'],
				'connection' 	=> $connection,
				'name' 			=> $value['name'],
				'photo' 		=> $value['photo'],
				'status' 		=> customer_status_tf($value['status']),
				'level' 		=> $value['level'],
				'relation'  	=> $value['relation'],
				'source' 		=> $value['source'],
				'extra_field' 	=> $extra_field,
				'city' 			=> $value['city'],
				'dist' 			=> $value['dist'],
				'birthday' 		=> $value['birthday'],
			];
		}

		$this->response_json(TRUE, '', array(
			'total' 	 => $total,
			'total_page' => $total_page,
			'now_page' => $data['page'],
			'data' => $customer,
			'sad' => $join_note
		));
	}

	/*  快速下拉 客戶資料   start */

	public function get_all_customer_manage_fast()
	{
		$this->load->model('Customer_manage_model');
		$this->load->helper('tw_zipcode');
		$data = $this->get_request_post(array(
			['login_token', '', 'token is not empty', 'text'],
			['index', '', 'index is not empty', 'text'],
			['filter', json_encode(array()), '', 'text'],
			['page', 1, '', 'text'],
			['minor_id', 0, '', 'text'],
			['keyword', '', '', 'text'],
			['include', '', '', 'text']
		));


		if (!(is_string($data['filter']) and ((json_last_error() == JSON_ERROR_NONE) ? TRUE : FALSE)))
			$this->response_json(FALSE, 'filter 類型不支援');

		$where_data = array();
		$join_note = FALSE;
		$join_schedule = FALSE;
		$schedule_where = '';
		foreach (json_decode($data['filter'], TRUE) as $key => $value) {
			if (!empty($value) and $key !== 'status' and $key !== 'is_master_note' and $key !== 'connection')
				$where_data['C.' . $key] = $value;

			if (!empty($value) and $key === 'status')
				$where_data['C.' . $key] = customer_status_tf_str($value);

			if (!empty($value) and $key === 'is_master_note') {
				$where_data['N.note !='] = '';
				$join_note = TRUE;
			}

			if (!empty($value) and $key === 'connection') {
				$join_schedule = TRUE;
				if ($value === 'red') {
					$schedule_where = ' AND end_date >= "' . Date('Y-m-d H:i:s', strtotime('-1 month')) . '" AND end_date <= "' . Date('Y-m-d H:i:s') . '"';
					$where_data['S.end_date <='] = Date('Y-m-d H:i:s');
				} else if ($value === 'orange') {
					$schedule_where = ' AND end_date >= "' . Date('Y-m-d H:i:s', strtotime('-6 month')) . '" AND end_date <= "' . Date('Y-m-d H:i:s', strtotime('-1 month')) . '"';
					$where_data['S.end_date <='] = Date('Y-m-d H:i:s', strtotime('-1 month'));
				} else if ($value === 'yellow') {
					$schedule_where = ' AND end_date >= "' . Date('Y-m-d H:i:s', strtotime('-1 year')) . '" AND end_date <= "' . Date('Y-m-d H:i:s', strtotime('-6 month')) . '"';
					$where_data['S.end_date <='] = Date('Y-m-d H:i:s', strtotime('-6 month'));
				} else if ($value === 'blue') {
					$schedule_where = ' AND end_date <= "' . Date('Y-m-d H:i:s', strtotime('-1 year')) . '"';
					$where_data['S.end_date <='] = Date('Y-m-d H:i:s', strtotime('-1 year'));
				}
			}
		}

		$like_data = array('C.name' => $data['keyword']);

		// for 主管瀏覽
		if ($data['minor_id'] !== 0) {
			// 檢查是否可以檢視
			if (!$this->User_info_model->is_owner_user($data['login_token'], $data['minor_id']))
				$this->response_json(FALSE, '您無權查看此頁面');

			$data['login_token'] = $data['minor_id'];
		}


		//指定index
		$index = $data['index'];
		$lower_data = array_merge($where_data, array(
			'C.user_id' 	=> $data['login_token'],
			'C.is_delete' 	=> 0,
			'C.customer_no < ' => $index
		));



		//取得index是第幾筆
		$max = $this->Customer_manage_model->get_max_no($data['login_token']);
		$lower_count = $this->Customer_manage_model->get_user_all_customer_num($data['login_token'], $lower_data, $like_data, $join_note, $join_schedule, $schedule_where);
		// $this->response_json(TRUE, '', array(
		// 	'low' 	 => $lower_count,

		// ));
		$total_data = array_merge($where_data, array(
			'C.user_id' 	=> $data['login_token'],
			'C.is_delete' 	=> 0,
			//'C.customer_no < '=>$index
		));


		$total = $this->Customer_manage_model->get_user_all_customer_num($data['login_token'], $total_data, $like_data, $join_note, $join_schedule, $schedule_where);
		$total_page  = $this->Customer_manage_model->compute_total_page($total);

		// $this->response_json(TRUE, '', array(
		// 	'total' 	 => $total,

		// ));




		$order_by = ' `customer_no` ASC';
		$bigger_data = array_merge($where_data, array(
			'C.user_id' 	=> $data['login_token'],
			'C.is_delete' 	=> 0,
			"C.customer_no >= "	=> $index
		));

		$user_customer_info   = $this->Customer_manage_model->get_user_all_customer($data['login_token'], $bigger_data, $order_by, $like_data, $data['page'], $total_page, $join_note, $join_schedule, $schedule_where, $total);
		$user_customer_source = $this->Customer_manage_model->get_user_all_source($data['login_token']);
		// $user_customer_field  = $this->Customer_manage_model->get_user_all_field_name($data['login_token']);

		$next_no = 0;
		if ($index > $max)
			$this->response_json(FALSE, '超過資料筆數');

		if (!isset($user_customer_info))
			$user_customer_info = array();

		if (!isset($user_customer_source))
			$user_customer_source = array();

		$customer = array();
		$count = 0;
		foreach ($user_customer_info as $key => $value) {

			if ($count == 25) {
				$next_no = $value['customer_no'];
				break;
			}

			for ($i = 0; $i < count($user_customer_source); $i++) {
				if ($user_customer_source[$i]['id'] == $value['source_id'])
					$value['source'] = $user_customer_source[$i]['source_name'];
			}

			if ($value['photo'] != NULL)
				$value['photo'] = base_url() . $value['photo'];

			$field = $this->Customer_manage_model->get_customer_field_value_by_customer($value['id']);
			$connection = $this->Customer_manage_model->get_connection_level($data['login_token'], $value['id']);

			// $birthday_str = '生日  ' . (empty($value['birthday']) ? '' : $value['birthday']);
			unset($extra_field);
			// $extra_field[] = $birthday_str;
			$extra_field = array();
			for ($i = 0; $i < count($field); $i++) {
				if (empty($field[$i]['field_value'])) continue;

				$extra_field[$i] = $field[$i]['field_name'] . '  ' . $field[$i]['field_value'];
			}

			// $c_d = get_addr_str($value['city'],$value['dist']);
			$customer[$key] =  [
				'id' 			=> $value['id'],
				'customer_no' 	=> $value['customer_no'],
				'connection' 	=> $connection,
				'name' 			=> $value['name'],
				'photo' 		=> $value['photo'],
				'status' 		=> customer_status_tf($value['status']),
				'level' 		=> $value['level'],
				'relation'  	=> $value['relation'],
				'source' 		=> $value['source'],
				'extra_field' 	=> $extra_field,
				'city' 			=> $value['city'],
				'dist' 			=> $value['dist'],
				'birthday' 		=> $value['birthday'],
			];


			$count++;
		}



		$this->response_json(TRUE, '', array(
			'total' 	 => $total,
			'total_page' => $total_page,
			'now_page' => ceil(($lower_count + 1) / 25),
			'data' => $customer,
			'sad' => $join_note,
			'next_no' =>	$next_no
		));
	}

	/* 快速下拉取得管理客戶  end */


	public function get_all_customer_manage_no_page()
	{
		$this->load->model('Customer_manage_model');
		$this->load->helper('tw_zipcode');
		$data = $this->get_request_post(array(
			['login_token', '', 'login_token is not empty', 'text', 'token'],
			['minor_id', 	0, 	'', 						'text'],
			['customer_no', '', 'customer no is not empty', 'text'],
		));

		$where_data = array();

		// for 主管瀏覽
		if ($data['minor_id'] !== 0) {
			// 檢查是否可以檢視
			if (!$this->User_info_model->is_owner_user($data['login_token'], $data['minor_id']))
				$this->response_json(FALSE, '您無權查看此頁面');

			$data['login_token'] = $data['minor_id'];
		}
		if (!$this->Customer_manage_model->is_customer_no_exist_2($data['login_token'], $data['customer_no'])) $this->response_json(FALSE, '找不到客戶, 請重新輸入');

		$where_data = array_merge($where_data, array(
			'C.user_id' 	=> $data['login_token'],
			'C.is_delete' 	=> 0,
		));

		$total = $this->Customer_manage_model->get_user_all_customer_num_nopage($data['login_token'], $where_data);

		$order_by = ' `customer_no` ASC';
		$user_customer_info   = $this->Customer_manage_model->get_user_all_customer_nopage($data['login_token'], $where_data, $order_by);
		$user_customer_source = $this->Customer_manage_model->get_user_all_source($data['login_token']);
		// $user_customer_field  = $this->Customer_manage_model->get_user_all_field_name($data['login_token']);

		if (!isset($user_customer_info))
			$user_customer_info = array();

		if (!isset($user_customer_source))
			$user_customer_source = array();

		$customer = array();
		foreach ($user_customer_info as $key => $value) {
			for ($i = 0; $i < count($user_customer_source); $i++) {
				if ($user_customer_source[$i]['id'] == $value['source_id']) $value['source'] = $user_customer_source[$i]['source_name'];
			}

			if ($value['photo'] != NULL) $value['photo'] = base_url() . $value['photo'];

			$field = $this->Customer_manage_model->get_customer_field_value_by_customer($value['id']);

			// $birthday_str = '生日  ' . (empty($value['birthday']) ? '' : $value['birthday']);
			unset($extra_field);
			// $extra_field[] = $birthday_str;
			$extra_field = array();
			for ($i = 0; $i < count($field); $i++) {
				if (empty($field[$i]['field_value'])) continue;

				$extra_field[$i] = $field[$i]['field_name'] . '  ' . $field[$i]['field_value'];
			}

			$c_d = get_addr_str($value['city'], $value['dist']);
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
				'dist' 			=> $value['dist'],
				'city_str' 		=> $c_d['city'],
				'dist_str' 		=> $c_d['dist'],
				'birthday' 		=> $value['birthday'],
			];
		}

		$this->response_json(TRUE, '成功', array(
			'total' => $total,
			'data'  => $customer,
		));
	}

	public function get_customer_mgr_info()
	{
		$this->load->model('Customer_manage_model');
		$this->load->helper('tw_zipcode');
		$data = $this->get_request_post(array(
			['login_token', '', 'token is not empty', 'text'],
			['id', '', 'id no is not empty', 'text'],
		));

		$user_customer_info = $this->Customer_manage_model->get_mgr_customer_info($data['login_token'], $data['id']);
		$user_customer_note = $this->Customer_manage_model->get_customer_note($data['login_token'], $data['id']);

		if (!isset($user_customer_info)) {
			$user_customer_info = array();
		} else {
			$user_customer_info['source'] = $this->Customer_manage_model->get_customer_source($data['login_token'], $user_customer_info['source_id'])['source_name'];
			$user_customer_info['status'] = customer_status_tf($user_customer_info['status']);
			$field = $this->Customer_manage_model->get_customer_field_value_by_customer($user_customer_info['id']);
			if ($user_customer_info['photo'] != NULL)
				$user_customer_info['photo'] = base_url() . $user_customer_info['photo'];

			$user_customer_info['extra_field'] = array();
			foreach ($field as $key => $value) {
				$user_customer_info['extra_field'][$key]['field_name']  = $value['field_name'];
				$user_customer_info['extra_field'][$key]['field_value'] = $value['field_value'];
			}
			$user_customer_info['notes'] = $user_customer_note;
		}

		$this->response_json(TRUE, '', array(
			'data' => $user_customer_info,
		));
	}

	public function get_user_source_list()
	{
		$this->load->model('Customer_manage_model');
		$data = $this->get_request_post(array(
			['login_token', '', 'token is not empty', 'text'],
		));
		$user_custome_source_list = array();
		$user_source_list = $this->Customer_manage_model->get_user_source_list($data['login_token']);
		if (empty($user_source_list)) $user_source_list = array();

		foreach ($user_source_list as $key => $value) {
			$user_custome_source_list[$key]['id'] = $value['id'];
			$user_custome_source_list[$key]['source_name'] = $value['source_name'];
		}

		$this->response_json(TRUE, '', array(
			'data' => $user_custome_source_list,
		));
	}

	public function del_customer()
	{
		$this->load->model('Customer_manage_model');
		$data = $this->get_request_post(array(
			['login_token', '', 'login_token is not empty', 'text', 'token'],
			['customer_id', '', '', 'text'],
		));

		if (($customer = $this->Customer_manage_model->is_customer_mine($data['login_token'], $data['customer_id'])) == FALSE)
			$this->response_json(FALSE, '查無此客戶, 導致無法刪除');

		$this->Customer_manage_model->del_customer($data['login_token'], $data['customer_id']);
		$this->Customer_manage_model->reset_customer_number($data['login_token'], $customer['customer_no']);
		$this->response_json(TRUE, '刪除成功');
	}

	public function set_user_customer_info()
	{
		//require('./vendor/autoload.php');
		$this->load->model('Customer_manage_model');
		$data = $this->get_request_post(array(
			['login_token', '', 		'token is not empty', 		'text'],
			['option', '', 		'option is not empty', 		'text'],
			['name', '', 		'name is not empty', 		'text'],
			['source_id', '', 		'source_id is not empty', 	'text'],
			['relation', '', 		'relation is not empty', 	'text'],
			['level', '', 		'level is not empty', 		'text'],
			['job', NULL, 	'', 'text'],
			['birthday', NULL, 	'', 'text'],
			['city', 0, 		'', 'text'],
			['dist', 100, 		'', 'text'],
			['address', NULL, 	'', 'text'],

			['formatted_address', NULL, 	'', 'text'],
			['global_code', NULL, 	'', 'text'],
			['location_lat', NULL, 	'', 'text'],
			['location_lng', NULL, 	'', 'text'],
			['geocoding_result', NULL, 	'', 'text'],

			['text', NULL, 	'', 'text'],
			['photo', NULL, 	'', 'text'],
			['extra_field', json_encode(array()),  '', 'array'],
		));


		// $this->response_json(true, 'test', compact('data'));
		//$data['birthday'] = date('Y-m-d', strtotime("$data[birthday] + 11 year"));
		$option = $data['option'];
		$extra_field = json_decode($data['extra_field'], TRUE);

		if ($option == 'add' or $option == 'edit') {
			unset($data['option']);
			$data['user_id'] = $data['login_token'];
			unset($data['login_token']);
			unset($data['extra_field']);
			if (empty($data['photo']) or $data['photo'] == NULL) unset($data['photo']);
		}

		if ($option == 'edit') {
			$id = $this->get_request_post(array(
				['id', '', 'id is not empty', 'text'],
			));
			$data = array_merge($data, $id);
		} elseif ($option == 'add') {
			if (!$this->User_info_model->can_view_supervisor($data['user_id'])) {
				if (!$this->Customer_manage_model->is_customers_num_less_200($data['user_id'])) {
					$this->response_json(FALSE, '客戶管理名單已超過200人次, 新增失敗, 請加值後繼續');
				}
			}
			$data['customer_no'] = $this->Customer_manage_model->get_customer_num($data['user_id']) + 1;
		}

		if ($insert_id = $this->Customer_manage_model->set_user_customer_info($data, $option)) {
			if ($option == 'edit') {
				$insert_id = $data['id'];
				$res = $this->Customer_manage_model->get_mgr_customer_info($data['user_id'], $data['id']);
			} else {
				$res  = $this->Customer_manage_model->get_add_customer_info($data['user_id'], $insert_id);
			}


			$field_data = array();


			if (!empty($extra_field)) {
				$count = 0;
				foreach ($extra_field as $key => $value) {
					$field_data[$count]['customer_id'] = $insert_id;
					$field_data[$count]['field_name']  = $key;
					$field_data[$count]['field_value'] = $value;
					$count++;
				}
				if (!$this->Customer_manage_model->set_customer_field($field_data, $option, $insert_id))
					$this->response_json(FALSE, '新增/編輯自訂欄位失敗!');
			}
			//收到空陣列表示全刪除
			else {
				if ($option == 'edit')
					$this->Customer_manage_model->set_customer_field($field_data, $option, $insert_id);
			}


			if ($option == 'edit') {
				$user_customer_info = $this->Customer_manage_model->get_mgr_customer_info($data['user_id'], $data['id']);
				$user_customer_note = $this->Customer_manage_model->get_customer_note($data['user_id'], $data['id']);
				//var_dump($user_customer_info);
				if (!isset($user_customer_info)) {
					$user_customer_info = array();
				} else {
					$user_customer_info['source'] = $this->Customer_manage_model->get_customer_source($data['user_id'], $user_customer_info['source_id'])['source_name'];
					$user_customer_info['status'] = customer_status_tf($user_customer_info['status']);
					$connection = $this->Customer_manage_model->get_connection_level($data['user_id'], $data['id']);

					$field = $this->Customer_manage_model->get_customer_field_value_by_customer($user_customer_info['id']);
					if ($user_customer_info['photo'] != NULL)
						$user_customer_info['photo'] = base_url() . $user_customer_info['photo'];

					$user_customer_info['extra_field'] = array();
					$count = 0;
					foreach ($field as $key => $value) {
						$user_customer_info['extra_field'][$count]  = $value['field_name'] . '  ' . $value['field_value'];
						//$user_customer_info['extra_field'][$key]['field_value'] = $value['field_value'];
						$count++;
					}
					$user_customer_info['connection'] = $connection;
					$user_customer_info['notes'] = $user_customer_note;
					$res = $user_customer_info;
				}
			} else {

				$connection = $this->Customer_manage_model->get_connection_level($data['user_id'], $data['customer_no']);
				$user_customer_note = $this->Customer_manage_model->get_customer_note($data['user_id'], $data['customer_no']);
				$field = $this->Customer_manage_model->get_customer_field_value_by_customer($res['id']);
				if ($res['photo'] != NULL)
					$res['photo'] = base_url() . $res['photo'];

				$res['extra_field'] = array();
				$count = 0;
				foreach ($field as $key => $value) {
					$res['extra_field'][$count]  = $value['field_name'] . '  ' . $value['field_value'];
					//$user_customer_info['extra_field'][$key]['field_value'] = $value['field_value'];
					$count++;
				}
				$res['connection'] = $connection;
				$res['notes'] = $user_customer_note;
				//$res=$user_customer_info;
			}


			$this->response_json(TRUE, '新增/編輯成功', array(
				'info'	=>	$res
			));
		} else {
			$this->response_json(FALSE, '新增/編輯客戶失敗!');
		}
	}

	public function set_customer_source()
	{
		$this->load->model('Customer_manage_model');
		$data = $this->get_request_post(array(
			['login_token', '', 'token is not empty',  'text'],
			['source_name', '', 'source name is not empty', 'text'],
			['option', '', 'option is not empty', 'text'],
		));
		$option = $data['option'];
		$data['user_id'] = $data['login_token'];
		unset($data['login_token']);
		unset($data['option']);
		$id = $this->Customer_manage_model->set_customer_source($data, $option);
		$this->response_json(TRUE, '新增成功', array('id' => $id));
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
			['login_token', '', 'token is not empty', 'text', 'token'],
		));

		$out_data = array();
		foreach ($this->Customer_manage_model->get_customer_job($data['login_token']) as $key => $value) {
			if (!in_array($value['job'], $out_data))
				array_push($out_data, $value['job']);
		}

		$this->response_json(TRUE, '修改成功', array(
			'data' => $out_data,
		));
	}

	public function upload_img()
	{
		// print 123789;exit;
		// $data = $this->get_request_post(array(
		// 	['login_token', '', 'token is not empty', 'text', 'token'],
		// ));
		// print_r($data);exit;

		$confif = array(
			'upload_path' 	=> APPPATH . '../uploads/customer_manage',
			'allowed_types' => '*',
			'max_size' 		=> 20480,
			'overwrite' 	=> FALSE,
		);
		// $this->upload->initialize($config);
		$this->load->library('upload');

		$types 		= explode('.', $_FILES['img']['name']);
		$file_type  = $types[count($types) - 1];
		$_FILES['img']['name'] = uniqid(date('YmdHis')) . uniqid() . '.' . $file_type;

		$this->upload->initialize($confif);

		// print 123;
		if (!$this->upload->do_upload('img')) {
			$error = array('error' => $this->upload->display_errors());
			$this->response_json(TRUE, '', array(
				'error' => $error
			));
		} else {
			$this->response_json(TRUE, '', array(
				'img_src' => 'uploads/customer_manage/' . $_FILES['img']['name']
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
			['login_token', '', 'token is not empty', 'text', 'token'],
			['file_name', '', '', 'text'],
		));

		// print_r($data);exit;

		$token = $this->Jwt->excel_import_token($data['login_token'], $data['file_name']);
		$this->response_json(TRUE, 'token建立成功', array(
			'token' => $token,
		));
	}

	public function okk()
	{
		$this->load->model('PhpSpreadsheet_excel_model', 'Csv');
		$this->load->model('Customer_manage_model', 'Customer');
		$this->load->model('Jwt_model', 'Jwt');


		$w = [
			['this is A1', '', 'C1', '',   'E1'],
			['this is A2', '', 'C2', '',   'E2'],
			['this is A3', '', '',   'D3', 'E3'],
			['this is A4', '', '',   '',   '', 'E4'],
		];

		$this->Csv->write($w, 'xls');
	}

	public function uploads_excel($token = '')
	{
		// ajax call upload_img apic, then post to uploads_excel/$token ( file_name
		// print_r($token);exit;
		$this->load->view('excel_view_upload', array('token' => $token));
	}

	public function view_excel($token='')
	{
		// print 555;exit;

		$this->load->model('PhpSpreadsheet_excel_model', 'Csv');
		$this->load->model('Customer_manage_model', 'Customer');
		$this->load->model('Jwt_model', 'Jwt');
		$excel_import_token = $this->Jwt->verify_token($token);
		//
		$this->Csv->read_verify_view('uploads/excel_files/xddddd.csv', $this->Customer, 16, $token, '3');
		// var_dump($this->Csv->read_verify_view($excel_import_token['file_name'], $this->Customer, $excel_import_token['user_id'], $token, '3'));
		// exit;
		// var_dump($excel_import_token); 
		// var_dump($excel_import_token['user_id']);
		// print_r($excel_import_token);
// exit;
		// print_r($token);exit;
		// $this->Csv->read_verify_view($excel_import_token['file_name'], $this->Customer, $excel_import_token['user_id'], $token, '3');
	}

	public function import_excel_for_view_excel($token)
	{
		// print 123;exit;
		// print_r($_POST);exit;
		$this->load->model('PhpSpreadsheet_excel_model', 'Csv');
		$this->load->model('Customer_manage_model', 'Customer');
		$this->load->model('Jwt_model', 'Jwt');

		$verified_data = $this->input->post();
		// print_r($verified_data);exit;
		unset($verified_data['example_length']);

		// $excel_import_token = $this->Jwt->verify_token($token);
		$excel_import_token['user_id']=16;
		// print 123;
		// print_r($excel_import_token);exit;
		// $this->Csv->set_package_reader('uploads/excel_files/Jenny test.csv');
		$this->Csv->set_package_reader('uploads/excel_files/xddddd.csv');
		// $this->Csv->set_package_reader($excel_import_token['file_name']);

		// $verified_data[]=array(
		// 	'example_length'=>'on'
		// );
		foreach ($verified_data as $key => $value) {
			if ($value == 'on') {
				$this->Csv->r_line("$key");
			}
		}
		$data = $this->Csv->r_table();
		$insert_data=$this->Customer->excel_field_tf_db($data, $excel_import_token['user_id']);
		print_r($insert_data);exit;
		// $data = $this->Csv->read_data;
		// $this->Csv->read_verify_view('uploads/excel_files/xddddd.csv', $this->Customer, 16, $token, '3');
		// print 123;
		// print_r($data);exit;
		// $this->view_excel($token);
		
		// $this->js_output_and_redirect('成功', base_url() . 'Api/view_excel/'.$token);
		// 轉換insert data array, 並且入DB
		if ($this->Customer->add_customer_batch($this->Customer->excel_field_tf_db($data, $excel_import_token['user_id']))) {
			$this->js_output_and_redirect('新增成功', base_url() . 'Excel_import/close_windon');
		} else {
			$this->js_output_and_redirect('發生錯誤', base_url() . 'Excel_import/close_windon');
		}
	}

	public function addr_tf_map()
	{
		$data = $this->get_request_post(array(
			['login_token', '', 'login_token is not empty', 'text', 'token'],
			['address', 	'苦', '', 	'text'],
		));

		$this->load->model('Customer_manage_model', 'Customer');

		if (($result = $this->Customer->addr_tf_location($data['address']))['status'] === 'OK') {
			$global_code = isset($result['results'][0]['plus_code']['global_code']);

			$this->response_json(TRUE, '成功', array(
				'data' => array(
					'geocoding_result' 	=> json_encode($result),
					'formatted_address' => $result['results'][0]['formatted_address'],
					'global_code' 		=> ($global_code ? $result['results'][0]['plus_code']['global_code'] : NULL),
					'location_lat' 		=> $result['results'][0]['geometry']['location']['lat'],
					'location_lng' 		=> $result['results'][0]['geometry']['location']['lng'],
				)
			));
		} else return $this->response_json(FALSE, '地址經緯度轉換錯誤', ['data' => $result]);
	}

	public function map_location()
	{
		$data = $this->get_request_post(array(
			['login_token', '', 'login_token is not empty', 'text', 'token'],
		));

		$this->load->model('Customer_manage_model', 'Customer');

		$this->response_json(TRUE, '成功', array(
			'data' => $this->Customer->get_map_location($data['login_token'])
		));
	}

	public function del_customer_source()
	{
		$data = $this->get_request_post(array(
			['login_token', '', 'login_token is not empty', 'text', 'token'],
			['source_id', '', 'source_id is not empty', 'text'],
		));
		$this->load->model('Customer_manage_model', 'Customer');

		if ($this->Customer->is_source_id_can_del($data['source_id'], $data['login_token'])) {
			$this->Customer->del_source_id($data['login_token'], $data['source_id']);

			$this->response_json(TRUE, '刪除成功');
		} else $this->response_json(FALSE, '目前有其他客戶名單使用此來源');
	}

	public function check_del_customer_source()
	{
		$data = $this->get_request_post(array(
			['login_token', '', 'login_token is not empty', 'text', 'token'],
			['source_id', '', 'source_id is not empty', 'text'],
		));
		$this->load->model('Customer_manage_model', 'Customer');

		if (!$this->Customer->is_source_id_can_del($data['source_id'], $data['login_token']))
			$this->response_json(FALSE, '目前有其他客戶名單使用此來源');
		$this->response_json(TRUE, '可以刪除');
	}

	public function set_customer_status()
	{
		$data = $this->get_request_post(array(
			['login_token', '', 'login_token is not empty', 'text', 'token'],
			['customer_id', '', 'customer id is not empty', 'number'],
			['status', '', 'status is not empty', 'number'],
		));
		$this->load->model('Customer_manage_model', 'Customer');

		$this->Customer->set_status($data['login_token'], $data['customer_id'], $data['status']);

		$this->response_json(TRUE, '成功');
	}
	/* Customer manage - End */



	/* Calendar & memo - Start */

	public function memo($opt = FALSE, $id = FALSE)
	{
		$this->load->model('Calendar_model');

		if ($opt == 'add') {
			$data = $this->get_request_post(array(
				['login_token', '', 'token is not empty', 'text'],
				['color', '', 'color is not empty', 'text'],
				['text', '', '', 'text'],
			));

			$data['user_id'] = $data['login_token'];
			unset($data['login_token']);

			if ($this->Calendar_model->add_memo($data))
				$this->response_json(TRUE, '新增成功');
			else
				$this->response_json(FALSE, '發生錯誤');
		} elseif (is_numeric($opt)) {
			$data = $this->get_request_post(array(
				['login_token', '', 'token is not empty', 'text'],
				['color', '', 'color is not empty', 'text'],
				['text', '', '', 'text'],
			));

			$where_data = array('id' => $opt, 'user_id' => $data['login_token']);
			unset($data['login_token']);

			if ($this->Calendar_model->modify_memo($data, $where_data))
				$this->response_json(TRUE, '修改成功');
			else
				$this->response_json(FALSE, '發生錯誤');
		} elseif ($opt == 'del' and is_numeric($id)) {
			$data = $this->get_request_post(array(
				['login_token', '', 'token is not empty', 'text'],
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
			['login_token', '', '', 'text', 'token'],
		));

		$o_data = $this->Calendar_model->get_memo($data['login_token']);
		if (isset($o_data)) {
			$this->response_json(TRUE, '', array(
				'data' => $o_data,
			));
		} else {
			$this->response_json(FALSE, '發生錯誤');
		}
	}

	public function schedule_item($opt = FALSE, $id = FALSE)
	{
		$this->load->model('Calendar_model');

		if ($opt == 'add') {
			$data = $this->get_request_post(array(
				['login_token', '', 'token is not empty', 'text'],
				['item_name', '', 'item name is not empty', 'text'],
			));

			$data['user_id'] = $data['login_token'];
			unset($data['login_token']);

			if ($insert_id = $this->Calendar_model->add_schedule_item($data)) {
				$this->response_json(TRUE, '新增成功', array(
					'item_id' => $insert_id,
				));
			} else
				$this->response_json(FALSE, '發生錯誤');
		} elseif (is_numeric($opt)) {
			$data = $this->get_request_post(array(
				['login_token', '', 'token is not empty', 'text'],
				['item_name', '', 'item name is not empty', 'text'],
			));

			$where_data = array('id' => $opt, 'user_id' => $data['login_token']);
			unset($data['login_token']);

			if ($this->Calendar_model->modify_schedule_item($data, $where_data))
				$this->response_json(TRUE, '修改成功');
			else
				$this->response_json(FALSE, '發生錯誤');
		} elseif ($opt == 'del') //  AND is_numeric($id)
		{
			$data = $this->get_request_post(array(
				['login_token', '', 'token is not empty', 'text'],
				['delete', '', 'delete[] array is not empty', 'array'],
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
			['login_token', '', '', 'text', 'token'],
		));

		$f_data = $this->Calendar_model->get_schedule_item_fixed();
		$s_data = $this->Calendar_model->get_schedule_item($data['login_token']);
		if ($f_data) {
			$this->response_json(TRUE, '', array(
				'fixed_data' => $f_data,
				'self_data' => $s_data,
			));
		} else {
			$this->response_json(FALSE, '發生錯誤');
		}
	}
	// add 0927 設定配分、名稱(全部人都可)
	public function edit_schedule_item(){
		$data = $this->get_request_post(array(
			['login_token', '', '', 'text', 'token'],
			['data', '', '', 'array'],
			// ['data', '', 'schedule_item is not empty', 'array'],
		// 	['schedule_item_name', '', 'schedule_item_name is not empty', 'array'],
		// 	['schedule_item_score', '', 'schedule_item_score is not empty', 'array'],
		));

	print_r($data['data']);exit;
		$res = $this->db->select('*')
						->from('user_schedule_item_score' )
						->where(array('id' =>$data['schedule_item_id'] ,'user_id'=>$data['login_token']))
					
						->get()
						->row_array();
		print_r($res);exit;

	}
	public function get_schedule_item_score(){
		$data['login_token']='104';
		// $data = $this->get_request_post(array(
		// 	['login_token', '', '', 'text', 'token'],
		// 	// ['schedule_item_id', '', 'schedule_item_id is not empty', 'array'],
		// 	// ['schedule_item_name', '', 'schedule_item_name is not empty', 'array'],
		// 	// ['schedule_item_score', '', 'schedule_item_score is not empty', 'array'],
		// ));
		// $id='';
		$res = $this->db->select('*')
						->from('user_schedule_item_score' )
						->where(array('user_id'=>$data['login_token']))
						->get()
						->result_array();
		print_r($res);exit;

	}
	// 對應schedule_item 加入 schedule_item_score
	public function set_schedule_item_in_all(){
		$res = $this->db->select('id')
						->from('user' )
						->where(array('is_delete' =>0))
						->get()
						->result_array();

		// print_r(count($res));exit;
		foreach($res as $r){
			$arr=['新增','約訪','面談','建議書','簽約','收費'];
			$arr_s=[1,1,2,3,4,5];
			
		
			for($i=0;$i<6;$i++){
				$data=array(
					"user_id"       =>	$r['id'],
					"item_name"     =>	$arr[$i],
					"score_fix"     =>	$arr_s[$i],	
				);
				// print_r($data);
				$this->db->insert("user_schedule_item_score", $data);
			}
			
			
		}
		exit;
		// print_r($r['id']);exit;
	}
	public function set_schedule_item_in($user_id=false){
		$res = $this->db->select('*')
						->from('user' )
						->where(array('is_delete' =>0,'id'=>$user_id))
						->get()
						->row_array();

		// print_r($res);exit;
		$arr=['新增','約訪','面談','建議書','簽約','收費'];
		$arr_s=[1,1,2,3,4,5];
		for($i=0;$i<6;$i++){
				$data=array(
					"user_id"       =>	$user_id,
					"item_name"     =>	$arr[$i],
					"score_fix"     =>	$arr_s[$i],	
				);
				print_r($data);
				// $this->db->insert("user_schedule_item_score", $data);
		}
			
			
	

	}
	public function get_customer_name()
	{
		$this->load->model('Calendar_model');

		$data = $this->get_request_post(array(
			['login_token', '', '', 'text', 'token'],
			['name', '', 'name is not empty', 'text'],
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

		$checkType  = $this->input->post('schedule_type');
		$item_id	= $this->input->post('item_id');

		// print_r($opt);exit;
		/* 
			判斷行程type 是否為text 且 item_id 包含純文字 選項 , 
			如果有 不用傳customer_id 
			如果無 則要傳  
		*/
		if (isset($checkType) && $checkType == 'text' && isset($item_id)) {

			// 分割字串 , 
			$itemArray = explode(',', $item_id);

			//new 1209
			if($id=true){
				$customer_id = ['customer_id', '', '', 'text'];
			}
			// print_r($itemArray);exit;
			elseif (in_array(8, $itemArray))
				$customer_id = ['customer_id', '', '', 'text'];
			else
				$customer_id = ['customer_id', '', 'customer id is not empty!', 'text'];
		} else{
			$customer_id = ['customer_id', '', 'customer id is not empty', 'text'];
		}
			
		
		if ($opt == 'add') {
			// print 123;
			
			$data = $this->get_request_post(array(
				['login_token', '', '', 'text', 'token'],
				$customer_id,
				['item_id', '', 'item id is not empty', 'text'],
				['alert', 0, '', 'text'],
				['note', '', '', 'text'],
				['start_date', date('Y-m-d H:i:s'), 'start date is not empty', 'text'],
				['end_date', date('Y-m-d H:i:s', strtotime('+1 hour')), 'end date is not empty', 'text'],
				['schedule_type', '', 'schedule type is not empty', 'text'],
				['text_title', '', '', 'text'],
			));
			// print_r($data['customer_id']);exit;
			// print_r($customer_id);exit;
			if ($data['start_date'] == $data['end_date']) {
				$this->response_json(FALSE, '開始時間不能和結束時間一樣');
			}
			// print_r($customer_id);exit;
			// $res=$this->Calendar_model->is_schedule_can_add($data['login_token'], $data['start_date'], $data['end_date']);
			// print_r($res);exit;
			if ($this->Calendar_model->is_schedule_can_add($data['login_token'], $data['start_date'], $data['end_date']) > 0) {
				$this->response_json(FALSE, '此時段已有其他行程,請檢察您的行程或是其他時段', array(
					'is_overlapped' => TRUE,
					'title' 		=> '行程重疊',
				));
			}

			// print_r($)
			$data['user_id'] = $data['login_token'];
			unset($data['login_token']);

			// For 新增加的純文字功能
			if ($data['schedule_type'] === 'text') {
				$data = array_merge($data, $this->get_request_post(array(
					['text_title', '', 'text_title is not empty', 'text'],
				)));

				$data['customer_id'] = 0;
				if ($data['text_title'] === '') unset($data['text_title']);
				// $data['item_id'] = 8;
			} else if ($data['schedule_type'] !== 'customer') $this->response_json(FALSE, '輸入類型不支援');

			// $this->response_json(TRUE, '', array(
			// 	'data' => $data,
			// 	//'item'		=>$item-1,
			// 	//'settle'   => $settle,
			// ));
			if ($this->Calendar_model->add_schedule($data)) {
				$this->response_json(TRUE, '新增成功', array(
					'is_overlapped' => FALSE,
				));
			} else {
				$this->response_json(TRUE, '新增失敗', array(
					'is_overlapped' => FALSE,
				));
			}
		} elseif (is_numeric($opt)) {
	// print 1123;exit;
			// print_r($opt);exit;
			// $str="select * from  schedule where id=$opt ";
			// $res=$this->db->query($str)->row_array();
			// print_r($res);exit;
			$data = $this->get_request_post(array(
				['login_token', '', '', 'text', 'token'],
				$customer_id,
				['item_id', '', 'item id is not empty', 'text'],
				['alert', 0, '', 'text'],
				['note', 0, '', 'text'],
				['start_date', date('Y-m-d H:i:s'), 'start date is not empty', 'text'],
				['end_date', date('Y-m-d H:i:s', strtotime('+1 hour')), 'end date is not empty', 'text'],
				['schedule_type', '', 'schedule type is not empty', 'text'],
				['text_title', '', '', 'text'],
			));

			if ($data['start_date'] == $data['end_date']) {
				$this->response_json(FALSE, '開始時間不能和結束時間一樣');
			}
			// $test=$this->Calendar_model->is_schedule_can_edit($data['login_token'], $data['start_date'], $data['end_date'], $opt);
			// print_r($test);exit;
			if ($this->Calendar_model->is_schedule_can_edit($data['login_token'], $data['start_date'], $data['end_date'], $opt) > 0) {
				$this->response_json(FALSE, '此時段已有其他行程,請檢察您的行程或是其他時段', array(
					'is_overlapped' => TRUE,
					'title' 		=> '行程重疊',
				));
			}
			
			
			$where_data = array('id' => $opt, 'user_id' => $data['login_token']);
			// print_r($where_data);exit;
			unset($data['login_token']);

			// For 新增加的純文字功能
			if ($data['schedule_type'] === 'text') {
				$data = array_merge($data, $this->get_request_post(array(
					['text_title', '', 'text_title is not empty', 'text'],
				)));

				$data['customer_id'] = 0;
				if ($data['text_title'] === '') unset($data['text_title']);
				// $data['item_id'] = 8;

			} else if ($data['schedule_type'] !== 'customer') {
				$this->response_json(FALSE, '輸入類型不支援');
			} 
			// else if ($data['schedule_type']=='customer'){
			// 	// print 123;exit;
			// 	// $data['customer_id'] = 0;
			// 	print_r($data);exit;

			// }
// print_r($data);exit;
			// exit;
			if ($this->Calendar_model->modify_schedule($data, $where_data)) {
				$this->response_json(TRUE, '修改成功', array(
					'is_overlapped' => FALSE,
				));
			} else {
				$this->response_json(TRUE, '修改失敗', array(
					'is_overlapped' => FALSE,
				));
			}
		} elseif ($opt == 'del' and is_numeric($id)) {

			
			$data = $this->get_request_post(array(
				['login_token', '', '', 'text', 'token'],
			));
			// print_r($data);exit;
			$del_data = array('is_delete' => 1);
			$where_data = array('id' => $id, 'user_id' => $data['login_token']);

			if ($this->Calendar_model->modify_schedule($del_data, $where_data)) {
				$this->response_json(TRUE, '刪除成功');
			} else {
				$this->response_json(TRUE, '刪除失敗');
			}
		}
	}


	public function get_schedule()
	{
		$this->load->model('Calendar_model');
		//var_dump('11');
		$data = $this->get_request_post(array(
			['login_token', '', '', 'text', 'token'],
			['start_time', '', 'start time is not empty', 'text'],
			['end_time', '', 'end time is not empty', 'text'],
			['minor_id', 0, '', 'text'],
		));

		$where_data = array(
			'start_time'  => $data['start_time'],
			'end_time'	  => $data['end_time'],
		);

		// for 主管瀏覽
		// ['minor_id' , 0, '', 'text'],
		if ($data['minor_id'] !== 0) {
			if (!$this->User_info_model->can_view_supervisor($data['login_token']))
				$this->response_json(FALSE, '請升級後繼續');

			// 檢查是否可以檢視
			if (!$this->User_info_model->is_owner_user($data['login_token'], $data['minor_id']))
				$this->response_json(FALSE, '您無權查看此頁面');

			$data['login_token'] = $data['minor_id'];
		}


		//取得期間所有行程
		$schedule = $this->Calendar_model->get_schedule($data['login_token'], $where_data);
		//var_dump($schedule);
		// exit;


		//暫存item_name
		$result_array = array();

		$settle = [0 => ['新增' => 0], 1 => ['約訪' => 0], 2 => ['面談' => 0], 3 => ['建議書' => 0], 4 => ['簽約' => 0], 5 => ['收費' => 0], 6 => ['合計' => 0]];
		//$code =['新增','約訪','面談','建議書','簽約','收費'];
		$code[] = $this->Calendar_model->get_item_name();
		//var_dump($code[0][0]['item_name']);
		$weights = array(
			1, 1, 2, 3, 4, 5
		);
		//exit;

		$test = '';
		for ($i = 0; $i < count($schedule); $i++) {
			$tmp = '';

			//分割儲存item_id的字串
			$id_array = explode(",", $schedule[$i]['item_id']);
			//存放計分項目array
			$last_array = array();
			// foreach ($id_array as $r) {
			// 	if ($r < 7) {
			// 		array_push($last_array, $r);
			// 	}
			// }
			//var_dump($last_array);
			$count = 0;
			$length_item_name = count($code[0]);

			$length = count($id_array);
			foreach ($id_array as $item) {
				// var_dump($code[0]);
				// exit;
				for ($j = 0; $j < $length_item_name; $j++) {
					//找相符id	
					// $tmp.=$item;
					if ($code[0][$j]['id'] == $item) {


						$tmp .= $code[0][$j]['item_name'];
						if ($count != $length - 1)
							$tmp .= ',';


						if ($item <= 6 and $schedule[$i]['schedule_type'] !== 'text' and $schedule[$i]['is_complete'] == 1) {

							// 所有項目顯示個數	
							$settle[$item - 1][$code[0][$j]['item_name']] += 1;

							// 只有合計計算加權
							$settle[6]['合計'] += $weights[$item - 1];
							break;
						}
					}
				}
				$count++;
			}
			if ($schedule[$i]['schedule_type'] === 'text') {
				$schedule[$i]['name'] = $schedule[$i]['text_title'];
			}
			//var_dump($tmp);
			$schedule[$i]['item_name'] = $tmp;
		}





		$this->response_json(TRUE, '', array(
			'schedule' => $schedule,
			// 'result'   => $test,	
			'settle'   => $settle,
		));
	}

	public function schedule_complete()
	{
		$this->load->model('Calendar_model');

		$data = $this->get_request_post(array(
			['login_token', '', 'login_token is not empty', 'text', 'token'],
			['schedule_id', '', 'schedule id is not empty', 'text'],
			['is_complete', 0, '', 'text'],
		));

		$where_data = array(
			'id' 		=> $data['schedule_id'],
			'user_id' 	=> $data['login_token'],
		);

		$complete_data = array('is_complete' => $data['is_complete']);

		if ($this->Calendar_model->set_schedule_complete($where_data, $complete_data)) {
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
			['login_type', 'normal', 'login type is not empty', 'text'],
			['email', '', 'email is not empty', 'text'],
			['push_token', '', 'push token is not empty', 'text'],
			['os', '', 'OS is not empty', 'text'],
		));

		if (!is_os_type_exists($this->data['os'])) {
			$this->response_json(FALSE, '不支援的作業系統');
		}

		$is_new = FALSE;
		// 帳號驗證
		if ($this->data['login_type'] === 'normal') {
			$this->data = array_merge($this->data, $this->get_request_post(array(
				['password', '', 'password is not empty', 'text'],  	// normal 登錄用
			)));

			if (!$this->User_info_model->is_email_exist($this->data['email'])) {
				$this->response_json(FALSE, '查無此帳號');
			}
			if (!$this->User_info_model->pwd_confirm($this->data['email'], $this->data['password'])) {
				if (!$this->User_info_model->forgetpwd_confirm($this->data['email'], $this->data['password'])) {
					$this->response_json(FALSE, '密碼輸入錯誤');
				} else {
					$this->User_info_model->clear_forgetpwd($this->data['email']);
				}
			}

			$this->data['user_id'] = $this->User_info_model->get_user_id_by_email($this->data['email']);
		} elseif (is_login_type_exists($this->data['login_type'])) {
			$this->data = array_merge($this->data, $this->get_request_post(array(
				['social_id', '', 'social id is not empty', 'text'],
			)));

			$login_type = login_type_trf_db_name($this->data['login_type']);

			if ($this->User_info_model->is_email_exist($this->data['email'])) {
				if ($this->User_info_model->is_social_id_exist($this->data['email'], $login_type)) {
					if (!$this->User_info_model->social_id_confirm($this->data['email'], $login_type, $this->data['social_id'])) {
						if (!$this->User_info_model->forgetpwd_confirm($this->data['email'], $this->data['social_id'])) {
							$this->response_json(FALSE, 'Social id 不正確');
						} else {
							$this->User_info_model->clear_forgetpwd($this->data['email']);
						}
					}
				} else {
					$data[$login_type] = $this->data['social_id'];
					$this->User_info_model->add_soical_id_in_account($this->data['email'], $data);
				}

				$this->data['user_id'] = $this->User_info_model->get_user_id_by_email($this->data['email']);
			} else {
				$is_new = TRUE;
			}
		} else {
			$this->response_json(FALSE, '登錄類型不支援');
		}

		// For 社群登入, 丟去帳號註冊
		if ($is_new) {
			if ($login_type == 'mobile_id') {
				$this->data['email'] = $this->User_info_model->get_fack_email_for_moblie();
			}

			// 丟去社群註冊
			$this->data['user_id'] = $this->social_register($login_type, $this->data);

			if ($this->data['user_id'] === FALSE) {
				$this->response_json(FALSE, '註冊失敗');
			}
		}

		$this->data['token'] = $this->Jwt_model->login_token($this->data['user_id']);
		$is_face = $this->User_info_model->get_is_face($this->data['user_id']);

		unset($this->data['social_id']);
		unset($this->data['email']);
		unset($this->data['password']);
		unset($this->data['login_type']);

		// 將登入紀錄存進 login token table
		$set_token = $this->User_info_model->set_login_token($this->data, date('Y/m/d H:i:s', strtotime("-12 hours")));
		if ($set_token === FALSE) {
			$this->response_json(TRUE, '新增token失敗, 請重新嘗試');
		} elseif ($set_token === TRUE) {
			$this->response_json(TRUE, '登錄成功', array(
				'login_token'  =>	$this->data['token'],
				'is_face' 	   => ($is_face['is_face'] == 1 ? TRUE : FALSE),
			));
		} else {
			$this->response_json(TRUE, '登錄成功', array(
				'login_token'  =>	$set_token,
				'is_face' 	   => ($is_face['is_face'] == 1 ? TRUE : FALSE),
			));
		}
	}

	public function normal_register()
	{
		$data = $this->get_request_post(array(
			['name', '', '', 'text'],  	// normal 登錄用
			['email', '', 'email is not empty', 'text'],  	// normal 登錄用, 當帳號
			['password', '', 'password is not empty', 'text'],  	// normal 登錄用
			['password_confirm', '', 'password confirm is not empty', 'text'],
			['is_face', '', 'is face is not empty', 'text'],
		));

		if ($this->User_info_model->is_email_exist($data['email']))
			$this->response_json(FALSE, '此帳號已被註冊');

		if ($data['password'] !== $data['password_confirm'])
			$this->response_json(FALSE, '兩次輸入密碼不相同');

		$data['password'] = $this->encryption->encrypt(md5($data['password']));
		unset($data['password_confirm']);

		if (($user_id = $this->User_info_model->add_register($data)) != FALSE) {
			// $this->set_schedule_item_in($user_id);//add 0927 預設6個配分項目
			$this->account_init($user_id);
			$this->response_json(TRUE, "註冊成功");
		} else {
			$this->response_json(FALSE, '發生錯誤,註冊失敗');
		}
	}

	private function social_register($login_type, $data)
	{
		$res_data[$login_type]  = $data['social_id'];
		$res_data['email']		= $data['email'];
		$res_data['is_face']	= 1;

		$user_id = $this->User_info_model->add_register($res_data);
		$this->account_init($user_id);
		return $user_id;
	}
	// send mail test
	public function send_mail_test(){

		$this->load->model('Mail_model');
		$this->Mail_model->send_mail('sss9611300@yahoo.com.tw', '測試', '暫時密碼通知');
	}

	public function forget_pwd()
	{
		$data = $this->get_request_post(array(
			['email', '', 'email is not empty', 'text'],
		));

		if ($this->User_info_model->is_email_exist($data['email'])) {
			$this->load->model('Mail_model');

			$pwd = $this->User_info_model->set_forgetpwd($data['email']);
			$body = '您的一次性密碼為 : [' . $pwd . '] 請登入後更新您的密碼。';
			$test = $this->db->last_query();
			$this->Mail_model->send_mail($data['email'], $body, '暫時密碼通知');
			$this->Mail_model->send_mail('anbonbackend2021@gmail.com', $body, '暫時密碼通知');
			//寫檔log
			$date = date("Y-m-d h:i:s");
			$fp = fopen('logForMail.txt', 'a'); //opens file in append mode  
			fwrite($fp, $data['email'] . PHP_EOL);
			fwrite($fp, 'date : ' . $date . PHP_EOL);
			fclose($fp);
			$this->response_json(TRUE, '成功');
		} else {
			$this->response_json(FALSE, '查無此email');
		}
	}
	/* Register or Login - End */
	public function test_pwd()
	{
		// $str="1234";
		// $db_pwd="b39f6008ce8c0e072102927ed791f0d9997e9d99626cba813648ab8e961fec7ec59c0250fd7e4df1b67872f929a55790977be16f981179a41694ccebbe2c93c3kTSxrYBXNEkIXmACmxc/tVddAOnBl5HMpJI1KE/P0aA7qeDs61ervN7j4JoTwosL+SrvutGSjWXsAEcCZV0HVg==";

		// // $this->encryption->encrypt(md5($str));

		// // echo $this->encryption->encrypt(md5($str));

		// if($this->encryption->decrypt($db_pwd) == md5($str)){
		// 	echo "equal";
		// }else{
		// 	echo "no ";

		// }




	}


	/* Supervisor - Start */

	public function supervisor_view()
	{
		$data = $this->get_request_post(array(
			['login_token', '', 'login_token is not empty', 'text', 'token'],
			['keyword', '', '', 'text'],
			['order', 'decrease', '', 'text'],
		));
		//////////////////
		// 先判斷是不是VIP，再判斷進階會員
		if($this->User_info_model->can_view_full($data['login_token'])){
			if ($data['order'] === 'increase') $order_by = 'ASC';
			else $order_by = 'DESC';

			$this->response_json(TRUE, '成功', array(
				'data' => $this->User_info_model->get_supervisor_list($data['login_token'], $data['keyword'], $order_by)
			));

		}elseif($this->User_info_model->can_view_part($data['login_token'])){
			if ($data['order'] === 'increase') $order_by = 'ASC';
			else $order_by = 'DESC';

			$this->response_json(TRUE, '成功', array(
				'data' => $this->User_info_model->get_part_list($data['login_token'], $data['keyword'], $order_by,6)
			));

		}else{
			if ($data['order'] === 'increase') $order_by = 'ASC';
			else $order_by = 'DESC';

			$this->response_json(TRUE, '成功', array(
				'data' => $this->User_info_model->get_part_list($data['login_token'], $data['keyword'], $order_by,1)
			));
			// $this->response_json(FALSE, '您無權查看此頁面');
		}

		//////////////////
	
		

		if ($this->User_info_model->can_view_supervisor($data['login_token'])) {
			if ($data['order'] === 'increase') $order_by = 'ASC';
			else $order_by = 'DESC';

			$this->response_json(TRUE, '成功', array(
				'data' => $this->User_info_model->get_supervisor_list($data['login_token'], $data['keyword'], $order_by)
			));
		} else {
			$this->response_json(FALSE, '您無權查看此頁面');
		}
	}

	public function chat_for_minor()
	{
		$data = $this->get_request_post(array(
			['login_token', '', 'login_token is not empty', 'text', 'token'],
			['minor_id', '', 'minor id is not empty', 'text'],
			['text', '', 'text is not empty', 'text'],
		));

		if ($this->User_info_model->is_owner_user($data['login_token'], $data['minor_id'])) {
			$res = $this->User_info_model->add_chat_for_minor($data['login_token'], $data['minor_id'], $data['text']);
			$this->response_json(TRUE, '留言成功', array('data' => $res));
		} else $this->response_json(TRUE, '您無權留言給此使用者');
	}

	public function add_condition()
	{
		$this->load->model('Customer_manage_model', 'Customer');
		$data 	= $this->get_request_post(array(
			['login_token', '', 'login_token is not empty', 'text', 'token'],
			['minor_id', 	'', 'minor id is not empty', 	'text'],
			['title', 		'', 'title is not empty', 		'text'],
			['status', 	'', '', 						'array'],
			['source_id', 	'', '', 						'array'],
			['relation', 	'', '', 						'array'],
			['level', 		'', '', 						'array'],
		));

		if (!$this->User_info_model->can_view_supervisor($data['login_token']))
			$this->response_json(FALSE, '請升級後繼續');
		if (!$this->User_info_model->is_owner_user($data['login_token'], $data['minor_id']))
			$this->response_json(FALSE, '您無權查看此頁面');

		// $this->response_json(TRUE, '成功', array(
		// 		'data' => $data
		// ));

		$where 	= $this->Customer->compose_condition_where($data);
		$sql 	= $this->Customer->compose_condition_sql($where);
		$get_selected = $this->Customer->compose_condition_selected($data['login_token'], $data);

		$this->Customer->add_condition($data['login_token'], $data['minor_id'], $data['title'], $sql, $get_selected);

		$this->response_json(TRUE, '成功', array(
			'data' => $sql
		));
	}

	public function get_conditions()
	{
		$this->load->model('Customer_manage_model', 'Customer');
		$data 	= $this->get_request_post(array(
			['login_token', '', 'login_token is not empty', 'text', 'token'],
			['minor_id', 	'', 'minor id is not empty', 	'text'],
		));

		if (!$this->User_info_model->can_view_supervisor($data['login_token']))
			$this->response_json(FALSE, '請升級後繼續');
		if (!$this->User_info_model->is_owner_user($data['login_token'], $data['minor_id']))
			$this->response_json(FALSE, '您無權查看此頁面');

		$res = $this->Customer->get_conditions($data['login_token'], $data['minor_id']);
		$this->response_json(TRUE, '成功', array(
			'data' => $res
		));
	}



	//	編輯業務概況
	public function edit_condition()
	{
		$this->load->model('Customer_manage_model', 'Customer');
		$data 	= $this->get_request_post(array(
			['login_token', '', 'login_token is not empty', 'text', 'token'],
			['condition_id', '', 'condition id is not empty', 'text'],
			['minor_id', 	'', 'minor id is not empty', 	'text'],
			['title', 		'', 'title is not empty', 		'text'],
			['status', 	'', '', 						'array'],
			['source_id', 	'', '', 						'array'],
			['relation', 	'', '', 						'array'],
			['level', 		'', '', 						'array'],
		));

		if (!$this->User_info_model->can_view_supervisor($data['login_token']))
			$this->response_json(FALSE, '請升級後繼續');
		if (!$this->User_info_model->is_owner_user($data['login_token'], $data['minor_id']))
			$this->response_json(FALSE, '您無權查看此頁面');

		$where 	= $this->Customer->compose_condition_where($data);
		$sql 	= $this->Customer->compose_condition_sql($where);
		$get_selected = $this->Customer->compose_condition_selected($data['login_token'], $data);

		$this->Customer->update_condition($data['condition_id'], $data['login_token'], $data['minor_id'], $data['title'], $sql, $get_selected);

		$this->response_json(TRUE, '成功', array(
			'data' => $sql
		));
	}

	public function del_condition()
	{
		$this->load->model('Customer_manage_model', 'Customer');
		$data 	= $this->get_request_post(array(
			['login_token', '', 'login_token is not empty', 'text', 'token'],
			['condition_id', '', 'condition id is not empty', 'text'],
			['minor_id', 	'', 'minor id is not empty', 	'text'],
		));

		if (!$this->User_info_model->can_view_supervisor($data['login_token']))
			$this->response_json(FALSE, '請升級後繼續');
		if (!$this->User_info_model->is_owner_user($data['login_token'], $data['minor_id']))
			$this->response_json(FALSE, '您無權查看此頁面');

		$this->Customer->del_condition($data['condition_id'], $data['login_token'], $data['minor_id']);

		$this->response_json(TRUE, '成功');
	}

	public function get_condition()
	{
		$this->load->model('Customer_manage_model', 'Customer');
		$this->load->helper('tw_zipcode');
		$data 	= $this->get_request_post(array(
			['login_token', '', 'login_token is not empty', 'text', 'token'],
			['condition_id', '', 'condition id is not empty', 'text'],
			['minor_id', 	'', 'minor id is not empty', 	'text'],
			['page', 		1,  '', 						'text'],
		));

		if (!$this->User_info_model->can_view_supervisor($data['login_token']))
			$this->response_json(FALSE, '請升級後繼續');
		if (!$this->User_info_model->is_owner_user($data['login_token'], $data['minor_id']))
			$this->response_json(FALSE, '您無權查看此頁面');

		$total 			 = $this->Customer->get_condition_count($data['condition_id'], $data['login_token'], $data['minor_id']);
		$total_page 	 = $this->Customer->compute_total_page($total);
		$customer_info 	 = $this->Customer->get_condition($data['condition_id'], $data['login_token'], $data['minor_id'], $data['page']);
		$customer_source = $this->Customer->get_user_all_source($data['minor_id']);

		// $this->response_json(TRUE, '', array(
		// 		'total'					=> $total,				
		// 		'customer_info' 	 	=> $customer_info,
		// 		'total_page'			=> $total_page ,
		// 		'customer_source'		=> $customer_source
		// ));



		// 舊的寫法複製貼上 ----------------------

		if ($total == 0) {
			$this->response_json(TRUE, '', array(
				'total' 	 	=> 0,
				'total_page' 	=> 0,
				'now_page' 		=> 1,
				'data' 			=> [],
			));
		}

		if (!isset($customer_info)) $customer_info = array();
		if (!isset($customer_source)) $customer_source = array();

		$customers = array();

		foreach ($customer_info as $key => $value) {
			for ($i = 0; $i < count($customer_source); $i++) {
				if ($customer_source[$i]['id'] == $value['source_id'])
					$value['source'] = $customer_source[$i]['source_name'];
			}

			if ($value['photo'] != NULL) $value['photo'] = base_url() . $value['photo'];

			$field = $this->Customer->get_customer_field_value_by_customer($value['id']);

			//	取得熱絡關係
			$connection = $this->Customer->get_connection_level($data['login_token'], $value['id']);

			// $birthday_str = '生日  ' . (empty($value['birthday']) ? '' : $value['birthday']);
			unset($extra_field);
			// $extra_field[] = $birthday_str;
			$extra_field = array();

			for ($i = 0; $i <= count($field); $i++) {
				if (empty($field[$i]['field_value'])) continue;

				$extra_field[$i + 1] = $field[$i]['field_name'] . '  ' . $field[$i]['field_value'];
			}

			$customers[$key] =  [
				'id' 			=> $value['id'],
				'customer_no' 	=> $value['customer_no'],
				'connection' 	=> $connection,
				'name' 			=> $value['name'],
				'photo' 		=> $value['photo'],
				'status' 		=> customer_status_tf($value['status']),
				'level' 		=> $value['level'],
				'relation'  	=> $value['relation'],
				'source' 		=> $value['source'],
				'extra_field' 	=> $extra_field,
				'text' 			=> $value['text'],
				'birthday' 		=> $value['birthday'],
				'is_master_note' => (empty($value['n_note']) ? '主管備註' : '已備註'),
				'master_note' 	=> (empty($value['n_note']) ? '' : $value['n_note']),
			];
		}

		$this->response_json(TRUE, '', array(
			'total' 	 	=> $total,
			'total_page' 	=> $total_page,
			'now_page' 		=> $data['page'],
			'data' 			=> $customers,
		));

		// --------------------
	}

	public function edit_condition_note()
	{
		$this->load->model('Customer_manage_model', 'Customer');
		$data = $this->get_request_post(array(
			['login_token', '', 'login_token is not empty', 'text', 'token'],
			['minor_id', 	'', 'minor id is not empty', 	'text'],
			['customer_id', '', 'customer id is not empty', 'text'],
			['note', 		'', '', 						'text'],
		));
		$master 	= $this->User_info_model->get_user_info($data['login_token']);
		$customer 	= $this->Customer->get_mgr_customer_info($data['minor_id'], $data['customer_id']);

		if (!$this->User_info_model->can_view_supervisor($data['login_token']))
			$this->response_json(FALSE, '請升級後繼續');
		if (!$this->User_info_model->is_owner_user($data['login_token'], $data['minor_id']))
			$this->response_json(FALSE, '您無權查看此頁面');

		$data['user_id'] = $data['login_token'];
		unset($data['login_token']);

		if ($this->Customer->del_condition_note($data['user_id'], $data['minor_id'], $data['customer_id']))
			$this->Customer->add_condition_note($data);

		$subject = $master['name'] . ' 給你備註 客戶 No.' . $customer['customer_no'] . '-' . $customer['name'] . ' :「' . $data['note'] . '」';
		$this->db->insert('notice', array(
			'send_id' => $data['user_id'], 'receive_id' => $data['minor_id'], 'subject' => $subject, 'class' => 'supervisor'
		));

		$this->response_json(TRUE, '成功');
	}

	public function get_condition_filter()
	{
		$this->load->model('Customer_manage_model', 'Customer');
		$data = $this->get_request_post(array(
			['login_token', '', 'login_token is not empty', 'text', 'token'],
			['minor_id', 	'', 'minor id is not empty', 	'text'],
			['condition_id', '', 'customer id is not empty', 'text'],
		));

		$res = $this->Customer->get_minor_condition($data['login_token'], $data['minor_id'], $data['condition_id']);

		// print_r($res);exit;
		$this->response_json(TRUE, '成功', $res);
	}

	public function click_direct()
	{
		$data = $this->get_request_post(array(
			['login_token', '', 'login_token is not empty', 'text', 'token'],
		));

		$click_direct 	= $this->User_info_model->get_click_direct($data['login_token']);
		$click_num 		= explode('=', $click_direct);

		if ($this->User_info_model->can_view_supervisor($data['login_token'])) {
			$this->response_json(TRUE, '開啟google map導航');
		}

		if (strtotime($click_num[0]) < strtotime(date('Y-m-d'))) {
			$click_direct = date('Y-m-d') . '=1';
			$click_num[1] = 1;
		} else {
			if ($click_num[1] !== '3') $click_num[1] += 1;
			else {
				$this->response_json(FALSE, '每日使用次數已滿, 請加值後繼續');
			}
			$click_direct = $click_num[0] . '=' . $click_num[1];
		}
		$this->User_info_model->set_click_direct($data['login_token'], $click_direct);
		$this->response_json(TRUE, '開啟google map導航\n使用後剩餘次數 : ' . $click_num[1] . '/3');
	}
	/* Supervisor - End */



	/* Private function - Start */

	// For year goal and month goal
	private function count_item_goal_percent($datas)
	{



		foreach ($datas as $key => $value) {
			if (isset($value['total_num']) and (!empty($value['total_num'])) and ($value['total_num'] != 0)) {
				$total = intval($value['total_num']);
				$now   = intval($value['now_num']);
				// $datas[$key]['percent'] = ($value['now_num'] / $value['total_num']) * 100;
				if (($now / $total) * 100 > 100)
					$res = 100.0;
				else
					$res = ($now / $total) * 100;
				$datas[$key]['percent'] = sprintf("%.2f", $res);
			} else
				$datas[$key]['percent'] = 0;
		}

		return $datas;
	}

	// For year goal and month goal
	private function count_total_percent($datas)
	{
		$count = count($datas);

		// $all_t = 1.0;
		// $all_n = 0.0;


		// foreach ($datas as $value) {
		// 	$all_t += $value['total_num'];
		// 	$all_n += $value['now_num'];
		// }

		// if(($all_n/$all_t)*100>100)
		// 	$res = 100.0;
		// else
		// 	$res = ($all_n/$all_t)*100;	
		// return sprintf("%.2f", $res);
		$total = 0;
		$res   = 0;
		foreach ($datas as $key => $value) {
			$total += $datas[$key]['percent'];
		}
		if ($count > 0)
			$res  = $total / $count;
		else
			$res  = 0;
		return sprintf("%.2f", $res);
	}
	/* Private function - Start */


	/* For customer only (no work in system) - Start */

	// 要用再打開
	private function set_complete_customer_numbersssssssssssssssssssssssssss()
	{
		$res = $this->db->select('id, customer_no')
			->from('customer_mgr')
			->where(array('user_id' => 16))
			->order_by('create_date ASC')
			->get()
			->result_array();

		$i = 1;
		foreach ($res as $key => $value) {
			$this->db->where(array('user_id' => 16, 'id' => $value['id']))->update('customer_mgr', array('customer_no' => $i));
			$i++;
		}
	}
	/* For customer only (no work in system) - End */


	/* Account init - Start */

	public function account_init($user_id = 1)
	{
		if (!is_numeric($user_id)) return FALSE;

		$goal_field 	   = array('user_id' => NULL, 'name' => NULL, 'year' => date('Y'), 'total_money' => 0, 'month' => TRUE);
		$goal_name_value   = array('壽險期繳追蹤名單', '躉繳', 'A&H');

		$year_goal_field   = array('user_id' => NULL, 'name' => NULL, 'year' => date('Y'), 'now_num' => 0, 'total_num' => 0);
		$year_goal_value   = array('FYP', 'FYC', '增員');

		$month_goal_field  = array('user_id' => NULL, 'name' => NULL, 'year' => date('Y'), 'now_num' => 0, 'total_num' => 0, 'month' => TRUE);
		$fixed_month_goal  = array('FYP', 'FYC', '增員');

		// Start
		$users = array(array('id' => $user_id));

		//
		$fixed_goal_data  = $this->_create_fixed_arr($goal_field, $goal_name_value, $users);
		$fixed_year_data  = $this->_create_fixed_arr($year_goal_field, $year_goal_value, $users);
		$fixed_month_data = $this->_create_fixed_arr($month_goal_field, $fixed_month_goal, $users);

		$this->User_info_model->goal_init_insert($fixed_goal_data);
		$this->User_info_model->year_init_insert($fixed_year_data);
		$this->User_info_model->month_init_insert($fixed_month_data);

		// $this->response_json(TRUE, '成功');
		return TRUE;
	}

	private function _create_fixed_arr($field_arr, $value_arr, $users)
	{
		$all_fixed = array();
		$fixed_arr = array();
		for ($i = 0; $i < count($value_arr); $i++) {
			$fixed_arr[$i] = $field_arr;
			$fixed_arr[$i]['name'] = $value_arr[$i];
		}

		foreach ($users as $u_key => $u_value) {
			$temp_arr = array();
			foreach ($fixed_arr as $f_key => $f_value) {
				if (isset($field_arr['month'])) {
					for ($i = 1; $i <= 12; $i++) {
						$temp_arr = $f_value;
						$temp_arr['user_id'] = $u_value['id'];
						$temp_arr['month']   = $i;
						$temp_arr['sort']    = $this->_set_sort($temp_arr['name']);

						$all_fixed[] = $temp_arr;
					}
				} else {
					$temp_arr = $f_value;
					$temp_arr['user_id'] = $u_value['id'];
					$temp_arr['sort']    = $this->_set_sort($temp_arr['name']);

					$all_fixed[] = $temp_arr;
				}
			}
		}

		return $all_fixed;
	}

	private function _set_sort($name)
	{
		switch ($name) {
			case '壽險期繳追蹤名單':
				return 3;

			case '躉繳':
				return 2;

			case 'A&H':
				return 1;

			case 'FYP':
				return 3;

			case 'FYC':
				return 2;

			case '增員':
				return 1;


			default:
				return 0;
		}
	}
	/* Account init - Start */


	/* Base function - Start */

	private function get_request_post(array $key_array)
	{

		$tmp = '';
		foreach ($key_array as $value) {
			$data[$value[0]] = $this->input->post($value[0]);
			// print_r($key_array);exit;
			// var_dump($key_array);exit;
			if (isset($value[4]) and $value[4] == 'token') {
				$header = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];

				$token = sscanf($header, 'Sales %s');

				$user = $this->Jwt_model->verify_token($token[0]);

				if ($user['status']) {
					$data['login_token'] = $user['user_id'];
					$this->Flow_record_model->set_flow_record($this->data['active'], $this->get_client_ip(), $user['user_id']);
				} else {
					$this->response_json(FALSE, '此 token 已過期,請重新登入', array(
						'url' => $this->login_url,
					));
				}
			} elseif (!$data[$value[0]]) {
				$data[$value[0]] = $value[1];
			} elseif ($value[0] == 'login_token') {
				//丟去 check token 判斷此token 是否過期
				$user = $this->Jwt_model->verify_token($data[$value[0]]);
				if ($user['status']) {
					$data[$value[0]] = $user['user_id'];
					$this->Flow_record_model->set_flow_record($this->data['active'], $this->get_client_ip(), $user['user_id']);
				} else {
					$this->response_json(FALSE, '此 token 已過期,請重新登入', array(
						'url' => $this->login_url,
					));
				}
			}
			if ($value[2] != '') {
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

		foreach ($fields as $field) {
			$data[$field] = $user[$field];
		}
		if ($user['avatar'] != '') {
			$data['avatar'] = base_url() . $user['avatar'];
		}

		return $data;
	}
	/* Base function - End */



	public function test_stastics()
	{
		$this->load->model('Calendar_model');
		$this->load->model('Customer_manage_model');

		$data = $this->get_request_post(array(
			['login_token', 	'', 'token is not empty', 'text', 'token'],
			['start_time', '', 'start time is not empty', 'text'],
			['end_time', '', 'end time is not empty', 'text'],
			['minor_id', 0, '', 'text'],
		));

		// for 主管瀏覽
		if ($data['minor_id'] !== 0) {
			// 檢查是否可以檢視
			if (!$this->User_info_model->is_owner_user($data['login_token'], $data['minor_id']))
				$this->response_json(FALSE, '您無權查看此頁面');

			$data['login_token'] = $data['minor_id'];
		}

		$user = $this->User_info_model->get_user_info($data['login_token']);

		$where_data = array(
			'start_time'  => $data['start_time'],
			'end_time'	  => $data['end_time'],
		);
		$schedule = $this->Calendar_model->test_get_schedule($data['login_token'], $where_data);
		$active = array();
		$settle = [0 => ['新增' => 0], 1 => ['約訪' => 0], 2 => ['面談' => 0], 3 => ['建議書' => 0], 4 => ['簽約' => 0], 5 => ['收費' => 0], 6 => ['合計' => 0]];
		$item_array = $this->Calendar_model->get_schedule_item_name();
		$score_fix = array(1, 1, 2, 3, 4, 5);

		// var_dump($settle);
		// exit;
		// $this->response_json(TRUE, '成功', array(
		// 	'res' => $schedule,
		// 	'item'=> $item_array
		// ));

		//多個item_id

		for ($i = 0; $i < count($schedule); $i++) {


			$id_array = explode(',', $schedule[$i]['item_id']);

			foreach ($id_array as $ia) {

				// var_dump($ia);
				// exit;
				if ($ia < 7 and $schedule[$i]['schedule_type'] !== 'text') {

					// var_dump( $item_array[$ia-1]['item_name']);

					// exit;
					$settle[$ia - 1][$item_array[$ia - 1]['item_name']]++;


					$settle[6]['合計']++;
				}
			}
		}

		// For 前端要求的格式..
		for ($i = 0; $i < 7; $i++) {
			foreach ($settle[$i] as $key => $value) {
				$active[$i]['title']  = $key;
				$active[$i]['number'] = $value;
				if ($i == 6) {
					$total = 0;
					for ($j = 0; $j < 6; $j++) {
						$total += $active[$j]['percent'];
					}
					$active[$i]['percent']  = $total;
				} else {
					$active[$i]['percent']  = $value * $score_fix[$i];
				}
			}
		}

		// $this->response_json(
		// 	TRUE,'',array(
		// 		'active',$active
		// 	)
		// );

		$status  = $this->Customer_manage_model->get_all_customer_manage_status($data['login_token']);
		$settle2 = [0 => ['告知' => 0], 1 => ['約訪' => 0], 2 => ['拜訪' => 0], 3 => ['建議' => 0], 4 => ['成交' => 0]];
		$result = array_reduce($status, function ($result, $value) {
			return array_merge($result, array_values($value));
		}, array());
		$result = array_count_values($result);

		// For 前端要求的格式..
		$work = array();
		$settle_total = 0;
		for ($i = 0; $i < count($settle2); $i++) {
			foreach ($settle2[$i] as $key => $value) {
				$work[] = array('title' => $key, 'number' => (isset($result[status_ch_tf_en($key)]) ? $result[status_ch_tf_en($key)] : 0));
				$settle_total += (isset($result[status_ch_tf_en($key)]) ? $result[status_ch_tf_en($key)] : 0);
			}
		}
		for ($j = 0; $j < count($settle2); $j++) {
			if ($settle_total == 0) $work[$j]['percent'] = 0;
			else $work[$j]['percent'] = round($work[$j]['number'] / $settle_total * 100);
		}

		$this->response_json(TRUE, '', array(
			'person' 	=> $user['name'],
			'customer_num' 	=> count($status),
			'work' 		=> $work,
			'active'    => $active,
		));
	}

	public function test_goal()
	{
		$this->data = $this->get_request_post(array(
			['login_token', '', 'token is not empty', 'text'],
			['year', '', 'year is not empty', 'text'],
			['minor_id', 0, '', 'text'],
		));

		// for 主管瀏覽
		if ($this->data['minor_id'] !== 0) {
			if (!$this->User_info_model->can_view_supervisor($this->data['login_token']))
				$this->response_json(FALSE, '請升級後繼續');

			// 檢查是否可以檢視
			if (!$this->User_info_model->is_owner_user($this->data['login_token'], $this->data['minor_id']))
				$this->response_json(FALSE, '您無權查看此頁面');

			$this->data['login_token'] = $this->data['minor_id'];
		}

		$result = $this->User_info_model->get_year_goal_data($this->data['login_token'], $this->data['year']);


		$this->response_json(TRUE, '成功', array(
			'res'	=>	$result
		));

		if ($result == FALSE)
			$result = array();

		for ($i = 0; $i < count($result); $i++) {
			unset($result[$i]['user_id']);
			unset($result[$i]['year']);
			unset($result[$i]['create_date']);
			unset($result[$i]['is_delete']);
		}

		if ($result == FALSE) {
			$this->status = TRUE;
			$this->msg = '取得成功';
			$data = array();
			$total_percent = 0;
		} else {
			$this->status = TRUE;
			$this->msg = '取得成功';
			$data = $this->count_item_goal_percent($result);
			$total_percent = $this->count_total_percent($result);
		}



		$this->response_json($this->status, $this->msg, array(
			'data' => $data,
			'total_percent' => $total_percent,
		));
	}


	public function test_edit()
	{

		$this->load->model('Customer_manage_model');
		$data = $this->get_request_post(array(
			['login_token', '', 		'token is not empty', 		'text'],
			['option', '', 		'option is not empty', 		'text'],
			['name', '', 		'name is not empty', 		'text'],
			['source_id', '', 		'source_id is not empty', 	'text'],
			['relation', '', 		'relation is not empty', 	'text'],
			['level', '', 		'level is not empty', 		'text'],
			['job', NULL, 	'', 'text'],
			['birthday', NULL, 	'', 'text'],
			['city', 0, 		'', 'text'],
			['dist', 100, 		'', 'text'],
			['address', NULL, 	'', 'text'],

			['formatted_address', NULL, 	'', 'text'],
			['global_code', NULL, 	'', 'text'],
			['location_lat', NULL, 	'', 'text'],
			['location_lng', NULL, 	'', 'text'],
			['geocoding_result', NULL, 	'', 'text'],

			['text', NULL, 	'', 'text'],
			['photo', NULL, 	'', 'text'],
			['extra_field', json_encode(array()),  '', 'array'],
		));
		// $this->response_json(true, 'test', compact('data'));
		//$data['birthday'] = date('Y-m-d', strtotime("$data[birthday] + 11 year"));
		$option = $data['option'];
		$extra_field = json_decode($data['extra_field'], TRUE);

		if ($option == 'add' or $option == 'edit') {
			unset($data['option']);
			$data['user_id'] = $data['login_token'];
			unset($data['login_token']);
			unset($data['extra_field']);
			if (empty($data['photo']) or $data['photo'] == NULL) unset($data['photo']);
		}

		if ($option == 'edit') {
			$id = $this->get_request_post(array(
				['id', '', 'id is not empty', 'text'],
			));
			$data = array_merge($data, $id);
		} elseif ($option == 'add') {
			if (!$this->User_info_model->can_view_supervisor($data['user_id'])) {
				if (!$this->Customer_manage_model->is_customers_num_less_200($data['user_id'])) {
					$this->response_json(FALSE, '客戶管理名單已超過200人次, 新增失敗, 請加值後繼續');
				}
			}
			$data['customer_no'] = $this->Customer_manage_model->get_customer_num($data['user_id']) + 1;
		}
		// $res = $this->Customer_manage_model->get_mgr_customer_info($data['user_id'], $data['id']);
		// var_dump($data);
	}





	public function test_fast()
	{
		$this->load->model('Customer_manage_model');
		$this->load->helper('tw_zipcode');
		$data = $this->get_request_post(array(
			['login_token', '', 'token is not empty', 'text'],
			['index', '', 'index is not empty', 'text'],
			['filter', json_encode(array()), '', 'text'],
			['page', 1, '', 'text'],
			['minor_id', 0, '', 'text'],
			['keyword', '', '', 'text'],
			['include', '', '', 'text']
		));


		if (!(is_string($data['filter']) and ((json_last_error() == JSON_ERROR_NONE) ? TRUE : FALSE)))
			$this->response_json(FALSE, 'filter 類型不支援');

		$where_data = array();
		$join_note = FALSE;
		$join_schedule = FALSE;
		$schedule_where = '';
		foreach (json_decode($data['filter'], TRUE) as $key => $value) {
			if (!empty($value) and $key !== 'status' and $key !== 'is_master_note' and $key !== 'connection')
				$where_data['C.' . $key] = $value;

			if (!empty($value) and $key === 'status')
				$where_data['C.' . $key] = customer_status_tf_str($value);

			if (!empty($value) and $key === 'is_master_note') {
				$where_data['N.note !='] = '';
				$join_note = TRUE;
			}

			if (!empty($value) and $key === 'connection') {
				$join_schedule = TRUE;
				if ($value === 'red') {
					$schedule_where = ' AND end_date >= "' . Date('Y-m-d H:i:s', strtotime('-1 month')) . '" AND end_date <= "' . Date('Y-m-d H:i:s') . '"';
					$where_data['S.end_date <='] = Date('Y-m-d H:i:s');
				} else if ($value === 'orange') {
					$schedule_where = ' AND end_date >= "' . Date('Y-m-d H:i:s', strtotime('-6 month')) . '" AND end_date <= "' . Date('Y-m-d H:i:s', strtotime('-1 month')) . '"';
					$where_data['S.end_date <='] = Date('Y-m-d H:i:s', strtotime('-1 month'));
				} else if ($value === 'yellow') {
					$schedule_where = ' AND end_date >= "' . Date('Y-m-d H:i:s', strtotime('-1 year')) . '" AND end_date <= "' . Date('Y-m-d H:i:s', strtotime('-6 month')) . '"';
					$where_data['S.end_date <='] = Date('Y-m-d H:i:s', strtotime('-6 month'));
				} else if ($value === 'blue') {
					$schedule_where = ' AND end_date <= "' . Date('Y-m-d H:i:s', strtotime('-1 year')) . '"';
					$where_data['S.end_date <='] = Date('Y-m-d H:i:s', strtotime('-1 year'));
				}
			}
		}

		$like_data = array('C.name' => $data['keyword']);

		// for 主管瀏覽
		if ($data['minor_id'] !== 0) {
			// 檢查是否可以檢視
			if (!$this->User_info_model->is_owner_user($data['login_token'], $data['minor_id']))
				$this->response_json(FALSE, '您無權查看此頁面');

			$data['login_token'] = $data['minor_id'];
		}


		//指定index
		$index = $data['index'];
		$lower_data = array_merge($where_data, array(
			'C.user_id' 	=> $data['login_token'],
			'C.is_delete' 	=> 0,
			'C.customer_no < ' => $index
		));



		//取得index是第幾筆
		$max = $this->Customer_manage_model->get_max_no($data['login_token']);
		$lower_count = $this->Customer_manage_model->get_user_all_customer_num($data['login_token'], $lower_data, $like_data, $join_note, $join_schedule, $schedule_where);
		// $this->response_json(TRUE, '', array(
		// 	'low' 	 => $lower_count,

		// ));
		$total_data = array_merge($where_data, array(
			'C.user_id' 	=> $data['login_token'],
			'C.is_delete' 	=> 0,
			//'C.customer_no < '=>$index
		));


		$total = $this->Customer_manage_model->get_user_all_customer_num($data['login_token'], $total_data, $like_data, $join_note, $join_schedule, $schedule_where);
		$total_page  = $this->Customer_manage_model->compute_total_page($total);

		// $this->response_json(TRUE, '', array(
		// 	'total' 	 => $total,

		// ));




		$order_by = ' `customer_no` ASC';
		$bigger_data = array_merge($where_data, array(
			'C.user_id' 	=> $data['login_token'],
			'C.is_delete' 	=> 0,
			"C.customer_no >= "	=> $index
		));

		$user_customer_info   = $this->Customer_manage_model->get_user_all_customer($data['login_token'], $bigger_data, $order_by, $like_data, $data['page'], $total_page, $join_note, $join_schedule, $schedule_where, $total);
		$user_customer_source = $this->Customer_manage_model->get_user_all_source($data['login_token']);
		// $user_customer_field  = $this->Customer_manage_model->get_user_all_field_name($data['login_token']);

		$next_no = 0;
		if ($index > $max)
			$this->response_json(FALSE, '超過資料筆數');

		if (!isset($user_customer_info))
			$user_customer_info = array();

		if (!isset($user_customer_source))
			$user_customer_source = array();

		$customer = array();
		$count = 0;
		foreach ($user_customer_info as $key => $value) {

			if ($count == 25) {
				$next_no = $value['customer_no'];
				break;
			}

			for ($i = 0; $i < count($user_customer_source); $i++) {
				if ($user_customer_source[$i]['id'] == $value['source_id'])
					$value['source'] = $user_customer_source[$i]['source_name'];
			}

			if ($value['photo'] != NULL)
				$value['photo'] = base_url() . $value['photo'];

			$field = $this->Customer_manage_model->get_customer_field_value_by_customer($value['id']);
			$connection = $this->Customer_manage_model->get_connection_level($data['login_token'], $value['id']);

			// $birthday_str = '生日  ' . (empty($value['birthday']) ? '' : $value['birthday']);
			unset($extra_field);
			// $extra_field[] = $birthday_str;
			$extra_field = array();
			for ($i = 0; $i < count($field); $i++) {
				if (empty($field[$i]['field_value'])) continue;

				$extra_field[$i] = $field[$i]['field_name'] . '  ' . $field[$i]['field_value'];
			}

			// $c_d = get_addr_str($value['city'],$value['dist']);
			$customer[$key] =  [
				'id' 			=> $value['id'],
				'customer_no' 	=> $value['customer_no'],
				'connection' 	=> $connection,
				'name' 			=> $value['name'],
				'photo' 		=> $value['photo'],
				'status' 		=> customer_status_tf($value['status']),
				'level' 		=> $value['level'],
				'relation'  	=> $value['relation'],
				'source' 		=> $value['source'],
				'extra_field' 	=> $extra_field,
				'city' 			=> $value['city'],
				'dist' 			=> $value['dist'],
				'birthday' 		=> $value['birthday'],
			];


			$count++;
		}



		$this->response_json(TRUE, '', array(
			'total' 	 => $total,
			'total_page' => $total_page,
			'now_page' => ceil(($lower_count + 1) / 25),
			'data' => $customer,
			'sad' => $join_note,
			'next_no' =>	$next_no
		));
	}


	public function test_c()
	{

		//require('./vendor/autoload.php');
		$this->load->model('Customer_manage_model');
		$data = $this->get_request_post(array(
			['login_token', '', 		'token is not empty', 		'text'],
			['option', '', 		'option is not empty', 		'text'],
			['name', '', 		'name is not empty', 		'text'],
			['source_id', '', 		'source_id is not empty', 	'text'],
			['relation', '', 		'relation is not empty', 	'text'],
			['level', '', 		'level is not empty', 		'text'],
			['job', NULL, 	'', 'text'],
			['birthday', NULL, 	'', 'text'],
			['city', 0, 		'', 'text'],
			['dist', 100, 		'', 'text'],
			['address', NULL, 	'', 'text'],

			['formatted_address', NULL, 	'', 'text'],
			['global_code', NULL, 	'', 'text'],
			['location_lat', NULL, 	'', 'text'],
			['location_lng', NULL, 	'', 'text'],
			['geocoding_result', NULL, 	'', 'text'],

			['text', NULL, 	'', 'text'],
			['photo', NULL, 	'', 'text'],
			['extra_field', json_encode(array()),  '', 'array'],
		));


		// $this->response_json(true, 'test', compact('data'));
		//$data['birthday'] = date('Y-m-d', strtotime("$data[birthday] + 11 year"));
		$option = $data['option'];
		$extra_field = json_decode($data['extra_field'], TRUE);

		if ($option == 'add' or $option == 'edit') {
			unset($data['option']);
			$data['user_id'] = $data['login_token'];
			unset($data['login_token']);
			unset($data['extra_field']);
			if (empty($data['photo']) or $data['photo'] == NULL) unset($data['photo']);
		}

		if ($option == 'edit') {
			$id = $this->get_request_post(array(
				['id', '', 'id is not empty', 'text'],
			));
			$data = array_merge($data, $id);
		} elseif ($option == 'add') {
			//////////////////
			// 先判斷是不是VIP，再判斷進階會員
			if(!$this->User_info_model->can_view_full($data['login_token'])){
				if($this->User_info_model->can_view_part($data['login_token'])){
					if (!$this->Customer_manage_model->is_customers_num_less_600($data['user_id'])) {
						$this->response_json(FALSE, '客戶管理名單已超過600人次, 新增失敗, 請加值後繼續');
					}
				}else{
					if (!$this->Customer_manage_model->is_customers_num_less_150($data['user_id'])) {
						$this->response_json(FALSE, '150, 新增失敗, 請加值後繼續');
					}
				}
			}
			//////////////////
			// if (!$this->User_info_model->can_view_supervisor($data['user_id'])) {
			// 	if (!$this->Customer_manage_model->is_customers_num_less_200($data['user_id'])) {
			// 		$this->response_json(FALSE, '客戶管理名單已超過200人次, 新增失敗, 請加值後繼續');
			// 	}
			// }
			$data['customer_no'] = $this->Customer_manage_model->get_customer_num($data['user_id']) + 1;
		}

		if ($insert_id = $this->Customer_manage_model->set_user_customer_info($data, $option)) {
			if ($option == 'edit') {
				$insert_id = $data['id'];
				$res = $this->Customer_manage_model->get_mgr_customer_info($data['user_id'], $data['id']);
			} else {
				$res  = $this->Customer_manage_model->get_add_customer_info($data['user_id'], $insert_id);
			}


			$field_data = array();


			if (!empty($extra_field)) {
				$count = 0;
				foreach ($extra_field as $key => $value) {
					$field_data[$count]['customer_id'] = $insert_id;
					$field_data[$count]['field_name']  = $key;
					$field_data[$count]['field_value'] = $value;
					$count++;
				}
				if (!$this->Customer_manage_model->set_customer_field($field_data, $option, $insert_id))
					$this->response_json(FALSE, '新增/編輯自訂欄位失敗!');
			}
			/*
				新增時 
				編輯時 收到空陣列表示全刪除
			*/ else {

				if ($option == 'edit')
					$this->Customer_manage_model->set_customer_field($field_data, $option, $insert_id);
			}


			if ($option == 'edit') {
				$user_customer_info = $this->Customer_manage_model->get_mgr_customer_info($data['user_id'], $data['id']);
				$user_customer_note = $this->Customer_manage_model->get_customer_note($data['user_id'], $data['id']);
				//var_dump($user_customer_info);
				if (!isset($user_customer_info)) {
					$user_customer_info = array();
				} else {
					$user_customer_info['source'] = $this->Customer_manage_model->get_customer_source($data['user_id'], $user_customer_info['source_id'])['source_name'];
					$user_customer_info['status'] = customer_status_tf($user_customer_info['status']);
					$connection = $this->Customer_manage_model->get_connection_level($data['user_id'], $data['id']);

					$field = $this->Customer_manage_model->get_customer_field_value_by_customer($user_customer_info['id']);
					if ($user_customer_info['photo'] != NULL)
						$user_customer_info['photo'] = base_url() . $user_customer_info['photo'];

					$user_customer_info['extra_field'] = array();
					$count = 0;
					foreach ($field as $key => $value) {
						$user_customer_info['extra_field'][$count]  = $value['field_name'] . '  ' . $value['field_value'];
						//$user_customer_info['extra_field'][$key]['field_value'] = $value['field_value'];
						$count++;
					}
					$user_customer_info['connection'] = $connection;
					$user_customer_info['notes'] = $user_customer_note;
					$res = $user_customer_info;
				}
			} else {

				$connection = $this->Customer_manage_model->get_connection_level($data['user_id'], $data['customer_no']);
				$user_customer_note = $this->Customer_manage_model->get_customer_note($data['user_id'], $data['customer_no']);
				$field = $this->Customer_manage_model->get_customer_field_value_by_customer($res['id']);
				if ($res['photo'] != NULL)
					$res['photo'] = base_url() . $res['photo'];

				$res['extra_field'] = array();
				$count = 0;
				foreach ($field as $key => $value) {
					$res['extra_field'][$count]  = $value['field_name'] . '  ' . $value['field_value'];
					//$user_customer_info['extra_field'][$key]['field_value'] = $value['field_value'];
					$count++;
				}
				$res['connection'] = $connection;
				$res['notes'] = $user_customer_note;
				//$res=$user_customer_info;
			}


			$this->response_json(TRUE, '新增/編輯成功', array(
				'info'	=>	$res
			));
		} else {
			$this->response_json(FALSE, '新增/編輯客戶失敗!');
		}
	}



	public function test_customer_manage()
	{
		$this->load->model('Customer_manage_model');
		$this->load->helper('tw_zipcode');
		$data = $this->get_request_post(array(
			['login_token', '', 'token is not empty', 'text'],
			['filter', json_encode(array()), '', 'text'],
			['page', 1, '', 'text'],
			['minor_id', 0, '', 'text'],
			['keyword', '', '', 'text'],
		));


		var_dump($data['filter']);
		// exit;

		if (!(is_string($data['filter']) and ((json_last_error() == JSON_ERROR_NONE) ? TRUE : FALSE)))
			$this->response_json(FALSE, 'filter 類型不支援');

		$where_data = array();
		$join_note = FALSE;
		$join_schedule = FALSE;
		$schedule_where = '';


		foreach (json_decode($data['filter'], TRUE) as $key => $value) {

			if ($value != '不限' && $value != '全部') {


				if (!empty($value) and $key !== 'status' and $key !== 'is_master_note' and $key !== 'connection')
					$where_data['C.' . $key] = $value;

				if (!empty($value) and $key === 'status')
					$where_data['C.' . $key] = customer_status_tf_str($value);

				if (!empty($value) and $key === 'is_master_note') {
					$where_data['N.note !='] = '';
					$join_note = TRUE;
				}

				if (!empty($value) and $key === 'connection') {
					$join_schedule = TRUE;
					if ($value === 'red') {
						$schedule_where = ' AND end_date >= "' . Date('Y-m-d H:i:s', strtotime('-1 month')) . '" AND end_date <= "' . Date('Y-m-d H:i:s') . '"';
						$where_data['S.end_date <='] = Date('Y-m-d H:i:s');
					} else if ($value === 'orange') {
						$schedule_where = ' AND end_date >= "' . Date('Y-m-d H:i:s', strtotime('-6 month')) . '" AND end_date <= "' . Date('Y-m-d H:i:s', strtotime('-1 month')) . '"';
						$where_data['S.end_date <='] = Date('Y-m-d H:i:s', strtotime('-1 month'));
					} else if ($value === 'yellow') {
						$schedule_where = ' AND end_date >= "' . Date('Y-m-d H:i:s', strtotime('-1 year')) . '" AND end_date <= "' . Date('Y-m-d H:i:s', strtotime('-6 month')) . '"';
						$where_data['S.end_date <='] = Date('Y-m-d H:i:s', strtotime('-6 month'));
					} else if ($value === 'blue') {
						$schedule_where = ' AND end_date <= "' . Date('Y-m-d H:i:s', strtotime('-1 year')) . '"';
						$where_data['S.end_date <='] = Date('Y-m-d H:i:s', strtotime('-1 year'));
					}
				}
			}
		}

		$like_data = array('C.name' => $data['keyword']);

		// for 主管瀏覽
		if ($data['minor_id'] !== 0) {
			// 檢查是否可以檢視
			if (!$this->User_info_model->is_owner_user($data['login_token'], $data['minor_id']))
				$this->response_json(FALSE, '您無權查看此頁面');

			$data['login_token'] = $data['minor_id'];
		}

		$where_data = array_merge($where_data, array(
			'C.user_id' 	=> $data['login_token'],
			'C.is_delete' 	=> 0,
		));

		$total = $this->Customer_manage_model->get_user_all_customer_num($data['login_token'], $where_data, $like_data, $join_note, $join_schedule, $schedule_where);
		$total_page  = $this->Customer_manage_model->compute_total_page($total);

		$order_by = ' `customer_no` ASC';
		$user_customer_info   = $this->Customer_manage_model->get_user_all_customer($data['login_token'], $where_data, $order_by, $like_data, $data['page'], $join_note, $join_schedule, $schedule_where);
		var_dump($this->db->last_query());
		exit;
		$user_customer_source = $this->Customer_manage_model->get_user_all_source($data['login_token']);
		// $user_customer_field  = $this->Customer_manage_model->get_user_all_field_name($data['login_token']);
		// $this->response_json(TRUE,'',array(
		// 	'source'	=>	$user_customer_source
		// ));
		if (!isset($user_customer_info))
			$user_customer_info = array();

		if (!isset($user_customer_source))
			$user_customer_source = array();

		$customer = array();
		foreach ($user_customer_info as $key => $value) {
			for ($i = 0; $i < count($user_customer_source); $i++) {
				if ($user_customer_source[$i]['id'] == $value['source_id'])
					$value['source'] = $user_customer_source[$i]['source_name'];
			}

			if ($value['photo'] != NULL)
				$value['photo'] = base_url() . $value['photo'];
			$field = $this->Customer_manage_model->get_customer_field_value_by_customer($value['id']);
			$connection = $this->Customer_manage_model->get_connection_level($data['login_token'], $value['id']);


			// $birthday_str = '生日  ' . (empty($value['birthday']) ? '' : $value['birthday']);
			unset($extra_field);
			// $extra_field[] = $birthday_str;
			$extra_field = array();
			for ($i = 0; $i < count($field); $i++) {
				if (empty($field[$i]['field_value'])) continue;

				$extra_field[$i] = $field[$i]['field_name'] . '  ' . $field[$i]['field_value'];
			}

			// $c_d = get_addr_str($value['city'],$value['dist']);
			$customer[$key] =  [
				'id' 			=> $value['id'],
				'customer_no' 	=> $value['customer_no'],
				'connection' 	=> $connection,
				'name' 			=> $value['name'],
				'photo' 		=> $value['photo'],
				'status' 		=> customer_status_tf($value['status']),
				'level' 		=> $value['level'],
				'relation'  	=> $value['relation'],
				'source' 		=> $value['source'],
				'extra_field' 	=> $extra_field,
				'city' 			=> $value['city'],
				'dist' 			=> $value['dist'],
				'birthday' 		=> $value['birthday'],
			];
		}

		$this->response_json(TRUE, '', array(
			'total' 	 => $total,
			'total_page' => $total_page,
			'now_page' => $data['page'],
			'data' => $customer,
			'sad' => $join_note
		));
	}
	public function test_data()
	{
		$this->data = $this->get_request_post(array(
			['login_token', '', 'token is not empty', 'text'],
			['year', '', 'year is not empty', 'text'],
			['month', '', 'month is not empty', 'text'],
			['minor_id', 0, '', 'text'],
		));

		// for 主管瀏覽
		if ($this->data['minor_id'] !== 0) {
			if (!$this->User_info_model->can_view_supervisor($this->data['login_token']))
				$this->response_json(FALSE, '請升級後繼續');

			// 檢查是否可以檢視
			if (!$this->User_info_model->is_owner_user($this->data['login_token'], $this->data['minor_id']))
				$this->response_json(FALSE, '您無權查看此頁面');

			$this->data['login_token'] = $this->data['minor_id'];
		}

		$result = $this->User_info_model->get_month_goal_data($this->data['login_token'], $this->data['year'], $this->data['month']);
		if ($result == FALSE)
			$result = array();

		for ($i = 0; $i < count($result); $i++) {
			unset($result[$i]['user_id']);
			unset($result[$i]['year']);
			unset($result[$i]['create_date']);
			unset($result[$i]['is_delete']);
		}
		if ($result == FALSE) {
			$this->status = TRUE;
			$this->msg = '取得成功';
			$data = array();
			$total_percent = 0;
		} else {
			$this->status = TRUE;
			$this->msg = '取得成功';
			$data = $this->count_item_goal_percent($result);
			$total_percent = $this->count_total_percent($data);
		}
		$this->response_json($this->status, $this->msg, array(
			'data' 	  => $data,
			'total_percent' => $total_percent,
		));
	}


	// 複製其他月份的目標
	public function  copy_goals()
	{

		$this->load->model('Goal_model');


		//欲新增的月份 copy_year
		//欲複製的月份 copy_month

		$this->data = $this->get_request_post(array(
			['login_token', '', 'token is not empty', 'text'],
			['year', '', 'year is not empty', 'text'],
			['month', '', 'month is not empty', 'text'],

			['copy_year', '', 'copy_year is not empty', 'text'],
			['copy_month', '', 'copy_month is not empty', 'text'],

		));

		date_default_timezone_set('Asia/Taipei');
		$date  = date('Y-m-d h:i:s');

		//取得欲複製的月目標
		$goals = $this->Goal_model->getGoals($this->data['login_token'], '', $this->data['copy_year'], $this->data['copy_month']);

		$goalIdArray  = array();
		$countId 	  = 0;



		//  年月替換成指定年月份
		for ($i = 0; $i < count($goals); $i++) {

			$syntax = array(
				'is_delete' => 0,
				'goal_id' => $goals[$i]['id']
			);

			$goalId = $goals[$i]['id'];

			$insertGoalData = array(

				'id'			=>	null,
				'user_id'		=>	$goals[$i]['user_id'],
				'sort'			=>	$goals[$i]['sort'],
				'name'			=>	$goals[$i]['name'],
				'year'			=>	$this->data['year'],
				'month'			=>	$this->data['month'],
				'total_money'	=>  $goals[$i]['total_money'],
				'create_date'	=>	$date

			);




			// 複製目標 寫入db
			$insertGoalId	= $this->Goal_model->addGoals($insertGoalData);




			// 取得複製目標客戶名單
			$customerResult = $this->Goal_model->getGoalCustomers($goalId, $syntax);



			// 如果目標的顧客名單為空
			if (!$customerResult) continue;


			for ($j =  0; $j < count($customerResult); $j++) {

				$customerResult[$j]['id'] 			= null;
				$customerResult[$j]['goal_id']		= $insertGoalId;
				$customerResult[$j]['is_complete'] 	= 0;
			}

			$insertCustomerResult = $this->Goal_model->insertGoalCustomers($customerResult);





			unset($customerResult);
		}




		// 取得目標客戶陣列

		$this->response_json(TRUE, '操作成功');
	}

	//	編輯客戶概況名單
	public function edit_condition_list()
	{

		$this->load->model('Customer_manage_model', 'Customer');
		$data 	= $this->get_request_post(array(
			['login_token', '', 'login_token is not empty', 'text', 'token'],
			['condition_id', '', 'condition id is not empty', 'text'],
			['minor_id', 	'', 'minor id is not empty', 	'text'],
			// ['customer_no', '', 'customer_no is not empty', 'array'],
			['customer_no', '', '', 'array'],
		));

		// add 0926
		if($data['customer_no']==''){
			$this->response_json(TRUE, '未進行任何編輯');
		}

		// print 123;exit;


		if (!$this->User_info_model->can_view_supervisor($data['login_token']))
			$this->response_json(FALSE, '請升級後繼續');
		if (!$this->User_info_model->is_owner_user($data['login_token'], $data['minor_id']))
			$this->response_json(FALSE, '您無權查看此頁面');
		// //	取得紀錄的sql指令
		// var_dump($data['customer_no'][0]);
		// var_dump($data['customer_no'][1]);

		// $this->response_json(TRUE, '操作成功', array('data' => $data['customer_no'] ));

		// $data['customer_no'] =  str_replace(array('[',']','"'),"",$data['customer_no']);

		// $customerNoArray 	 =  explode(',',$data['customer_no']);

		$executeIsSuccessful =  $this->Customer->editMinorCondition($data, $data['customer_no']);

		if ($executeIsSuccessful) $this->response_json(TRUE, '編輯成功');
		else $this->response_json(FALSE, '操作發生錯誤');
	}
	public function test_mail()
	{
		$this->load->model('Mail_model');

		$mail_to  = $this->input->post('email');
		$body = "測試" . date('Y-m-d h:i:s');

		$this->Mail_model->send_mail($mail_to, $body, '暫時密碼通知');

		//寫檔log
		$date = date("Y-m-d h:i:s");
		$fp = fopen('logForMail.txt', 'a'); //opens file in append mode  
		fwrite($fp, $mail_to . PHP_EOL);
		fwrite($fp, 'date : ' . $date . PHP_EOL);
		fclose($fp);

		$this->response_json(true, '成功');
	}
}
