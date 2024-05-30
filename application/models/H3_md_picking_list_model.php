<?php

class H3_md_picking_list_model extends Honda_Model
{

	protected $table = 'tr_h3_md_picking_list';

	public function __construct()
	{
		parent::__construct();

		$this->load->library('Mcarbon');
		$this->load->helper('get_only');

		$this->load->model('H3_md_do_revisi_model', 'do_revisi');
		$this->load->model('H3_md_do_revisi_item_model', 'do_revisi_item');
		$this->load->model('H3_md_do_revisi_detail_item_model', 'do_revisi_detail_item');
	}

	public function insert($data)
	{
		$data['created_at'] = date('Y-m-d H:i:s', time());
		$data['created_by'] = $this->session->userdata('id_user');
		$data['status'] = '';
		parent::insert($data);
	}

	public function cancel($id)
	{
		$picking_list = $this->db
			->from($this->table)
			->where('id', $id)
			->limit(1)
			->get()->row_array();

		if ($picking_list == null) throw new Exception('Picking list tidak ditemukan');

		$this->update([
			'status' => 'Canceled',
			'canceled_at' => Mcarbon::now()->toDateTimeString(),
			'canceled_by' => $this->session->userdata('id_user')
		], ['id' => $id]);

		log_message('info', sprintf('Picking list % dicancel', $picking_list['id_picking_list']));

		$this->reset_qty_pick_tracking($picking_list['id']);
		$this->reset_qty_pack_tracking($picking_list['id']);
	}

	public function reset_qty_pick_tracking($id)
	{
		$picking_list = $this->db
			->from($this->table)
			->where('id', $id)
			->limit(1)
			->get()->row_array();

		if ($picking_list == null) throw new Exception('Picking list tidak ditemukan');

		$delivery_order = $this->db
			->from('tr_h3_md_do_sales_order')
			->where('id', $picking_list['id_ref_int'])
			->limit(1)
			->get()->row_array();

		if ($delivery_order == null) throw new Exception('Delivery order tidak ditemukan');

		$sales_order = $this->db
			->from('tr_h3_md_sales_order')
			->where('id', $delivery_order['id_sales_order_int'])
			->limit(1)
			->get()->row_array();

		if ($sales_order == null) throw new Exception('Delivery order tidak ditemukan');

		$kategori = $sales_order['kategori_po'];
		$purchase_order_dealer = $sales_order['id_ref'];
		$merupakan_rekapan_purchase_order_dealer = $sales_order['id_rekap_purchase_order_dealer'] != null && $sales_order['id_rekap_purchase_order_dealer'] != '';
		$rekapan_purchase_order_dealer = $sales_order['id_rekap_purchase_order_dealer'];

		$this->db
			->select('plp.id_picking_list')
			->select('plp.id_part')
			->select('plp.qty_disiapkan')
			->from('tr_h3_md_picking_list_parts as plp')
			->where('id_picking_list_int', $picking_list['id']);

		if ($kategori != null and $kategori == 'KPB') $this->db->select('plp.id_tipe_kendaraan');

		log_message('info', sprintf('Reset kuantitas picking order part tracking untuk picking list %s', $picking_list['id_picking_list']));
		foreach ($this->db->get()->result_array() as $picking_part) {
			if ($kategori != null and $kategori == 'KPB') {
				$this->order_parts_tracking->kurang_qty_pick($purchase_order_dealer, $picking_part['id_part'], $picking_part['qty_disiapkan'], $picking_part['id_tipe_kendaraan']);
			} else {
				$this->order_parts_tracking->kurang_qty_pick($purchase_order_dealer, $picking_part['id_part'], $picking_part['qty_disiapkan']);
			}

			if ($merupakan_rekapan_purchase_order_dealer) {
				$this->db
					->select('po.po_id')
					->select('pop.id_part')
					->select('pop.kuantitas')
					->select('(opt.qty_book - opt.qty_pick) as selisih')
					->from('tr_h3_md_rekap_purchase_order_dealer_item as ri')
					->join('tr_h3_dealer_purchase_order as po', 'po.po_id = ri.id_referensi')
					->join('tr_h3_dealer_purchase_order_parts as pop', 'pop.po_id = ri.id_referensi')
					->join('tr_h3_dealer_order_parts_tracking as opt', '(opt.po_id = pop.po_id and opt.id_part = pop.id_part)')
					->where('ri.id_rekap', $rekapan_purchase_order_dealer)
					->where('pop.id_part', $picking_part['id_part'])
					->order_by('po.proses_at', 'desc');

				if ($kategori != null and $kategori == 'KPB') {
					$this->db->select('pop.id_tipe_kendaraan');
					$this->db->where('pop.id_tipe_kendaraan', $picking_part['id_tipe_kendaraan']);
				}

				$supply_untuk_dipecah = $picking_part['qty_disiapkan'];
				foreach ($this->db->get()->result_array() as $purchase_order) {
					if ($purchase_order['selisih'] <= $supply_untuk_dipecah) {
						if ($kategori != null and $kategori == 'KPB') {
							$this->order_parts_tracking->kurang_qty_pick($purchase_order['po_id'], $purchase_order['id_part'], $purchase_order['selisih'], $purchase_order['id_tipe_kendaraan']);
						} else {
							$this->order_parts_tracking->kurang_qty_pick($purchase_order['po_id'], $purchase_order['id_part'], $purchase_order['selisih']);
						}
						$supply_untuk_dipecah -= $purchase_order['selisih'];
					} else if ($purchase_order['selisih'] >= $supply_untuk_dipecah) {
						if ($kategori != null and $kategori == 'KPB') {
							$this->order_parts_tracking->kurang_qty_pick($purchase_order['po_id'], $purchase_order['id_part'], $supply_untuk_dipecah, $purchase_order['id_tipe_kendaraan']);
						} else {
							$this->order_parts_tracking->kurang_qty_pick($purchase_order['po_id'], $purchase_order['id_part'], $supply_untuk_dipecah);
						}
						break;
					}

					if ($supply_untuk_dipecah == 0) break;
				}
			}
		}

		$this->load->model('h3_md_picking_list_parts_model', 'picking_list_parts');
		$this->picking_list_parts->delete($picking_list['id'], 'id_picking_list_int');
		log_message('info', sprintf('Menghapus parts untuk picking list %s', $picking_list['id_picking_list']));
	}

