<?php

class H3_md_penerimaan_manual_model extends Honda_Model{

    protected $table = 'tr_h3_md_penerimaan_manual';

    public function insert($data){
		$data['created_at'] = date('Y-m-d H:i:s', time());
		$data['created_by'] = $this->session->userdata('id_user');
		$data['status'] = 'New';

        parent::insert($data);
	}
	
	public function update($data, $condition){
		$data['updated_at'] = date('Y-m-d H:i:s', time());
		$data['updated_by'] = $this->session->userdata('id_user');

        parent::update($data, $condition);
    }

    public function generateID(){
		$tahun_bulan = date('Y-m', time());
		$tahun = date('Y', time());
		$bulan = date('m', time());

		$query = $this->db
		->from($this->table)
		->where("LEFT(created_at, 7) = '{$tahun_bulan}'")
		->order_by('created_at', 'desc')
		->where('created_at >', '2020-08-03 11:15:00')
		->limit(1)
		->get();

		if ($query->num_rows()>0) {
			$row = $query->row();
			$id_penerimaan_manual = substr($row->id_penerimaan_manual, 0, 5);
			$id_penerimaan_manual = sprintf("%'.05d",$id_penerimaan_manual+1);
			$id = "{$id_penerimaan_manual}/{$bulan}/{$tahun}";
		}else{
			$id   = "00001/{$bulan}/{$tahun}";
		}

   		return strtoupper($id);
    }
}