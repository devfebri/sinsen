<?php

class H3_md_kartu_stock_model extends Honda_Model{

    protected $table = 'tr_h3_md_kartu_stock';

    public function __construct(){
		parent::__construct();
		$this->load->model('m_admin');
    }

    public function insert($data){
		$stock = $this->db
		->select('qty')
		->from('tr_stok_part')
		->where('id_part', $data['id_part'])
		->where('id_lokasi_rak', $data['id_lokasi_rak'])
		->get()->row();

		if($stock == null){
			$stock_awal = 0;
		}else{
			$stock_awal = $stock->qty;
		}

		$data['stock_awal'] = $stock_awal;
		if($data['tipe_transaksi'] == '+'){
			$data['stock_akhir'] = $stock_awal + $data['stock_value'];
		}else if($data['tipe_transaksi'] == '-'){
			$data['stock_akhir'] = $stock_awal - $data['stock_value'];
		}
		$data['created_at'] = date('Y-m-d H:i:s', time());

        parent::insert($data);
    }
}
