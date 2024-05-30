<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_ms_kelompok_vendor extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_ms_kelompok_vendor";
    protected $title  = "Master Kelompok Vendor";

	public function __construct()
	{		
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('H3_md_ms_kelompok_vendor_model', 'kelompok_vendor');
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
		$this->template($data);	
	}

	public function add()
	{				
		$data['mode']    = 'insert';
		$data['set']     = "form";
		$this->template($data);	
	}

	public function save()
	{		
		$this->db->trans_start();
		$this->validate();
		$data = $this->input->post([
			'id_kelompok_vendor', 'kelompok_vendor', 'status_pengiriman', 'created_manually', 'active'
		]);
		
		$this->kelompok_vendor->insert($data);
		$id = $this->db->insert_id();
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$kelompok_vendor = $this->kelompok_vendor->find($id);
			send_json($kelompok_vendor);
		}else{
		  	$this->output->set_status_header(400);
		}
	}

	public function detail(){
		$data['mode'] = 'detail';
		$data['set'] = "form";
		$data['kelompok_vendor'] = $this->kelompok_vendor->find($this->input->get('id'));

		$this->template($data);	
	}

	public function edit()
	{		
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['kelompok_vendor'] = $this->kelompok_vendor->find($this->input->get('id'));

		$this->template($data);									
	}

	public function update()
	{	
		$this->db->trans_start();
		$this->validate();
		$data = $this->input->post([
			'id_kelompok_vendor', 'kelompok_vendor', 'status_pengiriman', 'created_manually', 'active'
		]);
		
		$this->kelompok_vendor->update($data, $this->input->post(['id']));
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$kelompok_vendor = $this->kelompok_vendor->find($this->input->post('id'));
			send_json($kelompok_vendor);
		}else{
		  	$this->output->set_status_header(400);
		}
	}

	public function validate(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('kelompok_vendor', 'Nama Kelompok Vendor', 'required|max[50]');
		$this->form_validation->set_rules('status_pengiriman', 'Status Pengiriman', 'required|max[20]');

		if($this->uri->segment(3) == 'update'){
			$kelompok_vendor = $this->kelompok_vendor->find($this->input->post('id'));
			
			if(
				!($kelompok_vendor->id_kelompok_vendor == $this->input->post('id_kelompok_vendor'))
			){
				$this->form_validation->set_rules('id_kelompok_vendor', 'Kode Kelompok Vendor', 'required|is_unique[ms_kelompok_vendor.id_kelompok_vendor]');
			}
		}else{
			$this->form_validation->set_rules('id_kelompok_vendor', 'Kode Kelompok Vendor', 'required|is_unique[ms_kelompok_vendor.id_kelompok_vendor]');
		}


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