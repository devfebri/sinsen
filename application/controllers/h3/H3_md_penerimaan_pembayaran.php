<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_penerimaan_pembayaran extends Honda_Controller
{

	protected $folder = "h3";
	protected $page   = "h3_md_penerimaan_pembayaran";
	protected $title  = "Penerimaan Pembayaran";

	public function __construct()
	{
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('H3_md_penerimaan_pembayaran_model', 'penerimaan_pembayaran');
		$this->load->model('H3_md_penerimaan_pembayaran_item_model', 'penerimaan_pembayaran_item');
		$this->load->model('H3_md_laporan_penerimaan_pembayaran_model', 'laporan_penerimaan_pembayaran');
		$this->load->model('H3_md_berita_acara_penyerahan_faktur_item_model', 'berita_acara_penyerahan_faktur_item');
		$this->load->model('H3_md_ar_part_model', 'ar_part');
		//===== Load Library =====
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

	public function proses_faktur()
	{
		$this->validate_generate_faktur();

		$dealer = $this->db
			->select('d.id_dealer')
			->select('d.tipe_plafon_h3')
			->from('ms_dealer as d')
			->where('d.id_dealer', $this->input->get('id_dealer'))
			->limit(1)
			->get()->row_array();

		$amount_sudah_dilakukan_penerimaan = $this->db
			->select('SUM(pbi.jumlah_pembayaran) as jumlah_pembayaran')
			// ->select('pb.id_penerimaan_pembayaran')
			// ->select('pbi.jumlah_pembayaran')
			->from('tr_h3_md_penerimaan_pembayaran_item as pbi')
			->join('tr_h3_md_penerimaan_pembayaran as pb', 'pb.id_penerimaan_pembayaran = pbi.id_penerimaan_pembayaran')
			->where('pbi.referensi = ar.referensi', null, false)
			->where('
			case
				when pb.jenis_pembayaran = "BG" then (pb.status_bg is null or pb.status_bg = "Cair")
				else true
			end
		', null, false)
			->get_compiled_select();

		$data = $this->db
			->select('ar.kode_coa')
			->select('ar.tipe_referensi as tipe_transaksi')
			->select('ar.referensi')
			->select('date_format(ar.tanggal_jatuh_tempo, "%d/%m/%Y") as tgl_jatuh_tempo')
			->select('d.nama_dealer')
			->select("(ar.total_amount - IFNULL(({$amount_sudah_dilakukan_penerimaan}), 0)) as amount", false)
			->select("IFNULL(({$amount_sudah_dilakukan_penerimaan}), 0) as amount_sudah_dilakukan_penerimaan", false)
			->select('0 as jumlah_pembayaran')
			->select('k.nama_lengkap as nama_debt_collector')
			->select('ar.lunas')
			->from('tr_h3_md_ar_part as ar')
			->join('ms_group_dealer_detail as gd', 'gd.id_dealer = ar.id_dealer', 'left')
			->join('tr_h3_md_packing_sheet as ps', 'ps.no_faktur = ar.referensi', 'left')
			->join('tr_h3_md_berita_acara_penyerahan_faktur_item as bapi', 'bapi.no_faktur = ps.no_faktur and bapi.dikembalikan=0', 'left')
			->join('tr_h3_md_berita_acara_penyerahan_faktur as bap', 'bap.no_bap = bapi.no_bap', 'left')
			->join('tr_h3_md_picking_list as pl', 'pl.id = ps.id_picking_list_int', 'left')
			->join('ms_dealer as d', 'd.id_dealer = pl.id_dealer', 'left')
			->join('tr_h3_md_do_sales_order as dso', 'dso.id = pl.id_ref_int', 'left')
			->join('tr_h3_md_sales_order as so', 'so.id = dso.id_sales_order_int', 'left')
			->join('ms_karyawan as k', 'k.id_karyawan = bap.id_debt_collector', 'left')
			->where('ar.lunas', 0)
			// ->where('bapi.dikembalikan', 0)
			->where("(ar.total_amount - IFNULL(({$amount_sudah_dilakukan_penerimaan}), 0)) > 0", null, false)
			->order_by('ar.tanggal_jatuh_tempo', 'asc')
			->order_by('ar.referensi', 'asc');

		if ($this->input->get('id_debt_collector') != null) {
			$this->db->where('bap.id_debt_collector', $this->input->get('id_debt_collector'));
		}

		if ($this->input->get('tanggal_bap') != null) {
			$this->db->where("left(bap.created_at, 10) = '{$this->input->get('tanggal_bap')}'");
		}

		if ($this->input->get('id_dealer') != null) {
			if ($dealer['tipe_plafon_h3'] == 'gimmick') {
				$this->db->where('ar.gimmick', 1);
				$this->db->where('ar.kpb', 0);
			} else if ($dealer['tipe_plafon_h3'] == 'kpb') {
				$this->db->where('ar.gimmick', 0);
				$this->db->where('ar.kpb', 1);
			} else {
				$this->db->where('ar.gimmick', 0);
				$this->db->where('so.id_dealer', $this->input->get('id_dealer'));
			}
		}

		if ($this->input->get('id_group_dealer')) {
			$this->db->where('gd.id_group_dealer', $this->input->get('id_group_dealer'));
		}

		send_json(
			$this->db->get()->result_array()
		);
	}

	public function validate_generate_faktur()
	{
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_data($this->input->get());
		// $this->form_validation->set_rules('id_dealer', 'Customer', 'required');
		// $this->form_validation->set_rules('tanggal_bap', 'Tanggal BAP', 'required');
		// $this->form_validation->set_rules('id_debt_collector', 'Debt Collector', 'required');
		$this->form_validation->set_rules('jenis_pembayaran', 'Jenis Pembayaran', 'required');

		if (!$this->form_validation->run()) {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
		}
	}

	public function save()
	{
		$this->db->trans_start();
		$this->validate();

		$jenis_pembayaran = $this->input->post('jenis_pembayaran');

		$data = $this->clean_data($this->input->post([
			'tanggal_bap', 'id_dealer', 'id_group_dealer', 'id_debt_collector',
			'jenis_pembayaran', 'nominal_cash', 'tanggal_proses', 'nomor_bg',
			'nama_bank_bg', 'tanggal_jatuh_tempo_bg', 'nominal_bg', 'id_rekening_md_bg',
			'tanggal_transfer', 'nominal_transfer', 'id_rekening_md_transfer', 'total_pembayaran'
		]));
		$data = array_merge($data, [
			'id_penerimaan_pembayaran' => $this->penerimaan_pembayaran->generate_id()
		]);

		$this->penerimaan_pembayaran->insert($data);
		foreach ($this->input->post('items') as $item) {
			$item_penerimaan_pembayaran = $this->get_in_array(['kode_coa', 'referensi', 'tipe_transaksi', 'amount', 'jumlah_pembayaran', 'sisa_piutang', 'lunas'], $item, [
				'id_penerimaan_pembayaran' => $data['id_penerimaan_pembayaran']
			]);

			if ($item_penerimaan_pembayaran['tipe_transaksi'] == 'manual_coa') {
				$item_penerimaan_pembayaran['amount'] = $item_penerimaan_pembayaran['jumlah_pembayaran'];
				$item_penerimaan_pembayaran['sisa_piutang'] = 0;
				$item_penerimaan_pembayaran['lunas'] = 1;
			}

			$this->penerimaan_pembayaran_item->insert($item_penerimaan_pembayaran);

			if ($jenis_pembayaran != 'BG' and $item['tipe_transaksi'] != 'manual_coa') {
				$this->db
					->set('ar.sudah_dibayar', "ar.sudah_dibayar + {$item['jumlah_pembayaran']}", false)
					->where('ar.referensi', $item['referensi'])
					->update('tr_h3_md_ar_part as ar');
			}

			if ($item['lunas'] == 1 && $item['tipe_transaksi'] == 'faktur_penjualan' && $jenis_pembayaran != 'BG') {
				$this->ar_part->dilunaskan($item['referensi']);
			}
		}

		$penerimaan_pembayaran = (array) $this->penerimaan_pembayaran->find($data['id_penerimaan_pembayaran'], 'id_penerimaan_pembayaran');
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_userdata('pesan', 'Penerimaan pembayaran berhasil disimpan');
			$this->session->set_userdata('tipe', 'info');

			send_json([
				'redirect_url' => base_url(sprintf('h3/%s/detail?id_penerimaan_pembayaran=%s', $this->page, $penerimaan_pembayaran['id_penerimaan_pembayaran']))
			]);
		} else {
			send_json([
				'message' => 'Gagal menyimpan penerimaan pembayaran'
			], 422);
		}
	}

	public function detail()
	{
		$data['mode'] = 'detail';
		$data['set'] = "form";
		$data['penerimaan_pembayaran'] = $this->db
			->select('pb.*')
			->select('d.nama_dealer')
			->select('gd.group_dealer as nama_group_dealer')
			->select('debt_collector.nama_lengkap as nama_debt_collector')
			->select('rekening_tujuan_bg.bank as nama_bank_rekening_md_bg')
			->select('rekening_tujuan_transfer.bank as nama_bank_rekening_md_transfer')
			->from('tr_h3_md_penerimaan_pembayaran as pb')
			->join('ms_dealer as d', 'd.id_dealer = pb.id_dealer', 'left')
			->join('ms_group_dealer as gd', 'gd.id_group_dealer = pb.id_group_dealer', 'left')
			->join('ms_karyawan as debt_collector', 'debt_collector.id_karyawan = pb.id_debt_collector', 'left')
			->join('ms_rek_md as rekening_tujuan_bg', 'rekening_tujuan_bg.id_rek_md = pb.id_rekening_md_bg', 'left')
			->join('ms_rek_md as rekening_tujuan_transfer', 'rekening_tujuan_transfer.id_rek_md = pb.id_rekening_md_transfer', 'left')
			->where('pb.id_penerimaan_pembayaran', $this->input->get('id_penerimaan_pembayaran'))
			->get()->row();

		$data['items'] = $this->db
			->select('pbi.kode_coa')
			->select('pbi.tipe_transaksi')
			->select('pbi.referensi')
			->select('date_format(ps.tgl_jatuh_tempo, "%d/%m/%Y") as tgl_jatuh_tempo')
			->select('d.nama_dealer')
			->select('pbi.amount')
			->select('pbi.jumlah_pembayaran')
			->select('k.nama_lengkap as nama_debt_collector')
			->select('pbi.lunas')
			->from('tr_h3_md_penerimaan_pembayaran_item as pbi')
			->join('tr_h3_md_penerimaan_pembayaran as pb', 'pb.id_penerimaan_pembayaran = pbi.id_penerimaan_pembayaran')
			->join('tr_h3_md_packing_sheet as ps', 'ps.no_faktur = pbi.referensi', 'left')
			->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list', 'left')
			->join('ms_dealer as d', 'd.id_dealer = pl.id_dealer', 'left')
			->join('tr_h3_md_do_sales_order as dso', 'dso.id_do_sales_order = pl.id_ref', 'left')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = dso.id_sales_order', 'left')
			->join('ms_karyawan as k', 'k.id_karyawan = pb.id_debt_collector', 'left')
			->where('pbi.id_penerimaan_pembayaran', $this->input->get('id_penerimaan_pembayaran'))
			->get()->result();

		$this->template($data);
	}

	public function validate()
	{
		$this->form_validation->set_error_delimiters('', '');
		// $this->form_validation->set_rules('id_group_dealer', 'Group Dealer', 'required');
		// $this->form_validation->set_rules('id_dealer', 'Customer', 'required');
		// $this->form_validation->set_rules('tanggal_bap', 'Tanggal BAP', 'required');
		// $this->form_validation->set_rules('id_debt_collector', 'Debt Collector', 'required');

		if ($this->input->post('jenis_pembayaran') == 'Cash') {
			$this->form_validation->set_rules('nominal_cash', 'Nominal Cash', 'required');
		} else if ($this->input->post('jenis_pembayaran') == 'BG') {
			$this->form_validation->set_rules('nomor_bg', 'Nomor BG', 'required');
			$this->form_validation->set_rules('nama_bank_bg', 'Nama Bank BG', 'required');
			$this->form_validation->set_rules('tanggal_jatuh_tempo_bg', 'Tanggal Jatuh Tempo BG', 'required');
			$this->form_validation->set_rules('nominal_bg', 'Nominal BG', 'required');
			$this->form_validation->set_rules('id_rekening_md_bg', 'Rekening Tujuan', 'required');
		} else if ($this->input->post('jenis_pembayaran') == 'Transfer') {
			$this->form_validation->set_rules('tanggal_transfer', 'Tanggal Transfer', 'required');
			$this->form_validation->set_rules('nominal_transfer', 'Nominal Transfer', 'required');
			$this->form_validation->set_rules('id_rekening_md_transfer', 'Rekening Tujuan', 'required');
		}

		$this->form_validation->set_rules('tanggal_proses', 'Tanggal Proses', 'required');

		if (!$this->form_validation->run()) {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
		}
	}

	public function download_excel()
	{
		$this->laporan_penerimaan_pembayaran->generateExcel($this->input->get('periode_awal'), $this->input->get('periode_akhir'));
	}

	public function download_pdf()
	{
		$this->laporan_penerimaan_pembayaran->generatePdf($this->input->get('periode_awal'), $this->input->get('periode_akhir'));
	}
}
