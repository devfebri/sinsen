<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_create_do_other extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_create_do_other";
    protected $title  = "Create DO Other";

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
		$this->load->model('part_model', 'master_part');
		$this->load->model('dealer_model', 'dealer');
	}

	public function index(){
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$do_other_ids = $this->db->select('id_so_other')->from('tr_h3_md_do_other')->where('status !=', 'Rejected')->get_compiled_select();
		$data['so_other'] = $this->db->select('*')
									->from('tr_h3_md_so_other as soo')
									->where("soo.id_so_other NOT IN ({$do_other_ids})")
									->get()
									->result();
		$this->template($data);
	}

	public function detail(){
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['so_other'] = $this->so_other->find($this->input->get('id'), 'id_so_other');
		$data['dealer'] = $this->dealer->find($data['so_other']->id_dealer, 'id_dealer');
		$so_other_parts = $this->so_other_parts->get([
			'id_so_other' => $this->input->get('id')
		]);

		foreach ($so_other_parts as $each) {
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
}