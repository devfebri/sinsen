<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_laporan_penjualan_harian extends Honda_Controller
{
	protected $folder = "h3";
	protected $page   = "h3_md_laporan_penjualan_harian";
	protected $title  = "Laporan Penjualan Harian";

	public function __construct()
	{
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('H3_md_laporan_penjualan_harian_model', 'laporan_penjualan_harian');
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
	}

	public function index()
	{
		$data['mode'] = 'index';
		$data['set'] = 'index';

		$this->template($data);
	}

	public function pdf(){
		$periode_awal = $this->input->get('periode_start');
		$periode_akhir = $this->input->get('periode_end');
		$group = $this->input->get('group');
		$this->laporan_penjualan_harian->generatePdf($periode_awal, $periode_akhir, $group);
	}

	public function hitung_so_diskon(){
		$this->load->model('h3_md_sales_order_parts_model', 'sales_order_parts');

		$this->db
		->select('sop.id_sales_order')
		->select('sop.id_part')
		->select('sop.qty_order')
		->select('sop.harga')
		->select('p.harga_md_dealer as hpp')
		->select('sop.tipe_diskon')
		->select('sop.diskon_value')
		->select('sop.tipe_diskon_campaign')
		->select('sop.diskon_value_campaign')
		->select('sop.id_campaign_diskon')
		->select('sc.jenis_diskon_campaign')
		->from('tr_h3_md_sales_order_parts as sop')
		->join('ms_part as p', 'p.id_part = sop.id_part')
		->join('ms_h3_md_sales_campaign as sc', 'sc.id = sop.id_campaign_diskon', 'left');

		$this->db->trans_start();

		foreach($this->db->get()->result_array() as $row){
			$this->sales_order_parts->set_hpp($row['id_sales_order'], $row['id_part']);
			$this->sales_order_parts->set_perhitungan_diskon($row['id_sales_order'], $row['id_part']);
		}

		$this->db->trans_complete();
	}
}
