<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_scan_picking_list extends Honda_Controller
{
	protected $folder = "h3";
	protected $page   = "h3_md_scan_picking_list";
	protected $title  = "Scan Picking List";

	public function __construct()
	{
		parent::__construct();
		
		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		$this->load->helper('harga_setelah_diskon');
		$this->load->helper('get_diskon_part');
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

		$this->load->model('h3_md_picking_list_model', 'picking_list');
		$this->load->model('h3_md_picking_list_parts_model', 'picking_list_parts');
		$this->load->model('h3_md_scan_picking_list_parts_model', 'scan_picking_list_parts');
		$this->load->model('h3_md_do_sales_order_model', 'do_sales_order');
		$this->load->model('h3_md_do_sales_order_parts_model', 'do_sales_order_parts');
		$this->load->model('h3_md_do_revisi_model', 'do_revisi');
		$this->load->model('h3_md_do_revisi_item_model', 'do_revisi_item');
		$this->load->model('H3_md_do_revisi_cashback_model', 'do_revisi_cashback');
		$this->load->model('part_model', 'master_part');
		$this->load->model('dealer_model', 'dealer');
		$this->load->model('karyawan_md_model', 'karyawan_md');
		$this->load->model('notifikasi_model', 'notifikasi');		
		$this->load->model('H3_dealer_order_parts_tracking_model','order_parts_tracking');
		$this->load->model('H3_md_sales_campaign_model', 'sales_campaign');
	}

	public function index(){
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$data['picking_list'] = $this->picking_list->all();
		$this->template($data);
	}

	public function scan(){
		$data['mode']    = 'scan';
		$data['set']     = "form";
		$data['picking_list'] = $this->db
		->select('pl.id')
		->select('pl.id_picking_list')
		->select('date_format(pl.tanggal, "%d-%m-%Y") as tanggal_picking')
		->select('do.id_do_sales_order')
		->select('date_format(do.tanggal, "%d-%m-%Y") as tanggal_do')
		->select('k.nama_lengkap as nama_picker')
		->select('d.nama_dealer')
		->select('d.alamat')
		->select('so.kategori_po')
		->select('so.po_type')
		->select('so.produk')
		->select('pl.selesai_scan')
		->select('po_aksesoris.id_paket_bundling')
		->select('(CASE WHEN so.is_ev = 1 THEN "EV" ELSE "Non EV" END) as is_ev')
		->from('tr_h3_md_picking_list as pl')
		->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
		->join('tr_po_aksesoris as po_aksesoris', 'po_aksesoris.no_po_aksesoris = so.referensi_po_bundling', 'left')
		->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
		->join('ms_karyawan as k', 'k.id_karyawan = pl.id_picker')
		->where('pl.id_picking_list', $this->input->get('id_picking_list'))
		->limit(1)
		->get()->row();

		$data['scanned_parts'] = $this->db
		->select('splp.*')
		->select('p.nama_part')
		->select('ifnull(p.qty_dus, 0) as qty_dus')
		->from('tr_h3_md_scan_picking_list_parts as splp')
		->join('ms_part as p', 'p.id_part = splp.id_part')
		->where('splp.id_picking_list', $this->input->get('id_picking_list'))
		->get()->result();

		$this->template($data);
	}

	public function generate_no_dus(){
		$picking_list = $this->db
		->select('so.po_type')
		->select('LEFT(do.id_do_sales_order, 5) as suffix_do')
		->select('so.produk')
		->select('d.kode_dealer_md')
		->from('tr_h3_md_picking_list as pl')
		->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
		->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
		->where('pl.id_picking_list', $this->input->get('id_picking_list'))
		->get()->row_array();

		$query = $this->db
		->select('splp.no_dus')
		->from('tr_h3_md_scan_picking_list_parts as splp')
		->where('splp.id_picking_list', $this->input->get('id_picking_list'))
		->where('splp.produk', $this->input->get('produk'))
		->order_by('splp.no_dus', 'desc')
		->limit(1)
		->get();

		$tanggal_bulan_tahun = date('dmy', time());
		$produk = strtoupper($this->input->get('produk'));

		if($query->num_rows() > 0){
			$data = $query->row_array();
			$no_dus = substr($data['no_dus'], 4, 3);
			$new_digit = sprintf("%'.03d", $no_dus + 1);
			send_json([
				'no_dus' => "DS-{$new_digit}/DO-{$picking_list['po_type']}{$picking_list['suffix_do']}-{$picking_list['kode_dealer_md']}/{$tanggal_bulan_tahun}/{$produk}"
			]);
		}else{
			send_json([
				'no_dus' => "DS-001/DO-{$picking_list['po_type']}{$picking_list['suffix_do']}-{$picking_list['kode_dealer_md']}/{$tanggal_bulan_tahun}/{$produk}"
			]);
		}
	}

	public function add_scan_part(){
		$this->db->trans_start();
		$picking_list = $this->picking_list->find($this->input->post('id_picking_list'), 'id_picking_list');

		$sales_order = $this->db
		->select('so.kategori_po')
		->select('so.id_ref')
		->select('so.id_rekap_purchase_order_dealer')
		->from('tr_h3_md_do_sales_order as do')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
		->where('do.id_do_sales_order', $picking_list->id_ref)
		->get()->row_array();

		$part_keys = ['id_part_int', 'id_part', 'qty_do', 'qty_picking', 'qty_scan', 'no_dus', 'id_lokasi_rak', 'produk', 'id_picking_list', 'id_picking_list_int','serial_number'];
		if($sales_order['kategori_po'] == 'KPB'){
			$part_keys[] = 'id_tipe_kendaraan';
		}
		$data = $this->input->post($part_keys);

		$this->picking_list->check_kuantitas_scan_part_sama_atau_melebih_do($data['id_picking_list_int'], $data['id_part_int'], $data['qty_scan']);

		$this->picking_list->update([
			'status' => 'Scan'
		], $this->input->post(['id_picking_list']));

		$this->picking_list->update([
			'start_scan' => date('Y-m-d H:i:s')
		], [
			'id_picking_list' => $this->input->post('id_picking_list'),
			'start_scan' => null
		]);

		$this->scan_picking_list_parts->insert($data);
		$id = $this->db->insert_id();
		$this->set_tanggal_mulai_scan();
		if($sales_order['kategori_po'] == 'KPB'){
			$this->order_parts_tracking->tambah_qty_pack($sales_order['id_ref'], $data['id_part'], $data['qty_scan'], $data['id_tipe_kendaraan']);
		}else{
			$this->order_parts_tracking->tambah_qty_pack($sales_order['id_ref'], $data['id_part'], $data['qty_scan']);
		}
		if($sales_order['id_rekap_purchase_order_dealer'] != null){
			$jumlah_item = $this->db
			->select('SUM( pop.kuantitas - ppd.qty_supply) as jumlah_item', false)
			->from('tr_h3_dealer_purchase_order_parts as pop')
			->join('tr_h3_md_pemenuhan_po_dari_dealer as ppd', '(ppd.po_id = pop.po_id AND ppd.id_part_int = pop.id_part_int)')
			->where('pop.po_id = po.po_id', null, false)
			->get_compiled_select();

			$this->db
			->select('po.po_id')
			->select('pop.id_part')
			->select('(opt.qty_pick - opt.qty_pack) as selisih')
			->select('pop.id_tipe_kendaraan')
			->select("IFNULL(({$jumlah_item}), 0) as jumlah_item", false)
			->from('tr_h3_md_rekap_purchase_order_dealer_item as ri')
			->join('tr_h3_dealer_purchase_order as po', 'po.po_id = ri.id_referensi')
			->join('tr_h3_dealer_purchase_order_parts as pop', 'pop.po_id = ri.id_referensi')
			// ->join('tr_h3_dealer_order_parts_tracking as opt', '(opt.po_id = pop.po_id and opt.id_part = pop.id_part)')
			->join('tr_h3_dealer_order_parts_tracking as opt', '(opt.po_id = pop.po_id and opt.id_part_int = pop.id_part_int)')
			->where('ri.id_rekap', $sales_order['id_rekap_purchase_order_dealer'])
			->where('pop.id_part', $data['id_part'])
			->order_by('jumlah_item', 'asc')
			->order_by('po.created_at', 'desc');

			if($sales_order['kategori_po'] == 'KPB'){
				$this->db->select('pop.id_tipe_kendaraan');
				$this->db->where('pop.id_tipe_kendaraan', $data['id_tipe_kendaraan']);
			}

			$purchase_urgents = $this->db->get()->result_array();

			$supply_untuk_dipecah = $data['qty_scan'];
			foreach ($purchase_urgents as $purchase_urgent) {
				if($purchase_urgent['selisih'] <= $supply_untuk_dipecah){
					if($sales_order['kategori_po'] == 'KPB'){
						$this->order_parts_tracking->tambah_qty_pack($purchase_urgent['po_id'], $purchase_urgent['id_part'], $purchase_urgent['selisih'], $purchase_urgent['id_tipe_kendaraan']);
					}else{
						$this->order_parts_tracking->tambah_qty_pack($purchase_urgent['po_id'], $purchase_urgent['id_part'], $purchase_urgent['selisih']);
					}
					$supply_untuk_dipecah -= $purchase_urgent['selisih'];
				}else if($purchase_urgent['selisih'] >= $supply_untuk_dipecah){
					if($sales_order['kategori_po'] == 'KPB'){
						$this->order_parts_tracking->tambah_qty_pack($purchase_urgent['po_id'], $purchase_urgent['id_part'], $supply_untuk_dipecah, $purchase_urgent['id_tipe_kendaraan']);
					}else{
						$this->order_parts_tracking->tambah_qty_pack($purchase_urgent['po_id'], $purchase_urgent['id_part'], $supply_untuk_dipecah);
					}
					break;
				}

				if($supply_untuk_dipecah == 0) break;
			}
		}
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$data = $this->db
			->select('splp.*')
			->select('p.nama_part')
			->select('ifnull(p.qty_dus, 0) as qty_dus')
			->from('tr_h3_md_scan_picking_list_parts as splp')
			->join('ms_part as p', 'p.id_part_int = splp.id_part_int')
			->where('splp.id', $id)
			->get()->row();

			send_json([
				'success' => 1,
				'message' => 'Data berhasil ditambahkan',
				'payload' => $data
			]);
		}else{
			send_json([
				'success' => 0,
				'message' => 'Data tidak berhasil ditambahkan'
			], 422);
		}
	}

	public function set_tanggal_mulai_scan(){
		$this->db
		->set('pl.tanggal_mulai_scan', date('Y-m-d H:i:s'))
		->where('pl.id_picking_list', $this->input->post('id_picking_list'))
		->where('pl.tanggal_mulai_scan', null)
		->update('tr_h3_md_picking_list as pl');
	}

	public function remove_scan_parts(){
		$this->db->trans_start();
		$data = $this->db
		->select('so.kategori_po')
		->select('so.id_ref')
		->select('so.id_rekap_purchase_order_dealer')
		->select('splp.id_part')
		->select('splp.id_tipe_kendaraan')
		->select('splp.qty_scan')
		->from('tr_h3_md_scan_picking_list_parts as splp')
		->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = splp.id_picking_list')
		->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
		->where('splp.id', $this->input->get('id'))
		->get()->row_array();

		$this->scan_picking_list_parts->delete($this->input->get('id'));
		if($data['kategori_po'] == 'KPB'){
			$this->order_parts_tracking->kurang_qty_pack($data['id_ref'], $data['id_part'], $data['qty_scan'], $data['id_tipe_kendaraan']);
		}else{
			$this->order_parts_tracking->kurang_qty_pack($data['id_ref'], $data['id_part'], $data['qty_scan']);
		}
		if($data['id_rekap_purchase_order_dealer'] != null){
			$jumlah_item = $this->db
			->select('SUM( pop.kuantitas - ppd.qty_supply) as jumlah_item', false)
			->from('tr_h3_dealer_purchase_order_parts as pop')
			->join('tr_h3_md_pemenuhan_po_dari_dealer as ppd', '(ppd.po_id = pop.po_id AND ppd.id_part = pop.id_part)')
			->where('pop.po_id = po.po_id', null, false)
			->get_compiled_select();

			$purchase_orders = $this->db
			->select('po.po_id')
			->select('pop.id_part')
			->select('(opt.qty_pack - opt.qty_bill) as selisih')
			->select("IFNULL(({$jumlah_item}), 0) as jumlah_item", false)
			->from('tr_h3_md_rekap_purchase_order_dealer_item as ri')
			->join('tr_h3_dealer_purchase_order as po', 'po.po_id = ri.id_referensi')
			->join('tr_h3_dealer_purchase_order_parts as pop', 'pop.po_id = ri.id_referensi')
			->join('tr_h3_dealer_order_parts_tracking as opt', '(opt.po_id = pop.po_id and opt.id_part = pop.id_part)')
			->join('tr_h3_md_rekap_purchase_order_dealer_parts as rpodp', '(rpodp.po_id = po.po_id and rpodp.id_part = pop.id_part)')
			->where('ri.id_rekap', $data['id_rekap_purchase_order_dealer'])
			->where('pop.id_part', $data['id_part'])
			->where('opt.qty_pack > 0')
			->order_by('jumlah_item', 'asc')
			->order_by('po.created_at', 'desc')
			->get()->result_array();

			if($data['kategori_po'] == 'KPB'){
				$this->db->select('pop.id_tipe_kendaraan');
				$this->db->where('pop.id_tipe_kendaraan', $data['id_tipe_kendaraan']);
			}

			$supply_untuk_dipecah = $data['qty_scan'];
			foreach ($purchase_orders as $purchase_order) {
				if($purchase_order['selisih'] <= $supply_untuk_dipecah){
					if($data['kategori_po'] == 'KPB'){
						$this->order_parts_tracking->kurang_qty_pack($purchase_order['po_id'], $purchase_order['id_part'], $purchase_order['selisih'], $purchase_order['id_tipe_kendaraan']);
					}else{
						$this->order_parts_tracking->kurang_qty_pack($purchase_order['po_id'], $purchase_order['id_part'], $purchase_order['selisih']);
					}
					$supply_untuk_dipecah -= $purchase_order['selisih'];
				}else if($purchase_order['selisih'] >= $supply_untuk_dipecah){
					if($data['kategori_po'] == 'KPB'){
						$this->order_parts_tracking->kurang_qty_pack($purchase_order['po_id'], $purchase_order['id_part'], $supply_untuk_dipecah, $purchase_order['id_tipe_kendaraan']);
					}else{
						$this->order_parts_tracking->kurang_qty_pack($purchase_order['po_id'], $purchase_order['id_part'], $supply_untuk_dipecah);
					}
					break;
				}

				if($supply_untuk_dipecah == 0) break;
			}
		}
		$this->db->trans_complete();

		if($this->db->trans_status()){
			send_json([
				'success' => 1,
				'message' => 'Data berhasil dihapus',
			]);
		}else{
			send_json([
				'success' => 0,
				'message' => 'Data tidak berhasil dihapus'
			], 422);
		}
	}

	public function add_part_by_scanner(){
		$total_part_telah_discan = $this->db
        ->select('sum(qty_scan)')
        ->from('tr_h3_md_scan_picking_list_parts')
        ->where('id_part_int = plp.id_part_int')
        ->where('id_lokasi_rak = plp.id_lokasi_rak')
        ->where('id_picking_list = plp.id_picking_list')
        ->get_compiled_select();
		
		$scan_part = $this->db
		->select('plp.id_part')
		->select('p.nama_part')
		->select('plp.serial_number')
		->select('plp.qty_supply as qty_do')
		->select('plp.qty_disiapkan as qty_picking')
		->select('plp.id_lokasi_rak')
		->select("ifnull( ({$total_part_telah_discan}), 0) as qty_sudah_scan")
        ->select("( plp.qty_disiapkan - ifnull( ({$total_part_telah_discan}), 0) ) as qty_belum_scan")
        ->select("1 as qty_scan")
		->from('tr_h3_md_picking_list_parts as plp')
		->join('ms_part as p', 'p.id_part_int = plp.id_part_int')
		->where('plp.id_part', $this->input->get('id_part'))
		->where('plp.id_picking_list', $this->input->get('id_picking_list'))
		->where("( plp.qty_disiapkan - ifnull( ({$total_part_telah_discan}), 0) ) > 0")
		->order_by('plp.qty_disiapkan', 'asc')
		->get()->row();

		send_json($scan_part);
	}

	public function detail(){
		$data['mode']    = 'detail';
		$data['set']     = "form";

		$data['picking_list'] = $this->db
		->select('pl.id_picking_list')
		->select('date_format(pl.tanggal, "%d-%m-%Y") as tanggal_picking')
		->select('
			case
				when do.sudah_revisi = 1 then concat(do.id_do_sales_order, "-REV")
				else do.id_do_sales_order
			end as id_do_sales_order
		', false)
		->select('date_format(do.tanggal, "%d-%m-%Y") as tanggal_do')
		->select('k.nama_lengkap as nama_picker')
		->select('d.nama_dealer')
		->select('d.alamat')
		->select('so.kategori_po')
		->select('so.po_type')
		->select('pl.selesai_scan')
		->select('po_aksesoris.id_paket_bundling')
		->from('tr_h3_md_picking_list as pl')
		->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
		->join('tr_po_aksesoris as po_aksesoris', 'po_aksesoris.no_po_aksesoris = so.referensi_po_bundling', 'left')
		->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
		->join('ms_karyawan as k', 'k.id_karyawan = pl.id_picker')
		->where('pl.id_picking_list', $this->input->get('id_picking_list'))
		->limit(1)
		->get()->row();

		$data['scanned_parts'] = $this->db
		->select('splp.*')
		->select('p.nama_part')
		->from('tr_h3_md_scan_picking_list_parts as splp')
		->join('ms_part as p', 'p.id_part = splp.id_part')
		->where('splp.id_picking_list', $this->input->get('id_picking_list'))
		->get()->result();

		$this->template($data);
	}

	public function selesai_scan(){
		$id_picking_list = $this->input->get('id_picking_list');

		$this->db->trans_start();

		if($this->picking_list->selisih_scan($id_picking_list)){
			$this->picking_list->create_do_revisi_from_scan($id_picking_list);
		}
		
		$this->picking_list->update([
			'selesai_scan' => 1,
			'status' => 'Closed'
		], [
			'id_picking_list' => $this->input->get('id_picking_list')
		]);

		$this->picking_list->update([
			'end_scan' => date('Y-m-d H:i:s')
		], [
			'id_picking_list' => $this->input->get('id_picking_list'),
		]);

		$data = $this->db
		->select('d.nama_dealer')
		->select('do.id_do_sales_order')
		->from('tr_h3_md_picking_list as pl')
		->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
		->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
		->where('pl.id_picking_list', $this->input->get('id_picking_list'))
		->get()->row_array();

		$this->do_sales_order->update([
			'status' => 'Closed Scan'
		], [
			'id_do_sales_order' => $data['id_do_sales_order']
		]);
		
		$menu_kategori = $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'notif_do_selesai_scan_untuk_create_faktur')->get()->row_array();
		if($menu_kategori != null){
			$this->notifikasi->insert([
				'id_notif_kat' => $menu_kategori['id_notif_kat'],
				'judul' => $menu_kategori['nama_kategori'],
				'pesan' => "No. DO {$data['id_do_sales_order']} a.n {$data['nama_dealer']} telah selesai di scan. Segera Create Faktur.",
				'link' => "{$menu_kategori['link']}/detail?id={$data['id_do_sales_order']}",
				'show_popup' => $menu_kategori['popup'],
			]);
		}
		$this->db->trans_complete();

		$picking_list = (array) $this->picking_list->find($this->input->get('id_picking_list'), 'id_picking_list');
		if ($this->db->trans_status() AND $picking_list != null) {
			$this->session->set_flashdata('pesan', 'Berhasil menyelesaikan picking list.');
			$this->session->set_flashdata('tipe', 'info');

			send_json([
				'payload' => $picking_list,
				'redirect_url' => base_url(sprintf('h3/h3_md_scan_picking_list/detail?id_picking_list=%s', $picking_list['id_picking_list']))
			]);
		} else {
			$message = sprintf('Tidak berhasil menyelesaikan scan picking list %s', $this->input->get('id_picking_list'));
			send_json([
				'message' => $message,
			], 422);
		}
	}

	public function parts_belum_scan(){
		$total_part_telah_discan = $this->db
        ->select('sum(qty_scan)')
        ->from('tr_h3_md_scan_picking_list_parts')
        ->where('id_part_int = plp.id_part_int')
        ->where('id_lokasi_rak = plp.id_lokasi_rak')
        ->where('id_picking_list = plp.id_picking_list')
        ->get_compiled_select();

        $this->db
        ->select('plp.id_part')
        ->select('plp.id_lokasi_rak')
        ->select('p.nama_part')
        ->select('plp.qty_supply as qty_do')
        ->select('plp.qty_disiapkan as qty_picking')
        ->select("ifnull( ({$total_part_telah_discan}), 0) as qty_sudah_scan")
        ->select("( plp.qty_disiapkan - ifnull( ({$total_part_telah_discan}), 0) ) as qty_belum_scan")
        ->select("( plp.qty_disiapkan - ifnull( ({$total_part_telah_discan}), 0) ) as qty_scan")
        ->from('tr_h3_md_picking_list_parts as plp')
        ->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = plp.id_picking_list')
        ->join('ms_part as p', 'p.id_part_int = plp.id_part_int')
		->where('plp.id_picking_list', $this->input->get('id_picking_list'))
		->having('qty_belum_scan > 0')
		;
		
		send_json($this->db->get()->result_array());
	}

	public function terdapat_selisih($parts){
		foreach ($parts as $part) {
			if($part['qty_do'] != $part['qty_revisi']) return true;
		}
		return false;
	}

	public function generate_do_revisi($id_picking_list, $parts){
		$data = $this->input->post(['id_do_sales_order', 'total']);

		$delivery_order = $this->db
		->from('tr_h3_md_do_sales_order')
		->where('id_do_sales_order', $this->input->post('id_do_sales_order'))
		->limit(1)
		->get()->row_array();

		if($delivery_order == null) throw new Exception('Delivery order tidak ditemukan');

		$data['source'] = 'validasi_picking_list';
		$data['id_do_sales_order_int'] = $delivery_order['id'];
		$this->do_revisi->insert($data);
		$id_revisi = $this->db->insert_id();
		$items = $this->getOnly([
			'id_part', 'qty_disiapkan', 'qty_do'
		], $this->input->post('parts'), [
			'id_revisi' => $id_revisi
		]);
		$items = array_map(function($data){
			$data['qty_revisi'] = $data['qty_disiapkan'];
			unset($data['qty_disiapkan']);
			return $data;
		}, $items);
		$this->do_revisi_item->insert_batch($items);
	}

	public function get_paket_bundling(){
		$qty_scan = $this->db
		->select('SUM(splp.qty_scan) as qty_scan')
		->from('tr_h3_md_scan_picking_list_parts as splp')
		->where('splp.id_picking_list', $this->input->get('id_picking_list'))
		->where('splp.id_part = pbd.id_part', null, false)
		->get_compiled_select();

		$data = $this->db
		->select('pbd.id_part')
		->select('pbd.qty_part')
		->select("(IFNULL(({$qty_scan}), 0) / pbd.qty_part) as kelipatan")
		->from('ms_paket_bundling_detail as pbd')
		->where('pbd.id_paket_bundling', $this->input->get('id_paket_bundling'))
		->get()->result_array();

		send_json($data);
	}
}
