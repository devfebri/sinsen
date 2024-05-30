<?php 
if($mode == "new"){
?>
<table id="myTable" class="table myTable1 order-list" border="0">
  <thead>
    <tr>
      <th width="20%">No Mesin</th>
      <th width="10%">Tipe Motor</th>
      <th width="10%">Warna Motor</th>                    
      <th width="10%">Tahun Produksi</th>                    
      <th width="15%">No Faktur</th>                    
      <th width="20%">Alasan Penggantian</th>                          
      <th width="10%">Aksi</th>
    </tr>
  </thead> 
</table>

<table id="example2" class="table myTable1 table-bordered table-hover">
  <?php   
  foreach($dt_data->result() as $row) {           
    $re = $this->db->query("SELECT * FROM tr_fkb INNER JOIN ms_tipe_kendaraan ON tr_fkb.kode_tipe=ms_tipe_kendaraan.id_tipe_kendaraan 
              INNER JOIN ms_warna ON tr_fkb.kode_warna=ms_warna.id_warna
              WHERE tr_fkb.no_mesin_spasi = '$row->no_mesin'")->row();
    echo "   
    <tr>                    
      <td width='20%'>$row->no_mesin</td>
      <td width='10%'>$re->tipe_ahm</td>      
      <td width='10%'>$re->warna</td>      
      <td width='10%'>$re->tahun_produksi</td>      
      <td width='15%'>$re->nomor_faktur</td>      
      <td width='20%'>$row->alasan_penggantian</td>            
      <td width='10%'>"; ?>
        <button title="Hapus Data"
            class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
            onClick="hapus_nosin('<?php echo $row->id_penggantian_fkb_detail; ?>','<?php echo $row->no_surat; ?>')"></button>
      </td>
    </tr>
  <?php    
    }
  ?>  
</table>


<table id="myTable" class="table myt order-list" border="0">     
  <tbody>                      
    <tr>
      <td width="20%">
        <select class="form-control select2 isi_combo" id="no_mesin" onchange="ambil_nosin()">
          <option value="">- choose -</option>
          <?php 
          // $no_mesin = $this->m_admin->getAll("tr_input_fkb_detail");
          $no_mesin = $this->m_admin->getAll("tr_scan_barcode");
          foreach ($no_mesin->result() as $isi) {
            echo "<option value='$isi->no_mesin'>$isi->no_mesin</option>";
          }
          ?>
        </select>
      </td>
      <td width="10%">
        <input type="text" class="form-control isi_combo" id="tipe" placeholder="Tipe Motor" readonly>
      </td>
      <td width="10%">
        <input type="text" class="form-control isi_combo" id="warna" placeholder="Warna Motor" readonly>
      </td>                          
      <td width="10%">
        <input type="text" class="form-control isi_combo" id="tahun_produksi" placeholder="Tahun Produksi" readonly>
      </td>
      <td width="15%">
        <input type="text" class="form-control isi_combo" id="no_faktur" placeholder="No Faktur" readonly>
      </td>
      <td width="20%">
        <select class="form-control select2 isi_combo" id="alasan_penggantian">
          <option value="">- choose -</option>
          <?php 
          $alasan = $this->m_admin->getSortCond("ms_alasan_ganti_faktur","alasan_ganti_faktur","ASC");
          foreach ($alasan->result() as $isi) {
            echo "<option value='$isi->alasan_ganti_faktur'>$isi->alasan_ganti_faktur</option>";
          }
          ?>
        </select>
      </td>      
      <td width="10%">
        <button type="button" onClick="simpan_data()" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>                          
      </td>                        
    </tr>
  </tbody>                        
</table>
<?php 
}else{
?>

<table id="myTable" class="table myTable1 order-list" border="0">
  <thead>
    <tr>
      <th width="20%">No Mesin</th>
      <th width="10%">Tipe Motor</th>
      <th width="10%">Warna Motor</th>                    
      <th width="10%">Tahun Produksi</th>                    
      <th width="15%">No Faktur</th>                    
      <th width="20%">Alasan Penggantian</th>                                
    </tr>
  </thead> 
</table>

<table id="example2" class="table myTable1 table-bordered table-hover">
  <?php   
  foreach($dt_data->result() as $row) {           
    $re = $this->db->query("SELECT * FROM tr_fkb INNER JOIN ms_tipe_kendaraan ON tr_fkb.kode_tipe=ms_tipe_kendaraan.id_tipe_kendaraan 
              INNER JOIN ms_warna ON tr_fkb.kode_warna=ms_warna.id_warna
              WHERE tr_fkb.no_mesin_spasi = '$row->no_mesin'")->row();
    echo "   
    <tr>                    
      <td width='20%'>$row->no_mesin</td>
      <td width='10%'>$re->tipe_ahm</td>      
      <td width='10%'>$re->warna</td>      
      <td width='10%'>$re->tahun_produksi</td>      
      <td width='15%'>$re->nomor_faktur</td>      
      <td width='20%'>$row->alasan_penggantian</td>                  
    </tr>";  
    }
  ?>  
</table>

<?php 
}
?>

<script type="text/javascript">
    $(".select2").select2({
            placeholder: "-- Pilih --",
            allowClear: false
        });
</script>