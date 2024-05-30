<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_ms_diskon_oli_kpb extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_ms_diskon_oli_kpb";
    protected $title  = "Diskon Oli KPB";

	public function __construct()
	{		
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('h3_md_ms_diskon_oli_kpb_model', 'diskon_oli_kpb');		
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
			'id_part', 'tipe_produksi', 'id_tipe_kendaraan', 'tipe_diskon', 'diskon_value', 'active', 'harga_kpb'
		]);
		
		$this->diskon_oli_kpb->insert($data);
		$id = $this->db->insert_id();
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$diskon_oli_kpb = $this->diskon_oli_kpb->find($id);
			send_json($diskon_oli_kpb);
		}else{
		  	$this->output->set_status_header(400);
		}
	}

	public function detail(){
		$data['mode'] = 'detail';
		$data['set'] = "form";
		$data['diskon_oli_kpb'] = $this->db
		->select('dok.id')
		->select('dok.id_part')
		->select('p.nama_part')
		->select('p.harga_dealer_user')
		->select('tk.tipe_ahm as nama_tipe_kendaraan')
		->select('date_format(tk.tgl_awal, "%d-%m-%Y") as tahun_kendaraan')
		->select('dok.active')
		->select('dok.id_tipe_kendaraan')
		->select('dok.tipe_produksi')
		->select('dok.tipe_diskon')
		->select('dok.diskon_value')
		->from('ms_h3_md_diskon_oli_kpb as dok')
		->join('ms_part as p', 'p.id_part = dok.id_part')
		->join('ms_tipe_kendaraan as tk', 'tk.id_tipe_kendaraan = dok.id_tipe_kendaraan', 'left')
		->where('dok.id', $this->input->get('id'))
		->get()->row();

		$this->template($data);	
	}

	public function edit()
	{		
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['diskon_oli_kpb'] = $this->db
		->select('dok.id')
		->select('dok.id_part')
		->select('p.nama_part')
		->select('p.harga_dealer_user')
		->select('tk.tipe_ahm as nama_tipe_kendaraan')
		->select('date_format(tk.tgl_awal, "%d-%m-%Y") as tahun_kendaraan')
		->select('dok.active')
		->select('dok.id_tipe_kendaraan')
		->select('dok.tipe_produksi')
		->select('dok.tipe_diskon')
		->select('dok.diskon_value')
		->from('ms_h3_md_diskon_oli_kpb as dok')
		->join('ms_part as p', 'p.id_part = dok.id_part')
		->join('ms_tipe_kendaraan as tk', 'tk.id_tipe_kendaraan = dok.id_tipe_kendaraan', 'left')
		->where('dok.id', $this->input->get('id'))
		->get()->row();

		$this->template($data);									
	}

	public function set_tipe_produksi(){
		$data = $this->db
		->select('dok.id')
		->select('ptm.tipe_produksi')
		->from('ms_h3_md_diskon_oli_kpb as dok')
		->join('ms_ptm as ptm', 'ptm.tipe_marketing = dok.id_tipe_kendaraan')
		->where('dok.tipe_produksi is null', null, false)
		->get()->result_array();

		foreach ($data as $row) {
			$this->db
			->set('tipe_produksi', $row['tipe_produksi'])
			->where('id', $row['id'])
			->update('ms_h3_md_diskon_oli_kpb');
		}
		echo 'selesai';
	}

	public function update()
	{	
		$this->db->trans_start();
		$this->validate();
		$data = $this->input->post([
			'id_part', 'id_tipe_kendaraan', 'tipe_diskon', 'diskon_value', 'active', 'harga_kpb'
		]);
		
		$this->diskon_oli_kpb->update($data, $this->input->post(['id']));
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$diskon_oli_kpb = $this->diskon_oli_kpb->get($this->input->post(['id']), true);
			send_json($diskon_oli_kpb);
		}else{
		  	$this->output->set_status_header(400);
		}
	}

	public function validate(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id_part', 'Part', 'required');
		$this->form_validation->set_rules('id_tipe_kendaraan', 'Tipe Kendaraan', 'required');
		$this->form_validation->set_rules('tipe_diskon', 'Tipe Diskon', 'required');
		$this->form_validation->set_rules('diskon_value', 'Diskon Oli', 'required|numeric');

        if (!$this->form_validation->run())
        {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
		}
	}
	
	public function get_parts_diskon_oli_kpb(){
		$data = [];
		foreach ($this->input->post('parts') as $part) {
			$diskon = $this->diskon_oli_kpb->get_diskon_oli_kpb($part['id_part'], $part['id_tipe_kendaraan']);

			if($diskon != null){
				$data[] = $diskon;
			}
		}

		send_json($data);
	}
}