<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_menu_model extends Base_Model {

	public function __construct()
	{
		parent::__construct();
	}

	private function _get_privilege_menu_by_privilege_id($privilege_id)
	{

		return $this->db->order_by("menu_id ASC")
						->get_where($this->mgr_privilege_menu_table, array("privilege_id"=>$privilege_id))
						->result_array();
	}

	private function _get_mgr_menu()
	{

		return $this->db->order_by('parent_id ASC, sort ASC')
						->get_where($this->mgr_menu_table, array('status' => 'on'))
						->result_array();
	}

	private function _set_member_menu_action($privilege_menu, $menus_item, $member_menu)
	{
		foreach ($privilege_menu as $item)
		{
			if ($item['menu_id'] == $menus_item['id'])
			{
				array_push($member_menu['action'], $item['action_id']);
			}
		}
		return $member_menu;
	}


	public function get_menu($member, $show = 'member')
	{
		if ( ! is_array($member))
			return NULL;

		$priv_menu = array();
		$privilege_menu = $this->_get_privilege_menu_by_privilege_id($member['privilege_id']);

		if (isset($member['privilege_id']))
		{
			foreach ($privilege_menu as $item)
			{
				if ($show == 'member' && $item['action_id'] != 1)
					continue ;

				$priv_menu[$item['menu_id']] = ($item['enabled'] == 1);
			}
		}

		$menus = $this->_get_mgr_menu();
		$member_menu = array();

		foreach ($menus as $menus_item)
		{
			if ($menus_item['parent_id'] == 0)
			{
				if (array_key_exists($menus_item['id'], $priv_menu))
				{
					if ($show == 'member' && ( ! $priv_menu[$menus_item['id']]))
						continue ;

					$member_menu[$menus_item['id']] = array(
							'controller' => $menus_item['controller'],
							'name'       => $menus_item['name'],
							'icon'       => $menus_item['icon'],
							'url'        => $menus_item['url'],
							'action'     => array(),
							'sub_menu'   => array(),
							"badge"    =>	0,
					);

					$member_menu[$menus_item['id']] = $this->_set_member_menu_action($privilege_menu, $menus_item, $member_menu[$menus_item['id']]);
				}
			}
			else
			{
				// 單純不想要包在太多大括號裡
				if ( ! array_key_exists($menus_item['parent_id'], $member_menu))
					continue ;

				if (array_key_exists($menus_item['id'], $priv_menu))
				{
					if ($show == 'member' && ( ! $priv_menu[$menus_item['id']]))
						continue ;

					$member_menu[$menus_item['parent_id']]['sub_menu'][$menus_item['id']] = array(
							'controller' => $menus_item['controller'],
							'name'       => $menus_item['name'],
							'icon'       => $menus_item['icon'],
							'url'        => $menus_item['url'],
							'action'     => array(),
							"badge"    =>	0,
					);

					$member_menu[$menus_item['parent_id']]['sub_menu'][$menus_item['id']] = $this->_set_member_menu_action($privilege_menu, $menus_item, $member_menu[$menus_item['parent_id']]['sub_menu'][$menus_item['id']]);
				}
			}
		}
		return $member_menu;
	}
}