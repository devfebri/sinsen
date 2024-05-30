<?php 
function mata_uang($a){
    return number_format($a, 2, ',', '.');
}
?>
<table id="example2" class="table myTable1 table-bordered table-hover">
  <thead>
    <tr>                  
      <th width="10%">No Mesin</th>
      <th width="20%">Tipe</th>
      <th width="10%">Warna</th>      
      <th width="10%">Lokasi Unit</th>              
    </tr>    
  </thead>
  <tbody>
  <?php 
  $no=1;
  $cek_view = $this->db->query("SELECT * FROM tr_picking_list WHERE no_picking_list = '$no_pl'")->row();
  if($cek_view->status == 'input'){  
    $cek_tmp = $this->db->query("SELECT * FROM tr_picking_list_view WHERE no_picking_list = '$no_pl'");    
    
                         
      
      //$is = $dt_pl->row();
    
    if($cek_tmp->num_rows() > 0){
      foreach ($cek_tmp->result() as $amb){
        $cek_pik = $this->db->query("SELECT tr_scan_barcode.*,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna, ms_warna.warna FROM tr_scan_barcode INNER JOIN ms_item 
                    ON tr_scan_barcode.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan           
                    ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
                    ON ms_item.id_warna=ms_warna.id_warna WHERE tr_scan_barcode.no_mesin = '$amb->no_mesin'")->row();        
        echo "
        <tr>                    
          <td width='10%'>$amb->no_mesin</td>          
          <td width='20%'>$cek_pik->tipe_ahm ($cek_pik->id_tipe_kendaraan)</td>
          <td width='10%'>$cek_pik->warna ($cek_pik->id_warna)</td>
          <td width='10%'>$cek_pik->lokasi - $cek_pik->slot</td>      
        </tr>
        ";  
        $no++;
      }
    }else{
      echo "
        <tr>                    
          <td width='10%'></td>          
          <td width='20%'></td>
          <td width='10%'></td>
          <td width='10%'></td>      
        </tr>
        ";  


    }/*elseif($cek_tmp->num_rows() ){
      foreach ($dt_pl->result() as $row) {    
        $cek_jum = $row->qty_do;        
        $sisa = $cek_jum - $cek_tmp->num_rows();      
        for ($i=1;$i <= $sisa;$i++) {                       
          $cek2 = $this->db->query("SELECT * FROM tr_scan_barcode WHERE tipe_motor ='$row->id_tipe_kendaraan' AND warna = '$row->id_warna' AND status = 1 AND tipe = 'RFS' ORDER BY fifo ASC");
          if($cek1->num_rows() == 1){
            $isi = $cek1->row();
            $no_mesin = $isi->no_mesin;
            $id_item  = $isi->id_item;
            $lokasi   = $isi->lokasi;
            $slot     = $isi->slot;
          }elseif($cek2->num_rows() > 0){
            $isi2 = $cek2->row();
            $no_mesin = $isi2->no_mesin;
            $id_item  = $isi2->id_item;
            $lokasi   = $isi2->lokasi;
            $slot     = $isi2->slot;
            $ubah = $this->db->query("UPDATE tr_scan_barcode SET status = 2 WHERE no_mesin = '$no_mesin'");
          }else{
            $no_mesin = "";
            $lokasi   = "";
            $id_item  = "";
            $slot     = "";
          } 

          echo "
          <tr>                      
            <td width='10%'>$no_mesin</td>
            <input type='hidden' name='no_mesin[]' value='$no_mesin'>        
            <input type='hidden' name='id_item[]' value='$id_item'>                
            <input type='hidden' name='lokasi[]' value='$lokasi'>                
            <input type='hidden' name='slot[]' value='$slot'>                
            <td width='20%'>$row->tipe_ahm ($row->id_tipe_kendaraan)</td>
            <td width='10%'>$row->warna ($row->id_warna)</td>
            <td width='10%'>$lokasi $slot</td>      
          </tr>
          ";  
          $no++;
        }
      } */
    
  }else{
    $ambil_view = $this->db->query("SELECT * FROM tr_picking_list_view WHERE no_picking_list = '$no_pl' AND (konfirmasi = 'ya' OR scan = 'ya')");    
    foreach ($ambil_view->result() as $isi) {
      $item = $this->db->query("SELECT * FROM ms_item INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
                  INNER JOIN ms_warna ON ms_item.id_warna=ms_warna.id_warna
                  WHERE ms_item.id_item = '$isi->id_item'")->row();
      echo "
      <tr>                  
        <td width='10%'>$isi->no_mesin</td>        
        <td width='20%'>$item->tipe_ahm ($item->id_tipe_kendaraan)</td>
        <td width='10%'>$item->warna ($item->id_warna)</td>
        <td width='10%'>$isi->lokasi - $isi->slot</td>      
      </tr>
      ";  
      $no++;
    }
  }
  ?>
  </tbody> 
</table>
    