<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notification extends Base_Controller
{
    private $th_title = ["#", "會員名稱", "ID", "身份", "手機", "狀態", "動作"]; //, "置頂"
    private $th_width = ["80px", "", "", "", "", "", "150px"];
    private $order_column = ["id", "username", "atid", "", "mobile", "", ""];
    private $can_order_fields = [0];

    private $param;
    private $action;

    public function __construct()
    {
        parent::__construct();
        $this->is_mgr_login();
        $this->data['active'] = "LETTER";
        $this->data['sub_active'] = 'LETTER_LIST';
        $this->load->model('Notification_model');
        $this->action = base_url() . "mgr/user/";
        $this->param = [
            //																								md 		sm
            ["推播對象ID",                "atid",            "text",                "",            TRUE,     "",     12,         12],
            ["推播對象暱稱",             "username",            "text",                "",            TRUE,     "",     12,         12],
            ["推播通知",               "content",            "textarea_plain",                "",            TRUE,     "",     12,         12],
        ];
    }

    public function index()
    {
        $this->data['title'] = '會員列表';

        $this->data['action'] = $this->action;
        $this->data['th_title'] = $this->th_title;
        $this->data['th_width'] = $this->th_width;
        $this->data['can_order_fields'] = $this->can_order_fields;
        $this->data['tool_btns'] = [
            // ['新增廣告網址', base_url() . "mgr/adv/add", "btn-primary"],
            // ['上傳廣告影片', base_url() . "mgr/adv/addVideo", "btn-primary"]
        ];
        $this->data['default_order_column'] = 0;
        $this->data['default_order_direction'] = 'ASC';
        $this->load->view('mgr/user_list', $this->data);
    }

    //推播中獎會員
    public function add($user_id = '')
    {

        if ($_POST) {
            $data = $this->process_post_data($this->param);
            // require('./vendor/autoload.php');
            // !d($data);
            $res = $this->Notification_model->add_notification($user_id, 'system', 0, $data['content'], array('type' => 'system'));
            if ($res !== FALSE) {
                if ($this->User_model->letter_edit($user_id, array('is_contacted' => 1)))
                    $this->js_output_and_redirect("新增成功", base_url() . "mgr/letter");
            } else {
                $this->js_output_and_back("發生錯誤");
            }
        } else {
            $this->data['param'] = $this->param;
            if (!empty($user_id)) {
                $user = $this->User_model->get_user_data($user_id);
                $this->data['param'][0][3] = $user['username'];
                $this->data['param'][1][3] = $user['atid'];
            }

            $this->data['title'] = '推播通知';
            $this->data['parent'] = '神秘信中獎列表';
            $this->data['parent_link'] = base_url() . "mgr/letter";

            $this->data['action'] = base_url() . "mgr/notification/add/" . $user['id'];
            $this->data['submit_txt'] = "推播通知";
            $this->load->view("mgr/template_form_complex", $this->data);
        }
    }

    public function data()
    {
        $page        = ($this->input->post("page")) ? $this->input->post("page") : 1;
        $search      = ($this->input->post("search")) ? $this->input->post("search") : "";
        $order       = ($this->input->post("order")) ? $this->input->post("order") : 0;
        $direction   = ($this->input->post("direction")) ? $this->input->post("direction") : "ASC";

        $order_column = $this->order_column;
        $canbe_search_field = ["username", "atid", "mobile",];

        $syntax = "is_delete = 0";
        if ($search != "") {
            $syntax .= " AND (";
            $index = 0;
            foreach ($canbe_search_field as $field) {
                if ($index > 0) $syntax .= " OR ";
                $syntax .= $field . " LIKE '%" . $search . "%'";
                $index++;
            }
            $syntax .= ")";
        }

        $order_by = "id ASC";
        if ($order_column[$order] != "") {
            $order_by = $order_column[$order] . " " . $direction . ", " . $order_by;
        }

        $data = $this->User_model->get_user_list($syntax, $order_by, $page, $this->page_count);
        $html = "";
        foreach ($data['list'] as $item) {
            $html .= $this->load->view("mgr/items/user_item", array(
                "item" =>    $item,
            ), TRUE);
        }
        if ($search != "") $html = preg_replace('/' . $search . '/i', '<mark data-markjs="true">' . $search . '</mark>', $html);

        $this->output(TRUE, "成功", array(
            "html"       =>    $html,
            "page"       =>    $page,
            "total_page" =>    $data['total_page']
        ));
    }

    public function switch_toggle()
    {
        $id     = $this->input->post("id");
        $is_disable = $this->input->post("is_disable");

        if ($this->User_model->edit($id, array("is_disable" => $is_disable))) {
            $this->output(TRUE, "success");
        } else {
            $this->output(FALSE, "fail");
        }
    }

    public function detail($id)
    {
        $user = $this->User_model->get_user_data($id);

        switch ($user['occupation']) {
            case 'student':
                $user['occupation'] = '學生';
                break;
            case 'office_work':
                $user['occupation'] = '上班族';
                break;
            case 'freelancer':
                $user['occupation'] = '自由工作者';
                break;
            case 'teleworker':
                $user['occupation'] = '遠距工作者';
                break;
            case 'merchant':
                $user['occupation'] = '商家';
                break;
            case 'other':
                $user['occupation'] = '其他';
                break;
        }

        if (!empty($user['avatar'])) $user['avatar'] = base_url() . $user['avatar'];
        $this->data['user'] = $user;
        $this->load->view('mgr/user_preview', $this->data);
    }
}
