<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_modal_view_picking_list extends Honda_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('H3_md_stock_model', 'stock');
		$this->load->model('h3_md_picking_list_model', 'picking_list');
		$this->load->model('h3_md_picking_list_parts_model', 'picking_list_parts');
		$this->load->model('H3_dealer_order_parts_tracking_model','order_parts_tracking');
	}
	
	public function get_view_picking_list_data(){
		$data = [];
		$data['picking'] = $this->db
		->select('pl.id_picking_list')
		->select('date_format(pl.created_at, "%d-%m-%Y") as tanggal_picking')
		->select('so.kategori_po')
		->select('so.po_type')
		->select('so.id_sales_order')
		->select('date_format(so.tanggal_order, "%d-%m-%Y") as tanggal_so')
		->select('date_format(do.created_at, "%d-%m-%Y") as tanggal_do')
		->select('k.nama_lengkap as nama_picker')
		->select('d.nama_dealer')
		->select('d.alamat')
		->select('do.id_do_sales_order')
		->select('pl.ready_for_scan')
		->select('date_format(pl.start_pick, "%d/%m/%Y %H:%i") as start_pick')
		->select('date_format(pl.end_pick, "%d/%m/%Y %H:%i") as end_pick')
		->select('pl.status')
		->from('tr_h3_md_picking_list as pl')
		->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
		->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
		->join('ms_karyawan as k', 'k.id_karyawan = pl.id_picker', 'left')
		->where('pl.id_picking_list', $this->input->get('id_picking_list'))
		->limit(1)
		->get()->row_array();

		$qty_avs = $this->stock->qty_avs('plp.id_part', [], true);

		$this->db
		->select('plp.id_part')
		->select('plp.id_lokasi_rak')
		->select('p.nama_part')
		->select('lr.kode_lokasi_rak')
		->select('dop.qty_supply as qty_do')
		->select("format( ifnull( ({$qty_avs}), 0 ), 0) as qty_avs", false)
		->select('plp.qty_supply as qty_picking')
		->select('plp.qty_disiapkan')
		->select('plp.recheck')
		->select('plp.serial_number')
		->from('tr_h3_md_picking_list_parts as plp')
		->join('ms_h3_md_lokasi_rak as lr', 'lr.id = plp.id_lokasi_rak')
		->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = plp.id_picking_list')
		->join('ms_part as p', 'p.id_part = plp.id_part')
		->where('plp.id_picking_list', $this->input->get('id_picking_list'));

		if($data['picking']['kategori_po'] == 'KPB'){
			$this->db->select('plp.id_tipe_kendaraan');
			$this->db->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_part = plp.id_part and pl.id_ref = dop.id_do_sales_order and plp.id_tipe_kendaraan = dop.id_tipe_kendaraan)');
		}else{
			$this->db->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_part = plp.id_part and pl.id_ref = dop.id_do_sales_order)');
		}

		$data['parts'] = $this->db->get()->result();

		send_json($data);
	}

	public function recheck_item(){
		$this->picking_list->update([
			'status' => 'Re-check',
			'end_pick' => null
		], $this->input->post(['id_picking_list']));
		$this->picking_list_parts->update(['recheck' => 1], $this->input->post(['id_part', 'id_picking_list']));

		$part = $this->db
		->select('plp.id_part')
		->select('p.nama_part')
		->select('dop.qty_supply as qty_do')
		->select('plp.qty_supply as qty_picking')
		->select('plp.recheck')
		->from('tr_h3_md_picking_list_parts as plp')
		->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = plp.id_picking_list')
		->join('tr_h3_md_do_sales_order_parts as dop', '(dop.id_part = plp.id_part and pl.id_ref = dop.id_do_sales_order)')
		->join('ms_part as p', 'p.id_part = plp.id_part')
		->where('plp.id_picking_list', $this->input->post('id_picking_list'))
		->where('plp.id_part', $this->input->post('id_part'))
		->limit(1)
		->get()->row_array();
		$part['qty_avs'] = $this->stock->qty_avs($part['id_part']);

		send_json([
			'part' => $part,
			'index' => $this->input->post('index')
		]);
	}

	public function recheck_picking(){
		$id_picking_list = $this->input->post('id_picking_list');

		$this->db->trans_start();
		$data = $this->db
		->select('d.nama_dealer')
		->select('pl.id_picking_list')
		->select('so.id_ref')
		->select('so.id_rekap_purchase_order_dealer')
		->from('tr_h3_md_picking_list as pl')
		->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
		->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
		->where('pl.id_picking_list', $id_picking_list)
		->get()->row_array();

		$update_data = ['recheck' => 1, 'Status' => 'Re-Check', 'end_pick' => null];
		$condition = [
			'id_picking_list' => $id_picking_list,
		];
		$this->picking_list->update($update_data, $condition);

		log_message('debug', sprintf('Recheck picking dengan data %s dan condition %s', $update_data, $condition));
		
		foreach ($this->input->post('parts') as $part) {
			if($part['recheck'] == 1){
				$condition = array_merge($this->input->post(['id_picking_list']), [
					'id_part' => $part['id_part'],
					'id_lokasi_rak' => $part['id_lokasi_rak']
				]);
				$this->picking_list_parts->update(['recheck' => 1], $condition);
			}

			$this->order_parts_tracking->kurang_qty_pick($data['id_ref'], $part['id_part'], $part['qty_disiapkan']);
			if($data['id_rekap_purchase_order_dealer'] != null){
				$purchase_orders = $this->db
				->select('po.po_id')
				->select('pop.id_part')
				->select('opt.qty_pick')
				->from('tr_h3_md_rekap_purchase_order_dealer_item as ri')
				->join('tr_h3_dealer_purchase_order as po', 'po.po_id = ri.id_referensi')
				->join('tr_h3_dealer_purchase_order_parts as pop', 'pop.po_id = ri.id_referensi')
				->join('tr_h3_dealer_order_parts_tracking as opt', '(opt.po_id = pop.po_id and opt.id_part = pop.id_part)')
				->where('ri.id_rekap', $data['id_rekap_purchase_order_dealer'])
				->where('pop.id_part', $part['id_part'])
				->order_by('po.created_at', 'desc')
				->get()->result_array();

				$supply_untuk_dipecah = $part['qty_disiapkan'];
				foreach ($purchase_orders as $purchase_order) {
					if($purchase_order['qty_pick'] <= $supply_untuk_dipecah){
						$this->order_parts_tracking->kurang_qty_pick($purchase_order['po_id'], $purchase_order['id_part'], $purchase_order['qty_pick']);
						$supply_untuk_dipecah -= $purchase_order['qty_pick'];
					}else if($purchase_order['qty_pick'] >= $supply_untuk_dipecah){
						$this->order_parts_tracking->kurang_qty_pick($purchase_order['po_id'], $purchase_order['id_part'], $supply_untuk_dipecah);
						break;
					}

					if($supply_untuk_dipecah == 0) break;
				}
			}
		}
		$this->db->trans_complete();

		if($this->db->trans_status()){
			send_json([
				'message' => 'Berhasil recheck'
			]);
		}else{
			send_json([
				'message' => 'Tidak berhasil recheck'
			], 422);
		}
	}
}