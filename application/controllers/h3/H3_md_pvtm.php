<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_pvtm extends Honda_Controller
{
	protected $folder = "h3";
	protected $page   = "h3_md_pvtm";
	protected $title  = "AHM FILE .PVTM";

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

		$this->load->model('H3_md_pvtm_model', 'pvtm');
		$this->load->model('ms_part_model', 'part');
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
		$lines = $this->upload_dan_baca_pvtm();
		$processed_data = $this->proses_pvtm($lines);

		$this->db->trans_begin();

		$all_valid = true;
		$validation_error = [];
		foreach ($processed_data as $data) {
			$this->form_validation->set_data($data);
			$this->validate_upload_pvtm();

			if (!$this->form_validation->run()){
				$all_valid = false;
				$validation_error[] = [
					'message' => "Terdapat data tidak lengkap pada {$data['no_part']}",
					'errors' =>$this->form_validation->error_array(),
				];
			}
			$this->form_validation->reset_validation();

			$pvtm = $this->pvtm->get([
				'no_part' => $data['no_part'],
				'tipe_marketing' => $data['tipe_marketing'],
			], true);

			$part = $this->part->find($data['no_part'], 'id_part');

			if($pvtm == null and $part != null){
				$this->pvtm->insert($data);
			}
		}

		if (!$all_valid) {
			$this->db->trans_rollback();

			send_json([
				'error_type' => 'validation_error',
				'payload' => $validation_error
			], 422);
		}

		if ($this->db->trans_status()) {
			$this->db->trans_commit();
			$this->session->set_userdata('pesan', 'File PVTM berhasil diupload.');
			$this->session->set_userdata('tipe', 'success');
		} else {
			$this->db->trans_rollback();
			$this->session->set_userdata('pesan', 'File PVTM tidak berhasil diupload.');
			$this->session->set_userdata('tipe', 'danger');
		}
	}

	public function validate_upload_pvtm(){
		$this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('no_part', 'Kode Part', [
			'required',
			['part_exist_callable', [$this->part, 'part_exist']]
		]);
        $this->form_validation->set_rules('tipe_marketing', 'Tipe Marketing', 'required');
    }

	public function upload_dan_baca_pvtm()
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

	public function proses_pvtm($fdo)
	{
		$registedInvoiceNumber = [];
		$finalData = [];

		$keys = [
			'no_part', 'tipe_marketing'
		];

		foreach ($fdo as $line) {
			// Lakukan pemecahan berdasarkan panjang karakter yang telah ditentukan.
			$column = $this->parsing_pvtm($line);

			$index = 0;
			$subArr = [];
			foreach ($keys as $index => $key) {
				$subArr[$key] = trim($column[$index]);				
			}
			$finalData[] = $subArr;
		}

		return $finalData;
	}

	public function parsing_pvtm($line)
	{
		$blocks = explode(';', $line);
		array_pop($blocks);
		return $blocks;
	}

	public function export()
	{
		set_time_limit(500);
		ini_set('memory_limit', '5000M');
		ini_set('max_execution_time', 1000000000000);

		$parts = $this->db
			->select('pvtm.no_part')
			->select('mp.nama_part')
			->select('pvtm.tipe_marketing as dua_digit')
			->select('ptm.tipe_marketing as tiga_digit')
			->select('ptm.deskripsi ')
			->from('ms_pvtm as pvtm')
			->join('ms_part as mp', 'mp.id_part=pvtm.no_part')
			->join('ms_ptm as ptm', 'ptm.tipe_produksi=pvtm.tipe_marketing')
			// ->limit(100)
			->get()->result_array();

		$delimiter = ";";
		$filename = "Master PVTM_" . date('Y-m-d') . ".csv";

		//create a file pointer
		$f = fopen('php://memory', 'w');

		$header= ['Kode Part','Nama Part','Kode 2 Digit','Kode 3 Digit','Deskripsi'];
		//set column headers
		$fields = $header;
		fputcsv($f, $fields, $delimiter);

		//output each row of the data, format line as csv and write to file pointer
		foreach ($parts as $part) {
			$lineData = array("'" . $part['no_part'], $part['nama_part'], $part['dua_digit'], $part['tiga_digit'], $part['deskripsi']);
			fputcsv($f, $lineData, $delimiter);
		}

		//move back to beginning of file
		fseek($f, 0);

		//set headers to download file rather than displayed
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="' . $filename . '";');

		//output all remaining data on a file pointer
		fpassthru($f);
	}
}
