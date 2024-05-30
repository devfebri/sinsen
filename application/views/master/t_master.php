
<table class="table table-bordered responsive-utilities jambo_table bulk_action" id="jasa">
    <thead>
        <tr class="headings">                                                
            <th width="5%">No </th>            
            <th>Dealer </th>
            <th>Lead Time AHM ke MD</th>                                                                                                                                
            <th>Proses Receiving MD</th>
            <th>Lead Time MD ke Dealer</th>                                                                                                                                
            <th>Proses Receiving </th>                                                                                
            <th>Total Lead Time </th>                                                                                
            <th width="7%">Status</th>
            <th width="7%">Aksi </th>                                                                                                                
        </tr>
    </thead>
    <tbody> 
    <?php 
      $no=1; 
      foreach($dt_master->result() as $row) {     
        
      echo "          
        <tr>
          <td>$no</td>
          <td>$row->nama_dealer</td>
          <td>$row->lead_time_ahm_md hari</td>
          <td>$row->proses_receiving_md hari</td>
          <td>$row->lead_time_md_d hari</td>
          <td>$row->proses_receiving hari</td>                
          <td>$row->total_lead_time hari</td>                
          <td>$row->status</td>                
          <td>"; ?>
            <button title="Hapus Data"
                class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
                onClick="hapus_master('<?php echo $row->id_master_lead_detail; ?>','<?php echo $row->id_master_lead; ?>')"></button>
          </td>
        </tr>
        <?php
        $no++;
        }
    ?>   
    </tbody>
</table>
