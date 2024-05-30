<?php

class H3_md_surat_pengantar_claim_c3_model extends Honda_Model{

    protected $table = 'tr_h3_md_surat_pengantar_claim_c3_dealer';

    public function insert($data){
        $data['created_at'] = date('Y-m-d H:i:s', time());
        $data['created_by'] = $this->session->userdata('id_user');
        
        parent::insert($data);
    }

    public function generateID($id_dealer){
        $dealer = $this->db
        ->select('d.kode_dealer_md')
        ->from('ms_dealer as d')
        ->where('d.id_dealer', $id_dealer)
        ->get()->row_array();

		$tahun = date('Y');
		$bulan = date('m');
		$tahun_bulan = date('Y-m');

		$data = $this->db
		->select('id_surat_pengantar')
		->from($this->table)
		->where("LEFT(tanggal, 7)='{$tahun_bulan}'")
		->where('id_dealer', $id_dealer)
		->order_by('created_at', 'desc')
		->limit(1)->get();

		if ($data->num_rows() > 0) {
			$row = $data->row();
			$id_surat_pengantar = substr($row->id_surat_pengantar, 0, 5);
			$new_kode = sprintf("%'.05d", $id_surat_pengantar + 1 ) . "/SSP-CLAIM-C3/{$dealer['kode_dealer_md']}/{$bulan}/{$tahun}";
		}else{
			$new_kode = "00001/SSP-CLAIM-C3/{$dealer['kode_dealer_md']}/{$bulan}/{$tahun}";
		}
   		return strtoupper($new_kode);
    }

}
