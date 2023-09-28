<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cron extends Base_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function check_1_minutes()
	{
		$this->load->model('Chatroom_model');
		// 解除禁言
		$this->Chatroom_model->change_mute();
		// 解除剔除
		$this->Chatroom_model->change_remove();
	}

	public function check_per_days()
	{
		$this->load->model('Item_model');
		$this->load->model('Adv_model');
		// 每日補道具
		$this->Item_model->full_items();
		// 廣告排程
		$this->Adv_model->check_adv();
	}
}
