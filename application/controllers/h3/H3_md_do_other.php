<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_do_other extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_do_other";
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
		$this->load->model('h3_md_do_other_model', 'do_other');
		$this->load->model('h3_md_do_other_parts_model', 'do_other_parts');
		$this->load->model('h3_md_picking_list_model', 'picking_list');
		$this->load->model('h3_md_picking_list_parts_model', 'picking_list_parts');
		$this->load->model('part_model', 'master_part');
		$this->load->model('dealer_model', 'dealer');
	}

	public function index(){
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$data['do_other'] = $this->do_other->all();
		$this->template($data);
	}

	public function detail(){
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['do_other'] = $this->do_other->find($this->input->get('id'), 'id_do_other');
		$data['so_other'] = $this->so_other->find($data['do_other']->id_so_other, 'id_so_other');
		$data['dealer'] = $this->dealer->find($data['so_other']->id_dealer, 'id_dealer');
		$do_other_parts = $this->do_other_parts->get([
			'id_do_other' => $this->input->get('id')
		]);

		foreach ($do_other_parts as $each) {
			$subArr = (array) $each;
			$subArr['qty_suply'] = $each->qty_order;
			$partDetail = (array) $this->master_part->find($each->id_part, 'id_part');
			$subArr = array_merge($subArr, $partDetail);	
			$data['so_other_parts'][] = $subArr;
		}

		$this->template($data);
	}

	public function create(){
		$doOtherData = array_merge($this->input->post(['id_so_other']), [
			'id_do_other' => $this->do_other->generateID(),
			'tanggal' => date('Y-m-d', time())
		]);

		$doOtherPartsData = $this->groupArray($this->input->post(['id_part', 'qty_on_hand', 'qty_order', 'qty_suply', 'harga']), [
			'id_do_other' => $doOtherData['id_do_other']
		]);

		$this->db->trans_start();
		$this->do_other->insert($doOtherData);
		$this->do_other_parts->insert_batch($doOtherPartsData);
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Data berhasil diperbarui.');
			$this->session->set_flashdata('tipe', 'info');
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/$this->page'>";
		}else{
			$this->session->set_flashdata('pesan', 'Data not found !');
			$this->session->set_flashdata('tipe', 'danger');
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/$this->page'>";
		}
	}

	public function approve(){
		$this->db->trans_start();
		$this->do_other->update(['status' => 'Approved'], $this->input->get(['id_do_other']));

		// Buat Picking List
		$do_other = $this->do_other->get($this->input->get(['id_do_other']), true);
		$do_other_parts = $this->do_other_parts->get($this->input->get(['id_do_other']));

		$picking_list_data = [
			'id_picking_list' => $this->picking_list->generateID(),
			'id_ref' => $do_other->id_do_other,
			'tipe_ref' => 'do_other',
			'tanggal' => date('Y-m-d', time()),
		];

		$picking_list_parts_data = [];
		foreach ($do_other_parts as $each) {
			$subArr = [];
			$subArr['id_picking_list'] = $picking_list_data['id_picking_list'];
			$subArr['id_part'] = $each->id_part;
			$subArr['qty_on_hand'] = $each->qty_on_hand;
			$subArr['qty_order'] = $each->qty_order;
			$subArr['qty_suply'] = $each->qty_suply;
			$subArr['harga'] = $each->harga;
			$picking_list_parts_data[] = $subArr;
		}

		$this->picking_list->insert($picking_list_data);
		$this->picking_list_parts->insert_batch($picking_list_parts_data);

		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Data berhasil diperbarui.');
			$this->session->set_flashdata('tipe', 'info');
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/$this->page/detail?id={$this->input->get('id_do_other')}'>";
		}else{
			$this->session->set_flashdata('pesan', 'Data not found !');
			$this->session->set_flashdata('tipe', 'danger');
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/$this->page'>";
		}
	}

	public function reject(){
		$this->db->trans_start();
		$doOtherData = array_merge($this->input->post(['alasan_reject']), [
			'status' => 'Rejected'
		]);
		$this->do_other->update($doOtherData, $this->input->post(['id_do_other']));
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$this->output->set_status_header(200);
		}else{
			$this->output->set_status_header(400);
		}
	}
}