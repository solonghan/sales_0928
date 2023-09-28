<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Base_Controller {

	public function __construct(){
		parent::__construct();	
	}

	public function now(){
		$this->output(TRUE, "success", array(
			"date"           =>	date('Y-m-d H:i:s'),
			"formatted_time" =>	date("M d, Y l H:i:s")
		));
	}

	public function index()
	{
		$this->is_mgr_login();
		$this->data['active'] = "dashboard";

		
		$pre30day = date('Y-m-d', strtotime("-30 day", strtotime(date("Y-m-d H:i:s"))));
		$this->data['statistic'] = $this->db->select("count(id) as value, SUBSTRING_INDEX(`create_date`, ' ', 1) as date")->from("flow")->where(array("create_date>="=>$pre30day))->group_by("SUBSTRING_INDEX(`create_date`, ' ', 1)")->get()->result_array();
		$this->data['statistic_independent'] = $this->db->select("count(id) as value, SUBSTRING_INDEX(`create_date`, ' ', 1) as date")->from("flow")->where(array("create_date>="=>$pre30day, "enter"=>"home"))->group_by("SUBSTRING_INDEX(`create_date`, ' ', 1)")->get()->result_array();
		
		$this->load->view('mgr/index_2', $this->data);
	}

	public function changepwd(){
		if ($_POST) {
			$old_pwd         =	$this->input->post("old_pwd");
			$new_pwd         =	$this->input->post("new_pwd");
			$new_pwd_confirm =	$this->input->post("new_pwd_confirm");

			if ($new_pwd != $new_pwd_confirm || $new_pwd == "") {
				$this->js_output_and_back("兩次輸入密碼不同");
			}

			$member = $this->db->get_where("member", array("id"=>$this->encryption->decrypt($this->session->id)))->row_array();
			if ($member == null) show_404();

			if (md5($old_pwd) != $this->encryption->decrypt($member['password'])) {
				$this->js_output_and_back("密碼輸入錯誤");
			}

			$res = $this->db->where(array("id"=>$this->encryption->decrypt($this->session->id)))->update("member", array("password"=>$this->encryption->encrypt(md5($new_pwd))));
			if ($res) {
				$this->js_output_and_redirect("密碼變更成功", base_url()."mgr");
			}else{
				$this->js_output_and_back("發生錯誤");
			}
		}else{
			$this->load->view("mgr/changepwd", $this->data);
		}
	}

	public function send_email(){
		$email   = $this->input->post("email");
		$subject = $this->input->post("subject");
		$content = $this->input->post("content");

		$this->Member_model->send_mail($email, $content, $subject);

		$this->output(100, "信件已發送");
	}

	public function login(){
		$data['error'] = "";

		if ($this->input->post("email")) {
			$this->db->select("*");
			$this->db->from("member");
			$this->db->where(array("account"=>$this->input->post("email")));
			$r = $this->db->get()->row();
			// echo $this->encryption->encrypt(md5($this->input->post("password")));
			if ($r != null) {
				if ($this->encryption->decrypt($r->password) == md5($this->input->post("password"))) {
					if ($r->status == "block") {
						$data['error'] = "您無權限登入";
					}else{
						$priv_name = "";
						if ($r->privilege == "super") {
							$priv_name = "最高權限管理員";
						}
						$this->session->set_userdata(array(
							"isMgrlogin" =>	$this->encryption->encrypt(md5("MGRLOGIN")),
							"p"          => $this->encryption->encrypt($r->privilege),
							"name"       =>	$r->name,
							"id"         =>	$this->encryption->encrypt($r->id),
							"avatar"     =>	$r->avatar,
							"email"      =>	$r->account,
							"canuse"     =>	$r->canuse,
							"priv_name"	 => $priv_name
						));
						$this->log_record_with_user_id($r->id, "登入系統");
						header("Location: ".base_url()."mgr");
						return;	
					}
				}else{
					$data['error'] = "密碼錯誤";
				}
			}else{
				$data['error'] = "查無此帳號";
			}
		}

		$this->load->view('mgr/login', $data);
	}

	public function logout(){
		$this->session->sess_destroy();
		
		header("Location: ".base_url()."mgr/login");
	}

	public function lock(){
		$this->session->set_userdata(array(
			"lock"	=>	$this->encryption->encrypt("B".$this->session->email)
		));

		$this->load->view('mgr/lock');
	}

	public function unlock(){
		$password = md5($this->input->post("password"));
		if ($this->session->has_userdata('email') && $this->session->has_userdata('lock') && $this->encryption->decrypt($this->session->lock) == "B".$this->session->email) {
			$email = $this->session->email;
			$lock = $this->session->lock;
					
			$this->db->select("*");
	        $this->db->from("member");
	        $this->db->where("`account`='{$email}'");
			$q = $this->db->get();
			
			if ($q->num_rows() > 0) {
				$r = $q->row();
				if ($this->encryption->decrypt($r->password) == $password) {
					$this->session->unset_userdata('lock');
					header("Location: ".base_url()."mgr");
				}else{
					header("Location: ".base_url()."mgr/lock");
				}
			}else{
				$this->logout();
			}
		}else{
			$this->logout();
		}
	}

	public function img_upload(){
		$this->load->model("Pic_model");
		$path = $this->Pic_model->crop_img_upload();
		echo $path;
	}

	public function file_upload()
	{
		$this->load->model("Pic_model");
		$path = $this->Pic_model->file_upload("files");
		$data = array();
		require('./vendor/autoload.php');
			$data[] = array(
				"id"       => $path,
				"realname" => $_FILES['files']['name'],
				"url"      => $path
			);
		$this->output(TRUE, "success", array("data" => $data));
	}
}
