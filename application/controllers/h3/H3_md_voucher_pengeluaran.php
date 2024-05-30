<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_voucher_pengeluaran extends Honda_Controller
{

	protected $folder = "h3";
	protected $page   = "h3_md_voucher_pengeluaran";
	protected $title  = "Voucher Pengeluaran";

	public function __construct()
	{
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('H3_md_voucher_pengeluaran_model', 'voucher_pengeluaran');
		$this->load->model('H3_md_voucher_pengeluaran_items_model', 'voucher_pengeluaran_items');
		//===== Load Library =====
		$this->load->library('Mcarbon');
		$this->load->library('upload');
		$this->load->library('form_validation');
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page, "select");
		$sess = $this->m_admin->sess_auth();
		if ($name == "" or $auth == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
		} elseif ($sess == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "crash'>";
		}
	}

	public function index()
	{
		$data['set']	= "index";
		$this->template($data);
	}

	public function add()
	{
		$data['mode']    = 'insert';
		$data['set']     = "form";
		$this->template($data);
	}

	public function save()
	{
		$this->validate();

		$this->db->trans_start();
		$data = array_merge($this->input->post([
			'tanggal_transaksi','tipe_penerima','id_account','id_dibayarkan_kepada', 'nama_penerima_dibayarkan_kepada', 'referensi_dibayarkan_kepada', 'alamat',
            'via_bayar','id_giro', 'no_giro', 'tanggal_giro', 'deskripsi', 'nominal_giro', 'nama_account', 'no_rekening_account',
            'no_rekening_tujuan', 'bank_tujuan', 'atas_nama_tujuan', 'id_voucher_pengeluaran', 'total_amount', 'id_bank', 'tanggal_transfer', 'nominal_transfer'
		]), [
			'id_voucher_pengeluaran' => $this->voucher_pengeluaran->generate_id()
		]);
		$data = $this->clean_data($data);

		$items = $this->getOnly([
			'nomor_account', 'nama_coa', 'id_referensi', 'jumlah_terutang', 'jenis_transaksi', 'nominal', 'keterangan'
		], $this->input->post('items'), [
			'id_voucher_pengeluaran' => $data['id_voucher_pengeluaran']
		]);

		$this->voucher_pengeluaran->insert($data);
		$this->voucher_pengeluaran_items->insert_batch($items);
		$this->db->trans_complete();

		$voucher_pengeluaran = (array) $this->voucher_pengeluaran->find($data['id_voucher_pengeluaran'], 'id_voucher_pengeluaran');
		if ($this->db->trans_status() AND $voucher_pengeluaran != null) {
			$message = 'Berhasil menyimpan voucher pengeluaran dengan nomor ' . $voucher_pengeluaran['id_voucher_pengeluaran'];
			$this->session->set_userdata('pesan', $message);
			$this->session->set_userdata('tipe', 'success');

			send_json([
				'message' => $message,
				'payload' => $voucher_pengeluaran,
				'redirect_url' => base_url(sprintf('h3/h3_md_voucher_pengeluaran'))
			]);
		} else {
			send_json([
				'message' => 'Tidak berhasil menyimpan voucher pengeluaran'
			], 422);
		}
	}

	public function detail()
	{
		$data['mode'] = 'detail';
		$data['set'] = "form";
		$data['voucher_pengeluaran'] = $this->db
			->select('vp.id_voucher_pengeluaran')
			->select('vp.tanggal_transaksi')
			->select('vp.tipe_penerima')
			->select('vp.id_bank')
			->select('vp.id_account')
			->select('bank.bank as nama_account')
			->select('vp.id_dibayarkan_kepada')
			->select('vp.referensi_dibayarkan_kepada')
			->select('vp.nama_penerima_dibayarkan_kepada')
			->select('vp.alamat')
			->select('vp.via_bayar')
			->select('vp.id_giro')
			->select('vp.tanggal_giro')
			->select('giro.kode_giro as no_giro')
			->select('vp.nominal_giro')
			->select('vp.deskripsi')
			->select('vp.tanggal_transfer')
			->select('vp.nominal_transfer')
			->select('vp.no_rekening_tujuan')
			->select('vp.bank_tujuan')
			->select('vp.atas_nama_tujuan')
			->select('vp.status')
			->select('vp.alasan_cancel')
			->from('tr_h3_md_voucher_pengeluaran as vp')
			->join('ms_rek_md as bank', 'bank.id_rek_md = vp.id_account', 'left')
			->join('ms_dealer as d', 'd.id_dealer = vp.id_dibayarkan_kepada', 'left')
			->join('ms_vendor as v', 'v.id_vendor = vp.id_dibayarkan_kepada', 'left')
			->join('ms_karyawan as k', 'k.id_karyawan = vp.id_dibayarkan_kepada', 'left')
			->join('ms_cek_giro as giro', 'giro.id_cek_giro = vp.id_giro', 'left')
			->where('vp.id_voucher_pengeluaran', $this->input->get('id_voucher_pengeluaran'))
			->get()->row_array();

		$data['items'] = $this->db
			->select('vpi.nomor_account')
			->select('vpi.nama_coa')
			->select('vpi.jenis_transaksi')
			->select('vpi.id_referensi')
			->select('ap.referensi')
			->select('vpi.jumlah_terutang')
			->select('vpi.nominal')
			->select('vpi.keterangan')
			->from('tr_h3_md_voucher_pengeluaran_items as vpi')
			->join('tr_h3_md_ap_part as ap', 'ap.id = vpi.id_referensi', 'left')
			->where('vpi.id_voucher_pengeluaran', $this->input->get('id_voucher_pengeluaran'))
			->get()->result_array();

		$this->template($data);
	}

	public function cek_nominal_giro()
	{
		$data = $this->db
			->select('IFNULL( SUM(vp.total_amount), 0) as total_amount')
			->from('tr_h3_md_voucher_pengeluaran as vp')
			->where('vp.id_giro', $this->input->get('id_giro'))
			->get()->row_array();

		if ($data != null) {
			echo $data['total_amount'];
		} else {
			echo 0;
		}
		die;
	}

	public function edit()
	{
		$data['mode'] = 'edit';
		$data['set'] = "form";
		$data['voucher_pengeluaran'] = $this->db
			->select('vp.id_voucher_pengeluaran')
			->select('vp.tanggal_transaksi')
			->select('vp.tipe_penerima')
			->select('vp.id_bank')
			->select('vp.id_account')
			->select('bank.bank as nama_account')
			->select('vp.id_dibayarkan_kepada')
			->select('vp.referensi_dibayarkan_kepada')
			->select('vp.nama_penerima_dibayarkan_kepada')
			->select('vp.alamat')
			->select('vp.via_bayar')
			->select('vp.id_giro')
			->select('vp.tanggal_giro')
			->select('giro.kode_giro as no_giro')
			->select('vp.nominal_giro')
			->select('vp.deskripsi')
			->select('vp.tanggal_transfer')
			->select('vp.nominal_transfer')
			->select('vp.no_rekening_tujuan')
			->select('vp.bank_tujuan')
			->select('vp.atas_nama_tujuan')
			->select('vp.status')
			->select('vp.alasan_cancel')
			->from('tr_h3_md_voucher_pengeluaran as vp')
			->join('ms_rek_md as bank', 'bank.id_rek_md = vp.id_account', 'left')
			->join('ms_dealer as d', 'd.id_dealer = vp.id_dibayarkan_kepada', 'left')
			->join('ms_vendor as v', 'v.id_vendor = vp.id_dibayarkan_kepada', 'left')
			->join('ms_karyawan as k', 'k.id_karyawan = vp.id_dibayarkan_kepada', 'left')
			->join('ms_cek_giro as giro', 'giro.id_cek_giro = vp.id_giro', 'left')
			->where('vp.id_voucher_pengeluaran', $this->input->get('id_voucher_pengeluaran'))
			->get()->row_array();

		$data['items'] = $this->db
			->select('vpi.nomor_account')
			->select('vpi.nama_coa')
			->select('vpi.jenis_transaksi')
			->select('vpi.id_referensi')
			->select('ap.referensi')
			->select('vpi.jumlah_terutang')
			->select('vpi.nominal')
			->select('vpi.keterangan')
			->from('tr_h3_md_voucher_pengeluaran_items as vpi')
			->join('tr_h3_md_ap_part as ap', 'ap.id = vpi.id_referensi', 'left')
			->where('vpi.id_voucher_pengeluaran', $this->input->get('id_voucher_pengeluaran'))
			->get()->result_array();

		$this->template($data);
	}

	public function update()
	{
		$this->validate();

		$this->db->trans_start();
		$data = $this->input->post([
			'tanggal_transaksi','tipe_penerima','id_account','id_dibayarkan_kepada', 'nama_penerima_dibayarkan_kepada', 'referensi_dibayarkan_kepada', 'alamat',
            'via_bayar','id_giro', 'no_giro', 'tanggal_giro', 'deskripsi', 'nominal_giro', 'nama_account', 'no_rekening_account',
            'no_rekening_tujuan', 'bank_tujuan', 'atas_nama_tujuan', 'id_voucher_pengeluaran', 'total_amount', 'id_bank', 'tanggal_transfer', 'nominal_transfer'
		]);
		$data = $this->clean_data($data);

		$items = $this->getOnly([
			'nomor_account', 'nama_coa', 'id_referensi', 'jumlah_terutang', 'jenis_transaksi', 'nominal', 'keterangan'
		], $this->input->post('items'), $this->input->post(['id_voucher_pengeluaran']));

		$this->voucher_pengeluaran->update($data, $this->input->post(['id_voucher_pengeluaran']));
		$this->voucher_pengeluaran_items->update_batch($items, $this->input->post(['id_voucher_pengeluaran']));
		$this->db->trans_complete();

		$voucher_pengeluaran = (array) $this->voucher_pengeluaran->get($this->input->post(['id_voucher_pengeluaran']), true);
		if ($this->db->trans_status() AND $voucher_pengeluaran != null) {
			$message = 'Berhasil memperbarui voucher pengeluaran ' . $voucher_pengeluaran['id_voucher_pengeluaran'];
			$this->session->set_userdata('pesan', $message);
			$this->session->set_userdata('tipe', 'success');

			send_json([
				'message' => $message,
				'payload' => $voucher_pengeluaran,
				'redirect_url' => base_url(sprintf('h3/h3_md_voucher_pengeluaran'))
			]);
		} else {
			send_json([
				'message' => 'Tidak berhasil memperbarui voucher pengeluaran'
			], 422);
		}
	}

	public function cancel()
	{
		$this->db->trans_start();

		$this->voucher_pengeluaran->update([
			'status' => 'Canceled',
			'canceled_at' => Mcarbon::now()->toDateTimeString(),
			'canceled_by' => $this->session->userdata('id_user'),
			'alasan_cancel' => $this->input->get('alasan_cancel'),
		], $this->input->get(['id_voucher_pengeluaran']));

		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_userdata('pesan', 'Berhasil cancel voucher pengeluaran ' . $this->input->get('id_voucher_pengeluaran'));
			$this->session->set_userdata('tipe', 'success');

			$voucher_pengeluaran = $this->voucher_pengeluaran->find($this->input->get('id_voucher_pengeluaran'), 'id_voucher_pengeluaran');
			send_json($voucher_pengeluaran);
		} else {
			$this->output->set_status_header(500);
		}
	}

	public function validate()
	{
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('tanggal_transaksi', 'Tanggal Transaksi', 'required');
		$this->form_validation->set_rules('tipe_penerima', 'Tipe Penerima', 'required');
		$this->form_validation->set_rules('id_account', 'Account', 'required');
		if($this->input->post('tipe_penerima') != 'Lain-lain'){
			$this->form_validation->set_rules('id_dibayarkan_kepada', 'Dibayarkan Kepada', 'required');
		}
		$this->form_validation->set_rules('via_bayar', 'Via Bayar', 'required');

		if ($this->input->post('via_bayar') == 'Giro') {
			$this->form_validation->set_rules('id_giro', 'No. Giro', 'required');
			$this->form_validation->set_rules('tanggal_giro', 'Tanggal Giro', 'required');
		}

		if($this->input->post('via_bayar') == 'Transfer'){
			$this->form_validation->set_rules('tanggal_transfer', 'Tanggal transfer', 'required');
			$this->form_validation->set_rules('nominal_transfer', 'Nominal transfer', 'required|numeric');
			// $this->form_validation->set_rules('no_rekening_tujuan', 'No. Rekening', 'required');
			// $this->form_validation->set_rules('bank_tujuan', 'Bank', 'required');
			// $this->form_validation->set_rules('atas_nama_tujuan', 'Atas nama', 'required');
		}

		if (!$this->form_validation->run()) {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
		}
	}

	public function cetak(){
		$this->load->model('H3_md_laporan_voucher_pengeluaran_model', 'laporan_voucher_pengeluaran');

		$this->laporan_voucher_pengeluaran->generatePdf($this->input->get('id_voucher_pengeluaran'));
	}
}
