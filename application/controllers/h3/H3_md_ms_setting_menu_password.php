<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_ms_setting_menu_password extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_ms_setting_menu_password";
    protected $title  = "Master Setting Password Menu";

	public function __construct()
	{		
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('H3_md_ms_setting_menu_password_model', 'menu_password');			
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
			'id_menu', 'password', 'active'
		]);

		$this->menu_password->insert(
			$this->clean_data($data)
		);
		$menu_password = $this->db->insert_id();

		$this->db->trans_complete();

		if($this->db->trans_status()){
			$result = $this->menu_password->find($menu_password);
			send_json($result);
		}else{
		  	$this->output->set_status_header(400);
		}
	}


	public function detail(){
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['menu'] = $this->db
		->select('ms.*')
		->select('mm.menu_name')
		->from('tr_h3_md_setting_menu_password as ms')
		->join('ms_menu as mm', 'mm.id_menu=ms.id_menu')
		->where('ms.id', $this->input->get('id'))
		->get()->row();

		$this->template($data);	
	}

	public function edit()
	{		
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['menu'] = $this->db
		->select('ms.*')
		->select('mm.menu_name')
		->from('tr_h3_md_setting_menu_password as ms')
		->join('ms_menu as mm', 'mm.id_menu=ms.id_menu')
		->where('ms.id', $this->input->get('id'))
		->get()->row();

		$this->template($data);									
	}

	public function update()
	{		
		$this->validate();

		$this->db->trans_start();
		$data = $this->input->post([
			'id_menu', 'password', 'active'
		]);
		$data = $this->clean_data($data);
		$this->menu_password->update($data, $this->input->post(['id']));
	
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$result = $this->menu_password->find($this->input->post('id'));
			send_json($result);
		}else{
		  	$this->output->set_status_header(500);
		}
	}

	public function validate(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id_menu', 'Menu', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required|max_length[49]');
		// $this->form_validation->set_rules('active', 'Active', 'required');

		$data = $this->input->post([
			'id_menu', 'password', 'active'
		]);

		if($this->uri->segment(3) == 'save'){
			//Tambah Validasi Jika menu sudah pernah diinput
			$cek_menu = $this->db->select('mm.menu_name')
					->from('tr_h3_md_setting_menu_password as ms')
					->join('ms_menu as mm', 'mm.id_menu = ms.id_menu')
					->where('ms.id_menu', $data['id_menu'])
					->get()->row_array();
			if(!empty($cek_menu)){
				send_json([
					'error_type' => 'validation_error',
					'message' => 'Menu '. $cek_menu['menu_name'] . ' pernah diinput '
				], 422);
			}
		}

		$pattern = '/ /';
		$result = preg_match($pattern, $data['password']);

		if ($result)
		{
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Password tidak boleh ada spasi'
			], 422);
		}
		

        if (!$this->form_validation->run())
        {
			$this->output->set_status_header(400);
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			]);
        }
    }
}