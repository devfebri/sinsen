<?php

class h3_md_list_update_harga_model extends Honda_Model
{
    protected $table = 'tr_h3_md_list_parts_update_harga';

	public function __construct(){
		$this->load->library('Mcarbon');
	}

    public function insert($data){
		$data['created_at'] = Mcarbon::now()->toDateTimeString();
		$data['created_by'] = $this->session->userdata('id_user');
        parent::insert($data);
    }

	public function set_process($id){
		$this->db
		->set('luh.sudah_proses', 1)
		->where('luh.id', $id)
		->update(sprintf('%s as luh', $this->table));

		log_message('debug', sprintf('List update harga diproses [%s]', $id));
	}
}
