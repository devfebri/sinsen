
<table class="table table-bordered responsive-utilities jambo_table bulk_action" id="jasa">
    <thead>
        <tr class="headings">                                                
            <th width="5%">No </th>            
            <th>ID KSU</th>  
            <th>KSU</th>          
            <th width="7%">Aksi </th>                                                                                                                
        </tr>
    </thead>
    <tbody> 
    <?php 
      $no=1; 
      foreach($dt_ksu->result() as $row) {     
        
      echo "          
        <tr>
          <td>$no</td>
          <td>$row->id_ksu</td>          
          <td>$row->ksu</td>          
          <td>"; ?>
            <button title="Hapus Data"
                class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
                onClick="hapus_ksu('<?php echo $row->id_koneksi_ksu_detail; ?>','<?php echo $row->id_koneksi_ksu; ?>')"></button>
          </td>
        </tr>
        <?php
        $no++;
        }
    ?>   
    </tbody>
</table>
