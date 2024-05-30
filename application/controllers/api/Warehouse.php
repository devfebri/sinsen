<?php
defined('BASEPATH') or exit('No direct script access allowed');

class warehouse extends CI_Controller
{
    public $table = "ms_h3_dealer_stock";
    public $pk = "id";

    public function __construct()
    {
        parent::__construct();
        $this->load->model('h3_dealer_gudang_h23_model', 'gudang');
        $this->load->model('h3_dealer_lokasi_rak_bin_model', 'rak');
        $this->load->model('dealer_model', 'dealer');
    }

    public function warehouse_pada_dealer()
    {
        send_json($this->gudang->warehousePadaDealer());
    }

    public function rak_warehouse_pada_dealer()
    {
        send_json($this->rak->rakWarehousePadaDealer());
    }
}
