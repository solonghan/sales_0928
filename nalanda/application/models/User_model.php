<?php defined('BASEPATH') or exit('No direct script access allowed');
// include("./phpmailer/class.phpmailer.php");
// require "./phpmailer/PHPMailerAutoload.php";
class User_model extends Base_Model
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Taipei");
    }

    public function send_sms($phone, $destName, $msg)
    {
        $username = "25231711";
        $password = "Pass82962755";
        // $username = "50875169";
        // $password = "Pass82962755";

        $encoding = "UTF8";
        $dlvtime = "";            //預約簡訊YYYYMMDDHHNNSS，若為空則為即時簡訊
        $vldtime = "3600";        //簡訊有效時間YYYYMMDDHHNNSS，整數值為幾秒後內有限，不可超過24hr
        $smsbody = urlencode($msg);
        //簡訊內容，空白直接空白即可，換行請使用 chr(6)
        $response = "";            //簡訊狀態回報網址
        $ClientID = "";            //用於避免重複發送(不太會用到)

        $url = "https://smsapi.mitake.com.tw/api/mtk/SmSend?username=" . $username . "&password=" . $password . "&dstaddr=" . $phone . "&CharsetURL=" . $encoding . "&DestName=" . $destName . "&dlvtime=" . $dlvtime . "&vldtime=" . $vldtime . "&smbody=" . $smsbody . "&response=" . $response . "&ClientID=" . $ClientID;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

        $r = curl_exec($ch);
        curl_close($ch);
        // echo $r;
        // echo $url;
        $this->db->insert("sms_log", array("mobile" => $phone, "content" => $msg, "result" => $r));
    }

    public function send_mail($email, $body, $subject = "")
    {
        $mail = new PHPMailer();

        $mail->IsSMTP();

        // $mail->SMTPDebug = 2;
        // $mail->Host = "localhost";
        $mail->CharSet = "utf-8";

        //Google 寄信
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "ssl";
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 465;
        $mail->Username = "anbon.tw@gmail.com";
        $mail->Password = "vxtseczukfobscgb";

        $mail->From = "anbon.tw@gmail.com";
        $mail->FromName = "ANBONTW";

        $mail->Subject = $subject;

        $mail->IsHTML(true);
        $mail->AddAddress($email, $email);
        $mail->Body = $body;

        if ($mail->Send()) {
            return array("status" => TRUE);
        } else {
            return array("status" => FALSE, "msg" => $mail->ErrorInfo);
        }
        $mail->ClearAddresses();
    }
}
