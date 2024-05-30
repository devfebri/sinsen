<?php

defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_po_bundling extends Honda_Controller
{

	protected $folder = "h3";
	protected $page   = "h3_md_po_bundling";
	protected $title  = "PO Bundling";

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
		$this->load->model('H3_md_po_logistik_model', 'po_logistik');
		$this->load->model('H3_md_po_logistik_parts_model', 'po_logistik_parts');
		$this->load->model('H3_md_po_logistik_parts_detail_model', 'po_logistik_parts_detail');
		$this->load->model('H3_md_stock_model', 'stock');
		$this->load->model('H3_md_target_salesman_model', 'target_salesman');
		$this->load->model('h3_md_diskon_part_tertentu_model', 'diskon_part_tertentu');
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
		$data['po_bundling'] = $this->db
			->select('po.no_po_aksesoris')
			->select('po.no_po_aksesoris as referensi_po_bundling')
			->select('po.tgl_po')
			->select('"Bundling H1" as kategori_po')
			->select('"REG" as po_type')
			->select('po.id_paket_bundling')
			->select('pb.nama_paket_bundling')
			->select('"Acc" as produk')
			->select('"Dealer" as tipe_source')
			->select('po.qty_paket')
			->select('po.created_at as tgl_terima')
			->select('po.keterangan')
			->select('d.id_dealer')
			->select('d.nama_dealer')
			->select('d.kode_dealer_md')
			->select('d.alamat')
			->select('1 as created_by_md')
			->select('"Credit" as jenis_pembayaran')
			->select('so.id_sales_order')
			->select('po.status_po')
			->from('tr_po_aksesoris as po')
			->join('ms_dealer as d', 'd.kode_dealer_md = "E20-H1"', 'left')
			->join('ms_paket_bundling as pb', 'pb.id_paket_bundling = po.id_paket_bundling')
			->join('tr_h3_md_sales_order as so', '(so.referensi_po_bundling = po.no_po_aksesoris AND so.status != "Canceled")', 'left')
			->where('po.no_po_aksesoris', $this->input->get('no_po_aksesoris'))
			->get()->row_array();

		$this->target_salesman->get_target_sales_query(date('Y-m-d'), $data['po_bundling']['id_dealer'], $data['po_bundling']['produk']);
		$this->db->select('ts.id_salesman');
		$this->db->select('k.nama_lengkap as nama_salesman');
		$this->db->join('ms_karyawan as k', 'k.id_karyawan = ts.id_salesman');
		$target_salesman = $this->db->get()->row_array();

		if ($target_salesman != null) {
			$data['po_bundling']['id_salesman'] = $target_salesman['id_salesman'];
			$data['po_bundling']['nama_salesman'] = $target_salesman['nama_salesman'];
		} else {
			$data['po_bundling']['id_salesman'] = null;
			$data['po_bundling']['nama_salesman'] = null;
		}

		$qty_supply = $this->db
			->select('SUM(dop.qty_supply) as qty_supply')
			->from('tr_h3_md_sales_order as so')
			->join('tr_h3_md_do_sales_order as do', 'do.id_sales_order = so.id_sales_order')
			->join('tr_h3_md_do_sales_order_parts as dop', 'dop.id_do_sales_order = do.id_do_sales_order')
			->where('pod.no_po_aksesoris = so.referensi_po_bundling', null, false)
			->where('dop.id_part = pod.id_part', null, false)
			->where('do.sudah_create_faktur', 1)
			->get_compiled_select();

		$this->db
			->select('pod.id_part')
			->select('p.nama_part')
			->select('pod.qty as qty_order')
			->select('pod.qty as qty_pemenuhan')
			->select("IFNULL(({$qty_supply}), 0) as qty_supply", false)
			->select('p.harga_dealer_user as harga')
			->select('pod.harga as harga_h1')
			->select('"" as tipe_diskon')
			->select('0 as diskon_value')
			->select('"" as tipe_diskon_campaign')
			->select('0 as diskon_value')
			->from('tr_po_aksesoris_detail as pod')
			->join('ms_part as p', 'p.id_part = pod.id_part')
			->where('pod.no_po_aksesoris', $this->input->get('no_po_aksesoris'));

		$data['parts'] = array_map(function ($row) use ($data) {
			$row['qty_on_hand'] = $this->stock->qty_on_hand($row['id_part']);
			$row['qty_avs'] = $this->stock->qty_avs($row['id_part']);
			$diskon = $this->diskon_part_tertentu->get_diskon($row['id_part'], $data['po_bundling']['id_dealer'], $data['po_bundling']['po_type']);
			if ($diskon != null) {
				$row['tipe_diskon'] = $diskon['tipe_diskon'];
				$row['diskon_value'] = $diskon['diskon_value'];
			}
			return $row;
		}, $this->db->get()->result_array());

		$this->template($data);
	}

	public function edit()
	{
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['po_logistik'] = $this->db
			->select('pol.id_po_logistik')
			->select('date_format(pol.tanggal, "%d/%m/%Y") as tanggal')
			->select('pol.sudah_create_po_urgent')
			->select('po.id_purchase_order')
			->from('tr_h3_md_po_logistik as pol')
			->join('tr_h3_md_purchase_order as po', 'po.id_po_logistik = pol.id_po_logistik', 'left')
			->where('pol.id_po_logistik', $this->input->get('id_po_logistik'))
			->get()->row_array();

		$qty_book = $this->db
			->select('SUM(sop.qty_order) as qty_order')
			->from('tr_h3_md_sales_order as so')
			->join('tr_h3_md_sales_order_parts as sop', 'so.id_sales_order = sop.id_sales_order')
			->where('so.id_po_logistik', $this->input->get('id_po_logistik'))
			->where('sop.id_part = polp.id_part')
			->get_compiled_select();

		$data['parts'] = $this->db
			->select('polp.id_part')
			->select('p.nama_part')
			->select('polp.qty_part')
			->select('polp.qty_supply')
			->select('polp.qty_po_ahm')
			->select("IFNULL(({$qty_book}), 0) as qty_book")
			->select('polp.harga')
			->from('tr_h3_md_po_logistik_parts as polp')
			->join('ms_part as p', 'p.id_part = polp.id_part')
			->where('polp.id_po_logistik', $this->input->get('id_po_logistik'))
			->get()->result_array();
		$data['parts'] = array_map(function ($row) {
			$row['qty_onhand'] = $this->stock->qty_on_hand($row['id_part']);
			$row['qty_avs'] = $this->stock->qty_avs($row['id_part']);
			return $row;
		}, $data['parts']);

		$this->template($data);
	}

	public function update()
	{
		$this->validate();
		$parts = $this->getOnly([
			'id_part', 'qty_part', 'harga', 'qty_supply', 'qty_po_ahm'
		], $this->input->post('parts'), $this->input->post(['id_po_logistik']));

		$this->db->trans_start();
		$this->po_logistik_parts->update_batch($parts, $this->input->post(['id_po_logistik']));
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'PO Logistik berhasil diupdate.');
			$this->session->set_flashdata('tipe', 'info');
			send_json(
				$this->po_logistik->find($this->input->post('id_po_logistik'), 'id_po_logistik')
			);
		} else {
			$this->session->set_flashdata('pesan', 'PO Logistik tidak berhasil diupdate.');
			$this->session->set_flashdata('tipe', 'danger');
			$this->output->set_status_header(500);
		}
	}

	public function validate()
	{
		return;

		$this->form_validation->set_error_delimiters('', '');

		if (!$this->form_validation->run()) {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
		}
	}

	public function reject()
	{
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('alasan_reject', 'Alasan reject', 'required');

		if (!$this->form_validation->run()) {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
		}

		$this->db
			->set('status_po', 'reject H3')
			->set('alasan_reject', $this->input->post('alasan_reject'))
			->set('rejected_by', $this->session->userdata('id_user'))
			->set('rejected_at', date('Y-m-d H:i:s'))
			->where($this->input->post(['no_po_aksesoris']))
			->update('tr_po_aksesoris');

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'PO aksesoris berhasil direject');
			$this->session->set_flashdata('tipe', 'info');
			send_json([
				'redirect_url' => base_url(sprintf('/h3/h3_md_po_bundling/detail?no_po_aksesoris=%s', $this->input->post('no_po_aksesoris')))
			]);
		} else {
			send_json([
				'message' => 'PO aksesoris tidak berhasil direject'
			], 422);
		}
	}

	public function rekap_po_bundling()
	{
		$data['mode'] = 'rekap_po_bundling';
		$data['set'] = 'rekap_po_bundling';
		$this->template($data);
	}
}
