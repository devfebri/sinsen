<?php

class h3_md_claim_dealer_model extends Honda_Model{

    protected $table = 'tr_h3_md_claim_dealer';

    public function insert($data){
		$data['created_at'] = date('Y-m-d H:i:s', time());
		$data['created_by'] = $this->session->userdata('id_user');

        parent::insert($data);
	}

	public function update($data, $condition){
		$data['updated_at'] = date('Y-m-d H:i:s', time());
		$data['updated_by'] = $this->session->userdata('id_user');

        parent::update($data, $condition);
	}

    public function generateID($id_dealer){
		$th = date('Y');
		$bln = date('m');
		$tahun_bulan = date('Y-m');
		$thbln = date('ym');

		$dealer = $this->db
		->select('d.kode_dealer_md')
		->from('ms_dealer as d')
		->where('d.id_dealer', $id_dealer)
		->get()->row();

		$query = $this->db
		->select('id_claim_dealer')
		->from($this->table)
		->where("LEFT(tanggal, 7)='{$tahun_bulan}'", null, false)
		->where('id_dealer', $id_dealer)
		->where('created_at >', '2020-10-10 19:50:00')
		->order_by('id', 'DESC')
		->limit(1)
		->get();

		if ($query->num_rows()>0) {
			$row = $query->row();
			$id_claim_dealer = substr($row->id_claim_dealer, 0, 3);
			$id_claim_dealer = sprintf("%'.03d",$id_claim_dealer+1);
			$id = "{$id_claim_dealer}/C3-{$dealer->kode_dealer_md}/{$bln}/{$th}";
		}else{
			$id = "001/C3-{$dealer->kode_dealer_md}/{$bln}/{$th}";
		}

   		return strtoupper($id);
    }
}