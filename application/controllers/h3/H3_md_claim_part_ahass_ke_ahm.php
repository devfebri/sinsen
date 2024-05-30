<?php

defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_claim_part_ahass_ke_ahm extends Honda_Controller
{

	protected $folder = "h3";
	protected $page   = "h3_md_claim_part_ahass_ke_ahm";
	protected $title  = "Claim Part AHASS ke AHM";

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
		$this->load->model('h3_md_claim_part_ahass_model', 'claim_part_ahass');
		$this->load->model('h3_md_claim_part_ahass_parts_model', 'claim_part_ahass_parts');
	}

	public function index()
	{
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$this->template($data);
	}

	public function add()
	{
		$data['mode']    = 'insert';
		$data['set']     = "form";

		$data['claim_dealer_parts'] = $this->db
			->select('cd.id_claim_dealer')
			->select('date_format(cd.tanggal, "%d-%m-%Y") as tanggal')
			->select('cd.id_dealer')
			->select('d.nama_dealer')
			->select('cdp.id_part')
			->select('p.id_part_int')
			->select('cdp.qty_part_diclaim')
			->select('dop.qty_supply as qty_ps')
			->select('cdp.keterangan')
			->select('date_format(ps.tgl_packing_sheet, "%d-%m-%Y") as tgl_packing_sheet')
			->select('cdp.id_kategori_claim_c3')
			->select('kc.kode_claim')
			->select('kc.nama_claim')
			->select('cdp.keputusan')
			->select('ps.id_packing_sheet')
			->select('0 as checklist')
			->from('tr_h3_md_claim_dealer as cd')
			->join('tr_h3_md_claim_dealer_parts as cdp', 'cd.id_claim_dealer = cdp.id_claim_dealer')
			->join('tr_h3_md_packing_sheet as ps', 'ps.id_packing_sheet = cd.id_packing_sheet')
			->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
			->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
			->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_do_sales_order = do.id_do_sales_order and dop.id_part = cdp.id_part)')
			->join('ms_dealer as d', 'd.id_dealer = cd.id_dealer')
			->join('ms_part as p', 'p.id_part = cdp.id_part')
			->join('ms_kategori_claim_c3 as kc', 'kc.id = cdp.id_kategori_claim_c3')
			->join('tr_h3_md_claim_part_ahass_parts as cpap', '(cpap.id_claim_dealer = cd.id_claim_dealer and cpap.id_part = cdp.id_part and cpap.id_kategori_claim_c3 = cdp.id_kategori_claim_c3)', 'left')
			->join('tr_h3_md_claim_part_ahass as cpa', 'cpa.id_claim_part_ahass = cpap.id_claim_part_ahass', 'left')
			->where("
			case
				when cpap.id is not null then cpa.status = 'Canceled'
				else true
			end 
		", null, false)
			->where('cd.status', 'Approved')
			->where('cdp.keputusan', 'Terima')
			->order_by('cdp.id_part', 'asc')
			->get()->result_array();

		$this->template($data);
	}

	public function get_claim_dealer_parts()
	{
		$packing_sheet_number_int = $this->input->get('packing_sheet_number_int');
		$mode = $this->input->get('mode');

		$kuantitas_ps = $this->db
			->select('SUM(psp.packing_sheet_quantity) as kuantitas')
			->from('tr_h3_md_ps_parts as psp')
			->where('psp.packing_sheet_number_int', $packing_sheet_number_int)
			->where('cpa.nomor_karton_int = psp.no_doos_int', null, false)
			->where('cpap.id_part_int = psp.id_part_int', null, false)
			->get_compiled_select();

		$this->db
			->select('cd.id_claim_dealer')
			->select('date_format(cd.tanggal, "%d-%m-%Y") as tanggal')
			->select('cd.id_dealer')
			->select('d.nama_dealer')
			->select('cdp.id_part')
			->select('p.id_part_int')
			->select('cdp.qty_part_diclaim')
			->select(sprintf('IFNULL((%s), 0) as qty_ps', $kuantitas_ps), false)
			->select('cdp.keterangan')
			->select('date_format(ps.tgl_packing_sheet, "%d-%m-%Y") as tgl_packing_sheet')
			->select('cdp.id_kategori_claim_c3')
			->select('kc.kode_claim')
			->select('kc.nama_claim')
			->select('cdp.keputusan')
			->select('ps.id_packing_sheet')
			->select('1 as checklist')
			->select("
			case
				when '{$mode}' = 'edit' then cpa.id_claim_part_ahass = '{$this->input->get('id_claim_part_ahass')}'
				when '{$mode}' = 'detail' then 1
				else 0
			end as checklist 
		", false)
			// ->select('cdp.*')
			->from('tr_h3_md_claim_dealer as cd')
			->join('tr_h3_md_claim_dealer_parts as cdp', 'cd.id_claim_dealer = cdp.id_claim_dealer')
			->join('tr_h3_md_packing_sheet as ps', 'ps.id_packing_sheet = cd.id_packing_sheet')
			->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
			->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
			->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_do_sales_order = do.id_do_sales_order and dop.id_part = cdp.id_part)')
			->join('ms_dealer as d', 'd.id_dealer = cd.id_dealer')
			->join('ms_kategori_claim_c3 as kc', 'kc.id = cdp.id_kategori_claim_c3')
			->join('ms_part as p', 'p.id_part = cdp.id_part')
			->where('cd.status', 'Approved')
			->where('cdp.keputusan', 'Terima')
			->order_by('cd.created_at', 'asc')
			->order_by('cdp.id_part', 'asc');

		if ($mode == 'insert') {
			$this->db
				->join('tr_h3_md_claim_part_ahass_parts as cpap', '(cpap.id_claim_dealer = cd.id_claim_dealer and cpap.id_part = cdp.id_part and cpap.id_kategori_claim_c3 = cdp.id_kategori_claim_c3 and cpap.status != "Canceled")', 'left')
				->join('tr_h3_md_claim_part_ahass as cpa', '(cpa.id_claim_part_ahass = cpap.id_claim_part_ahass and cpa.status != "Canceled")', 'left');
			$this->db->where('cpa.id_claim_part_ahass', null);
		} elseif ($mode == 'detail') {
			$this->db
				->join('tr_h3_md_claim_part_ahass_parts as cpap', '(cpap.id_claim_dealer = cd.id_claim_dealer and cpap.id_part = cdp.id_part and cpap.id_kategori_claim_c3 = cdp.id_kategori_claim_c3)', 'left')
				->join('tr_h3_md_claim_part_ahass as cpa', '(cpa.id_claim_part_ahass = cpap.id_claim_part_ahass)', 'left');
			$this->db->where('cpa.id_claim_part_ahass', $this->input->get('id_claim_part_ahass'));
		} elseif ($mode == 'edit') {
			$this->db
				->join('tr_h3_md_claim_part_ahass_parts as cpap', '(cpap.id_claim_dealer = cd.id_claim_dealer and cpap.id_part = cdp.id_part and cpap.id_kategori_claim_c3 = cdp.id_kategori_claim_c3 and cpap.status != "Canceled")', 'left')
				->join('tr_h3_md_claim_part_ahass as cpa', '(cpa.id_claim_part_ahass = cpap.id_claim_part_ahass and cpa.status != "Canceled")', 'left');
			$this->db->group_start();
			$this->db->where('cpa.id_claim_part_ahass', $this->input->get('id_claim_part_ahass'));
			$this->db->or_where('cpa.id_claim_part_ahass', null);
			$this->db->group_end();
		}

		$parts = $this->db->get()->result_array();

		send_json($parts);
	}

	public function update_qty_ps()
	{
		$packing_sheet_number_int = $this->input->get('packing_sheet_number_int');
		$nomor_karton_int = $this->input->get('nomor_karton_int');
		$id_part_int = $this->input->get('id_part_int');

		$this->db
			->select('psp.id_part_int')
			->select('psp.id_part')
			->select('SUM(psp.packing_sheet_quantity) as kuantitas')
			->from('tr_h3_md_ps_parts as psp')
			->where('psp.packing_sheet_number_int', $packing_sheet_number_int)
			->where('psp.no_doos_int', $nomor_karton_int)
			->group_by('psp.id_part_int')
			;

		if (count($id_part_int) > 0) {
			$this->db->where_in('psp.id_part_int', $id_part_int);
		} else {
			send_json([]);
		}

		$data = $this->db->get()->result_array();

		send_json($data);
	}

	public function save()
	{
		$this->validate();
		$claim_part_ahass = $this->input->post([
			'packing_sheet_number', 'packing_sheet_number_int', 'nomor_karton', 'nomor_karton_int', 'jumlah_item_dalam_karton',
			'dokumen_packing_sheet', 'dokumen_packing_ticket', 'dokumen_foto_bukti', 'dokumen_shipping_list',
			'dokumen_nomor_karton', 'dokumen_tutup_botol', 'dokumen_label_timbangan', 'dokumen_label_karton',
			'dokumen_lain',
		]);
		$claim_part_ahass = array_merge($claim_part_ahass, [
			'id_claim_part_ahass' => $this->claim_part_ahass->generateID()
		]);
		$claim_dealer_parts = $this->getOnly(['id_part', 'id_claim_dealer', 'id_kategori_claim_c3'], $this->input->post('claim_dealer_parts'), [
			'id_claim_part_ahass' => $claim_part_ahass['id_claim_part_ahass'],
			'status' => 'Open'
		]);

		$this->db->trans_start();
		$this->claim_part_ahass->insert($claim_part_ahass);
		$this->claim_part_ahass_parts->insert_batch($claim_dealer_parts);
		$this->db->trans_complete();

		$claim_part_ahass = (array) $this->claim_part_ahass->find($claim_part_ahass['id_claim_part_ahass'], 'id_claim_part_ahass');
		if ($this->db->trans_status() and $claim_part_ahass != null) {
			$this->session->set_flashdata('pesan', 'Claim part ahass berhasil dibuat.');
			$this->session->set_flashdata('tipe', 'info');

			send_json([
				'message' => 'Claim part ahass berhasil dibuat',
				'payload' => $claim_part_ahass,
				'redirect_url' => base_url(sprintf('h3/h3_md_claim_part_ahass_ke_ahm/detail?id_claim_part_ahass=%s', $claim_part_ahass['id_claim_part_ahass']))
			]);
		} else {
			$this->session->set_flashdata('pesan', 'Claim part ahass tidak berhasil dibuat.');
			$this->session->set_flashdata('tipe', 'danger');

			send_json([
				'message' => 'Claim part ahass tidak berhasil dibuat'
			], 422);
		}
	}

	public function detail()
	{
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['claim_part_ahass'] = $this->db
			->select('cpa.id_claim_part_ahass')
			->select('date_format(cpa.created_at, "%d/%m/%Y") as created_at')
			->select('cpa.packing_sheet_number')
			->select('cpa.packing_sheet_number_int')
			->select('cpa.nomor_karton')
			->select('cpa.nomor_karton_int')
			->select('cpa.jumlah_item_dalam_karton')
			->select('cpa.dokumen_packing_sheet')
			->select('cpa.dokumen_packing_ticket')
			->select('cpa.dokumen_foto_bukti')
			->select('cpa.dokumen_shipping_list')
			->select('cpa.dokumen_nomor_karton')
			->select('cpa.dokumen_tutup_botol')
			->select('cpa.dokumen_label_timbangan')
			->select('cpa.dokumen_label_karton')
			->select('cpa.dokumen_lain')
			->select('cpa.status')
			->select('date_format(ps.packing_sheet_date, "%d/%m/%Y") as packing_sheet_date')
			->from('tr_h3_md_claim_part_ahass as cpa')
			->join('tr_h3_md_ps as ps', 'ps.packing_sheet_number = cpa.packing_sheet_number')
			->where('cpa.id_claim_part_ahass', $this->input->get('id_claim_part_ahass'))
			->get()->row();

		$kuantitas_ps = $this->db
			->select('SUM(psp.packing_sheet_quantity) as kuantitas')
			->from('tr_h3_md_ps_parts as psp')
			->where('cpa.packing_sheet_number_int = psp.packing_sheet_number_int', null, false)
			->where('cpa.nomor_karton_int = psp.no_doos_int', null, false)
			->where('cpap.id_part_int = psp.id_part_int', null, false)
			->get_compiled_select();

		$data['claim_dealer_parts'] = $this->db
			->select('cpap.id_claim_dealer')
			->select('date_format(cd.tanggal, "%d-%m-%Y") as tanggal')
			->select('cd.id_dealer')
			->select('d.nama_dealer')
			->select('cpap.id_part')
			->select('p.id_part_int')
			->select('cdp.qty_part_diclaim')
			->select(sprintf('IFNULL((%s), 0) as qty_ps', $kuantitas_ps), false)
			->select('cdp.keterangan')
			->select('date_format(ps.tgl_packing_sheet, "%d-%m-%Y") as tgl_packing_sheet')
			->select('cdp.id_kategori_claim_c3')
			->select('kc.kode_claim')
			->select('kc.nama_claim')
			->select('cdp.keputusan')
			->select('ps.id_packing_sheet')
			->select('1 as checklist')
			->from('tr_h3_md_claim_part_ahass_parts as cpap')
			->join('tr_h3_md_claim_part_ahass as cpa', 'cpa.id_claim_part_ahass = cpap.id_claim_part_ahass')
			->join('tr_h3_md_claim_dealer as cd', 'cd.id_claim_dealer = cpap.id_claim_dealer')
			->join('tr_h3_md_claim_dealer_parts as cdp', '(cdp.id_part = cpap.id_part and cdp.id_claim_dealer = cpap.id_claim_dealer and cdp.id_kategori_claim_c3 = cpap.id_kategori_claim_c3)')
			->join('tr_h3_md_packing_sheet as ps', 'ps.id_packing_sheet = cd.id_packing_sheet')
			->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
			->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
			->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_do_sales_order = do.id_do_sales_order and dop.id_part = cdp.id_part)')
			->join('ms_dealer as d', 'd.id_dealer = cd.id_dealer')
			->join('ms_part as p', 'p.id_part = cdp.id_part')
			->join('ms_kategori_claim_c3 as kc', 'kc.id = cdp.id_kategori_claim_c3')
			->where('cpap.id_claim_part_ahass', $this->input->get('id_claim_part_ahass'))
			->get()->result();

		if ($data['claim_part_ahass'] == null) {
			throw new Exception('Data claim part ahass tidak ditemukan');
		}

		$this->template($data);
	}

	public function edit()
	{
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['claim_part_ahass'] = $this->db
			->select('cpa.id_claim_part_ahass')
			->select('date_format(cpa.created_at, "%d/%m/%Y") as created_at')
			->select('cpa.packing_sheet_number')
			->select('cpa.packing_sheet_number_int')
			->select('cpa.nomor_karton')
			->select('cpa.nomor_karton_int')
			->select('cpa.jumlah_item_dalam_karton')
			->select('cpa.dokumen_packing_sheet')
			->select('cpa.dokumen_packing_ticket')
			->select('cpa.dokumen_foto_bukti')
			->select('cpa.dokumen_shipping_list')
			->select('cpa.dokumen_nomor_karton')
			->select('cpa.dokumen_tutup_botol')
			->select('cpa.dokumen_label_timbangan')
			->select('cpa.dokumen_label_karton')
			->select('cpa.dokumen_lain')
			->select('cpa.status')
			->select('date_format(ps.packing_sheet_date, "%d/%m/%Y") as packing_sheet_date')
			->from('tr_h3_md_claim_part_ahass as cpa')
			->join('tr_h3_md_ps as ps', 'ps.packing_sheet_number = cpa.packing_sheet_number')
			->where('cpa.id_claim_part_ahass', $this->input->get('id_claim_part_ahass'))
			->get()->row_array();

		$kuantitas_ps = $this->db
			->select('SUM(psp.packing_sheet_quantity) as kuantitas')
			->from('tr_h3_md_ps_parts as psp')
			->where('cpa.packing_sheet_number_int = psp.packing_sheet_number_int', null, false)
			->where('cpa.nomor_karton_int = psp.no_doos_int', null, false)
			->where('cpap.id_part_int = psp.id_part_int', null, false)
			->get_compiled_select();

		$data['claim_dealer_parts'] = $this->db
			->select('cpap.id_claim_dealer')
			->select('date_format(cd.tanggal, "%d-%m-%Y") as tanggal')
			->select('cd.id_dealer')
			->select('d.nama_dealer')
			->select('cpap.id_part')
			->select('p.id_part_int')
			->select('cdp.qty_part_diclaim')
			->select(sprintf('IFNULL((%s), 0) as qty_ps', $kuantitas_ps), false)
			->select('cdp.keterangan')
			->select('date_format(ps.tgl_packing_sheet, "%d-%m-%Y") as tgl_packing_sheet')
			->select('cdp.id_kategori_claim_c3')
			->select('kc.kode_claim')
			->select('kc.nama_claim')
			->select('cdp.keputusan')
			->select('ps.id_packing_sheet')
			->select('1 as checklist')
			->from('tr_h3_md_claim_part_ahass_parts as cpap')
			->join('tr_h3_md_claim_part_ahass as cpa', 'cpa.id_claim_part_ahass = cpap.id_claim_part_ahass')
			->join('tr_h3_md_claim_dealer as cd', 'cd.id_claim_dealer = cpap.id_claim_dealer')
			->join('tr_h3_md_claim_dealer_parts as cdp', '(cdp.id_part = cpap.id_part and cdp.id_claim_dealer = cpap.id_claim_dealer and cdp.id_kategori_claim_c3 = cpap.id_kategori_claim_c3)')
			->join('tr_h3_md_packing_sheet as ps', 'ps.id_packing_sheet = cd.id_packing_sheet')
			->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
			->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
			->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_do_sales_order = do.id_do_sales_order and dop.id_part = cdp.id_part)')
			->join('ms_dealer as d', 'd.id_dealer = cd.id_dealer')
			->join('ms_part as p', 'p.id_part = cdp.id_part')
			->join('ms_kategori_claim_c3 as kc', 'kc.id = cdp.id_kategori_claim_c3')
			->where('cpap.id_claim_part_ahass', $this->input->get('id_claim_part_ahass'))
			->get()->result();

		if ($data['claim_part_ahass'] == null) {
			throw new Exception('Data claim part ahass tidak ditemukan');
		}

		$this->template($data);
	}

	public function update()
	{
		$this->validate();
		$claim_part_ahass = $this->input->post([
			'packing_sheet_number', 'packing_sheet_number_int', 'nomor_karton', 'nomor_karton_int', 'jumlah_item_dalam_karton',
			'dokumen_packing_sheet', 'dokumen_packing_ticket', 'dokumen_foto_bukti', 'dokumen_shipping_list',
			'dokumen_nomor_karton', 'dokumen_tutup_botol', 'dokumen_label_timbangan', 'dokumen_label_karton',
			'dokumen_lain',
		]);
		$claim_part_ahass['status'] = 'Open';
		$claim_dealer_parts = $this->getOnly(['id_part', 'id_claim_dealer', 'id_kategori_claim_c3'], $this->input->post('claim_dealer_parts'), [
			'id_claim_part_ahass' => $this->input->post('id_claim_part_ahass'),
			'status' => 'Open'
		]);
		$condition = $this->input->post(['id_claim_part_ahass']);

		$this->db->trans_start();
		$this->claim_part_ahass->update($claim_part_ahass, $condition);
		$this->claim_part_ahass_parts->update_batch($claim_dealer_parts, $condition);
		$this->db->trans_complete();

		$claim_part_ahass = (array) $this->claim_part_ahass->find($this->input->post('id_claim_part_ahass'), 'id_claim_part_ahass');
		if ($this->db->trans_status() and $claim_part_ahass != null) {
			$this->session->set_flashdata('pesan', 'Claim part ahass berhasil diperbarui.');
			$this->session->set_flashdata('tipe', 'info');

			send_json([
				'message' => 'Claim part ahass berhasil dibuat',
				'payload' => $claim_part_ahass,
				'redirect_url' => base_url(sprintf('h3/h3_md_claim_part_ahass_ke_ahm/detail?id_claim_part_ahass=%s', $claim_part_ahass['id_claim_part_ahass']))
			]);
		} else {
			$this->session->set_flashdata('pesan', 'Claim part ahass tidak berhasil diperbarui.');
			$this->session->set_flashdata('tipe', 'danger');

			send_json([
				'message' => 'Claim part ahass tidak berhasil diperbarui.'
			], 422);
		}
	}

	public function cancel()
	{
		$this->db->trans_start();
		$this->claim_part_ahass->update([
			'status' => 'Canceled',
			'canceled_at' => date('Y-m-d H:i:s', time()),
			'canceled_by' => $this->session->userdata('id_user'),
		], $this->input->get(['id_claim_part_ahass']));
		$this->claim_part_ahass_parts->update([
			'status' => 'Canceled'
		], $this->input->get(['id_claim_part_ahass']));
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Claim part ahass berhasil dibatalkan.');
			$this->session->set_flashdata('tipe', 'info');
			send_json([
				'redirect_url' => sprintf('h3/h3_md_claim_part_ahass_ke_ahm/detail?id_claim_part_ahass=%s', $this->input->get('id_claim_part_ahass'))
			]);
		} else {
			send_json([
				'message' => 'Claim part ahass tidak berhasil dibatalkan'
			], 422);
		}
	}

	public function proses()
	{
		$this->db->trans_start();
		$this->claim_part_ahass->update([
			'status' => 'Processed',
			'processed_at' => date('Y-m-d H:i:s', time()),
			'processed_by' => $this->session->userdata('id_user'),
		], $this->input->get(['id_claim_part_ahass']));
		$this->claim_part_ahass_parts->update([
			'status' => 'Processed'
		], $this->input->get(['id_claim_part_ahass']));
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Claim part ahass berhasil diproses.');
			$this->session->set_flashdata('tipe', 'info');

			send_json([
				'redirect_url' => sprintf('h3/h3_md_claim_part_ahass_ke_ahm/detail?id_claim_part_ahass=%s', $this->input->get('id_claim_part_ahass'))
			]);
		} else {
			send_json([
				'message' => 'Claim part ahass tidak berhasil diproses'
			], 422);
		}
	}

	public function validate()
	{
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('packing_sheet_number', 'Packing Sheet Number', 'required');
		$this->form_validation->set_rules('packing_sheet_number_int', 'Packing Sheet Number Int', 'required');

		if (!$this->form_validation->run()) {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
		}
	}

	public function cetak()
	{
		require_once APPPATH . 'third_party/mpdf/mpdf.php';
		$mpdf = new Mpdf('c');

		$data = [];
		$data['header'] = $this->db
			->select('cpa.id_claim_part_ahass')
			->select('date_format(cpa.created_at, "%d/%m/%Y") as created_at')
			->select('fdo.invoice_number')
			->select('cpa.packing_sheet_number_int')
			->select('cpa.packing_sheet_number')
			->select('cpa.nomor_karton')
			->select('cpa.dokumen_packing_sheet')
			->select('cpa.dokumen_packing_ticket')
			->select('cpa.dokumen_foto_bukti')
			->select('cpa.dokumen_shipping_list')
			->select('cpa.dokumen_nomor_karton')
			->select('cpa.dokumen_tutup_botol')
			->select('cpa.dokumen_label_timbangan')
			->select('cpa.dokumen_label_karton')
			->select('cpa.dokumen_lain')
			->select('date_format(ps.packing_sheet_date, "%d/%m/%Y") as packing_sheet_date')
			->from('tr_h3_md_claim_part_ahass as cpa')
			->join('tr_h3_md_ps as ps', 'ps.id = cpa.packing_sheet_number_int')
			->join('tr_h3_md_fdo as fdo', 'fdo.id = ps.invoice_number_int', 'left')
			->where('cpa.id_claim_part_ahass', $this->input->get('id_claim_part_ahass'))
			->get()->row_array();

		$kuantitas_ps = $this->db
			->select('SUM(psp.packing_sheet_quantity) as kuantitas')
			->from('tr_h3_md_ps_parts as psp')
			->where('cpa.packing_sheet_number_int = psp.packing_sheet_number_int', null, false)
			->where('cpa.nomor_karton_int = psp.no_doos_int', null, false)
			->where('cpap.id_part_int = psp.id_part_int', null, false)
			->get_compiled_select();

		$data['parts'] = $this->db
			->select('cpap.id_part')
			->select('p.nama_part')
			->select(sprintf('IFNULL((%s), 0) as qty_ps', $kuantitas_ps), false)
			->select('cdp.qty_part_diclaim')
			->select('cdp.qty_part_dikirim_ke_md')
			->select('cdp.keterangan')
			->select('kc.kode_claim')
			->select('kc.nama_claim')
			->select('cdp.keputusan')
			->from('tr_h3_md_claim_part_ahass_parts as cpap')
			->join('tr_h3_md_claim_part_ahass as cpa', 'cpa.id_claim_part_ahass = cpap.id_claim_part_ahass')
			->join('tr_h3_md_claim_dealer as cd', 'cd.id_claim_dealer = cpap.id_claim_dealer')
			->join('tr_h3_md_claim_dealer_parts as cdp', '(cdp.id_part = cpap.id_part and cdp.id_claim_dealer = cpap.id_claim_dealer and cdp.id_kategori_claim_c3 = cpap.id_kategori_claim_c3)')
			->join('tr_h3_md_packing_sheet as ps', 'ps.id_packing_sheet = cd.id_packing_sheet')
			->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
			->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
			->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_do_sales_order = do.id_do_sales_order and dop.id_part = cdp.id_part)')
			->join('ms_dealer as d', 'd.id_dealer = cd.id_dealer')
			->join('ms_kategori_claim_c3 as kc', 'kc.id = cdp.id_kategori_claim_c3')
			->join('ms_part as p', 'p.id_part = cpap.id_part')
			->where('cpap.id_claim_part_ahass', $this->input->get('id_claim_part_ahass'))
			->get()->result_array();

		$html = $this->load->view('h3/h3_md_cetakan_claim_part_ahass_ke_ahm', $data, true);
		$mpdf->WriteHTML($html);
		$mpdf->Output("Form CLaim C3 Parts.pdf", "I");
	}
}
