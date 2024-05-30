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
      <th width="1%" align="center">Action</th>           
    </tr>    
  </thead>
  <tbody>
  <?php 
  $no=1;$jum=0;    
  foreach ($dt_sj->result() as $isi) {

    
    $cek_i = $this->db->query("SELECT * FROM tr_surat_jalan WHERE no_surat_sppm = '$isi->no_surat_sppm'");
    if($cek_i->num_rows()==0){
      $no_pl = $this->db->query("SELECT * FROM tr_picking_list INNER JOIN tr_sppm ON tr_picking_list.no_do=tr_sppm.no_do WHERE tr_sppm.no_surat_sppm='$isi->no_surat_sppm'")->row();
      $ubah = $this->db->query("UPDATE tr_picking_list_view SET status = 'input' WHERE no_picking_list = '$no_pl->no_picking_list'");      
    }


    $item = $this->db->query("SELECT * FROM ms_item INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
                INNER JOIN ms_warna ON ms_item.id_warna=ms_warna.id_warna
                WHERE ms_item.id_item = '$isi->id_item'")->row();
    $cek_jum = $isi->qty_ambil==null?0:$isi->qty_ambil;

    $cek = $this->db->query("SELECT * FROM tr_picking_list_view INNER JOIN tr_picking_list ON tr_picking_list.no_picking_list = tr_picking_list_view.no_picking_list 
                  WHERE tr_picking_list.no_do ='$isi->no_do' AND tr_picking_list_view.id_item = '$isi->id_item'
                  AND tr_picking_list_view.status = 'input' AND tr_picking_list_view.konfirmasi = 'ya' 
                  AND tr_picking_list_view.no_mesin NOT IN (SELECT no_mesin FROM tr_penerimaan_unit_dealer_detail WHERE no_mesin IS NOT NULL AND retur = 0)                                    
                  AND tr_picking_list_view.no_mesin NOT IN (SELECT no_mesin FROM tr_surat_jalan_detail WHERE ceklist = 'ya' AND retur = 0)                                    
                  ORDER BY no_picking_list_view ASC LIMIT 0,$cek_jum");        
    foreach ($cek->result() as $key) {    
      $cek_scan = $this->m_admin->getByID("tr_surat_jalan_detail","no_mesin",$key->no_mesin)->row();
      if(isset($cek_scan->scan) AND $cek_scan->scan == 'ya'){
        $r = "checked";
      }else{
        $r = "";
      }
      $jum++;

      echo "
      <tr>          
        <th width='1%'>$no</th>              
        <td width='10%'>$key->no_mesin</td>        
        <td width='20%'>$item->tipe_ahm ($item->id_tipe_kendaraan)</td>
        <td width='10%'>$item->warna ($item->id_warna)</td>      
        <td width='1%' align='center'>        
          <input type='hidden' value='$key->no_mesin' name='no_mesin_$no'>        
          <input type='hidden' value='$isi->id_item' name='id_item_$no'>        
          <input type='checkbox' class='data-check' $r class='flat-red' name='check_sj_$no'>
        </td>      
      </tr>
      ";  
      $no++;
    }
  }   
  echo "<input type='hidden' value='$jum' name='jum'>";   
  ?>
  </tbody> 
</table>
    
<script type="text/javascript">
    function toggle(source) {
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i] != source)
            checkboxes[i].checked = source.checked;
    }
}
</script>