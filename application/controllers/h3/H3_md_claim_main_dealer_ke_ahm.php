<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_claim_main_dealer_ke_ahm extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_claim_main_dealer_ke_ahm";
    protected $title  = "Claim Main Dealer ke AHM";

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

		$this->load->model('h3_md_claim_main_dealer_ke_ahm_model', 'claim_main_dealer_ke_ahm');
		$this->load->model('h3_md_claim_main_dealer_ke_ahm_item_model', 'claim_main_dealer_ke_ahm_item');
		$this->load->model('H3_md_stock_model', 'stock');
		$this->load->model('H3_md_stock_int_model', 'stock_int');
		$this->load->helper('clean_data');
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
		$claim_main_dealer = array_merge($this->input->post([
			'packing_sheet_number', 'packing_sheet_number_int', 'invoice_number', 'invoice_number_int', 'packing_sheet', 'packing_ticket',
			'foto_bukti', 'shipping_list', 'nomor_karton', 'tutup_botol', 
			'label_timbangan', 'label_karton', 'lain_lain'
		]), [
			'id_claim' => $this->claim_main_dealer_ke_ahm->generateID()
		]);
		$claim_main_dealer = clean_data($claim_main_dealer);

		$parts = $this->getOnly([
			'id_part_int', 'id_part', 'no_doos', 'qty_part_diclaim','qty_part_dikirim_ke_ahm',
			'id_kode_claim','id_lokasi_rak', 'keterangan', 'qty_avs', 'no_po', 'no_doos_int'
		], $this->input->post('parts'), [
			'id_claim' => $claim_main_dealer['id_claim']
		]);
		$this->db->trans_start();
		$this->claim_main_dealer_ke_ahm->insert($claim_main_dealer);
		$this->claim_main_dealer_ke_ahm_item->insert_batch($parts);
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Claim Main Dealer ke AHM berhasil dibuat.');
			$this->session->set_flashdata('tipe', 'info');
			$claim_main_dealer_ke_ahm = $this->claim_main_dealer_ke_ahm->find($claim_main_dealer['id_claim'], 'id_claim');
			send_json($claim_main_dealer_ke_ahm);
		}else{
			$this->session->set_flashdata('pesan', 'Claim Main Dealer ke AHM tidak berhasil dibuat.');
			$this->session->set_flashdata('tipe', 'danger');
			send_json(['message' => 'Claim Main Dealer ke AHM tidak berhasil dibuat.'], 422);
		}
	}

	public function detail(){
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['claim_main_dealer'] = $this->db
		->select('cmd.*')
		->select('fdo.invoice_number')
		->from('tr_h3_md_claim_main_dealer_ke_ahm as cmd')
		->join('tr_h3_md_fdo as fdo', 'fdo.id = cmd.invoice_number_int', 'left')
		->where('cmd.id_claim', $this->input->get('id_claim'))
		->get()->row_array();

		$qty_claim_main_dealer = $this->db
        ->select('SUM(cmdai.qty_part_diclaim) as qty_part_diclaim')
        ->from('tr_h3_md_claim_main_dealer_ke_ahm_item as cmdai')
        ->join('tr_h3_md_claim_main_dealer_ke_ahm as cmda', 'cmda.id_claim = cmdai.id_claim')
        ->where('cmdai.id_part = psp.id_part')
        ->where('cmdai.no_doos = psp.no_doos')
        ->where('cmdai.no_po = psp.no_po')
		->where('cmda.status !=', 'Canceled')
		->where('cmda.id_claim != cmdi.id_claim')
        ->get_compiled_select();

		$parts = $this->db
		->select('cmdi.id_claim_int')
		->select('cmdi.id_part_int')
		->select('cmdi.id_part')
		->select('p.nama_part')
		->select('cmdi.no_doos')
		->select('cmdi.no_doos_int')
		->select('cmdi.no_po')
		->select('psp.packing_sheet_quantity')
        ->select("(psp.packing_sheet_quantity - IFNULL(({$qty_claim_main_dealer}), 0)) as qty_part_yang_boleh_claim", false)
		->select('cmdi.qty_part_diclaim')
		->select('cmdi.qty_part_dikirim_ke_ahm')
		->select('cmdi.id_kode_claim')
		->select('kc.nama_claim')
		->select('cmdi.id_lokasi_rak')
        ->select('lr.kode_lokasi_rak as lokasi')
		->select('cmdi.keterangan')
		->from('tr_h3_md_claim_main_dealer_ke_ahm_item as cmdi')
		->join('tr_h3_md_claim_main_dealer_ke_ahm as cmd', 'cmd.id_claim = cmdi.id_claim')
		->join('tr_h3_md_ps_parts as psp', '(psp.id_part = cmdi.id_part and psp.packing_sheet_number = cmd.packing_sheet_number and psp.no_doos = cmdi.no_doos and psp.no_po = cmdi.no_po)')
		->join('ms_part as p', 'p.id_part = cmdi.id_part')
		->join('ms_kategori_claim_c3 as kc', 'kc.id = cmdi.id_kode_claim')
        ->join('ms_h3_md_lokasi_rak as lr', 'lr.id = cmdi.id_lokasi_rak')
		->where('cmdi.id_claim', $this->input->get('id_claim'))
		->get()->result_array();

		$parts = array_map(function($part){
			$part['qty_avs'] = $this->stock_int->qty_avs($part['id_part_int'], [], false, false, [$part['id_claim_int']]);
			return $part;
		}, $parts);
		
		$data['parts'] = $parts;

		$this->template($data);
	}

	public function edit(){
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['claim_main_dealer'] = $this->db
		->select('cmd.*')
		->select('fdo.invoice_number')
		->from('tr_h3_md_claim_main_dealer_ke_ahm as cmd')
		->join('tr_h3_md_fdo_parts as fdo_parts', 'fdo_parts.nomor_packing_sheet = cmd.packing_sheet_number', 'left')
		->join('tr_h3_md_fdo as fdo', 'fdo.invoice_number = fdo_parts.invoice_number', 'left')
		->where('cmd.id_claim', $this->input->get('id_claim'))
		->get()->row_array();

		$qty_claim_main_dealer = $this->db
        ->select('SUM(cmdai.qty_part_diclaim) as qty_part_diclaim')
        ->from('tr_h3_md_claim_main_dealer_ke_ahm_item as cmdai')
        ->join('tr_h3_md_claim_main_dealer_ke_ahm as cmda', 'cmda.id_claim = cmdai.id_claim')
        ->where('cmdai.id_part = psp.id_part')
        ->where('cmdai.no_doos = psp.no_doos')
        ->where('cmdai.no_po = psp.no_po')
		->where('cmda.status !=', 'Canceled')
		->where('cmda.id_claim != cmdi.id_claim')
        ->get_compiled_select();

		$parts = $this->db
		->select('cmdi.id_claim_int')
		->select('cmdi.id_part_int')
		->select('cmdi.id_part')
		->select('p.nama_part')
		->select('cmdi.no_doos')
		->select('cmdi.no_doos_int')
		->select('cmdi.no_po')
		->select('psp.packing_sheet_quantity')
        ->select("(psp.packing_sheet_quantity - IFNULL(({$qty_claim_main_dealer}), 0)) as qty_part_yang_boleh_claim", false)
		->select('cmdi.qty_part_diclaim')
		->select('cmdi.qty_part_dikirim_ke_ahm')
		->select('cmdi.id_kode_claim')
		->select('kc.nama_claim')
		->select('cmdi.id_lokasi_rak')
        ->select('lr.kode_lokasi_rak as lokasi')
		->select('cmdi.keterangan')
		->from('tr_h3_md_claim_main_dealer_ke_ahm_item as cmdi')
		->join('tr_h3_md_claim_main_dealer_ke_ahm as cmd', 'cmd.id_claim = cmdi.id_claim')
		->join('tr_h3_md_ps_parts as psp', '(psp.id_part = cmdi.id_part and psp.packing_sheet_number = cmd.packing_sheet_number and psp.no_doos = cmdi.no_doos and psp.no_po = cmdi.no_po)')
		->join('ms_part as p', 'p.id_part = cmdi.id_part')
		->join('ms_kategori_claim_c3 as kc', 'kc.id = cmdi.id_kode_claim')
        ->join('ms_h3_md_lokasi_rak as lr', 'lr.id = cmdi.id_lokasi_rak')
		->where('cmdi.id_claim', $this->input->get('id_claim'))
		->get()->result_array();

		$parts = array_map(function($part){
			$part['qty_avs'] = $this->stock_int->qty_avs($part['id_part_int'], [], false, false, [$part['id_claim_int']]);
			return $part;
		}, $parts);
		
		$data['parts'] = $parts;

		$this->template($data);
	}

	public function update(){
		$this->validate();
		$claim_main_dealer = $this->input->post([
			'packing_sheet_number', 'packing_sheet_number_int', 'invoice_number', 'invoice_number_int', 'packing_sheet', 'packing_ticket',
			'foto_bukti', 'shipping_list', 'nomor_karton', 'tutup_botol', 
			'label_timbangan', 'label_karton', 'lain_lain'
		]);
		$claim_main_dealer = clean_data($claim_main_dealer);

		$parts = $this->getOnly([
			'id_part_int', 'id_part', 'no_doos', 'qty_part_diclaim','qty_part_dikirim_ke_ahm',
			'id_kode_claim','id_lokasi_rak', 'keterangan', 'qty_avs', 'no_po', 'no_doos_int'
		], $this->input->post('parts'), $this->input->post(['id_claim']));
		$this->db->trans_start();
		$this->claim_main_dealer_ke_ahm->update($claim_main_dealer, $this->input->post(['id_claim']));
		$this->claim_main_dealer_ke_ahm_item->update_batch($parts, $this->input->post(['id_claim']));
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Claim Main Dealer ke AHM berhasil diupdate.');
			$this->session->set_flashdata('tipe', 'info');
			$claim_main_dealer_ke_ahm = $this->claim_main_dealer_ke_ahm->get($this->input->post(['id_claim']), true);
			send_json($claim_main_dealer_ke_ahm);
		}else{
			$this->session->set_flashdata('pesan', 'Claim Main Dealer ke AHM tidak berhasil diupdate.');
			$this->session->set_flashdata('tipe', 'danger');
			send_json(['message' => 'Claim Main Dealer ke AHM tidak berhasil diupdate.'], 422);
		}
	}

	public function proses(){
		$this->db->trans_start();
		$this->claim_main_dealer_ke_ahm->update([
			'status' => 'Processed',
			'proses_at' => date('Y-m-d H:i:s', time()),
			'proses_by' => $this->session->userdata('id_user')
		], $this->input->post(['id_claim']));
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Claim Main Dealer berhasil diproses.');
			$this->session->set_flashdata('tipe', 'info');
			$claim_main_dealer_ke_ahm = $this->claim_main_dealer_ke_ahm->get($this->input->post(['id_claim']), true);
			send_json($claim_main_dealer_ke_ahm);
		}else{
			$this->session->set_flashdata('pesan', 'Claim Main Dealer tidak berhasil diproses.');
			$this->session->set_flashdata('tipe', 'danger');
			send_json(['message' => 'Claim Main Dealer tidak berhasil diproses.'], 422);
		}
	}

	public function cancel(){
		$this->db->trans_start();
		$this->claim_main_dealer_ke_ahm->update([
			'status' => 'Canceled',
			'cancel_at' => date('Y-m-d H:i:s', time()),
			'cancel_by' => $this->session->userdata('id_user')
		], $this->input->post(['id_claim']));
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Claim Main Dealer berhasil dicancel.');
			$this->session->set_flashdata('tipe', 'info');
			$claim_main_dealer_ke_ahm = $this->claim_main_dealer_ke_ahm->get($this->input->post(['id_claim']), true);
			send_json($claim_main_dealer_ke_ahm);
		}else{
			$this->session->set_flashdata('pesan', 'Claim Main Dealer tidak berhasil dicancel.');
			$this->session->set_flashdata('tipe', 'danger');
			send_json(['message' => 'Claim Main Dealer tidak berhasil dicancel.'], 422);
		}
	}

	public function validate(){
        $this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('packing_sheet_number', 'Packing Sheet', 'required');

        if (!$this->form_validation->run()){
            $data = $this->form_validation->error_array();
            send_json($data, 422);
        }
	}
	
	public function cetak(){
        require_once APPPATH .'third_party/mpdf/mpdf.php';
		$mpdf = new Mpdf('c');
		
		$data = [];
		$data['header'] = $this->db
		->select('cmda.id_claim')
		->select('date_format(cmda.created_at, "%d/%m/%Y") as created_at')
		->select('fdo.invoice_number')
		->select('cmda.packing_sheet_number')
		->select('cmda.packing_sheet')
		->select('cmda.packing_ticket')
		->select('cmda.foto_bukti')
		->select('cmda.shipping_list')
		->select('cmda.nomor_karton')
		->select('cmda.tutup_botol')
		->select('cmda.label_timbangan')
		->select('cmda.label_karton')
		->select('cmda.lain_lain')
		->from('tr_h3_md_claim_main_dealer_ke_ahm as cmda')
		->join('tr_h3_md_ps as ps', 'ps.packing_sheet_number = cmda.packing_sheet_number')
		->join('tr_h3_md_fdo_ps as fdo', 'fdo.packing_sheet_number = ps.packing_sheet_number', 'left')
		->where('cmda.id_claim', $this->input->get('id_claim'))
		->get()->row_array();

		$data['parts'] = $this->db
		->select('cmdap.id_part')
		->select('cmdap.no_doos')
		->select('cmda.packing_sheet_number')
		->select('p.nama_part')
		->select('psp.packing_sheet_quantity as qty_ps')
		->select('cmdap.qty_part_diclaim')
		->select('cmdap.qty_part_dikirim_ke_ahm')
		->select('cmdap.keterangan')
		->select('kc.kode_claim')
		->select('"" as keputusan')
		->from('tr_h3_md_claim_main_dealer_ke_ahm_item as cmdap')
		->join('tr_h3_md_claim_main_dealer_ke_ahm as cmda', 'cmda.id_claim = cmdap.id_claim')
		->join('tr_h3_md_ps_parts as psp', '(psp.packing_sheet_number = cmda.packing_sheet_number and psp.id_part = cmdap.id_part and psp.no_doos = cmdap.no_doos and psp.no_po = cmdap.no_po)')
		->join('ms_kategori_claim_c3 as kc', 'kc.id = cmdap.id_kode_claim')
		->join('ms_part as p', 'p.id_part = cmdap.id_part')
		// ->join('ms_part as p', '1 = 1')
		// ->limit(5)
		->where('cmdap.id_claim', $this->input->get('id_claim'))
		->get()->result_array();

		$html = $this->load->view('h3/h3_md_cetakan_claim_main_dealer_ke_ahm', $data, true);
        $mpdf->WriteHTML($html);
        $mpdf->Output("Form Claim C3 Parts.pdf", "I");
	}
}