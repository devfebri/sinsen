
<table class="table table-bordered responsive-utilities jambo_table bulk_action" id="jasa">
    <thead>
        <tr class="headings">                                                
            <th width="5%">No </th>            
            <th>Bank</th>
            <th>Jenis Rek</th>
            <th>No Rek</th>
            <th>Nama Rek</th>
            <th width="7%">Aksi </th>                                                                                                                
        </tr>
    </thead>
    <tbody> 
    <?php 
      $no=1; 
      foreach($dt_norek->result() as $row) {     
        
      echo "          
        <tr>
          <td>$no</td>
          <td>$row->bank</td>
          <td>$row->jenis_rek</td>          
          <td>$row->no_rek</td>          
          <td>$row->nama_rek</td>                    
          <td>"; ?>
            <button title="Hapus Data"
                class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
                onClick="hapus_norek('<?php echo $row->id_norek_dealer_detail; ?>','<?php echo $row->id_norek_dealer; ?>')"></button>
          </td>
        </tr>
        <?php
        $no++;
        }
    ?>   
    </tbody>
</table>
