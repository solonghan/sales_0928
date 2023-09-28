<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Merchant extends Base_Controller
{
    private $th_title = ["#", "商家封面", "商家名稱", "聯絡電話", "動作"]; //, "置頂"
    private $th_width = ["80px", "", "", "", "150px"];
    private $order_column = ["id", "", "", "", "", ""];
    private $can_order_fields = [0];

    private $param;
    private $action;

    public function __construct()
    {
        parent::__construct();
        $this->is_mgr_login();
        $this->data['active'] = "MERCHANT";
        $this->data['sub_active'] = 'MERCHANT_LIST';
        $this->action = base_url() . "mgr/merchant/";
        $this->load->model('Merchant_model');
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
        $this->data['title'] = '商家列表';

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

    public function data()
    {
        $page        = ($this->input->post("page")) ? $this->input->post("page") : 1;
        $search      = ($this->input->post("search")) ? $this->input->post("search") : "";
        $order       = ($this->input->post("order")) ? $this->input->post("order") : 0;
        $direction   = ($this->input->post("direction")) ? $this->input->post("direction") : "ASC";

        $order_column = $this->order_column;
        $canbe_search_field = ["title", "mobile",];

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

        $order_by = "create_date ASC";
        if ($order_column[$order] != "") {
            $order_by = $order_column[$order] . " " . $direction . ", " . $order_by;
        }

        $data = $this->Merchant_model->get_merchant_list($syntax, $order_by, $page, $this->page_count);
        $html = "";
        foreach ($data['list'] as $item) {
            $html .= $this->load->view("mgr/items/merchant_item", array(
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

    public function detail($id)
    {
        $merchant = $this->Merchant_model->get_merchant_single($id);
        $this->data['merchant'] = $merchant;
        $this->load->view('mgr/merchant_preview', $this->data);
    }

    //----------------------------------------------------------------商品

    public function commodity($merchant_id)
    {
        $merchant = $this->Merchant_model->get_merchant_single($merchant_id);
        $this->data['title'] = $merchant['title'] . '的商品列表';

        $this->data['custom_data_url'] = base_url() . "mgr/merchant/commodity_data";
        $this->data['action'] = $this->action;
        $this->data['th_title'] = ['#', '商品圖片', '商品名稱', '商品價格', '狀態'];
        $this->data['th_width'] = ['50px', '', '', '', '150px'];
        $this->data['can_order_fields'] = [0, 3, 4];
        $this->data['tool_btns'] = [
            // ['新增廣告網址', base_url() . "mgr/adv/add", "btn-primary"],
        ];
        $this->data['default_order_column'] = 4;
        $this->data['default_order_direction'] = 'ASC';
        $this->load->view('mgr/template_list', $this->data);
    }

    public function commodity_data()
    {
        $page        = ($this->input->post("page")) ? $this->input->post("page") : 1;
        $search      = ($this->input->post("search")) ? $this->input->post("search") : "";
        $order       = ($this->input->post("order")) ? $this->input->post("order") : 0;
        $direction   = ($this->input->post("direction")) ? $this->input->post("direction") : "ASC";

        $order_column = ["id", "", "", "price", "status"];
        $canbe_search_field = ["title", "price",];

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

        $order_by = "create_date ASC";
        if ($order_column[$order] != "") {
            $order_by = $order_column[$order] . " " . $direction . ", " . $order_by;
        }

        $data = $this->Merchant_model->get_commodity_list_b($syntax, $order_by, $page, $this->page_count);
        $html = "";
        foreach ($data['list'] as $item) {
            $html .= $this->load->view("mgr/items/commodity_item", array(
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

    //----------------------------------------------------------------評論

    public function comment($merchant_id)
    {
        $merchant = $this->Merchant_model->get_merchant_single($merchant_id);
        $this->data['title'] = $merchant['title'] . '的評論列表';

        $this->data['custom_data_url'] = base_url() . "mgr/merchant/comment_data";
        $this->data['action'] = $this->action;
        $this->data['th_title'] = ['#', '評論者', '評論內容', '評分', '商家回覆', '留言時間'];
        $this->data['th_width'] = ['50px', '', '', '', '', ''];
        $this->data['can_order_fields'] = [0, 3, 5];
        $this->data['tool_btns'] = [
            // ['新增廣告網址', base_url() . "mgr/adv/add", "btn-primary"],
        ];
        $this->data['default_order_column'] = 5;
        $this->data['default_order_direction'] = 'ASC';
        $this->load->view('mgr/template_list', $this->data);
    }

    public function comment_data()
    {
        $page        = ($this->input->post("page")) ? $this->input->post("page") : 1;
        $search      = ($this->input->post("search")) ? $this->input->post("search") : "";
        $order       = ($this->input->post("order")) ? $this->input->post("order") : 0;
        $direction   = ($this->input->post("direction")) ? $this->input->post("direction") : "ASC";

        $order_column = ["id", "", "", "score", "", "create_date"];
        $canbe_search_field = ["username", "score", "C.create_date"];

        $syntax = "C.is_delete=0";
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

        $data = $this->Merchant_model->get_comment_list_b($syntax, $order_by, $page, $this->page_count);
        $html = "";
        foreach ($data['list'] as $item) {
            $html .= $this->load->view("mgr/items/comment_item", array(
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
