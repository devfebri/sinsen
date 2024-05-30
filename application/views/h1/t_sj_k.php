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
      <th width="1%">Aksi</th>           
    </tr>    
  </thead>
  <tbody>
  <?php 
  $no=1;    
  $sj = $this->db->query("SELECT * FROM tr_surat_jalan INNER JOIN tr_surat_jalan_detail ON tr_surat_jalan.no_surat_jalan=tr_surat_jalan_detail.no_surat_jalan
              WHERE tr_surat_jalan.no_surat_sppm = '$no_surat_sppm' AND tr_surat_jalan_detail.ceklist = 'ya'");
  foreach ($sj->result() as $isi) {        
    $item = $this->db->query("SELECT * FROM ms_item INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
                INNER JOIN ms_warna ON ms_item.id_warna=ms_warna.id_warna
                WHERE ms_item.id_item = '$isi->id_item'")->row();
    
    if($isi->pengganti != "" AND $isi->status_nosin == 'waiting'){
      $no_mesin = $isi->pengganti;
      $status = "<span class='label label-warning'>need approval</span>";              
    }else{
      $no_mesin = $isi->no_mesin;
      $status = "";
    }

    echo "
    <tr>          
      <th width='1%'>$no</th>              
      <td width='10%'>$no_mesin $status</td>        
      <td width='20%'>$item->tipe_ahm ($item->id_tipe_kendaraan)</td>
      <td width='10%'>$item->warna ($item->id_warna)</td>      
      <td width='1%' align='center'>        
        <a href='h1/surat_jalan/edit_nosin?no=$isi->no_surat_jalan&id=$isi->id_surat_jalan_detail'><button type='button' class='btn btn-xs btn-flat btn-primary' name='edit'>edit</button></a>
      </td>      
    </tr>
    ";  
    $no++;
  }
  ?>
  </tbody> 
</table>
    