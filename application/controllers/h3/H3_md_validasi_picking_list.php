<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_validasi_picking_list extends Honda_Controller
{
	protected $folder = "h3";
	protected $page   = "h3_md_validasi_picking_list";
	protected $title  = "Validasi Picking List";

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
		if ($name == "" or $auth == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
		} elseif ($sess == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "crash'>";
		}

		$this->load->model('H3_md_picking_list_model', 'picking_list');
		$this->load->model('h3_md_picking_list_parts_model', 'picking_list_parts');
		$this->load->model('h3_md_do_revisi_model', 'do_revisi');
		$this->load->model('h3_md_do_revisi_item_model', 'do_revisi_item');
		$this->load->model('part_model', 'master_part');
		$this->load->model('dealer_model', 'dealer');
		$this->load->model('karyawan_md_model', 'karyawan_md');
		$this->load->model('H3_md_stock_model', 'stock');
		$this->load->model('notifikasi_model', 'notifikasi');
		$this->load->model('H3_dealer_order_parts_tracking_model', 'order_parts_tracking');
		$this->load->model('H3_md_do_sales_order_model', 'delivery_order');
	}

	public function index()
	{
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$data['picking_list'] = $this->picking_list->all();
		$this->template($data);
	}

	public function open()
	{
		$this->picking_list->update([
			'status' => 'Open',
		], [
			'id_picking_list' => $this->input->get('id_picking_list'),
			'status' => ''
		]);

		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['picking'] = $this->db
			->select('pl.id_picking_list')
			->select('date_format(pl.created_at, "%d-%m-%Y") as tanggal_picking')
			->select('so.po_type')
			->select('so.kategori_po')
			->select('so.id_sales_order')
			->select('date_format(so.tanggal_order, "%d-%m-%Y") as tanggal_so')
			->select('date_format(do.created_at, "%d-%m-%Y") as tanggal_do')
			->select('k.nama_lengkap as nama_picker')
			->select('d.nama_dealer')
			->select('d.alamat')
			->select('do.id_do_sales_order')
			->select('pl.status')
			->select('pl.start_pick')
			->select('pl.end_pick')
			->select('do.total')
			->select('salesman.nama_lengkap as nama_salesman')
			->select('
			case
				when pl.revisi_validasi = 1 then "Ya"
				when pl.revisi_validasi = 0 then "Tidak"
			end revisi_validasi
		', false)
			->select('po_aksesoris.id_paket_bundling')
			->from('tr_h3_md_picking_list as pl')
			->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
			->join('tr_po_aksesoris as po_aksesoris', 'po_aksesoris.no_po_aksesoris = so.referensi_po_bundling', 'left')
			->join('ms_karyawan as salesman', 'salesman.id_karyawan = so.id_salesman', 'left')
			->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
			->join('ms_karyawan as k', 'k.id_karyawan = pl.id_picker', 'left')
			->where('pl.id_picking_list', $this->input->get('id_picking_list'))
			->limit(1)
			->get()->row_array();

		$this->db
			->select('plp.id_part')
			->select('p.nama_part')
			->select('p.kelompok_part')
			->select('dop.qty_supply as qty_do')
			->select('plp.qty_supply as qty_picking')
			->select('plp.qty_disiapkan')
			->select('lr.kode_lokasi_rak as nama_lokasi')
			->select('plp.id_lokasi_rak')
			->select('plp.serial_number')
			->select('plp.recheck')
			->from('tr_h3_md_picking_list_parts as plp')
			->join('ms_h3_md_lokasi_rak as lr', 'lr.id = plp.id_lokasi_rak')
			->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = plp.id_picking_list')
			->join('ms_part as p', 'p.id_part = plp.id_part')
			->where('plp.id_picking_list', $this->input->get('id_picking_list'));

		if ($data['picking']['kategori_po'] == 'KPB') {
			$this->db->select('plp.id_tipe_kendaraan');
			$this->db->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_part = plp.id_part and pl.id_ref = dop.id_do_sales_order and plp.id_tipe_kendaraan = dop.id_tipe_kendaraan)');
		} else {
			$this->db->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_part = plp.id_part and pl.id_ref = dop.id_do_sales_order)');
		}

		$parts = $this->db->get()->result_array();

		$parts = array_map(function ($data) {
			$data['qty_on_hand'] = $this->stock->qty_on_hand($data['id_part'], $data['id_lokasi_rak']);
			return $data;
		}, $parts);

		$data['parts'] = $parts;

		if ($this->input->get('status') == 'start') {
			$picking_list = $this->picking_list->find($this->input->get('id_picking_list'), 'id_picking_list');
			$this->picking_list->set_status_picking_list_recheck($this->input->get('id_picking_list'));

			$this->delivery_order->set_status_picking_list($picking_list->id_ref);

			$this->picking_list->set_start_pick($this->input->get('id_picking_list'));
			$this->picking_list->set_validation_start($this->input->get('id_picking_list'));
		}

		$this->template($data);
	}

	public function close()
	{
		$id_picking_list = $this->input->post('id_picking_list');
		$this->db->trans_start();
		$this->picking_list->check_picking_list_has_picker($id_picking_list);
		$this->picking_list->set_end_pick($id_picking_list);
		$this->picking_list->set_validation_end($id_picking_list);

		// Update qty parts jika ada.
		if ($this->input->post('parts') != null and count($this->input->post('parts')) > 0) 
		{
			foreach ($this->input->post('parts') as $part) {
				$kelompok_part = $this->db->select('kelompok_part')
									  ->from('ms_part')
									  ->where('id_part', $part['id_part'])
									  ->get()->row_array();

				if($kelompok_part['kelompok_part'] == 'EVBT' || $kelompok_part['kelompok_part'] == 'EVCH'){
					$this->db->set('qty_disiapkan', $part['qty_disiapkan']);
					$this->db->where('id_picking_list', $id_picking_list);
					$this->db->where('id_part', $part['id_part']);
					if ($this->input->post('kategori_po') != null and $this->input->post('kategori_po') == 'KPB') {
						$this->db->where('id_tipe_kendaraan', $part['id_tipe_kendaraan']);
					}
					$this->db->where('id_lokasi_rak', $part['id_lokasi_rak']);
					$this->db->where('serial_number', $part['serial_number']);
					$this->db->update('tr_h3_md_picking_list_parts');
				}else{
					$this->db->set('qty_disiapkan', $part['qty_disiapkan']);
					$this->db->where('id_picking_list', $id_picking_list);
					$this->db->where('id_part', $part['id_part']);
					if ($this->input->post('kategori_po') != null and $this->input->post('kategori_po') == 'KPB') {
						$this->db->where('id_tipe_kendaraan', $part['id_tipe_kendaraan']);
					}
					$this->db->where('id_lokasi_rak', $part['id_lokasi_rak']);
					$this->db->update('tr_h3_md_picking_list_parts');
				}
			}
		}

		$this->picking_list_parts->update([
			'recheck' => 0
		], $this->input->post(['id_picking_list']));

		$data = $this->db
			->select('d.nama_dealer')
			->select('pl.id_picking_list')
			->select('so.id_ref')
			->select('so.kategori_po')
			->select('so.id_rekap_purchase_order_dealer')
			->from('tr_h3_md_picking_list as pl')
			->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
			->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
			->where('pl.id_picking_list', $id_picking_list)
			->get()->row_array();

		$menu_kategori = $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'validasi_picking_list_close')->get()->row_array();
		if ($menu_kategori != null) {
			$this->notifikasi->insert([
				'id_notif_kat' => $menu_kategori['id_notif_kat'],
				'judul' => $menu_kategori['nama_kategori'],
				'pesan' => "No. Picking List {$data['id_picking_list']} a.n {$data['nama_dealer']} telah selesai picking. Segera di Re-check kembali.",
				'link' => "{$menu_kategori['link']}",
				'show_popup' => $menu_kategori['popup'],
			]);
		}

		if ($this->input->post('parts') and count($this->input->post('parts')) > 0) {
			foreach ($this->input->post('parts') as $part) {
				if ($this->input->post('kategori_po') != null and $this->input->post('kategori_po') == 'KPB') {
					$this->order_parts_tracking->tambah_qty_pick($data['id_ref'], $part['id_part'], $part['qty_disiapkan'], $part['id_tipe_kendaraan']);
				} else {
					$this->order_parts_tracking->tambah_qty_pick($data['id_ref'], $part['id_part'], $part['qty_disiapkan']);
				}
				if ($data['id_rekap_purchase_order_dealer'] != null) {
					$jumlah_item = $this->db
						->select('SUM( pop.kuantitas - ppd.qty_supply) as jumlah_item', false)
						->from('tr_h3_dealer_purchase_order_parts as pop')
						->join('tr_h3_md_pemenuhan_po_dari_dealer as ppd', '(ppd.po_id = pop.po_id AND ppd.id_part = pop.id_part)')
						->where('pop.po_id = po.po_id', null, false)
						->get_compiled_select();

					$this->db
						->select('po.po_id')
						->select('pop.id_part')
						->select('pop.id_tipe_kendaraan')
						->select('(opt.qty_book - opt.qty_pick) as selisih', false)
						->select("IFNULL(({$jumlah_item}), 0) as jumlah_item", false)
						->from('tr_h3_md_rekap_purchase_order_dealer_item as ri')
						->join('tr_h3_dealer_purchase_order as po', 'po.po_id = ri.id_referensi')
						->join('tr_h3_dealer_purchase_order_parts as pop', 'pop.po_id = ri.id_referensi')
						->join('tr_h3_dealer_order_parts_tracking as opt', '(opt.po_id = pop.po_id and opt.id_part = pop.id_part)')
						->where('ri.id_rekap', $data['id_rekap_purchase_order_dealer'])
						->where('pop.id_part', $part['id_part'])
						->order_by('jumlah_item', 'asc')
						->order_by('po.created_at', 'desc');

					if ($this->input->post('kategori_po') == 'KPB') {
						$this->db->select('pop.id_tipe_kendaraan');
						$this->db->where('pop.id_tipe_kendaraan', $part['id_tipe_kendaraan']);
					}

					$purchase_orders = $this->db->get()->result_array();

					$supply_untuk_dipecah = $part['qty_disiapkan'];
					foreach ($purchase_orders as $purchase_order) {
						if ($purchase_order['selisih'] <= $supply_untuk_dipecah) {
							if ($this->input->post('kategori_po') != null and $this->input->post('kategori_po') == 'KPB') {
								$this->order_parts_tracking->tambah_qty_pick($purchase_order['po_id'], $purchase_order['id_part'], $purchase_order['selisih'], $purchase_order['id_tipe_kendaraan']);
							} else {
								$this->order_parts_tracking->tambah_qty_pick($purchase_order['po_id'], $purchase_order['id_part'], $purchase_order['selisih']);
							}
							$supply_untuk_dipecah -= $purchase_order['selisih'];
						} else if ($purchase_order['selisih'] >= $supply_untuk_dipecah) {
							if ($this->input->post('kategori_po') != null and $this->input->post('kategori_po') == 'KPB') {
								$this->order_parts_tracking->tambah_qty_pick($purchase_order['po_id'], $purchase_order['id_part'], $supply_untuk_dipecah, $purchase_order['id_tipe_kendaraan']);
							} else {
								$this->order_parts_tracking->tambah_qty_pick($purchase_order['po_id'], $purchase_order['id_part'], $supply_untuk_dipecah);
							}
							break;
						}

						if ($supply_untuk_dipecah == 0) break;
					}
				}
			}
		}

		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			echo "h3/h3_md_validasi_picking_list/open?id_picking_list={$id_picking_list}";
			die;
		} else {
			$this->output->set_status_header(400);
		}
	}

	public function close_old()
	{
		$id_picking_list = $this->input->post('id_picking_list');
		$this->db->trans_start();
		$this->picking_list->check_picking_list_has_picker($id_picking_list);
		$this->picking_list->set_end_pick($id_picking_list);
		$this->picking_list->set_validation_end($id_picking_list);

		// Update qty parts jika ada.
		if ($this->input->post('parts') != null and count($this->input->post('parts')) > 0) {
			foreach ($this->input->post('parts') as $part) {
				$this->db->set('qty_disiapkan', $part['qty_disiapkan']);
				$this->db->where('id_picking_list', $id_picking_list);
				$this->db->where('id_part', $part['id_part']);
				if ($this->input->post('kategori_po') != null and $this->input->post('kategori_po') == 'KPB') {
					$this->db->where('id_tipe_kendaraan', $part['id_tipe_kendaraan']);
				}
				$this->db->where('id_lokasi_rak', $part['id_lokasi_rak']);
				$this->db->update('tr_h3_md_picking_list_parts');
			}
		}

		$this->picking_list_parts->update([
			'recheck' => 0
		], $this->input->post(['id_picking_list']));

		$data = $this->db
			->select('d.nama_dealer')
			->select('pl.id_picking_list')
			->select('so.id_ref')
			->select('so.kategori_po')
			->select('so.id_rekap_purchase_order_dealer')
			->from('tr_h3_md_picking_list as pl')
			->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
			->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
			->where('pl.id_picking_list', $id_picking_list)
			->get()->row_array();

		$menu_kategori = $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'validasi_picking_list_close')->get()->row_array();
		if ($menu_kategori != null) {
			$this->notifikasi->insert([
				'id_notif_kat' => $menu_kategori['id_notif_kat'],
				'judul' => $menu_kategori['nama_kategori'],
				'pesan' => "No. Picking List {$data['id_picking_list']} a.n {$data['nama_dealer']} telah selesai picking. Segera di Re-check kembali.",
				'link' => "{$menu_kategori['link']}",
				'show_popup' => $menu_kategori['popup'],
			]);
		}

		if ($this->input->post('parts') and count($this->input->post('parts')) > 0) {
			foreach ($this->input->post('parts') as $part) {
				if ($this->input->post('kategori_po') != null and $this->input->post('kategori_po') == 'KPB') {
					$this->order_parts_tracking->tambah_qty_pick($data['id_ref'], $part['id_part'], $part['qty_disiapkan'], $part['id_tipe_kendaraan']);
				} else {
					$this->order_parts_tracking->tambah_qty_pick($data['id_ref'], $part['id_part'], $part['qty_disiapkan']);
				}
				if ($data['id_rekap_purchase_order_dealer'] != null) {
					$jumlah_item = $this->db
						->select('SUM( pop.kuantitas - ppd.qty_supply) as jumlah_item', false)
						->from('tr_h3_dealer_purchase_order_parts as pop')
						->join('tr_h3_md_pemenuhan_po_dari_dealer as ppd', '(ppd.po_id = pop.po_id AND ppd.id_part = pop.id_part)')
						->where('pop.po_id = po.po_id', null, false)
						->get_compiled_select();

					$this->db
						->select('po.po_id')
						->select('pop.id_part')
						->select('pop.id_tipe_kendaraan')
						->select('(opt.qty_book - opt.qty_pick) as selisih', false)
						->select("IFNULL(({$jumlah_item}), 0) as jumlah_item", false)
						->from('tr_h3_md_rekap_purchase_order_dealer_item as ri')
						->join('tr_h3_dealer_purchase_order as po', 'po.po_id = ri.id_referensi')
						->join('tr_h3_dealer_purchase_order_parts as pop', 'pop.po_id = ri.id_referensi')
						->join('tr_h3_dealer_order_parts_tracking as opt', '(opt.po_id = pop.po_id and opt.id_part = pop.id_part)')
						->where('ri.id_rekap', $data['id_rekap_purchase_order_dealer'])
						->where('pop.id_part', $part['id_part'])
						->order_by('jumlah_item', 'asc')
						->order_by('po.created_at', 'desc');

					if ($this->input->post('kategori_po') == 'KPB') {
						$this->db->select('pop.id_tipe_kendaraan');
						$this->db->where('pop.id_tipe_kendaraan', $part['id_tipe_kendaraan']);
					}

					$purchase_orders = $this->db->get()->result_array();

					$supply_untuk_dipecah = $part['qty_disiapkan'];
					foreach ($purchase_orders as $purchase_order) {
						if ($purchase_order['selisih'] <= $supply_untuk_dipecah) {
							if ($this->input->post('kategori_po') != null and $this->input->post('kategori_po') == 'KPB') {
								$this->order_parts_tracking->tambah_qty_pick($purchase_order['po_id'], $purchase_order['id_part'], $purchase_order['selisih'], $purchase_order['id_tipe_kendaraan']);
							} else {
								$this->order_parts_tracking->tambah_qty_pick($purchase_order['po_id'], $purchase_order['id_part'], $purchase_order['selisih']);
							}
							$supply_untuk_dipecah -= $purchase_order['selisih'];
						} else if ($purchase_order['selisih'] >= $supply_untuk_dipecah) {
							if ($this->input->post('kategori_po') != null and $this->input->post('kategori_po') == 'KPB') {
								$this->order_parts_tracking->tambah_qty_pick($purchase_order['po_id'], $purchase_order['id_part'], $supply_untuk_dipecah, $purchase_order['id_tipe_kendaraan']);
							} else {
								$this->order_parts_tracking->tambah_qty_pick($purchase_order['po_id'], $purchase_order['id_part'], $supply_untuk_dipecah);
							}
							break;
						}

						if ($supply_untuk_dipecah == 0) break;
					}
				}
			}
		}

		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			echo "h3/h3_md_validasi_picking_list/open?id_picking_list={$id_picking_list}";
			die;
		} else {
			$this->output->set_status_header(400);
		}
	}

	public function simpan()
	{
		$id_picking_list = $this->input->post('id_picking_list');
		$this->db->trans_start();
		$this->picking_list->update([
			'status' => 'On Process',
		], [
			'id_picking_list' => $id_picking_list,
			'status' => 'Open'
		]);

		foreach ($this->input->post('parts') as $part) {
			// Cek apakah EV atau tidak 
			$kelompok_part = $this->db->select('kelompok_part')
									  ->from('ms_part')
									  ->where('id_part', $part['id_part'])
									  ->get()->row_array();

			if($kelompok_part['kelompok_part'] == 'EVBT' || $kelompok_part['kelompok_part'] == 'EVCH'){
				$this->db->set('qty_disiapkan', $part['qty_disiapkan']);
				$this->db->where('id_picking_list', $id_picking_list);
				if ($this->input->post('kategori_po') != null and $this->input->post('kategori_po') == 'KPB') {
					$this->db->where('id_tipe_kendaraan', $part['id_tipe_kendaraan']);
				}
				$this->db->where('id_part', $part['id_part']);
				$this->db->where('id_lokasi_rak', $part['id_lokasi_rak']);
				$this->db->where('serial_number', $part['serial_number']);
				$this->db->update('tr_h3_md_picking_list_parts');
			}else{
				$this->db->set('qty_disiapkan', $part['qty_disiapkan']);
				$this->db->where('id_picking_list', $id_picking_list);
				if ($this->input->post('kategori_po') != null and $this->input->post('kategori_po') == 'KPB') {
					$this->db->where('id_tipe_kendaraan', $part['id_tipe_kendaraan']);
				}
				$this->db->where('id_part', $part['id_part']);
				$this->db->where('id_lokasi_rak', $part['id_lokasi_rak']);
				$this->db->update('tr_h3_md_picking_list_parts');
			}
			
		}
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			send_json([
				'success' => 1,
				'message' => 'Data berhasil disimpan'
			]);
		} else {
			send_json([
				'success' => 0,
				'message' => 'Data tidak berhasil disimpan'
			], 422);
		}
	}

	public function simpan_old()
	{
		$id_picking_list = $this->input->post('id_picking_list');
		$this->db->trans_start();
		$this->picking_list->update([
			'status' => 'On Process',
		], [
			'id_picking_list' => $id_picking_list,
			'status' => 'Open'
		]);

		foreach ($this->input->post('parts') as $part) {
			$this->db->set('qty_disiapkan', $part['qty_disiapkan']);
			$this->db->where('id_picking_list', $id_picking_list);
			if ($this->input->post('kategori_po') != null and $this->input->post('kategori_po') == 'KPB') {
				$this->db->where('id_tipe_kendaraan', $part['id_tipe_kendaraan']);
			}
			$this->db->where('id_part', $part['id_part']);
			$this->db->where('id_lokasi_rak', $part['id_lokasi_rak']);
			$this->db->update('tr_h3_md_picking_list_parts');
		}
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			send_json([
				'success' => 1,
				'message' => 'Data berhasil disimpan'
			]);
		} else {
			send_json([
				'success' => 0,
				'message' => 'Data tidak berhasil disimpan'
			], 422);
		}
	}

	public function cetak_picking_list()
	{
		$data = [];
		$data['picking_list'] = $this->db
			->select('pl.id_picking_list')
			->select('date_format(pl.tanggal, "%d/%m/%Y") as tanggal_picking')
			->select('do.id_do_sales_order')
			->select('date_format(do.tanggal, "%d/%m/%Y") as tanggal_do')
			->select('so.id_sales_order')
			->select('date_format(so.tanggal_order, "%d/%m/%Y") as tanggal_so')
			->select('d.nama_dealer')
			->select('d.kode_dealer_md')
			->select('d.alamat')
			->select('so.jenis_pembayaran')
			->select('k.nama_lengkap as nama_picker')
			->from('tr_h3_md_picking_list as pl')
			->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
			->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
			->join('ms_karyawan as k', 'k.id_karyawan = pl.id_picker')
			->where('pl.id_picking_list', $this->input->get('id_picking_list'))
			->get()->row();

		$this->db
			->select('plp.id_part')
			->select('p.nama_part')
			->select('plp.qty_supply')
			->select('lr.kode_lokasi_rak')
			->select('plp.serial_number')
			->select('lr.deskripsi')
			->from('tr_h3_md_picking_list_parts as plp')
			->join('ms_part as p', 'p.id_part = plp.id_part')
			->join('ms_h3_md_lokasi_rak as lr', 'lr.id = plp.id_lokasi_rak')
			->where('plp.id_picking_list', $this->input->get('id_picking_list'));

		if ($this->input->get('filters_lokasi') != null and count($this->input->get('filters_lokasi')) > 0) {
			$this->db->where_in('plp.id_lokasi_rak', $this->input->get('filters_lokasi'));
		}

		if ($this->input->get('filters_part') != null and count($this->input->get('filters_part')) > 0) {
			$this->db->where_in('plp.id_part', $this->input->get('filters_part'));
		}

		if ($this->input->get('filters_kelompok_part') != null and count($this->input->get('filters_kelompok_part')) > 0) {
			$this->db->where_in('p.kelompok_part', $this->input->get('filters_kelompok_part'));
		}
		$data['parts'] = $this->db->get()->result();
		
		$this->db->set('cetak_ke', 'COALESCE(cetak_ke,0) + 1', FALSE);
		$this->db->where('id_picking_list', $this->input->get('id_picking_list'));
		$this->db->update('tr_h3_md_picking_list');

		// $this->load->library('mpdf_l');
		require_once APPPATH . 'third_party/mpdf/mpdf.php';
		// Require composer autoload
		$mpdf = new Mpdf();
		// Write some HTML code:
		$html = $this->load->view('h3/h3_md_cetak_picking_list', $data, true);
		$mpdf->WriteHTML($html);

		// Output a PDF file directly to the browser
		$mpdf->Output("Picking List.pdf", "I");
	}

	public function verifyPassword() {
		$inputPassword = $this->input->post('password');
		$id = $this->input->post('id'); 

		$correctPassword = $this->db->select('ms.password')
									->from('tr_h3_md_setting_menu_password ms')
									->join('ms_menu mm','mm.id_menu=ms.id_menu')
									->where('mm.menu_link',$this->uri->segment(2))
									->get()
									->row_array();

		if(!empty($correctPassword)){
			$correctPassword['password'] = $correctPassword['password'];
		}else{ 
			$correctPassword['password'] = 'sparepart';
		}
								
		if ($inputPassword == $correctPassword['password']) {
			echo 'success';
		} else {
			echo 'fail';
		}
	}
}
