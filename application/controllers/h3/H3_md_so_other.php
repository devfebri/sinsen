<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_so_other extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_so_other";
    protected $title  = "SO Other";

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

		$this->load->model('h3_md_so_other_model', 'so_other');
		$this->load->model('h3_md_so_other_parts_model', 'so_other_parts');
		$this->load->model('part_model', 'master_part');
		$this->load->model('dealer_model', 'dealer');
	}

	public function index(){
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$data['so_other'] = $this->so_other->all();
		$this->template($data);
	}

	public function add(){
		$data['mode']    = 'insert';
		$data['set']     = "form";
		$this->template($data);
	}

	public function save(){
		$soOtherData = array_merge($this->input->post(['tipe_penjualan', 'jenis_pembayaran', 'id_dealer']), [
			'id_so_other' => $this->so_other->generateID(),
			'tanggal' => date('Y-m-d', time())
		]);

		$soOtherPartsData = $this->groupArray($this->input->post(['id_part', 'qty_on_hand', 'qty_order', 'harga']), [
			'id_so_other' => $soOtherData['id_so_other']
		]);

		$this->db->trans_start();
		$this->so_other->insert($soOtherData);
		$this->so_other_parts->insert_batch($soOtherPartsData);
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Data berhasil diperbarui.');
			$this->session->set_flashdata('tipe', 'info');
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/$this->page/detail?id={$soOtherData['id_so_other']}'>";
		}else{
			$this->session->set_flashdata('pesan', 'Data not found !');
			$this->session->set_flashdata('tipe', 'danger');
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/$this->page'>";
		}
	}

	public function detail(){
		// FIX: Buat halaman detail SO Other
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['so_other'] = $this->so_other->find($this->input->get('id'), 'id_so_other');
		$data['dealer'] = $this->dealer->find($data['so_other']->id_dealer, 'id_dealer');
		$so_other_parts = $this->so_other_parts->get([
			'id_so_other' => $this->input->get('id')
		]);

		foreach ($so_other_parts as $each) {
			$subArr = (array) $each;
			$partDetail = (array) $this->master_part->find($each->id_part, 'id_part');
			$subArr = array_merge($subArr, $partDetail);	
			$data['so_other_parts'][] = $subArr;
		}

		$this->template($data);
	}

	public function edit(){
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['so_other'] = $this->so_other->find($this->input->get('id'), 'id_so_other');
		$data['dealer'] = $this->dealer->find($data['so_other']->id_dealer, 'id_dealer');
		$so_other_parts = $this->so_other_parts->get([
			'id_so_other' => $this->input->get('id')
		]);

		foreach ($so_other_parts as $each) {
			$subArr = (array) $each;
			$partDetail = (array) $this->master_part->find($each->id_part, 'id_part');
			$subArr = array_merge($subArr, $partDetail);	
			$data['so_other_parts'][] = $subArr;
		}

		$this->template($data);
	}

	public function update(){
		$soOtherPartsData = $this->groupArray($this->input->post(['id_part', 'qty_on_hand', 'qty_order', 'harga']), $this->input->post(['id_so_other']));
		$this->db->trans_start();
		$this->so_other->update($this->input->post(['tipe_penjualan', 'jenis_pembayaran', 'id_dealer']), $this->input->post(['id_so_other']));
		$this->so_other_parts->update_batch($soOtherPartsData, $this->input->post(['id_so_other']));
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Data berhasil diperbarui.');
			$this->session->set_flashdata('tipe', 'info');
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/$this->page/detail?id={$this->input->post('id_so_other')}'>";
		}else{
			$this->session->set_flashdata('pesan', 'Data not found !');
			$this->session->set_flashdata('tipe', 'danger');
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/$this->page'>";
		}
	}
}