<?php defined('BASEPATH') or exit('No direct script access allowed');


class  Base_Model  extends  CI_Model
{
    protected $adv_table                   = 'adv';
    protected $blacklist_table             = 'blacklist';
    protected $cart_table                  = 'cart';
    protected $cart_snapshot_table         = 'cart_snapshot';
    protected $chat_table                  = 'chat';
    protected $chatroom_table              = 'chatroom';
    protected $chatroom_user_table         = 'chatroom_user';
    protected $chatroom_user_dropout_table = 'chatroom_user_dropout';
    protected $chat_head_table             = 'chat_head';
    protected $coin_log_table              = 'coin_log';
    protected $comment_table               = 'comment';
    protected $commodity_table             = 'commodity';
    protected $discover_table              = 'discover';
    protected $discover_read_table         = 'discover_read';
    protected $event_table                 = 'event';
    protected $friend_table                = 'friend';
    protected $gift_table                  = 'gift';
    protected $item_table                  = 'item';
    protected $level_table                 = 'level';
    protected $lottery_table               = 'lottery';
    protected $mark_table                  = 'mark';
    protected $mark_user_table             = 'mark_user';
    protected $merchant_table              = 'merchant';
    protected $mission_table               = 'mission';
    protected $notification_table          = 'notification';
    protected $photo_table                 = 'photo';
    protected $post_table                  = 'post';
    protected $priv_menu_table             = 'privilege_menu';
    protected $push_token_table            = 'push_token';
    protected $report_table                = 'report';
    protected $search_log_table            = 'search_log';
    protected $sms_log_table               = 'sms_log';
    protected $story_table                 = 'story';
    protected $subscribe_table             = 'subscribe';
    protected $task_table                  = 'task';
    protected $unlock_table                = 'unlock';
    protected $user_table                  = 'user';
    protected $user_active_log_table       = 'user_active_log';
    protected $user_adv_log_table          = 'user_adv_log';
    protected $user_item_table             = 'user_item';
    protected $user_item_log_table         = 'user_item_log';
    protected $user_seen_story_table       = 'user_seen_story';
    protected $user_unlock_log_table       = 'user_unlock_log';
    protected $video_table                 = 'video';

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Taipei");
    }

    public function dateStr($date)
    {
        $date = strtotime($date);
        if ((time() - $date) < 60 * 10) {
            //十分鐘內
            return '剛剛';
        } elseif (((time() - $date) < 60 * 60) && ((time() - $date) >= 60 * 10)) {
            //十分鐘~1小時
            $s = floor((time() - $date) / 60);
            return  $s . "分鐘前";
        } elseif (((time() - $date) < 60 * 60 * 24) && ((time() - $date) >= 60 * 60)) {
            //1小時～24小時
            $s = floor((time() - $date) / 60 / 60);
            return  $s . "小時前";
        } elseif (((time() - $date) < 60 * 60 * 24 * 3) && ((time() - $date) >= 60 * 60 * 24)) {
            //1天~3天
            $s = floor((time() - $date) / 60 / 60 / 24);
            return $s . "天前";
        } else {
            //超过3天
            if (date('Y', strtotime($date)) == date('Y')) {
                //今年
                return date("m/d H:i", $date);
            } else {
                return date("Y/m/d", $date);
            }
        }
    }

    public function send_push($os, $registatoin_ids, $message, $data = FALSE)
    {
        // if ($os == "android") {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $title = "BBTruck";
        $fields = array('to' => $registatoin_ids);

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
                'title' =>    $title,
                'text'  =>    $message
            );
            if ($data !== FALSE) {
                $fields['notification'] = array_merge($fields['notification'], $data);
            }
        }

        // if ($data !== FALSE) {
        // 	$fields = array_merge($fields, $data);
        // }

        $headers = array(
            'Authorization: key=AAAA9Sg37_w:APA91bFH3jxJe8pkoboujnmqOFGUS1xp0goqlCPvZDzL1KfkFTHUy4wE89UI7inoGv2KZsaI2-1gBIn1qZ1q7mDvHXcR3jV7IoYtv4qyCdX0kl3EDwQbv8SXFxtc9mcHtyByJOtrAeHW',
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

        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);

        $result = json_decode($result, true);

        return $result['success'];
    }

    protected function custom_encrypt($string, $operation, $key = 'KeyyE')
    {
        $replcae_str = "_Sl.";
        if ($operation == 'D') {
            $string = str_replace($replcae_str, "/", $string);
        }
        $key = md5($key);
        $key_length = strlen($key);
        $string = $operation == 'D' ? base64_decode($string) : substr(md5($string . $key), 0, 8) . $string;
        $string_length = strlen($string);
        $rndkey = $box = array();
        $result = '';
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($key[$i % $key_length]);
            $box[$i] = $i;
        }
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if ($operation == 'D') {
            if (substr($result, 0, 8) == substr(md5(substr($result, 8) . $key), 0, 8)) {
                return substr($result, 8);
            } else {
                return '';
            }
        } else {
            $encryt_str = str_replace('=', '', base64_encode($result));
            $encryt_str = str_replace("/", $replcae_str, $encryt_str);
            return $encryt_str;
        }
    }

    protected function generate_code($length = 6, $only_degital = FALSE)
    {
        $alphabet_upper = range('A', 'Z');
        $alphabet_lower = range('a', 'z');
        $s = "";
        for ($i = 0; $i <= 9; $i++) $s .= strval($i);
        if (!$only_degital) {
            foreach ($alphabet_upper as $a) $s .= $a;
            // $s .= '_';
            for ($i = 0; $i <= 9; $i++) $s .= strval($i);
            foreach ($alphabet_lower as $a) $s .= $a;
            for ($i = 0; $i <= 9; $i++) $s .= strval($i);
            // $s .= '@';
        }

        $cnt = strlen($s);

        $code = "";
        for ($i = 0; $i < $length; $i++) {
            $code .= substr($s, rand(0, $cnt - 1), 1);
        }
        return $code;
    }
}
