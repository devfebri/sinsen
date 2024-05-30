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
    

    $id_sales_program = $data['id_program_md'];
    $query_periode = $this->db->query("select periode_awal,periode_akhir from tr_sales_program where id_program_md ='$id_sales_program'")->row();

	  $query_tipe = $this->db->query("select id_tipe_kendaraan from tr_sales_program_tipe WHERE id_program_md ='$id_sales_program'")->result();

    $array_row=[];
    foreach ($query_tipe as $element ) {
      $array_row[] = $element->id_tipe_kendaraan;
    }

    $hasil_array_tipe = $array_row;
    $string_array_tipe = "'" . implode("','", $hasil_array_tipe) . "'";

    $dealer = array(
    '44','84','103','105','82','97','37','106','39','104','102','13','2','51',
    '45','66','22','3','43','25','40','100','101','46','85','18','93','94','95',
    '78','65','80','47','90','96','77','1','4','8','41','70','98','74','71','81',
    '83','86','107');

    $hasil_array_dealer = $dealer;
    $string_array_dealer = "'" . implode("','", $hasil_array_dealer) . "'";

      $query = $this->db->query("SELECT * 
      from ms_dealer delaer s
left join (
      SELECT
      dl.id_dealer, dl.kode_dealer_md as kode_dealer_ahm, dl.nama_dealer as nama_dealer,
      SUM(CASE when 1=1  AND stp.id_tipe_kendaraan =spk.id_tipe_kendaraan then 1 else NULL end) as 'sama',
      SUM(case when 1=1 then 1 else NULL end) as 'claim_dealer',
      SUM(case when so.no_spk is not null then 1 else null AND  stp.id_tipe_kendaraan is not null  end) as 'ssu_total',

      SUM(case when spk.jenis_beli = 'cash'   AND so.no_spk is not null  AND so.tgl_cetak_invoice is not null  AND  stp.id_tipe_kendaraan is not null then 1 else 0  end) as 'ssu_total_cash',
      SUM(case when spk.jenis_beli = 'kredit' AND so.no_spk is not null  AND so.tgl_cetak_invoice is not null  AND  stp.id_tipe_kendaraan is not null  then 1 else 0  end) as 'ssu_total_kredit',

      SUM(case when  spk.jenis_beli = 'kredit' AND spk.program_umum is not null 	AND  stp.id_tipe_kendaraan is not null  then 1 else 0  end) as 'kredit_claim_by_dealer',
      SUM(case when  spk.jenis_beli = 'kredit' AND so.no_spk is not null  		AND  stp.id_tipe_kendaraan is not null  AND cld.status = 'approved'  AND pca.id_claim_dealer is not null AND  pca.statusVerifikasi1 ='1'	   then 1 else 0     end) as 'ssu_total_kredit_approve_to_by_ahm',
      SUM(case when  spk.jenis_beli = 'kredit' AND so.no_spk is not null  		AND  stp.id_tipe_kendaraan is not null  AND cld.status = 'approved'            	   then 1 else 0     end) as 'ssu_total_kredit_approve_to_dealer',
      SUM(case when  spk.jenis_beli = 'kredit' AND so.no_spk is not null  		AND  stp.id_tipe_kendaraan is not null  AND cld.status_proposal = 'rejected_by_md' then 1 else 0   end) as 'ssu_total_kredit_reject_to_dealer',

      SUM(case when  spk.jenis_beli = 'cash' AND spk.program_umum is not null 	AND  stp.id_tipe_kendaraan is not null  then 1 else 0  end) as 'cash_claim_by_dealer',
      SUM(case when  spk.jenis_beli = 'cash' AND so.no_spk is not null  		    AND  stp.id_tipe_kendaraan is not null  AND cld.status = 'approved'  AND pca.id_claim_dealer is not null	 AND  pca.statusVerifikasi1 ='1'	  then 1 else 0     end) as 'ssu_total_cash_approve_to_by_ahm',
      SUM(case when  spk.jenis_beli = 'cash' AND so.no_spk is not null  		    AND  stp.id_tipe_kendaraan is not null  AND cld.status = 'approved'            	   then 1 else 0     end) as 'ssu_total_cash_approve_to_dealer',
      SUM(case when  spk.jenis_beli = 'cash' AND so.no_spk is not null  		    AND  stp.id_tipe_kendaraan is not null  AND cld.status_proposal = 'rejected_by_md' then 1 else 0   end) as 'ssu_cash_kredit_reject_to_dealer',

      spk.program_umum,spk.program_gabungan
      from tr_spk spk left join tr_sales_order so on so.no_spk =spk.no_spk 
      JOIN ms_dealer dl on spk.id_dealer = dl.id_dealer 
      left join tr_sales_program sp on sp.id_program_md = spk.program_umum
      left join tr_sales_program spg on spg.id_program_md = spk.program_gabungan
      left join tr_sales_program_tipe stp on stp.id_program_md = sp.id_program_md  AND spk.id_tipe_kendaraan = stp.id_tipe_kendaraan 
      left join tr_claim_dealer cld on cld.id_sales_order = so.id_sales_order 
      left join tr_pengajuan_claim_to_ahm pca on cld.id_claim = pca.id_claim_dealer
      WHERE 1=1
      AND so.tgl_cetak_invoice BETWEEN '$query_periode->periode_awal' AND '$query_periode->periode_akhir'
      AND so.no_spk is not null
      and cld.id_program_md is not null
      and cld.id_program_md= '$id_sales_program'
      and spk.id_tipe_kendaraan in ($string_array_tipe)
      AND spk.id_dealer in ($string_array_dealer)
      group by spk.id_dealer
      HAVING sama is not null
      order by cld.id_program_md asc
      ) as sub_sales_program on  sub_sales_program.id_dealer= delaer.id_dealer
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

  

  

  public function get_sales_program($sales_program,$syarat)
  {
    $limit ='' ;
    $group ='' ;
    $select ='';
    $join ='';

    if($syarat == 1) {
      $join  .= "left join tr_sales_program_syarat sps on sp.id_program_md = sps.id_program_md";
      $limit  .="";
      $group  .= "group by sps.syarat_ketentuan";
      $select .= "sps.syarat_ketentuan ,";
    }else{
      $limit .="limit 1";
    }

    $query = $this->db->query("
    SELECT
    $select
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
      $join
    where sp.id_program_md ='$sales_program'
    $group 
    $limit
    ");
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
    }
    else{ 
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
    SUM(case when  spk.jenis_beli = 'cash' AND so.no_spk is not null  		    AND  stp.id_tipe_kendaraan is not null  AND cld.status_proposal = 'rejected_by_md' then 1 else null   end) as 'ssu_cash_reject_to_dealer',

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
    AND espe.id_jenis_sales_program != 'SP-005'
    AND  espe.jenis !='dealer'
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

  public function get_from_md_set($data)
  {
      $program        =  $data['program_md_set'];
      
      $start_periode    = $data['start_periode'];
      $end_periode      = $data['end_periode'];
      
      $tipe_kendaraan =  $data['tipe_kendaraan'];
      $tipeKendaraanArray = explode(',', $tipe_kendaraan->tipe_kendaraan);
      $tipeKendaraanString = "'" . implode("','", $tipeKendaraanArray) . "'";

      $dealer_wilayah   = $data['dealer_wilayah'];
      $hasil_array_dealer = $dealer_wilayah;
      $string_array_dealer = "'" . implode("','", $hasil_array_dealer) . "'";

      $query = $this->db->query("SELECT
      dl.id_dealer, dl.kode_dealer_md as kode_dealer_ahm, dl.nama_dealer as nama_dealer,
      SUM(CASE when 1=1  AND stp.id_tipe_kendaraan =spk.id_tipe_kendaraan then 1 else NULL end) as 'sama',
      SUM(case when 1=1 then 1 else NULL end) as 'claim_dealer',
      SUM(case when so.no_spk is not null then 1 else null AND  stp.id_tipe_kendaraan is not null  end) as 'ssu_total',

      SUM(case when spk.jenis_beli = 'cash'   AND so.no_spk is not null  AND so.tgl_cetak_invoice is not null  AND  stp.id_tipe_kendaraan is not null then 1 else 0  end) as 'ssu_total_cash',
      SUM(case when spk.jenis_beli = 'kredit' AND so.no_spk is not null  AND so.tgl_cetak_invoice is not null  AND  stp.id_tipe_kendaraan is not null  then 1 else 0  end) as 'ssu_total_kredit',

      SUM(case when  spk.jenis_beli = 'kredit' AND spk.program_umum is not null 	AND  stp.id_tipe_kendaraan is not null  then 1 else 0  end) as 'kredit_claim_by_dealer',
      SUM(case when  spk.jenis_beli = 'kredit' AND so.no_spk is not null  		AND  stp.id_tipe_kendaraan is not null  AND cld.status = 'approved'  AND pca.id_claim_dealer is not null AND  pca.statusVerifikasi1 ='1'	   then 1 else 0     end) as 'ssu_total_kredit_approve_to_by_ahm',
      SUM(case when  spk.jenis_beli = 'kredit' AND so.no_spk is not null  		AND  stp.id_tipe_kendaraan is not null  AND cld.status = 'approved'            	   then 1 else 0     end) as 'ssu_total_kredit_approve_to_dealer',
      SUM(case when  spk.jenis_beli = 'kredit' AND so.no_spk is not null  		AND  stp.id_tipe_kendaraan is not null  AND cld.status_proposal = 'rejected_by_md' then 1 else 0   end) as 'ssu_total_kredit_reject_to_dealer',

      SUM(case when  spk.jenis_beli = 'cash' AND spk.program_umum is not null 	AND  stp.id_tipe_kendaraan is not null  then 1 else 0  end) as 'cash_claim_by_dealer',
      SUM(case when  spk.jenis_beli = 'cash' AND so.no_spk is not null  		    AND  stp.id_tipe_kendaraan is not null  AND cld.status = 'approved'  AND pca.id_claim_dealer is not null	 AND  pca.statusVerifikasi1 ='1'	  then 1 else 0     end) as 'ssu_total_cash_approve_to_by_ahm',
      SUM(case when  spk.jenis_beli = 'cash' AND so.no_spk is not null  		    AND  stp.id_tipe_kendaraan is not null  AND cld.status = 'approved'            	   then 1 else 0     end) as 'ssu_total_cash_approve_to_dealer',
      SUM(case when  spk.jenis_beli = 'cash' AND so.no_spk is not null  		    AND  stp.id_tipe_kendaraan is not null  AND cld.status_proposal = 'rejected_by_md' then 1 else 0   end) as 'ssu_cash_kredit_reject_to_dealer',

      spk.program_umum,spk.program_gabungan
      from tr_sales_order so left join 
      tr_spk spk on so.no_spk =spk.no_spk 
      JOIN ms_dealer dl on spk.id_dealer = dl.id_dealer 
      left join tr_sales_program sp on sp.id_program_md = spk.program_umum
      left join tr_sales_program spg on spg.id_program_md = spk.program_gabungan
      left join tr_sales_program_tipe stp on stp.id_program_md = sp.id_program_md  AND spk.id_tipe_kendaraan = stp.id_tipe_kendaraan 
      left join tr_claim_dealer cld on cld.id_sales_order = so.id_sales_order 
      left join tr_pengajuan_claim_to_ahm pca on cld.id_claim = pca.id_claim_dealer
      WHERE 1=1
      AND so.tgl_cetak_invoice BETWEEN '$start_periode' AND '$end_periode'
      AND so.no_spk is not null
      and cld.id_program_md is not null
      and cld.id_program_md= '$program'
      and spk.id_tipe_kendaraan in ($tipeKendaraanString)
      AND dl.kode_dealer_md in ($string_array_dealer)
      HAVING sama is not null
      order by cld.id_program_md asc
      ");	

      return $query;
  }



  public function credit_cash($data)
  {
    $where = "WHERE 1=1 ";
    $periode_awal        =  $data['start_periode'];
    $periode_akhir       =  $data['end_periode'];
    $dealer              =  $data['id_dealer_set'];
    $dealer_wilayah      =  $data['dealer_wilayah'];

    if(isset($dealer_wilayah)!==0){
      $dealer_wilayah   = $data['dealer_wilayah'];
      $hasil_array_dealer = $dealer_wilayah;
      $string_array_dealer = "'" . implode("','", $hasil_array_dealer) . "'";
      $where .= "AND spk.id_dealers in ('$string_array_dealer')";
    }

    if(isset($dealer)!==NULL)
    {
      $where .= "AND spk.id_dealer = '$dealer'";
    }
    
    $tipe_kendaraan =  $data['tipe_kendaraan'];
    $tipeKendaraanArray = explode(',', $tipe_kendaraan->tipe_kendaraan);
    $tipeKendaraanString = "'" . implode("','", $tipeKendaraanArray) . "'";

        $query = $this->db->query("SELECT
				SUM(CASE when spk.jenis_beli ='cash'    AND (LENGTH(spk.program_umum) < 2  or spk.program_umum is null)  AND (LENGTH(spk.program_gabungan) < 2 or spk.program_gabungan is null)  then 1 else 0 end) as 'cash',
				SUM(CASE when spk.jenis_beli ='kredit'  AND (LENGTH(spk.program_umum) < 2  or spk.program_umum is null)  AND (LENGTH(spk.program_gabungan) < 2 or spk.program_gabungan is null)  then 1 else 0 end) as 'kredit'
					from tr_spk spk 
				join tr_sales_order so on so.no_spk = spk.no_spk
				$where
				AND so.tgl_cetak_invoice  BETWEEN '$periode_awal' AND '$periode_akhir'
				AND spk.id_tipe_kendaraan in ($tipeKendaraanString)
				");

        return  $query ;

  }


  public function get_id_kendaraan_set($data)
  {
      $query = $this->db->query("
      SELECT
        GROUP_CONCAT(DISTINCT CONCAT(kendaraan)) AS tipe_kendaraan
      FROM tr_sales_program sapr
      LEFT JOIN (
        SELECT sp.id_program_md, spt.id_tipe_kendaraan AS kendaraan
        FROM tr_sales_program sp
        LEFT JOIN tr_sales_program_tipe spt ON sp.id_program_md = spt.id_program_md
      ) AS sub_sp ON sub_sp.id_program_md = sapr.id_program_md
      WHERE sapr.id_program_md ='$data'");
      return $query;
  }
  

}