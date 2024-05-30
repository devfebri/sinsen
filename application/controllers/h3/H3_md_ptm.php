<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_ptm extends Honda_Controller
{
	protected $folder = "h3";
	protected $page   = "h3_md_ptm";
	protected $title  = "AHM FILE .PTM";

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
		$auth = $this->m_admin->user_auth($this->page, "select");
		$sess = $this->m_admin->sess_auth();
		if ($name == "" OR $auth == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
		} elseif ($sess == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "crash'>";
		}

		$this->load->model('H3_md_ptm_model', 'ptm');
	}

	public function index()
	{
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$this->template($data);
	}

	public function import()
	{
		$data['mode']    = 'upload';
		$data['set']     = "form";
		$this->template($data);
	}

	public function inject()
	{
		$lines = $this->upload_dan_baca_ptm();
		$processed_data = $this->proses_ptm($lines);

		$this->db->trans_begin();
		$all_valid = true;
		$validation_error = [];
		foreach ($processed_data as $data) {
			$this->form_validation->set_data($data);
			$this->validate_upload_ptm();

			if (!$this->form_validation->run()){
				$all_valid = false;
				$validation_error[] = [
					'message' => "Terdapat data tidak lengkap pada data tipe produksi {$data['tipe_produksi']} dan tipe marketing {$data['tipe_marketing']}",
					'errors' =>$this->form_validation->error_array(),
				];
			}
			$this->form_validation->reset_validation();

			$ptm = $this->ptm->get([
				'tipe_produksi' => $data['tipe_produksi'],
				'tipe_marketing' => $data['tipe_marketing']
			], true);

			if($data['terakhir_efektif'] != ''){
				$data['terakhir_efektif'] = DateTime::createFromFormat('ymd', $data['terakhir_efektif'])->format('Y-m-d');
			}

			if ($ptm == null) {
				$this->ptm->insert($data);
			}else{
				$this->ptm->update([
					'deskripsi' => $data['deskripsi'],
					'terakhir_efektif' => $data['terakhir_efektif']
				], [
					'tipe_produksi' => $data['tipe_produksi'],
					'tipe_marketing' => $data['tipe_marketing']
				]);
			}
		}
		$this->db->trans_complete();

		if (!$all_valid) {
			$this->db->trans_rollback();
			send_json([
				'error_type' => 'validation_error',
				'payload' => $validation_error
			], 422);
		}

		if ($this->db->trans_status()) {
			$this->db->trans_commit();
			$this->session->set_userdata('pesan', 'Data berhasil diupload.');
			$this->session->set_userdata('tipe', 'info');
		} else {
			$this->db->trans_rollback();
			$this->session->set_userdata('pesan', 'Data tidak berhasil diupload.');
			$this->session->set_userdata('tipe', 'danger');
		}
	}

	public function validate_upload_ptm(){
		$this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('terakhir_efektif', 'Terakhir Efektif', [
			['validate_terakhir_efektif_callable', [$this->ptm, 'validate_terakhir_efektif']]
		]);
        $this->form_validation->set_rules('tipe_marketing', 'Tipe Marketing', 'required');
        $this->form_validation->set_rules('tipe_produksi', 'Tipe Produksi', 'required');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'required');
    }

	public function upload_dan_baca_ptm()
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

	public function proses_ptm($fdo)
	{
		$registedInvoiceNumber = [];
		$finalData = [];

		$keys = [
			'tipe_produksi', 'tipe_marketing', 'deskripsi', 'terakhir_efektif',
		];

		foreach ($fdo as $line) {
			// Lakukan pemecahan berdasarkan panjang karakter yang telah ditentukan.
			$column = $this->parsing_ptm($line);

			$index = 0;
			$subArr = [];
			foreach ($column as $each) {
				$subArr[$keys[$index]] = trim($each);				
				$index++;
			}
			$finalData[] = $subArr;
		}

		return $finalData;
	}

	public function parsing_ptm($line)
	{
		$blocks = explode(';', $line);
		array_pop($blocks);
		return $blocks;
	}

	public function detail()
	{
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['fdo'] = $this->fdo->get($this->input->get(['invoice_number']), true);
		$data['parts'] = $this->db->select('fdop.*, date_format(fdop.dpp_due_date, "%d-%m-%Y") as dpp_due_date, date_format(fdop.ppn_due_date, "%d-%m-%Y") as ppn_due_date, mp.nama_part')
		->from('tr_h3_md_fdo_parts as fdop')
		->join('ms_part as mp', 'mp.id_part = fdop.id_part', 'left')
		->where($this->input->get(['invoice_number']))
		->get()->result();

		$this->template($data);
	}



	public function edit()

	{

		$data['mode']    = 'edit';

		$data['set']     = "form";

		$data['mutasi_gudang'] = $this->mutasi_gudang->get($this->input->get(['id_mutasi_gudang']), true);

		$data['part'] = $this->db->select('mp.id_part, mp.nama_part, mmg.qty')

			->from('tr_h3_md_mutasi_gudang as mmg')

			->where('mp.id_part', $data['mutasi_gudang']->id_part)

			->join('ms_part as mp', 'mp.id_part = mmg.id_part')

			->get()->row();



		$data['gudang_asal'] = $this->db->from('ms_gudang')

			->where('id_gudang', $data['mutasi_gudang']->id_gudang_asal)

			->get()->row();



		$data['lokasi_asal'] = $this->db->from('ms_lokasi_unit')

			->where('id_lokasi_unit', $data['mutasi_gudang']->id_lokasi_asal)

			->get()->row();



		$data['gudang_tujuan'] = $this->db->from('ms_gudang')

			->where('id_gudang', $data['mutasi_gudang']->id_gudang_tujuan)

			->get()->row();



		$data['lokasi_tujuan'] = $this->db->from('ms_lokasi_unit')

			->where('id_lokasi_unit', $data['mutasi_gudang']->id_lokasi_tujuan)

			->get()->row();



		$data['option_gudang_asal'] = $data['option_gudang_tujuan'] = $this->ms_gudang->all();



		$data['option_lokasi_asal'] = $this->db->select('*')

			->from('ms_lokasi_unit')

			->where('id_gudang', $data['mutasi_gudang']->id_gudang_asal)

			->get()->result();



		$data['option_lokasi_tujuan'] = $this->db->select('*')

			->from('ms_lokasi_unit')

			->where('id_gudang', $data['mutasi_gudang']->id_gudang_tujuan)

			->get()->result();



		$this->template($data);

	}



	public function update()

	{

		$mutasiGudangData = $this->input->post();



		$this->db->trans_start();

		$this->mutasi_gudang->update($mutasiGudangData, $this->input->post(['id_mutasi_gudang']));

		$this->db->trans_complete();



		if ($this->db->trans_status()) {

			send_json($this->mutasi_gudang->get($this->input->post(['id_mutasi_gudang']), true));

		}

	}

}
