<?php 
function mata_uang($a){
    	if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
    return number_format($a, 2, ',', '.');
}
?>
<table id="example2" class="table myTable1 table-bordered table-hover">
  <thead>
    <tr>                  
      <th width="10%">Kode Item</th>            
      <th width="10%">Qty Onhand MD</th>            
      <th width="10%">Qty SPPM</th>                  
      <th width="10%">Kode KSU/Qty Supply</th>
    </tr>    
  </thead>
  <tbody>
  <?php 
  $no=1;    
  $x=0;$xx=0;
  foreach ($dt_sj->result() as $isi) {            
    $item = $this->db->query("SELECT * FROM ms_item INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
              INNER JOIN ms_warna ON ms_item.id_warna=ms_warna.id_warna
              WHERE ms_item.id_item = '$isi->id_item'")->row();
    $qty = $this->db->query("SELECT * FROM tr_do_po_detail WHERE no_do = '$isi->no_do' AND id_item = '$item->id_item'");
    if($qty->num_rows() > 0){
      $amb = $qty->row();
      $qty_on_hand = $amb->qty_on_hand;
    }else{
      $qty_on_hand = 0;
    }
    $cek = $this->db->query("SELECT * FROM tr_sppm INNER JOIN tr_sppm_detail ON tr_sppm.no_surat_sppm = tr_sppm_detail.no_surat_sppm
        WHERE tr_sppm.no_surat_sppm = '$isi->no_surat_sppm' AND tr_sppm_detail.id_item = '$item->id_item'");
    if($cek->num_rows() > 0){
      $t = $cek->row();
      $qty_sppm = $t->qty_ambil;
    }else{
      $qty_sppm = 0;
    }
    echo "
    <tr>               
      <td width='20%'>$item->tipe_ahm ($item->id_tipe_kendaraan) - $item->id_item</td>
      <td width='10%'>$qty_on_hand</td>
      <td width='10%'><span id='qty_sppm_$no'>$qty_sppm</span></td>
      <td width='20%'>";
      $cek = $this->db->query("SELECT ms_koneksi_ksu_detail.id_ksu FROM ms_koneksi_ksu_detail INNER JOIN ms_koneksi_ksu ON ms_koneksi_ksu.id_koneksi_ksu = ms_koneksi_ksu_detail.id_koneksi_ksu 
            INNER JOIN ms_ksu ON ms_ksu.id_ksu = ms_koneksi_ksu_detail.id_ksu WHERE ms_koneksi_ksu.id_tipe_kendaraan = '$item->id_tipe_kendaraan'
            ORDER BY ms_ksu.ksu ASC");
      if(count($cek) > 0){
        $amb = $cek->row();
        
        foreach ($cek->result() as $key) {                    
          $cek2 = $this->db->query("SELECT id_ksu,ksu FROM ms_ksu WHERE id_ksu = '$key->id_ksu'");
          if(count($cek2) > 0){
            $rd = $cek2->row();
            $rty = $this->db->query("SELECT * FROM tr_surat_jalan_ksu WHERE no_surat_jalan = '$no_sj' AND id_ksu = '$rd->id_ksu' AND no_do = '$isi->no_do' AND id_item = '$item->id_item'");
            if($rty->num_rows() == 0){
              echo "
                 <div class='input-group'>
                  <span class='input-group-addon bg-maroon'>$rd->ksu</span>                         
                  <input type='hidden' name='isian' value='insert'>
                  <input type='hidden' name='id_item_add_$xx' value='$item->id_item'>                    
                  <input type='hidden' name='no_do_$xx' value='$isi->no_do'>                    
                  <input type='hidden' name='qty_do_add_$xx' value='$isi->qty_do'>                    
                  <input type='hidden' name='id_ksu_add_$xx' value='$rd->id_ksu'>                    
                  <input type='text' onkeypress='return number_only(event)' onkeyup='cekXX($xx,$no)' onchange='cekXX($xx,$no)' onkeydown='getXX($xx,$no)' value='$qty_sppm' name='qty_add_$xx' class='input-group-addon input-block' style='width:50px;'>
                  <input type='hidden' name='xx' value='$xx'>
                </div>";   
                $xx++;                     
            }else{
              $ui = $rty->row();
              echo "
                 <div class='input-group'>
                  <span class='input-group-addon bg-maroon'>$rd->ksu</span>                         
                  <input type='hidden' name='isian' value='update'>
                  <input type='hidden' name='id_surat_jalan_ksu_$x' value='$ui->id_surat_jalan_ksu'>
                  <input type='hidden' name='id_ksu_$x' value='$rd->id_ksu'>
                  <input type='hidden' name='x' value='$x'>
                  <input type='hidden' name='id_item_$x' value='$item->id_item'>                    
                  <input type='hidden' name='qty_do_$x' value='$isi->qty_do'>                    
                  <input type='hidden' name='no_do_$x' value='$isi->no_do'>
                  <input type='text' onkeypress='return number_only(event)'  onkeydown='getX($x,$no)' onkeyup='cekX($x,$no)' onchange='cekX($x,$no)'  value='$qty_sppm' name='qty_$x' id='qty_$x' class='input-group-addon input-block' style='width:50px;'>
                </div>";     
               $x++;     ;               
            }
          }
        }   

      }
      echo "
      </td>              
      "; ?>           
    </tr>
    <?php 
    $no++;    
  } 
  ?>
  </tbody> 
</table>
    
