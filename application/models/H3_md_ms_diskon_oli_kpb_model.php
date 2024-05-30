<?php

class h3_md_ms_diskon_oli_kpb_model extends Honda_Model{

    protected $table = 'ms_h3_md_diskon_oli_kpb';

    public function __construct(){
        parent::__construct();
    }

    public function insert($data){
		$data['created_by'] = $this->session->userdata('id_user');
		$data['created_at'] = date('Y-m-d H:i:s', time());
		
        parent::insert($data);
	}
	
	public function update($data, $condition){
		$data['updated_by'] = $this->session->userdata('id_user');
		$data['updated_at'] = date('Y-m-d H:i:s', time());
		
        parent::update($data, $condition);
    }

    public function get_diskon_oli_kpb($id_part, $id_tipe_kendaraan){
        $diskon = $this->db
        ->select('dok.id_part')
        ->select('dok.id_tipe_kendaraan')
        ->select('dok.tipe_diskon')
        ->select('dok.diskon_value')
        ->from('ms_h3_md_diskon_oli_kpb as dok')
        ->where('dok.id_tipe_kendaraan', $id_tipe_kendaraan)
        ->where('dok.id_part', $id_part)
        ->get()->row_array();

        if($diskon == null){
            log_message('debug', sprintf('Diskon KPB untuk kode part %s dengan tipe kendaraan %s tidak ditemukan', $id_part, $id_tipe_kendaraan));
        }else{
            log_message('debug', sprintf('Diskon KPB untuk kode part %s dengan tipe kendaraan %s ditemukan [payload] %s', $id_part, $id_tipe_kendaraan, print_r($diskon, true)));
        }

        return $diskon;
    }
}
