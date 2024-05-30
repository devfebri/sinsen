<?php

use Cake\Log\Engine\BaseLog;

defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_retur_pembelian_claim extends Honda_Controller
{

	protected $folder = "h3";
	protected $page   = "h3_md_retur_pembelian_claim";
	protected $title  = "Return Pembelian Claim";

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
		$this->load->model('H3_md_retur_pembelian_claim_model', 'retur_pembelian_claim');
		$this->load->model('H3_md_retur_pembelian_claim_items_model', 'retur_pembelian_claim_items');
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
		$data['retur_pembelian_claim'] = $this->db
			->select('rpc.no_retur')
			->select('rpc.tanggal')
			->select('cmda.id_claim')
			->select('cmda.created_at as tanggal_claim')
			->select('ps.packing_sheet_number')
			->select('ps.packing_sheet_date')
			->select('fdo.invoice_number')
			->select('fdo.invoice_date')
			->select('rpc.status')
			->from('tr_h3_md_retur_pembelian_claim as rpc')
			->join('tr_h3_md_claim_main_dealer_ke_ahm as cmda', 'cmda.id_claim = rpc.id_claim')
			->join('tr_h3_md_ps as ps', 'ps.packing_sheet_number = cmda.packing_sheet_number')
			->join('tr_h3_md_fdo_ps as fdo_ps', 'fdo_ps.packing_sheet_number = ps.packing_sheet_number', 'left')
			->join('tr_h3_md_fdo as fdo', 'fdo.invoice_number = fdo_ps.invoice_number', 'left')
			->where('rpc.no_retur', $this->input->get('no_retur'))
			->get()->row_array();

		$data['parts'] = $this->db
			->select('rpcai.id_part')
			->select('p.nama_part')
			->select('rpcai.qty')
			->select('(rpcai.nominal / rpcai.qty) as price', false)
			->select('rpcai.nominal')
			->select('cmdai.keterangan')
			->from('tr_h3_md_retur_pembelian_claim_items as rpcai')
			->join('tr_h3_md_retur_pembelian_claim as rpc', 'rpc.no_retur = rpcai.no_retur')
			->join('tr_h3_md_claim_main_dealer_ke_ahm as cmda', 'cmda.id_claim = rpc.id_claim')
			->join('tr_h3_md_claim_main_dealer_ke_ahm_item as cmdai', '(cmdai.id_claim = cmda.id_claim and cmdai.id_part = rpcai.id_part and cmdai.no_doos = rpcai.no_doos and cmdai.no_po = rpcai.no_po and cmdai.id_kode_claim = rpcai.id_kode_claim)')
			->join('tr_h3_md_ps as ps', 'ps.id = cmda.packing_sheet_number_int')
			->join('tr_h3_md_fdo as fdo', 'fdo.id = ps.invoice_number_int', 'left')
			->join('tr_h3_md_fdo_parts as fdo_parts', '(fdo_parts.id_part = rpcai.id_part and fdo_parts.nomor_packing_sheet = ps.packing_sheet_number and fdo_parts.invoice_number = fdo.invoice_number)', 'left')
			->join('ms_part as p', 'p.id_part = rpcai.id_part')
			->where('rpcai.no_retur', $this->input->get('no_retur'))
			->get()->result_array();

		$this->template($data);
	}

	public function proses()
	{
		$no_retur = $this->input->post('no_retur');

		$retur_pembelian_claim = (array) $this->retur_pembelian_claim->find($no_retur, 'no_retur');
		if ($retur_pembelian_claim == null) {
			send_json([
				'message' => 'Return pembelian claim tidak ditemukan'
			], 404);
		}

		$this->db->trans_start();
		$this->retur_pembelian_claim->proses($retur_pembelian_claim['no_retur']);
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$message = 'Retur Pembelian Claim berhasil diproses.';
			$this->session->set_flashdata('pesan', $message);
			$this->session->set_flashdata('tipe', 'info');

			send_json([
				'message' => $message,
				'redirect_url' => base_url(sprintf('h3/h3_md_retur_pembelian_claim/detail?no_retur=%s', $retur_pembelian_claim['no_retur']))
			]);
		} else {
			send_json([
				'message' => 'Retur Pembelian Claim tidak berhasil diproses.'
			], 422);
		}
	}

	public function cetak()
	{
		$data = [];
		$data['retur_pembelian_claim'] = $this->db
			->select('rpc.no_retur')
			->select('date_format(rpc.tanggal, "%d/%m/%Y") as tanggal')
			->select('cmda.id_claim')
			->select('date_format(cmda.created_at, "%d/%m/%Y") as tanggal_claim')
			->select('ps.packing_sheet_number')
			->select('date_format(ps.packing_sheet_date, "%d/%m/%Y") as packing_sheet_date')
			->select('fdo.invoice_number')
			->select('date_format(fdo.invoice_date, "%d/%m/%Y") as invoice_date')
			->select('rpc.status')
			->from('tr_h3_md_retur_pembelian_claim as rpc')
			->join('tr_h3_md_claim_main_dealer_ke_ahm as cmda', 'cmda.id_claim = rpc.id_claim')
			->join('tr_h3_md_ps as ps', 'ps.packing_sheet_number = cmda.packing_sheet_number')
			->join('tr_h3_md_fdo_ps as fdo_ps', 'fdo_ps.packing_sheet_number = ps.packing_sheet_number', 'left')
			->join('tr_h3_md_fdo as fdo', 'fdo.invoice_number = fdo_ps.invoice_number', 'left')
			->where('rpc.no_retur', $this->input->get('no_retur'))
			->get()->row_array();

		$data['parts'] = $this->db
			->select('rpcai.id_part')
			->select('p.nama_part')
			->select('rpcai.qty')
			->select('(rpcai.nominal / rpcai.qty) as price', false)
			->select('rpcai.nominal')
			->select('cmdai.keterangan')
			->from('tr_h3_md_retur_pembelian_claim_items as rpcai')
			->join('tr_h3_md_retur_pembelian_claim as rpc', 'rpc.no_retur = rpcai.no_retur')
			->join('tr_h3_md_claim_main_dealer_ke_ahm as cmda', 'cmda.id_claim = rpc.id_claim')
			->join('tr_h3_md_claim_main_dealer_ke_ahm_item as cmdai', '(cmdai.id_claim = cmda.id_claim and cmdai.id_part = rpcai.id_part and cmdai.no_doos = rpcai.no_doos and cmdai.no_po = rpcai.no_po and cmdai.id_kode_claim = rpcai.id_kode_claim)')
			->join('tr_h3_md_ps as ps', 'ps.id = cmda.packing_sheet_number_int')
			->join('tr_h3_md_fdo as fdo', 'fdo.id = ps.invoice_number_int', 'left')
			->join('tr_h3_md_fdo_parts as fdo_parts', '(fdo_parts.id_part = rpcai.id_part and fdo_parts.nomor_packing_sheet = ps.packing_sheet_number and fdo_parts.invoice_number = fdo.invoice_number)', 'left')
			->join('ms_part as p', 'p.id_part = rpcai.id_part')
			->where('rpcai.no_retur', $this->input->get('no_retur'))
			->get()->result_array();

		// $this->load->library('mpdf_l');
		require_once APPPATH . 'third_party/mpdf/mpdf.php';
		// Require composer autoload
		$mpdf = new Mpdf();
		// Write some HTML code:
		$html = $this->load->view('h3/h3_md_cetak_retur_pembelian_claim', $data, true);
		$mpdf->WriteHTML($html);

		// Output a PDF file directly to the browser
		$mpdf->Output("Retur Pembelian.pdf", "I");
	}
}
