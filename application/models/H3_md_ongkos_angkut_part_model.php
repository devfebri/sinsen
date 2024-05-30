<?php

class H3_md_ongkos_angkut_part_model extends Honda_Model{
	
	protected $table = 'ms_h3_md_ongkos_angkut_part';

	public function insert($data){
		$data['created_at'] = date('Y-m-d H:i:s', time());
		$data['created_by'] = $this->session->userdata('id_user');
		parent::insert($data);
	}
	
}
