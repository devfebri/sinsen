<?php

class h3_md_claim_ahm_model extends Honda_Model{

    protected $table = 'tr_h3_md_claim_ahm';

    public function __construct(){
        parent::__construct();
    }

    public function insert($data){
		$data['created_by'] = $this->session->userdata('id_user');
		
        parent::insert($data);
    }

}
