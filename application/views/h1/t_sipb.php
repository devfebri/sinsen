<?php 
function mata_uang($a){
    return number_format($a, 2, ',', '.');
}
?>
<table id="example2" class="table myTable1 table-bordered table-hover">
  <thead>
    <tr>            
      <th width="1%">No</th>            
      <th width="10%">No Mesin</th>
      <th width="10%">No Rangka</th>
      <th width="10%">Kode Item</th>
      <th width="20%">Tipe</th>
      <th width="10%">Warna</th>            
      
    </tr>    
  </thead>
  <tbody>
  <?php 
  $no=1;    
  foreach ($dt_sj->result() as $isi) {  
    $item = $this->db->query("SELECT * FROM ms_item INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
                INNER JOIN ms_warna ON ms_item.id_warna=ms_warna.id_warna
                WHERE ms_item.id_item = '$isi->id_item'")->row();   
    $rangka = $this->db->query("SELECT * FROM tr_scan_barcode WHERE no_mesin = '$isi->no_mesin'")->row(); 
    
    echo "
    <tr>          
      <th width='1%'>$no</th>              
      <td width='10%'>$isi->no_mesin</td>        
      <td width='10%'>$rangka->no_rangka</td>        
      <td width='10%'>$isi->id_item</td>        
      <td width='20%'>$item->tipe_ahm ($item->id_tipe_kendaraan)</td>
      <td width='10%'>$item->warna ($item->id_warna)</td>      
      
    </tr>
    ";  
    $no++;  
  } 
  ?>
  </tbody> 
</table>
    