<?php

class H3_md_data_perolehan_gimmick_tidak_langsung_model extends Honda_Model
{

	private function query_dealer($id_campaign)
	{
		$dealers = $this->db
			->select('scd.id_dealer')
			->from('ms_h3_md_sales_campaign_dealers as scd')
			->where('scd.id_campaign', $id_campaign)
			->where('scd.diskualifikasi', 0)
			->get()->result_array();
		$dealers = array_map(function ($row) {
			return $row['id_dealer'];
		}, $dealers);

		$this->db
			->select('d.id_dealer')
			->select('d.nama_dealer')
			->select('d.kode_dealer_md')
			->from('ms_dealer as d')
			->where('d.active', 1);

		if (count($dealers) > 0) {
			$this->db->where_in('d.id_dealer', $dealers);
		}
	}

	public function global_get($id_campaign)
	{
		$sales_campaign = $this->db
			->select('sc.id')
			->select('sc.reward_gimmick')
			->select('sc.jenis_item_gimmick')
			->select('sc.start_date')
			->select('sc.end_date')
			->select('sc.start_date_gimmick')
			->select('sc.end_date_gimmick')
			->select('sc.kelipatan_gimmick')
			->select('sc.satuan_rekapan_gimmick')
			->from('ms_h3_md_sales_campaign as sc ')
			->where('sc.id', $id_campaign)
			->where('sc.reward_gimmick', 'Tidak Langsung')
			->get()->row_array();

		$this->query_dealer($id_campaign);

		$dealers = [];
		foreach ($this->db->get()->result_array() as $dealer) {
			$this->db
				->select('scdg.id as id_detail')
				->select('scdg.id_campaign')
				->select('scdg.id_part')
				->select('IFNULL(p.qty_dus, 1) as qty_dus', false)
				->select('scdg.id_kelompok_part')
				->from('ms_h3_md_sales_campaign_detail_gimmick as scdg')
				->join('ms_part as p', 'p.id_part = scdg.id_part', 'left')
				->where('scdg.id_campaign', $sales_campaign['id']);

			$sales_campaign_details = [];
			foreach ($this->db->get()->result_array() as $sales_campaign_detail) {
				$this->db
					->select('do.id_do_sales_order')
					->select('dop.id_part')
					->select('p.nama_part')
					->select('p.kelompok_part')
					->select('p.qty_dus')
					->select('dop.qty_supply as kuantitas')
					->select('(dop.qty_supply / p.qty_dus) as dus')
					->from('tr_h3_md_do_sales_order as do')
					->join('tr_h3_md_do_sales_order_parts as dop', 'dop.id_do_sales_order = do.id_do_sales_order')
					->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
					->join('tr_h3_md_picking_list as pl', 'pl.id_ref = do.id_do_sales_order')
					->join('tr_h3_md_packing_sheet as ps', 'ps.id_picking_list = pl.id_picking_list')
					->join('ms_part as p', 'p.id_part = dop.id_part')
					->where('so.id_dealer', $dealer['id_dealer'])
					->where('so.gimmick', 0);

				if ($sales_campaign['jenis_item_gimmick'] == 'Per Kelompok Part') {
					$this->db->where('p.kelompok_part', $sales_campaign_detail['id_kelompok_part']);
				} else if ($sales_campaign['jenis_item_gimmick'] == 'Per Item Number') {
					$this->db->where('p.id_part', $sales_campaign_detail['id_part']);
				}

				if ($sales_campaign['start_date_gimmick'] != null and $sales_campaign['end_date_gimmick'] != null) {
					$this->db->where("ps.tgl_faktur BETWEEN '{$sales_campaign['start_date_gimmick']} 00:00:01' AND '{$sales_campaign['end_date_gimmick']} 23:59:59'", null, false);
				} else {
					$this->db->where("ps.tgl_faktur BETWEEN '{$sales_campaign['start_date']} 00:00:01' AND '{$sales_campaign['end_date']} 23:59:59'", null, false);
				}

				$penjualan = $this->db->get()->result_array();

				// $sales_campaign_detail['penjualan'] = $penjualan;

				$sales_campaign_detail['jumlah_kuantitas_yang_tercapai'] = array_sum(
					array_map(function ($row) {
						return floatval($row['kuantitas']);
					}, $penjualan)
				);

				$sales_campaign_detail['jumlah_dus_yang_tercapai'] = array_sum(
					array_map(function ($row) {
						return floatval($row['dus']);
					}, $penjualan)
				);

				$sales_campaign_details[] = $sales_campaign_detail;
			}

			$dealer['sales_campaign_details'] = $sales_campaign_details;
			$dealer['total_pembelian'] = array_sum(
				array_map(function ($row) {
					return $row['jumlah_kuantitas_yang_tercapai'];
				}, $sales_campaign_details)
			);
			$dealer['total_pembelian_dus'] = array_sum(
				array_map(function ($row) {
					return $row['jumlah_dus_yang_tercapai'];
				}, $sales_campaign_details)
			);
			$dealer['id_campaign'] = $sales_campaign['id'];

			$this->db
				->from('ms_h3_md_sales_campaign_detail_gimmick_global as scdgg')
				->where('scdgg.id_campaign', $sales_campaign['id'])
				->order_by('scdgg.qty', 'desc');

			$sales_campaign_gimmick_globals = [];
			$total_pembelian = $dealer['total_pembelian'];
			$total_pembelian_dus = $dealer['total_pembelian_dus'];

			if ($sales_campaign['kelipatan_gimmick'] == 0) {
				if ($sales_campaign['satuan_rekapan_gimmick'] == 'Dus') {
					$this->db->where('scdgg.qty <= ', $total_pembelian_dus);
				} else {
					$this->db->where('scdgg.qty <= ', $total_pembelian);
				}
			}

			foreach ($this->db->get()->result_array() as $sales_campaign_gimmick_global) {
				$row = [];
				$row['id_gimmick_global'] = $sales_campaign_gimmick_global['id'];

				if ($sales_campaign['kelipatan_gimmick'] == 1) {
					$count_gimmick = 0;
					if ($sales_campaign_gimmick_global['satuan'] == 'Dus') {
						while ($total_pembelian_dus >= $sales_campaign_gimmick_global['qty']) {
							$count_gimmick++;
							$total_pembelian_dus -= floatval($sales_campaign_gimmick_global['qty']);
						}
					} else {
						while ($total_pembelian >= $sales_campaign_gimmick_global['qty']) {
							$count_gimmick++;
							$total_pembelian -= floatval($sales_campaign_gimmick_global['qty']);
						}
					}

					$row['count_gimmick'] = $count_gimmick;
					$sales_campaign_gimmick_globals[] = $row;
				} elseif ($sales_campaign['kelipatan_gimmick'] == 0) {
					$row['count_gimmick'] = 1;
					if ($sales_campaign_gimmick_global['satuan'] == 'Dus') {
						$total_pembelian_dus -= floatval($sales_campaign_gimmick_global['qty']);
					} else {
						$total_pembelian -= floatval($sales_campaign_gimmick_global['qty']);
					}
					$sales_campaign_gimmick_globals[] = $row;
					break;
				}
			}

			$dealer['total_pembelian_dus_sisa'] = $total_pembelian_dus;
			$dealer['total_pembelian_sisa'] = $total_pembelian;
			$dealer['sales_campaign_gimmick_globals'] = $sales_campaign_gimmick_globals;

			$dealers[] = $dealer;
		}

		return $dealers;
	}

