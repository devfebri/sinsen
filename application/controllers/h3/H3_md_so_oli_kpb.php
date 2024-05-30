<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_so_oli_kpb extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_so_oli_kpb";
    protected $title  = "SO Oli KPB";

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

		$this->load->model('h3_md_claim_ahm_model', 'claim_ahm');
		$this->load->model('h3_md_claim_ahm_parts_model', 'claim_ahm_parts');
		$this->load->model('part_model', 'master_part');
		$this->load->model('dealer_model', 'dealer');
	}

	public function index(){
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$data['so_oli_kpb'] = $this->db->select('*')
									->from('tr_claim_kpb_generate_detail as ckgd')
									->where('id_so_kpb !=', null)
									->get()
									->result();
		$this->template($data);
	}

	public function add(){
		$data['mode']    = 'insert';
		$data['set']     = "form";
		$this->template($data);
	}

	public function save(){
		$this->db->trans_start();
		$this->claim_ahm->insert([]);
		$id_claim_ahm = $this->db->insert_id();
		$claimAhmPartsData = $this->groupArray($this->input->post(['id_part', 'qty_claim', 'harga']), [
			'id_claim_ahm' => $id_claim_ahm
		]);
		$this->claim_ahm_parts->insert_batch($claimAhmPartsData);
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Data berhasil diperbarui.');
			$this->session->set_flashdata('tipe', 'info');
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/$this->page/detail?id={$id_claim_ahm}'>";
		}else{
			$this->session->set_flashdata('pesan', 'Data not found !');
			$this->session->set_flashdata('tipe', 'danger');
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/$this->page'>";
		}
	}

	public function detail(){
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['claim_ahm'] = $this->claim_ahm->find($this->input->get('id'), 'id_claim_ahm');
		$claim_ahm_parts = $this->claim_ahm_parts->get([
			'id_claim_ahm' => $this->input->get('id')
		]);

		foreach ($claim_ahm_parts as $each) {
			$subArr = (array) $each;
			$partDetail = (array) $this->master_part->find($each->id_part, 'id_part');
			$subArr = array_merge($subArr, $partDetail);	
			$data['claim_ahm_parts'][] = $subArr;
		}
		$this->template($data);
	}

	public function terima_claim(){
		$data['mode']    = 'terima_claim';
		$data['set']     = "form";
		$data['claim_ahm'] = $this->claim_ahm->find($this->input->get('id'), 'id_claim_ahm');
		$claim_ahm_parts = $this->claim_ahm_parts->get([
			'id_claim_ahm' => $this->input->get('id')
		]);

		foreach ($claim_ahm_parts as $each) {
			$subArr = (array) $each;
			$partDetail = (array) $this->master_part->find($each->id_part, 'id_part');
			$subArr = array_merge($subArr, $partDetail);	
			$data['claim_ahm_parts'][] = $subArr;
		}
		$this->template($data);
	}

	public function simpan_claim(){
		// TODO: Belum ada pemotongan atau penambahan stok pada terima claim. Begitu juga dengan laporannya.
		$this->db->trans_start();
		$this->claim_ahm->update($this->input->post(['respon', 'tipe_ganti']), $this->input->post(['id_claim_ahm']));
		$this->db->trans_complete();
		
		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Data berhasil diperbarui.');
			$this->session->set_flashdata('tipe', 'info');
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/$this->page/detail?id={$this->input->post('id_claim_ahm')}'>";
		}else{
			$this->session->set_flashdata('pesan', 'Data not found !');
			$this->session->set_flashdata('tipe', 'danger');
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/$this->page'>";
		}
	}
}