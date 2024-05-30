<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_ms_satuan extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_ms_satuan";
    protected $title  = "Master Satuan";

	public function __construct()
	{		
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('satuan_model', 'satuan');
		$this->load->model('H3_md_satuan_item_model', 'satuan_item');
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
			'kode_satuan', 'satuan', 'active'
		]);
		
		$this->satuan->insert($data);
		$id = $this->db->insert_id();

		$items = $this->getOnly(['id_kelompok_part'], $this->input->post('items'), [
			'id_satuan' => $id
		]);
		if(count($items) > 0){
			$this->satuan_item->insert_batch($items);
		}
		$this->update_satuan_part($id);
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$satuan = $this->satuan->find($id, 'id_satuan');
			send_json($satuan);
		}else{
		  	$this->output->set_status_header(400);
		}
	}

	public function detail(){
		$data['mode'] = 'detail';
		$data['set'] = "form";
		$data['satuan'] = $this->satuan->find($this->input->get('id_satuan'), 'id_satuan');
		$data['items'] = $this->satuan_item->get($this->input->get(['id_satuan']));

		$this->template($data);	
	}

	public function edit()
	{		
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['satuan'] = $this->satuan->find($this->input->get('id_satuan'), 'id_satuan');
		$data['items'] = $this->satuan_item->get($this->input->get(['id_satuan']));

		$this->template($data);									
	}

	public function update()
	{	
		$this->db->trans_start();
		$this->validate();
		$data = $this->input->post([
			'kode_satuan', 'satuan', 'active'
		]);
		
		$this->satuan->update($data, $this->input->post(['id_satuan']));

		$items = $this->getOnly(['id_kelompok_part'], $this->input->post('items'), $this->input->post(['id_satuan']));
		$this->satuan_item->delete($this->input->post('id_satuan'), 'id_satuan');
		if(count($items) > 0){
			$this->satuan_item->insert_batch($items);
		}
		$this->update_satuan_part($this->input->post('id_satuan'));
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$satuan = $this->satuan->find($this->input->post('id_satuan'), 'id_satuan');
			send_json($satuan);
		}else{
		  	$this->output->set_status_header(400);
		}
	}

	public function update_satuan_part($id_satuan){
		$items_kelompok_part = $this->db
		->select('si.id_kelompok_part')
		->from('ms_satuan_item as si')
		->where('si.id_satuan', $id_satuan)
		->get_compiled_select();

		$this->db->set('p.id_satuan', $id_satuan)
		->where("p.kelompok_part in ({$items_kelompok_part})")
		->update('ms_part as p')
		;
	}

	public function validate(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('satuan', 'Nama Satuan', 'required|max[50]');

		if($this->uri->segment(3) == 'update'){
			$satuan = $this->satuan->find($this->input->post('id_satuan'), 'id_satuan');
			
			if(
				!($satuan->kode_satuan == $this->input->post('kode_satuan'))
			){
				$this->form_validation->set_rules('kode_satuan', 'Kode Satuan', 'required|is_unique[ms_satuan.kode_satuan]');
			}
		}else{
			$this->form_validation->set_rules('kode_satuan', 'Kode Satuan', 'required|is_unique[ms_satuan.kode_satuan]');
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