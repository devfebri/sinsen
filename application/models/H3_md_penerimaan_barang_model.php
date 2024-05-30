<?php

class H3_md_penerimaan_barang_model extends Honda_Model{
	
	protected $table = 'tr_h3_md_penerimaan_barang';

	public function insert($data){
		$data['tanggal_penerimaan'] = date('Y-m-d', time());
		$data['created_at'] = date('Y-m-d H:i:s', time());
		$data['created_by'] = $this->session->userdata('id_user');

		parent::insert($data);
	}

	public function generateID(){
		$bulan_short = date('m');
		$tahun_short = date('y');
        $tahun_dan_bulan    = date('Y-m');
        
        $data = $this->db
        ->from($this->table)
        ->where("LEFT(created_at,7) = '{$tahun_dan_bulan}'")
        ->order_by('no_penerimaan_barang', 'desc')
        ->order_by('created_at', 'desc')
        ->limit(1)
        ->get();

        if ($data->num_rows()>0) {
            $row        = $data->row();
            $no_penerimaan_barang = substr($row->no_penerimaan_barang, -5);
			$new_kode   = "PB/{$bulan_short}/{$tahun_short}/" .sprintf("%'.05d", $no_penerimaan_barang+1);
			
        } else {
            $new_kode   = "PB/{$bulan_short}/{$tahun_short}/00001";
        }
        return strtoupper($new_kode);
	}
	
}
