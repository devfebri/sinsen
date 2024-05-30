<?php

class H3_md_penagihan_pihak_kedua_model extends Honda_Model{

    protected $table = 'tr_h3_md_penagihan_pihak_kedua';

    public function insert($data){
		$data['created_at'] = date('Y-m-d H:i:s', time());
		$data['created_by'] = $this->session->userdata('id_user');
		$data['updated_at'] = date('Y-m-d H:i:s', time());
		$data['updated_by'] = $this->session->userdata('id_user');
		$data['status'] = 'Open';
		
		log_message('info', "Membuat Penagihan Pihak Kedua dengan nomor {$data['no_surat']}");
		
        parent::insert($data);
    }

	public function update($data, $condition){
		$data['updated_at'] = date('Y-m-d H:i:s', time());
		$data['updated_by'] = $this->session->userdata('id_user');
		
		log_message('info', "Memperbarui Penagihan Pihak Kedua [{$condition['id']}]");
		
        parent::update($data, $condition);
    }
}
