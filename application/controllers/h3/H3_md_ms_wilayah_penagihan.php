<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_ms_wilayah_penagihan extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_ms_wilayah_penagihan";
    protected $title  = "Master Wilayah Penagihan";

	public function __construct()
	{		
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('H3_md_ms_wilayah_penagihan_model', 'wilayah_penagihan');	
		$this->load->model('H3_md_ms_wilayah_penagihan_item_model', 'wilayah_penagihan_item');	
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
			'kode_wilayah', 'nama', 'active'
		]);
		$this->wilayah_penagihan->insert($data);
		$id = $this->db->insert_id();

		$items = $this->getOnly(['id_dealer'], $this->input->post('items'), [
			'id_wilayah_penagihan' => $id
		]);
		$this->wilayah_penagihan_item->insert_batch($items);
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$wilayah_penagihan = $this->wilayah_penagihan->find($id);
			send_json($wilayah_penagihan);
		}else{
		  	$this->output->set_status_header(400);
		}
	}

	public function detail(){
		$data['mode'] = 'detail';
		$data['set'] = "form";
		$data['wilayah_penagihan'] = $this->db
		->from('ms_h3_md_wilayah_penagihan as wp')
		->where('wp.id', $this->input->get('id'))
		->get()->row();

		$data['items'] = $this->db
		->select('wpi.*')
		->select('d.nama_dealer')
		->select('d.kode_dealer_md')
		->from('ms_h3_md_wilayah_penagihan_item as wpi')
		->join('ms_dealer as d', 'd.id_dealer = wpi.id_dealer')
		->where('wpi.id_wilayah_penagihan', $this->input->get('id'))
		->get()->result();

		$this->template($data);	
	}

	public function edit()
	{		
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['wilayah_penagihan'] = $this->db
		->from('ms_h3_md_wilayah_penagihan as wp')
		->where('wp.id', $this->input->get('id'))
		->get()->row();

		$data['items'] = $this->db
		->select('wpi.*')
		->select('d.nama_dealer')
		->select('d.kode_dealer_md')
		->from('ms_h3_md_wilayah_penagihan_item as wpi')
		->join('ms_dealer as d', 'd.id_dealer = wpi.id_dealer')
		->where('wpi.id_wilayah_penagihan', $this->input->get('id'))
		->get()->result();

		$this->template($data);									
	}

	public function update()
	{	
		$this->db->trans_start();
		$this->validate();
		$data = $this->input->post([
			'kode_wilayah', 'nama', 'active'
		]);
		$this->wilayah_penagihan->update($data, $this->input->post(['id']));

		$items = $this->getOnly(['id_dealer'], $this->input->post('items'), [
			'id_wilayah_penagihan' => $this->input->post('id')
		]);
		$this->wilayah_penagihan_item->update_batch($items, [
			'id_wilayah_penagihan' => $this->input->post('id')
		]);
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$wilayah_penagihan = $this->wilayah_penagihan->find($this->input->post('id'));
			send_json($wilayah_penagihan);
		}else{
		  	$this->output->set_status_header(400);
		}
	}

	public function validate(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('nama', 'Wilayah Penagihan', 'required|max_length[60]');

		if($this->uri->segment(3) == 'update'){
			$wilayah_penagihan = $this->wilayah_penagihan->get($this->input->post(['id']), true);
			if(
				!($wilayah_penagihan->kode_wilayah == $this->input->post('kode_wilayah'))
			){
				$this->form_validation->set_rules('kode_wilayah', 'Kode Wilayah', 'required|max_length[30]|is_unique[ms_h3_md_wilayah_penagihan.kode_wilayah]');
			}
		}else{
			$this->form_validation->set_rules('kode_wilayah', 'Kode Wilayah', 'required|max_length[30]|is_unique[ms_h3_md_wilayah_penagihan.kode_wilayah]');
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