<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/CreatorJwt.php';

class Jwt_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->obj = new CreatorJwt();
	}

	/*
	  generate token
	*/
	// public function login_token($type, $event_uid,$user_id)
	// {

	// 	//save data to jwt
		
	// 	$tokenData['event_uid'] = $event_uid;

	// 	//type= buyer exhibitor
	// 	$tokenData['type'] = $type;

	// 	//type = buyer ==> user_id = buyer_uid
	// 	//type = exhibitor ==> user_id = exhibitor_uid
	// 	$tokenData['user_id'] = $user_id;

	// 	//set time
	// 	$tokenData['create_time'] = Date('Y-m-d H:i:s');
	// 	$tokenData['expired_time'] = Date('Y-m-d H:i:s',strtotime('+2 hours'));

	// 	//建立token
	// 	$jwtToken = $this->obj->GenerateToken($tokenData);
	// 	return (array('Token' => $jwtToken));
	// }

	public function login_token($id)
	{
		$tokenData['user_id'] 		= $id;
		$tokenData['create_time'] 	= Date('Y-m-d H:i:s');
		$tokenData['expired_time'] 	= Date('Y-m-d H:i:s',strtotime('+24 hours'));
		
		// $jwtToken = $this->obj->GenerateToken($tokenData);
		// return (array('Token' => $jwtToken));
		return $this->obj->GenerateToken($tokenData);
	}

	/*
	  verify token from headers
	*/
	public function verify_token($token)
	{
		
		$decodedData = $this->obj->DecodeToken($token);
		
		return $decodedData;
	}
	
	/*
	  verify token from headers
	*/
	public function GetTokenData()
	{
		$received_Token = $this->input->request_headers('Authorization');
		try {
			$jwtData = $this->obj->DecodeToken($received_Token['Token']);
			echo json_encode($jwtData);
		} catch (Exception $e) {
			http_response_code('401');
			echo json_encode(array("status" => false, "message" => $e->getMessage()));
			exit;
		}
	}    
}