	public function reset_qty_pack_tracking($id)
	{
		$picking_list = $this->db
			->from($this->table)
			->where('id', $id)
			->limit(1)
			->get()->row_array();

		if ($picking_list == null) throw new Exception('Picking list tidak ditemukan');

		$delivery_order = $this->db
			->from('tr_h3_md_do_sales_order')
			->where('id', $picking_list['id_ref_int'])
			->limit(1)
			->get()->row_array();

		if ($delivery_order == null) throw new Exception('Delivery order tidak ditemukan');

		$sales_order = $this->db
			->from('tr_h3_md_sales_order')
			->where('id', $delivery_order['id_sales_order_int'])
			->limit(1)
			->get()->row_array();

		if ($sales_order == null) throw new Exception('Delivery order tidak ditemukan');

		$kategori = $sales_order['kategori_po'];
		$purchase_order_dealer = $sales_order['id_ref'];
		$merupakan_rekapan_purchase_order_dealer = $sales_order['id_rekap_purchase_order_dealer'] != null && $sales_order['id_rekap_purchase_order_dealer'] != '';
		$rekapan_purchase_order_dealer = $sales_order['id_rekap_purchase_order_dealer'];

		$this->db
			->from('tr_h3_md_scan_picking_list_parts')
			->where('id_picking_list', $picking_list['id_picking_list']);
		if ($kategori != null and $kategori == 'KPB') $this->db->select('splp.id_tipe_kendaraan');

		$scan_parts = $this->db->get()->result_array();

		if (count($scan_parts) > 0) {
			$this->picking_list->update([
				'revisi_scan' => 1,
				'selesai_scan' => 0,
			], ['id' => $picking_list['id']]);
		}

		log_message('info', sprintf('Reset kuantitas packing order part tracking untuk picking list %s', $picking_list['id_picking_list']));
		foreach ($scan_parts as $scan_part) {
			$this->scan_picking_list_parts->delete($scan_part['id']);
			if ($kategori != null and $kategori == 'KPB') {
				$this->order_parts_tracking->kurang_qty_pack($purchase_order_dealer, $scan_part['id_part'], $scan_part['qty_scan'], $scan_part['id_tipe_kendaraan']);
			} else {
				$this->order_parts_tracking->kurang_qty_pack($purchase_order_dealer, $scan_part['id_part'], $scan_part['qty_scan']);
			}

			if ($merupakan_rekapan_purchase_order_dealer) {
				$this->db
					->select('po.po_id')
					->select('pop.id_part')
					->select('opt.qty_pack')
					->from('tr_h3_md_rekap_purchase_order_dealer_item as ri')
					->join('tr_h3_dealer_purchase_order as po', 'po.po_id = ri.id_referensi')
					->join('tr_h3_dealer_purchase_order_parts as pop', 'pop.po_id = ri.id_referensi')
					->join('tr_h3_dealer_order_parts_tracking as opt', '(opt.po_id = pop.po_id and opt.id_part = pop.id_part)')
					->where('ri.id_rekap', $rekapan_purchase_order_dealer)
					->where('pop.id_part', $scan_part['id_part'])
					->where('opt.qty_pack > 0')
					->order_by('po.created_at', 'asc');

				if ($kategori != null and $kategori == 'KPB') {
					$this->db->select('pop.id_tipe_kendaraan');
					$this->db->where('pop.id_tipe_kendaraan', $scan_part['id_tipe_kendaraan']);
				}

				$purchase_orders = $this->db->get()->result_array();

				$supply_untuk_dipecah = $scan_part['qty_scan'];
				foreach ($purchase_orders as $purchase_order) {
					if ($purchase_order['qty_pack'] <= $supply_untuk_dipecah) {
						if ($kategori != null and $kategori == 'KPB') {
							$this->order_parts_tracking->kurang_qty_pack($purchase_order['po_id'], $purchase_order['id_part'], $purchase_order['qty_pack'], $purchase_order['id_tipe_kendaraan']);
						} else {
							$this->order_parts_tracking->kurang_qty_pack($purchase_order['po_id'], $purchase_order['id_part'], $purchase_order['qty_pack']);
						}
						$supply_untuk_dipecah -= $purchase_order['qty_pack'];
					} else if ($purchase_order['qty_pack'] >= $supply_untuk_dipecah) {
						if ($kategori != null and $kategori == 'KPB') {
							$this->order_parts_tracking->kurang_qty_pack($purchase_order['po_id'], $purchase_order['id_part'], $supply_untuk_dipecah, $purchase_order['id_tipe_kendaraan']);
						} else {
							$this->order_parts_tracking->kurang_qty_pack($purchase_order['po_id'], $purchase_order['id_part'], $supply_untuk_dipecah);
						}
						break;
					}
					if ($supply_untuk_dipecah == 0) break;
				}
			}
		}
	}

