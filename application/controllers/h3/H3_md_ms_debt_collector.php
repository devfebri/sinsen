<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_ms_debt_collector extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_ms_debt_collector";
    protected $title  = "Master Debt Collector";

	public function __construct()
	{		
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('H3_md_debt_collector_model', 'debt_collector');	
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
			'id_karyawan', 'active'
		]);
		$this->debt_collector->insert($data);
		$id = $this->db->insert_id();
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$debt_collector = $this->debt_collector->find($id);
			send_json($debt_collector);
		}else{
		  	$this->output->set_status_header(400);
		}
	}

	public function detail(){
		$data['mode'] = 'detail';
		$data['set'] = "form";
		$data['debt_collector'] = $this->db
		->select('dc.*')
		->select('k.npk')
		->select('k.nama_lengkap')
		->select('k.no_telp')
		->select('k.alamat')
		->select('date_format(k.tgl_masuk, "%d-%m-%Y") as tgl_masuk')
		->from('ms_h3_md_debt_collector as dc')
		->join('ms_karyawan as k', 'k.id_karyawan = dc.id_karyawan')
		->where('dc.id', $this->input->get('id'))
		->get()->row();

		$this->template($data);	
	}

	public function edit()
	{		
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['debt_collector'] = $this->db
		->select('dc.*')
		->select('k.npk')
		->select('k.nama_lengkap')
		->select('k.no_telp')
		->select('k.alamat')
		->select('date_format(k.tgl_masuk, "%d-%m-%Y") as tgl_masuk')
		->from('ms_h3_md_debt_collector as dc')
		->join('ms_karyawan as k', 'k.id_karyawan = dc.id_karyawan')
		->where('dc.id', $this->input->get('id'))
		->get()->row();

		$this->template($data);									
	}

	public function update()
	{	
		$this->db->trans_start();
		$this->validate();
		$data = $this->input->post([
			'id_karyawan', 'active'
		]);
		$this->debt_collector->update($data, $this->input->post(['id']));
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$debt_collector = $this->debt_collector->find($this->input->post('id'));
			send_json($debt_collector);
		}else{
		  	$this->output->set_status_header(400);
		}
	}

	public function validate(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id_karyawan', 'Karyawan', 'required');

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