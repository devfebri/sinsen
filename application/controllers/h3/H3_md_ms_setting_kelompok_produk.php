<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_ms_setting_kelompok_produk extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_ms_setting_kelompok_produk";
    protected $title  = "Master Setting Kelompok Produk";

	public function __construct()
	{		
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('H3_md_ms_setting_kelompok_produk_model', 'setting_kelompok_produk');
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
		$data['parts'] = $this->setting_kelompok_produk->get(['produk' => 'Parts']);
		$data['oil'] = $this->setting_kelompok_produk->get(['produk' => 'Oil']);
		$data['acc'] = $this->setting_kelompok_produk->get(['produk' => 'Acc']);
		$data['other'] = $this->setting_kelompok_produk->get(['produk' => 'Other']);
		$data['apparel'] = $this->setting_kelompok_produk->get(['produk' => 'Apparel']);
		$data['tools'] = $this->setting_kelompok_produk->get(['produk' => 'Tools']);
		$this->template($data);	
	}

	public function simpan_setting_kelompok_produk()
	{		
		$this->db->trans_start();

		$this->validate();
		$result = [];
		foreach ($this->input->post('kelompok_part') as $each) {
			$data = [
				'produk' => $this->input->post('produk'),
				'id_kelompok_part' => $each
			];
			$id = $this->setting_kelompok_produk->insert($data);
			$setting_kelompok_produk = $this->setting_kelompok_produk->find($id);
			$result[] = $setting_kelompok_produk;
		}
		$this->db->trans_complete();

		if($this->db->trans_status()){
			send_json($result);
		}else{
		  	send_json([
				  'message' => 'Tidak berhasil menyimpan settingan kelompok produk'
			  ], 422);
		}
	}

	public function hapus_setting_kelompok_produk()
	{		
		$this->db->trans_start();
		$this->setting_kelompok_produk->delete($this->input->get('id'));
		$this->db->trans_complete();

		if($this->db->trans_status()){
			send_json([
				'message' => 'Data berhasil dihapus.'
			]);
		}else{
		  	send_json([
				'message' => 'Tidak berhasil menghapus data'
			  ], 422);
		}
	}

	public function validate(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('produk', 'Produk', 'required');

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