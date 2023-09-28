<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Post extends Base_Controller
{
    private $th_title = ["#", "類型", "第一張圖片", "貼文作者", "建立時間", "動作"]; //, "置頂"
    private $th_width = ["80px", "", "", "", "", "150px"];
    private $order_column = ["id", "type", "", "username", "create_date", ""];
    private $can_order_fields = [0, 4];

    private $param;
    private $action;

    public function __construct()
    {
        parent::__construct();
        $this->is_mgr_login();
        $this->data['active'] = "POST";
        $this->data['sub_active'] = 'POST_LIST';
        $this->load->model('Post_model');
        $this->action = base_url() . "mgr/post/";
        $this->param = [
            //																								md 		sm
            ["廣告名稱",                 "title",            "text",                "",            TRUE,     "",     3,         12],
            ["影片網址",                    "url",                  "text",             "",         TRUE,     "",     6,         12],
        ];
    }

    public function index($user_id = '')
    {
        $this->data['title'] = '貼文列表';

        if (!empty($user_id)) $this->data['custom_data_url'] = base_url() . "mgr/post/data/" . $user_id;
        $this->data['action'] = $this->action;
        $this->data['th_title'] = $this->th_title;
        $this->data['th_width'] = $this->th_width;
        $this->data['can_order_fields'] = $this->can_order_fields;
        $this->data['tool_btns'] = [
            // ['新增廣告網址', base_url() . "mgr/adv/add", "btn-primary"],
            // ['上傳廣告影片', base_url() . "mgr/adv/addVideo", "btn-primary"]
        ];
        $this->data['default_order_column'] = 4;
        $this->data['default_order_direction'] = 'DESC';
        $this->load->view('mgr/template_list', $this->data);
    }

    public function story($user_id = '')
    {
        $this->data['sub_active'] = 'STORY_LIST';
        $this->data['title'] = '限時動態列表';

        $this->data['action'] = base_url() . "mgr/post/";
        $this->data['custom_data_url'] =
            (!empty($user_id))
            ? base_url() . "mgr/post/story_data/" . $user_id
            : base_url() . "mgr/post/story_data";
        $this->data['th_title'] = ["#", "類型", "內容", "限時動態作者", "建立時間", "動作"];
        $this->data['th_width'] = ['80px', '', '', '', '', '150px'];
        $this->data['can_order_fields'] = [0, 4];
        $this->data['tool_btns'] = [
            // ['新增廣告網址', base_url() . "mgr/adv/add", "btn-primary"],
            // ['上傳廣告影片', base_url() . "mgr/adv/addVideo", "btn-primary"]
        ];
        $this->data['default_order_column'] = 4;
        $this->data['default_order_direction'] = 'DESC';
        $this->load->view('mgr/template_list', $this->data);
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

    public function data($user_id = '')
    {
        $page        = ($this->input->post("page")) ? $this->input->post("page") : 1;
        $search      = ($this->input->post("search")) ? $this->input->post("search") : "";
        $order       = ($this->input->post("order")) ? $this->input->post("order") : 0;
        $direction   = ($this->input->post("direction")) ? $this->input->post("direction") : "ASC";

        $order_column = $this->order_column;
        $canbe_search_field = [""];

        $syntax = "P.is_delete = 0";
        if (!empty($user_id)) $syntax .= " AND P.user_id = $user_id";
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

        $data = $this->Post_model->get_post_data_list($syntax, $order_by, $page, $this->page_count);
        $html = "";
        foreach ($data['list'] as $item) {
            $html .= $this->load->view("mgr/items/post_item", array(
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

    public function story_data($user_id = '')
    {
        $page        = ($this->input->post("page")) ? $this->input->post("page") : 1;
        $search      = ($this->input->post("search")) ? $this->input->post("search") : "";
        $order       = ($this->input->post("order")) ? $this->input->post("order") : 0;
        $direction   = ($this->input->post("direction")) ? $this->input->post("direction") : "ASC";

        $order_column = $this->order_column;
        $canbe_search_field = [""];

        $syntax = "S.is_delete = 0";
        if (!empty($user_id)) $syntax .= " AND S.user_id = $user_id";
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

        $data = $this->Post_model->get_story_data_list($syntax, $order_by, $page, $this->page_count);
        $html = "";
        foreach ($data['list'] as $item) {
            $html .= $this->load->view("mgr/items/story_item", array(
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

    public function detail($post_id)
    {
        // echo '還沒做';
        $data    = $this->Post_model->normal_post_single($post_id);
        $this->data['title'] = '貼文詳細資訊';
        $this->data['post']  = $data['post'];
        $this->data['photo'] = $data['post']['photo'];
        $this->data['video'] = $data['post']['video'];
        // var_dump($data['post']);


        $this->load->view('mgr/post_preview', $this->data);
    }
}
