<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_do_sales_order extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_do_sales_order";
	protected $title  = "Approve DO";

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
		$auth = $this->m_admin->user_auth($this->page,"select");		
		$sess = $this->m_admin->sess_auth();						
		if($name=="" OR $auth=='false')
		{
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."denied'>";
		}elseif($sess=='false'){
			echo "<meta http-equiv='refresh' content='0; url=".base_url()."crash'>";
		}

		$this->load->model('h3_md_do_sales_order_model', 'do_sales_order');
		$this->load->model('h3_md_do_sales_order_parts_model', 'do_sales_order_parts');
		$this->load->model('h3_md_do_sales_order_cashback_model', 'do_sales_order_cashback');
		$this->load->model('h3_md_do_sales_order_gimmick_model', 'do_sales_order_gimmick');
		$this->load->model('H3_md_do_sales_order_poin_model', 'do_sales_order_poin');
		$this->load->model('h3_md_sales_order_model', 'sales_order');
		$this->load->model('h3_md_sales_order_parts_model', 'sales_order_parts');
		$this->load->model('h3_md_picking_list_model', 'picking_list');
		$this->load->model('h3_md_picking_list_parts_model', 'picking_list_parts');
		$this->load->model('h3_md_scan_picking_list_parts_model', 'scan_picking_list_parts');
		$this->load->model('H3_md_ms_plafon_model', 'plafon');
		$this->load->model('notifikasi_model', 'notifikasi');
		$this->load->model('H3_dealer_order_parts_tracking_model', 'order_parts_tracking');		
		$this->load->model('H3_md_ar_part_model', 'ar_part');
	}

	public function index(){
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$this->template($data);
	}

	public function detail(){
		$data['mode']    = 'detail';
		$data['set']     = "form";

		$data['ppn'] =round(getPPN(1.1,false)/10,2);
		$data['do_sales_order'] = $this->do_sales_order->get_do_sales_order($this->input->get('id'));
		$data['do_sales_order_parts'] = $this->do_sales_order_parts->get_do_sales_order_parts($this->input->get('id'));
		$data['monitoring_piutang'] = $this->ar_part->piutang_dealer($data['do_sales_order']['id_dealer'], $data['do_sales_order']['gimmick'] == 1, $data['do_sales_order']['kategori_po'] == 'KPB');
		$data['do_cashback'] = $this->do_sales_order_cashback->get_cashback_do($data['do_sales_order']['id_do_sales_order'], true);
		$data['do_gimmick'] = $this->do_sales_order_gimmick->get_gimmick_do($data['do_sales_order']['id_do_sales_order'], true);
		$data['do_poin'] = $this->do_sales_order_poin->get_poin_do($data['do_sales_order']['id_do_sales_order']);

		$this->template($data);
	}

	public function get_salesman(){
		$salesman = $this->db
		->select('ts.id_salesman_parts')
		->select('kd.nama_lengkap as nama_salesman')
		->from('ms_h3_md_target_salesman as ts')
		->join('ms_karyawan_dealer as kd', 'kd.id_karyawan_dealer = ts.id_salesman_parts')
		->where('ts.id_dealer', $this->input->get('id_dealer'))
		->order_by('ts.created_at', 'desc')
		->get()->row();

		send_json($salesman);
	}

	public function approve(){
		$this->db->trans_start();

		$do_sales_order = $this->do_sales_order->get_do_sales_order($this->input->post('id_do_sales_order'));

		$data = array_merge($this->input->post([
			'status', 'check_diskon_insentif', 'diskon_insentif', 
			'check_diskon_cashback', 'diskon_cashback', 'total', 'sub_total',
			'id_salesman', 'total_ppn','check_ppn_tools'
		]), [
			'status' => 'Approved',
			'approved_at' => date('Y-m-d H:i:s', time()),
			'approved_by' => $this->session->userdata('id_user')
		]);
		$data = $this->clean_data($data);
		$this->do_sales_order->update($data, $this->input->post(['id_do_sales_order']));
		$claim_insentif = $this->do_sales_order->claim_insentif_poin($this->input->post('id_do_sales_order'), $this->input->post('diskon_insentif'));
		if(is_array($claim_insentif)){
			send_json($claim_insentif, 422);
		}

		foreach ($this->input->post('parts') as $part) {
			$condition = [
				'id_part_int' => $part['id_part_int'],
				'id_do_sales_order' => $this->input->post('id_do_sales_order')
			];

			if($do_sales_order != null and $do_sales_order['kategori_po'] == 'KPB'){
				$condition['id_tipe_kendaraan'] = $part['id_tipe_kendaraan'];
			}

			$data = $this->get_in_array(['harga_setelah_diskon'], $part);
			$this->do_sales_order_parts->update($data, $condition);
		}

		$this->create_picking_list($this->input->post('id_do_sales_order'));

		//Update Status SO menjadi Closed SO 
		$id_sales_order = $this->db->select('id_sales_order_int')
								   ->from('tr_h3_md_do_sales_order')
								   ->where('id_do_sales_order',$this->input->post('id_do_sales_order'))
								   ->get()->row_array();
		$this->db->set('status','Closed')
				->set('closed_at',date('Y-m-d H:i:s', time()))
				->set('closed_by',$this->session->userdata('id_user'))
				->where('id',$id_sales_order['id_sales_order_int'])
				->update('tr_h3_md_sales_order');

		$data = $this->db
		->select('d.nama_dealer')
		->select('do.id_do_sales_order')
		->from('tr_h3_md_do_sales_order as do')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
		->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
		->where('do.id_do_sales_order', $this->input->post('id_do_sales_order'))
		->get()->row_array();
		
		$menu_kategori = $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'do_approved_to_picking_list')->get()->row_array();
		if($menu_kategori != null){
			$this->notifikasi->insert([
				'id_notif_kat' => $menu_kategori['id_notif_kat'],
				'judul' => $menu_kategori['nama_kategori'],
				'pesan' => "No. DO {$data['id_do_sales_order']} a.n {$data['nama_dealer']} telah di approve. Silahkan proses picking list.",
				'link' => "{$menu_kategori['link']}",
				'show_popup' => $menu_kategori['popup'],
			]);
		}
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$this->session->set_userdata('pesan', 'Berhasil approve DO dengan nomor ' . $this->input->post('id_do_sales_order'));
			$this->session->set_userdata('tipe', 'success');

			send_json([
				'redirect_url' => base_url('h3/h3_md_do_sales_order')
			]);
		}else{
			send_json([
				'message' => 'Tidak berhasil approve DO dengan nomor ' . $this->input->post('id_do_sales_order')
			], 422);
		}
	}

	public function create_picking_list($id_do_sales_order){
		$sales_order = $this->db
		->select('do.id as id_do')
		->select('so.kategori_po')
		->select('so.po_type')
		->select('so.id_dealer')
		->select('so.gimmick')
		->select('so.is_ev')
		->Select('so.id_rekap_purchase_order_dealer')
		->from('tr_h3_md_do_sales_order as do')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
		->where('do.id_do_sales_order', $id_do_sales_order)
		->get()->row();

		$picking_list = $this->db
		->select('pl.id')
		->select('pl.id_picking_list')
		->from('tr_h3_md_picking_list as pl')
		->where('pl.id_ref', $id_do_sales_order)
		->get()->row_array();

		// Jika terdapat picking list dengan nomor do yang sama, lakukan reset validasi ataupun scan picking list
		if($picking_list != null){
			$this->picking_list->update([
				'revisi_validasi' => 1,
				'status' => 'On Process',
				'end_pick' => null,
				'ready_for_scan' => 0
			], [
				'id_picking_list' => $picking_list['id_picking_list']
			]);
			
			$this->picking_list->reset_qty_pick_tracking($picking_list['id']);
			$this->picking_list->reset_qty_pack_tracking($picking_list['id']);
			$parts = $this->get_picking_parts($this->input->post('parts'), $picking_list['id_picking_list'], $sales_order->kategori_po);
			$parts = array_map(function($row) use ($picking_list) {
				$row['id_picking_list_int'] = $picking_list['id'];
				return $row;
			}, $parts);
			$this->picking_list_parts->insert_batch($parts);
		}else{
			$picking_list = array_merge($this->input->post(['id_dealer']), [
				'id_ref_int' => $sales_order->id_do,
				'id_ref' => $id_do_sales_order,
				'id_picking_list' => $this->picking_list->generateID($sales_order->po_type, $sales_order->id_dealer, $sales_order->gimmick),
				'tipe_ref' => 'do_sales_order',
				'tanggal' => date('Y-m-d', time()),
			]);

			$parts = $this->get_picking_parts($this->input->post('parts'), $picking_list['id_picking_list'], $sales_order->kategori_po);
			if(count($parts) > 0){
				$this->picking_list->insert($picking_list);
				$id_picking_list_int = $this->db->insert_id();
				$parts = array_map(function($row) use ($id_picking_list_int) {
					$row['id_picking_list_int'] = $id_picking_list_int;
					return $row;
				}, $parts);
				$this->picking_list_parts->insert_batch($parts);
			}else{
				send_json([
					'error_type' => 'part_for_picking_list_not_available',
					'message' => 'Gagal buat picking list dikarenakan part yang diminta tidak tersedia'
				], 422);
			}
		}
	}

	public function get_picking_parts($parts, $id_picking_list, $kategori_po = null){
		$picking_parts = [];
		foreach ($parts as $part) {
			// Cek apakah EV atau tidak 
			$kelompok_part = $this->db->select('kelompok_part')
									  ->from('ms_part')
									  ->where('id_part', $part['id_part'])
									  ->get()->row_array();
			
			$qty_harus_dipenuhi = $part['qty_supply'];
			if($kelompok_part['kelompok_part'] == 'EVBT' || $kelompok_part['kelompok_part'] == 'EVCH'){
				// Type ACC 
				if($kelompok_part['kelompok_part'] == 'EVBT'){
					$type_acc = 'B';
				}elseif($kelompok_part['kelompok_part'] == 'EVCH'){
					$type_acc = 'C';
				}

				$stocks = $this->db->select('ts.serial_number')
									   ->select('ts.id_lokasi_rak_md as id_lokasi_rak')
									   ->select('ts.fifo')
									   ->select('1 as qty')
									   ->from('tr_h3_serial_ev_tracking as ts')
									   ->where('ts.accStatus',2)
									   ->where('ts.type_accesories',$type_acc)
									   ->where('id_part_int',$part['id_part_int'])
									   ->group_start()
										->where('ts.id_do_sales_order_int',null)
										->or_where('ts.id_do_sales_order_int',0)
										->or_where('ts.id_do_sales_order_int','')
									   ->group_end()
									   ->order_by('ts.fifo','ASC')
									   ->get()->result_array();

				foreach ($stocks as $stock) {
					if($qty_harus_dipenuhi == 0) break;
	
					$picking_part['id_picking_list'] = $id_picking_list;
					$picking_part['id_part_int'] = $part['id_part_int'];
					$picking_part['id_part'] = $part['id_part'];
					if($kategori_po != null and $kategori_po == 'KPB'){
						$picking_part['id_tipe_kendaraan'] = $part['id_tipe_kendaraan'];
					}
					$picking_part['id_lokasi_rak'] = $stock['id_lokasi_rak'];
					$picking_part['qty_supply'] = $qty_harus_dipenuhi;
					$picking_part['serial_number'] = $stock['serial_number'];
					if($stock['qty'] <=$qty_harus_dipenuhi){
						$picking_part['qty_supply'] = $stock['qty'];
						$qty_harus_dipenuhi -= $stock['qty'];
					}else{
						$picking_part['qty_supply'] = $qty_harus_dipenuhi;
						$qty_harus_dipenuhi -= $qty_harus_dipenuhi;
					}

					//Cek untuk no int dari do sales order 
					$id_do_sales_order_int = $this->db->select('id')
									  ->from('tr_h3_md_do_sales_order')
									  ->where('id_do_sales_order', $this->input->post('id_do_sales_order'))
									  ->get()->row_array();

					// Update Status id_do_sales_order di tr_h3_serial_ev_tracking 
					$this->db->set('id_do_sales_order_int',$id_do_sales_order_int['id'])
							->set('id_do_sales_order',$this->input->post('id_do_sales_order'))
							->where('serial_number',$stock['serial_number'])
							->update('tr_h3_serial_ev_tracking');
					$picking_parts[] = $picking_part;
				}					   
			}else{
				$qty_booking_lokasi = $this->db
				->select('SUM(plp.qty_supply)')
				->from('tr_h3_md_picking_list as pl')
				->join('tr_h3_md_picking_list_parts as plp', 'plp.id_picking_list = pl.id_picking_list')
				->join('tr_h3_md_packing_sheet as ps', 'ps.id_picking_list = pl.id_picking_list', 'left')
				// ->where('pl.status', 'On Process')
				->where('ps.id', null)
				->where('plp.id_part_int = sp.id_part_int')
				->where('plp.id_lokasi_rak = sp.id_lokasi_rak')
				->get_compiled_select();
	
				$stocks = $this->db
				->select('sp.id_lokasi_rak')
				->select('sp.id_part_int')
				->select("(sp.qty - IFNULL(({$qty_booking_lokasi}), 0)) as qty")
				->from('tr_stok_part as sp')
				->where('sp.id_part_int', $part['id_part_int'])
				// ->where("(sp.qty - IFNULL(({$qty_booking_lokasi}), 0)) >", 0, false)
				->where('sp.id_lokasi_rak IS NOT NULL', null, false)
				->having('qty >', 0)
				->order_by('sp.qty', 'asc')
				->get()->result_array();
	
				foreach ($stocks as $stock) {
					if($qty_harus_dipenuhi == 0) break;
	
					$picking_part['id_picking_list'] = $id_picking_list;
					$picking_part['id_part_int'] = $part['id_part_int'];
					$picking_part['id_part'] = $part['id_part'];
					if($kategori_po != null and $kategori_po == 'KPB'){
						$picking_part['id_tipe_kendaraan'] = $part['id_tipe_kendaraan'];
					}
					$picking_part['id_lokasi_rak'] = $stock['id_lokasi_rak'];
					if($stock['qty'] <=$qty_harus_dipenuhi){
						$picking_part['qty_supply'] = $stock['qty'];
						$qty_harus_dipenuhi -= $stock['qty'];
					}else{
						$picking_part['qty_supply'] = $qty_harus_dipenuhi;
						$qty_harus_dipenuhi -= $qty_harus_dipenuhi;
					}
					$picking_parts[] = $picking_part;
				}
			}
		}
		return $picking_parts;
	}


	public function get_picking_parts_old($parts, $id_picking_list, $kategori_po = null){
		$picking_parts = [];
		foreach ($parts as $part) {
			$qty_harus_dipenuhi = $part['qty_supply'];

			$qty_booking_lokasi = $this->db
			->select('SUM(plp.qty_supply)')
			->from('tr_h3_md_picking_list as pl')
			->join('tr_h3_md_picking_list_parts as plp', 'plp.id_picking_list = pl.id_picking_list')
			->join('tr_h3_md_packing_sheet as ps', 'ps.id_picking_list = pl.id_picking_list', 'left')
			// ->where('pl.status', 'On Process')
			->where('ps.id', null)
			->where('plp.id_part = sp.id_part')
			->where('plp.id_lokasi_rak = sp.id_lokasi_rak')
			->get_compiled_select();

			$stocks = $this->db
			->select('sp.id_lokasi_rak')
			->select("(sp.qty - IFNULL(({$qty_booking_lokasi}), 0)) as qty")
			->from('tr_stok_part as sp')
			->where('sp.id_part', $part['id_part'])
			// ->where("(sp.qty - IFNULL(({$qty_booking_lokasi}), 0)) >", 0, false)
			->where('sp.id_lokasi_rak IS NOT NULL', null, false)
			->having('qty >', 0)
			->order_by('sp.qty', 'asc')
			->get()->result_array();

			foreach ($stocks as $stock) {
				if($qty_harus_dipenuhi == 0) break;

				$picking_part['id_picking_list'] = $id_picking_list;
				$picking_part['id_part_int'] = $part['id_part_int'];
				$picking_part['id_part'] = $part['id_part'];
				if($kategori_po != null and $kategori_po == 'KPB'){
					$picking_part['id_tipe_kendaraan'] = $part['id_tipe_kendaraan'];
				}
				$picking_part['id_lokasi_rak'] = $stock['id_lokasi_rak'];
				if($stock['qty'] <=$qty_harus_dipenuhi){
					$picking_part['qty_supply'] = $stock['qty'];
					$qty_harus_dipenuhi -= $stock['qty'];
				}else{
					$picking_part['qty_supply'] = $qty_harus_dipenuhi;
					$qty_harus_dipenuhi -= $qty_harus_dipenuhi;
				}
				$picking_parts[] = $picking_part;
			}
		}

		return $picking_parts;
	}

	public function reject(){
		$this->db->trans_start();
		//Check PW 
		$inputPassword = $this->input->post('pw_reject');

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
								
		if ($inputPassword != $correctPassword['password']) {
			send_json([
				'status' => 'gagal',
				'message' => 'Tidak Berhasil Reject DO. Cek kembali PW',
				'errors' => $this->form_validation->error_array()
			], 422);
		} 

		$data = array_merge($this->input->post(['alasan_reject', 'total']), [
			'status' => 'Rejected',
			'rejected_at' => date('Y-m-d H:i:s', time()),
			'rejected_by' => $this->session->userdata('id_user')
		]);
		$this->do_sales_order->update($data, $this->input->post(['id_do_sales_order']));

		$data = $this->db
		->select('d.nama_dealer')
		->select('do.id_do_sales_order')
		->select('so.id_ref')
		->select('so.id_rekap_purchase_order_dealer')
		->from('tr_h3_md_do_sales_order as do')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
		->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
		->where('do.id_do_sales_order', $this->input->post('id_do_sales_order'))
		->get()->row_array();

		$parts_do = $this->db
		->select('dop.id_part')
		->select('dop.qty_supply')
		->from('tr_h3_md_do_sales_order_parts as dop')
		->where('dop.id_do_sales_order', $this->input->post('id_do_sales_order'))
		->get()->result_array();

		foreach ($parts_do as $part) {
			$this->order_parts_tracking->kurang_qty_book($data['id_ref'], $part['id_part'], $part['qty_supply']);
			if($data['id_rekap_purchase_order_dealer'] != null){
				$purchase_orders = $this->db
				->select('po.po_id')
				->select('pop.id_part')
				->select('opt.qty_book')
				->from('tr_h3_md_rekap_purchase_order_dealer_item as ri')
				->join('tr_h3_dealer_purchase_order as po', 'po.po_id = ri.id_referensi')
				->join('tr_h3_dealer_purchase_order_parts as pop', 'pop.po_id = ri.id_referensi')
				->join('tr_h3_dealer_order_parts_tracking as opt', '(opt.po_id = pop.po_id and opt.id_part = pop.id_part)')
				->where('ri.id_rekap', $data['id_rekap_purchase_order_dealer'])
				->where('pop.id_part', $part['id_part'])
				->order_by('po.created_at', 'desc')
				->get()->result_array();

				$supply_untuk_dipecah = $part['qty_supply'];
				foreach ($purchase_orders as $purchase_order) {
					if($purchase_order['qty_book'] <= $supply_untuk_dipecah){
						$this->order_parts_tracking->kurang_qty_book($purchase_order['po_id'], $purchase_order['id_part'], $purchase_order['qty_book']);
						$supply_untuk_dipecah -= $purchase_order['qty_book'];
					}else if($purchase_order['qty_book'] >= $supply_untuk_dipecah){
						$this->order_parts_tracking->kurang_qty_book($purchase_order['po_id'], $purchase_order['id_part'], $supply_untuk_dipecah);
						break;
					}

					if($supply_untuk_dipecah == 0) break;
				}
			}
		}

		//Check apakah merupakan part EV atau tidak, jika iya hapus kolom booking id do 
		if($data['is_ev']==1){
			$this->db->set('id_do_sales_order_int', null);
            $this->db->set('id_do_sales_order', null);
            $this->db->where('id_do_sales_order',$this->input->post('id_do_sales_order'));
            $this->db->update('tr_h3_serial_ev_tracking');
		}
		
		$menu_kategori = $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'do_got_rejected_by_finance')->get()->row_array();
		if($menu_kategori != null){
			$this->notifikasi->insert([
				'id_notif_kat' => $menu_kategori['id_notif_kat'],
				'judul' => $menu_kategori['nama_kategori'],
				'pesan' => "No. DO {$data['id_do_sales_order']} a.n {$data['nama_dealer']} telah di reject. Silahkan cek.",
				'link' => "{$menu_kategori['link']}",
				'show_popup' => $menu_kategori['popup'],
			]);
		}
		$this->db->trans_complete();

		if($this->db->trans_status()){
			$do = $this->do_sales_order->get($this->input->post(['id_do_sales_order']), true);
			send_json($do);
		}else{
			$this->set_status_header(500);
		}
	}

	public function cancel(){
		$do_sales_order = (array) $this->do_sales_order->find($this->input->get('id_do_sales_order'), 'id_do_sales_order');
		$alasan_cancel = $this->input->get('alasan_cancel');
		//Check PW 
		$inputPassword = $this->input->post('pw_cancel');

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
								
		if ($inputPassword != $correctPassword['password']) {
			send_json([
				'status' => 'gagal',
				'message' => 'Tidak Berhasil Cancel DO. Cek kembali PW',
				'errors' => $this->form_validation->error_array()
			], 422);
		} 
		
		$this->db->trans_begin();
		try{
			$this->do_sales_order->cancel($do_sales_order['id'],$alasan_cancel);
			$cek = $this->db->query("SELECT COUNT(id_do_sales_order) AS id_do_sales_order FROM tr_h3_serial_ev_tracking WHERE id_do_sales_order = '".$this->input->get('id_do_sales_order')."'", array($this->input->get('id_do_sales_order')))->row_array();
			//Check apakah merupakan part EV atau tidak, jika iya hapus kolom booking id do 
			if($cek['id_do_sales_order'] > 0){
				$this->db->set('id_do_sales_order_int', null);
				$this->db->set('id_do_sales_order', null);
				$this->db->where('id_do_sales_order',$this->input->post('id_do_sales_order'));
				$this->db->update('tr_h3_serial_ev_tracking');
			}

			$this->db->trans_commit();

			send_json([
				'redirect_url' => base_url(sprintf('h3/h3_md_do_sales_order/detail?id=%s', $do_sales_order['id_do_sales_order']))
			]);
		}catch(Exception $exception){
			$this->db->trans_rollback();

			send_json([
				'message' => $exception->getMessage()
			], 422);
		}
	}
}