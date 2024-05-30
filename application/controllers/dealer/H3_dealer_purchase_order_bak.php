<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_dealer_purchase_order extends Honda_Controller {
	public $tables = "tr_h3_dealer_purchase_order";	
	public $folder = "dealer";
	public $page   = "h3_dealer_purchase_order";
	public $title  = "Purchase Order";

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

	public function ambil_data_target_pembelian(){
		$produk = $this->input->get('produk');
		if($this->input->get('tanggal_order') != null){
			$time = strtotime($this->input->get('tanggal_order'));
		}else{
			$time = time();
		}
		$date = date('Y-m-d', $time);

		if($this->input->get('po_type') == 'FIX'){
			$pesan_untuk_bulan = sprintf("%02d", $this->input->get('pesan_untuk_bulan'));

			$date = date("Y-{$pesan_untuk_bulan}-d", $time);
			$time = strtotime($date);
		}

		$start_of_month = date('Y-m-01', $time);
		$end_of_month = new DateTime($start_of_month); 
		$end_of_month = $end_of_month->format('Y-m-t');

		$month = date('n', $time);
		$year = date('Y', $time);

		$total_amount_po = null;
		$target_salesman = null;
		$data = [];
		if($produk == 'Oil'){
			$target_salesman = $this->db
			->select('tso.total_amount')
			->select('ts.id_salesman')
			->from('ms_h3_md_target_salesman_oil as tso')
			->join('ms_h3_md_target_salesman as ts', 'ts.id = tso.id_target_salesman')
			->where('tso.id_dealer', $this->m_admin->cari_dealer())
			->where('ts.jenis_target_salesman', 'Oil')
			->group_start()
			->where('ts.start_date <=', $date)
			->where('ts.end_date >=', $date)
			->group_end()
			->order_by('ts.created_at', 'desc')
			->limit(1)
			->get()->row_array();

			$total_amount_po = $this->db
			->select('IFNULL(
				SUM(po.total_amount)
				,0
			) as total_amount_po', false)
			->from('tr_h3_dealer_purchase_order as po')
			->where('po.id_dealer', $this->m_admin->cari_dealer())
			->where('po.produk', 'Oil')
			->where("
				case
					when po.po_type = 'FIX' then po.pesan_untuk_bulan = '{$month}' AND date_format(po.tanggal_order, '%Y') = '{$year}'
					else po.tanggal_order between '{$start_of_month}' and '{$end_of_month}'
				end
			", null, false)
			->where_not_in('po.po_id', $this->input->get('exclude_po'))
			->group_start()
			->where('po.order_to', null)
			->or_where('po.order_to', 0)
			->group_end()
			->get()->row_array();

		}else if($produk == 'Parts'){
			$target_salesman = $this->db
			->select('tsp.target_part as total_amount')
			->select('ts.id_salesman')
			->from('ms_h3_md_target_salesman_parts as tsp')
			->join('ms_h3_md_target_salesman as ts', 'ts.id = tsp.id_target_salesman')
			->where('tsp.id_dealer', $this->m_admin->cari_dealer())
			->where('ts.jenis_target_salesman', 'Parts')
			->group_start()
			->where('ts.start_date <=', $date)
			->where('ts.end_date >=', $date)
			->group_end()
			->order_by('ts.created_at', 'desc')
			->limit(1)
			->get()->row_array();

			$total_amount_po = $this->db
			->select('IFNULL(
				SUM(po.total_amount)
				,0
			) as total_amount_po', false)
			->from('tr_h3_dealer_purchase_order as po')
			->where('po.id_dealer', $this->m_admin->cari_dealer())
			->where('po.produk', 'Parts')
			->where("
				case
					when po.po_type = 'FIX' then po.pesan_untuk_bulan = '{$month}' AND date_format(po.tanggal_order, '%Y') = '{$year}'
					else po.tanggal_order between '{$start_of_month}' and '{$end_of_month}'
				end
			", null, false)
			->where_not_in('po.po_id', $this->input->get('exclude_po'))
			->group_start()
			->where('po.order_to', null)
			->or_where('po.order_to', 0)
			->group_end()
			->get()->row_array();
		}
		else if($produk == 'Acc'){
			$target_salesman = $this->db
			->select('tsa.target_acc as total_amount')
			->select('ts.id_salesman')
			->from('ms_h3_md_target_salesman_acc as tsa')
			->join('ms_h3_md_target_salesman as ts', 'ts.id = tsa.id_target_salesman')
			->where('tsa.id_dealer', $this->m_admin->cari_dealer())
			->where('ts.jenis_target_salesman', 'Acc')
			->group_start()
			->where('ts.start_date <=', $date)
			->where('ts.end_date >=', $date)
			->group_end()
			->order_by('ts.created_at', 'desc')
			->limit(1)
			->get()->row_array();

			$total_amount_po = $this->db
			->select('IFNULL(
				SUM(po.total_amount)
				,0
			) as total_amount_po', false)
			->from('tr_h3_dealer_purchase_order as po')
			->where('po.id_dealer', $this->m_admin->cari_dealer())
			->where('po.produk', 'Acc')
			->where("
				case
					when po.po_type = 'FIX' then po.pesan_untuk_bulan = '{$month}' AND date_format(po.tanggal_order, '%Y') = '{$year}'
					else po.tanggal_order between '{$start_of_month}' and '{$end_of_month}'
				end
			", null, false)
			->where_not_in('po.po_id', $this->input->get('exclude_po'))
			->group_start()
			->where('po.order_to', null)
			->or_where('po.order_to', 0)
			->group_end()
			->get()->row_array();
		}

		if($target_salesman != null){
			$data['total_amount'] = $target_salesman['total_amount'];
			$data['id_salesman'] = $target_salesman['id_salesman'];
		}else{
			$data['total_amount'] = 0;
			$data['id_salesman'] = null;
		}

		if($total_amount_po != null){
			$data['total_amount_po'] = $total_amount_po['total_amount_po'];
		}else{
			$data['total_amount_po'] = 0;
		}

		send_json($data);
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
		$this->validate();
		$purchaseOrderData = $this->input->post([
			'pesan_untuk_bulan', 'po_type', 'id_booking', 'order_to', 'dokumen_nrfs_id', 'batas_waktu', 'kategori_po',
			'target_pembelian', 'total_amount', 'ach', 'produk', 'id_salesman'
		]);
		$purchaseOrderData = $this->clean_data($purchaseOrderData);
		$purchaseOrderData = array_merge($purchaseOrderData, [
			'po_id' => $this->purchase_order->generatePONumber(),
			'tanggal_order' => date('Y-m-d'),
			'status' => 'Draft'
		]);

		$purchaseOrderPartsData = $this->getOnly([
			'id_part_int', 'id_part', 'po_id', 'kuantitas', 'harga_saat_dibeli', 'tipe_diskon',
			'diskon_value', 'eta_terlama', 'eta_tercepat', 'w1', 'w2', 'w3', 'w4', 'w5', 'w6',
			'avg_six_weeks', 'akumulasi_qty', 'akumulasi_persen', 'suggested_order', 'adjusted_order', 'stock', 'rank', 'status',
			'tipe_diskon_campaign', 'diskon_value_campaign', 'id_campaign_diskon', 'jenis_diskon_campaign', 'tot_harga_part', 'order_md', 'qty_in_transit', 'stock_days'
		], $this->input->post('parts'), [
			'po_id' => $purchaseOrderData['po_id']
		]);

		$this->db->trans_start();
		if($purchaseOrderData['po_type'] == 'HLO'){
			$purchaseOrderData['status'] = 'Approved';

			$menu_kategori = $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'request_purchase_order')->get()->row();
			$this->notifikasi->insert([
				'id_notif_kat' => $menu_kategori->id_notif_kat,
				'judul' => $menu_kategori->nama_kategori,
				'pesan' => "Request Purchase Order baru dengan nomor {$purchaseOrderData['po_id']}",
				'link' => "{$menu_kategori->link}/detail?id={$purchaseOrderData['po_id']}",
				'id_dealer' => $purchaseOrderData['order_to'],
				'show_popup' => $menu_kategori->popup,
			]);
		}

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
				'redirect_url' => base_url('dealer/h3_dealer_purchase_order/detail?id=' . $purchase_order['po_id']) 
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
		->select('dealer_terdekat.nama_dealer as nama_dealer_terdekat')
		->select('rd.penomoran_ulang')
		->select('rd.tipe_penomoran_ulang')
		->select('uj.no_inv_uang_jaminan')
		->select('0 as total_amount_po')
		->from('tr_h3_dealer_purchase_order as po')
		->join('ms_dealer as dealer_terdekat', 'po.order_to = dealer_terdekat.id_dealer', 'left')
		->join('tr_h3_dealer_request_document as rd', 'rd.id_booking = po.id_booking', 'left')
		->join('tr_h2_uang_jaminan as uj', 'uj.id_booking = rd.id_booking', 'left')
		->where('po.po_id', $this->input->get('id'))
		->get()->row();


		$parts = $this->db
		->select('pop.*')
		->select('p.id_part_int')
		->select('p.nama_part')
		->select('ROUND(IFNULL(ar.avg_six_weeks, 0), 0) AS avg_six_weeks')
		->select('IFNULL(ar.w1, 0) AS w1')
		->select('IFNULL(ar.w2, 0) AS w2')
		->select('IFNULL(ar.w3, 0) AS w3')
		->select('IFNULL(ar.w4, 0) AS w4')
		->select('IFNULL(ar.w5, 0) AS w5')
		->select('IFNULL(ar.w6, 0) AS w6')
		->select('IFNULL(ar.stock_days, 0) AS stock_days')
		->from('tr_h3_dealer_purchase_order_parts as pop')
		->join('ms_h3_analisis_ranking as ar', "(ar.id_part = pop.id_part and ar.id_dealer = {$this->m_admin->cari_dealer()})", 'left')
		->join('ms_part as p', 'p.id_part = pop.id_part')
		->where('pop.po_id', $this->input->get('id'))
		->get()->result_array();

		$data['parts'] = array_map(function($row){
			$row['order_md'] = $this->purchase_parts->qty_on_order_md($this->m_admin->cari_dealer(), $row['id_part']);
			$row['qty_in_transit'] = $this->dealer_stock->qty_intransit_md($this->m_admin->cari_dealer(), $row['id_part']);
			$row['sim_part'] = $this->sim_part->qty_sim_part($this->m_admin->cari_dealer(), $row['id_part_int']);
			
			return $row;
		}, $parts);

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
		->select('dealer_terdekat.nama_dealer')
		->select('rd.penomoran_ulang')
		->select('rd.tipe_penomoran_ulang')
		->select('uj.no_inv_uang_jaminan')
		->select('0 as total_amount_po')
		->from('tr_h3_dealer_purchase_order as po')
		->join('ms_dealer as dealer_terdekat', 'po.order_to = dealer_terdekat.id_dealer', 'left')
		->join('tr_h3_dealer_request_document as rd', 'rd.id_booking = po.id_booking', 'left')
		->join('tr_h2_uang_jaminan as uj', 'uj.id_booking = rd.id_booking', 'left')
		->where('po.po_id', $this->input->get('id'))
		->get()->row()
		;

		$parts = $this->db
		->select('pop.*')
		->select('p.nama_part')
		->select('p.id_part_int')
		->select('ROUND(IFNULL(ar.avg_six_weeks, 0), 0) AS avg_six_weeks')
		->select('IFNULL(ar.w1, 0) AS w1')
		->select('IFNULL(ar.w2, 0) AS w2')
		->select('IFNULL(ar.w3, 0) AS w3')
		->select('IFNULL(ar.w4, 0) AS w4')
		->select('IFNULL(ar.w5, 0) AS w5')
		->select('IFNULL(ar.w6, 0) AS w6')
		->select('IFNULL(ar.stock_days, 0) AS stock_days')
		->from('tr_h3_dealer_purchase_order_parts as pop')
		->join('ms_h3_analisis_ranking as ar', "(ar.id_part = pop.id_part and ar.id_dealer = {$this->m_admin->cari_dealer()})", 'left')
		->join('ms_part as p', 'p.id_part = pop.id_part')
		->where('pop.po_id', $this->input->get('id'))
		->get()->result_array();

		$data['parts'] = array_map(function($row){
			$row['order_md'] = $this->purchase_parts->qty_on_order_md($this->m_admin->cari_dealer(), $row['id_part']);
			$row['qty_in_transit'] = $this->dealer_stock->qty_intransit_md($this->m_admin->cari_dealer(), $row['id_part']);
			$row['sim_part'] = $this->sim_part->qty_sim_part($this->m_admin->cari_dealer(), $row['id_part_int']);
			
			return $row;
		}, $parts);
		$this->template($data);	
	}

	public function update(){
		$this->validate();

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

		$request_document_parts = $this->getOnly([
			'id_part', 'harga_saat_dibeli', 'kuantitas', 'eta_terlama'
		], $this->input->post('parts'));
		$this->update_request_document_parts($this->input->post('id_booking'), $request_document_parts);
		$this->db->trans_complete();

		$purchase_order = (array) $this->purchase_order->get($this->input->post(['po_id']), true);
		if ($this->db->trans_status() AND $purchase_order != null) {
			send_json([
				'message' => 'Berhasil memperbarui purchase order',
				'payload' => $purchase_order,
				'redirect_url' => base_url('dealer/h3_dealer_purchase_order/detail?id=' . $purchase_order['po_id']) 
			]);
		}else{
			log_message('debug', sprintf('Purchase order dealer %s tidak berhasil di perbarui', $this->input->post('po_id')));
			send_json([
				'message' => 'Tidak berhasil memperbarui purchase order'
			], 422);
		}
	}

	private function update_request_document_parts($id_booking, $request_document_parts){
		$this->load->model('h3_dealer_request_document_model', 'request_document');
		$this->load->model('h3_dealer_request_document_parts_model', 'request_document_parts');

		$request_document = $this->db
		->select('rd.id_booking')
		->from('tr_h3_dealer_request_document as rd')
		->where('rd.id_booking', $id_booking)
		->get()->row_array();

		if($request_document != null){
			$request_document_parts = array_map(function($row) use ($request_document) {
				$row['id_booking'] = $request_document['id_booking'];
				return $row;
			}, $request_document_parts);
			$this->request_document_parts->update_batch($request_document_parts, [
				'id_booking' => $request_document['id_booking']
			]);
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
			->select('pop.id_part')
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

	public function get_parts_diskon(){
		$this->load->model('h3_md_diskon_part_tertentu_model', 'diskon_part_tertentu');

		$list_diskon = [];
		foreach ($this->input->get('id_part') as $part) {
			$list_diskon[] = $this->diskon_part_tertentu->get_diskon($part, $this->input->get('id_dealer'), $this->input->get('po_type'), $this->input->get('produk'));
		}

		send_json($list_diskon);
	}

	public function get_parts_sales_campaign(){
		$this->load->model('H3_md_sales_campaign_model', 'sales_campaign');

		$result = [];
		foreach ($this->input->post('order') as $part) {
			$diskon = $this->sales_campaign->get_diskon_sales_campaign($part['id_part'], $part['qty_order']);
			if($diskon != null){
				$result[] = $diskon;
			}
		}
		send_json($result);
	}

	public function get_parts_diskon_oli_reguler(){
		$this->load->model('h3_md_diskon_oli_reguler_model', 'diskon_oli_reguler');

		$result = [];
		$jumlah_dus = $this->get_jumlah_dus($this->input->post('parts'));
		foreach ($this->input->post('parts') as $part) {
			$result[] = $this->diskon_oli_reguler->get_diskon($part['id_part'], $this->input->post('id_dealer'), $jumlah_dus);
		}
		send_json($result);
	}

	private function get_jumlah_dus($parts){
		$total_dus = 0;
		foreach ($parts as $part) {
			$data_part = $this->db
			->select('IFNULL(p.qty_dus, 1) as qty_dus')
			->from('ms_part as p')
			->where('p.id_part', $part['id_part'])
			->get()->row_array();
			$total_dus += $part['kuantitas'] / $data_part['qty_dus'];
		}

		return floor($total_dus);
	}

	public function get_parts_diskon_oli_kpb(){
		$this->load->model('h3_md_ms_diskon_oli_kpb_model', 'diskon_oli_kpb');		

		$data = [];
		foreach ($this->input->post('parts') as $part) {
			$diskon = $this->diskon_oli_kpb->get_diskon_oli_kpb($part['id_part'], $part['id_tipe_kendaraan']);

			if($diskon != null){
				$data[] = $diskon;
			}
		}

		send_json($data);
	}

	private function set_closed_date_po(){
		$id_booking_untuk_po_closed = $this->db
		->select('po.id_booking')
		->from('tr_h3_dealer_purchase_order as po')
		->where('po.status', 'Closed')
		->get_compiled_select();

		$data = $this->db
		->select('so.nomor_so')
		->select('so.booking_id_reference')
		->select('nsc.no_nsc')
		->select('date_format(nsc.created_at, "%Y-%m-%d") as nsc_dibuat')
		->from('tr_h3_dealer_sales_order as so')
		->join('tr_h23_nsc as nsc', 'nsc.id_referensi = so.nomor_so')
		->where("so.booking_id_reference IN ({$id_booking_untuk_po_closed})", null, false)
		->order_by('nsc.created_at', 'desc')
		->get()->result_array();


		$id_booking_sudah_update = [];
		foreach ($data as $row) {
			if(!in_array($row['booking_id_reference'], $id_booking_sudah_update)){
				$this->db
				->set('po.tanggal_selesai', $row['nsc_dibuat'])
				->where('po.id_booking', $row['booking_id_reference'])
				->update('tr_h3_dealer_purchase_order as po');
				$id_booking_sudah_update[] = $row['booking_id_reference'];
			}
		}
	}
	
	public function cetakan_po_hotline_md_non_penomoran_ulang(){
		$this->load->library('mpdf_l');
		$mpdf                           = $this->mpdf_l->load();
		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
		$mpdf->charset_in               = 'UTF-8';
        $mpdf->autoLangToFont           = true;

        $perkiraan_hari = $this->db
        ->select('rdp.eta_terlama')
        ->from('tr_h3_dealer_request_document_parts as rdp')
        ->where('rdp.id_booking', $this->input->get('k'))
        ->order_by('rdp.eta_terlama', 'asc')
        ->limit(1)
        ->get_compiled_select();
        
        $total_pembayaran = $this->db
        ->select('
            sum( (rdp.kuantitas * rdp.harga_saat_dibeli) )
        ')
        ->from('tr_h3_dealer_request_document_parts as rdp')
        ->where('rdp.id_booking', $this->input->get('k'))
        ->get_compiled_select();

		$data['purchase_order'] = $this->db
		->select('po.po_id')
        ->select('rd.*')
        ->select('d.nama_dealer as nama_jaringan')
        ->select('d.alamat as alamat_jaringan')
        ->select('"-" as fax_jaringan')
        ->select('d.no_telp as no_telp_jaringan')
        ->select('rd.id_booking as nomor_order')
        ->select('date_format(rd.created_at, "%d/%m/%Y") as tanggal_order')
        ->select('ifnull(rd.no_buku_khusus_claim_c2, "-") as nomor_claim_c2')
        ->select('c.nama_customer')
        ->select('c.no_hp as no_telp_customer')
        ->select('c.alamat as alamat_customer')
        ->select('c.no_polisi')
        ->select('c.tahun_produksi as tahun_perakitan')
        ->select('c.no_rangka')
        ->select('c.no_mesin')
        ->select('format(rd.uang_muka, 0, "ID_id") as uang_muka_formatted')
        ->select("format(({$total_pembayaran}), 0, 'ID_id') as total_pembayaran")
        ->select("format( ( ({$total_pembayaran}) - rd.uang_muka ), 0, 'ID_id') as sisa_pembayaran")
        ->select("
            case 
                when ({$perkiraan_hari}) is not null then date_format(({$perkiraan_hari}), '%d-%m-%Y')
                else '-'
            end as perkiraan_hari
        ", false)
		->select('rd.vor')
		->from('tr_h3_dealer_purchase_order as po')
        ->join('tr_h3_dealer_request_document as rd', 'rd.id_booking = po.id_booking')
        ->join('ms_dealer as d', 'd.id_dealer = rd.id_dealer')
        ->join('ms_customer_h23 as c', 'c.id_customer = rd.id_customer')
        ->where('po.po_id', $this->input->get('id'))
		->limit(1)
        ->get()->row_array();

        $data['parts'] = $this->db
		->select('pop.id_part')
		->select('p.nama_part')
		->select('pop.kuantitas')
		->from('tr_h3_dealer_purchase_order_parts as pop')
        ->join('ms_part as p', 'p.id_part = pop.id_part')
        ->where('pop.po_id', $this->input->get('id'))
        ->limit(10)
		->get()->result_array();

		$html = $this->load->view('dealer/h3_dealer_cetakan_po_hotline_md', $data, true);
		
        // render the view into HTML
        $mpdf->WriteHTML($html);
        
        if($data['purchase_order']['penomoran_ulang'] == 0){
            for ($i=0; $i < 3; $i++) { 
                $start = ($i * 8) + 11;
                $mpdf->RoundedRect(140 , $start , 3, 3, 'D');
            }
        }
		$po_number_formatted_for_title = str_replace('/', '-', $data['purchase_order']['po_id']);
		$output = "Cetakan PO {$po_number_formatted_for_title}.pdf";
		$mpdf->Output($output, 'I');
	}

	public function cetakan_po_hotline_md_penomoran_ulang(){
		$this->load->library('mpdf_l');
		$mpdf                           = $this->mpdf_l->load();
		$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
		$mpdf->charset_in               = 'UTF-8';
        $mpdf->autoLangToFont           = true;

        $perkiraan_hari = $this->db
        ->select('rdp.eta_terlama')
        ->from('tr_h3_dealer_request_document_parts as rdp')
        ->where('rdp.id_booking', $this->input->get('k'))
        ->order_by('rdp.eta_terlama', 'asc')
        ->limit(1)
        ->get_compiled_select();
        
        $total_pembayaran = $this->db
        ->select('
            sum( (rdp.kuantitas * rdp.harga_saat_dibeli) )
        ')
        ->from('tr_h3_dealer_request_document_parts as rdp')
        ->where('rdp.id_booking', $this->input->get('k'))
        ->get_compiled_select();

		$data['purchase_order'] = $this->db
		->select('po.po_id')
		->select('po.tanggal_order')
        ->select('rd.*')
        ->select('d.nama_dealer as nama_jaringan')
        ->select('d.alamat as alamat_jaringan')
        ->select('"-" as fax_jaringan')
        ->select('d.no_telp as no_telp_jaringan')
        ->select('rd.id_booking as nomor_order')
        ->select('ifnull(rd.no_buku_khusus_claim_c2, "-") as nomor_claim_c2')
        ->select('c.nama_customer')
        ->select('c.no_hp as no_telp_customer')
        ->select('c.alamat as alamat_customer')
        ->select('tk.tipe_ahm as tipe_kendaraan')
		->select('c.no_polisi')
		->select('tk.tipe_ahm as tipe_kendaraan')
		->select('prov.provinsi')
		->select('kel.kode_pos')
        ->select('c.tahun_produksi as tahun_perakitan')
        ->select('c.no_rangka')
        ->select('c.no_mesin')
        ->select('format(rd.uang_muka, 0, "ID_id") as uang_muka_formatted')
        ->select("format(({$total_pembayaran}), 0, 'ID_id') as total_pembayaran")
        ->select("format( ( ({$total_pembayaran}) - rd.uang_muka ), 0, 'ID_id') as sisa_pembayaran")
        ->select("
            case 
                when ({$perkiraan_hari}) is not null then date_format(({$perkiraan_hari}), '%d-%m-%Y')
                else '-'
            end as perkiraan_hari
        ", false)
		->select('rd.vor')
		->from('tr_h3_dealer_purchase_order as po')
        ->join('tr_h3_dealer_request_document as rd', 'rd.id_booking = po.id_booking')
        ->join('ms_dealer as d', 'd.id_dealer = rd.id_dealer')
		->join('ms_customer_h23 as c', 'c.id_customer = rd.id_customer')
		->join('ms_kelurahan as kel', 'kel.id_kelurahan = c.id_kelurahan', 'left')
        ->join('ms_kecamatan as kec', 'kec.id_kecamatan = c.id_kecamatan', 'left')
        ->join('ms_kabupaten as kab', 'kab.id_kabupaten = c.id_kabupaten', 'left')
        ->join('ms_provinsi as prov', 'prov.id_provinsi = c.id_provinsi', 'left')
        ->join('ms_tipe_kendaraan as tk', 'tk.id_tipe_kendaraan = c.id_tipe_kendaraan', 'left')
        ->where('po.po_id', $this->input->get('id'))
		->limit(1)
        ->get()->row_array();

        $data['parts'] = $this->db
		->select('pop.id_part')
		->select('p.nama_part')
		->select('pop.harga_saat_dibeli')
		->from('tr_h3_dealer_purchase_order_parts as pop')
        ->join('ms_part as p', 'p.id_part = pop.id_part')
        ->where('pop.po_id', $this->input->get('id'))
        ->limit(10)
		->get()->result_array();

		$html = $this->load->view('dealer/h3_dealer_cetakan_po_hotline_md_penomoran_ulang', $data, true);
		
        // render the view into HTML
        $mpdf->WriteHTML($html);
        
        if($data['purchase_order']['penomoran_ulang'] == 0){
            for ($i=0; $i < 3; $i++) { 
                $start = ($i * 8) + 11;
                $mpdf->RoundedRect(140 , $start , 3, 3, 'D');
            }
        }
		$po_number_formatted_for_title = str_replace('/', '-', $data['purchase_order']['po_id']);
		$output = "Cetakan PO {$po_number_formatted_for_title}.pdf";
		$mpdf->Output($output, 'I');
	}
}