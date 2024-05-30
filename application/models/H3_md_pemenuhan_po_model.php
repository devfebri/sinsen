<?php

class h3_md_pemenuhan_po_model extends Honda_Model{

    protected $table = 'tr_h3_md_pemenuhan_po';

    public function __construct(){
        parent::__construct();
    }

    public function insert($data){
		$data['created_by'] = $this->session->userdata('id_user');
		
        parent::insert($data);
    }

}