	public function check_picking_list_has_picker($id_picking_list)
	{
		$picking_list = $this->db
			->from($this->table)
			->where('id_picking_list', $id_picking_list)
			->limit(1)
			->get()->row_array();

		if ($picking_list == null) throw new Exception('Picking list tidak ditemukan');

		if ($picking_list['id_picker'] == null or $picking_list['id_picker'] == '') {
			send_json([
				'message' => 'Picking list tidak memiliki picker',
			], 422);
		}
	}

	public function ready_for_scanning($id_picking_list)
	{
		$this->update(['ready_for_scan' => 1], [
			'id_picking_list' => $id_picking_list,
		]);

		$this->db
			->set('pl.datetime_proses_scan', Mcarbon::now()->toDateTimeString())
			->where('pl.id_picking_list', $id_picking_list)
			->where('pl.datetime_proses_scan', null)
			->update(sprintf('%s as pl', $this->table));

		log_message('debug', sprintf('Picking list %s siap untuk discan', $id_picking_list));
	}

	public function set_do_status_scan($id_picking_list)
	{
		$data = $this->db
			->select('do.id_do_sales_order')
			->from('tr_h3_md_picking_list as pl')
			->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
			->where('pl.id_picking_list', $id_picking_list)
			->get()->row_array();

		if ($data == null) {
			throw new Exception(sprintf('Data delivery order tidak ditemukan untuk picking list %s', $id_picking_list));
		}

		$this->db
			->set('do.status', 'Proses Scan')
			->where('do.id_do_sales_order', $data['id_do_sales_order'])
			->update('tr_h3_md_do_sales_order as do');

		log_message('debug', sprintf('Status delivery order %s dirubah menjadi proses scan dikarenakan picking list %s siap untuk discan', $data['id_do_sales_order'], $id_picking_list));
	}

