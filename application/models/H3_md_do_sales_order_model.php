<?php

class H3_md_do_sales_order_model extends Honda_Model
{

	protected $table = 'tr_h3_md_do_sales_order';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('h3_md_do_sales_order_parts_model', 'do_sales_order_parts');
		$this->load->model('H3_md_ms_plafon_model', 'plafon');
		$this->load->model('h3_md_do_sales_order_cashback_model', 'do_sales_order_cashback');
		$this->load->model('h3_md_sales_order_model', 'sales_order');
		$this->load->model('H3_md_sales_campaign_model', 'sales_campaign');
		$this->load->model('H3_md_ap_part_model', 'ap_part');
		$this->load->library('Mcarbon');
		$this->load->helper('get_diskon_part');
		$this->load->helper('rupiah_format');
	}

	public function cancel($id,$alasan_cancel)
	{
		$do_sales_order = $this->db
			->from($this->table)
			->limit(1)
			->where('id', $id)
			->get()->row_array();

		if ($do_sales_order == null) throw new Exception(sprintf('Delivery order tidak ditemukan'));

		if ($do_sales_order['status'] == 'Canceled') throw new Exception(sprintf('Delivery order % sudah pernah dicancel sebelumnya', $do_sales_order['id_do_sales_order']), 403);

		$this->update([
			'status' => 'Canceled',
			'alasan_cancel_do' => $alasan_cancel,
			'canceled_at' => Mcarbon::now()->toDateTimeString(),
			'canceled_by' => $this->session->userdata('id_user')
		], ['id' => $id]);

		log_message('info', sprintf('Delivery order % dicancel', $do_sales_order['id_do_sales_order']));

		$this->load->model('h3_md_sales_order_model', 'sales_order');
		$do_sales_order_terproses = $this->db
			->select('do.id_do_sales_order')
			->from('tr_h3_md_do_sales_order as do')
			->where('do.id_sales_order_int', $do_sales_order['id_sales_order_int'])
			->group_start()
			->where('do.status !=', 'Rejected')
			->where('do.status !=', 'Canceled')
			->group_end()
			->get()->num_rows();

		if ($do_sales_order_terproses > 0) {
			$this->sales_order->set_new_so_bo($do_sales_order['id_sales_order_int']);
		} else {
			$this->sales_order->set_new_so($do_sales_order['id_sales_order_int']);
		}

		$picking_list = $this->db
			->select('id')
			->from('tr_h3_md_picking_list')
			->where('id_ref_int', $do_sales_order['id'])
			->limit(1)
			->get()->row_array();

		if ($picking_list != null) {
			$this->load->model('h3_md_picking_list_model', 'picking_list');
			$this->picking_list->cancel($picking_list['id']);
		}
	}

	public function insert($data)
	{
		$data['created_at'] = date('Y-m-d H:i:s', time());
		$data['created_by'] = $this->session->userdata('id_user');
		$data['status'] = 'On Process';

		parent::insert($data);
		$id = $this->db->insert_id();

		return (array) $this->find($id);
	}

	public function close_so_jika_terpenuhi($id_do_sales_order)
	{
		$do_sales_order = (array) $this->find($id_do_sales_order, 'id_do_sales_order');
		$total_amount_so = $this->sales_order->get_amount_so($do_sales_order['id_sales_order']);
		$total_amount_do = $this->sales_order->get_amount_do_sudah_create_faktur($do_sales_order['id_sales_order']);

		if ($total_amount_so == $total_amount_do) {
			$this->sales_order->update([
				'status' => 'Closed',
				'closed_at' => date('Y-m-d H:i:s', time()),
				'closed_by' => $this->session->userdata('id_user')
			], [
				'id_sales_order' => $do_sales_order['id_sales_order']
			]);
		}
	}

	public function get_do_sales_order($id_do_sales_order)
	{
		$do_sales_order = $this->db
			->select('dso.tanggal as tanggal_do')
			->select('so.produk')
			->select('so.po_type')
			->select('so.id_dealer')
			->select('so.id_rekap_purchase_order_dealer')
			->select('dso.id_do_sales_order')
			->select('so.tanggal_order as tanggal_so')
			->select('so.id_sales_order')
			->select('d.nama_dealer')
			->select('d.kode_dealer_md')
			->select('d.alamat')
			->select('so.kategori_po')
			->select('dso.top as top')
			->select('so.po_type')
			->select('dso.status')
			->select('dso.diskon_additional')
			->select('dso.check_diskon_insentif')
			->select('dso.diskon_insentif')
			->select('dso.check_diskon_cashback')
			->select('dso.diskon_cashback')
			->select('dso.diskon_cashback_otomatis')
			->select('dso.alasan_reject')
			->select('dso.sub_total')
			->select('dso.total')
			->select('ifnull(dso.total_ppn,0) as total_ppn')
			->select('dso.sudah_create_faktur')
			->select('so.gimmick')
			->select('so.id_dealer')
			->select('so.id_salesman')
			->select('k.nama_lengkap as nama_salesman')
			->select('sc.kode_campaign')
			->select('sc.nama as nama_campaign')
			->select('dso.sudah_revisi')
			->select('dso.check_ppn_tools')
			->from('tr_h3_md_do_sales_order as dso')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = dso.id_sales_order')
			->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
			->join('ms_karyawan as k', 'k.id_karyawan = so.id_salesman', 'left')
			->join('ms_h3_md_sales_campaign as sc', 'sc.id = so.id_campaign', 'left')
			->where('dso.id_do_sales_order', $id_do_sales_order)
			->limit(1)
			->get()->row_array();

		if ($do_sales_order == null) {
			throw new Exception(sprintf('Data delivery order %s tidak ditemukan', $id_do_sales_order));
		}

		$do_sales_order['plafon'] = $this->plafon->get_plafon($do_sales_order['id_dealer'], $do_sales_order['gimmick'], $do_sales_order['kategori_po']);
		$do_sales_order['plafon_yang_dipakai'] = $this->plafon->get_plafon_terpakai($do_sales_order['id_dealer'], $do_sales_order['gimmick'], $do_sales_order['kategori_po']);
		$do_sales_order['plafon_booking'] = $this->plafon->get_plafon_booking($do_sales_order['id_dealer'], $do_sales_order['gimmick'], $do_sales_order['kategori_po']);
		$do_sales_order['insentif_dealer'] = $this->ap_part->insentif_poin($do_sales_order['id_dealer']);

		return $do_sales_order;
	}

	public function update_total_do($id_do_sales_order)
	{
		$delivery_order = $this->db
			->select('IFNULL(do.diskon_insentif, 0) as diskon_insentif')
			->select('IFNULL(do.diskon_cashback, 0) as diskon_cashback')
			->select('IFNULL(do.diskon_cashback_otomatis, 0) as diskon_cashback_otomatis')
			->from("{$this->table} as do")
			->where('do.id_do_sales_order', $id_do_sales_order)
			->get()->row_array();

		$total_diskon = $delivery_order['diskon_insentif'] + ($delivery_order['diskon_cashback'] + $delivery_order['diskon_cashback_otomatis']);
		$total_parts = $this->get_total_parts($id_do_sales_order);
		$total = ($total_parts - $total_diskon);

		$updated = $this->update([
			'sub_total' => $total_parts,
			'total' => $total,
		], [
			'id_do_sales_order' => $id_do_sales_order
		]);

		log_message('debug', sprintf('Update amount DO MD %s subtotal: %s; total: %s', $id_do_sales_order, rupiah_format($total_parts), rupiah_format($total)));

		return $updated;
	}

	public function get_total_parts($id_do_sales_order)
	{
		$parts = $this->do_sales_order_parts->get_do_sales_order_parts($id_do_sales_order);
		$parts = array_map(function ($data) {
			return $data['amount'];
		}, $parts);

		return array_sum($parts);
	}

	public function update_do_parts_diskon($id_do_sales_order)
	{
		$parts = $this->do_sales_order_parts->get_do_sales_order_parts($id_do_sales_order);

		foreach ($parts as $part) {
			if ($part['gimmick'] == 0) {
				$this->do_sales_order_parts->update([
					'tipe_diskon_satuan_dealer' => $part['tipe_diskon_satuan_dealer'],
					'diskon_satuan_dealer' => $part['diskon_satuan_dealer'],
					'tipe_diskon_campaign' => $part['tipe_diskon_campaign'],
					'diskon_campaign' => $part['diskon_campaign'],
				], [
					'id_part' => $part['id_part'],
					'id_do_sales_order' => $part['id_do_sales_order']
				]);
			}
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
			->from("{$this->table} as do")
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
			->where("LEFT(do.tanggal, 7)='{$th_bln}'")
			->where('so.po_type', $tipe_po)
			->where('so.id_dealer', $id_dealer)
			->limit(1)
			->where('do.created_at >', '2020-06-30 09:54:00')
			->order_by('do.id', 'DESC')
			->get();

		if ($query->num_rows() > 0) {
			$row = $query->row();
			$id_do_sales_order = substr($row->id_do_sales_order, 0, 5);
			$id_do_sales_order = sprintf("%'.05d", $id_do_sales_order + 1);
			$id = "{$id_do_sales_order}/DO-{$tipe_po}/{$dealer->kode_dealer_md}/{$bln}/{$th}";
		} else {
			$id = "00001/DO-{$tipe_po}/{$dealer->kode_dealer_md}/{$bln}/{$th}";
		}

		if ($gimmick == 1) {
			$id .= '/FGD';
		}

		return strtoupper($id);
	}

	public function qty_avg_sales($id_part, $key = 'id_part', $sql = false)
	{
		$enam_bulan_lalu = Mcarbon::now()->subMonths(6)->startOfMonth();

		$this->db
			->select(sprintf('IFNULL(AVG(dop_%s.qty_supply), 0) as qty', __FUNCTION__))
			->from(sprintf('tr_h3_md_do_sales_order_parts as dop_%s', __FUNCTION__))
			->join(sprintf('tr_h3_md_do_sales_order as do_%s', __FUNCTION__), sprintf('do_%s.id = dop_%s.id_do_sales_order_int', __FUNCTION__, __FUNCTION__))
			->where(sprintf('do_%s.sudah_create_faktur', __FUNCTION__), 1)
			->where(sprintf('do_%s.tanggal >=', __FUNCTION__), $enam_bulan_lalu->format('Y-m-d'));

		$this->db->where(sprintf('dop_%s.%s', __FUNCTION__, $key), $id_part, !$sql);

		if ($sql) {
			return $this->db->get_compiled_select();
		} else {
			$data = $this->db->get()->row_array();
			return $data != null ? $data['qty'] : 0;
		}
	}

	public function claim_insentif_poin($id_do_sales_order, $nilai_diskon_insentif)
	{
		$this->load->model('H3_md_ap_part_model', 'ap_part');
		$this->load->model('H3_md_claim_insentif_sales_campaign_poin_model', 'claim_insentif');

		$data = $this->db
			->select('so.id_dealer')
			->from('tr_h3_md_do_sales_order as do')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
			->where('do.id_do_sales_order', $id_do_sales_order)
			->get()->row_array();

		$insentif_dealer = $this->ap_part->insentif_poin($data['id_dealer']);
		$insentif_dealer_formatted = "Rp " . number_format($insentif_dealer, 0, ',', '.');
		$nilai_diskon_insentif_formatted = "Rp " . number_format($nilai_diskon_insentif, 0, ',', '.');

		if ($insentif_dealer < $nilai_diskon_insentif) {
			return [
				'error_type' => 'insentif_tidak_cukup',
				'message' => "Insentif yang tersedia adalah senilai {$insentif_dealer_formatted} dari {$nilai_diskon_insentif_formatted} yang dibutuhkan."
			];
		}

		$ap_parts = $this->db
			->select('ap.id')
			->select('(ap.total_bayar - ap.total_sudah_dibayar) as sisa_bayar', false)
			->from('tr_h3_md_ap_part as ap')
			->where('ap.id_dealer', $data['id_dealer'])
			->where('ap.jenis_transaksi', 'perolehan_insentif_sales_campaign')
			->having('sisa_bayar > 0')
			->order_by('sisa_bayar', 'asc')
			->get()->result_array();

		$sisa_diskon_insentif_akan_diclaim = $nilai_diskon_insentif;
		$claim_insentif = [];
		while ($sisa_diskon_insentif_akan_diclaim > 0) {
			foreach ($ap_parts as $ap_part) {
				$row = [];
				if ($sisa_diskon_insentif_akan_diclaim <= $ap_part['sisa_bayar']) {
					$row['id_ap_part'] = $ap_part['id'];
					$row['id_do_sales_order'] = $id_do_sales_order;
					$row['nilai_claim'] = $sisa_diskon_insentif_akan_diclaim;
					$sisa_diskon_insentif_akan_diclaim -= $ap_part['sisa_bayar'];
				} else if ($sisa_diskon_insentif_akan_diclaim >= $ap_part['sisa_bayar']) {
					$row['id_ap_part'] = $ap_part['id'];
					$row['id_do_sales_order'] = $id_do_sales_order;
					$row['nilai_claim'] = $ap_part['sisa_bayar'];
					$sisa_diskon_insentif_akan_diclaim -= $ap_part['sisa_bayar'];
				}
				$claim_insentif[] = $row;
			}
		}

		foreach ($claim_insentif as $row) {
			$this->db
				->set('ap.total_sudah_dibayar', "(ap.total_sudah_dibayar + {$row['nilai_claim']})", false)
				->where('ap.id', $row['id_ap_part'])
				->update('tr_h3_md_ap_part as ap');

			$this->claim_insentif->insert($row);
		}

		return true;
	}

	public function tambah_amount_supply_po_dealer($id_do_sales_order)
	{
		$this->load->model('h3_dealer_purchase_order_model', 'purchase_order_dealer');

		$delivery_order = $this->db
			->select('do.id_do_sales_order')
			->select('do.id_sales_order')
			->from('tr_h3_md_do_sales_order as do')
			->where('do.id_do_sales_order', $id_do_sales_order)
			->limit(1)
			->get()->row_array();

		if ($delivery_order == null) throw new Exception(sprintf('Delivery order MD %s tidak ditemukan', $id_do_sales_order));

		$sales_order = $this->db
			->select('so.id_sales_order')
			->select('(so.id_rekap_purchase_order_dealer IS NOT NULL AND so.id_rekap_purchase_order_dealer != "") as rekapan', false)
			->from('tr_h3_md_sales_order as so')
			->where('so.id_sales_order', $delivery_order['id_sales_order'])
			->limit(1)
			->get()->row_array();

		if ($sales_order == null) throw new Exception(sprintf('Sales order MD %s tidak ditemukan', $delivery_order['id_sales_order']));

		$rekapan = $sales_order['rekapan'] == 1;

		$parts = $this->db
			->select('po.po_id')
			->select('so.id_sales_order')
			->select('so.id_rekap_purchase_order_dealer')
			->select('dop.id_do_sales_order')
			->select('dop.id_part_int')
			->select('dop.id_part')
			->select('dop.qty_supply')
			->select('dop.harga_beli')
			->select('dop.harga_setelah_diskon')
			->select('(dop.qty_supply * dop.harga_setelah_diskon) as amount')
			->from('tr_h3_md_do_sales_order_parts as dop')
			->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = dop.id_do_sales_order')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
			->join('tr_h3_dealer_purchase_order as po', 'po.po_id = so.id_ref')
			->where('do.id_do_sales_order', $id_do_sales_order)
			->where('do.sudah_create_faktur', 0)
			->where('dop.qty_supply >', 0)
			->get()->result_array();

		// log_message('debug', sprintf('List parts DO %s %s', $id_do_sales_order, print_r($parts, true)));

		foreach ($parts as $part) {
			$this->purchase_order_dealer->add_amount_supply($part['po_id'], $part['amount'], $part['id_do_sales_order']);
		}

		if ($rekapan) {
			foreach ($parts as $part) {
				$parts_purchase_order_rekapan = $this->db
					->select('rpp.po_id')
					->select('pop.harga_setelah_diskon')
					->select('pop.id_part')
					->select('pemenuhan_po.qty_do')
					->select('pemenuhan_po.qty_supply')
					->select('(pop.kuantitas - pemenuhan_po.qty_supply) as kuantitas_harus_supply', false)
					->from('tr_h3_md_rekap_purchase_order_dealer_parts as rpp')
					->join('tr_h3_dealer_purchase_order_parts as pop', '(pop.po_id = rpp.po_id AND pop.id_part = rpp.id_part)')
					->join('tr_h3_md_pemenuhan_po_dari_dealer as pemenuhan_po', '(pemenuhan_po.po_id = pop.po_id and pemenuhan_po.id_part = pop.id_part)')
					->where('rpp.id_part', $part['id_part'])
					->where('rpp.id_rekap', $part['id_rekap_purchase_order_dealer'])
					->having('kuantitas_harus_supply > ', 0)
					->order_by('pemenuhan_po.qty_supply', 'desc')
					->get()->result_array();

				$kuantitas_yang_akan_dibagi = $part['qty_supply'];
				log_message('info', sprintf('Kuantitas yang akan dibagi untuk kode part %s adalah %s', $part['id_part'], $kuantitas_yang_akan_dibagi));
				log_message('debug', sprintf('Parts purchase order rekapan %s', print_r($parts_purchase_order_rekapan, true)));
				foreach ($parts_purchase_order_rekapan as $part_purchase_order_rekapan) {
					$harga_setelah_diskon = floatval($part_purchase_order_rekapan['harga_setelah_diskon']);

					if ($kuantitas_yang_akan_dibagi > $part_purchase_order_rekapan['kuantitas_harus_supply']) {
						$amount = $harga_setelah_diskon * floatval($part_purchase_order_rekapan['kuantitas_harus_supply']);
						$this->purchase_order_dealer->add_amount_supply($part_purchase_order_rekapan['po_id'], $amount, $part['id_do_sales_order']);

						$kuantitas_yang_akan_dibagi -= $part_purchase_order_rekapan['kuantitas_harus_supply'];
						log_message('info', sprintf('Total pembelian part %s pada purchase order dealer %s dengan kuantitas %s adalah %s', $part_purchase_order_rekapan['id_part'], $part_purchase_order_rekapan['po_id'], $part_purchase_order_rekapan['kuantitas_harus_supply'], rupiah_format($amount, true)));
					} else if ($kuantitas_yang_akan_dibagi <= $part_purchase_order_rekapan['kuantitas_harus_supply']) {
						$amount = $harga_setelah_diskon * floatval($kuantitas_yang_akan_dibagi);
						$this->purchase_order_dealer->add_amount_supply($part_purchase_order_rekapan['po_id'], $amount, $part['id_do_sales_order']);
						log_message('info', sprintf('Total pembelian part %s pada purchase order dealer %s adalah %s', $part_purchase_order_rekapan['id_part'], $part_purchase_order_rekapan['po_id'], $kuantitas_yang_akan_dibagi, rupiah_format($amount, true)));
						break;
					}
				}
			}
		}
	}

	public function tambah_amount_supply_po_dealer_v2($id_do_sales_order)
	{
		$this->load->model('h3_dealer_purchase_order_model', 'purchase_order_dealer');

		$delivery_order = $this->db
			->select('do.id_do_sales_order')
			->select('do.id_sales_order')
			->from('tr_h3_md_do_sales_order as do')
			->where('do.id_do_sales_order', $id_do_sales_order)
			->limit(1)
			->get()->row_array();

		if ($delivery_order == null) throw new Exception(sprintf('Delivery order MD %s tidak ditemukan', $id_do_sales_order));

		$sales_order = $this->db
			->select('so.id_sales_order')
			->select('(so.id_rekap_purchase_order_dealer IS NOT NULL AND so.id_rekap_purchase_order_dealer != "") as rekapan', false)
			->from('tr_h3_md_sales_order as so')
			->where('so.id_sales_order', $delivery_order['id_sales_order'])
			->limit(1)
			->get()->row_array();

		if ($sales_order == null) throw new Exception(sprintf('Sales order MD %s tidak ditemukan', $delivery_order['id_sales_order']));

		$rekapan = $sales_order['rekapan'] == 1;

		$parts = $this->db
			->select('po.po_id')
			->select('so.id_sales_order')
			->select('so.id_rekap_purchase_order_dealer')
			->select('dop.id_do_sales_order')
			->select('dop.id_part_int')
			->select('dop.id_part')
			->select('dop.qty_supply')
			->select('dop.harga_beli')
			->select('dop.harga_setelah_diskon')
			->select('(dop.qty_supply * dop.harga_setelah_diskon) as amount')
			->from('tr_h3_md_do_sales_order_parts as dop')
			// ->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = dop.id_do_sales_order')
			// ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
			// ->join('tr_h3_dealer_purchase_order as po', 'po.po_id = so.id_ref')
			->join('tr_h3_md_do_sales_order as do', 'do.id=dop.id_do_sales_order_int')
			->join('tr_h3_md_sales_order as so', 'so.id=do.id_sales_order_int')
			->join('tr_h3_dealer_purchase_order as po', 'po.po_id = so.id_ref')
			->where('do.id_do_sales_order', $id_do_sales_order)
			->where('do.sudah_create_faktur', 0)
			->where('dop.qty_supply >', 0)
			->get()->result_array();

		// log_message('debug', sprintf('List parts DO %s %s', $id_do_sales_order, print_r($parts, true)));

		foreach ($parts as $part) {
			$this->purchase_order_dealer->add_amount_supply($part['po_id'], $part['amount'], $part['id_do_sales_order']);
		}

		if ($rekapan) {
			foreach ($parts as $part) {
				$parts_purchase_order_rekapan = $this->db
					->select('rpp.po_id')
					->select('pop.harga_setelah_diskon')
					->select('pop.id_part')
					->select('pemenuhan_po.qty_do')
					->select('pemenuhan_po.qty_supply')
					->select('(pop.kuantitas - pemenuhan_po.qty_supply) as kuantitas_harus_supply', false)
					->from('tr_h3_md_rekap_purchase_order_dealer_parts as rpp')
					->join('tr_h3_dealer_purchase_order_parts as pop', '(pop.po_id = rpp.po_id AND pop.id_part = rpp.id_part)')
					->join('tr_h3_md_pemenuhan_po_dari_dealer as pemenuhan_po', '(pemenuhan_po.po_id = pop.po_id and pemenuhan_po.id_part = pop.id_part)')
					->where('rpp.id_part', $part['id_part'])
					->where('rpp.id_rekap', $part['id_rekap_purchase_order_dealer'])
					->having('kuantitas_harus_supply > ', 0)
					->order_by('pemenuhan_po.qty_supply', 'desc')
					->get()->result_array();

				$kuantitas_yang_akan_dibagi = $part['qty_supply'];
				log_message('info', sprintf('Kuantitas yang akan dibagi untuk kode part %s adalah %s', $part['id_part'], $kuantitas_yang_akan_dibagi));
				log_message('debug', sprintf('Parts purchase order rekapan %s', print_r($parts_purchase_order_rekapan, true)));
				foreach ($parts_purchase_order_rekapan as $part_purchase_order_rekapan) {
					$harga_setelah_diskon = floatval($part_purchase_order_rekapan['harga_setelah_diskon']);

					if ($kuantitas_yang_akan_dibagi > $part_purchase_order_rekapan['kuantitas_harus_supply']) {
						$amount = $harga_setelah_diskon * floatval($part_purchase_order_rekapan['kuantitas_harus_supply']);
						$this->purchase_order_dealer->add_amount_supply($part_purchase_order_rekapan['po_id'], $amount, $part['id_do_sales_order']);

						$kuantitas_yang_akan_dibagi -= $part_purchase_order_rekapan['kuantitas_harus_supply'];
						log_message('info', sprintf('Total pembelian part %s pada purchase order dealer %s dengan kuantitas %s adalah %s', $part_purchase_order_rekapan['id_part'], $part_purchase_order_rekapan['po_id'], $part_purchase_order_rekapan['kuantitas_harus_supply'], rupiah_format($amount, true)));
					} else if ($kuantitas_yang_akan_dibagi <= $part_purchase_order_rekapan['kuantitas_harus_supply']) {
						$amount = $harga_setelah_diskon * floatval($kuantitas_yang_akan_dibagi);
						$this->purchase_order_dealer->add_amount_supply($part_purchase_order_rekapan['po_id'], $amount, $part['id_do_sales_order']);
						log_message('info', sprintf('Total pembelian part %s pada purchase order dealer %s adalah %s', $part_purchase_order_rekapan['id_part'], $part_purchase_order_rekapan['po_id'], $kuantitas_yang_akan_dibagi, rupiah_format($amount, true)));
						break;
					}
				}
			}
		}
	}

	public function set_status_picking_list($id_do_sales_order)
	{
		$this->db
			->set('do.status', 'Picking List')
			->where('do.id_do_sales_order', $id_do_sales_order)
			->where('do.status', 'Approved')
			->update(sprintf('%s as do', $this->table));
	}

	public function update_harga($id_part_int)
	{
		$this->db
			->select('dop.id')
			->from('tr_h3_md_do_sales_order_parts as dop')
			->join(sprintf('%s as do', $this->table), 'dop.id_do_sales_order_int = do.id')
			->where('dop.id_part_int', $id_part_int)
			->where('do.sudah_create_faktur', 0);

		foreach ($this->db->get()->result_array() as $part) {
			$this->do_sales_order_parts->update_harga($part['id']);
		}
	}

	public function update_diskon_belum_create_faktur()
	{
		$this->db
			->select('do.id')
			->select('do.id_do_sales_order')
			->select('do.status')
			->select('so.id_dealer')
			->select('so.po_type')
			->select('so.produk')
			->select('so.kategori_po')
			->where('do.sudah_create_faktur', 0)
			->from(sprintf('%s as do', $this->table))
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order');

		foreach ($this->db->get()->result_array() as $row) {
			$this->update_diskon($row['id']);
		}
	}

	public function update_diskon($id)
	{
		$do_sales_order = $this->db
			->select('do.id')
			->select('do.id_do_sales_order')
			->select('do.status')
			->select('so.id_dealer')
			->select('so.po_type')
			->select('so.produk')
			->select('so.kategori_po')
			->where('do.id', $id)
			->from(sprintf('%s as do', $this->table))
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
			->get()->row_array();

		if ($do_sales_order == null) {
			throw new Exception(sprintf('Delivery order tidak ditemukan [%s]', $id));
		}

		log_message('debug', sprintf('Memperbarui diskon untuk DO MD dengan nomor %s bertipe %s dan status %s [%s]', $do_sales_order['id_do_sales_order'], $do_sales_order['po_type'], $do_sales_order['status'], $do_sales_order['id']));

		$parts = $this->db
			->select('dop.id')
			->select('dop.id_part_int')
			->select('dop.id_part')
			->select('dop.id_tipe_kendaraan')
			->select('dop.tipe_diskon_satuan_dealer as tipe_diskon')
			->select('dop.diskon_satuan_dealer as diskon_value')
			->select('dop.tipe_diskon_campaign')
			->select('dop.diskon_campaign as diskon_value_campaign')
			->select('dop.qty_supply as kuantitas')
			->from('tr_h3_md_do_sales_order_parts as dop')
			->where('dop.qty_supply > 0', null, false)
			->where('dop.id_do_sales_order_int', $do_sales_order['id'])
			->get()->result_array();

		$parts = get_diskon_part($do_sales_order['id_dealer'], $do_sales_order['po_type'], $do_sales_order['produk'], $do_sales_order['kategori_po'], $parts);
		foreach ($parts as $part) {
			$this->db
				->set('dop.tipe_diskon_satuan_dealer', $part['tipe_diskon'])
				->set('dop.diskon_satuan_dealer', $part['diskon_value'])
				->set('dop.tipe_diskon_campaign', $part['tipe_diskon_campaign'])
				->set('dop.diskon_campaign', $part['diskon_value_campaign'])
				->set('dop.id_diskon_campaign', $part['id_campaign_diskon'])
				->where('dop.id', $part['id'])
				->update('tr_h3_md_do_sales_order_parts as dop');
			$this->do_sales_order_parts->update_harga($part['id']);
		}
	}

	public function set_revisi($id_do_sales_order)
	{
		$this->db
			->set('do.sudah_revisi', 1)
			->where('do.id_do_sales_order', $id_do_sales_order)
			->update(sprintf('%s as do', $this->table));

		log_message('debug', sprintf('Set delivery order nomor %s sudah direvisi', $id_do_sales_order));
	}

	public function update_gimmick_dan_cashback($id_do_sales_order)
	{
		$this->load->model('h3_md_sales_order_model', 'sales_order');

		$do_sales_order = (array) $this->find($id_do_sales_order, 'id_do_sales_order');
		$sales_order = $this->sales_order->find($do_sales_order['id_sales_order'], 'id_sales_order');

		$this->db
			->select('dop.id_part')
			->select('dop.qty_supply')
			->from('tr_h3_md_do_sales_order_parts as dop')
			->where('dop.id_do_sales_order', $do_sales_order['id']);

		if ($sales_order->kategori_po == 'KPB') {
			$this->db->select('dop.id_tipe_kendaraan');
			$this->db->group_by('dop.id_tipe_kendaraan');
		}

		$parts = $this->db->get()->result_array();

		if (count($parts) > 0 and $sales_order['kategori_po'] != 'KPB') {
			$this->do_sales_order_gimmick->delete($id_do_sales_order, 'id_do_sales_order');
			$gimmick = $this->sales_campaign->get_gimmick_campaign($parts);
			$gimmick = array_map(function ($item) use ($id_do_sales_order) {
				$item['id_do_sales_order'] = $id_do_sales_order;
				return $item;
			}, $gimmick);

			if (count($gimmick) > 0) {
				$this->do_sales_order_gimmick->insert_batch($gimmick);
			}

			$this->do_sales_order_cashback->delete($id_do_sales_order, 'id_do_sales_order');

			$cashback = $this->sales_campaign->get_cashback_campaign($parts);

			$cashback_langsung = array_filter($cashback, function ($item) {
				if ($item['reward_cashback'] == 'Langsung') {
					return $item;
				}
			}, ARRAY_FILTER_USE_BOTH);
			$cashback = array_map(function ($item) use ($id_do_sales_order) {
				$item['id_do_sales_order'] = $id_do_sales_order;
				unset($item['reward_cashback']);
				return $item;
			}, $cashback);

			if (count($cashback_langsung) > 0) {
				$sum_cashback = array_sum(
					array_map(function ($data) {
						return floatval($data['cashback']);
					}, $cashback_langsung)
				);
				$this->do_sales_order_cashback->insert_batch($cashback);

				$this->do_sales_order->update([
					'diskon_cashback_otomatis' => $sum_cashback,
				], [
					'id_do_sales_order' => $id_do_sales_order
				]);
			}
		}
	}
}
