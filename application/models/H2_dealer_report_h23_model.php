<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class H2_dealer_report_h23_model extends CI_Model{

        public function __construct()
        {
            parent::__construct();
            $start_date = $this->input->post('tgl1');
            $end_date = $this->input->post('tgl2','end_date');
            $id_dealer = $this->m_admin->cari_dealer();
        }

		public function getDataAhass($id_dealer){
			
			$dealer = $this->db->query("SELECT a.nama_dealer, a.kode_dealer_ahm, a.alamat, kab.kabupaten, a.id_dealer, COUNT(pit.id_pit) as jumlah_pit,kec.kecamatan, (CASE WHEN a.no_telp!= NULL THEN a.no_telp ELSE '0' END) as no_telp, DATE_FORMAT(a.tanggal_kerjasama,'%Y') as tanggal_kerjasama, 
										(CASE WHEN h1 <> '' and h2 <> '' and h3 <>'' then 'H123' 
										WHEN h1 = '1' and h2 = '1' and h3 ='' then 'H12'
										WHEN h1 = '1' and h2 = '' and h3 ='' then 'H1'
										WHEN h1 = '' and h2 = '1' and h3 ='1' then 'H23' 
										WHEN h1 = '' and h2 = '1' and h3 ='' then 'H2' 
										WHEN h1 = '' and h2 = '' and h3 ='1' then 'H3' end) as jenis_channel
			 							FROM ms_dealer a
			 							JOIN ms_kelurahan kel on a.id_kelurahan=kel.id_kelurahan
	    								JOIN ms_kecamatan kec on kel.id_kecamatan=kec.id_kecamatan
	    								JOIN ms_kabupaten kab on kec.id_kabupaten=kab.id_kabupaten
	    								JOIN ms_provinsi prov on kab.id_provinsi=prov.id_provinsi
										JOIN ms_h2_pit_mekanik pit on a.id_dealer = pit.id_dealer
										WHERE a.id_dealer = '$id_dealer'")->row();
			 return $dealer;
		}

		public function hariKerja($id_dealer, $start_date,$end_date){
			$hariKerja = $this->db->query("SELECT id_dealer , count(tanggal) as tgl
			 FROM tr_h2_absen_mekanik a 
			 where tanggal >='$start_date' and tanggal<='$end_date' and a.id_dealer = '$id_dealer'
			 group by id_dealer");

			return $hariKerja;
		}

		public function jumlahUE($id_dealer,$start_date,$end_date){
			$jumlahUE = $this->db->query("SELECT SUM(CASE WHEN c.id_type!='JR' then 1 ELSE 0 end)as total_ue
			FROM ms_dealer a 
			JOIN tr_h2_wo_dealer b on a.id_dealer = b.id_dealer
			JOIN tr_h2_sa_form c on b.id_sa_form = c.id_sa_form
			where b.status='closed' and left(b.created_njb_at,10)>='$start_date' and left(b.created_njb_at,10)<='$end_date' and a.id_dealer = '$id_dealer'
			group by b.id_dealer");

			return $jumlahUE;
		}

        public function downloadReportMotor($id_dealer,$start_date,$end_date){

			$type_motor = $this->db->query("SELECT a.nama_dealer, a.kode_dealer_ahm, g.tipe_ahm , d.id_tipe_kendaraan, 
					SUM(CASE WHEN c.id_type='ASS1' then 1 ELSE 0 end )as total_ass1,
					SUM(CASE WHEN c.id_type='ASS2' then 1 ELSE 0 end )as total_ass2,
					SUM(CASE WHEN c.id_type='ASS3' then 1 ELSE 0 end )as total_ass3,
					SUM(CASE WHEN c.id_type='ASS4' then 1 ELSE 0 end )as total_ass4,
					SUM(CASE WHEN c.id_type='CS' then 1 ELSE 0 end )as total_cs,
					SUM(CASE WHEN c.id_type='LS' then 1 ELSE 0 end )as total_ls,
					SUM(CASE WHEN c.id_type='OR+' then 1 ELSE 0 end )as total_or,
					SUM(CASE WHEN c.id_type='LR' then 1 ELSE 0 end )as total_lr,
					SUM(CASE WHEN c.id_type='HR' then 1 ELSE 0 end )as total_hr,
					SUM(CASE WHEN c.id_type='JR' then 1 ELSE 0 end )as total_jr,
					SUM(CASE WHEN c.id_type='C2' then 1 ELSE 0 end )as total_claim,
					SUM(CASE WHEN c.id_type='OTHER' then 1 ELSE 0 end )as total_other,
					SUM(CASE WHEN c.id_type NOT IN ('JR','C1','QS','PL','PUD') then 1 ELSE 0 end)as total_job
					FROM ms_dealer a 
					JOIN tr_h2_wo_dealer b on a.id_dealer = b.id_dealer
					JOIN tr_h2_sa_form c on b.id_sa_form = c.id_sa_form
					LEFT JOIN ms_customer_h23 d on c.id_customer = d.id_customer 
					LEFT JOIN tr_h2_wo_dealer_pekerjaan e on b.id_work_order = e.id_work_order
					JOIN ms_tipe_kendaraan g on d.id_tipe_kendaraan =g.id_tipe_kendaraan
					WHERE b.status='closed' and left(b.created_njb_at,10)>='$start_date' and left(b.created_njb_at,10)<='$end_date' and b.id_dealer = '$id_dealer'
					GROUP BY d.id_tipe_kendaraan
					ORDER BY d.id_tipe_kendaraan asc");
			return $type_motor;
		}

		public function downloadReportTrx($id_dealer,$start_date,$end_date){
			
			$tgl_trx = $this->db->query("SELECT a.nama_dealer, a.kode_dealer_ahm, DATE_FORMAT(b.closed_at,'%d') as tgl,
										SUM(CASE WHEN c.id_type='ASS1' then 1 ELSE 0 end )as total_ass1,
										SUM(CASE WHEN c.id_type='ASS2' then 1 ELSE 0 end )as total_ass2,
										SUM(CASE WHEN c.id_type='ASS3' then 1 ELSE 0 end )as total_ass3,
										SUM(CASE WHEN c.id_type='ASS4' then 1 ELSE 0 end )as total_ass4,
										SUM(CASE WHEN c.id_type='CS' then 1 ELSE 0 end )as total_cs,
										SUM(CASE WHEN c.id_type='LS' then 1 ELSE 0 end )as total_ls,
										SUM(CASE WHEN c.id_type='OR+' then 1 ELSE 0 end )as total_or,
										SUM(CASE WHEN c.id_type='LR' then 1 ELSE 0 end )as total_lr,
										SUM(CASE WHEN c.id_type='HR' then 1 ELSE 0 end )as total_hr,
										SUM(CASE WHEN c.id_type='JR' then 1 ELSE 0 end )as total_jr,
										SUM(CASE WHEN c.id_type='C2' then 1 ELSE 0 end )as total_claim,
										SUM(CASE WHEN c.id_type ='OTHER' then 1 ELSE 0 end )as total_other,
										SUM(CASE WHEN c.id_type NOT IN ('JR','C1','QS','PL','PUD') then 1 ELSE 0 end)as total_job
										FROM ms_dealer a 
										JOIN tr_h2_wo_dealer b on a.id_dealer = b.id_dealer
										JOIN tr_h2_sa_form c on b.id_sa_form = c.id_sa_form
										-- LEFT JOIN ms_customer_h23 d on c.id_customer = d.id_customer 
										LEFT JOIN tr_h2_wo_dealer_pekerjaan e on b.id_work_order = e.id_work_order
										-- JOIN ms_ptm g on d.id_tipe_kendaraan=g.tipe_marketing
										WHERE b.status='closed' and left(b.created_njb_at,10)>='$start_date' and left(b.created_njb_at,10)<='$end_date' and b.id_dealer = '$id_dealer'
										GROUP BY left(b.created_njb_at,10)
										ORDER BY left(b.created_njb_at,10) asc");
			return $tgl_trx;
		}

		public function downloadReportMekanik($id_dealer,$start_date,$end_date){

			$mekanik = $this->db->query("SELECT b.id_karyawan_dealer as id_mekanik, h.nama_lengkap as nama_mekanik, h.id_flp_md as id_mekanik_flp,
										SUM(CASE WHEN c.id_type='ASS1' then 1 ELSE 0 end )as total_ass1,
										SUM(CASE WHEN c.id_type='ASS2' then 1 ELSE 0 end )as total_ass2,
										SUM(CASE WHEN c.id_type='ASS3' then 1 ELSE 0 end )as total_ass3,
										SUM(CASE WHEN c.id_type='ASS4' then 1 ELSE 0 end )as total_ass4,
										SUM(CASE WHEN c.id_type='CS' then 1 ELSE 0 end )as total_cs,
										SUM(CASE WHEN c.id_type='LS' then 1 ELSE 0 end )as total_ls,
										SUM(CASE WHEN c.id_type='OR+' then 1 ELSE 0 end )as total_or,
										SUM(CASE WHEN c.id_type='LR' then 1 ELSE 0 end )as total_lr,
										SUM(CASE WHEN c.id_type='HR' then 1 ELSE 0 end )as total_hr,
										SUM(CASE WHEN c.id_type='JR' then 1 ELSE 0 end )as total_jr,
										SUM(CASE WHEN c.id_type='C2' then 1 ELSE 0 end )as total_claim,
										SUM(CASE WHEN c.id_type ='OTHER' then 1 ELSE 0 end )as total_other,
										SUM(CASE WHEN c.id_type NOT IN ('JR','C1','QS','PL','PUD') then 1 ELSE 0 end)as total_job
										FROM ms_dealer a 
										JOIN tr_h2_wo_dealer b on a.id_dealer = b.id_dealer
										JOIN tr_h2_sa_form c on b.id_sa_form = c.id_sa_form
										-- LEFT JOIN ms_customer_h23 d on c.id_customer = d.id_customer 
										LEFT JOIN tr_h2_wo_dealer_pekerjaan e on b.id_work_order = e.id_work_order
										JOIN ms_h2_jasa f on f.id_jasa=e.id_jasa
										JOIN ms_karyawan_dealer h on b.id_karyawan_dealer =h.id_karyawan_dealer		
										WHERE b.status='closed' and left(b.created_njb_at,10)>='$start_date' and left(b.created_njb_at,10)<='$end_date' and b.id_dealer = '$id_dealer'
										GROUP BY b.id_karyawan_dealer
										ORDER BY b.id_karyawan_dealer asc");
			return $mekanik;
		}

		public function downloadReportSA($id_dealer,$start_date,$end_date){
			$sa = $this->db->query("SELECT b.created_by as id_sa, i.nama_lengkap as nama_sa, i.id_flp_md as id_sa_flp,
										SUM(CASE WHEN c.id_type='ASS1' then 1 ELSE 0 end )as total_ass1,
										SUM(CASE WHEN c.id_type='ASS2' then 1 ELSE 0 end )as total_ass2,
										SUM(CASE WHEN c.id_type='ASS3' then 1 ELSE 0 end )as total_ass3,
										SUM(CASE WHEN c.id_type='ASS4' then 1 ELSE 0 end )as total_ass4,
										SUM(CASE WHEN c.id_type='CS' then 1 ELSE 0 end )as total_cs,
										SUM(CASE WHEN c.id_type='LS' then 1 ELSE 0 end )as total_ls,
										SUM(CASE WHEN c.id_type='OR+' then 1 ELSE 0 end )as total_or,
										SUM(CASE WHEN c.id_type='LR' then 1 ELSE 0 end )as total_lr,
										SUM(CASE WHEN c.id_type='HR' then 1 ELSE 0 end )as total_hr,
										SUM(CASE WHEN c.id_type='JR' then 1 ELSE 0 end )as total_jr,
										SUM(CASE WHEN c.id_type='C2' then 1 ELSE 0 end )as total_claim,
										SUM(CASE WHEN c.id_type ='OTHER' then 1 ELSE 0 end )as total_other,
										SUM(CASE WHEN c.id_type NOT IN ('JR','C1','QS','PL','PUD') then 1 ELSE 0 end)as total_job
										FROM ms_dealer a 
										JOIN tr_h2_wo_dealer b on a.id_dealer = b.id_dealer
										JOIN tr_h2_sa_form c on b.id_sa_form = c.id_sa_form
										-- LEFT JOIN ms_customer_h23 d on c.id_customer = d.id_customer 
										LEFT JOIN tr_h2_wo_dealer_pekerjaan e on b.id_work_order = e.id_work_order
										JOIN ms_h2_jasa f on f.id_jasa=e.id_jasa
										-- JOIN ms_ptm g on d.id_tipe_kendaraan=g.tipe_marketing	
										JOIN ms_user j on j.id_user=b.created_by
										JOIN ms_karyawan_dealer i on j.id_karyawan_dealer = i.id_karyawan_dealer
										-- JOIN ms_karyawan_dealer i on b.created_by = i.id_karyawan_dealer
										WHERE b.status='closed' and left(b.created_njb_at,10)>='$start_date' and left(b.created_njb_at,10)<='$end_date' and b.id_dealer = '$id_dealer'
										GROUP BY b.created_by
										ORDER BY b.created_by asc");
			return $sa;
		}

		public function partRevenue($id_dealer,$start_date,$end_date){
			$partRevSales = $this->db->query(" SELECT d.nama_dealer, SUM(CASE WHEN skp.produk='Parts' then (CASE WHEN b.tipe_diskon='Percentage' then ((b.harga_beli*b.qty)-(b.harga_beli*b.diskon_value/100))
			WHEN b.tipe_diskon='Value' then ((b.harga_beli*b.qty)-b.diskon_value) ELSE b.harga_beli*b.qty END) ELSE 0 END) AS spart,
			SUM(CASE WHEN skp.produk='Parts' and a.referensi='sales' then (CASE WHEN b.tipe_diskon='Percentage' 
			then ((b.harga_beli*b.qty)-(b.harga_beli*b.diskon_value/100))
			WHEN b.tipe_diskon='Value' then ((b.harga_beli*b.qty)-b.diskon_value) ELSE b.harga_beli*b.qty END) ELSE 0 END) AS spart_tanpa_wo,
			SUM(CASE WHEN skp.produk='Parts' and a.referensi='work_order' then (CASE WHEN b.tipe_diskon='Percentage' 
			then ((b.harga_beli*b.qty)-(b.harga_beli*b.diskon_value/100))
			WHEN b.tipe_diskon='Value' then ((b.harga_beli*b.qty)-b.diskon_value) ELSE b.harga_beli*b.qty END) ELSE 0 END) AS spart_wo,
			   SUM(CASE WHEN skp.produk='Oil' and a.referensi='sales' then (CASE WHEN b.tipe_diskon='Percentage' then ((b.harga_beli*b.qty)-(b.harga_beli*b.diskon_value/100))
			WHEN b.tipe_diskon='Value' then ((b.harga_beli*b.qty)-b.diskon_value) ELSE b.harga_beli*b.qty END) ELSE 0 END) AS oil_tanpa_wo,
			   SUM(CASE WHEN skp.produk='Oil' and a.referensi='work_order' then (CASE WHEN b.tipe_diskon='Percentage' then ((b.harga_beli*b.qty)-(b.harga_beli*b.diskon_value/100))
			WHEN b.tipe_diskon='Value' then ((b.harga_beli*b.qty)-b.diskon_value) ELSE b.harga_beli*b.qty END) ELSE 0 END) AS oil_wo,
			   SUM(CASE WHEN c.kelompok_part ='PACC' then (CASE WHEN b.tipe_diskon='Percentage' then ((b.harga_beli*b.qty)-(b.harga_beli*b.diskon_value/100))
			WHEN b.tipe_diskon='Value' then ((b.harga_beli*b.qty)-b.diskon_value) ELSE b.harga_beli*b.qty END) ELSE 0 END) AS accessories,
			   SUM(CASE WHEN c.kelompok_part in('TB','TB1','TBHGP','TBVL','TIRE','TIRE1') then (CASE WHEN b.tipe_diskon='Percentage' then ((b.harga_beli*b.qty)-(b.harga_beli*b.diskon_value/100))
			WHEN b.tipe_diskon='Value' then ((b.harga_beli*b.qty)-b.diskon_value) ELSE b.harga_beli*b.qty END) ELSE 0 END) AS ban,
			   SUM(CASE WHEN c.kelompok_part ='BATT' then (CASE WHEN b.tipe_diskon='Percentage' then ((b.harga_beli*b.qty)-(b.harga_beli*b.diskon_value/100))
			WHEN b.tipe_diskon='Value' then ((b.harga_beli*b.qty)-b.diskon_value) ELSE b.harga_beli*b.qty END) ELSE 0 END) AS aki,
			   SUM(CASE WHEN c.kelompok_part in('SPLUR','SP','SPLUG') then (CASE WHEN b.tipe_diskon='Percentage' then ((b.harga_beli*b.qty)-(b.harga_beli*b.diskon_value/100))
			WHEN b.tipe_diskon='Value' then ((b.harga_beli*b.qty)-b.diskon_value) ELSE b.harga_beli*b.qty END) ELSE 0 END) AS busi,
			   SUM(CASE WHEN c.kelompok_part ='BLDRV' then (CASE WHEN b.tipe_diskon='Percentage' then ((b.harga_beli*b.qty)-(b.harga_beli*b.diskon_value/100))
			WHEN b.tipe_diskon='Value' then ((b.harga_beli*b.qty)-b.diskon_value) ELSE b.harga_beli*b.qty END) ELSE 0 END) AS belt,
			   SUM(CASE WHEN c.kelompok_part ='BRAKE' then (CASE WHEN b.tipe_diskon='Percentage' then ((b.harga_beli*b.qty)-(b.harga_beli*b.diskon_value/100))
			WHEN b.tipe_diskon='Value' then ((b.harga_beli*b.qty)-b.diskon_value) ELSE b.harga_beli*b.qty END) ELSE 0 END) AS brake,
			   SUM(CASE WHEN c.kelompok_part ='PLAST' then (CASE WHEN b.tipe_diskon='Percentage' then ((b.harga_beli*b.qty)-(b.harga_beli*b.diskon_value/100))
			WHEN b.tipe_diskon='Value' then ((b.harga_beli*b.qty)-b.diskon_value) ELSE b.harga_beli*b.qty END) ELSE 0 END) AS plastic_part,
			   SUM(CASE WHEN c.kelompok_part ='EC' then (CASE WHEN b.tipe_diskon='Percentage' then ((b.harga_beli*b.qty)-(b.harga_beli*b.diskon_value/100))
			WHEN b.tipe_diskon='Value' then ((b.harga_beli*b.qty)-b.diskon_value) ELSE b.harga_beli*b.qty END) ELSE 0 END) AS element_cleaner,
			   SUM(CASE WHEN c.kelompok_part in('ACCEC','TL','HELM','CTHV1','OTHERS') then (CASE WHEN b.tipe_diskon='Percentage' then ((b.harga_beli*b.qty)-(b.harga_beli*b.diskon_value/100))
			WHEN b.tipe_diskon='Value' then ((b.harga_beli*b.qty)-b.diskon_value) ELSE b.harga_beli*b.qty END) ELSE 0 END) AS other,
			   SUM(CASE WHEN c.kelompok_part ='FED OIL' then (CASE WHEN b.tipe_diskon='Percentage' then ((b.harga_beli*b.qty)-(b.harga_beli*b.diskon_value/100))
			WHEN b.tipe_diskon='Value' then ((b.harga_beli*b.qty)-b.diskon_value) ELSE b.harga_beli*b.qty END) ELSE 0 END) AS fed
			  from tr_h23_nsc a
			  join tr_h23_nsc_parts b on a.no_nsc=b.no_nsc 
			  join ms_part c on c.id_part_int=b.id_part_int 
			  join ms_dealer d on d.id_dealer=a.id_dealer
			  join ms_h3_md_setting_kelompok_produk skp on skp.id_kelompok_part= c.kelompok_part 
			  where a.created_at>='$start_date 00:00:00' and a.created_at<='$end_date 23:59:59' and a.id_dealer='$id_dealer'
			  group by a.id_dealer  
			  order by d.nama_dealer asc ");
			return $partRevSales;
		}

		public function JasaRevenue($id_dealer,$start_date,$end_date){
		   $jasaRev = $this->db->query("SELECT D.nama_dealer,
		   							SUM(CASE WHEN A.id_type in ('ASS1','ASS2','ASS3','ASS4') THEN B.harga ELSE 0 END) AS ass,
									SUM(CASE WHEN A.id_type ='ASS1' THEN B.harga ELSE 0 END) AS ass1,
									SUM(CASE WHEN A.id_type ='ASS2' THEN B.harga ELSE 0 END) AS ass2,
									SUM(CASE WHEN A.id_type ='ASS3' THEN B.harga ELSE 0 END) AS ass3,
									SUM(CASE WHEN A.id_type ='ASS4' THEN B.harga ELSE 0 END) AS ass4,
									SUM(CASE WHEN A.id_type ='C2' THEN B.harga ELSE 0 END) AS claim,
									SUM(CASE WHEN A.id_type ='LS' THEN B.harga ELSE 0 END) AS ls,
									SUM(CASE WHEN A.id_type ='HR' THEN B.harga ELSE 0 END) AS hr,
									SUM(CASE WHEN A.id_type ='CS' THEN B.harga ELSE 0 END) AS cs,
									SUM(CASE WHEN A.id_type ='LR' THEN B.harga ELSE 0 END) AS lr,
									SUM(CASE WHEN A.id_type ='OR+' THEN B.harga ELSE 0 END) AS or_plus,
									SUM(CASE WHEN A.id_type ='JR' THEN B.harga ELSE 0 END) AS jr,
									SUM(CASE WHEN A.id_type ='PUD' THEN B.harga ELSE 0 END) AS pud,
									SUM(CASE WHEN A.id_type = 'PL' THEN B.harga ELSE 0 END) AS pl,
		   							SUM(CASE WHEN A.id_type ='OTHER' THEN B.harga ELSE 0 END) AS other
	   								FROM tr_h2_wo_dealer_pekerjaan B 
	   								JOIN ms_h2_jasa A ON A.id_jasa=B.id_jasa 
									JOIN tr_h2_wo_dealer C ON B.id_work_order=C.id_work_order
									JOIN ms_dealer D ON D.id_dealer = C.id_dealer
	   								WHERE C.status='closed'
	   								AND C.created_njb_at>='$start_date 00:00:00' and C.created_njb_at<='$end_date 23:59:59' and C.id_dealer='$id_dealer' AND B.pekerjaan_batal=0 ");
		   return $jasaRev;
		}

		public function alasanDatangKeAhass($id_dealer,$start_date,$end_date){
		   $alasanDatang = $this->db->query("SELECT c.alasan_ke_ahass,
						SUM(CASE WHEN c.id_type='ASS1' then 1 ELSE 0 end )as total_ass1,
						SUM(CASE WHEN c.id_type='ASS2' then 1 ELSE 0 end )as total_ass2,
						SUM(CASE WHEN c.id_type='ASS3' then 1 ELSE 0 end )as total_ass3,
						SUM(CASE WHEN c.id_type='ASS4' then 1 ELSE 0 end )as total_ass4,
						SUM(CASE WHEN c.id_type='CS' then 1 ELSE 0 end )as total_cs,
						SUM(CASE WHEN c.id_type='LS' then 1 ELSE 0 end )as total_ls,
						SUM(CASE WHEN c.id_type='OR+' then 1 ELSE 0 end )as total_or,
						SUM(CASE WHEN c.id_type='LR' then 1 ELSE 0 end )as total_lr,
						SUM(CASE WHEN c.id_type='HR' then 1 ELSE 0 end )as total_hr,
						SUM(CASE WHEN c.id_type='JR' then 1 ELSE 0 end )as total_jr,
						SUM(CASE WHEN c.id_type='C2' then 1 ELSE 0 end )as total_claim,
						SUM(CASE WHEN c.id_type='OTHER' then 1 ELSE 0 end )as total_other,
						SUM(CASE WHEN c.id_type NOT IN ('JR','C1','QS','PL','PUD') then 1 ELSE 0 end)as total_job
						FROM ms_dealer a 
						JOIN tr_h2_wo_dealer b on a.id_dealer = b.id_dealer
						JOIN tr_h2_sa_form c on b.id_sa_form = c.id_sa_form
						-- LEFT JOIN ms_customer_h23 d on c.id_customer = d.id_customer 
						JOIN tr_h2_wo_dealer_pekerjaan e on b.id_work_order = e.id_work_order
						JOIN ms_h2_jasa f on f.id_jasa=e.id_jasa
						WHERE b.status='closed' and left(b.created_njb_at,10)>='$start_date' and left(b.created_njb_at,10)<='$end_date' AND B.pekerjaan_batal=0 and b.id_dealer='$id_dealer'
						GROUP BY c.alasan_ke_ahass
						ORDER BY c.alasan_ke_ahass asc");

		   return $alasanDatang;
		}

		public function activityPromotion($id_dealer,$start_date,$end_date){

		   $activityPromotion = $this->db->query("SELECT c.activity_promotion_id, j.name as activity_promotion,
						SUM(CASE WHEN c.id_type='ASS1' then 1 ELSE 0 end )as total_ass1,
						SUM(CASE WHEN c.id_type='ASS2' then 1 ELSE 0 end )as total_ass2,
						SUM(CASE WHEN c.id_type='ASS3' then 1 ELSE 0 end )as total_ass3,
						SUM(CASE WHEN c.id_type='ASS4' then 1 ELSE 0 end )as total_ass4,
						SUM(CASE WHEN c.id_type='CS' then 1 ELSE 0 end )as total_cs,
						SUM(CASE WHEN c.id_type='LS' then 1 ELSE 0 end )as total_ls,
						SUM(CASE WHEN c.id_type='OR+' then 1 ELSE 0 end )as total_or,
						SUM(CASE WHEN c.id_type='LR' then 1 ELSE 0 end )as total_lr,
						SUM(CASE WHEN c.id_type='HR' then 1 ELSE 0 end )as total_hr,
						SUM(CASE WHEN c.id_type='JR' then 1 ELSE 0 end )as total_jr,
						SUM(CASE WHEN c.id_type='C2' then 1 ELSE 0 end )as total_claim,
						SUM(CASE WHEN c.id_type ='OTHER' then 1 ELSE 0 end )as total_other,
						SUM(CASE WHEN c.id_type NOT IN ('JR','C1','QS','PL','PUD') then 1 ELSE 0 end)as total_job
						FROM ms_dealer a 
						JOIN tr_h2_wo_dealer b on a.id_dealer = b.id_dealer
						JOIN tr_h2_sa_form c on b.id_sa_form = c.id_sa_form
						-- LEFT JOIN ms_customer_h23 d on c.id_customer = d.id_customer 
						LEFT JOIN tr_h2_wo_dealer_pekerjaan e on b.id_work_order = e.id_work_order
						JOIN ms_h2_jasa f on f.id_jasa=e.id_jasa
						JOIN dms_ms_activity_promotion j on j.id = c.activity_promotion_id 
						WHERE b.status='closed' and b.created_njb_at>='$start_date 00:00:00' and b.created_njb_at<='$end_date 23:59:59' and b.id_dealer='$id_dealer'
						GROUP BY j.name
						ORDER BY j.name asc");

		   return $activityPromotion;
		}

		public function activityCapacity($id_dealer,$start_date,$end_date){

		   $activityCapacity = $this->db->query("SELECT c.activity_capacity_id, k.keterangan as activity_capacity,
						SUM(CASE WHEN c.id_type='ASS1' then 1 ELSE 0 end )as total_ass1,
						SUM(CASE WHEN c.id_type='ASS2' then 1 ELSE 0 end )as total_ass2,
						SUM(CASE WHEN c.id_type='ASS3' then 1 ELSE 0 end )as total_ass3,
						SUM(CASE WHEN c.id_type='ASS4' then 1 ELSE 0 end )as total_ass4,
						SUM(CASE WHEN c.id_type='CS' then 1 ELSE 0 end )as total_cs,
						SUM(CASE WHEN c.id_type='LS' then 1 ELSE 0 end )as total_ls,
						SUM(CASE WHEN c.id_type='OR+' then 1 ELSE 0 end )as total_or,
						SUM(CASE WHEN c.id_type='LR' then 1 ELSE 0 end )as total_lr,
						SUM(CASE WHEN c.id_type='HR' then 1 ELSE 0 end )as total_hr,
						SUM(CASE WHEN c.id_type='JR' then 1 ELSE 0 end )as total_jr,
						SUM(CASE WHEN c.id_type='C2' then 1 ELSE 0 end )as total_claim,
						SUM(CASE WHEN c.id_type in('OTHER') then 1 ELSE 0 end )as total_other,
						SUM(CASE WHEN c.id_type NOT IN ('JR','C1','QS','PL','PUD') then 1 ELSE 0 end)as total_job
						FROM ms_dealer a 
						JOIN tr_h2_wo_dealer b on a.id_dealer = b.id_dealer
						JOIN tr_h2_sa_form c on b.id_sa_form = c.id_sa_form
						-- LEFT JOIN ms_customer_h23 d on c.id_customer = d.id_customer 
						LEFT JOIN tr_h2_wo_dealer_pekerjaan e on b.id_work_order = e.id_work_order
						JOIN ms_h2_jasa f on f.id_jasa=e.id_jasa
						JOIN dms_ms_activity_capacity k on k.id = c.activity_capacity_id
						WHERE b.status='closed' and b.created_njb_at>='$start_date 00:00:00' and b.created_njb_at<='$end_date 23:59:59' and b.id_dealer='$id_dealer'
						GROUP BY k.keterangan
						ORDER BY k.keterangan asc");

		   return $activityCapacity;
		}

		public function downloadReportAhass($id_dealer,$start_date,$end_date){
			$all_ahass = $this->db->query("SELECT md.nama_dealer, wo.id_dealer, SUM(CASE WHEN jasa.id_type='ASS1' then 1 ELSE 0 end )as total_ass1,
				SUM(CASE WHEN jasa.id_type='ASS2' then 1 ELSE 0 end )as total_ass2,
				SUM(CASE WHEN jasa.id_type='ASS3' then 1 ELSE 0 end )as total_ass3,
				SUM(CASE WHEN jasa.id_type='ASS4' then 1 ELSE 0 end )as total_ass4,
				SUM(CASE WHEN jasa.id_type='CS' then 1 ELSE 0 end )as total_cs,
				SUM(CASE WHEN jasa.id_type='LS' then 1 ELSE 0 end )as total_ls,
				SUM(CASE WHEN jasa.id_type='OR+' then 1 ELSE 0 end )as total_or,
				SUM(CASE WHEN jasa.id_type='LR' then 1 ELSE 0 end )as total_lr,
				SUM(CASE WHEN jasa.id_type='HR' then 1 ELSE 0 end )as total_hr,
				SUM(CASE WHEN jasa.id_type='JR' then 1 ELSE 0 end )as total_jr,
				SUM(CASE WHEN jasa.id_type='C2' then 1 ELSE 0 end )as total_claim
				FROM tr_h2_wo_dealer wo
				join tr_h2_wo_dealer_pekerjaan wop on wop.id_work_order=wo.id_work_order 
				JOIN ms_dealer md on md.id_dealer = wo.id_dealer 
				JOIN ms_h2_jasa jasa on jasa.id_jasa = wop.id_jasa
				where wo.status='Closed' and wo.created_njb_at>='$start_date 00:00:00' and wo.created_njb_at<='$end_date 23:59:59' and wop.pekerjaan_batal=0 and wo.id_dealer = $id_dealer
				group by wo.id_dealer");
			return $all_ahass;
		}

		public function dataUEperJam($id_dealer,$start_date,$end_date){
			$ue_jam=$this->db->query("SELECT a.nama_dealer,
						SUM(CASE WHEN time(b.created_njb_at) between '07:00:00' and '08:00:00' then 1 ELSE 0 end) as jam_7,
						SUM(CASE WHEN time(b.created_njb_at) between '08:00:01' and '09:00:00' then 1 ELSE 0 end) as jam_8,
						SUM(CASE WHEN time(b.created_njb_at) between '09:00:01' and '10:00:00' then 1 ELSE 0 end) as jam_9,
						SUM(CASE WHEN time(b.created_njb_at) between '10:00:01' and '11:00:00' then 1 ELSE 0 end) as jam_10,
						SUM(CASE WHEN time(b.created_njb_at) between '11:00:01' and '12:00:00' then 1 ELSE 0 end) as jam_11,
						SUM(CASE WHEN time(b.created_njb_at) between '12:00:01' and '13:00:00' then 1 ELSE 0 end) as jam_12,
						SUM(CASE WHEN time(b.created_njb_at) between '13:00:01' and '14:00:00' then 1 ELSE 0 end) as jam_13,
						SUM(CASE WHEN time(b.created_njb_at) between '14:00:01' and '15:00:00' then 1 ELSE 0 end) as jam_14,
						SUM(CASE WHEN time(b.created_njb_at) between '15:00:01' and '16:00:00' then 1 ELSE 0 end) as jam_15,
						SUM(CASE WHEN time(b.created_njb_at) between '16:00:01' and '17:00:00' then 1 ELSE 0 end) as jam_16
						FROM ms_dealer a 
						JOIN tr_h2_wo_dealer b on a.id_dealer = b.id_dealer
						JOIN tr_h2_sa_form c on c.id_sa_form=b.id_sa_form
						where b.status='closed' and b.created_njb_at >= '$start_date 00:00:00' and b.created_njb_at <='$end_date 23:59:59' and b.id_dealer='$id_dealer'")->row();
   			return $ue_jam;
		}

		public function dataTOJ($id_dealer,$start_date,$end_date){	
			$toj = $this->db->query("SELECT md.nama_dealer, wo.id_dealer, SUM(CASE WHEN jasa.id_type='ASS1' then 1 ELSE 0 end )as total_ass1,
				SUM(CASE WHEN jasa.id_type='ASS2' then 1 ELSE 0 end )as total_ass2,
				SUM(CASE WHEN jasa.id_type='ASS3' then 1 ELSE 0 end )as total_ass3,
				SUM(CASE WHEN jasa.id_type='ASS4' then 1 ELSE 0 end )as total_ass4,
				SUM(CASE WHEN jasa.id_type='CS' then 1 ELSE 0 end )as total_cs,
				SUM(CASE WHEN jasa.id_type='LS' then 1 ELSE 0 end )as total_ls,
				SUM(CASE WHEN jasa.id_type='OR+' then 1 ELSE 0 end )as total_or,
				SUM(CASE WHEN jasa.id_type='LR' then 1 ELSE 0 end )as total_lr,
				SUM(CASE WHEN jasa.id_type='HR' then 1 ELSE 0 end )as total_hr,
				SUM(CASE WHEN jasa.id_type='JR' then 1 ELSE 0 end )as total_jr,
				SUM(CASE WHEN jasa.id_type='C2' then 1 ELSE 0 end )as total_claim,
				SUM(CASE WHEN jasa.id_type='PUD' then 1 ELSE 0 end )as total_pud,
				SUM(CASE WHEN jasa.id_type='PL' then 1 ELSE 0 end )as total_pl,
				SUM(CASE WHEN jasa.id_type ='OTHER' then 1 ELSE 0 end )as total_other
				FROM tr_h2_wo_dealer wo
				join tr_h2_wo_dealer_pekerjaan wop on wop.id_work_order=wo.id_work_order 
				JOIN ms_dealer md on md.id_dealer = wo.id_dealer 
				JOIN ms_h2_jasa jasa on jasa.id_jasa = wop.id_jasa
				where wo.status='Closed' and wo.created_njb_at>='$start_date 00:00:00' and wo.created_njb_at<='$end_date 23:59:59' and wop.pekerjaan_batal=0 and wo.id_dealer = $id_dealer
				group by wo.id_dealer
				ORDER BY md.nama_dealer asc");
		   return $toj;
		}


		public function dataTOJ11($id_dealer, $start_date,$end_date){
			$toj11 = $this->db->query("SELECT md.nama_dealer, wo.id_dealer, SUM(CASE WHEN sa.id_type='ASS1' then 1 ELSE 0 end )as total_ass1,
				SUM(CASE WHEN sa.id_type='ASS2' then 1 ELSE 0 end )as total_ass2,
				SUM(CASE WHEN sa.id_type='ASS3' then 1 ELSE 0 end )as total_ass3,
				SUM(CASE WHEN sa.id_type='ASS4' then 1 ELSE 0 end )as total_ass4,
				SUM(CASE WHEN sa.id_type='CS' then 1 ELSE 0 end )as total_cs,
				SUM(CASE WHEN sa.id_type='LS' then 1 ELSE 0 end )as total_ls,
				SUM(CASE WHEN sa.id_type='OR+' then 1 ELSE 0 end )as total_or,
				SUM(CASE WHEN sa.id_type='LR' then 1 ELSE 0 end )as total_lr,
				SUM(CASE WHEN sa.id_type='HR' then 1 ELSE 0 end )as total_hr,
				SUM(CASE WHEN sa.id_type='JR' then 1 ELSE 0 end )as total_jr,
				SUM(CASE WHEN sa.id_type='C2' then 1 ELSE 0 end )as total_claim
				FROM tr_h2_wo_dealer wo
				-- join tr_h2_wo_dealer_pekerjaan wop on wop.id_work_order=wo.id_work_order 
				JOIN tr_h2_sa_form sa on sa.id_sa_form=wo.id_sa_form
				JOIN ms_dealer md on md.id_dealer = wo.id_dealer 
				where wo.status='Closed' and wo.created_njb_at>='$start_date 00:00:00' and wo.created_njb_at<='$end_date 23:59:59' and wo.id_dealer = $id_dealer
				group by wo.id_dealer
				ORDER BY md.nama_dealer asc");
		   return $toj11;
		}

		public function partCount($id_dealer,$start_date,$end_date){
			$partCount = $this->db->query("SELECT d.nama_dealer,
			SUM(CASE WHEN skp.produk='Parts' and a.referensi='sales' then b.qty ELSE 0 END) AS spart_tanpa_wo,
			SUM(CASE WHEN skp.produk='Parts' and a.referensi='work_order' then b.qty ELSE 0 END) AS spart_wo,
			   SUM(CASE WHEN c.kelompok_part in('GMO','OIL') and a.referensi='sales' then b.qty ELSE 0 END) AS oil_tanpa_wo,
			   SUM(CASE WHEN c.kelompok_part in('GMO','OIL') and a.referensi='work_order' then b.qty ELSE 0 END) AS oil_wo,
			   SUM(CASE WHEN c.kelompok_part ='PACC' then b.qty ELSE 0 END) AS accessories,
			   SUM(CASE WHEN c.kelompok_part in('TB','TB1','TBHGP','TBVL','TIRE','TIRE1') then b.qty ELSE 0 END) AS ban,
			   SUM(CASE WHEN c.kelompok_part ='BATT' then b.qty ELSE 0 END) AS aki,
			   SUM(CASE WHEN c.kelompok_part in('SPLUR','SP','SPLUG') then b.qty ELSE 0 END) AS busi,
			   SUM(CASE WHEN c.kelompok_part ='BLDRV' then b.qty ELSE 0 END) AS belt,
			   SUM(CASE WHEN c.kelompok_part ='BRAKE' then b.qty ELSE 0 END) AS brake,
			   SUM(CASE WHEN c.kelompok_part ='PLAST' then b.qty ELSE 0 END) AS plastic_part,
			   SUM(CASE WHEN c.kelompok_part ='EC' then b.qty ELSE 0 END) AS element_cleaner,
			   SUM(CASE WHEN c.kelompok_part in('ACCEC','TL','HELM','CTHV1','OTHERS') then b.qty ELSE 0 END) AS other,
			   SUM(CASE WHEN c.kelompok_part ='FED OIL' then b.qty ELSE 0 END) AS fed
			  from tr_h23_nsc a
			  join tr_h23_nsc_parts b on a.no_nsc=b.no_nsc 
			  join ms_part c on c.id_part_int=b.id_part_int 
			  join ms_dealer d on d.id_dealer=a.id_dealer 
			  join ms_h3_md_setting_kelompok_produk skp on skp.id_kelompok_part= c.kelompok_part  
			  where a.created_at>='$start_date 00:00:00' and a.created_at<='$end_date 23:59:59' and a.id_dealer='$id_dealer'
			  group by a.id_dealer order by d.nama_dealer asc");
			return $partCount;
		}

		public function salesIn($id_dealer,$start_date,$end_date){
	
			$salesIn = $this->db->query("SELECT md.nama_dealer, SUM(case when so.produk='Parts' then dso.total else 0 end) as part, SUM(case when so.produk='Oil' then dso.total else 0 end) as oli  
			FROM tr_h3_md_sales_order so
			JOIN tr_h3_md_do_sales_order dso on dso.id_sales_order_int=so.id 
			JOIN tr_h3_md_picking_list pl on dso.id = pl.id_ref_int 
			JOIN tr_h3_md_packing_sheet ps on ps.id_picking_list_int=pl.id 
			JOIN ms_dealer md on md.id_dealer=so.id_dealer
			WHERE ps.tgl_faktur >='$start_date 00:00:00' and ps.tgl_faktur <='$end_date 23:59:59' and b.id_dealer='$id_dealer'
			GROUP BY so.id_dealer 
		   	ORDER BY md.nama_dealer ASC");
		   return $salesIn;
		}

		public function posAHASSRevenueWO($id_dealer,$start_date,$end_date,$type){

		   $filter_pos = '';
			if($type=='pos_rev'){
				$filter_pos = "AND d.pos='ya' AND h2='1'";
			}elseif($type=='ahass_rev'){
				$filter_pos = "";
			}

			$posAHASSWO = $this->db->query("SELECT d.nama_dealer, SUM(CASE WHEN c.kelompok_part in('ACB','ACC','ACG','AH','AHM','BB','BBIST','BM1','BR','BRNG',
				'BRNG2','BRNG3','BS','BT','CABLE','CB','CCKIT','CD','CDKGP','CDKIT','CH','CHAIN','COMP','COOL','CRKIT',
				'DIHVL','DISK','ELECT','EP','EPHVL','EPMTI','ET','FKT','FLUID','GS','GSA','GSB','GST','HAH','HM','HNMTI',
				'HPLAS','HRW','HSD','IMPOR','INS','ISTC','LGS','LSIST','MF','MTI','MUF','N','NF','OAHM1','OAHM2','OC','OFCC',
				'OINS','OISTC','OKGD','OMTI','ORPL','OSEAL','OTHER','OTHR','PA','PAINT','PS','PSKIT','PSTN','PT','RBR','RIMWH',
				'RPHVL','RPIST','RSKIT','RW','RW2','RW3','RWHVL','SA','SAOIL','SD','SDN','SDN2','SDT','SE','SHOCK','SP2','SPGUI',
				'SPOKE','STR','TAS','TDI','TR','VALVE','VV') then b.subtotal ELSE 0 END) AS spart_wo,
				SUM(CASE WHEN c.kelompok_part in('GMO','OIL') then b.subtotal ELSE 0 END) AS oil_wo
				from tr_h2_wo_dealer a
				join tr_h2_wo_dealer_parts b on a.id_work_order=b.id_work_order 
				join ms_part c on b.id_part_int=c.id_part_int 
				join ms_dealer d on a.id_dealer=d.id_dealer
				where left(a.created_njb_at,10)>='$start_date' and left(a.created_njb_at,10)<='$end_date' and a.status='closed'
				and a.id_dealer='$id_dealer' $filter_pos
				 GROUP by a.id_dealer
				 order by d.nama_dealer asc");

			return $posAHASSWO;
		}

		public function posAHASSRevenueTanpaWO($id_dealer,$start_date,$end_date,$type){
		   $filter_pos = '';
			if($type=='pos_rev'){
				$filter_pos = "AND d.pos='ya' AND h2='1'";
			}elseif($type=='ahass_rev'){
				$filter_pos = "";
			}

			$posAHASSTanpaWO = $this->db->query("SELECT d.nama_dealer , SUM(CASE WHEN c.kelompok_part in('ACB','ACC','ACG','AH','AHM','BB','BBIST','BM1','BR','BRNG',
				'BRNG2','BRNG3','BS','BT','CABLE','CB','CCKIT','CD','CDKGP','CDKIT','CH','CHAIN','COMP','COOL','CRKIT',
				'DIHVL','DISK','ELECT','EP','EPHVL','EPMTI','ET','FKT','FLUID','GS','GSA','GSB','GST','HAH','HM','HNMTI',
				'HPLAS','HRW','HSD','IMPOR','INS','ISTC','LGS','LSIST','MF','MTI','MUF','N','NF','OAHM1','OAHM2','OC','OFCC',
				'OINS','OISTC','OKGD','OMTI','ORPL','OSEAL','OTHER','OTHR','PA','PAINT','PS','PSKIT','PSTN','PT','RBR','RIMWH',
				'RPHVL','RPIST','RSKIT','RW','RW2','RW3','RWHVL','SA','SAOIL','SD','SDN','SDN2','SDT','SE','SHOCK','SP2','SPGUI',
				'SPOKE','STR','TAS','TDI','TR','VALVE','VV') then a.tot_nsc ELSE 0 END) AS spart_tanpa_wo,
				   SUM(CASE WHEN c.kelompok_part in('GMO','OIL') then a.tot_nsc ELSE 0 END) AS oil_tanpa_wo
				from tr_h23_nsc a
				join tr_h23_nsc_parts b on a.no_nsc=b.no_nsc 
				join ms_part c on c.id_part_int=b.id_part_int 
				join ms_dealer d on a.id_dealer=d.id_dealer
				where left(a.created_at ,10)>='$start_date' and left(a.created_at,10)<='$end_date' and a.referensi ='sales'
				and a.id_dealer='$id_dealer' $filter_pos
				GROUP by a.id_dealer
				order by d.nama_dealer asc");

			return $posAHASSTanpaWO;
		}

		public function posAHASSJasa($id_dealer,$start_date,$end_date,$type){
			// $type='';
		   $filter_pos = '';
			if($type=='pos_rev'){
				$filter_pos = "AND d.pos='ya' AND h2='1'";
			}elseif($type=='ahass_rev'){
				$filter_pos = "";
			}

			$posAHASSJasa = $this->db->query("SELECT d.nama_dealer, sum(a.total_jasa) as total_jasa
			FROM tr_h2_wo_dealer a
			join ms_dealer d on a.id_dealer=d.id_dealer
			where left(a.created_njb_at ,10)>='$start_date' and left(a.created_njb_at,10)<='$end_date' and a.status='closed'
			and a.id_dealer='$id_dealer' $filter_pos
			GROUP by a.id_dealer
			order by d.nama_dealer asc");

			return $posAHASSJasa;
		}

		public function mekanikPosServiceRev($id_dealer,$start_date,$end_date,$type){

		   $filter_pos = '';
			if($type=='pos_rev'){
				$filter_pos  = "AND a.pos='ya'";
			}elseif($type=='ahass_rev'){
				$filter_pos = "";
			}

		   $mekanikposService = $this->db->query("SELECT a.nama_dealer, h.nama_lengkap as nama_mekanik, SUM(b.grand_total) as revenue_mekanik
							FROM ms_dealer a 
							JOIN tr_h2_wo_dealer b on a.id_dealer = b.id_dealer
							JOIN ms_karyawan_dealer h on b.id_karyawan_dealer =h.id_karyawan_dealer		
							WHERE b.status='closed' and left(b.created_njb_at,10) between '$start_date' and '$end_date' $filter_pos and b.id_dealer='$id_dealer'
							GROUP BY b.id_karyawan_dealer
							ORDER BY b.id_karyawan_dealer asc");

			return $mekanikposService;
		}

		public function saPosServiceRev($id_dealer,$start_date,$end_date,$type){
		   $filter_pos = '';
			if($type=='pos_rev'){
				$filter_pos  = "AND a.pos='ya'";
			}elseif($type=='ahass_rev'){
				$filter_pos = "";
			}

		   $saposService = $this->db->query("SELECT a.nama_dealer, SUM(b.grand_total) as revenue_sa, h.nama_lengkap as nama_sa
						FROM ms_dealer a 
						JOIN tr_h2_wo_dealer b on a.id_dealer = b.id_dealer
						JOIN ms_user j on j.id_user=b.created_by
						JOIN ms_karyawan_dealer h on j.id_karyawan_dealer=h.id_karyawan_dealer		
						WHERE b.status='closed' and left(b.created_njb_at,10) between '$start_date' and '$end_date' and b.id_dealer='$id_dealer' $filter_pos 
						GROUP BY b.created_by
						ORDER BY b.created_by asc");

			return $saposService;
		}

		public function activityPromotionRev($id_dealer,$start_date,$end_date){
		   $apRev = $this->db->query("SELECT sum(b.grand_total) as revenue_ap,j.name 
						FROM ms_dealer a 
						JOIN tr_h2_wo_dealer b on a.id_dealer = b.id_dealer
						JOIN tr_h2_sa_form c on b.id_sa_form = c.id_sa_form
						JOIN tr_h2_wo_dealer_pekerjaan e on b.id_work_order = e.id_work_order
						LEFT JOIN ms_h2_jasa f on f.id_jasa=e.id_jasa
						JOIN dms_ms_activity_promotion j on j.id = c.activity_promotion_id
						where b.status='closed' and left(b.created_njb_at,10) between '$start_date' and '$end_date' and b.id_dealer='$id_dealer'
						group by c.activity_promotion_id");

		  return $apRev;
		}

		public function activityPromotionRev2($id_dealer,$start_date,$end_date){
			$apRev2 = $this->db->query("SELECT d.nama_dealer, SUM(CASE WHEN a.activity_promotion_id ='1' THEN b.grand_total ELSE 0 END) AS pos_service,
			SUM(CASE WHEN a.activity_promotion_id ='2' THEN b.grand_total ELSE 0 END) AS join_dealer_activity,
			SUM(CASE WHEN a.activity_promotion_id ='3' THEN b.grand_total ELSE 0 END) AS group_customer,
			SUM(CASE WHEN a.activity_promotion_id ='4' THEN b.grand_total ELSE 0 END) AS public_area,
			SUM(CASE WHEN a.activity_promotion_id ='5' THEN b.grand_total ELSE 0 END) AS emergency_id,
			SUM(CASE WHEN a.activity_promotion_id ='6' THEN b.grand_total ELSE 0 END) AS pit_express,
			SUM(CASE WHEN a.activity_promotion_id ='7' THEN b.grand_total ELSE 0 END) AS reminder,
			SUM(CASE WHEN a.activity_promotion_id ='8' THEN b.grand_total ELSE 0 END) AS ahass_event_ahm,
			SUM(CASE WHEN a.activity_promotion_id ='9' THEN b.grand_total ELSE 0 END) AS ahass_event_md,
			SUM(CASE WHEN a.activity_promotion_id ='10' THEN b.grand_total ELSE 0 END) AS ahass_event_ahass,
			SUM(CASE WHEN a.activity_promotion_id ='11' THEN b.grand_total ELSE 0 END) AS non_promotion,
			SUM(CASE WHEN a.activity_promotion_id ='12' THEN b.grand_total ELSE 0 END) AS pit_express_outdoor,
			SUM(CASE WHEN a.activity_promotion_id ='13' THEN b.grand_total ELSE 0 END) AS pit_express_indoor,
			SUM(CASE WHEN a.activity_promotion_id ='14' THEN b.grand_total ELSE 0 END) AS ahass_keliling,
			SUM(CASE WHEN a.activity_promotion_id ='15' THEN b.grand_total ELSE 0 END) AS home_care
				from tr_h2_sa_form a
				join tr_h2_wo_dealer b on a.id_sa_form=b.id_sa_form 
				join dms_ms_activity_promotion c on c.id=a.activity_promotion_id 
				join ms_dealer d on d.id_dealer=b.id_dealer 
				where left(b.created_njb_at,10)>='$start_date' and left(b.created_njb_at,10)<='$end_date' and b.status='closed' and b.id_dealer='$id_dealer'
				group by b.id_dealer 
				order by d.nama_dealer asc ");
		   return $apRev2;
		}

		public function dataMekanik($id_dealer, $start_date, $end_date){
		   $dataMekanik=$this->db->query("SELECT DISTINCT b.id_karyawan_dealer, c.id_flp_md,b.id_absen, c.nama_lengkap, d.nama_dealer, sum(case when b.aktif=1 then 1 else 0 end) as kehadiran, sum(case when b.aktif=0 then 1 else 0 end) as tidak_hadir, a.id_dealer, ('16:30:00'-DATE_FORMAT(a.created_at,'%h:%m:%s')) as waktu
						FROM tr_h2_absen_mekanik a 
						join tr_h2_absen_mekanik_detail b on b.id_absen = a.id_absen 
						join ms_karyawan_dealer c on c.id_karyawan_dealer = b.id_karyawan_dealer 
						join ms_dealer d on d.id_dealer = a.id_dealer 
						where a.tanggal >= '$start_date' and a.tanggal <= '$end_date' and a.id_dealer='$id_dealer'
						group by c.id_karyawan_dealer");
						return $dataMekanik;
		}

		public function dataAHASSperAlasanDatang($id_dealer,$start_date,$end_date){

		   $alasankeAHASS= $this->db->query("SELECT a.nama_dealer,
						SUM(CASE WHEN c.alasan_ke_ahass ='Inisiatif sendiri' THEN 1 ELSE 0 END) AS inisiatif_sendiri,
						SUM(CASE WHEN c.alasan_ke_ahass ='Stiker Reminder' THEN 1 ELSE 0 END) AS stiker_reminder,
						SUM(CASE WHEN c.alasan_ke_ahass not in ('Inisiatif sendiri','Stiker Reminder') THEN 1 ELSE 0 END) AS lainnya
						FROM ms_dealer a 
						JOIN tr_h2_wo_dealer b on a.id_dealer = b.id_dealer
						JOIN tr_h2_sa_form c on b.id_sa_form = c.id_sa_form
						where left(b.created_njb_at ,10)>='$start_date' and left(b.created_njb_at,10)<='$end_date' and b.status='closed' and b.id_dealer='$id_dealer'
						group by  b.id_dealer order by b.id_dealer asc");
		   return $alasankeAHASS;
		}

		public function dataAHASSperActivityPromotion($id_dealer,$start_date,$end_date){
		   $ue_ap=$this->db->query("SELECT a.nama_dealer,
						SUM(CASE WHEN c.activity_promotion_id ='1' THEN 1 ELSE 0 END) AS pos_service,
						SUM(CASE WHEN c.activity_promotion_id ='2' THEN 1 ELSE 0 END) AS join_dealer_activity,
						SUM(CASE WHEN c.activity_promotion_id ='3' THEN 1 ELSE 0 END) AS group_customer,
						SUM(CASE WHEN c.activity_promotion_id ='4' THEN 1 ELSE 0 END) AS public_area,
						SUM(CASE WHEN c.activity_promotion_id ='5' THEN 1 ELSE 0 END) AS emergency_id,
						SUM(CASE WHEN c.activity_promotion_id ='6' THEN 1 ELSE 0 END) AS pit_express,
						SUM(CASE WHEN c.activity_promotion_id ='7' THEN 1 ELSE 0 END) AS reminder,
						SUM(CASE WHEN c.activity_promotion_id ='8' THEN 1 ELSE 0 END) AS ahass_event_ahm,
						SUM(CASE WHEN c.activity_promotion_id ='9' THEN 1 ELSE 0 END) AS ahass_event_md,
						SUM(CASE WHEN c.activity_promotion_id ='10' THEN 1 ELSE 0 END) AS ahass_event_ahass,
						SUM(CASE WHEN c.activity_promotion_id ='11' THEN 1 ELSE 0 END) AS non_promotion,
						SUM(CASE WHEN c.activity_promotion_id ='12' THEN 1 ELSE 0 END) AS pit_express_outdoor,
						SUM(CASE WHEN c.activity_promotion_id ='13' THEN 1 ELSE 0 END) AS pit_express_indoor,
						SUM(CASE WHEN c.activity_promotion_id ='14' THEN 1 ELSE 0 END) AS ahass_keliling,
						SUM(CASE WHEN c.activity_promotion_id ='15' THEN 1 ELSE 0 END) AS home_care
		   				FROM ms_dealer a 
						JOIN tr_h2_wo_dealer b on a.id_dealer = b.id_dealer
						JOIN tr_h2_sa_form c on b.id_sa_form = c.id_sa_form
						JOIN dms_ms_activity_promotion d on d.id = c.activity_promotion_id 
						where left(b.created_njb_at,10)>='$start_date' and left(b.created_njb_at,10)<='$end_date' and b.status='closed' and b.id_dealer='$id_dealer'
						group by c.id_dealer
						order by c.id_dealer asc");
		   return $ue_ap;
		}

		public function dataAHASSperActivityCapacity($id_dealer,$start_date,$end_date){

			$ue_ac=$this->db->query("SELECT a.nama_dealer,
						SUM(CASE WHEN c.activity_capacity_id ='1' THEN 1 ELSE 0 END) AS booking_service,
						SUM(CASE WHEN c.activity_capacity_id ='2' THEN 1 ELSE 0 END) AS happy_hours,
						SUM(CASE WHEN c.activity_capacity_id ='3' THEN 1 ELSE 0 END) AS lainnya
						FROM ms_dealer a 
						JOIN tr_h2_wo_dealer b on a.id_dealer = b.id_dealer
						JOIN tr_h2_sa_form c on b.id_sa_form = c.id_sa_form
						join dms_ms_activity_capacity d on d.id =c.activity_capacity_id 
						where left(b.created_njb_at,10)>='$start_date' and left(b.created_njb_at,10)<='$end_date' and b.status='closed' and b.id_dealer='$id_dealer'
						group by b.id_dealer
						order by b.id_dealer asc");
			return $ue_ac;
		}

		public function dataSegmentMotor($id_dealer,$start_date,$end_date){

			   $ue_segment=$this->db->query("SELECT a.nama_dealer,
						SUM(CASE WHEN h.id_kategori='T' then 1 ELSE 0 end) as matic,
						SUM(CASE WHEN h.id_kategori='H' then 1 ELSE 0 end) as cub,
						SUM(CASE WHEN h.id_segment in ('SM','SL') then 1 ELSE 0 end) as sport,
						SUM(CASE WHEN h.id_segment='SH' then 1 ELSE 0 end) as big_bike,
						SUM(CASE WHEN h.id_series = 'VARIO150' then 1 ELSE 0 end) as vario,
						SUM(CASE WHEN h.id_series = 'SCOOPY' then 1 ELSE 0 end) as scoopy,
						SUM(CASE WHEN h.id_series = 'GENIO' then 1 ELSE 0 end) as genio,
						SUM(CASE WHEN h.id_series IN ('ADV150','ADV160','ADV750') then 1 ELSE 0 end) as adv,
						SUM(CASE WHEN h.id_series IN ('SUPRAX100','SUPRAX125') then 1 ELSE 0 end) as supra
						FROM ms_dealer a 
						JOIN tr_h2_wo_dealer b on a.id_dealer = b.id_dealer
						JOIN tr_h2_sa_form c on c.id_sa_form=b.id_sa_form
						LEFT JOIN ms_customer_h23 d on c.id_customer = d.id_customer
						JOIN ms_tipe_kendaraan h on d.id_tipe_kendaraan=h.id_tipe_kendaraan
						where b.status='closed' and left(b.created_njb_at,10) >= '$start_date' and left(b.created_njb_at,10) <= '$end_date'  and b.id_dealer='$id_dealer'
						group by b.id_dealer
						order by b.id_dealer asc");
   				return $ue_segment;
		}

		public function dataSegmentMotor3($id_dealer,$start_date,$end_date){

			   $ue_segment3=$this->db->query("SELECT DISTINCT h.id_series, b.id_dealer
						FROM ms_dealer a 
						JOIN tr_h2_wo_dealer b on a.id_dealer = b.id_dealer
						JOIN tr_h2_sa_form c on c.id_sa_form=b.id_sa_form
						LEFT JOIN ms_customer_h23 d on c.id_customer = d.id_customer
						JOIN ms_tipe_kendaraan h on d.id_tipe_kendaraan=h.id_tipe_kendaraan
						where b.status='closed' and left(b.created_njb_at,10) between '$start_date' and '$end_date' and h.id_series!='' and b.id_dealer='$id_dealer'
						group by h.id_series");
   				return $ue_segment3;
		}

		public function dataSegmentMotor4($id_dealer,$start_date,$end_date){
			   $ue_segment4=$this->db->query("SELECT a.nama_dealer, b.id_dealer,
						SUM(CASE WHEN i.id_kategori='T' then 1 ELSE 0 end) as matic,
						SUM(CASE WHEN i.id_kategori='C' then 1 ELSE 0 end) as cub,
						SUM(CASE WHEN i.id_kategori='S' then 1 ELSE 0 end) as sport
						FROM ms_dealer a 
						JOIN tr_h2_wo_dealer b on a.id_dealer = b.id_dealer
						JOIN tr_h2_sa_form c on c.id_sa_form=b.id_sa_form
						LEFT JOIN ms_customer_h23 d on c.id_customer = d.id_customer
						JOIN ms_tipe_kendaraan h on d.id_tipe_kendaraan=h.id_tipe_kendaraan
						JOIN ms_segment i on h.id_segment = i.id_segment 
						where b.status='closed' and left(b.created_njb_at,10) between '$start_date' and '$end_date' and b.id_dealer='$id_dealer'
						group by b.id_dealer
						order by b.id_dealer asc");
   				return $ue_segment4;
		}
	
		public function downloadReportTransaksional($id_dealer,$start_date,$end_date){

			$filter_dealer = '';
          		if ($id_dealer!='all') {
           			$filter_dealer = " AND b.id_dealer='$id_dealer'";
         		}

			$filter_non_fed ='';
			$filter_non_fed2 ='';
			if(!$this->config->item('ahm_d_only')){
				$filter_non_fed = " and (select mpart.kelompok_part from ms_part mpart where mpart.id_part_int=c.id_part_int) != 'FED OIL'";
				$filter_non_fed2 =" and e.kelompok_part !='FED OIL'";

			}


			$transaksi = $this->db->query("SELECT a.kode_dealer_ahm, a.kode_dealer_md, b.id_work_order, b.created_at, b.start_at, h.harga as harga_jasa,  (CASE WHEN c.subtotal IS null then 0 else c.subtotal end) as harga_part, (ifnull(h.subtotal,0) + ifnull(c.subtotal,0)) as grand_total, (CASE WHEN e.nama_part is null then '' else e.nama_part end) as nama_part, (CASE WHEN c.id_part is null then '' else c.id_part end) as id_part, d.deskripsi, upper(replace((case when i.no_mesin is not null then i.no_mesin else g.no_mesin end),' ','')) as no_mesin, b.no_njb, b.created_njb_at, g.no_polisi, g.id_tipe_kendaraan, f.no_claim_c2, b.tipe_pembayaran, f.alasan_ke_ahass, j.name , k.keterangan, 
			upper((case when i.no_rangka is not null then i.no_rangka else g.no_rangka end)) as no_rangka,
			(case when b.start_at is not null and b.status = 'open' then 'start' else b.status end) as status, h.diskon_value as diskon_jasa, (CASE WHEN c.diskon_value is null then 0 else c.diskon_value end) as diskon_part, l.id_flp_md as id_mekanik, l.nama_lengkap as nama_mekanik, m.id_flp_md as id_sa, m.nama_lengkap as nama_sa, left(timediff(right(b.closed_at ,8),right(b.start_at ,8)),8) as durasi_pekerjaan, ps.nama_promo as promo_jasa, pd.nama as promo_part, d.id_type as tipe_jasa
			-- (case when pos.id_dealer is null then '-' else pos.nama_pos end) as pos		 	
							FROM tr_h2_wo_dealer AS b
							JOIN ms_dealer AS a ON a.id_dealer = b.id_dealer
							-- left join ms_pos_dealer pos ON pos.id_dealer=b.id_dealer 
							JOIN tr_h2_wo_dealer_pekerjaan AS h ON b.id_work_order = h.id_work_order 
							JOIN ms_h2_jasa AS d ON d.id_jasa  = h.id_jasa 
							LEFT JOIN tr_h2_wo_dealer_parts AS c ON h.id_work_order = c.id_work_order and h.id_jasa = c.id_jasa  and (select sop.return from tr_h3_dealer_sales_order_parts sop where sop.id_part_int=c.id_part_int and sop.nomor_so=c.nomor_so) = 0 $filter_non_fed
							LEFT JOIN ms_part AS e ON e.id_part_int  = c.id_part_int $filter_non_fed2
							JOIN tr_h2_sa_form AS f ON f.id_sa_form = b.id_sa_form 
							JOIN ms_customer_h23 AS g ON g.id_customer = f.id_customer
							LEFT JOIN tr_sales_order AS i ON i.no_mesin = g.no_mesin	
							JOIN dms_ms_activity_promotion j on j.id = f.activity_promotion_id 
							JOIN dms_ms_activity_capacity k on k.id = f.activity_capacity_id
							JOIN ms_karyawan_dealer l on b.id_karyawan_dealer = l.id_karyawan_dealer 
							JOIN ms_user n on n.id_user = b.created_by 		
							JOIN ms_karyawan_dealer m on n.id_karyawan_dealer = m.id_karyawan_dealer 
							LEFT JOIN ms_promo_servis ps on ps.id_promo=h.id_promo 
							LEFT JOIN ms_h3_promo_dealer pd on pd.id_promo=c.id_promo 
							WHERE b.created_njb_at>='$start_date 00:00:00' and left(b.created_njb_at,10)<='$end_date 23:59:59'  and b.status in ('closed','cancel','canceled') and h.pekerjaan_batal=0 $filter_dealer order by a.nama_dealer asc, b.closed_at asc, d.deskripsi asc");

			return $transaksi;
		} 
		
		public function get_peformance_fd_sa($id_dealer,$start_date,$end_date){
			$get_data = $this->db->query("
				select a.id_work_order , a.no_njb , d.nama_lengkap as nama_sa ,  c.no_nsc , a.total_jasa , a.total_part , a.created_at as create_wo , a.closed_at as close_wo, 
				a.created_njb_at , h.nama_lengkap as nama_njb , 
				(case when a.cetak_njb_at is null then a.cetak_gab_at else a.cetak_njb_at end) as cetak_njb, 
				c.created_at as create_nsc , f.nama_lengkap as nama_nsc,
				(case when c.cetak_nsc_at is null then a.cetak_gab_at else c.cetak_nsc_at end) as cetak_nsc
				from tr_h2_wo_dealer a
				join ms_user b on b.id_user = a.created_by
				join ms_karyawan_dealer d on d.id_karyawan_dealer = b.id_karyawan_dealer 
				left join tr_h23_nsc c on c.id_referensi  = a.id_work_order and c.referensi ='work_order'
				left join ms_user e on e.id_user =  (case when c.cetak_nsc_by is null then a.cetak_gab_by else c.cetak_nsc_by end)
				left join ms_karyawan_dealer f on f.id_karyawan_dealer = e.id_karyawan_dealer 
				left join ms_user g on g.id_user =  (case when c.cetak_nsc_by is null then a.cetak_gab_by else c.cetak_nsc_by end)
				left join ms_karyawan_dealer h on h.id_karyawan_dealer = g.id_karyawan_dealer 
				where a.closed_at >'$start_date' and a.closed_at <'$end_date' and a.id_dealer = $id_dealer
			");

			return $get_data;
		}

		public function report_picking_slip($id_dealer,$start_date,$end_date){
			$report = $this->db->query("SELECT md.nama_dealer, ps.nomor_ps, ps.nomor_so, DATE_FORMAT(ps.tanggal_ps, '%d-%m-%Y') as tanggal_ps, 
			(CASE WHEN ps.sudah_cetak = 0 then 'Belum Cetak PS' else 'Sudah Cetak PS' end) as sudah_cetak, ps.status as status_ps, 
			so.nama_pembeli, so.no_hp_pembeli, so.id_work_order, (CASE WHEN so.id_work_order is not null then 'WO' else 'Direct Sales' end) as referensi, 
			so.status as status_so, DATE_FORMAT(so.tanggal_so, '%d-%m-%Y') as  tanggal_so
			FROM tr_h3_dealer_picking_slip ps 
			join tr_h3_dealer_sales_order so on so.id=ps.nomor_so_int 
			join ms_customer_h23 c on c.id_customer_int = so.id_customer_int 
			join ms_dealer md on md.id_dealer=ps.id_dealer 
			where ps.id_dealer=$id_dealer AND ps.created_at>='$start_date 00:00:00' and ps.created_at<='$end_date 23:59:59' 
			order by ps.created_at ASC");

			return $report;
		}

		public function report_wo_gantung($id_dealer,$start_date,$end_date){
			$report = $this->db->query("SELECT 
			wo.id_work_order, sa.id_sa_form, DATE_FORMAT(sa.tgl_servis,'%d-%m-%Y') as tgl_servis, 
				sa.jenis_customer, mch.no_polisi, mch.nama_customer,  mch.no_mesin, mch.no_rangka, mch.tahun_produksi, wo.status as status_wo,
				(SELECT stats FROM tr_h2_wo_dealer_waktu WHERE id_work_order=wo.id_work_order ORDER BY set_at DESC LIMIT 1) AS last_stats ,tipe_ahm, 
				jasa.deskripsi as nama_jasa, jasa.id_type, wops.id_part, wops.qty, mp.nama_part,DATE_FORMAT(wo.created_at,'%d-%m-%Y') as tgl_wo
				from tr_h2_wo_dealer wo
				join tr_h2_sa_form sa on wo.id_sa_form=sa.id_sa_form 
				join ms_customer_h23 mch on mch.id_customer=sa.id_customer 
				JOIN tr_h2_wo_dealer_pekerjaan wop on wop.id_work_order=wo.id_work_order 
				JOIN ms_h2_jasa jasa on jasa.id_jasa=wop.id_jasa 
				LEFT JOIN tr_h2_wo_dealer_parts wops on wops.id_work_order=wo.id_work_order 
				LEFT JOIN ms_part mp on mp.id_part_int=wops.id_part_int 
				LEFT JOIN ms_tipe_kendaraan AS tk ON mch.id_tipe_kendaraan=tk.id_tipe_kendaraan
				where wo.id_dealer = $id_dealer and wo.id_work_order IS NOT NULL  and wo.status not in ('Closed','Canceled','Cancel')
				ORDER BY wo.created_at DESC");

			return $report;
		}
    }
?>