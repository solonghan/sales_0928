<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends Base_Controller
{
    private $th_title = ["#", "會員名稱", "ID", "身份", "手機", "實名認證", "狀態", "動作"]; //, "置頂"
    private $th_width = ["80px", "", "", "", "", "", "", "150px"];
    private $order_column = ["id", "username", "atid", "", "mobile", "name_verify", "is_disable", ""];
    private $can_order_fields = [0, 5, 6];

    private $param;
    private $action;

    public function __construct()
    {
        parent::__construct();
        $this->is_mgr_login();
        $this->data['active'] = "USER";
        $this->data['sub_active'] = 'USER_LIST';

        $this->action = base_url() . "mgr/user/";
        $this->param = [
            //																								md 		sm
            ["廣告名稱",                 "title",            "text",                "",            TRUE,     "",     3,         12],
            ["影片網址",                    "url",                  "text",             "",         TRUE,     "",     6,         12],
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

    public function del()
    {
        $id = $this->input->post("id");
        if (!is_numeric($id)) show_404();

        if ($this->User_model->edit($id, array("is_delete" => 1))) {
            $this->output(TRUE, "success");
        } else {
            $this->output(FALSE, "fail");
        }
    }

    public function data()
    {
        $page        = ($this->input->post("page")) ? $this->input->post("page") : 1;
        $search      = ($this->input->post("search")) ? $this->input->post("search") : "";
        $order       = ($this->input->post("order")) ? $this->input->post("order") : 0;
        $direction   = ($this->input->post("direction")) ? $this->input->post("direction") : "ASC";

        $order_column = $this->order_column;
        $canbe_search_field = ["username", "atid", "mobile", "create_date"];

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
        $this->load->model("Friend_model");
        $this->load->model("Subscribe_model");
        $this->load->model("Post_model");
        $user          = $this->User_model->get_user_data($id);
        $name_verified = $this->User_model->get_verify_photo($id);
        $friend        = $this->Friend_model->friend_count($id);
        $subscribe     = $this->Subscribe_model->subscribe_count($id);
        $fans          = $this->Subscribe_model->fans_count($id);
        $post          = $this->Post_model->post_count($id);
        $story         = $this->Post_model->story_count($id);

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
        if (!empty($name_verified['thumb_url'])) $name_verified['thumb_url'] = base_url() . $name_verified['thumb_url'];
        if (!empty($name_verified['normal_url'])) $name_verified['normal_url'] = base_url() . $name_verified['normal_url'];

        $this->data['user']          = $user;
        $this->data['name_verified'] = $name_verified;
        $this->data['friend']        = $friend;
        $this->data['subscribe']     = $subscribe;
        $this->data['fans']          = $fans;
        $this->data['post']          = $post;
        $this->data['story']         = $story;
        $this->load->view('mgr/user_preview', $this->data);
    }
}
