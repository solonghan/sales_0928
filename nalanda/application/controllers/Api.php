<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends Base_Controller
{
    /**
     *	@OA\Server(url="https://anbon.works/kihsiao//")
     *	@OA\Server(url="http://localhost/kihsiao//")
     *	@OA\Info(
     *		version="1.0.0",
     *		title="kihsiao Api文件",
     *		description="起笑 Api 文件",
     *	)
     */
    private $login_url;

    public function __construct()
    {
        parent::__construct();
    }

    private function post($key, $default = '', $required_alert = '', $type = 'text')
    {
        $value = $this->input->post($key);

        if (is_null($value)) {
            if (is_null($default)) {
                return null;
            }
            $value = $default;
        }

        if ($required_alert != '') {
            if ($type == 'text' && $value == '') {
                $this->output(false, $required_alert);
            } elseif ($type == 'number' && $value == 0) {
                $this->output(false, $required_alert);
            }
        }
        return $value;
    }

    private function check_user_token($auth_action = true, $first = false)
    {
        $token = $this->input->post("token");
        if (!$auth_action) {
            return false;
        }

        $decode_data = $this->Jwt_model->verify_token($token);

        if ($decode_data['status'] == 0) {
            if ($auth_action) {
                $this->output(false, $decode_data['msg'], array("login_auth" => false));
            } else {
                return false;
            }
        } else {
            $user = $this->User_model->get_user_data($decode_data['user_id']);
            if ($user['is_disable']) {
                $this->output(false, "此帳戶已經被停權\r\n請聯絡管理員！！");
            }
            return $user;
        }
    }
}
