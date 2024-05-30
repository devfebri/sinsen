<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_ms_plafon_pimpinan extends Honda_Controller
{

	protected $folder = "h3";
	protected $page   = "h3_md_ms_plafon_pimpinan";
	protected $title  = "Master Plafon (Pimpinan)";

	public function __construct()
	{
		parent::__construct();
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');
		$this->load->model('H3_md_ms_plafon_model', 'plafon');
		$this->load->model('H3_md_ms_plafon_sales_orders_model', 'plafon_sales_orders');
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

	public function get_tagihan()
	{
		$tagihan = $this->db
			->select('ps.no_faktur')
			->select('date_format(now(), "%d-%m-%Y") as tgl_jatuh_tempo')
			->select('dso.total as nilai_faktur')
			->select('so.produk')
			->from('tr_h3_md_packing_sheet as ps')
			->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
			->join('tr_h3_md_do_sales_order as dso', 'dso.id_do_sales_order = pl.id_ref')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = dso.id_sales_order')
			->where('pl.id_dealer', $this->input->get('id_dealer'))
			->get()->result();

		send_json($tagihan);
	}

	public function get_rincian_pembayaran()
	{
		$rincian_pembayaran = $this->db
			->select('pp.jenis_pembayaran')
			->select('pp.nomor_bg as nomor_bg')
			->select('pp.tanggal_jatuh_tempo_bg')
			->select('
			case
				when pp.jenis_pembayaran = "Cash" then pp.nominal_cash
				when pp.jenis_pembayaran = "BG" then pp.nominal_bg
				when pp.jenis_pembayaran = "Transfer" then pp.nominal_transfer
			end as nominal
		', false)
			->from('tr_h3_md_penerimaan_pembayaran_item as ppi')
			->join('tr_h3_md_penerimaan_pembayaran as pp', 'pp.id_penerimaan_pembayaran = ppi.id_penerimaan_pembayaran')
			->where('ppi.referensi', $this->input->get('no_faktur'))
			->order_by('pp.created_at', 'desc')
			->get()->result();

		send_json($rincian_pembayaran);
	}

	public function get_sales_orders()
	{
		$sales_order_sudah_terbuat_do = $this->db
			->select('dso.id_sales_order')
			->from('tr_h3_md_do_sales_order as dso')
			->get_compiled_select();

		$data = $this->db
			->select('so.id_sales_order')
			->select('date_format(so.tanggal_order, "%d-%m-%Y") as tanggal_order')
			->select('so.po_type')
			->select('so.kategori_po')
			->select('so.produk')
			->select('so.total_amount')
			->from('tr_h3_md_sales_order as so')
			->where('so.id_dealer', $this->input->get('id_dealer'))
			->where('so.jenis_pembayaran', 'Credit')
			->where("so.id_sales_order not in ({$sales_order_sudah_terbuat_do})")
			->get()->result();

		send_json($data);
	}

	public function get_plafon_awal()
	{
		$plafon = $this->db
			->select('d.plafon_h3')
			->from('ms_dealer as d')
			->where('d.id_dealer', $this->input->get('id_dealer'))
			->get()->row();

		send_json($plafon);
	}

	public function get_nilai_po_part()
	{
		$purchase_order = $this->db
			->select('
		sum(
			(pop.kuantitas * pop.harga_saat_dibeli)
		)	
		as amount')
			->from('tr_h3_dealer_purchase_order as po')
			->join('tr_h3_dealer_purchase_order_parts as pop', 'po.po_id = pop.po_id')
			->join('ms_part as p', 'p.id_part = pop.id_part')
			->where('po.id_dealer', $this->input->get('id_dealer'))
			->where('po.produk', 'Parts')
			->get()->row();

		send_json($purchase_order);
	}

	public function get_nilai_po_oli()
	{
		$purchase_order = $this->db
			->select('
		sum(
			(pop.kuantitas * pop.harga_saat_dibeli)
		)	
		as amount')
			->from('tr_h3_dealer_purchase_order as po')
			->join('tr_h3_dealer_purchase_order_parts as pop', 'po.po_id = pop.po_id')
			->join('ms_part as p', 'p.id_part = pop.id_part')
			->where('po.id_dealer', $this->input->get('id_dealer'))
			->where('po.produk', 'Oli')
			->get()->row();

		send_json($purchase_order);
	}

	public function save()
	{
		$this->validate();
		$this->db->trans_start();
		$plafon = $this->input->post([
			'id_dealer', 'id_salesman', 'plafon_awal', 'nilai_penambahan_plafon', 'nilai_penambahan_sementara', 'nilai_pengurang_plafon',
			'total_plafon_baru', 'nilai_po_part', 'nilai_po_oli', 'sisa_plafon', 'total_plafon_baru', 'keterangan'
		]);
		$plafon['nilai_penambahan_plafon_pimpinan'] = $plafon['nilai_penambahan_plafon'];
		$plafon['nilai_penambahan_sementara_pimpinan'] = $plafon['nilai_penambahan_sementara'];
		$plafon['nilai_pengurang_plafon_pimpinan'] = $plafon['nilai_pengurang_plafon'];

		$this->plafon->insert($plafon);
		$id = $this->db->insert_id();
		$this->plafon->set_detail_plafon($id);
		$plafon_sales_order = $this->getOnly(['id_sales_order'], $this->input->post('sales_orders'), [
			'id_plafon' => $id
		]);
		if (count($plafon_sales_order) > 0) {
			$this->plafon_sales_orders->insert_batch($plafon_sales_order);
		}

		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_userdata('pesan', 'Berhasil menyimpan pengajuan plafon.');
			$this->session->set_userdata('tipe', 'info');
			$plafon = $this->plafon->find($id);
			send_json($plafon);
		} else {
			$this->session->set_userdata('pesan', 'Tidak berhasil menyimpan pengajuan plafon.');
			$this->session->set_userdata('tipe', 'danger');
			$this->output->set_status_header(400);
		}
	}

	public function detail()
	{
		$data['mode'] = 'detail';
		$data['set'] = "form";
		$data['plafon'] = $this->db
			->select('plafon.id')
			->select('plafon.id_dealer')
			->select('d.nama_dealer')
			->select('d.kode_dealer_md')
			->select('d.alamat')
			->select('d.status_dealer')
			->select('d.luas_bangunan')
			->select('plafon.plafon_awal')
			->select('plafon.plafon_booking')
			->select('plafon.sisa_plafon')
			->select('plafon.nilai_penambahan_plafon')
			->select('plafon.nilai_penambahan_sementara')
			->select('plafon.nilai_pengurang_plafon')
			->select('plafon.nilai_penambahan_plafon_finance')
			->select('plafon.nilai_penambahan_sementara_finance')
			->select('plafon.nilai_pengurang_plafon_finance')
			->select('plafon.nilai_penambahan_plafon_pimpinan')
			->select('plafon.nilai_penambahan_sementara_pimpinan')
			->select('plafon.nilai_pengurang_plafon_pimpinan')
			->select('plafon.total_plafon_baru')
			->select('plafon.nilai_po_part')
			->select('plafon.nilai_po_oli')
			->select('plafon.status')
			->select('plafon.keterangan')
			->select('plafon.keterangan_finance')
			->select('plafon.keterangan_pimpinan')
			->select('
			case
				when plafon.create_by_finance = 1 then null
				else plafon.approve_at
			end as approve_at
		', false)
			->select('plafon.approved_finance_at')
			->select('plafon.approved_pimpinan_at')
			->select('k.nama_lengkap as nama_salesman')
			->from('ms_h3_md_plafon as plafon')
			->join('ms_dealer as d', 'd.id_dealer = plafon.id_dealer')
			->join('ms_karyawan as k', 'k.id_karyawan = plafon.id_salesman', 'left')
			->where('plafon.id', $this->input->get('id'))
			->get()->row();

		$data['sales_orders'] = $this->db
			->select('so.id_sales_order')
			->select('date_format(so.tanggal_order, "%d-%m-%Y") as tanggal_order')
			->select('so.po_type')
			->select('so.kategori_po')
			->select('so.produk')
			->select('pso.total_amount')
			->select('pso.checked')
			->from('ms_h3_md_plafon_sales_orders as pso')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = pso.id_sales_order')
			->where('pso.id_plafon', $this->input->get('id'))
			->get()->result();

		$this->template($data);
	}

	public function edit()
	{
		$data['mode']    = 'edit';
		$data['set']     = "form";
		$data['plafon'] = $this->db
			->select('plafon.id')
			->select('plafon.id_dealer')
			->select('d.nama_dealer')
			->select('d.kode_dealer_md')
			->select('d.alamat')
			->select('d.status_dealer')
			->select('d.luas_bangunan')
			->select('plafon.plafon_awal')
			->select('plafon.plafon_booking')
			->select('plafon.sisa_plafon')
			->select('plafon.nilai_penambahan_plafon')
			->select('plafon.nilai_penambahan_sementara')
			->select('plafon.nilai_pengurang_plafon')
			->select('plafon.nilai_penambahan_plafon_finance')
			->select('plafon.nilai_penambahan_sementara_finance')
			->select('plafon.nilai_pengurang_plafon_finance')
			->select('plafon.nilai_penambahan_plafon_pimpinan')
			->select('plafon.nilai_penambahan_sementara_pimpinan')
			->select('plafon.nilai_pengurang_plafon_pimpinan')
			->select('plafon.total_plafon_baru')
			->select('plafon.nilai_po_part')
			->select('plafon.nilai_po_oli')
			->select('plafon.keterangan')
			->select('plafon.keterangan_finance')
			->select('plafon.keterangan_pimpinan')
			->select('
			case
				when plafon.create_by_finance = 1 then null
				else plafon.approve_at
			end as approve_at
		', false)
			->select('plafon.approved_finance_at')
			->select('plafon.approved_pimpinan_at')
			->select('k.nama_lengkap as nama_salesman')
			->from('ms_h3_md_plafon as plafon')
			->join('ms_dealer as d', 'd.id_dealer = plafon.id_dealer')
			->join('ms_karyawan as k', 'k.id_karyawan = plafon.id_salesman', 'left')
			->where('plafon.id', $this->input->get('id'))
			->get()->row();

		$data['sales_orders'] = $this->db
			->select('so.id_sales_order')
			->select('date_format(so.tanggal_order, "%d-%m-%Y") as tanggal_order')
			->select('so.po_type')
			->select('so.kategori_po')
			->select('so.produk')
			->select('pso.total_amount')
			->select('pso.checked')
			->from('ms_h3_md_plafon_sales_orders as pso')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = pso.id_sales_order')
			->where('pso.id_plafon', $this->input->get('id'))
			->get()->result();

		$this->template($data);
	}

	public function update()
	{
		$this->validate();

		$this->db->trans_start();
		$plafon = $this->input->post([
			'plafon_awal', 'nilai_penambahan_plafon', 'nilai_penambahan_sementara', 'nilai_pengurang_plafon',
			'nilai_penambahan_plafon_pimpinan', 'nilai_penambahan_sementara_pimpinan', 'nilai_pengurang_plafon_pimpinan',
			'total_plafon_baru', 'nilai_po_part', 'nilai_po_oli', 'sisa_plafon', 'total_plafon_baru', 'keterangan_pimpinan'
		]);

		$this->plafon->update($plafon, $this->input->post(['id']));
		$this->plafon->set_detail_plafon($this->input->post('id'));
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_userdata('pesan', 'Berhasil memperbarui pengajuan plafon.');
			$this->session->set_userdata('tipe', 'info');
			$plafon = $this->plafon->find($this->input->post('id'));
			send_json($plafon);
		} else {
			$this->session->set_userdata('pesan', 'Tidak berhasil memperbarui pengajuan plafon.');
			$this->session->set_userdata('tipe', 'danger');
			$this->output->set_status_header(400);
		}
	}

	public function approve()
	{
		$this->db->trans_start();
		$plafon = $this->plafon->find($this->input->get('id'));

		$plafon_approved = $this->plafon->get([
			'id' => $this->input->get('id'),
			'status' => 'Approved by Pimpinan'
		], true);

		if ($plafon_approved != null) {
			send_json([
				'message' => 'Plafon sudah pernah diapprove sebelumnya',
			], 422);
		}

		$this->plafon->update([
			'approved_pimpinan_at' => date('Y-m-d H:i:s', time()),
			'approved_pimpinan_by' => $this->session->userdata('id_user'),
			'status' => 'Approved by Pimpinan'
		], $this->input->get(['id']));

		$nilai_penambahan_plafon = $plafon->nilai_penambahan_plafon_pimpinan - $plafon->nilai_pengurang_plafon_pimpinan;
		$this->db->set('plafon_h3', "plafon_h3 + {$nilai_penambahan_plafon}", FALSE)
			->where('id_dealer', $plafon->id_dealer)
			->update('ms_dealer');

		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_userdata('pesan', 'Berhasil menyetujui pengajuan plafon.');
			$this->session->set_userdata('tipe', 'info');
			send_json($plafon);
		} else {
			$this->session->set_userdata('pesan', 'Tidak berhasil menyetujui pengajuan plafon.');
			$this->session->set_userdata('tipe', 'danger');
			$this->output->set_status_header(400);
		}
	}

	public function close()
	{
		$this->db->trans_start();
		$this->plafon->update([
			'closed_pimpinan_at' => date('Y-m-d H:i:s', time()),
			'closed_pimpinan_by' => $this->session->userdata('id_user'),
			'status' => 'Closed by Pimpinan'
		], $this->input->get(['id']));
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$this->session->set_userdata('pesan', 'Berhasil men-close pengajuan plafon.');
			$this->session->set_userdata('tipe', 'info');
			$plafon = $this->plafon->find($this->input->get('id'));
			send_json($plafon);
		} else {
			$this->session->set_userdata('pesan', 'Tidak berhasil men-close pengajuan plafon.');
			$this->session->set_userdata('tipe', 'danger');
			$this->output->set_status_header(400);
		}
	}

	public function validate()
	{
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id_dealer', 'Dealer', 'required');
		$this->form_validation->set_rules('nilai_penambahan_plafon', 'Nilai Penambahan Plafon', 'required');
		$this->form_validation->set_rules('nilai_penambahan_sementara', 'Nilai Penambahan Sementara', 'required');
		$this->form_validation->set_rules('nilai_pengurang_plafon', 'Nilai Pengurangan Plafon', 'required');

		if (!$this->form_validation->run()) {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
		}
	}

	public function memo()
	{
		$this->load->model('H3_md_memo_plafon_model', 'memo_plafon');

		$ids = $this->session->userdata('plafon_id_pimpinan');
		$this->session->unset_userdata('plafon_id_pimpinan');

		if ($ids == null) {
			$this->session->set_userdata('pesan', 'Tidak ada plafon yang di check.');
			$this->session->set_userdata('tipe', 'warning');

			redirect(
				base_url('h3/h3_md_ms_plafon_pimpinan')
			);
		}

		$this->memo_plafon->generate($ids, $this->input->get('filetype'));
	}
}
