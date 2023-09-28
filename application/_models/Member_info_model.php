<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_info_model extends Base_Model {

	public function get_member_info_by_email($email)
	{
		$this->db->select('*');
		$this->db->from($this->member_table);
		$this->db->where(array('account' => $email));
		return $this->db->get()->row();
	}

	public function get_member_info_by_mid($mid)
	{
		if ($mid == FALSE)
		{
			return NULL;
		}
		return $this->db->get_where($this->member_table, array("id"=>$mid))
						->row_array();
	}

	public function set_member_pwd($mid, $new_pwd)
	{
		$this->db->where(array('id' => $this->encryption->decrypt($mid)))
				 ->update($this->member_table, array('password' => $this->encryption->encrypt(md5($new_pwd))));

		if ($res)
			return 'msg_01';
		else
			return 'from_50';
	}
}