<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_ms_ongkos_angkut_part extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_ms_ongkos_angkut_part";
    protected $title  = "Master Ongkos Angkut Part";

	public function __construct()
	{		
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('H3_md_ongkos_angkut_part_model', 'ongkos_angkut_part');		
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

	public function penambahan(){
		$data['mode']    = 'penambahan';
		$data['set']     = "form";
		$this->template($data);	
	}

	public function simpan_penambahan(){
		$this->db->trans_start();
		$this->validate();
		$data = $this->input->post([
			'id_vendor', 'type_mobil', 'kapasitas', 'per_satuan', 'harga', 'kategori', 'start_date', 'jenis'
		]);
		$this->ongkos_angkut_part->insert($data);
		$this->db->trans_complete();

		if($this->db->trans_status()){
			send_json($this->input->post(['id_vendor']));
		}else{
		  	$this->output->set_status_header(500);
		}
	}

	public function ambil_harga_sebelumnya(){
		$query = $this->db
		->select('o.harga')
		->from('ms_h3_md_ongkos_angkut_part as o')
		->where('o.id_vendor', $this->input->get('id_vendor'))
		->where('o.jenis', $this->input->get('jenis'))
		->where('o.jenis', $this->input->get('jenis'))
		->where('o.type_mobil', $this->input->get('type_mobil'))
		->order_by('o.start_date', 'desc')
		->order_by('o.id', 'desc')
		->limit(1)
		->get()->row();

		if ($query == null) {
			echo 0;
		}else{
			echo $query->harga;
		}
		die;
	}

	public function hapus_ongkos_angkut_part(){
		$this->db->trans_start();
		$this->ongkos_angkut_part->delete($this->input->get('id'));
		$this->db->trans_complete();

		if(!$this->db->trans_status()){
			$this->output->set_status_header(500);
		}
	}

	public function validate(){
		$this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('id_vendor', 'Ekspedisi', 'required');
        $this->form_validation->set_rules('type_mobil', 'Type Mobil', 'required');
        $this->form_validation->set_rules('harga', 'Harga', 'required');
        $this->form_validation->set_rules('kategori', 'Kategori', 'required');
        $this->form_validation->set_rules('per_satuan', 'Per Satuan', 'required');
        $this->form_validation->set_rules('start_date', 'Dimulai Tanggal', 'required');

        if (!$this->form_validation->run())
        {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
        }
    }

	public function detail(){
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['ongkos_angkut_part'] = $this->db
        ->select('o.*')
        ->select('e.nama_ekspedisi as nama_vendor')
        ->from('ms_h3_md_ongkos_angkut_part as o')
		->join('ms_h3_md_ekspedisi as e', 'e.id = o.id_vendor')
		->where('o.id_vendor', $this->input->get('id_vendor'))
		->limit(1)
		->get()->row();

		$data['items'] = $this->db
		->select('o.*')
		->select('date_format(o.start_date, "%d-%m-%Y") as start_date')
		->from('ms_h3_md_ongkos_angkut_part as o')
		->where('o.id_vendor', $this->input->get('id_vendor'))
		->order_by('o.start_date', 'asc')
		->get()->result();

		$this->template($data);	
	}
}