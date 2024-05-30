<table id="example2" class="table table-bordered table-hover">
  <thead>
    <tr>
      <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
      <th width="5%">No</th>
      <th>Tipe Kendaraan</th>              
      <th>Warna</th>              
      <th>Qty Dist. Plan</th>              
      <th>Qty DO AHM</th>              
      <th>Qty Unfill D</th>
      <th>Qty Intransit AHM</th>
      <th>Qty Received MD</th>
      <th>Selisih</th>
    </tr>
  </thead>
  <tbody>            
  <?php 
  $no=1; 
  $tot_displan = 0;
  $tot_do =0;
  $tot_unfill =0;
  $tot_sl =0;
  $tot_pu =0;
  $sisa = 0;

  foreach($dt_displan->result() as $row) {      
    if(!is_null($row->warna)){
      $warna = "<td>$row->id_warna | $row->warna</td>";
    }else{
      $warna = "<td bgcolor='red'>$row->id_warna | $row->warna</td>";
    }

    if(!is_null($row->tipe_ahm)){
      $tipe = "<td>$row->id_tipe_kendaraan | $row->tipe_ahm</td>";
    }else{
      $tipe = "<td bgcolor='red'>$row->id_tipe_kendaraan | $row->tipe_ahm</td>";
    }
    
    $qty_do = 0;
    $cek_sipb = $this->db->query("SELECT SUM(jumlah) as jum 
      FROM tr_sipb 
      WHERE id_tipe_kendaraan = '$row->id_tipe_kendaraan'
      AND MID(tr_sipb.tgl_sipb,3,2) = '$bulan' AND RIGHT(tr_sipb.tgl_sipb,4) = '$tahun' AND id_warna = '$row->id_warna'");
    if($cek_sipb->num_rows() > 0){
      $t = $cek_sipb->row();
      $qty_do = $t->jum;
    }

    $cek_pu = $this->db->query("SELECT COUNT(no_mesin) AS jumlah 
      FROM tr_scan_barcode 
      WHERE tipe_motor = '$row->id_tipe_kendaraan'
      AND MID(tgl_penerimaan,6,2) = '$bulan' AND LEFT(tgl_penerimaan,4) = '$tahun' AND warna = '$row->id_warna'");
    if($cek_pu->num_rows() > 0){
      $t = $cek_pu->row();
      $qty_pu = $t->jumlah;
    }else{  
      $qty_pu = 0;
    }

    $cek_sl = $this->db->query("SELECT COUNT(no_mesin) AS jumlah 
      FROM tr_shipping_list 
      WHERE id_modell = '$row->id_tipe_kendaraan'
      AND MID(tgl_sl,3,2) = '$bulan' AND RIGHT(tgl_sl,4) = '$tahun' AND id_warna = '$row->id_warna'");
    if($cek_sl->num_rows() > 0){
      $t = $cek_sl->row();
      $qty_sl = $t->jumlah;
    }else{  
      $qty_sl = 0;
    }

    $cek_unfill  = $this->db->query("SELECT COUNT(tr_do_po_detail.id_item) AS jum 
        FROM tr_do_po 
        INNER JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do 
        INNER JOIN tr_picking_list ON tr_do_po.no_do = tr_picking_list.no_do 
        INNER JOIN ms_item ON tr_do_po_detail.id_item = ms_item.id_item
        WHERE tr_picking_list.no_picking_list NOT IN (SELECT tr_surat_jalan.no_picking_list FROM tr_surat_jalan WHERE no_picking_list IS NOT NULL) 
        AND tr_do_po.status = 'approved' AND ms_item.id_tipe_kendaraan = '$row->id_tipe_kendaraan'
        AND ms_item.id_warna = '$row->id_warna'");

    if($cek_unfill->num_rows() > 0){
      $t = $cek_unfill->row();
      $qty_unfill = $t->jum;
    }else{  
      $qty_unfill = 0;
    }

    $qty_sl_fix = $qty_sl - $qty_pu;

    $tot_displan += $row->jum;
    $tot_do += $qty_do;
    $tot_unfill += $qty_unfill;
    $tot_sl += $qty_sl;
    $tot_pu += $qty_pu;

    $sisa = $row->jum - $qty_pu;
  echo "          
    <tr>
      <td>$no</td>
      $tipe
      $warna"; ?>                    
      <td align="center"><button class="btn btn-default btn-flat btn-sm" type="button" data-toggle="modal" data-target=".modal_detail" onclick="detail_popup('<?php echo $id_tipe_kendaraan ?>','<?php echo $row->id_warna ?>','<?php echo $bulan ?>','<?php echo $tahun ?>','qty_plan')"><?php echo $row->jum ?></button></td>
      <td align="center"><button class="btn btn-default btn-flat btn-sm" type="button" data-toggle="modal" data-target=".modal_detail" onclick="detail_popup('<?php echo $id_tipe_kendaraan ?>','<?php echo $row->id_warna ?>','<?php echo $bulan ?>','<?php echo $tahun ?>','qty_do')"><?php echo $qty_do ?></button></td>
      <td align="center"><button class="btn btn-default btn-flat btn-sm" type="button"><?php echo $qty_unfill ?></button></td>
      <td align="center"><button class="btn btn-default btn-flat btn-sm" type="button" data-toggle="modal" data-target=".modal_detail" onclick="detail_popup('<?php echo $id_tipe_kendaraan ?>','<?php echo $row->id_warna ?>','<?php echo $bulan ?>','<?php echo $tahun ?>','qty_sl')"><?php echo $qty_sl ?></button></td>
      <td align="center"><button class="btn btn-default btn-flat btn-sm" type="button" data-toggle="modal" data-target=".modal_detail" onclick="detail_popup('<?php echo $id_tipe_kendaraan ?>','<?php echo $row->id_warna ?>','<?php echo $bulan ?>','<?php echo $tahun ?>','qty_pu')"><?php echo $qty_pu ?></button></td>      
      <td align="center"><button class="btn btn-default btn-flat btn-sm" type="button"><?php echo $sisa ?></button></td>
    </tr>
  <?php
  $no++;
  }
  ?>
  </tbody>

  <tfoot>
    <?php
      echo "<tr>
        <td colspan='3'>Total</td>
        <td align='center'>".$tot_displan."</td>
        <td align='center'>".$tot_do."</td>
        <td align='center'>".$tot_unfill."</td>
        <td align='center'>".$tot_sl."</td>
        <td align='center'>".$tot_pu."</td>
        <td align='center'>".($tot_displan - $tot_pu)."</td>
      </tr>";
    ?>
  </tfoot>

</table> 

