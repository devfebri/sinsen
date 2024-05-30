<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_ms_ekspedisi extends Honda_Controller
{

	protected $folder = "h3";
	protected $page   = "h3_md_ms_ekspedisi";
	protected $title  = "Master Ekspedisi";

	public function __construct()
	{
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('H3_md_ekspedisi_model', 'ekspedisi');
		$this->load->model('H3_md_ekspedisi_item_model', 'ekspedisi_item');
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
		$this->template($data);
	}

	public function save()
	{
		$this->db->trans_start();
		$this->validate();
		$data = $this->input->post([
			'id_vendor', 'nama_ekspedisi', 'npwp', 'nama_pemilik',
			'no_telp', 'alamat', 'tanggal_kerjasama', 'ppn', 'id_dealer'
		]);
		$data = $this->clean_data($data);

		$this->ekspedisi->insert($data);
		$id = $this->db->insert_id();
		$items = $this->getOnly([
			'type_mobil', 'kapasitas', 'no_polisi',
			'nama_supir', 'produk_angkatan'
		], $this->input->post('items'), [
			'id_ekspedisi' => $id
		]);
		$this->ekspedisi_item->insert_batch($items);
		$this->db->trans_complete();

		$ekspedisi = $this->ekspedisi->find($id);

		if ($this->db->trans_status() and $ekspedisi != null) {
			send_json([
				'redirect_url' => sprintf('h3/h3_md_ms_ekspedisi/detail?id=%s', $ekspedisi->id)
			]);
		} else {
			send_json([
				'message' => 'Tidak berhasil menyimpan ekspedisi'
			], 422);
		}
	}

	public function detail()
	{
		$data['mode'] = 'detail';
		$data['set'] = "form";
		$data['ekspedisi'] = $this->db
			->select('e.*')
			->select('d.id_dealer')
			->select('d.nama_dealer')
			->from('ms_h3_md_ekspedisi as e')
			->join('ms_dealer as d', 'd.id_dealer = e.id_dealer', 'left')
			->where('e.id', $this->input->get('id'))
			->get()->row();

		$data['items'] = $this->db
			->from('ms_h3_md_ekspedisi_item as ei')
			->where('ei.id_ekspedisi', $this->input->get('id'))
			->get()->result();

		$this->template($data);
	}

	public function edit()
	{
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['ekspedisi'] = $this->db
			->select('e.*')
			->select('d.id_dealer')
			->select('d.nama_dealer')
			->from('ms_h3_md_ekspedisi as e')
			->join('ms_dealer as d', 'd.id_dealer = e.id_dealer', 'left')
			->where('e.id', $this->input->get('id'))
			->get()->row();

		$data['items'] = $this->db
			->from('ms_h3_md_ekspedisi_item as ei')
			->where('ei.id_ekspedisi', $this->input->get('id'))
			->get()->result();

		$this->template($data);
	}

	public function update()
	{
		$this->db->trans_start();
		$this->validate();
		$data = $this->input->post([
			'id_vendor', 'nama_ekspedisi', 'npwp', 'nama_pemilik',
			'no_telp', 'alamat', 'tanggal_kerjasama', 'ppn', 'id_dealer'
		]);
		$data = $this->clean_data($data);

		$this->ekspedisi->update($data, $this->input->post(['id']));
		$items = $this->getOnly([
			'type_mobil', 'kapasitas', 'no_polisi',
			'nama_supir', 'produk_angkatan'
		], $this->input->post('items'), [
			'id_ekspedisi' => $this->input->post('id')
		]);
		$this->ekspedisi_item->update_batch($items, [
			'id_ekspedisi' => $this->input->post('id')
		]);
		$this->db->trans_complete();

		$ekspedisi = $this->ekspedisi->find($this->input->post('id'));

		if ($this->db->trans_status() and $ekspedisi != null) {
			send_json([
				'redirect_url' => sprintf('h3/h3_md_ms_ekspedisi/detail?id=%s', $ekspedisi->id)
			]);
		} else {
			send_json([
				'message' => 'Tidak berhasil memperbarui ekspedisi'
			], 422);
		}
	}

	public function validate()
	{
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id_vendor', 'Nama Ekspedisi', 'required');
		$this->form_validation->set_rules('nama_ekspedisi', 'Nama Ekspedisi', 'required');
		$this->form_validation->set_rules('npwp', 'No. NPWP', 'required');
		$this->form_validation->set_rules('nama_pemilik', 'Nama Pemilik', 'required');
		$this->form_validation->set_rules('no_telp', 'No Telepon', 'required');
		$this->form_validation->set_rules('alamat', 'Alamat', 'required');
		$this->form_validation->set_rules('tanggal_kerjasama', 'Tanggal Kerjasama', 'required');
		$this->form_validation->set_rules('ppn', 'PPN', 'required|numeric|is_natural|greater_than_equal_to[0]|less_than_equal_to[100]');

		if (!$this->form_validation->run()) {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
		}
	}
}
