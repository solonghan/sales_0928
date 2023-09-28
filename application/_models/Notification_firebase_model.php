<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_firebase_model extends Base_Model {

	protected const API_ACCESS_KEY 	  = 'AAAAGUk6RZ4:APA91bGufSHfVME_EM4diT8vwqp-CZjM2l6qrpUH99LrizSrGce6NhG2qj2JhBskeDgCrGnzasdYlKwatpd5bacolRCt6rbPC74fgXI6k1DTfvWEEBaQSRJi-AnDyGZXlIU6sroszfg8';
	protected const FIREBASE_PUSH_URL = 'https://fcm.googleapis.com/fcm/send';

	protected $registration_ids =  array();
	protected $title 			=  array();
	protected $body 			=  array();
	protected $headers 			=  array('Authorization: key=' . self::API_ACCESS_KEY, 'Content-Type: application/json');
	protected $message 			=  array('title' => '', 'body' => '', 'sound' => 'default'); 	// text for ios
	protected $fields 			=  array(
			'registration_ids'  => array(),
			'notification' 		=> array(),
			// 'data' 				=> array(), 			// 自定義數據
	);

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Flow_record_model', 'Record');
		$this->_init();
	}

	public function send_push($title, $body, $users, $type = 'web', $data = array())
	{
		if ( ! $this->_field_verify(func_get_args())) return FALSE;

		$this->fields['notification']['title'] = $title;
		$this->fields['notification']['text']  = $body;
		unset($this->fields['notification']['body']);
		// $this->fields['data'] = $data;
		$this->_set_users($users);

		$result = $this->call_api(self::FIREBASE_PUSH_URL, $this->headers, $this->fields);

		return $result;
	}

	private function _init()
	{
		$this->message['title'] 			= $this->title;
		$this->message['body'] 				= $this->body;

		$this->fields['registration_ids'] 	= $this->registration_ids;
		$this->fields['notification'] 		= $this->message;
	}

	private function call_api($url, $headers, $fields)
	{
        $ch = curl_init();  											// Open connection
 
        curl_setopt($ch, CURLOPT_URL, $url);  							// Set the url, number of POST vars, POST data
 
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  				// Disabling SSL Certificate support temporarly
        // curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);  										// Execute post
        if ($result === FALSE) die('Curl failed: ' . curl_error($ch));

        curl_close($ch);

        return json_decode($result, TRUE);
	}

	private function _set_users($users)
	{
		if ( ! is_array($users))
		{
			unset($this->fields['registration_ids']);
			$this->fields['to'] = $users;
		}
		else $this->fields['registration_ids'] = $users;
	}

/* Field Verify - Start */

	private function _field_verify($params)
	{
		if ( ! $this->_verify_title($params[0])) 							return FALSE;
		if ( ! $this->_verify_body($params[1])) 							return FALSE;
		// if ( ! $this->str_arr_distribute('users', $params[2]))  			return FALSE;
		if ( ! $this->_verify_type(($params[3]) ? $params[3] : 'android'))  return FALSE;

		return TRUE;
	}

	private function str_arr_distribute($field, $values)
	{
		$fun = '_verify_' . $field;
		if (is_string($values) OR is_numeric($values))
		{
			return $this->{$fun}($values);
		}
		elseif (is_array($values))
		{
			foreach ($values as $key => $value)
			{
				if ( ! $this->{$fun}($value)) return FALSE;
			}

			return TRUE;
		}
		else return FALSE;
	}

	private function _verify_type($type)
	{
		switch ($type)
		{
			case 'android':
				return TRUE;

			case 'ios':
				return TRUE;

			case 'web':
				return TRUE;

			
			default:
				return FALSE;
		}
	}

	private function _verify_title($title)
	{
		if ( ! is_string($title)) return FALSE;
		else return TRUE;
	}

	private function _verify_body($body)
	{
		if ( ! is_string($body)) return FALSE;
		else return TRUE;
	}

	private function _verify_users($users)
	{
		if ( ! is_numeric($users)) return FALSE;
		else return TRUE;
	}
/* Field Verify - End */
}