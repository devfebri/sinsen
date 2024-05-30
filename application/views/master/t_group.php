
<table class="table table-bordered responsive-utilities jambo_table bulk_action" id="jasa">
    <thead>
        <tr class="headings">                                                
            <th width="5%">No </th>            
            <th>Kode Dealer </th>
            <th>Dealer </th>
            <th width="15%">Head Office </th>                                                                                                                                            
            <th width="7%">Aksi </th>                                                                                                                
        </tr>
    </thead>
    <tbody> 
    <?php 
      $no=1; 
      foreach($dt_group->result() as $row) {     
        
      echo "          
        <tr>
          <td>$no</td>
          <td>$row->kode_dealer_md</td>
          <td>$row->nama_dealer</td>
          <td>$row->head_office</td>          
          <td>"; ?>
            <button title="Hapus Data"
                class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
                onClick="hapus_group('<?php echo $row->id_group_dealer_detail; ?>','<?php echo $row->id_dealer; ?>')"></button>
          </td>
        </tr>
        <?php
        $no++;
        }
    ?>   
    </tbody>
</table>
