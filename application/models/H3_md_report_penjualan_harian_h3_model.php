<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class H3_md_report_penjualan_harian_h3_model extends CI_Model{
		public function __construct()
        {
            parent::__construct();
        }

        public function report_penjualan_h3($start_date,$end_date)
        {
            $report = $this->db->query("SELECT DATE_FORMAT(ps.tgl_faktur,'%d-%m-%Y') as tgl_faktur, dso.id as id_do_sales_order_int, so.id as id_sales_order_int, ps.no_faktur, md.nama_dealer, plp.id_part, mp.nama_part,  plp.id_part_int,plp.qty_supply, plp.harga,
            mp.kelompok_part,plp.id_tipe_kendaraan, 
            (case when so.id_salesman is not null then mk.nama_lengkap else '-' end) as salesman, 
            (case when md.h1=1 then 'Ya' else 'Tidak' end) as h1,(case when md.h2=1 then 'Ya' else 'Tidak' end ) as h2,
            (case when md.h1=1 then 'Ya' else 'Tidak' end) as h3, (case when so.is_hadiah=1 then 'Ya' else 'Tidak' end ) as is_hadiah
            from tr_h3_md_packing_sheet ps
            join tr_h3_md_picking_list pl on ps.id_picking_list_int=pl.id 
            join tr_h3_md_picking_list_parts plp on plp.id_picking_list_int=pl.id
            join tr_h3_md_do_sales_order dso on dso.id=pl.id_ref_int 
            -- left join tr_h3_md_do_sales_order_parts dsop on dsop.id_do_sales_order_int=dso.id and dsop.id_part_int=plp.id_part_int and dsop.id_tipe_kendaraan=plp.id_tipe_kendaraan
            join tr_h3_md_sales_order so on so.id=dso.id_sales_order_int 
            -- left join tr_h3_md_sales_order_parts sop on sop.id_sales_order_int=so.id and sop.id_part_int=dsop.id_part_int and sop.id_tipe_kendaraan=plp.id_tipe_kendaraan
            join ms_dealer md on md.id_dealer=so.id_dealer 
            join ms_part mp on mp.id_part_int=plp.id_part_int 
            left join ms_karyawan mk on mk.id_karyawan=so.id_salesman 
            where left(ps.tgl_faktur,10) >= '$start_date' and left(ps.tgl_faktur,10) <= '$end_date' and (ps.is_retur_penjualan = 0 or ps.is_retur_penjualan is null)
            order by ps.tgl_faktur, ps.no_faktur ASC");
            return $report;
        }

	}	
?>