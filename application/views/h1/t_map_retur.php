<table id="example2" class="table myTable1 table-bordered table-hover">
  <thead>
    <tr>
      <th>No BASTD</th>
      <th>Nama Konsumen</th>
      <th>Alamat</th>
      <th>No Mesin</th>
      <th>No Rangka</th>
      <th>No Faktur AHM</th>      
      <th>Tipe</th>
      <th>Warna</th>
      <th>Tahun</th>                    
      <th>Aksi</th>                    
    </tr>
  </thead>
 
  <tbody>                    
    <?php   
    $no = 1;
    foreach($dt_retur->result() as $isi) {  
      $jum = $dt_retur->num_rows();                 
        echo "
      <tr>                     
        <td>$isi->no_bastd</td> 
        <td>$isi->nama_konsumen</td> 
        <td>$isi->alamat</td> 
        <td>$isi->no_mesin</td> 
        <td>$isi->no_rangka</td>       
        <td>$isi->no_faktur</td>                 
        <td>$isi->id_tipe_kendaraan</td>                 
        <td>$isi->id_warna</td>                 
        <td>$isi->tahun</td>                 
        <td align='center'>
          <input type='hidden' value='$jum' name='jum'>        
          <input type='hidden' value='$isi->no_mesin' name='no_mesin_$no'>        
          <input type='checkbox' name='cek_retur_$no'>
        </td>              
      </tr>";
      $no++;
      }      
    ?>
  </tbody>
</table>     