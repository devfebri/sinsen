<?php

class H3_md_proses_barang_bagi_model extends Honda_Model{

    protected $table = 'tr_h3_proses_barang_bagi';

    public function insert($data){
        $data['created_at'] = date('Y-m-d H:i:s', time());
        $data['created_by'] = $this->session->userdata('id_user');
        parent::insert($data);
    }

    public function generateID(){
        $tahun = date('Y', time());
        $bulan = date('m', time());
        $tahun_bulan = date('Y-m');

		$query = $this->db
		->from($this->table)
		->where("LEFT(created_at, 7)='{$tahun_bulan}'")
		->order_by('created_at', 'desc')
		->limit(1)
		->get();

		if ($query->num_rows() > 0) {
			$row = $query->row();
			$id_proses_barang_bagi = substr($row->id_proses_barang_bagi, 0, 3);
			$id_proses_barang_bagi = sprintf("%'.03d", $id_proses_barang_bagi + 1);
			$id = "{$id_proses_barang_bagi}/BG/{$bulan}/{$tahun}";
		}else{
			$id = "001/BG/{$bulan}/{$tahun}";
		}

   		return strtoupper($id);
    }
}
