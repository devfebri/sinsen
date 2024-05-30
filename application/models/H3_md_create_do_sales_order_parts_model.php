<?php

class H3_md_create_do_sales_order_parts_model extends Honda_Model{

	public function get_sales_order_parts($id_sales_order){
		$this->load->model('h3_md_diskon_part_tertentu_model', 'diskon_part_tertentu');	
		$this->load->model('h3_md_diskon_oli_reguler_model', 'diskon_oli_reguler');
		$this->load->model('H3_md_stock_int_model', 'stock_int');

		$part_sudah_di_do =  $this->db
		->select('SUM(dop.qty_supply)')
		->from('tr_h3_md_do_sales_order as do')
		->join('tr_h3_md_do_sales_order_parts as dop', 'dop.id_do_sales_order = do.id_do_sales_order')
		->where('do.id_sales_order = sop.id_sales_order')
		->where('dop.id_part_int = sop.id_part_int')
		->group_start()
		->where('do.status !=', 'Rejected')
		->where('do.status !=', 'Canceled')
		->group_end()
		->get_compiled_select();

		$parts = $this->db
		->select('so.id_rekap_purchase_order_dealer')
		->select('so.id_ref')
		->select('so.gimmick')
		->select('so.id_dealer')
		->select('so.produk')
		->select('so.po_type')
		->select('sop.id_part')
		->select('p.id_part_int')
		->select('p.nama_part')
		->select('sop.id_tipe_kendaraan')
		->select('p.kelompok_part')
		->select('IFNULL(p.qty_dus, 1) as qty_dus')
		->select('sop.harga as harga_jual')
		->select('sop.qty_order')
		->select("sop.qty_pemenuhan - IFNULL(($part_sudah_di_do), 0) as qty_so")
		->select('sop.qty_suggest')
		->select('sop.qty_pemenuhan as qty_supply')
		->select('sop.qty_booking as part_booking')
		->select('IFNULL(sop.tipe_diskon, "") as tipe_diskon_satuan_dealer')
		->select('IFNULL(sop.diskon_value, 0) as diskon_satuan_dealer')
		->select('IFNULL(sop.tipe_diskon_campaign, "") as tipe_diskon_campaign')
		->select('IFNULL(sop.diskon_value_campaign, 0) as diskon_campaign')
		->select('sop.id_campaign_diskon')
		->select('sc.jenis_diskon_campaign')
		->from('tr_h3_md_sales_order_parts as sop')
		->join('tr_h3_md_sales_order as so', 'so.id_sales_order = sop.id_sales_order')
		->join('ms_part as p', 'p.id_part_int = sop.id_part_int')
		->join('ms_h3_md_sales_campaign as sc', 'sc.id = sop.id_campaign_diskon', 'left')
		->where('sop.id_sales_order', $id_sales_order)
		->having('qty_so > 0')
		->get()->result_array();

		$jumlah_dus = $this->get_jumlah_dus($parts, 'qty_supply');
		$parts = array_map(function($data) use ($jumlah_dus){
			if($data['gimmick'] == 0){
				// if($data['produk'] == 'Oil'){
				// 	$diskon_oli_reguler = $this->diskon_oli_reguler->get_diskon($data['id_part'], $data['id_dealer'], $jumlah_dus);
				// 	$data['tipe_diskon_satuan_dealer'] = $diskon_oli_reguler['tipe_diskon'];
				// 	$data['diskon_satuan_dealer'] = $diskon_oli_reguler['diskon_value'];
				// }else{
				// 	$diskon_part_tertentu = $this->diskon_part_tertentu->get_diskon($data['id_part'], $data['id_dealer'], $data['po_type'], $data['produk']);
				// 	$data['tipe_diskon_satuan_dealer'] = $diskon_part_tertentu['tipe_diskon'];
				// 	$data['diskon_satuan_dealer'] = $diskon_part_tertentu['diskon_value'];
				// }
	
				// $sales_campaign = $this->sales_campaign->get_diskon_sales_campaign($data['id_part'], $data['qty_supply']);
				// if($sales_campaign != null){
				// 	$data['tipe_diskon_campaign'] = $sales_campaign['tipe_diskon'];
				// 	$data['tipe_diskon_campaign'] = $sales_campaign['diskon_value'];
				// }
			}

			$data['qty_on_hand'] = $this->stock_int->qty_on_hand($data['id_part_int']);
			$id_purchase_order = []; 
			if($data['id_rekap_purchase_order_dealer'] != null){
				$this->db
				->select('rpodi.id_referensi')
				->from('tr_h3_md_rekap_purchase_order_dealer_item as rpodi')
				->where('rpodi.id_rekap', $data['id_rekap_purchase_order_dealer']);
				
				$id_purchase_order = array_map(function($row){
					return $row['id_referensi'];
				}, $this->db->get()->result_array());
			}else if(($data['po_type'] == 'HLO' || $data['po_type'] == 'URG')){
				$id_purchase_order[] = $data['id_ref'];
			}
			$data['qty_avs'] = $this->stock_int->qty_avs($data['id_part_int'], $id_purchase_order, false, $data['po_type'] == 'HLO');
			// $data['jumlah_dus'] = $jumlah_dus;

			if($data['qty_avs'] < 0){
				$data['qty_avs'] = 0;
			}

			if($data['qty_avs'] > $data['qty_so']){
				$data['qty_supply'] = $data['qty_so'];
			}
			if($data['qty_avs'] < $data['qty_so']){
				$data['qty_supply'] = $data['qty_avs'];
			}
			if($data['qty_suggest'] > 0){
				$data['qty_supply'] = $data['qty_suggest'];
			}

			unset($data['gimmick']);

			return $data;
		}, $parts);

		return $parts;
	}

	private function get_jumlah_dus($parts, $key_qty = 'qty_order'){
		$total_dus = 0;
		foreach ($parts as $part) {
			$total_dus += $part[$key_qty] / $part['qty_dus'];
		}

		return floor($total_dus);
	}
}
