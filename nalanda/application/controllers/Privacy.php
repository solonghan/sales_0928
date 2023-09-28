<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Privacy extends Base_Controller
{
    public function index()
    {
        $this->load->view('privacy');
    }
}
