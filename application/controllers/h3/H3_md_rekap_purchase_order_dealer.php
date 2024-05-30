<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_rekap_purchase_order_dealer extends Honda_Controller
{
	protected $folder = "h3";
	protected $page   = "h3_md_rekap_purchase_order_dealer";
	protected $title  = "Rekap Purchase Order";

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
		$auth = $this->m_admin->user_auth($this->page, "select");
		$sess = $this->m_admin->sess_auth();
		if ($name == "" OR $auth == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
		} elseif ($sess == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "crash'>";
		}

		$this->load->model('H3_md_rekap_purchase_order_dealer_model', 'rekap_purchase_order_dealer');
		$this->load->model('H3_md_rekap_purchase_order_dealer_item_model', 'rekap_purchase_order_dealer_item');
		$this->load->model('H3_md_rekap_purchase_order_dealer_parts_model', 'rekap_purchase_order_dealer_parts');
	}

	public function index()
	{
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$this->template($data);
	}

	public function add()
	{
		$data['mode'] = 'insert';
		$data['set'] = "form";

		$this->template($data);
	}

	public function save(){
		$this->validate();

		$this->db->trans_start();
		$data = $this->input->post(['id_dealer', 'tipe_po']);
		$this->rekap_purchase_order_dealer->insert($data);
		$id_rekap = $this->db->insert_id();
		$items = $this->getOnly(['id_referensi'], $this->input->post('items'), [
			'id_rekap' => $id_rekap
		]);
		$this->rekap_purchase_order_dealer_item->insert_batch($items);

		$parts = $this->getOnly(['po_id', 'id_part', 'kuantitas'], $this->input->post('parts'), [
			'id_rekap' => $id_rekap
		]);
		$this->rekap_purchase_order_dealer_parts->insert_batch($parts);
		$this->db->trans_complete();

		if($this->db->trans_status()){
			send_json(
				$this->rekap_purchase_order_dealer->find($id_rekap)
			);
		}else{
		  	$this->output->set_status_header(500);
		}
	}

	public function get_parts(){
		$this->db
		->select('pop.po_id')
		->select('pop.id_part')
		->select('p.nama_part')
		->select('ppdd.qty_pemenuhan as kuantitas')
		->from('tr_h3_dealer_purchase_order_parts as pop')
		->join('ms_part as p', 'p.id_part = pop.id_part')
		->join('tr_h3_md_pemenuhan_po_dari_dealer as ppdd', 'ppdd.po_id = pop.po_id and ppdd.id_part = pop.id_part')
		->where('ppdd.qty_pemenuhan >', 0)
		;

		if($this->input->post('items') != null && count($this->input->post('items')) > 0){
			$this->db->where_in('pop.po_id', $this->input->post('items'));
		}else{
			send_json([]);
		}

		send_json($this->db->get()->result_array());
	}

	public function detail()
	{
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['rekap'] = $this->db
		->select('r.id')
		->select('r.id_dealer')
		->select('r.tipe_po')
		->select('d.nama_dealer')
		->select('d.kode_dealer_md')
		->select('so.id_sales_order')
		->from('tr_h3_md_rekap_purchase_order_dealer as r')
		->join('ms_dealer as d', 'd.id_dealer = r.id_dealer')
		->join('tr_h3_md_sales_order as so', '(so.id_rekap_purchase_order_dealer = r.id and so.status != "Canceled")', 'left')
		->where('r.id', $this->input->get('id'))
		->limit(1)
		->get()->row();

		$data['items'] = $this->db
		->select('ri.id_referensi')
		->from('tr_h3_md_rekap_purchase_order_dealer_item as ri')
		->where('ri.id_rekap', $this->input->get('id'))
		->get()->result_array();

		$data['parts'] = $this->db
		->select('rpodp.po_id')
		->select('rpodp.id_part')
		->select('p.nama_part')
		->select('rpodp.kuantitas')
		->from('tr_h3_md_rekap_purchase_order_dealer_parts as rpodp')
		->join('ms_part as p', 'p.id_part = rpodp.id_part')
		->where('rpodp.id_rekap', $this->input->get('id'))
		->get()->result_array();

		$this->template($data);
	}

	public function edit()
	{
		$data['mode']    = 'edit';
		$data['set']     = "form";
		
		$data['rekap'] = $this->db
		->select('r.id')
		->select('r.id_dealer')
		->select('r.tipe_po')
		->select('d.nama_dealer')
		->select('d.kode_dealer_md')
		->select('so.id_sales_order')
		->from('tr_h3_md_rekap_purchase_order_dealer as r')
		->join('ms_dealer as d', 'd.id_dealer = r.id_dealer')
		->join('tr_h3_md_sales_order as so', '(so.id_rekap_purchase_order_dealer = r.id and so.status != "Canceled")', 'left')
		->where('r.id', $this->input->get('id'))
		->limit(1)
		->get()->row();

		$data['items'] = $this->db
		->select('ri.id_referensi')
		->from('tr_h3_md_rekap_purchase_order_dealer_item as ri')
		->where('ri.id_rekap', $this->input->get('id'))
		->get()->result_array();

		$data['parts'] = $this->db
		->select('rpodp.po_id')
		->select('rpodp.id_part')
		->select('p.nama_part')
		->select('rpodp.kuantitas')
		->from('tr_h3_md_rekap_purchase_order_dealer_parts as rpodp')
		->join('ms_part as p', 'p.id_part = rpodp.id_part')
		->where('rpodp.id_rekap', $this->input->get('id'))
		->get()->result_array();


		$this->template($data);
	}

	public function update()
	{
		$this->validate();

		$this->db->trans_start();
		$data = $this->input->post(['id_dealer', 'tipe_po']);
		$this->rekap_purchase_order_dealer->update($data, $this->input->post(['id']));
		$items = $this->getOnly(['id_referensi'], $this->input->post('items'), [
			'id_rekap' => $this->input->post('id')
		]);
		$this->rekap_purchase_order_dealer_item->update_batch($items, [
			'id_rekap' => $this->input->post('id')
		]);
		$parts = $this->getOnly(['po_id', 'id_part', 'kuantitas'], $this->input->post('parts'), [
			'id_rekap' => $this->input->post('id')
		]);
		$this->rekap_purchase_order_dealer_parts->update_batch($parts, [
			'id_rekap' => $this->input->post('id')
		]);
		$this->db->trans_complete();

		if($this->db->trans_status()){
			send_json(
				$this->rekap_purchase_order_dealer->find($this->input->post('id'))
			);
		}else{
		  	$this->output->set_status_header(500);
		}
	}

	public function validate(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id_dealer', 'Dealer', 'required');
		$this->form_validation->set_rules('tipe_po', 'Tipe PO', 'required');

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
