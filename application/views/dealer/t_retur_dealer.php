

<button class="btn btn-primary btn-block btn-flat" disabled>Detail Unit</button>
<table id="" class="table table-bordered table-hover">
  <thead>
    <tr>                    
      <th width="15%">No Mesin</th>              
      <th width="15%">No Rangka</th>              
      <th width="10%">Kode Item</th>              
      <th width="15%">Tipe</th>              
      <th width="10%">Warna</th>
      <th width="10%">Tahun Produksi</th>                            
      <th width="10%">Tgl Penerimaan</th>
      <th width="15%">Keterangan</th>              
      <th width="5%">Action</th>
    </tr>
  </thead>

  <?php   
  foreach($dt_data->result() as $row) {               
    $tgl = $this->db->query("SELECT * FROM tr_penerimaan_unit_dealer_detail INNER JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer
        WHERE tr_penerimaan_unit_dealer_detail.no_mesin = '$row->no_mesin'")->row()->tgl_penerimaan;
    echo "   
    <tr>                    
      <td width='15%'>$row->no_mesin</td>
      <td width='15%'>$row->no_rangka</td>      
      <td width='10%'>$row->id_item</td>            
      <td width='15%'>$row->tipe_ahm</td>            
      <td width='10%'>$row->warna</td>            
      <td width='10%'>$row->tahun_produksi</td>            
      <td width='10%'>$tgl</td>
      <td width='15%'>$row->keterangan</td>            
      <td width='5%'>"; ?>
        <button title="Hapus Data"
            class="btn btn-xs btn-danger btn-flat" type="button" 
            onClick="hapus_data('<?php echo $row->id_retur_dealer_detail; ?>')"><i class="fa fa-trash-o"></i></button>        
      </td>
    </tr>
  <?php    
    }
  ?>  

  <tbody>                    
    <tr>      
      <td width="15%">
        <input type="text" readonly data-toggle="modal" data-target="#Nosinmodal" id="no_mesin" onchange="cek_nosin()" placeholder="No Mesin" class="form-control isi">        
      </td>      
      <td width="15%">
        <input type="text" readonly data-toggle="modal" data-target="#Nosinmodal" id="no_rangka" placeholder="No Rangka" class="form-control isi" name="no_ragka">
      </td>      
      <td width="10%">
        <input type="text" id="id_item" data-toggle="modal" data-target="#Nosinmodal" readonly placeholder="Kode Item" class="form-control isi" name="id_item">
      </td>      
      <td width="15%">
        <input type="text" id="tipe" data-toggle="modal" data-target="#Nosinmodal" readonly placeholder="Tipe" class="form-control isi" name="tipe">
      </td>      
      <td width="10%">
        <input type="text" readonly data-toggle="modal" data-target="#Nosinmodal" id="warna" placeholder="Warna" class="form-control isi" name="warna">
      </td>      
      <td width="10%">
        <input type="text" readonly data-toggle="modal" data-target="#Nosinmodal" id="tahun" placeholder="Tahun" class="form-control isi" name="tahun">
      </td>      
      <td width="10%">
        <input type="text" readonly data-toggle="modal" data-target="#Nosinmodal" id="tgl_terima" placeholder="Warna" class="form-control isi" name="tgl_terima">
      </td>            
      <td width="15%">        
        <input type="text" id="keterangan" placeholder="Keterangan" class="form-control isi" name="keterangan">
      </td>            
      <td width="5%">
        <button onclick="simpan_data()" type="button" class="btn btn-xs btn-flat btn-primary">Add</button>                              
      </td>                        
    </tr>                       
  </tbody>
</table>