
<table class="table table-bordered responsive-utilities jambo_table bulk_action" id="jasa">
    <thead>
        <tr class="headings">                                                
            <th width="5%">No </th>            
            <th>Training </th>
            <th>Tgl.Mulai </th>                                                                                                                                
            <th>Tgl.Selesai </th>                                                                                
            <th width="7%">Aksi </th>                                                                                                                
        </tr>
    </thead>
    <tbody> 
    <?php 
      $no=1; 
      foreach($dt_training->result() as $row) {     
        
      echo "          
        <tr>
          <td>$no</td>
          <td>$row->training</td>
          <td>$row->tgl_mulai</td>
          <td>$row->tgl_selesai</td>                
          <td>"; ?>
            <button title="Hapus Data"
                class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
                onClick="hapus_training('<?php echo $row->id_karyawan_dealer_training; ?>','<?php echo $row->id_karyawan_dealer; ?>')"></button>
          </td>
        </tr>
        <?php
        $no++;
        }
    ?>   
    </tbody>
</table>
