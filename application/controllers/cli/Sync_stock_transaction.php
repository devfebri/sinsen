<?php

class Sync_stock_transaction extends Honda_Controller {

    public function index(){
        $this->load->model('h3_dealer_stock_model', 'stock');
        
        $id_dealer = '103';

        $transaksi_stock_terakhir = $this->db
		->select('ts.stok_akhir as kuantitas', false)
		->from('ms_h3_dealer_transaksi_stok as ts')
		->where('ts.id_dealer', $id_dealer)
		->where('ts.id_part = p.id_part', null, false)
		->order_by('ts.created_at', 'desc')
		->limit(1)
		->get_compiled_select();

        $parts = $this->db
        ->select('p.id_part')
        ->select('ds.id_gudang')
        ->select('ds.id_rak')
        ->select('ds.stock')
        ->select("IFNULL(({$transaksi_stock_terakhir}), 0) as transaksi_stock_terakhir", false)
        ->from('ms_h3_dealer_stock as ds')
        ->join('ms_part as p', 'p.id_part = ds.id_part')
        ->join('ms_kelompok_part as kp', 'kp.id_kelompok_part = p.kelompok_part')
        ->where('ds.id_dealer', $id_dealer)
        ->where('kp.id_kelompok_part', 'BATT')
        ->get()->result_array();

        send_json($parts);
    }
}