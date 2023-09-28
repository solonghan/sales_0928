<?php defined('BASEPATH') or exit('No direct script access allowed');

class Notification_model extends Base_Model
{
    private $page_count = 30;
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Taipei");
    }

    public function send_user_push($user_id, $msg, $data = FALSE)
    {
        $user = $this->db->select("L.os, L.push_token")
            ->from($this->user_table . " U")
            ->join($this->push_token_table . " L", "L.user_id = U.id", "left")
            ->where(array("U.id" => $user_id))
            ->get()->last_row("array");
        if ($user['os'] != null && $user['push_token'] != "")
            return $this->send_push($user['os'], $user['push_token'], $msg, $data);
    }

    public function send_push($os, $registatoin_ids, $message, $data = FALSE)
    {
        // if ($os == "android") {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $title = "起笑";
        if (is_array($message)) {
            $title = $message["title"];
            $message = $message["content"];
        }
        if (is_array($registatoin_ids)) {
            $fields = array('registration_ids' => $registatoin_ids);
        } else {
            $fields = array('to' => $registatoin_ids);
        }

        //皆為 firebase 平台
        if ($os == "android") {
            $fields['data'] = array(
                'title'   =>    $title,
                'message' =>    $message
            );
            if ($data !== FALSE) {
                $fields['data'] = array_merge($fields['data'], $data);
            }
        } else if ($os == "ios") {
            $fields['notification'] = array(
                'title' => $title,
                'body'  => $message,
                'text'  => $message,
                'sound' => "default"
            );
            if ($data !== FALSE) {
                $fields['notification'] = array_merge($fields['notification'], $data);
            }
        }

        // if ($data !== FALSE) {
        // 	$fields = array_merge($fields, $data);
        // }
        // echo json_encode($fields);
        $headers = array(
            'Authorization: key=AAAABhgV9_A:APA91bFrM3LYGNh_4I1kOG89McXxqTUbxi8GuX-2Q96IcX-LEvyp2ZP7K2pPZhcmoJJU3C0pwg9FI5Ah4LDTOVdtoTuFuTQ6lP9Kvyr4tCFvtTv5sY4t8nz5qX2Cw7pC_MpfbDRdIXE-',
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        // echo "[".$result."]";
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);

        $result = json_decode($result, true);

        return $result;
    }
}
