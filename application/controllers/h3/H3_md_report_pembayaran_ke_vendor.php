<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_report_pembayaran_ke_vendor extends Honda_Controller
{
	protected $folder = "h3";
	protected $page   = "h3_md_report_pembayaran_ke_vendor";
	protected $title  = "Report Pembayaran ke Vendor";

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

		$this->load->model('h3_md_penagihan_pihak_kedua_model', 'penagihan_pihak_kedua');
		$this->load->model('h3_md_penagihan_pihak_kedua_tujuan_model', 'penagihan_pihak_kedua_tujuan');
	}

	public function index()
	{
		$data['mode'] = 'index';
		$data['set'] = 'index';

		$this->template($data);
	}

	public function download_excel(){
		$this->load->model('H3_md_laporan_pembayaran_ke_vendor_model', 'laporan');
		$tanggal_entry_start = $this->input->get('tanggal_entry_start');
		$tanggal_entry_end = $this->input->get('tanggal_entry_end');
		$tanggal_transaksi_start = $this->input->get('tanggal_transaksi_start');
		$tanggal_transaksi_end = $this->input->get('tanggal_transaksi_end');
		$tanggal_pembayaran_start = $this->input->get('tanggal_pembayaran_start');
		$tanggal_pembayaran_end = $this->input->get('tanggal_pembayaran_end');

		$this->laporan->download($tanggal_entry_start, $tanggal_entry_end, $tanggal_transaksi_start, $tanggal_transaksi_end, $tanggal_pembayaran_start, $tanggal_pembayaran_end);
	}
}
