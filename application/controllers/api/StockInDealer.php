<?php

defined('BASEPATH') or exit('No direct script access allowed');

class StockInDealer extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('m_admin');
        $this->load->model('h3_dealer_stock_model', 'dealer_stock');
    }

    public function index()
    {
        $qty_avs = $this->dealer_stock->qty_avs('ds.id_dealer', 'ds.id_part', 'ds.id_gudang', 'ds.id_rak', true);

        $query = $this->db
        ->select('ds.*')
        ->select('mp.id_part')
        ->select('mp.nama_part')
        ->select('mp.harga_dealer_user as harga_saat_dibeli')
        ->select("
            case
                when ds.id_gudang is not null then ds.id_gudang
                else '---'
            end as id_gudang
        ")
        ->select("
            case
                when ds.id_rak is not null then ds.id_rak
                else '---'
            end as id_rak
        ")
        ->select("IFNULL(({$qty_avs}), 0) AS stock")
        ->from('ms_part as mp')
        ->join('ms_h3_dealer_stock as ds', "(ds.id_part_int = mp.id_part_int and ds.id_dealer = '{$this->m_admin->cari_dealer()}')", 'left')
        ->where('mp.id_part', $this->input->get('id_part'))
        // ->where('ds.freeze', 0)
        ;
        send_json($query->get()->result());
    }
}
