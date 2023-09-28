<?php
defined('BASEPATH') OR exit('No direct script access allowed');

ini_set('memory_limit', '1600M');
class Home extends Base_Controller {
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		header("Location: ".base_url()."mgr");
	}

}
