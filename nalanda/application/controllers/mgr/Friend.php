<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Friend extends Base_Controller
{
    private $th_title = ["#", "好友名稱", "ID", "動作"]; //, "置頂"
    private $th_width = ["80px", "", "", "150px"];
    private $order_column = ["", "", "", ""];
    private $can_order_fields = [0];

    private $param;
    private $action;

    public function __construct()
    {
        parent::__construct();
        $this->is_mgr_login();
        $this->data['active'] = "USER";
        $this->data['sub_active'] = 'USER_LIST';
        $this->load->model('Friend_model');
        $this->action = base_url() . "mgr/friend/";
        $this->param = [
            //																								md 		sm
            ["廣告名稱",                 "title",            "text",                "",            TRUE,     "",     3,         12],
            ["影片網址",                    "url",                  "text",             "",         TRUE,     "",     6,         12],
        ];
    }

    public function index($user_id)
    {
        $this->data['title'] = '好友列表';

        $this->data['custom_data_url'] = base_url() . 'mgr/friend/data/' . $user_id;
        $this->data['action'] = $this->action;
        $this->data['th_title'] = $this->th_title;
        $this->data['th_width'] = $this->th_width;
        $this->data['can_order_fields'] = $this->can_order_fields;
        $this->data['tool_btns'] = [
            // ['新增廣告網址', base_url() . "mgr/adv/add", "btn-primary"],
        ];
        $this->data['default_order_column'] = 0;
        $this->data['default_order_direction'] = 'ASC';
        $this->load->view('mgr/template_list', $this->data);
    }

    public function data($user_id)
    {
        $page        = ($this->input->post("page")) ? $this->input->post("page") : 1;
        $search      = ($this->input->post("search")) ? $this->input->post("search") : "";
        $order       = ($this->input->post("order")) ? $this->input->post("order") : 0;
        $direction   = ($this->input->post("direction")) ? $this->input->post("direction") : "ASC";

        $order_column = $this->order_column;
        $canbe_search_field = ["username",];

        $syntax = "F.user_id=$user_id";
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

        $data = $this->Friend_model->friend_list($syntax, $order_by, $page, $this->page_count);
        $html = "";
        foreach ($data['list'] as $item) {
            $html .= $this->load->view("mgr/items/friend_item", array(
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
}
