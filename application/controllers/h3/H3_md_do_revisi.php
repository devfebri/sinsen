<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_do_revisi extends Honda_Controller {

	protected $folder = "h3";
    protected $page   = "h3_md_do_revisi";
	protected $title  = "DO Revisi";

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

		$this->load->model('h3_md_sales_order_model', 'sales_order');
		$this->load->model('h3_md_do_sales_order_model', 'do_sales_order');
		$this->load->model('h3_md_do_sales_order_parts_model', 'do_sales_order_parts');
		$this->load->model('h3_md_do_sales_order_cashback_model', 'do_sales_order_cashback');
		$this->load->model('h3_md_do_sales_order_gimmick_model', 'do_sales_order_gimmick');
		$this->load->model('h3_md_picking_list_model', 'picking_list');
		$this->load->model('h3_md_picking_list_parts_model', 'picking_list_parts');
		$this->load->model('h3_md_do_revisi_model', 'do_revisi');
		$this->load->model('h3_md_do_revisi_item_model', 'do_revisi_item');
		$this->load->model('H3_md_sales_campaign_model', 'sales_campaign');
		$this->load->model('h3_md_packing_sheet_model', 'packing_sheet');
		$this->load->model('H3_md_ms_plafon_model', 'plafon');
		$this->load->model('h3_md_scan_picking_list_parts_model', 'scan_picking_list');
		$this->load->model('H3_dealer_order_parts_tracking_model','order_parts_tracking');
		$this->load->model('h3_md_diskon_part_tertentu_model', 'diskon_part_tertentu');
		$this->load->model('h3_md_diskon_oli_reguler_model', 'diskon_oli_reguler');
		$this->load->model('h3_md_ms_diskon_oli_kpb_model', 'diskon_oli_kpb');
		$this->load->model('H3_md_sales_campaign_model', 'sales_campaign');
		$this->load->helper('get_diskon_part');
		$this->load->helper('harga_setelah_diskon');
	}

	public function index(){
		$data['mode'] = 'index';
		$data['set'] = 'index';
		$this->template($data);
	}

	public function detail(){
		$data['mode']    = 'detail';
		$data['set']     = "form";

		$do_sales_order = $this->db
		->select('dr.id')
		->select('dso.tanggal as tanggal_do')
		->select('so.id_dealer')
		->select('dso.id_do_sales_order')
		->select('so.tanggal_order as tanggal_so')
		->select('so.id_sales_order')
		->select('d.nama_dealer')
		->select('d.kode_dealer_md as kode_dealer')
		->select('d.alamat')
		->select('so.kategori_po')
		->select('dso.top')
		->select('so.po_type')
		->select('dso.status')
		->select('dso.diskon_additional')
		->select('dso.check_diskon_insentif')
		->select('dso.diskon_insentif')
		->select('dso.check_diskon_cashback')
		->select('dso.diskon_cashback')
		->select('so.id_dealer')
		->select('so.id_salesman')
		->select('k.nama_lengkap as nama_salesman')
		->from('tr_h3_md_do_revisi as dr')
		->join('tr_h3_md_do_sales_order as dso', 'dso.id_do_sales_order = dr.id_do_sales_order')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = dso.id_sales_order')
		->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
		->join('ms_karyawan as k', 'k.id_karyawan = so.id_salesman', 'left')
		->where('dr.id', $this->input->get('id'))
		->limit(1)
		->get()->row_array();
		$plafon = $this->plafon->get_plafon($do_sales_order['id_dealer']);
		$plafon_booking = $this->plafon->get_plafon_booking($do_sales_order['id_dealer']);
		$do_sales_order['plafon_booking'] = $plafon_booking;
		$do_sales_order['plafon'] = $plafon;
		$campaign = $this->do_sales_order_cashback->get_cashback_do($do_sales_order['id_do_sales_order']);
		$sum_campaign = array_sum(
			array_map(function($data){
				return floatval($data['cashback']);
			}, $campaign)
		);
		$data['do_sales_order'] = $do_sales_order;

		$this->db
		->select('dri.id_part')
		->select('p.nama_part')
		->select('sop.harga')
		->select('SUM(dri.qty_do) as qty_supply')
		->select('dop.tipe_diskon_satuan_dealer')
		->select('dop.diskon_satuan_dealer')
		->select('dop.tipe_diskon_campaign')
		->select('dop.diskon_campaign')
		->select('ifnull(kp.include_ppn, 0) as include_ppn')
		->select('p.harga_dealer_user as harga_jual')
		->select('p.harga_md_dealer as harga_beli')
		->from('tr_h3_md_do_revisi as dr')
		->join('tr_h3_md_do_revisi_item as dri', 'dri.id_revisi = dr.id')
		->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = dr.id_do_sales_order')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
		->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
		->group_by('dri.id_part')
		->order_by('dri.id_part', 'asc');

		if($do_sales_order['kategori_po'] == 'KPB'){
			$this->db->select('dri.id_tipe_kendaraan');
			$this->db->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_part = dri.id_part and dop.id_do_sales_order = dr.id_do_sales_order and dop.id_tipe_kendaraan = dri.id_tipe_kendaraan)');
			$this->db->join('tr_h3_md_sales_order_parts as sop', '(sop.id_sales_order = do.id_sales_order AND sop.id_part = dop.id_part AND sop.id_tipe_kendaraan = dop.id_tipe_kendaraan)');
		}else{
			$this->db->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_part = dri.id_part and dop.id_do_sales_order = dr.id_do_sales_order)');
			$this->db->join('tr_h3_md_sales_order_parts as sop', '(sop.id_sales_order = do.id_sales_order AND sop.id_part = dop.id_part)');
		}

		$this->db
		->join('ms_part as p', 'p.id_part = dop.id_part')
		->join('ms_kelompok_part as kp', 'p.kelompok_part = kp.id_kelompok_part', 'left')
		->where('dr.id', $this->input->get('id'));

		$data['do_sales_order_parts'] = $this->db->get()->result_array();

		$this->template($data);
	}

	public function get_plafon(){
		$plafon_yang_dipakai = $this->db
		->select('sum(do.total)')
		->from('tr_h3_md_sales_order as so')
		->join('tr_h3_md_do_sales_order as do', 'do.id_sales_order = so.id_sales_order')
		->where('so.id_dealer = d.id_dealer')
		->get_compiled_select();

		$data = $this->db
		->select('d.plafon_h3 as plafon')
		->select("ifnull(({$plafon_yang_dipakai}), 0) as plafon_yang_dipakai")
		->from('ms_dealer as d')
		->where('d.id_dealer', $this->input->get('id_dealer'))
		->get()->row();

		if ($data == null) {
			$data = [];
		}

		send_json($data);
	}

	public function view_check(){
		$data['mode']    = 'view_check';
		$data['set']     = "form";

		$id = $this->input->get('id');

		try {
			$data['do_revisi'] = $this->do_revisi->get_data($id);
			$data['items'] = $this->do_revisi_item->get_parts($id);
		} catch (Exception $e) {
			log_message('debug', $e);
		}
		
		$this->template($data);
	}

	public function cetak(){
		$id = $this->input->get('id');

		$this->db
		->set('sudah_print', 1)
		->where('id', $id)
		->update('tr_h3_md_do_revisi');

		$data = [];
		$revisi = $this->db
		->from('tr_h3_md_do_revisi as dr')
		->where('dr.id', $id)
		->get()->row_array();

		if($revisi == null) throw new Exception('DO revisi tidak ditemukan');

		$data['do_revisi'] = $revisi;

		$data['do_sales_order'] = $this->do_sales_order->get_do_sales_order($revisi['id_do_sales_order']);
		$parts = $this->db
		->select('do.id_do_sales_order')
		->select('dri.id_part')
		->select('SUM(dri.qty_revisi) as qty_supply')
		->select('p.nama_part')
		->select('IFNULL(p.qty_dus, 1) as qty_dus')
		->select('dop.harga_jual as harga')
		->select('dri.tipe_diskon_satuan_dealer')
		->select('dri.diskon_satuan_dealer')
		->select('dri.tipe_diskon_campaign')
		->select('dri.diskon_campaign')
		->select('dri.harga_setelah_diskon')
		->select('d.nama_dealer')
		->select('d.kode_dealer_md as kode_dealer')
		->select('d.alamat')
		->select('ifnull(kp.include_ppn, 0) as include_ppn')
		->select('dop.harga_jual')
		->select('dop.harga_beli')
		->select('so.po_type')
		->select('so.produk')
		->select('so.id_dealer')
		->select('do.sudah_revisi')
		->from('tr_h3_md_do_revisi as dr')
		->join('tr_h3_md_do_revisi_item as dri', 'dri.id_revisi = dr.id')
		->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = dr.id_do_sales_order')
		->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_part = dri.id_part and dop.id_do_sales_order = dr.id_do_sales_order)')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
		->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
		->join('ms_part as p', 'p.id_part = dri.id_part')
		->join('ms_kelompok_part as kp', 'p.kelompok_part = kp.id_kelompok_part', 'left')
		->where('dr.id', $id)
		->group_by('dri.id_part')
		->having('qty_supply > ', 0)
		->get()->result_array();

		$jumlah_dus = $this->do_sales_order_parts->get_jumlah_dus($parts);
		$parts = array_map(function($data) use ($jumlah_dus){
			$data['jumlah_dus'] = $jumlah_dus;
			$data['amount'] = $data['harga_setelah_diskon'] * $data['qty_supply'];

			return $data;
		}, $parts);

		$data['do_sales_order_parts'] = $parts;

		require_once APPPATH .'third_party/mpdf/mpdf.php';
        // Require composer autoload
        $mpdf = new Mpdf();
        // Write some HTML code:
        $html = $this->load->view('h3/h3_md_cetak_delivery_order', $data, true);
        $mpdf->WriteHTML($html);

        // Output a PDF file directly to the browser
        $mpdf->Output("{$data['do_sales_order']['id_do_sales_order']}.pdf", "I");
	}

	public function approve(){
		$id = $this->input->post('id');

		$this->db->trans_begin();
		try {
			$this->do_revisi->approve($id);
			$this->do_revisi->set_delivery_order_revisi_status($id, $this->input->post('total'));

			$this->db->trans_commit();

			send_json([
				'redirect_url' => base_url('h3/h3_md_do_revisi/view_check?id=' .$id)
			]);
		} catch (Exception $e) {
			$this->db->trans_rollback();

			log_message('error', $e);

			send_json([
				'message' => $e->getMessage()
			], 422);
		}
	}

	public function reject(){
		$this->db->trans_start();
		$do_revisi = $this->do_revisi->find($this->input->post('id'));
		$do_sales_order = $this->do_sales_order->find($do_revisi->id_do_sales_order, 'id_do_sales_order');
		$sales_order = $this->sales_order->find($do_sales_order->id_sales_order, 'id_sales_order');

		$this->do_revisi->update([
			'status' => 'Rejected',
			'rejected_at' => date('Y-m-d H:i:s'),
			'rejected_by' => $this->session->userdata('id_user'),
			'alasan_reject' => $this->input->post('alasan_reject')
		], [
			'id' => $do_revisi->id
		]);

		if($do_revisi->source == 'validasi_picking_list'){
			$this->db
			->select('plp.id_part')
			->select('plp.qty_disiapkan')
			->from('tr_h3_md_picking_list_parts as plp')
			->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = plp.id_picking_list')
			->where('pl.id_ref', $do_sales_order->id_do_sales_order);

			if($sales_order->kategori_po == 'KPB'){
				$this->db->select('plp.id_tipe_kendaraan');	
			}

			$parts = $this->db->get()->result_array();

			foreach ($parts as $part) {
				if($sales_order->kategori_po == 'KPB'){
					$this->order_parts_tracking->kurang_qty_pick($sales_order->id_ref, $part['id_part'], $part['qty_disiapkan'], $part['id_tipe_kendaraan']);
				}else{
					$this->order_parts_tracking->kurang_qty_pick($sales_order->id_ref, $part['id_part'], $part['qty_disiapkan']);
				}
				if($sales_order->id_rekap_purchase_order_dealer != null){
					$jumlah_item = $this->db
					->select('SUM( pop.kuantitas - ppd.qty_supply) as jumlah_item', false)
					->from('tr_h3_dealer_purchase_order_parts as pop')
					->join('tr_h3_md_pemenuhan_po_dari_dealer as ppd', '(ppd.po_id = pop.po_id AND ppd.id_part = pop.id_part)')
					->where('pop.po_id = po.po_id', null, false)
					->get_compiled_select();

					$this->db
					->select('po.po_id')
					->select('pop.id_part')
					->select('opt.qty_pick')
					->select('opt.id_tipe_kendaraan')
					->select("IFNULL(({$jumlah_item}), 0) as jumlah_item", false)
					->from('tr_h3_md_rekap_purchase_order_dealer_item as ri')
					->join('tr_h3_dealer_purchase_order as po', 'po.po_id = ri.id_referensi')
					->join('tr_h3_dealer_purchase_order_parts as pop', 'pop.po_id = ri.id_referensi')
					->join('tr_h3_dealer_order_parts_tracking as opt', '(opt.po_id = pop.po_id and opt.id_part = pop.id_part)')
					->where('ri.id_rekap', $sales_order->id_rekap_purchase_order_dealer)
					->where('pop.id_part', $part['id_part'])
					->where('opt.qty_pick > 0')
					->order_by('jumlah_item', 'asc')
					->order_by('po.created_at', 'desc');

					if($sales_order->kategori_po == 'KPB'){
						$this->db->select('pop.id_tipe_kendaraan');
						$this->db->where('pop.id_tipe_kendaraan', $part['id_tipe_kendaraan']);
					}

					$purchase_orders = $this->db->get()->result_array();

					$supply_untuk_dipecah = $part['qty_disiapkan'];
					foreach ($purchase_orders as $purchase_order) {
						if($purchase_order['qty_pick'] <= $supply_untuk_dipecah){
							if($sales_order->kategori_po == 'KPB'){
								$this->order_parts_tracking->kurang_qty_pick($purchase_order['po_id'], $purchase_order['id_part'], $purchase_order['qty_pick'], $part['id_tipe_kendaraan']);
							}else{
								$this->order_parts_tracking->kurang_qty_pick($purchase_order['po_id'], $purchase_order['id_part'], $purchase_order['qty_pick']);
							}
							$supply_untuk_dipecah -= $purchase_order['qty_pick'];
						}else if($purchase_order['qty_pick'] >= $supply_untuk_dipecah){
							if($sales_order->kategori_po == 'KPB'){
								$this->order_parts_tracking->kurang_qty_pick($purchase_order['po_id'], $purchase_order['id_part'], $supply_untuk_dipecah, $part['id_tipe_kendaraan']);
							}else{
								$this->order_parts_tracking->kurang_qty_pick($purchase_order['po_id'], $purchase_order['id_part'], $supply_untuk_dipecah);
							}
							break;
						}
	
						if($supply_untuk_dipecah == 0) break;
					}
				}
			}

			$this->picking_list->update([
				'status' => 'On Process',
				'end_pick' => null,
				'ready_for_scan' => 0
			], [
				'id_ref' => $do_revisi->id_do_sales_order
			]);
		}else if($do_revisi->source == 'scan_picking_list'){
			$this->picking_list->update([
				'selesai_scan' => 0,
				'end_scan' => null
			], [
				'id_ref' => $do_revisi->id_do_sales_order
			]);
		}
		$this->db->trans_complete();

		if($this->db->trans_status()){
			send_json([
				'message' => 'Berhasil reject DO Revisi',
				'payload' => $do_revisi,
				'redirect_url' => base_url('h3/h3_md_do_sales_order/detail?id=' . $do_revisi->id_do_sales_order)
			]);
		}else{
			send_json([
				'message' => 'Tidak berhasil reject DO Revisi'
			], 422);
		}
	}
}