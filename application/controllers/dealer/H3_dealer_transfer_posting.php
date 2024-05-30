<?php

defined('BASEPATH') or exit('No direct script access allowed');

class H3_dealer_transfer_posting extends Honda_Controller

{
    protected $folder = "dealer";
    protected $page   = "h3_dealer_transfer_posting";
    protected $title  = "Transfer Posting";

    public function __construct(){

        parent::__construct();

        $this->load->database();
        $this->load->helper('url');
        // //===== Load Model =====
        $this->load->model('m_admin');
        $this->load->library('form_validation');
        $this->load->model('h3_dealer_outbound_form_part_transfer_model', 'outbound_form_part_transfer');
        $this->load->model('h3_dealer_outbound_form_part_transfer_parts_model', 'outbound_form_part_transfer_parts');
        $this->load->model('H3_dealer_gudang_h23_model', 'gudang');
        $this->load->model('H3_dealer_lokasi_rak_bin_model', 'rak');
        $this->load->model('Ms_part_model', 'part');
        $this->load->model('notifikasi_model', 'notifikasi');
        $this->load->model('h3_dealer_stock_model', 'stock');
    }

    public function index(){
        $data['set']	= "index";
        $this->template($data);
    }
}
