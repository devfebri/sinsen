<style type="text/css">
  .hide{
    display: none;
  }
</style>
<table id="myTable" class="table myTable1 order-list" border="0">
  <thead>
    <tr>
      <th width="15%">Kode Tipe Kendaraan</th>
      <th width="10%">Tahun Kendaraan</th>
      <th width="10%">Warna</th>
      <th width="10%">Metode Pembayaran</th>            
      <th width="5%">Action</th>                      
    </tr>
  </thead> 
</table>

<table id="example2" class="table myTable1 table-bordered table-hover">
  <?php   
  foreach($dt_tipe->result() as $row) {           
    echo "   
    <tr>                    
      <td width='15%'>$row->id_tipe_kendaraan | $row->tipe_ahm</td>
      <td width='10%'>$row->tahun</td>
      <td width='10%'>$row->id_warna | $row->warna</td>      
      <td width='10%'>$row->metode_bayar ";
      if (!!$row->jenis_bayar_dibelakang) {
        echo ", $row->jenis_bayar_dibelakang";
      }
      echo"</td>                                                            
      <td width='5%'>"; ?>
        <button title="Hapus Data"
            class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
            onClick="hapus_tipe('<?php echo $row->id_sales_program_tipe; ?>','<?php echo $row->id_tipe_kendaraan; ?>')"></button>
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
        <select class="form-control select2 isi_combo" id="id_tipe_kendaraan">
          <option>- choose -</option>
          <?php 
          $tipe = $this->m_admin->getSortCond("ms_tipe_kendaraan","id_tipe_kendaraan","ASC");
          foreach ($tipe->result() as $isi) {
            echo "<option value='$isi->id_tipe_kendaraan'>$isi->id_tipe_kendaraan | $isi->tipe_ahm</option>";
          }
          ?>
        </select>
      </td>
      <td width="10%">
        <input type="text" id="tahun_kendaraan" onkeypress="return number_only(event)" placeholder="Tahun Kendaraan" class="form-control isi">
      </td>
      <td width="10%">
        <select class="form-control select2 isi_combo" id="id_warna">
          <option>- choose -</option>
          <?php 
          $warna = $this->m_admin->getSortCond("ms_warna","id_warna","ASC");
          foreach ($warna->result() as $isi) {
            echo "<option value='$isi->id_warna'>$isi->id_warna | $isi->warna</option>";
          }
          ?>
        </select>
      </td>
      <td width="10%">
        <select class="form-control select2 isi_combo" id="metode_bayar" onchange="check_metodeBayar()">
          <option>- choose -</option>
          <option>Bayar Di Depan (Potong DO)</option>
          <option>Bayar Di Belakang</option>
        </select>
        <div style="padding-top: 4px;" class="input_jenis_bayar_dibelakang hide">
          <select class="form-control select2" id="jenis_bayar_dibelakang">
          <option>- choose -</option>
          <option>Bayar Cash</option>
          <option>Quotation</option>
        </select>
        </div>
      </td>             
      <td width="5%">
        <button type="button" onClick="simpan_tipe()" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>                          
      </td>                        
    </tr>
  </tbody>                        
</table>