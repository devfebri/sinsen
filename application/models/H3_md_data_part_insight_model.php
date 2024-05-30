<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class H3_md_data_part_insight_model extends CI_Model{
		public function __construct()
        {
            parent::__construct();
        }

		public function getDataDealer()
		{
            $filter_toko = '';
            if(!$this->config->item('ahass_only')){
                $filter_toko = 'WHERE h2=1 and active=1 and LENGTH (kode_dealer_ahm)=5';
            }

			$query=$this->db->query("SELECT id_dealer,kode_dealer_ahm,nama_dealer from ms_dealer $filter_toko order by nama_dealer ASC");
			return $query->result();
		}

        public function grouping_parts()
        {
            $grouping_parts=$this->db->query("SELECT kp.produk as major_group, kp.id_kelompok_part as kelompok_part
            from ms_h3_md_setting_kelompok_produk kp WHERE kp.id_kelompok_part !='FED OIL'");
			return $grouping_parts->result();
        }

        public function ps_channel2($id_dealer,$start_date,$end_date)
        {
            $filter_dealer = '';
          	if ($id_dealer!='all') {
           		$filter_dealer = " AND C.id_dealer='$id_dealer'";
         	}

            $ps_channel = $this->db->query("SELECT 
                SUM(CASE WHEN (D.h1='1' and D.h2='1' and D.h3='1' AND B.tipe_diskon='Percentage') THEN (B.harga_beli*B.qty)-((B.harga_beli*B.qty)*(B.diskon_value/100)) 
				WHEN (D.h1='1' and D.h2='1' and D.h3='1') THEN ((B.harga_beli*B.qty)-B.diskon_value)
							ELSE 0 END) as h123,
				SUM(CASE WHEN (D.h1='0' and D.h2='1' and D.h3='1' AND B.tipe_diskon='Percentage') THEN (B.harga_beli*B.qty)-((B.harga_beli*B.qty)*(B.diskon_value/100))  
				WHEN (D.h1='0' and D.h2='1' and D.h3='1') THEN ((B.harga_beli*B.qty)-B.diskon_value) ELSE 0 END) as h23
                from tr_h23_nsc_parts B 
                JOIN ms_part A ON A.id_part_int=B.id_part_int 
                join tr_h23_nsc C ON C.no_nsc=B.no_nsc 
                join ms_dealer D on D.id_dealer=C.id_dealer 
                where C.tgl_nsc >= '$start_date' and C.tgl_nsc <='$end_date' $filter_dealer");
            return $ps_channel;
        }

        public function ps_channel($id_dealer,$start_date,$end_date)
        {
            $filter_dealer = '';
          	if ($id_dealer!='all') {
           		$filter_dealer = " AND nsc.id_dealer='$id_dealer'";
         	}

            $filter_toko = '';
            if(!$this->config->item('ahass_only')){
                $filter_toko = 'AND md.h2=1 and md.active=1 and LENGTH (md.kode_dealer_ahm)=5';
            }


            $ps_channel = $this->db->query("SELECT 
            md.nama_dealer, skp.produk , mp.kelompok_part, nsc.referensi, nsc.id_dealer, (CASE WHEN md.h1 = 1 and md.h2 = 1 and md.h3 = 1 then 'H123' WHEN md.h1 = 0 and md.h2 = 1 and md.h3 = 1 THEN 'H23' 
                WHEN md.h1 = 0 and md.h2 = 0 and md.h3 = 1 THEN 'H3' ELSE '-' END) as status, SUM(CASE WHEN nscp.tipe_diskon='Percentage' 
                then ((nscp.harga_beli*nscp.qty)-(nscp.harga_beli*nscp.diskon_value/100))
                WHEN nscp.tipe_diskon='Value' then ((nscp.harga_beli*nscp.qty)-nscp.diskon_value) ELSE nscp.harga_beli*nscp.qty END) as total_penjualan,(CASE WHEN mp.kelompok_part in ('TIRE','TIRE1') THEN 'T' 
			WHEN mp.kelompok_part='BATT' THEN 'B' WHEN mp.kelompok_part in ('BLDRV','BRAKE','EC','SPLUG','SPLUR') THEN 'PM' 
			WHEN mp.kelompok_part in ('ACB','ACG','BATT','FLUID','GMO','OIL') THEN 'O' ELSE 'Other' END) as tobpm,(CASE WHEN nsc.referensi='work_order' THEN 'WO' ELSE 'Direct Sales' END) AS amount_by_service
                FROM tr_h23_nsc nsc
                JOIN tr_h23_nsc_parts nscp on nscp.no_nsc=nsc.no_nsc 
                JOIN ms_part mp on mp.id_part_int=nscp.id_part_int 
                JOIN ms_h3_md_setting_kelompok_produk skp on skp.id_kelompok_part=mp.kelompok_part 
                JOIN ms_dealer md on md.id_dealer=nsc.id_dealer
                where nsc.created_at >='$start_date 00:00:00' and nsc.created_at<='$end_date 23:59:59'
                and mp.kelompok_part !='FED OIL' $filter_dealer $filter_toko
                GROUP BY nsc.id_dealer, mp.kelompok_part, nsc.referensi 
                ORDER BY md.nama_dealer");
            return $ps_channel;
        }

        public function ps_service($id_dealer,$start_date,$end_date)
        {
            $filter_dealer = '';
          	if ($id_dealer!='all') {
           		$filter_dealer = " AND C.id_dealer='$id_dealer'";
         	}

            $ps_service = $this->db->query("select 
                SUM(CASE WHEN (C.referensi='work_order' AND B.tipe_diskon='Percentage') THEN (B.harga_beli*B.qty)-((B.harga_beli*B.qty)*(B.diskon_value/100)) 
                WHEN (C.referensi='work_order') THEN ((B.harga_beli*B.qty)-B.diskon_value)
                ELSE 0 END) as wo,
                SUM(CASE WHEN (C.referensi='sales' AND B.tipe_diskon='Percentage') THEN (B.harga_beli*B.qty)-((B.harga_beli*B.qty)*(B.diskon_value/100))  
                WHEN (C.referensi='sales') THEN ((B.harga_beli*B.qty)-B.diskon_value)
                ELSE 0 END) as sales
				 from tr_h23_nsc_parts B 
				 JOIN ms_part A ON A.id_part_int=B.id_part_int 
				 join tr_h23_nsc C ON C.no_nsc=B.no_nsc 
				 where C.tgl_nsc >= '$start_date' and C.tgl_nsc <='$end_date' $filter_dealer");
            return $ps_service;
        }

        public function ps_jenis_kelompok($id_dealer,$start_date,$end_date)
        {
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = " AND nsc.id_dealer='$id_dealer'";
            }

            $ps_jenis_kelompok = $this->db->query("SELECT SUM(CASE WHEN (kp.produk='Parts' AND nscp.tipe_diskon='Percentage')  
                            then (nscp.harga_beli*nscp.qty)-((nscp.harga_beli*nscp.qty)*(nscp.diskon_value/100)) 
                        WHEN (kp.produk='Parts') then ((nscp.harga_beli*nscp.qty)-nscp.diskon_value)
                        ELSE 0 END) as hgp,
                SUM(CASE WHEN (kp.produk='Acc' AND nscp.tipe_diskon='Percentage')  
                            then (nscp.harga_beli*nscp.qty)-((nscp.harga_beli*nscp.qty)*(nscp.diskon_value/100)) 
                        WHEN (kp.produk='Acc') then ((nscp.harga_beli*nscp.qty)-nscp.diskon_value)
                        ELSE 0 END) as hga,
                SUM(CASE WHEN (kp.produk='Oil' AND nscp.tipe_diskon='Percentage')  
                            then (nscp.harga_beli*nscp.qty)-((nscp.harga_beli*nscp.qty)*(nscp.diskon_value/100)) 
                        WHEN (kp.produk='Oil') then ((nscp.harga_beli*nscp.qty)-nscp.diskon_value)
                        ELSE 0 END) as hgo
                from tr_h23_nsc_parts nscp
                join tr_h23_nsc nsc on nsc.no_nsc=nscp.no_nsc 
                join ms_part mp on mp.id_part_int=nscp.id_part_int 
                left join ms_h3_md_setting_kelompok_produk kp on kp.id_kelompok_part_int=mp.kelompok_part_int 
                where nsc.tgl_nsc >= '$start_date' and nsc.tgl_nsc <='$end_date' $filter_dealer");
            return $ps_jenis_kelompok;
        }
        
        public function ps_tobpm($id_dealer,$start_date,$end_date)
        {
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = " AND C.id_dealer='$id_dealer'";
            }

            $ps_tobpm = $this->db->query("SELECT 
                SUM(CASE WHEN (A.kelompok_part in ('ACB','ACG','BATT','FLUID','GMO','OIL','TIRE','TIRE1') AND B.tipe_diskon='Percentage') then (B.harga_beli*B.qty)-((B.harga_beli*B.qty)*(B.diskon_value/100)) 
				 WHEN A.kelompok_part in ('ACB','ACG','BATT','FLUID','GMO','OIL','TIRE','TIRE1') then ((B.harga_beli*B.qty)-B.diskon_value) ELSE 0 END) as tob,
				 SUM(CASE WHEN (A.kelompok_part in ('BLDRV','BRAKE','EC','SPLUG','SPLUR') AND B.tipe_diskon='Percentage') then (B.harga_beli*B.qty)-((B.harga_beli*B.qty)*(B.diskon_value/100)) 
				 WHEN A.kelompok_part in ('BLDRV','BRAKE','EC','SPLUG','SPLUR') then ((B.harga_beli*B.qty)-B.diskon_value) ELSE 0 END) as pm,
				 SUM(CASE WHEN (A.kelompok_part NOT IN ('ACB','ACG','BATT','FLUID','GMO','OIL','TIRE','TIRE1','BLDRV','BRAKE','EC','SPLUG','SPLUR') AND B.tipe_diskon='Percentage') then (B.harga_beli*B.qty)-((B.harga_beli*B.qty)*(B.diskon_value/100)) 
				 WHEN A.kelompok_part NOT IN ('ACB','ACG','BATT','FLUID','GMO','OIL','TIRE','TIRE1','BLDRV','BRAKE','EC','SPLUG','SPLUR') then ((B.harga_beli*B.qty)-B.diskon_value) ELSE 0 END) as other
                from tr_h23_nsc_parts B 
                JOIN ms_part A ON A.id_part_int=B.id_part_int 
                join tr_h23_nsc C ON C.no_nsc=B.no_nsc
                where C.tgl_nsc >= '$start_date' and C.tgl_nsc <='$end_date' $filter_dealer");
            return $ps_tobpm;
        }


        public function ps_alert_growth($id_dealer,$start_date,$end_date)
        {
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = "where C.id_dealer='$id_dealer'";
            }

            $ps_alert_growth = $this->db->query(" SELECT md.nama_dealer,
				 SUM(CASE WHEN (C.tgl_nsc >= '$start_date' and C.tgl_nsc <='$end_date' and B.tipe_diskon='Percentage') then (B.harga_beli*B.qty)-((B.harga_beli*B.qty)*(B.diskon_value/100)) 
				 WHEN (C.tgl_nsc >= '$start_date' and C.tgl_nsc <='$end_date') then ((B.harga_beli*B.qty)-B.diskon_value) else 0 end ) as M,
				 SUM(CASE WHEN (C.tgl_nsc >= DATE_ADD(LAST_DAY(DATE_SUB(('$end_date'),INTERVAL 2 MONTH)),INTERVAL 1 DAY) 
				 and C.tgl_nsc <= DATE_SUB(('$end_date'), INTERVAL 1 MONTH) and B.tipe_diskon='Percentage') then (B.harga_beli*B.qty)-((B.harga_beli*B.qty)*(B.diskon_value/100)) 
				 WHEN (C.tgl_nsc >= DATE_ADD(LAST_DAY(DATE_SUB(('$end_date'),INTERVAL 2 MONTH)),INTERVAL 1 DAY) 
				 and C.tgl_nsc <= DATE_SUB(('$end_date'), INTERVAL 1 MONTH)) then ((B.harga_beli*B.qty)-B.diskon_value) else 0 end ) as Msebelum,
				 SUM(CASE WHEN (C.tgl_nsc >= MAKEDATE(year('$start_date'),1) and C.tgl_nsc<='$end_date' and B.tipe_diskon='Percentage') then (B.harga_beli*B.qty)-((B.harga_beli*B.qty)*(B.diskon_value/100)) 
				 WHEN (C.tgl_nsc >= MAKEDATE(year('$start_date'),1) and C.tgl_nsc<='$end_date') then ((B.harga_beli*B.qty)-B.diskon_value) else 0 end) as ytd
                from tr_h23_nsc_parts B 
                JOIN ms_part A ON A.id_part_int=B.id_part_int
                join tr_h23_nsc C ON C.no_nsc=B.no_nsc  
                JOIN ms_dealer md on md.id_dealer=C.id_dealer 
                $filter_dealer
                group by C.id_dealer");
            return $ps_alert_growth;
        }

        public function ps_target_achievement($id_dealer,$start_date,$end_date)
        {
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = "where C.id_dealer='$id_dealer'";
            }

            $ps_target_achievement = $this->db->query("SELECT md.nama_dealer,
                 SUM(CASE WHEN (C.tgl_nsc >= '$start_date' and C.tgl_nsc <='$end_date' and B.tipe_diskon='Percentage') then (B.harga_beli*B.qty)-((B.harga_beli*B.qty)*(B.diskon_value/100)) 
				 WHEN (C.tgl_nsc >= '$start_date' and C.tgl_nsc <='$end_date') then ((B.harga_beli*B.qty)-B.diskon_value) else 0 end ) as ytd
				--  SUM(CASE WHEN (C.tgl_nsc >= MAKEDATE(year('$start_date'),1) and C.tgl_nsc<='$end_date' and B.tipe_diskon='Percentage') then (B.harga_beli*B.qty)-((B.harga_beli*B.qty)*(B.diskon_value/100)) 
				--  WHEN (C.tgl_nsc >= MAKEDATE(year('$start_date'),1) and C.tgl_nsc<='$end_date') then ((B.harga_beli*B.qty)-B.diskon_value) else 0 end) as ytd
                from tr_h23_nsc_parts B 
                JOIN ms_part A ON A.id_part_int=B.id_part_int
                join tr_h23_nsc C ON C.no_nsc=B.no_nsc 
                join ms_dealer md on md.id_dealer=C.id_dealer  
                $filter_dealer
                group by C.id_dealer");
            return $ps_target_achievement;
        }

        public function ps_grouping_part($id_dealer,$start_date,$end_date)
        {
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = " and C.id_dealer='$id_dealer'";
            }

            $ps_grouping_part = $this->db->query("SELECT A.kelompok_part, 
				 (CASE WHEN B.tipe_diskon ='Percentage' then (B.harga_beli*B.qty)-((B.harga_beli*B.qty)*(B.diskon_value/100)) else((B.harga_beli*B.qty)-B.diskon_value) END)  as pendapatan
				 from tr_h23_nsc_parts B 
                 JOIN ms_part A ON A.id_part_int=B.id_part_int 
				 join tr_h23_nsc C ON C.no_nsc=B.no_nsc 
				 where C.tgl_nsc >= '$start_date' and C.tgl_nsc <='$end_date' $filter_dealer
				 GROUP BY A.kelompok_part 
				 ORDER BY pendapatan DESC LIMIT 10");
            return $ps_grouping_part;
        }


        // public function ps_avg_grouping_part($id_dealer,$start_date,$end_date)
        // {
        //     $filter_dealer = '';
        //     if ($id_dealer!='all') {
        //          $filter_dealer = "and C.id_dealer='$id_dealer'";
        //     }

        //     $ps_avg_grouping_part = $this->db->query("SELECT md.nama_dealer, A.kelompok_part as kelompok_part,(CASE WHEN C.referensi='work_order' THEN 'WO' ELSE 'Direct Sales' END) AS referensi,  skp.produk,date_format(C.tgl_nsc ,'%m-%Y') as bulantahun,  
        //         SUM(CASE WHEN B.tipe_diskon='Percentage' 
        //         then ((B.harga_beli*B.qty)-((B.harga_beli*B.diskon_value/100)*B.qty))
        //         WHEN B.tipe_diskon='Value' then ((B.harga_beli*B.qty)-(B.diskon_value*B.qty)) ELSE B.harga_beli*B.qty END)  as pendapatan,(CASE WHEN md.h1 = 1 and md.h2 = 1 and md.h3 = 1 then 'H123' WHEN md.h1 = 0 and md.h2 = 1 and md.h3 = 1 THEN 'H23' 
        //          WHEN md.h1 = 0 and md.h2 = 0 and md.h3 = 1 THEN 'H3' ELSE '-' END) as status
        //         from tr_h23_nsc_parts B JOIN ms_part A ON A.id_part=B.id_part 
        //         join tr_h23_nsc C ON C.no_nsc=B.no_nsc 
        //         join ms_h3_md_setting_kelompok_produk skp on skp.id_kelompok_part=A.kelompok_part
        //         JOIN ms_dealer md on md.id_dealer=C.id_dealer 
        //         where  C.tgl_nsc >= DATE_SUB('$start_date', interval 1 month) and 
        //         C.tgl_nsc <= DATE_SUB('$end_date', interval 1 month) $filter_dealer
        //         GROUP BY C.id_dealer, A.kelompok_part, C.referensi, bulantahun 
        //      UNION
        //         SELECT md.nama_dealer,A.kelompok_part as kelompok_part, (CASE WHEN C.referensi='work_order' THEN 'WO' ELSE 'Direct Sales' END) AS referensi, skp.produk, date_format(C.tgl_nsc ,'%m-%Y') as bulantahun, 
        //         SUM(CASE WHEN B.tipe_diskon='Percentage' 
        //         then ((B.harga_beli*B.qty)-((B.harga_beli*B.diskon_value/100)*B.qty))
        //         WHEN B.tipe_diskon='Value' then ((B.harga_beli*B.qty)-(B.diskon_value*B.qty)) ELSE B.harga_beli*B.qty END)  as pendapatan,(CASE WHEN md.h1 = 1 and md.h2 = 1 and md.h3 = 1 then 'H123' WHEN md.h1 = 0 and md.h2 = 1 and md.h3 = 1 THEN 'H23' 
        //         WHEN md.h1 = 0 and md.h2 = 0 and md.h3 = 1 THEN 'H3' ELSE '-' END) as status
        //         from tr_h23_nsc_parts B JOIN ms_part A ON A.id_part=B.id_part 
        //         join tr_h23_nsc C ON C.no_nsc=B.no_nsc 
        //         join ms_h3_md_setting_kelompok_produk skp on skp.id_kelompok_part=A.kelompok_part
        //         JOIN ms_dealer md on md.id_dealer=C.id_dealer 
        //         where  C.tgl_nsc >= DATE_SUB('$start_date', interval 2 month) and 
        //         C.tgl_nsc <= DATE_SUB('$end_date', interval 2 month)  $filter_dealer
        //         GROUP BY C.id_dealer, A.kelompok_part, C.referensi, bulantahun
        //      UNION
        //         SELECT md.nama_dealer,A.kelompok_part as kelompok_part, (CASE WHEN C.referensi='work_order' THEN 'WO' ELSE 'Direct Sales' END) AS referensi, skp.produk, date_format(C.tgl_nsc ,'%m-%Y')   as bulantahun, 
        //         SUM(CASE WHEN B.tipe_diskon='Percentage' 
        //         then ((B.harga_beli*B.qty)-((B.harga_beli*B.diskon_value/100)*B.qty))
        //         WHEN B.tipe_diskon='Value' then ((B.harga_beli*B.qty)-(B.diskon_value*B.qty)) ELSE B.harga_beli*B.qty END)  as pendapatan,(CASE WHEN md.h1 = 1 and md.h2 = 1 and md.h3 = 1 then 'H123' WHEN md.h1 = 0 and md.h2 = 1 and md.h3 = 1 THEN 'H23' 
        //         WHEN md.h1 = 0 and md.h2 = 0 and md.h3 = 1 THEN 'H3' ELSE '-' END) as status
        //         from tr_h23_nsc_parts B JOIN ms_part A ON A.id_part=B.id_part 
        //         join tr_h23_nsc C ON C.no_nsc=B.no_nsc 
        //         join ms_h3_md_setting_kelompok_produk skp on skp.id_kelompok_part=A.kelompok_part
        //      JOIN ms_dealer md on md.id_dealer=C.id_dealer 
        //         where  C.tgl_nsc >= DATE_SUB('$start_date', interval 3 month) and 
        //         C.tgl_nsc <= DATE_SUB('$end_date', interval 3 month)  $filter_dealer
        //         GROUP BY C.id_dealer, A.kelompok_part, C.referensi, bulantahun 
        //     ORDER BY pendapatan DESC");
        //     return $ps_avg_grouping_part;
        // }

        public function ps_avg_grouping_part($id_dealer,$start_date,$end_date)
        {
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = "and C.id_dealer='$id_dealer'";
            }

            $ps_avg_grouping_part = $this->db->query("SELECT md.nama_dealer, A.kelompok_part as kelompok_part,(CASE WHEN C.referensi='work_order' THEN 'WO' ELSE 'Direct Sales' END) AS referensi,  skp.produk,date_format(C.tgl_nsc ,'%m-%Y') as bulantahun,  
                SUM(CASE WHEN B.tipe_diskon='Percentage' 
                then ((B.harga_beli*B.qty)-((B.harga_beli*B.diskon_value/100)*B.qty))
                WHEN B.tipe_diskon='Value' then ((B.harga_beli*B.qty)-(B.diskon_value*B.qty)) ELSE B.harga_beli*B.qty END)  as pendapatan,
                (CASE WHEN md.h1 = 1 and md.h2 = 1 and md.h3 = 1 then 'H123' WHEN md.h1 = 0 and md.h2 = 1 and md.h3 = 1 THEN 'H23' 
                 WHEN md.h1 = 0 and md.h2 = 0 and md.h3 = 1 THEN 'H3' ELSE '-' END) as status
                from tr_h23_nsc_parts B 
                JOIN ms_part A ON A.id_part=B.id_part 
                join tr_h23_nsc C ON C.no_nsc=B.no_nsc 
                join ms_h3_md_setting_kelompok_produk skp on skp.id_kelompok_part=A.kelompok_part
                JOIN ms_dealer md on md.id_dealer=C.id_dealer 
                where A.kelompok_part != 'FED OIL' and C.tgl_nsc >= '$start_date' and 
                C.tgl_nsc <= '$end_date' $filter_dealer
                GROUP BY C.id_dealer, A.kelompok_part, C.referensi, bulantahun");
            return $ps_avg_grouping_part;
        }

        public function ps_avg_grouping_part2($id_dealer,$start_date,$end_date)
        {
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = "and C.id_dealer='$id_dealer'";
            }

            $ps_avg_grouping_part = $this->db->query("SELECT md.nama_dealer,  A.kelompok_part, skp.produk, date_format(C.tgl_nsc ,'%m-%Y') as bulantahun, (CASE WHEN md.h1 = 1 and md.h2 = 1 and md.h3 = 1 then 'H123' WHEN md.h1 = 0 and md.h2 = 1 and md.h3 = 1 THEN 'H23' 
            WHEN md.h1 = 0 and md.h2 = 0 and md.h3 = 1 THEN 'H3' ELSE '-' END) as status, C.referensi,
                (CASE WHEN B.tipe_diskon='Percentage' 
			 then ((B.harga_beli*B.qty)-((B.harga_beli*B.diskon_value/100)*B.qty))
			 WHEN B.tipe_diskon='Value' then ((B.harga_beli*B.qty)-(B.diskon_value*B.qty)) ELSE B.harga_beli*B.qty END)  as pendapatan
             FROM tr_h23_nsc_parts B 
             JOIN ms_part A ON A.id_part_int=B.id_part_int 
             JOIN tr_h23_nsc C ON C.no_nsc=B.no_nsc 
             JOIN ms_dealer md on md.id_dealer=C.id_dealer 
             JOIN ms_h3_md_setting_kelompok_produk skp on skp.id_kelompok_part=A.kelompok_part 
             JOIN (
                     SELECT A.kelompok_part as kelompok_part,
                     (CASE WHEN B.tipe_diskon='Percentage' 
                     then ((B.harga_beli*B.qty)-((B.harga_beli*B.diskon_value/100)*B.qty))
                     WHEN B.tipe_diskon='Value' then ((B.harga_beli*B.qty)-(B.diskon_value*B.qty)) ELSE B.harga_beli*B.qty END)  as pendapatan
                     from tr_h23_nsc_parts B JOIN ms_part A ON A.id_part=B.id_part 
                     join tr_h23_nsc C ON C.no_nsc=B.no_nsc 
                     where  C.tgl_nsc >= DATE_SUB('$end_date', interval 3 month) and 
                     C.tgl_nsc <= '$end_date' and A.kelompok_part!='FED OIL' $filter_dealer
                     GROUP BY C.id_dealer, A.kelompok_part, C.referensi
                     ORDER BY pendapatan DESC LIMIT 10
                     ) as kel_part on kel_part.kelompok_part = A.kelompok_part 
             WHERE C.tgl_nsc >= DATE_SUB('$end_date', interval 3 month) and 
             C.tgl_nsc <= '$end_date' 
             AND A.kelompok_part!='FED OIL' $filter_dealer
             GROUP BY C.id_dealer, A.kelompok_part, C.referensi, bulantahun                 
             ");
            return $ps_avg_grouping_part;
        }

        public function ps_9_segment($id_dealer,$start_date,$end_date)
        {
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = "and C.id_dealer='$id_dealer'";
            }

            $ps_9_segment = $this->db->query("SELECT
                SUM(CASE WHEN mtk.id_segment = 'AH' and B.tipe_diskon='Percentage' then (B.harga_beli*B.qty)-((B.harga_beli*B.qty)*(B.diskon_value/100)) WHEN (mtk.id_segment = 'AH') then ((B.harga_beli*B.qty)-B.diskon_value) ELSE 0 end) as at_high,
                SUM(CASE WHEN mtk.id_segment = 'AL' and B.tipe_diskon='Percentage' then (B.harga_beli*B.qty)-((B.harga_beli*B.qty)*(B.diskon_value/100)) WHEN (mtk.id_segment = 'AL') then ((B.harga_beli*B.qty)-B.diskon_value) ELSE 0 end) as at_low,
                SUM(CASE WHEN mtk.id_segment = 'AM' and B.tipe_diskon='Percentage' then (B.harga_beli*B.qty)-((B.harga_beli*B.qty)*(B.diskon_value/100)) WHEN (mtk.id_segment = 'AM') then ((B.harga_beli*B.qty)-B.diskon_value) ELSE 0 end) as at_medium,
                SUM(CASE WHEN mtk.id_segment = 'CH' and B.tipe_diskon='Percentage' then (B.harga_beli*B.qty)-((B.harga_beli*B.qty)*(B.diskon_value/100)) WHEN (mtk.id_segment = 'CH') then ((B.harga_beli*B.qty)-B.diskon_value) ELSE 0 end) as cub_high,
                SUM(CASE WHEN mtk.id_segment = 'CL' and B.tipe_diskon='Percentage' then (B.harga_beli*B.qty)-((B.harga_beli*B.qty)*(B.diskon_value/100)) WHEN (mtk.id_segment = 'CL') then ((B.harga_beli*B.qty)-B.diskon_value) ELSE 0 end) as cub_low,
                SUM(CASE WHEN mtk.id_segment = 'CM' and B.tipe_diskon='Percentage' then (B.harga_beli*B.qty)-((B.harga_beli*B.qty)*(B.diskon_value/100)) WHEN (mtk.id_segment = 'CM') then ((B.harga_beli*B.qty)-B.diskon_value) ELSE 0 end) as cub_medium,
                SUM(CASE WHEN mtk.id_segment = 'SH' and B.tipe_diskon='Percentage' then (B.harga_beli*B.qty)-((B.harga_beli*B.qty)*(B.diskon_value/100)) WHEN (mtk.id_segment = 'SH') then ((B.harga_beli*B.qty)-B.diskon_value) ELSE 0 end) as sport_high,
                SUM(CASE WHEN mtk.id_segment = 'SL' and B.tipe_diskon='Percentage' then (B.harga_beli*B.qty)-((B.harga_beli*B.qty)*(B.diskon_value/100)) WHEN (mtk.id_segment = 'SL') then ((B.harga_beli*B.qty)-B.diskon_value) ELSE 0 end) as sport_low,
                SUM(CASE WHEN mtk.id_segment = 'SM' and B.tipe_diskon='Percentage' then (B.harga_beli*B.qty)-((B.harga_beli*B.qty)*(B.diskon_value/100)) WHEN (mtk.id_segment = 'SM') then ((B.harga_beli*B.qty)-B.diskon_value) ELSE 0 end) as sport_medium
                from tr_h23_nsc_parts B 
                JOIN ms_part A ON A.id_part_int=B.id_part_int 
                join tr_h23_nsc C ON C.no_nsc=B.no_nsc 
                join tr_h2_wo_dealer wo on C.id_referensi=wo.id_work_order 
                join tr_h2_sa_form sa on wo.id_sa_form=sa.id_sa_form
                join ms_customer_h23 cust on cust.id_customer = sa.id_customer 
                left join ms_tipe_kendaraan mtk on mtk.id_tipe_kendaraan = cust.id_tipe_kendaraan 
                where C.referensi='work_order' 
                and C.tgl_nsc BETWEEN '$start_date' and '$end_date' $filter_dealer");
            return $ps_9_segment;
        }

        public function ps_details($id_dealer,$start_date,$end_date)
        {
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = "and C.id_dealer='$id_dealer'";
            }

            $filter_toko = '';
            if(!$this->config->item('ahass_only')){
                $filter_toko = 'AND md.h2=1 and md.active=1';
            }

            $ps_details = $this->db->query("SELECT md.nama_dealer, C.id_customer,
            (CASE WHEN md.h1='1' and md.h2='1' and md.h3='1' then 'H123' 
            WHEN md.h1='0' and md.h2='1' and md.h3='1' then 'H23' ELSE 'H3' END) as channel_h123,
              kp.produk as klp_part,
            B.id_part, A.nama_part, A.kelompok_part, B.qty, B.harga_beli, B.no_nsc, (CASE WHEN C.referensi= 'work_order' THEN 'WO' ELSE 'Direct Sales' END) as referensi, 
            -- mtk.deskripsi_ahm,
            -- ms.segment, DATE_FORMAT(mtk.tgl_awal,'%Y') as production_year,
            (CASE WHEN B.tipe_diskon='Percentage' 
				then ((B.harga_beli*B.qty)-((B.harga_beli*B.diskon_value/100)*B.qty))
				WHEN B.tipe_diskon='Value' then ((B.harga_beli*B.qty)-(B.diskon_value*B.qty)) ELSE B.harga_beli*B.qty END) as total,B.tipe_diskon, B.diskon_value 
            from tr_h23_nsc C
            JOIN tr_h23_nsc_parts B ON C.no_nsc=B.no_nsc 
            JOIN ms_part A ON A.id_part_int=B.id_part_int 
            -- join tr_h23_nsc C ON C.no_nsc=B.no_nsc 
            join ms_dealer md ON md.id_dealer=C.id_dealer
            -- JOIN ms_h3_md_setting_kelompok_produk kp on kp.id_kelompok_part_int=A.kelompok_part_int
            JOIN ms_h3_md_setting_kelompok_produk kp on kp.id_kelompok_part=A.kelompok_part
            -- LEFT JOIN ms_customer_h23 mch on mch.id_customer=C.id_customer 
            -- LEFT JOIN ms_tipe_kendaraan mtk on mch.id_tipe_kendaraan=mtk.id_tipe_kendaraan 
            -- LEFT JOIN ms_segment ms on ms.id_segment=mtk.id_segment 
            where C.tgl_nsc BETWEEN '$start_date' and '$end_date' and A.kelompok_part != 'FED OIL' $filter_dealer $filter_toko");
            return $ps_details;
        }

        public function ps_details2($id_dealer,$start_date,$end_date)
        {
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = "and C.id_dealer='$id_dealer'";
            }

            $ps_details = $this->db->query("SELECT md.nama_dealer, 
                (CASE WHEN md.h1='1' and md.h2='1' and md.h3='1' then 'H123' 
                WHEN md.h1='0' and md.h2='1' and md.h3='1' then 'H23' ELSE 'H3' END) as channel_h123,
                (CASE WHEN kp.produk='Parts' then 'HGP'
                WHEN kp.produk='Acc' then 'ACC&HGA' 
                WHEN kp.produk='Oil' then 'OIL' 
                when A.kelompok_part ='TL' then 'Tools' ELSE '-' END) as klp_part,
                B.id_part, A.nama_part, A.kelompok_part, B.qty, B.harga_beli, B.no_nsc, C.referensi
                from tr_h23_nsc_parts B 
                JOIN ms_part A ON A.id_part_int=B.id_part_int 
                join tr_h23_nsc C ON C.no_nsc=B.no_nsc 
                join ms_dealer md ON md.id_dealer=C.id_dealer
                LEFT JOIN ms_h3_md_setting_kelompok_produk kp on kp.id_kelompok_part_int=A.kelompok_part_int
                where C.tgl_nsc BETWEEN '$start_date' and '$end_date' $filter_dealer ");
            return $ps_details;
        }

        public function sl_penjualan_dealer($id_dealer,$start_date,$end_date)
        {
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = "where C.id_dealer='$id_dealer'";
            }

            $sl_penjualan_dealer = $this->db->query("SELECT C.id_dealer,md.nama_dealer,
                SUM(CASE WHEN (C.tgl_nsc >= MAKEDATE(year('$start_date'),1) and C.tgl_nsc<='$end_date' and B.tipe_diskon='Percentage') then (B.harga_beli*B.qty)-((B.harga_beli*B.qty)*(B.diskon_value/100))
				 WHEN (C.tgl_nsc >= MAKEDATE(year('$start_date'),1) and C.tgl_nsc<='$end_date') then ((B.harga_beli*B.qty)-B.diskon_value) else 0 end) as ytd
                from tr_h23_nsc_parts B 
                join tr_h23_nsc C ON C.no_nsc=B.no_nsc 
                join ms_dealer md on md.id_dealer=C.id_dealer 
                $filter_dealer 
                group by C.id_dealer");
            return $sl_penjualan_dealer;
        }

        public function sl_penjualan_grouping_parts($id_dealer,$start_date,$end_date)
        {
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = "and C.id_dealer='$id_dealer'";
            }

            $sl_penjualan_grouping_parts = $this->db->query("SELECT A.kelompok_part, 
				(CASE WHEN B.tipe_diskon ='Percentage' then (B.harga_beli*B.qty)-((B.harga_beli*B.qty)*(B.diskon_value/100)) else((B.harga_beli*B.qty)-B.diskon_value) END)  as pendapatan
				 from tr_h23_nsc_parts B JOIN ms_part A ON A.id_part=B.id_part 
				 join tr_h23_nsc C ON C.no_nsc=B.no_nsc 
				 where C.tgl_nsc >= '$start_date' and C.tgl_nsc <='$end_date' $filter_dealer
				 GROUP BY A.kelompok_part 
                 ORDER BY pendapatan DESC LIMIT 10");
            return $sl_penjualan_grouping_parts;
        }

        public function sl_details2($id_dealer,$start_date,$end_date)
        {
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = "and C.id_dealer='$id_dealer'";
            }

            $sl_details = $this->db->query("SELECT md.kode_dealer_md, md.nama_dealer, 
            (CASE WHEN md.h1='1' and md.h2='1' and md.h3='1' then 'H123' 
            WHEN md.h1='0' and md.h2='1' and md.h3='1' then 'H23' ELSE 'H3' END) as channel_h123,
            (CASE WHEN kp.produk='Parts' then 'HGP' 
				 WHEN kp.produk='Acc' then 'ACC&HGA' WHEN kp.produk='Oil' then 'Oil' 
				 when A.kelompok_part ='TL' then 'Tools' else '-' END) as klp_part,
            (CASE WHEN C.referensi = 'work_order' then 'Work Order' ELSE 'Sales' END) as referensi, A.nama_part, A.kelompok_part, SUM(B.qty) as qty, B.harga_beli, A.id_part 
            from tr_h23_nsc_parts B 
            JOIN ms_part A ON A.id_part_int=B.id_part_int 
            join tr_h23_nsc C ON C.no_nsc=B.no_nsc 
            join ms_dealer md ON md.id_dealer=C.id_dealer 
            LEFT JOIN ms_h3_md_setting_kelompok_produk kp on kp.id_kelompok_part_int=A.kelompok_part_int 
            where C.tgl_nsc BETWEEN '$start_date' and '$end_date' $filter_dealer
            group by md.id_dealer, A.id_part, C.referensi");
            return $sl_details;
        }

        public function sl_details($id_dealer,$start_date,$end_date)
        {
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = "and nsc.id_dealer='$id_dealer'";
            }

            $sl_details = $this->db->query("SELECT 
            md.nama_dealer, (CASE WHEN nsc.referensi='work_order' THEN 'WO' ELSE 'Direct Sales' END) AS referensi, md.kode_dealer_ahm, skp.produk , mp.kelompok_part, nscp.id_part, mp.nama_part, SUM(nscp.qty) as kuantitas, 
            nscp.harga_beli, (CASE WHEN md.h1 = 1 and md.h2 = 1 and md.h3 = 1 then 'H123' WHEN md.h1 = 0 and md.h2 = 1 and md.h3 = 1 THEN 'H23' 
                WHEN md.h1 = 0 and md.h2 = 0 and md.h3 = 1 THEN 'H3' ELSE '-' END) as status, SUM(CASE WHEN nscp.tipe_diskon='Percentage' 
                then ((nscp.harga_beli*nscp.qty)-(nscp.harga_beli*nscp.diskon_value/100))
                WHEN nscp.tipe_diskon='Value' then ((nscp.harga_beli*nscp.qty)-nscp.diskon_value) ELSE nscp.harga_beli*nscp.qty END) as total_penjualan
                FROM tr_h23_nsc nsc
                JOIN tr_h23_nsc_parts nscp on nscp.no_nsc=nsc.no_nsc 
                JOIN ms_part mp on mp.id_part_int=nscp.id_part_int 
                JOIN ms_h3_md_setting_kelompok_produk skp on skp.id_kelompok_part=mp.kelompok_part 
                JOIN ms_dealer md on md.id_dealer=nsc.id_dealer
                where nsc.created_at >='$start_date 00:00:00' and nsc.created_at<='$end_date 23:59:59' $filter_dealer
                and mp.kelompok_part !='FED OIL' 
                GROUP BY nsc.id_dealer, mp.id_part, nsc.referensi
                ORDER BY md.nama_dealer");
            return $sl_details;
        }

        public function sl_dealer($id_dealer,$start_date,$end_date)
        {
            $filter_dealer = '';
            $filter_dealer2 ='' ;
            if ($id_dealer!='all') {
                 $filter_dealer = "and ds.id_dealer='$id_dealer'";
            }

            $filter_toko = '';
            if(!$this->config->item('ahass_only')){
                $filter_toko = 'AND md.h2=1 and md.active=1 and LENGTH (md.kode_dealer_ahm)=5';
            }

            $sl_dealer = $this->db->query("SELECT ds.id_dealer, ds.id_part, skp.produk, mp.kelompok_part, SUM(ds.stock) as stock, mp.harga_md_dealer, md.nama_dealer, (CASE WHEN md.h1 = 1 and md.h2 = 1 and md.h3 = 1 then 'H123' WHEN md.h1 = 0 and md.h2 = 1 and md.h3 = 1 THEN 'H23' 
            WHEN md.h1 = 0 and md.h2 = 0 and md.h3 = 1 THEN 'H3' ELSE '-' END) as status
                FROM ms_h3_dealer_stock ds 
                join ms_part mp on mp.id_part_int=ds.id_part_int 
                join ms_h3_md_setting_kelompok_produk skp on skp.id_kelompok_part=mp.kelompok_part
                join ms_dealer md on md.id_dealer=ds.id_dealer 
                WHERE mp.kelompok_part != 'FED OIL' $filter_dealer $filter_toko
                GROUP BY mp.id_part_int, ds.id_dealer ");
            return $sl_dealer;
        }

        public function sl_grouping_parts($id_dealer,$start_date,$end_date)
        {
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = "and C.id_dealer='$id_dealer'";
            }

            $sl_grouping_parts = $this->db->query("SELECT A.kelompok_part, 
				 count(1)  as total_part
				 from tr_h23_nsc_parts B JOIN ms_part A ON A.id_part=B.id_part 
				 join tr_h23_nsc C ON C.no_nsc=B.no_nsc 
				 where C.tgl_nsc >= '$start_date' and C.tgl_nsc <='$end_date' $filter_dealer
				 GROUP BY A.kelompok_part 
				 ORDER BY total_part DESC LIMIT 10");
            return $sl_grouping_parts;
        }
        
        public function hlo_service_rate($id_dealer,$start_date,$end_date)
        {
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = "and po.id_dealer='$id_dealer'";
            }

            // $hlo_service_rate = $this->db->query("SELECT COUNT(po.po_id) as qty_order , (
            //     SELECT COUNT(DISTINCT (po.po_id)) as fulfilled_order
            //     FROM tr_h3_dealer_purchase_order po 
            //     join tr_h3_dealer_good_receipt gr on gr.nomor_po=po.po_id 
            //     where po.po_type='HLO'
            //     and po.created_at >= '$start_date' and po.created_at <='$end_date' $filter_dealer
            //     ) as fulfill_order
            //     from tr_h3_dealer_purchase_order po 
            //     where po.po_type='HLO'
            //     and po.created_at >= '$start_date' and po.created_at <='$end_date' $filter_dealer ");
            $hlo_service_rate = $this->db->query("SELECT po.po_id, date_format(po.tanggal_order ,'%m-%Y') as bulantahun, SUM(pop.kuantitas) as qty_order
				from tr_h3_dealer_purchase_order po 
				left join tr_h3_dealer_purchase_order_parts pop on po.po_id=pop.po_id 
				where po.po_type='HLO' and po.tanggal_order >= DATE_SUB('$end_date', interval 5 month) and po.tanggal_order <='$end_date' $filter_dealer
				GROUP BY bulantahun");
            return $hlo_service_rate;
        }

        public function hlo_service_rate_fulfilled($id_dealer,$start_date,$end_date)
        {
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = "and po.id_dealer='$id_dealer'";
            }

            $hlo_service_rate = $this->db->query("SELECT date_format(po.tanggal_order ,'%m-%Y') as bulantahun, SUM(dof.qty_fulfillment) as fulfilled_order
            from tr_h3_dealer_purchase_order po  
            left join tr_h3_dealer_order_fulfillment dof on po.po_id=dof.po_id 
            where po.po_type='HLO' and po.tanggal_order >= DATE_SUB('$end_date', interval 5 month) and po.tanggal_order <='$end_date' $filter_dealer
            GROUP BY bulantahun");
            return $hlo_service_rate;
        }

        public function hlo_kota($id_dealer,$start_date,$end_date)
        {
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = "and po.id_dealer='$id_dealer'";
            }

            $hlo_kota = $this->db->query(" SELECT COUNT(1) as value_kota, mkeb.kabupaten 
                FROM tr_h3_dealer_request_document rd 
                JOIN tr_h3_dealer_purchase_order po on rd.id_booking=po.id_booking 
                JOIN ms_customer_h23 cust on cust.id_customer=rd.id_customer 
                LEFT JOIN tr_h3_dealer_purchase_order_parts pop on pop.po_id_int=po.id 
                JOIN ms_kelurahan mk on mk.id_kelurahan = cust.id_kelurahan 
                JOIN ms_kecamatan mkec on mkec.id_kecamatan=mk.id_kecamatan 
                JOIN ms_kabupaten mkeb on mkeb.id_kabupaten =mkec.id_kabupaten 
                WHERE po.tanggal_order >= '$start_date' and po.tanggal_order <='$end_date'
                $filter_dealer
                GROUP BY mkeb.id_kabupaten");
            return $hlo_kota;
        }

        public function hlo_dealer($id_dealer,$start_date,$end_date)
        {
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = "and po.id_dealer='$id_dealer'";
            }

            $hlo_dealer = $this->db->query("SELECT po.id, pop.id_part_int, md.nama_dealer, mkeb.kabupaten, mtk.id_series, DATE_FORMAT(mtk.tgl_awal,'%Y') as production_year, pop.id_part, mp.nama_part, mp.kelompok_part, skp.produk, pop.kuantitas,
            -- , ifnull(dof.qty_fulfillment,0) as qty_fulfil, ifnull(DATE_FORMAT(dof.created_at,'%d/%m/%Y'),'') as tgl_pemenuhan,
            DATE_FORMAT(po.tanggal_order,'%d/%m/%Y') as tanggal_order, po.tanggal_order as tgl_order
            -- po.tanggal_order as tanggal_order
                FROM tr_h3_dealer_purchase_order po 
                JOIN tr_h3_dealer_purchase_order_parts pop on pop.po_id_int=po.id
                JOIN tr_h3_dealer_request_document rd on rd.id=po.id_booking_int 
                JOIN ms_customer_h23 mch on mch.id_customer=rd.id_customer 
                JOIN ms_dealer md on md.id_dealer=po.id_dealer 
                JOIN ms_kelurahan mk on mk.id_kelurahan = mch.id_kelurahan 
                JOIN ms_kecamatan mkec on mkec.id_kecamatan=mk.id_kecamatan 
                JOIN ms_kabupaten mkeb on mkeb.id_kabupaten =mkec.id_kabupaten 
                JOIN ms_tipe_kendaraan mtk on mtk.id_tipe_kendaraan=mch.id_tipe_kendaraan 
                JOIN ms_part mp on mp.id_part_int=pop.id_part_int 
                JOIN ms_h3_md_setting_kelompok_produk skp on skp.id_kelompok_part=mp.kelompok_part 
                -- LEFT JOIN tr_h3_dealer_order_fulfillment dof on dof.po_id_int=po.id and pop.id_part_int=dof.id_part_int 
                WHERE po.po_type ='HLO' and po.status IN ('Submitted','Closed', 'Processing', 'Processing by MD') and mp.kelompok_part !='FED OIL' 
                and po.created_at >= '$start_date 00:00:00' and po.created_at <= '$end_date 23:59:59'  $filter_dealer
                ");
            return $hlo_dealer;
        }

        public function hlo_status_order_fulfilled($id_dealer,$start_date,$end_date)
        {
            $filter_dealer = '';
            $filter_dealer_gr = '';
            if ($id_dealer!='all') {
                 $filter_dealer = "and po.id_dealer='$id_dealer'";
                 $filter_dealer_gr = " and gr.id_dealer='$id_dealer'";
            }
            // if ($id_dealer!='all') {
            //     $filter_dealer_gr = "gr.id_dealer='$id_dealer'";
            // }

            $hlo_status_order_fulfilled = $this->db->query("SELECT sum(grp.qty) as fulfilled
                from tr_h3_dealer_good_receipt gr 
                left join tr_h3_dealer_good_receipt_parts grp on grp.id_good_receipt=gr.id_good_receipt 
                where gr.nomor_po IN (
                select po.po_id from tr_h3_dealer_purchase_order po where po.tanggal_order >= '$start_date' and po.tanggal_order <= '$end_date'
                AND po.po_type='HLO' $filter_dealer) $filter_dealer_gr ");
            return $hlo_status_order_fulfilled;
        }
        

        public function hlo_status_order_all($id_dealer,$start_date,$end_date)
        {
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = "and po.id_dealer='$id_dealer'";
            }
            // if ($id_dealer!='all') {
            //     $filter_dealer_gr = "gr.id_dealer='$id_dealer'";
            // }

            $hlo_status_order_all = $this->db->query("SELECT sum(pop.kuantitas) as total 
            from tr_h3_dealer_purchase_order po 
            left join tr_h3_dealer_purchase_order_parts pop on pop.po_id=po.po_id 
            where po.tanggal_order >= '$start_date' and po.tanggal_order <= '$end_date'
            AND po.po_type='HLO' $filter_dealer");
            return $hlo_status_order_all;
        }

        public function hlo_series($id_dealer,$start_date,$end_date)
        {
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = "and po.id_dealer='$id_dealer'";
            }

            $hlo_series = $this->db->query("SELECT sum(pop.kuantitas) as sum_qty, mp.deskripsi 
                FROM tr_h3_dealer_request_document rd 
                JOIN tr_h3_dealer_purchase_order po on rd.id_booking=po.id_booking 
                JOIN ms_customer_h23 cust on cust.id_customer=rd.id_customer 
                LEFT JOIN tr_h3_dealer_purchase_order_parts pop on pop.po_id_int=po.id 
                JOIN ms_ptm mp on mp.tipe_marketing=cust.id_tipe_kendaraan 
                WHERE po.tanggal_order >= '$start_date' and po.tanggal_order <='$end_date' and po.po_type='HLO' $filter_dealer
                GROUP BY mp.tipe_produksi ORDER BY sum_qty DESC LIMIT 10");
            return $hlo_series;
        }

        // public function hlo_production_year($id_dealer,$start_date,$end_date)
        // {
        //     $filter_dealer = '';
        //     if ($id_dealer!='all') {
        //          $filter_dealer = "and po.id_dealer='$id_dealer'";
        //     }

        //     $hlo_tahun_perakitan = $this->db->query("SELECT count(pop.id_part_int) as sum_qty, mp.deskripsi 
        //         FROM tr_h3_dealer_request_document rd 
        //         JOIN tr_h3_dealer_purchase_order po on rd.id_booking=po.id_booking 
        //         JOIN ms_customer_h23 cust on cust.id_customer=rd.id_customer 
        //         LEFT JOIN tr_h3_dealer_purchase_order_parts pop on pop.po_id_int=po.id 
        //         JOIN ms_ptm mp on mp.tipe_marketing=cust.id_tipe_kendaraan 
        //         WHERE po.created_at >= '$start_date' and po.created_at <='$end_date'
        //         $filter_dealer
        //         GROUP BY mp.tipe_produksi");
        //     return $hlo_tahun_perakitan;
        // }

        public function hlo_grouping_parts($id_dealer,$start_date,$end_date)
        {
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = "and po.id_dealer='$id_dealer'";
            }

            $hlo_grouping_parts = $this->db->query("SELECT sum(pop.kuantitas) as sum_qty, mp.nama_part 
                FROM tr_h3_dealer_request_document rd 
                JOIN tr_h3_dealer_purchase_order po on rd.id_booking=po.id_booking 
                JOIN ms_customer_h23 cust on cust.id_customer=rd.id_customer 
                LEFT JOIN tr_h3_dealer_purchase_order_parts pop on pop.po_id_int=po.id 
                JOIN ms_part mp on mp.id_part_int=pop.id_part_int 
                WHERE po.tanggal_order >= '$start_date' and po.tanggal_order <='$end_date'
                $filter_dealer
                GROUP BY pop.id_part_int 
                ORDER BY sum_qty DESC LIMIT 10");
            return $hlo_grouping_parts;
        }

        public function hlo_lead_time_fulfillment($id_dealer,$start_date,$end_date)
        {
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = "and po.id_dealer='$id_dealer'";
            }

            $hlo_lead_time_fulfillment = $this->db->query("SELECT SUM(CASE WHEN DATEDIFF(gr.created_at, po.tanggal_order)<= 7 THEN 1 ELSE 0 END) as 'kurang_dr_1', 
            SUM(CASE WHEN DATEDIFF(gr.created_at, po.tanggal_order)>= 8 AND DATEDIFF(gr.created_at, po.tanggal_order)<= 14 THEN 1 ELSE 0 END) as 'minggu_1_2',
            SUM(CASE WHEN DATEDIFF(gr.created_at, po.tanggal_order)>= 15 AND DATEDIFF(gr.created_at, po.tanggal_order)<= 28 THEN 1 ELSE 0 END) as 'minggu_3_4',
            SUM(CASE WHEN DATEDIFF(gr.created_at, po.tanggal_order)>= 29 AND DATEDIFF(gr.created_at, po.tanggal_order)<= 42 THEN 1 ELSE 0 END) as 'minggu_5_6',
            SUM(CASE WHEN DATEDIFF(gr.created_at, po.tanggal_order)>=43 THEN 1 ELSE 0 END) as 'lebih_dr_8'
            FROM tr_h3_dealer_purchase_order po 
            JOIN tr_h3_dealer_good_receipt gr on gr.nomor_po=po.po_id  
            left join tr_h3_dealer_purchase_order_parts pop on pop.po_id=po.po_id 
            left join tr_h3_dealer_good_receipt_parts grp on grp.id_good_receipt=gr.id_good_receipt  and grp.id_part_int=pop.id_part_int 
            WHERE po.tanggal_order >= '$start_date' and po.tanggal_order <= '$end_date' and grp.qty >0 and po.po_type='HLO' $filter_dealer");
            return $hlo_lead_time_fulfillment;
        }

        // public function hlo_outstanding_fulfilled($id_dealer,$start_date,$end_date)
        // {
        //     $filter_dealer = '';
        //     $filter_dealer_gr = '';
        //     if ($id_dealer!='all') {
        //          $filter_dealer = "and po.id_dealer='$id_dealer'";
        //          $filter_dealer_gr = " WHERE gr.id_dealer='$id_dealer'";
        //     }

        //     // $hlo_outstanding = $this->db->query(" SELECT SUM(CASE WHEN DATEDIFF(NOW(), po.created_at)<= 7 THEN 1 ELSE 0 END) 'kurang_dr_1', 
        //     //     SUM(CASE WHEN DATEDIFF(NOW(), po.created_at)>= 8 AND DATEDIFF(NOW(), po.created_at)<= 14 THEN 1 ELSE 0 END) 'minggu_1_2',
        //     //     SUM(CASE WHEN DATEDIFF(NOW(), po.created_at)>= 15 AND DATEDIFF(NOW(), po.created_at)<= 28 THEN 1 ELSE 0 END) 'minggu_3_4',
        //     //     SUM(CASE WHEN DATEDIFF(NOW(), po.created_at)>= 29 AND DATEDIFF(NOW(), po.created_at)<= 42 THEN 1 ELSE 0 END) 'minggu_5_6',
        //     //     SUM(CASE WHEN DATEDIFF(NOW(), po.created_at)>=43 THEN 1 ELSE 0 END) 'lebih_dr_8'
        //     //     FROM tr_h3_dealer_purchase_order po 
        //     //     WHERE po.created_at >= '$start_date' and po.created_at <= '$end_date'
        //     //     AND po.po_type='HLO' 
        //     //     AND po.po_id NOT IN (SELECT gr.nomor_po 
        //     //     FROM tr_h3_dealer_good_receipt gr 
        //     //     $filter_dealer_gr
        //     //     group by gr.nomor_po )
        //     //     $filter_dealer");
        //     $hlo_outstanding_fulfilled = $this->db->query("SELECT SUM(CASE WHEN DATEDIFF(NOW(), po.created_at)<= 7 THEN 1 ELSE 0 END) 'kurang_dr_1', 
        //     SUM(CASE WHEN DATEDIFF(NOW(), po.created_at)>= 8 AND DATEDIFF(NOW(), po.created_at)<= 14 THEN 1 ELSE 0 END) 'minggu_1_2',
        //     SUM(CASE WHEN DATEDIFF(NOW(), po.created_at)>= 15 AND DATEDIFF(NOW(), po.created_at)<= 28 THEN 1 ELSE 0 END) 'minggu_3_4',
        //     SUM(CASE WHEN DATEDIFF(NOW(), po.created_at)>= 29 AND DATEDIFF(NOW(), po.created_at)<= 42 THEN 1 ELSE 0 END) 'minggu_5_6',
        //     SUM(CASE WHEN DATEDIFF(NOW(), po.created_at)>=43 THEN 1 ELSE 0 END) 'lebih_dr_8'
        //     FROM tr_h3_dealer_purchase_order po 
        //     WHERE po.created_at >= '$start_date' and po.created_at <= '$end_date'
        //     AND po.po_type='HLO' 
        //     AND po.po_id IN (SELECT gr.nomor_po 
        //     FROM tr_h3_dealer_good_receipt gr 
        //     $filter_dealer_gr
        //     group by gr.nomor_po )
        //     $filter_dealer");

        //     return $hlo_outstanding_fulfilled;
        // }

        public function hlo_outstanding_belum_dipenuhi_semua($id_dealer,$start_date,$end_date){
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = "and po.id_dealer='$id_dealer'";
                 
            }
            $hlo_outstanding_belum_dipenuhi_semua = $this->db->query("SELECT SUM(CASE WHEN DATEDIFF(NOW(), po.tanggal_order)<= 7 THEN 1 ELSE 0 END) 'kurang_dr_1', 
            SUM(CASE WHEN DATEDIFF(NOW(), po.tanggal_order)>= 8 AND DATEDIFF(NOW(), po.tanggal_order)<= 14 THEN 1 ELSE 0 END) 'minggu_1_2',
            SUM(CASE WHEN DATEDIFF(NOW(), po.tanggal_order)>= 15 AND DATEDIFF(NOW(), po.tanggal_order)<= 28 THEN 1 ELSE 0 END) 'minggu_3_4',
            SUM(CASE WHEN DATEDIFF(NOW(), po.tanggal_order)>= 29 AND DATEDIFF(NOW(), po.tanggal_order)<= 42 THEN 1 ELSE 0 END) 'minggu_5_6',
                SUM(CASE WHEN DATEDIFF(NOW(), po.tanggal_order)>=43 THEN 1 ELSE 0 END) 'lebih_dr_8'
            from tr_h3_dealer_purchase_order po
            where po.po_type='HLO' and po.tanggal_order >= '$start_date' and po.tanggal_order <= '$end_date'
            and (select sum(dof.qty_fulfillment) from tr_h3_dealer_order_fulfillment dof where dof.po_id=po.po_id) < (select sum(pop.kuantitas) from tr_h3_dealer_purchase_order_parts pop where pop.po_id=po.po_id) $filter_dealer
            ");
            return $hlo_outstanding_belum_dipenuhi_semua;
        }

        public function hlo_outstanding_belum_dipenuhi($id_dealer,$start_date,$end_date)
        {
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = "and po.id_dealer='$id_dealer'";
                 
            }
            $hlo_outstanding_belum_dipenuhi = $this->db->query("SELECT SUM(CASE WHEN DATEDIFF(NOW(), po.tanggal_order)<= 7 THEN 1 ELSE 0 END) 'kurang_dr_1', 
            SUM(CASE WHEN DATEDIFF(NOW(), po.tanggal_order)>= 8 AND DATEDIFF(NOW(), po.tanggal_order)<= 14 THEN 1 ELSE 0 END) 'minggu_1_2',
            SUM(CASE WHEN DATEDIFF(NOW(), po.tanggal_order)>= 15 AND DATEDIFF(NOW(), po.tanggal_order)<= 28 THEN 1 ELSE 0 END) 'minggu_3_4',
            SUM(CASE WHEN DATEDIFF(NOW(), po.tanggal_order)>= 29 AND DATEDIFF(NOW(), po.tanggal_order)<= 42 THEN 1 ELSE 0 END) 'minggu_5_6',
                SUM(CASE WHEN DATEDIFF(NOW(), po.tanggal_order)>=43 THEN 1 ELSE 0 END) 'lebih_dr_8'
            FROM tr_h3_dealer_purchase_order po
            WHERE po.tanggal_order >= '$start_date' and po.tanggal_order <= '$end_date'
            AND po.po_type='HLO'
            AND po.po_id NOT IN (
                select DISTINCT (dof.po_id) 
                from tr_h3_dealer_order_fulfillment dof
            ) $filter_dealer");
            return $hlo_outstanding_belum_dipenuhi;
        }

        public function hlo_outstanding_all($id_dealer,$start_date,$end_date)
        {
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = "and po.id_dealer='$id_dealer'";
                 
            }
            $hlo_outstanding_all = $this->db->query("SELECT SUM(CASE WHEN DATEDIFF(NOW(), po.tanggal_order)<= 7 THEN 1 ELSE 0 END) 'kurang_dr_1', 
            SUM(CASE WHEN DATEDIFF(NOW(), po.tanggal_order)>= 8 AND DATEDIFF(NOW(), po.tanggal_order)<= 14 THEN 1 ELSE 0 END) 'minggu_1_2',
            SUM(CASE WHEN DATEDIFF(NOW(), po.tanggal_order)>= 15 AND DATEDIFF(NOW(), po.tanggal_order)<= 28 THEN 1 ELSE 0 END) 'minggu_3_4',
            SUM(CASE WHEN DATEDIFF(NOW(), po.tanggal_order)>= 29 AND DATEDIFF(NOW(), po.tanggal_order)<= 42 THEN 1 ELSE 0 END) 'minggu_5_6',
                SUM(CASE WHEN DATEDIFF(NOW(), po.tanggal_order)>=43 THEN 1 ELSE 0 END) 'lebih_dr_8'
            from tr_h3_dealer_purchase_order po
            where po.po_type='HLO' and po.tanggal_order >= '$start_date' and po.tanggal_order <= '$end_date' and po.id_dealer=103 AND po.po_type='HLO' $filter_dealer");

            return $hlo_outstanding_all;
        }

        public function hlo_outstanding_details($id_dealer,$start_date,$end_date)
        {
            $filter_dealer = '';
            $filter_dealer_gr = '';
            if ($id_dealer!='all') {
                 $filter_dealer = "and po.id_dealer='$id_dealer'";
                 $filter_dealer_gr = " WHERE gr.id_dealer='$id_dealer'";
                
            }

            // $hlo_outstanding_details = $this->db->query("SELECT md.nama_dealer,
            //     DATEDIFF(NOW() , po.created_at) as outstanding_days,
            //     (CASE WHEN DATEDIFF(NOW(), po.created_at)<= 7 THEN 'Kurang dari 1 Minggu'
            //         WHEN DATEDIFF(NOW(), po.created_at)>= 8 AND DATEDIFF(NOW(), po.created_at)<= 14 THEN '1-2 Minggu' 
            //         WHEN DATEDIFF(NOW(), po.created_at)>= 15 AND DATEDIFF(NOW(), po.created_at)<= 28 THEN '3-4 Minggu' 
            //         WHEN DATEDIFF(NOW(), po.created_at)>= 29 AND DATEDIFF(NOW(), po.created_at)<= 42 THEN '5-6 Minggu'
            //         ELSE 'Lebih dari 8 Minggu' END) as outstanding, pop.id_part,mp.nama_part, pop.kuantitas ,po.created_at,
            //         mtk.tipe_ahm 
            //     FROM tr_h3_dealer_purchase_order po 
            //     JOIN ms_dealer md on po.id_dealer=md.id_dealer 
            //     LEFT JOIN tr_h3_dealer_purchase_order_parts pop on pop.po_id_int=po.id
            //     JOIN ms_part mp on mp.id_part_int=pop.id_part_int 
            //     JOIN tr_h3_dealer_request_document rd on rd.id=po.id_booking_int 
            //     LEFT JOIN ms_customer_h23 mch on mch.id_customer=rd.id_customer 
            //     JOIN ms_tipe_kendaraan mtk on mtk.id_tipe_kendaraan = mch.id_tipe_kendaraan 
            //     WHERE po.created_at >= '$start_date' and po.created_at <= '$end_date'
            //     AND po.po_type='HLO' 
            //     AND po.po_id NOT IN (SELECT gr.nomor_po 
            //     FROM tr_h3_dealer_good_receipt gr 
            //     WHERE  $filter_dealer_gr
            //     group by gr.nomor_po )
            //     $filter_dealer");
           $hlo_outstanding_details = $this->db->query("SELECT md.nama_dealer, pop.id_part, mp.nama_part, pop.kuantitas,po.tanggal_order as created_at, ptm.deskripsi as tipe_ahm, sum(dof.qty_fulfillment) as qty_fulfil
           , po.po_id, max(date_format(dof.created_at,'%d-%m-%Y')) as tgl_pemenuhan,
           DATEDIFF(NOW() , po.tanggal_order) as outstanding_days,
          (CASE WHEN DATEDIFF(NOW(), po.tanggal_order)<= 7 THEN 'Kurang dari 1 Minggu'
              WHEN DATEDIFF(NOW(), po.tanggal_order)>= 8 AND DATEDIFF(NOW(), po.tanggal_order)<= 14 THEN '1-2 Minggu' 
              WHEN DATEDIFF(NOW(), po.tanggal_order)>= 15 AND DATEDIFF(NOW(), po.tanggal_order)<= 28 THEN '3-4 Minggu' 
              WHEN DATEDIFF(NOW(), po.tanggal_order)>= 29 AND DATEDIFF(NOW(), po.tanggal_order)<= 42 THEN '5-6 Minggu'
              ELSE 'Lebih dari 8 Minggu' END) as outstanding
            from ms_customer_h23 mch 
            join tr_h3_dealer_request_document rd on rd.id_customer_int =mch.id_customer_int 
            join tr_h3_dealer_purchase_order po on po.id_booking=rd.id_booking 
            left join tr_h3_dealer_purchase_order_parts pop on pop.po_id=po.po_id 
            join ms_dealer md on md.id_dealer=po.id_dealer 
            join ms_part mp on mp.id_part_int=pop.id_part_int 
            left join tr_h3_dealer_order_fulfillment dof on dof.po_id=po.po_id and dof.id_part_int=pop.id_part_int
            join ms_ptm ptm on ptm.tipe_marketing=mch.id_tipe_kendaraan 
            WHERE po.po_type='HLO' and po.tanggal_order >= '$start_date' and po.tanggal_order <= '$end_date'  $filter_dealer
            group by po.po_id,pop.id_part");
           return $hlo_outstanding_details;
        }

        public function hlo_production_year($id_dealer,$start_date,$end_date)
        {
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = "and b.id_dealer='$id_dealer'";
                
            }
            $hlo_production_year = $this->db->query("SELECT mch.tahun_produksi, SUM(pop.kuantitas) as qty
            FROM tr_h3_dealer_request_document a
            JOIN tr_h3_dealer_purchase_order b on a.id=b.id_booking_int 
            LEFT JOIN tr_h3_dealer_purchase_order_parts pop on pop.po_id=b.po_id 
            JOIN ms_customer_h23 mch on a.id_customer=mch.id_customer 
            WHERE mch.tahun_produksi >= YEAR(DATE_SUB('$end_date', INTERVAL 5 YEAR)) and mch.tahun_produksi <=  YEAR('$end_date')
            $filter_dealer
            GROUP BY mch.tahun_produksi");
            return $hlo_production_year;
        }

        public function ps_production_year($id_dealer,$start_date,$end_date)
        {
            $filter_dealer = '';
            if ($id_dealer!='all') {
                 $filter_dealer = "and wo.id_dealer='$id_dealer'";
                
            }
            $ps_production_year = $this->db->query("SELECT mch.tahun_produksi, SUM(part.qty) as qty
            FROM ms_customer_h23 mch 
            JOIN tr_h2_sa_form sa on sa.id_customer=mch.id_customer 
            JOIN tr_h2_wo_dealer wo on wo.id_sa_form=sa.id_sa_form 
            JOIN tr_h23_nsc nsc on nsc.id_referensi=wo.id_work_order 
            LEFT JOIN tr_h23_nsc_parts part on part.no_nsc=nsc.no_nsc 
            WHERE mch.tahun_produksi >= YEAR(DATE_SUB('$end_date', INTERVAL 5 YEAR)) and mch.tahun_produksi <=  YEAR('$end_date')
            $filter_dealer
            GROUP BY mch.tahun_produksi ");
            return $ps_production_year;
        }
	}	
?>