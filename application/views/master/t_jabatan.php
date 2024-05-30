
<table class="table table-bordered responsive-utilities jambo_table bulk_action" id="jasa">
    <thead>
        <tr class="headings">                                                
            <th width="5%">No </th>            
            <th>Dealer </th>
            <th>Jabatan </th>
            <th>Tgl.Aktif </th>                                                                                                                                
            <th>Tgl.Nonaktif </th>                                                                                
            <th width="7%">Status</th>
            <th width="7%">Aksi </th>                                                                                                                
        </tr>
    </thead>
    <tbody> 
    <?php 
      $no=1; 
      foreach($dt_jabatan->result() as $row) {     
        
      echo "          
        <tr>
          <td>$no</td>
          <td>$row->nama_dealer</td>
          <td>$row->jabatan</td>
          <td>$row->tgl_aktif</td>
          <td>$row->tgl_nonaktif</td>                
          <td>$row->status</td>                
          <td>"; ?>
            <button title="Hapus Data"
                class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
                onClick="hapus_jabatan('<?php echo $row->id_karyawan_detail; ?>','<?php echo $row->id_karyawan; ?>')"></button>
          </td>
        </tr>
        <?php
        $no++;
        }
    ?>   
    </tbody>
</table>
