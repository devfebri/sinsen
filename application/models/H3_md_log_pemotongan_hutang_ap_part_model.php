<?php

class H3_md_log_pemotongan_hutang_ap_part_model extends Honda_Model {

	protected $table = 'tr_h3_md_log_pemotongan_hutang_ap_part';

	public function __construct(){
		parent::__construct();

		$this->load->library('Mcarbon');
	}

	public function insert($data){
		$data['created_at'] = Mcarbon::now()->toDateTimeString();
		parent::insert($data);
	}

}
