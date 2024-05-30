<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_pembayaran_claim_dealer extends CI_Model {

	public function get_pembayaran_claim_dealer($status)
    {
		if (!empty($status))                   { $query_dealer = "  WHERE a.status = 'close' ";}else{ $query_dealer =''; } 	
		$query = $this->db->query("SELECT a.*, b.nama_dealer  from tr_claim_sales_program_payment_generate a
		join ms_dealer b on a.id_dealer = b.id_dealer $query_dealer  ORDER by created_at DESC ");
		return $query;
	}

	public function get_sales_program_modal()
    {
		$query =$this->db->query("SELECT sp.id_program_md,sp.id_program_ahm, sp.judul_kegiatan,jsp.jenis_sales_program,sp.periode_awal ,sp.periode_akhir 
		from tr_sales_program sp left join ms_jenis_sales_program  jsp on sp.id_jenis_sales_program=jsp.id_jenis_sales_program 
		where sp.periode_awal  BETWEEN  '2022-06-01'  AND '2023-06-01' order by sp.id_sales_program DESC");
		return $query;
	}

	public function get_select_ppn_table($tipe_program)
    {
		$query = $this->db->query(" SELECT ps.id_kategory from ms_jenis_sales_program jsp 
		inner join ms_program_subcategory ps on jsp.id_sub_category =ps.id_subcategory  WHERE jsp.id_jenis_sales_program  ='$tipe_program' ");
		return $query;
	}

	
	public function get_header_claim($id)
    {
		$query = $this->db->query("SELECT a.tgl_transaksi_claim ,b.nama_dealer,a.priode_program,a.id_dealer, a.status, a.send_payment_created ,a.approve_payment_dealer_created ,a.reject_payment_dealer_created,a.include_ppn ,a.id_bank, c.bank, a.tipe_program
		FROM tr_claim_sales_program_payment_generate a 
		join ms_dealer b on a.id_dealer =b.id_dealer 
		left join ms_bank c on a.id_bank = c.id_bank 
		where a.id_claim_generate_payment ='$id' group by a.id_claim_generate_payment  order by a.created_at DESC");
		return $query;
	}

	public function get_detail_claim($id)
    {
		$query = $this->db->query("SELECT csp.*,
		case when csp.jenis_pembayaran  = 'scp' then csp.total_pembayaran  else  0  end as total_pembayaran_md_ke_d,
		case when csp.jenis_pembayaran  = 'dg'  then csp.total_pembayaran  else  0  end as total_pembayaran_d_ke_md,
		md.nama_dealer  from tr_claim_sales_program_payment_generate_detail csp
		left join ms_dealer md on md.id_dealer = csp.id_dealer   where csp.id_claim_generate_payment ='$id' ");
		return $query;
	}



	
	public function get($dealer,$no_sales_id,$tipe_program,$awal,$akhir,$juklak)
    {
	
			if($tipe_program=='scp'){ // category discount // ms SP-001= 
				$in_category = "in ('SP-001','SP-004','SP-006','SP-008')";
				$status = NULL;

			
			}else if ($tipe_program=='dg'){
				$in_category= "in ('SP-002','SP-003','SP-005','SP-007','SP-009','SP-010','SP-011')";
			$status = "AND NOT tr_claim_sales_program.status IS NULL";
			// $in_category = NULL;
			$status = NULL;
			}else{
				$in_category= NULL;
				$status = NULL;
			}
			
			if (!empty($dealer))                   { $query_dealer = " AND ms_dealer.kode_dealer_ahm = '$dealer' ";}else{ $query_dealer =''; } 	
			if (!empty($no_sales_id))	           { $query_sales_id = " AND tr_claim_dealer.id_program_md  ='$no_sales_id'"; }else{ $query_sales_id =''; }
			if (!empty($awal)  &&  !empty($akhir) ){ $query_date = "AND  tr_claim_dealer.tgl_ajukan_claim  BETWEEN '$awal' AND '$akhir' "; }else{$query_date='';}
			if (!empty($juklak))		           { $query_juklak = "AND tr_sales_program.no_juklak_md ='$juklak' "; }else{ $query_juklak='';}
			if (!empty($tipe_program))             { $query_tipe_program = " AND tr_sales_program.id_jenis_sales_program  $in_category  "; }else{$query_tipe_program='';}


    	$data['temp_data']  = $this->db->query("SELECT
		ms_dealer.nama_dealer,ms_group_dealer.id_group_dealer, ms_dealer.kode_dealer_ahm, ms_jenis_sales_program.jenis_sales_program,tr_claim_dealer.id_program_md, tr_claim_dealer.id_claim, ms_dealer.id_dealer,
		tr_sales_program_tipe.id_tipe_kendaraan,tr_sales_program.no_juklak_md,tr_sales_program.series_motor,ms_dealer.id_dealer as dealer_detail,
		tr_sales_program.periode_awal,tr_sales_program.periode_akhir,
			sum(Case When tr_claim_dealer.Status = 'approved' Then 1 Else 0 End) AS status_approved,
			sum(Case When tr_claim_dealer.Status = 'ajukan' or tr_claim_dealer.status ='' Then 1 Else 0 End) AS status_ajukan,
			sum(Case When tr_claim_dealer.Status = 'rejected' Then 1 Else 0 End) AS status_reject,
			(case when tr_spk.jenis_beli = 'Cash' AND tr_claim_dealer.Status = 'approved' then (tr_sales_program_tipe.ahm_cash) 											 WHEN tr_spk.jenis_beli = 'Kredit' and tr_claim_dealer.Status = 'approved'  THEN  (tr_sales_program_tipe.ahm_kredit) else  (tr_sales_program_tipe.ahm_kredit) end) as kontribusi_ahm ,
			(case when tr_spk.jenis_beli = 'Cash' AND tr_claim_dealer.Status = 'approved' then (tr_sales_program_tipe.md_cash + tr_sales_program_tipe.add_md_cash) 			 WHEN tr_spk.jenis_beli = 'Kredit' and tr_claim_dealer.Status = 'approved'  THEN  (tr_sales_program_tipe.md_kredit + tr_sales_program_tipe.add_md_kredit) else  (tr_sales_program_tipe.md_kredit + tr_sales_program_tipe.add_md_kredit)   end) as kontribusi_md,
			(case when tr_spk.jenis_beli = 'Cash' AND tr_claim_dealer.Status = 'approved' then (tr_sales_program_tipe.dealer_cash +tr_sales_program_tipe.add_dealer_cash)	 WHEN tr_spk.jenis_beli = 'Kredit' and tr_claim_dealer.Status = 'approved'  THEN  (tr_sales_program_tipe.dealer_kredit + tr_sales_program_tipe.add_dealer_kredit) else (tr_sales_program_tipe.dealer_kredit + tr_sales_program_tipe.add_dealer_kredit) end) as kontribusi_dealer
			FROM tr_claim_dealer 
			left join tr_claim_sales_program_detail on tr_claim_sales_program_detail.id_claim_dealer =  tr_claim_dealer.id_claim
			left JOIN ms_dealer on tr_claim_dealer.id_dealer = ms_dealer.id_dealer 
			JOIN tr_sales_program on  tr_sales_program.id_program_md  = tr_claim_dealer.id_program_md 
			left join tr_sales_order on tr_sales_order.id_sales_order = tr_claim_dealer.id_sales_order 
			left JOIN ms_jenis_sales_program on tr_sales_program.id_jenis_sales_program = ms_jenis_sales_program.id_jenis_sales_program 
			left join tr_spk on tr_spk.no_spk = tr_sales_order.no_spk 
			left JOIN tr_sales_program_tipe ON tr_sales_program_tipe.id_program_md = tr_claim_dealer.id_program_md and tr_sales_program_tipe.id_tipe_kendaraan = tr_spk.id_tipe_kendaraan 
			join ms_group_dealer_detail on ms_dealer.id_dealer = ms_group_dealer_detail.id_dealer 
			join ms_group_dealer on ms_group_dealer.id_group_dealer = ms_group_dealer_detail.id_group_dealer 
			left join tr_claim_sales_program on tr_claim_sales_program.id_program_md = tr_claim_dealer.id_program_md and  tr_claim_sales_program.id_claim_sp = tr_claim_sales_program_detail.id_claim_sp   
			WHERE  1=1  $query_dealer $query_sales_id  $query_tipe_program $query_juklak $query_date
			$status
			AND tr_claim_dealer.send_dealer is not null
			AND tr_claim_dealer.id_claim_generate_payment is  null
			AND tr_claim_dealer.approve_from_dealer is not NULL
			GROUP by ms_dealer.kode_dealer_ahm, tr_sales_program.id_program_md
			order by ms_dealer.kode_dealer_ahm
		")->result();	
        return $data['temp_data'];
    }



	
	public function get_manual_ajax($query_dealer,$query_sales,$ppn_check)
    {

	$query=  $this->db->query(" SELECT  
	ms_dealer.nama_dealer,ms_group_dealer.id_group_dealer, ms_dealer.kode_dealer_ahm, ms_jenis_sales_program.jenis_sales_program,tr_claim_dealer.id_program_md, tr_claim_dealer.id_claim, ms_dealer.id_dealer,
	tr_sales_program_tipe.id_tipe_kendaraan,tr_sales_program.no_juklak_md,tr_sales_program.series_motor,ms_dealer.id_dealer as dealer_detail,
	tr_sales_program.periode_awal,tr_sales_program.periode_akhir, left(tr_claim_sales_program.created_at,10) as ppn_date_check,
		sum(Case When tr_claim_dealer.Status = 'approved' Then 1 Else 0 End) AS status_approved,
		sum(Case When tr_claim_dealer.Status = 'ajukan' or tr_claim_dealer.status ='' Then 1 Else 0 End) AS status_ajukan,
		sum(Case When tr_claim_dealer.Status = 'rejected' Then 1 Else 0 End) AS status_reject,
		(case when tr_spk.jenis_beli = 'Cash' AND tr_claim_dealer.Status = 'approved' then (tr_sales_program_tipe.ahm_cash) 											 WHEN tr_spk.jenis_beli = 'Kredit' and tr_claim_dealer.Status = 'approved'  THEN  (tr_sales_program_tipe.ahm_kredit) else  (tr_sales_program_tipe.ahm_kredit) end) as kontribusi_ahm ,
		(case when tr_spk.jenis_beli = 'Cash' AND tr_claim_dealer.Status = 'approved' then (tr_sales_program_tipe.md_cash + tr_sales_program_tipe.add_md_cash) 			 WHEN tr_spk.jenis_beli = 'Kredit' and tr_claim_dealer.Status = 'approved'  THEN  (tr_sales_program_tipe.md_kredit + tr_sales_program_tipe.add_md_kredit) else  (tr_sales_program_tipe.md_kredit + tr_sales_program_tipe.add_md_kredit)   end) as kontribusi_md,
		(case when tr_spk.jenis_beli = 'Cash' AND tr_claim_dealer.Status = 'approved' then (tr_sales_program_tipe.dealer_cash +tr_sales_program_tipe.add_dealer_cash)	 WHEN tr_spk.jenis_beli = 'Kredit' and tr_claim_dealer.Status = 'approved'  THEN  (tr_sales_program_tipe.dealer_kredit + tr_sales_program_tipe.add_dealer_kredit) else (tr_sales_program_tipe.dealer_kredit + tr_sales_program_tipe.add_dealer_kredit) end) as kontribusi_dealer,
		(case when tr_spk.jenis_beli = 'Cash' AND tr_claim_dealer.Status = 'approved' then (tr_sales_program_tipe.dealer_cash +tr_sales_program_tipe.add_dealer_cash)	 WHEN tr_spk.jenis_beli = 'Kredit' and tr_claim_dealer.Status = 'approved'  THEN  (tr_sales_program_tipe.dealer_kredit + tr_sales_program_tipe.add_dealer_kredit) else (tr_sales_program_tipe.dealer_kredit + tr_sales_program_tipe.add_dealer_kredit) end) as kontribusi_dealer,
		(case when tr_spk.jenis_beli = 'Cash' AND tr_claim_dealer.Status = 'approved' then (sum(Case When tr_claim_dealer.Status = 'approved' Then 1 Else 0 End) * tr_sales_program_tipe.ahm_cash) 												 WHEN tr_spk.jenis_beli = 'Kredit' and tr_claim_dealer.Status = 'approved'  THEN  (sum(Case When tr_claim_dealer.Status = 'approved' Then 1 Else 0 End) * tr_sales_program_tipe.ahm_kredit) else  (sum(Case When tr_claim_dealer.Status = 'approved' Then 1 Else 0 End) * tr_sales_program_tipe.ahm_kredit) end) as total_kontribusi_ahm ,
		(case when tr_spk.jenis_beli = 'Cash' AND tr_claim_dealer.Status = 'approved' then (sum(Case When tr_claim_dealer.Status = 'approved' Then 1 Else 0 End) * tr_sales_program_tipe.md_cash + tr_sales_program_tipe.add_md_cash)    		 WHEN tr_spk.jenis_beli = 'Kredit' and tr_claim_dealer.Status = 'approved'  THEN  (sum(Case When tr_claim_dealer.Status = 'approved' Then 1 Else 0 End) * tr_sales_program_tipe.md_kredit + tr_sales_program_tipe.add_md_kredit) else  (sum(Case When tr_claim_dealer.Status = 'approved' Then 1 Else 0 End) * tr_sales_program_tipe.ahm_kredit) end) as total_kontribusi_md ,
		(case when tr_spk.jenis_beli = 'Cash' AND tr_claim_dealer.Status = 'approved' then (sum(Case When tr_claim_dealer.Status = 'approved' Then 1 Else 0 End) * tr_sales_program_tipe.dealer_cash +tr_sales_program_tipe.add_dealer_cash)	 WHEN tr_spk.jenis_beli = 'Kredit' and tr_claim_dealer.Status = 'approved'  THEN  (sum(Case When tr_claim_dealer.Status = 'approved' Then 1 Else 0 End) * (tr_sales_program_tipe.dealer_kredit + tr_sales_program_tipe.add_dealer_kredit)) else (sum(Case When tr_claim_dealer.Status = 'approved' Then 1 Else 0 End) * (tr_sales_program_tipe.dealer_kredit + tr_sales_program_tipe.add_dealer_kredit)) end) as total_kontribusi_d
		FROM tr_claim_dealer 
		left join tr_claim_sales_program_detail on tr_claim_sales_program_detail.id_claim_dealer =  tr_claim_dealer.id_claim
		left JOIN ms_dealer on tr_claim_dealer.id_dealer = ms_dealer.id_dealer 
		JOIN tr_sales_program on  tr_sales_program.id_program_md  = tr_claim_dealer.id_program_md 
		left join tr_sales_order on tr_sales_order.id_sales_order = tr_claim_dealer.id_sales_order 
		left JOIN ms_jenis_sales_program on tr_sales_program.id_jenis_sales_program = ms_jenis_sales_program.id_jenis_sales_program 
		left join tr_spk on tr_spk.no_spk = tr_sales_order.no_spk 
		left JOIN tr_sales_program_tipe ON tr_sales_program_tipe.id_program_md = tr_claim_dealer.id_program_md and tr_sales_program_tipe.id_tipe_kendaraan = tr_spk.id_tipe_kendaraan 
		join ms_group_dealer_detail on ms_dealer.id_dealer = ms_group_dealer_detail.id_dealer 
		join ms_group_dealer on ms_group_dealer.id_group_dealer = ms_group_dealer_detail.id_group_dealer 
		left join tr_claim_sales_program on tr_claim_sales_program.id_program_md = tr_claim_dealer.id_program_md and  tr_claim_sales_program.id_claim_sp = tr_claim_sales_program_detail.id_claim_sp   
		WHERE  1=1  AND ms_dealer.kode_dealer_ahm = '$query_dealer' AND tr_claim_sales_program.id_program_md  = '$query_sales'
		GROUP by ms_dealer.kode_dealer_ahm, tr_sales_program.id_program_md
		order by ms_dealer.kode_dealer_ahm
	");
		return $query;
	}


	public function get_claim_approve_from_dealer($status)
    {
		$query = $this->db->query("SELECT  dl.nama_dealer ,cd.id_dealer,cd.id_program_md,cd.id_claim ,
		sum(case when cd.status ='approved' then 1 else 0 end) as approved,
		sum(case when cd.status ='rejected' then 1 else 0 end) as rejected,
		sum(case when cd.status ='approved' then cspd.nilai_potongan else 0 end) as nilai_potongan_approved,
		sum(case when cd.status ='rejected' then cspd.nilai_potongan else 0 end) as nilai_potongan_rejected
		from tr_claim_dealer cd left join tr_claim_sales_program_detail cspd on cspd.id_claim_dealer = cd.id_claim 
		left join ms_dealer dl on dl.id_dealer = cd.id_dealer 
		-- left join tr_claim_sales_program csp on cd.id_claim = csp.id_claim_sp 
		WHERE cd.id_dealer ='103'
		AND cd.approve_dealer_by is not null
		AND cd.id_claim_generate_payment is null
		group by cd.id_dealer,cd.id_program_md  ");
		return $query;
	}







}
