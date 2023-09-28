<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Line_Notify_model extends Base_Model {
	//瞻新網站群 Token
	private $token = "c6VOAS9QdC3genmjBLbxMoXAuPOCvpPtwTJr6F9MJ9k";

	//安邦個人
	// private $token = "VT4TUIue15I7nZcDR1C4SBfyGt4IUUpVFe9EiWhBzUt";
	
	//紀錄notify排程的TABLE NAME
	private $notify_table = "line_notify";
	//排程執行時，是否把所有訊息合併成一則
	private $is_merge_send = TRUE;
	//排程DB
	/*
	
		CREATE TABLE `line_notify` (
		  `id` int(11) NOT NULL,
		  `msg` varchar(256) NOT NULL,
		  `remarks` longtext NOT NULL,
  		  `create_date` datetime NOT NULL DEFAULT current_timestamp(),
		  `is_send` int(11) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
		ALTER TABLE `line_notify`
		  ADD PRIMARY KEY (`id`);
		ALTER TABLE `line_notify`
		  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
		COMMIT;

	*/

	function __construct(){
		parent::__construct ();
		date_default_timezone_set("Asia/Taipei");
	}

	//message 			訊息
	//is_notify_alarm	是否要跳出提示
	//token 			指定傳送token
	public function send($message, $is_notify_alarm = TRUE, $token = FALSE){
		if ($token === FALSE) $token = $this->token;

		$headers = array(
		    'Content-Type: multipart/form-data',
		    'Authorization: Bearer '.$token
		);
		$message = array(
			'message'              => 	$message,
			'notificationDisabled' =>	($is_notify_alarm)?'false':'true'
		);
		// print_r($message);
		$ch = curl_init();
		curl_setopt($ch , CURLOPT_URL , "https://notify-api.line.me/api/notify");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
		$result = curl_exec($ch);
		curl_close($ch);
	}

	//設定排程
	public function schedule_cron($message, $remarks = ""){
		$data = array(
			"message"	=>	$messagem,
			"remarks"	=>	$remarks
		);
		if ($this->db->insert($this->notify_table, $data)) {
			return TRUE;
		}else{
			return FALSE;
		}
	}

	//執行排程  這個function請丟給執行排程的那支controller
	public function execute_cron(){
		$data = $this->db->order_by("create_date ASC")->get_where($this->notify_table, array("is_send"=>0))->result_array();
		if (count($data) <= 0) return;

		if ($this->is_merge_send) {
			$message = "";
			foreach ($data as $item) {
				if ($message != "") $message .= "\n\n------------\n\n";
				$message .= $item['msg'];
			}
			$this->send($message);
		}else{
			foreach ($data as $item) {
				$this->send($item['msg']);
			}
		}
	}
}