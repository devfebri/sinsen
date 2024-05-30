<?php

class h3_md_so_other_model extends Honda_Model{

    protected $table = 'tr_h3_md_so_other';

    public function __construct(){
        parent::__construct();
    }

    public function insert($data){
		$data['created_by'] = $this->session->userdata('id_user');
		$data['status'] = 'New';
		
        parent::insert($data);
    }

    public function generateID(){
		$th        = date('Y');
		$bln       = date('m');
		$th_bln    = date('Y-m');
		$thbln     = date('ym');
		
		$query = $this->db->select('*')
						->from($this->table)
						->where("LEFT(tanggal, 7)='{$th_bln}'")
						->order_by('created_at', 'DESC')
						->limit(1)
						->get();

		if ($query->num_rows()>0) {
			$row        = $query->row();
			$id_so_other = substr($row->id_so_other, 0, 5);
			$id_so_other = sprintf("%'.05d",$id_so_other+1);
			$id   = "{$id_so_other}/SO-OTH/{$bln}/{$th}";
		}else{
			$id   = "00001/SO-OTH/{$bln}/{$th}";
		}

   		return strtoupper($id);
    }

}
