<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_purchase_hotline extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_purchase_hotline";
    protected $title  = "Purchase Hotline";

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

		$this->load->model('h3_dealer_purchase_order_model', 'purchase_order');
		$this->load->model('h3_dealer_purchase_order_parts_model', 'purchase_order_parts');
		$this->load->model('h3_md_purchase_hotline_model', 'purchase_hotline');
		$this->load->model('h3_md_purchase_hotline_parts_model', 'purchase_hotline_parts');
		$this->load->model('part_model', 'master_part');
		$this->load->model('dealer_model', 'dealer');
	}

	public function index(){
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$data['purchase_hotline'] = $this->purchase_hotline->all();
		$this->template($data);
	}

	public function detail(){
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['purchase_order'] = $this->purchase_order->find($this->input->get('id'), 'po_id');
		$purchase_order_parts = $this->purchase_order_parts->get([
			'po_id' => $this->input->get('id')
		]);

		foreach ($purchase_order_parts as $each) {
			$subArr = (array) $each;
			$partDetail = (array) $this->master_part->find($each->id_part, 'id_part');
			$subArr = array_merge($subArr, $partDetail);	
			$data['purchase_order_parts'][] = $subArr;
		}
		$this->template($data);
	}

	public function create_po_hotline(){
		die();
		$purchase_order = $this->purchase_order->find($this->input->get('id'), 'po_id');
		$purchase_order_parts = $this->purchase_order_parts->get([
			'po_id' => $this->input->get('id')
		]);

		$purchaseHotline = [
			'id_purchase_hotline' => $this->purchase_hotline->generateID(),
			'id_ref' => $purchase_order->po_id,
			'tipe_ref' => 'purchase_hotline_dealer'
		];

		$purchaseHotlineParts = [];
		foreach ($purchase_order_parts as $each) {
			$subArr = [];
			$subArr['id_purchase_hotline'] = $purchaseHotline['id_purchase_hotline'];
			$subArr['id_part'] = $each->id_part;
			$subArr['kuantitas'] = $each->kuantitas;
			$subArr['harga_saat_dibeli'] = $each->harga_saat_dibeli;
			$purchaseHotlineParts[] = $subArr;
		}

		$this->db->trans_start();
		$this->purchase_hotline->insert($purchaseHotline);
		$this->purchase_hotline_parts->insert_batch($purchaseHotlineParts);
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Data berhasil diperbarui.');
			$this->session->set_flashdata('tipe', 'info');
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/$this->page/detail?id={$purchaseHotline['id_purchase_hotline']}'>";
		}else{
			$this->session->set_flashdata('pesan', 'Data not found !');
			$this->session->set_flashdata('tipe', 'danger');
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/$this->page'>";
		}
	}
}