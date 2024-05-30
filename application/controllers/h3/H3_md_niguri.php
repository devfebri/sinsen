<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_niguri extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_niguri";
	protected $title  = "Niguri";

	public function __construct(){		
		parent::__construct();

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('form_validation');
		$this->load->library('Mcarbon');

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

		$this->load->model('part_model', 'master_part');
		$this->load->model('H3_md_stock_model', 'stock');
		$this->load->model('H3_md_niguri_header_model', 'niguri_header');
		$this->load->model('H3_md_niguri_model', 'niguri');
	}

	public function index(){
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$this->template($data);
	}

	public function generate_niguri(){
		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', '0');

		$now = Mcarbon::now();
		$type_niguri = $this->input->get('type_niguri');

		$niguri = $this->niguri_header->niguri_exists($now, $type_niguri);
		if($niguri != null){
			$this->session->set_flashdata('pesan', "Niguri {$type_niguri} Periode " . $now->copy()->format('m/Y') . " sudah pernah dibuat.");
			$this->session->set_flashdata('tipe', 'info');

			send_json([
				'response_type' => 'niguri_already_exists',
				'payload' => $niguri
			]);
		}
		
		$this->db->trans_start();
		$id_niguri_header = $this->niguri_header->create_header($now, $type_niguri);
		$this->niguri->create_item($id_niguri_header, true);
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$this->session->set_flashdata('pesan', 'Niguri berhasil digenerate.');
			$this->session->set_flashdata('tipe', 'info');
			send_json([
				'response_type' => 'niguri_created',
				'payload' => $this->niguri_header->find($id_niguri_header),
			]);
		}else{
			$this->session->set_flashdata('pesan', 'Niguri tidak berhasil digenerate.');
			$this->session->set_flashdata('tipe', 'warning');
			send_json([
				'response_type' => 'niguri_not_created'
			]);
		}
		die;
	}

	public function perbarui_niguri(){
		$this->niguri->perbarui_item($this->input->get('id_niguri_header'));
	}

	public function detail(){
		$data['set'] = 'form';
		$data['mode'] = 'detail';

		$bulan = date('m');
		$tahun = date('Y');
		$data['header'] = $this->db
		->select('nh.id')
		->select('nh.tanggal_generate')
		->select('nh.type_niguri')
		->select('nh.created_at')
		->select('nh.updated_at')
		->select('nh.status')
		->select("(nh.bulan = '{$bulan}' AND nh.tahun = '{$tahun}') as periode_sama", false)
		->from('tr_h3_md_niguri_header as nh')
		->where('nh.id', $this->input->get('id'))
		->get()->row_array();

		$this->template($data);
	}

    public function update_fix_order(){
        $this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id_niguri_header', 'Header Niguri', 'required');
		$header_errors = [];
        if (!$this->form_validation->run())
        {
			$header_errors = $this->form_validation->error_array();
		}

		$parts_errors = [];
		foreach ($this->input->post('parts') as $part) {
			$this->form_validation->reset_validation();
			$this->form_validation->set_data($part);
			$this->form_validation->set_rules('id_part', 'Kode Part', 'required');
			$this->form_validation->set_rules('fix_order_n', 'Fix Order N', 'numeric');
			$this->form_validation->set_rules('fix_order_n_1', 'Fix Order N-1', 'numeric');
			$this->form_validation->set_rules('fix_order_n_2', 'Fix Order N-2', 'numeric');
			$this->form_validation->set_rules('fix_order_n_3', 'Fix Order N-3', 'numeric');
			$this->form_validation->set_rules('fix_order_n_4', 'Fix Order N-4', 'numeric');
			$this->form_validation->set_rules('fix_order_n_5', 'Fix Order N-5', 'numeric');

			if($this->input->post('type_niguri') == 'REG'){
				$this->form_validation->set_rules('qty_reguler', 'Qty Reguler', 'numeric');
			}

			if (!$this->form_validation->run()){
				$errors = [];
				foreach ($this->form_validation->error_array() as $key => $value) {
					$errors[] = $value;
				}
				$parts_errors[] = [
					'id_part' => $part['id_part'],
					'errors' => $errors
				];
			}
		}

		if(count($header_errors) > 0 || count($parts_errors) > 0){
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'header_errors' => $header_errors,
				'parts_errors' => $parts_errors,
			], 422);
		}

		$this->db->trans_start();
		foreach ($this->input->post('parts') as $part) {
			$data = [];
			$data['fix_order_n'] = $part['fix_order_n'] != '' ? $part['fix_order_n'] : 0;
			$data['fix_order_n_1'] = $part['fix_order_n_1'] != '' ? $part['fix_order_n_1'] : 0;
			$data['fix_order_n_2'] = $part['fix_order_n_2'] != '' ? $part['fix_order_n_2'] : 0;
			$data['fix_order_n_3'] = $part['fix_order_n_3'] != '' ? $part['fix_order_n_3'] : 0;
			$data['fix_order_n_4'] = $part['fix_order_n_4'] != '' ? $part['fix_order_n_4'] : 0;
			$data['fix_order_n_5'] = $part['fix_order_n_5'] != '' ? $part['fix_order_n_5'] : 0;
			$data['qty_reguler'] = $part['qty_reguler'] != '' ? $part['qty_reguler'] : 0;

			$this->niguri->update($data, [
				'id' => $part['id'],
				'id_part' => $part['id_part']
			]);

			log_message('debug', sprintf('Update niguri %s untuk kode part %s', $this->input->post('id_niguri_header'), $part['id_part']));
			log_message('debug', print_r($part, true));
		}
        
		$this->db->trans_complete();
		
		if(!$this->db->trans_status()){
			send_json([
				'message' => 'Tidak berhasil update'
			], 422);
		}
	}

	public function set_fix_order(){
        $this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_data($this->input->post());
		$this->form_validation->set_rules('id', 'ID Niguri Item', 'required');
		$this->form_validation->set_rules('update_key', 'Update Key', 'required');
		$this->form_validation->set_rules('value', 'Value', 'required');

		if (!$this->form_validation->run()){
			send_json([
				'errors' => $this->form_validation->error_array()
			], 422);
		}

		$this->db->trans_start();
		$this->db
		->set("n.{$this->input->post('update_key')}", $this->input->post('value'))
		->where('n.id', $this->input->post('id'))
		->update('tr_h3_md_niguri as n');
		$this->db->trans_complete();

		if(!$this->db->trans_status()){
			send_json([
				'message' => 'Gagal menyimpan value fix order'
			], 422);
		}else{
			send_json([
				'message' => 'OK'
			]);
		}
	}
	
	public function proses(){
		$niguri_header = (array) $this->niguri_header->find($this->input->get('id'));

		$this->db->trans_start();
		$this->niguri_header->update([
			'status' => 'Processed',
			'updated_at' => date('Y-m-d H:i:s', time()),
			'updated_by' => $this->session->userdata('id_user')
		], $this->input->get(['id']));
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$this->session->set_flashdata('pesan', 'Niguri Periode ' . date('m/Y', strtotime($niguri_header['tanggal_generate'])) . ' berhasil diproses.');
			$this->session->set_flashdata('tipe', 'info');
			
		}else{
			$this->session->set_flashdata('pesan', 'Niguri Periode ' . date('m/Y', strtotime($niguri_header['tanggal_generate'])) . ' tidak berhasil diproses.');
			$this->session->set_flashdata('tipe', 'info');
		}
		send_json($niguri_header);
	}

	public function close(){
		$niguri_header = (array) $this->niguri_header->find($this->input->get('id'));

		$this->db->trans_start();
		$this->niguri_header->update([
			'status' => 'Closed',
			'updated_at' => date('Y-m-d H:i:s', time()),
			'updated_by' => $this->session->userdata('id_user')
		], $this->input->get(['id']));
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$this->session->set_flashdata('pesan', 'Niguri Periode ' . date('m/Y', strtotime($niguri_header['tanggal_generate'])) . ' berhasil diclose.');
			$this->session->set_flashdata('tipe', 'info');
			
		}else{
			$this->session->set_flashdata('pesan', 'Niguri Periode ' . date('m/Y', strtotime($niguri_header['tanggal_generate'])) . ' tidak berhasil diclose.');
			$this->session->set_flashdata('tipe', 'info');
		}
		send_json($niguri_header);
	}

	public function cetak(){
		$this->load->model('H3_md_laporan_niguri_model', 'laporan_niguri');

		$this->laporan_niguri->generateExcel($this->input->get('id'));
	}
}