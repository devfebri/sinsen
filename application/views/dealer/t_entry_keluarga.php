<table id="myTable" class="table myTable1 order-list" border="0">
  <thead>
    <tr>                      
      <th width='10%'>NIK</th>              
      <th width='15%'>Nama</th>              
      <th width='10%'>Tempat Lahir</th>
      <th width='10%'>Tgl Lahir</th>                      
      <th width='10%'>Status Kawin</th>
      <th width='10%'>Posisi Keluarga</th>
      <th width='10%'>Pekerjaan</th>
      <th width='10%'>Pendidikan</th>
      <th width='10%'>No HP</th>
      <th width='5%'>Aksi</th>                      
    </tr>
  </thead>
</table>
                  
<table id="example2" class="table myTable1 table-bordered table-hover">
  <?php   
  foreach($dt_keluarga->result() as $row) {               
    echo "   
    <tr>                    
      <td width='10%'>$row->nik</td>
      <td width='15%'>$row->nama_keluarga</td>      
      <td width='10%'>$row->tempat_lahir</td>
      <td width='10%'>$row->tgl_lahir</td>            
      <td width='10%'>$row->status_kawin</td>            
      <td width='10%'>$row->posisi_keluarga</td>            
      <td width='10%'>$row->pekerjaan</td>            
      <td width='10%'>$row->pendidikan</td>            
      <td width='10%'>$row->no_hp</td>            
      <td width='5%'>"; ?>
        <button title="Hapus Data"
            class="btn btn-sm btn-danger btn-flat" type="button" 
            onClick="hapus_keluarga('<?php echo $row->id_list_appointment; ?>','<?php echo $row->id_permohonan_keluarga; ?>')"><i class="fa fa-trash-o"></i></button>        
      </td>
    </tr>
  <?php    
    }
  ?>  
</table>

<table id="myTable" class="table myt order-list" border="0">     
  <tbody>                    
    <tr>      
      <td width="10%">
        <input type="text" id="nik" placeholder="NIK" class="form-control isi_combo" name="nik">
      </td>      
      <td width="15%">
        <input type="text" id="nama_keluarga" placeholder="Nama" class="form-control isi_combo" name="nama_keluarga">
      </td>      
      <td width="10%">
        <input type="text" id="tempat_lahir" placeholder="Tempat Lahir" class="form-control isi_combo" name="tempat_lahir">
      </td>      
      <td width="10%">
        <input type="text" id="tanggal1" placeholder="yyyy-mm-dd" class="form-control isi_combo" name="tgl_lahir">
      </td>      
      <td width="10%">
        <select class="form-control isi_combo" id="status_kawin" name="status_kawin">
          <option value="belum kawin">Belum Kawin</option>
          <option value="kawin">Kawin</option>
          <option value="janda/duda">Janda/Duda</option>
        </select>
      </td>
      <td width="10%">
        <select class="form-control isi_combo" id="posisi_keluarga" name="posisi_keluarga">
          <option value="ayah">Ayah</option>
          <option value="ibu">Ibu</option>
          <option value="anak">Anak</option>
        </select>
      </td>
      <td width="10%">
        <select class="form-control isi_combo" id="pekerjaan" name="pekerjaan">
          <option value="">- choose -</option>
          <?php 
          foreach ($dt_pekerjaan->result() as $isi) {
            echo "<option value='$isi->id_pekerjaan'>$isi->pekerjaan</option>";
          }
          ?>
          
        </select>
      </td>
      <td width="10%">
        <select class="form-control isi_combo" id="pendidikan" name="pendidikan">
          <option value="">- choose -</option>
          <?php 
          foreach ($dt_pendidikan->result() as $isi) {
            echo "<option value='$isi->id_pendidikan'>$isi->pendidikan</option>";
          }
          ?>
          
        </select>
      </td>      
      <td width="10%">
        <input type="text" id="no_hp" placeholder="No HP" class="form-control isi_combo" name="no_hp">
      </td>      
      <td width="5%">
        <button onclick="simpan_keluarga()" type="button" class="btn btn-xs btn-flat btn-primary">Add</button>                              
      </td>                        
    </tr>                       
  </tbody>
</table>