	public function per_item_get($id_campaign)
	{
		$sales_campaign = $this->db
			->select('sc.id')
			->select('sc.reward_gimmick')
			->select('sc.jenis_item_gimmick')
			->select('sc.start_date')
			->select('sc.end_date')
			->select('sc.start_date_gimmick')
			->select('sc.end_date_gimmick')
			->select('sc.satuan_rekapan_gimmick')
			->from('ms_h3_md_sales_campaign as sc ')
			->where('sc.id', $id_campaign)
			->where('sc.reward_gimmick', 'Tidak Langsung')
			->get()->row_array();

		$this->query_dealer($id_campaign);

		$dealers = [];
		foreach ($this->db->get()->result_array() as $dealer) {
			$this->db
				->select('scdg.id as id_detail')
				->select('scdg.id_campaign')
				->select('scdg.id_part')
				->select('scdg.id_kelompok_part')
				->select('scdg.kelipatan_gimmick')
				->select('IFNULL(p.qty_dus, 1) as qty_dus')
				->from('ms_h3_md_sales_campaign_detail_gimmick as scdg')
				->join('ms_part as p', 'p.id_part = scdg.id_part', 'left')
				->where('scdg.id_campaign', $sales_campaign['id']);

			$sales_campaign_details = [];
			foreach ($this->db->get()->result_array() as $sales_campaign_detail) {
				$this->db
					->select('do.id_do_sales_order')
					->select('dop.id_part')
					->select('p.nama_part')
					->select('p.kelompok_part')
					->select('p.qty_dus')
					->select('dop.qty_supply as kuantitas')
					->select('(dop.qty_supply / p.qty_dus) as dus')
					->select('DATE_FORMAT(ps.tgl_faktur, "%d/%m/%Y") as tgl_faktur', false)
					->from('tr_h3_md_do_sales_order as do')
					->join('tr_h3_md_do_sales_order_parts as dop', 'dop.id_do_sales_order = do.id_do_sales_order')
					->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
					->join('tr_h3_md_picking_list as pl', 'pl.id_ref = do.id_do_sales_order')
					->join('tr_h3_md_packing_sheet as ps', 'ps.id_picking_list = pl.id_picking_list')
					->join('ms_part as p', 'p.id_part = dop.id_part')
					->where('so.id_dealer', $dealer['id_dealer']);

				if ($sales_campaign['jenis_item_gimmick'] == 'Per Kelompok Part') {
					$this->db->where('p.kelompok_part', $sales_campaign_detail['id_kelompok_part']);
				} else if ($sales_campaign['jenis_item_gimmick'] == 'Per Item Number') {
					$this->db->where('p.id_part', $sales_campaign_detail['id_part']);
				}

				if ($sales_campaign['start_date_gimmick'] != null and $sales_campaign['end_date_gimmick'] != null) {
					$this->db->where("ps.tgl_faktur BETWEEN '{$sales_campaign['start_date_gimmick']} 00:00:01' AND '{$sales_campaign['end_date_gimmick']} 23:59:59'", null, false);
				} else {
					$this->db->where("ps.tgl_faktur BETWEEN '{$sales_campaign['start_date']} 00:00:01' AND '{$sales_campaign['end_date']} 23:59:59'", null, false);
				}

				$penjualan = $this->db->get()->result_array();
				// $sales_campaign_detail['penjualan'] = $penjualan;

				// $random_number = rand(5, 480);
				// $sales_campaign_detail['jumlah_kuantitas_yang_tercapai'] = $random_number;
				// $sales_campaign_detail['jumlah_dus_yang_tercapai'] = $random_number;

				$sales_campaign_detail['jumlah_kuantitas_yang_tercapai'] = array_sum(
					array_map(function ($row) {
						return floatval($row['kuantitas']);
					}, $penjualan)
				);

				$sales_campaign_detail['jumlah_dus_yang_tercapai'] = array_sum(
					array_map(function ($row) {
						return floatval($row['dus']);
					}, $penjualan)
				);

				$this->db
					->select('sc_gimmick_item.id as id_gimmick_item')
					->select('sc_gimmick_item.qty')
					->select('sc_gimmick_item.satuan')
					->select('sc_gimmick_item.hadiah_part')
					->select('sc_gimmick_item.nama_hadiah')
					->select('sc_gimmick_item.qty_hadiah')
					->select('sc_gimmick_item.satuan_hadiah')
					->from('ms_h3_md_sales_campaign_detail_gimmick_item as sc_gimmick_item')
					->where('sc_gimmick_item.id_detail_gimmick', $sales_campaign_detail['id_detail'])
					->order_by('sc_gimmick_item.qty', 'desc');

				$jumlah_kuantitas_yang_tercapai_sisa = $sales_campaign_detail['jumlah_kuantitas_yang_tercapai'];
				$jumlah_dus_yang_tercapai_sisa = $sales_campaign_detail['jumlah_dus_yang_tercapai'];

				if ($sales_campaign_detail['kelipatan_gimmick'] == 0) {
					if ($sales_campaign['satuan_rekapan_gimmick'] == 'Dus') {
						$this->db->where('sc_gimmick_item.qty <= ', $jumlah_dus_yang_tercapai_sisa);
					} else {
						$this->db->where('sc_gimmick_item.qty <= ', $jumlah_kuantitas_yang_tercapai_sisa);
					}
				}

				$sc_gimmick_items = [];
				$loop_gimmick_items = 0;
				foreach ($this->db->get()->result_array() as $sc_gimmick_item) {
					$qty = intval($sc_gimmick_item['qty']);
					if ($sales_campaign_detail['kelipatan_gimmick'] == 1) {
						$count_gimmick = 0;
						if ($sc_gimmick_item['satuan'] == 'Dus') {
							while ($jumlah_dus_yang_tercapai_sisa >= $qty) {
								$count_gimmick++;
								$jumlah_dus_yang_tercapai_sisa -= $qty;
								$jumlah_kuantitas_yang_tercapai_sisa -= floatval($sales_campaign_detail['qty_dus']);
							}
						} else if ($sc_gimmick_item['satuan'] == 'Pcs') {
							while ($jumlah_kuantitas_yang_tercapai_sisa >= $qty) {
								$count_gimmick++;
								$jumlah_kuantitas_yang_tercapai_sisa -= $qty;
							}
						}

						$sc_gimmick_item['count_gimmick'] = $count_gimmick;
						$sc_gimmick_items[] = $sc_gimmick_item;
					} else if ($sales_campaign_detail['kelipatan_gimmick'] == 0) {
						if ($loop_gimmick_items == 0) {
							$sc_gimmick_item['count_gimmick'] = 1;
							if ($sc_gimmick_item['satuan'] == 'Dus') {
								$jumlah_dus_yang_tercapai_sisa -= floatval($qty);
							} else {
								$jumlah_kuantitas_yang_tercapai_sisa -= floatval($qty);
							}
						} else {
							$sc_gimmick_item['count_gimmick'] = 0;
						}

						$sc_gimmick_items[] = $sc_gimmick_item;
						$loop_gimmick_items++;
					}
				}

				$sales_campaign_detail['sc_gimmick_items'] = $sc_gimmick_items;

				$sales_campaign_detail['jumlah_kuantitas_yang_tercapai_sisa'] = $jumlah_kuantitas_yang_tercapai_sisa;
				$sales_campaign_detail['jumlah_dus_yang_tercapai_sisa'] = $jumlah_dus_yang_tercapai_sisa;

				$sales_campaign_details[] = $sales_campaign_detail;
			}

			$dealer['id_campaign'] = $sales_campaign['id'];
			$dealer['sales_campaign_details'] = $sales_campaign_details;

			$dealers[] = $dealer;
		}

		return $dealers;
	}
}
