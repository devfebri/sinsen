<?php 
 function mata_uang($a){
      if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
        if(is_numeric($a) AND $a != 0 AND $a != ""){
          return number_format($a, 0, ',', '.');
        }else{
          return $a;
        }        
    }
    
?>
<table id="example2" class="table myTable1 table-bordered table-hover">
  <thead>
    <tr>
      <th width="10%">No Mesin</th>
      <th width="15%">Nama Konsumen</th>
      <th width="15%">Alamat</th>
      <th width="15%">No HP</th>
      <th width="15%">Nilai Tagihan</th>      
      <th width="5%">Aksi</th>
    </tr>
  </thead> 
  <tbody>
  <?php   
  $no=1;
  foreach($dt_tagihan->result() as $row) {           
    $jum = $dt_tagihan->num_rows();
    echo "   
    <tr>                    
      <td width='10%'>$row->no_mesin</td>
      <td width='15%'>$row->nama_konsumen</td>
      <td width='15%''>$row->alamat</td>
      <td width='15%'>$row->no_hp</td>
      <td width='15%'>".mata_uang($row->biaya_denda)."</td>      
      <td width='5%'>"; ?>
        <input type="hidden" name="no_mesin_<?php echo $no ?>" value="<?php echo $row->no_mesin ?>">
        <input type="hidden" name="jum" value="<?php echo $jum ?>">
        <input type="checkbox" name="cek_tagihan_<?php echo $no ?>">
      </td>
    </tr>
  <?php    
    }
  ?>  
  </tbody>                        
</table>
