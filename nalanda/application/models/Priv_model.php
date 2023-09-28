<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Priv_model extends Base_Model {

	function __construct(){
		parent::__construct ();
		date_default_timezone_set("Asia/Taipei");
	}

	public function priv_menu(){
		$list = $this->db->order_by("parent_id ASC, sort DESC")->get_where($this->priv_menu_table, array("status"=>"on"))->result_array();

		$data = array();
		foreach ($list as $item) {
			if ($item['parent_id'] == 0) {
				$data[$item['id']] = array(
					"function" =>	$item['function'],
					"name"     =>	$item['name'],
					"icon"     =>	$item['icon'],
					"url"      =>	$item['url'],
					"badge"    =>	0,
					"sub_menu" =>	array()
				);
			}else{
				$data[$item['parent_id']]['sub_menu'][] = array(
					"function" =>	$item['function'],
					"name"     =>	$item['name'],
					"icon"     =>	$item['icon'],
					"url"      =>	$item['url'],
					"badge"    =>	0
				);
			}
		}
		return $data;
	}

	public function get_all_priv(){
		return $this->db->order_by("id asc")->get_where($this->priv_table, array("is_delete"=>0))->result_array("id");
	}

	public function get_priv_list($syntax, $order_by, $page = 1, $page_count = 20){
		$total = $this->db->where($syntax)->get($this->priv_table)->num_rows();
		$total_page = ($total % $page_count == 0) ? floor(($total)/$page_count) : floor(($total)/$page_count) + 1;

		$list = $this->db->select("*")
						 ->from($this->priv_table)
						 ->where($syntax)
						 ->order_by($order_by)
						 ->limit($page_count, ($page-1)*$page_count)
						 ->get()->result_array();
		
		return array(
			"total"      =>	$total,
			"total_page" =>	$total_page,
			"list"       =>	$list
		);
	}

	public function member_edit($id, $data, $is_multi = FALSE){
		if ($is_multi) {
			return $this->db->update_batch($this->member_table, $data, "id");
		}else{
			return $this->db->where(array("id"=>$id))->update($this->member_table, $data);
		}
	}

	public function member_add($data, $is_multi = FALSE){
		if ($is_multi) {
			return $this->db->insert_batch($this->member_table, $data);
		}else{
			$res = $this->db->insert($this->member_table, $data);
			if ($res) return $this->db->insert_id();
			return FALSE;
		}
	}

	public function get_member_data($id){
		return $this->db->get_where($this->member_table, array("id"=>$id))->row_array();
	}

	public function get_member_list($syntax, $order_by, $contain_log = TRUE, $page = 1, $page_count = 20){
		$total = $this->db->where($syntax)->get($this->member_table)->num_rows();
		$total_page = ($total % $page_count == 0) ? floor(($total)/$page_count) : floor(($total)/$page_count) + 1;

		$list = $this->db->select("*")
						 ->from($this->member_table)
						 ->where($syntax)
						 ->order_by($order_by)
						 ->limit($page_count, ($page-1)*$page_count)
						 ->get()->result_array();
		if ($contain_log) {
			foreach ($list as $key => $item) {
				$log = $this->db->order_by("id desc")->get_where($this->log_record_table, array("member_id"=>$item['id']))->row_array();
				if ($log == null) {
					$list[$key]['last_action'] = "";
					$list[$key]['last_action_datetime'] = "";
				}else{
					$list[$key]['last_action'] = $log['msg'];
					$list[$key]['last_action_datetime'] = $this->dateStr($log['create_date']);//date("m/d H:i", strtotime($log['create_date']));
				}
			}
		}
		
		return array(
			"total"      =>	$total,
			"total_page" =>	$total_page,
			"list"       =>	$list
		);
	}
}