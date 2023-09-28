<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_verify_model extends Base_Model {

	public function pwd_check($input_pwd, $db_pws, $status = 'open')
	{
		if ($this->encryption->decrypt($db_pws) == md5($input_pwd))
		{
			if ($status == 'block')
			{
				return 'login_04';
			}
			else
			{
				return 'msg_01';
			}
		}
		else
		{
			return 'login_03';
		}
	}
}