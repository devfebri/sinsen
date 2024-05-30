<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Outstanding_claim_md extends CI_Controller
{

	var $table_head =   "tr_";
	var $pk_head     =   "id_";
	var $table_det =   "tr_";
	var $pk_det     =   "id_";
	var $folder =   "h2";
	var $page		=		"outstanding_claim_md";
	var $title  =   "Outstanding Claim MD";

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

		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page, "select");
		$sess = $this->m_admin->sess_auth();
		if ($name == "" or $auth == 'false' or $sess == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
		}
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
		$data['isi']    = $this->page;
		$data['title']	= $this->title;
		$data['set']	= "view";
		$data['dt_result'] = $this->db->query("SELECT rcw.*,nama_dealer,kode_dealer_md,no_mesin,no_rangka,tgl_pembelian
			FROM tr_rekap_claim_waranty rcw
			LEFT JOIN tr_rekap_claim_waranty_detail rcwd ON rcwd.id_rekap_claim=rcw.id_rekap_claim
			JOIN ms_dealer ON ms_dealer.id_dealer=rcw.id_dealer
			JOIN tr_lkh ON tr_lkh.id_lkh=rcw.no_lkh
			WHERE no_lbpc IS NOT NULL
			AND rcwd.no_ptcd IS NULL GROUP BY rcw.id_rekap_claim")->result();
		$this->template($data);
	}
}
