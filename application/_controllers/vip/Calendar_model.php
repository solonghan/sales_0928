<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Calendar_model extends Base_Model {

	public function __construct()
	{
		parent::__construct();
		
		date_default_timezone_set("Asia/Taipei");
	}

	public function add_memo($data)
	{
		return $this->db->insert($this->memo_table, $data);
	}

	public function modify_memo($data, $where_data)
	{
		return $this->db->where($where_data)
						->update($this->memo_table, $data);
	}

	public function add_schedule_item($data)
	{
		if ($this->db->insert($this->schedule_item_table, $data))
			return $this->db->insert_id();
		else
			return FALSE;
	}

	public function modify_schedule_item($data, $where_data)
	{
		return $this->db->where($where_data)
						->update($this->schedule_item_table, $data);
	}

	public function del_schedule_item($data, $where_data, $or_where)
	{
		$this->db->where($where_data)
				 ->group_start();
		foreach ($or_where as $value)
		{
			$this->db->or_where('id', $value);
		}
		return $this->db->group_end()
				 ->update($this->schedule_item_table, $data);
	}

	public function get_memo($user_id)
	{
		return $this->db->select('*')
						->from($this->memo_table)
						->where(array('user_id' => $user_id, 'is_delete' => 0))
						->order_by('id', 'ASC')
						->get()
						->result_array();
	}

	public function get_schedule_item($user_id)
	{
		return $this->db->select('*')
						->from($this->schedule_item_table)
						->where(array('user_id' => $user_id, 'is_delete' => 0))
						->get()
						->result_array();
	}

	public function get_schedule_item_fixed()
	{
		return $this->db->select('*')
						->from($this->schedule_item_table)
						->where('user_id', 0)
						->get()
						->result_array();
	}

	public function get_customer_name($user_id, $like_data)
	{
		return $this->db->select('id, name, customer_no')
						->from($this->customer_manage_table)
						->where(array('user_id'=> $user_id,'is_delete' =>0))
						->like($like_data)
						->get()
						->result_array();
	}

	public function is_schedule_can_add($user_id, $s_time, $e_time)
	{
		return $this->db->select('id')
						->from($this->schedule_table)
						->where(array('user_id' => $user_id, 'is_delete' => 0))
						->group_start()
						->group_start()
								->where(array('start_date <=' => $s_time, 'end_date >' => $s_time))
						->group_end()
						->or_group_start()
		                        ->where(array('start_date <' => $e_time, 'end_date >=' => $e_time))
		                ->group_end()
		                ->group_end()
						->get()
						->num_rows();
	}

	public function is_schedule_can_edit($user_id, $s_time, $e_time, $id)
	{
		return $this->db->select('id')
						->from($this->schedule_table)
						->where(array('user_id' => $user_id, 'id !=' => $id, 'is_delete' => 0))
						->group_start()
						->group_start()
								->where(array('start_date <=' => $s_time, 'end_date >=' => $s_time))
						->group_end()
						->or_group_start()
		                        ->where(array('start_date <=' => $e_time, 'end_date >=' => $e_time))
		                ->group_end()
		                ->group_end()
						->get()
						->num_rows();
	}


	public function add_schedule($data)
	{
		return $this->db->insert($this->schedule_table, $data);
	}


	public function modify_schedule($data, $where_data)
	{
		return $this->db->where($where_data)
						->update($this->schedule_table, $data);
	}


	public function get_schedule_item_name(){
		return $this->db->select()
						->from($this->schedule_item_table)
						->where('is_delete',0)
						->get()
						->result_array();	
	}

	public function test_get_schedule($user_id,$w_d)
	{
		return $this->db->select('S.*')
						->from($this->schedule_table . ' as S')
						// ->join($this->schedule_item_table . ' as U', 'S.item_id = U.id', 'left')
						// ->join($this->customer_manage_table . ' as C', 'S.customer_id = C.id', 'left')
						->where(array('S.user_id' => $user_id, 'S.is_delete' => 0,'S.customer_id' => 307))
						// ->group_start()
						// 		->where(array('S.end_date >=' => $w_d['start_time'], 'S.start_date <=' => $w_d['end_time']))
		                // ->group_end()
		                ->get()
		                ->result_array();

	}

	public function get_schedule($user_id, $w_d)
	{
		return $this->db->select('S.*, U.item_name, C.name')
						->from($this->schedule_table . ' as S')
						->join($this->schedule_item_table . ' as U', 'S.item_id = U.id', 'left')
						->join($this->customer_manage_table . ' as C', 'S.customer_id = C.id', 'left')
						->where(array('S.user_id' => $user_id, 'S.is_delete' => 0))
						->group_start()
								->where(array('S.end_date >=' => $w_d['start_time'], 'S.start_date <=' => $w_d['end_time']))
		                ->group_end()
		                ->get()
		                ->result_array();
	}

	public function set_schedule_complete($where_data, $data)
	{
		return $this->db->where($where_data)
						->update($this->schedule_table, $data);
	}

	public function get_all_notify_schedule()
	{
		$where = "start_date <= DATE_ADD(NOW(),INTERVAL 1 HOUR) AND start_date >= NOW() AND L.status = 'normal' AND S.alert = 1";
		return $this->db->select('S.*, L.push_token, U.name, U.email, I.item_name')
						->from($this->schedule_table . ' as S')
						->join($this->user_table . ' as U', 'S.user_id = U.id', 'left')
						->join($this->login_token_table . ' as L', 'L.user_id = U.id', 'left')
						->join($this->schedule_item_table . ' as I', 'I.id = S.item_id', 'left')
						->where($where)
						->get()
						->result_array();
	}

	public function get_item_name(){
		$str="SELECT  id ,item_name from  user_schedule_item  where is_delete=0 order by  id ASC ";

		return $this->db->query($str)->result_array();
	}
}