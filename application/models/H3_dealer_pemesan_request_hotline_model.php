<?php

class h3_dealer_pemesan_request_hotline_model extends Honda_Model {

	protected $table = 'tr_h3_dealer_pemesan_request_hotline';
	
	public function __construct(){
		parent::__construct();

		$this->load->model('m_admin');
	}

	public function insert($data){
		$data['id_dealer'] = $this->m_admin->cari_dealer();

		parent::insert($data);
	}
}
?>