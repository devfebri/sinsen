<?php

defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_laporan_retur_pembelian_claim extends Honda_Controller
{

	protected $folder = "h3";
	protected $page   = "h3_md_laporan_retur_pembelian_claim";
	protected $title  = "Laporan Return Pembelian Claim";

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

	public function cetak()
	{
		$this->load->model('H3_md_laporan_retur_pembelian_claim_model', 'laporan');
		$this->laporan->generateExcel($this->input->get('periode_start'), $this->input->get('periode_end'));
	}
}
