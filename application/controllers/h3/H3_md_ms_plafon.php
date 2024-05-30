<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_ms_plafon extends Honda_Controller
{

	protected $folder = "h3";
	protected $page   = "h3_md_ms_plafon";
	protected $title  = "Master Plafon";

	public function set_detail_plafon()
	{
		$plafon = (array) $this->plafon->all();
		foreach ($plafon as $row) {
			$row = (array) $row;
			$this->plafon->set_detail_plafon($row['id']);
		}
	}

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
		$data = $this->plafon->get_faktur($this->input->get('id_dealer'), true);
		send_json($data);
	}

	public function get_rincian_pembayaran()
	{
		$rincian_pembayaran = $this->plafon->get_rincian_pembayaran($this->input->get('no_faktur'));
		send_json($rincian_pembayaran);
	}

	public function get_sales_orders()
	{
		$this->load->model('h3_md_do_sales_order_parts_model', 'do_parts');
		$mode = $this->input->get('mode');
		$id = $this->input->get('id');

		$sales_orders = $this->db
			->select('so.id_sales_order')
			->select('date_format(so.tanggal_order, "%d-%m-%Y") as tanggal_order')
			->select('so.po_type')
			->select('so.kategori_po')
			->select('so.produk')
			->select("
			case
				when '{$mode}' != 'insert' then pso.checked IS NOT NULL
				else 1
			end checked
		", false)
			->from('tr_h3_md_sales_order as so')
			->join('ms_h3_md_plafon_sales_orders as pso', "(pso.id_plafon = '{$id}' AND pso.id_sales_order = so.id_sales_order)", 'left')
			->where('so.id_dealer', $this->input->get('id_dealer'))
			->where('so.jenis_pembayaran', 'Credit')
			->where('so.status !=', 'Closed')
			->where('so.status !=', 'Canceled')
			->where('so.kategori_po !=', 'KPB')
			->where('so.gimmick', '0')
			->order_by('so.created_at', 'desc')
			->get()->result_array();

		$data = [];
		foreach ($sales_orders as $sales_order) {
			$qty_do = $this->db
				->select('SUM(dop.qty_supply) as qty_supply', false)
				->from('tr_h3_md_do_sales_order as do')
				->join('tr_h3_md_do_sales_order_parts as dop', 'dop.id_do_sales_order = do.id_do_sales_order')
				->where('do.id_sales_order = sop.id_sales_order', null, false)
				->where('dop.id_part = sop.id_part', null, false)
				->group_start()
				->where('do.status !=', 'Canceled')
				->where('do.status !=', 'Rejected')
				->group_end()
				->get_compiled_select();

			$parts = $this->db
				->select('sop.id_part')
				->select('sop.qty_pemenuhan')
				->select("IFNULL(({$qty_do}), 0) as qty_do", false)
				->select('sop.harga as harga_jual')
				->select('sop.tipe_diskon as tipe_diskon_satuan_dealer')
				->select('sop.diskon_value as diskon_satuan_dealer')
				->select('sop.tipe_diskon_campaign')
				->select('sop.diskon_value_campaign as diskon_campaign')
				->select('sc.jenis_diskon_campaign')
				->from('tr_h3_md_sales_order_parts as sop')
				->join('ms_h3_md_sales_campaign as sc', 'sc.id = sop.id_campaign_diskon', 'left')
				->where('sop.id_sales_order', $sales_order['id_sales_order'])
				->get()->result_array();
			$parts = array_map(function ($row) {
				$row['harga_setelah_diskon'] = $this->do_parts->harga_setelah_diskon($row);
				$row['sisa_so'] = $row['qty_pemenuhan'] - $row['qty_do'];
				$row['amount'] = $row['harga_setelah_diskon'] * $row['sisa_so'];
				return $row;
			}, $parts);

			$sales_order['total_amount'] = array_sum(
				array_map(function ($part) {
					return $part['amount'];
				}, $parts)
			);

			if ($sales_order['total_amount'] != 0) {
				$data[] = $sales_order;
			}
		}

		send_json($data);
	}

	public function get_plafon_awal()
	{
		$this->load->model('H3_md_ms_plafon_model', 'plafon');

		$data = [
			'plafon_h3' => 0,
			'plafon_booking' => 0
		];

		$id_dealer = $this->input->get('id_dealer');
		$plafon = $this->db
			->select('d.plafon_h3')
			->from('ms_dealer as d')
			->where('d.id_dealer', $id_dealer)
			->get()->row_array();

		if($plafon != null){
			$data['plafon_h3'] = floatval($plafon['plafon_h3']);
		}

		$data['plafon_booking'] = $this->plafon->get_plafon_booking($id_dealer);

		send_json($data);
	}

	public function get_nilai_po_part()
	{
		$sales_orders = $this->db
			->select('so.total_amount as amount')
			->from('tr_h3_md_sales_order as so')
			->where('so.produk', 'Parts')
			->where('so.id_dealer', $this->input->get('id_dealer'))
			->get()->result_array();

		$total = 0;
		foreach ($sales_orders as $sales_order) {
			$total += (float) $sales_order['amount'];
		}

		send_json([
			'amount' => $total
		]);
	}

	public function get_nilai_po_oli()
	{
		$sales_orders = $this->db
			->select('so.total_amount as amount')
			->from('tr_h3_md_sales_order as so')
			->where('so.produk', 'Oil')
			->where('so.id_dealer', $this->input->get('id_dealer'))
			->get()->result_array();

		$total = 0;
		foreach ($sales_orders as $sales_order) {
			$total += (float) $sales_order['amount'];
		}

		send_json([
			'amount' => $total
		]);
	}

	public function save()
	{
		$this->validate();
		$this->db->trans_start();
		$plafon = $this->input->post([
			'id_dealer', 'id_salesman', 'plafon_awal', 'plafon_booking', 'nilai_penambahan_plafon', 'nilai_penambahan_sementara', 'nilai_pengurang_plafon',
			'total_plafon_baru', 'nilai_po_part', 'nilai_po_oli', 'sisa_plafon', 'total_plafon_baru', 'keterangan', 'grand_total_nilai_po'
		]);
		$plafon = $this->clean_data($plafon);

		$this->plafon->insert($plafon);
		$id = $this->db->insert_id();
		$this->plafon->set_detail_plafon($id);
		$plafon_sales_order = $this->getOnly(['id_sales_order', 'total_amount', 'checked'], $this->input->post('sales_orders'), [
			'id_plafon' => $id
		]);

		if (count($plafon_sales_order) > 0) {
			$this->plafon_sales_orders->insert_batch($plafon_sales_order);
		}

		$this->db->trans_complete();

		$plafon = (array) $this->plafon->find($id);
		if ($this->db->trans_status() and $plafon != null) {
			$message = 'Berhasil menyimpan pengajuan plafon.';
			$this->session->set_userdata('pesan', $message);
			$this->session->set_userdata('tipe', 'info');

			send_json([
				'message' => $message,
				'payload' => $plafon,
				'redirect_url' => base_url(sprintf('h3/h3_md_ms_plafon/detail?id=%s', $plafon['id']))
			]);
		} else {
			send_json([
				'message' => 'Tidak berhasil menyimpan pengajuan plafon.'
			], 422);
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
			->select('k.nama_lengkap as nama_salesman')
			->select('plafon.plafon_awal')
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
			->select('so.total_amount')
			->select('pso.checked')
			->from('ms_h3_md_plafon_sales_orders as pso')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = pso.id_sales_order')
			->where('pso.id_plafon', $this->input->get('id'))
			->order_by('so.created_at', 'desc')
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
			->select('k.nama_lengkap as nama_salesman')
			->select('d.luas_bangunan')
			->select('plafon.plafon_awal')
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
			->order_by('so.created_at', 'desc')
			->get()->result();

		$this->template($data);
	}

	public function update()
	{
		$this->validate();

		$this->db->trans_start();
		$plafon = $this->input->post([
			'plafon_awal','plafon_booking', 'nilai_penambahan_plafon', 'nilai_penambahan_sementara', 'nilai_pengurang_plafon',
			'total_plafon_baru', 'nilai_po_part', 'nilai_po_oli', 'sisa_plafon', 'total_plafon_baru', 'keterangan'
		]);

		$this->plafon->update($plafon, $this->input->post(['id']));
		$this->plafon->set_detail_plafon($this->input->post('id'));
		$plafon_sales_order = $this->getOnly(['id_sales_order', 'total_amount', 'checked'], $this->input->post('sales_orders'), [
			'id_plafon' => $this->input->post('id')
		]);
		$this->plafon_sales_orders->delete($this->input->post('id'), 'id_plafon');
		if (count($plafon_sales_order) > 0) {
			$this->plafon_sales_orders->insert_batch($plafon_sales_order);
		}
		$this->db->trans_complete();

		$plafon = (array) $this->plafon->find($this->input->post('id'));
		if ($this->db->trans_status() and $plafon != null) {
			$message = 'Berhasil memperbarui pengajuan plafon.';
			$this->session->set_userdata('pesan', $message);
			$this->session->set_userdata('tipe', 'info');

			send_json([
				'message' => $message,
				'payload' => $plafon,
				'redirect_url' => base_url(sprintf('h3/h3_md_ms_plafon/detail?id=%s', $plafon['id']))
			]);
		} else {
			send_json([
				'message' => 'Tidak berhasil memperbarui pengajuan plafon.',
			], 422);
		}
	}

	public function approve()
	{
		$this->db->trans_start();
		$plafon = $this->plafon->find($this->input->get('id'));

		$this->plafon->update([
			'approve_at' => date('Y-m-d H:i:s', time()),
			'approve_by' => $this->session->userdata('id_user'),
			'status' => 'Approved by Admin'
		], $this->input->get(['id']));

		// $nilai_penambahan_plafon = $plafon->nilai_penambahan_plafon_pimpinan - $plafon->nilai_pengurang_plafon_pimpinan;
		// $this->db->set('plafon_h3', "plafon_h3 + {$nilai_penambahan_plafon}", FALSE)
		// ->where('id_dealer', $plafon->id_dealer)
		// ->update('ms_dealer');

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
			'close_at' => date('Y-m-d H:i:s', time()),
			'close_by' => $this->session->userdata('id_user'),
			'status' => 'Closed by Admin'
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
		$this->form_validation->set_rules('keterangan', 'Keterangan', 'required');

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

		$ids = $this->session->userdata('plafon_id_marketing');
		$filetype = $this->input->get('filetype');
		$this->session->unset_userdata('plafon_id_marketing');

		if ($ids == null) {
			$this->session->set_userdata('pesan', 'Tidak ada plafon yang di check.');
			$this->session->set_userdata('tipe', 'warning');

			redirect(
				base_url('h3/h3_md_ms_plafon')
			);
		}

		$this->memo_plafon->generate($ids, $filetype);
	}
}
