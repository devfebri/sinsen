<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class H1_md_report_sla_fincoy_model extends CI_Model{

		public function get_disburst($segment,$series,$tipe,$kecamatan,$fincoy,$dp,$tanggal_akhir,$tanggal_awal,$dealer){

			$get_date_m1="";
			$get_date_mtd="";
			$where ="";
			if (!empty($segment))	          	   { $where.= " AND tk.id_kategori = '$segment' "; }
			if (!empty($tipe))	          	   	   { $where.= " AND tk.id_tipe_kendaraan = '$tipe' "; }
			if (!empty($series))	          	   { $where.= " AND os.id_tipe_kendaraan = '$series' "; }
			if (!empty($dealer))                   { $where.= " AND os.id_dealer = '$dealer' ";}
			if (!empty($fincoy))	          	   { $where.= " AND os.id_finance_company ='$fincoy'"; }
			if (!empty($kecamatan))	          	   { $where.= " AND os.id_kecamatan ='$kecamatan'"; }

			if (!empty($tanggal_akhir)){ 
				$tanggalSatuBulanLaluAwal = date('Y-m-d', strtotime('-1 month', strtotime($tanggal_akhir)));
				$tanggalSatuBulanLaluAkhir = date('Y-m-d', strtotime('-1 month', strtotime($tanggal_awal)));
				$tanggalSatuBulanIniAwal  = date($tanggal_akhir);
				$tanggalSatuBulanIniAkhir = date($tanggal_awal);
				$get_date_m1  .= "BETWEEN '$tanggalSatuBulanLaluAkhir'  and '$tanggalSatuBulanLaluAwal'";
				$get_date_mtd .= "BETWEEN '$tanggalSatuBulanIniAkhir'   and '$tanggalSatuBulanIniAwal'";	
			}


			$query=$this->db->query("SELECT
			sub_query_m1.*,
			sub_query_m.* 
			from ms_finance_company  fc
			 join 
			(SELECT	
			spk.id_finance_company as fincoy,
							CONCAT(
								FLOOR(AVG(TIME_TO_SEC(TIMEDIFF(so.tgl_bastk, DATE_FORMAT(STR_TO_DATE(fifd.po_date, '%d/%m/%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s')))) / (60 * 60 * 24)), ' days ',
								FLOOR((AVG(TIME_TO_SEC(TIMEDIFF(so.tgl_bastk, DATE_FORMAT(STR_TO_DATE(fifd.po_date, '%d/%m/%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s')))) % (60 * 60 * 24)) / (60 * 60)), ' hours ',
								FLOOR((AVG(TIME_TO_SEC(TIMEDIFF(so.tgl_bastk, DATE_FORMAT(STR_TO_DATE(fifd.po_date, '%d/%m/%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s')))) % (60 * 60)) / 60), ' minutes'
							) AS delivery_to_disburst_m1,
							AVG(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(fifd.inv_paid_date, '%d/%m/%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'), DATE_FORMAT(STR_TO_DATE(fifd.po_date, '%d/%m/%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s')))) AS avg_time_difference_delivery_to_disburst_m1,
							DATE_FORMAT(STR_TO_DATE(fifd.po_date, '%d/%m/%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') AS converted_po_m1, 
							DATE_FORMAT(STR_TO_DATE(fifd.inv_paid_date, '%d/%m/%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') AS converted_paid_m1
			-- 				fif.no_spk, fifd.po_date,fifd.inv_paid_date  
							from tr_spk spk join tr_sales_order so on spk.no_spk = so.no_spk 
							join tr_fif_order fif on fif.no_spk = so.no_spk 
							join tr_fif_order_json_detail fifd on fifd.order_uuid  =fif.order_uuid 
							WHERE fifd.inv_paid_status = 'PAID'  and fifd.po_cancel_reason is null $where 
							and DATE_FORMAT(STR_TO_DATE(fifd.po_date, '%d/%m/%Y %H:%i:%s'), '%Y-%m-%d') BETWEEN  $get_date_m1
							)as sub_query_m1 on sub_query_m1.fincoy = fc.id_finance_company
			left join 
			(SELECT	
			spk.id_finance_company as fincoy,
							CONCAT(
								FLOOR(AVG(TIME_TO_SEC(TIMEDIFF(so.tgl_bastk, DATE_FORMAT(STR_TO_DATE(fifd.po_date, '%d/%m/%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s')))) / (60 * 60 * 24)), ' days ',
								FLOOR((AVG(TIME_TO_SEC(TIMEDIFF(so.tgl_bastk, DATE_FORMAT(STR_TO_DATE(fifd.po_date, '%d/%m/%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s')))) % (60 * 60 * 24)) / (60 * 60)), ' hours ',
								FLOOR((AVG(TIME_TO_SEC(TIMEDIFF(so.tgl_bastk, DATE_FORMAT(STR_TO_DATE(fifd.po_date, '%d/%m/%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s')))) % (60 * 60)) / 60), ' minutes'
							) AS delivery_to_disburst_m,
							AVG(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(fifd.inv_paid_date, '%d/%m/%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s'), DATE_FORMAT(STR_TO_DATE(fifd.po_date, '%d/%m/%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s')))) AS avg_time_difference_delivery_to_disburst_m,
							DATE_FORMAT(STR_TO_DATE(fifd.po_date, '%d/%m/%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') AS converted_po_m, 
							DATE_FORMAT(STR_TO_DATE(fifd.inv_paid_date, '%d/%m/%Y %H:%i:%s'), '%Y-%m-%d %H:%i:%s') AS converted_paid_m
			-- 				fif.no_spk, fifd.po_date,fifd.inv_paid_date  
							from tr_spk spk join tr_sales_order so on spk.no_spk = so.no_spk 
							join tr_fif_order fif on fif.no_spk = so.no_spk 
							join tr_fif_order_json_detail fifd on fifd.order_uuid  =fif.order_uuid 
							WHERE fifd.inv_paid_status = 'PAID'  and fifd.po_cancel_reason is null  $where  
							and DATE_FORMAT(STR_TO_DATE(fifd.po_date, '%d/%m/%Y %H:%i:%s'), '%Y-%m-%d') $get_date_mtd
							)as sub_query_m on sub_query_m.fincoy = fc.id_finance_companys
			WHERE fc.id_finance_company ='FC00000003'
				")->result();

				return $query ;
		}


		public function get_lead_month($segment,$series,$tipe,$kecamatan,$fincoy,$dp,$tanggal_akhir,$tanggal_awal,$dealer)
		{
			$get_date_m1="";
			$get_date_mtd="";

			$where ="";
			if (!empty($segment))	          	   { $where.= " AND tk.id_kategori = '$segment' "; }
			if (!empty($tipe))	          	   	   { $where.= " AND tk.id_tipe_kendaraan = '$tipe' "; }
			if (!empty($series))	          	   { $where.= " AND os.id_tipe_kendaraan = '$series' "; }
			if (!empty($dealer))                   { $where.= " AND os.id_dealer = '$dealer' ";}
			if (!empty($fincoy))	          	   { $where.= " AND os.id_finance_company ='$fincoy'"; }
			if (!empty($kecamatan))	          	   { $where.= " AND os.id_kecamatan ='$kecamatan'"; }

			if (!empty($tanggal_akhir)){ 
				$tanggalSatuBulanLaluAwal = date('Y-m-d', strtotime('-1 month', strtotime($tanggal_akhir)));
				$tanggalSatuBulanLaluAkhir = date('Y-m-d', strtotime('-1 month', strtotime($tanggal_awal)));
				$tanggalSatuBulanIniAwal  = date($tanggal_akhir);
				$tanggalSatuBulanIniAkhir = date($tanggal_awal);
				$get_date_m1  .= "AND spk.tgl_spk BETWEEN '$tanggalSatuBulanLaluAkhir'  and '$tanggalSatuBulanLaluAwal'";
				$get_date_mtd .= "AND spk.tgl_spk BETWEEN '$tanggalSatuBulanIniAkhir'   and '$tanggalSatuBulanIniAwal'";	
			}

			$date_check = strtotime($tanggalSatuBulanIniAwal);
			$update_created_at_check = strtotime('2023-06-01');

	

			if ($date_check > $update_created_at_check) {
				
				$hasil = $this->lead_after_hasil_create_at_update($segment,$series,$tipe,$kecamatan,$fincoy,$dp,$tanggal_akhir,$tanggal_awal,$dealer);
			} else {

		
				$hasil = $this->lead_before_hasil_create_at_update($segment,$series,$tipe,$kecamatan,$fincoy,$dp,$tanggal_akhir,$tanggal_awal,$dealer);
			}


			return $hasil;
		}


		function lead_before_hasil_create_at_update ($segment,$series,$tipe,$kecamatan,$fincoy,$dp,$tanggal_akhir,$tanggal_awal,$dealer) 
		{
			$where ="WHERE 1=1";
			$get_date_m1="";
			$get_date_mtd="";
			$fincoy_array ="";
	
			if (!empty($segment))	          	   { $where.= " AND tk.id_kategori = '$segment' "; }
			if (!empty($tipe))	          	   	   { $where.= " AND tk.id_tipe_kendaraan = '$tipe' "; }
			if (!empty($series))	          	   { $where.= " AND os.id_tipe_kendaraan = '$series' "; }
			if (!empty($dealer))                   { $where.= " AND b.id_dealer = '$dealer' ";}
			if (!empty($kecamatan))	          	   { $where.= " AND os.id_kecamatan ='$kecamatan'"; }


			if (!empty($fincoy))	          	   { 
				foreach ($fincoy as  $key =>  $element ) {
					if( $key == 0 ){$komma = NULL;}else {$komma =",";}
					$fincoy_array .= $komma."'".$element."'";
				  }
				$where.= " AND spk.id_finance_company  in ($fincoy_array)"; 
			}
			
				if (!empty($dp))	
				{ 
					if ($dp == '10'){
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 0 AND ((os.uang_muka/os.total_bayar)*100) < 10";
	
					}else if($dp == '1015') {
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 10 AND ((os.uang_muka/os.total_bayar)*100) < 15";
	
					}else if($dp == '1520') {
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 15 AND ((os.uang_muka/os.total_bayar)*100) < 20";
	
					}else if($dp == '20') {
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 20";
					}else{
					}
				}

				if (!empty($tanggal_akhir)){ 
					$dt = strtotime($tanggal_akhir);
					$tanggal_set_mtd=date("Y-m-d", strtotime("+1 month", $dt));
					$tanggal_set_min1=date("Y-m-d", strtotime("-1 month", $dt));
					$get_date_m1  .= "AND a.tgl_spk  BETWEEN '$tanggal_set_min1' and '$tanggal_akhir'";
					$get_date_mtd .= "AND a.tgl_spk  BETWEEN '$tanggal_akhir'    and '$tanggal_set_mtd'";

					$date_check = strtotime($tanggal_set_min1);
					$update_created_at_check = strtotime('2023-06-01');
		

				}else{
					$get_date_m1  .= "AND a.tgl_spk  BETWEEN '2022-04-01' and '2022-05-01'";
					$get_date_mtd .= "AND a.tgl_spk  BETWEEN '2022-05-01' and '2022-06-01'";
				}

				var_dump($where);
				die();



			$query=$this->db->query("SELECT
			fincoy.finance_company,
			fincoy_m1.id_finance_company,
			fincoy_m1.count_spk,
			fincoy_m1.order_to_survey_m1,
			fincoy_m1.avg_time_difference_order_to_survey_m1,
			fincoy_m1.po_to_delivery_m1,
			fincoy_m1.avg_time_difference_po_to_delivery_m1,
			-- fincoy_m.delivery_to_disburst_m1,
			-- fincoy_m.avg_time_difference_delivery_to_disburst_m1,
			fincoy_m.id_finance_company,
			fincoy_m.order_to_survey_m,
			fincoy_m.count_spk,
			fincoy_m.order_to_survey_m,
			fincoy_m.avg_time_difference_order_to_survey_m,
			fincoy_m.po_to_delivery_m,
			fincoy_m.avg_time_difference_po_to_delivery_m,
			fincoy_m.delivery_to_disburst_m,
			fincoy_m.avg_time_difference_delivery_to_disburst_m
		FROM
			ms_finance_company fincoy
			LEFT JOIN (
				SELECT
					sub.id_finance_company,
					COUNT(*) AS count_spk,
					CONCAT(
						FLOOR(AVG(TIME_TO_SEC(TIMEDIFF(sub.tgl_hasil_survey, sub.tgl_order_survey))) / (60 * 60 * 24)), ' days ',
						FLOOR((AVG(TIME_TO_SEC(TIMEDIFF(sub.tgl_hasil_survey, sub.tgl_order_survey))) % (60 * 60 * 24)) / (60 * 60)), ' hours ',
						FLOOR((AVG(TIME_TO_SEC(TIMEDIFF(sub.tgl_hasil_survey, sub.tgl_order_survey))) % (60 * 60)) / 60), ' minutes'
					) AS order_to_survey_m1,
					AVG(TIME_TO_SEC(TIMEDIFF(sub.tgl_hasil_survey, sub.tgl_order_survey))) AS avg_time_difference_order_to_survey_m1,
					CONCAT(
						FLOOR(AVG(TIME_TO_SEC(TIMEDIFF(sub.bastk_created, sub.po_created))) / (60 * 60 * 24)), ' days ',
						FLOOR((AVG(TIME_TO_SEC(TIMEDIFF(sub.bastk_created, sub.po_created))) % (60 * 60 * 24)) / (60 * 60)), ' hours ',
						FLOOR((AVG(TIME_TO_SEC(TIMEDIFF(sub.bastk_created, sub.po_created))) % (60 * 60)) / 60), ' minutes'
					) AS po_to_delivery_m1,
					AVG(TIME_TO_SEC(TIMEDIFF(sub.bastk_created, sub.po_created))) AS avg_time_difference_po_to_delivery_m1,

					CONCAT(
						FLOOR(AVG(TIME_TO_SEC(TIMEDIFF(sub.invoice_created, sub.bastk_created))) / (60 * 60 * 24)), ' days ',
						FLOOR((AVG(TIME_TO_SEC(TIMEDIFF(sub.invoice_created, sub.bastk_created))) % (60 * 60 * 24)) / (60 * 60)), ' hours ',
						FLOOR((AVG(TIME_TO_SEC(TIMEDIFF(sub.invoice_created, sub.bastk_created))) % (60 * 60)) / 60), ' minutes'
					) AS delivery_to_disburst_m1,
					AVG(TIME_TO_SEC(TIMEDIFF(sub.bastk_created, sub.po_created))) AS avg_time_difference_delivery_to_disburst_m1
				FROM (
					SELECT
						a.id_finance_company,
						c.no_spk,
						b.no_order_survey,
						b.created_at AS tgl_order_survey,
						CASE
							WHEN RIGHT(c.created_at, 8) BETWEEN '07:00:00' AND '12:59:59' THEN c.created_at
							ELSE ADDTIME(c.created_at, '12:00:00')
						END AS tgl_hasil_survey,
						po_sub.po_created,
						po_sub.bastk_created,
						po_sub.invoice_created
					FROM
					tr_spk a
						JOIN tr_order_survey b ON a.no_spk = b.no_spk
						JOIN tr_hasil_survey c ON c.no_order_survey = b.no_order_survey
						left join tr_sales_order e on e.no_spk = a.no_spk
						left join (
						select 
						so.tgl_bastk as bastk_created,
						po.created_at as po_created,
						so.no_spk,
						so.tgl_cetak_invoice2 as invoice_created
						from tr_entry_po_leasing po join tr_sales_order so 
						on so.no_po_leasing = po.po_dari_finco AND so.no_spk=po.no_spk
						)as po_sub on po_sub.no_spk = a.no_spk
						$where
						$get_date_m1 
						AND b.status_survey = 'approved'
						AND a.jenis_beli = 'kredit'
					GROUP BY
						c.created_at, a.id_finance_company
					ORDER BY
						c.created_at DESC
				) sub
				GROUP BY
					sub.id_finance_company
			) AS fincoy_m1 ON fincoy_m1.id_finance_company = fincoy.id_finance_company

			
			LEFT JOIN (
				SELECT
					sub.id_finance_company,
					COUNT(*) AS count_spk,
					CONCAT(
						FLOOR(AVG(TIME_TO_SEC(TIMEDIFF(sub.tgl_hasil_survey, sub.tgl_order_survey))) / (60 * 60 * 24)), ' days ',
						FLOOR((AVG(TIME_TO_SEC(TIMEDIFF(sub.tgl_hasil_survey, sub.tgl_order_survey))) % (60 * 60 * 24)) / (60 * 60)), ' hours ',
						FLOOR((AVG(TIME_TO_SEC(TIMEDIFF(sub.tgl_hasil_survey, sub.tgl_order_survey))) % (60 * 60)) / 60), ' minutes'
					) AS order_to_survey_m,
					AVG(TIME_TO_SEC(TIMEDIFF(sub.tgl_hasil_survey, sub.tgl_order_survey))) AS avg_time_difference_order_to_survey_m,
					CONCAT(
						FLOOR(AVG(TIME_TO_SEC(TIMEDIFF(sub.bastk_created, sub.po_created))) / (60 * 60 * 24)), ' days ',
						FLOOR((AVG(TIME_TO_SEC(TIMEDIFF(sub.bastk_created, sub.po_created))) % (60 * 60 * 24)) / (60 * 60)), ' hours ',
						FLOOR((AVG(TIME_TO_SEC(TIMEDIFF(sub.bastk_created, sub.po_created))) % (60 * 60)) / 60), ' minutes'
					) AS po_to_delivery_m,
					AVG(TIME_TO_SEC(TIMEDIFF(sub.bastk_created, sub.po_created))) AS avg_time_difference_po_to_delivery_m,
					CONCAT(
						FLOOR(AVG(TIME_TO_SEC(TIMEDIFF(sub.invoice_created, sub.bastk_created))) / (60 * 60 * 24)), ' days ',
						FLOOR((AVG(TIME_TO_SEC(TIMEDIFF(sub.invoice_created, sub.bastk_created))) % (60 * 60 * 24)) / (60 * 60)), ' hours ',
						FLOOR((AVG(TIME_TO_SEC(TIMEDIFF(sub.invoice_created, sub.bastk_created))) % (60 * 60)) / 60), ' minutes'
					) AS delivery_to_disburst_m,
					AVG(TIME_TO_SEC(TIMEDIFF(sub.bastk_created, sub.po_created))) AS avg_time_difference_delivery_to_disburst_m
				FROM (
					SELECT
						a.id_finance_company,
						c.no_spk,
						b.no_order_survey,
						b.created_at AS tgl_order_survey,
						CASE
							WHEN RIGHT(c.created_at, 8) BETWEEN '07:00:00' AND '12:59:59' THEN c.created_at
							ELSE ADDTIME(c.created_at, '12:00:00')
						END AS tgl_hasil_survey,
						po_sub.po_created,
						po_sub.bastk_created,
						po_sub.invoice_created
					FROM
						tr_spk a
						JOIN tr_order_survey b ON a.no_spk = b.no_spk
						JOIN tr_hasil_survey c ON c.no_order_survey = b.no_order_survey
						left join tr_sales_order e on e.no_spk = a.no_spk
						left join (
						select 
						so.tgl_bastk as bastk_created,
						po.created_at as po_created,
						so.no_spk ,
						so.tgl_cetak_invoice2 as invoice_created
						from tr_entry_po_leasing po join tr_sales_order so 
						on so.no_po_leasing = po.po_dari_finco AND so.no_spk=po.no_spk
						)as po_sub on po_sub.no_spk = a.no_spk
						$where
						$get_date_m1 
						AND b.status_survey = 'approved'
						AND a.jenis_beli = 'kredit'
					GROUP BY
						c.created_at, a.id_finance_company
					ORDER BY
						c.created_at DESC
				) sub
				GROUP BY
					sub.id_finance_company
			) AS fincoy_m ON fincoy_m.id_finance_company = fincoy.id_finance_company

			where fincoy.active ='1' 
			ORDER  by fincoy.finance_company ASC
			   ")->result();
			   return $query;
		}

		
		function lead_after_hasil_create_at_update ($segment,$series,$tipe,$kecamatan,$fincoy,$dp,$tanggal_akhir,$tanggal_awal,$dealer) 
		{
			$where ="WHERE 1=1";
			$get_date_m1="";
			$get_date_mtd="";
			$fincoy_array ="";
	
			if (!empty($segment))	          	   { $where.= " AND tk.id_kategori = '$segment' "; }
			if (!empty($tipe))	          	   	   { $where.= " AND tk.id_tipe_kendaraan = '$tipe' "; }
			if (!empty($series))	          	   { $where.= " AND os.id_tipe_kendaraan = '$series' "; }
			if (!empty($dealer))                   { $where.= " AND b.id_dealer = '$dealer' ";}
			// if (!empty($fincoy))	          	   { $where.= " AND os.id_finance_company ='$fincoy'"; }
			if (!empty($kecamatan))	          	   { $where.= " AND os.id_kecamatan ='$kecamatan'"; }


			if (!empty($fincoy))	          	   { 
				foreach ($fincoy as  $key =>  $element ) {
					if( $key == 0 ){$komma = NULL;}else {$komma =",";}
					$fincoy_array .= $komma."'".$element."'";
				  }
				$where.= " AND spk.id_finance_company  in ($fincoy_array)"; 
			}
			
				if (!empty($dp))	
				{ 
					if ($dp == '10'){
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 0 AND ((os.uang_muka/os.total_bayar)*100) < 10";
	
					}else if($dp == '1015') {
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 10 AND ((os.uang_muka/os.total_bayar)*100) < 15";
	
					}else if($dp == '1520') {
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 15 AND ((os.uang_muka/os.total_bayar)*100) < 20";
	
					}else if($dp == '20') {
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 20";
					}else{
					}
				}
					
				if (!empty($tanggal_akhir)){ 
					$dt = strtotime($tanggal_akhir);
			
					$tanggal_set_mtd=date("Y-m-d", strtotime("+1 month", $dt));
					$tanggal_set_min1=date("Y-m-d", strtotime("-1 month", $dt));
					
					$get_date_m1  .= "AND a.tgl_spk  BETWEEN '$tanggal_set_min1' and '$tanggal_akhir'";
					$get_date_mtd .= "AND a.tgl_spk  BETWEEN '$tanggal_akhir'    and '$tanggal_set_mtd'";
				}else{
					$get_date_m1  .= "AND a.tgl_spk  BETWEEN '2022-04-01' and '2022-05-01'";
					$get_date_mtd .= "AND a.tgl_spk  BETWEEN '2022-05-01' and '2022-06-01'";
				}


			$query=$this->db->query("SELECT 
				fincoy.finance_company,
				fincoy_m1.id_finance_company,
				fincoy_m1.count_spk,
				fincoy_m1.order_to_survey_m1,
				fincoy_m1.avg_time_difference_order_to_survey_m1,
				fincoy_m1.po_to_delivery_m1,
				fincoy_m1.avg_time_difference_po_to_delivery_m1,
				fincoy_m.delivery_to_disburst_m1,
				fincoy_m.avg_time_difference_delivery_to_disburst_m1

				fincoy_m.id_finance_company,
				fincoy_m.order_to_survey_m,
				fincoy_m.count_spk,
				fincoy_m.order_to_survey_m,
				fincoy_m.avg_time_difference_order_to_survey_m,
				fincoy_m.po_to_delivery_m,
				fincoy_m.avg_time_difference_po_to_delivery_m,
				fincoy_m.delivery_to_disburst_m,
				fincoy_m.avg_time_difference_delivery_to_disburst_m
			FROM
				ms_finance_company fincoy
				LEFT JOIN (
					SELECT
						sub.id_finance_company,
						COUNT(*) AS count_spk,
					CONCAT(
						FLOOR(AVG(TIME_TO_SEC(TIMEDIFF(sub.tgl_hasil_survey, sub.tgl_order_survey))) / (60 * 60 * 24)), ' days ',
						FLOOR((AVG(TIME_TO_SEC(TIMEDIFF(sub.tgl_hasil_survey, sub.tgl_order_survey))) % (60 * 60 * 24)) / (60 * 60)), ' hours ',
						FLOOR((AVG(TIME_TO_SEC(TIMEDIFF(sub.tgl_hasil_survey, sub.tgl_order_survey))) % (60 * 60)) / 60), ' minutes'
					) AS order_to_survey_m1,
						AVG(TIME_TO_SEC(TIMEDIFF(sub.tgl_hasil_survey, sub.tgl_order_survey))) AS avg_time_difference_order_to_survey_m1,
						CONCAT(
							FLOOR(AVG(TIME_TO_SEC(TIMEDIFF(sub.bastk_created, sub.po_created))) / (60 * 60 * 24)), ' days ',
							FLOOR((AVG(TIME_TO_SEC(TIMEDIFF(sub.bastk_created, sub.po_created))) % (60 * 60 * 24)) / (60 * 60)), ' hours ',
							FLOOR((AVG(TIME_TO_SEC(TIMEDIFF(sub.bastk_created, sub.po_created))) % (60 * 60)) / 60), ' minutes'
						) AS po_to_delivery_m1,
						AVG(TIME_TO_SEC(TIMEDIFF(sub.bastk_created, sub.po_created))) AS avg_time_difference_po_to_delivery_m1,

						CONCAT(
							FLOOR(AVG(TIME_TO_SEC(TIMEDIFF(sub.invoice_created, sub.bastk_created))) / (60 * 60 * 24)), ' days ',
							FLOOR((AVG(TIME_TO_SEC(TIMEDIFF(sub.invoice_created, sub.bastk_created))) % (60 * 60 * 24)) / (60 * 60)), ' hours ',
							FLOOR((AVG(TIME_TO_SEC(TIMEDIFF(sub.invoice_created, sub.bastk_created))) % (60 * 60)) / 60), ' minutes'
						) AS delivery_to_disburst_m1,
						AVG(TIME_TO_SEC(TIMEDIFF(sub.bastk_created, sub.po_created))) AS avg_time_difference_delivery_to_disburst_m1,
					FROM (
						SELECT
							a.id_finance_company,
							c.no_spk,
							b.no_order_survey,
							b.created_at AS tgl_order_survey,
							CASE
								WHEN RIGHT(c.created_at, 8) BETWEEN '07:00:00' AND '12:59:59' THEN c.created_at
								ELSE ADDTIME(c.created_at, '12:00:00')
							END AS tgl_hasil_survey,
							po_sub.po_created,
							po_sub.bastk_created,
							po_sub.invoice_created
						FROM
						tr_spk a
							JOIN tr_order_survey b ON a.no_spk = b.no_spk
							JOIN tr_hasil_survey c ON c.no_order_survey = b.no_order_survey
							left join tr_sales_order e on e.no_spk = a.no_spk
							left join (
							select 
							so.tgl_bastk as bastk_created,
							po.created_at as po_created,
							so.no_spk,
							so.tgl_cetak_invoice2 as invoice_created
							from tr_entry_po_leasing po join tr_sales_order so 
							on so.no_po_leasing = po.po_dari_finco AND so.no_spk=po.no_spk
							)as po_sub on po_sub.no_spk = a.no_spk
							$where
							$get_date_m1 
							AND b.status_survey = 'approved'
							AND a.jenis_beli = 'kredit'
						GROUP BY
							c.created_at, a.id_finance_company
						ORDER BY
							c.created_at DESC
					) sub
					GROUP BY
						sub.id_finance_company
				) AS fincoy_m1 ON fincoy_m1.id_finance_company = fincoy.id_finance_company

				
				LEFT JOIN (
					SELECT
						sub.id_finance_company,
						COUNT(*) AS count_spk,
						CONCAT(
							FLOOR(AVG(TIME_TO_SEC(TIMEDIFF(sub.tgl_hasil_survey, sub.tgl_order_survey))) / (60 * 60 * 24)), ' days ',
							FLOOR((AVG(TIME_TO_SEC(TIMEDIFF(sub.tgl_hasil_survey, sub.tgl_order_survey))) % (60 * 60 * 24)) / (60 * 60)), ' hours ',
							FLOOR((AVG(TIME_TO_SEC(TIMEDIFF(sub.tgl_hasil_survey, sub.tgl_order_survey))) % (60 * 60)) / 60), ' minutes'
						) AS order_to_survey_m,
						AVG(TIME_TO_SEC(TIMEDIFF(sub.tgl_hasil_survey, sub.tgl_order_survey))) AS avg_time_difference_order_to_survey_m,
						CONCAT(
							FLOOR(AVG(TIME_TO_SEC(TIMEDIFF(sub.bastk_created, sub.po_created))) / (60 * 60 * 24)), ' days ',
							FLOOR((AVG(TIME_TO_SEC(TIMEDIFF(sub.bastk_created, sub.po_created))) % (60 * 60 * 24)) / (60 * 60)), ' hours ',
							FLOOR((AVG(TIME_TO_SEC(TIMEDIFF(sub.bastk_created, sub.po_created))) % (60 * 60)) / 60), ' minutes'
						) AS po_to_delivery_m,
						AVG(TIME_TO_SEC(TIMEDIFF(sub.bastk_created, sub.po_created))) AS avg_time_difference_po_to_delivery_m,
						CONCAT(
							FLOOR(AVG(TIME_TO_SEC(TIMEDIFF(sub.invoice_created, sub.bastk_created))) / (60 * 60 * 24)), ' days ',
							FLOOR((AVG(TIME_TO_SEC(TIMEDIFF(sub.invoice_created, sub.bastk_created))) % (60 * 60 * 24)) / (60 * 60)), ' hours ',
							FLOOR((AVG(TIME_TO_SEC(TIMEDIFF(sub.invoice_created, sub.bastk_created))) % (60 * 60)) / 60), ' minutes'
						) AS delivery_to_disburst_m,
						AVG(TIME_TO_SEC(TIMEDIFF(sub.bastk_created, sub.po_created))) AS avg_time_difference_delivery_to_disburst_m
					FROM (
						SELECT
							a.id_finance_company,
							c.no_spk,
							b.no_order_survey,
							b.created_at AS tgl_order_survey,
							CASE
								WHEN RIGHT(c.created_at, 8) BETWEEN '07:00:00' AND '12:59:59' THEN c.created_at
								ELSE ADDTIME(c.created_at, '12:00:00')
							END AS tgl_hasil_survey,
							po_sub.po_created,
							po_sub.bastk_created,
							po_sub.invoice_created
						FROM
							tr_spk a
							JOIN tr_order_survey b ON a.no_spk = b.no_spk
							JOIN tr_hasil_survey c ON c.no_order_survey = b.no_order_survey
							left join tr_sales_order e on e.no_spk = a.no_spk
							left join (
							select 
							so.tgl_bastk as bastk_created,
							po.created_at as po_created,
							so.no_spk ,
							so.tgl_cetak_invoice2 as invoice_created
							from tr_entry_po_leasing po join tr_sales_order so 
							on so.no_po_leasing = po.po_dari_finco AND so.no_spk=po.no_spk
							)as po_sub on po_sub.no_spk = a.no_spk
							$where
							$get_date_m1 
							AND b.status_survey = 'approved'
							AND a.jenis_beli = 'kredit'
						GROUP BY
							c.created_at, a.id_finance_company
						ORDER BY
							c.created_at DESC
					) sub
					GROUP BY
						sub.id_finance_company
				) AS fincoy_m ON fincoy_m.id_finance_company = fincoy.id_finance_company
				where fincoy.active ='1' 
				ORDER  by fincoy.finance_company ASC
			   ")->result();

			   return $query;
		}


		public function get_fincoy_time()
		{
            $query=$this->db->query("(SELECT id_finance_company ,finance_company,CONCAT('m1') as 'Time'
			from ms_finance_company  WHERE active ='1' 
			)UNION
			(SELECT id_finance_company ,finance_company,CONCAT('mtd') as 'Time'
			from ms_finance_company  WHERE active ='1' 
			) ORDER by id_finance_company, time ASC ")->result();
            return $query;
        }
		       
        public function get_pekerjaan()
		{
            $query=$this->db->query("select id_pekerjaan, pekerjaan  from ms_pekerjaan mp WHERE active ='1'")->result();
            return $query;
        }

        public function get_kecamatan()
		{
            $query=$this->db->query("SELECT kec.id_kecamatan, kec.kecamatan  from ms_kecamatan kec join ms_kabupaten kab  on kec.id_kabupaten  = kab.id_kabupaten  WHERE kab.id_provinsi = '1500' order by kecamatan ASC")->result();
            return $query;
        }
        
        public function get_segment()
		{
            $query=$this->db->query("SELECT id_kategori, kategori FROM ms_kategori WHERE active=1 AND id_kategori IN('T','S','C') ORDER BY kategori ASC")->result();
            return $query;
        }
        
        
        public function get_series($id_series)
		{
			$segment = 'WHERE 1=1';
			if ($id_series == 'AT'){
			$segment .=" AND tk.id_segment in ('AH','AL','AM')";
			}else if ($id_series == 'CUB'){
				$segment .=" AND tk.id_segment in ('CH','CL','CM')";
			}else if ($id_series == 'SPORT'){
				$segment .=" AND tk.id_segment in ('SH','SL','SM')";
			}
            $query=$this->db->query("SELECT DISTINCT tk.id_series  from ms_tipe_kendaraan tk left join ms_series ms on ms.id_series = tk.id_series  and tk.id_segment = ms.id_segment $segment  order by tk.id_series asc");
			return $query;
        }

		  
        public function get_data_tipe($series)
		{
            $query=$this->db->query("select id_tipe_kendaraan, tipe_ahm  FROM  ms_tipe_kendaraan WHERE id_series ='$series'");
            return $query;
        }
        
        public function get_fincoy()
		{
            $query=$this->db->query("SELECT id_finance_company ,finance_company  from ms_finance_company  WHERE active ='1' ORDER  by finance_company  asc")->result();
            return $query;
        }

		       
        public function get_fincoy_id()
		{
            $query=$this->db->query("SELECT id_finance_company ,finance_company  from ms_finance_company  WHERE active ='1' ORDER  by id_finance_company  asc")->result();
            return $query;
        }

		
        public function get_credit_funneling($search,$segment,$series,$tipe,$dealer,$fincoy,$kecamatan,$keterangan,$tanggal_awal,$tanggal_akhir,$dp)
		{

			$where ="WHERE 1=1";
			if (!empty($search))	          	   { $where.= " AND os.no_spk ='$search' OR os.nama_konsumen like '%$search%'"; }
			if (!empty($dealer))                   { $where.= " AND os.id_dealer = '$dealer' ";}
			// if (!empty($fincoy))	          	   { $where.= " AND os.id_finance_company ='$fincoy'"; }
			if (!empty($kecamatan))	          	   { $where.= " AND os.id_kecamatan ='$kecamatan'"; }
			if (!empty($segment))	          	   { $where.= " AND tk.id_kategori = '$segment' "; }
			if (!empty($series))	          	   { $where.= " AND tk.id_series = '$series' "; }
			if (!empty($tipe))	          	       { $where.= " AND tk.id_tipe_kendaraan = '$tipe' "; }
			
			$limit = "";
			$having='HAVING 1=1 ';
			$fincoy_array="";
			
			if (!empty($tanggal_awal))	           { $where.= " AND left(spk.created_at,10) BETWEEN '$tanggal_awal' AND  '$tanggal_akhir' "; }

			else{
				$where .= "AND left(os.created_at,10) BETWEEN '2022-05-01' and '2022-06-01'";
			}

			if (!empty($fincoy)){ 
				foreach ($fincoy as  $key =>  $element ) {
					if( $key == 0 ){$komma = NULL;}else {$komma =",";}
					$fincoy_array .= $komma."'".$element."'";
				  }
				$where.= " AND os.id_finance_company  in ($fincoy_array)"; 
			}

			if (!empty($dp))	
			          	   { 
				if ($dp == '10'){
					$having .= " AND jumlah BETWEEN  1 AND 10 ";
				}else if($dp == '1015') {
					$having .=' AND  jumlah BETWEEN  10 AND 15';
				}else if($dp == '1520') {
					$having .=' AND jumlah BETWEEN  15 AND 20';
				}else if($dp == '20') {
					$having .=' AND jumlah BETWEEN  20 AND 100';
				}else{
					$having .='';
				}
			}

			if (!empty($keterangan))	
			    { 
				if ($keterangan == 'on_going'){
					$having .= " AND status_credit ='Going'";
				}else if($keterangan == 'ap_pending') {
					$having .= " AND status_credit ='Pending'";
				}else if($keterangan == 'ap_schedule') {
					$having .=" AND status_credit ='ap_schedule'";
				}else if($keterangan == 'rejected') {
					$having .=" AND status_credit ='Rejected'";
				}else if($keterangan == 'delivered') {
					$having .=" AND status_credit ='delivered'";
				}else if($keterangan == 'invoice') {
					$having .=" AND status_credit ='invoice'";
				}else if($keterangan == 'disbused') {
					$having .=" AND status_credit ='disbused'";
				}
				else{
					$having .='';
				}
			}

			$query=$this->db->query("
			SELECT 
			so.tgl_cetak_invoice,
			os.id_finance_company,os.no_order_survey,so.id_sales_order, os.no_spk,os.nama_konsumen, fc.finance_company , tk.tipe_ahm ,os.created_at as create_order_survey, hs.created_at as create_hasil_survey,so.tgl_pengiriman
			,floor((os.uang_muka/os.total_bayar)*100) as jumlah, os.status_survey, hs.status_approval,so.delivery_document_id,so.no_bastk,
			CASE WHEN os.id_finance_company='FC00000003'   then so.no_invoice ELSE NULL END AS no_invoice,
			CASE WHEN os.id_finance_company='FC00000003'   then so.tgl_cetak_invoice2 ELSE NULL END AS tgl_cetak_invoice2,
			CASE WHEN  os.status_survey IS NOT NULL   then 1 ELSE NULL END AS orders,
			CASE WHEN  os.status_survey = 'approved'  AND hs.status_approval= 'approved'  then 1 ELSE NULL END AS approved,
			CASE WHEN  hs.status_approval = 'rejected'  then 1 ELSE NULL END AS rejected,
			CASE WHEN  os.status_survey = 'baru'      AND hs.status_approval IS NULL      AND so.id_sales_order IS NULL      then 1 ELSE NULL END AS ongoing,
			CASE WHEN  os.status_survey = 'approved'  AND hs.status_approval = 'approved' AND so.id_sales_order IS NOT NULL  AND so.delivery_document_id IS NOT NULL then 1 ELSE NULL END AS delivered,
			CASE WHEN  os.status_survey = 'approved'  AND hs.status_approval = 'approved' AND so.id_sales_order IS NOT NULL  AND so.no_bastk IS NULL   then 1 ELSE NULL END AS scheduled,
			CASE WHEN  os.status_survey = 'approved'  AND hs.status_approval = 'approved' AND so.id_sales_order IS NULL  AND so.delivery_document_id IS NULL  then 1 ELSE NULL END AS pending,
			CASE WHEN  os.status_survey = 'approved'  AND hs.status_approval = 'approved' AND so.id_sales_order IS NOT NULL  AND os.id_finance_company='FC00000003'  and so.no_bastk IS NOT NULL  AND so.tgl_bastk IS NOT NULL  then 1 ELSE NULL END AS invoice_send,
			CASE WHEN  os.status_survey = 'approved'  AND hs.status_approval = 'approved' AND so.id_sales_order IS NOT NULL  AND os.id_finance_company='FC00000003' AND so.no_bastk IS NULL AND so.no_invoice IS NULL then 1 ELSE NULL END AS not_invoice_send,
			CASE WHEN  os.status_survey = 'approved'  AND hs.status_approval = 'approved' AND so.id_sales_order IS NOT NULL  AND os.id_finance_company='FC00000003' AND so.no_bastk IS NOT NULL  then 1 ELSE NULL END AS disbursed,
			CASE WHEN  os.status_survey = 'approved'  AND hs.status_approval = 'approved' AND so.id_sales_order IS NOT NULL  AND os.id_finance_company='FC00000003' AND so.no_bastk IS NULL   then 1 ELSE NULL END AS not_disbursed,
			CASE 
			WHEN os.status_survey = 'baru'  AND hs.status_approval IS NULL AND so.id_sales_order IS NULL THEN 'Going'	
			WHEN os.status_survey = 'cancel' and hs.status_approval IS NULL THEN 'Rejected'	
			WHEN so.id_sales_order IS NULL AND so.delivery_document_id is null    THEN 'Pending'	
			WHEN so.id_sales_order IS NOT NULL AND hs.status_approval = 'approved'  AND so.delivery_document_id is null   THEN 'Aproved'	
			WHEN so.id_sales_order IS NOT NULL AND hs.status_approval = 'approved'  AND os.id_finance_company='FC00000003'  AND so.delivery_document_id is not null AND so.tgl_cetak_invoice is Null  THEN 'Invoice Send'
			WHEN so.id_sales_order IS NOT NULL AND hs.status_approval = 'approved'  AND os.id_finance_company='FC00000003'  AND so.delivery_document_id is not null AND so.tgl_cetak_invoice is not Null  THEN 'Disburst'		
			ELSE '-' END AS status_credit
			FROM tr_order_survey os  
			left join tr_hasil_survey hs on hs.no_order_survey = os.no_order_survey
			left join tr_spk spk on hs.no_spk = spk.no_spk
			left join tr_sales_order so on so.no_spk=os.no_spk and hs.no_order_survey=os.no_order_survey
			left join ms_finance_company fc on fc.id_finance_company = os.id_finance_company 
			left join ms_tipe_kendaraan tk on tk.id_tipe_kendaraan = os.id_tipe_kendaraan
			$where
			AND os.status_survey not in ('rejected','cancel','') 
			$having  order by os.no_spk desc $limit ");
			return $query;
		
		}

      
        public function get_data_cash_vs_credit($segment,$series,$tipe,$kecamatan,$fincoy,$dp,$tanggal_akhir,$tanggal_awal,$dealer)
		{
			$where ="";
			$get_date_m1="";
			$get_date_mtd="";
			$fincoy_array ="";

			$dp_10 = NULL;
			$dp_1015 = NULL;
			$dp_1520 = NULL;
			$dp_20 = NULL;

			if (!empty($fincoy)){ 
				foreach ($fincoy as  $key =>  $element ) {
					if( $key == 0 ){$komma = NULL;}else {$komma =",";}
					$fincoy_array .= $komma."'".$element."'";
				  }
				$where.= " AND spk.id_finance_company  in ($fincoy_array)"; 
			}

			if (!empty($dealer))                   { $where.= " AND spk.id_dealer = '$dealer' ";}
			if (!empty($fincoy))	          	   { $where.= " AND spk.id_finance_company ='$fincoy'"; }
			if (!empty($kecamatan))	          	   { $where.= " AND spk.id_kecamatan ='$kecamatan'"; }
			if (!empty($segment))	          	   { $where.= " AND tk.id_kategori = '$segment' "; }
			if (!empty($series))	          	   { $where.= " AND tk.id_series = '$series' "; }
			if (!empty($tipe))	          	       { $where.= " AND tk.id_tipe_kendaraan = '$tipe' "; }

		
			if (!empty($tanggal_akhir)){ 
				$tanggalSatuBulanLaluAwal = date('Y-m-d', strtotime('-1 month', strtotime($tanggal_akhir)));
				$tanggalSatuBulanLaluAkhir = date('Y-m-d', strtotime('-1 month', strtotime($tanggal_awal)));
				$tanggalSatuBulanIniAwal  = date($tanggal_akhir);
				$tanggalSatuBulanIniAkhir = date($tanggal_awal);
				$get_date_m1  .= "AND tgl_spk BETWEEN '$tanggalSatuBulanLaluAkhir' and '$tanggalSatuBulanLaluAwal'";
				$get_date_mtd .= "AND tgl_spk BETWEEN '$tanggalSatuBulanIniAkhir'   and '$tanggalSatuBulanIniAwal'";
			}else{
			
			}

			if (!empty($dp)){ 
				foreach ($dp as  $key =>  $element ) {
						$dp_10   .= ($element == '10')   ? 1 : NULL;
						$dp_1015 .= ($element == '1015') ? 1 : NULL;
						$dp_1520 .= ($element == '1520') ? 1 : NULL;
						$dp_20   .= ($element == '20')   ? 1 : NULL;
				  }

				  $where = "(";
				  if (!empty($dp_10)) {
					$one = "AND";
					$where .= " $one ((spk.uang_muka/spk.total_bayar)*100) > 0 AND ((spk.uang_muka/spk.total_bayar)*100)) < 10";
				  }
				  if (!empty($dp_1015)) {
					if($one == NULL){ $var_dp="AND";}else{ $var_dp="OR";}
					$where .= " $var_dp ((spk.uang_muka/spk.total_bayar)*100) > 10 AND ((spk.uang_muka/spk.total_bayar)*100)) < 15";
				  }
				  if (!empty($dp_1520)) {
					if($one == NULL){ $var_dp="AND"; }else{ $var_dp="OR";}
					$where .= " $var_dp  ((spk.uang_muka/spk.total_bayar)*100) > 15 AND ((spk.uang_muka/spk.total_bayar)*100)) < 20";
				  }
				  if (!empty($dp_20)) {
					if($one = NULL){ $var_dp="AND"; }else{ $var_dp="OR";}
					$where .= " $var_dp ((spk.uang_muka/spk.total_bayar)*100)) > 20 AND ((spk.uang_muka/spk.total_bayar)*100)) < 100";
				  }
				  $where = ")";
			}


				$query=$this->db->query("
				(
				SELECT CONCAT('M-1') as 'Time',
				(COUNT(CASE WHEN spk.jenis_beli = 'cash'   AND  spk.status_spk not in ('rejected','canceled') $where  $get_date_m1   then 1 ELSE NULL END)) as 'Cash',
				(COUNT(CASE WHEN spk.jenis_beli = 'kredit' AND  spk.status_spk not in ('rejected','canceled') $where  $get_date_m1  then 1 ELSE NULL END)) as 'Credit'
				from tr_spk spk join tr_sales_order so on so.no_spk = spk.no_spk 
				left join ms_tipe_kendaraan tk on tk.id_tipe_kendaraan = spk.id_tipe_kendaraan
				) 
                UNION
                (
				SELECT CONCAT('MTD') as 'Time',
				(COUNT(CASE WHEN spk.jenis_beli = 'cash'   AND spk.status_spk not in ('rejected','canceled') $where $get_date_mtd    then 1 ELSE NULL END)) as 'Cash',
				(COUNT(CASE WHEN spk.jenis_beli = 'kredit' AND spk.status_spk not in ('rejected','canceled') $where $get_date_mtd   then 1 ELSE NULL END)) as 'Credit'
				from tr_spk spk join tr_sales_order so on so.no_spk = spk.no_spk
				left join ms_tipe_kendaraan tk on tk.id_tipe_kendaraan = spk.id_tipe_kendaraan
				)
				 ")->result();
                return $query;


		}

        public function get_data_credit_share($segment,$series,$tipe,$kecamatan,$fincoy,$dp,$tanggal_akhir,$tanggal_awal,$dealer)
		{

			$where ="1=1";
			$get_date_m1="";
			$get_date_mtd="";
			$fincoy_array ="";

			if (!empty($fincoy))	          	   { 
				foreach ($fincoy as  $key =>  $element ) {
					if( $key == 0 ){$komma = NULL;}else {$komma =",";}
					$fincoy_array .= $komma."'".$element."'";
				  }
				$where.= " AND os.id_finance_company  in ($fincoy_array)"; 
			}

			if (!empty($segment))	          	   { $where.= " AND tk.id_kategori = '$segment' "; }
			if (!empty($tipe))	          	   	   { $where.= " AND tk.id_tipe_kendaraan = '$tipe' "; }
			if (!empty($series))	          	   { $where.= " AND os.id_tipe_kendaraan = '$series' "; }
			if (!empty($dealer))                   { $where.= " AND os.id_dealer = '$dealer' ";}
			// if (!empty($fincoy))	          	   { $where.= " AND os.id_finance_company ='$fincoy'"; }
			if (!empty($kecamatan))	          	   { $where.= " AND os.id_kecamatan ='$kecamatan'"; }

			$limit = "";
			$having='HAVING 1=1 ';

			if (!empty($dp))	
			          	   { 
				if ($dp == '10'){
					$having .= " AND jumlah BETWEEN  1 AND 10 ";
					$where  .= "AND ((os.uang_muka/os.total_bayar)*100) > 0 AND ((os.uang_muka/os.total_bayar)*100) < 10";

				}else if($dp == '1015') {
					$having .=' AND  jumlah BETWEEN  10 AND 15';
					$where  .= "AND ((os.uang_muka/os.total_bayar)*100) > 10 AND ((os.uang_muka/os.total_bayar)*100) < 15";

				}else if($dp == '1520') {
					$having .=' AND jumlah BETWEEN  15 AND 20';
					$where  .= "AND ((os.uang_muka/os.total_bayar)*100) > 15 AND ((os.uang_muka/os.total_bayar)*100) < 20";

				}else if($dp == '20') {
					$having .=' AND jumlah BETWEEN  20 AND 100';
					$where  .= "AND ((os.uang_muka/os.total_bayar)*100) > 20";
				}else{
					$having .='';
				}
			}
				
			if (!empty($tanggal_akhir)){ 

				$tanggalSatuBulanLaluAwal = date('Y-m-d', strtotime('-1 month', strtotime($tanggal_akhir)));
				$tanggalSatuBulanLaluAkhir = date('Y-m-d', strtotime('-1 month', strtotime($tanggal_awal)));
				$tanggalSatuBulanIniAwal  = date($tanggal_akhir);
				$tanggalSatuBulanIniAkhir = date($tanggal_awal);

				$get_date_m1  .= "AND tgl_spk BETWEEN '$tanggalSatuBulanLaluAkhir' and '$tanggalSatuBulanLaluAwal'";
				$get_date_mtd .= "AND tgl_spk BETWEEN '$tanggalSatuBulanIniAkhir'   and '$tanggalSatuBulanIniAwal'";
			}

			$query=$this->db->query("SELECT fc.id_finance_company as id_fincoy ,fc.finance_company as fincoy, 
			(COUNT(CASE WHEN  $where  $get_date_m1 then 1 ELSE NULL END)) as 'm1',
			(COUNT(CASE WHEN  $where  $get_date_mtd then 1 ELSE NULL END)) as 'mtd'
			from tr_order_survey os
						join tr_hasil_survey hs on hs.no_order_survey = os.no_order_survey 
						join ms_finance_company fc on fc.id_finance_company = os.id_finance_company
						join ms_tipe_kendaraan tk on tk.id_tipe_kendaraan = os.id_tipe_kendaraan
						join tr_sales_order so on so.no_spk = os.no_spk 
						WHERE  so.no_invoice is not null and os.status_survey ='approved'
						GROUP by fc.finance_company
			")->result();

			return $query;
        }

		public function get_data_down_payment_comparrison($segment,$series,$tipe,$kecamatan,$fincoy,$dp,$tanggal_akhir,$tanggal_awal,$dealer)
		{
			$where ="";
			$get_date_m1="";
			$get_date_mtd="";
			$fincoy_array ="";


			if (!empty($fincoy))	          	   { 
				foreach ($fincoy as  $key =>  $element ) {
					if( $key == 0 ){$komma = NULL;}else {$komma =",";}
					$fincoy_array .= $komma."'".$element."'";
				  }
				$where.= " AND os.id_finance_company  in ($fincoy_array)"; 
			}

			if (!empty($segment))	          	   { $where.= " AND tk.id_kategori = '$segment' "; }
			if (!empty($tipe))	          	   	   { $where.= " AND tk.id_tipe_kendaraan = '$tipe' "; }
			if (!empty($series))	          	   { $where.= " AND os.id_tipe_kendaraan = '$series' "; }
			if (!empty($dealer))                   { $where.= " AND os.id_dealer = '$dealer' ";}
			if (!empty($fincoy))	          	   { $where.= " AND os.id_finance_company ='$fincoy'"; }
			if (!empty($kecamatan))	          	   { $where.= " AND os.id_kecamatan ='$kecamatan'"; }
	
			$limit = "";
			$having='HAVING 1=1 ';
			$dp_having ='';

			if (!empty($dp))	
			          	   { 
				if ($dp == '10'){
					$having .= " AND jumlah BETWEEN  1 AND 10 ";
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 0 AND ((os.uang_muka/os.total_bayar)*100) < 10";

				}else if($dp == '1015') {
					$having .=' AND  jumlah BETWEEN  10 AND 15';
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 10 AND ((os.uang_muka/os.total_bayar)*100) < 15";

				}else if($dp == '1520') {
					$having .=' AND jumlah BETWEEN  15 AND 20';
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 15 AND ((os.uang_muka/os.total_bayar)*100) < 20";

				}else if($dp == '20') {
					$having .=' AND jumlah BETWEEN  20 AND 100';
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 20";

				}else{
					$having .='';
				}
			}
				
			if (!empty($tanggal_akhir)){ 
				$tanggalSatuBulanLaluAwal = date('Y-m-d', strtotime('-1 month', strtotime($tanggal_akhir)));
				$tanggalSatuBulanLaluAkhir = date('Y-m-d', strtotime('-1 month', strtotime($tanggal_awal)));
				$tanggalSatuBulanIniAwal  = date($tanggal_akhir);
				$tanggalSatuBulanIniAkhir = date($tanggal_awal);

				$get_date_m1  .= "AND  left(os.created_at,10) BETWEEN '$tanggalSatuBulanLaluAkhir' and '$tanggalSatuBulanLaluAwal'";
				$get_date_mtd .= "AND  left(os.created_at,10) BETWEEN '$tanggalSatuBulanIniAkhir'   and '$tanggalSatuBulanIniAwal'";
			}else{
				$get_date_m1  .= "AND  left(os.created_at,10) BETWEEN '2022-04-01' and '2022-05-01'";
				$get_date_mtd .= "AND  left(os.created_at,10)  BETWEEN '2022-05-01'    and '2022-06-01'";
			}

			$where_conditional="WHERE os.status_survey ='approved'";
			$where_conditional.="AND so.status_so ='so_invoice'";
			$join_table="join tr_hasil_survey hs on hs.no_order_survey=os.no_order_survey
			join tr_sales_order so on so.no_spk=os.no_spk and hs.no_order_survey=os.no_order_survey";

			 $query=$this->db->query("
			 (SELECT 
			 CONCAT('M1') as 'Time',
			 (COUNT(CASE WHEN ((os.uang_muka/os.total_bayar)*100) > 0   AND ((os.uang_muka/os.total_bayar)*100) < 10  $where $get_date_m1   AND os.status_survey ='approved' then 1 ELSE NULL END)) as 'p1010',
			 (COUNT(CASE WHEN ((os.uang_muka/os.total_bayar)*100) > 10  AND ((os.uang_muka/os.total_bayar)*100) < 15  $where $get_date_m1   AND os.status_survey ='approved'  then 1 ELSE NULL END)) as 'p1015',
			 (COUNT(CASE WHEN ((os.uang_muka/os.total_bayar)*100) > 15  AND ((os.uang_muka/os.total_bayar)*100) < 20  $where $get_date_m1   AND os.status_survey ='approved'  then 1 ELSE NULL END)) as 'p1520',
			 (COUNT(CASE WHEN ((os.uang_muka/os.total_bayar)*100) > 20   $where $get_date_m1  then 1 ELSE NULL END)) as 'p2020'
			 FROM tr_order_survey os 
			 LEFT join ms_tipe_kendaraan tk on tk.id_tipe_kendaraan = os.id_tipe_kendaraan
			 WHERE os.status_survey ='approved')
			 UNION
			 (
				SELECT 
				CONCAT('MTD') as 'Time',
				(COUNT(CASE WHEN ((os.uang_muka/os.total_bayar)*100) > 0  AND ((os.uang_muka/os.total_bayar)*100) < 10   $where $get_date_mtd   AND os.status_survey ='approved' then 1 ELSE NULL END)) as 'p1010',
				(COUNT(CASE WHEN ((os.uang_muka/os.total_bayar)*100) > 10  AND ((os.uang_muka/os.total_bayar)*100) < 15  $where $get_date_mtd   AND os.status_survey ='approved' then 1 ELSE NULL END)) as 'p1015',
				(COUNT(CASE WHEN ((os.uang_muka/os.total_bayar)*100) > 15  AND ((os.uang_muka/os.total_bayar)*100) < 20  $where $get_date_mtd   AND os.status_survey ='approved' then 1 ELSE NULL END)) as 'p1520',
				(COUNT(CASE WHEN ((os.uang_muka/os.total_bayar)*100) > 20   $where $get_date_mtd  then 1 ELSE NULL END)) as 'p2020'
				FROM tr_order_survey os 
				LEFT join ms_tipe_kendaraan tk on tk.id_tipe_kendaraan = os.id_tipe_kendaraan
				WHERE os.status_survey ='approved'
			 )
				");
			
			return $query;
        }

        
        public function get_data_reject_by_oc($segment,$series,$tipe,$kecamatan,$fincoy,$dp,$tanggal_akhir,$tanggal_awal,$dealer)
		{

			$where ="WHERE 1=1";
			$get_date_m1="";
			$get_date_mtd="";
			$fincoy_array ="";


			if (!empty($fincoy))	          	   { 
				foreach ($fincoy as  $key =>  $element ) {
					if( $key == 0 ){$komma = NULL;}else {$komma =",";}
					$fincoy_array .= $komma."'".$element."'";
				  }
				$where.= " AND os.id_finance_company  in ($fincoy_array)"; 
			}

			if (!empty($segment))	          	   { $where.= " AND tk.id_kategori = '$segment' "; }
			if (!empty($tipe))	          	   	   { $where.= " AND tk.id_tipe_kendaraan = '$tipe' "; }
			if (!empty($series))	          	   { $where.= " AND os.id_tipe_kendaraan = '$series' "; }
			if (!empty($dealer))                   { $where.= " AND os.id_dealer = '$dealer' ";}
			if (!empty($fincoy))	          	   { $where.= " AND os.id_finance_company ='$fincoy'"; }
			if (!empty($kecamatan))	          	   { $where.= " AND os.id_kecamatan ='$kecamatan'"; }
		
			
			$having='HAVING 1=1 ';

			if (!empty($dp))	
			          	   { 
				if ($dp == '10'){
					$having .= " AND jumlah BETWEEN  1 AND 10 ";
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 0 AND ((os.uang_muka/os.total_bayar)*100) < 10";

				}else if($dp == '1015') {
					$having .=' AND  jumlah BETWEEN  10 AND 15';
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 10 AND ((os.uang_muka/os.total_bayar)*100) < 15";

				}else if($dp == '1520') {
					$having .=' AND jumlah BETWEEN  15 AND 20';
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 15 AND ((os.uang_muka/os.total_bayar)*100) < 20";

				}else if($dp == '20') {
					$having .=' AND jumlah BETWEEN  20 AND 100';
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 20";

				}else{
					$having .='';
				}
			}
				
			if (!empty($tanggal_akhir)){ 

				$tanggalSatuBulanIniAwal  = date($tanggal_akhir);
				$tanggalSatuBulanIniAkhir = date($tanggal_awal);
				
				$get_date_mtd .= "AND  left(os.created_at,10) BETWEEN '$tanggalSatuBulanIniAkhir'   and '$tanggalSatuBulanIniAwal'";
			}

            $query=$this->db->query("SELECT
			mp.pekerjaan as nama_pekerjaan ,os.pekerjaan, COUNT(1) as jumlah_pekerjaan 
			from  tr_order_survey os 
			join ms_tipe_kendaraan tk on tk.id_tipe_kendaraan = os.id_tipe_kendaraan 
			join ms_pekerjaan mp on os.pekerjaan = mp.id_pekerjaan 
			$where $get_date_mtd
			AND os.status_survey ='rejected'
			GROUP by os.pekerjaan
            ")->result();
            return $query;
        }


        public function get_data_reject_vs_approval($segment,$series,$tipe,$kecamatan,$fincoy,$dp,$tanggal_akhir,$tanggal_awal,$dealer)
		{

			$where ="";
			$get_date_m1="";
			$get_date_mtd="";
			$fincoy_array ="";

			if (!empty($dealer))                   { $where.= " AND os.id_dealer = '$dealer' ";}
			if (!empty($kecamatan))	          	   { $where.= " AND os.id_kecamatan ='$kecamatan'"; }
			if (!empty($series))	          	   { $where.= " AND os.id_tipe_kendaraan = '$series' "; }
			if (!empty($segment))	          	   { $where.= " AND tk.id_kategori = '$segment' "; }
			if (!empty($tipe))	          	   	   { $where.= " AND tk.id_tipe_kendaraan = '$tipe' "; }

			if (!empty($dp))	
			{ 
				if ($dp == '10'){
				$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 0 AND ((os.uang_muka/os.total_bayar)*100) < 10";

				}else if($dp == '1015') {
				$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 10 AND ((os.uang_muka/os.total_bayar)*100) < 15";

				}else if($dp == '1520') {
				$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 15 AND ((os.uang_muka/os.total_bayar)*100) < 20";

				}else if($dp == '20') {
				$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 20";
				}else{
				}
			}
			
			if (!empty($tanggal_akhir)){ 
				$tanggalSatuBulanLaluAwal  = date('Y-m-d', strtotime('-1 month', strtotime($tanggal_awal)));
				$tanggalSatuBulanLaluAkhir = date('Y-m-d', strtotime('-1 month', strtotime($tanggal_akhir)));
				$tanggalSatuBulanIniAwal   = date($tanggal_awal);
				$tanggalSatuBulanIniAkhir  = date($tanggal_akhir);
				
				$get_date_m1  .= "AND  left(os.created_at,10) BETWEEN '$tanggalSatuBulanLaluAwal' and '$tanggalSatuBulanLaluAkhir'";
				$get_date_mtd .= "AND  left(os.created_at,10) BETWEEN '$tanggalSatuBulanIniAwal'   and '$tanggalSatuBulanIniAkhir'";
			}
			

			if (!empty($fincoy))	          	   { 
				foreach ($fincoy as  $key =>  $element ) {
					if( $key == 0 ){$komma = NULL;}else {$komma =",";}
					$fincoy_array .= $komma."'".$element."'";
				  }
				$where.= " AND os.id_finance_company  in ($fincoy_array)"; 
			}else{
				$fincoy ="'FC00000002','FC00000003','FC00000005','FC00000006','FC000003','FC000006','FC000008,'FC000027'";
				$query_fincoy = " and id_finance_company in ($fincoy)";
			}

			
            $query=$this->db->query("       
			(SELECT CONCAT('M-1') as 'Time',
			(COUNT(CASE WHEN os.status_survey = 'approved'  $where $get_date_m1 then 1 ELSE NULL END))  as 'Approve',
			(COUNT(CASE WHEN os.status_survey = 'rejected'  $where $get_date_m1 then 1 ELSE NULL END))  as 'Reject'
			from tr_order_survey os
			left join ms_tipe_kendaraan tk on tk.id_tipe_kendaraan = os.id_tipe_kendaraan
			)
			UNION
			(SELECT CONCAT('M') as 'Time',
			(COUNT(CASE WHEN os.status_survey = 'approved'  $where	$get_date_mtd then 1 ELSE NULL END))  as 'Approve',
			(COUNT(CASE WHEN os.status_survey = 'rejected'  $where	$get_date_mtd then 1 ELSE NULL END))  as 'Reject'
			from tr_order_survey os
			left join ms_tipe_kendaraan tk on tk.id_tipe_kendaraan = os.id_tipe_kendaraan
			)
             ")->result();
            return $query;
        }


		public function get_data_reject_by_oc_daily($segment,$series,$tipe,$kecamatan,$fincoy,$dp,$tanggal_akhir,$tanggal_awal,$dealer)
		{
			$where ="1=1";
			$get_date_m1="";
			$get_date_mtd="";

			$fincoy_array ="";
			$having='HAVING 1=1 ';

			if (!empty($segment))	          	   { $where.= " AND tk.id_kategori = '$segment' "; }
			if (!empty($tipe))	          	   	   { $where.= " AND tk.id_tipe_kendaraan = '$tipe' "; }
			if (!empty($series))	          	   { $where.= " AND os.id_tipe_kendaraan = '$series' "; }
			if (!empty($dealer))                   { $where.= " AND os.id_dealer = '$dealer' ";}
			// if (!empty($fincoy))	          	   { $where.= " AND os.id_finance_company ='$fincoy'"; }
			if (!empty($kecamatan))	          	   { $where.= " AND os.id_kecamatan ='$kecamatan'"; }

			if (!empty($dp))	
			{ 
				if ($dp == '10'){
				$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 0 AND ((os.uang_muka/os.total_bayar)*100) < 10";

				}else if($dp == '1015') {
				$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 10 AND ((os.uang_muka/os.total_bayar)*100) < 15";

				}else if($dp == '1520') {
				$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 15 AND ((os.uang_muka/os.total_bayar)*100) < 20";

				}else if($dp == '20') {
				$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 20";
				}else{
				}
			}

			if (!empty($fincoy))	          	   { 
				foreach ($fincoy as  $key =>  $element ) {
					if( $key == 0 ){$komma = NULL;}else {$komma =",";}
					$fincoy_array .= $komma."'".$element."'";
				  }
				$where.= " AND os.id_finance_company  in ($fincoy_array)"; 
			}
				
			if (!empty($tanggal_akhir)){ 
				$tanggalSatuBulanLaluAwal = date('Y-m-d', strtotime('-1 month', strtotime($tanggal_akhir)));
				$tanggalSatuBulanLaluAkhir = date('Y-m-d', strtotime('-1 month', strtotime($tanggal_awal)));
				$tanggalSatuBulanIniAwal  = date($tanggal_akhir);
				$tanggalSatuBulanIniAkhir = date($tanggal_awal);
				$get_date_m1  .= "AND  left(os.created_at,10) BETWEEN '$tanggalSatuBulanLaluAkhir'  and '$tanggalSatuBulanLaluAwal'";
				$get_date_mtd .= "AND  left(os.created_at,10) BETWEEN '$tanggalSatuBulanIniAkhir'   and '$tanggalSatuBulanIniAwal'";
				$date=" AND  left(os.created_at,10) BETWEEN '$tanggalSatuBulanIniAkhir' and '$tanggalSatuBulanIniAwal'";
			}

			$query=$this->db->query("SELECT 
			left(os.created_at,10),
			DATE_FORMAT(left(os.created_at,10),'%d-%M') as tgl_spk,
			(COUNT(CASE WHEN $where $get_date_mtd AND os.status_survey  = 'approved'   then 1 ELSE NULL END)) as 'order_sales',
			(COUNT(CASE WHEN $where $get_date_mtd AND os.status_survey  = 'rejected'  then 1 ELSE NULL END)) as 'rejected_rate'
						from  tr_order_survey os 
						join ms_tipe_kendaraan tk on tk.id_tipe_kendaraan = os.id_tipe_kendaraan
						join ms_pekerjaan mp on os.pekerjaan = mp.id_pekerjaan
						$date
						GROUP by  EXTRACT(YEAR FROM left(os.created_at,10)), EXTRACT(MONTH FROM left(os.created_at,10)) , EXTRACT(DAY FROM left(os.created_at,10))
			")->result();

            return $query;
		}


		public function down_payment_comparrison_occuptation_mtd($segment,$series,$tipe,$kecamatan,$fincoy,$dp,$tanggal_akhir,$tanggal_awal,$dealer)
		{
		$where ="";
		$get_date_m1="";
		$get_date_mtd="";
		$fincoy_array ="";

		if (!empty($segment))	          	   { $where.= " AND tk.id_kategori = '$segment' "; }
		if (!empty($tipe))	          	   	   { $where.= " AND tk.id_tipe_kendaraan = '$tipe' "; }
		if (!empty($series))	          	   { $where.= " AND os.id_tipe_kendaraan = '$series' "; }
		if (!empty($dealer))                   { $where.= " AND os.id_dealer = '$dealer' ";}
		// if (!empty($fincoy))	          	   { $where.= " AND os.id_finance_company ='$fincoy'"; }
		if (!empty($kecamatan))	          	   { $where.= " AND os.id_kecamatan ='$kecamatan'"; }


		if (!empty($fincoy))	          	   { 
			foreach ($fincoy as  $key =>  $element ) {
				if( $key == 0 ){$komma = NULL;}else {$komma =",";}
				$fincoy_array .= $komma."'".$element."'";
			  }
			$where.= " AND os.id_finance_company  in ($fincoy_array)"; 
		}
		
			$having='HAVING 1=1 ';
	
			if (!empty($dp))	
			{ 
				if ($dp == '10'){
				$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 0 AND ((os.uang_muka/os.total_bayar)*100) < 10";

				}else if($dp == '1015') {
				$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 10 AND ((os.uang_muka/os.total_bayar)*100) < 15";

				}else if($dp == '1520') {
				$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 15 AND ((os.uang_muka/os.total_bayar)*100) < 20";

				}else if($dp == '20') {
				$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 20";
				}else{
				}
			}
				
			if (!empty($tanggal_akhir)){ 
				$tanggalSatuBulanLaluAwal = date('Y-m-d', strtotime('-1 month', strtotime($tanggal_akhir)));
				$tanggalSatuBulanLaluAkhir = date('Y-m-d', strtotime('-1 month', strtotime($tanggal_awal)));
				$tanggalSatuBulanIniAwal  = date($tanggal_akhir);
				$tanggalSatuBulanIniAkhir = date($tanggal_awal);
				$get_date_m1  .= "AND  left(os.created_at,10) BETWEEN '$tanggalSatuBulanLaluAkhir' and '$tanggalSatuBulanLaluAwal'";
				$get_date_mtd .= "AND  left(os.created_at,10) BETWEEN '$tanggalSatuBulanIniAkhir'   and '$tanggalSatuBulanIniAwal'";
			}else{
				$get_date_m1  .= "AND left(os.created_at,10)  BETWEEN '2022-04-01' and '2022-05-01'";
				$get_date_mtd .= "AND left(os.created_at,10)  BETWEEN '2022-05-01' and '2022-06-01'";
			}

			$query = $this->db->query("SELECT os.pekerjaan as pekerjaan,
						(COUNT(CASE WHEN ((os.uang_muka/os.total_bayar)*100) > 0  AND ((os.uang_muka/os.total_bayar)*100) < 10   $where	    $get_date_mtd   then 1 ELSE NULL END)) as 'p1010',
						(COUNT(CASE WHEN ((os.uang_muka/os.total_bayar)*100) > 10  AND ((os.uang_muka/os.total_bayar)*100) < 15  $where 	$get_date_mtd   then 1 ELSE NULL END)) as 'p1015',
						(COUNT(CASE WHEN ((os.uang_muka/os.total_bayar)*100) > 15  AND ((os.uang_muka/os.total_bayar)*100) < 20  $where 	$get_date_mtd   then 1 ELSE NULL END)) as 'p1520',
						(COUNT(CASE WHEN ((os.uang_muka/os.total_bayar)*100) > 20   $where  $get_date_mtd   then 1 ELSE NULL END)) as 'p2020'
						from tr_order_survey os join tr_hasil_survey hs on os.no_order_survey = hs.no_order_survey
						join ms_tipe_kendaraan tk on tk.id_tipe_kendaraan = os.id_tipe_kendaraan 
						join tr_sales_order so on so.no_spk = hs.no_spk
						WHERE os.status_survey ='approved' AND hs.status_approval = 'approved'
						and so.no_invoice is not null
						GROUP by os.pekerjaan
			")->result();
			return $query;
		}


		public function	percobaan()
		{

		$sql = $this->db->query("
			select a.no_spk , a.created_at as tgl_spk , b.no_order_survey ,b.created_at as tgl_order, (case when b.created_at > m.created_at then ADDTIME(m.created_at, \"12:00:00\") else m.created_at end) as tgl_hasil,
			a.no_ktp , a.nama_konsumen , f.pekerjaan , a.alamat , c.kelurahan , d.kecamatan , e.kabupaten , 
			a.id_tipe_kendaraan, b.id_warna , h.tipe_ahm , g.warna ,
			a.harga_on_road , a.jenis_beli , (case when i.finance_company is null then '-' else i.finance_company end) as finance_company , a.uang_muka , ifnull((a.voucher_1 + a.voucher_2),0) as voucer , a.voucher_tambahan_1 ,
			a.voucher_tambahan_2 , diskon , (a.uang_muka - ifnull((a.voucher_1 + a.voucher_2),0)) as dp_stor , 
			k.id_flp_md , l.jabatan , (case when m.status_approval is null then a.status_spk else m.status_approval end) as status, 
			(case when m.status_approval = 'rejected' then m.keterangan else '-' end) alasan,
			SEC_TO_TIME ( TIMESTAMPDIFF(MINUTE,  b.created_at, (case when a.created_at > m.created_at then ADDTIME(m.created_at, \"12:00:00\") else m.created_at end)) )as selisih , k.nama_lengkap, a.angsuran, a.tenor,
			(case when SEC_TO_TIME ( TIMESTAMPDIFF(SECOND ,  a.created_at, (case when a.created_at > m.created_at then ADDTIME(m.created_at, \"12:00:00\") else m.created_at end)) ) is null then '00:00:00' else TIME_FORMAT(SEC_TO_TIME ( TIMESTAMPDIFF(SECOND ,  a.created_at, (case when a.created_at > m.created_at then ADDTIME(m.created_at, \"12:00:00\") else m.created_at end)) ),\"%H:%i:%s\") end) as times
			from tr_spk a 
			left join tr_order_survey b on a.no_spk = b.no_spk 
			join ms_kelurahan c on a.id_kelurahan  = c.id_kelurahan 
			join ms_kecamatan d on c.id_kecamatan = d.id_kecamatan 
			join ms_kabupaten e on d.id_kabupaten = e.id_kabupaten 
			join ms_pekerjaan f on f.id_pekerjaan = a.pekerjaan 
			join ms_warna g on g.id_warna = b.id_warna 
			join ms_tipe_kendaraan h on h.id_tipe_kendaraan = a.id_tipe_kendaraan 
			left join ms_finance_company i on i.id_finance_company = b.id_finance_company
			join tr_prospek j on j.id_customer = a.id_customer 
			join ms_karyawan_dealer k on k.id_karyawan_dealer = j.id_karyawan_dealer 
			join ms_jabatan l on l.id_jabatan = k.id_jabatan 
			left join tr_hasil_survey m on b.no_order_survey = m.no_order_survey 
			where a.created_at >= '2023-05-04' and a.created_at <= '2023-05-04' and a.id_dealer = '46'
			and (m.status_approval IN ('rejected','approved') or a.jenis_beli ='cash' or a.status_spk in ('canceled','booking','rejected','close')) 
			order by a.created_at asc, b.created_at asc
		")->result();

		return $sql;

		}
		

		public function	get_lead_time($segment,$series,$tipe,$kecamatan,$fincoy,$dp,$tanggal_akhir,$tanggal_awal,$dealer)
		{
			$where ="1=1";
			$get_date_m1="";
			$get_date_mtd="";
			$fincoy_array ="";

	
			if (!empty($segment))	          	   { $where.= " AND tk.id_kategori = '$segment' "; }
			if (!empty($tipe))	          	   	   { $where.= " AND tk.id_tipe_kendaraan = '$tipe' "; }
			if (!empty($series))	          	   { $where.= " AND os.id_tipe_kendaraan = '$series' "; }
			if (!empty($dealer))                   { $where.= " AND os.id_dealer = '$dealer' ";}
			if (!empty($fincoy))	          	   { $where.= " AND os.id_finance_company ='$fincoy'"; }
			if (!empty($kecamatan))	          	   { $where.= " AND os.id_kecamatan ='$kecamatan'"; }
			
				$having='HAVING 1=1 ';

				if (!empty($fincoy))	          	   { 
					foreach ($fincoy as  $key =>  $element ) {
						if( $key == 0 ){$komma = NULL;}else {$komma =",";}
						$fincoy_array .= $komma."'".$element."'";
					  }
					$where.= " AND os.id_finance_company  in ($fincoy_array)"; 
				}
	
				if (!empty($dp))	
				{ 
					if ($dp == '10'){
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 0 AND ((os.uang_muka/os.total_bayar)*100) < 10";
	
					}else if($dp == '1015') {
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 10 AND ((os.uang_muka/os.total_bayar)*100) < 15";
	
					}else if($dp == '1520') {
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 15 AND ((os.uang_muka/os.total_bayar)*100) < 20";
	
					}else if($dp == '20') {
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 20";
					}else{
					}
				}
					
				if (!empty($tanggal_akhir)){ 
					$tanggalSatuBulanLaluAwal = date('Y-m-d', strtotime('-1 month', strtotime($tanggal_akhir)));
					$tanggalSatuBulanLaluAkhir = date('Y-m-d', strtotime('-1 month', strtotime($tanggal_awal)));
					$tanggalSatuBulanIniAwal  = date($tanggal_akhir);
					$tanggalSatuBulanIniAkhir = date($tanggal_awal);
					$get_date_m1  .= "AND  left(os.created_at,10) BETWEEN '$tanggalSatuBulanLaluAwal' and '$tanggalSatuBulanLaluAkhir'";
					$get_date_mtd .= "AND  left(os.created_at,10) BETWEEN '$tanggalSatuBulanIniAwal'   and '$tanggalSatuBulanIniAkhir'";

				}else{
					$get_date_m1  .= "AND left(os.created_at,10)  BETWEEN '2022-04-01' and '2022-05-01'";
					$get_date_mtd .= "AND left(os.created_at,10)  BETWEEN '2022-05-01' and '2022-06-01'";
				}

			$query = $this->db->query("
			SELECT os.id_finance_company ,os.created_at as order_created, hs.created_at as hasil_created , so.tgl_cetak_invoice,tjs.created_at as tjs_creted, so.delivery_document_id
			from tr_sales_order so
			join tr_entry_po_leasing po on po.po_dari_finco  = so.no_po_leasing and so.no_spk =po.no_spk 
			join tr_hasil_survey hs on hs.id_hasil_survey = po.id_hasil_survey
			join tr_order_survey os on os.no_order_survey = hs.id_hasil_survey 
			join tr_invoice_tjs tjs on so.no_spk = tjs.id_spk 
			WHERE hs.status_approval = 'approved' $get_date_mtd
			and so.no_invoice is not null
			group by id_finance_company order by os.no_order_survey  DESC  
			")->result();
			return $query;
		}
		
		public function	down_payment_per_fincoy($segment,$series,$tipe,$kecamatan,$fincoy,$dp,$tanggal_akhir,$tanggal_awal,$dealer)
		{
			$where ="";
			$get_date_m1="";
			$get_date_mtd="";
			$fincoy_array ="";
	
			if (!empty($segment))	          	   { $where.= " AND tk.id_kategori = '$segment' "; }
			if (!empty($tipe))	          	   	   { $where.= " AND tk.id_tipe_kendaraan = '$tipe' "; }
			if (!empty($series))	          	   { $where.= " AND os.id_tipe_kendaraan = '$series' "; }
			if (!empty($dealer))                   { $where.= " AND os.id_dealer = '$dealer' ";}
			if (!empty($kecamatan))	          	   { $where.= " AND os.id_kecamatan ='$kecamatan'"; }

			if (!empty($fincoy))	          	   { 
				foreach ($fincoy as  $key =>  $element ) {
					if( $key == 0 ){$komma = NULL;}else {$komma =",";}
					$fincoy_array .= $komma."'".$element."'";
				  }
				$where.= " AND os.id_finance_company  in ($fincoy_array)"; 
			}
				$having='HAVING 1=1 ';
				$limit = "";

				if (!empty($dp))	
				{ 
					if ($dp == '10'){
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 0 AND ((os.uang_muka/os.total_bayar)*100) < 10";
	
					}else if($dp == '1015') {
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 10 AND ((os.uang_muka/os.total_bayar)*100) < 15";
	
					}else if($dp == '1520') {
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 15 AND ((os.uang_muka/os.total_bayar)*100) < 20";
	
					}else if($dp == '20') {
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 20";
					}else{
					}
				}
					
				if (!empty($tanggal_akhir)){ 
					$tanggalSatuBulanLaluAwal = date('Y-m-d', strtotime('-1 month', strtotime($tanggal_akhir)));
					$tanggalSatuBulanLaluAkhir = date('Y-m-d', strtotime('-1 month', strtotime($tanggal_awal)));
					$tanggalSatuBulanIniAwal  = date($tanggal_akhir);
					$tanggalSatuBulanIniAkhir = date($tanggal_awal);

					$get_date_m1  .= "AND  left(os.created_at,10) BETWEEN '$tanggalSatuBulanLaluAkhir' and '$tanggalSatuBulanLaluAwal'";
					$get_date_mtd .= "AND  left(os.created_at,10) BETWEEN '$tanggalSatuBulanIniAkhir'   and '$tanggalSatuBulanIniAwal'";

					// $get_date_m1  .= "AND  left(os.created_at,10) BETWEEN '$tanggalSatuBulanLaluAwal' and '$tanggalSatuBulanLaluAkhir'";
					// $get_date_mtd .= "AND  left(os.created_at,10) BETWEEN '$'   and '$tanggalSatuBulanIniAkhir'";
					
				}
				else{
					$get_date_m1  .= "AND left(os.created_at,10)  BETWEEN '2022-04-01' and '2022-05-01'";
					$get_date_mtd .= "AND left(os.created_at,10)  BETWEEN '2022-05-01' and '2022-06-01'";
				}


			$query=$this->db->query("
			(
			SELECT os.id_finance_company, fc.finance_company as finance_company ,
			CONCAT('m1') as 'Times',
			(COUNT(CASE WHEN ((os.uang_muka/os.total_bayar)*100) > 0  AND ((os.uang_muka/os.total_bayar)*100) < 10  $where $get_date_m1  then 1 ELSE NULL END)) as 'p1010',
			(COUNT(CASE WHEN ((os.uang_muka/os.total_bayar)*100) > 10  AND ((os.uang_muka/os.total_bayar)*100) < 15 $where $get_date_m1  then 1 ELSE NULL END)) as 'p1015',
			(COUNT(CASE WHEN ((os.uang_muka/os.total_bayar)*100) > 15  AND ((os.uang_muka/os.total_bayar)*100) < 20 $where $get_date_m1  then 1 ELSE NULL END)) as 'p1520',
			(COUNT(CASE WHEN ((os.uang_muka/os.total_bayar)*100) > 20 $where $get_date_m1   then 1 ELSE NULL END)) as 'p2020'
			FROM tr_order_survey os 
			left join tr_hasil_survey hs on hs.no_spk =os.no_spk and hs.no_order_survey=os.no_order_survey
									join tr_sales_order so on so.no_spk=os.no_spk and hs.no_order_survey=os.no_order_survey
									join ms_finance_company fc on fc.id_finance_company = os.id_finance_company  
									left join tr_faktur_stnk_detail fs on fs.id_sales_order =so.id_sales_order and fs.no_spk= so.no_spk and hs.no_order_survey=os.no_order_survey
									left join ms_tipe_kendaraan tk on tk.id_tipe_kendaraan = os.id_tipe_kendaraan
									WHERE  os.status_survey ='approved'	
			GROUP BY os.id_finance_company 
			)
			UNION
			(SELECT os.id_finance_company, fc.finance_company as finance_company ,
			CONCAT('mtd') as 'Times',
			(COUNT(CASE WHEN ((os.uang_muka/os.total_bayar)*100) > 0  AND ((os.uang_muka/os.total_bayar)*100) < 10  $where $get_date_mtd  then 1 ELSE NULL END)) as 'p1010',
			(COUNT(CASE WHEN ((os.uang_muka/os.total_bayar)*100) > 10  AND ((os.uang_muka/os.total_bayar)*100) < 15 $where $get_date_mtd  then 1 ELSE NULL END)) as 'p1015',
			(COUNT(CASE WHEN ((os.uang_muka/os.total_bayar)*100) > 15  AND ((os.uang_muka/os.total_bayar)*100) < 20 $where $get_date_mtd  then 1 ELSE NULL END)) as 'p1520',
			(COUNT(CASE WHEN ((os.uang_muka/os.total_bayar)*100) > 20 $where $get_date_mtd   then 1 ELSE NULL END)) as 'p2020'
			FROM tr_order_survey os 
			left join tr_hasil_survey hs on hs.no_spk =os.no_spk and hs.no_order_survey=os.no_order_survey
									join tr_sales_order so on so.no_spk=os.no_spk and hs.no_order_survey=os.no_order_survey
									join ms_finance_company fc on fc.id_finance_company = os.id_finance_company  
									left join tr_faktur_stnk_detail fs on fs.id_sales_order =so.id_sales_order and fs.no_spk= so.no_spk and hs.no_order_survey=os.no_order_survey
									left join ms_tipe_kendaraan tk on tk.id_tipe_kendaraan = os.id_tipe_kendaraan
									WHERE  os.status_survey ='approved'	
			GROUP BY os.id_finance_company 
			) order by id_finance_company , times  asc
				");
				return $query;
		}


			
		public function	reject_by_down_perfincoy_mtd($segment,$series,$tipe,$kecamatan,$fincoy,$dp,$tanggal_akhir,$tanggal_awal,$dealer)
		{
			$where ="";
			$get_date_m1="";
			$get_date_mtd="";
			$fincoy_array="";
	
			if (!empty($segment))	          	   { $where.= " AND tk.id_kategori = '$segment' "; }
			if (!empty($tipe))	          	   	   { $where.= " AND tk.id_tipe_kendaraan = '$tipe' "; }
			if (!empty($series))	          	   { $where.= " AND os.id_tipe_kendaraan = '$series' "; }
			if (!empty($dealer))                   { $where.= " AND os.id_dealer = '$dealer' ";}
			if (!empty($kecamatan))	          	   { $where.= " AND os.id_kecamatan ='$kecamatan'"; }

			if (!empty($fincoy))	          	   { 
				foreach ($fincoy as  $key =>  $element ) {
					if( $key == 0 ){$komma = NULL;}else {$komma =",";}
					$fincoy_array .= $komma."'".$element."'";
				  }
				$where.= " AND os.id_finance_company  in ($fincoy_array)"; 
			}

				$having='HAVING 1=1 ';
				$limit = "";

				if (!empty($dp))	
				{ 
					if ($dp == '10'){
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 0 AND ((os.uang_muka/os.total_bayar)*100) < 10";
	
					}else if($dp == '1015') {
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 10 AND ((os.uang_muka/os.total_bayar)*100) < 15";
	
					}else if($dp == '1520') {
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 15 AND ((os.uang_muka/os.total_bayar)*100) < 20";
	
					}else if($dp == '20') {
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 20";
					}else{
					}
				}
					
				if (!empty($tanggal_akhir)){ 
					$tanggalSatuBulanLaluAwal = date('Y-m-d', strtotime('-1 month', strtotime($tanggal_akhir)));
					$tanggalSatuBulanLaluAkhir = date('Y-m-d', strtotime('-1 month', strtotime($tanggal_awal)));
					$tanggalSatuBulanIniAwal  = date($tanggal_akhir);
					$tanggalSatuBulanIniAkhir = date($tanggal_awal);
					$get_date_m1  .= "AND  left(os.created_at,10) BETWEEN '$tanggalSatuBulanLaluAkhir' and '$tanggalSatuBulanLaluAwal'";
					$get_date_mtd .= "AND  left(os.created_at,10) BETWEEN '$tanggalSatuBulanIniAkhir'   and '$tanggalSatuBulanIniAwal'";
				}else{
					$get_date_m1  .= "AND left(os.created_at,10)  BETWEEN '2022-04-01' and '2022-05-01'";
					$get_date_mtd .= "AND left(os.created_at,10) BETWEEN '2022-05-01'    and '2022-06-01'";
				}


			$query=$this->db->query("
			(SELECT os.id_finance_company,
			CONCAT('m1') as 'Times',
			(COUNT(CASE WHEN ((os.uang_muka/os.total_bayar)*100) > 0  AND ((os.uang_muka/os.total_bayar)*100) < 10  $where $get_date_m1  then 1 ELSE NULL END)) as 'p1010',
			(COUNT(CASE WHEN ((os.uang_muka/os.total_bayar)*100) > 10  AND ((os.uang_muka/os.total_bayar)*100) < 15 $where $get_date_m1  then 1 ELSE NULL END)) as 'p1015',
			(COUNT(CASE WHEN ((os.uang_muka/os.total_bayar)*100) > 15  AND ((os.uang_muka/os.total_bayar)*100) < 20 $where $get_date_m1  then 1 ELSE NULL END)) as 'p1520',
			(COUNT(CASE WHEN ((os.uang_muka/os.total_bayar)*100) > 20 $where $get_date_m1   then 1 ELSE NULL END)) as 'p2020'
			FROM tr_order_survey os 
			left join tr_hasil_survey hs on hs.no_spk =os.no_spk and hs.no_order_survey=os.no_order_survey
									left join ms_tipe_kendaraan tk on tk.id_tipe_kendaraan = os.id_tipe_kendaraan
									WHERE  os.status_survey ='rejected'
			GROUP BY os.id_finance_company order by os.id_finance_company ASC 
			)
			UNION
			(SELECT os.id_finance_company,
			CONCAT('mtd') as 'Times',
			(COUNT(CASE WHEN ((os.uang_muka/os.total_bayar)*100) > 0  AND ((os.uang_muka/os.total_bayar)*100) < 10  $where $get_date_mtd  then 1 ELSE NULL END)) as 'p1010',
			(COUNT(CASE WHEN ((os.uang_muka/os.total_bayar)*100) > 10  AND ((os.uang_muka/os.total_bayar)*100) < 15 $where $get_date_mtd  then 1 ELSE NULL END)) as 'p1015',
			(COUNT(CASE WHEN ((os.uang_muka/os.total_bayar)*100) > 15  AND ((os.uang_muka/os.total_bayar)*100) < 20 $where $get_date_mtd  then 1 ELSE NULL END)) as 'p1520',
			(COUNT(CASE WHEN ((os.uang_muka/os.total_bayar)*100) > 20 $where $get_date_mtd   then 1 ELSE NULL END)) as 'p2020'
			FROM tr_order_survey os 
			left join tr_hasil_survey hs on hs.no_spk =os.no_spk and hs.no_order_survey=os.no_order_survey
									left join ms_tipe_kendaraan tk on tk.id_tipe_kendaraan = os.id_tipe_kendaraan
									WHERE  os.status_survey ='rejected'
			GROUP BY os.id_finance_company order by os.id_finance_company ASC 
			)  order by id_finance_company , times  ascs
			");
				return $query;
		}


		
		public function	reject_by_oc_perfincoy_mtd($segment,$series,$tipe,$kecamatan,$fincoy,$dp,$tanggal_akhir,$tanggal_awal,$dealer)
		{

			$where ="WHERE 1=1";
			$get_date_m1="";
			$get_date_mtd="";
			$fincoy_array ="";
			if (!empty($segment))	          	   { $where.= " AND tk.id_kategori = '$segment' "; }
			if (!empty($tipe))	          	   	   { $where.= " AND tk.id_tipe_kendaraan = '$tipe' "; }
			if (!empty($series))	          	   { $where.= " AND os.id_tipe_kendaraan = '$series' "; }
			if (!empty($dealer))                   { $where.= " AND os.id_dealer = '$dealer' ";}
			// if (!empty($fincoy))	          	   { $where.= " AND os.id_finance_company ='$fincoy'"; }
			if (!empty($kecamatan))	          	   { $where.= " AND os.id_kecamatan ='$kecamatan'"; }


			if (!empty($fincoy))	          	   { 
				foreach ($fincoy as  $key =>  $element ) {
					if( $key == 0 ){$komma = NULL;}else {$komma =",";}
					$fincoy_array .= $komma."'".$element."'";
				  }
				$where.= " AND spk.id_finance_company  in ($fincoy_array)"; 
			}
			
				if (!empty($dp))	
				{ 
					if ($dp == '10'){
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 0 AND ((os.uang_muka/os.total_bayar)*100) < 10";
	
					}else if($dp == '1015') {
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 10 AND ((os.uang_muka/os.total_bayar)*100) < 15";
	
					}else if($dp == '1520') {
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 15 AND ((os.uang_muka/os.total_bayar)*100) < 20";
	
					}else if($dp == '20') {
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 20";
					}else{
					}
				}

					
				if (!empty($tanggal_akhir)){ 
					// $dt = strtotime($tanggal_akhir);
			
					// $tanggal_set_mtd=date("Y-m-d", strtotime("+1 month", $dt));
					// $tanggal_set_min1=date("Y-m-d", strtotime("-1 month", $dt));
					// $get_date_m1  .= "AND cari.spk  BETWEEN '$tanggal_set_min1' and '$tanggal_akhir'";
					// $get_date_mtd .= "AND cari.spk  BETWEEN '$tanggal_akhir'    and '$tanggal_set_mtd'";

					$tanggalSatuBulanLaluAwal = date('Y-m-d', strtotime('-1 month', strtotime($tanggal_akhir)));
					$tanggalSatuBulanLaluAkhir = date('Y-m-d', strtotime('-1 month', strtotime($tanggal_awal)));
					$tanggalSatuBulanIniAwal  = date($tanggal_akhir);
					$tanggalSatuBulanIniAkhir = date($tanggal_awal);
					$get_date_m1  .= "AND cari.spk BETWEEN '$tanggalSatuBulanLaluAwal' and '$tanggalSatuBulanLaluAkhir'";
					$get_date_mtd .= "AND cari.spk BETWEEN '$tanggalSatuBulanIniAwal'   and '$tanggalSatuBulanIniAkhir'";

				}else{
					// $get_date_m1  .= "AND cari.spk  BETWEEN '2022-04-01' and '2022-05-01'";
					// $get_date_mtd .= "AND cari.spk  BETWEEN '2022-05-01'    and '2022-06-01'";
				}
				
				$query=$this->db->query("SELECT 
				cari.*,
				(COUNT(CASE WHEN 1=1 $get_date_m1  then 1 ELSE NULL END)) as 'm1',
				(COUNT(CASE WHEN 1=1 $get_date_mtd  then 1 ELSE NULL END)) as 'mtd'
				FROM (SELECT os.id_finance_company as fincoy , floor((os.uang_muka/os.total_bayar)*100) as jumlah, left(os.created_at,10) as spk from tr_order_survey os  
								left join tr_hasil_survey hs on hs.no_spk =os.no_spk and hs.no_order_survey=os.no_order_survey
								 join tr_sales_order so on so.no_spk=os.no_spk and hs.no_order_survey=os.no_order_survey
								left join tr_faktur_stnk_detail fs on fs.id_sales_order =so.id_sales_order and fs.no_spk= so.no_spk and hs.no_order_survey=os.no_order_survey
								left join ms_tipe_kendaraan tk on tk.id_tipe_kendaraan = os.id_tipe_kendaraan
								WHERE os.status_survey ='rejected' 
								) cari
				GROUP by fincoy
				");
				return $query;
			}


			public function	avg_reject_approval_per_fincoy($segment,$series,$tipe,$kecamatan,$fincoy,$dp,$tanggal_akhir,$tanggal_awal,$dealer)
			{
				
			$where ="";
			$get_date_m1="";
			$get_date_mtd="";
	
			if (!empty($segment))	          	   { $where.= " AND tk.id_kategori = '$segment' "; }
			if (!empty($series))	          	   { $where.= " AND os.id_tipe_kendaraan = '$series' "; }
			if (!empty($tipe))	          	   	   { $where.= " AND tk.id_tipe_kendaraan = '$tipe' "; }
			if (!empty($dealer))                   { $where.= " AND os.id_dealer = '$dealer' ";}
			// if (!empty($fincoy))	          	   { $where.= " AND os.id_finance_company ='$fincoy'"; }
			if (!empty($kecamatan))	          	   { $where.= " AND os.id_kecamatan ='$kecamatan'"; }
			
			$fincoy_array ="";


			if (!empty($fincoy))	          	   { 
				foreach ($fincoy as  $key =>  $element ) {
					if( $key == 0 ){$komma = NULL;}else {$komma =",";}
					$fincoy_array .= $komma."'".$element."'";
				  }
				$where.= " AND os.id_finance_company  in ($fincoy_array)"; 
			}
				$having='HAVING 1=1 ';
				$limit = "";
	
				if (!empty($dp))	
				{ 
					if ($dp == '10'){
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 0 AND ((os.uang_muka/os.total_bayar)*100) < 10";
	
					}else if($dp == '1015') {
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 10 AND ((os.uang_muka/os.total_bayar)*100) < 15";
	
					}else if($dp == '1520') {
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 15 AND ((os.uang_muka/os.total_bayar)*100) < 20";
	
					}else if($dp == '20') {
					$where .= "AND ((os.uang_muka/os.total_bayar)*100) > 20";
					}else{
					}
				}

					
				if (!empty($tanggal_akhir)){ 
					$tanggalSatuBulanLaluAwal = date('Y-m-d', strtotime('-1 month', strtotime($tanggal_akhir)));
					$tanggalSatuBulanLaluAkhir = date('Y-m-d', strtotime('-1 month', strtotime($tanggal_awal)));
					$tanggalSatuBulanIniAwal  = date($tanggal_akhir);
					$tanggalSatuBulanIniAkhir = date($tanggal_awal);
					$get_date_m1  .= "AND  left(os.created_at,10) BETWEEN '$tanggalSatuBulanLaluAkhir' and '$tanggalSatuBulanLaluAwal'";
					$get_date_mtd .= "AND  left(os.created_at,10) BETWEEN '$tanggalSatuBulanIniAkhir'   and '$tanggalSatuBulanIniAwal'";

				}else{
					$get_date_m1  .= "AND  left(os.created_at,10)  BETWEEN '2022-04-01' and '2022-05-01'";
					$get_date_mtd .= "AND  left(os.created_at,10)  BETWEEN '2022-05-01' and '2022-06-01'";
				}

			$query=$this->db->query("SELECT os.id_finance_company,
								COUNT(CASE WHEN  1=1 $get_date_mtd AND os.status_survey ='approved'    $where  THEN 1 END) AS 'mtd_approve',
								COUNT(CASE WHEN  1=1 $get_date_mtd AND os.status_survey ='rejected'     $where THEN 1 END) AS 'mtd_rejected',
								COUNT(CASE WHEN  1=1 $get_date_m1  AND os.status_survey ='approved' 	$where  THEN 1 END) AS m1_approve,
								COUNT(CASE WHEN  1=1 $get_date_m1  AND os.status_survey ='rejected' 	$where  THEN 1 END) AS m1_rejected
							FROM tr_order_survey os 
													left join ms_tipe_kendaraan tk on tk.id_tipe_kendaraan = os.id_tipe_kendaraan
													join ms_finance_company fc on fc.id_finance_company = os.id_finance_company 
													WHERE  os.status_survey in ('approved','rejected')
							GROUP BY os.id_finance_company order by os.id_finance_company ASC
			");
							
				$query=$this->db->query("
				(SELECT os.id_finance_company,
				CONCAT('m1') as 'Times',
												COUNT(CASE WHEN  1=1 THEN NULL END) AS 'mtd_approve',
												COUNT(CASE WHEN  1=1 THEN NULL END) AS 'mtd_rejected',
												COUNT(CASE WHEN  1=1 $get_date_m1  AND os.status_survey ='approved' 	$where    THEN 1 END) AS m1_approve,
												COUNT(CASE WHEN  1=1 $get_date_m1  AND os.status_survey ='rejected' 	$where    THEN 1 END) AS m1_rejected
											FROM tr_order_survey os 
																	left join ms_tipe_kendaraan tk on tk.id_tipe_kendaraan = os.id_tipe_kendaraan
																	join ms_finance_company fc on fc.id_finance_company = os.id_finance_company 
																	WHERE  os.status_survey in ('approved','rejected')
											GROUP BY os.id_finance_company order by os.id_finance_company ASC)
				UNION 
				(SELECT os.id_finance_company,
				CONCAT('mtd') as 'Times',
												COUNT(CASE WHEN  1=1  $get_date_mtd  AND os.status_survey ='approved'  $where   THEN 1 END) AS 'mtd_approve',
												COUNT(CASE WHEN  1=1  $get_date_mtd  AND os.status_survey ='rejected'  $where   THEN 1 END) AS 'mtd_rejected',
												COUNT(CASE WHEN  1=1  THEN NULL END) AS 'm1_approve',
												COUNT(CASE WHEN  1=1  THEN NULL END) AS 'm1_rejected'
											FROM tr_order_survey os 
																	left join ms_tipe_kendaraan tk on tk.id_tipe_kendaraan = os.id_tipe_kendaraan
																	join ms_finance_company fc on fc.id_finance_company = os.id_finance_company 
																	WHERE  os.status_survey in ('approved','rejected')
											GROUP BY os.id_finance_company order by os.id_finance_company ASC)
										ORDER by id_finance_company, times ASC
				");
				return $query;
			  }

    }
