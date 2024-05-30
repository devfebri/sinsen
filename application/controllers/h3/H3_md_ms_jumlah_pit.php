<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_ms_jumlah_pit extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_ms_jumlah_pit";
    protected $title  = "Master Jumlah Pit";

	public function __construct()
	{		
		parent::__construct();
		
		$this->load->database();
		$this->load->helper('url');

		$this->load->model('m_admin');		
		$this->load->model('m_part');		
		$this->load->model('H3_md_ms_jumlah_pit_model', 'jumlah_pit');		
		
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
		->select('jp.*')
		->select('d.kode_dealer_md')
		->select('d.nama_dealer')
		->select('d.alamat')
		->select('kab.kabupaten')
		->select('0 as edit')
		->from('ms_h3_md_jumlah_pit as jp')
		->join('ms_dealer as d', 'd.id_dealer = jp.id_dealer')
		->join('ms_kelurahan as kel', 'kel.id_kelurahan = d.id_kelurahan', 'left')
		->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan', 'left')
		->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten', 'left')
		->join('ms_provinsi as prov', 'prov.id_provinsi = kab.id_provinsi', 'left')
		->get()->result();
		$this->template($data);	
	}

	public function tambah(){
		$this->db->trans_start();
		$this->validate();

		$data = $this->input->post(['id_dealer', 'jumlah_pit']);
		$this->jumlah_pit->insert($data);
		$id = $this->db->insert_id();
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$jumlah_pit = $this->db
			->select('jp.*')
			->select('d.kode_dealer_md')
			->select('d.nama_dealer')
			->select('d.alamat')
			->select('kab.kabupaten as kota_kabupaten')
			->select('0 as edit')
			->from('ms_h3_md_jumlah_pit as jp')
			->join('ms_dealer as d', 'd.id_dealer = jp.id_dealer')
			->join('ms_kelurahan as kel', 'kel.id_kelurahan = d.id_kelurahan', 'left')
			->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan', 'left')
			->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten', 'left')
			->join('ms_provinsi as prov', 'prov.id_provinsi = kab.id_provinsi', 'left')
			->where('jp.id', $id)
			->get()->row();
			send_json($jumlah_pit);
		}else{
		  	$this->output->set_status_header(500);
		}
	}

	public function hapus_dealer(){
		$this->db->trans_start();
		$this->jumlah_pit->delete($this->input->get('id'));
		$this->db->trans_complete();

		if(!$this->db->trans_status()){
			$this->output->set_status_header(500);
		}
	}

	public function update_dealer(){
		$this->db->trans_start();
		$this->jumlah_pit->update($this->input->get(['jumlah_pit']) ,$this->input->get(['id']));
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$dealer = $this->db
			->select('jp.*')
			->select('d.kode_dealer_md')
			->select('d.nama_dealer')
			->select('d.alamat')
			->select('kab.kabupaten')
			->select('0 as edit')
			->from('ms_h3_md_jumlah_pit as jp')
			->join('ms_dealer as d', 'd.id_dealer = jp.id_dealer')
			->join('ms_kelurahan as kel', 'kel.id_kelurahan = d.id_kelurahan')
			->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan')
			->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten')
			->join('ms_provinsi as prov', 'prov.id_provinsi = kab.id_provinsi')
			->where('jp.id', $this->input->get('id'))
			->get()->row();
			send_json($dealer);
		}else{
			$this->output->set_status_header(500);
		}
	}

	public function validate(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id_dealer', 'Dealer', 'required');
		$this->form_validation->set_rules('jumlah_pit', 'Jumlah PIT', 'required|numeric');

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