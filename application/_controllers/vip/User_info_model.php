<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class User_info_model extends Base_Model {

	public function __construct()
	{
		parent::__construct();
	}



/* Return TRUE or FALSE block - Start */

	public function is_email_exist($email)
	{
		$row = $this->db->select('id')
						->from($this->user_table)
						->where('email', $email)
						->get()
						->num_rows();

		if ($row === 1)
			return TRUE;
		else
			return FALSE;
	}

	public function is_social_id_exist($email, $login_type)
	{
		if ( ! login_type_can_search_db($login_type))
			return FALSE;

		$row = $this->db->select($login_type)
						->from($this->user_table)
						->where('email', $email)
						->get()
						->row_array();

		if ($row[$login_type] !== NULL)
			return TRUE;
		else
			return FALSE;
	}

	public function pwd_confirm($email, $input_pwd)
	{
		$db_pwd = $this->db->select('password')
						   ->from($this->user_table)
						   ->where('email', $email)
						   ->get()
						   ->row_array();

		if ($this->encryption->decrypt($db_pwd['password']) == md5($input_pwd))
			return TRUE;
		else
			return FALSE;
	}

	public function forgetpwd_confirm($email, $input_pwd)
	{
		$db_pwd = $this->db->select('forgetpwd')
						   ->from($this->user_table)
						   ->where('email', $email)
						   ->get()
						   ->row_array();

		if ($db_pwd['forgetpwd'] == $input_pwd)
			return TRUE;
		else
			return FALSE;
	}

	public function social_id_confirm($email, $login_type, $input_social_id)
	{
		if ( ! login_type_can_search_db($login_type))
			return FALSE;

		$db_social_id = $this->db->select($login_type)
								 ->from($this->user_table)
								 ->where('email', $email)
								 ->get()
								 ->row_array();

		if ($db_social_id[$login_type] == $input_social_id)
			return TRUE;
		else
			return FALSE;
	}

	public function is_open_ai_interview($user_id)
	{
		$row = $this->db->select('ai_interview')
						->from($this->user_table)
						->where('id', $user_id)
						->get()
						->row_array();

		if ($row === NULL OR $row['ai_interview'] == 0)
		{
			return 0;
		}
		elseif ($row['ai_interview'] == 1)
		{
			return 1;
		}
	}

	public function is_notice_confirmed($user_id, $notice_id)
	{
		return $this->db->select('id')
						->from($this->notice_table)
						->where(array('id' => $notice_id, 'receive_id' => $user_id))
						->get()
						->num_rows();
	}

	public function is_user_token_confirmed($token, $now_date_sub_24)
	{
		$has_token = $this->db_output_tf($this->db->select('create_date')
												  ->from($this->login_token_table)
												  ->where(array('token' => $token, 'status' => 'normal'))
												  ->get()
												  ->row_array(), FALSE
		);

		if ($has_token == FALSE)
			return FALSE;
		else
			return ((strtotime($has_token['create_date']) < strtotime($now_date_sub_24)) ? FALSE : TRUE);
	}

	public function is_year_goal_exist($user_id, $name, $year)
	{
		return $this->db->select('id')
						->from($this->year_goal_table)
						->where(array('name' => $name, 'user_id' => $user_id, 'year' => $year, 'is_delete' => 0))
						->get()
						->num_rows();
	}

	public function is_month_goal_exist($user_id, $name, $year, $month)
	{
		return $this->db->select('id')
						->from($this->month_goal_table)
						->where(array('name' => $name, 'user_id' => $user_id, 'year' => $year, 'month' => $month ,'is_delete' => 0 ))
						->get()
						->num_rows();
	}

	public function can_view_supervisor($user_id)
	{
		$row = $this->db->select('id')
						->from($this->user_table)
						->where(array('id' => $user_id, 'privilege_end_date >=' => date( "Y-m-d H:i:s" )))
						->get()
						->num_rows();

		if ($row > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function is_owner_user($owner, $user_id)
	{
		$row = $this->db->select('id')
						->from($this->master_minor_relation_table)
						->where(array('master_id' => $owner, 'minor_id' => $user_id))
						->get()
						->num_rows();

		if ($row > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function is_minor_exist($user_id, $minor_id)
	{
		return $this->db->select('id')
						->from($this->master_minor_relation_table)
						->where(array('master_id' => $user_id, 'minor_id' => $minor_id, 'is_delete' => 0))
						->get()
						->num_rows();
	}
/* Return TRUE or FALSE block - End */


/* Get block - Start */

	public function get_info_by_email($email)
	{
		$row = $this->db->select('*')
						->from($this->user_table)
						->where('email', $email)
						->get()
						->num_rows();

		if ($row !== NULL)
		{
			return $row;
		}
		else
		{
			return FALSE;
		}
	}

	public function get_info_by_social($login_type, $social_id)
	{
		$row = $this->db->select('*')
						->from($this->user_table)
						->where($login_type, $social_id)
						->get()
						->num_rows();

		if ($row !== NULL)
		{
			return $row;
		}
		else
		{
			return FALSE;
		}
	}

	public function get_user_id_by_email($email)
	{
		$row = $this->db->select('id')
						->from($this->user_table)
						->where('email', $email)
						->get()
						->row_array();

		if ($row === NULL)
			return FALSE;
		else
			return $row['id'];
	}

	public function get_user_info($user_id)
	{
		$row = $this->db->select('name, email, avatar, privilege_end_date, seal_code, click_direct,id')
						->from($this->user_table)
						->where('id', $user_id)
						->get()
						->row_array();
		if (strtotime($row['privilege_end_date']) > strtotime(Date('Y-m-d H:i:s'))) $row['has_privilege'] = TRUE;
		else $row['has_privilege'] = FALSE;

		$row['avatar'] = base_url() . $row['avatar'];
		// $row['click_direct'] = explode('=', $row['click_direct'])[1];


		$click_num = explode('=', $row['click_direct']);
		if (strtotime($click_num[0]) < strtotime(date('Y-m-d'))) $row['click_direct'] = 0;
		else $row['click_direct'] = $click_num[1];

		return $row;
	}

	public function get_user_notice($user_id)
	{
		return $this->db->select('*')
						->from($this->notice_table)
						->where('receive_id', $user_id)
						->order_by('create_date DESC')
						->get()
						->result_array();
	}

	public function get_user_birthday_notice($user_id)
	{
		return $this->db->select('*')
						->from('birthday_notice')
						// ->where('receive_id', $user_id)
						->where(array('receive_id' => $user_id,'birthday_month ' => date('m')))
						->order_by('create_date DESC')
						->get()
						->result_array();
	}

	public function get_year_goal_data($user_id, $year)
	{
		return $this->db_output_tf($this->db->select('*')
											->from($this->year_goal_table)
											->where(array('user_id' => $user_id, 'year' => $year, 'is_delete' => 0))
											->order_by('sort DESC')
											->get()
											->result_array(), FALSE
		);
	}

	public function delete_month_goal($user_id,$goal_id)
	{

		$data=array(
			'is_delete'	=> 1
		);
		
		$syntax="id= '$goal_id' and user_id ='$user_id' ";
		$this->db->where($syntax)
				 ->update($this->month_goal_table,array('is_delete' => 1));
				 
			
		

	}

	public function delete_year_goal($user_id,$goal_id)
	{

		$data=array(
			'is_delete'	=> 1
		);
		
		$syntax="id= '$goal_id' and user_id ='$user_id' ";
		$this->db->where($syntax)
				 ->update($this->year_goal_table,array('is_delete' => 1));
				 
			
		

	}


	public function get_month_goal_data($user_id, $year, $month)
	{
		return $this->db_output_tf($this->db->select('*')
											->from($this->month_goal_table)
											->where(array('user_id' => $user_id, 'year' => $year, 'month' => $month, 'is_delete' => 0))
											->order_by('sort DESC')
											->get()
											->result_array(), FALSE
		);
	}

	public function get_montyh_goal_by_id($user_id,$goal_id)
	{
		return $this->db->select()
						->from($this->month_goal_table)
						->where(array('user_id' =>	$user_id, 'id'=> $goal_id ,'is_delete'=>0 ))
						->get()
						->result_array();	

	} 


	public function get_year_goal_by_id($user_id,$goal_id)
	{
		return $this->db->select()
						->from($this->year_goal_table)
						->where(array('user_id'=>	$user_id,'id'=> $goal_id ,'is_delete'=>0))
						->get()
						->result_array();
	
	}


	public function get_goal($where_data, $like_data)
	{
		return $this->db->select('*')
						->from($this->goal_table)
						->where($where_data)
						->like($like_data)
						->order_by('sort DESC')
						->get()
						->result_array();
	}

	public function get_goal_customer($goal_id, $like_data)
	{
		return $this->db->select('*')
						->from($this->goal_customer_table)
						->where(array('goal_id' => $goal_id, 'is_delete' => 0))
						->like($like_data)
						->get()
						->result_array();
	}

	public function get_email_by_user_id($user_id)
	{
		return $this->db->select('email')
						->from($this->user_table)
						->where(array('id' => $user_id))
						->get()
						->row_array();
	}

	public function get_supervisor_list($user_id, $keyword = '', $order = 'DESC')
	{
		$res = $this->db->select('U.id, U.name, U.avatar')
						->from($this->master_minor_relation_table . ' as M')
						->join('user as U', 'M.minor_id = U.id', 'left')
						->where(array('master_id' => $user_id, 'M.is_delete' => 0))
						->like('U.name', $keyword)
						->order_by('U.name',$order)
						->get()
						->result_array();

		foreach ($res as $key => $value)
		{
			if ($value['avatar'] == NULL OR $value['avatar'] == '') continue;
			$res[$key]['avatar'] = base_url() . $value['avatar'];
		}
		return $res;
	}

	public function get_is_face($user_id)
	{
		return $this->db->select('is_face')
						->from($this->user_table)
						->where(array('id' => $user_id))
						->get()
						->row_array();
	}

	public function get_user_id_by_seal_code($seal_code)
	{
		return $this->db->select('id')
						->from($this->user_table)
						->where(array('is_delete' => 0, 'seal_code' => $seal_code))
						->get()
						->row_array();
	}

	public function add_minor_user($user_id, $minor_id)
	{
		$this->db->insert($this->master_minor_relation_table, array('master_id' => $user_id, 'minor_id' => $minor_id));
	}

	//new  add to 11/18
	public function del_minor_user($user_id, $minor_id)
	{
		$data = array(
			'is_delete' => 1,
		);
		$this->db->where(array('master_id' => $user_id, 'minor_id' => $minor_id))
						->update($this->master_minor_relation_table, $data);

	}

	public function get_push_token_by_id($user_id)
	{
		return $this->db->select('push_token')
						->from($this->login_token_table)
						->where(array('status' => 'normal', 'user_id' => $user_id))
						->get()
						->row_array();
	}

	public function get_goal_notify()
	{
		// $where = 'is_delete = 0 AND id = (SELECT FLOOR(RAND()*(2-1+1)+1) as dd)';
		$where = 'is_delete = 0 ';

		return $this->db->select('*')
						->from('goal_notify')
						->where($where)
						->order_by('id', 'RANDOM')
						->get()
						->row_array()['title'];
	}

	public function get_goal_customer_info($user_id, $goal_id, $customer_id)
	{
		return $this->db->select('*')
				->from($this->goal_table . ' as G')
				->join($this->goal_customer_table . ' as GC', 'G.id = GC.goal_id', 'left')
				->where(array('GC.id' => $customer_id, 'G.user_id' => $user_id, 'G.id' => $goal_id))
				->get()
				->row_array();
	}

	public function get_click_direct($user_id)
	{
		return $this->db->select('click_direct')
						->from($this->user_table)
						->where(array('id' => $user_id, 'is_delete' => 0))
						->get()
						->row_array()['click_direct'];
	}
/* Get block - End */


/* Set or Add block - Start */

	public function add_register($data)
	{
		$data['privilege_end_date'] = Date('Y-m-d H:i:s', strtotime('+30 days'));
		$data['seal_code'] 			= md5(uniqid('Sales', TRUE) . uniqid('privilege', TRUE) . uniqid('code', TRUE));
		if ($this->db->insert($this->user_table, $data))
		{
			return $this->db->insert_id();
		}
		else
		{
			return FALSE;
		}
	}

	public function set_login_token($data, $expired_time)
	{
		// 把過期的 status 都設成 expired
		$expired_data = array('status' => 'expired');
		$this->db->where(array('user_id' => $data['user_id'], 'create_date <' => $expired_time))
				 ->update($this->login_token_table, $expired_data);

		// 搜尋
		$row = $this->db_output_tf($this->db->select('token')
											->from($this->login_token_table)
											->where(array('user_id' => $data['user_id'], 'status' => 'normal'))
											->get()
											->row_array(), FALSE
		);

		if ($row == FALSE)
			return $this->db_output_tf($this->db->insert($this->login_token_table, $data), TRUE);
		else
			return $row['token'];
	}

	public function set_user_notice_readed($user_id, $notice_id)
	{
		$data = array(
				'is_read' => 1,
		);

		return $this->db->where(array('id' => $notice_id, 'receive_id' => $user_id))
						->update($this->notice_table, $data);
	}

	public function add_soical_id_in_account($email, $data)
	{
		return $this->db->where('email', $email)
						->update($this->user_table, $data);
	}

	public function set_year_goal_data($data, $option)
	{
		if ($option == 'add')
			return $this->db->insert($this->year_goal_table, $data);
		elseif ($option == 'edit')
			return $this->db->where(array('id' => $data['id'], 'user_id' => $data['user_id']))->update($this->year_goal_table, $data);
	}

	public function set_month_goal_data($data, $option)
	{
		if ($option == 'add')
			return $this->db->insert($this->month_goal_table, $data);
		elseif ($option == 'edit')
			return $this->db->where(array('id' => $data['id'], 'user_id' => $data['user_id']))->update($this->month_goal_table, $data);
	}

	public function get_priv_date($user_id)
	{
		return $this->db->select('privilege_end_date')
						->from($this->user_table)
						->where('id', $user_id)
						->get()
						->row_array();
	}

	private function modify_priv_date($user_id, $priv_data)
	{
		$data = array('privilege_end_date' => $priv_data);
		return $this->db->where('id', $user_id)
						->update($this->user_table, $data);
	}

	public function add_upgrad($data)
	{
		$this->db->trans_start();

		$this->modify_priv_date($data['user_id'], $data['end_date']);
		$this->db->insert($this->upgrad_table, $data);

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	public function add_customer_goal($data)
	{
		if ($this->db->insert($this->goal_customer_table, $data))
			return $this->db->insert_id();
	}

	public function modify_customer_goal($where_data, $data)
	{
		return $this->db->where($where_data)
						->update($this->goal_customer_table, $data);
	}

	public function add_goal($goal_data, $customer_data)
	{
		$this->db->insert($this->goal_table, $goal_data);

		if (empty($customer_data['customer_name']))
			return TRUE;

		$customer_data['goal_id'] = $this->db->insert_id();
		return $this->db->insert($this->goal_customer_table, $customer_data);
	}

	public function modify_goal($where_data, $data)
	{
		return $this->db->where($where_data)
						->update($this->goal_table, $data);
	}

	private function _pwd_encrypt($pwd)
	{
		return $this->encryption->encrypt(md5($pwd));
	}

	public function change_pwd($user_id, $pwd)
	{
		$pwd = $this->_pwd_encrypt($pwd);
		return $this->db->where('id', $user_id)->update($this->user_table, array('password' => $pwd));
	}

	public function set_forgetpwd($email)
	{
		$pwd = uniqid() . uniqid();
		$this->db->where(array('email' => $email))->update($this->user_table, array('forgetpwd' => $pwd));

		return $pwd;
	}

	public function clear_forgetpwd($email)
	{
		$this->db->where(array('email' => $email))->update($this->user_table, array('forgetpwd' => NULL));
	}

	public function set_goal_customer_complete($goal_id, $customer_id, $is)
	{
		$this->db->where(array('goal_id' => $goal_id, 'id' => $customer_id))->update($this->goal_customer_table, array('is_complete' => $is));
	}

	public function the_goal_is_mine($user_id, $goal_id)
	{
		if (0 < $this->db->select('id')
						->from($this->goal_table)
						->where(array('user_id' => $user_id, 'id' => $goal_id))
						->get()
						->num_rows())
		{
			return TRUE;
		}
		else return FALSE;
	}
/* Set or Add block - End */

	public function add_chat_for_minor($user_id, $minor_id, $text)
	{
		$this->load->model('Notification_firebase_model', 'Notify');
		$minor   = $this->get_push_token_by_id($minor_id);

		$user    = $this->get_user_info($user_id);
		$subject = $user['name'] . ' 留言給你:「' . $text . '」';
		$title 	 = $user['name'] . ' 留言給你。';

		$this->db->insert($this->notice_table, array(
			'send_id' => $user_id, 'receive_id' => $minor_id, 'subject' => $subject, 'class' => 'supervisor'
		));
		return $this->Notify->send_push($title, $text, $minor['push_token'], 'ios');
	}

	public function get_fack_email_for_moblie()
	{
		return 'fack_' . uniqid() . '@email.com';
	}

	// For Cron
	public function get_all_users()
	{
		return $this->db->select('id')
						->from($this->user_table)
						->where(array('is_delete' => 0))
						->get()
						->result_array();
	}

	public function goal_init_insert($insert_data)
	{
		return $this->db->insert_batch($this->goal_table, $insert_data);
	}

	public function year_init_insert($insert_data)
	{
		return $this->db->insert_batch($this->year_goal_table, $insert_data);
	}

	public function month_init_insert($insert_data)
	{
		return $this->db->insert_batch($this->month_goal_table, $insert_data);
	}

	public function set_avatar($user_id, $path)
	{
		$this->db->where(array('id' => $user_id))->update($this->user_table, array('avatar' => $path));
	}

	public function set_click_direct($user_id, $click_direct)
	{
		$this->db->where(array('id' => $user_id))->update($this->user_table, array('click_direct' => $click_direct));
	}
}