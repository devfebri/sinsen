<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_monitor_ptrac extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_monitor_ptrac";
    protected $title  = "Monitoring PTRAC";

	public function __construct()
	{		
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('form_validation');

		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page,"select");		
		$sess = $this->m_admin->sess_auth();						
		if($name=="" OR $auth=='false')
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."denied'>";
		}elseif($sess=='false'){
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."crash'>";
		}

		$this->load->model('H3_md_ptrac_model', 'ptrac');
		$this->load->model('ms_part_model', 'part');
	}

	public function index(){
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$this->template($data);
	}

	public function upload(){
		$data['mode']    = 'upload';
		$data['set']     = "upload";
		$this->template($data);
	}

	public function inject()
	{
		$lines = $this->upload_dan_baca();
		$parsedData = $this->proses_file($lines);

		$this->db->trans_begin();
		$terdapat_part_yang_tidak_terdaftar = [];
		foreach ($parsedData as $each) {
			// Check part
			$part = $this->part->find($each['id_part'], 'id_part');
			if($part == null){
				$terdapat_part_yang_tidak_terdaftar[] = $each['id_part'];
				continue;
			}

			$condition = [
				'no_po_ahm' => $each['no_po_ahm'],
				'id_part' => $each['id_part'],
			];
			$this->ptrac->insert_or_update($each, $condition);
		}

		if ($this->db->trans_status() AND count($terdapat_part_yang_tidak_terdaftar) < 1) {
			$this->db->trans_commit();
			$this->session->set_userdata('pesan', 'File .PTRAC berhasil diupload.');
			$this->session->set_userdata('tipe', 'info');
		} else {
			$this->db->trans_rollback();
			send_json([
				'error_type' => 'part_not_found',
				'payload' => [
					'terdapat_part_yang_tidak_terdaftar' => array_unique($terdapat_part_yang_tidak_terdaftar)
				]
			], 422);
		}
	}
	public function upload_dan_baca()
	{
		$upload_path = "./uploads/AHM";
		$config['upload_path'] = $upload_path;
		$config['allowed_types'] = '*';
		$config['overwrite'] = true;

		$this->load->library('upload');
		$this->upload->initialize($config);

		if ($this->upload->do_upload('file')) {
			$data = $this->upload->data();
			$myfile = fopen("$upload_path/{$data['file_name']}", "r");

			$lines = [];
			while ($line = fgets($myfile)) {
				$lines[] = $line;
			}
			return $lines;
		}
	}

	public function proses_file($ptrac)
	{
		$keys = [
			'no_po_ahm', 'id_part', 'qq_ship_to', 'kode_md', 'kode_order', 
			'qty_po', 'qty_ship', 'month_deliver', 'no_po_md', 'qty_book', 
			'qty_packing', 'qty_picking', 'dmodi', 'dcrea', 'flag_fast_slow', 
			'qty_invoice', 'flag_additional'
		];
		$result = [];
		foreach ($ptrac as $each_ptrac) {
			$index = 0;
			$exploded_each_ptrac = explode(';', $each_ptrac);
			array_pop($exploded_each_ptrac);

			$data = [];
			foreach ($exploded_each_ptrac as $value) {
				if($value == '' || $value == null){
					$data[
						$keys[$index]
					] = null;
				}else{
					$data[
						$keys[$index]
					] = trim($value);
				}
				
				$index++;
			}
			$result[] = $data;
		}

		return $result;
	}
}