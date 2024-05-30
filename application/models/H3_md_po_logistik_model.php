<?php

class H3_md_po_logistik_model extends Honda_Model{

	protected $table = 'tr_h3_md_po_logistik';
	
	public function insert($data){
		$data['created_at'] = date('Y-m-d H:i:s', time());
		$data['created_by'] = $this->session->userdata('id_user');
		parent::insert($data);
	}

    public function generateID(){
		$tahun = date('Y');
		$bulan = date('m');
		$tahun_bulan = date('Y-m');

		$data = $this->db
		->select('id_po_logistik')
		->from($this->table)
		->where("LEFT(tanggal,7)='$tahun_bulan'")
		->order_by('id_po_logistik', 'desc')
		->order_by('created_at', 'desc')
		->limit(1)->get();

		if ($data->num_rows() > 0) {
			$row = $data->row();
			$id_po_logistik = substr($row->id_po_logistik, -5);
			$new_kode = sprintf("%'.05d",$id_po_logistik + 1);
		}else{
			$new_kode = '00001';
		}
   		return strtoupper($new_kode);
    }

}
