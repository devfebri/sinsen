
<table class="table table-bordered responsive-utilities jambo_table bulk_action" id="jasa">
    <thead>
        <tr class="headings">                                                
            <th width="5%">No </th>            
            <th>Tipe Kendaraan </th>
            <th>Januari </th>                                                                                                                                
            <th>Februari </th>                                                                                
            <th>Maret </th>                                                                                
            <th>April</th>
            <th>Mei</th>
            <th>Juni</th>
            <th>Juli</th>
            <th>Agustus</th>
            <th>September</th>
            <th>Oktober</th>
            <th>November</th>
            <th>Desember</th>
            <th width="7%">Aksi </th>                                                                                                                
        </tr>
    </thead>
    <tbody> 
    <?php 
      $no=1; 
      foreach($dt_target->result() as $row) {     
        
      echo "          
        <tr>
          <td>$no</td>
          <td>$row->tipe_ahm</td>
          <td>$row->jan</td>
          <td>$row->feb</td>                
          <td>$row->mar</td>                
          <td>$row->apr</td>                
          <td>$row->mei</td>                
          <td>$row->jun</td>                
          <td>$row->jul</td>                
          <td>$row->agus</td>                
          <td>$row->sept</td>                
          <td>$row->okt</td>                
          <td>$row->nov</td>                
          <td>$row->des</td>                
          <td>"; ?>
            <button title="Hapus Data"
                class="btn btn-sm btn-danger btn-flat fa fa-trash-o" type="button" 
                onClick="hapus_target('<?php echo $row->id_target_sales_detail; ?>','<?php echo $row->id_target_sales; ?>')"></button>
          </td>
        </tr>
        <?php
        $no++;
        }
    ?>   
    </tbody>
</table>
