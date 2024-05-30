<?php

class h3_md_jawaban_claim_dealer_model extends Honda_Model{

    protected $table = 'tr_h3_md_jawaban_claim_dealer';

    public function __construct(){
        parent::__construct();
    }

    public function insert($data){
		$data['created_at'] = date('Y-m-d H:i:s', time());
		$data['created_by'] = $this->session->userdata('id_user');
		
        parent::insert($data);
    }

    public function generateID(){
		$th = date('Y');
		$bln = date('m');
		$tahun_bulan = date('Y-m');
		$thbln = date('ym');
		
		$query = $this->db
		->from($this->table)
		->where("LEFT(created_at, 7)='{$tahun_bulan}'")
		->order_by('created_at', 'desc')
		->order_by('id', 'desc')
		->limit(1)
		->get();

		if ($query->num_rows()>0) {
			$row = $query->row();
			$id_jawaban_claim_dealer = substr($row->id_jawaban_claim_dealer, 0, 5);
			$id_jawaban_claim_dealer = sprintf("%'.05d",$id_jawaban_claim_dealer+1);
			$id   = "{$id_jawaban_claim_dealer}/JCD/{$bln}/{$th}";
		}else{
			$id   = "00001/JCD/{$bln}/{$th}";
		}

   		return strtoupper($id);
    }

}
