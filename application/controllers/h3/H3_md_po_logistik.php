<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_po_logistik extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_po_logistik";
	protected $title  = "PO Logistik";

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
		$this->load->model('H3_md_po_logistik_model', 'po_logistik');
		$this->load->model('H3_md_po_logistik_parts_model', 'po_logistik_parts');
		$this->load->model('H3_md_po_logistik_parts_detail_model', 'po_logistik_parts_detail');
		$this->load->model('H3_md_stock_model', 'stock');
	}

	public function index(){
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$this->template($data);
	}

	public function detail(){
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['po_logistik'] = $this->db
		->select('pol.id_po_logistik')
		->select('date_format(pol.tanggal, "%d/%m/%Y") as tanggal')
		->select('pol.sudah_create_po_urgent')
		->select('po.id_purchase_order')
		->from('tr_h3_md_po_logistik as pol')
		->join('tr_h3_md_purchase_order as po', 'po.id_po_logistik = pol.id_po_logistik', 'left')
		->where('pol.id_po_logistik', $this->input->get('id_po_logistik'))
		->get()->row_array();

		$qty_book = $this->db
		->select('SUM(sop.qty_order) as qty_order')
		->from('tr_h3_md_sales_order as so')
		->join('tr_h3_md_sales_order_parts as sop', 'so.id_sales_order = sop.id_sales_order')
		->where('so.id_po_logistik', $this->input->get('id_po_logistik'))
		->where('sop.id_part = polp.id_part')
		->get_compiled_select();

		$data['parts'] = $this->db
		->select('polp.id_part')
		->select('p.nama_part')
		->select('polp.qty_part')
		->select('polp.qty_supply')
		->select('polp.qty_po_ahm')
		->select("IFNULL(({$qty_book}), 0) as qty_book")
		->select('polp.harga')
		->from('tr_h3_md_po_logistik_parts as polp')
		->join('ms_part as p', 'p.id_part = polp.id_part')
		->where('polp.id_po_logistik', $this->input->get('id_po_logistik'))
		->get()->result_array();
		$data['parts'] = array_map(function($row){
			$row['qty_onhand'] = $this->stock->qty_on_hand($row['id_part']);
			$row['qty_avs'] = $this->stock->qty_avs($row['id_part']);
			return $row;
		}, $data['parts']);

		$this->template($data);
	}

	public function edit(){
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['po_logistik'] = $this->db
		->select('pol.id_po_logistik')
		->select('date_format(pol.tanggal, "%d/%m/%Y") as tanggal')
		->select('pol.sudah_create_po_urgent')
		->select('po.id_purchase_order')
		->from('tr_h3_md_po_logistik as pol')
		->join('tr_h3_md_purchase_order as po', 'po.id_po_logistik = pol.id_po_logistik', 'left')
		->where('pol.id_po_logistik', $this->input->get('id_po_logistik'))
		->get()->row_array();

		$qty_book = $this->db
		->select('SUM(sop.qty_order) as qty_order')
		->from('tr_h3_md_sales_order as so')
		->join('tr_h3_md_sales_order_parts as sop', 'so.id_sales_order = sop.id_sales_order')
		->where('so.id_po_logistik', $this->input->get('id_po_logistik'))
		->where('sop.id_part = polp.id_part')
		->get_compiled_select();

		$data['parts'] = $this->db
		->select('polp.id_part')
		->select('p.nama_part')
		->select('polp.qty_part')
		->select('polp.qty_supply')
		->select('polp.qty_po_ahm')
		->select("IFNULL(({$qty_book}), 0) as qty_book")
		->select('polp.harga')
		->from('tr_h3_md_po_logistik_parts as polp')
		->join('ms_part as p', 'p.id_part = polp.id_part')
		->where('polp.id_po_logistik', $this->input->get('id_po_logistik'))
		->get()->result_array();
		$data['parts'] = array_map(function($row){
			$row['qty_onhand'] = $this->stock->qty_on_hand($row['id_part']);
			$row['qty_avs'] = $this->stock->qty_avs($row['id_part']);
			return $row;
		}, $data['parts']);

		$this->template($data);
	}

	public function update(){
		$this->validate();
		$parts = $this->getOnly([
			'id_part', 'qty_part', 'harga', 'qty_supply', 'qty_po_ahm'
		], $this->input->post('parts'), $this->input->post(['id_po_logistik']));

		$this->db->trans_start();
		$this->po_logistik_parts->update_batch($parts, $this->input->post(['id_po_logistik']));
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'PO Logistik berhasil diupdate.');
			$this->session->set_flashdata('tipe', 'info');
			send_json(
				$this->po_logistik->find($this->input->post('id_po_logistik'), 'id_po_logistik')
			);
		}else{
			$this->session->set_flashdata('pesan', 'PO Logistik tidak berhasil diupdate.');
			$this->session->set_flashdata('tipe', 'danger');
			$this->output->set_status_header(500);
		}
	}

	public function validate(){
		return;
		
        $this->form_validation->set_error_delimiters('', '');

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