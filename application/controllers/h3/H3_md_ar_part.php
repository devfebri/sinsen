<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_ar_part extends Honda_Controller
{
	protected $folder = "h3";
	protected $page   = "h3_md_ar_part";
	protected $title  = "AR Part";

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
	}

	public function generate_nama_customer()
	{
		$this->db
			->select('ar.id')
			->select('d.nama_dealer as nama_customer')
			->from('tr_h3_md_ar_part as ar')
			->join('ms_dealer as d', 'd.id_dealer = ar.id_dealer')
			->where('ar.id_dealer !=', null)
			->where('ar.nama_customer', null);

		foreach ($this->db->get()->result_array() as $row) {
			$this->db
				->set('nama_customer', $row['nama_customer'])
				->where('id', $row['id'])
				->update('tr_h3_md_ar_part');
		}
	}

	public function generate_nama_customer_ahm()
	{
		$this->db
			->select('ar.id')
			->from('tr_h3_md_ar_part as ar')
			->group_start()
			->where('ar.tipe_referensi', 'retur_pembelian_claim')
			->or_where('ar.tipe_referensi', 'jawaban_claim_dealer')
			->group_end();

		foreach ($this->db->get()->result_array() as $row) {
			$this->db
				->set('nama_customer', 'ASTRA HONDA MOTOR (AHM)')
				->where('id', $row['id'])
				->update('tr_h3_md_ar_part');
		}
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
		$data['rekap_invoice'] = $this->db
			->select('ria.id_rekap_invoice')
			->from('tr_h3_rekap_invoice_ahm as ria')
			->where('ria.id_rekap_invoice', $this->input->get('id_rekap_invoice'))
			->get()->row();

		$data['items'] = $this->db
			->select('date_format(fdo.invoice_date, "%d-%m-%Y") as invoice_date')
			->select('riai.invoice_number')
			->select('fdo.total_dpp')
			->select('date_format(fdo.dpp_due_date, "%d-%m-%Y") as dpp_due_date')
			->select('fdo.total_ppn')
			->select('date_format(fdo.ppn_due_date, "%d-%m-%Y") as ppn_due_date')
			->select('0 as no_giro')
			->select('0 as amount_giro')
			->from('tr_h3_rekap_invoice_ahm_items as riai')
			->join('tr_h3_md_fdo as fdo', 'fdo.invoice_number = riai.invoice_number')
			->where('riai.id_rekap_invoice', $this->input->get('id_rekap_invoice'))
			->get()->result();

		$this->template($data);
	}

	public function set_ps_to_lunas()
	{
		$this->db->trans_start(true);

		$this->db
			->select('ar.referensi')
			->from('tr_h3_md_ar_part as ar')
			->where('ar.tipe_referensi', 'faktur_penjualan')
			->where('ar.lunas', 1);

		foreach ($this->db->get()->result_array() as $row) {
			$this->db
				->set('ps.faktur_lunas', 1)
				->where('ps.no_faktur', $row['referensi'])
				->where('ps.faktur_lunas', 0)
				->update('tr_h3_md_packing_sheet as ps');
		}

		$this->db->trans_complete();
	}

	public function download()
	{
		$filters = $this->input->get([
			'no_referensi_filter',
			'jenis_transaksi_filter',
			'tanggal_jatuh_tempo_filter_start',
			'tanggal_jatuh_tempo_filter_end',
			'id_customer_filter',
			'tanggal_batas_akhir_referensi',
			'history',
			'filetype',
		]);
		$this->load->helper('clean_data');
		$filters = clean_data($filters);

		$this->load->model('H3_md_laporan_list_ar_model', 'laporan');
		$this->laporan->download($filters);
	}

	public function set_jenis_transaksi()
	{
		$retur_pembelians = $this->db
			->select('ar.id')
			->select('ar.referensi')
			->select('rpc.no_retur')
			->select('cmd.id_claim')
			->select('psp.packing_sheet_number')
			->select('ifnull(po.produk, "tidak_diketahui") as produk')
			->from('tr_h3_md_ar_part as ar')
			->join('tr_h3_md_retur_pembelian_claim as rpc', 'rpc.no_retur = ar.referensi')
			->join('tr_h3_md_claim_main_dealer_ke_ahm as cmd', 'cmd.id_claim = rpc.id_claim')
			->join('tr_h3_md_ps_parts as psp', 'psp.packing_sheet_number_int = cmd.packing_sheet_number_int')
			->join('tr_h3_md_purchase_order as po', 'po.id_purchase_order = psp.no_po', 'left')
			->where('ar.tipe_referensi', 'retur_pembelian_claim')
			->group_by('psp.packing_sheet_number_int')
			->where('ar.jenis_transaksi', null)
			->get()->result_array();

		foreach ($retur_pembelians as $retur_pembelian) {
			$this->db
				->set('jenis_transaksi', strtolower($retur_pembelian['produk']))
				->where('id', $retur_pembelian['id'])
				->update('tr_h3_md_ar_part');
		}

		$jawaban_claim_dealers = $this->db
			->select('ar.id')
			->select('ar.referensi')
			->select('jcd.id as id_jawaban_claim_dealer_int')
			->select('jcd.id_jawaban_claim_dealer')
			->select('cpa.id as id_claim_part_ahass_int')
			->select('cpa.id_claim_part_ahass')
			->select('cpap.id_claim_part_ahass')
			->select('cd.id as id_claim_dealer_int')
			->select('cd.id_claim_dealer')
			->select('ps.id as id_packing_sheet_int')
			->select('ps.id_packing_sheet')
			->select('pl.id as id_picking_list_int')
			->select('pl.id_picking_list')
			->select('do.id as id_do_sales_order_int')
			->select('do.id_do_sales_order')
			->select('so.id as id_sales_order_int')
			->select('so.id_sales_order')
			->select('so.produk')
			->from('tr_h3_md_ar_part as ar')
			->join('tr_h3_md_jawaban_claim_dealer as jcd', '(jcd.id_jawaban_claim_dealer = ar.referensi and jcd.status = "Processed")')
			->join('tr_h3_md_claim_part_ahass as cpa', '(cpa.id_claim_part_ahass = jcd.id_claim_part_ahass and cpa.status = "Processed")')
			->join('tr_h3_md_claim_part_ahass_parts as cpap', '(cpap.id_claim_part_ahass_int = cpa.id)')
			->join('tr_h3_md_claim_dealer as cd', 'cd.id_claim_dealer and cpap.id_claim_dealer')
			->join('tr_h3_md_packing_sheet as ps', 'ps.id_packing_sheet = cd.id_packing_sheet')
			->join('tr_h3_md_picking_list as pl', 'pl.id = ps.id_picking_list_int')
			->join('tr_h3_md_do_sales_order as do', 'do.id = pl.id_ref_int')
			->join('tr_h3_md_sales_order as so', 'so.id = do.id_sales_order_int')
			->where('ar.tipe_referensi', 'jawaban_claim_dealer')
			->where('ar.jenis_transaksi', null)
			->order_by('jcd.id_jawaban_claim_dealer')
			->order_by('cpap.id_claim_part_ahass')
			->get()->result_array();

		foreach ($jawaban_claim_dealers as $jawaban_claim_dealer) {
			$this->db
				->set('jenis_transaksi', strtolower($jawaban_claim_dealer['produk']))
				->where('id', $jawaban_claim_dealer['id'])
				->update('tr_h3_md_ar_part');
		}

		$faktur_penjualans = $this->db
			->select('ar.id')
			->select('so.id_sales_order')
			->select('so.produk')
			->from('tr_h3_md_ar_part as ar')
			->join('tr_h3_md_packing_sheet as ps', 'ps.no_faktur = ar.referensi')
			->join('tr_h3_md_picking_list as pl', 'pl.id = ps.id_picking_list_int')
			->join('tr_h3_md_do_sales_order as do', 'do.id = pl.id_ref_int')
			->join('tr_h3_md_sales_order as so', 'so.id = do.id_sales_order_int')
			->where('ar.tipe_referensi', 'faktur_penjualan')
			->where('ar.jenis_transaksi', null)
			->get()->result_array();

		foreach ($faktur_penjualans as $faktur_penjualan) {
			$this->db
				->set('jenis_transaksi', strtolower($faktur_penjualan['produk']))
				->where('id', $faktur_penjualan['id'])
				->update('tr_h3_md_ar_part');
		}

		echo 'SELESAI!';
	}
}
