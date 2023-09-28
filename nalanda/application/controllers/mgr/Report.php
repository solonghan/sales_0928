<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report extends Base_Controller
{
    private $th_title = ["#", "舉報人", "留言內容", "狀態", "檢舉時間", "動作"]; //, "置頂"
    private $th_width = ["80px", "", "", "", "", "150px"];
    private $order_column = ["id", "username", "", "status", "create_date", ""];
    private $can_order_fields = [0, 3, 4];

    private $param;
    private $action;

    public function __construct()
    {
        parent::__construct();
        $this->is_mgr_login();
        $this->data['active'] = "REPORT";
        $this->data['sub_active'] = 'REPORT_COMMENT';
        $this->action = base_url() . "mgr/report/";
        $this->load->model('Report_model');
        $this->load->model('Notification_model');
        $this->param = [
            //																								md 		sm
            ["廣告名稱",                 "title",            "text",                "",            TRUE,     "",     3,         12],
            ["影片網址",                    "url",                  "text",             "",         TRUE,     "",     6,         12],
        ];
    }

    public function comment()
    {
        $this->data['title'] = '檢舉留言列表';

        $this->data['custom_data_url'] = base_url() . 'mgr/report/comment_data';
        $this->data['action'] = $this->action;
        $this->data['th_title'] = $this->th_title;
        $this->data['th_width'] = $this->th_width;
        $this->data['can_order_fields'] = $this->can_order_fields;
        $this->data['tool_btns'] = [
            // ['新增廣告網址', base_url() . "mgr/adv/add", "btn-primary"],
            // ['上傳廣告影片', base_url() . "mgr/adv/addVideo", "btn-primary"]
        ];
        $this->data['default_order_column'] = 3;
        $this->data['default_order_direction'] = 'ASC';
        $this->load->view('mgr/report_list', $this->data);
    }

    public function user()
    {
        $this->data['title'] = '檢舉用戶列表';
        $this->data['sub_active'] = 'REPORT_USER';
        $this->data['custom_data_url'] = base_url() . 'mgr/report/user_data';
        $this->data['action'] = $this->action;
        $this->data['th_title'] = ["#", "舉報人", "被檢舉的用戶", "狀態", "檢舉時間", "動作"];;
        $this->data['th_width'] = ["80px", "", "", "", "", "150px"];;
        $this->data['can_order_fields'] = $this->can_order_fields;
        $this->data['tool_btns'] = [
            // ['新增廣告網址', base_url() . "mgr/adv/add", "btn-primary"],
            // ['上傳廣告影片', base_url() . "mgr/adv/addVideo", "btn-primary"]
        ];
        $this->data['default_order_column'] = 3;
        $this->data['default_order_direction'] = 'ASC';
        $this->load->view('mgr/report_list', $this->data);
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

    public function comment_data()
    {
        $page        = ($this->input->post("page")) ? $this->input->post("page") : 1;
        $search      = ($this->input->post("search")) ? $this->input->post("search") : "";
        $order       = ($this->input->post("order")) ? $this->input->post("order") : 0;
        $direction   = ($this->input->post("direction")) ? $this->input->post("direction") : "ASC";

        $order_column = $this->order_column;
        $canbe_search_field = ["username"];

        $syntax = "R.type = 'comment'";
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

        $order_by = "create_date ASC";
        if ($order_column[$order] != "") {
            $order_by = $order_column[$order] . " " . $direction . ", " . $order_by;
        }

        $data = $this->Report_model->get_data_list($syntax, $order_by, $page, $this->page_count);
        $html = "";
        foreach ($data['list'] as $item) {
            $html .= $this->load->view("mgr/items/report_comment_item", array(
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

    public function user_data()
    {
        $page        = ($this->input->post("page")) ? $this->input->post("page") : 1;
        $search      = ($this->input->post("search")) ? $this->input->post("search") : "";
        $order       = ($this->input->post("order")) ? $this->input->post("order") : 0;
        $direction   = ($this->input->post("direction")) ? $this->input->post("direction") : "ASC";

        $order_column = $this->order_column;
        $canbe_search_field = ["U.username", "U2.username"];

        $syntax = "R.type = 'user'";
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

        $order_by = "create_date ASC";
        if ($order_column[$order] != "") {
            $order_by = $order_column[$order] . " " . $direction . ", " . $order_by;
        }

        $data = $this->Report_model->get_data_list($syntax, $order_by, $page, $this->page_count);
        $html = "";
        foreach ($data['list'] as $item) {
            $html .= $this->load->view("mgr/items/report_user_item", array(
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

    public function del_report()
    {
        $id = $this->input->post('id');
        $report = $this->Report_model->get_data($id);
        $msg = '';
        // $this->output(FALSE,'error',['data'=>$report]);
        if ($report['type'] == 'comment') {
            $msg = "您舉報的留言【$report[content]】，\n經人工審核為違反規定，\n因此予以刪除留言。";

            $this->load->model('Post_model');
            if (!$this->Post_model->del_comment($report['relation_id'])) $this->output(FALSE, 'error');
        } elseif ($report['type'] == 'user') {
            $user = $this->User_model->get_user_data($report['relation_id']);
            $msg = "您舉報的用戶【$user[username]】，\n經人工審核為違反規定，\n因此予以停權。";

            if (!$this->User_model->edit($report['relation_id'], array('is_disable' => 1))) $this->output(FALSE, 'error');
        }

        // if (!$this->Notification_model->add_notification($report['user_id'], 'system', 0, $msg, array('type' => 'system'))) $this->output(FALSE, 'error');

        if ($this->Report_model->edit($id, array('status' => 'processed'))) {
            $this->output(TRUE, 'success');
        } else {
            $this->output(FALSE, 'error');
        }
    }

    public function pass_report()
    {
        $id = $this->input->post('id');
        // $report = $this->Report_model->get_data($id);
        // $msg = '';
        // if ($report['type'] == 'comment') {
        //     $msg = "您舉報的留言【$report[content]】，\n經人工審核為正常，\n因此不予以刪除留言。";
        // } elseif ($report['type'] == 'user') {
        //     $user = $this->User_model->get_user_data($report['relation_id']);
        //     $msg = "您舉報的用戶【$user[username]】，\n經人工審核為正常，\n因此不予以停權。";
        // }

        // if (!$this->Notification_model->add_notification($report['user_id'], 'system', 0, $msg, array('type' => 'system'))) $this->output(FALSE, 'error');

        if ($this->Report_model->edit($id, array('status' => 'processed'))) {
            $this->output(TRUE, 'success');
        } else {
            $this->output(FALSE, 'error');
        }
    }
}
