<?php

class h3_md_mutasi_gudang_model extends Honda_Model{

    protected $table = 'tr_h3_md_mutasi_gudang';

    public function insert($data){
		$data['created_at'] = date('Y-m-d H:i:s', time());
		$data['created_by'] = $this->session->userdata('id_user');
		$data['status'] = 'New';
        parent::insert($data);
    }

    public function generateID(){
		$th = date('Y');
		$bln = date('m');
		$th_bln = date('Y-m');
		$thbln = date('ym');

		$query = $this->db
		->select('*')
		->from($this->table)
		->where("LEFT(tanggal, 7)='{$th_bln}'")
		->order_by('id', 'DESC')
		->limit(1)
		->get();

		if ($query->num_rows()>0) {
			$row = $query->row();
			$id_mutasi_gudang = substr($row->id_mutasi_gudang, 0, 5);
			$id_mutasi_gudang = sprintf("%'.05d",$id_mutasi_gudang+1);
			$id = "{$id_mutasi_gudang}/MTG/{$bln}/{$th}";
		}else{
			$id = "00001/MTG/{$bln}/{$th}";
		}

   		return strtoupper($id);
    }
}
