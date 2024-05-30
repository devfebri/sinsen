<?php

class H3_md_status_update_harga_model extends Honda_Model {

	protected $table = 'tr_h3_md_status_update_harga';

	private $user_id;

	public function __construct(){
		parent::__construct();

        $this->load->library('Mcarbon');

		$this->user_id = $this->session->userdata('id_user');
	}

	public function sudah_update_po_dealer(){
		$data = [
			'update_po_dealer' => 1,
			'update_po_dealer_timestamp' => Mcarbon::now()->toDateTimeString(),
			'update_po_dealer_by' => $this->user_id
		];

		$this->db
		->set($data)
		->update($this->table);

		log_message('debug', sprintf('Update harga PO dealer %s', print_r($data, true)));
	}

	public function sudah_update_so(){
		$data = [
			'update_so' => 1,
			'update_so_timestamp' => Mcarbon::now()->toDateTimeString(),
			'update_so_by' => $this->user_id
		];

		$this->db
		->set($data)
		->update($this->table);

		log_message('debug', sprintf('Update harga SO MD %s', print_r($data, true)));
	}

	public function sudah_update_do(){
		$data = [
			'update_do' => 1,
			'update_do_timestamp' => Mcarbon::now()->toDateTimeString(),
			'update_do_by' => $this->user_id
		];

		$this->db
		->set($data)
		->update($this->table);

		log_message('debug', sprintf('Update harga DO MD %s', print_r($data, true)));
	}

	public function sudah_update_po_md(){
		$data = [
			'update_po_md' => 1,
			'update_po_md_timestamp' => Mcarbon::now()->toDateTimeString(),
			'update_po_md_by' => $this->user_id
		];

		$this->db
		->set($data)
		->update($this->table);

		log_message('debug', sprintf('Update harga PO MD %s', print_r($data, true)));
	}

	public function sudah_update_niguri(){
		$data = [
			'update_niguri' => 1,
			'update_niguri_timestamp' => Mcarbon::now()->toDateTimeString(),
			'update_niguri_by' => $this->user_id
		];

		$this->db
		->set($data)
		->update($this->table);

		log_message('debug', sprintf('Update harga Niguri MD %s', print_r($data, true)));
	}

	public function sudah_update_do_revisi(){
		$data = [
			'update_do_revisi' => 1,
			'update_do_revisi_timestamp' => Mcarbon::now()->toDateTimeString(),
			'update_do_revisi_by' => $this->user_id
		];

		$this->db
		->set($data)
		->update($this->table);

		log_message('debug', sprintf('Update harga DO revisi %s', print_r($data, true)));
	}
}
?>