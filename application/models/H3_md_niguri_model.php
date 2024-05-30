<?php

class H3_md_niguri_model extends Honda_Model{

    protected $table = 'tr_h3_md_niguri';

    public function __construct(){
        parent::__construct();

        $this->load->library('Mcarbon');

        $this->load->model('H3_md_niguri_header_model', 'niguri_header');
		$this->load->model('H3_md_stock_int_model', 'stock_int');
		$this->load->model('H3_md_stock_model', 'stock');
    }

    public function create_item($id_niguri_header, $random = false){
        $niguri = (array) $this->niguri_header->find($id_niguri_header);

        $data = $this->get_niguri_data($niguri['tanggal_generate'], null, $niguri['type_niguri'], $random);
		foreach ($data as $row) {
			$row['id_niguri_header'] = $id_niguri_header;
			$row['qty_avs'] = $this->stock_int->qty_avs($row['id_part_int']);
			$row['average'] = ( intval($row['pertama']) + intval($row['kedua']) + intval($row['ketiga']) + intval($row['keempat']) + intval($row['kelima']) + intval($row['keenam']) ) / 6;
			if($row['average'] > 0){
				$row['s_l'] = floatval($row['qty_avs']) / floatval($row['average']);
			}else{
				$row['s_l'] = 0;
			}
			$row['qty_intransit'] = $this->stock_int->qty_intransit($row['id_part_int']);
			$row['qty_suggest'] = $this->perhitungan_qty_suggest($row['average'], $row['qty_avs'], $row['fix_order_n_1']);

			$row['updated_at'] = Mcarbon::now()->toDateTimeString();
			$row['updated_by'] = $this->session->userdata('id_user');

			$this->niguri->insert($row);
		}
    }

