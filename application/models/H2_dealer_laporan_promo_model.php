<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class H2_dealer_laporan_promo_model extends CI_Model{

        public function __construct()
        {
            parent::__construct();
            $start_date = $this->input->post('tgl1');
            $end_date = $this->input->post('tgl2','end_date');
            $id_dealer = $this->m_admin->cari_dealer();
        }

		public function program_promo_servis($id_dealer){
			$promo_servis = $this->db->query("SELECT mps.id_promo, mps.nama_promo 
			from ms_promo_servis mps
			join ms_promo_servis_dealer mpsd on mps.id_promo=mpsd.id_promo 
			where mpsd.id_dealer='$id_dealer'
			ORDER BY mps.created_at DESC");

			return $promo_servis;
		}

		public function program_promo_part($id_dealer){
			$promo_part = $this->db->query("SELECT id_promo,nama 
			from ms_h3_promo_dealer pd
			ORDER BY pd.created_at DESC");

			return $promo_part;
		}

		public function promo_servis($id_dealer,$start_date,$end_date,$type_jasa){
			$promo_servis_query = $this->db->query("SELECT wo.id_work_order, wo.no_njb, DATE_FORMAT(LEFT(wo.created_njb_at,10),'%d/%m/%Y') as tgl_njb ,  wop.id_jasa, jasa.deskripsi as nama_jasa, wop.id_promo, mps.nama_promo, wop.harga, wop.subtotal, 
			mch.nama_customer, mch.no_mesin, mch.no_rangka, jasa.id_type
			FROM tr_h2_wo_dealer wo 
			JOIN tr_h2_wo_dealer_pekerjaan wop on wop.id_work_order=wo.id_work_order 
			JOIN ms_promo_servis mps on mps.id_promo=wop.id_promo 
			JOIN ms_h2_jasa jasa on jasa.id_jasa=wop.id_jasa 
			JOIN tr_h2_sa_form sa on sa.id_sa_form=wo.id_sa_form 
			JOIN ms_customer_h23 mch on mch.id_customer=sa.id_customer 
			WHERE wo.id_dealer=$id_dealer and wo.status='Closed' and wo.no_njb is not null and wo.created_njb_at >= '$start_date 00:00:00' and wo.created_njb_at <= '$end_date 23:59:59' and wop.id_promo = '$type_jasa' and wop.pekerjaan_batal = 0");

			return $promo_servis_query;
		}

		public function promo_part($id_dealer,$start_date,$end_date,$type_part){
			$promo_servis_query = $this->db->query("SELECT
			 nsc.no_nsc, DATE_FORMAT(LEFT(nsc.created_at,10),'%d/%m/%Y') as tgl_nsc, nscp.id_part, mp.nama_part, nscp.id_promo, mpd.nama, (CASE WHEN nsc.referensi = 'work_order' then 'WO' WHEN nsc.referensi = 'sales' then 'Direct Sales' end) as referensi, nsc.id_referensi,  (CASE WHEN tipe_diskon='Percentage' THEN harga_beli - (harga_beli*(diskon_value/100)) ELSE harga_beli END ) * 
			(CASE WHEN tipe_diskon='FoC' THEN qty-diskon_value ELSE qty END ) -( (CASE WHEN tipe_diskon='FoC' THEN qty-diskon_value ELSE qty END ) * 
			(CASE WHEN tipe_diskon='Value' THEN diskon_value ELSE 0 END)) as subtotal, nscp.harga_beli
			from tr_h23_nsc nsc
			join tr_h23_nsc_parts nscp on nscp.no_nsc=nsc.no_nsc 
			join ms_part mp on mp.id_part_int=nscp.id_part_int 
			join ms_h3_promo_dealer mpd on mpd.id_promo=nscp.id_promo 
			where nsc.id_dealer=$id_dealer and nscp.id_promo='$type_part' and nsc.created_at >= '$start_date 00:00:00' and nsc.created_at <= '$end_date 23:59:59'
			");

			return $promo_servis_query;
		}

		public function promo_part_goliath($id_dealer,$start_date,$end_date,$type_part){
			$promo_servis_query = $this->db->query("SELECT wo.id_work_order, nsc.no_nsc, DATE_FORMAT(LEFT(nsc.created_at,10),'%d/%m/%Y') as tgl_nsc, mch.id_tipe_kendaraan, mch.no_mesin, mch.no_rangka, DATE_FORMAT(LEFT(mch.tgl_pembelian,10),'%d/%m/%Y') AS tgl_pembelian_indo,
			DATE_FORMAT(LEFT(sa.tgl_servis,10),'%d/%m/%Y') AS tgl_servis_indo , sa.km_terakhir, (CASE WHEN sa.id_type='ASS2' then '2' WHEN sa.id_type='ASS3' then '3' when sa.id_type='ASS3' then '3' END) as kpb_ke,
			wops.harga as harga_beli, wops.subtotal, nscp.qty, ROUND((SELECT IFNULL(SUM(wop.harga-IFNULL((wop.harga * (prj.diskon/100)),0)),0) 
				FROM tr_h2_wo_dealer_pekerjaan AS wop 
				JOIN ms_h2_jasa jstp ON jstp.id_jasa=wop.id_jasa 
				LEFT JOIN ms_promo_servis_jasa prj ON prj.id_promo=wop.id_promo AND prj.id_jasa=wop.id_jasa 
				LEFT JOIN ms_promo_servis pr ON pr.id_promo=prj.id_promo WHERE wop.id_work_order=wo.id_work_order AND jstp.id_type 
				IN ('ASS2','ASS3','ASS4'))) AS tot_pekerjaan, wo.id_dealer
		   	FROM tr_h2_sa_form sa
		   	JOIN tr_h2_wo_dealer wo on sa.id_sa_form=wo.id_sa_form 
		   	JOIN tr_h2_wo_dealer_parts wops on wops.id_work_order=wo.id_work_order
		   	JOIN ms_h3_promo_dealer mpd on mpd.id_promo=wops.id_promo
		   	JOIN tr_h23_nsc nsc on nsc.id_referensi=wo.id_work_order 
		   	JOIN ms_customer_h23 mch on mch.id_customer=sa.id_customer 
		   	JOIN tr_h23_nsc_parts nscp on nscp.id_part_int=wops.id_part_int and nsc.no_nsc=nscp.no_nsc 
		   	WHERE wo.id_dealer=$id_dealer and wops.pekerjaan_batal=0 and wops.id_promo='PRM/01/24/00004' and sa.id_type in ('ASS2','ASS3','ASS4') and nsc.created_at >= '$start_date 00:00:00' and nsc.created_at <= '$end_date 23:59:59'");

			return $promo_servis_query;
		}
    }
?>