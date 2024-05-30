

<table class="table table-bordered responsive-utilities jambo_table bulk_action" id="jasa">
    <thead>
        <tr class="headings">                                                            
            <th>ID Tipe Kendaraan </th>
            <th>Nilai </th>          
            <th width="7%">Aksi </th>                                                                                                                
        </tr>
    </thead>
    <tbody> 
    <?php 
      $no=1; 
      foreach($dt_quot2->result() as $row) {     
        
      echo "          
        <tr>          
          <td>$row->id_tipe_kendaraan</td>
          <td>".number_format($row->nilai, 0, ',', '.')."</td>          
          <td>"; ?>
            <button title="Hapus Data"
                class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
                onClick="hapus_tipe('<?php echo $row->id_quot_tipe; ?>')"></button>
          </td>
        </tr>
        <?php
        $no++;
        }
    ?>   
    </tbody>
</table>
