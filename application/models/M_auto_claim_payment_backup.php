<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_auto_claim_payment extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function get_from_md($data)
  {
    $id_program_ahm = $data['id_program_ahm'];
    $id_sales_program = $data['id_program_md'];
    $start_periode = $data['start_periode'];
    $end_periode = $data['end_periode'];

    
		$dealer = array(
			'00888','12203','05399','05391','01118','03530','05621','13385','07628','12598','12142','12797','13384','07719','07465',
			'11791','06935','13387','07780',
			'05545','04730','13386','17338',
			'03540','03573','07781','13621','13382',
			'03538','07464','12144','07717','04576',
			'01354','04692','13759','01925',
			'06111','06112','12143','00758','09673',
			'05529','13388','00675','07720',
			'08549','13381'
		);

    $hasil_array = $dealer;
    $string = "'" . implode("','", $hasil_array) . "'";

    var_dump($string);
    die();


    
    if (!empty($data['id_dealer']))            { 
            $query = $this->db->query("SELECT * from ms_dealer where id_dealer =".$data['id_dealer']." ");

            $query_dealer = " AND ms_dealer.id_dealer = ".$data['id_dealer']." ";}else{ $query_dealer ='';  } 	
            
          if (!empty($id_sales_program))	{ $id_sales_programs = " AND tr_claim_dealer.id_program_md  ='$id_sales_program'"; }else{ $id_sales_programs =''; }
          if (!empty($start_periode)  &&  !empty($end_periode) ){ $query_date = "AND  tr_claim_dealer.tgl_ajukan_claim  BETWEEN '$start_periode' AND '$end_periode' "; }else{$query_date='';}

      $query = $this->db->query("SELECT * 
      from ms_dealer delaer 
left join (
      SELECT
      tr_sales_program.id_sales_program ,ms_dealer.nama_dealer,ms_group_dealer.id_group_dealer, ms_dealer.kode_dealer_ahm, ms_jenis_sales_program.jenis_sales_program,tr_claim_dealer.id_program_md, tr_claim_dealer.id_claim, ms_dealer.id_dealer,
      tr_sales_program_tipe.id_tipe_kendaraan,tr_sales_program.no_juklak_md,tr_sales_program.series_motor,ms_dealer.id_dealer as dealer_detail,
      tr_sales_program.periode_awal,tr_sales_program.periode_akhir,
      sum(Case When  tr_claim_sales_program.Status = 'close' and   tr_spk.jenis_beli in ('Cash','Kredit')  Then 1 Else 0 End) AS status_ssu,
      sum(Case When tr_claim_dealer.Status  in ('approved','reject','ajukan')  Then 1 Else 0 End) AS status_claim,
      sum(Case When tr_claim_dealer.Status = 'approved' Then 1 Else 0 End) AS status_approved,
      sum(Case When tr_claim_dealer.Status = 'rejected' Then 1 Else 0 End) AS status_reject,
    
      sum(Case When  tr_spk.jenis_beli = 'Cash' AND  tr_sales_order.status_close   = 'close'   Then 1 Else 0 End) AS status_ssu_claim_cash,
      sum(Case When  tr_spk.jenis_beli = 'Cash' AND  tr_claim_sales_program.Status   = 'close'   Then 1 Else 0 End) AS status_claim_cash,
      sum(Case When  tr_spk.jenis_beli = 'Cash' AND  tr_claim_dealer.Status in ('approved','reject')  Then 1 Else 0 End) AS status_approved_claim_cash,
      sum(Case When  tr_spk.jenis_beli = 'Cash' AND  tr_claim_dealer.Status = 'approved' Then 1 Else 0 End) AS status_approved_cash,
      sum(Case When  tr_spk.jenis_beli = 'Cash' AND  tr_claim_dealer.Status = 'rejected' Then 1 Else 0 End) AS status_reject_cash,

      sum(Case When tr_spk.jenis_beli = 'Kredit' AND  tr_sales_order.status_close  = 'close'   Then 1 Else 0 End) AS status_ssu_claim_credit,
      sum(Case When tr_spk.jenis_beli = 'Kredit' AND tr_claim_sales_program.Status   = 'close'   Then 1 Else 0 End) AS status_claim_credit,
      sum(Case When tr_spk.jenis_beli = 'Kredit' AND tr_claim_dealer.Status in ('approved','reject') Then 1 Else 0 End) AS status_approved_claim_credit,
      sum(Case When tr_spk.jenis_beli = 'Kredit' AND tr_claim_dealer.Status = 'approved' Then 1 Else 0 End) AS status_approved_credit,
      sum(Case When tr_spk.jenis_beli   = 'Kredit' AND tr_claim_dealer.Status = 'rejected' Then 1 Else 0 End) AS status_reject_credit,
      (sum(Case When  tr_spk.jenis_beli = 'Cash' AND  tr_claim_dealer.Status = 'approved'  Then 1 Else 0 End))  				        * (case WHEN tr_spk.jenis_beli = 'Cash' and tr_claim_dealer.Status = 'approved'  THEN  (tr_sales_program_tipe.ahm_cash + tr_sales_program_tipe.md_cash ) else 0 end) as nilai_claim_approve_cash,
      (sum(Case When  tr_spk.jenis_beli = 'Kredit' AND  tr_claim_dealer.Status in ('approved','reject')  Then 1 Else 0 End))    * (case WHEN tr_spk.jenis_beli = 'Kredit' and tr_claim_dealer.Status = 'approved'  THEN  (tr_sales_program_tipe.ahm_kredit + tr_sales_program_tipe.md_kredit ) else 0 end) as nilai_claim_approve_credit
     
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
      WHERE  1=1  $query_dealer $id_sales_programs  $query_date
      GROUP by ms_dealer.kode_dealer_ahm, tr_sales_program.id_program_md
      order by ms_dealer.kode_dealer_ahm    
   ) as sub_sales_program on  sub_sales_program.id_dealer= delaer.id_dealer
   
      ");	
      return $query;
  }


  public function get_from_main_dealer_backup($data)
  {

    $id_program_ahm = $data['id_program_ahm'];
    $id_program_md = $data['id_program_md'];
    $dealer = $data['id_dealer'];
    $start_periode = $data['start_periode'];
    $end_periode = $data['end_periode'];
          
          if (!empty($dealer))                         { $query_dealer = " AND ms_dealer.kode_dealer_ahm = '$dealer' ";}else{ $query_dealer =''; } 	
          if (!empty($id_sales_program))	             { $id_sales_programs = " AND tr_claim_dealer.id_program_md  ='$id_sales_program'"; }else{ $id_sales_programs =''; }
          if (!empty($start_periode)  &&  !empty($end_periode) ){ $query_date = "AND  tr_claim_dealer.tgl_ajukan_claim  BETWEEN '$start_periode' AND '$end_periode' "; }else{$query_date='';}


      $query = $this->db->query("SELECT
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
          WHERE  1=1  $query_dealer $id_sales_programs  $query_date
          GROUP by ms_dealer.kode_dealer_ahm, tr_sales_program.id_program_md
          order by ms_dealer.kode_dealer_ahm
      ");	
      return $query;
  }


  public function get_from_main_dealer($data)
  {

    $id_program_ahm = $data['id_program_ahm'];
    $id_program_md = $data['id_program_md'];
    $dealer = $data['id_dealer'];
    $start_periode = $data['start_periode'];
    $end_periode = $data['end_periode'];
          
          if (!empty($dealer))                         { $query_dealer = " AND ms_dealer.kode_dealer_ahm = '$dealer' ";}else{ $query_dealer =''; } 	
          if (!empty($id_sales_program))	             { $id_sales_programs = " AND tr_claim_dealer.id_program_md  ='$id_sales_program'"; }else{ $id_sales_programs =''; }
          if (!empty($start_periode)  &&  !empty($end_periode) ){ $query_date = "AND  tr_claim_dealer.tgl_ajukan_claim  BETWEEN '$start_periode' AND '$end_periode' "; }else{$query_date='';}



          // ms_dealer.nama_dealer,
          // ms_group_dealer.id_group_dealer, 
          // ms_dealer.kode_dealer_ahm, 
          // ms_jenis_sales_program.jenis_sales_program,
          // tr_claim_dealer.id_program_md, 
          // tr_claim_dealer.id_claim, 
          // ms_dealer.id_dealer,
          // tr_sales_program_tipe.id_tipe_kendaraan,
          // tr_sales_program.no_juklak_md,
          // tr_sales_program.series_motor,
          // ms_dealer.id_dealer as dealer_detail,
          // tr_sales_program.periode_awal,
          // tr_sales_program.periode_akhir,

      $query = $this->db->query("SELECT
          spk.id_dealer, dl.kode_dealer_md, dl.nama_dealer,
          SUM(CASE when 1=1  AND stp.id_tipe_kendaraan =spk.id_tipe_kendaraan then 1 else NULL end) as 'sama',
          SUM(case when 1=1 then 1 else NULL end) as 'claim_dealer',
          SUM(case when so.no_spk is not null then 1 else null AND  stp.id_tipe_kendaraan is not null  end) as 'ssu_total',

          SUM(case when spk.jenis_beli = 'cash'   AND so.no_spk is not null  AND so.tgl_cetak_invoice is not null  AND  stp.id_tipe_kendaraan is not null then 1 else null  end) as 'ssu_total_cash',
          SUM(case when spk.jenis_beli = 'kredit' AND so.no_spk is not null  AND so.tgl_cetak_invoice is not null  AND  stp.id_tipe_kendaraan is not null  then 1 else null  end) as 'ssu_total_kredit',

          SUM(case when  spk.jenis_beli = 'kredit' AND spk.program_umum is not null 	AND  stp.id_tipe_kendaraan is not null  then 1 else null  end) as 'kredit_claim_by_dealer',
          SUM(case when  spk.jenis_beli = 'kredit' AND so.no_spk is not null  		AND  stp.id_tipe_kendaraan is not null  AND cld.status = 'approved'            	   then 1 else null     end) as 'ssu_total_kredit_approve_to_dealer',
          SUM(case when  spk.jenis_beli = 'kredit' AND so.no_spk is not null  		AND  stp.id_tipe_kendaraan is not null  AND cld.status_proposal = 'rejected_by_md' then 1 else null   end) as 'ssu_total_kredit_reject_to_dealer',

          SUM(case when  spk.jenis_beli = 'cash' AND spk.program_umum is not null 	AND  stp.id_tipe_kendaraan is not null  then 1 else null  end) as 'cash_claim_by_dealer',
          SUM(case when  spk.jenis_beli = 'cash' AND so.no_spk is not null  		    AND  stp.id_tipe_kendaraan is not null  AND cld.status = 'approved'            	   then 1 else null     end) as 'ssu_total_cash_approve_to_dealer',
          SUM(case when  spk.jenis_beli = 'cash' AND so.no_spk is not null  		    AND  stp.id_tipe_kendaraan is not null  AND cld.status_proposal = 'rejected_by_md' then 1 else null   end) as 'ssu_cash_kredit_reject_to_dealer',

          spk.program_umum,spk.program_gabungan
          from tr_spk spk left join tr_sales_order so on so.no_spk =spk.no_spk 
          JOIN ms_dealer dl on spk.id_dealer = dl.id_dealer 
          left join tr_sales_program sp on sp.id_program_md = spk.program_umum
          left join tr_sales_program spg on spg.id_program_md = spk.program_gabungan 
          left join tr_sales_program_tipe stp on stp.id_program_md = sp.id_program_md  AND spk.id_tipe_kendaraan = stp.id_tipe_kendaraan 
          left join tr_claim_dealer cld on cld.id_sales_order = so.id_sales_order 
          WHERE 1=1
          AND spk.tgl_spk BETWEEN  '2023-06-01' AND '2023-06-30' 
          AND so.no_spk is not null
          and cld.id_program_md is not null
          group by spk.id_dealer 
          HAVING sama is not null
          order by cld.id_program_md asc
      ");	
      return $query;
  }

  
  public function get_from_dealer($data)
  {

          $start_periode = $data['start_periode'];
          $end_periode = $data['end_periode'];
    
          if (!empty($data['id_dealer']))            
          { 
            $id_dealer_set =$data['id_dealer'];
		        $query	=	$this->m_admin->cari_kode_dealer($id_dealer_set);
            $dealer_array['set_dealer'] = $query_dealer = " AND ms_dealer.id_dealer = '$id_dealer_set'";
             $query_dealer_id = " AND spk.id_dealer = '$id_dealer_set'";
          }else
          { 
            $query_dealer ='';
          } 	
 
          if (!empty($start_periode)  &&  !empty($end_periode) ){ $query_date = "AND  tr_sales_program.periode_awal  BETWEEN '$start_periode' AND '$end_periode' AND   tr_sales_program.periode_akhir  BETWEEN '$start_periode' AND '$end_periode' "; }else{$query_date='';}


          $query = $this->db->query("SELECT sp.id_program_md as 'id_program_md_sub', tp.id_series as 'id_series_sub',
           sp.judul_kegiatan as sub_judul_deskripsi
          ,sub_query.*  from tr_sales_program sp 
          left join (
          SELECT
          -- sub_sales_order. id_tipe_kendaraan as 'sub_tipe_kendaraaan',
          -- sub_sales_order.status_ssu as 'status_ssu',
          -- sub_sales_order.jumlah_ssu_cash as 'status_ssu_claim_cash',
          -- sub_sales_order.jumlah_ssu_kredit as 'status_ssu_claim_credit',
          tr_sales_program.judul_kegiatan,
          tr_sales_program.id_sales_program ,ms_dealer.nama_dealer,ms_group_dealer.id_group_dealer, ms_dealer.kode_dealer_ahm, ms_jenis_sales_program.jenis_sales_program,tr_claim_dealer.id_program_md, tr_claim_dealer.id_claim, ms_dealer.id_dealer,
          tr_sales_program_tipe.id_tipe_kendaraan,tr_sales_program.no_juklak_md,tr_sales_program.series_motor,ms_dealer.id_dealer as dealer_detail,
          tr_sales_program.periode_awal,tr_sales_program.periode_akhir,
          ms_tipe_kendaraan.id_series,

          sum(Case When tr_claim_dealer.Status  in ('approved','ajukan','')  Then 1 Else 0 End) AS status_claim,
          sum(Case When tr_claim_dealer.Status = 'approved' Then 1 Else 0 End) AS status_approved,
          sum(Case When tr_claim_dealer.Status = 'rejected' Then 1 Else 0 End) AS status_reject,
        
          sum(Case When  tr_spk.jenis_beli = 'Cash' AND  tr_claim_dealer.Status in ('approved','rejected','ajukan','')  Then 1 Else 0 End) AS status_approved_claim_cash,
          sum(Case When  tr_spk.jenis_beli = 'Cash' AND  tr_claim_dealer.Status in ('approved','ajukan','') Then 1 Else 0 End) AS status_approved_cash,
          sum(Case When  tr_spk.jenis_beli = 'Cash' AND tr_claim_dealer.Status = 'rejected' Then 1 Else 0 End) AS status_reject_cash,

          sum(Case When tr_spk.jenis_beli = 'Kredit' AND tr_claim_dealer.Status in ('approved','rejected','ajukan','') Then 1 Else 0 End) AS status_approved_claim_credit,
          sum(Case When tr_spk.jenis_beli = 'Kredit' AND tr_claim_dealer.Status in ('approved','ajukan','') Then 1 Else 0 End) AS status_approved_credit,
          sum(Case When tr_spk.jenis_beli = 'Kredit' AND tr_claim_dealer.Status = 'rejected' Then 1 Else 0 End) AS status_reject_credit
    
          FROM tr_claim_dealer 
          left join tr_claim_sales_program_detail on tr_claim_sales_program_detail.id_claim_dealer =  tr_claim_dealer.id_claim
          left JOIN ms_dealer on tr_claim_dealer.id_dealer = ms_dealer.id_dealer 
          JOIN tr_sales_program on  tr_sales_program.id_program_md  = tr_claim_dealer.id_program_md 
          left join tr_sales_order on tr_sales_order.id_sales_order = tr_claim_dealer.id_sales_order 
          left JOIN ms_jenis_sales_program on tr_sales_program.id_jenis_sales_program = ms_jenis_sales_program.id_jenis_sales_program 
          left join tr_spk on tr_spk.no_spk = tr_sales_order.no_spk 
          left JOIN tr_sales_program_tipe ON tr_sales_program_tipe.id_program_md = tr_claim_dealer.id_program_md and tr_sales_program_tipe.id_tipe_kendaraan = tr_spk.id_tipe_kendaraan           
          left join ms_tipe_kendaraan on tr_sales_program_tipe.id_tipe_kendaraan  = ms_tipe_kendaraan.id_tipe_kendaraan
          -- LEFT JOIN
          -- (   
          --   select  
          --   tk.id_series, 
          --   spk.id_tipe_kendaraan,
          --   sum(case when so.tgl_cetak_invoice is not null then 1 else NULL end)  as 'status_ssu',
          --   sum(case when so.tgl_cetak_invoice is not null AND  spk.jenis_beli  = 'Cash' then 1 else NULL end) as 'jumlah_ssu_cash',
          --   sum(case when so.tgl_cetak_invoice is not null AND  spk.jenis_beli  = 'Kredit' then 1 else NULL end) as 'jumlah_ssu_kredit'
          --   from tr_spk spk 
          --   left JOIN ms_dealer on spk.id_dealer = ms_dealer.id_dealer 
          --   join ms_tipe_kendaraan tk on tk.id_tipe_kendaraan = spk.id_tipe_kendaraan 
          --   left join tr_sales_order so on spk.no_spk = so.no_spk 
          --   WHERE spk.status_spk not in ('canceled','rejected')
          --   and left(so.tgl_cetak_invoice,10) BETWEEN  '$start_periode' AND '$end_periode' $query_dealer 
          --   GROUP by 
          --   spk.id_tipe_kendaraan,
          --   tk.id_series
          --   order by spk.program_umum DESC 
          -- ) as sub_sales_order on ms_tipe_kendaraan.id_series = sub_sales_order.id_series
          join ms_group_dealer_detail on ms_dealer.id_dealer = ms_group_dealer_detail.id_dealer 
          join ms_group_dealer on ms_group_dealer.id_group_dealer = ms_group_dealer_detail.id_group_dealer 
          left join tr_claim_sales_program on tr_claim_sales_program.id_program_md = tr_claim_dealer.id_program_md and  tr_claim_sales_program.id_claim_sp = tr_claim_sales_program_detail.id_claim_sp   
          WHERE  1=1  $query_dealer  $query_date
          GROUP by ms_dealer.id_dealer, tr_sales_program.id_program_md
          order by ms_dealer.id_dealer
          ) as sub_query on sub_query.id_program_md = sp.id_program_md
          inner join tr_sales_program_tipe spt on sp.id_program_md =spt.id_program_md
          left join ms_tipe_kendaraan tp on spt.id_tipe_kendaraan = tp.id_tipe_kendaraan 
          WHERE  sp.periode_awal BETWEEN  '$start_periode' AND '$end_periode' AND  sp.periode_akhir BETWEEN  '$start_periode' AND '$end_periode'
          group by sp.id_program_md
      ");	
      return $query;
  }



  public function get_sales_program($sales_program)
  {
    $query = $this->db->query("
    SELECT
    CASE  when mjp.id_kategory ='1' then 'SCP' else 'DG' end as jenis_program,
    sp.id_sales_program,sp.id_program_md,sp.series_motor,sp.jenis,judul_kegiatan,
       sp.id_jenis_sales_program,
        sp.no_juklak_md,
        sp.periode_awal,
        sp.periode_akhir,
        spt.ahm_cash,             
        spt.ahm_kredit,           
        spt.md_cash,              
        spt.md_kredit,            
        spt.dealer_cash,          
        spt.dealer_kredit,        
        spt.other_cash,           
        spt.other_kredit,         
        spt.add_md_cash,          
        spt.add_dealer_cash,      
        spt.add_md_kredit,        
        spt.add_dealer_kredit,
        spt.id_tipe_kendaraan,
        tk.tipe_ahm  
        from tr_sales_program sp
        left join tr_sales_program_tipe spt on sp.id_program_md = spt.id_program_md 
        left join ms_tipe_kendaraan tk on spt.id_tipe_kendaraan = tk.id_tipe_kendaraan
        left join ms_jenis_sales_program jsp on sp.id_jenis_sales_program = jsp.id_jenis_sales_program 
      left join ms_program_subcategory mjp  on mjp.id_subcategory = jsp.id_sub_category 
    where sp.id_program_md ='$sales_program'");
    return $query;
  }

  public function get_report_finance($data)
  {

    $id_program_ahm = $data['id_program_ahm'];
    $id_program_md = $data['id_program_md'];
    $dealer = $data['id_dealer'];
    $start_periode = $data['start_periode'];

    $query = $this->db->query("   SELECT 
    sp.id_sales_program,sp.id_program_md,sp.series_motor,sp.jenis,sp.judul_kegiatan,
    spt.ahm_cash,             
    spt.ahm_kredit,           
    spt.md_cash,              
    spt.md_kredit,            
    spt.dealer_cash,          
    spt.dealer_kredit,        
    spt.other_cash,           
    spt.other_kredit,         
    spt.add_md_cash,          
    spt.add_dealer_cash,      
    spt.add_md_kredit,        
    spt.add_dealer_kredit,
    spt.id_tipe_kendaraan,
    tk.tipe_ahm  
    from tr_sales_program sp
    left join tr_sales_program_tipe spt on sp.id_program_md = spt.id_program_md 
    left join ms_tipe_kendaraan tk on spt.id_tipe_kendaraan = tk.id_tipe_kendaraan limit 1");
    return $query;

  }

  public function get_from_dealer_sub($data)
  {

    $start_periode = $data['start_periode'];
    $end_periode = $data['end_periode'];

    if (!empty($data['id_dealer']))            
    { 
      $id_dealer_set =$data['id_dealer'];
       $query_dealer_id = " AND b.id_dealer = '$id_dealer_set'";
       $query_dealer_sub = " AND spk.id_dealer = '$id_dealer_set'";
    }else
    { 
      $query_dealer_id ='';
    } 	

    if (!empty($start_periode)  &&  !empty($end_periode) ){ $query_date = "AND  tr_sales_program.periode_awal  BETWEEN '$start_periode' AND '$end_periode' AND   tr_sales_program.periode_akhir  BETWEEN '$start_periode' AND '$end_periode' "; }else{$query_date='';}


    $query = $this->db->query("SELECT 
    sum(CASE when  b.jenis_beli ='Cash'  	  then 1 else NULL end) as 'jumlah_ssu_cash',
    sum(CASE when  b.jenis_beli ='Kredit' 	  then 1 else NULL end) as 'jumlah_ssu_kredit',
    sum(CASE when  b.jenis_beli ='Cash'  	 AND	LENGTH(b.program_umum) > 1     then 1 else NULL end) as 'jumlah_umum_cash',
    sum(CASE when  b.jenis_beli ='Kredit' 	 AND	LENGTH(b.program_umum) > 1     then 1 else NULL end) as 'jumlah_umum_claim_kredit',
    sum(CASE when  b.jenis_beli ='Cash'	 	 AND    LENGTH(b.program_gabungan) > 1 then 1 else NULL end) as 'jumlah_gabungan_cash',
    sum(CASE when  b.jenis_beli ='Kredit'	 AND	LENGTH(b.program_gabungan) > 1 then 1 else NULL end) as 'jumlah_gabungan_kedit',
    sub_claim.*
    from tr_sales_order a
    join tr_spk b on a.no_spk = b.no_spk 
    left join (
    SELECT
      so.id_sales_order as sales,
      sum(case  when spk.jenis_beli ='Cash'  	  then 1 else null end) as tot_claim_cash,
      sum(case  when spk.jenis_beli ='Kredit'   then 1 else null end) as tot_claim_kredit,
      sum(case  when spk.jenis_beli ='Cash'  	 AND cd.status ='rejected' then 1 else null end) as tot_reject_cash,
      sum(case  when spk.jenis_beli ='Cash'  	 AND cd.status ='approved' then 1 else null end) as tot_approved_cash,
      sum(case  when spk.jenis_beli ='Kredit'  AND cd.status ='rejected' then 1 else null end) as tot_reject_kredit,
      sum(case  when spk.jenis_beli ='Kredit'  AND cd.status ='approved' then 1 else null end) as tot_approved_kredit
      from 
      tr_sales_order so 
      join tr_spk spk on spk.no_spk = so.no_spk 
      left join tr_claim_dealer cd on cd.id_sales_order = so.id_sales_order 
      WHERE 
      so.tgl_cetak_invoice  BETWEEN '$start_periode' AND '$end_periode'  $query_dealer_sub
    ) as sub_claim  on sub_claim.sales = a.id_sales_order 
    
    where a.tgl_cetak_invoice  BETWEEN '$start_periode' AND '$end_periode' $query_dealer_id
    ")->result();

      return $query ;

  }


  

  public function get_from_dealer_sub_initial($data)
  {

    $start_periode = $data['start_periode'];
    $end_periode = $data['end_periode'];

    if (!empty($data['id_dealer']))            
    { 
      $id_dealer_set =$data['id_dealer'];
       $query_dealer_sub = " AND spk.id_dealer = '$id_dealer_set'";
    }else
    { 
      $query_dealer_id ='';
    } 	

   

$query = $this->db->query("SELECT
espe.id_program_md as id_program_md,
espe.series_motor as series_motor,
espe.judul_kegiatan as judul_kegiatan,
sub_auto_claim.* from tr_sales_program espe 
left join 
(
SELECT 
cld.id_program_md as program,
SUM(CASE when 1=1  AND stp.id_tipe_kendaraan =spk.id_tipe_kendaraan then 1 else NULL end) as 'sama',
SUM(case when 1=1 then 1 else NULL end) as 'claim_dealer',
SUM(case when so.no_spk is not null then 1 else null AND  stp.id_tipe_kendaraan is not null  end) as 'ssu_total',

SUM(case when spk.jenis_beli = 'cash'   AND so.no_spk is not null  AND so.tgl_cetak_invoice is not null  AND  stp.id_tipe_kendaraan is not null then 1 else null  end) as 'ssu_total_cash',
SUM(case when spk.jenis_beli = 'kredit' AND so.no_spk is not null  AND so.tgl_cetak_invoice is not null  AND  stp.id_tipe_kendaraan is not null  then 1 else null  end) as 'ssu_total_kredit',

SUM(case when  spk.jenis_beli = 'kredit' AND spk.program_umum is not null 	AND  stp.id_tipe_kendaraan is not null  then 1 else null  end) as 'kredit_claim_by_dealer',
SUM(case when  spk.jenis_beli = 'kredit' AND so.no_spk is not null  		AND  stp.id_tipe_kendaraan is not null  AND cld.status = 'approved'            	   then 1 else null     end) as 'ssu_total_kredit_approve_to_dealer',
SUM(case when  spk.jenis_beli = 'kredit' AND so.no_spk is not null  		AND  stp.id_tipe_kendaraan is not null  AND cld.status_proposal = 'rejected_by_md' then 1 else null   end) as 'ssu_total_kredit_reject_to_dealer',

SUM(case when  spk.jenis_beli = 'cash' AND spk.program_umum is not null 	AND  stp.id_tipe_kendaraan is not null  then 1 else null  end) as 'cash_claim_by_dealer',
SUM(case when  spk.jenis_beli = 'cash' AND so.no_spk is not null  		    AND  stp.id_tipe_kendaraan is not null  AND cld.status = 'approved'            	   then 1 else null     end) as 'ssu_total_cash_approve_to_dealer',
SUM(case when  spk.jenis_beli = 'cash' AND so.no_spk is not null  		    AND  stp.id_tipe_kendaraan is not null  AND cld.status_proposal = 'rejected_by_md' then 1 else null   end) as 'ssu_cash_kredit_reject_to_dealer',

spk.program_umum,spk.program_gabungan
from tr_spk spk left join tr_sales_order so on so.no_spk =spk.no_spk 
left join tr_sales_program sp on sp.id_program_md = spk.program_umum
left join tr_sales_program spg on spg.id_program_md = spk.program_gabungan 
left join tr_sales_program_tipe stp on stp.id_program_md = sp.id_program_md  AND spk.id_tipe_kendaraan = stp.id_tipe_kendaraan 
left join tr_claim_dealer cld on cld.id_sales_order = so.id_sales_order 
WHERE 1=1
AND spk.tgl_spk BETWEEN  '$start_periode' AND '$end_periode' $query_dealer_sub
AND so.no_spk is not null
and cld.id_program_md is not null
group by cld.id_program_md
HAVING sama is not null
order by cld.id_program_md asc
) sub_auto_claim on sub_auto_claim.program = espe.id_program_md 
 WHERE  espe.periode_awal   BETWEEN  '$start_periode' AND '$end_periode' AND  espe.periode_akhir  BETWEEN  '$start_periode' AND '$end_periode'
")->result();


      return $query ;

  }


  
  public function get_sales_program_initial($data)
  {

    $start_periode = $data['start_periode'];
    $end_periode = $data['end_periode'];

    $query = $this->db->query("SELECT sp.id_program_md FROM tr_sales_program  sp  WHERE  sp.periode_awal BETWEEN  '$start_periode' AND '$end_periode' AND  sp.periode_akhir BETWEEN  '$start_periode' AND '$end_periode'")->result();
    return $query;

  }




  

  

}