<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_master_kategori_claim_c3 extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_master_kategori_claim_c3";
    protected $title  = "Kategori Claim C3";

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
		$auth = $this->m_admin->user_auth($this->page,"select");		
		$sess = $this->m_admin->sess_auth();						
		if($name=="" OR $auth=='false')
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."denied'>";
		}elseif($sess=='false'){
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."crash'>";
		}

		$this->load->model('h3_md_kategori_claim_c3_model', 'kategori_claim_c3');
	}

	public function index(){
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$data['kategori_claim_c3'] = $this->kategori_claim_c3->all();
		$this->template($data);
	}

	public function add(){
		$data['mode']    = 'insert';
		$data['set']     = "form";
		$this->template($data);
	}

	public function save(){
		$this->db->trans_start();
		$this->validate();
		$data = $this->input->post(['kode_claim', 'nama_claim', 'tipe_claim', 'claim_potong_avs', 'active']);
		$this->kategori_claim_c3->insert($data);
		$id = $this->db->insert_id();
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Kategori Claim C3 berhasil dibuat');
			$this->session->set_flashdata('tipe', 'info');
			$kategori_claim_c3 = $this->kategori_claim_c3->find($id);
			send_json($kategori_claim_c3);
		}else{
			$this->session->set_flashdata('pesan', 'Kategori Claim C3 tidak berhasil dibuat');
			$this->session->set_flashdata('tipe', 'danger');
			$this->output->set_status_header(500);
		}
	}

	public function detail(){
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['kategori_claim_c3'] = $this->kategori_claim_c3->find($this->input->get('id'));

		$this->template($data);
	}

	public function edit(){
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['kategori_claim_c3'] = $this->kategori_claim_c3->find($this->input->get('id'));

		$this->template($data);
	}

	public function update(){
		$this->db->trans_start();
		$this->validate();
		$data = $this->input->post(['kode_claim', 'nama_claim', 'tipe_claim', 'claim_potong_avs', 'active']);
		$this->kategori_claim_c3->update($data, $this->input->post(['id']));
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Kategori Claim C3 berhasil diperbarui');
			$this->session->set_flashdata('tipe', 'info');
			$kategori_claim_c3 = $this->kategori_claim_c3->find($this->input->post('id'));
			send_json($kategori_claim_c3);
		}else{
			$this->session->set_flashdata('pesan', 'Kategori Claim C3 tidak berhasil diperbarui');
			$this->session->set_flashdata('tipe', 'danger');
			$this->output->set_status_header(500);
		}
	}

	public function validate(){
		$this->form_validation->set_error_delimiters('', '');
		if($this->uri->segment(3) == 'update'){
			$kategori_claim_c3 = $this->kategori_claim_c3->find($this->input->post('id'));
			
			if(
				!($kategori_claim_c3->kode_claim == $this->input->post('kode_claim'))
			){
				$this->form_validation->set_rules('kode_claim', 'Kode Claim', 'required|is_unique[ms_kategori_claim_c3.kode_claim]');
			}
		}else{
			$this->form_validation->set_rules('kode_claim', 'Kode Claim', 'required|is_unique[ms_kategori_claim_c3.kode_claim]');
		}
		$this->form_validation->set_rules('nama_claim', 'Nama Claim', 'required');
		$this->form_validation->set_rules('tipe_claim', 'Tipe Claim', 'required');

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