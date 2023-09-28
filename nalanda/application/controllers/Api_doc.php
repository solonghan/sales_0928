<?php
defined('BASEPATH') or exit('No direct script access allowed');
require('./vendor/autoload.php');

class Api_doc extends Base_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $openapi = OpenApi\scan(__DIR__);
        echo $openapi->toJson();
        // $jasonFile = './api2_doc.json';
        // file_put_contents($jasonFile, $openapi);
        // echo $jasonFile;
        // !d($openapi);
        // $this->load->view('swagger', '');
    }
}
