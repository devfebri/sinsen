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
  foreach ($dt_sj->result() as $isi) {        
    $ambil = $this->db->query("SELECT * FROM tr_surat_jalan_detail WHERE id_surat_jalan_detail = '$id'")->row();
    $nosin_lama = $ambil->no_mesin;    
    $item = $this->db->query("SELECT * FROM ms_item INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
              INNER JOIN ms_warna ON ms_item.id_warna=ms_warna.id_warna
              WHERE ms_item.id_item = '$isi->id_item'")->row();
  
    echo "
    <tr>          
      <th width='1%'>$no</th>              
      <td width='10%'>$isi->no_mesin</td>        
      <td width='20%'>$item->tipe_ahm ($item->id_tipe_kendaraan)</td>
      <td width='10%'>$item->warna ($item->id_warna)</td>      
      <td width='1%' align='center'>                      
        <a href='h1/surat_jalan/ubah_nosin?nosin=$isi->no_mesin&id=$id&lama=$nosin_lama&no_sj=$no_sj&no_do=$isi->no_do'>"; ?>
          <button class="btn btn-primary btn-flat btn-xs" type='button' 
            onclick="return confirm('Anda akan mengedit surat jalan <?php echo $no_sj ?>. Dan digantikan dengan unit DO sama atau DO selanjutnya yaitu no mesin <?php echo $nosin_lama ?> digantikan dengan no mesin <?php echo $isi->no_mesin ?>, mohon menunggu Approval oleh Warehouse Head terlebih dahulu. Lanjutkan?')">
            pilih
          </button>
        </a>
      </td>      
    </tr>
    <?php 
    $no++;    
  } 
  ?>
  </tbody> 
</table>
    