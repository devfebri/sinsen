<table id="myTable" class="table myTable1 order-list" border="0">
  <thead>
    <tr>
      <th width="10%">Part</th>
      <th width="15%">Deskripsi</th>
      <th width="15%">Gejala</th>
      <th width="15%">Penyebab</th>
      <th width="15%">Pengatasan</th>
      <th width="5%">QTY Order</th>
      <th width="10%">Ongkos Kerja</th>
      <th width="15%">Keterangan</th>
      <th width="5%">Aksi</th>
    </tr>
  </thead> 
</table>

<table id="example2" class="table myTable1 table-bordered table-hover">
  <?php   
  foreach($dt_checker->result() as $row) {           
    echo "   
    <tr>                    
      <td width='10%'>$row->id_part</td>
      <td width='15%'>$row->deskripsi</td>
      <td width='15%''>$row->gejala</td>
      <td width='15%'>$row->penyebab</td>
      <td width='15%'>$row->pengatasan</td>
      <td width='5%'>$row->qty_order</td>
      <td width='10%'>$row->ongkos_kerja</td>
      <td width='15%'>$row->ket</td>      
      <td width='5%'>"; ?>
        <button title="Hapus Data"
            class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
            onClick="hapus_checker('<?php echo $row->id_checker_detail; ?>','<?php echo $row->id_checker; ?>')"></button>
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
        <input id="id_part" readonly type="text" data-toggle="modal" data-target="#Partmodal" name="id_part" class="form-control isi_combo" placeholder="ID Part">
      </td> 
      <td width="15%">
        <input type="text" id="deskripsi" placeholder="Deskripsi" class="form-control isi_combo" name="deskripsi">
      </td>
      <td width="15%">
        <select class="form-control select2 isi_combo" id="gejala">
          <option value="">- choose -</option>
          <?php 
          $gej = $this->m_admin->getSortCond("ms_gejala","gejala","ASC");
          foreach ($gej->result() as $isi) {
            echo "<option value='$isi->gejala'>$isi->gejala</option>";
          }
          ?>
        </select>
      </td>
      <td width="15%">
        <select class="form-control select2 isi_combo" id="penyebab">
          <option value="">- choose -</option>
          <?php 
          $peny = $this->m_admin->getSortCond("ms_penyebab","penyebab","ASC");
          foreach ($peny->result() as $isi) {
            echo "<option value='$isi->penyebab'>$isi->penyebab</option>";
          }
          ?>
        </select>
      </td>
      <td width="15%">
        <select class="form-control select2 isi_combo" id="pengatasan">
          <option value="">- choose -</option>
          <?php 
          $peny = $this->m_admin->getSortCond("ms_pengatasan","nama_pengatasan","ASC");
          foreach ($peny->result() as $isi) {
            echo "<option value='$isi->nama_pengatasan'>$isi->nama_pengatasan</option>";
          }
          ?>
        </select>
      </td>
      <td width="5%">
        <input type="text" id="qty_order" placeholder="QTY Order" class="form-control isi_combo" name="qty_order" value=1>      
      </td>
      <td width="10%">
        <input type="text" id="ongkos_kerja" placeholder="Ongkos Kerja" class="form-control isi_combo" name="ongkos_kerja">      
      </td>
      <td width="15%">
        <input type="text" id="ket" class="form-control isi_combo" placeholder="Keterangan" name="ket">
      </td>      
      <td width="5%">
        <button type="button" onClick="simpan_checker()" class="btn btn-sm btn-primary btn-flat btn-xs"> Add</button>                          
      </td>                        
    </tr>
  </tbody>                        
</table>
