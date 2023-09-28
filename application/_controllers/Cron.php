<?php
defined('BASEPATH') or exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
header("Content-Type:text/html; charset=utf-8");

class Cron extends Base_Controller {

	private $login_url;
	private $status = FALSE;
	private $msg 	= '';


	public function __construct()
	{
		parent::__construct();

		$this->load->model('User_info_model');
		$this->login_url = base_url().'api/login';
	}

	public function account_init()
	{
		$goal_field 	   = array('user_id' => NULL, 'name' => NULL, 'year' => date('Y'), 'total_money' => 0, 'month' => TRUE);
		$goal_name_value   = array('壽險期繳追蹤名單', '躉繳', 'A&H');

		$year_goal_field   = array('user_id' => NULL, 'name' => NULL, 'year' => date('Y'), 'now_num' => 0, 'total_num' => 0);
		$year_goal_value   = array('FYP', 'FYC', '增員');

		$month_goal_field  = array('user_id' => NULL, 'name' => NULL, 'year' => date('Y'), 'now_num' => 0, 'total_num' => 0, 'month' => TRUE);
		$fixed_month_goal  = array('FYP', 'FYC', '增員');

		// Start
		$users = $this->User_info_model->get_all_users();
		// $users = array(array('id' => 1));

		//
		$fixed_goal_data  = $this->_create_fixed_arr($goal_field, $goal_name_value, $users);
		$fixed_year_data  = $this->_create_fixed_arr($year_goal_field, $year_goal_value, $users);
		$fixed_month_data = $this->_create_fixed_arr($month_goal_field, $fixed_month_goal, $users);

		$this->User_info_model->goal_init_insert($fixed_goal_data);
		$this->User_info_model->year_init_insert($fixed_year_data);
		$this->User_info_model->month_init_insert($fixed_month_data);

		$this->response_json(TRUE, '成功');
	}

	private function _create_fixed_arr($field_arr, $value_arr, $users)
	{
		$all_fixed = array();
		$fixed_arr = array();
		for ($i = 0; $i < count($value_arr); $i++)
		{
			$fixed_arr[$i] = $field_arr;
			$fixed_arr[$i]['name'] = $value_arr[$i];
		}

		foreach ($users as $u_key => $u_value)
		{
			$temp_arr = array();
			foreach ($fixed_arr as $f_key => $f_value)
			{
				if (isset($field_arr['month']))
				{
					for ($i = 1; $i <= 12; $i++)
					{ 
						$temp_arr = $f_value;
						$temp_arr['user_id'] = $u_value['id'];
						$temp_arr['month']   = $i;
						$temp_arr['sort']    = $this->_set_sort($temp_arr['name']);

						$all_fixed[] = $temp_arr;
					}
				}
				else
				{
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
		switch ($name)
		{
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

	public function schedule_notify()
	{
		$this->load->model('Calendar_model', 'Calend');
		$this->load->model('Notification_firebase_model', 'Notify');

		foreach ($this->Calend->get_all_notify_schedule() as $key => $value)
		{
			$info = $this->_set_notify_info($value);

			$res = $this->Notify->send_push($info['title'], $info['body'], array($value['push_token']), 'ios', $info['data']);
			$this->response_json(TRUE, '', array(
					'data' => $res,
			));
		}
	}

	private function _set_notify_info($user)
	{
		$title = '';
		$body  = '';
		$title = (empty($user['name']) ? $user['email'] : $user['name']);
		$title .= ' 您好,有您的行事曆提醒';
		$body  .= $user['item_name'];

		return array('title' => $title, 'body' => $body, 'data' => array('hi' => $user['email']));
	}

	public function manage_customer_birthday()
	{
		$this->load->model('Notification_firebase_model', 'Notify');
		$res = $this->db->select('T.push_token, C.name, C.customer_no')
						->from('customer_mgr as C')
						->join('login_token as T', 'T.user_id = C.user_id', 'left')
						->where(array('C.is_delete' => 0, 'T.status' => 'normal', 'C.birthday' => Date('Y-m-d')))
						->get()
						->result_array();

		foreach ($res as $key => $value)
		{
			$title       = '您的客戶 : ' . $value['name'] . ' - 編號 : ' . $value['customer_no'] . ', 今天生日! 快去祝他生日快樂!';
			$body        = '';
			$res[$key]   = $this->Notify->send_push($title, $body, array($value['push_token']), 'ios');
		}

		$this->response_json(TRUE, '', array(
				'data' => $res,
		));
	}
	
	public function manage_customer_birthday_of_month()
	{
		$this->load->model('Notification_firebase_model', 'Notify');
		$birthday='C.birthday';
		$birthday=substr($birthday,0,4);
		$res = $this->db->select('T.push_token,T.user_id, C.name, C.customer_no,C.birthday')
						->from('customer_mgr as C')
						->join('login_token as T', 'T.user_id = C.user_id', 'left')
						// ->where(array('C.is_delete' => 0, 'T.status' => 'normal', 'C.birthday' => Date('Y-m-d')))
						// ->where(array('C.is_delete' => 0, 'T.status' => 'normal', $birthday => Date('Y')))
						->where(array('C.is_delete' => 0, 'T.status' => 'normal', 'C.birthday !=' => NULL))
						->get()
						->result_array();
		
		

		foreach ($res as $key => $value)
		{
			// print_r(substr($value['birthday'],0,7)); exit;
			// print_r(substr($value['birthday'],5,7));exit;
			if(substr($value['birthday'],5,2)==Date('m')){
			// 	print 123;
			// print_r(substr($value['birthday'],5,2));exit;
			// print_r($value);
			// print_r(substr($value['birthday'],8,2));exit;
			
			// exit;
			// if(isset($str="select * from  notice  where subject='您的客戶 : '.$value[name] .'本月 08 生日提醒'")){
			// 	$res=$this->db->query($str)->result_array();
			// 	print_r($res);exit;
			// }
			$this->db->insert('birthday_notice', array(
				'send_id' => '0', 
				'receive_id' => $value['user_id'], 
				'subject' => '您的客戶 : ' .$value['name'].' 於本月 '.substr($value['birthday'],8,2).'號 生日提醒', 
				'class' => 'system',
				// 'type' => 'birthday',
				'birthday_month' =>substr($value['birthday'],5,2)
			));

			$r[]=$value;
			}
		}
		// exit;
		$this->response_json(TRUE, '', array(
				'data' => $r,
		));
	}
	public function set_smart_cron(){
		$this->load->model('Calendar_model');
		// $str="select * from user where is_delete=0 AND ai_Interview =1 ";
		$str="select * from user where is_delete=0 AND  id=104";
		$res=$this->db->query($str)->result_array();

		$f = fopen("0925_log.txt", "a+");
            fwrite($f, "_FILES_02:\n".date("Y-m-d-H:i:s")."\n".json_encode($res)."\n\n");
            fclose($f);
			// exit;
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
			$data=array();
			foreach($user_customer_info as $item){
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
				
	
			}
			//依照優先順序排序
			$data_sort=array_column($data,'sort');
			array_multisort($data_sort,SORT_ASC,SORT_NUMERIC,$data);
			$num=0;
			$date=date('Y-m-d')."06:00:00";
			print_r($data);
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
				$this->Calendar_model->add_schedule($d);
				$date=$date_list['start'];
				
				if($num==9){
					// print_r($num);
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
			return $data;
		}
		
		
	}
}