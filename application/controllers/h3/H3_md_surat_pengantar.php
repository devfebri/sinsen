<?php
defined('BASEPATH') or exit('No direct script access allowed');

class H3_md_surat_pengantar extends Honda_Controller
{
	protected $folder = "h3";
	protected $page   = "h3_md_surat_pengantar";
	protected $title  = "Surat Pengantar";

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

		$this->load->model('h3_md_surat_pengantar_model', 'surat_pengantar');
		$this->load->model('h3_md_surat_pengantar_items_model', 'surat_pengantar_items');
		$this->load->model('dealer_model', 'dealer');
		$this->load->model('vendor_model', 'vendor');
		$this->load->model('h3_md_do_sales_order_model', 'do_sales_order');
		$this->load->model('h3_md_picking_list_model', 'picking_list');
	}

	public function index()
	{
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$data['surat_pengantar'] = $this->db->select('sp.*')
			->select('date_format(sp.tanggal, "%d-%m-%Y") as tanggal')
			->select('md.nama_dealer as nama_customer')
			->select('mv.vendor_name as nama_ekspedisi')
			->from('tr_h3_md_surat_pengantar as sp')
			->join('ms_dealer as md', 'md.id_dealer = sp.id_dealer')
			->join('ms_vendor as mv', 'mv.id_vendor = sp.id_ekspedisi')
			->get()->result();

		$this->template($data);
	}

	public function add()
	{
		$data['mode']    = 'insert';
		$data['set']     = "form";

		$this->template($data);
	}

	public function get_packing_sheets()
	{
		$packing_sheet_sudah_ada_surat_pengantar = $this->db
			->select('spi.id_packing_sheet')
			->from('tr_h3_md_surat_pengantar_items as spi')
			->get_compiled_select();

		$jumlah_koli = $this->db
			->select('count( distinct(splp.no_dus) )')
			->from('tr_h3_md_scan_picking_list_parts as splp')
			->where('splp.id_picking_list = pl.id_picking_list')
			->get_compiled_select();

		$packing_sheets = $this->db
			->select('date_format(ps.tgl_packing_sheet, "%d/%m/%Y") as tgl_packing_sheet')
			->select('ps.id_packing_sheet')
			->select('d.nama_dealer')
			->select('so.id_sales_order')
			->select('so.po_type')
			->select("({$jumlah_koli}) as jumlah_koli")
			->select('1 as checked')
			->from('tr_h3_md_packing_sheet as ps')
			->join('tr_h3_md_picking_list as pl', ' pl.id_picking_list = ps.id_picking_list')
			->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
			->join('ms_dealer as d', 'd.id_dealer = pl.id_dealer')
			->where('pl.id_dealer', $this->input->get('id_dealer'))
			->where("ps.id_packing_sheet not in ({$packing_sheet_sudah_ada_surat_pengantar})")
			->where('left(ps.created_at,10) >', '2023-10-01')
			->get()->result_array();

		send_json($packing_sheets);
	}

	public function save()
	{
		$this->db->trans_start();
		$this->validate();
		$surat_pengantar = array_merge($this->input->post(['id_dealer', 'id_ekspedisi', 'no_plat']), [
			'id_surat_pengantar' => $this->surat_pengantar->generateID($this->input->post('id_dealer')),
			'tanggal' => date('Y-m-d', time())
		]);

		$packing_sheets = $this->getOnly(['id_packing_sheet'], $this->input->post('packing_sheets'), [
			'id_surat_pengantar' => $surat_pengantar['id_surat_pengantar']
		]);

		$surat_pengantar = $this->surat_pengantar->insert($surat_pengantar);
		foreach ($this->input->post('packing_sheets') as $packing_sheet) {
			$item = [];
			$item['id_packing_sheet'] = $packing_sheet['id_packing_sheet'];
			$item['id_surat_pengantar'] = $surat_pengantar['id_surat_pengantar'];
			$item['id_surat_pengantar_int'] = $surat_pengantar['id'];

			$check_data = $this->db->select('id')
				->from('tr_h3_md_surat_pengantar_items as spi')
				->where('id_packing_sheet',$packing_sheet['id_packing_sheet'])
				->get()->row_array();

			if($check_data['id'] == '' || $check_data['id'] == NULL){
				$data = $this->db
					->select('pl.id_picking_list')
					->select('pl.id as id_picking_list_int')
					->select('do.id_do_sales_order')
					->select('ps.id as id_packing_sheet_int')
					->select('so.po_type')
					->from('tr_h3_md_packing_sheet as ps')
					->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
					->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
					->join('tr_h3_md_sales_order as so', 'do.id_sales_order_int = so.id')
					->where('ps.id_packing_sheet', $packing_sheet['id_packing_sheet'])
					->get()->row_array();
				$item['id_packing_sheet_int'] = $data['id_packing_sheet_int'];

				$this->do_sales_order->update([
					'status' => 'Shipping List'
				], [
					'id_do_sales_order' => $data['id_do_sales_order'],
					'status' => 'Packing Sheet'
				]);

				$this->picking_list->update([
					'status' => 'Shipping List'
				], [
					'id_picking_list' => $data['id_picking_list'],
					'status' => 'Packing Sheet'
				]);

				$this->surat_pengantar_items->insert($item);

				
				// Update Tgl Shipping List di Tabel History Hotline & Order Part Tracking
				if($data['po_type'] == 'HLO'){
					$data_part_pl = $this->db->select('plp.id_part')
						->select('so.id_ref')
						->from('tr_h3_md_picking_list pl')
						->join('tr_h3_md_picking_list_parts plp','plp.id_picking_list_int=pl.id')
						->join('tr_h3_md_do_sales_order dso','dso.id=pl.id_ref_int')
						->join('tr_h3_md_sales_order so','so.id = dso.id_sales_order_int')
						->where('pl.id_picking_list',$data['id_picking_list'])
						->get()
						->result_array();

					foreach($data_part_pl as $data_pl){
					//Check PO dan Part di Tabel History Hotline
						$check_po_history = $this->db->select('id_purchase_order')
							->from('tr_h3_md_history_estimasi_waktu_hotline')
							->where('po_id', $data_pl['id_ref'])
							->where('id_part', $data_pl['id_part'])
							->get()
							->num_rows();

						if($check_po_history > 0){
							$this->db->set('tgl_shipping_list_md',  date('Y-m-d H:i:s', time()));
							$this->db->where('po_id', $data_pl['id_ref']);
							$this->db->where('id_part', $data_pl['id_part']);
							$this->db->update('tr_h3_md_history_estimasi_waktu_hotline');
						}
						
					//Update Data di Order Part Tracking
						$this->db->set('tgl_shipping_list_md',  date('Y-m-d H:i:s', time()));
						$this->db->where('po_id', $data_pl['id_ref']);
						$this->db->where('id_part', $data_pl['id_part']);
						$this->db->update('tr_h3_dealer_order_parts_tracking');
					}
				}

				$data_part_plp = $this->db->select('plp.id_part')
						->select('plp.id_part_int')
						->select('plp.serial_number')
						->from('tr_h3_md_picking_list pl')
						->join('tr_h3_md_picking_list_parts plp','plp.id_picking_list_int=pl.id')
						->where('pl.id',$data['id_picking_list_int'])
						->get()
						->result_array();

				foreach($data_part_plp as $data_pl){
					if($data_pl['serial_number'] != '' || $data_pl['serial_number'] != null){
						// Cek type ACC 
						$kelompok_part = $this->db->select('kelompok_part')
							->from('ms_part')
							->where('id_part_int', $data_pl['id_part_int'])
							->get()->row_array();
		
						$accType ='';
						if($kelompok_part['kelompok_part']=='EVBT'){
							$accType ='B';
						}elseif($kelompok_part['kelompok_part']=='EVCH'){
							$accType ='C';
						}

						// Cek created at dan created by surat pengantar
						$check_sl = $this->db->select('created_at')
								->select('created_by')
								->select('id_dealer')
								->from('tr_h3_md_surat_pengantar')
								->where('id',$surat_pengantar['id'])
								->get()->row_array();	

						//Insert no shipping list di table tr_h3_serial_ev_tracking
						$this->db->set('id_surat_pengantar_md_int', $surat_pengantar['id'])
								->set('id_surat_pengantar_md',  $surat_pengantar['id_surat_pengantar'])
								->set('created_at_surat_pengantar', $check_sl['created_at'])
								->set('created_by_surat_pengantar', $check_sl['created_by'])
								->set('id_dealer', $check_sl['id_dealer'])
								->set('accStatus', 3)
								->where('id_part_int',  $data_pl['id_part_int'])	
								->where('serial_number', $data_pl['serial_number'])
								->update('tr_h3_serial_ev_tracking');


						//Ambil data MD Receive Date 
						$check_md_penerimaan = $this->db->select('created_at')
								->select('created_by')
								->from('tr_h3_md_penerimaan_barang_items')
								->where('serial_number',$data_pl['serial_number'])
								->where('id_part_int',$data_pl['id_part_int'])
								->get()->row_array();	

						//Kode dealer 
						$check_kode_dealer = $this->db->select('kode_dealer_ahm')
							->from('ms_dealer')
							->where('id_dealer',$check_sl['id_dealer'])
							->get()->row_array();	

						//Insert data di table tr_status_ev_acc 
						$data_ev = array(
							'serialNo' =>  $data_pl['serial_number'],
							'accType' => $accType,
							'accStatus' => 3,
							'mdReceiveDate' =>  $check_md_penerimaan['created_at'],
							'mdSLDate' => $check_sl['created_at'],
							'mdSLNo' =>  $surat_pengantar['id_surat_pengantar'],
							'dealerCode' => $check_kode_dealer['kode_dealer_ahm'],
							'accStatus_2_processed_at' =>  $check_md_penerimaan['created_at'],
							'accStatus_2_processed_by_user' =>  $check_md_penerimaan['created_by'],
							'accStatus_3_processed_at' =>  $check_sl['created_at'],
							'accStatus_3_processed_by_user' => $check_sl['created_by'],
							'api_from' =>2,
							'last_updated' => date('Y-m-d H:i:s', time())
						);
						
						$this->db->insert('tr_status_ev_acc', $data_ev);

						//Insert data di table ev_log_send_api_3
						$data_ev_to_ahm = array(
							'serialNo' =>  $data_pl['serial_number'],
							'accStatus' => 3,
							'created_at' =>  $check_sl['created_at'],
							'status_scan' => 1, 
						);
						
						$this->db->insert('ev_log_send_api_3', $data_ev_to_ahm);
					}
				}

			}else{
				$this->db->trans_rollback();
			}	
			
		}
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			$surat_pengantar = $this->surat_pengantar->find($surat_pengantar['id_surat_pengantar'], 'id_surat_pengantar');
			send_json($surat_pengantar);
		} else {
			$this->db->trans_rollback();
			$this->output->set_status_header(500);
		}
	}

	public function detail()
	{
		$data['mode']    = 'detail';
		$data['set']     = "form";
		$data['surat_pengantar'] = $this->db
			->select('sp.id_surat_pengantar')
			->select('date_format(sp.tanggal, "%d/%m/%Y") as tanggal')
			->select('d.id_dealer')
			->select('d.kode_dealer_md')
			->select('d.nama_dealer')
			->select('sp.id_ekspedisi')
			->select('e.nama_ekspedisi')
			->select('sp.no_plat')
			->select('sp.close_sl')
			->select('sp.cetak_sl_ke')
			->from('tr_h3_md_surat_pengantar as sp')
			->join('ms_dealer as d', 'd.id_dealer = sp.id_dealer')
			->join('ms_h3_md_ekspedisi as e', 'e.id = sp.id_ekspedisi')
			->where('sp.id_surat_pengantar', $this->input->get('id_surat_pengantar'))
			->get()->row();

		$jumlah_koli = $this->db
			->select('count( distinct(splp.no_dus) )')
			->from('tr_h3_md_scan_picking_list_parts as splp')
			->where('splp.id_picking_list = pl.id_picking_list')
			->get_compiled_select();

		$data['packing_sheets'] = $this->db
			->select('date_format(ps.tgl_packing_sheet, "%d/%m/%Y") as tgl_packing_sheet')
			->select('ps.id_packing_sheet')
			->select('d.nama_dealer')
			->select('so.id_sales_order')
			->select('so.po_type')
			->select("({$jumlah_koli}) as jumlah_koli")
			->from('tr_h3_md_surat_pengantar_items as spi')
			// ->join('tr_h3_md_packing_sheet as ps', 'ps.id_packing_sheet = spi.id_packing_sheet')
			->join('tr_h3_md_packing_sheet as ps', 'ps.id = spi.id_packing_sheet_int')
			// ->join('tr_h3_md_picking_list as pl', ' pl.id_picking_list = ps.id_picking_list')
			->join('tr_h3_md_picking_list as pl', ' pl.id = ps.id_picking_list_int')
			->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
			->join('ms_dealer as d', 'd.id_dealer = pl.id_dealer')
			->where('spi.id_surat_pengantar', $this->input->get('id_surat_pengantar'))
			->get()->result_array();

		$this->template($data);
	}

	public function cetak()
	{
		$this->db->trans_start();
		$data = [];
		$data['surat_pengantar'] = $this->db
			->select('sp.id_surat_pengantar')
			->select('date_format(sp.tanggal, "%d/%m/%Y") as tanggal')
			->select('d.id_dealer')
			->select('d.kode_dealer_md')
			->select('d.nama_dealer')
			->select('d.alamat')
			->select('d.no_telp')
			->select('d.pemilik')
			->select('sp.id_ekspedisi')
			->select('e.nama_ekspedisi')
			->select('sp.no_plat')
			->select('sp.shipping_list_printed')
			->select('sp.cetak_sl_ke')
			->from('tr_h3_md_surat_pengantar as sp')
			->join('ms_dealer as d', 'd.id_dealer = sp.id_dealer')
			->join('ms_h3_md_ekspedisi as e', 'e.id = sp.id_ekspedisi')
			->where('sp.id_surat_pengantar', $this->input->get('id_surat_pengantar'))
			->get()->row_array();

		$jumlah_koli = $this->db
			->select('count( distinct(splp.no_dus))')
			->from('tr_h3_md_scan_picking_list_parts as splp')
			->where('splp.id_picking_list = pl.id_picking_list')
			->get_compiled_select();

		$parts = $this->db
			->select('count( distinct(splp.no_dus) )')
			->from('tr_h3_md_scan_picking_list_parts as splp')
			->where('splp.produk', 'Parts')
			->where('splp.id_picking_list = pl.id_picking_list')
			->get_compiled_select();

		$ban = $this->db
			->select('count( distinct(splp.no_dus) )')
			->from('tr_h3_md_scan_picking_list_parts as splp')
			->where('splp.produk', 'Ban')
			->where('splp.id_picking_list = pl.id_picking_list')
			->get_compiled_select();

		$oil = $this->db
			->select('count( distinct(splp.no_dus) )')
			->from('tr_h3_md_scan_picking_list_parts as splp')
			->where('splp.produk', 'Oil')
			->where('splp.id_picking_list = pl.id_picking_list')
			->get_compiled_select();

		$data['packing_sheets'] = $this->db
			->select('ps.no_faktur')
			->select('date_format(ps.tgl_packing_sheet, "%d/%m/%Y") as tgl_packing_sheet')
			->select('ps.id')
			->select('ps.id_packing_sheet')
			->select('d.nama_dealer')
			->select('so.id_sales_order')
			->select('so.po_type')
			->select("({$jumlah_koli}) as jumlah_koli")
			->select("({$parts}) as parts")
			->select("({$oil}) as oil")
			->select("({$ban}) as ban")
			->from('tr_h3_md_surat_pengantar_items as spi')
			->join('tr_h3_md_packing_sheet as ps', 'ps.id_packing_sheet = spi.id_packing_sheet')
			->join('tr_h3_md_picking_list as pl', ' pl.id_picking_list = ps.id_picking_list')
			->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
			->join('ms_dealer as d', 'd.id_dealer = pl.id_dealer')
			->where('spi.id_surat_pengantar', $this->input->get('id_surat_pengantar'))
			->get()->result_array();

		if ($data['surat_pengantar']['shipping_list_printed'] == 0) {
			foreach ($data['packing_sheets'] as $row) {
				$this->update_order_parts_tracking($row['id']);
			}

			$this->update_status_print_surat_pengantar($this->input->get('id_surat_pengantar'));
		}

		//Cek data cetakan keberapa  
		$this->db->set('cetak_sl_ke', $data['surat_pengantar']['cetak_sl_ke']+1, FALSE);
		$this->db->where('id_surat_pengantar', $this->input->get('id_surat_pengantar'));
		$this->db->update('tr_h3_md_surat_pengantar');

		$this->db->trans_complete();

		// $this->load->library('mpdf_l');
		require_once APPPATH . 'third_party/mpdf/mpdf.php';
		// Require composer autoload
		$mpdf = new Mpdf('c', 'A5');
		$mpdf->AddPage('L');
		// Write some HTML code:
		$html = $this->load->view('h3/h3_md_cetak_surat_pengantar', $data, true);
		$mpdf->WriteHTML($html);

		// Output a PDF file directly to the browser
		$mpdf->Output("Surat Pengantar.pdf", "I");
	}

	public function close(){
		$this->db->trans_start();
		$this->surat_pengantar->update([
			'close_sl' => '1',
			'close_sl_at' => date('Y-m-d H:i:s', time()),
			'close_sl_by' => $this->session->userdata('id_user')
		], $this->input->get(['id_surat_pengantar']));
		$this->db->trans_complete();

		if($this->db->trans_status()) {
			$this->session->set_flashdata('pesan', 'Surat Pengantar berhasil diclose');
			$this->session->set_flashdata('tipe', 'info');
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/$this->page/detail?id_surat_pengantar={$this->input->get('id_surat_pengantar')}'>";
		}else{
			$this->session->set_flashdata('pesan', 'Surat Pengantar tidak berhasil diclose.');
			$this->session->set_flashdata('tipe', 'danger');
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."h3/$this->page/detail?id_surat_pengantar={$this->input->get('id_surat_pengantar')}";
		}
	}

	public function update_status_print_surat_pengantar($id_surat_pengantar)
	{
		$this->db
			->set('sp.shipping_list_printed', 1)
			->where('sp.id_surat_pengantar', $id_surat_pengantar)
			->where('sp.shipping_list_printed', 0)
			->update('tr_h3_md_surat_pengantar as sp');
	}

	public function update_order_parts_tracking($id)
	{
		$this->load->model('H3_dealer_order_parts_tracking_model', 'order_parts_tracking');

		$header = $this->db
			->select('so.kategori_po')
			->select('so.id_ref')
			->select('so.id_rekap_purchase_order_dealer')
			->select('pl.id_picking_list')
			->from('tr_h3_md_packing_sheet as ps')
			->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
			->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
			->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
			->where('ps.id', $id)
			->get()->row_array();

		$parts = $this->db
			->select('plp.id_part')
			->select('plp.qty_disiapkan')
			->select('plp.id_tipe_kendaraan')
			->from('tr_h3_md_picking_list_parts as plp')
			->where('plp.id_picking_list', $header['id_picking_list'])
			->get()->result_array();

		foreach ($parts as $part) {
			if ($header['kategori_po'] == 'KPB') {
				$this->order_parts_tracking->tambah_qty_ship($header['id_ref'], $part['id_part'], $part['qty_disiapkan'], $part['id_tipe_kendaraan']);
			} else {
				$this->order_parts_tracking->tambah_qty_ship($header['id_ref'], $part['id_part'], $part['qty_disiapkan']);
			}
			if ($header['id_rekap_purchase_order_dealer'] != null) {
				$jumlah_item = $this->db
					->select('SUM( pop.kuantitas - ppd.qty_supply) as jumlah_item', false)
					->from('tr_h3_dealer_purchase_order_parts as pop')
					->join('tr_h3_md_pemenuhan_po_dari_dealer as ppd', '(ppd.po_id = pop.po_id AND ppd.id_part = pop.id_part)')
					->where('pop.po_id = po.po_id', null, false)
					->get_compiled_select();

				$this->db
					->select('po.po_id')
					->select('pop.id_part')
					->select('(opt.qty_bill - opt.qty_ship) as selisih')
					->select("IFNULL(({$jumlah_item}), 0) as jumlah_item", false)
					->from('tr_h3_md_rekap_purchase_order_dealer_item as ri')
					->join('tr_h3_dealer_purchase_order as po', 'po.po_id = ri.id_referensi')
					->join('tr_h3_dealer_purchase_order_parts as pop', 'pop.po_id = ri.id_referensi')
					->join('tr_h3_dealer_order_parts_tracking as opt', '(opt.po_id = pop.po_id and opt.id_part = pop.id_part)')
					->where('ri.id_rekap', $header['id_rekap_purchase_order_dealer'])
					->where('pop.id_part', $part['id_part'])
					->order_by('jumlah_item', 'asc')
					->order_by('po.created_at', 'desc');

				if ($header['kategori_po'] == 'KPB') {
					$this->db->select('pop.id_tipe_kendaraan');
					$this->db->where('pop.id_tipe_kendaraan', $part['id_tipe_kendaraan']);
				}

				$purchase_orders = $this->db->get()->result_array();

				$supply_untuk_dipecah = $part['qty_disiapkan'];
				foreach ($purchase_orders as $purchase_order) {
					if ($purchase_order['selisih'] <= $supply_untuk_dipecah) {
						if ($header['kategori_po'] == 'KPB') {
							$this->order_parts_tracking->tambah_qty_ship($purchase_order['po_id'], $purchase_order['id_part'], $purchase_order['selisih'], $part['id_tipe_kendaraan']);
						} else {
							$this->order_parts_tracking->tambah_qty_ship($purchase_order['po_id'], $purchase_order['id_part'], $purchase_order['selisih']);
						}
						$supply_untuk_dipecah -= $purchase_order['selisih'];
					} else if ($purchase_order['selisih'] >= $supply_untuk_dipecah) {
						if ($header['kategori_po'] == 'KPB') {
							$this->order_parts_tracking->tambah_qty_ship($purchase_order['po_id'], $purchase_order['id_part'], $supply_untuk_dipecah, $part['id_tipe_kendaraan']);
						} else {
							$this->order_parts_tracking->tambah_qty_ship($purchase_order['po_id'], $purchase_order['id_part'], $supply_untuk_dipecah);
						}
						break;
					}

					if ($supply_untuk_dipecah == 0) break;
				}
			}
		}
	}

	public function validate()
	{
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('id_dealer', 'Customer', 'required');
		$this->form_validation->set_rules('id_ekspedisi', 'Ekspedisi', 'required');
		$this->form_validation->set_rules('no_plat', 'No Plat', 'required');

		if (!$this->form_validation->run()) {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
		}
	}

	public function verifyPassword() {
		$inputPassword = $this->input->post('password');
		$id = $this->input->post('id_surat_pengantar'); 

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
			send_json([
				'pesan' => 'Success',
				'id_surat_pengantar' => $id
			]);
		} else {
			send_json([
				'pesan' => 'Fail'
			]);
		}
	}
}
