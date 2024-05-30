<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// TODO: Monitor packing_sheet baru tampilan
class H3_md_monitor_packing_sheet extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_monitor_packing_sheet";
    protected $title  = "Monitoring Packing Sheet";

	public function __construct()
	{		
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
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

		$this->load->model('h3_md_mutasi_gudang_model', 'mutasi_gudang');
		$this->load->model('part_model', 'master_part');
		$this->load->model('dealer_model', 'dealer');
		$this->load->model('ms_gudang_model', 'ms_gudang');
	}

	public function index(){
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$data['mutasi_gudang'] = $this->db->select('mmg.*, date_format(mmg.tanggal, "%d-%m-%Y") as tanggal, mp.nama_part')
										->from('tr_h3_md_mutasi_gudang as mmg')
										->join('ms_part as mp', 'mmg.id_part = mp.id_part')
										// ->join('ms_gudang as mga', 'mga.id_gudang = mmg.id_gudang_asal')
										// ->join('ms_gudang as mgt', 'mgt.id_gudang = mmg.id_gudang_tujuan')
										->get()->result();
										
		$this->template($data);
	}

	public function add(){
		$data['mode']    = 'insert';
		$data['set']     = "form";
		$this->template($data);
	}

	public function cari_gudang(){
		$gudang = $this->db->select('id_gudang, gudang')
						->from('ms_gudang as mg')
						->like('gudang', $this->input->post('query'))
						->get()->result();

		send_json($gudang);
	}

	public function cari_lokasi(){
		$lokasi = $this->db->select('id_lokasi_unit')
						->from('ms_lokasi_unit as mlu')
						->where($this->input->post(['id_gudang']))
						->like('id_lokasi_unit', $this->input->post('query'))
						->get()->result();

		send_json($lokasi);
	}

	public function save(){
		$mutasiGudangData = array_merge($this->input->post(), [
			'id_mutasi_gudang' => $this->mutasi_gudang->generateID(),
			'tanggal' => date('Y-m-d', time())
		]);

		$this->db->trans_start();
		$this->mutasi_gudang->insert($mutasiGudangData);
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			send_json($this->mutasi_gudang->find($mutasiGudangData['id_mutasi_gudang'], 'id_mutasi_gudang'));
		}
	}

	public function detail(){
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['mutasi_gudang'] = $this->mutasi_gudang->get($this->input->get(['id_mutasi_gudang']), true);
		$data['part'] = $this->db->select('mp.id_part, mp.nama_part, mmg.qty')
								->from('tr_h3_md_mutasi_gudang as mmg')
								->where('mp.id_part', $data['mutasi_gudang']->id_part)
								->join('ms_part as mp', 'mp.id_part = mmg.id_part')
								->get()->row();

		$data['gudang_asal'] = $this->db->from('ms_gudang')
		->where('id_gudang', $data['mutasi_gudang']->id_gudang_asal)
		->get()->row();

		$data['lokasi_asal'] = $this->db->from('ms_lokasi_unit')
		->where('id_lokasi_unit', $data['mutasi_gudang']->id_lokasi_asal)
		->get()->row();

		$data['gudang_tujuan'] = $this->db->from('ms_gudang')
		->where('id_gudang', $data['mutasi_gudang']->id_gudang_tujuan)
		->get()->row();

		$data['lokasi_tujuan'] = $this->db->from('ms_lokasi_unit')
		->where('id_lokasi_unit', $data['mutasi_gudang']->id_lokasi_tujuan)
		->get()->row();

		$data['option_gudang_asal'] = $data['option_gudang_tujuan'] = $this->ms_gudang->all();

		$data['option_lokasi_asal'] = $this->db->select('*')
		->from('ms_lokasi_unit')
		->where('id_gudang', $data['mutasi_gudang']->id_gudang_asal)
		->get()->result();

		$data['option_lokasi_tujuan'] = $this->db->select('*')
		->from('ms_lokasi_unit')
		->where('id_gudang', $data['mutasi_gudang']->id_gudang_tujuan)
		->get()->result();

		$this->template($data);
	}

	public function edit(){
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['mutasi_gudang'] = $this->mutasi_gudang->get($this->input->get(['id_mutasi_gudang']), true);
		$data['part'] = $this->db->select('mp.id_part, mp.nama_part, mmg.qty')
								->from('tr_h3_md_mutasi_gudang as mmg')
								->where('mp.id_part', $data['mutasi_gudang']->id_part)
								->join('ms_part as mp', 'mp.id_part = mmg.id_part')
								->get()->row();

		$data['gudang_asal'] = $this->db->from('ms_gudang')
		->where('id_gudang', $data['mutasi_gudang']->id_gudang_asal)
		->get()->row();

		$data['lokasi_asal'] = $this->db->from('ms_lokasi_unit')
		->where('id_lokasi_unit', $data['mutasi_gudang']->id_lokasi_asal)
		->get()->row();

		$data['gudang_tujuan'] = $this->db->from('ms_gudang')
		->where('id_gudang', $data['mutasi_gudang']->id_gudang_tujuan)
		->get()->row();

		$data['lokasi_tujuan'] = $this->db->from('ms_lokasi_unit')
		->where('id_lokasi_unit', $data['mutasi_gudang']->id_lokasi_tujuan)
		->get()->row();

		$data['option_gudang_asal'] = $data['option_gudang_tujuan'] = $this->ms_gudang->all();

		$data['option_lokasi_asal'] = $this->db->select('*')
		->from('ms_lokasi_unit')
		->where('id_gudang', $data['mutasi_gudang']->id_gudang_asal)
		->get()->result();

		$data['option_lokasi_tujuan'] = $this->db->select('*')
		->from('ms_lokasi_unit')
		->where('id_gudang', $data['mutasi_gudang']->id_gudang_tujuan)
		->get()->result();

		$this->template($data);
	}

	public function update(){
		$mutasiGudangData = $this->input->post();

		$this->db->trans_start();
		$this->mutasi_gudang->update($mutasiGudangData, $this->input->post(['id_mutasi_gudang']));
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			send_json($this->mutasi_gudang->get($this->input->post(['id_mutasi_gudang']), true));
		}
	}
}