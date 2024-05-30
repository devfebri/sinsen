<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_ap_part extends Honda_Controller
{
	protected $folder = "h3";
	protected $page   = "h3_md_ap_part";
	protected $title  = "AP Part";

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
}
