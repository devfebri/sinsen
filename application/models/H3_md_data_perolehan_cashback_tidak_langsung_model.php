<?php

class H3_md_data_perolehan_cashback_tidak_langsung_model extends Honda_Model{

	public function __construct(){
		parent::__construct();

		$this->load->library('Mcarbon');
	}

	private function query_dealer($id_campaign){
		$dealers = $this->db
		->select('scd.id_dealer')
		->from('ms_h3_md_sales_campaign_dealers as scd')
		->where('scd.id_campaign', $id_campaign)
		->where('scd.diskualifikasi', 0)
		->get()->result_array();
		$dealers = array_map(function($row){
			return $row['id_dealer'];
		}, $dealers);

		$this->db
		->select('d.id_dealer')
		->select('d.nama_dealer')
		->select('d.kode_dealer_md')
		->select('
			case
				when (d.pkp = "Ya") then 10 
				else 0
			end as persentase_ppn
		', false)
		->select('
			case
				when (d.dealer_cb_ssp = "Ya") then 0 
				when (d.npwp IS NOT NULL AND d.npwp != "-") then 2 
				else 4
			end as persentase_pph23
		', false)
		->where('d.active', 1)
		->from('ms_dealer as d')
		;

		if(count($dealers) > 0){
			$this->db->where_in('d.id_dealer', $dealers);
		}
	}

    public function global_get($id_campaign){
		$sales_campaign = $this->db
		->select('sc.id')
		->select('sc.reward_cashback')
		->select('sc.jenis_item_cashback')
		->select('
			case
				when sc.start_date_cashback is not null then sc.start_date_cashback
				else sc.start_date
			end as start_date
		', false)
		->select('
			case
				when sc.end_date_cashback is not null then sc.end_date_cashback
				else sc.end_date
			end as end_date
		', false)
		->select('sc.satuan_rekapan_cashback')
		->from('ms_h3_md_sales_campaign as sc ')
		->where('sc.id', $id_campaign)
		->where('sc.jenis_reward_cashback', 1)
		->where('sc.reward_cashback', 'Tidak Langsung')
		->get()->row_array();

		$start_date = Mcarbon::parse($sales_campaign['start_date']);
		$start_date_start_of_month = $start_date->copy()->startOfMonth();
		$end_date = Mcarbon::parse($sales_campaign['end_date']);
		$end_date_start_of_month = $end_date->copy()->startOfMonth();

		$perbedaan_bulan = $start_date_start_of_month->diffInMonths($end_date_start_of_month) + 1;

        $this->query_dealer($id_campaign);

		$dealers = [];
		foreach ($this->db->get()->result_array() as $dealer) {
			$months = [];
			for ($add_month = 0; $add_month < $perbedaan_bulan; $add_month++) { 
				$month = [];
				$date_iteration = $start_date->copy()->addMonths($add_month);
				if($date_iteration->greaterThan($start_date)){
					$date_iteration->startOfMonth();
				}
				$date_iteration_end_of_month = $date_iteration->copy()->endOfMonth();
				$month['start_date'] = $start_date->greaterThan($date_iteration) ? $start_date->format('Y-m-d 00:00:01') : $date_iteration->format('Y-m-d 00:00:01');
				$month['end_date'] = $end_date->lessThan($date_iteration_end_of_month) ? $end_date->format('Y-m-d 23:59:59') : $date_iteration_end_of_month->format('Y-m-d 23:59:59');

				$this->db
				->select('scdc.id as id_detail')
				->select('scdc.id_campaign')
				->select('scdc.id_part')
				->select('scdc.id_kelompok_part')
				->select('IFNULL(p.qty_dus, 1) as qty_dus')
				->from('ms_h3_md_sales_campaign_detail_cashback as scdc')
				->join('ms_part as p', 'p.id_part = scdc.id_part', 'left')
				->where('scdc.id_campaign', $sales_campaign['id']);


				$sales_campaign_details = [];
				foreach ($this->db->get()->result_array() as $sales_campaign_detail) {
					$this->db
					->select('do.id_do_sales_order')
					->select('dop.id_part')
					->select('p.nama_part')
					->select('p.kelompok_part')
					->select('p.qty_dus')
					->select('dop.qty_supply as kuantitas')
					->select('ROUND( (dop.qty_supply / p.qty_dus), 2 ) as dus')
					->select('DATE_FORMAT(ps.tgl_faktur, "%d/%m/%Y") as tgl_faktur', false)
					->from('tr_h3_md_do_sales_order as do')
					->join('tr_h3_md_do_sales_order_parts as dop', 'dop.id_do_sales_order = do.id_do_sales_order')
					->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
					->join('tr_h3_md_picking_list as pl', 'pl.id_ref = do.id_do_sales_order')
					->join('tr_h3_md_packing_sheet as ps', 'ps.id_picking_list = pl.id_picking_list')
					->join('ms_part as p', 'p.id_part = dop.id_part')
					->where('so.gimmick', 0)
					->where('so.kategori_po !=', 'KPB')
					->group_start()
					->where('so.po_type', 'FIX')
					->or_where('so.po_type', 'REG')
					->group_end()
					->where('do.sudah_create_faktur', 1)
					->where('so.id_dealer', $dealer['id_dealer'])
					;

					if($sales_campaign['jenis_item_cashback'] == 'Per Kelompok Part'){
						$this->db->where('p.kelompok_part', $sales_campaign_detail['id_kelompok_part']);
					}else if($sales_campaign['jenis_item_cashback'] == 'Per Item Number'){
						$this->db->where('p.id_part', $sales_campaign_detail['id_part']);
					}

					$this->db->where("ps.tgl_faktur BETWEEN '{$month['start_date']}' AND '{$month['end_date']}'", null, false);

					$penjualan = $this->db->get()->result_array();
					// $sales_campaign_detail['penjualan'] = $penjualan;

					$total_kuantitas_penjualan = array_sum(
						array_map(function($row){
							return floatval($row['kuantitas']);
						}, $penjualan)
					);

					$total_dus_penjualan = array_sum(
						array_map(function($row){
							return floatval($row['dus']);
						}, $penjualan)
					);

					$sales_campaign_detail['total_kuantitas_penjualan'] = $total_kuantitas_penjualan;
					$sales_campaign_detail['total_dus_penjualan'] = $total_dus_penjualan;

					$sales_campaign_details[] = $sales_campaign_detail;
				}

				$month['sales_campaign_details'] = $sales_campaign_details;

				$total_penjualan_per_bulan = array_sum(
					array_map(function($row){
						return floatval($row['total_kuantitas_penjualan']);
					}, $sales_campaign_details)
				);

				$total_dus_penjualan_per_bulan = array_sum(
					array_map(function($row){
						return floatval($row['total_dus_penjualan']);
					}, $sales_campaign_details)
				);

				$month['total_penjualan_per_bulan'] = $total_penjualan_per_bulan;
				$month['total_dus_penjualan_per_bulan'] = $total_dus_penjualan_per_bulan;

				$months[] = $month;
			}

			$total_penjualan_per_dealer = array_sum(
				array_map(function($row){
					return floatval($row['total_penjualan_per_bulan']);
				}, $months)
			);

			$total_dus_penjualan_per_dealer = array_sum(
				array_map(function($row){
					return floatval($row['total_dus_penjualan_per_bulan']);
				}, $months)
			);

			$dealer['total_penjualan_per_dealer'] = $total_penjualan_per_dealer;
			$dealer['total_dus_penjualan_per_dealer'] = $total_dus_penjualan_per_dealer;

			$dealer['months'] = $months;

			$this->db
			->select('scdcg.id as id_global')
			->select('scdcg.id_campaign as id_campaign')
			->select('scdcg.qty')
			->select('scdcg.cashback')
			->select('scdcg.satuan')
			->from('ms_h3_md_sales_campaign_detail_cashback_global as scdcg')
			->where('scdcg.id_campaign', $id_campaign)
			->order_by('scdcg.qty', 'desc');
			;

			$global = [];
			foreach ($this->db->get()->result_array() as $row) {
				$count_cashback = 0;
				if($sales_campaign['satuan_rekapan_cashback'] == 'Dus'){
					$total_dus_penjualan_per_dealer -= intval($row['qty']);
				}else{
					while($total_penjualan_per_dealer >= $row['qty']){
						$count_cashback++;
						$total_penjualan_per_dealer -= intval($row['qty']);
					}
				}
				$row['count_cashback'] = $count_cashback;

				$global[] = $row;
			}
			$dealer['global'] = $global;
			$dealer['sisa_total_penjualan_per_dealer'] = $total_penjualan_per_dealer;
			$dealer['sisa_total_dus_penjualan_per_dealer'] = $total_dus_penjualan_per_dealer;

			$dealer['total_insentif'] = array_sum(
				array_map(function($row){
					return floatval($row['count_cashback']) * floatval($row['cashback']);
				}, $global)
			);

			$dealer['ppn'] = ($dealer['total_insentif'] * (floatval($dealer['persentase_ppn'])/100));
			$dealer['nilai_kw'] = $dealer['total_insentif'] + $dealer['ppn'];
			$dealer['pph_23'] = ($dealer['total_insentif'] * (floatval($dealer['persentase_pph23'])/100));
			$dealer['pph_21'] = 0;
			$dealer['total_bayar'] = $dealer['nilai_kw'] - $dealer['pph_23'] - $dealer['pph_21'];
			
			$dealers[] = $dealer;
		}

        return $dealers;
    }
}
