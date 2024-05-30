<table id="myTable" class="table myTable1 order-list table-bordered" border="0">
  <thead>
    <tr>
      <th width="15%">Dealer yang Ikut Program</th>
      <th width="10%">Kuota</th>      
      <th width="5%">Action</th>                      
    </tr>
  </thead> 
  <tbody>
    <?php   
  foreach($dt_dealer->result() as $row) {           
    echo "   
    <tr>                    
      <td width='15%'>$row->kode_dealer_md | $row->nama_dealer</td>
      <td width='10%'>$row->kuota</td>      
      <td width='5%'>"; ?>
        <button title="Hapus Data"
            class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
            onClick="hapus_dealer('<?php echo $row->id_sales_program_dealer; ?>','<?php echo $row->id_dealer; ?>')"></button>
      </td>
    </tr>
  <?php    
    }
  ?>  
  </tbody>
  <tfoot>                      
    <tr>
      <td>
        <select class="form-control select2 isi_combo" id="id_dealer">          
          <option value="semua">Semua Dealer</option>
          <?php 
          // $dealer = $this->m_admin->getSortCond("ms_dealer","id_dealer","ASC");
          $dealer = $this->db->query("select id_dealer, kode_dealer_md, nama_dealer from ms_dealer where active =1 and h1 =1 order by nama_dealer asc");
          foreach ($dealer->result() as $isi) {
            echo "<option value='$isi->id_dealer'>$isi->kode_dealer_md | $isi->nama_dealer</option>";
          }
          ?>
        </select>
      </td>
      <td>
        <input type="text" id="kuota" placeholder="Kuota" class="form-control isi">
      </td>      
      <td>
        <button type="button" onClick="simpan_dealer()" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>                          
      </td>                        
    </tr>
  </tfoot>    
</table>