<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mission extends Base_Controller
{
    private $th_title = ["#", "會員名稱", "身份", "手機", "任務內容", "狀態", "動作"]; //, "置頂"
    private $th_width = ["80px", "60px", "60px", "", "", "80px", "150px"];
    private $order_column = ["id", "U.username", "U.occupation", "U.mobile", "M.content", "M.is_contacted", ""];
    private $can_order_fields = [0, 5];

    private $param;
    private $action;

    public function __construct()
    {
        parent::__construct();
        $this->is_mgr_login();
        $this->data['active'] = "USER";
        $this->data['sub_active'] = 'MISSION_LIST';
        $this->load->model('Mission_model');
        $this->action = base_url() . "mgr/mission/";
        $this->param = [
            //																								md 		sm
            ["推播對象",                  "user_id",           "select_multi",             "",         TRUE,     "",     12,         12, ['id', 'username', 'atid', 'name']],
            ["任務內容",                 "content",            "textarea_plain",            "",            TRUE,     "",     12,         12],
        ];
    }

    public function index()
    {
        $this->data['title'] = '官方任務列表';

        $this->data['action'] = $this->action;
        $this->data['th_title'] = $this->th_title;
        $this->data['th_width'] = $this->th_width;
        $this->data['can_order_fields'] = $this->can_order_fields;
        $this->data['tool_btns'] = [
            ['新增官方任務', base_url() . "mgr/mission/add", "btn-primary"]
        ];
        $this->data['default_order_column'] = 5;
        $this->data['default_order_direction'] = 'ASC';
        $this->load->view('mgr/mission_list', $this->data);
    }

    public function add($user_id = '')
    {
        $this->load->model("Notification_model");
        if ($_POST) {
            $data = $this->process_post_data($this->param);
            if (empty(trim($data['content']))) $this->js_output_and_back("任務內容不可為空");
            $data['title'] = '官方任務送幸福';
            $data['status'] = 'pushed';
            foreach ($this->input->post('user_id') as $user_id) {
                $data['user_id'] = $user_id;
                if ($id = $this->Mission_model->add($data) !== FALSE) {
                    $this->Notification_model->add_notification($user_id, 'mission', $id, $data['content']);
                } else {
                    $this->js_output_and_back("發生錯誤");
                }
            }
            $this->js_output_and_redirect("新增成功", base_url() . "mgr/mission");
        } else {
            $this->data['title'] = '新增官方任務';

            $this->data['parent'] = '官方任務管理';
            $this->data['parent_link'] = base_url() . "mgr/mission";

            $this->data['action'] = base_url() . "mgr/mission/add";
            $this->data['submit_txt'] = "新增";

            $this->data['select']['user_id'] = $this->User_model->get_all_all();

            $this->data['param'] = $this->param;
            if (is_numeric($user_id)) $this->data['param'][0][3] = ["$user_id"];
            $this->load->view("mgr/mission_form", $this->data);
        }
    }

    public function data()
    {
        $page        = ($this->input->post("page")) ? $this->input->post("page") : 1;
        $search      = ($this->input->post("search")) ? $this->input->post("search") : "";
        $order       = ($this->input->post("order")) ? $this->input->post("order") : 0;
        $direction   = ($this->input->post("direction")) ? $this->input->post("direction") : "ASC";

        $order_column = $this->order_column;
        $canbe_search_field = ["U.username", "M.content", "U.mobile", "M.create_date"];

        $syntax = "M.is_delete = 0";
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

        $order_by = "M.create_date ASC";
        if ($order_column[$order] != "") {
            $order_by = $order_column[$order] . " " . $direction . ", " . $order_by;
        }

        $data = $this->Mission_model->get_data_list($syntax, $order_by, $page, $this->page_count);
        $html = "";
        foreach ($data['list'] as $item) {
            $html .= $this->load->view("mgr/items/mission_item", array(
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
        $is_contacted = $this->input->post("is_contacted");

        if ($this->Mission_model->edit($id, array("is_contacted" => $is_contacted))) {
            $this->output(TRUE, "success");
        } else {
            $this->output(FALSE, "fail");
        }
    }
}
