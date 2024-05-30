<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_ms_tipe_po extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_ms_tipe_po";
    protected $title  = "Master Tipe PO";

	public function __construct()
	{		
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('H3_md_ms_tipe_po_model', 'tipe_po');
		$this->load->model('H3_md_ms_tipe_po_item_model', 'tipe_po_item');
		//===== Load Library =====
		$this->load->library('upload');
		$this->load->library('form_validation');
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page,"select");		
		$sess = $this->m_admin->sess_auth();						
		if($name=="" OR $auth=='false')
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."denied'>";
		}elseif($sess=='false'){
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."crash'>";
		}

	}

	public function index()
	{				
		$data['set']	= "index";
		$data['tipe_po'] = $this->db
		->from('master_tipe_po')
		->get()->row();

		$data['items'] = $this->db
		->select('tpi.id_dealer')
		->select('d.kode_dealer_md')
		->select('d.nama_dealer')
		->select('d.alamat')
		->select('tpi.fix')
		->select('tpi.reg')
		->from('master_tipe_po_item as tpi')
		->join('ms_dealer as d', 'd.id_dealer = tpi.id_dealer')
		->get()->result();

		$this->template($data);	
	}

	public function simpan()
	{		
		$this->db->trans_start();

		$this->validate();
		$data = $this->input->post([
			'fix', 'reg'
		]);
		
		$this->tipe_po->update($data, []);

		$items = $this->getOnly([
			'id_dealer', 'fix', 'reg'
		], $this->input->post('items'), []);

		$this->tipe_po_item->truncate();
		$this->tipe_po_item->insert_batch($items);
		$this->db->trans_complete();

		if(!$this->db->trans_status()){
		  	$this->output->set_status_header(400);
		}
	}

	public function validate(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('fix', 'PO Fix', 'required|numeric');
		$this->form_validation->set_rules('reg', 'PO Reguler', 'required|numeric');

        if (!$this->form_validation->run())
        {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
		}
    }
}