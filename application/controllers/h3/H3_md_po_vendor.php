<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_po_vendor extends Honda_Controller {
	protected $folder = "h3";
    protected $page   = "h3_md_po_vendor";
    protected $title  = "PO Vendor";

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

		$this->load->model('h3_md_po_vendor_model', 'po_vendor');
		$this->load->model('h3_md_po_vendor_parts_model', 'po_vendor_parts');
		$this->load->model('part_model', 'master_part');
		$this->load->model('dealer_model', 'dealer');
		$this->load->model('vendor_model', 'vendor');
		$this->load->model('H3_md_stock_model', 'stock');
	}

	public function index(){
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$this->template($data);
	}

	public function add(){
		$data['mode']    = 'insert';
		$data['set']     = "form";
		$this->template($data);
	}

	public function save(){
		$this->validate();
		$po_vendor = array_merge($this->input->post(['keterangan', 'id_vendor', 'total']), [
			'id_po_vendor' => $this->po_vendor->generateID(),
			'tanggal' => date('Y-m-d', time())
		]);

		$parts = $this->getOnly([
			'id_part', 'qty_order', 
			'qty_on_hand', 'qty_avg_sales', 
			'harga'
		], $this->input->post('parts'), [
			'id_po_vendor' => $po_vendor['id_po_vendor']
		]);
		$this->db->trans_start();
		$this->po_vendor->insert($po_vendor);
		$this->po_vendor_parts->insert_batch($parts);
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'PO vendor berhasil dibuat.');
			$this->session->set_flashdata('tipe', 'info');
			send_json($this->po_vendor->find($po_vendor['id_po_vendor'], 'id_po_vendor'));
		}else{
			$this->session->set_flashdata('pesan', 'PO vendor tidak berhasil dibuat.');
			$this->session->set_flashdata('tipe', 'danger');
			$this->output->set_status_header(500);
		}
	}

	public function detail(){
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['po_vendor'] = $this->db
		->select('pov.*')
		->select('date_format(pov.tanggal, "%d/%m/%Y") tanggal')
		->select('v.vendor_name')
		->from('tr_h3_md_po_vendor as pov')
		->join('ms_vendor as v', 'v.id_vendor = pov.id_vendor')
		->where('pov.id_po_vendor', $this->input->get('id_po_vendor'))
		->get()->row();

		$parts = $this->db
		->select('povp.*')
		->select('p.nama_part')
		->from('tr_h3_md_po_vendor_parts as povp')
		->join('ms_part as p', 'p.id_part = povp.id_part')
		->where('povp.id_po_vendor', $this->input->get('id_po_vendor'))
		->get()->result_array();

		$parts = array_map(function($part){
			$part['qty_on_hand'] = $this->stock->qty_on_hand($part['id_part']);
			return $part;
		}, $parts);

		$data['parts'] = $parts;

		$this->template($data);
	}

	public function edit(){
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['po_vendor'] = $this->db
		->select('pov.*')
		->select('date_format(pov.tanggal, "%d/%m/%Y") tanggal')
		->select('v.vendor_name')
		->from('tr_h3_md_po_vendor as pov')
		->join('ms_vendor as v', 'v.id_vendor = pov.id_vendor')
		->where('pov.id_po_vendor', $this->input->get('id_po_vendor'))
		->get()->row();

		$parts = $this->db
		->select('povp.*')
		->select('p.nama_part')
		->from('tr_h3_md_po_vendor_parts as povp')
		->join('ms_part as p', 'p.id_part = povp.id_part')
		->where('povp.id_po_vendor', $this->input->get('id_po_vendor'))
		->get()->result_array();

		$parts = array_map(function($part){
			$part['qty_on_hand'] = $this->stock->qty_on_hand($part['id_part']);
			return $part;
		}, $parts);

		$data['parts'] = $parts;

		$this->template($data);
	}

	public function update(){
		$this->validate();
		$po_vendor = $this->input->post(['keterangan', 'id_vendor', 'total']);

		$parts = $this->getOnly([
			'id_part', 'qty_order', 
			'qty_on_hand', 'qty_avg_sales', 
			'harga'
		], $this->input->post('parts'), $this->input->post(['id_po_vendor']));
		$this->db->trans_start();
		$this->po_vendor->update($po_vendor, $this->input->post(['id_po_vendor']));
		$this->po_vendor_parts->update_batch($parts, $this->input->post(['id_po_vendor']));
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'PO vendor berhasil diupdate.');
			$this->session->set_flashdata('tipe', 'info');
			send_json($this->po_vendor->get($this->input->post(['id_po_vendor']), true));
		}else{
			$this->session->set_flashdata('pesan', 'PO vendor tidak berhasil diupdate.');
			$this->session->set_flashdata('tipe', 'danger');
			$this->output->set_status_header(500);
		}
	}

	public function close(){
		$this->db->trans_start();
		$this->po_vendor->update([
			'status' => 'Closed',
			'closed_at' => date('Y-m-d H:i:s', time()),
			'closed_by' => $this->session->userdata('id_user')
		], $this->input->get(['id_po_vendor']));
		$this->db->trans_complete();

		if($this->db->trans_status()){
			send_json(
				$this->po_vendor->get($this->input->get(['id_po_vendor']), true)
			);
		}else{
		  	$this->output->set_status_header(500);
		}
	}

	public function proses(){
		$this->db->trans_start();
		$this->po_vendor->update([
			'status' => 'Processed',
			'proses_at' => date('Y-m-d H:i:s', time()),
			'proses_by' => $this->session->userdata('id_user')
		], $this->input->get(['id_po_vendor']));
		$this->db->trans_complete();

		if($this->db->trans_status()){
			send_json(
				$this->po_vendor->get($this->input->get(['id_po_vendor']), true)
			);
		}else{
		  	$this->output->set_status_header(500);
		}
	}

	public function cancel(){
		$this->db->trans_start();
		$this->po_vendor->update([
			'status' => 'Canceled',
			'canceled_at' => date('Y-m-d H:i:s', time()),
			'canceled_by' => $this->session->userdata('id_user')
		], $this->input->get(['id_po_vendor']));
		$this->db->trans_complete();

		if($this->db->trans_status()){
			send_json(
				$this->po_vendor->get($this->input->get(['id_po_vendor']), true)
			);
		}else{
		  	$this->output->set_status_header(500);
		}
	}

	public function close_at_index(){
		$this->db->trans_start();
		$this->po_vendor->update([
			'status' => 'Closed',
			'closed_at' => date('Y-m-d H:i:s', time()),
			'closed_by' => $this->session->userdata('id_user')
		], $this->input->get(['id_po_vendor']));
		$this->db->trans_complete();

		if($this->db->trans_status()){
			redirect(
				base_url('h3/h3_md_po_vendor')
			);
		}else{
		  	$this->output->set_status_header(500);
		}
	}

	public function cetak(){
		$data = [];
		$data['po_vendor'] = $this->db
		->select('pov.*')
		->select('date_format(pov.tanggal, "%d/%m/%Y") tanggal')
		->select('v.vendor_name')
		->select('
			concat(
				"Rp ",
				format(pov.total, 0, "ID_id")
			) as total_formatted
		', false)
		->from('tr_h3_md_po_vendor as pov')
		->join('ms_vendor as v', 'v.id_vendor = pov.id_vendor')
		->where('pov.id_po_vendor', $this->input->get('id_po_vendor'))
		->get()->row_array();

		$data['parts'] = $this->db
		->select('povp.*')
		->select('p.nama_part')
		->select('
			concat(
				"Rp ",
				format(povp.harga, 0, "ID_id")
			) as harga_formatted
		', false)
		->from('tr_h3_md_po_vendor_parts as povp')
		->join('ms_part as p', 'p.id_part = povp.id_part')
		->where('povp.id_po_vendor', $this->input->get('id_po_vendor'))
		->get()->result_array();

        // $this->load->library('mpdf_l');
        require_once APPPATH .'third_party/mpdf/mpdf.php';
        // Require composer autoload
        $mpdf = new Mpdf();
        // Write some HTML code:
        $html = $this->load->view('h3/h3_md_cetakan_po_vendor', $data, true);
        $mpdf->WriteHTML($html);

        // Output a PDF file directly to the browser
        $mpdf->Output("PO Vendor.pdf", "I");
	}

	public function validate(){
        $this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id_vendor', 'Vendor', 'required');
		// $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');

        if (!$this->form_validation->run())
        {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
		}
	}

	public function generate_ap(){
		$this->db
		->select('pv.id_po_vendor')
		->from('tr_h3_md_po_vendor as pv')
		->join('tr_h3_md_ap_part as ap', '(ap.referensi = pv.id_po_vendor and ap.jenis_transaksi = "purchase_order_vendor")', 'left')
		->where('pv.status', 'Closed')
		->where('ap.id', null)
		;

		foreach ($this->db->get()->result_array() as $row) {
			$this->po_vendor->create_ap($row['id_po_vendor']);
		}
	}
}