	public function create_picking_list_ready_to_scan_nofitication($id_picking_list)
	{
		$data = $this->db
			->select('d.nama_dealer')
			->select('pl.id_picking_list')
			->select('do.id_do_sales_order')
			->from('tr_h3_md_picking_list as pl')
			->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
			->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
			->where('pl.id_picking_list', $id_picking_list)
			->get()->row_array();

		$menu_kategori = $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'picking_ready_to_scan')->get()->row_array();
		if ($menu_kategori != null) {
			$this->notifikasi->insert([
				'id_notif_kat' => $menu_kategori['id_notif_kat'],
				'judul' => $menu_kategori['nama_kategori'],
				'pesan' => "No. Picking List {$data['id_picking_list']} a.n {$data['nama_dealer']} telah selesai picking. Segera Scan dan Cek.",
				'link' => "{$menu_kategori['link']}/detail?id_picking_list={$data['id_picking_list']}",
				'show_popup' => $menu_kategori['popup'],
			]);

			log_message('debug', sprintf('Membuat notifikasi ready to scan untuk picking list %s', $id_picking_list));
		} else {
			log_message('debug', 'Tidak terdapat notifikasi kategori "picking_ready_to_scan"');
		}
	}

	public function generateID($tipe_po, $id_dealer, $gimmick = 0)
	{
		$th = date('Y');
		$bln = date('m');
		$th_bln = date('Y-m');
		$thbln = date('ym');

		$dealer = $this->db
			->select('d.kode_dealer_md')
			->from('ms_dealer as d')
			->where('d.id_dealer', $id_dealer)
			->get()->row();

		$query = $this->db
			->select('pl.*')
			->from("{$this->table} as pl")
			->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
			->where('so.id_dealer', $id_dealer)
			->where('so.po_type', $tipe_po)
			->where("LEFT(pl.tanggal, 7)='{$th_bln}'")
			->order_by('pl.created_at', 'DESC')
			->order_by('pl.id', 'desc')
			->order_by('pl.id_picking_list', 'DESC')
			->limit(1)
			->get();

		if ($query->num_rows() > 0) {
			$row = $query->row();
			$id_picking_list = substr($row->id_picking_list, 0, 5);
			$id_picking_list = sprintf("%'.05d", $id_picking_list + 1);
			$id = "{$id_picking_list}/PL-{$tipe_po}/{$dealer->kode_dealer_md}/{$bln}/{$th}";
		} else {
			$id = "00001/PL-{$tipe_po}/{$dealer->kode_dealer_md}/{$bln}/{$th}";
		}

		if ($gimmick == 1) {
			$id .= '/FGD';
		}

		return strtoupper($id);
	}

	public function set_start_pick($id_picking_list)
	{
		$this->db
			->set('pl.status', 'On Process')
			->set('pl.start_pick', Mcarbon::now()->toDateTimeString())
			->where('pl.start_pick IS NULL', null, false)
			->where('pl.id_picking_list', $id_picking_list)
			->update(sprintf('%s as pl', $this->table));

		log_message('debug', sprintf('Set start picking untuk picking list nomor %s', $id_picking_list));
	}

	public function set_end_pick($id_picking_list)
	{
		$this->db
			->set('pl.status', 'Closed PL')
			->set('pl.end_pick', Mcarbon::now()->toDateTimeString())
			->where('pl.end_pick IS NULL', null, false)
			->group_start()
			->where('pl.status', 'On Process')
			->or_where('pl.status', 'Re-Check')
			->group_end()
			->where('pl.id_picking_list', $id_picking_list)
			->update(sprintf('%s as pl', $this->table));
	}

	public function set_validation_start($id_picking_list)
	{
		$this->db
			->set('pl.validation_start', Mcarbon::now()->toDateTimeString())
			->where('pl.validation_start IS NULL', null, false)
			->where('pl.id_picking_list', $id_picking_list)
			->update(sprintf('%s as pl', $this->table));

		log_message('debug', sprintf('Set validation start untuk picking list %s', $id_picking_list));
	}

	public function set_validation_end($id_picking_list)
	{
		$this->db
			->set('pl.validation_end', Mcarbon::now()->toDateTimeString())
			->where('pl.validation_end IS NULL', null, false)
			->where('pl.id_picking_list', $id_picking_list)
			->update(sprintf('%s as pl', $this->table));
	}

	public function set_status_picking_list_recheck($id_picking_list)
	{
		$data = [
			'end_pick' => null,
			'status' => 'Re-check'
		];

		$condition = [
			'id_picking_list' => $id_picking_list
		];

		$this->update($data, $condition);

		log_message('debug', sprintf('Set status picking list recheck data %s', print_r($data, true)));
	}

	public function selisih_validasi($id_picking_list)
	{
		$this->db
			->from('tr_h3_md_picking_list_parts as plp')
			->where('plp.id_picking_list', $id_picking_list)
			->where('plp.qty_supply != plp.qty_disiapkan', null, false);

		return ($this->db->get()->num_rows() > 0);
	}

	public function create_do_revisi_from_validasi($id_picking_list)
	{
		$this->load->helper('get_diskon_part');
		$this->load->helper('harga_setelah_diskon');

		$do_sales_order = $this->db
			->select('do.id')
			->select('so.id_dealer')
			->select('so.produk')
			->select('so.po_type')
			->select('so.kategori_po')
			->select('do.id_do_sales_order')
			->select('do.sub_total')
			->select('do.total')
			->select('do.diskon_insentif')
			->select('do.diskon_cashback_otomatis')
			->select('do.diskon_cashback')
			->from('tr_h3_md_picking_list as pl')
			->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
			->where('pl.id_picking_list', $id_picking_list)
			->get()->row_array();

		if ($do_sales_order == null) {
			throw new Exception(sprintf('Tidak berhasil menemukan data delivery order untuk picking list nomor %s', $id_picking_list));
		}

		$do_revisi = [];
		$do_revisi['id_do_sales_order_int'] = $do_sales_order['id'];
		$do_revisi['id_do_sales_order'] = $do_sales_order['id_do_sales_order'];
		$do_revisi['source'] = 'validasi_picking_list';
		$do_revisi['sub_total'] = $do_sales_order['sub_total'];
		$do_revisi['total'] = $do_sales_order['total'];
		$do_revisi['diskon_insentif'] = $do_sales_order['diskon_insentif'];
		$do_revisi['diskon_cashback_otomatis'] = $do_sales_order['diskon_cashback_otomatis'];
		$do_revisi['diskon_cashback'] = $do_sales_order['diskon_cashback'];

		$parts_validasi_picking_list = $this->get_validasi_parts($id_picking_list, $do_sales_order['id_dealer'], $do_sales_order['po_type'], $do_sales_order['produk'], $do_sales_order['kategori_po']);
		$parts_revisi_per_id_part = $this->get_validasi_parts_grouped($do_sales_order['id_do_sales_order'], $do_sales_order['id_dealer'], $do_sales_order['po_type'], $do_sales_order['produk'], $do_sales_order['kategori_po']);

		$do_revisi['diskon_cashback_otomatis_revisi'] = 0;
		if ($do_sales_order['kategori_po'] != 'KPB') {
			$cashback_revisi = $this->sales_campaign->get_cashback_campaign($parts_revisi_per_id_part);
			$cashback_revisi_langsung = array_filter($cashback_revisi, function ($item) {
				if ($item['reward_cashback'] == 'Langsung') return $item;
			}, ARRAY_FILTER_USE_BOTH);

			$do_revisi['diskon_cashback_otomatis_revisi'] = array_sum(
				array_map(function ($item) {
					return $item['cashback'];
				}, $cashback_revisi_langsung)
			);
		}

		$do_revisi['sub_total'] = $do_sales_order['sub_total'];
		$do_revisi['total'] = $do_sales_order['total'];
		$do_revisi['diskon_cashback'] = $do_sales_order['diskon_cashback'];
		$do_revisi['diskon_insentif'] = $do_sales_order['diskon_insentif'];
		$do_revisi['diskon_cashback_revisi'] = $do_sales_order['diskon_cashback'];
		$do_revisi['diskon_insentif_revisi'] = $do_sales_order['diskon_insentif'];

		$do_revisi['sub_total_revisi'] = array_sum(
			array_map(function ($item) {
				return floatval($item['amount']);
			}, $parts_revisi_per_id_part)
		);

		$total_diskon = $do_revisi['diskon_insentif_revisi'] + ($do_revisi['diskon_cashback_revisi'] + $do_revisi['diskon_cashback_otomatis_revisi']);
		$do_revisi['total_revisi'] = $do_revisi['sub_total_revisi'] - $total_diskon;

		$this->do_revisi->insert($do_revisi);
		$id_revisi = $this->db->insert_id();

		$items = [];
		foreach ($parts_revisi_per_id_part as $part) {
			$item = [];
			$item['id_revisi'] = $id_revisi;
			$item['id_part'] = $part['id_part'];
			$item['qty_revisi'] = $part['qty_revisi'];
			$item['qty_do'] = $part['qty_do'];
			$item['tipe_diskon_satuan_dealer'] = $part['tipe_diskon_satuan_dealer'];
			$item['diskon_satuan_dealer'] = $part['diskon_satuan_dealer'];
			$item['tipe_diskon_campaign'] = $part['tipe_diskon_campaign'];
			$item['diskon_campaign'] = $part['diskon_campaign'];
			$item['id_campaign_diskon'] = $part['id_campaign_diskon'];
			$item['harga_setelah_diskon'] = $part['harga_setelah_diskon'];

			if ($do_sales_order['kategori_po'] == 'KPB') $item['id_tipe_kendaraan'] = $part['id_tipe_kendaraan'];

			$items[] = $item;
		}
		$this->do_revisi_item->insert_batch($items);

		$detail_items = [];
		foreach ($parts_validasi_picking_list as $part) {
			$item = [];
			$item['id_revisi'] = $id_revisi;
			$item['id_part_int'] = $part['id_part_int'];
			$item['qty_revisi'] = $part['qty_revisi'];
			$item['qty_awal'] = $part['qty_do'];
			$item['id_lokasi_rak'] = $part['id_lokasi_rak'];

			if ($do_sales_order['kategori_po'] == 'KPB') $item['id_tipe_kendaraan'] = $part['id_tipe_kendaraan'];

			$detail_items[] = $item;
		}
		$this->do_revisi_detail_item->insert_batch($detail_items);

		log_message('debug', sprintf('Membuat DO Revisi validasi picking list [header] %s [parts] %s', print_r($do_sales_order, true), print_r($items, true)));

		if (count($cashback_revisi) > 0) {
			$cashback_revisi = array_map(function ($item) use ($id_revisi) {
				$item['id_revisi'] = $id_revisi;
				unset($item['reward_cashback']);
				return $item;
			}, $cashback_revisi);

			log_message('debug', sprintf('[%s] DO revisi validasi picking list mendapatkan cashback [payload] %s', $id_revisi, $cashback_revisi));

			$this->do_revisi_cashback->insert_batch($cashback_revisi);
		}

		$this->do_revisi->update_harga($id_revisi);
	}

	private function get_validasi_parts($id_picking_list, $id_dealer, $tipe_po, $produk, $kategori_po)
	{
		$this->db
			->select('p.id_part_int')
			->select('plp.id_part')
			->select('plp.qty_supply as qty_do')
			->select('plp.qty_disiapkan as qty_revisi')
			->select('plp.qty_disiapkan as kuantitas')
			->select('plp.id_lokasi_rak')
			->select('dop.harga_jual')
			->from('tr_h3_md_picking_list_parts as plp')
			->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = plp.id_picking_list')
			->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_do_sales_order = pl.id_ref AND dop.id_part = plp.id_part)')
			->join('ms_part as p', 'p.id_part = plp.id_part')
			->where('plp.id_picking_list', $id_picking_list);
		if ($kategori_po == 'KPB') $this->db->select('plp.id_tipe_kendaraan');

		$parts = $this->db->get()->result_array();
		$parts = get_diskon_part($id_dealer, $tipe_po, $produk, $kategori_po, $parts);
		$parts = array_map(function ($part) {
			$part['tipe_diskon_satuan_dealer'] = $part['tipe_diskon'];
			$part['diskon_satuan_dealer'] = $part['diskon_value'];
			unset($part['tipe_diskon']);
			unset($part['diskon_value']);

			$part['tipe_diskon_campaign'] = $part['tipe_diskon_campaign'];
			$part['diskon_campaign'] = $part['diskon_value_campaign'];
			unset($part['diskon_value_campaign']);

			$part['harga_setelah_diskon'] = harga_setelah_diskon($part['tipe_diskon_satuan_dealer'], $part['diskon_satuan_dealer'], $part['harga_jual'], false, $part['tipe_diskon_campaign'], $part['diskon_campaign']);
			$part['amount'] = $part['kuantitas'] * $part['harga_setelah_diskon'];
			return $part;
		}, $parts);

		log_message('debug', sprintf('log parts_validasi_picking_list %s', print_r($parts, true)));

		return $parts;
	}

	private function get_validasi_parts_grouped($id_do_sales_order, $id_dealer, $tipe_po, $produk, $kategori_po)
	{
		$this->db
			->select('SUM(plp.qty_disiapkan) as qty_revisi')
			->from('tr_h3_md_picking_list_parts as plp')
			->where('plp.id_picking_list = pl.id_picking_list')
			->where('plp.id_part = dop.id_part');
		if ($kategori_po == 'KPB') $this->db->where('plp.id_tipe_kendaraan = dop.id_tipe_kendaraan');

		$qty_revisi = $this->db->get_compiled_select();

		$this->db
			->select('dop.id_part')
			->select("dop.qty_supply as qty_do")
			->select("IFNULL( ({$qty_revisi}), 0) as qty_revisi")
			->select("IFNULL( ({$qty_revisi}), 0) as kuantitas")
			->select("dop.harga_jual")
			->select('dop.tipe_diskon_satuan_dealer')
			->select('dop.diskon_satuan_dealer')
			->select('dop.tipe_diskon_campaign')
			->select('dop.diskon_campaign')
			->select('sop.id_campaign_diskon')
			->select('sc.jenis_diskon_campaign')
			->from('tr_h3_md_do_sales_order_parts as dop')
			->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = dop.id_do_sales_order')
			->join('tr_h3_md_picking_list as pl', 'pl.id_ref = do.id_do_sales_order')
			->where('dop.id_do_sales_order', $id_do_sales_order);

		if ($kategori_po == 'KPB') {
			$this->db->select('dop.id_tipe_kendaraan');
			$this->db->join('tr_h3_md_sales_order_parts as sop', '(sop.id_sales_order = do.id_sales_order AND sop.id_part = dop.id_part and sop.id_tipe_kendaraan = dop.id_tipe_kendaraan)');
		} else {
			$this->db->join('tr_h3_md_sales_order_parts as sop', '(sop.id_sales_order = do.id_sales_order AND sop.id_part = dop.id_part)');
		}

		$this->db->join('ms_h3_md_sales_campaign as sc', 'sc.id = sop.id_campaign_diskon', 'left');

		$parts = $this->db->get()->result_array();
		$parts = get_diskon_part($id_dealer, $tipe_po, $produk, $kategori_po, $parts);
		$parts = array_map(function ($part) {
			$part['qty_order'] = $part['qty_revisi'];
			$part['tipe_diskon_satuan_dealer'] = $part['tipe_diskon'];
			$part['diskon_satuan_dealer'] = $part['diskon_value'];
			$part['tipe_diskon_campaign'] = $part['tipe_diskon_campaign'];
			$part['diskon_campaign'] = $part['diskon_value_campaign'];
			$part['harga_setelah_diskon'] = harga_setelah_diskon($part['tipe_diskon_satuan_dealer'], $part['diskon_satuan_dealer'], $part['harga_jual'], false, $part['tipe_diskon_campaign'], $part['diskon_campaign']);
			$part['amount'] = $part['qty_revisi'] * $part['harga_setelah_diskon'];

			unset($part['tipe_diskon']);
			unset($part['diskon_value']);
			unset($part['diskon_value_campaign']);

			return $part;
		}, $parts);

		log_message('debug', sprintf('Payload parts %s', print_r($parts, true)));

		return $parts;
	}

	public function selisih_scan($id_picking_list)
	{
		$do_sales_order = $this->db
			->select('so.kategori_po')
			->from('tr_h3_md_picking_list as pl')
			->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
			->where('pl.id_picking_list', $id_picking_list)
			->get()->row_array();

		$this->db
			->select('SUM(splp_sq.qty_scan) as qty_scan', false)
			->from('tr_h3_md_scan_picking_list_parts as splp_sq')
			->where('splp_sq.id_picking_list = plp.id_picking_list')
			->where('splp_sq.id_lokasi_rak = plp.id_lokasi_rak')
			->where('splp_sq.id_part = plp.id_part');
		if ($do_sales_order['kategori_po'] == 'KPB') $this->db->where('splp_sq.id_tipe_kendaraan = plp.id_tipe_kendaraan');
		$qty_scan = $this->db->get_compiled_select();

		$this->db
			->where('plp.id_picking_list', $id_picking_list)
			->where(sprintf('plp.qty_disiapkan != IFNULL((%s), 0)', $qty_scan), null, false)
			->from('tr_h3_md_picking_list_parts as plp');

		return $this->db->get()->num_rows() > 0;
	}

	public function create_do_revisi_from_scan($id_picking_list)
	{
		$do_sales_order = $this->db
			->select('do.id')
			->select('do.id_do_sales_order')
			->select('do.sub_total')
			->select('do.total')
			->select('do.diskon_insentif')
			->select('do.diskon_cashback_otomatis')
			->select('do.diskon_cashback')
			->select('so.kategori_po')
			->select('so.produk')
			->select('so.po_type')
			->select('so.id_dealer')
			->from('tr_h3_md_picking_list as pl')
			->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
			->where('pl.id_picking_list', $id_picking_list)
			->get()->row_array();

		if ($do_sales_order == null) {
			throw new Exception(sprintf('Tidak ditemukan delivery order untuk nomor picking list %s', $id_picking_list));
		}

		$parts_selisih_scan = $this->get_parts_selisih_scan($id_picking_list, $do_sales_order['id_dealer'], $do_sales_order['po_type'], $do_sales_order['produk'], $do_sales_order['kategori_po']);
		$parts_revisi_per_id_part = $this->get_scanned_parts_grouped($do_sales_order['id_do_sales_order'], $do_sales_order['id_dealer'], $do_sales_order['po_type'], $do_sales_order['produk'], $do_sales_order['kategori_po']);

		$do_revisi = [];
		$do_revisi['id_do_sales_order_int'] = $do_sales_order['id'];
		$do_revisi['id_do_sales_order'] = $do_sales_order['id_do_sales_order'];
		$do_revisi['source'] = 'scan_picking_list';

		$cashback_revisi = $this->sales_campaign->get_cashback_campaign($parts_revisi_per_id_part);
		$cashback_revisi_langsung = array_filter($cashback_revisi, function ($item) {
			if ($item['reward_cashback'] == 'Langsung') return $item;
		}, ARRAY_FILTER_USE_BOTH);
		$do_revisi['diskon_cashback_otomatis_revisi'] = array_sum(
			array_map(function ($item) {
				return $item['cashback'];
			}, $cashback_revisi_langsung)
		);
		$do_revisi['sub_total'] = $do_sales_order['sub_total'];
		$do_revisi['total'] = $do_sales_order['total'];
		$do_revisi['diskon_cashback'] = $do_sales_order['diskon_cashback'];
		$do_revisi['diskon_insentif'] = $do_sales_order['diskon_insentif'];
		$do_revisi['diskon_cashback_revisi'] = $do_sales_order['diskon_cashback'];
		$do_revisi['diskon_insentif_revisi'] = $do_sales_order['diskon_insentif'];
		$do_revisi['sub_total_revisi'] = array_sum(
			array_column($parts_revisi_per_id_part, 'amount')
		);
		$total_diskon = $do_revisi['diskon_insentif_revisi'] + ($do_revisi['diskon_cashback_revisi'] + $do_revisi['diskon_cashback_otomatis_revisi']);
		$do_revisi['total_revisi'] = $do_revisi['sub_total_revisi'] - $total_diskon;

		$this->do_revisi->insert($do_revisi);
		$id_revisi = $this->db->insert_id();
		$items = get_only([
			'id_part', 'qty_revisi', 'qty_do', 'id_lokasi_rak',
			'tipe_diskon_satuan_dealer', 'diskon_satuan_dealer', 'tipe_diskon_campaign', 'diskon_campaign', 'id_campaign_diskon',
			'harga_setelah_diskon'
		], $parts_revisi_per_id_part, [
			'id_revisi' => $id_revisi
		]);
		$this->do_revisi_item->insert_batch($items);

		$item_details = array_map(function ($row) use ($id_revisi, $do_sales_order) {
			$data =  [
				'id_revisi' => $id_revisi,
				'id_part_int' => $row['id_part_int'],
				'id_lokasi_rak' => $row['id_lokasi_rak'],
				'qty_awal' => $row['qty_do'],
				'qty_revisi' => $row['qty_revisi'],
			];

			if ($do_sales_order['kategori_po'] == 'KPB') $data['id_tipe_kendaraan'] = $row['id_tipe_kendaraan'];

			return $data;
		}, $parts_selisih_scan);
		$this->do_revisi_detail_item->insert_batch($item_details);

		log_message('info', sprintf('Membuat DO revisi dari scan picking list %s [header] %s [parts] %s [parts detail] %s', $id_picking_list, print_r($do_revisi, true), print_r($items, true), print_r($item_details, true)));

		if (count($cashback_revisi) > 0) {
			$cashback_revisi = array_map(function ($item) use ($id_revisi) {
				$item['id_revisi'] = $id_revisi;
				unset($item['reward_cashback']);
				return $item;
			}, $cashback_revisi);

			log_message('info', sprintf('Membuat cashback untuk do revisi [%s]', $id_revisi));
			$this->do_revisi_cashback->insert_batch($cashback_revisi);
		}

		$this->do_revisi->update_harga($id_revisi);
	}

	private function get_parts_selisih_scan($id_picking_list, $id_dealer, $tipe_po, $produk, $kategori_po)
	{
		$this->db
			->select('sum(splp_sq.qty_scan)', false)
			->from('tr_h3_md_scan_picking_list_parts as splp_sq')
			->where('splp_sq.id_picking_list = plp.id_picking_list')
			->where('splp_sq.id_lokasi_rak = plp.id_lokasi_rak')
			->where('splp_sq.id_part = plp.id_part');
		if ($kategori_po == 'KPB') $this->db->where('splp_sq.id_tipe_kendaraan = plp.id_tipe_kendaraan', null, false);
		$qty_scan = $this->db->get_compiled_select();

		$this->db
			->select('plp.id_part_int')
			->select('plp.id_part')
			->select('plp.id_lokasi_rak')
			->select("plp.qty_supply as qty_do")
			->select("IFNULL( ({$qty_scan}), 0) as qty_revisi", false)
			->select("IFNULL( ({$qty_scan}), 0) as kuantitas", false)
			->select('dop.harga_jual')
			->from('tr_h3_md_picking_list_parts as plp')
			->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = plp.id_picking_list')
			->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_do_sales_order = pl.id_ref AND dop.id_part = plp.id_part)')
			->where('plp.id_picking_list', $id_picking_list);
		if ($kategori_po == 'KPB') $this->db->select('plp.id_tipe_kendaraan');

		$parts_selisih_scan = $this->db->get()->result_array();
		$parts_selisih_scan = get_diskon_part($id_dealer, $tipe_po, $produk, $kategori_po, $parts_selisih_scan);
		$parts_selisih_scan = array_map(function ($item) {
			$item['tipe_diskon_satuan_dealer'] = $item['tipe_diskon'];
			$item['diskon_satuan_dealer'] = $item['diskon_value'];
			unset($item['tipe_diskon']);
			unset($item['diskon_value']);

			$item['tipe_diskon_campaign'] = $item['tipe_diskon_campaign'];
			$item['id_campaign_diskon'] = $item['id_campaign_diskon'];
			$item['diskon_campaign'] = $item['diskon_value_campaign'];
			unset($item['diskon_value_campaign']);

			$item['harga_setelah_diskon'] = harga_setelah_diskon($item['tipe_diskon_satuan_dealer'], $item['diskon_satuan_dealer'], $item['harga_jual'], false, $item['tipe_diskon_campaign'], $item['diskon_campaign']);
			$item['amount'] = $item['kuantitas'] * $item['harga_setelah_diskon'];
			return $item;
		}, $parts_selisih_scan);

		log_message('debug', sprintf('Parts selisih scan [payload] %s', print_r($parts_selisih_scan, true)));

		return $parts_selisih_scan;
	}

	private function get_scanned_parts_grouped($id_do_sales_order,  $id_dealer, $tipe_po, $produk, $kategori_po)
	{
		$this->db
			->select('sum(splp_sq.qty_scan)')
			->from('tr_h3_md_scan_picking_list_parts as splp_sq')
			->where('splp_sq.id_picking_list = pl.id_picking_list')
			->where('splp_sq.id_part = dop.id_part');

		if ($kategori_po == 'KPB') {
			$this->db->where('splp_sq.id_tipe_kendaraan = dop.id_tipe_kendaraan');
		}

		$qty_revisi = $this->db->get_compiled_select();

		$parts_revisi_per_id_part = $this->db
			->select('dop.id_part_int')
			->select('dop.id_part')
			->select('dop.qty_supply as qty_do')
			->select("IFNULL( ({$qty_revisi}), 0) as qty_order")
			->select("IFNULL( ({$qty_revisi}), 0) as qty_revisi")
			->select("IFNULL( ({$qty_revisi}), 0) as kuantitas")
			->select("dop.harga_jual")
			->select('dop.tipe_diskon_satuan_dealer')
			->select('dop.diskon_satuan_dealer')
			->select('dop.tipe_diskon_campaign')
			->select('dop.diskon_campaign')
			->from('tr_h3_md_do_sales_order_parts as dop')
			->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = dop.id_do_sales_order')
			->join('tr_h3_md_picking_list as pl', 'pl.id_ref = do.id_do_sales_order')
			->where('dop.id_do_sales_order', $id_do_sales_order)
			->get()->result_array();

		$parts_revisi_per_id_part = get_diskon_part($id_dealer, $tipe_po, $produk, $kategori_po, $parts_revisi_per_id_part);

		$parts_revisi_per_id_part = array_map(function ($item) {
			$item['tipe_diskon_satuan_dealer'] = $item['tipe_diskon'];
			$item['diskon_satuan_dealer'] = $item['diskon_value'];
			unset($item['tipe_diskon']);
			unset($item['diskon_value']);

			$item['tipe_diskon_campaign'] = $item['tipe_diskon_campaign'];
			$item['id_campaign_diskon'] = $item['id_campaign_diskon'];
			$item['diskon_campaign'] = $item['diskon_value_campaign'];
			unset($item['diskon_value_campaign']);

			$item['harga_setelah_diskon'] = harga_setelah_diskon($item['tipe_diskon_satuan_dealer'], $item['diskon_satuan_dealer'], $item['harga_jual'], false, $item['tipe_diskon_campaign'], $item['diskon_campaign']);
			$item['amount'] = $item['qty_revisi'] * $item['harga_setelah_diskon'];
			return $item;
		}, $parts_revisi_per_id_part);

		return $parts_revisi_per_id_part;
	}

	public function check_kuantitas_scan_part_sama_atau_melebih_do($id_picking_list_int, $id_part_int, $kuantitas_scan)
	{
		$picking_list = $this->db
			->from($this->table)
			->where('id', $id_picking_list_int)
			->limit(1)
			->get()->row_array();
		if ($picking_list == null) throw new Exception('Picking list tidak ditemukan');

		$delivery_order = $this->db
			->from('tr_h3_md_do_sales_order as do')
			->where('do.id', $picking_list['id_ref_int'])
			->limit(1)
			->get()->row_array();
		if ($delivery_order == null) throw new Exception('Delivery order tidak ditemukan');

		$part = $this->db
			->from('ms_part')
			->where('id_part_int', $id_part_int)
			->limit(1)
			->get()->row_array();
		if ($part == null) throw new Exception('Part tidak ditemukan');

		$kuantitas_do = $this->db
			->select('IFNULL(SUM(qty_supply), 0) AS kuantitas_do', false)
			->from('tr_h3_md_do_sales_order_parts')
			->where('id_do_sales_order_int', $delivery_order['id'])
			->where('id_part_int', $part['id_part_int'])
			->get()->row_array()['kuantitas_do'];

		$kuantitas_sudah_scan = $this->db
			->select('IFNULL(SUM(qty_scan), 0) as kuantitas_sudah_scan')
			->from('tr_h3_md_scan_picking_list_parts as scp')
			->where('scp.id_part_int', $part['id_part_int'])
			->where('scp.id_picking_list_int', $picking_list['id'])
			->get()->row_array()['kuantitas_sudah_scan'];

		if($kuantitas_sudah_scan == $kuantitas_do){
			send_json([
				'message' => sprintf('Kode part %s pada picking list %s sudah terpenuhi', $part['id_part'], $picking_list['id_picking_list'])
			], 422);
		}else if(($kuantitas_sudah_scan + $kuantitas_scan) > $kuantitas_do){
			send_json([
				'message' => sprintf('Kuantitas scan kode part %s melebihi kuantitas delivery order', $part['id_part'])
			], 422);
		}
	}
}
