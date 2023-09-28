<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datas_form_model extends Base_Model {

	public $changepwd_field = [
            ["舊密碼", "old_pwd", "password"],
            ["新密碼", "new_pwd", "password"],
        	["確認新密碼", "new_pwd_confirm", "password"]
    ];

}