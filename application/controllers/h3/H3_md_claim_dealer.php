<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_claim_dealer extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_claim_dealer";
    protected $title  = "Claim dari Dealer";

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
		$data['claim_dealer'] = $this->claim_dealer->all();
		$this->template($data);
	}

	public function add(){
		$data['mode']    = 'insert';
		$data['set']     = "form";
		$this->template($data);
	}

	public function save(){
		$this->validate();
		$data = array_merge($this->input->post([
			'id_dealer', 'id_packing_sheet', 'dokumen_packing_sheet', 'dokumen_packing_ticket',
			'dokumen_foto_bukti', 'dokumen_shipping_list', 'dokumen_nomor_karton', 'dokumen_tutup_botol', 
			'dokumen_label_timbangan', 'dokumen_label_karton', 'dokumen_lain'
		]), [
			'tanggal' => date('Y-m-d', time()),
			'id_claim_dealer' => $this->claim_dealer->generateID($this->input->post('id_dealer'))
		]);

		$claim_dealer = [];
		foreach ($data as $key => $value) {
			if($data[$key] != ''){
				$claim_dealer[$key] = $value;
			}
		}
		
		$parts = $this->getOnly(true, $this->input->post('parts'), [
			'id_claim_dealer' => $claim_dealer['id_claim_dealer']
		]);
		$this->db->trans_start();
		$this->claim_dealer->insert($claim_dealer);
		$this->claim_dealer_parts->insert_batch($parts);
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Claim dealer berhasil dibuat.');
			$this->session->set_flashdata('tipe', 'info');
			$claim_dealer = $this->claim_dealer->find($claim_dealer['id_claim_dealer'], 'id_claim_dealer');
			send_json($claim_dealer);
		}else{
			$this->session->set_flashdata('pesan', 'Claim dealer tidak berhasil dibuat.');
			$this->session->set_flashdata('tipe', 'danger');
			send_json([
				'message' => 'Claim dealer tidak berhasil dibuat.'
			], 422);
		}
	}

	public function detail(){
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['claim_dealer'] = $this->db
		->select('cd.*')
		->select('date_format(cd.tanggal, "%d-%m-%Y") as tanggal')
		->select('date_format(ps.tgl_packing_sheet, "%d-%m-%Y") as tgl_packing_sheet')
		->select('date_format(ps.tgl_faktur, "%d-%m-%Y") as tgl_faktur')
		->select('ps.no_faktur')
		->select('d.nama_dealer')
		->select('d.alamat')
		->from('tr_h3_md_claim_dealer as cd')
		->join('tr_h3_md_packing_sheet as ps', 'ps.id_packing_sheet = cd.id_packing_sheet')
		->join('ms_dealer as d', 'd.id_dealer = cd.id_dealer')
		->where('cd.id_claim_dealer', $this->input->get('id_claim_dealer'))
		->get()->row();

		$data['parts'] = $this->db
		->select('cdp.*')
		->select('kc.kode_claim')
		->select('kc.nama_claim')
		->select('p.nama_part')
		->from('tr_h3_md_claim_dealer_parts as cdp')
		->join('ms_kategori_claim_c3 as kc', 'kc.id = cdp.id_kategori_claim_c3')
		->join('ms_part as p', 'p.id_part = cdp.id_part')
		->where('cdp.id_claim_dealer', $this->input->get('id_claim_dealer'))
		->get()->result();

		$this->template($data);
	}

	public function edit(){
		$data['mode']    = 'edit';
		$data['set']     = "form";

		$data['claim_dealer'] = $this->db
		->select('cd.*')
		->select('date_format(cd.tanggal, "%d-%m-%Y") as tanggal')
		->select('date_format(ps.tgl_packing_sheet, "%d-%m-%Y") as tgl_packing_sheet')
		->select('date_format(ps.tgl_faktur, "%d-%m-%Y") as tgl_faktur')
		->select('ps.no_faktur')
		->select('d.nama_dealer')
		->select('d.alamat')
		->from('tr_h3_md_claim_dealer as cd')
		->join('tr_h3_md_packing_sheet as ps', 'ps.id_packing_sheet = cd.id_packing_sheet')
		->join('ms_dealer as d', 'd.id_dealer = cd.id_dealer')
		->where('cd.id_claim_dealer', $this->input->get('id_claim_dealer'))
		->get()->row();

		$data['parts'] = $this->db
		->select('cdp.*')
		->select('kc.kode_claim')
		->select('kc.nama_claim')
		->select('p.nama_part')
		->from('tr_h3_md_claim_dealer_parts as cdp')
		->join('ms_kategori_claim_c3 as kc', 'kc.id = cdp.id_kategori_claim_c3')
		->join('ms_part as p', 'p.id_part = cdp.id_part')
		->where('cdp.id_claim_dealer', $this->input->get('id_claim_dealer'))
		->get()->result();

		$this->template($data);
	}

	public function update(){
		$this->validate();
		$this->db->trans_start();

		$claim_dealer = $this->input->post([
			'id_dealer', 'id_packing_sheet', 'dokumen_packing_sheet', 'dokumen_packing_ticket',
			'dokumen_foto_bukti', 'dokumen_shipping_list', 'dokumen_nomor_karton', 'dokumen_tutup_botol', 
			'dokumen_label_timbangan', 'dokumen_label_karton', 'dokumen_lain'
		]);
		$claim_dealer['status'] = 'Open';
		$parts = $this->getOnly(true, $this->input->post('parts'), $this->input->post(['id_claim_dealer']));

		$this->claim_dealer->update($claim_dealer, $this->input->post(['id_claim_dealer']));
		$this->claim_dealer_parts->update_batch($parts, $this->input->post(['id_claim_dealer']));

		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Claim dealer berhasil diupdate.');
			$this->session->set_flashdata('tipe', 'info');
			$claim_dealer = $this->claim_dealer->get($this->input->post(['id_claim_dealer']), true);
			send_json($claim_dealer);
		}else{
			$this->session->set_flashdata('pesan', 'Claim dealer tidak berhasil diupdate.');
			$this->session->set_flashdata('tipe', 'danger');
			send_json(['message' => 'Claim dealer tidak berhasil diupdate.'], 422);
		}
	}

	public function approve(){
		$this->db->trans_start();
		$this->claim_dealer->update([
			'status' => 'Approved',
			'approved_at' => date('Y-m-d H:i:s', time()),
			'approved_by' => $this->session->userdata('id_user')
		], $this->input->post(['id_claim_dealer']));
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Claim dealer berhasil diapprove.');
			$this->session->set_flashdata('tipe', 'info');
			$claim_dealer = $this->claim_dealer->get($this->input->post(['id_claim_dealer']), true);
			send_json($claim_dealer);
		}else{
			$this->session->set_flashdata('pesan', 'Claim dealer tidak berhasil diapprove.');
			$this->session->set_flashdata('tipe', 'danger');
			send_json(['message' => 'Claim dealer tidak berhasil diapprove.'], 422);
		}
	}

	public function reject(){
		$this->db->trans_start();
		$this->claim_dealer->update([
			'status' => 'Rejected',
			'rejected_message' => $this->input->post('message'),
			'rejected_at' => date('Y-m-d H:i:s', time()),
			'rejected_by' => $this->session->userdata('id_user')
		], $this->input->post(['id_claim_dealer']));
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Claim dealer berhasil direject.');
			$this->session->set_flashdata('tipe', 'info');
			$claim_dealer = $this->claim_dealer->get($this->input->post(['id_claim_dealer']), true);
			send_json($claim_dealer);
		}else{
			$this->session->set_flashdata('pesan', 'Claim dealer tidak berhasil direject.');
			$this->session->set_flashdata('tipe', 'danger');
			send_json([
				'message' => 'Claim dealer tidak berhasil direject.',
			], 422);
		}
	}

	public function recheck(){
		$this->db->trans_start();
		$this->claim_dealer->update([
			'status' => 'Recheck',
			'rechecked_message' => $this->input->post('message'),
			'rechecked_at' => date('Y-m-d H:i:s', time()),
			'rechecked_by' => $this->session->userdata('id_user')
		], $this->input->post(['id_claim_dealer']));
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Claim dealer berhasil direcheck.');
			$this->session->set_flashdata('tipe', 'info');
			$claim_dealer = $this->claim_dealer->get($this->input->post(['id_claim_dealer']), true);
			send_json($claim_dealer);
		}else{
			$this->session->set_flashdata('pesan', 'Claim dealer tidak berhasil direcheck.');
			$this->session->set_flashdata('tipe', 'danger');
			send_json([
				'message' => 'Claim dealer tidak berhasil direcheck.'
			], 422);
		}
	}

	public function cancel(){
		$this->db->trans_start();
		$this->claim_dealer->update([
			'status' => 'Canceled',
			'canceled_at' => date('Y-m-d H:i:s', time()),
			'canceled_by' => $this->session->userdata('id_user')
		], $this->input->get(['id_claim_dealer']));
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Claim dealer berhasil dicancel.');
			$this->session->set_flashdata('tipe', 'info');
			$claim_dealer = $this->claim_dealer->get($this->input->get(['id_claim_dealer']), true);
			send_json($claim_dealer);
		}else{
			$this->session->set_flashdata('pesan', 'Claim dealer tidak berhasil dicancel.');
			$this->session->set_flashdata('tipe', 'danger');
			send_json(['message' => 'Claim dealer tidak berhasil dicancel.'], 422);
		}
	}

	public function validate(){
        $this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id_dealer', 'Customer', 'required');
		$this->form_validation->set_rules('id_packing_sheet', 'Packing Sheet', 'required');

        if (!$this->form_validation->run()){
            $data = $this->form_validation->error_array();
            send_json($data, 422);
        }
    }
}