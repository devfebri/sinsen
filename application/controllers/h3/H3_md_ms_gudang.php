<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_ms_gudang extends Honda_Controller
{

	protected $folder = "h3";
	protected $page   = "h3_md_ms_gudang";
	protected $title  = "Master Gudang";

	public function __construct()
	{
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('h3_md_gudang_model', 'gudang');
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('form_validation');
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page, "select");
		$sess = $this->m_admin->sess_auth();
		if ($name == "" or $auth == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
		} elseif ($sess == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "crash'>";
		}
	}

	public function index()
	{
		$data['set']	= "index";
		$this->template($data);
	}

	public function add()
	{
		$data['mode']    = 'insert';
		$data['set']     = "form";
		$data['satuan'] = $this->db->from('ms_satuan')->get()->result();
		$data['kelompok_vendor'] = $this->db->from('ms_kelompok_vendor')->get()->result();
		$data['kelompok_part'] = $this->db->from('ms_kelompok_part')->get()->result();
		$this->template($data);
	}
	public function save()
	{
		$this->validate();

		$this->db->trans_start();
		$data = $this->input->post(['kode_gudang', 'nama_gudang', 'jenis_gudang', 'alamat', 'luas_gudang', 'jumlah_rak', 'jumlah_binbox', 'jumlah_pallet']);
		$this->gudang->insert($data);
		$id = $this->db->insert_id();
		$this->db->trans_complete();

		$gudang = $this->gudang->find($id);

		if ($this->db->trans_status() and $gudang != null) {
			send_json([
				'redirect_url' => sprintf('h3/h3_md_ms_gudang/detail?id=%s', $gudang->id)
			]);
		} else {
			send_json([
				'message' => 'Gagal menyimpan gudang'
			], 422);
		}
	}

	public function validate()
	{
		$this->form_validation->set_error_delimiters('', '');
		if ($this->uri->segment(3) == 'update') {
			$gudang = $this->gudang->get($this->input->post(['id']), true);
			if ($gudang->kode_gudang != $this->input->post('kode_gudang')) {
				$this->form_validation->set_rules('kode_gudang', 'Kode Gudang', 'required|is_unique[ms_h3_md_gudang.kode_gudang]|max_length[30]');
			}
		} else {
			$this->form_validation->set_rules('kode_gudang', 'Kode Gudang', 'required|is_unique[ms_h3_md_gudang.kode_gudang]|max_length[30]');
		}
		$this->form_validation->set_rules('nama_gudang', 'Nama Gudang', 'required|max_length[30]');
		$this->form_validation->set_rules('jenis_gudang', 'Jenis Gudang', 'required');
		$this->form_validation->set_rules('alamat', 'Alamat', 'required|max_length[60]');
		$this->form_validation->set_rules('luas_gudang', 'Luas Gudang', 'required|numeric');
		$this->form_validation->set_rules('jumlah_rak', 'Jumlah Rak', 'required|numeric');
		$this->form_validation->set_rules('jumlah_binbox', 'Jumlah Binbox', 'required|numeric');
		$this->form_validation->set_rules('jumlah_pallet', 'Jumlah Pallet', 'required|numeric');

		if (!$this->form_validation->run()) {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
		}
	}

	public function detail()
	{
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['gudang'] = $this->gudang->find($this->input->get('id'));
		$this->template($data);
	}

	public function edit()
	{
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['gudang'] = $this->gudang->find($this->input->get('id'));
		$this->template($data);
	}

	public function update()
	{
		$this->validate();
		$data = $this->input->post(['kode_gudang', 'nama_gudang', 'jenis_gudang', 'alamat', 'luas_gudang', 'jumlah_rak', 'jumlah_binbox', 'jumlah_pallet', 'active']);
		$condition = $this->input->post(['id']);
		$this->db->trans_start();
		$this->gudang->update($data, $condition);
		$this->db->trans_complete();

		$gudang = $this->gudang->find($this->input->post('id'));

		if ($this->db->trans_status() and $gudang != null) {
			send_json([
				'redirect_url' => sprintf('h3/h3_md_ms_gudang/detail?id=%s', $gudang->id)
			]);
		} else {
			send_json([
				'message' => 'Gagal menyimpan gudang'
			], 422);
		}
	}
}
