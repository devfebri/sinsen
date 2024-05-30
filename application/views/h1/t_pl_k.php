<?php 
function mata_uang($a){
    	if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
    return number_format($a, 2, ',', '.');
}
?>
<table id="example2" class="table myTable1 table-bordered table-hover">
  <thead>
    <tr>            
      <th width="1%">No</th>            
      <th width="10%">No Mesin</th>
      <th width="20%">Tipe</th>
      <th width="10%">Warna</th>      
      <th width="10%">Lokasi Unit</th>   
      <th width="1%" align="center">PDI</th>                 
      <th width="1%" align="center"><input onclick="toggle(this);" type="checkbox" id="chk_boxes"></th>           
    </tr>    
  </thead>
  <tbody>
  <?php 
  $no=1;  
  $ambil_view = $this->db->query("SELECT * FROM tr_picking_list_view WHERE no_picking_list = '$no_pl'");    
  foreach ($ambil_view->result() as $isi) {
    $item = $this->db->query("SELECT * FROM ms_item INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
                INNER JOIN ms_warna ON ms_item.id_warna=ms_warna.id_warna
                WHERE ms_item.id_item = '$isi->id_item'")->row();    
    $jum = $ambil_view->num_rows();  
    // //$r = $this->session->userdata('isi_nosin');  
    // $r = "JM31E1934578|JM41E1085535|JM41E1085422|JM31E1934596";
    // $isinya = explode("|", $r);    
    // foreach($isinya as $hasil)
    // {
    //   if($hasil == $isi->no_mesin){
    //     $cek = "ok";
    //   }else{
    //     $cek = "";
    //   }            
    // }

    if($isi->konfirmasi == 'ya' or $isi->scan == 'ya'){
      $is = "checked";
    }else{
      $is = "";
    }
    if($isi->pdi == 'ya'){
      $ir = "checked";
    }else{
      $ir = "";
    }
          
    echo "
    <tr>          
      <th width='1%'>$no</th>              
      <td width='10%'>$isi->no_mesin</td>        
      <td width='20%'>$item->tipe_ahm ($item->id_tipe_kendaraan)</td>
      <td width='10%'>$item->warna ($item->id_warna)</td>
      <td width='10%'>$isi->lokasi - $isi->slot</td>      
      <td width='1%'><input type='checkbox' name='check_pdi_$no' $ir></td>      
      <td width='1%' align='center'>
        <input type='hidden' value='$jum' name='jum'>
        <input type='hidden' value='$isi->no_mesin' name='no_mesin_$no'>
        <input type='hidden' value='$isi->id_item' name='id_item_$no'>
        <input type='hidden' value='$isi->no_picking_list_view' name='no_picking_list_view_$no'>        
        <input type='checkbox' class='data_check' required name='check_pl_$no' $is>
      </td>      
    </tr>
    ";  
    $no++;
  } 
  ?>
  </tbody> 
</table>
