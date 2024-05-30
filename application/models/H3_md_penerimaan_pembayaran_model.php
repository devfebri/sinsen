<?php

class H3_md_penerimaan_pembayaran_model extends Honda_Model{

    protected $table = 'tr_h3_md_penerimaan_pembayaran';

    public function insert($data){
		$data['created_at'] = date('Y-m-d H:i:s', time());
		$data['created_by'] = $this->session->userdata('id_user');
		
        parent::insert($data);
    }

    public function generate_id(){
		$th = date('Y');
		$bln = date('m');
		$th_bln = date('Y-m');
		$thbln = date('ym');
		
		$query = $this->db->select('*')
		->from($this->table)
		->where("LEFT(created_at, 7)='{$th_bln}'")
		->order_by('created_at', 'DESC')
		->limit(1)
		->get();

		if ($query->num_rows()>0) {
			$row        = $query->row();
			$id_penerimaan_pembayaran = substr($row->id_penerimaan_pembayaran, 0, 5);
			$id_penerimaan_pembayaran = sprintf("%'.05d",$id_penerimaan_pembayaran+1);
			$id   = "{$id_penerimaan_pembayaran}/PMB/{$bln}/{$th}";
		}else{
			$id   = "00001/PMB/{$bln}/{$th}";
		}

   		return strtoupper($id);
    }

}
