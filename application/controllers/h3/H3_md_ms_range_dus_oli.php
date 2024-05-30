<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_ms_range_dus_oli extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_ms_range_dus_oli";
    protected $title  = "Master Range Dus Oli";

	public function __construct()
	{		
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('H3_md_ms_range_dus_oli_model', 'range_dus_oli');
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
			'kode_range', 'range_start', 'range_end', 'active'
		]);
		
		$this->range_dus_oli->insert($data);
		$id = $this->db->insert_id();
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$range_dus_oli = $this->range_dus_oli->find($id);
			send_json($range_dus_oli);
		}else{
		  	$this->output->set_status_header(400);
		}
	}

	public function detail(){
		$data['mode'] = 'detail';
		$data['set'] = "form";
		$data['range_dus_oli'] = $this->range_dus_oli->find($this->input->get('id'));

		$this->template($data);	
	}

	public function edit()
	{		
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['range_dus_oli'] = $this->range_dus_oli->find($this->input->get('id'));

		$this->template($data);									
	}

	public function update()
	{	
		$this->db->trans_start();
		$this->validate();
		$data = $this->input->post([
			'kode_range', 'range_start', 'range_end', 'active'
		]);
		
		$this->range_dus_oli->update($data, $this->input->post(['id']));
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$range_dus_oli = $this->range_dus_oli->find($this->input->post('id'));
			send_json($range_dus_oli);
		}else{
		  	$this->output->set_status_header(400);
		}
	}

	public function validate(){
		$this->form_validation->set_error_delimiters('', '');
		if($this->uri->segment(3) == 'update'){
			$range_dus_oli = $this->range_dus_oli->find($this->input->post('id'));
			
			if(
				!($range_dus_oli->kode_range == $this->input->post('kode_range'))
			){
				$this->form_validation->set_rules('kode_range', 'Kode Range', 'required|is_unique[ms_h3_md_range_dus_oli.kode_range]');
			}
		}else{
			$this->form_validation->set_rules('kode_range', 'Kode Range', 'required|is_unique[ms_h3_md_range_dus_oli.kode_range]');
		}
		$this->form_validation->set_rules('range_start', 'Awal Range', 'required|numeric');
		$this->form_validation->set_rules('range_end', 'Akhir Range', 'required|numeric');

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