    public function perbarui_item($id_niguri_header, $random = false){
        $niguri = (array) $this->niguri_header->find($id_niguri_header);

        $kode_parts_yang_direquest_dealer = [];
		$kode_parts_yang_dibeli = [];
		if($niguri['type_niguri'] == 'REG'){
			$periode_awal = Mcarbon::parse($niguri['tanggal_generate'])->subMonths(6);
			$periode_akhir = Mcarbon::parse($niguri['tanggal_generate']);

			$kode_parts_yang_direquest_dealer = $this->kode_parts_yang_direquest_dealer($periode_awal->toDateString(), $periode_akhir->toDateString());
			$kode_parts_yang_dibeli = $this->kode_parts_yang_dibeli($periode_awal->toDateString(), $periode_akhir->toDateString());
		}

        $this->db
		->select('n.id')
		->select('p.id_part_int')
		->select('p.id_part')
		->select('n.average')
		->select('n.fix_order_n_1')
		->select('n.pertama')
		->select('n.kedua')
		->select('n.ketiga')
		->select('n.keempat')
		->select('n.kelima')
		->select('n.keenam')
		->from('ms_part as p')
		->join('tr_h3_md_niguri as n', "(n.id_part_int = p.id_part_int and n.id_niguri_header = {$id_niguri_header})", 'left');

		// $this->db->limit(1000);

		if($niguri['type_niguri'] == 'FIX'){
			$this->db->where('p.fix', 1);
		}else if($niguri['type_niguri'] == 'REG'){
			$this->db->group_start();
			$this->db->where('p.fix', 1);

			if(count($kode_parts_yang_direquest_dealer) > 0){
				$this->db->or_where_in('p.id_part_int', $kode_parts_yang_direquest_dealer);
			}

			if(count($kode_parts_yang_dibeli) > 0){
				$this->db->or_where_in('p.id_part_int', $kode_parts_yang_dibeli);
			}
			$this->db->group_end();
		}

		foreach ($this->db->get()->result_array() as $part) {
			if($part['id'] == null){
				$row = $this->get_niguri_data($niguri['tanggal_generate'], $part['id_part_int'], $niguri['type_niguri'], $random);
				$row['id_niguri_header'] = $id_niguri_header;
				$row['qty_avs'] = $this->stock_int->qty_avs($row['id_part_int']);
				$row['average'] = ( intval($row['pertama']) + intval($row['kedua']) + intval($row['ketiga']) + intval($row['keempat']) + intval($row['kelima']) + intval($row['keenam']) ) / 6;
				if($row['average'] > 0){
					$row['s_l'] = floatval($row['qty_avs']) / floatval($row['average']);
				}else{
					$row['s_l'] = 0;
				}
				$row['qty_intransit'] = $this->stock_int->qty_intransit($row['id_part_int']);
				$row['qty_suggest'] = $this->perhitungan_qty_suggest($row['average'], $row['qty_avs'], $row['fix_order_n_1']);

				$row['updated_at'] = Mcarbon::now()->toDateTimeString();
				$row['updated_by'] = $this->session->userdata('id_user');

				$this->niguri->insert($row);
			}else{
				$qty_avs = $this->stock_int->qty_avs($part['id_part_int']);

                if($random){
                    $qty_avs = rand(10,1000);
                    $part['pertama'] = rand(10,1000);
                    $part['kedua'] = rand(10,1000);
                    $part['ketiga'] = rand(10,1000);
                    $part['keempat'] = rand(10,1000);
                    $part['kelima'] = rand(10,1000);
                    $part['keenam'] = rand(10,1000);
                }
				
	
				$part['average'] = ( intval($part['pertama']) + intval($part['kedua']) + intval($part['ketiga']) + intval($part['keempat']) + intval($part['kelima']) + intval($part['keenam']) ) / 6;
				$part['average'] = $part['average'];
				if($part['average'] > 0){
					$part['s_l'] = floatval($qty_avs) / floatval($part['average']);
				}else{
					$part['s_l'] = 0;
				}
	
				$qty_suggest = $this->perhitungan_qty_suggest($part['average'], $qty_avs, $part['fix_order_n_1']);
	
				$this->db
				->set('n.qty_suggest', $qty_suggest)
				->set('n.qty_avs', $qty_avs)
				->set('n.average', $part['average'])
				->set('n.s_l', $part['s_l']);

                if($random){
                    $this->db
                    ->set('n.pertama', $part['pertama'])
                    ->set('n.kedua', $part['kedua'])
                    ->set('n.ketiga', $part['ketiga'])
                    ->set('n.keempat', $part['keempat'])
                    ->set('n.kelima', $part['kelima'])
                    ->set('n.keenam', $part['keenam']);
                }
				
				$this->db
                ->where('n.id_part_int', $part['id_part_int'])
				->where('n.id_niguri_header', $id_niguri_header);

				$this->db->update('tr_h3_md_niguri as n');
			}
		}

        $this->db->trans_start();
		$updated_at = Mcarbon::now()->toDateTimeString();
		$this->db
		->set('nh.updated_at', $updated_at)
		->set('nh.updated_by', $this->session->userdata('id_user'))
		->where('nh.id', $id_niguri_header)
		->update('tr_h3_md_niguri_header as nh');
		$this->db->trans_complete();

		if($this->db->trans_status()){
			send_json([
				'message' => 'Berhasil memperbarui data.',
				'payload' => [
					'updated_at' => $updated_at
				]
			]);
		}else{
			send_json([
				'message' => 'Tidak berhasil memperbarui data.',
			], 422);
		}
    }

