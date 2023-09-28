<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
header('Access-Control-Allow-Origin: https://anbon.works');
header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");
header('Access-Control-Allow-Credentials: true');

class  Base_Controller  extends  CI_Controller
{
	protected $data = array("active" => "");
	// protected $lang;

	private $DEBUG_MODE = TRUE;

	protected $page_count = 25;

	protected $log_record_table = "log_record";

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("Asia/Taipei");

		// global $RTR;
		// $this->lang = $RTR->language;
		// $this->data['lang'] = $this->lang;


		if (strpos($_SERVER['REQUEST_URI'], '/mgr') !== FALSE) {
			$this->load->model("Priv_model");
			$this->data['nav'] = $this->Priv_model->priv_menu();
		} else {
			// $this->process_lang();
			$this->data['is_login'] = $this->is_login();
		}

		if ($this->session->isLogin && $this->encryption->decrypt($this->session->isLogin) == md5("uLogIn")) {
			$this->data['isLogin'] = TRUE;
		} else {
			$this->data['isLogin'] = FALSE;
		}
	}

	protected function adv_option_generate()
	{
		$adv_option = array();
		$adv_option['age'] = array(
			'title' => '目標客群年齡',
			'option' => array(
				'1' => '18以下',
				'2' => '19-24',
				'3' => '25-30',
				'4' => '31-40',
				'5' => '41-55',
				'6' => '56以上',
			),
		);

		$adv_option['area'] = array(
			'title' => '目標客群地區',
			'option' => array(
				'-1' => '全台',
				'1'  => '基隆市',
				'2'  => '台北市',
				'3'  => '新北市',
				'4'  => '桃園市',
				'5'  => '新竹市',
				'6'  => '新竹縣',
				'7'  => '苗栗縣',
				'8'  => '台中市',
				'9'  => '彰化縣',
				'10' => '南投縣',
				'11' => '雲林縣',
				'12' => '嘉義市',
				'13' => '嘉義縣',
				'14' => '台南市',
				'15' => '高雄市',
				'16' => '屏東縣',
				'17' => '宜蘭縣',
				'18' => '花蓮縣',
				'19' => '台東縣',
				'20' => '澎湖縣',
				'21' => '金門縣',
				'22' => '連江縣',
			),
		);

		$adv_option['sex'] = array(
			'title' => '目標客群性別',
			'option' => array(
				'0'  => '女生',
				'1'  => '男生',
				'-1' => '不拘',
			),
		);
		return $adv_option;
	}

	protected function first_option_generate()
	{
		$first_option = array();
		$first_option['area'] = array(
			'title'  => '所在區域',
			'option' => array(
				'1'  => '基隆市',
				'2'  => '台北市',
				'3'  => '新北市',
				'4'  => '桃園市',
				'5'  => '新竹市',
				'6'  => '新竹縣',
				'7'  => '苗栗縣',
				'8'  => '台中市',
				'9'  => '彰化縣',
				'10' => '南投縣',
				'11' => '雲林縣',
				'12' => '嘉義市',
				'13' => '嘉義縣',
				'14' => '台南市',
				'15' => '高雄市',
				'16' => '屏東縣',
				'17' => '宜蘭縣',
				'18' => '花蓮縣',
				'19' => '台東縣',
				'20' => '澎湖縣',
				'21' => '金門縣',
				'22' => '連江縣',
			),
		);
		return $first_option;
	}

	//Param
	//Column 	Description
	// 0		欄位中文名稱
	// 1		DB資料庫欄位
	// 2		類型
	// 3 		預設值(若為select則是 [value, text])
	// 4		是否必填 TRUE/FALSE
	// 5		提示文字
	// 6		其它條件設定 (Optional)
	//				select 			=>	[value代的欄位值, text代的欄位值]
	//				img,img_multi 	=>	data ratio
	public function process_post_data($param)
	{
		$data = array();
		foreach ($param as $item) {
			if ($item[2] == "header" || $item[2] == "select_multi" || $item[2] == "img_multi_without_crop" || $item[2] == "img_multi" || $item[2] == "plain") continue;

			if ($item[2] == "checkbox_multi") {
				$data[$item[1]] = (is_array($this->input->post($item[1])) && count($this->input->post($item[1])) > 0) ? serialize($this->input->post($item[1])) : serialize(array());
			} else if ($item[2] == "checkbox") {
				$data[$item[1]] = ($this->input->post($item[1]) == "on") ? 1 : 0;
				// }else if ($item[2] == "file") {
				// 	if ($_FILES[$item[1]]['error'] != 4 && $this->input->post($item[1]) == "") {
				// 		$dir = 'uploads/';
				// 		$this->upload->initialize($this->set_upload_options($dir));
				// 	    $this->upload->do_upload($item[1]);
				// 		$idata = $this->upload->data();
				// 		$data[$item[1]] = $dir.$idata['file_name'];
				// 	}						
			} else if ($item[2] == "city") {
				$city = $this->get_zipcode()['city'];

				$data['city'] = $this->input->post("city");
				$data['dist'] = $this->input->post("dist");

				$data['city_str'] = $city[$data['city']]['name'];

				$dist_key = array_search($data['dist'], array_column($city[$data['city']]['dist'], 'c3'));
				$data['dist_str'] = $city[$data['city']]['dist'][$dist_key]['name'];
			} else {
				$data[$item[1]] = $this->input->post($item[1]);
			}
		}
		return $data;
	}

	public function set_data_to_param($param, $data)
	{
		for ($i = 0; $i < count($param); $i++) {
			if ($param[$i][1] != "" && array_key_exists($param[$i][1], $data)) {
				if (substr($param[$i][2], 0, 4) == "img_"  || substr($param[$i][2], 0, 7) == "select_") continue;
				// echo $param[$i][2].": ".$data[$param[$i][1]]."<br><br>";
				if ($param[$i][2] == 'city') {
					$param[$i][3] = array($data['city'], $data['dist']);
				} else {
					$param[$i][3] = $data[$param[$i][1]];
				}
			}
		}
		return $param;
	}

	public function log_record_with_user_id($member_id, $msg = '', $remarks = '')
	{
		$this->db->insert($this->log_record_table, array(
			"member_id" =>	$member_id,
			"url"       =>	isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : "",
			"msg"       =>	$msg
		));
	}
	public function log_record($msg = '', $remarks = '')
	{
		$this->log_record_with_user_id($this->encryption->decrypt($this->session->id), $msg, $remarks);
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

	protected function distance($lat1, $lon1, $lat2, $lon2, $unit)
	{
		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		$unit = strtoupper($unit);

		if ($unit == "K") {
			return ($miles * 1.609344);
		} else if ($unit == "N") {
			return ($miles * 0.8684);
		} else {
			return $miles;
		}
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

	public function curl_post($url, $post)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}

	private function lang_list()
	{
		return $this->db->get_where("lang", array("is_show" => 1))->result_array();
	}

	// private function strip_lang() {
	//     $lang = $this->input->get('lang');
	//        if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !$lang) {
	//            return;
	//        }
	//        unset($_GET['lang']);
	//        $params = http_build_query($_GET);
	//        $uri_parts = explode('?', uri_string(), 2);
	//        $url = base_url("$lang/" . $uri_parts[0]);
	//        if ($params) {
	//            $url .= "?$params";
	//        }
	//        header('Location: '.$url);
	//        exit();
	//    }

	// private function process_lang() {
	// 	global $RTR;
	// 	$lang = $RTR->language;
	// 	if ($lang) {
	// 		$lang = $this->Lang_model->set_lang($RTR->language);
	// 	}

	// 	if (!$lang) {
	// 	    // $this->strip_lang();
	// 		$lang = $this->Lang_model->get_lang();
	//            $this->Lang_model->set_lang($lang);
	// 		if ($_SERVER['REQUEST_METHOD'] === 'GET' && !preg_match('/^(fb|g)_/', $this->uri->segment(1))) {
	// 			// redirect("$lang/".$this->uri->uri_string());
	// 			$params = http_build_query($_GET);
	// 	        $uri_parts = explode('?', $this->uri->uri_string(), 2);
	// 	        $url = base_url("$lang/" . $uri_parts[0]);
	// 	        if ($params) {
	// 	            $url .= "?$params";
	// 	        }
	//        		redirect($url);
	// 		}
	// 	}
	// 	$this->data['lang_list'] = $this->lang_list();		
	// 	$this->data['lang'] = $this->ulang = $lang;
	// 	// $this->data['localized'] = $this->Lang_model->localized();
	// }

	public function page_login_required($uri = '')
	{
		if ($uri == '') return FALSE;
		$pages = $this->db->get_where("pages", array("uri" => $uri))->row_array();
		if ($pages == null) return FALSE;
		return ($pages['login_required'] == 1) ? TRUE : FALSE;
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

	public function flow_record($enter, $user_id = 0)
	{
		// if($this->db->where("create_date like '".date("Y-m-d")."%' AND ip = '".$this->get_client_ip()."' AND enter='{$enter}'")->count_all_results("flow") <= 0 ){
		$this->db->insert("flow", array("ip" => $this->get_client_ip(), "enter" => $enter, "user_id" => $user_id));
		// }
	}

	protected function output($code, $msg, $pass_data = FALSE)
	{
		$data = array();
		if ($pass_data === FALSE) {
			$data = array("status" => $code, "msg" => $msg);
		} else {
			$data = array_merge($data, $pass_data);
			$data['status'] = $code;
			$data['msg'] = $msg;
		}
		if ($this->DEBUG_MODE && isset($_POST)) {
			$data['debug_post'] = $_POST;
			$data['debug_file'] = $_FILES;
			// $data['debug_post'] = array_merge($data['debug_post'], $this->input->request_headers());
		}
		// echo json_encode($data, 256);
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
		// echo json_encode($data, JSON_NUMERIC_CHECK);
		exit();
	}

	protected function js_output_and_back($msg)
	{
		echo "<script> alert('" . $msg . "'); history.back(); </script>";
		exit();
	}

	protected function js_output_and_redirect($msg, $url)
	{
		echo "<script> alert('" . $msg . "'); location.href='" . $url . "'; </script>";
	}

	protected function loading_item()
	{
		$items = "";
		for ($i = 0; $i < 3; $i++) {
			$items .= $this->load->view("loading_item", array(), true);
		}
		return $items;
	}

	public function is_login()
	{
		if (!($this->session->isLogin && $this->encryption->decrypt($this->session->isLogin) == md5("uLogIn"))) return FALSE;
		return TRUE;
	}

	protected function is_mgr_lock()
	{
		if ($this->session->has_userdata('lock') && $this->session->has_userdata('isMgrlogin') && $this->encryption->decrypt($this->session->isMgrlogin) == md5("MGRLOGIN")) {
			header("Location: " . base_url() . "mgr/lock");
		}
	}

	protected function is_mgr_login()
	{
		$this->is_mgr_lock();

		if ($this->session->has_userdata('isMgrlogin') && $this->encryption->decrypt($this->session->isMgrlogin) == md5("MGRLOGIN")) {
			// }else if($this->input->cookie('al', TRUE) && $this->input->cookie('al', TRUE)!=""){
			// 	$decrypt_token = $this->encryption->decrypt($this->input->cookie('al', TRUE));
			// 	$this->auto_login($decrypt_token);
		} else {
			//強制登出
			// $this->session->sess_destroy();

			header("Location: " . base_url() . "mgr/login");
		}
	}

	protected function set_upload_options($dir = 'uploads/')
	{
		if (!file_exists($dir)) {
			$oldmask = umask(0);
			mkdir($dir, 0755);
			umask($oldmask);
		}

		$config = array();
		$config['upload_path']   = $dir;
		$config['allowed_types'] = '*'; //'gif|jpg|png|jpeg';
		$config['max_size']      = '0';
		$config['overwrite']     = FALSE;
		$config['encrypt_name']  = TRUE;

		return $config;
	}

	protected function get_client_ip()
	{
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if (isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if (isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if (isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}

	protected function get_zipcode()
	{
		return json_decode('{"city":[{"dist":[{"name":"中正區","c3":"100"},{"name":"大同區","c3":"103"},{"name":"中山區","c3":"104"},{"name":"松山區","c3":"105"},{"name":"大安區","c3":"106"},{"name":"萬華區","c3":"108"},{"name":"信義區","c3":"110"},{"name":"士林區","c3":"111"},{"name":"北投區","c3":"112"},{"name":"內湖區","c3":"114"},{"name":"南港區","c3":"115"},{"name":"文山區","c3":"116"}],"name":"台北市"},{"dist":[{"name":"仁愛區","c3":"200"},{"name":"信義區","c3":"201"},{"name":"中正區","c3":"202"},{"name":"中山區","c3":"203"},{"name":"安樂區","c3":"204"},{"name":"暖暖區","c3":"205"},{"name":"七堵區","c3":"206"}],"name":"基隆市"},{"dist":[{"name":"萬里區","c3":"207"},{"name":"金山區","c3":"208"},{"name":"板橋區","c3":"220"},{"name":"汐止區","c3":"221"},{"name":"深坑區","c3":"222"},{"name":"石碇區","c3":"223"},{"name":"瑞芳區","c3":"224"},{"name":"平溪區","c3":"226"},{"name":"雙溪區","c3":"227"},{"name":"貢寮區","c3":"228"},{"name":"新店區","c3":"231"},{"name":"坪林區","c3":"232"},{"name":"烏來區","c3":"233"},{"name":"永和區","c3":"234"},{"name":"中和區","c3":"235"},{"name":"土城區","c3":"236"},{"name":"三峽區","c3":"237"},{"name":"樹林區","c3":"238"},{"name":"鶯歌區","c3":"239"},{"name":"三重區","c3":"241"},{"name":"新莊區","c3":"242"},{"name":"泰山區","c3":"243"},{"name":"林口區","c3":"244"},{"name":"蘆洲區","c3":"247"},{"name":"五股區","c3":"248"},{"name":"八里區","c3":"249"},{"name":"淡水區","c3":"251"},{"name":"三芝區","c3":"252"},{"name":"石門區","c3":"253"}],"name":"新北市"},{"dist":[{"name":"中壢區","c3":"320"},{"name":"平鎮區","c3":"324"},{"name":"龍潭區","c3":"325"},{"name":"楊梅區","c3":"326"},{"name":"新屋區","c3":"327"},{"name":"觀音區","c3":"328"},{"name":"桃園區","c3":"330"},{"name":"龜山區","c3":"333"},{"name":"八德區","c3":"334"},{"name":"大溪區","c3":"335"},{"name":"復興區","c3":"336"},{"name":"大園區","c3":"337"},{"name":"蘆竹區","c3":"338"}],"name":"桃園市"},{"dist":[{"name":"新竹市","c3":"300"}],"name":"新竹市"},{"dist":[{"name":"竹北市","c3":"302"},{"name":"湖口鄉","c3":"303"},{"name":"新豐鄉","c3":"304"},{"name":"新埔鎮","c3":"305"},{"name":"關西鎮","c3":"306"},{"name":"芎林鄉","c3":"307"},{"name":"寶山鄉","c3":"308"},{"name":"竹東鎮","c3":"310"},{"name":"五峰鄉","c3":"311"},{"name":"橫山鄉","c3":"312"},{"name":"尖石鄉","c3":"313"},{"name":"北埔鄉","c3":"314"},{"name":"峨眉鄉","c3":"315"}],"name":"新竹縣"},{"dist":[{"name":"竹南鎮","c3":"350"},{"name":"頭份鎮","c3":"351"},{"name":"三灣鄉","c3":"352"},{"name":"南庄鄉","c3":"353"},{"name":"獅潭鄉","c3":"354"},{"name":"後龍鎮","c3":"356"},{"name":"通霄鎮","c3":"357"},{"name":"苑裡鎮","c3":"358"},{"name":"苗栗市","c3":"360"},{"name":"造橋鄉","c3":"361"},{"name":"頭屋鄉","c3":"362"},{"name":"公館鄉","c3":"363"},{"name":"大湖鄉","c3":"364"},{"name":"泰安鄉","c3":"365"},{"name":"銅鑼鄉","c3":"366"},{"name":"三義鄉","c3":"367"},{"name":"西湖鄉","c3":"368"},{"name":"卓蘭鎮","c3":"369"}],"name":"苗栗縣"},{"dist":[{"name":"中區","c3":"400"},{"name":"東區","c3":"401"},{"name":"南區","c3":"402"},{"name":"西區","c3":"403"},{"name":"北區","c3":"404"},{"name":"北屯區","c3":"406"},{"name":"西屯區","c3":"407"},{"name":"南屯區","c3":"408"},{"name":"太平區","c3":"411"},{"name":"大里區","c3":"412"},{"name":"霧峰區","c3":"413"},{"name":"烏日區","c3":"414"},{"name":"豐原區","c3":"420"},{"name":"后里區","c3":"421"},{"name":"石岡區","c3":"422"},{"name":"東勢區","c3":"423"},{"name":"和平區","c3":"424"},{"name":"新社區","c3":"426"},{"name":"潭子區","c3":"427"},{"name":"大雅區","c3":"428"},{"name":"神岡區","c3":"429"},{"name":"大肚區","c3":"432"},{"name":"沙鹿區","c3":"433"},{"name":"龍井區","c3":"434"},{"name":"梧棲區","c3":"435"},{"name":"清水區","c3":"436"},{"name":"大甲區","c3":"437"},{"name":"外埔區","c3":"438"},{"name":"大安區","c3":"439"}],"name":"台中市"},{"dist":[{"name":"彰化市","c3":"500"},{"name":"芬園鄉","c3":"502"},{"name":"花壇鄉","c3":"503"},{"name":"秀水鄉","c3":"504"},{"name":"鹿港鎮","c3":"505"},{"name":"福興鄉","c3":"506"},{"name":"線西鄉","c3":"507"},{"name":"和美鄉","c3":"508"},{"name":"伸港鄉","c3":"509"},{"name":"員林鎮","c3":"510"},{"name":"社頭鄉","c3":"511"},{"name":"永靖鄉","c3":"512"},{"name":"埔心鄉","c3":"513"},{"name":"溪湖鎮","c3":"514"},{"name":"大村鄉","c3":"515"},{"name":"埔鹽鄉","c3":"516"},{"name":"田中鎮","c3":"520"},{"name":"北斗鎮","c3":"521"},{"name":"田尾鄉","c3":"522"},{"name":"埤頭鄉","c3":"523"},{"name":"溪州鄉","c3":"524"},{"name":"竹塘鄉","c3":"525"},{"name":"二林鎮","c3":"526"},{"name":"大城鄉","c3":"527"},{"name":"芳苑鄉","c3":"528"},{"name":"二水鄉","c3":"530"}],"name":"彰化縣"},{"dist":[{"name":"南投市","c3":"540"},{"name":"中寮鄉","c3":"541"},{"name":"草屯鎮","c3":"542"},{"name":"國姓鄉","c3":"544"},{"name":"埔里鎮","c3":"545"},{"name":"仁愛鄉","c3":"546"},{"name":"名間鄉","c3":"551"},{"name":"集集鎮","c3":"552"},{"name":"水里鄉","c3":"553"},{"name":"魚池鄉","c3":"555"},{"name":"信義鄉","c3":"556"},{"name":"竹山鎮","c3":"557"},{"name":"鹿谷鄉","c3":"558"}],"name":"南投縣"},{"dist":[{"name":"斗南鎮","c3":"630"},{"name":"大埤鄉","c3":"631"},{"name":"虎尾鎮","c3":"632"},{"name":"土庫鎮","c3":"633"},{"name":"褒忠鄉","c3":"634"},{"name":"東勢鄉","c3":"635"},{"name":"台西鄉","c3":"636"},{"name":"崙背鄉","c3":"637"},{"name":"麥寮鄉","c3":"638"},{"name":"斗六市","c3":"640"},{"name":"林內鄉","c3":"643"},{"name":"古坑鄉","c3":"646"},{"name":"莿桐鄉","c3":"647"},{"name":"西螺鎮","c3":"648"},{"name":"二崙鄉","c3":"649"},{"name":"北港鎮","c3":"651"},{"name":"水林鄉","c3":"652"},{"name":"口湖鄉","c3":"653"},{"name":"四湖鄉","c3":"654"},{"name":"元長鄉","c3":"655"}],"name":"雲林縣"},{"dist":[{"name":"嘉義市","c3":"600"}],"name":"嘉義市"},{"dist":[{"name":"番路鄉","c3":"602"},{"name":"梅山鄉","c3":"603"},{"name":"竹崎鄉","c3":"604"},{"name":"阿里山","c3":"605"},{"name":"中埔鄉","c3":"606"},{"name":"大埔鄉","c3":"607"},{"name":"水上鄉","c3":"608"},{"name":"鹿草鄉","c3":"611"},{"name":"太保鄉","c3":"612"},{"name":"朴子市","c3":"613"},{"name":"東石鄉","c3":"614"},{"name":"六腳鄉","c3":"615"},{"name":"新港鄉","c3":"616"},{"name":"民雄鄉","c3":"621"},{"name":"大林鎮","c3":"622"},{"name":"溪口鄉","c3":"623"},{"name":"義竹鄉","c3":"624"},{"name":"布袋鄉","c3":"625"}],"name":"嘉義縣"},{"dist":[{"name":"中西區","c3":"700"},{"name":"東區","c3":"701"},{"name":"南區","c3":"702"},{"name":"北區","c3":"704"},{"name":"安平區","c3":"708"},{"name":"安南區","c3":"709"},{"name":"永康區","c3":"710"},{"name":"歸仁區","c3":"711"},{"name":"新化區","c3":"712"},{"name":"左鎮區","c3":"713"},{"name":"玉井區","c3":"714"},{"name":"楠西區","c3":"715"},{"name":"南化區","c3":"716"},{"name":"仁德區","c3":"717"},{"name":"關廟區","c3":"718"},{"name":"龍崎區","c3":"719"},{"name":"官田區","c3":"720"},{"name":"麻豆區","c3":"721"},{"name":"佳里區","c3":"722"},{"name":"西港區","c3":"723"},{"name":"七股區","c3":"724"},{"name":"將軍區","c3":"725"},{"name":"學甲區","c3":"726"},{"name":"北門區","c3":"727"},{"name":"新營區","c3":"730"},{"name":"後壁區","c3":"731"},{"name":"白河區","c3":"732"},{"name":"東山區","c3":"733"},{"name":"六甲區","c3":"734"},{"name":"下營區","c3":"735"},{"name":"柳營區","c3":"736"},{"name":"鹽水區","c3":"737"},{"name":"善化區","c3":"741"},{"name":"大內區","c3":"742"},{"name":"山上區","c3":"743"},{"name":"新市區","c3":"744"},{"name":"安定區","c3":"745"}],"name":"台南市"},{"dist":[{"name":"新興區","c3":"800"},{"name":"前金區","c3":"801"},{"name":"苓雅區","c3":"802"},{"name":"鹽埕區","c3":"803"},{"name":"鼓山區","c3":"804"},{"name":"旗津區","c3":"805"},{"name":"前鎮區","c3":"806"},{"name":"三民區","c3":"807"},{"name":"楠梓區","c3":"811"},{"name":"小港區","c3":"812"},{"name":"左營區","c3":"813"},{"name":"仁武區","c3":"814"},{"name":"大社區","c3":"815"},{"name":"岡山區","c3":"820"},{"name":"路竹區","c3":"821"},{"name":"阿蓮區","c3":"822"},{"name":"田寮區","c3":"823"},{"name":"燕巢區","c3":"824"},{"name":"橋頭區","c3":"825"},{"name":"梓官區","c3":"826"},{"name":"彌陀區","c3":"827"},{"name":"永安區","c3":"828"},{"name":"湖內區","c3":"829"},{"name":"鳳山區","c3":"830"},{"name":"大寮區","c3":"831"},{"name":"林園區","c3":"832"},{"name":"鳥松區","c3":"833"},{"name":"大樹區","c3":"840"},{"name":"旗山區","c3":"842"},{"name":"美濃區","c3":"843"},{"name":"六龜區","c3":"844"},{"name":"內門區","c3":"845"},{"name":"杉林區","c3":"846"},{"name":"甲仙區","c3":"847"},{"name":"桃源區","c3":"848"},{"name":"那瑪夏區","c3":"849"},{"name":"茂林區","c3":"851"},{"name":"茄萣區","c3":"852"},{"name":"東沙","c3":"817"},{"name":"南沙","c3":"819"}],"name":"高雄市"},{"dist":[{"name":"屏東市","c3":"900"},{"name":"三地鄉","c3":"901"},{"name":"霧台鄉","c3":"902"},{"name":"瑪家鄉","c3":"903"},{"name":"九如鄉","c3":"904"},{"name":"里港鄉","c3":"905"},{"name":"高樹鄉","c3":"906"},{"name":"鹽埔鄉","c3":"907"},{"name":"長治鄉","c3":"908"},{"name":"麟洛鄉","c3":"909"},{"name":"竹田鄉","c3":"911"},{"name":"內埔鄉","c3":"912"},{"name":"萬丹鄉","c3":"913"},{"name":"潮州鎮","c3":"920"},{"name":"泰武鄉","c3":"921"},{"name":"來義鄉","c3":"922"},{"name":"萬巒鄉","c3":"923"},{"name":"崁頂鄉","c3":"924"},{"name":"新埤鄉","c3":"925"},{"name":"南州鄉","c3":"926"},{"name":"林邊鄉","c3":"927"},{"name":"東港鄉","c3":"928"},{"name":"琉球鄉","c3":"929"},{"name":"佳冬鄉","c3":"931"},{"name":"新園鄉","c3":"932"},{"name":"枋寮鄉","c3":"940"},{"name":"枋山鄉","c3":"941"},{"name":"春日鄉","c3":"942"},{"name":"獅子鄉","c3":"943"},{"name":"車城鄉","c3":"944"},{"name":"牡丹鄉","c3":"945"},{"name":"恆春鎮","c3":"946"},{"name":"滿洲鄉","c3":"947"}],"name":"屏東縣"},{"dist":[{"name":"台東市","c3":"950"},{"name":"綠島鄉","c3":"951"},{"name":"蘭嶼鄉","c3":"952"},{"name":"延平鄉","c3":"953"},{"name":"卑南鄉","c3":"954"},{"name":"鹿野鄉","c3":"955"},{"name":"關山鎮","c3":"956"},{"name":"海端鄉","c3":"957"},{"name":"池上鄉","c3":"958"},{"name":"東河鄉","c3":"959"},{"name":"成功鎮","c3":"961"},{"name":"長濱鄉","c3":"962"},{"name":"太麻里","c3":"963"},{"name":"金峰鄉","c3":"964"},{"name":"大武鄉","c3":"965"},{"name":"達仁鄉","c3":"966"}],"name":"台東縣"},{"dist":[{"name":"花蓮市","c3":"970"},{"name":"新城鄉","c3":"971"},{"name":"秀林鄉","c3":"972"},{"name":"吉安鄉","c3":"973"},{"name":"壽豐鄉","c3":"974"},{"name":"鳳林鎮","c3":"975"},{"name":"光復鄉","c3":"976"},{"name":"豐濱鄉","c3":"977"},{"name":"瑞穗鄉","c3":"978"},{"name":"萬榮鄉","c3":"979"},{"name":"玉里鎮","c3":"981"},{"name":"卓溪鄉","c3":"982"},{"name":"富里鄉","c3":"983"}],"name":"花蓮縣"},{"dist":[{"name":"宜蘭巿","c3":"260"},{"name":"頭城鎮","c3":"261"},{"name":"礁溪鄉","c3":"262"},{"name":"壯圍鄉","c3":"263"},{"name":"員山鄉","c3":"264"},{"name":"羅東鎮","c3":"265"},{"name":"三星鄉","c3":"266"},{"name":"大同鄉","c3":"267"},{"name":"五結鄉","c3":"268"},{"name":"冬山鄉","c3":"269"},{"name":"蘇澳鎮","c3":"270"},{"name":"南澳鄉","c3":"272"},{"name":"釣魚台","c3":"290"}],"name":"宜蘭縣"},{"dist":[{"name":"馬公市","c3":"880"},{"name":"西嶼鄉","c3":"881"},{"name":"望安鄉","c3":"882"},{"name":"七美鄉","c3":"883"},{"name":"白沙鄉","c3":"884"},{"name":"湖西鄉","c3":"885"}],"name":"澎湖縣"},{"dist":[{"name":"金沙鎮","c3":"890"},{"name":"金湖鎮","c3":"891"},{"name":"金寧鄉","c3":"892"},{"name":"金城鎮","c3":"893"},{"name":"烈嶼鄉","c3":"894"},{"name":"烏坵","c3":"896"}],"name":"金門縣"},{"dist":[{"name":"南竿","c3":"209"},{"name":"北竿","c3":"210"},{"name":"莒光","c3":"211"},{"name":"東引","c3":"212"}],"name":"連江縣"}],"version":"10410"}', TRUE);
	}
}
