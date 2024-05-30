<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class H2_md_historical_service_model extends CI_Model{
		public function __construct()
        {
            parent::__construct();
        }

		public function getDataDealer()
		{
			$query=$this->db->query("SELECT id_dealer,kode_dealer_ahm,nama_dealer from ms_dealer where h2=1 and active=1 and LENGTH (kode_dealer_ahm)=5 order by nama_dealer asc");
			return $query->result();
		}

		public function historical_service($id_dealer, $start_date,$end_date)
		{
			$filter_dealer = '';
			if ($id_dealer!='all') {
				 $filter_dealer = " AND wo.id_dealer='$id_dealer'";
		   }

			$query=$this->db->query("SELECT md.kode_dealer_ahm, REPLACE(cust.no_rangka ,'-','') as no_rangka ,REPLACE(cust.no_mesin ,'-','') as no_mesin, cust.no_polisi ,wo.id_work_order , 
			DATE_FORMAT(wo.created_at,'%d/%m/%Y') as tgl_wo,
			wo.no_njb, DATE_FORMAT(wo.created_njb_at,'%d/%m/%Y') as tgl_njb, nsc.no_nsc, DATE_FORMAT(nsc.created_at ,'%d/%m/%Y') as tgl_nsc, cust.id_tipe_kendaraan, 
			DATE_FORMAT(wo.start_at,'%d/%m/%Y %H:%i') as waktu_mulai, DATE_FORMAT(wo.closed_at,'%d/%m/%Y %H:%i') as waktu_selesai, wo.tipe_pembayaran, 
			(case when sa.tipe_coming like '%milik%' then 1 else 2 end) as flag_pembawa, (CASE WHEN mkd.honda_id != '' then mkd.honda_id else '-' end) as honda_id_mekanik ,mkd.nama_lengkap as nama_mekanik,
			(CASE WHEN mkd2.honda_id != '' then mkd2.honda_id else '-' end) as honda_id_sa, mkd2.nama_lengkap as nama_sa, (CASE WHEN sa.activity_promotion_id != '' then ap.kode else '-' end) as kode_alasan_datang_ke_ahass,
			(CASE WHEN sa.activity_promotion_id != '' then ap.kode else '-' end) as activity_promotion, (CASE WHEN sa.activity_promotion_id != '' then ap.name else '-' end) as nama_activity_promotion,
			(CASE WHEN sa.activity_capacity_id != '' then ac.kode else '-' end) as activity_capacity, sa.km_terakhir, (CASE WHEN sa.keluhan_konsumen != '' then REPLACE(sa.keluhan_konsumen ,'\n','') else '-' end) as keluhan_konsumen,
			jt.kode_jenis_pekerjaan, js.deskripsi,  (CASE WHEN sa.no_claim_c2 != '' then sa.no_claim_c2 else '' end) as no_claim_c2, wop.subtotal as subtotal_jasa ,wops.id_part, mp.kelompok_part, mp.nama_part, 
			wops.qty, wops.subtotal , sa.soc, sa.serial_number_battery, 
			kcl.deskripsi as deskripsi_kesediaan_customer, rrl.deskripsi as deskripsi_alasan_tidak_bersedia, hpl.deskripsi as deskripsi_hasil_pengecekan
			FROM tr_h2_sa_form sa 
			JOIN tr_h2_wo_dealer wo on sa.id_sa_form=wo.id_sa_form 
			JOIN tr_h2_wo_dealer_pekerjaan wop on wo.id_work_order=wop.id_work_order 
			LEFT JOIN tr_h2_wo_dealer_parts wops on wops.id_work_order=wo.id_work_order and wop.id_jasa=wops.id_jasa and (select sop.return from tr_h3_dealer_sales_order_parts sop where sop.id_part_int=wops.id_part_int and sop.nomor_so=wops.nomor_so) = 0 and (select mpart.kelompok_part from ms_part mpart where mpart.id_part_int=wops.id_part_int) != 'FED OIL'
			JOIN ms_dealer md on md.id_dealer=wo.id_dealer
			JOIN ms_customer_h23 cust on cust.id_customer=sa.id_customer 
			LEFT JOIN tr_h23_nsc nsc on nsc.id_referensi=wo.id_work_order 
			JOIN ms_karyawan_dealer mkd on mkd.id_karyawan_dealer=wo.id_karyawan_dealer 
			JOIN ms_user mu on mu.id_user=wo.created_by 
			JOIN ms_karyawan_dealer mkd2 on mkd2.id_karyawan_dealer=mu.id_karyawan_dealer 
			LEFT JOIN dms_ms_activity_promotion ap on ap.id=sa.activity_promotion_id 
			LEFT JOIN dms_ms_activity_capacity ac on ac.id=sa.activity_capacity_id 
			JOIN ms_h2_jasa js on js.id_jasa=wop.id_jasa 
			JOIN ms_h2_jasa_type jt on jt.id_type=js.id_type 
			LEFT JOIN ms_part mp on mp.id_part_int=wops.id_part_int and mp.kelompok_part != 'FED OIL'
			LEFT JOIN tr_h2_wo_lcr_history lcr on lcr.id_work_order=wo.id_work_order 
			LEFT JOIN ms_h2_kesediaan_customer_lcr kcl on kcl.id=lcr.kesediaan_customer_lcr_id 
			LEFT JOIN ms_h2_record_reason_lcr rrl on rrl.id=lcr.record_reason_lcr_id 
			LEFT JOIN ms_h2_hasil_pengecekan_lcr hpl on hpl.id = lcr.hasil_pengecekan_lcr_id 
			WHERE wo.status='Closed' and wo.created_njb_at != ''
			and wo.created_njb_at >= '$start_date 00:00:00' and wo.created_njb_at<='$end_date 23:59:59'
			and wop.pekerjaan_batal=0 $filter_dealer
			ORDER BY md.kode_dealer_ahm, wo.created_njb_at ASC");
			return $query;
		}
	}	
?>