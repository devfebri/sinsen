<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_create_faktur extends Honda_Controller
{

	protected $folder = "h3";
	protected $page   = "h3_md_create_faktur";
	protected $title  = "Create Faktur";

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
		$this->load->library('Mcarbon');

		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		$auth = $this->m_admin->user_auth($this->page, "select");
		$sess = $this->m_admin->sess_auth();
		if ($name == "" or $auth == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "denied'>";
		} elseif ($sess == 'false') {
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "crash'>";
		}

		$this->load->model('h3_md_sales_order_model', 'sales_order');
		$this->load->model('h3_md_do_sales_order_model', 'do_sales_order');
		$this->load->model('h3_md_do_sales_order_parts_model', 'do_sales_order_parts');
		$this->load->model('h3_md_picking_list_model', 'picking_list');
		$this->load->model('h3_md_picking_list_parts_model', 'picking_list_parts');
		$this->load->model('h3_md_packing_sheet_model', 'packing_sheet');
		$this->load->model('h3_md_kartu_stock_model', 'kartu_stock');
		$this->load->model('dealer_model', 'dealer');
		$this->load->model('notifikasi_model', 'notifikasi');
		$this->load->model('H3_dealer_order_parts_tracking_model', 'order_parts_tracking');
		$this->load->model('H3_md_sales_campaign_model', 'sales_campaign');
		$this->load->model('H3_md_pencatatan_poin_sales_campaign_model', 'pencatatan_poin_sales_campaign');
		$this->load->model('H3_md_ms_plafon_model', 'plafon');
		$this->load->model('H3_md_pemenuhan_po_dari_dealer_model', 'pemenuhan_po_dari_dealer');
	}

	public function index()
	{
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$this->template($data);
	}

	public function detail()
	{
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$do_sales_order = $this->db
			->select('date_format(dso.tanggal, "%d-%m-%Y") as tanggal_do')
			->select('dso.id_do_sales_order')
			->select('date_format(so.tanggal_order, "%d-%m-%Y") as tanggal_so')
			->select('so.id_sales_order')
			->select('d.nama_dealer')
			->select('dso.check_ppn_tools')
			->select('dso.sub_total')
			->select('ifnull(dso.total_ppn,0) as total_ppn')
			->select('dso.total')
			->select('d.kode_dealer_md as kode_dealer')
			->select('d.alamat')
			->select('so.kategori_po')
			->select('so.po_type')
			->select('dso.status')
			->select('dso.check_diskon_insentif')
			->select('dso.diskon_insentif')
			->select('dso.check_diskon_cashback')
			->select('dso.diskon_cashback')
			->select('dso.diskon_cashback_otomatis')
			->select('k.nama_lengkap as nama_salesman')
			->select('so.id_dealer')
			->select('pl.selesai_scan')
			->select('
			case
				when ps.no_faktur is not null then 1
				else 0
			end as faktur_created
		', false)
			->select('dso.top')
			->select('dso.gimmick')
			->select('dso.sudah_revisi')
			->select('ps.faktur_printed')
			->from('tr_h3_md_do_sales_order as dso')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = dso.id_sales_order')
			->join('tr_h3_md_picking_list as pl', '(pl.id_ref = dso.id_do_sales_order)')
			->join('tr_h3_md_packing_sheet as ps', 'ps.id_picking_list = pl.id_picking_list', 'left')
			->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
			->join('ms_karyawan as k', 'k.id_karyawan = so.id_salesman', 'left')
			->where('dso.id_do_sales_order', $this->input->get('id'))
			->limit(1)
			->get()->row_array();

		$do_sales_order['plafon'] = $this->plafon->get_plafon($do_sales_order['id_dealer'], $do_sales_order['gimmick'], $do_sales_order['kategori_po']);
		$do_sales_order['plafon_yang_dipakai'] = $this->plafon->get_plafon_terpakai($do_sales_order['id_dealer'], $do_sales_order['gimmick'], $do_sales_order['kategori_po']);
		$do_sales_order['plafon_booking'] = $this->plafon->get_plafon_booking($do_sales_order['id_dealer'], $do_sales_order['gimmick'], $do_sales_order['kategori_po']);
		$data['do_sales_order'] = $do_sales_order;

		$this->db
			->select('dsop.id_part')
			->select('p.nama_part')
			->select('dsop.qty_supply')
			->select('dsop.tipe_diskon_satuan_dealer')
			->select('dsop.diskon_satuan_dealer')
			->select('dsop.tipe_diskon_campaign')
			->select('dsop.diskon_campaign')
			->select('d.nama_dealer')
			->select('d.kode_dealer_md as kode_dealer')
			->select('d.alamat')
			->select('ifnull(kp.include_ppn, 0) as include_ppn')
			->select('dsop.harga_jual')
			->select('dsop.harga_beli')
			->select('(sc.jenis_diskon_campaign = "Additional") as additional_discount', false)
			->from('tr_h3_md_do_sales_order_parts as dsop')
			->join('tr_h3_md_do_sales_order as dso', 'dso.id_do_sales_order = dsop.id_do_sales_order')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = dso.id_sales_order')
			->join('tr_h3_dealer_purchase_order as po', 'so.id_ref = po.po_id')
			->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
			->join('ms_part as p', 'p.id_part = dsop.id_part')
			->join('ms_kelompok_part as kp', 'p.kelompok_part = kp.id_kelompok_part', 'left')
			->join('ms_h3_md_sales_campaign as sc', '(sc.id = dsop.id_diskon_campaign AND sc.jenis_reward_diskon = 1)', 'left')
			->where('dsop.id_do_sales_order', $this->input->get('id'))
			->where('dsop.qty_supply >', 0)
			->order_by('dsop.id_part', 'asc');

		if ($do_sales_order['kategori_po'] == 'KPB') {
			$this->db->select('dsop.id_tipe_kendaraan');
			$this->db->join('tr_h3_md_sales_order_parts as sop', '(sop.id_sales_order = dso.id_sales_order AND sop.id_part = dsop.id_part and sop.id_tipe_kendaraan = dsop.id_tipe_kendaraan)');
		}

		$data['do_sales_order_parts'] = $this->db->get()->result_array();

		$this->template($data);
	}

	public function create_faktur()
	{
		$this->db->trans_start();
		$data = $this->db
			->select('pl.id_picking_list')
			->select('pl.id as id_picking_list_int')
			->select('pl.selesai_scan')
			->select('so.id_sales_order')
			->select('so.kategori_po')
			->select('so.gimmick')
			->select('so.jenis_pembayaran')
			->select('so.produk')
			->select('so.id_ref')
			->select('
			case
				when so.produk = "Oil" then d.top_oli
				else d.top_part
			end as top
		', false)
			->select('d.kode_dealer_md')
			->select('d.nama_dealer')
			->select('d.id_dealer')
			->from('tr_h3_md_do_sales_order as do')
			// ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
			// ->join('tr_h3_md_picking_list as pl', 'pl.id_ref = do.id_do_sales_order')
			->join('tr_h3_md_sales_order as so', 'so.id = do.id_sales_order_int')
			->join('tr_h3_md_picking_list as pl', 'pl.id_ref_int=do.id')
			->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
			->where('do.id_do_sales_order', $this->input->get('id'))
			->get()->row();

		if ($data->selesai_scan == 0) throw new Exception(sprintf('Transaksi belum selesai dilakukan scanning parts pada picking list %s, mohon selesaikan proses tersebut terlebih dahulu', $data->id_picking_list));

		$faktur = $this->db
			->from('tr_h3_md_packing_sheet as ps')
			// ->where('ps.id_picking_list', $data->id_picking_list) 
			->where('ps.id_picking_list_int', $data->id_picking_list_int)
			->get()->row_array();

		if ($faktur != null) {
			$this->session->set_flashdata('pesan', "Faktur untuk DO {$this->input->get('id')} sudah pernah dibuat.");
			$this->session->set_flashdata('tipe', 'warning');
			echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "h3/$this->page/detail?id={$this->input->get('id')}'>";
			die();
		}

		$this->picking_list->update([
			'status' => 'Create Faktur'
		], [
			'id_ref' => $this->input->get('id')
		]);

		// $this->do_sales_order->tambah_amount_supply_po_dealer($this->input->get('id'));
		$this->do_sales_order->tambah_amount_supply_po_dealer_v2($this->input->get('id'));

		$this->do_sales_order->update([
			'sudah_create_faktur' => 1,
			'status' => 'Create Faktur'
		], [
			'id_do_sales_order' => $this->input->get('id')
		]);

		$this->update_qty_supply_pemenuhan_hotline($this->input->get('id'));

		$this->do_sales_order->close_so_jika_terpenuhi($this->input->get('id'));

		$this->sales_order->update([
			'so_tidak_diizinkan_batal' => 1
		], [
			'id_sales_order' => $data->id_sales_order
		]);

		$tgl_jatuh_tempo = Mcarbon::now();
		if ($data->jenis_pembayaran == 'Credit') {
			if ($data->gimmick == 1) {
				$dealer_penampungan_gimmick = $this->db
					->select('d.top_oli')
					->select('d.top_part')
					->from('ms_dealer as d')
					->where('d.tipe_plafon_h3', 'gimmick')
					->limit(1)
					->get()->row_array();

				if ($data->produk == 'Oil') {
					$tgl_jatuh_tempo = $tgl_jatuh_tempo->addDays($dealer_penampungan_gimmick['top_oli']);
				} else if ($data->produk == 'Part') {
					$tgl_jatuh_tempo = $tgl_jatuh_tempo->addDays($dealer_penampungan_gimmick['top_part']);
				}
			} else {
				$tgl_jatuh_tempo = $tgl_jatuh_tempo->addDays($data->top);
			}
		}
		$tgl_jatuh_tempo = $tgl_jatuh_tempo->format('Y-m-d');
		$no_faktur = $this->packing_sheet->generateFaktur($data->kode_dealer_md);
		$packing_sheet = [
			'id_picking_list' => $data->id_picking_list,
			'no_faktur' => $no_faktur,
			'tgl_faktur' => date('Y-m-d H:i:s', time()),
			'tgl_jatuh_tempo' => $tgl_jatuh_tempo,
		];

		// Cek no faktur yang sama telah dibuat
		$total_faktur_sama = $this->db
			->from('tr_h3_md_packing_sheet as ps')
			->where('ps.no_faktur', $no_faktur)
			->count_all_results();
		if ($total_faktur_sama == 0) {
			$this->packing_sheet->insert($packing_sheet);
		} else {
			send_json([
				'message' => 'Faktur yang sama sudah pernah dibuat sebelumnya'
			], 422);
		}

		// Sinkron kan TOP DO dengan faktur
		$this->db
			->set('top', $tgl_jatuh_tempo)
			->where('id_do_sales_order', $this->input->get('id'))
			->update('tr_h3_md_do_sales_order');

		$this->potong_stok($packing_sheet['no_faktur']);

		// $menu_kategori = $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'faktur_created_ready_to_print_packing_sheet')->get()->row_array();
		// if ($menu_kategori != null) {
		// 	$this->notifikasi->insert([
		// 		'id_notif_kat' => $menu_kategori['id_notif_kat'],
		// 		'judul' => $menu_kategori['nama_kategori'],
		// 		'pesan' => "No. Faktur {$packing_sheet['no_faktur']} a.n {$data->nama_dealer} telah di create faktur. Silahkan Cetak Packing Sheet.",
		// 		'link' => "{$menu_kategori['link']}",
		// 		'show_popup' => $menu_kategori['popup'],
		// 	]);
		// }

		$this->db
			->select('so.id_ref')
			->select('so.id_rekap_purchase_order_dealer')
			->select('plp.id_part')
			->select('plp.id_part_int')
			->select('plp.qty_disiapkan')
			->from('tr_h3_md_do_sales_order as do')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
			->join('tr_h3_md_picking_list as pl', 'pl.id_ref = do.id_do_sales_order')
			->join('tr_h3_md_picking_list_parts as plp', 'plp.id_picking_list = pl.id_picking_list')
			->where('do.id_do_sales_order', $this->input->get('id'));


		if ($data->kategori_po == 'KPB') {
			$this->db->select('plp.id_tipe_kendaraan');
		}

		$parts = $this->db->get()->result_array();

		foreach ($parts as $part) {
			// Update tabel stok part summary qty booking dan qty onhand (-)
			/*$check_id_part = $this->db->select('id_part_int')
									->from('tr_stok_part_summary')
									->where('id_part_int',$part['id_part_int'])->get()->row_array();
			if($check_id_part['id_part_int']!=NULL){
				$this->db->set('qty_book', "qty_book - {$part['qty_disiapkan']}", false);
				$this->db->set('qty', "qty - {$part['qty_disiapkan']}", false);
				$this->db->where('id_part_int', $part['id_part_int']);
				$this->db->update('tr_stok_part_summary');
			}*/

			// Update Order Parts Tracking
			if ($data->kategori_po == 'KPB') {
				$this->order_parts_tracking->tambah_qty_bill($part['id_ref'], $part['id_part'], $part['qty_disiapkan'], $part['id_tipe_kendaraan']);
			} else {
				$this->order_parts_tracking->tambah_qty_bill($part['id_ref'], $part['id_part'], $part['qty_disiapkan']);
			}
			if ($part['id_rekap_purchase_order_dealer'] != null) {
				$this->db
					->select('po.po_id')
					->select('pop.id_part')
					->select('(opt.qty_pack - opt.qty_bill) as selisih')
					->select("ppd.qty_do as kuantitas_yang_boleh_disupply", false)
					->from('tr_h3_md_rekap_purchase_order_dealer_item as ri')
					->join('tr_h3_dealer_purchase_order as po', 'po.po_id = ri.id_referensi')
					->join('tr_h3_dealer_purchase_order_parts as pop', 'pop.po_id = ri.id_referensi')
					->join('tr_h3_dealer_order_parts_tracking as opt', '(opt.po_id = pop.po_id and opt.id_part = pop.id_part)')
					->join('tr_h3_md_pemenuhan_po_dari_dealer as ppd', '(ppd.po_id = pop.po_id AND ppd.id_part = pop.id_part)')
					->where('ri.id_rekap', $part['id_rekap_purchase_order_dealer'])
					->where('pop.id_part', $part['id_part'])
					->order_by('kuantitas_yang_boleh_disupply', 'asc')
					->order_by('po.created_at', 'desc');

				if ($data->kategori_po == 'KPB') {
					$this->db->select('pop.id_tipe_kendaraan');
					$this->db->where('pop.id_tipe_kendaraan', $part['id_tipe_kendaraan']);
				}

				$purchase_orders = $this->db->get()->result_array();

				log_message('debug', sprintf('%s Parts purchase order %s', __FUNCTION__, print_r($purchase_orders, true)));

				$supply_untuk_dipecah = $part['qty_disiapkan'];
				foreach ($purchase_orders as $purchase_order) {
					if ($purchase_order['selisih'] <= $supply_untuk_dipecah) {
						if ($data->kategori_po == 'KPB') {
							$this->order_parts_tracking->tambah_qty_bill($purchase_order['po_id'], $purchase_order['id_part'], $purchase_order['selisih'], $part['id_tipe_kendaraan']);
						} else {
							$this->order_parts_tracking->tambah_qty_bill($purchase_order['po_id'], $purchase_order['id_part'], $purchase_order['selisih']);
						}
						$supply_untuk_dipecah -= $purchase_order['selisih'];
					} else if ($purchase_order['selisih'] >= $supply_untuk_dipecah) {
						if ($data->kategori_po == 'KPB') {
							$this->order_parts_tracking->tambah_qty_bill($purchase_order['po_id'], $purchase_order['id_part'], $supply_untuk_dipecah, $part['id_tipe_kendaraan']);
						} else {
							$this->order_parts_tracking->tambah_qty_bill($purchase_order['po_id'], $purchase_order['id_part'], $supply_untuk_dipecah);
						}
						break;
					}

					if ($supply_untuk_dipecah == 0) break;
				}
			}
		}

		$this->generate_notif_do_gimmick($this->input->get('id'));

		
		$kuantitas = $this->db
		->select('SUM(pop.kuantitas) as kuantitas')
		->from('tr_h3_dealer_purchase_order_parts as pop')
		->where('pop.po_id',$data->id_ref)
		->get()->row();

		$qty_terpenuhi = $this->db
			->select('SUM(opt.qty_bill) as qty_bill')
			->from('tr_h3_dealer_order_parts_tracking as opt')
			->where('opt.po_id',$data->id_ref)
			->get()->row();

		
		$this->load->model('h3_dealer_purchase_order_model', 'dealer_purchase_order');			
		if($kuantitas->kuantitas==$qty_terpenuhi->qty_bill){
			$this->dealer_purchase_order->update([
				'status' => 'Closed',
				'status_md' => 'Closed',
				'tanggal_selesai' => date('Y-m-d', time())
			], ['po_id' => $data->id_ref]);
		}
		// $this->update_status_po();

		$this->db->trans_complete();


		if ($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Faktur berhasil dibuat.');
			$this->session->set_flashdata('tipe', 'info');
		} else {
			$this->session->set_flashdata('pesan', 'Faktur tidak berhasil dibuat.');
			$this->session->set_flashdata('tipe', 'danger');
		}

		redirect(
			base_url("h3/$this->page/detail?id={$this->input->get('id')}")
		);
	}

	public function update_qty_supply_pemenuhan_hotline($id_do_sales_order)
	{
		$delivery_order_parts = $this->db
			->select('so.id_sales_order')
			->select('so.po_type')
			->select('so.id_ref')
			->select('so.id_rekap_purchase_order_dealer')
			->select('dop.id_part')
			->select('dop.id_part_int')
			->select('dop.qty_supply')
			->from('tr_h3_md_do_sales_order_parts as dop')
			// ->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = dop.id_do_sales_order')
			// ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
			->join('tr_h3_md_do_sales_order as do', 'dop.id_do_sales_order_int=do.id')
			->join('tr_h3_md_sales_order as so', 'so.id=do.id_sales_order_int')
			->where('dop.id_do_sales_order', $id_do_sales_order)
			->get()->result_array();


		foreach ($delivery_order_parts as $delivery_order_part) {
			if ($delivery_order_part['id_rekap_purchase_order_dealer'] != null && $delivery_order_part['id_rekap_purchase_order_dealer'] != '') {
				$jumlah_item = $this->db
					->select('SUM( pop.kuantitas - ppd.qty_supply ) as jumlah_item', false)
					->from('tr_h3_dealer_purchase_order_parts as pop')
					->join('tr_h3_md_pemenuhan_po_dari_dealer as ppd', '(ppd.po_id = pop.po_id AND ppd.id_part = pop.id_part)')
					->where('pop.po_id = rpodp.po_id', null, false)
					->where('pop.id_part = rpodp.id_part', null, false)
					->get_compiled_select();

				$list_part_rekap = $this->db
					->select('rpodp.po_id')
					->select('rpodp.id_part')
					->select('rpodp.kuantitas')
					->select("IFNULL(({$jumlah_item}), 0) as jumlah_item", false)
					->from('tr_h3_md_rekap_purchase_order_dealer_parts as rpodp')
					->where('rpodp.id_rekap', $delivery_order_part['id_rekap_purchase_order_dealer'])
					->where('rpodp.id_part', $delivery_order_part['id_part'])
					->order_by('jumlah_item', 'asc')
					->having('jumlah_item >', 0)
					->get()->result_array();

				log_message('debug', sprintf('[%s] List part rekap %s', __FUNCTION__, print_r($list_part_rekap, true)));

				if (count($list_part_rekap) > 0) {
					$supply_untuk_dibagi = $delivery_order_part['qty_supply'];
					log_message('debug', sprintf('Kuantitas yang akan dibagi %s', $supply_untuk_dibagi));
					foreach ($list_part_rekap as $row) {
						if ($row['kuantitas'] <= $supply_untuk_dibagi) {
							$this->pemenuhan_po_dari_dealer->kurangi_qty_do($row['po_id'], $row['id_part'], $row['kuantitas']);
							$this->pemenuhan_po_dari_dealer->tambah_qty_supply($row['po_id'], $row['id_part'], $row['kuantitas']);
							$supply_untuk_dibagi -= $row['kuantitas'];
						} else if ($row['kuantitas'] >= $supply_untuk_dibagi) {
							$this->pemenuhan_po_dari_dealer->kurangi_qty_do($row['po_id'], $row['id_part'], $supply_untuk_dibagi);
							$this->pemenuhan_po_dari_dealer->tambah_qty_supply($row['po_id'], $row['id_part'], $supply_untuk_dibagi);
							break;
						}

						if ($supply_untuk_dibagi == 0) break;
					}
				}
			} elseif (($delivery_order_part['po_type'] == 'HLO' || $delivery_order_part['po_type'] == 'URG') and $delivery_order_part['id_ref'] != null and $delivery_order_part['id_ref'] != '') {
				// $this->pemenuhan_po_dari_dealer->kurangi_qty_do($delivery_order_part['id_ref'], $delivery_order_part['id_part'], $delivery_order_part['qty_supply']);
				$this->pemenuhan_po_dari_dealer->kurangi_qty_do_v2($delivery_order_part['id_ref'], $delivery_order_part['id_part'], $delivery_order_part['qty_supply'],$delivery_order_part['id_part_int']);
				// $this->pemenuhan_po_dari_dealer->tambah_qty_supply($delivery_order_part['id_ref'], $delivery_order_part['id_part'], $delivery_order_part['qty_supply']);
				$this->pemenuhan_po_dari_dealer->tambah_qty_supply_v2($delivery_order_part['id_ref'], $delivery_order_part['id_part'], $delivery_order_part['qty_supply'],$delivery_order_part['id_part_int']);
			}
		}
	}

	public function potong_stok($referensi)
	{
		$parts = $this->db
			->select('plp.id_part')
			->select('plp.id_part_int')
			->select('plp.id_lokasi_rak')
			->select('p.nama_part')
			->select('plp.qty_supply as qty_do')
			->select('plp.qty_disiapkan as qty_picking')
			->select('plp.serial_number as serial_number')
			->from('tr_h3_md_picking_list as pl')
			->join('tr_h3_md_picking_list_parts as plp', 'plp.id_picking_list = pl.id_picking_list')
			->join('ms_part as p', 'p.id_part = plp.id_part')
			->where('pl.id_ref', $this->input->get('id'))
			->get()->result_array();

		foreach ($parts as $part) {
			// Cek apakah EV atau tidak 
			$kelompok_part = $this->db->select('kelompok_part')
				->from('ms_part')
				->where('id_part_int', $part['id_part_int'])
				->get()->row_array();

				if($kelompok_part['kelompok_part']=='EVBT' ||$kelompok_part['kelompok_part']=='EVCH'){
					//Cek tgl faktur 
					$check_tgl_faktur = $this->db->select('tgl_faktur')
												->select('created_by')
												->from('tr_h3_md_packing_sheet')
												->where('no_faktur',$referensi)
												->get()->row_array();
	
					$transaksi_stock = [
						'id_part_int' => $part['id_part_int'],
						'id_part' => $part['id_part'],
						'id_lokasi_rak' => $part['id_lokasi_rak'],
						'tipe_transaksi' => '-',
						'sumber_transaksi' => $this->page,
						'serial_number' => $part['serial_number'],
						'referensi' => $referensi,
						'stock_value' => $part['qty_picking'],
					];
	
					$this->kartu_stock->insert($transaksi_stock);
	
					$this->db->set('no_faktur', $referensi)
					->set('created_at_faktur', $check_tgl_faktur['tgl_faktur'])
					->set('created_by_faktur', $check_tgl_faktur['created_by'])
					->where('id_part_int', $part['id_part_int'])	
					->where('id_lokasi_rak_md', $part['id_lokasi_rak'])
					->where('serial_number', $part['serial_number'])
					->update('tr_h3_serial_ev_tracking');
				}else{
					$transaksi_stock = [
						'id_part_int' => $part['id_part_int'],
						'id_part' => $part['id_part'],
						'id_lokasi_rak' => $part['id_lokasi_rak'],
						'tipe_transaksi' => '-',
						'sumber_transaksi' => $this->page,
						'referensi' => $referensi,
						'stock_value' => $part['qty_picking'],
					];
		
					$this->kartu_stock->insert($transaksi_stock);
				}

			// Update tabel stok part summary qty booking dan qty onhand (-)
			$check_id_part = $this->db->select('id_part_int')
									->from('tr_stok_part_summary')
									->where('id_part_int',$part['id_part_int'])->get()->row_array();
			if($check_id_part['id_part_int']!=NULL){
				$this->db->set('qty_book', "qty_book - {$part['qty_picking']}", false);
				$this->db->set('qty', "qty - {$part['qty_picking']}", false);
				$this->db->where('id_part_int', $part['id_part_int']);
				$this->db->update('tr_stok_part_summary');
			}	

			$this->db->set('qty', "qty-{$part['qty_picking']}", false)
				->where('id_part_int', $part['id_part_int'])
				->where('id_lokasi_rak', $part['id_lokasi_rak'])
				->update('tr_stok_part');
		}
	}

	public function update_status_po()
	{
		$this->load->model('h3_dealer_purchase_order_model', 'dealer_purchase_order');

		$kuantitas = $this->db
			->select('SUM(pop.kuantitas) as kuantitas')
			->from('tr_h3_dealer_purchase_order_parts as pop')
			->where('pop.po_id = po.po_id')
			->get_compiled_select();

		$qty_terpenuhi = $this->db
			->select('SUM(opt.qty_bill) as qty_bill')
			->from('tr_h3_dealer_order_parts_tracking as opt')
			->where('opt.po_id = po.po_id')
			->get_compiled_select();

		$data = $this->db
			->select('po.po_id')
			// ->select("IFNULL(({$kuantitas}), 0) as kuantitas", false)
			// ->select("IFNULL(({$qty_terpenuhi}), 0) as qty_terpenuhi", false)
			->from('tr_h3_dealer_purchase_order as po')
			->where('po.status', 'Processed by MD')
			->group_start()
			->where('po.order_to', 0)
			->or_where('po.order_to', null)
			->group_end()
			->where("IFNULL(({$kuantitas}), 0) = IFNULL(({$qty_terpenuhi}), 0)", null, false)
			->get()->result_array();

		foreach ($data as $row) {
			$this->dealer_purchase_order->update([
				'status' => 'Closed',
				'status_md' => 'Closed',
				'tanggal_selesai' => date('Y-m-d', time())
			], ['po_id' => $row['po_id']]);
		}
	}

	public function approve()
	{
		$this->db->trans_start();
		$data = array_merge($this->input->post(['status', 'check_diskon_insentif', 'diskon_insentif', 'check_diskon_cashback', 'diskon_cashback', 'total']), [
			'status' => 'Approved'
		]);
		$this->do_sales_order->update($data, $this->input->post(['id_do_sales_order']));

		$picking_list = array_merge($this->input->post(['id_dealer']), [
			'id_ref' => $this->input->post('id_do_sales_order'),
			'id_picking_list' => $this->picking_list->generateID(),
			'tipe_ref' => 'do_sales_order',
			'tanggal' => date('Y-m-d', time()),
		]);

		$picking_parts = $this->getOnly(true, $this->input->post('parts'), [
			'id_picking_list' => $this->picking_list->generateID(),
		]);

		$this->picking_list->insert($picking_list);
		$this->picking_list_parts->insert_batch($picking_parts);

		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$do = $this->do_sales_order->get($this->input->post(['id_do_sales_order']), true);
			send_json($do);
		} else {
			$this->set_status_header(500);
		}
	}

	public function reject()
	{
		$this->db->trans_start();
		$data = array_merge($this->input->post(['alasan_reject', 'total']), [
			'status' => 'Rejected'
		]);
		$this->do_sales_order->update($data, $this->input->post(['id_do_sales_order']));
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$do = $this->do_sales_order->get($this->input->post(['id_do_sales_order']), true);
			send_json($do);
		} else {
			$this->set_status_header(500);
		}
	}

	public function cetak()
	{
		$faktur_printed = $this->db
			->from('tr_h3_md_do_sales_order as do')
			->join('tr_h3_md_picking_list as pl', 'pl.id_ref = do.id_do_sales_order')
			->join('tr_h3_md_packing_sheet as ps', 'ps.id_picking_list = pl.id_picking_list')
			->where('do.id_do_sales_order', $this->input->get('id'))
			->where('ps.faktur_printed', 1)
			->get()->row_array();

		if ($faktur_printed != null) {
			$this->session->set_flashdata('pesan', 'Faktur sudah pernah dicetak sebelumnya.');
			$this->session->set_flashdata('tipe', 'warning');
			redirect(
				base_url("h3/$this->page/detail?id={$this->input->get('id')}")
			);
			die;
		} else {
			$faktur = $this->db
				->select('ps.id')
				->from('tr_h3_md_do_sales_order as do')
				->join('tr_h3_md_picking_list as pl', 'pl.id_ref = do.id_do_sales_order')
				->join('tr_h3_md_packing_sheet as ps', 'ps.id_picking_list = pl.id_picking_list')
				->where('do.id_do_sales_order', $this->input->get('id'))
				->get()->row_array();

			$this->db
				->set('ps.tgl_cetak_faktur', date('Y-m-d H:i:s'))
				->set('ps.faktur_printed', 1)
				->where('ps.id', $faktur['id'])
				->update('tr_h3_md_packing_sheet as ps');
		}

		$this->load->helper('terbilang');

		$data = [];
		$data['faktur'] = $this->db
			->select('ps.no_faktur')
			->select('date_format(ps.tgl_faktur, "%d/%m/%Y") as tgl_faktur')
			->select('date_format(ps.tgl_cetak_faktur, "%d/%m/%Y") as tgl_cetak_faktur')
			->select('date_format(ps.tgl_jatuh_tempo, "%d/%m/%Y") as tgl_jatuh_tempo')
			->select('so.jenis_pembayaran')
			->select('d.nama_dealer')
			->select('so.id_ref')
			->select('do.total')
			->select('do.sub_total')
			->select('so.produk')
			->select('so.kategori_po')
			->select('ifnull(do.total_ppn,0) as total_ppn')
			->from('tr_h3_md_do_sales_order as do')
			->join('tr_h3_md_picking_list as pl', 'pl.id_ref = do.id_do_sales_order')
			->join('tr_h3_md_packing_sheet as ps', 'ps.id_picking_list = pl.id_picking_list')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
			->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
			->where('do.id_do_sales_order', $this->input->get('id'))
			->get()->row_array();

		$parts = $this->db
			->select('dop.id_part')
			->select('p.nama_part')
			->select('dop.qty_supply')
			->select('IFNULL(p.qty_dus, 1) as qty_dus')
			->select('dop.harga_jual as harga')
			->select('dop.tipe_diskon_satuan_dealer')
			->select('dop.diskon_satuan_dealer')
			->select('dop.tipe_diskon_campaign')
			->select('dop.diskon_campaign')
			->select('(sc.jenis_diskon_campaign = "Additional") as additional_discount', false)
			->from('tr_h3_md_do_sales_order_parts as dop')
			->join('ms_part as p', 'p.id_part = dop.id_part')
			->join('ms_h3_md_sales_campaign as sc', '(sc.id = dop.id_diskon_campaign AND sc.jenis_reward_diskon = 1)', 'left')
			->where('dop.id_do_sales_order', $this->input->get('id'))
			->where('dop.qty_supply >', 0)
			->order_by('dop.id_part', 'asc')
			->get()->result_array();


		$parts = array_map(function ($data) {
			$data['harga_setelah_diskon'] = harga_setelah_diskon($data['tipe_diskon_satuan_dealer'], $data['diskon_satuan_dealer'], $data['harga'], ($data['additional_discount'] == 1), $data['tipe_diskon_campaign'], $data['diskon_campaign']);
			$data['diskon'] = $data['harga'] - $data['harga_setelah_diskon'];

			$data['amount'] = $data['qty_supply'] * $data['harga_setelah_diskon'];

			unset($data['diskon_satuan_dealer']);
			unset($data['tipe_diskon_satuan_dealer']);
			unset($data['diskon_campaign']);
			unset($data['tipe_diskon_campaign']);

			return $data;
		}, $parts);

		$data['parts'] = $parts;
		$data['dibuat_oleh'] = $this->db
			->select('k.nama_lengkap')
			->from('ms_user as u')
			->join('ms_karyawan as k', 'k.id_karyawan = u.id_karyawan_dealer', 'left')
			->where('u.id_user', $this->session->userdata('id_user'))
			->limit(1)
			->get()->row_array()['nama_lengkap'];

		require_once APPPATH . 'third_party/mpdf/mpdf.php';
		$mpdf = new Mpdf();
		$html = $this->load->view('h3/h3_md_cetak_faktur', $data, true);
		$mpdf->WriteHTML($html);
		$mpdf->Output("{$data['do_sales_order']['id_do_sales_order']}.pdf", "I");
	}

	private function generate_notif_do_gimmick($id_do_sales_order)
	{
		$gimmick = $this->db
			->select('do.id_sales_order')
			->select('dog.id_do_sales_order')
			->select('dog.qty_hadiah')
			->select('dog.id_part')
			->select('p.nama_part')
			->select('dog.satuan_hadiah')
			->from('tr_h3_md_do_sales_order_gimmick as dog')
			->join('ms_part as p', 'p.id_part = dog.id_part')
			->join('ms_h3_md_sales_campaign as sc', 'sc.id = dog.id_campaign')
			->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = dog.id_do_sales_order')
			->where('dog.id_do_sales_order', $id_do_sales_order)
			->where('sc.reward_gimmick', 'Langsung')
			->get()->result_array();

		foreach ($gimmick as $row) {
			$menu_kategori = $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'do_gimmick_available')->get()->row_array();
			if ($menu_kategori != null) {
				$this->notifikasi->insert([
					'id_notif_kat' => $menu_kategori['id_notif_kat'],
					'judul' => $menu_kategori['nama_kategori'],
					'pesan' => "SO {$row['id_sales_order']}, mendapatkan gimmick hadiah {$row['qty_hadiah']} {$row['satuan_hadiah']} {$row['nama_part']}, generate SO hadiahnya.",
					'link' => "{$menu_kategori['link']}/detail?id={$row['id_do_sales_order']}",
					'show_popup' => $menu_kategori['popup'],
				]);
			}
		}
	}
}
