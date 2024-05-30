<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_auto_claim_payment extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function dealer_kode_area($kode_area = NULL)
  {
    $group ="";
    // $group .= "GROUP BY left(md.kode_dealer_md,5)";
    $query = $this->db->query(" SELECT md.id_dealer,md.nama_dealer,md.kode_dealer_md from ms_dealer md  
    join ms_kelurahan kel on kel.id_kelurahan = md.id_kelurahan 
    join ms_kecamatan kec on kec.id_kecamatan = kel.id_kecamatan 
    join ms_kabupaten kab on kec.id_kabupaten  = kab.id_kabupaten 
    WHERE kab.kode_samsat  = '$kode_area' and md.h1 ='1' and md.active ='1'
    $group
    ");	
      return $query;
  }

public function syarat_ketentuan($filter,$jenis_beli)
{

  $id_dealer     = $filter['id_dealer'];
  $id_program_md = $filter['id_program_md'];
  $kode_dealer_md = $filter['kode_dealer_md'];
  $kode_samsat = $filter['kode_samsat'];
  $join  = '';
  $group = '';

  $where = "WHERE 1=1 ";
  if(isset($filter['kode_samsat'])){
    if($filter['kode_samsat'] !==''){
       $where .="AND kab.kode_samsat  = '".$filter['kode_samsat']."' and md.h1 ='1' and md.active ='1' ";
       $join .="left join ms_dealer md on spk.id_dealer  = md.id_dealer 
            left join  ms_kelurahan kel on kel.id_kelurahan = md.id_kelurahan 
            join ms_kecamatan kec on kec.id_kecamatan = kel.id_kecamatan 
            join ms_kabupaten kab on kec.id_kabupaten  = kab.id_kabupaten 
      "; 
      // $where .=" AND md.id_dealer ='46'";
      $group .=NULL;
    }
  }else{
    $join .=" left join ms_dealer md on md.id_dealer =spk.id_dealer";
    $where .=" AND md.kode_dealer_md ='$kode_dealer_md' ";
    $group .="GROUP by cd.id_dealer,cs.id_syarat_ketentuan";
  }
 
  $string ="SELECT 
  sum(case when cs.checklist_reject_md = 0 then 0 else 1 end) as status_reject
  from tr_claim_dealer cd 
  left join tr_sales_order so on so.id_sales_order = cd.id_sales_order 
  join tr_spk spk on spk.no_spk = so.no_spk
  left join tr_claim_dealer_syarat cs on cs.id_claim = cd.id_claim 
  join tr_sales_program_syarat spsg on spsg.id=cs.id_syarat_ketentuan 
  left join ms_alasan_reject ar on ar.id_alasan_reject = cs.alasan_reject
  $join
  $where 
  AND cd.status ='rejected' 
  AND cd.id_program_md ='$id_program_md'
  AND spk.jenis_beli ='$jenis_beli'
  AND spsg.id = sps.id
  $group
  ORDER by cd.id_sales_order,spsg.syarat_ketentuan asc";

  $query= $this->db->query("SELECT sps.syarat_ketentuan,
  ($string) as jumlah 
  from tr_sales_program sp left join tr_sales_program_syarat sps on sp.id_program_md = sps.id_program_md 
  where sp.id_program_md ='$id_program_md'
  group by sps.id  order by sps.syarat_ketentuan asc")->result();
  return $query;
}

  
  public function get_from_dealers_group($data)
  {
          $start_periode = $data['start_periode'];
          $end_periode   = $data['end_periode'];
    
          if (!empty($data['dealer_group']))            
          { 
            $dealer = $data['dealer_group'];
            $query_dealer = " AND ms_group_dealer.id_group_dealer = '$dealer'";

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


  public function get_sales_program($sales_program=null,$syarat=null)
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

  public function get_sales_program_initial($data)
  {
    $start_periode = $data['start_periode'];
    $end_periode = $data['end_periode'];

    $query = $this->db->query("SELECT sp.id_program_md,sp.series_motor as series, sp.judul_kegiatan as kegiatan 
    FROM tr_sales_program sp  WHERE  sp.periode_awal BETWEEN  '$start_periode' AND '$end_periode' AND  sp.periode_akhir BETWEEN  '$start_periode' AND '$end_periode'
    AND sp.id_jenis_sales_program != 'SP-005'
    AND  sp.jenis !='dealer'
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

    // if(isset($dealer_wilayah)!==0){
    //   $dealer_wilayah   = $data['dealer_wilayah'];
    //   $hasil_array_dealer = $dealer_wilayah;
    //   $string_array_dealer = "'" .implode("','", $hasil_array_dealer) . "'";
    //   $where .= " AND spk.id_dealer in ($string_array_dealer)";
    // }


    if(isset($dealer)!==NULL)
    {
      $where .= " AND spk.id_dealer = '$dealer'";
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



  public function credit_cash_set($data)
  {

    $where = "WHERE 1=1 ";
    $periode_awal        =  $data['start_periode'];
    $periode_akhir       =  $data['end_periode'];

    $dealer_wilayah   = $data['dealer_wilayah'];
    $hasil_array_dealer = $dealer_wilayah;
    $string_array_dealer = "'" .implode("','", $hasil_array_dealer) . "'";
    $where .= " AND dl.kode_dealer_md in ($string_array_dealer)";

    $tipe_kendaraan =  $data['tipe_kendaraan'];
    $tipeKendaraanArray = explode(',', $tipe_kendaraan->tipe_kendaraan);
    $tipeKendaraanString = "'" . implode("','", $tipeKendaraanArray) . "'";

        $query = $this->db->query("SELECT
				SUM(CASE when spk.jenis_beli ='cash'    AND (LENGTH(spk.program_umum) < 2  or spk.program_umum is null)  AND (LENGTH(spk.program_gabungan) < 2 or spk.program_gabungan is null)  then 1 else 0 end) as 'cash',
				SUM(CASE when spk.jenis_beli ='kredit'  AND (LENGTH(spk.program_umum) < 2  or spk.program_umum is null)  AND (LENGTH(spk.program_gabungan) < 2 or spk.program_gabungan is null)  then 1 else 0 end) as 'kredit'
					from tr_spk spk 
				  join tr_sales_order so on so.no_spk = spk.no_spk
          LEFT JOIN ms_dealer dl on spk.id_dealer = dl.id_dealer
				$where
				AND so.tgl_cetak_invoice  BETWEEN '$periode_awal' AND '$periode_akhir'
				AND spk.id_tipe_kendaraan in ($tipeKendaraanString)
				");
        return  $query ;
  }



  
  public function get_syarat_reject($data,$jenis)
  {
    $where = "";
    if ($jenis == 'kredit'){
      $where .= " AND spk.jenis_beli ='kredit'";
    }else if($jenis == 'cash') {
      $where .= " AND spk.jenis_beli ='cash'";
    }

    $program_md_set = $data['program_md_set'];
    $dealer_wilayah   = $data['dealer_wilayah'];
    $hasil_array_dealer = $dealer_wilayah;
    $string_array_dealer = "'" . implode("','", $hasil_array_dealer) . "'";

    $query_syarat_kredit = $this->db->query("SELECT 
    spp.syarat_ketentuan,
    sub_query.alasan_reject,
    sum(case when sub_query.status_reject is null then 0 else  sub_query.status_reject end) as status_reject
    from tr_sales_program_syarat spp
    left join( 
    SELECT 
        sum(case when cs.checklist_reject_md = 0 then 0 else 1 end) as status_reject,
        ar.alasan_reject,
        sps.syarat_ketentuan  from tr_claim_dealer cd 
        left join tr_sales_order so on so.id_sales_order = cd.id_sales_order 
        join tr_spk spk on spk.no_spk = so.no_spk
        join ms_dealer dl on dl.id_dealer = spk.id_dealer
        left join tr_claim_dealer_syarat cs on cs.id_claim = cd.id_claim 
        join tr_sales_program_syarat sps on sps.id=cs.id_syarat_ketentuan 
        left join ms_alasan_reject ar on ar.id_alasan_reject = cs.alasan_reject 
        WHERE cd.status ='rejected'
        AND cd.id_program_md = '$program_md_set'
        AND dl.kode_dealer_md in ($string_array_dealer)
        $where
        GROUP by
        cs.id_syarat_ketentuan 
        ORDER by sps.syarat_ketentuan asc 
        ) sub_query on sub_query.syarat_ketentuan = spp.syarat_ketentuan
        WHERE id_program_md = '$program_md_set'
        group by spp.syarat_ketentuan
        ORDER by spp.syarat_ketentuan asc
        ");

    return $query_syarat_kredit;
  }

  public function get_segment_kendaraan_finance($data)
  {

    $where = '';

    if (isset($data)!== NULL){
      $where .=" AND tk.id_kategori = '$data' ";
    }

        $query= $this->db->query(" SELECT tk.id_kategori,
        dl.id_dealer as id_dealer_set, dl.kode_dealer_md as kode_dealer_ahm_set, dl.nama_dealer as nama_dealer_set,
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
        SUM(case when  spk.jenis_beli = 'cash' AND so.no_spk is not null  		    AND  stp.id_tipe_kendaraan is not null  AND cld.status_proposal = 'rejected_by_md' then 1 else 0   end) as 'ssu_cash_kredit_reject_to_dealer'
        from tr_spk spk left join tr_sales_order so on so.no_spk =spk.no_spk 
        join ms_tipe_kendaraan tk ON spk.id_tipe_kendaraan = tk.id_tipe_kendaraan 
        JOIN ms_dealer dl on spk.id_dealer = dl.id_dealer 
        left join tr_sales_program sp on sp.id_program_md = spk.program_umum
        left join tr_sales_program spg on spg.id_program_md = spk.program_gabungan
        left join tr_sales_program_tipe stp on stp.id_program_md = sp.id_program_md  AND spk.id_tipe_kendaraan = stp.id_tipe_kendaraan 
        left join tr_claim_dealer cld on cld.id_sales_order = so.id_sales_order 
        left join tr_pengajuan_claim_to_ahm pca on cld.id_claim = pca.id_claim_dealer
        WHERE 1=1
        AND so.tgl_cetak_invoice BETWEEN '$start_periode' AND '$end_periode'
        AND so.no_spk is not null
        AND spk.id_dealer ='103'
        and cld.id_program_md is not null
        $where
        group by spk.id_dealer, tk.id_kategori
        HAVING sama is not null
        order by cld.id_program_md asc
        ");
        return $query;
  }

    
  public function get_dealer($filter)
  {
    $start_periode = $filter['start_periode'];
    $end_periode   = $filter['end_periode'];
    $id_program_md = $filter['id_program_md'];
    $tipe_kendaraan= $filter['tipe_kendaraan'];
    $where = "WHERE 1=1";
    $join ="";

    $group ="";
    // $group .="GROUP BY md.kode_dealer_md";

    if(isset($filter['dealer_group'])){
      if($filter['dealer_group'] !==''){
        //  $where .=" AND md.kode_dealer_ahm ='".$filter['dealer_group']."'";
         $join .="left join ms_dealer md on md.id_dealer=so.id_dealer"; 
      }
    }


    if(isset($filter['kode_administrasi'])){
      if($filter['kode_administrasi']!==''){
         $where .=" AND kab.kode_samsat ='".$filter['kode_administrasi']."'";
         $join .="left join ms_dealer md on md.id_dealer=so.id_dealer
         left join ms_kelurahan kel on kel.id_kelurahan = md.id_kelurahan 
         left join ms_kecamatan kec on kel.id_kecamatan = kec.id_kecamatan 
         left join ms_kabupaten kab on kab.id_kabupaten = kec.id_kabupaten"; 
        //  $where .=" AND md.id_dealer ='46'";

      }
    }

    if(isset($filter['id_dealer'])){
      if($filter['id_dealer']!==''){
         $where .=" AND md.id_dealer ='".$filter['id_dealer']."'";
         $join .="left join ms_dealer md on md.id_dealer=so.id_dealer"; 
      }
    }

    $query = $this->db->query("SELECT
		sum(CASE when 1=1 then 1 else null end) as tot_ssu,
		sum(CASE when spk.jenis_beli ='kredit' then 1 else null end) as tot_ssu_kredit,
		sum(CASE when spk.jenis_beli ='cash' then 1 else null end) as tot_ssu_cash,
		sum((select sum(1) from tr_claim_dealer cd where cd.id_sales_order = so.id_sales_order and id_program_md ='$id_program_md'  and spk.jenis_beli='kredit' )) as tot_claim_kredit,
		sum((select sum(1) from tr_claim_dealer cd where cd.id_sales_order = so.id_sales_order and cd.status ='approved' and id_program_md ='$id_program_md' and spk.jenis_beli='kredit' )) as tot_approved_kredit,
		sum((select sum(1) from tr_claim_dealer cd where cd.id_sales_order = so.id_sales_order and cd.status ='rejected' and id_program_md ='$id_program_md' and spk.jenis_beli='kredit' )) as tot_rejected_kredit,
		sum((select sum(1) from tr_claim_dealer cd where cd.id_sales_order = so.id_sales_order and id_program_md ='$id_program_md' and spk.jenis_beli='cash' )) as tot_claim_cash,
		sum((select sum(1) from tr_claim_dealer cd where cd.id_sales_order = so.id_sales_order and cd.status ='approved' and id_program_md ='$id_program_md' and spk.jenis_beli='cash' )) as tot_approved_cash,
		sum((select sum(1) from tr_claim_dealer cd where cd.id_sales_order = so.id_sales_order and cd.status ='rejected' and id_program_md ='$id_program_md' and spk.jenis_beli='cash' )) as tot_rejected_cash,
		spk.jenis_beli
		from tr_sales_order so join tr_spk spk on spk.no_spk = so.no_spk 
    $join
    $where 
		and so.tgl_cetak_invoice  BETWEEN '$start_periode' and '$end_periode'
		and spk.id_tipe_kendaraan
		in 
		($tipe_kendaraan)
    $group 
    ");
    return $query;
  }

      
  public function get_dealer_gc($filter)
  {
    $start_periode = $filter['start_periode'];
    $end_periode   = $filter['end_periode'];
    $id_program_md = $filter['id_program_md'];
    $tipe_kendaraan = $filter['tipe_kendaraan'];

      $where = "WHERE 1=1";
      $group = "";
      $join ="";

      if(isset($filter['dealer_group'])){
        if($filter['dealer_group'] !==''){
           $where .=" AND md.kode_dealer_ahm ='".$filter['dealer_group']."'";
           $group .="group by md.kode_dealer_ahm";
        }
      }
      
      if(isset($filter['from_md'])){
        if($filter['from_md'] !==''){
          $dealerin = $filter['id_dealer'];
          $where .=" AND md.id_dealer in ($dealerin)";
          
          $group .="group by md.id_dealer";
        }
      }

      if(isset($filter['id_dealer'])){
        if($filter['id_dealer']!==''){
          $join .=" join ms_dealer md on md.id_dealer=so_gc.id_dealer "; 
           $where .=" AND md.id_dealer ='".$filter['id_dealer']."'";
           $group .="group by md.id_dealer";
        }
      }

      if(isset($filter['kode_administrasi'])){
        if($filter['kode_administrasi']!==''){
          $where .=" AND kab.kode_samsat ='".$filter['kode_administrasi']."'";
          $join .="left join ms_dealer md on md.id_dealer=so_gc.id_dealer
          left join ms_kelurahan kel on kel.id_kelurahan = md.id_kelurahan 
          left join ms_kecamatan kec on kel.id_kecamatan = kec.id_kecamatan 
          left join ms_kabupaten kab on kab.id_kabupaten = kec.id_kabupaten "; 
          // $where .=" AND md.id_dealer ='46'";
          //  $where .=" AND md.id_dealer ='".$filter['id_dealer']."'";
          //  $group .="group by md.id_dealer";
        }
      }
  
      $query = $this->db->query("SELECT
      so_gc.id_dealer,
      sum(case when spk_gc.jenis_beli ='cash' then 1 else 0 end)as tot_cash_gc, 
      sum(case when spk_gc.jenis_beli ='kredit' then 1 else 0 end) as tot_kredit_gc 
      from tr_sales_order_gc so_gc  join tr_sales_order_gc_nosin gc_nosin
      on so_gc.id_sales_order_gc  = gc_nosin.id_sales_order_gc 
      $join
      left join tr_scan_barcode barcode on barcode.no_mesin = gc_nosin.no_mesin
      left join tr_spk_gc spk_gc on so_gc.no_spk_gc =spk_gc.no_spk_gc 
      $where 
      AND so_gc.tgl_cetak_invoice  BETWEEN '$start_periode' and '$end_periode'  
      and barcode.tipe_motor in ($tipe_kendaraan)
      $group 
      ");
      return $query;
  }

      
  public function get_tipe_kendaraan($filter)
  {
    $query = $this->db->query("SELECT id_tipe_kendaraan from tr_sales_program_tipe where id_program_md ='$filter'");

      $array_row=[];
      foreach ($query->result() as $element ) {
      $array_row[] = $element->id_tipe_kendaraan;
      }

      $hasil_array_tipe = $array_row;
      $string_array_tipe = "'" . implode("','", $hasil_array_tipe) . "'";

    return $string_array_tipe;
  }

}