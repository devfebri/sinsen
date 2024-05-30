<?php
    header("Content-type: application/vnd-ms-excel");
    $file_name = remove_space($title, '_') . '.xls';
    header("Content-Disposition: attachment; filename=$file_name");
    header("Pragma: no-cache");
    header("Expires: 0");
?>

<table>
      <tr>
        <td colspan="9"><?= kop_surat_dealer($id_dealer); ?></td>
      </tr>
    </table>
<div style="text-align: center;font-size: 13pt"><b><?= $title ?></b></div>
<div style="text-align: center;font-size: 11pt">Periode : <?= date_dmy($start_date) . ' - ' . date_dmy($end_date) ?></div>
<hr>

<table class="table table-bordered" border=1>
    <tr>
        <th>No</th>
        <th>ID Pit</th>
        <th width="150px;">Nama Mekanik</th>
        <th>Total UE</th>
        <th>CS</th>
        <th>Total Jasa</th>
        <th>Total Jasa Service Lengkap</th>
        <th>Diskon Jasa</th>
        <th>Pendapatan Part</th>
        <th>Pendapatan Oil</th>
    </tr>
    <?php 
    $sum_total_ue =array();
    $sum_total_cs =array();
    $sum_total_jasa =array();
    $sum_total_jasa_cs =array();
    $sum_total_diskon_jasa =array();
    $sum_total_part =array();
    $sum_total_oil =array();
    $no=1;
    $dealer = $id_dealer;

    if($dealer == 'all'){
    ?>
    <tr>
      <td colspan = "10" style="text-align:center">Silahkan Pilih Dealer/AHASS terlebih dahulu. </td>
    </tr>
    <?php
    }else{

    foreach($pit as $rows){
        $total_ue = $this->db->query("SELECT COALESCE(COUNT(wo.id_work_order)) AS total_ue from tr_h2_wo_dealer wo where wo.id_karyawan_dealer in($rows->id_on_pit) and wo.id_dealer='$dealer' and wo.status='Closed' and left(wo.closed_at,10) >= '$start_date' and  left(wo.closed_at,10) <= '$end_date'")->row();
        
        $total_cs = $this->db->query("SELECT COALESCE(COUNT(wo.id_work_order)) AS total_cs from tr_h2_wo_dealer wo join tr_h2_sa_form sa 
        on wo.id_sa_form =sa.id_sa_form join tr_h2_wo_dealer_pekerjaan wop on wo.id_work_order =wop.id_work_order 
        join ms_h2_jasa jasa on wop.id_jasa =jasa.id_jasa 
        where wo.id_karyawan_dealer in($rows->id_on_pit) and wo.status='Closed' 
        and jasa.id_type ='CS'
        and wo.id_dealer='$dealer' and left(wo.closed_at,10) >= '$start_date' and  left(wo.closed_at,10) <= '$end_date'")->row();
        
        
        
        $total_jasa = $this->db->query("SELECT COALESCE(SUM(wop.harga)) AS total_jasa from tr_h2_wo_dealer wo join tr_h2_sa_form sa 
        on wo.id_sa_form =sa.id_sa_form join tr_h2_wo_dealer_pekerjaan wop on wo.id_work_order =wop.id_work_order 
        where wo.id_karyawan_dealer in($rows->id_on_pit) and wo.status='Closed' 
        and wo.id_dealer='$dealer'
        and left(wo.closed_at,10) >= '$start_date' and left(wo.closed_at,10) <= '$end_date' and wop.pekerjaan_batal =0")->row();
        
        $diskon_jasa = $this->db->query("select wo.id_work_order,wop.id_jasa,COALESCE(SUM(CASE WHEN (wop.disc_percentage = 0 or wop.disc_percentage is NULL) then wop.diskon_value else wop.harga * wop.disc_percentage / 100 end)
        )as diskon_jasa from tr_h2_wo_dealer wo join tr_h2_wo_dealer_pekerjaan wop on wo.id_work_order = wop.id_work_order join ms_h2_jasa jasa on wop.id_jasa =jasa.id_jasa 
        where wo.id_karyawan_dealer in($rows->id_on_pit) and wo.id_dealer='$dealer' and left(wo.closed_at,10) >= '$start_date' and  left(wo.closed_at,10) <= '$end_date' and wop.pekerjaan_batal='0'")->row();

        if($diskon_jasa->diskon_jasa != '' || $diskon_jasa->diskon_jasa != null){
          $total_jasa_diskon = $total_jasa->total_jasa - $diskon_jasa->diskon_jasa;
        }else{
          $total_jasa_diskon = $total_jasa->total_jasa;
        }
        
        $total_jasa_cs = $this->db->query("SELECT COALESCE(SUM(wop.harga)) AS jasa, ( COALESCE(SUM(wop.harga))-COALESCE(SUM(CASE WHEN (wop.disc_percentage = 0 or wop.disc_percentage is NULL) then wop.diskon_value else wop.harga * wop.disc_percentage / 100 end)
        )) as total_jasa 
        from tr_h2_wo_dealer wo join tr_h2_sa_form sa 
        on wo.id_sa_form =sa.id_sa_form join tr_h2_wo_dealer_pekerjaan wop on wo.id_work_order =wop.id_work_order 
        join ms_h2_jasa jasa on wop.id_jasa =jasa.id_jasa 
        where wo.id_karyawan_dealer in($rows->id_on_pit) and wo.status='Closed' 
        and jasa.id_type ='CS'
        and wo.id_dealer='$dealer'
        and wop.pekerjaan_batal =0 and left(wo.closed_at,10) >= '$start_date' and  left(wo.closed_at,10) <= '$end_date'")->row();
        
        
        
        $total_part = $this->db->query("select nsc.no_nsc,nspart.id_part,nsc.id_referensi,wo.id_work_order,wo.id_karyawan_dealer,nspart.harga_beli,
            COALESCE(SUM(nspart.harga_beli * nspart.qty) - (CASE WHEN nspart.tipe_diskon='Percentage' 
            THEN nspart.harga_beli*(nspart.diskon_value/100)* nspart.qty ELSE nspart.diskon_value END )) as total_part
            from tr_h23_nsc nsc 
            join tr_h23_nsc_parts nspart on nspart.no_nsc=nsc.no_nsc
            join tr_h2_wo_dealer wo on wo.id_work_order = nsc.id_referensi 
            join ms_part part on part.id_part_int = nspart.id_part_int 
            where nsc.referensi ='work_order'
            and part.kelompok_part not in('FED OIL','OIL','GMO')
            AND wo.id_dealer ='$dealer'
            and wo.id_karyawan_dealer in($rows->id_on_pit)
            and left(wo.closed_at,10) >= '$start_date' and left(wo.closed_at,10) <= '$end_date'
        
        ")->row();
        
        // 24/10/23
        /*$total_oil = $this->db->query("select nsc.no_nsc,nspart.id_part,nsc.id_referensi,wo.id_work_order,wo.id_karyawan_dealer,nspart.harga_beli,
            COALESCE(SUM(nspart.harga_beli * nspart.qty) - (CASE WHEN nspart.tipe_diskon='Percentage' 
            THEN nspart.harga_beli*(nspart.diskon_value/100)* nspart.qty ELSE nspart.diskon_value END )) as total_part
            from tr_h23_nsc nsc 
            join tr_h23_nsc_parts nspart on nspart.no_nsc=nsc.no_nsc
            join tr_h2_wo_dealer wo on wo.id_work_order = nsc.id_referensi 
            join ms_part part on part.id_part_int = nspart.id_part_int 
            where nsc.referensi ='work_order'
            and part.kelompok_part in('GMO','OIL','FED OIL')
            AND wo.id_dealer ='$dealer'
            and wo.id_karyawan_dealer in($rows->id_on_pit)
            and left(wo.closed_at,10) >= '$params->start_date' and left(wo.closed_at,10) <= '$params->end_date'
        
        ")->row();*/

        /*
        $total_oil = $this->db->query("select nsc.no_nsc,nspart.id_part,nsc.id_referensi,wo.id_work_order,wo.id_karyawan_dealer,nspart.harga_beli,
            COALESCE(SUM(nspart.harga_beli * nspart.qty) - (CASE WHEN nspart.tipe_diskon='Percentage' 
            THEN nspart.harga_beli*(nspart.diskon_value/100)* nspart.qty ELSE nspart.diskon_value END )) as total_part
            from tr_h23_nsc nsc 
            join tr_h23_nsc_parts nspart on nspart.no_nsc=nsc.no_nsc
            join tr_h2_wo_dealer wo on wo.id_work_order = nsc.id_referensi 
            join ms_part part on part.id_part_int = nspart.id_part_int  
            join tr_h2_sa_form sa on wo.id_sa_form=sa.id_sa_form 
            where nsc.referensi ='work_order'
            and part.kelompok_part in('OIL')
            AND wo.id_dealer ='$dealer'
            and wo.id_karyawan_dealer in($rows->id_on_pit)
            and left(wo.closed_at,10) >= '$params->start_date' and left(wo.closed_at,10) <= '$params->end_date'
        
        ")->row();*/

        
        // Oli Tanpa KPB 1 
        $total_oil = $this->db->query("select nsc.no_nsc,nspart.id_part,nsc.id_referensi,wo.id_work_order,wo.id_karyawan_dealer,nspart.harga_beli,
            COALESCE(SUM(nspart.harga_beli * nspart.qty) - (CASE WHEN nspart.tipe_diskon='Percentage' 
            THEN nspart.harga_beli*(nspart.diskon_value/100)* nspart.qty ELSE nspart.diskon_value END )) as total_part
            from tr_h23_nsc nsc 
            join tr_h23_nsc_parts nspart on nspart.no_nsc=nsc.no_nsc
            join tr_h2_wo_dealer wo on wo.id_work_order = nsc.id_referensi 
            join ms_part part on part.id_part_int = nspart.id_part_int  
            join tr_h2_sa_form sa on wo.id_sa_form=sa.id_sa_form 
            where nsc.referensi ='work_order'
            and part.kelompok_part in('FED OIL','OIL','GMO')
            AND wo.id_dealer ='$dealer' AND sa.id_type != 'ASS1'
            and wo.id_karyawan_dealer in($rows->id_on_pit)
            and left(wo.closed_at,10) >= '$start_date' and left(wo.closed_at,10) <= '$end_date'
        
        ")->row();
        
        
        
        $sum_total_ue[]=intval($total_ue->total_ue);
        $sum_total_cs[]=intval($total_cs->total_cs);
        // $sum_total_jasa[]=intval($total_jasa->total_jasa);
        $sum_total_jasa[]=intval($total_jasa_diskon);
        $sum_total_jasa_cs[]=intval($total_jasa_cs->total_jasa);
        $sum_total_diskon_jasa[]=intval($diskon_jasa->diskon_jasa);
        $sum_total_part[]=intval($total_part->total_part);
        $sum_total_oil[]=intval($total_oil->total_part);
    ?>
    <tr>
        <td style="text-align:center"><?=$no++?></td>
        <td><?=$rows->id_pit?></td>
        <td><?=$rows->nama_lengkap?></td>
        <td style="text-align:center"><?=$total_ue->total_ue?></td>
        <td style="text-align:center"><?=$total_cs->total_cs?></td>
        <td style="text-align:right"><?=number_format($total_jasa_diskon,0,',','.')?></td>
        <td style="text-align:right"><?=number_format($total_jasa_cs->total_jasa,0,',','.')?></td>
        <td style="text-align:right"><?=number_format($diskon_jasa->diskon_jasa,0,',','.')?></td>
        <td style="text-align:right"><?=number_format($total_part->total_part,0,',','.')?></td>
        <td style="text-align:right"><?=number_format($total_oil->total_part,0,',','.')?></td>
    </tr>
    <?php } 
    ?>
    <tr>
        <th colspan="3">
            Total 
        </th>
        <th style="text-align:center"><?php echo number_format(array_sum($sum_total_ue),0,',','.')?></th>
        <th style="text-align:center"><?php echo number_format(array_sum($sum_total_cs),0,',','.')?></th>
        <th style="text-align:right"><?php echo number_format(array_sum($sum_total_jasa),0,',','.')?></th>
        <th style="text-align:right"><?php echo number_format(array_sum($sum_total_jasa_cs),0,',','.')?></th>
        <th style="text-align:right"><?php echo number_format(array_sum($sum_total_diskon_jasa),0,',','.')?></th>
        <th style="text-align:right"><?php echo number_format(array_sum($sum_total_part),0,',','.')?></th>
        <th style="text-align:right"><?php echo number_format(array_sum($sum_total_oil),0,',','.')?></th>
    </tr>
    <?php } ?>
</table>