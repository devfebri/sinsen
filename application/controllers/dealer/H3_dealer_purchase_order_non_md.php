<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_dealer_purchase_order_non_md extends Honda_Controller {
	public $tables = "tr_h3_dealer_purchase_order";	
	public $folder = "dealer";
	public $page   = "h3_dealer_purchase_order_non_md";
	public $title  = "Purchase Order Non MD";

	public function __construct(){		
		parent::__construct();
		//---- cek session -------//		
		$name = $this->session->userdata('nama');
		if ($name=="")
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
		}

		//===== Load Database =====
		$this->load->database();
		$this->load->helper('url');
		//===== Load Model =====
		$this->load->model('m_admin');		
		$this->load->model('h3_dealer_purchase_order_model', 'purchase_order');		
		$this->load->model('h3_dealer_purchase_order_parts_model', 'purchase_order_parts');		
		$this->load->model('h3_dealer_shipping_list_model', 'shipping_list');		
		$this->load->model('h3_dealer_shipping_list_parts_model', 'shipping_list_parts');		
		$this->load->model('dealer_model', 'dealer');		
		$this->load->model('notifikasi_model', 'notifikasi');		
		$this->load->model('ms_part_model', 'ms_part');		
		$this->load->model('H3_md_ms_sim_part_model', 'sim_part');
		$this->load->model('h3_dealer_stock_model', 'dealer_stock');
        $this->load->model('h3_md_purchase_order_parts_model', 'purchase_parts');
	}

	public function index(){				
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']	= "index";

		$this->template($data);	
	}

	public function add(){
		$data['kode_md'] = 'E22';
		$data['isi']     = $this->page;		
		$data['title']   = $this->title;		
		$data['mode']    = 'insert';
		$data['set']     = "form";
		$data['part_group'] = $this->db->distinct()->select('kelompok_part')->from('ms_part')->get()->result();
		$data['dealer_terdekat'] = $this->dealer->dealer_terdekat();
		$data['dealer'] = $this->db->from('ms_dealer')->where('id_dealer', $this->m_admin->cari_dealer())->get()->row();

		$this->template($data);	
	}

	public function validate(){
        $this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('kategori_po', 'Kategori Purchase Order', 'required');
		$this->form_validation->set_rules('po_type', 'Jenis Purchase', 'required');
		$this->form_validation->set_rules('produk', 'Produk', 'required');

		if($this->input->post('po_type') == 'FIX'){
			$this->form_validation->set_rules('pesan_untuk_bulan', 'Pesan untuk Bulan', 'required|numeric|in_list[1,2,3,4,5,6,7,8,9,10,11,12]');
			$this->form_validation->set_rules('batas_waktu', 'Batas Waktu', 'required');
		}else if($this->input->post('po_type') == 'REG'){
			$this->form_validation->set_rules('batas_waktu', 'Batas Waktu', 'required');
		}else if($this->input->post('po_type') == 'URG'){
			$this->form_validation->set_rules('dokumen_nrfs_id', 'Referensi NRFS', 'required');
		}else if($this->input->post('po_type') == 'HLO'){
			$this->form_validation->set_rules('id_booking', 'Referensi Booking', 'required');
		}else if($this->input->post('mode') == 'insert' && $this->input->post('po_type') == 'OTHER'){
			$this->form_validation->set_rules('po_nmd', 'Main Dealer', 'required');
		}

		$errors = [];

		$parts = $this->input->get('parts');
		if(!count($parts) > 0){
			$errors = array_merge($errors, [
				'check_parts' => 'Harus mengisi minimal 1 part'
			]);
		}

        if (!$this->form_validation->run())
        {
            $data = array_merge($errors, $this->form_validation->error_array());
            send_json($data, 422);
        }
    }

	public function save(){
		// $this->validate();
	
		$purchaseOrderData = $this->input->post([
			'pesan_untuk_bulan', 'po_type', 'id_booking', 'order_to', 'dokumen_nrfs_id', 'batas_waktu', 'kategori_po',
			'target_pembelian', 'total_amount', 'ach', 'produk', 'id_salesman','po_nmd'
		]);

		$purchaseOrderData = $this->clean_data($purchaseOrderData);
		$purchaseOrderData = array_merge($purchaseOrderData, [
			'po_id' => $this->purchase_order->generatePONumber(),
			'tanggal_order' => date('Y-m-d'),
			'created_at' => date('Y-m-d H:i:s'),
			'status' => 'Draft'
		]);

		$purchaseOrderPartsData = $this->getOnly([
			'id_part_int', 'id_part', 'po_id', 'kuantitas','harga_saat_dibeli', 'tipe_diskon', 
			'diskon_value', 'eta_terlama', 'eta_tercepat', 'w1', 'w2', 'w3', 'w4', 'w5', 'w6',
			'avg_six_weeks', 'akumulasi_qty', 'akumulasi_persen', 'suggested_order', 'adjusted_order', 'stock', 'rank', 'status',
			'tipe_diskon_campaign', 'diskon_value_campaign', 'id_campaign_diskon', 'jenis_diskon_campaign', 'tot_harga_part', 'order_md', 'qty_in_transit', 'stock_days'
		], $this->input->post('parts'), [
			'po_id' => $purchaseOrderData['po_id']
		]);

		$this->db->trans_start();

		$this->purchase_order->insert($purchaseOrderData);
		$this->purchase_order_parts->insert_batch($purchaseOrderPartsData);

		$menu_kategori = $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'request_purchase_order')->get()->row();
		$this->notifikasi->insert([
			'id_notif_kat' => $menu_kategori->id_notif_kat,
			'judul' => $menu_kategori->nama_kategori,
			'pesan' => "Request Purchase Order baru dengan nomor {$purchaseOrderData['po_id']}",
			'link' => "{$menu_kategori->link}/detail?id={$purchaseOrderData['po_id']}",
			'id_dealer' => $this->m_admin->cari_dealer(),
			'show_popup' => $menu_kategori->popup,
		]);
		
		$this->db->trans_complete();

		$purchase_order = (array) $this->purchase_order->find($purchaseOrderData['po_id'], 'po_id');
		if ($this->db->trans_status() AND $purchase_order != null) {
			send_json([
				'message' => 'Berhasil menyimpan purchase order',
				'payload' => $purchase_order,
				'redirect_url' => base_url('dealer/h3_dealer_purchase_order_non_md/detail?id=' . $purchase_order['po_id']) 
			]);
		}else{
			log_message('debug', sprintf('Purchase order dealer %s tidak berhasil di perbarui', $purchaseOrderData['po_id']));
			send_json([
				'message' => 'Tidak berhasil menyimpan purchase order'
			], 422);
		}
	}

	public function detail(){				
		$data['isi']   = $this->page;		
		$data['title'] = 'Purchase Order';		
		$data['mode']  = 'detail';
		$data['set']   = "form";
		$data['part_group'] = $this->db->distinct()->select('kelompok_part')->from('ms_part')->get()->result();
		$data['dealer'] = $this->db->from('ms_dealer')->where('id_dealer', $this->m_admin->cari_dealer())->get()->row();
		$data['purchase_order'] = $this->db
		->select('po.*')
		->select('date_format(po.tanggal_order, "%b") as periode')
		->select('po.tanggal_order')
		->select('ifnull(po.tanggal_selesai, "-") as tanggal_selesai')
		->select('0 as total_amount_po')
		->from('tr_h3_dealer_purchase_order as po')
		->where('po.po_id', $this->input->get('id'))
		->get()->row();
		
		$data['parts'] = $this->db
			->select('pop.*')
			->select('p.id_part_int')
			->select('p.nama_part')
			->select('p.current')
			->select('p.import_lokal')
			->select('p.hoo_flag')
			->select('p.hoo_max')
			->from('tr_h3_dealer_purchase_order_parts as pop')
			->join('ms_part as p', 'p.id_part = pop.id_part')
			->where('pop.po_id', $this->input->get('id'))
			->get()->result_array();

		// $data['parts'] = array_map(function($row){
		// 	$row['order_md'] = $this->purchase_parts->qty_on_order_md($this->m_admin->cari_dealer(), $row['id_part']);
		// 	$row['qty_in_transit'] = $this->dealer_stock->qty_intransit_md($this->m_admin->cari_dealer(), $row['id_part']);
		// 	$row['sim_part'] = $this->sim_part->qty_sim_part($this->m_admin->cari_dealer(), $row['id_part_int']);
			
		// 	return $row;
		// }, $parts);

		$this->template($data);
	}

	public function edit(){
		$data['isi']    = $this->page;		
		$data['title']	= $this->title;															
		$data['set']	= "form";
		$data['mode']  = 'edit';
		$data['part_group'] = $this->db->distinct()->select('kelompok_part')->from('ms_part')->get()->result();
		$data['dealer'] = $this->db->from('ms_dealer')->where('id_dealer', $this->m_admin->cari_dealer())->get()->row();
		$data['purchase_order'] = $this->db
		->select('po.*')
		->select('date_format(po.tanggal_order, "%b") as periode')
		->select('po.tanggal_order')
		->select('ifnull(po.tanggal_selesai, "-") as tanggal_selesai')
		->select('0 as total_amount_po')
		->from('tr_h3_dealer_purchase_order as po')
		->where('po.po_id', $this->input->get('id'))
		->get()->row()
		;

		$data['parts'] = $this->db
		->select('pop.*')
		->select('p.nama_part')
		->select('p.id_part_int')
		->select('p.current')
		->select('p.import_lokal')
		->select('p.hoo_flag')
		->select('p.hoo_max')
		->from('tr_h3_dealer_purchase_order_parts as pop')
		->join('ms_part as p', 'p.id_part = pop.id_part')
		->where('pop.po_id', $this->input->get('id'))
		->get()->result_array();

		// $data['parts'] = array_map(function($row){
		// 	$row['order_md'] = $this->purchase_parts->qty_on_order_md($this->m_admin->cari_dealer(), $row['id_part']);
		// 	$row['qty_in_transit'] = $this->dealer_stock->qty_intransit_md($this->m_admin->cari_dealer(), $row['id_part']);
		// 	$row['sim_part'] = $this->sim_part->qty_sim_part($this->m_admin->cari_dealer(), $row['id_part_int']);
			
		// 	return $row;
		// }, $parts);
		$this->template($data);	
	}

	public function update(){
		// $this->validate();

		$purchaseOrderData = $this->input->post([
			'pesan_untuk_bulan', 'id_dealer', 'po_type', 'id_booking', 'order_to', 'dokumen_nrfs_id', 'batas_waktu',
			'target_pembelian', 'total_amount', 'ach', 'produk'
		]);
		$purchaseOrderData = $this->clean_data($purchaseOrderData);

		$purchaseOrderPartsData = $this->getOnly([
			'id_part_int', 'id_part', 'po_id', 'kuantitas', 'harga_saat_dibeli', 'tipe_diskon',
			'diskon_value', 'eta_terlama', 'eta_tercepat', 'w1', 'w2', 'w3', 'w4', 'w5', 'w6',
			'avg_six_weeks', 'akumulasi_qty', 'akumulasi_persen', 'suggested_order', 'adjusted_order', 'stock', 'rank', 'status',
			'tipe_diskon_campaign', 'diskon_value_campaign', 'tot_harga_part', 'order_md', 'qty_in_transit', 'stock_days'
		], $this->input->post('parts'), $this->input->post(['po_id']));


		$this->db->trans_start();
		$this->purchase_order->update($purchaseOrderData, $this->input->post(['po_id']));
		$this->purchase_order_parts->update_batch($purchaseOrderPartsData, $this->input->post(['po_id']));

		$this->db->trans_complete();

		$purchase_order = (array) $this->purchase_order->get($this->input->post(['po_id']), true);
		if ($this->db->trans_status() AND $purchase_order != null) {
			send_json([
				'message' => 'Berhasil memperbarui purchase order',
				'payload' => $purchase_order,
				'redirect_url' => base_url('dealer/h3_dealer_purchase_order_non_md/detail?id=' . $purchase_order['po_id']) 
			]);
		}else{
			log_message('debug', sprintf('Purchase order dealer %s tidak berhasil di perbarui', $this->input->post('po_id')));
			send_json([
				'message' => 'Tidak berhasil memperbarui purchase order'
			], 422);
		}
	}

	public function update_status(){
		$this->db->trans_start();
		$purchase_order = $this->purchase_order->get($this->input->post(['po_id']), true);

		if($this->input->post('status') == 'Approved'){
			$data = array_merge($this->input->post(['status']), [
				'approve_at' => date('Y-m-d H:i:s'),
				'approve_by' => $this->session->userdata('id_user')
			]);
			$this->purchase_order->update($data, $this->input->post(['po_id']));
			$menu_kategori = $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'approved_purchase_order')->get()->row();
			$this->notifikasi->insert([
				'id_notif_kat' => $menu_kategori->id_notif_kat,
				'judul' => $menu_kategori->nama_kategori,
				'pesan' => "Purchase Order {$purchase_order->po_id} telah disetujui oleh Branch Manager",
				'link' => "{$menu_kategori->link}/detail?id={$purchase_order->po_id}",
				'id_dealer' => $this->m_admin->cari_dealer(),
				'show_popup' => $menu_kategori->popup == 1,
			]);
		}else if($this->input->post('status') == 'Canceled'){
			$data = array_merge($this->input->post(['status']), [
				'cancel_at' => date('Y-m-d H:i:s'),
				'cancel_by' => $this->session->userdata('id_user')
			]);
			$this->purchase_order->update($data, $this->input->post(['po_id']));
			$menu_kategori = $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'canceled_purchase_order')->get()->row();
			$this->notifikasi->insert([
				'id_notif_kat' => $menu_kategori->id_notif_kat,
				'judul' => $menu_kategori->nama_kategori,
				'pesan' => "Purchase Order {$purchase_order->po_id} telah dicancel oleh Branch Manager",
				'link' => "{$menu_kategori->link}/detail?id={$purchase_order->po_id}",
				'id_dealer' => $this->m_admin->cari_dealer(),
				'show_popup' => $menu_kategori->popup == 1,
			]);
		}else if($this->input->post('status') == 'Submitted'){
			$data = array_merge($this->input->post(['status']), [
				'submit_at' => date('Y-m-d H:i:s'),
				'submit_by' => $this->session->userdata('id_user')
			]);
			$this->purchase_order->update($data, $this->input->post(['po_id']));

			$parts = $this->db
			->select('pop.po_id')
			->select('pop.po_id_int')
			->select('pop.id_part')
			->select('pop.id_part_int')
			->from('tr_h3_dealer_purchase_order_parts as pop')
			->where('pop.po_id', $this->input->post('po_id'))
			->get()->result_array();

			$this->load->model('H3_dealer_order_parts_tracking_model', 'order_parts_tracking');
			foreach ($parts as $part) {
				$order_part_tracking = $this->order_parts_tracking->get($part, true);

				if($order_part_tracking === null){
					$this->order_parts_tracking->insert($part);
				}
			}
		}else if($this->input->post('status') == 'Submit & Approve Revisi'){
			$data = array_merge($this->input->post(['status']), [
				'submit_at' => date('Y-m-d H:i:s'),
				'submit_by' => $this->session->userdata('id_user')
			]);
			$this->purchase_order->update($data, $this->input->post(['po_id']));

			$parts = $this->db
			->select('pop.po_id')
			->select('pop.po_id_int')
			->select('pop.id_part')
			->select('pop.id_part_int')
			->from('tr_h3_dealer_purchase_order_parts as pop')
			->where('pop.po_id', $this->input->post('po_id'))
			->get()->result_array();

			$this->load->model('H3_dealer_order_parts_tracking_model', 'order_parts_tracking');
			foreach ($parts as $part) {
				$order_part_tracking = $this->order_parts_tracking->get($part, true);

				if($order_part_tracking === null){
					$this->order_parts_tracking->insert($part);
				}

				//Check data di tabel md_pemenuhan_po_dealer
				$check_tabel_pemenuhan_po = $this->db->select('id_part_int')
													 ->from('tr_h3_md_pemenuhan_po_dari_dealer')
													 ->where('po_id',$this->input->post('po_id'))
													 ->where('id_part_int',$part['id_part_int'])
													 ->get()->row_array();

				if($check_tabel_pemenuhan_po['id_part_int'] == NULL){
					$insert_data = array(
						'po_id' => $this->input->post('po_id'),
						'po_id_int' => $part['po_id_int'],
						'id_part' => $part['id_part'],
						'id_part_int' => $part['id_part_int']
					);
					$this->db->insert('tr_h3_md_pemenuhan_po_dari_dealer', $insert_data);
				}
			}
		}

		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			send_json($purchase_order);
		}else{
			$this->output->set_status_header(500);
		}
	}

	public function reject(){
		$this->db->trans_start();
		$data = array_merge($this->input->post(['status', 'alasan_reject']), [
			'reject_at' => date('Y-m-d H:i:s', time()),
			'reject_by' => $this->session->userdata('id_user')
		]);
		$this->purchase_order->update($data, $this->input->post(['po_id']));
		$purchase_order = $this->purchase_order->get($this->input->post(['po_id']), true);

		$menu = $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'reject_purchase_order')->get()->row();
		$this->notifikasi->insert([
            'id_notif_kat' => $menu->id_notif_kat,
            'judul' => $menu->nama_kategori,
            'pesan' => "Purchase Order {$purchase_order->po_id} telah direject oleh Branch Manager",
            'link' => "{$menu->link}/detail?id={$purchase_order->po_id}",
            'id_dealer' => $this->m_admin->cari_dealer(),
            'show_popup' => $menu->popup == 1,
        ]);
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			send_json($purchase_order);
		}else{
			$this->output->set_status_header(500);
		}
	}

	public function reopen_purchase(){
		$this->db->trans_start();
		$purchase_order = $this->purchase_order->get($this->input->post(['po_id']), true);
		if($purchase_order->po_type == 'HLO'){
			$this->purchase_order->update([
				'status' => 'Approved'
			], $this->input->post(['po_id']));
		}else{
			$this->purchase_order->update([
				'status' => 'Draft'
			], $this->input->post(['po_id']));
		}

		$this->notifikasi->insert([
            'id_notif_kat' => $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'reopen_purchase_order')->get()->row()->id_notif_kat,
            'judul' => 'Purchase Order Reopen',
            'pesan' => "Purchase Order {$purchase_order->po_id} telah dilakukan Re-open",
            'link' => "dealer/h3_dealer_purchase_order/detail?id={$purchase_order->po_id}",
            'id_dealer' => $this->m_admin->cari_dealer(),
            'show_popup' => true,
        ]);
		$this->db->trans_complete();

		if ($this->db->trans_status()) {
			send_json($purchase_order);
		}else{
			$this->output->set_status_header(500);
		}
	}

	public function cetak(){
		$this->load->library('mpdf_l');
		$mpdf                           = $this->mpdf_l->load();
		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
		$mpdf->charset_in               = 'UTF-8';
		$mpdf->autoLangToFont           = true;

		$data['purchase'] = $this->db
		->select('po.po_id')
		->select('date_format(po.tanggal_order, "%d-%m-%Y") as tanggal_order')
		->select('d.nama_dealer')
		->select('d.alamat')
		->select('d.logo as logo_dealer')
		->select('c.nama_customer')
		->select('c.no_polisi')
		->select('
			case
				when wo.id_work_order is not null then wo.id_work_order
				else "-"
			end as id_work_order
		', false)
		->select('
			case
				when po.order_to = 0 then "Main Dealer PT.Sinar Sentosa Primatama"
				else supplier.nama_dealer
			end as supplier_name
		', false)
		->from('tr_h3_dealer_purchase_order as po')
		->join('tr_h3_dealer_request_document as rd', 'rd.id_booking = po.id_booking', 'left')
		->join('tr_h2_wo_dealer as wo', 'wo.id_sa_form = rd.id_sa_form', 'left')
		->join('ms_customer_h23 as c', 'c.id_customer = rd.id_customer', 'left')
		->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
		->join('ms_dealer as supplier', 'supplier.id_dealer = po.order_to', 'left')
		->where('po.po_id', $this->input->get('id'))
		->limit(1)
		->get()->row();

		$data['parts'] = $this->db
		->select('pop.id_part')
		->select('p.nama_part')
		->select('p.current')
        ->select('p.import_lokal')
        ->select('p.hoo_flag')
        ->select('p.hoo_max')
		->select('pop.kuantitas')
		->select('pop.harga_saat_dibeli')
		->select('"-" as diskon')
		->select('(pop.harga_saat_dibeli * pop.kuantitas) as total')
		->select('"" as keterangan')
		->from('tr_h3_dealer_purchase_order_parts as pop')
		->join('ms_part as p', 'p.id_part = pop.id_part')
		->where('pop.po_id', $this->input->get('id'))
		->get()->result();

		$html = $this->load->view('dealer/h3_dealer_cetak_po', $data, true);
		
		// render the view into HTML
		$mpdf->WriteHTML($html);
		// write the HTML into the mpdf
		$output = "cetak_po.pdf";
		$mpdf->Output($output, 'I');
	}


}