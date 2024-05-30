

<table class="table table-bordered responsive-utilities jambo_table bulk_action" id="jasa">
    <thead>
        <tr class="headings">                                                            
            <th>Bulan </th>
            <th>Tahun </th>          
            <th width="7%">Aksi </th>                                                                                                                
        </tr>
    </thead>
    <tbody> 
    <?php 
      $no=1; 
      foreach($dt_quot->result() as $row) {     
        
      echo "          
        <tr>          
          <td>$row->bulan</td>
          <td>$row->tahun</td>          
          <td>"; ?>
            <button title="Hapus Data"
                class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
                onClick="hapus_bulan('<?php echo $row->id_quot_bulan; ?>')"></button>
          </td>
        </tr>
        <?php
        $no++;
        }
    ?>   
    </tbody>
</table>
