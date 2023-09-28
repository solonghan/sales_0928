<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/CreatorJwt.php';

class Jwt_model extends CI_Model {

	public function __construct(){
		parent::__construct();
		$this->jwt = new CreatorJwt();
	}

	/*
	  generate token 
	*/

	public function generate_token($data, $expired_hour = 24){
		$data['create_time'] = date('Y-m-d H:i:s');
		// $data['expired_time'] = date('Y-m-d H:i:s', strtotime('+ '.$expired_hour.' hours'));

		return $this->jwt->GenerateToken($data);
	}

	/*
	  verify token from parameter
	*/
	public function verify_token($token){
		return $this->jwt->DecodeToken($token);
	}
	
	/*
	  verify token from headers
	*/

	public function GetTokenData()
	{
		$received_Token = $this->input->request_headers('Authorization');
		try {
			$jwtData = $this->jwt->DecodeToken($received_Token['Token']);
			echo json_encode($jwtData);
		} catch (Exception $e) {
			http_response_code('401');
			echo json_encode(array("status" => false, "message" => $e->getMessage()));
			exit;
		}
	}    
}
