<?php

class H3_md_ms_wilayah_penagihan_model extends Honda_Model{

	protected $table = 'ms_h3_md_wilayah_penagihan';

	public function __construct(){
		parent::__construct();
	}

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
}
