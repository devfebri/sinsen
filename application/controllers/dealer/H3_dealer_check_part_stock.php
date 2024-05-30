<?php
defined('BASEPATH') or exit('No direct script access allowed');

class h3_dealer_check_part_stock extends Honda_Controller
{
    public $folder = "dealer";
    public $page   = "h3_dealer_check_part_stock";
    public $title  = "Check Part Stock";

    public function __construct()
    {
        parent::__construct();
        //---- cek session -------//
        $name = $this->session->userdata('nama');
        if ($name=="") {
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
        }

        //===== Load Database =====
        $this->load->database();
        $this->load->helper('url');
        //===== Load Model =====
        $this->load->model('m_admin');
        $this->load->library('form_validation');
        $this->load->model('h3_dealer_gudang_h23_model', 'gudang_h23');
        $this->load->model('dealer_model', 'dealer');
    }
    
    public function index()
    {
        $data['set']	= "form";
        $this->template($data);
    }
}
