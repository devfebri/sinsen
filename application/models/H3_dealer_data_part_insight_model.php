<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class H3_dealer_data_part_insight_model extends CI_Model{
		public function __construct()
        {
            parent::__construct();
        }

        public function ps_channel($id_dealer,$start_date,$end_date)
        {

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
                and mp.kelompok_part !='FED OIL' and nsc.id_dealer = $id_dealer
                GROUP BY nsc.id_dealer, mp.kelompok_part, nsc.referensi 
                ORDER BY md.nama_dealer");
            return $ps_channel;
        }

        public function ps_service($id_dealer,$start_date,$end_date)
        {
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
				 where C.tgl_nsc >= '$start_date' and C.tgl_nsc <='$end_date' AND C.id_dealer='$id_dealer'");
            return $ps_service;
        }

        public function ps_avg_grouping_part($id_dealer,$start_date,$end_date)
        {
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
                C.tgl_nsc <= '$end_date' and C.id_dealer=$id_dealer
                GROUP BY C.id_dealer, A.kelompok_part, C.referensi, bulantahun");
            return $ps_avg_grouping_part;
        }


        public function ps_details($id_dealer,$start_date,$end_date)
        {
            $ps_details = $this->db->query("SELECT md.nama_dealer, 
            (CASE WHEN md.h1='1' and md.h2='1' and md.h3='1' then 'H123' 
            WHEN md.h1='0' and md.h2='1' and md.h3='1' then 'H23' ELSE 'H3' END) as channel_h123,
              kp.produk as klp_part,
            B.id_part, A.nama_part, A.kelompok_part, B.qty, B.harga_beli, B.no_nsc, (CASE WHEN C.referensi= 'work_order' THEN 'WO' ELSE 'Direct Sales' END) as referensi, mtk.deskripsi_ahm,
            ms.segment, DATE_FORMAT(mtk.tgl_awal,'%Y') as production_year,
            (CASE WHEN B.tipe_diskon='Percentage' 
				then ((B.harga_beli*B.qty)-((B.harga_beli*B.diskon_value/100)*B.qty))
				WHEN B.tipe_diskon='Value' then ((B.harga_beli*B.qty)-(B.diskon_value*B.qty)) ELSE B.harga_beli*B.qty END) as total,B.tipe_diskon, B.diskon_value 
            from tr_h23_nsc_parts B 
            JOIN ms_part A ON A.id_part_int=B.id_part_int 
            join tr_h23_nsc C ON C.no_nsc=B.no_nsc 
            join ms_dealer md ON md.id_dealer=C.id_dealer
            JOIN ms_h3_md_setting_kelompok_produk kp on kp.id_kelompok_part_int=A.kelompok_part_int
            LEFT JOIN ms_customer_h23 mch on mch.id_customer=C.id_customer 
            LEFT JOIN ms_tipe_kendaraan mtk on mch.id_tipe_kendaraan=mtk.id_tipe_kendaraan 
            LEFT JOIN ms_segment ms on ms.id_segment=mtk.id_segment 
            where C.tgl_nsc BETWEEN '$start_date' and '$end_date' and C.id_dealer=$id_dealer");
            return $ps_details;
        }



        public function sl_details($id_dealer,$start_date,$end_date)
        {

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
                where nsc.created_at >='$start_date 00:00:00' and nsc.created_at<='$end_date 23:59:59' and nsc.id_dealer = $id_dealer
                and mp.kelompok_part !='FED OIL' 
                GROUP BY nsc.id_dealer, mp.id_part, nsc.referensi
                ORDER BY md.nama_dealer");
            return $sl_details;
        }

        public function sl_dealer($id_dealer,$start_date,$end_date)
        {

            $sl_dealer = $this->db->query("SELECT ds.id_dealer, ds.id_part, skp.produk, mp.kelompok_part, SUM(ds.stock) as stock, mp.harga_md_dealer, md.nama_dealer, (CASE WHEN md.h1 = 1 and md.h2 = 1 and md.h3 = 1 then 'H123' WHEN md.h1 = 0 and md.h2 = 1 and md.h3 = 1 THEN 'H23' 
            WHEN md.h1 = 0 and md.h2 = 0 and md.h3 = 1 THEN 'H3' ELSE '-' END) as status
                FROM ms_h3_dealer_stock ds 
                join ms_part mp on mp.id_part_int=ds.id_part_int 
                join ms_h3_md_setting_kelompok_produk skp on skp.id_kelompok_part=mp.kelompok_part
                join ms_dealer md on md.id_dealer=ds.id_dealer 
                WHERE mp.kelompok_part != 'FED OIL' and ds.id_dealer = $id_dealer
                GROUP BY mp.id_part_int, ds.id_dealer ");
            return $sl_dealer;
        }

       
        public function hlo_dealer($id_dealer,$start_date,$end_date)
        {

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
                and po.created_at >= '$start_date 00:00:00' and po.created_at <= '$end_date 23:59:59'  and po.id_dealer = $id_dealer
                ");
            return $hlo_dealer;
        }
	}	
?>