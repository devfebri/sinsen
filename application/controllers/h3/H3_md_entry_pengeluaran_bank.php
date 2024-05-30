<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_entry_pengeluaran_bank extends Honda_Controller
{
	protected $folder = "h3";
	protected $page   = "h3_md_entry_pengeluaran_bank";
	protected $title  = "Entry Pengeluaran Bank";

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

		$this->load->model('h3_md_entry_pengeluaran_bank_model', 'entry_pengeluaran_bank');
		$this->load->model('H3_md_voucher_pengeluaran_model', 'voucher_pengeluaran');
        $this->load->model('H3_md_ap_part_model', 'ap_part');
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

	public function get_voucher_pengeluaran_data(){
		$data = $this->db
		->select('vp.no_giro as kode_giro')
		->select('vp.tanggal_giro')
		->select('vp.nominal_giro')
		->select('vp.via_bayar')
		->select('vp.nama_penerima_dibayarkan_kepada')
		->select('vp.deskripsi')
		->select('vp.total_amount')
		->from('tr_h3_md_voucher_pengeluaran as vp')
		->join('ms_cek_giro as cg', 'cg.id_cek_giro = vp.id_giro', 'left')
		->where('vp.id', $this->input->get('id_voucher_pengeluaran_int'))
		->limit(1)
		->get()->row_array();

		send_json($data);
	}

	public function save(){
		$this->db->trans_start();
		$this->validate();

		$entry_pengeluaran_bank = $this->input->post([
			'id_voucher_pengeluaran_int', 'tgl_cair'
		]);
		$entry_pengeluaran_bank['id_entry_pengeluaran_bank'] = $this->entry_pengeluaran_bank->generate_nomor();

		$this->entry_pengeluaran_bank->insert($entry_pengeluaran_bank);

		$this->db->trans_complete();

		if($this->db->trans_status()){
			$entry_pengeluaran_bank = $this->entry_pengeluaran_bank->find($entry_pengeluaran_bank['id_entry_pengeluaran_bank'], 'id_entry_pengeluaran_bank');
			send_json($entry_pengeluaran_bank);
		}else{
			log_message('info', 'Gagal membuat Entry Pengeluaran Bank');
			send_json([
				'message' => 'Gagal membuat Entry Pengeluaran Bank'
			], 422);
		}
	}

	public function detail()
	{
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['entry_pengeluaran'] = $this->db
        ->select('epb.id_entry_pengeluaran_bank')
        ->select('vp.id as id_voucher_pengeluaran_int')
        ->select('vp.id_voucher_pengeluaran')
        ->select('vp.no_giro as kode_giro')
		->select('vp.tanggal_giro')
		->select('vp.via_bayar')
		->select('vp.nama_penerima_dibayarkan_kepada')
		->select('vp.deskripsi')
		->select('vp.total_amount')
		->select('epb.tgl_cair')
		->select('epb.status')
        ->from('tr_h3_md_entry_pengeluaran_bank as epb')
		->join('tr_h3_md_voucher_pengeluaran as vp', 'vp.id = epb.id_voucher_pengeluaran_int')
		->join('ms_cek_giro as cg', 'cg.id_cek_giro = vp.id_giro', 'left')
		->where('epb.id_entry_pengeluaran_bank', $this->input->get('id_entry_pengeluaran_bank'))
		->limit(1)
		->get()->row_array();

		$this->template($data);
	}

	public function edit()
	{
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['entry_pengeluaran'] = $this->db
        ->select('epb.id_entry_pengeluaran_bank')
        ->select('vp.id as id_voucher_pengeluaran_int')
        ->select('vp.id_voucher_pengeluaran')
        ->select('vp.no_giro as kode_giro')
		->select('vp.tanggal_giro')
		->select('vp.via_bayar')
		->select('vp.nama_penerima_dibayarkan_kepada')
		->select('vp.deskripsi')
		->select('vp.total_amount')
		->select('epb.tgl_cair')
		->select('epb.status')
        ->from('tr_h3_md_entry_pengeluaran_bank as epb')
		->join('tr_h3_md_voucher_pengeluaran as vp', 'vp.id = epb.id_voucher_pengeluaran_int')
		->join('ms_cek_giro as cg', 'cg.id_cek_giro = vp.id_giro', 'left')
		->where('epb.id_entry_pengeluaran_bank', $this->input->get('id_entry_pengeluaran_bank'))
		->limit(1)
		->get()->row_array();

		$this->template($data);
	}

	public function update(){
		$this->db->trans_start();
		$this->validate();

		$entry_pengeluaran_bank = $this->input->post([
			'id_voucher_pengeluaran_int', 'tgl_cair'
		]);

		$this->entry_pengeluaran_bank->update($entry_pengeluaran_bank, $this->input->post(['id_entry_pengeluaran_bank']));

		$this->db->trans_complete();

		if($this->db->trans_status()){
			$entry_pengeluaran_bank = $this->entry_pengeluaran_bank->get($this->input->post(['id_entry_pengeluaran_bank']), true);
			send_json($entry_pengeluaran_bank);
		}else{
			log_message('info', 'Gagal memperbarui Entry Pengeluaran Bank');
			send_json([
				'message' => 'Gagal memperbarui Entry Pengeluaran Bank'
			], 422);
		}
	}

	public function approve(){
		$this->load->model('H3_md_rekap_invoice_ahm_model', 'rekap_invoice_ahm');

		$this->db->trans_start();
		$entry_pengeluaran_bank = (array) $this->entry_pengeluaran_bank->get([
			'id_entry_pengeluaran_bank' => $this->input->get('id_entry_pengeluaran_bank'),
			'status' => 'Open'
		], true);

		if($entry_pengeluaran_bank == null) return;

		$this->voucher_pengeluaran->add_ke_nominal_giro($entry_pengeluaran_bank['id_voucher_pengeluaran_int']);
		$this->voucher_pengeluaran->set_processed($entry_pengeluaran_bank['id_voucher_pengeluaran_int']);

		$this->entry_pengeluaran_bank->update([
			'status' => 'Approved',
			'approved_at' => date('Y-m-d H:i:s', time()),
			'approved_by' => $this->session->userdata('id_user'),
		], ['id' => $entry_pengeluaran_bank['id']]);

		$this->db
		->select('ap.jenis_transaksi')
		->select('ap.referensi')
		->select('vpi.id_referensi')
		->select('vpi.nominal')
		->from('tr_h3_md_entry_pengeluaran_bank as epb')
		->join('tr_h3_md_voucher_pengeluaran as vp', 'vp.id = epb.id_voucher_pengeluaran_int')
		->join('tr_h3_md_voucher_pengeluaran_items as vpi', 'vpi.id_voucher_pengeluaran = vp.id_voucher_pengeluaran')
		->join('tr_h3_md_ap_part as ap', 'ap.id = vpi.id_referensi')
		->where('epb.id', $entry_pengeluaran_bank['id']);

		foreach($this->db->get()->result_array() as $item){
			if($item['jenis_transaksi'] == 'rekap_invoice_ahm'){
				$this->rekap_invoice_ahm->distribusi_pembayaran_ke_faktur($item['referensi'], $item['nominal'], $entry_pengeluaran_bank['id']);
			}
			$this->ap_part->bayar($item['id_referensi'], $item['nominal'], $entry_pengeluaran_bank['id']);
		}

		$this->db->trans_complete();

		if($this->db->trans_status()){
			$this->session->set_userdata('pesan', 'Berhasil approve Entry Pengeluaran Bank ' . $entry_pengeluaran_bank['id_entry_pengeluaran_bank']);
			$this->session->set_userdata('tipe', 'success');
			send_json($entry_pengeluaran_bank);
		}else{
			send_json([
				'message' => 'Gagal Approve entry pengeluaran bank'
			], 422);
		}
	}

	public function reject(){
		$this->db->trans_start();
		$this->entry_pengeluaran_bank->update([
			'status' => 'Rejected',
			'rejected_at' => date('Y-m-d H:i:s', time()),
			'rejected_by' => $this->session->userdata('id_user'),
		], $this->input->get(['id_entry_pengeluaran_bank']));
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$this->session->set_userdata('pesan', 'Berhasil reject Entry Pengeluaran Bank ' . $this->input->get('id_entry_pengeluaran_bank'));
			$this->session->set_userdata('tipe', 'success');
			$purchase = $this->entry_pengeluaran_bank->find($this->input->get('id_entry_pengeluaran_bank'), 'id_entry_pengeluaran_bank');
			send_json($purchase);
		}else{
			send_json([
				'message' => 'Gagal reject entry pengeluaran bank'
			], 422);
		}
	}

	public function validate(){
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id_voucher_pengeluaran_int', 'Voucher Pengeluaran', 'required');
		$this->form_validation->set_rules('tgl_cair', 'Tanggal Cair', 'required');

        if (!$this->form_validation->run())
        {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Tanggal cair belum diisi',
				'errors' => $this->form_validation->error_array()
			], 422);
		}
    }
}
