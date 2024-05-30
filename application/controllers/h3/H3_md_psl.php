<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class H3_md_psl extends Honda_Controller
{
	protected $folder = "h3";
	protected $page   = "h3_md_psl";
	protected $title  = "AHM FILE .PSL";

	private $surat_jalan_ahm;

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

		$this->load->model('H3_md_psl_model', 'psl');
		$this->load->model('H3_md_psl_items_model', 'psl_items');
	}

	public function index()
	{
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$data['ps'] = $this->psl->all();

		$this->template($data);
	}

	public function upload()
	{
		$data['set']     = "upload";
		$this->template($data);
	}

	public function inject()
	{
		$upload_path = "./uploads/AHM";
		$config['upload_path'] = $upload_path;
		$config['allowed_types'] = '*';
		$config['overwrite'] = true;

		$this->load->library('upload');
		$this->upload->initialize($config);

		if (!$this->upload->do_upload('file')) {
			send_json([
				'message' => 'Tidak berhasil upload PSL'
			], 422);
		}

		$data = $this->upload->data();
		$filename = $data['file_name'];
		$packing_sheets = $this->psl->upload($filename);
		$surat_jalan_ahm = $packing_sheets[0]['surat_jalan_ahm'];

		$this->db->trans_start();
		$condition = ['surat_jalan_ahm' => $surat_jalan_ahm];
		$this->psl->insert_or_update($condition, $condition);
		$this->psl_items->insert_or_update_batch($packing_sheets, $condition);
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_userdata('pesan', "Berhasil! No Surat Jalan {$surat_jalan_ahm} berhasil disimpan.");
			$this->session->set_userdata('tipe', 'info');

			send_json([
				'message' => 'Berhasil upload PSL',
				'redirect_url' => base_url('h3/' . $this->page)
			]);
		} else {
			$this->session->set_userdata('pesan', 'Gagal! File yang di upload tidak tersimpan.');
			$this->session->set_userdata('tipe', 'danger');

			send_json([
				'message' => 'Tidak berhasil upload PSL'
			], 422);
		}
	}

	public function detail()
	{
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['psl'] = $this->psl->get($this->input->get(['surat_jalan_ahm']), true);
		$data['items'] = $this->db
		->select('psli.packing_sheet_number')
		->select('psp.id_part')
		->select('p.nama_part')
		->select('psp.no_doos')
		->select('psp.packing_sheet_quantity')
		->from('tr_h3_md_psl_items as psli')
		->join('tr_h3_md_ps_parts as psp', 'psp.packing_sheet_number = psli.packing_sheet_number')
		->join('ms_part as p', 'p.id_part = psp.id_part')
		->where('psli.surat_jalan_ahm', $this->input->get('surat_jalan_ahm'))
		->order_by('psp.no_doos', 'asc')
		->get()->result_array();

		$this->template($data);
	}
}
