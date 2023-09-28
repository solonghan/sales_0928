<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mail_model extends Base_Model {

	private $send_to_contact = array(
			'anbonbackend2021@gmail.com' 	=> '暫時密碼通知',
			'chenzenyang2021@gmail.com' 	=> '暫時密碼通知',
	);


	public function __construct()
	{
		parent::__construct();
	}


	public function send_mail($email, $body, $subject = '')
	{
		try
		{
			$mail = new PHPMailer(); 							// 建立新物件        

	 		$mail->IsSMTP(); 									// 設定使用SMTP方式寄信        
			$mail->SMTPAuth = true; 							// 設定SMTP需要驗證
			$mail->SMTPSecure = 'ssl'; 							// Gmail的SMTP主機需要使用SSL連線   
			//$mail->SMTPDebug = 1;
			$mail->Host = 'smtp.gmail.com'; 					// Gmail的SMTP主機        
			$mail->Port = 465; 									// Gmail的SMTP主機的port為465      
			$mail->CharSet = 'utf-8'; 							// 設定郵件編碼   
			// $mail->SMTPDebug = 2;  
			// $mail->Username = 'anbonbackend20220701@gmail.com'; 	// 設定驗證帳號        
			// $mail->Password = 'twaccqprzbyxwjjh'; 					// 設定驗證密碼        
				  
			// $mail->From = 'anbonbackend20220701@gmail.com'; 		// 設定寄件者信箱        
			// $mail->FromName = 'Anbon Backend'; 					// 設定寄件者姓名 

			$mail->Username = "twm0929288035@gmail.com";
			$mail->Password = "pcgogcvtmcapgqnh";     
				  
			$mail->From = "sss9611300@yahoo.com.tw";
			$mail->FromName = "sales";
				  
			$mail->Subject = $subject; 							// 設定郵件標題        
			  
			$mail->IsHTML(true); 								// 設定郵件內容為HTML       
			$mail->AddAddress($email, $email); 					// 收件者郵件及名稱 ***改這個
			$mail->Body = $body;

			$mail->Send();


		}
		catch (Exception $e)
		{
			return $mail->ErrorInfo;
			// exit;
		}

		$mail->ClearAddresses();
	}


	// ------------------------------------------------------------------------------------------


	public function send_contact($data)
	{
		$result = array();
		foreach ($this->send_to_contact as $key => $value)
		{
			$result[$key] = $this->send_mail($key, $value, $value);
			unset($result[$key]['is_response']);
		}

		return $this->_check($result);
	}

	private function _check($res)
	{
		$success = TRUE;
		foreach ($this->send_to_contact as $key => $value)
		{
			if ($res[$key]['code'] != 200)
				$success = FALSE;
		}

		return array_merge(array('data' => $res), array(
				'code' 		=> ($success ? 200 : 400),
				'message' 	=> ($success ? output_msg('send_mail_01') : output_msg('send_mail_02')),
		));
	}
}