<?php

class h3_dealer_transaksi_stok_model extends Honda_Model{

    protected $table = 'ms_h3_dealer_transaksi_stok';

    public function __construct(){
		parent::__construct();
		$this->load->model('m_admin');
    }

    public function insert($data){
		$stock = $this->db
		->select('stock')
		->from('ms_h3_dealer_stock')
		->where('id_dealer', $this->m_admin->cari_dealer())
		->where('id_part', $data['id_part'])
		->where('id_gudang', $data['id_gudang'])
		->where('id_rak', $data['id_rak'])
		->get()->row();

		if($stock == null){
			$stok_awal = 0;
		}else{
			$stok_awal = $stock->stock;
		}

		$data['stok_awal'] = $stok_awal;
		if($data['tipe_transaksi'] == '+'){
			$data['stok_akhir'] = $stok_awal + $data['stok_value'];
		}else if($data['tipe_transaksi'] == '-'){
			$data['stok_akhir'] = $stok_awal - $data['stok_value'];
		}
		$data['created_at'] = date('Y-m-d H:i:s', time());
		if(!isset($data['id_dealer'])){
			$data['id_dealer'] = $this->m_admin->cari_dealer();
		}

        parent::insert($data);
    }
}
