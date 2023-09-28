<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Letter extends Base_Controller
{
    private $th_title = ["#", "用戶名", "真實姓名", "手機", "中獎內容", "狀態", "中獎時間"]; //, "置頂"
    private $th_width = ["80px", "", "", "", "", "", ""];
    private $order_column = ["id", "username", "", "mobile", "", "is_contacted", ""];
    private $can_order_fields = [0, 5];

    private $param;
    private $action;

    public function __construct()
    {
        parent::__construct();
        $this->is_mgr_login();
        $this->data['active'] = "LETTER";
        $this->data['sub_active'] = 'LETTER_LIST';
        $this->action = base_url() . "mgr/letter/";
        $this->load->model('Gift_model');
        $this->param = [
            //																								md 		sm
            ["禮物名稱",                 "title",            "text",                "",            TRUE,     "",     6,         12],
            ["一次獲得禮物數量(預設:1)",     "count",             "text",             "1",         TRUE,     "",     6,         12],
            ["禮物單位(EX:個、件)",            "unit",             "text",             "",         TRUE,     "",     6,         12],
            ["獎品總數量",                  "result",             "text",             "",         TRUE,     "",     6,         12],
        ];
    }

    public function index()
    {
        $this->data['title'] = '神秘信中獎列表';

        $this->data['action'] = $this->action;
        $this->data['th_title'] = $this->th_title;
        $this->data['th_width'] = $this->th_width;
        $this->data['can_order_fields'] = $this->can_order_fields;
        $this->data['tool_btns'] = [
            // ['新增廣告網址', base_url() . "mgr/adv/add", "btn-primary"],
            // ['上傳廣告影片', base_url() . "mgr/adv/addVideo", "btn-primary"]
        ];
        $this->data['default_order_column'] = 5;
        $this->data['default_order_direction'] = 'ASC';
        $this->load->view('mgr/letter_list', $this->data);
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
        $canbe_search_field = ["username", "e", "mobile",];

        $syntax = "";
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

        $order_by = "mobile ASC, create_date ASC";
        if ($order_column[$order] != "") {
            $order_by = $order_column[$order] . " " . $direction . ", " . $order_by;
        }

        $data = $this->User_model->get_letter_list($syntax, $order_by, $page, $this->page_count);
        $html = "";
        foreach ($data['list'] as $item) {
            $html .= $this->load->view("mgr/items/letter_item", array(
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

        if ($this->User_model->letter_edit($id, array("is_contacted" => $is_contacted))) {
            $this->output(TRUE, "success");
        } else {
            $this->output(FALSE, "fail");
        }
    }

    //----------------------------------------------------------------禮物列
    public function gift()
    {
        $this->data['title'] = '禮物列表';
        $this->data['sub_active'] = 'GIFT_LIST';
        $this->data['action'] = base_url() . 'mgr/letter/';
        $this->data['custom_data_url'] = base_url() . 'mgr/letter/gift_data';
        $this->data['custom_switch_url'] = base_url() . 'mgr/letter/gift_switch_toggle';
        $this->data['th_title'] = ['#', '類型', '禮物名稱', '禮物內容', '剩餘數量', '狀態', '動作'];
        $this->data['th_width'] = ['80px', '', '', '', '', '', '150px'];
        $this->order_column = ["id", "", "", "", "result", "status", ""];
        $this->data['can_order_fields'] = [0, 4, 5];
        $this->data['tool_btns'] = [
            ['新增禮物', base_url() . "mgr/letter/add_gift", "btn-primary"],
            ['新增K幣', base_url() . "mgr/letter/add_coin", "btn-default"],
        ];
        $this->data['default_order_column'] = 5;
        $this->data['default_order_direction'] = 'DESC';
        $this->load->view('mgr/gift_list', $this->data);
    }

    public function gift_data()
    {
        $page        = ($this->input->post("page")) ? $this->input->post("page") : 1;
        $search      = ($this->input->post("search")) ? $this->input->post("search") : "";
        $order       = ($this->input->post("order")) ? $this->input->post("order") : 0;
        $direction   = ($this->input->post("direction")) ? $this->input->post("direction") : "ASC";

        $order_column = ["id", "", "", "", "result", "status", ""];
        $canbe_search_field = ["title"];

        $syntax = "is_delete=0";
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

        $order_by = "count ASC";
        if ($order_column[$order] != "") {
            $order_by = $order_column[$order] . " " . $direction . ", " . $order_by;
        }

        $data = $this->Gift_model->get_gift_list($syntax, $order_by, $page, $this->page_count);
        $html = "";
        foreach ($data['list'] as $item) {
            $html .= $this->load->view("mgr/items/gift_item", array(
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

    public function add_gift()
    {
        if ($_POST) {
            $data = $this->process_post_data($this->param);

            $data['type']   = 'gift';
            $data['status'] = 'on';
            // require('./vendor/autoload.php');
            // !d($data);
            if ($this->Gift_model->add($data) !== FALSE) {
                $this->js_output_and_redirect("新增成功", base_url() . "mgr/letter/gift");
            } else {
                $this->js_output_and_back("發生錯誤");
            }
        } else {
            $this->data['title'] = '新增禮物選項';
            $this->data['sub_active'] = 'GIFT_LIST';

            $this->data['parent'] = '禮物列表';
            $this->data['parent_link'] = base_url() . "mgr/letter/gift";

            $this->data['action'] = base_url() . "mgr/letter/add_gift";
            $this->data['submit_txt'] = "新增";

            $this->data['param'] = $this->param;
            $this->load->view("mgr/template_form", $this->data);
        }
    }

    public function add_coin()
    {
        $param = [
            //																								md 		sm
            ["K幣點數",                 "count",            "text",                "",            TRUE,     "",     6,         12],
            ["獎品數量",                "result",            "text",             "",         TRUE,     "",     6,         12],
        ];

        if ($_POST) {
            $data = $this->process_post_data($param);

            $data['type']  = 'coin';
            $data['title'] = 'K幣';
            $data['unit']  = '點';
            $data['status'] = 'on';
            if ($this->Gift_model->add($data) !== FALSE) {
                $this->js_output_and_redirect("新增成功", base_url() . "mgr/letter/gift");
            } else {
                $this->js_output_and_back("發生錯誤");
            }
        } else {
            $this->data['title'] = '新增K幣選項';
            $this->data['sub_active'] = 'GIFT_LIST';

            $this->data['parent'] = '禮物列表';
            $this->data['parent_link'] = base_url() . "mgr/letter/gift";

            $this->data['action'] = base_url() . "mgr/letter/add_coin";
            $this->data['submit_txt'] = "新增";

            $this->data['param'] = $param;
            $this->load->view("mgr/template_form", $this->data);
        }
    }

    public function edit_gift($id)
    {
        if ($_POST) {
            $data = $this->process_post_data($this->param);

            if ($this->Gift_model->edit($id, $data)) {
                $this->js_output_and_redirect("編輯成功", base_url() . "mgr/letter/gift");
            } else {
                $this->js_output_and_back("發生錯誤");
            }
        } else {
            $data = $this->Gift_model->get_data($id);
            $this->data['title'] = '編輯禮物 ' . $data['title'];
            $this->data['sub_active'] = 'GIFT_LIST';

            $this->data['parent'] = '禮物列表';
            $this->data['parent_link'] = base_url() . "mgr/letter/gift";

            $this->data['action'] = base_url() . "mgr/member/edit_gift/" . $data['id'];
            $this->data['submit_txt'] = "確認編輯";

            $this->data['param'] = $this->set_data_to_param($this->param, $data);

            $this->load->view("mgr/template_form", $this->data);
        }
    }

    public function edit_coin($id)
    {
        $param = [
            //																								md 		sm
            ["K幣點數",                 "count",            "text",                "",            TRUE,     "",     6,         12],
            ["獎品數量",                "result",            "text",             "",         TRUE,     "",     6,         12],
        ];

        if ($_POST) {
            $data = $this->process_post_data($param);

            if ($this->Gift_model->edit($id, $data)) {
                $this->js_output_and_redirect("編輯成功", base_url() . "mgr/letter/gift");
            } else {
                $this->js_output_and_back("發生錯誤");
            }
        } else {
            $data = $this->Gift_model->get_data($id);
            $this->data['title'] = '編輯禮物 ' . $data['title'];
            $this->data['sub_active'] = 'GIFT_LIST';

            $this->data['parent'] = '禮物列表';
            $this->data['parent_link'] = base_url() . "mgr/letter/gift";

            $this->data['action'] = base_url() . "mgr/letter/edit_coin/" . $data['id'];
            $this->data['submit_txt'] = "確認編輯";

            //get exist data to param
            $this->data['param'] = $this->set_data_to_param($param, $data);

            $this->load->view("mgr/template_form", $this->data);
        }
    }

    public function gift_switch_toggle()
    {
        $id     = $this->input->post("id");
        $status = $this->input->post("status");

        if ($this->Gift_model->edit($id, array("status" => $status))) {
            $this->output(TRUE, "success");
        } else {
            $this->output(FALSE, "fail");
        }
    }

    public function gift_del()
    {
        $id = $this->input->post("id");
        if (!is_numeric($id)) show_404();

        if ($this->Gift_model->edit($id, array("is_delete" => 1))) {
            $this->output(TRUE, "success");
        } else {
            $this->output(FALSE, "fail");
        }
    }
}
