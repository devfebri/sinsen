<?php

class h3_md_retur_penjualan_model extends Honda_Model{

    protected $table = 'tr_h3_md_retur_penjualan';

    public function __construct(){
        parent::__construct();
    }

    public function insert($data){
		$data['created_at'] = date('Y-m-d H:i:s', time());
		$data['created_by'] = $this->session->userdata('id_user');
		$data['status'] = 'Open';
		
        parent::insert($data);
	}
	
	public function update($data, $condition){
		$data['updated_at'] = date('Y-m-d H:i:s', time());
		$data['updated_by'] = $this->session->userdata('id_user');
		
        parent::update($data, $condition);
    }

    public function generateID(){
		$th        = date('Y');
		$bln       = date('m');
		$th_bln    = date('Y-m');
		$thbln     = date('ym');
		
		$query = $this->db->select('*')
						->from($this->table)
						->where("LEFT(created_at, 7)='{$th_bln}'")
						->order_by('created_at', 'DESC')
						->limit(1)
						->get();

		if ($query->num_rows()>0) {
			$row        = $query->row();
			$id_retur_penjualan = substr($row->id_retur_penjualan, 0, 5);
			$id_retur_penjualan = sprintf("%'.05d",$id_retur_penjualan+1);
			$id   = "{$id_retur_penjualan}/SSP-PART/{$bln}/{$th}";
		}else{
			$id   = "00001/SSP-PART/{$bln}/{$th}";
		}

   		return strtoupper($id);
    }

}
