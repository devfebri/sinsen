<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_po_umum extends Honda_Controller
{
	protected $folder = "h3";
	protected $page   = "h3_md_po_umum";
	protected $title  = "PO Umum";

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

		$this->load->model('H3_md_po_umum_model', 'po_umum');
		$this->load->model('H3_md_po_umum_parts_model', 'po_umum_parts');
		$this->load->model('H3_md_po_umum_penanggung_model', 'po_umum_penanggung');
	}

	public function index()
	{
		$data['mode'] = 'index';
		$data['set'] = 'index';

		$this->template($data);
	}

	public function add(){
		$data['mode'] = 'insert';
		$data['set'] = 'form';

		$this->template($data);
	}

	public function proses_faktur(){
		$invoice_sudah_terekap = $this->db
        ->select('riai.invoice_number')
		->from('tr_h3_rekap_invoice_ahm_items as riai')
		->join('tr_h3_rekap_invoice_ahm as ria', 'ria.id_rekap_invoice = riai.id_rekap_invoice')
		->where('ria.tgl_jatuh_tempo', $this->input->get('tgl_jatuh_tempo'))
        ->get_compiled_select();

		$invoice_belum_terekap = $this->db
		->select('date_format(fdo.invoice_date, "%d/%m/%Y") as invoice_date')
		->select('fdo.invoice_number')
		->select("
			case
				when fdo.dpp_due_date = '{$this->input->get('tgl_jatuh_tempo')}' then date_format(fdo.dpp_due_date, '%d/%m/%Y')
				else '-'
			end as dpp_due_date_formatted
		", false)
		->select('fdo.dpp_due_date')
		->select("
			case
				when fdo.ppn_due_date = '{$this->input->get('tgl_jatuh_tempo')}' then date_format(fdo.ppn_due_date, '%d/%m/%Y')
				else '-'
			end as ppn_due_date_formatted
		", false)
		->select('fdo.ppn_due_date')
		->select("
			case
				when fdo.dpp_due_date = '{$this->input->get('tgl_jatuh_tempo')}' then fdo.total_dpp
				else 0
			end as total_dpp
		", false)
		->select("
			case
				when fdo.ppn_due_date = '{$this->input->get('tgl_jatuh_tempo')}' then fdo.total_ppn
				else 0
			end as total_ppn
		", false)
		->select('"" as no_giro')
		->select('0 as amount_giro')
		->from('tr_h3_md_fdo as fdo')
		->group_start()
		->where('fdo.ppn_due_date', $this->input->get('tgl_jatuh_tempo'))
		->or_where('fdo.dpp_due_date', $this->input->get('tgl_jatuh_tempo'))
		->group_end()
		->where("fdo.invoice_number not in ({$invoice_sudah_terekap})")
		->where('fdo.status', 'Approved')
		->get()->result_array()
		;
		
		send_json($invoice_belum_terekap);
	}

	public function save(){
		$this->db->trans_start();
		$this->validate();

		$purchase = $this->input->post([
			'id_vendor', 'divisi', 'dibuat_oleh', 'diketahui_oleh',
			'disetujui_oleh', 'dana_talangan', 'grand_total', 'keterangan'
		]);
		$purchase['id_purchase_order'] = $this->po_umum->generate_nomor();

		$parts = $this->getOnly([
			'nama_barang', 'kuantitas', 'harga', 'sub_total',
		], $this->input->post('parts'), [
			'id_purchase_order' => $purchase['id_purchase_order']
		]);

		$penanggung = $this->getOnly([
			'id_dealer', 'nama_penanggung', 'dana_yang_ditanggung'
		], $this->input->post('penanggung'), [
			'id_purchase_order' => $purchase['id_purchase_order']
		]);

		
		if(count($parts) > 0){
			$this->po_umum->insert($purchase);
			$this->po_umum_parts->insert_batch($parts);
			$this->po_umum_penanggung->insert_batch($penanggung);
		}else{
			log_message('info', 'Gagal membuat PO umum : Part tidak ada.');
			send_json([
				'message' => 'Gagal membuat PO umum : Part tidak ada.'
			], 422);
		}

		$this->db->trans_complete();

		if($this->db->trans_status()){
			$po_umum = $this->po_umum->find($purchase['id_purchase_order'], 'id_purchase_order');
			send_json($po_umum);
		}else{
			log_message('info', 'Gagal membuat PO umum');
			send_json([
				'message' => 'Gagal membuat PO umum'
			], 422);
		}
	}

	public function detail()
	{
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['purchase'] = $this->db
		->from('tr_h3_md_po_umum as pu')
		->where('pu.id_purchase_order', $this->input->get('id_purchase_order'))
		->get()->row_array();

		$data['parts'] = $this->db
		->from('tr_h3_md_po_umum_parts as pup')
		->where('pup.id_purchase_order', $this->input->get('id_purchase_order'))
		->get()->result_array();

		$data['penanggung'] = $this->db
		->from('tr_h3_md_po_umum_penanggung as penanggung')
		->where('penanggung.id_purchase_order', $this->input->get('id_purchase_order'))
		->get()->result_array();

		$this->template($data);
	}

	public function edit()
	{
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['purchase'] = $this->db
		->from('tr_h3_md_po_umum as pu')
		->where('pu.id_purchase_order', $this->input->get('id_purchase_order'))
		->get()->row_array();

		$data['parts'] = $this->db
		->from('tr_h3_md_po_umum_parts as pup')
		->where('pup.id_purchase_order', $this->input->get('id_purchase_order'))
		->get()->result_array();

		$data['penanggung'] = $this->db
		->from('tr_h3_md_po_umum_penanggung as penanggung')
		->where('penanggung.id_purchase_order', $this->input->get('id_purchase_order'))
		->get()->result_array();

		$this->template($data);
	}

	public function update(){
		$this->db->trans_start();
		$this->validate();

		$purchase = $this->input->post([
			'id_vendor', 'divisi', 'dibuat_oleh', 'diketahui_oleh',
			'disetujui_oleh', 'dana_talangan', 'grand_total', 'keterangan'
		]);

		$parts = $this->getOnly([
			'nama_barang', 'kuantitas', 'harga', 'sub_total',
		], $this->input->post('parts'), $this->input->post(['id_purchase_order']));

		$penanggung = $this->getOnly([
			'id_dealer', 'nama_penanggung', 'dana_yang_ditanggung'
		], $this->input->post('penanggung'), $this->input->post(['id_purchase_order']));

		
		$this->po_umum_parts->delete($this->input->post('id_purchase_order'), 'id_purchase_order');
		$this->po_umum_penanggung->delete($this->input->post('id_purchase_order'), 'id_purchase_order');
		if(count($parts) > 0){
			$this->po_umum->update($purchase, $this->input->post(['id_purchase_order']));
			$this->po_umum_parts->insert_batch($parts);
			$this->po_umum_penanggung->insert_batch($penanggung);
		}else{
			log_message('info', 'Gagal memperbarui PO umum : Part tidak ada.');
			send_json([
				'message' => 'Gagal memperbarui PO umum : Part tidak ada.'
			], 422);
		}

		$this->db->trans_complete();

		if($this->db->trans_status()){
			$po_umum = $this->po_umum->find($this->input->post('id_purchase_order'), 'id_purchase_order');
			send_json($po_umum);
		}else{
			log_message('info', 'Gagal memperbarui PO umum');
			send_json([
				'message' => 'Gagal memperbarui PO umum'
			], 422);
		}
	}

	public function approve(){
		$this->db->trans_start();
		$this->po_umum->update([
			'status' => 'Approved',
			'approved_at' => date('Y-m-d H:i:s', time()),
			'approved_by' => $this->session->userdata('id_user'),
		], $this->input->get(['id_purchase_order']));
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$this->session->set_userdata('pesan', 'Berhasil approve PO umum ' . $this->input->get('id_purchase_order'));
			$this->session->set_userdata('tipe', 'success');
			$purchase = $this->po_umum->find($this->input->get('id_purchase_order'), 'id_purchase_order');
			send_json($purchase);
		}else{
			send_json([
				'message' => 'Gagal Approve PO'
			], 422);
		}
	}

	public function cancel(){
		$this->db->trans_start();
		$this->po_umum->update([
			'status' => 'Canceled',
			'canceled_at' => date('Y-m-d H:i:s', time()),
			'canceled_by' => $this->session->userdata('id_user'),
		], $this->input->get(['id_purchase_order']));
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$this->session->set_userdata('pesan', 'Berhasil cancel PO umum ' . $this->input->get('id_purchase_order'));
			$this->session->set_userdata('tipe', 'success');
			$purchase = $this->po_umum->find($this->input->get('id_purchase_order'), 'id_purchase_order');
			send_json($purchase);
		}else{
			send_json([
				'message' => 'Gagal Cancel PO'
			], 422);
		}
	}

	public function validate(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id_vendor', 'Vendor', 'required');
		$this->form_validation->set_rules('divisi', 'Divisi', 'required');
		$this->form_validation->set_rules('dibuat_oleh', 'Dibuat Oleh', 'required');
		$this->form_validation->set_rules('diketahui_oleh', 'Diketahui Oleh', 'required');
		$this->form_validation->set_rules('disetujui_oleh', 'Disetujui Oleh', 'required');
		$this->form_validation->set_rules('dana_talangan', 'Dana Talangan', 'required');

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
