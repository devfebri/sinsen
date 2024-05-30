<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan_penjualan_program extends CI_Controller
{

	var $tables = "tr_sales_order";
	var $folder = "dealer/laporan";
	var $page   = "laporan_penjualan_program";
	var $title  = "Laporan Penjualan Promosi";

	public function __construct()
	{
		parent::__construct();
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		if ($name == "") {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
		}

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		//===== Load Library =====
		// $this->load->library('upload');
		$this->load->library('pdf');
		$this->load->helper('tgl_indo');
		$this->load->helper('terbilang');
	}
	protected function template($data)
	{
		$name = $this->session->userdata('nama');
		if ($name == "") {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
		} else {
			$this->load->view('template/header', $data);
			$this->load->view('template/aside');
			$this->load->view($this->folder . "/" . $this->page);
			$this->load->view('template/footer');
		}
	}

	public function index()
	{
		if (isset($_GET['cetak'])) {
			ini_set('memory_limit', '-1');
			ini_set('max_execution_time', 900);
			$data['set']       = 'cetak';
			$tgl_awal          = $data['tgl_awal']  = $this->input->get('tgl_awal');
			$tgl_akhir         = $data['tgl_akhir'] = $this->input->get('tgl_akhir');
			$dealer            = $this->input->get('id_dealer');
			$data['title']     = $this->title;
			$data['id_dealer'] = $this->m_admin->cari_dealer();

			if ($this->input->get('tipe') == 'preview') {
				$mpdf                           = $this->pdf->load();
				$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
				$mpdf->charset_in               = 'UTF-8';
				$mpdf->autoLangToFont           = true;
				$data['page_count'] = $mpdf->page;
				$html = $this->load->view('dealer/laporan/laporan_penjualan_program', $data, true);
				$mpdf->WriteHTML($html);
				$output = 'print.pdf';
				$mpdf->Output("$output", 'I');
			} else {
				$data['page_count'] = '';
				$data['tipe'] = $this->input->get('tipe');
				$this->load->view('dealer/laporan/laporan_penjualan_program', $data);
			}
		} else {
			$data['isi']   = $this->page;
			$data['title'] = $this->title;
			$data['page'] = $this->page;
			$data['set']   = "view";
			$this->template($data);
		}
	}
}
