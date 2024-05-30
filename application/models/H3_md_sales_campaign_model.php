<?php

class H3_md_sales_campaign_model extends Honda_Model{
	
	protected $table = 'ms_h3_md_sales_campaign';

	public function __construct(){
		parent::__construct();

		$this->load->library('Mcarbon');
	}

	public function insert($data){
		$data['created_at'] = date('Y-m-d H:i:s', time());
		$data['created_by'] = $this->session->userdata('id_user');

		parent::insert($data);
	}

	public function get_diskon_sales_campaign($id_part, $qty_order){
		$diskon_campaign_item_sql = $this->diskon_campaign_item_sql($id_part, $qty_order);
		$diskon_campaign_global_sql = $this->diskon_campaign_global_sql($id_part, $qty_order);

		$diskon = $this->db->query("
			({$diskon_campaign_item_sql})
			UNION ALL
			({$diskon_campaign_global_sql})
			ORDER BY qty DESC, diskon_value DESC
		")->row_array();
		
		return $diskon;
	}

	public function diskon_campaign_item_sql($id_part, $qty_order){
		$now = date('Y-m-d', time());

		return  $this->db
			->select('sc.id')
			->select('sc.jenis_diskon_campaign')
			->select('sc.produk_program_diskon')
			->select("'{$id_part}' as id_part")
			->select('scdd.tipe_diskon')
			->select('scddi.satuan')
			->select('
			case
				when scddi.satuan = "Dus" then IFNULL(p.qty_dus, 1) * scddi.qty
				else scddi.qty
			end as qty
			', false)
			->select('scddi.diskon_value')
			->select("{$qty_order} as qty_order")
			->from('ms_part as p')
			->join('ms_kelompok_part as kp', 'kp.id_kelompok_part = p.kelompok_part')
			->join('ms_h3_md_sales_campaign_detail_diskon as scdd', '(scdd.id_part = p.id_part or scdd.id_kelompok_part = kp.id_kelompok_part)')
			->join('ms_h3_md_sales_campaign as sc', 'sc.id = scdd.id_campaign')
			->join('ms_h3_md_sales_campaign_detail_diskon_item as scddi', 'scddi.id_detail_diskon = scdd.id', 'left')
			->where('p.id_part', $id_part)
			->group_start()
			->where(" '{$now}' between sc.start_date_diskon and sc.end_date_diskon ")
			->or_where(" '{$now}' between sc.start_date and sc.end_date ")
			->group_end()
			->where('sc.status !=', 'Closed')
			->having('qty <=', $qty_order)
			->order_by('scddi.diskon_value', 'desc')
			->get_compiled_select()
			;
	}

	public function diskon_campaign_global_sql($id_part, $qty_order){
		$now = Mcarbon::now();

		return $this->db
			->select('sc.id')
			->select('sc.jenis_diskon_campaign')
			->select('sc.produk_program_diskon')
			->select("'{$id_part}' as id_part")
			->select('scdd.tipe_diskon')
			->select('scddg.satuan')
			->select('
			case
				when scddg.satuan = "Dus" then IFNULL(p.qty_dus, 1) * scddg.qty
				else scddg.qty
			end as qty
			', false)
			->select('scddg.diskon_value')
			->select("{$qty_order} as qty_order")
			->from('ms_part as p')
			->join('ms_kelompok_part as kp', 'kp.id_kelompok_part = p.kelompok_part')
			->join('ms_h3_md_sales_campaign_detail_diskon as scdd', '(scdd.id_part = p.id_part or scdd.id_kelompok_part = kp.id_kelompok_part)')
			->join('ms_h3_md_sales_campaign as sc', 'sc.id = scdd.id_campaign')
			->join('ms_h3_md_sales_campaign_detail_diskon_global as scddg', 'scddg.id_campaign = sc.id')
			->where('p.id_part', $id_part)
			->group_start()
			->where(sprintf('
				case
					when (sc.start_date_diskon IS NOT NULL AND sc.end_date_diskon IS NOT NULL) then "%s" between sc.start_date_diskon and sc.end_date_diskon
					else "%s" between sc.start_date and sc.end_date
				end
			', $now->toDateString(), $now->toDateString()), null, false)
			->group_end()
			->where('sc.status !=', 'Closed')
			->having('qty <=', $qty_order)
			->order_by('scddg.diskon_value', 'desc')
			->get_compiled_select()
			;
	}

	public function get_poin_sales_campaign($id_part, $qty_order){
		$part = $this->db
		->select('p.kelompok_part')
		->select('ifnull(p.qty_dus, 1) as qty_dus')
		->from('ms_part as p')
		->where('p.id_part', $id_part)
		->get()->row_array();

		$now = date('Y-m-d', time());

		$data = $this->db
		->select('sc.id')
		// ->select('scdp.id_part')
		// ->select('scdp.id_kelompok_part')
		// ->select('scdp.poin')
		// ->select("{$qty_order} as qty_order")
		// ->select("
		// case
		// 	when scdp.satuan = 'Dus' then FLOOR( ({$qty_order}/{$part['qty_dus']}) )
		// 	else {$qty_order}
		// end as kelipatan
		// ")
		->select("
		case
			when scdp.satuan = 'Dus' then FLOOR( ({$qty_order}/{$part['qty_dus']}) ) * scdp.poin
			else {$qty_order} * scdp.poin
		end as poin_yang_didapat
		")
		->from('ms_h3_md_sales_campaign as sc')
		->join('ms_h3_md_sales_campaign_detail_poin as scdp', 'scdp.id_campaign = sc.id')
		->group_start()
		->where("'{$now}' between sc.start_date and sc.end_date")
		->or_where("'{$now}' between sc.start_date_poin and sc.end_date_poin")
		->group_end()
		->where('sc.jenis_reward_poin', 1)
		->group_start()
		->where('scdp.id_part', $id_part)
		->or_where('scdp.id_kelompok_part', $part['kelompok_part'])
		->group_end()
		;

		return($this->db->get()->result_array());
	}

	public function gimmick_campaign_item($id_part, $qty_order, $total_dus_yang_didapat = 1, $id_dealer = null){
		$now = date('Y-m-d', time());

		$sales_campaign_detail = $this->db
		->select('scdg.id')
		->select('scdg.id_campaign')
		->select('scdg.id_part')
		->select('scdg.kelipatan_gimmick')
		->select("{$qty_order} as qty_order", false)
		->from('ms_part as p')
		->join('ms_kelompok_part as kp', 'kp.id_kelompok_part = p.kelompok_part')
		->join('ms_h3_md_sales_campaign_detail_gimmick as scdg', '(scdg.id_part = p.id_part or scdg.id_kelompok_part = kp.id_kelompok_part)')
		->join('ms_h3_md_sales_campaign as sc', 'sc.id = scdg.id_campaign')
		->where('p.id_part', $id_part)
		->where('sc.produk_program_gimmick', 'Per Item')
		->group_start()
			->where(" '{$now}' between sc.start_date_gimmick and sc.end_date_gimmick")
			->or_where("'{$now}' between sc.start_date and sc.end_date ")
		->group_end()
		->limit(1)
		->get()->row_array();

		if($sales_campaign_detail == null) return;

		$dealers = $this->db
		->select('scd.id_dealer')
		->from('ms_h3_md_sales_campaign_dealers as scd')
		->where('scd.id_campaign', $sales_campaign_detail['id_campaign'])
		->get()->result_array();
		$dealers = array_map(function($row){
			return $row['id_dealer'];
		}, $dealers);

		if(count($dealers) > 0){
			if(!in_array($id_dealer, $dealers)) return;
		}

		$this->db
		->select('scdgi.id')
		->select('scdgi.qty')
		->select('scdgi.satuan')
		->select('scdgi.id_part')
		->select('scdgi.hadiah_part')
		->select('scdgi.qty_hadiah')
		->select('scdgi.satuan_hadiah')
		->select('p.qty_dus as qty_dus_hadiah')
		->from('ms_h3_md_sales_campaign_detail_gimmick_item as scdgi')
		->join('ms_part as p', 'p.id_part = scdgi.id_part')
		->where('scdgi.id_detail_gimmick', $sales_campaign_detail['id'])
		->where('scdgi.hadiah_part', 1)
		->order_by('scdgi.qty', 'desc');

		if($sales_campaign_detail['kelipatan_gimmick'] == 1){
			$this->db->limit(1);
			$sc_gimmick_item = $this->db->get()->row_array();

			$sisa_qty_order = $qty_order;
			$sisa_qty_order_dus = $total_dus_yang_didapat;
			$hadiah_yang_didapat = 0;
			
			if($sc_gimmick_item['satuan'] == 'Dus'){
				while($sisa_qty_order_dus >= $sc_gimmick_item['qty']){
					$sisa_qty_order_dus -= $sc_gimmick_item['qty'];
					$hadiah_yang_didapat += $sc_gimmick_item['qty_hadiah'] == 'Dus' ? ($sc_gimmick_item['qty_hadiah'] * $sc_gimmick_item['qty_dus_hadiah']) : $sc_gimmick_item['qty_hadiah'];
				}
			}else{
				while($sisa_qty_order >= $sc_gimmick_item['qty']){
					$sisa_qty_order -= $sc_gimmick_item['qty'];
					$hadiah_yang_didapat += $sc_gimmick_item['qty_hadiah'] == 'Dus' ? ($sc_gimmick_item['qty_hadiah'] * $sc_gimmick_item['qty_dus_hadiah']) : $sc_gimmick_item['qty_hadiah'];
				}
			}
		}else{
			$this->db->where("
				case
					when scdgi.satuan = 'Dus' then {$total_dus_yang_didapat} >= scdgi.qty
					else {$qty_order} >= scdgi.qty
				end
			", null, false);

			$this->db->limit(1);
			$sc_gimmick_item = $this->db->get()->row_array();

			$hadiah_yang_didapat = $sc_gimmick_item['qty_hadiah'] == 'Dus' ? ($sc_gimmick_item['qty_hadiah'] * $sc_gimmick_item['qty_dus_hadiah']) : $sc_gimmick_item['qty_hadiah'];
		}

		$sales_campaign_detail['id_item'] = $sc_gimmick_item['id'];
		$sales_campaign_detail['id_part'] = $sc_gimmick_item['id_part'];
		$sales_campaign_detail['qty'] = $sc_gimmick_item['qty'];
		$sales_campaign_detail['satuan'] = $sc_gimmick_item['satuan'];
		$sales_campaign_detail['hadiah_part'] = $sc_gimmick_item['hadiah_part'];
		$sales_campaign_detail['qty_hadiah'] = $hadiah_yang_didapat;
		$sales_campaign_detail['satuan_hadiah'] = $sc_gimmick_item['satuan_hadiah'];
		unset($sales_campaign_detail['id']);
		unset($sales_campaign_detail['kelipatan_gimmick']);

		return $sales_campaign_detail;
	}

	public function gimmick_campaign_global($parts, $id_dealer = null){
		$data = [];
		foreach ($parts as $row) {
			$data[] = $this->db
			->select('p.id_part')
			->select('p.qty_dus')
			->select("{$row['qty_order']} as qty_order")
			->select("({$row['qty_order']} / p.qty_dus) as jumlah_dus")
			->from('ms_part as p')
			->join('ms_kelompok_part as kp', 'kp.id_kelompok_part = p.kelompok_part')
			->where('p.id_part', $row['id_part'])
			->get()->row_array();
		}

		$id_part = array_map(function($part){
			return $part['id_part'];
		}, $data);

		$sum_qty_order = array_sum(
			array_map(function($part){
				return $part['qty_order'];
			}, $data)
		);

		$sum_jumlah_dus = floor(
			array_sum(
				array_map(function($part){
					return $part['jumlah_dus'];
				}, $data)
			)
		);

		$now = date('Y-m-d', time());
		$sales_campaigns = $this->db
		->select('p.id_part')
		->select('sc.id as id_sales_campaign')
		->select('sc.kelipatan_gimmick')
		->from('ms_part as p')
		->join('ms_kelompok_part as kp', 'kp.id_kelompok_part = p.kelompok_part')
		->join('ms_h3_md_sales_campaign_detail_gimmick as scdg', '(scdg.id_part = p.id_part or scdg.id_kelompok_part = kp.id_kelompok_part)')
		->join('ms_h3_md_sales_campaign as sc', 'sc.id = scdg.id_campaign')
		->group_start()
			->where(" '{$now}' between sc.start_date_gimmick and sc.end_date_gimmick")
			->or_where("'{$now}' between sc.start_date and sc.end_date")
		->group_end()
		->where('sc.produk_program_gimmick', 'Global')
		->where_in('p.id_part', $id_part)
		->group_by('sc.id')
		->get()->result_array();

		$result = [];
		foreach ($sales_campaigns as $row) {
			$dealers = $this->db
			->select('scd.id_dealer')
			->from('ms_h3_md_sales_campaign_dealers as scd')
			->where('scd.id_campaign', $row['id_sales_campaign'])
			->get()->result_array();
			$dealers = array_map(function($row){
				return $row['id_dealer'];
			}, $dealers);

			if(count($dealers) > 0){
				if(!in_array($id_dealer, $dealers)) break;
			}

			$this->db
			->select('scdgg.id_campaign')
			->select("scdgg.id as id_item")
			->select("scdgg.qty")
			->select("scdgg.satuan")
			->select("{$sum_qty_order} as qty_order")
			->select('scdgg.hadiah_part')
			->select('scdgg.id_part')
			->select('
			case
			when scdgg.satuan_hadiah = "Dus" then (p.qty_dus * scdgg.qty_hadiah)
			else scdgg.qty_hadiah
			end as qty_hadiah')
			->select('scdgg.satuan_hadiah')
			->from('ms_h3_md_sales_campaign_detail_gimmick_global as scdgg')
			->join('ms_part as p', 'p.id_part = scdgg.id_part')
			->where('scdgg.id_campaign', $row['id_sales_campaign'])
			->order_by('scdgg.qty_hadiah', 'desc');

			$gimmick_globals = [];
			foreach ($this->db->get()->result_array() as $gimmick_global) {
				if($row['kelipatan_gimmick'] == 0){
					if($gimmick_global['satuan'] == 'Dus'){
						if($sum_jumlah_dus >= $gimmick_global['qty']){
							$gimmick_globals[] = $gimmick_global;
						}
					}else{
						if($sum_qty_order >= $gimmick_global['qty']){
							$gimmick_globals[] = $gimmick_global;
						}
					}
				}else if($row['kelipatan_gimmick'] == 1){
					if($gimmick_global['satuan'] == 'Dus'){
						$sisa_qty_order_dus = $sum_jumlah_dus;
						$count_gimmick = 0;
						while($sisa_qty_order_dus >= $gimmick_global['qty']){
							$count_gimmick++;
							$sisa_qty_order_dus -= $gimmick_global['qty'];
						}

						if($count_gimmick > 0){
							$gimmick_global['qty_hadiah'] = $gimmick_global['qty_hadiah'] * $count_gimmick;
							$gimmick_globals[] = $gimmick_global;
						}
					}else{
						$sisa_qty_order = $gimmick_global['qty_order'];
						$count_gimmick = 0;
						while($sisa_qty_order >= $gimmick_global['qty']){
							$count_gimmick++;
							$sisa_qty_order -= $gimmick_global['qty'];
						}

						if($count_gimmick > 0){
							$gimmick_global['qty_hadiah'] = $gimmick_global['qty_hadiah'] * $count_gimmick;
							$gimmick_globals[] = $gimmick_global;
						}
					}
				}
			}

			if(count($gimmick_globals) > 0){
				$result[] = $gimmick_globals[0];
			}
		}
		return $result;
	}

	public function get_gimmick_campaign($parts, $id_dealer = null){
		$data = [];
		$global = $this->gimmick_campaign_global($parts, $id_dealer);
		$items = [];

		$total_dus_yang_didapat = 0;
		$total_order = 0;
		foreach ($parts as $part) {
			$data_part = $this->db
			->select('p.id_part')
			->select('IFNULL(p.qty_dus, 1) AS qty_dus', false)
			->from('ms_part as p')
			->where('p.id_part', $part['id_part'])
			->limit(1)
			->get()->row_array();

			$total_dus_yang_didapat += $part['qty_order'] / $data_part['qty_dus'];
			$total_order += $part['qty_order'];
		}

		foreach ($parts as $part) {
			$gimmick = $this->sales_campaign->gimmick_campaign_item($part['id_part'], $total_order, $total_dus_yang_didapat, $id_dealer);
			if($gimmick != null) $items[] = $gimmick;
		}

		$data = array_merge($data, $global);
		$data = array_merge($data, $items);

		return $data;
	}

	public function cashback_campaign_item($id_part, $qty_order){
		$now = date('Y-m-d', time());
		$sales_campaigns = $this->db
		->select('sc.id as id_sales_campaign')
		->from('ms_part as p')
		->join('ms_kelompok_part as kp', 'kp.id_kelompok_part = p.kelompok_part')
		->join('ms_h3_md_sales_campaign_detail_cashback as scdc', '(scdc.id_part = p.id_part or scdc.id_kelompok_part = kp.id_kelompok_part)')
		->join('ms_h3_md_sales_campaign as sc', 'sc.id = scdc.id_campaign')
		->group_start()
			->where(" '{$now}' between sc.start_date_cashback and sc.end_date_cashback")
			->or_where("'{$now}' between sc.start_date and sc.end_date ")
		->group_end()
		->where('sc.produk_program_cashback', 'Per Item')
		->where_in('p.id_part', $id_part)
		->group_by('sc.id')
		->get()->result_array();

		$result = [];
		foreach ($sales_campaigns as $sales_campaign) {
			$this->db
			->select('sc.id as id_campaign')
			->select('scdci.id as id_item')
			->select('p.id_part')
			->select("{$qty_order} as qty_order")
			->select("
				case
					when scdci.satuan = 'Dus' then (scdci.qty * p.qty_dus)
					else scdci.qty
				end as qty
			")
			->select('scdci.cashback')
			->select('sc.reward_cashback')
			->from('ms_part as p')
			->join('ms_kelompok_part as kp', 'kp.id_kelompok_part = p.kelompok_part')
			->join('ms_h3_md_sales_campaign_detail_cashback as scdc', '(scdc.id_part = p.id_part or scdc.id_kelompok_part = kp.id_kelompok_part)')
			->join('ms_h3_md_sales_campaign as sc', 'sc.id = scdc.id_campaign')
			->join('ms_h3_md_sales_campaign_detail_cashback_item as scdci', 'scdci.id_detail_cashback = scdc.id')
			->where('p.id_part', $id_part)
			->where('sc.id', $sales_campaign['id_sales_campaign'])
			->order_by('scdci.cashback', 'desc')
			->having('qty_order >= qty')
			->limit(1);

			$result[] = $this->db->get()->row_array();
		}
		return $result;
	}

	public function cashback_campaign_global($parts){
		$data = [];
		foreach ($parts as $row) {
			$data[] = $this->db
			->select('p.id_part')
			->select('p.kelompok_part')
			->select('p.qty_dus')
			->select("{$row['qty_order']} as qty_order")
			->select("({$row['qty_order']} / p.qty_dus) as jumlah_dus")
			->from('ms_part as p')
			->join('ms_kelompok_part as kp', 'kp.id_kelompok_part = p.kelompok_part')
			->where('p.id_part', $row['id_part'])
			->get()->row_array();
		}

		$id_part = array_map(function($part){
			return $part['id_part'];
		}, $data);

		$now = date('Y-m-d', time());
		$sales_campaigns = $this->db
		->select('sc.id as id_sales_campaign')
		->from('ms_part as p')
		->join('ms_kelompok_part as kp', 'kp.id_kelompok_part = p.kelompok_part')
		->join('ms_h3_md_sales_campaign_detail_cashback as scdc', '(scdc.id_part = p.id_part or scdc.id_kelompok_part = kp.id_kelompok_part)')
		->join('ms_h3_md_sales_campaign as sc', 'sc.id = scdc.id_campaign')
		->group_start()
			->where(" '{$now}' between sc.start_date_cashback and sc.end_date_cashback")
			->or_where("'{$now}' between sc.start_date and sc.end_date ")
		->group_end()
		->where('sc.produk_program_cashback', 'Global')
		->where_in('p.id_part', $id_part)
		->group_by('sc.id')
		->get()->result_array();

		$result = [];
		foreach ($sales_campaigns as $row) {
			$parts_cashback = $this->db
			->select('scdc.id_part')
			->select('scdc.id_kelompok_part')
			->from('ms_h3_md_sales_campaign_detail_cashback as scdc')
			->where('scdc.id_campaign', $row['id_sales_campaign'])
			->get()->result_array();

			$filtered_data = array_filter($data, function($row) use ($parts_cashback) {
				foreach ($parts_cashback as $part_cashback) {
					if(
						($part_cashback['id_part'] == $row['id_part']) ||
						($part_cashback['id_kelompok_part'] == $row['kelompok_part'])
					){
						return true;
					}
				}
				return false;
			}, ARRAY_FILTER_USE_BOTH);

			$sum_qty_order = array_sum(
				array_map(function($part){
					return $part['qty_order'];
				}, $filtered_data)
			);
	
			$sum_jumlah_dus = floor(
				array_sum(
					array_map(function($part){
						return $part['jumlah_dus'];
					}, $filtered_data)
				)
			);

			$this->db
			->select('scdcg.id_campaign')
			->select('sc.reward_cashback')
			->select('scdcg.id as id_item')
			->select('scdcg.nama_paket')
			->select("{$sum_qty_order} as qty_order")
			->select('scdcg.qty')
			->select('scdcg.satuan')
			->select('scdcg.cashback')
			->from('ms_h3_md_sales_campaign_detail_cashback_global as scdcg')
			->join('ms_h3_md_sales_campaign as sc', 'sc.id = scdcg.id_campaign')
			->where('scdcg.id_campaign', $row['id_sales_campaign'])
			->order_by('scdcg.cashback', 'desc');

			$cashback_globals = [];
			foreach ($this->db->get()->result_array() as $cashback_global) {
				if($cashback_global['satuan'] == 'Dus'){
					if($sum_jumlah_dus >= $cashback_global['qty']){
						$cashback_globals[] = $cashback_global;
					}
				}else{
					if($sum_qty_order >= $cashback_global['qty']){
						$cashback_globals[] = $cashback_global;
					}
				}
			}

			if(count($cashback_globals) > 0){
				$result[] = $cashback_globals[0];
			}
		}

		return $result;
	}

	public function get_cashback_campaign($parts){
		$data = [];
		$global = $this->cashback_campaign_global($parts);
		$items = [];
		foreach ($parts as $part) {
			$cashbacks = $this->cashback_campaign_item($part['id_part'], $part['qty_order']);
			if(count($cashbacks) > 0){
				foreach ($cashbacks as $cashback) {
					if($cashback != null){
						$items[] = $cashback;
					}
				}
			}
		}

		$data = array_merge($data, $global);
		$data = array_merge($data, $items);

		return $data;
	}

	public function get_campaign_poin($parts){
		$now = date('Y-m-d');

		$sales_campaigns = $this->db
		->select('sc.id')
		->select('sc.kode_campaign')
		->select('sc.nama')
		->from('ms_h3_md_sales_campaign as sc')
		->where('sc.jenis_reward_poin', 1)
		->where('sc.reward_poin', 'Langsung')
		->where("
			case	
				when sc.start_date_poin IS NOT NULL then '{$now}' BETWEEN sc.start_date_poin AND sc.end_date_poin
				else '{$now}' BETWEEN sc.start_date AND sc.end_date
			end 
		", null, false);
		
		$sales_campaigns = [];
		foreach ($this->db->get()->result_array() as $sales_campaign) {
			
			$data_parts = [];
			foreach ($parts as $part) {
				$row = $this->db
				->select('scdp.*')
				->select("
					case
						when scdp.satuan = 'Dus' then ( FLOOR(
							({$part['qty_order']} / IFNULL(p.qty_dus, 1))
						) * scdp.poin )
						else ({$part['qty_order']} * scdp.poin)
					end as poin_yang_didapat
				")
				->from('ms_h3_md_sales_campaign_detail_poin as scdp')
				->join('ms_part as p', 'p.id_part = scdp.id_part')
				->group_start()
				->where('scdp.id_part', $part['id_part'])
				->or_where('scdp.id_kelompok_part = p.kelompok_part', null, false)
				->group_end()
				->where('scdp.id_campaign', $sales_campaign['id'])
				->get()->row_array();

				$data_parts[] = $row;
			}

			$poin_yang_didapat = array_sum(
				array_map(function($row){
					return $row['poin_yang_didapat'];
				}, $data_parts)
			);
			$sales_campaign['poin_yang_didapat'] = $poin_yang_didapat;


			$this->db
			->select('scdh.*')
			->select('0 as count_hadiah')
			->from('ms_h3_md_sales_campaign_detail_hadiah as scdh')
			->where('scdh.id_campaign', $sales_campaign['id'])
			->where('scdh.voucher_rupiah', 1)
			->order_by('scdh.jumlah_poin', 'desc');

			$sisa_poin = $poin_yang_didapat;
			$hadiah = [];
			foreach ($this->db->get()->result_array() as $row) {
				$count_hadiah = 0;
				while($sisa_poin > $row['jumlah_poin']){
					$count_hadiah++;
					$sisa_poin -= $row['jumlah_poin'];
				}
				$row['count_hadiah'] = $count_hadiah;
				$hadiah[] = $row;
			}

			$nilai_insentif = array_sum(
				array_map(function($row){
					return $row['nama_hadiah'] * $row['count_hadiah'];
				}, $hadiah)
			);

			$sales_campaign['nilai_insentif'] = $nilai_insentif;
			$sales_campaign['hadiah'] = $hadiah;
			$sales_campaign['sisa_poin'] = $sisa_poin;

			$sales_campaigns[] = $sales_campaign;
		}

		return $sales_campaigns;
	}
}
