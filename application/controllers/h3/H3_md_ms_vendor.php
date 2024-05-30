<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_ms_vendor extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_ms_vendor";
    protected $title  = "Master Vendor";

	public function __construct()
	{		
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('Vendor_model', 'vendor');
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
			'id_vendor', 'vendor_name', 'no_telp', 'alamat',
            'id_vendor_type', 'id_vendor_group', 'ppn', 'no_rekening',
            'nama_rekening', 'atas_nama_bank', 'active'
		]);
		
		$this->vendor->insert($data);
		$this->db->trans_complete();

		$vendor = (array) $this->vendor->find($this->input->post('id_vendor'), 'id_vendor');
		if($this->db->trans_status() AND $vendor != null){
			send_json([
				'message' => 'Vendor berhasil ditambahkan',
				'payload' => $vendor,
				'redirect_url' => base_url(sprintf('h3/h3_md_ms_vendor/detail?id_vendor=%s', $vendor['id_vendor']))
			]);
		}else{
			send_json([
				'message' => 'Vendor tidak berhasil ditambahkan',
			], 422);
		}
	}

	public function detail(){
		$data['mode'] = 'detail';
		$data['set'] = "form";
		$data['vendor'] = $this->db
        ->select('v.id_vendor')
        ->select('v.vendor_name')
        ->select('v.no_telp')
        ->select('v.alamat')
        ->select('v.id_vendor_type')
        ->select('vt.vendor_type as tipe_vendor')
        ->select('v.id_vendor_group')
        ->select('vg.vendor_group as group_vendor')
        ->select('v.ppn')
        ->select('v.no_rekening')
        ->select('v.nama_rekening')
        ->select('v.atas_nama_bank')
        ->select('v.active')
        ->from('ms_vendor as v')
        ->join('ms_vendor_type as vt', 'vt.id_vendor_type = v.id_vendor_type', 'left')
        ->join('ms_vendor_group as vg', 'vg.id_vendor_group = v.id_vendor_group', 'left')
		->where('v.id_vendor', $this->input->get('id_vendor'))
		->get()->row_array();

		$this->template($data);	
	}

	public function edit()
	{		
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['vendor'] = $this->db
        ->select('v.id_vendor')
        ->select('v.vendor_name')
        ->select('v.no_telp')
        ->select('v.alamat')
        ->select('v.id_vendor_type')
        ->select('vt.vendor_type as tipe_vendor')
        ->select('v.id_vendor_group')
        ->select('vg.vendor_group as group_vendor')
        ->select('v.ppn')
        ->select('v.no_rekening')
        ->select('v.nama_rekening')
        ->select('v.atas_nama_bank')
        ->select('v.active')
        ->from('ms_vendor as v')
        ->join('ms_vendor_type as vt', 'vt.id_vendor_type = v.id_vendor_type', 'left')
        ->join('ms_vendor_group as vg', 'vg.id_vendor_group = v.id_vendor_group', 'left')
		->where('v.id_vendor', $this->input->get('id_vendor'))
		->get()->row_array();

		$this->template($data);									
	}

	public function update()
	{	
		$this->db->trans_start();
		$this->validate();
		$data = $this->input->post([
			'vendor_name', 'no_telp', 'alamat',
            'id_vendor_type', 'id_vendor_group', 'ppn', 'no_rekening',
            'nama_rekening', 'atas_nama_bank', 'active'
		]);
		
		$this->vendor->update($data, $this->input->post(['id_vendor']));
		$this->db->trans_complete();

		$vendor = (array) $this->vendor->find($this->input->post('id_vendor'), 'id_vendor');
		if($this->db->trans_status() AND $vendor != null){
			send_json([
				'message' => 'Vendor berhasil diperbarui',
				'payload' => $vendor,
				'redirect_url' => base_url(sprintf('h3/h3_md_ms_vendor/detail?id_vendor=%s', $vendor['id_vendor']))
			]);
		}else{
			send_json([
				'message' => 'Vendor tidak berhasil diperbarui',
			], 422);
		}
	}

	public function validate(){
		$this->form_validation->set_error_delimiters('', '');
		if($this->input->post('mode') == 'insert'){
			$this->form_validation->set_rules('id_vendor', 'Kode Vendor', 'required|is_unique[ms_vendor.id_vendor]');
		}else{
			$this->form_validation->set_rules('id_vendor', 'Kode Vendor', 'required');
		}
		$this->form_validation->set_rules('vendor_name', 'Nama Vendor', 'required');
		$this->form_validation->set_rules('no_telp', 'No. Telepon', 'required');
		$this->form_validation->set_rules('alamat', 'Alamat', 'required');
		$this->form_validation->set_rules('id_vendor_type', 'Tipe Vendor', 'required');
		// $this->form_validation->set_rules('id_vendor_group', 'Group Vendor', 'required');s
		$this->form_validation->set_rules('ppn', 'PPN', 'required|numeric|is_natural|greater_than_equal_to[0]|less_than_equal_to[100]');
		$this->form_validation->set_rules('no_rekening', 'Nomor Rekening', 'required');
		$this->form_validation->set_rules('nama_rekening', 'Bank', 'required');
		$this->form_validation->set_rules('atas_nama_bank', 'Atas Nama', 'required');

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