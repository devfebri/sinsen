<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class H3_md_laporan_performance_ahass_model extends CI_Model{
		public function __construct()
        {
            parent::__construct();
        }

		public function getDataDealer()
		{
			$query=$this->db->query("SELECT id_dealer,kode_dealer_ahm,nama_dealer from ms_dealer where id_dealer in('1','2','4','5','6','8','10','13','18','19','22','23','25','28','29','30','38','39','40','41','43','44','46','47','51','54','56','58','64','65','66','69','70','71','74','76','77','78','80','81','82','83','84','85','86','88','90','91','94','96','97','98','101','102','103','104','105','106','107','128','714','715','716')");
			return $query->result();
		}

		//Ini berelasi dengan Laporan Pendapatan Harian Servis v1
		//Laporan ini berkaitan dengan Standard Report H23
		public function laporan_performance_ahass($id_dealer,$start_date,$end_date){
			$filter_dealer = '';
          		if ($id_dealer!='all') {
           			$filter_dealer = " AND wo.id_dealer='$id_dealer'";
         		}

			$report = $this->db->query("SELECT md.nama_dealer, (CASE WHEN md.h1 = 1 and md.h2='1' and md.h3='1' then 'H123' WHEN md.h1=0 and md.h2=1 and md.h3=1 then 'H23' WHEN md.h1=0 and md.h2=0 and md.h3=1 then 'H3'
				else '-' end ) as status, mkab.kabupaten, SUM(CASE WHEN sa.id_type = 'ASS1' then 1 else 0 END) as ue_kpb_1,
				SUM(CASE WHEN sa.id_type = 'ASS2' then 1 else 0 END) as ue_kpb_2,
				SUM(CASE WHEN sa.id_type = 'ASS3' then 1 else 0 END) as ue_kpb_3,
				SUM(CASE WHEN sa.id_type = 'ASS4' then 1 else 0 END) as ue_kpb_4,
				SUM(CASE WHEN sa.id_type = 'OR+' then 1 else 0 END) as ue_go, SUM(CASE WHEN sa.id_type not in ('ASS1','ASS2','ASS3','ASS4', 'OR+') then 1 else 0 END) as ue_non_kpb, 
				COUNT(sa.id_type) as total_ue, wo.id_dealer
				FROM tr_h2_wo_dealer wo
				JOIN tr_h2_sa_form sa on sa.id_sa_form=wo.id_sa_form 
				JOIN ms_dealer md on wo.id_dealer=md.id_dealer 
				JOIN ms_kelurahan mk on mk.id_kelurahan=md.id_kelurahan 
				JOIN ms_kecamatan mk2 on mk2.id_kecamatan=mk.id_kecamatan 
				JOIN ms_kabupaten mkab on mkab.id_kabupaten=mk2.id_kabupaten 
				WHERE wo.status ='Closed' and left(wo.closed_at,10) >= '$start_date' and left(wo.closed_at,10) <= '$end_date' $filter_dealer 
				GROUP BY wo.id_dealer 
				ORDER BY md.nama_dealer ASC");
			return $report;
		}

		public function laporan_penjualan_part_selling($id_dealer,$start_date,$end_date){
			$filter_dealer = '';
          		if ($id_dealer!='all') {
           			$filter_dealer = " AND so.id_dealer='$id_dealer'";
         		}

			$report = $this->db->query("SELECT so.id_dealer, md.nama_dealer, (CASE WHEN md.h1 = 1 and md.h2='1' and md.h3='1' then 'H123' WHEN md.h1=0 and md.h2=1 and md.h3=1 then 'H23' WHEN md.h1=0 and md.h2=0 and md.h3=1 then 'H3'
			else '-' end ) as status, mkab.kabupaten, mkar.nama_lengkap, SUM(dso.total) as total 
				FROM tr_h3_md_sales_order so
				JOIN tr_h3_md_do_sales_order dso on dso.id_sales_order_int=so.id 
				JOIN tr_h3_md_picking_list pl on dso.id = pl.id_ref_int 
				JOIN tr_h3_md_packing_sheet ps on ps.id_picking_list_int=pl.id 
				JOIN ms_dealer md on md.id_dealer=so.id_dealer 
				JOIN ms_kelurahan mk on mk.id_kelurahan=md.id_kelurahan 
				JOIN ms_kecamatan mk2 on mk2.id_kecamatan=mk.id_kecamatan 
				JOIN ms_kabupaten mkab on mkab.id_kabupaten=mk2.id_kabupaten 
				LEFT JOIN ms_karyawan mkar on mkar.id_karyawan=so.id_salesman 
				WHERE left(ps.tgl_faktur,10) >='$start_date' and left(ps.tgl_faktur,10) <='$end_date' and so.produk='Parts' $filter_dealer
				GROUP BY so.id_dealer, so.id_salesman 
				ORDER BY md.nama_dealer ASC");
			return $report;
		}

		public function laporan_penjualan_oil_amount($id_dealer,$start_date,$end_date){
			$filter_dealer = '';
          		if ($id_dealer!='all') {
           			$filter_dealer = " AND so.id_dealer='$id_dealer'";
         		}

			$report = $this->db->query("SELECT so.id_dealer, md.nama_dealer, (CASE WHEN md.h1 = 1 and md.h2='1' and md.h3='1' then 'H123' WHEN md.h1=0 and md.h2=1 and md.h3=1 then 'H23' WHEN md.h1=0 and md.h2=0 and md.h3=1 then 'H3'
			else '-' end ) as status, mkab.kabupaten, mkar.nama_lengkap, 
			SUM(CASE WHEN mp.kelompok_part='OIL' then (dsop.harga_setelah_diskon*dsop.qty_supply) ELSE 0 END) as oil, 
			SUM(CASE WHEN mp.kelompok_part='GMO' then (dsop.harga_setelah_diskon*dsop.qty_supply) ELSE 0 END) as gmo
			FROM tr_h3_md_sales_order so 
			JOIN tr_h3_md_do_sales_order dso on dso.id_sales_order_int=so.id 
			JOIN tr_h3_md_do_sales_order_parts dsop on dsop.id_do_sales_order_int=dso.id 
			JOIN tr_h3_md_picking_list pl on dso.id = pl.id_ref_int 
			JOIN tr_h3_md_packing_sheet ps on ps.id_picking_list_int=pl.id 
			JOIN ms_dealer md on md.id_dealer=so.id_dealer 
			JOIN ms_kelurahan mk on mk.id_kelurahan=md.id_kelurahan 
			JOIN ms_kecamatan mk2 on mk2.id_kecamatan=mk.id_kecamatan 
			JOIN ms_kabupaten mkab on mkab.id_kabupaten=mk2.id_kabupaten 
			LEFT JOIN ms_karyawan mkar on mkar.id_karyawan=so.id_salesman 
			JOIN ms_part mp on mp.id_part_int=dsop.id_part_int 
			WHERE left(ps.tgl_faktur,10) >='$start_date' and left(ps.tgl_faktur,10) <='$end_date' $filter_dealer
			GROUP BY so.id_dealer, so.id_salesman 
			HAVING oil > 0 or gmo > 0
			ORDER BY md.nama_dealer ASC");
			return $report;
		}

		public function laporan_penjualan_oil_botol($id_dealer,$start_date,$end_date){
			$filter_dealer = '';
          		if ($id_dealer!='all') {
           			$filter_dealer = " AND so.id_dealer='$id_dealer'";
         		}

			$report = $this->db->query("SELECT so.id_dealer, md.nama_dealer, (CASE WHEN md.h1 = 1 and md.h2='1' and md.h3='1' then 'H123' WHEN md.h1=0 and md.h2=1 and md.h3=1 then 'H23' WHEN md.h1=0 and md.h2=0 and md.h3=1 then 'H3'
			else '-' end ) as status, mkab.kabupaten, mkar.nama_lengkap, 
			SUM(CASE WHEN mp.kelompok_part='OIL' then plp.qty_supply ELSE 0 END) as oil, 
			SUM(CASE WHEN mp.kelompok_part='GMO' then plp.qty_supply ELSE 0 END) as gmo
			FROM tr_h3_md_sales_order so 
			JOIN tr_h3_md_do_sales_order dso on dso.id_sales_order_int=so.id 
			JOIN tr_h3_md_do_sales_order_parts dsop on dsop.id_do_sales_order_int=dso.id 
			JOIN tr_h3_md_picking_list pl on dso.id = pl.id_ref_int 
			JOIN tr_h3_md_picking_list_parts plp on plp.id_picking_list_int=pl.id and plp.id_part_int=dsop.id_part_int 
			JOIN tr_h3_md_packing_sheet ps on ps.id_picking_list_int=pl.id 
			JOIN ms_dealer md on md.id_dealer=so.id_dealer 
			JOIN ms_kelurahan mk on mk.id_kelurahan=md.id_kelurahan 
			JOIN ms_kecamatan mk2 on mk2.id_kecamatan=mk.id_kecamatan 
			JOIN ms_kabupaten mkab on mkab.id_kabupaten=mk2.id_kabupaten 
			LEFT JOIN ms_karyawan mkar on mkar.id_karyawan=so.id_salesman 
			JOIN ms_part mp on mp.id_part_int=plp.id_part_int 
			WHERE left(ps.tgl_faktur,10) >='$start_date' and left(ps.tgl_faktur,10) <='$end_date' $filter_dealer
			GROUP BY so.id_dealer, so.id_salesman 
			HAVING oil > 0 or gmo > 0
			ORDER BY md.nama_dealer ASC");
			return $report;
		}

		public function laporan_performance_sales_parts($id_dealer,$start_date,$end_date){
			$filter_dealer = '';
          		if ($id_dealer!='all') {
           			$filter_dealer = " AND so.id_dealer='$id_dealer'";
         		}

			$report = $this->db->query("SELECT m.nama_lengkap, m.id_dealer, m.id_salesman, ifnull(m.total_aktual,0) as total_aktual_m, ifnull(m1.total_aktual,0) as total_aktual_m1
			FROM 
				(SELECT so.id_dealer, so.id_salesman, mkar.nama_lengkap, SUM(dsop.harga_setelah_diskon*dsop.qty_supply) as total_aktual
				FROM tr_h3_md_sales_order so 
				JOIN tr_h3_md_do_sales_order dso on dso.id_sales_order_int=so.id 
				JOIN tr_h3_md_do_sales_order_parts dsop on dsop.id_do_sales_order_int=dso.id 
				JOIN tr_h3_md_picking_list pl on dso.id = pl.id_ref_int 
				JOIN tr_h3_md_packing_sheet ps on ps.id_picking_list_int=pl.id 
				LEFT JOIN ms_karyawan mkar on mkar.id_karyawan=so.id_salesman 
				-- JOIN ms_part mp on mp.id_part_int = dsop.id_part_int 
				-- JOIN ms_h3_md_setting_kelompok_produk skp on skp.id_kelompok_part_int=mp.kelompok_part_int 
				WHERE left(ps.tgl_faktur,10) >='$start_date' and left(ps.tgl_faktur,10) <='$end_date' and so.produk='Parts' and so.id_salesman is not null $filter_dealer
				GROUP BY so.id_salesman 
				) AS m
			LEFT JOIN (
				SELECT so.id_salesman, mkar.nama_lengkap, SUM(dsop.harga_setelah_diskon*dsop.qty_supply) as total_aktual
				FROM tr_h3_md_sales_order so 
				JOIN tr_h3_md_do_sales_order dso on dso.id_sales_order_int=so.id 
				JOIN tr_h3_md_do_sales_order_parts dsop on dsop.id_do_sales_order_int=dso.id 
				JOIN tr_h3_md_picking_list pl on dso.id = pl.id_ref_int 
				JOIN tr_h3_md_packing_sheet ps on ps.id_picking_list_int=pl.id 
				LEFT JOIN ms_karyawan mkar on mkar.id_karyawan=so.id_salesman 
				-- JOIN ms_part mp on mp.id_part_int = dsop.id_part_int 
				-- JOIN ms_h3_md_setting_kelompok_produk skp on skp.id_kelompok_part_int=mp.kelompok_part_int 
				WHERE month(ps.tgl_faktur) = MONTH(DATE_FORMAT(LAST_DAY('$end_date' - INTERVAL 1 MONTH), '%Y-%m-%d'))  and so.produk='Parts' and so.id_salesman is not null $filter_dealer
				GROUP BY so.id_salesman 
			)m1
			on  m.id_salesman=m1.id_salesman
			ORDER BY m.nama_lengkap");
			return $report;
		}

		
		public function laporan_sales_by_channel_cost_price_oil_m($id_dealer,$start_date,$end_date){
			$filter_dealer = '';
          		if ($id_dealer!='all') {
           			$filter_dealer = " AND pl.id_dealer='$id_dealer'";
         		}

			$report = $this->db->query("SELECT IFNULL(SUM(CASE WHEN md.h1=1 and md.h2=1 and md.h3=1 then (dsop.harga_beli*dsop.qty_supply) else 0 end),0) as h123,
			IFNULL(SUM(CASE WHEN md.h1=0 and md.h2=1 and md.h3=1 then (dsop.harga_beli*dsop.qty_supply) else 0 end),0) as h23,
			IFNULL(SUM(CASE WHEN md.h1=0 and md.h2=0 and md.h3=1 then (dsop.harga_beli*dsop.qty_supply) else 0 end),0) as h3
			FROM tr_h3_md_sales_order so 
			JOIN tr_h3_md_do_sales_order dso on dso.id_sales_order_int=so.id 
			JOIN tr_h3_md_do_sales_order_parts dsop on dsop.id_do_sales_order_int=dso.id 
			JOIN tr_h3_md_picking_list pl on pl.id_ref_int=dso.id 
			JOIN tr_h3_md_packing_sheet ps on ps.id_picking_list_int=pl.id 
			JOIN ms_dealer md on md.id_dealer=pl.id_dealer 
			WHERE  left(ps.tgl_faktur,10) >='$start_date' and left(ps.tgl_faktur,10) <='$end_date' and so.produk='Oil' $filter_dealer");
			return $report;
		}

		public function laporan_sales_by_channel_cost_price_oil_m1($id_dealer,$start_date,$end_date){
			$filter_dealer = '';
          		if ($id_dealer!='all') {
           			$filter_dealer = " AND pl.id_dealer='$id_dealer'";
         		}

			$report = $this->db->query("SELECT IFNULL(SUM(CASE WHEN md.h1=1 and md.h2=1 and md.h3=1 then (dsop.harga_beli*dsop.qty_supply) else 0 end),0) as h123,
			IFNULL(SUM(CASE WHEN md.h1=0 and md.h2=1 and md.h3=1 then (dsop.harga_beli*dsop.qty_supply) else 0 end),0) as h23,
			IFNULL(SUM(CASE WHEN md.h1=0 and md.h2=0 and md.h3=1 then (dsop.harga_beli*dsop.qty_supply) else 0 end),0) as h3
			FROM tr_h3_md_sales_orders so 
			JOIN tr_h3_md_do_sales_order dso on dso.id_sales_order_int=so.id 
			JOIN tr_h3_md_do_sales_order_parts dsop on dsop.id_do_sales_order_int=dso.id 
			JOIN tr_h3_md_picking_list pl on pl.id_ref_int=dso.id 
			JOIN tr_h3_md_packing_sheet ps on ps.id_picking_list_int=pl.id 
			JOIN ms_dealer md on md.id_dealer=pl.id_dealer 
			WHERE  left(ps.tgl_faktur,10) >=('$start_date'- INTERVAL 1 MONTH) and left(ps.tgl_faktur,10) <=('$end_date'- INTERVAL 1 MONTH) and so.produk='Oil' $filter_dealer");
			return $report;
		}

		public function laporan_sales_by_channel_selling_price_oil_m($id_dealer,$start_date,$end_date){
			$filter_dealer = '';
          		if ($id_dealer!='all') {
           			$filter_dealer = " AND pl.id_dealer='$id_dealer'";
         		}

			$report = $this->db->query("SELECT IFNULL(SUM(CASE WHEN md.h1=1 and md.h2=1 and md.h3=1 then (dso.total) else 0 end),0) as h123,
			IFNULL(SUM(CASE WHEN md.h1=0 and md.h2=1 and md.h3=1 then (dso.total) else 0 end),0) as h23,
			IFNULL(SUM(CASE WHEN md.h1=0 and md.h2=0 and md.h3=1 then (dso.total) else 0 end),0) as h3
			FROM tr_h3_md_sales_order so 
			JOIN tr_h3_md_do_sales_order dso on dso.id_sales_order_int=so.id 
			JOIN tr_h3_md_picking_list pl on pl.id_ref_int=dso.id 
			JOIN tr_h3_md_packing_sheet ps on ps.id_picking_list_int=pl.id 
			JOIN ms_dealer md on md.id_dealer=pl.id_dealer 
			WHERE  left(ps.tgl_faktur,10) >='$start_date' and left(ps.tgl_faktur,10) <='$end_date' and so.produk='Oil' $filter_dealer");
			return $report;
		}

		public function laporan_sales_by_channel_selling_price_oil_m1($id_dealer,$start_date,$end_date){
			$filter_dealer = '';
          		if ($id_dealer!='all') {
           			$filter_dealer = " AND pl.id_dealer='$id_dealer'";
         		}

			$report = $this->db->query("SELECT IFNULL(SUM(CASE WHEN md.h1=1 and md.h2=1 and md.h3=1 then (dso.total) else 0 end),0) as h123,
			IFNULL(SUM(CASE WHEN md.h1=0 and md.h2=1 and md.h3=1 then (dso.total) else 0 end),0) as h23,
			IFNULL(SUM(CASE WHEN md.h1=0 and md.h2=0 and md.h3=1 then (dso.total) else 0 end),0) as h3
			FROM tr_h3_md_sales_order so 
			JOIN tr_h3_md_do_sales_order dso on dso.id_sales_order_int=so.id 
			JOIN tr_h3_md_picking_list pl on pl.id_ref_int=dso.id 
			JOIN tr_h3_md_packing_sheet ps on ps.id_picking_list_int=pl.id 
			JOIN ms_dealer md on md.id_dealer=pl.id_dealer 
			WHERE left(ps.tgl_faktur,10) >=('$start_date'- INTERVAL 1 MONTH) and left(ps.tgl_faktur,10) <=('$end_date'- INTERVAL 1 MONTH) and so.produk='Oil' $filter_dealer");
			return $report;
		}

		public function laporan_sales_by_channel_cost_price_part_m($id_dealer,$start_date,$end_date){
			$filter_dealer = '';
          		if ($id_dealer!='all') {
           			$filter_dealer = " AND pl.id_dealer='$id_dealer'";
         		}

			$report = $this->db->query("SELECT IFNULL(SUM(CASE WHEN md.h1=1 and md.h2=1 and md.h3=1 then (dsop.harga_beli*dsop.qty_supply) else 0 end),0) as h123,
			IFNULL(SUM(CASE WHEN md.h1=0 and md.h2=1 and md.h3=1 then (dsop.harga_beli*dsop.qty_supply) else 0 end),0) as h23,
			IFNULL(SUM(CASE WHEN md.h1=0 and md.h2=0 and md.h3=1 then (dsop.harga_beli*dsop.qty_supply) else 0 end),0) as h3
			FROM tr_h3_md_sales_order so 
			JOIN tr_h3_md_do_sales_order dso on dso.id_sales_order_int=so.id 
			JOIN tr_h3_md_do_sales_order_parts dsop on dsop.id_do_sales_order_int=dso.id 
			JOIN tr_h3_md_picking_list pl on pl.id_ref_int=dso.id 
			JOIN tr_h3_md_packing_sheet ps on ps.id_picking_list_int=pl.id 
			JOIN ms_dealer md on md.id_dealer=pl.id_dealer 
			WHERE  left(ps.tgl_faktur,10) >='$start_date' and left(ps.tgl_faktur,10) <='$end_date' and so.produk='Parts' $filter_dealer");
			return $report;
		}

		public function laporan_sales_by_channel_cost_price_part_m1($id_dealer,$start_date,$end_date){
			$filter_dealer = '';
          		if ($id_dealer!='all') {
           			$filter_dealer = " AND pl.id_dealer='$id_dealer'";
         		}

			$report = $this->db->query("SELECT IFNULL(SUM(CASE WHEN md.h1=1 and md.h2=1 and md.h3=1 then (dsop.harga_beli*dsop.qty_supply) else 0 end),0) as h123,
			IFNULL(SUM(CASE WHEN md.h1=0 and md.h2=1 and md.h3=1 then (dsop.harga_beli*dsop.qty_supply) else 0 end),0) as h23,
			IFNULL(SUM(CASE WHEN md.h1=0 and md.h2=0 and md.h3=1 then (dsop.harga_beli*dsop.qty_supply) else 0 end),0) as h3
			FROM tr_h3_md_sales_order so 
			JOIN tr_h3_md_do_sales_order dso on dso.id_sales_order_int=so.id 
			JOIN tr_h3_md_do_sales_order_parts dsop on dsop.id_do_sales_order_int=dso.id 
			JOIN tr_h3_md_picking_list pl on pl.id_ref_int=dso.id 
			JOIN tr_h3_md_packing_sheet ps on ps.id_picking_list_int=pl.id 
			JOIN ms_dealer md on md.id_dealer=pl.id_dealer 
			WHERE  left(ps.tgl_faktur,10) >=('$start_date'- INTERVAL 1 MONTH) and left(ps.tgl_faktur,10) <=('$end_date'- INTERVAL 1 MONTH) and so.produk='Parts' $filter_dealer");
			return $report;
		}

		public function laporan_sales_by_channel_selling_price_part_m($id_dealer,$start_date,$end_date){
			$filter_dealer = '';
          		if ($id_dealer!='all') {
           			$filter_dealer = " AND pl.id_dealer='$id_dealer'";
         		}

			$report = $this->db->query("SELECT IFNULL(SUM(CASE WHEN md.h1=1 and md.h2=1 and md.h3=1 then (dso.total) else 0 end),0) as h123,
			IFNULL(SUM(CASE WHEN md.h1=0 and md.h2=1 and md.h3=1 then (dso.total) else 0 end),0) as h23,
			IFNULL(SUM(CASE WHEN md.h1=0 and md.h2=0 and md.h3=1 then (dso.total) else 0 end),0) as h3
			FROM tr_h3_md_sales_order so 
			JOIN tr_h3_md_do_sales_order dso on dso.id_sales_order_int=so.id 
			JOIN tr_h3_md_picking_list pl on pl.id_ref_int=dso.id 
			JOIN tr_h3_md_packing_sheet ps on ps.id_picking_list_int=pl.id 
			JOIN ms_dealer md on md.id_dealer=pl.id_dealer 
			WHERE  left(ps.tgl_faktur,10) >='$start_date' and left(ps.tgl_faktur,10) <='$end_date' and so.produk='Parts' $filter_dealer");
			return $report;
		}

		public function laporan_sales_by_channel_selling_price_part_m1($id_dealer,$start_date,$end_date){
			$filter_dealer = '';
          		if ($id_dealer!='all') {
           			$filter_dealer = " AND pl.id_dealer='$id_dealer'";
         		}

			$report = $this->db->query("SELECT IFNULL(SUM(CASE WHEN md.h1=1 and md.h2=1 and md.h3=1 then (dso.total) else 0 end),0) as h123,
			IFNULL(SUM(CASE WHEN md.h1=0 and md.h2=1 and md.h3=1 then (dso.total) else 0 end),0) as h23,
			IFNULL(SUM(CASE WHEN md.h1=0 and md.h2=0 and md.h3=1 then (dso.total) else 0 end),0) as h3
			FROM tr_h3_md_sales_order so 
			JOIN tr_h3_md_do_sales_order dso on dso.id_sales_order_int=so.id 
			JOIN tr_h3_md_picking_list pl on pl.id_ref_int=dso.id 
			JOIN tr_h3_md_packing_sheet ps on ps.id_picking_list_int=pl.id 
			JOIN ms_dealer md on md.id_dealer=pl.id_dealer 
			WHERE left(ps.tgl_faktur,10) >=('$start_date'- INTERVAL 1 MONTH) and left(ps.tgl_faktur,10) <=('$end_date'- INTERVAL 1 MONTH) and so.produk='Parts' $filter_dealer");
			return $report;
		}

		public function laporan_sales_by_channel_target_part_m($id_dealer,$start_date,$end_date){
			$filter_dealer = '';
          		if ($id_dealer!='all') {
           			$filter_dealer = " AND tsp.id_dealer='$id_dealer'";
         		}

			$start_date_bulan = date("Y-m", strtotime($start_date));
			$end_date_bulan = date("Y-m", strtotime($end_date));

			$report = $this->db->query("SELECT ifnull(SUM(CASE WHEN md.h1=1 and md.h2=1 and md.h3=1 then tsp.target_part else 0 end),0) as h123,
			ifnull(SUM(CASE WHEN md.h1=0 and md.h2=1 and md.h3=1 then tsp.target_part else 0 end),0) as h23,
			ifnull(SUM(CASE WHEN md.h1=0 and md.h2=0 and md.h3=1 then tsp.target_part else 0 end),0) as h3
			FROM ms_h3_md_target_salesman_parts tsp 
			JOIN ms_h3_md_target_salesman ts on ts.id=tsp.id_target_salesman 
			JOIN ms_dealer md on md.id_dealer=tsp.id_dealer
			WHERE DATE_FORMAT(ts.start_date,'%Y-%m')='$start_date_bulan' and DATE_FORMAT(ts.end_date,'%Y-%m')='$end_date_bulan'  and ts.jenis_target_salesman='Parts' $filter_dealer");
			return $report;
		}

		public function laporan_sales_by_channel_target_part_m1($id_dealer,$start_date,$end_date){
			$filter_dealer = '';
          		if ($id_dealer!='all') {
           			$filter_dealer = " AND tsp.id_dealer='$id_dealer'";
         		}

			$start_date_bulan = date("Y-m", strtotime($start_date));
			$end_date_bulan = date("Y-m", strtotime($end_date));

			$start_date_bulan_m1 = date('Y-m', strtotime($start_date_bulan . ' -1 month'));
			$end_date_bulan_m1 = date('Y-m', strtotime($end_date_bulan . ' -1 month'));

			$report = $this->db->query("SELECT ifnull(SUM(CASE WHEN md.h1=1 and md.h2=1 and md.h3=1 then tsp.target_part else 0 end),0) as h123,
			ifnull(SUM(CASE WHEN md.h1=0 and md.h2=1 and md.h3=1 then tsp.target_part else 0 end),0) as h23,
			ifnull(SUM(CASE WHEN md.h1=0 and md.h2=0 and md.h3=1 then tsp.target_part else 0 end),0) as h3
			FROM ms_h3_md_target_salesman_parts tsp 
			JOIN ms_h3_md_target_salesman ts on ts.id=tsp.id_target_salesman 
			JOIN ms_dealer md on md.id_dealer=tsp.id_dealer
			WHERE DATE_FORMAT(ts.start_date,'%Y-%m')='$start_date_bulan_m1' and DATE_FORMAT(ts.end_date,'%Y-%m')='$end_date_bulan_m1'  and ts.jenis_target_salesman='Parts' $filter_dealer");
			return $report;
		}

		public function laporan_sales_by_channel_target_oli_m($id_dealer,$start_date,$end_date){
			$filter_dealer = '';
          		if ($id_dealer!='all') {
           			$filter_dealer = " AND so.id_dealer='$id_dealer'";
         		}

			$start_date_bulan = date("Y-m", strtotime($start_date));
			$end_date_bulan = date("Y-m", strtotime($end_date));


			$report = $this->db->query("SELECT ifnull(SUM(CASE WHEN md.h1=1 and md.h2=1 and md.h3=1 then so.total_amount else 0 end),0) as h123,
			ifnull(SUM(CASE WHEN md.h1=0 and md.h2=1 and md.h3=1 then so.total_amount else 0 end),0) as h23,
			ifnull(SUM(CASE WHEN md.h1=0 and md.h2=0 and md.h3=1 then so.total_amount else 0 end),0) as h3
			from ms_h3_md_target_salesman_oil so 
			join ms_h3_md_target_salesman ts on ts.id = so.id_target_salesman 
			JOIN ms_dealer md on md.id_dealer=so.id_dealer
			WHERE DATE_FORMAT(ts.start_date,'%Y-%m')='$start_date_bulan' and DATE_FORMAT(ts.end_date,'%Y-%m')='$end_date_bulan'  and ts.jenis_target_salesman='Oil' $filter_dealer");
			return $report;
		}

		public function laporan_sales_by_channel_target_oli_m1($id_dealer,$start_date,$end_date){
			$filter_dealer = '';
          		if ($id_dealer!='all') {
           			$filter_dealer = " AND so.id_dealer='$id_dealer'";
         		}

			$start_date_bulan = date("Y-m", strtotime($start_date));
			$end_date_bulan = date("Y-m", strtotime($end_date));

			$start_date_bulan_m1 = date('Y-m', strtotime($start_date_bulan . ' -1 month'));
			$end_date_bulan_m1 = date('Y-m', strtotime($end_date_bulan . ' -1 month'));

			$report = $this->db->query("SELECT ifnull(SUM(CASE WHEN md.h1=1 and md.h2=1 and md.h3=1 then so.total_amount else 0 end),0) as h123,
			ifnull(SUM(CASE WHEN md.h1=0 and md.h2=1 and md.h3=1 then so.total_amount else 0 end),0) as h23,
			ifnull(SUM(CASE WHEN md.h1=0 and md.h2=0 and md.h3=1 then so.total_amount else 0 end),0) as h3
			from ms_h3_md_target_salesman_oil so 
			join ms_h3_md_target_salesman ts on ts.id = so.id_target_salesman 
			JOIN ms_dealer md on md.id_dealer=so.id_dealer
			WHERE DATE_FORMAT(ts.start_date,'%Y-%m')='$start_date_bulan_m1' and DATE_FORMAT(ts.end_date,'%Y-%m')='$end_date_bulan_m1'  and ts.jenis_target_salesman='Oil' $filter_dealer");
			return $report;
		}

		public function laporan_performance_sales_oil($id_dealer,$start_date,$end_date){
			$filter_dealer = '';
          		if ($id_dealer!='all') {
           			$filter_dealer = " AND pl.id_dealer='$id_dealer'";
         		}

			// $start_date_bulan = date("Y-m", strtotime($start_date));
			// $end_date_bulan = date("Y-m", strtotime($end_date));

			// $start_date_bulan_m1 = date('Y-m', strtotime($start_date_bulan . ' -1 month'));
			// $end_date_bulan_m1 = date('Y-m', strtotime($end_date_bulan . ' -1 month'));

			$report = $this->db->query("SELECT m.id_dealer, m.id_salesman, m.nama_lengkap, ifnull(m.total,0) as total_aktual_m, ifnull(m1.total,0) as total_aktual_m1
			FROM 
				(SELECT so.id_dealer, so.id_salesman, mkar.nama_lengkap, SUM(dso.total) as total
				FROM tr_h3_md_sales_order so 
				JOIN tr_h3_md_do_sales_order dso on dso.id_sales_order_int=so.id 
				JOIN tr_h3_md_picking_list pl on dso.id = pl.id_ref_int 
				JOIN tr_h3_md_packing_sheet ps on ps.id_picking_list_int=pl.id 
				JOIN ms_karyawan mkar on mkar.id_karyawan=so.id_salesman 
				WHERE left(ps.tgl_faktur,10) >='$start_date' and left(ps.tgl_faktur,10) <='$end_date'
				and so.produk='Oil' $filter_dealer 
				group by so.id_salesman
				) AS m
			LEFT JOIN (
				SELECT so.id_salesman, mkar.nama_lengkap, SUM(dso.total) as total
				FROM tr_h3_md_sales_order so 
				JOIN tr_h3_md_do_sales_order dso on dso.id_sales_order_int=so.id 
				JOIN tr_h3_md_picking_list pl on dso.id = pl.id_ref_int 
				JOIN tr_h3_md_packing_sheet ps on ps.id_picking_list_int=pl.id 
				JOIN ms_karyawan mkar on mkar.id_karyawan=so.id_salesman 
				WHERE month(ps.tgl_faktur) = MONTH(DATE_FORMAT(LAST_DAY('$end_date' - INTERVAL 1 MONTH), '%Y-%m-%d'))
				and so.produk='Oil' $filter_dealer 
				group by so.id_salesman
			)m1
			on  m.id_salesman=m1.id_salesman
			ORDER BY m.nama_lengkap
			");
			return $report;
		}

		public function laporan_penjualan_hga($id_dealer,$start_date,$end_date){
			$filter_dealer = '';
          		if ($id_dealer!='all') {
           			$filter_dealer = " AND pl.id_dealer='$id_dealer'";
         		}

			$report = $this->db->query("SELECT md.nama_dealer, (CASE WHEN md.h1 = 1 and md.h2 = 1 and md.h3 = 1 then 'H123' WHEN md.h1 = 0 and md.h2 = 1 and md.h3 = 1 THEN 'H23' 
			WHEN md.h1 = 0 and md.h2 = 0 and md.h3 = 1 THEN 'H3' ELSE '-' END) as status, mkab.kabupaten, mkar.nama_lengkap, 
				SUM(CASE WHEN mp.kelompok_part='PACC' then dsop.qty_supply*dsop.harga_setelah_diskon else 0 end) as pacc,
				SUM(CASE WHEN mp.kelompok_part='ACCEC' then dsop.qty_supply*dsop.harga_setelah_diskon else 0 end) as accec, 
				SUM(CASE WHEN mp.kelompok_part='HELM' then dsop.qty_supply*dsop.harga_setelah_diskon else 0 end) as helm
				FROM tr_h3_md_sales_order so 
				JOIN tr_h3_md_do_sales_order dso on dso.id_sales_order_int=so.id 
				JOIN tr_h3_md_do_sales_order_parts dsop on dsop.id_do_sales_order_int=dso.id 
				JOIN tr_h3_md_picking_list pl on dso.id = pl.id_ref_int 
				JOIN tr_h3_md_packing_sheet ps on ps.id_picking_list_int=pl.id 
				JOIN ms_dealer md on md.id_dealer=so.id_dealer 
				JOIN ms_kelurahan mk on mk.id_kelurahan=md.id_kelurahan 
				JOIN ms_kecamatan mk2 on mk2.id_kecamatan=mk.id_kecamatan 
				JOIN ms_kabupaten mkab on mkab.id_kabupaten=mk2.id_kabupaten 
				JOIN ms_karyawan mkar on mkar.id_karyawan=so.id_salesman 
				JOIN ms_part mp on mp.id_part_int=dsop.id_part_int 
				WHERE mp.kelompok_part in ('PACC', 'ACCEC','HELM') 
				and left(ps.tgl_faktur,10) >= '$start_date' and left(ps.tgl_faktur,10) <= '$end_date' $filter_dealer
				GROUP BY pl.id_dealer, so.id_salesman 
				ORDER BY md.nama_dealer ASC");
			return $report;
		}

		public function laporan_product_value($id_dealer,$start_date,$end_date){
			$filter_dealer = '';
          		if ($id_dealer!='all') {
           			$filter_dealer = " AND pl.id_dealer='$id_dealer'";
         		}

			$report = $this->db->query("SELECT pl.id_dealer, md.nama_dealer, (CASE WHEN md.h1 = 1 and md.h2 = 1 and md.h3 = 1 then 'H123' WHEN md.h1 = 0 and md.h2 = 1 and md.h3 = 1 THEN 'H23' 
			WHEN md.h1 = 0 and md.h2 = 0 and md.h3 = 1 THEN 'H3' ELSE '-' END) as status, mkab.kabupaten, mkar.nama_lengkap,
				SUM(CASE WHEN mp.kelompok_part='OIL' then plp.qty_supply else 0 end) as oil,
				SUM(CASE WHEN mp.kelompok_part='GMO' then plp.qty_supply else 0 end) as gmo,
				SUM(CASE WHEN mp.id_part='HPC480ML' then plp.qty_supply else 0 end) as hpc,
				SUM(CASE WHEN mp.id_part='HIC60ML' then plp.qty_supply else 0 end) as hic,
				SUM(CASE WHEN mp.id_part='HBF50ML' then plp.qty_supply else 0 end) as hbf,
				SUM(CASE WHEN mp.id_part='ACG10GR' then plp.qty_supply else 0 end) as acg,
				SUM(CASE WHEN mp.id_part='ACL70ML' then plp.qty_supply else 0 end) as acl,
				SUM(CASE WHEN mp.id_part='OSC70ML' then plp.qty_supply else 0 end) as osc,
				SUM(CASE WHEN mp.id_part='CC200ML' then plp.qty_supply else 0 end) as cc,
				SUM(CASE WHEN mp.id_part='TBC500ML' then plp.qty_supply else 0 end) as tbc
				FROM tr_h3_md_sales_order so 
				JOIN tr_h3_md_do_sales_order dso on dso.id_sales_order_int=so.id 
				JOIN tr_h3_md_picking_list pl on dso.id = pl.id_ref_int 
				JOIN tr_h3_md_picking_list_parts plp on plp.id_picking_list_int=pl.id 
				JOIN tr_h3_md_packing_sheet ps on ps.id_picking_list_int=pl.id 
				JOIN ms_dealer md on md.id_dealer=so.id_dealer 
				JOIN ms_kelurahan mk on mk.id_kelurahan=md.id_kelurahan 
				JOIN ms_kecamatan mk2 on mk2.id_kecamatan=mk.id_kecamatan 
				JOIN ms_kabupaten mkab on mkab.id_kabupaten=mk2.id_kabupaten 
				JOIN ms_karyawan mkar on mkar.id_karyawan=so.id_salesman 
				JOIN ms_part mp on mp.id_part_int=plp.id_part_int 
				WHERE left(ps.tgl_faktur,10) >= '$start_date' and left(ps.tgl_faktur,10) <= '$end_date' $filter_dealer
				GROUP BY pl.id_dealer
				ORDER BY md.nama_dealer ASC");
			return $report;
		}

		public function laporan_hlo_dealer($id_dealer,$start_date,$end_date){
			$filter_dealer = '';
			if ($id_dealer!='all') {
				 $filter_dealer = " AND po.id_dealer='$id_dealer'";
		   }

			$report = $this->db->query("SELECT DATE_FORMAT(po.created_at, '%d/%m/%Y') as tgl_po_dealer, DATE_FORMAT(po.submit_at,'%d/%m/%Y') as tgl_submit_dealer_to_md, 
			DATE_FORMAT(po.tanggal_po_ahm ,'%d/%m/%Y') as tgl_po_md_to_ahm, DATE_FORMAT(opt.tgl_shipping_list_md ,'%d/%m/%Y') as tgl_shipping_md, 
			md.nama_dealer, md.alamat, md.no_telp, po.po_id as no_po_dealer, mch.nama_customer, mch.alamat as alamat_konsumen, mch.no_hp, mch.email, mch.no_mesin, mch.no_rangka, 
			pop.id_part, mp.nama_part, pop.kuantitas, DATE_FORMAT(pop.eta_terlama ,'%d/%m/%Y') as etd_ahm_to_md, 
			DATE_FORMAT(pop.eta_tercepat ,'%d/%m/%Y') as eta_dealer, DATE_FORMAT(pop.eta_revisi ,'%d/%m/%Y') as eta_revisi,
			mp.kelompok_part, pop.id_part_int, rd.id as id_booking_int, po.status, po.proses_ahm, po.id as po_id_int
			FROM tr_h3_dealer_purchase_order po 
			JOIN tr_h3_dealer_purchase_order_parts pop on pop.po_id_int=po.id 
			LEFT JOIN tr_h3_dealer_good_receipt gr on gr.nomor_po=po.po_id 
			JOIN ms_dealer md on md.id_dealer=po.id_dealer 
			JOIN tr_h3_dealer_request_document rd on rd.id=po.id_booking_int 
			JOIN ms_customer_h23 mch on mch.id_customer=rd.id_customer 
			JOIN ms_part mp on mp.id_part_int = pop.id_part_int 
			JOIN tr_h3_dealer_order_parts_tracking opt on opt.po_id=pop.po_id and pop.id_part_int=opt.id_part_int 
			WHERE po.created_at>='$start_date 00:00:00' and po.created_at <='$end_date 23:59:59' and po.status not in ('Canceled','Rejected') and po.order_to = 0 $filter_dealer 
			ORDER BY po.created_at ASC");
			return $report;
		}
	}
