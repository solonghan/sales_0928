<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Base_Controller extends CI_Controller {

	protected $data = array('active' => '');

	public function __construct()
	{
		parent::__construct();

		if (strpos($_SERVER['REQUEST_URI'], '/mgr') !== FALSE)
		{
			$this->is_member_login();

			$this->load->model('Member_info_model');
			$this->load->model('Member_menu_model');

			$this->data['web_title'] = 'Jenny Chou management';
			$this->data['active'] = strtoupper($this->uri->segment(2));
			$this->data['menu'] = $this->Member_menu_model->get_menu($this->Member_info_model->get_member_info_by_mid($this->encryption->decrypt($this->session->mid)));
		}
		else
		{
			$this->data['active'] = strtolower($this->uri->segment(3));
		}
	}

	public function entrance_view($page = 'index')
	{
		if ( ! file_exists(APPPATH.'_views/'.$page.'.php'))
			show_404();
		
		$this->load->view($page, $this->data);
	}

    protected function get_client_ip()
    {
        $ipaddress = '';

        if (isset($_SERVER['HTTP_CLIENT_IP']))
        {
        	$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        }
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
        	$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
        {
        	$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        }
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        {
        	$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        }
        else if (isset($_SERVER['HTTP_FORWARDED']))
        {
        	$ipaddress = $_SERVER['HTTP_FORWARDED'];
        }
        else if (isset($_SERVER['REMOTE_ADDR']))
        {
        	$ipaddress = $_SERVER['REMOTE_ADDR'];
        }
        else
        {
        	$ipaddress = 'UNKNOWN';
        }
        return $ipaddress;
    }

	protected function response_json($code, $msg, $data = FALSE)
	{
		if ($data === FALSE)
		{
			echo json_encode(array('status' => $code, 'msg' => $msg));
		}
		else
		{
			$data['status'] = $code;
			$data['msg'] = $msg;
			echo json_encode($data);
		}
		exit();
	}

	protected function &set_search_lists($search, $lists,array $canbe)
	{
		foreach ($lists as $key_p => $item)
		{
			foreach ($item as $key_c => $value)
			{
				if (in_array($key_c, $canbe))
				{
					$lists[$key_p][$key_c] = preg_replace('/'.$search.'/i', '<mark data-markjs="true">'.$search.'</mark>', $item[$key_c]);
				}
			}

		}
		return $lists;
	}

	public function process_post_data($param)
	{
		$data = array();
		foreach ($param as $item)
		{
			if ($item[2] == "" || $item[2] == "show_img" || $item[2] == "header" || $item[2] == "select_multi" ||
				$item[2] == "img_multi_without_crop" || $item[2] == "img_multi" || $item[2] == "plain")
				continue;

			if ($item[2] == "checkbox_multi")
			{
				$data[$item[1]] = (is_array($this->input->post($item[1])) && count($this->input->post($item[1])) > 0)?serialize($this->input->post($item[1])):serialize(array());	
			}
			elseif ($item[2] == "checkbox")
			{
				$data[$item[1]] = ($this->input->post($item[1]) == "on")?1:0;
			}
			elseif ($item[2] == "file")
			{
				if ($_FILES[$item[1]]['error'] != 4 && $this->input->post($item[1]) == '')
				{
					$dir = 'uploads/';
					$this->upload->initialize($this->set_upload_options($dir));
				    $this->upload->do_upload($item[1]);
					$idata = $this->upload->data();
					$data[$item[1]] = $dir.$idata['file_name'];
				}						
			}
			else if ($item[2] == "city")
			{
				$city = $this->get_zipcode()['city'];

				$data['city'] = $this->input->post("city");
				$data['dist'] = $this->input->post("dist");

				$data['city_str'] = $city[$data['city']]['name'];

				$dist_key = array_search($data['dist'], array_column($city[$data['city']]['dist'], 'c3'));
				$data['dist_str'] = $city[$data['city']]['dist'][$dist_key]['name'];
			}
			else
			{
				$data[$item[1]] = $this->input->post($item[1]);	
			}
		}
		return $data;
	}

	public function put_imgs_in_json($data, $field, $array)
	{
		$temp = array();
		foreach ($array as $value)
		{
			array_push($temp, $data[$value]);
			unset($data[$value]);
		}
		$data[$field] = json_encode($temp);
		return $data;
	}


	protected function js_output_and_back($msg)
	{
		echo "<script> alert('".$msg."'); history.back(); </script>";
		exit();
	}

	protected function js_output_and_redirect($msg, $url)
	{
		echo "<script> alert('".$msg."'); location.href='".$url."'; </script>";
	}

	protected function is_member_lock()
	{
		if ($this->session->has_userdata('lock') && 
			$this->session->has_userdata('isMgrlogin') && 
			$this->encryption->decrypt($this->session->isMgrlogin) == md5("MGRLOGIN"))
		{
			header('Location: '.base_url().'mgr/lock');
		}
	}

	protected function is_member_login()
	{
		$this->is_member_lock();

		if ( ! ($this->session->has_userdata('isMgrlogin') AND 
				($this->encryption->decrypt($this->session->isMgrlogin) == md5('MGRLOGIN'))))
		{
			header('Location: '.base_url().'mgr/Login');
		}
	}

	protected function set_upload_options($dir = 'uploads/', $allowed_types = '*')
	{   
        if ( ! file_exists($dir))
        {
            $oldmask = umask(0);
            mkdir($dir, 0755);
            umask($oldmask);
        }

        $config = array();
		$config['upload_path']   = $dir;
		$config['allowed_types'] = $allowed_types;  	// 'gif|jpg|png|jpeg'
		$config['max_size']	     = '0';  				// 0 表示不限制
		$config['overwrite']     = FALSE;
		$config['encrypt_name']  = TRUE;  				// 上傳檔名將會被隨機的加密字串取代

        return $config;
    }

    final public function call_api($url, $headers, $fields)
	{
        $ch = curl_init();  											
 
        curl_setopt($ch, CURLOPT_URL, $url);  							
 
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  				
        // curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);  										
        if ($result === FALSE) die('Curl failed: ' . curl_error($ch));

        curl_close($ch);

        return json_decode($result, TRUE);
	}
}