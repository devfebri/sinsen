<?php

defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_po_checker extends Honda_Controller
{

	protected $folder = "h3";
	protected $page   = "h3_md_po_checker";
	protected $title  = "PO Checker";

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

		$this->load->model('h3_md_claim_dealer_model', 'claim_dealer');
		$this->load->model('h3_md_claim_dealer_parts_model', 'claim_dealer_parts');
		$this->load->model('H3_md_stock_model', 'stock');
		$this->load->model('H3_md_stock_int_model', 'stock_int');
	}

	public function index()
	{
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$this->template($data);
	}

	public function detail()
	{
		$data['mode']    = 'detail';
		$data['set']     = "form";

		$data['po_checker'] = $this->db
			->select('c.id_checker')
			->select('scan_barcode.no_shipping_list')
			->select('c.tgl_checker')
			->select('c.no_mesin')
			->select('scan_barcode.no_rangka')
			->select('scan_barcode.tipe_motor')
			->select('ptm.deskripsi as deskripsi_unit')
			->select('scan_barcode.warna')
			->select('warna.warna as deskripsi_warna')
			->select('c.status_checker')
			->select('c.po_id_dealer_h3')
			->from('tr_checker as c')
			->join('tr_scan_barcode as scan_barcode', 'scan_barcode.no_mesin = c.no_mesin')
			->join('ms_ptm as ptm', 'ptm.tipe_marketing = scan_barcode.tipe_motor', 'left')
			->join('ms_warna as warna', 'warna.id_warna = scan_barcode.warna', 'left')
			->group_start()
			->where('c.sumber_kerusakan', 'Warehouse')
			->or_where('c.sumber_kerusakan', 'Ekspedisi')
			->group_end()
			->where('c.id_checker', $this->input->get('id_checker'))
			->get()->row_array();

		$parts = $this->db
			->select('cd.id_part')
			->select('p.id_part_int')
			->select('p.nama_part')
			->select('cd.qty_order')
			->from('tr_checker_detail as cd')
			->join('ms_part as p', 'p.id_part = cd.id_part')
			->where('cd.id_checker', $this->input->get('id_checker'))
			->get()->result_array();

		$data['parts'] = array_map(function ($row) {
			$row['qty_avs'] = $this->stock_int->qty_avs($row['id_part_int']);
			return $row;
		}, $parts);

		$this->template($data);
	}

	public function approve()
	{
		$this->db->trans_start();
		$this->db
			->set('c.status_checker', 'H3-Approved')
			->where('c.id_checker', $this->input->post('id_checker'))
			->update('tr_checker as c');
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Checker berhasil diapprove.');
			$this->session->set_flashdata('tipe', 'info');

			$checker = $this->db
				->from('tr_checker as c')
				->where('c.id_checker', $this->input->post('id_checker'))
				->get()->row_array();
			send_json($checker);
		} else {
			$this->session->set_flashdata('pesan', 'Checker tidak berhasil diapprove.');
			$this->session->set_flashdata('tipe', 'danger');
			$this->output->set_status_header(500);
		}
	}

	public function reject()
	{
		$this->db->trans_start();
		$this->db
			->set('c.status_checker', 'H3-Rejected')
			// ->set('prn.rejected_message', $this->input->post('message'))
			->where('c.id_checker', $this->input->post('id_checker'))
			->update('tr_checker as c');
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Checker berhasil direject.');
			$this->session->set_flashdata('tipe', 'info');

			$dokumen_nrfs = $this->db
				->from('tr_checker as c')
				->where('c.id_checker', $this->input->post('id_checker'))
				->get()->row_array();
			send_json($dokumen_nrfs);
		} else {
			$this->session->set_flashdata('pesan', 'Checker tidak berhasil direject.');
			$this->session->set_flashdata('tipe', 'danger');
			$this->output->set_status_header(500);
		}
	}

	public function validate()
	{
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id_dealer', 'Customer', 'required');
		$this->form_validation->set_rules('id_packing_sheet', 'Packing Sheet', 'required');

		if (!$this->form_validation->run()) {
			$data = $this->form_validation->error_array();
			send_json($data, 422);
		}
	}
}
