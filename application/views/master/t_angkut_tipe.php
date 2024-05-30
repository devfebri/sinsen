
<table class="table table-bordered responsive-utilities jambo_table bulk_action" id="jasa">
    <thead>
        <tr class="headings">                                                
            <th width="5%">No </th>            
            <th>ID Tipe Kendaraan</th>
            <th>Tipe AHM</th>                                                                                                                                            
            <th width="7%">Aksi </th>                                                                                                                
        </tr>
    </thead>
    <tbody> 
    <?php 
      $no=1; 
      foreach($dt_tipe->result() as $row) {           
      echo "          
        <tr>
          <td>$no</td>
          <td>$row->id_tipe_kendaraan</td>
          <td>$row->tipe_ahm</td>
          <td>"; ?>
            <button title="Hapus Data"
                class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
                onClick="hapus('<?php echo $row->id_group_angkut_detail; ?>')"></button>
          </td>
        </tr>
        <?php
        $no++;
        }
    ?>   
    </tbody>
</table>
