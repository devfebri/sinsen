
<button class="btn btn-primary btn-block btn-flat" disabled>Detail Unit</button>
<table id="" class="table table-bordered table-hover">
  <thead>
    <tr>                    
      <th width="15%">No Mesin</th>              
      <th width="15%">No Rangka</th>              
      <th width="20%">Nama Konsumen</th>              
      <th width="20%">Alamat</th>              
      <th width="10%">Kode Item</th>
      <th width="10%">Tipe</th>                            
      <th width="10%">Warna</th>
      <th width="5%">Act</th>              
    </tr>
  </thead>
</table>
<table id="example2" class="table myTable1 table-bordered table-hover">
  <?php   
  foreach($dt_sppu->result() as $row) {           
    $sql = $this->db->query("SELECT tr_sales_order.*,tr_prospek.nama_konsumen,tr_spk.alamat,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_scan_barcode.no_rangka,tr_scan_barcode.id_item FROM tr_sales_order 
      INNER JOIN tr_spk ON tr_sales_order.no_spk = tr_spk.no_spk
      INNER JOIN tr_prospek ON tr_spk.id_customer = tr_prospek.id_customer
      INNER JOIN tr_scan_barcode ON tr_sales_order.no_mesin = tr_scan_barcode.no_mesin
      INNER JOIN ms_tipe_kendaraan ON tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
      INNER JOIN ms_warna ON tr_spk.id_warna = ms_warna.id_warna      
      WHERE tr_scan_barcode.no_mesin = '$row->no_mesin'")->row();    
    echo "   
    <tr>                    
      <td width='15%'>$row->no_mesin</td>
      <td width='15%'>$sql->no_rangka</td>      
      <td width='20%'>$sql->nama_konsumen</td>            
      <td width='20%'>$sql->alamat</td>            
      <td width='10%'>$sql->id_item</td>            
      <td width='10%'>$sql->tipe_ahm</td>            
      <td width='10%'>$sql->warna</td>            
      <td width='5%'>"; ?>
        <button title="Hapus Data"
            class="btn btn-xs btn-danger btn-flat" type="button" 
            onClick="hapus_sppu('<?php echo $row->no_sppu; ?>','<?php echo $row->id_sppu_detail; ?>')"><i class="fa fa-trash-o"></i></button>        
      </td>
    </tr>
  <?php    
    }
  ?>  
</table>
<table id="myTable" class="table myt order-list" border="0">     
  <tbody>                    
    <tr>      
      <td width="15%">
        <input type="text" readonly data-toggle="modal" data-target="#Nosinmodal" id="no_mesin" onchange="cek_to()" placeholder="No Mesin" class="form-control isi">        
      </td>      
      <td width="15%">
        <input type="text" readonly data-toggle="modal" data-target="#Nosinmodal" id="no_rangka" placeholder="No Rangka" class="form-control isi" name="no_ragka">
      </td>      
      <td width="20%">
        <input type="text" id="nama_lengkap" data-toggle="modal" data-target="#Nosinmodal" readonly placeholder="No Lengkap" class="form-control isi" name="no_lengkap">
      </td>      
      <td width="20%">
        <input type="text" id="alamat" data-toggle="modal" data-target="#Nosinmodal" readonly placeholder="Alamat" class="form-control isi" name="alamat">
      </td>      
      <td width="10%">
        <input type="text" readonly data-toggle="modal" data-target="#Nosinmodal" id="kode_item" placeholder="Kode Item" class="form-control isi" name="kode_item">
      </td>      
      <td width="10%">
        <input type="text" readonly data-toggle="modal" data-target="#Nosinmodal" id="tipe" placeholder="Kode Item" class="form-control isi" name="kode_item">
      </td>      
      <td width="10%">
        <input type="text" readonly data-toggle="modal" data-target="#Nosinmodal" id="warna" placeholder="Warna" class="form-control isi" name="warna">
      </td>            
      <td width="5%">
        <button onclick="simpan_sppu()" type="button" class="btn btn-xs btn-flat btn-primary">Add</button>                              
      </td>                        
    </tr>                       
  </tbody>
</table>