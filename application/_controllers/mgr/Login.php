<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data['error'] = '';
		
		$this->load->model('Member_info_model');
		$this->load->model('Member_verify_model');

		if ($this->input->post('email') !== NULL)
		{
			$pwd = $this->input->post('password');

			$verify_info = $this->Member_info_model->get_member_info_by_email($this->input->post('email'));
			
			if ($verify_info != NULL)
			{
				$msg_num = $this->Member_verify_model->pwd_check($pwd, $verify_info->password, $verify_info->status);

				if ($msg_num == 'msg_01')
				{
					$this->load->helper('datas_transform');
					$priv_name = privilege_to_str($verify_info->privilege);

					// set session
					$this->session->set_userdata(array(
							"isMgrlogin" =>	$this->encryption->encrypt(md5("MGRLOGIN")),
							"p"          => $this->encryption->encrypt($verify_info->privilege),  	// 權限
							"name"       =>	$verify_info->name,
							"mid"        =>	$this->encryption->encrypt($verify_info->id),  	// mid = member id
							"avatar"     =>	$verify_info->avatar,  	// 登錄者圖片
							"email"      =>	$verify_info->account,
							"priv_name"  => $priv_name
					));

					header('Location: '.base_url().'mgr');
				}
				else
				{
					$data['error'] = output_msg($msg_num);
				}
			}
			else
			{
				$data['error'] = output_msg('login_02');
			}
		}

		$this->load->view('mgr/login', $data);
	}

	public function _remap($method, $params = array())
	{
	    if (method_exists($this, $method))
	    {
	        return call_user_func_array(array($this, $method), $params);
	    }
	    else
	    {
	    	return call_user_func_array(array($this, "index"), $params);
	    }
	}
}