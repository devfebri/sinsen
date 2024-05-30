
<table class="table table-bordered responsive-utilities jambo_table bulk_action" id="jasa">
    <thead>
        <tr class="headings">                                                
            <th width="5%">No </th>            
            <th>Nama Dealer </th>
            <th>Tgl.Masuk </th>                                                                                                                                
            <th>Tgl.Keluar </th>                                                                                
            <th width="7%">Aksi </th>                                                                                                                
        </tr>
    </thead>
    <tbody> 
    <?php 
      $no=1; 
      foreach($dt_kerja->result() as $row) {     
        
      echo "          
        <tr>
          <td>$no</td>
          <td>$row->nama_dealer</td>
          <td>$row->tgl_masuk</td>
          <td>$row->tgl_keluar</td>                
          <td>"; ?>
            <button title="Hapus Data"
                class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
                onClick="hapus_kerja('<?php echo $row->id_karyawan_dealer_kerja; ?>','<?php echo $row->id_karyawan_dealer; ?>')"></button>
          </td>
        </tr>
        <?php
        $no++;
        }
    ?>   
    </tbody>
</table>
