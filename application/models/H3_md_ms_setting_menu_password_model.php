<?php

class H3_md_ms_setting_menu_password_model extends Honda_Model{
	
	protected $table = 'tr_h3_md_setting_menu_password';

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
