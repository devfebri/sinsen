<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_ms_dealer_autofulfillment extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_ms_dealer_autofulfillment";
    protected $title  = "Master Setting Autofulfillment Dealer";

	public function __construct()
	{		
		parent::__construct();
		
		$this->load->database();
		$this->load->helper('url');

		$this->load->model('m_admin');			
		// $this->load->model('H3_md_ms_dealer_autofulfillment', 'dealer_auto');		
		
		$this->load->library('upload');
		$this->load->library('form_validation');
		
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
		$data['dealers'] = $this->db
		->select('d.kode_dealer_md')
		->select('d.nama_dealer')
		->select('d.autofulfillment_md')
		->select('d.alamat')
		->select('kab.kabupaten')
		->select('d.id_dealer')
		->select('0 as edit')
		->from('ms_dealer as d')
		->join('ms_kelurahan as kel', 'kel.id_kelurahan = d.id_kelurahan', 'left')
		->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan', 'left')
		->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten', 'left')
		->join('ms_provinsi as prov', 'prov.id_provinsi = kab.id_provinsi', 'left')
		->where('d.autofulfillment_md',1)
		->get()->result();
		$this->template($data);	
	}

	public function tambah(){
		$this->db->trans_start();
		$this->validate();

		$id_dealer = $this->input->post('id_dealer');
		// $this->jumlah_pit->insert($data);
		$this->db->set('autofulfillment_md', 1);
		$this->db->where('id_dealer', $id_dealer);
		$this->db->update('ms_dealer');
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$dealer_autof = $this->db
			->select('d.kode_dealer_md')
			->select('d.nama_dealer')
			->select('d.id_dealer')
			->select('d.alamat')
			->select('kab.kabupaten as kabupaten')
			->select('0 as edit')
			->from('ms_dealer as d')
			->join('ms_kelurahan as kel', 'kel.id_kelurahan = d.id_kelurahan', 'left')
			->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan', 'left')
			->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten', 'left')
			->join('ms_provinsi as prov', 'prov.id_provinsi = kab.id_provinsi', 'left')
			->where('d.id_dealer', $id_dealer)
			->where('d.autofulfillment_md',1)
			->get()->row();
			send_json($dealer_autof);
		}else{
		  	$this->output->set_status_header(500);
		}
	}

	public function hapus_dealer(){
		$this->db->trans_start();
		$id_dealer = $this->input->get('id');

		// $this->jumlah_pit->insert($data);
		$this->db->set('autofulfillment_md', 0);
		$this->db->where('id_dealer', $id_dealer);
		$this->db->update('ms_dealer');
		$this->db->trans_complete();

		if(!$this->db->trans_status()){
			$this->output->set_status_header(500);
		}
	}


	public function validate(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id_dealer', 'Dealer', 'required');

        if (!$this->form_validation->run())
        {
			$this->output->set_status_header(500);
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			]);
		}
    }
}