    private function get_niguri_data($tanggal_generate, $id_part = null, $type_niguri, $random = false){
        $tanggal_generate = Mcarbon::parse($tanggal_generate);
		$enam_bulan_terakhir = $this->get_enam_bulan_terakhir($tanggal_generate->toDateString());

		$sales_in_month = [];
		for ($index=0; $index < count($enam_bulan_terakhir); $index++) { 
			$sales_in_month[] = $this->get_sales_in_month($enam_bulan_terakhir[$index]['start_date'], $enam_bulan_terakhir[$index]['end_date']);
		}

		$fix_order_n = $this->pergeseran_fix_order('fix_order_n', 'p.id_part_int', $tanggal_generate->toDateString(), true);
		$fix_order_n_1 = $this->pergeseran_fix_order('fix_order_n_1', 'p.id_part_int', $tanggal_generate->toDateString(), true);
		$fix_order_n_2 = $this->pergeseran_fix_order('fix_order_n_2', 'p.id_part_int', $tanggal_generate->toDateString(), true);
		$fix_order_n_3 = $this->pergeseran_fix_order('fix_order_n_3', 'p.id_part_int', $tanggal_generate->toDateString(), true);
		$fix_order_n_4 = $this->pergeseran_fix_order('fix_order_n_4', 'p.id_part_int', $tanggal_generate->toDateString(), true);


		$periode_awal = $tanggal_generate->copy()->subMonths(6);
		$periode_akhir = $tanggal_generate;

		$kode_parts_yang_direquest_dealer = $this->kode_parts_yang_direquest_dealer($periode_awal->toDateString(), $periode_akhir->toDateString());
		$kode_parts_yang_dibeli = $this->kode_parts_yang_dibeli($periode_awal->toDateString(), $periode_akhir->toDateString());

		$this->db
		->select('p.id_part_int')
		->select('p.id_part')
		->select('IFNULL(p.harga_dealer_user, 0) as het', false)
		->select('IFNULL(p.harga_md_dealer, 0) as hpp', false)
		->select("IFNULL(({$fix_order_n}), 0) as fix_order_n", false)
		->select("IFNULL(({$fix_order_n_1}), 0) as fix_order_n_1", false)
		->from('ms_part as p');

		// $this->db->limit(500);

		if($type_niguri == 'FIX'){
			$this->db
			->select("IFNULL(({$fix_order_n_2}), 0) as fix_order_n_2", false)
			->select("IFNULL(({$fix_order_n_3}), 0) as fix_order_n_3", false)
			->select("IFNULL(({$fix_order_n_4}), 0) as fix_order_n_4", false);

			$this->db->where('p.fix', 1);
		}else if($type_niguri == 'REG'){
			$this->db
			->select('0 as fix_order_n_2', false)
			->select('0 as fix_order_n_3', false)
			->select('0 as fix_order_n_4', false);

			$this->db->group_start();
			$this->db->where('p.fix', 1);

			if(count($kode_parts_yang_direquest_dealer) > 0){
				$this->db->or_where_in('p.id_part_int', $kode_parts_yang_direquest_dealer);
			}

			if(count($kode_parts_yang_dibeli) > 0){
				$this->db->or_where_in('p.id_part_int', $kode_parts_yang_dibeli);
			}
			$this->db->group_end();
		}

		if($id_part != null){
			$this->db->where('p.id_part_int', $id_part);
		}

        $urutan_penamaan = [
            'pertama', 'kedua', 'ketiga', 'keempat', 'kelima', 'keenam'
        ];
		for ($index=0; $index < count($sales_in_month); $index++) { 
            if($random){
                // $random_number = rand(10,1000);
                $random_number = "FLOOR(RAND()*(1000-10+1)+10)";
                $this->db->select("({$random_number}) as {$urutan_penamaan[$index]}");
            }else{
                $this->db->select("IFNULL(({$sales_in_month[$index]}), 0) as {$urutan_penamaan[$index]}", false);
            }
		}

		if($id_part != null) return $this->db->get()->row_array();

		return $this->db->get()->result_array();
	}

    private function get_enam_bulan_terakhir($now){
        $now = Mcarbon::parse($now);

		$enam_bulan_terakhir = [];
		for ($i=1; $i <= 6 ; $i++) { 
            $start_date = $now->copy()->subMonth($i)->startOfMonth()->toDateString();
            $end_date = $now->copy()->subMonth($i)->endOfMonth()->toDateString();

			$enam_bulan_terakhir[] = [
				'start_date' => $start_date,
				'end_date' => $end_date,
			];
		}

		return $enam_bulan_terakhir;
	}

    private function get_sales_in_month($start_date, $end_date){
        $start_date = Mcarbon::parse($start_date)->startOfDay();
        $end_date = Mcarbon::parse($end_date)->endOfDay();

		$this->db
		->select('SUM(dop.qty_supply) as qty_supply', false)
		->from('tr_h3_md_do_sales_order as do')
		->join('tr_h3_md_do_sales_order_parts as dop', 'dop.id_do_sales_order_int = do.id')
		->join('tr_h3_md_picking_list as pl', 'pl.id_ref_int = do.id')
		->join('tr_h3_md_packing_sheet as ps', 'ps.id_picking_list_int = pl.id')
		->group_start()
		->where("ps.tgl_faktur BETWEEN '{$start_date->toDateTimeString()}' AND '{$end_date->toDateTimeString()}'", null, false)
		->group_end()
		->where('dop.id_part_int = p.id_part_int', null, false)
		->where('do.sudah_create_faktur', 1);

		return $this->db->get_compiled_select();
	}

