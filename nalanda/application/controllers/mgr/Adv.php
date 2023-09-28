<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Adv extends Base_Controller
{
    private $th_title = ["#", "廣告封面", "廣告名稱", "商家名稱", "審核狀態", "投放時間", "動作"]; //, "置頂"
    private $th_width = ["80px", "", "", "", "", "", "150px"];
    private $order_column = ["id", "", "", "", "status", "", ""];
    private $can_order_fields = [0, 4];

    private $param;
    private $action;

    public function __construct()
    {
        parent::__construct();
        $this->is_mgr_login();
        $this->data['active'] = "MERCHANT";
        $this->data['sub_active'] = 'ADV_LIST';
        $this->action = base_url() . "mgr/adv/";
        $this->load->model('Adv_model');
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
        $this->data['title'] = '廣告列表';

        $this->data['action'] = $this->action;
        $this->data['th_title'] = $this->th_title;
        $this->data['th_width'] = $this->th_width;
        $this->data['can_order_fields'] = $this->can_order_fields;
        $this->data['tool_btns'] = [
            // ['新增廣告網址', base_url() . "mgr/adv/add", "btn-primary"],
        ];
        $this->data['default_order_column'] = 4;
        $this->data['default_order_direction'] = 'ASC';
        $this->load->view('mgr/adv_list', $this->data);
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
        $canbe_search_field = ["A.title", "M.title", "A.start_date", "A.end_date"];

        $syntax = "A.is_delete=0";
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

        $order_by = "update_date ASC";
        if ($order_column[$order] != "") {
            $order_by = $order_column[$order] . " " . $direction . ", " . $order_by;
        }

        $data = $this->Adv_model->get_adv_data($syntax, $order_by, $page, $this->page_count);
        $html = "";
        foreach ($data['list'] as $item) {
            $html .= $this->load->view("mgr/items/adv_item", array(
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

    public function pass()
    {
        $id = $this->input->post('id');
        $adv = $this->Adv_model->get_adv($id);

        $msg = '';
        if ($adv['start_date'] > date("Y-m-d")) {
            $res = $this->Adv_model->edit($id, array('status' => 'pending_execute'));
            $msg = '待執行';
        } elseif ($adv['start_date'] <= date("Y-m-d") && $adv['end_date'] >= date("Y-m-d")) {
            $res = $this->Adv_model->edit($id, array('status' => 'executed'));
            $msg = '執行中';
        } elseif ($adv['start_date'] <= date("Y-m-d") && $adv['end_date'] < date("Y-m-d")) {
            $res = $this->Adv_model->edit($id, array('status' => 'finish'));
            $msg = '已結束';
        }

        if ($res) {
            $this->output(TRUE, 'success', array('text' => $msg));
        } else {
            $this->output(TRUE, 'fail');
        }
    }

    public function detail($id)
    {
        $adv = $this->Adv_model->get_adv($id);

        $adv['age'] = $this->adv_option_generate()['age']['option'][$adv['age']];
        $adv['sex'] = $this->adv_option_generate()['sex']['option'][$adv['sex']];

        // $adv['area'] = json_decode($adv['area'], true);
        $area = "";
        foreach ($adv['area'] as $item) {
            $area .= $this->adv_option_generate()['area']['option'][$item] . ",";
        }
        if (count($adv['area']) > 0) $area = substr($area, 0, -1);
        $adv['area'] = $area;
        $this->data['adv'] = $adv;
        $this->load->view("mgr/adv_preview", $this->data);
    }
}
