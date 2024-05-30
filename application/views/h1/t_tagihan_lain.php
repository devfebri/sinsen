<table id="myTable" class="table myTable1 order-list" border="0">
  <thead>
    <tr>
      <th width="15%">No PO</th>
      <th width="10%">Tgl PO</th>
      <th width="15%">No Kwitansi</th>
      <th width="10%">Tgl Kwitansi</th>
      <th width="15%">No BAST</th>
      <th width="10%">Tgl BAST</th>
      <th width="10%">Due Datetime</th>
      <th width="10%">Harga</th>
      <th width="5%">Aksi</th>
    </tr>
  </thead> 
</table>

<table id="example2" class="table myTable1 table-bordered table-hover">
  <?php   
  foreach($dt_rekap->result() as $row) {           
    echo "   
    <tr>                    
      <td width='15%'>$row->no_po</td>
      <td width='10%'>$row->tgl_po</td>
      <td width='15%''>$row->no_kwitansi</td>
      <td width='10%'>$row->tgl_kwitansi</td>
      <td width='15%'>$row->no_bast</td>
      <td width='10%'>$row->tgl_bast</td>
      <td width='10%'>$row->due_datetime</td>
      <td width='10%'>$row->harga</td>      
      <td width='5%'>"; ?>
        <button title="Hapus Data"
            class="btn btn-md btn-danger btn-flat fa fa-trash-o" type="button" 
            onClick="hapus_data('<?php echo $row->id_tagihan_lain_detail; ?>')"></button>
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
        <input id="no_po" type="text" name="no_po" class="form-control isi_combo" placeholder="No PO">
      </td> 
      <td width="10%">
        <input type="date" id="tgl_po" placeholder="Tgl PO" class="form-control isi_combo" name="tgl_po">
      </td>      
      <td width="15%">
        <input type="text" id="no_kwitansi" placeholder="No Kwitansi" class="form-control isi_combo" name="no_kwitansi">      
      </td>
      <td width="10%">
        <input type="date" id="tgl_kwitansi" placeholder="Tgl Kwitansi" class="form-control isi_combo" name="tgl_kwitansi">      
      </td>
      <td width="15%">
        <input type="text" id="no_bast" class="form-control isi_combo" placeholder="No BAST" name="no_bast">
      </td>      
      <td width="10%">
        <input type="date" id="tgl_bast" class="form-control isi_combo" placeholder="Tgl BAST" name="tgl_bast">
      </td>      
      <td width="10%">
        <input type="date" id="due_datetime" class="form-control isi_combo" placeholder="Due Datetime" name="due_datetime">
      </td>      
      <td width="10%">
        <input type="text" id="harga" class="form-control isi_combo" placeholder="Harga" name="harga">
      </td>      
      <td width="5%">
        <button type="button" onClick="simpan_data()" class="btn btn-md btn-primary btn-flat"> Add</button>                          
      </td>                        
    </tr>
  </tbody>                        
</table>
