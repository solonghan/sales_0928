<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Base_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Member_verify_model');
		$this->data['active'] = 'DASHBOARD';
	}

	public function index()
	{
		$pre30day = date('Y-m-d', strtotime("-30 day", strtotime(date("Y-m-d H:i:s"))));
		$this->data['statistic'] =& $this->Flow_record_model->get_statistic($pre30day);
		$this->data['statistic_independent'] =& $this->Flow_record_model->get_statistic_independent($pre30day);

		$this->load->view('mgr/index', $this->data);
	}

	public function logout()
	{
		$this->session->sess_destroy();
		header("Location: ".base_url()."mgr/Login");
	}

	public function changepwd()
	{
		if ($this->input->post('new_pwd') !== NULL)
		{
			if ($this->input->post('new_pwd') != $this->input->post('new_pwd_confirm'))
				$this->js_output_and_back(output_msg('signup_03'));

			$member = $this->Member_info_model->get_member_info_by_mid($this->session->mid);

			if ($member == NULL)
				show_404();

			$msg_num = $this->Member_verify_model->pwd_check($this->input->post('old_pwd'), $member['password']);
			if ($msg_num !== 'msg_01')
				$this->js_output_and_back(output_msg($msg_num));

			$msg_num = $this->Member_info_model->set_member_pwd($this->session->mid, $this->input->post('new_pwd'));

			if ($msg_num === 'msg_01')
				$this->js_output_and_redirect(output_msg('msg_01'), base_url().'mgr');
			else
				$this->js_output_and_back(output_msg($msg_num));
		}
		else
		{
			$this->data['field'] = $this->Datas_form_model->changepwd_field;
			$this->load->view('mgr/changepwd', $this->data);
		}
	}

	public function lock()
	{
		$this->session->set_userdata(
			array("lock" =>	$this->encryption->encrypt("B".$this->session->email))
		);
		$this->load->view('mgr/lock', $this->data);
	}

	public function unlock()
	{
		if ($this->input->post('password') !== NULL)
		{
			if ($this->session->has_userdata('email') AND
				$this->session->has_userdata('lock') AND
				$this->encryption->decrypt($this->session->lock) == 'B'.$this->session->email)
			{
				$member = $this->Member_info_model->get_member_info_by_email($this->session->email);
				if ($member != NULL)
				{
					$msg_num = $this->Member_verify_model->pwd_check($this->input->post('password'), $member->password);
					if ($msg_num === '901')
					{
						$this->session->unset_userdata('lock');
						header('Location: '.base_url().'mgr');
					}
					else
					{
						$this->js_output_and_back(output_msg($msg_num));
					}
				}
				else
				{
					$this->logout();
				}
			}
		}
		else
		{
			
			$this->lock();
		}
	}

	public function img_upload()
	{
		$this->load->model("Pic_model");
		$dir = ($this->input->post("dir")) ? $this->input->post("dir") : "uploads/" ;
		$path = $this->Pic_model->crop_img_upload($dir);
		echo $path;
	}

	public function upload_pic()
	{
		$this->load->model("Pic_model");
		$url = $this->Pic_model->upload_pics("pic", 1);
		echo base_url().$url[0];
	}

}