    private function pergeseran_fix_order($urutan_fix_order, $id_part_int, $tanggal_generate, $sql = false){
		$bulan = Mcarbon::parse($tanggal_generate)->format('m');
		$tahun = Mcarbon::parse($tanggal_generate)->format('Y');

		$this->db
		->select("
			case
				when '{$urutan_fix_order}' = 'fix_order_n' then n.fix_order_n_1
				when '{$urutan_fix_order}' = 'fix_order_n_1' then n.fix_order_n_2
				when '{$urutan_fix_order}' = 'fix_order_n_2' then n.fix_order_n_3
				when '{$urutan_fix_order}' = 'fix_order_n_3' then n.fix_order_n_4
				when '{$urutan_fix_order}' = 'fix_order_n_4' then n.fix_order_n_5
			end as nilai_pergeseran_fix_order
		", false)
		->from('tr_h3_md_niguri_header as nh')
		->join('tr_h3_md_niguri as n', 'n.id_niguri_header = nh.id')
		->where('nh.type_niguri', 'FIX')
		;

		if($sql){
			$this->db
			->group_start()
			->where("nh.bulan = {$bulan}", null, false)
			->where("nh.tahun = {$tahun}", null, false)
			->group_end()
			->where("n.id_part_int = {$id_part_int}", null, false);

			return $this->db->get_compiled_select();
		}else{
			$this->db
			->group_start()
			->where('nh.bulan', $bulan)
			->where('nh.tahun', $tahun)
			->group_end()
			->where('n.id_part_int', $id_part_int);

			$pergeseran_fix_order = $this->db->get()->row_array();

			if($pergeseran_fix_order != null){
				return $pergeseran_fix_order['nilai_pergeseran_fix_order'];
			}
			return 0;
		}
	}

    private function kode_parts_yang_direquest_dealer($periode_awal, $periode_akhir){
		$kode_parts_yang_direquest_dealer = $this->db
		->select('DISTINCT(pop.id_part_int) as id_part_int', false)
		->from('tr_h3_dealer_purchase_order_parts as pop')
		->join('tr_h3_dealer_purchase_order as po', 'po.id = pop.po_id_int')
		->where("po.tanggal_order BETWEEN '{$periode_awal}' AND '{$periode_akhir}'", null, false)
		->get()->result_array();

		return array_map(function($row){
			return $row['id_part_int'];
		}, $kode_parts_yang_direquest_dealer);
	}

    private function kode_parts_yang_dibeli($periode_awal, $periode_akhir){
		$kode_parts_yang_dibeli = $this->db
		->select('DISTINCT(pop.id_part_int) as id_part_int', false)
		->from('tr_h3_md_purchase_order_parts as pop')
		->join('tr_h3_md_purchase_order as po', 'po.id = pop.id_purchase_order_int')
		->where("po.tanggal_po BETWEEN '{$periode_awal}' AND '{$periode_akhir}'", null, false)
		->get()->result_array();

		return array_map(function($row){
			return $row['id_part_int'];
		}, $kode_parts_yang_dibeli);
	}

    private function perhitungan_qty_suggest($average, $qty_avs, $fix_order_n_1){
		$qty_suggest = $average - $qty_avs - intval($fix_order_n_1) + $average * 1.5;

		if($qty_suggest < 0){
			return 0;
		}
		return $qty_suggest;
	}

	public function update_harga($id_part_int){
		$niguri_item = $this->db
		->select('n.id')
		->select('n.id_part_int')
		->select('n.id_part')
		->select('n.het')
		->select('p.harga_dealer_user as het_terakhir')
		->select('n.hpp')
		->select('p.harga_md_dealer as hpp_terakhir')
		->from('tr_h3_md_niguri as n')
		->join('tr_h3_md_niguri_header as nh', 'nh.id = n.id_niguri_header')
		->join('ms_part as p', 'p.id_part_int = n.id_part_int')
		->where('nh.status !=', 'Processed')
		->where('n.id_part_int', $id_part_int)
		->get()->result_array();

		foreach($niguri_item as $row){
			$this->db
			->set('n.het', $row['het_terakhir'])
			->set('n.hpp', $row['hpp_terakhir'])
			->where('n.id', $row['id'])
			->update('tr_h3_md_niguri as n');

			log_message('debug', sprintf('Update harga niguri part [payload] %s', print_r($row, true)));
		}
	}

}
