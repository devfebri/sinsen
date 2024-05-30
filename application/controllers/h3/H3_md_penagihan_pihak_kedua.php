<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_penagihan_pihak_kedua extends Honda_Controller
{
	protected $folder = "h3";
	protected $page   = "h3_md_penagihan_pihak_kedua";
	protected $title  = "Penagihan Pihak Kedua";

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
		if ($name == "" or $auth == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
		} elseif ($sess == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "crash'>";
		}

		$this->load->model('h3_md_penagihan_pihak_kedua_model', 'penagihan_pihak_kedua');
		$this->load->model('h3_md_penagihan_pihak_kedua_tujuan_model', 'penagihan_pihak_kedua_tujuan');
	}

	public function index()
	{
		$data['mode'] = 'index';
		$data['set'] = 'index';

		$this->template($data);
	}

	public function add()
	{
		$data['mode'] = 'insert';
		$data['set'] = 'form';

		$this->template($data);
	}

	public function save()
	{
		$this->db->trans_start();
		$this->validate();

		$penagihan_pihak_kedua = $this->input->post([
			'referensi_int', 'referensi', 'tipe_referensi', 'nama_vendor', 'nominal_pembayaran', 'nomor_bg', 'nominal_bg',
			'divisi', 'no_surat', 'tgl_surat', 'tgl_jatuh_tempo', 'nominal', 'keterangan'
		]);
		$this->penagihan_pihak_kedua->insert($penagihan_pihak_kedua);
		$id = $this->db->insert_id();
		$penagihan_tujuan = $this->getOnly([
			'id_vendor'
		], $this->input->post('penagihan_tujuan'), [
			'id_penagihan_pihak_kedua' => $id
		]);
		if (count($penagihan_tujuan) > 0) {
			$this->penagihan_pihak_kedua_tujuan->insert_batch($penagihan_tujuan);
		}

		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			session_message('success', 'Penagihan pihak kedua berhasil disimpan');
			$penagihan_pihak_kedua = $this->penagihan_pihak_kedua->find($id);
			send_json($penagihan_pihak_kedua);
		} else {
			log_message('error', print_r($this->input->post(), true));
			send_json([
				'message' => 'Gagal membuat penagihan pihak kedua.'
			], 422);
		}
	}

	public function detail()
	{
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['penagihan'] = $this->db
			->from('tr_h3_md_penagihan_pihak_kedua as p')
			->where('p.id', $this->input->get('id'))
			->limit(1)
			->get()->row_array();

		$data['penagihan_tujuan'] = $this->db
			->select('v.id_vendor')
			->select('v.vendor_name')
			->from('tr_h3_md_penagihan_pihak_kedua_tujuan as pt')
			->join('ms_vendor as v', 'v.id_vendor = pt.id_vendor')
			->where('pt.id_penagihan_pihak_kedua', $this->input->get('id'))
			->get()->result_array();

		$this->template($data);
	}

	public function edit()
	{
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['penagihan'] = $this->db
			->from('tr_h3_md_penagihan_pihak_kedua as p')
			->where('p.id', $this->input->get('id'))
			->limit(1)
			->get()->row_array();

		$data['penagihan_tujuan'] = $this->db
			->select('v.id_vendor')
			->select('v.vendor_name')
			->from('tr_h3_md_penagihan_pihak_kedua_tujuan as pt')
			->join('ms_vendor as v', 'v.id_vendor = pt.id_vendor')
			->where('pt.id_penagihan_pihak_kedua', $this->input->get('id'))
			->get()->result_array();

		$this->template($data);
	}

	public function update()
	{
		$this->db->trans_start();
		$this->validate();

		$penagihan_pihak_kedua = $this->input->post([
			'referensi_int', 'referensi', 'tipe_referensi', 'nama_vendor', 'nominal_pembayaran', 'nomor_bg', 'nominal_bg',
			'divisi', 'no_surat', 'tgl_surat', 'tgl_jatuh_tempo', 'nominal', 'keterangan'
		]);
		$this->penagihan_pihak_kedua->update($penagihan_pihak_kedua, $this->input->post(['id']));
		$penagihan_tujuan = $this->getOnly([
			'id_vendor'
		], $this->input->post('penagihan_tujuan'), [
			'id_penagihan_pihak_kedua' => $this->input->post('id')
		]);
		$this->penagihan_pihak_kedua_tujuan->delete($this->input->post('id'), 'id_penagihan_pihak_kedua');
		if (count($penagihan_tujuan) > 0) {
			$this->penagihan_pihak_kedua_tujuan->insert_batch($penagihan_tujuan);
		}

		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			session_message('success', 'Penagihan pihak kedua berhasil diperbarui');
			$penagihan_pihak_kedua = $this->penagihan_pihak_kedua->find($this->input->post('id'));
			send_json($penagihan_pihak_kedua);
		} else {
			log_message('error', print_r($this->input->post(), true));
			send_json([
				'message' => 'Gagal memperbarui Entry Pengeluaran Bank'
			], 422);
		}
	}

	public function approve()
	{
		$this->db->trans_start();
		$this->penagihan_pihak_kedua->update([
			'status' => 'Approved',
			'approved_at' => date('Y-m-d H:i:s', time()),
			'approved_by' => $this->session->userdata('id_user'),
		], $this->input->get(['id']));
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			session_message('success', 'Berhasil approve penagihan pihak kedua.');
			$penagihan_pihak_kedua = $this->penagihan_pihak_kedua->find($this->input->get('id'));
			send_json($penagihan_pihak_kedua);
		} else {
			send_json([
				'message' => 'Gagal approve penagihan pihak kedua.'
			], 422);
		}
	}

	public function cancel()
	{
		$this->db->trans_start();
		$this->penagihan_pihak_kedua->update([
			'status' => 'Canceled',
			'canceled_at' => date('Y-m-d H:i:s', time()),
			'canceled_by' => $this->session->userdata('id_user'),
		], $this->input->get(['id']));
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			session_message('success', 'Berhasil cancel penagihan pihak kedua.');
			$penagihan_pihak_kedua = $this->penagihan_pihak_kedua->find($this->input->get('id'));
			send_json($penagihan_pihak_kedua);
		} else {
			send_json([
				'message' => 'Gagal reject entry pengeluaran bank'
			], 422);
		}
	}

	public function validate()
	{
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('referensi_int', 'Referensi int', 'required|numeric');
		$this->form_validation->set_rules('referensi', 'Referensi', 'required');
		$this->form_validation->set_rules('tgl_surat', 'Tanggal Surat', 'required');
		$this->form_validation->set_rules('tgl_jatuh_tempo', 'Tanggal Jatuh Tempo', 'required');

		if ($this->uri->segment(3) == 'update') {
			$penagihan_pihak_kedua = $this->penagihan_pihak_kedua->find($this->input->post('id'));

			if (
				!($penagihan_pihak_kedua->no_surat == $this->input->post('no_surat'))
			) {
				$this->form_validation->set_rules('no_surat', 'No. Surat', 'required|is_unique[tr_h3_md_penagihan_pihak_kedua.no_surat]');
			}
		} else {
			$this->form_validation->set_rules('no_surat', 'No. Surat', 'required|is_unique[tr_h3_md_penagihan_pihak_kedua.no_surat]');
		}

		if (!$this->form_validation->run()) {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
		}
	}
}
