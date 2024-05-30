<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_receive_part_request_nrfs extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_receive_part_request_nrfs";
    protected $title  = "Receive Part Request NRFS";

	public function __construct(){		
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

		$this->load->model('h3_md_claim_dealer_model', 'claim_dealer');
		$this->load->model('h3_md_claim_dealer_parts_model', 'claim_dealer_parts');
	}

	public function index(){
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$this->template($data);
	}

	public function detail(){
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['part_request'] = $this->db
        ->select('prn.request_id')
        ->select('prn.dokumen_nrfs_id')
        ->select('dn.no_shiping_list')
        ->select('dn.no_mesin')
        ->select('dn.no_rangka')
        ->select('dn.type_code')
        ->select('dn.deskripsi_unit')
        ->select('dn.deskripsi_warna')
        ->select('dn.sumber_rfs_nrfs')
        ->select('prn.status_request')
        ->from('tr_part_request_nrfs as prn')
        ->join('tr_dokumen_nrfs as dn', 'dn.dokumen_nrfs_id = prn.dokumen_nrfs_id', 'left')
        ->where('prn.request_id', $this->input->get('request_id'))
		->get()->row_array();

		$data['parts'] = $this->db
		->select('dnp.id_part')
		->select('p.nama_part')
		->select('dnp.qty_part')
        ->from('tr_part_request_nrfs as prn')
		->join('tr_dokumen_nrfs_part as dnp', 'dnp.dokumen_nrfs_id = prn.dokumen_nrfs_id')
		->join('ms_part as p', 'p.id_part = dnp.id_part')
		->where('prn.request_id', $this->input->get('request_id'))
		->get()->result();

		$this->template($data);
	}

	public function approve(){
		$this->db->trans_start();
		$this->db
		->set('prn.status_request', 'approved')
		->where('prn.request_id', $this->input->post('request_id'))
		->update('tr_part_request_nrfs as prn');
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Part Request berhasil diapprove.');
			$this->session->set_flashdata('tipe', 'info');

			$dokumen_nrfs = $this->db
			->from('tr_part_request_nrfs as prn')
			->where('prn.request_id', $this->input->post('request_id'))
			->get()->row_array();
			send_json($dokumen_nrfs);
		}else{
			$this->session->set_flashdata('pesan', 'Part Request tidak berhasil diapprove.');
			$this->session->set_flashdata('tipe', 'danger');
			$this->output->set_status_header(500);
		}
	}

	public function reject(){
		$this->db->trans_start();
		$this->db
		->set('prn.status_request', 'rejected')
		// ->set('prn.rejected_message', $this->input->post('message'))
		->where('prn.request_id', $this->input->post('request_id'))
		->update('tr_part_request_nrfs as prn');
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Part Request berhasil direject.');
			$this->session->set_flashdata('tipe', 'info');

			$dokumen_nrfs = $this->db
			->from('tr_part_request_nrfs as prn')
			->where('prn.request_id', $this->input->post('request_id'))
			->get()->row_array();
			send_json($dokumen_nrfs);
		}else{
			$this->session->set_flashdata('pesan', 'Part Request tidak berhasil direject.');
			$this->session->set_flashdata('tipe', 'danger');
			$this->output->set_status_header(500);
		}
	}

	public function validate(){
        $this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id_dealer', 'Customer', 'required');
		$this->form_validation->set_rules('id_packing_sheet', 'Packing Sheet', 'required');

        if (!$this->form_validation->run()){
            $this->output->set_status_header(400);
            $data = $this->form_validation->error_array();
            send_json($data);
        }
    }
}